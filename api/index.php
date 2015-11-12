<?php
include('../bootstrap.php');
ini_set('include_path',BASE_PATH."vendor/epiphany/src/:".ini_get('include_path'));

class api
{
	/**
	 * Give an index to the API's endpoints.
	 * @return array List of endpoint names and addresses.
	 */
	public static function index()
	{
		return array(
			'mission/validate' => 'mission/validate/',
			'mission/report' => 'mission/report/',
		);
	}
}

class mission
{
	protected static $is_valid = true;
	protected static $errors = array();
	const MAX_ERROR_LENGTH = 255;

	/**
	 * Takes input from GET and returns whether the parameters represent a valid SquadWar match setup
	 * for the given match code.  Input includes the mission and two arrays of player IDs, one for each team.
	 * These are in squad_plr1 and squad_plr2.
	 * @param  int $code The match code to be validated against
	 * @return array     The match code, is_valid, and error info (if any) to be encoded to JSON and returned by the API.
	 */
	public static function validate($code)
	{
		$rec = match_api::new_search_record();
		$rec->set_SWCode($code);
		$ret = match_api::search($rec);
		$ret = match_api::populate_info($ret);
		$ret = match_api::populate_squads($ret, true);
		$match = reset($ret->get_results());

		if(! $match instanceof match_record_detail)
		{
			self::$is_valid = false;
			self::$errors[] = "Could not find match code {$code}";
		}

		if($_GET['mission'] != $match->get_info()->get_Mission())
		{
			self::$is_valid = false;
			self::add_error("Match requires " . $match->get_info()->get_Mission() .
				", but mission is set to {$_GET['mission']}");
		}

		$squad_ids = array('squad_plr1'=>null, 'squad_plr2'=>null);
		$squads = self::identify_squads(array_intersect_key($_GET, $squad_ids), $match);

		if(!is_null(reset($squads)))
		{
			// Loop above was to find what squad was which, this loop specifically ensure proper team
			// membership for all players involved.
			foreach($squads as $squadid => $team)
			{
				foreach($_GET[$squadid] as $playerid => $name)
				{
					$func = 'get_'.ucfirst($team);
					if(!in_array($playerid, $match->$func()->get_SquadMembers()))
					{
						self::$is_valid = false;
						self::add_error("Player {$name} ({$playerid}) is not in {$team} squad " .
							$match->$func()->get_SquadName());
					}
				}
			}
		}

		return array(
			'code'=>$code,
			'is_valid'=>self::$is_valid,
			'squad1'=>$squads['squad_plr1'],
			'squad2'=>$squads['squad_plr2'],
			'errors'=>self::$errors,
			'error_count'=>count(self::$errors)
		);
	}

	/**
	 * Used to report the outcome of a completed SquadWar mission to FS2NetD/SquadWar.  This can only be done once
	 * for a mission, after which it cannot be played again without hackery.  Takes input from GET parameters
	 * including a list of players on the winning and losing teams.
	 * @param  int $code The match code that was played and is being reported
	 * @return array     The match code,
	 */
	public static function report($code)
	{
		$rec = match_api::new_search_record();
		$rec->set_SWCode($code);
		$ret = match_api::search($rec);
		$ret = match_api::populate_info($ret);
		$ret = match_api::populate_squads($ret, true);
		$match = reset($ret->get_results());

		$squad_ids = array('squad_winners'=>null,'squad_losers'=>null);
		$squads = self::identify_squads(array_intersect_key($_GET, $squad_ids), $match);

		if(!is_null(reset($squads)))
		{
			$func = 'get_'.ucfirst($squads['squad_winners']);
			$result = true;
/*			$result = match_api::award_match(
				$code,
				$match->$func()->get_SquadID()
			);*/

			if(!$result)
			{
				self::$is_valid = false;
				self::add_error("Could not find the match to award");
			}
		}

		return array(
			'code'=>$code,
			'is_valid'=>self::$is_valid,
			'winner'=>$squads['squad_winners'],
			'errors'=>self::$errors,
			'error_count'=>count(self::$errors)
		);
	}

	protected static function identify_squads($plists, $match)
	{
		// We don't know if the hosting team is the challenger or challenged.  We can proceed through
		// the two lists of players and use the first player not in both squads to determine which team
		// is which.  If all players are in both squads, we simply don't have enough data to identify
		// one squad from the other.
		$squad_ids = array_keys($plists);
		$squads = array_fill_keys($squad_ids, null); // default value for later if no matches found
		$functions = array('challenger','challenged');
		$functions = array_combine($squad_ids, array($functions, array_reverse($functions)));
		foreach($plists as $squad_id => $plist)
		{
			foreach($plist as $player => $name)
			{
				if(in_array($player, $match->get_Challenger()->get_SquadMembers()) &&
					in_array($player, $match->get_Challenged()->get_SquadMembers()))
				{
					continue;
				}
				elseif(in_array($player, $match->get_Challenger()->get_SquadMembers()))
				{
					$squads = array($squad_ids[0]=>$functions[$squad_id][0], $squad_ids[1]=>$functions[$squad_id][1]);
					break;
				}
				elseif(in_array($player, $match->get_Challenged()->get_SquadMembers()))
				{
					$squads = array($squad_ids[0]=>$functions[$squad_id][1], $squad_ids[1]=>$functions[$squad_id][0]);
					break;
				}
				else
				{
					self::$is_valid = false;
					self::add_error("Could not find player {$name} ({$player}) in any squad");
				}
			}

			if(!is_null(reset($squads)))
			{
				break;
			}
		}

		if(is_null(reset($squads)))
		{
			self::$is_valid = false;
			self::add_error("All players are in both squads or neither squad, cannot tell one squad from another");
		}

		return $squads;
	}

	protected static function add_error($error)
	{
		self::$errors[] = substr($error, 0, self::MAX_ERROR_LENGTH);
	}
}

Epi::setSetting('exceptions', true);
Epi::init('api');

getApi()->get('/', array('api', 'index'), EpiApi::external);
getApi()->get('/mission/validate/(\d+)', array('mission', 'validate'), EpiApi::external);
getApi()->get('/mission/report/(\d+)', array('mission', 'report'), EpiApi::external);
getRoute()->run();

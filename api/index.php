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
			'match/validate' => 'match/validate/',
			'match/report' => 'match/report/',
		);
	}

	public static function badrequest()
	{
		return array(
			'is_valid' => false,
			'errors' => array('Unrecognized request.'),
		);
	}
}

class match
{
	protected static $is_valid = true;
	protected static $errors = array();
	const MAX_ERROR_LENGTH = 255;

	/**
	 * Lists all pending matches.
	 * @return array 	The list of pending matches
	 */
	public static function pending()
	{
		$rec = match_api::new_search_record();
		$ret = match_api::search($rec);
		$ret = match_api::populate_info($ret);
		$ret = match_api::populate_sectors($ret);
		$ret = match_api::populate_squads($ret, true);
		$matches = $ret->get_results();

		foreach($matches as $index => $match)
		{
			$matches[$index] = $match->to_array_deep();
		}

		return array('matches'=>$matches);
	}

	/**
	 * Takes input from GET and returns whether the parameters represent a valid SquadWar match setup
	 * for the given match code.  Input includes the mission and two arrays of player IDs, one for each team.
	 * These are in squad_plr1 and squad_plr2.
	 * @param  int $code The match code to be validated against
	 * @return array     The match code, is_valid, and error info (if any) to be encoded to JSON and returned by the API.
	 */
	public static function validate($code)
	{
		$squads = array('squad_plr1'=>null,'squad_plr2'=>null);

		if($match = self::validate_match_code($code))
		{
			// Need to validate the mission, as well as the list of participants
			self::validate_mission($match);

			$squads = self::validate_squads($squads, $match);

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
								$match->$func()->get_SquadName() . ".");
						}
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
	 * Used to report the outcome of a completed SquadWar match to FS2NetD/SquadWar.  This can only be done once
	 * for a match, after which it cannot be played again without hackery.  Takes input from GET parameters
	 * including a list of players on the winning and losing teams.
	 * @param  int $code The match code that was played and is being reported
	 * @return array     The match code,
	 */
	public static function report($code)
	{
		$squads = array('squad_winners'=>null,'squad_losers'=>null);

		if($match = self::validate_match_code($code))
		{
			// Just need to validate the list of winners and losers now.
			$squads = self::validate_squads($squads, $match);

			if(!is_null(reset($squads)))
			{
				$func = 'get_'.ucfirst($squads['squad_winners']);
				$result = true;
//				$result = match_api::award_match($code, $match->$func()->get_SquadID());

				if(!$result)
				{
					self::$is_valid = false;
					self::add_error("Could not find the match to award.");
				}
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

	/**
	 * Validates that a mission is submitted and is the correct mission for the passed match.
	 * @param  match_record_detail $match The match detail object that contains a mission
	 * @return null
	 */
	protected static function validate_mission($match)
	{
		if(!array_key_exists('mission', $_GET))
		{
			self::$is_valid = false;
			self::add_error("No mission defined.");
		}
		elseif($_GET['mission'] != $match->get_info()->get_Mission())
		{
			self::$is_valid = false;
			self::add_error("Match requires " . $match->get_info()->get_Mission() .
				", but mission is set to {$_GET['mission']}.");
		}
	}

	/**
	 * Validates that the submitted match code exists and is in a state to be played.
	 * @param  string                    $code The match code to look up
	 * @return false|match_record_detail       The match object, or false if not found.
	 */
	protected static function validate_match_code($code)
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
			self::$errors[] = "Could not find match code {$code}.";
			return false;
		}

		if($match->get_info()->get_final_match_time() == '0000-00-00 00:00:00')
		{
			self::$is_valid = false;
			self::$errors[] = "Match settings have not been finalized.";
			return $match;
		}

		return $match;
	}

	/**
	 * Validates that the submitted players in $_GET are part of the squads playing in this match,
	 * and separates the players into the appropriate arrays after validating them.
	 * @param  array               $squads Placeholder array containing the keys to separate players by.
	 * @param  match_record_detail $match  The match object to validate against
	 * @return array                       The squads array, either empty or populated properly.
	 */
	protected static function validate_squads($squads, $match)
	{
		// First make sure we have all the data in the $_GET field
		$plists = array_intersect_key($_GET, $squads);
		if(count($plists) != count($squads))
		{
			self::$is_valid = false;
			self::add_error("Not enough squads submitted in request.");
			return $squads;
		}
		// We don't know if the hosting team is the challenger or challenged.  We can proceed through
		// the two lists of players and use the first player not in both squads to determine which team
		// is which.  If all players are in both squads, we simply don't have enough data to identify
		// one squad from the other.
		$squad_ids = array_keys($plists);
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
					self::add_error("Could not find player {$name} ({$player}) in any squad.");
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
			self::add_error("All players are in both squads or neither squad, cannot tell one squad from another.");
		}

		return $squads;
	}

	/**
	 * Appends errors to the error array, and makes any necessary tweaks to them.
	 * Eliminates needing to substr every single error message line and provides easier future filtering.
	 * @param string $error The error string to be added.
	 */
	protected static function add_error($error)
	{
		self::$errors[] = substr($error, 0, self::MAX_ERROR_LENGTH);
	}
}

Epi::setSetting('exceptions', true);
Epi::init('api');

getApi()->get('/', array('api', 'index'), EpiApi::external);
getApi()->get('/match/pending/?', array('match', 'pending'), EpiApi::external);
getApi()->get('/match/validate/(\d+)/?', array('match', 'validate'), EpiApi::external);
getApi()->get('/match/report/(\d+)/?', array('match', 'report'), EpiApi::external);
getApi()->get('.*', array('api', 'badrequest'), EpiApi::external);
getRoute()->run();

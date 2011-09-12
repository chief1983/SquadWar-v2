<?php
class squad_api
{

	/**
		@param $id int
		get a squad by id
		@return squad_record_detail
	**/
	public static function get($id)
	{
		$model = new squad_data_main();
		return $model->get($id);
	}

	/**
		@param $record base_record
		search for any kind of squad thingie.
		@return base_result or boolean
	**/
	public static function search(base_recordsearch $record)
	{
		$model = '';
		if($record instanceof squad_record_search)
		{
			$model = new squad_data_main();
		}
		elseif($record instanceof squad_record_info_search)
		{
			$model = new squad_data_info();
		}
		elseif($record instanceof squad_record_league_search)
		{
			$model = new squad_data_league();
		}
		elseif($record instanceof squad_record_matchhistory_search)
		{
			$model = new squad_data_matchhistory();
		}
		elseif($record instanceof squad_record_sector_search)
		{
			$model = new squad_data_sector();
		}
		elseif($record instanceof squad_record_sectorgraph_search)
		{
			$model = new squad_data_sectorgraph();
		}

		if($model == '')
		{
			return false;
		}
		else
		{
			return $model->search($record);
		}
	}

	/**
		@param $record base_recordsearch
		get count of results matching a search record.
		@return number of results
	 */
	public static function get_count(base_recordsearch $record)
	{
		$model = '';
		if($record instanceof squad_record_search)
		{
			$model = new squad_data_main();
		}

		if($model == '')
		{
			return false;
		}
		else
		{
			return $model->get_count($record);
		}
	}

	/**
		shortcut method.
		@return squad_record_search
	**/
	public static function get_squad_by_name($name)
	{
		$rec = self::new_search_record();
		$rec->set_SquadName($name);
		$ret = self::search($rec);
		return $ret->get_first_result();
	}

	/**
		shortcut method.
		@return squad_record_search
	**/
	public static function get_active_squads()
	{
		$rec = self::new_search_record();
		$rec->set_Active(1);
		$rec->set_sort_by('SquadName');
		$ret = self::search($rec);
		return $ret->get_results();
	}

	/**
		@param $ret base_return
		Populates squads with associated squad_league record
		@return base_return
	 */
	public static function populate_league(base_return $ret)
	{
		$ids = $ret->get_ids();
		$rec = self::new_league_search_record();
		$rec->set_SWSquad_SquadID($ids);
		$model = new squad_data_league();
		$returnobj = $model->search($rec);

		return self::populate_records_with_related_recs(
			$ret, $returnobj->get_results(), 'set_league', 'get_SWSquad_SquadID'
		);
	}

	/**
		@param $ret base_return
		Populates squads with associated squad_info record
		@return base_return
	 */
	public static function populate_info(base_return $ret)
	{
		$ids = $ret->get_ids();
		$rec = self::new_info_search_record();
		$rec->set_id($ids);
		$model = new squad_data_info();
		$returnobj = $model->search($rec);

		return self::populate_records_with_related_recs(
			$ret, $returnobj->get_results(), 'set_info', 'get_id', false
		);
	}

	/**
		@param $ret base_return
		Populates squads with associated match_history record
		@return base_return
	 */
	public static function populate_match_history(base_return $ret)
	{
		$ids = $ret->get_ids();
		$model = new squad_data_matchhistory();
		$rec = self::new_matchhistory_search_record();
		$rec->set_match_victor($ids);
		$returnobj = $model->search($rec);

		$victors = self::populate_records_with_related_recs(
			$ret, $returnobj->get_results(), 'set_matches_won', 'get_match_victor'
		);

		$rec = self::new_matchhistory_search_record();
		$rec->set_match_loser($ids);
		$returnobj = $model->search($rec);

		return self::populate_records_with_related_recs(
			$victors, $returnobj->get_results(), 'set_matches_lost', 'get_match_loser'
		);
	}

	/**
		@param $ret base_return
		Populates squads with associated match_history record
		@return base_return
	 */
	public static function populate_league_match_history(base_return $ret, $league)
	{
		$ids = $ret->get_ids();
		$model = new squad_data_matchhistory();
		$rec = self::new_matchhistory_search_record();
		$rec->set_League_ID($league);
		$rec->set_match_victor($ids);
		$returnobj = $model->search($rec);

		$victors = self::populate_records_with_related_recs(
			$ret, $returnobj->get_results(), 'set_l_matches_won', 'get_match_victor'
		);

		$rec = self::new_matchhistory_search_record();
		$rec->set_League_ID($league);
		$rec->set_match_loser($ids);
		$returnobj = $model->search($rec);

		return self::populate_records_with_related_recs(
			$victors, $returnobj->get_results(), 'set_l_matches_lost', 'get_match_loser'
		);
	}

	/**
		@param $ret base_return
		Populates squads with associated sector record
		@return base_return
	 */
	public static function populate_sectors(base_return $ret)
	{
		$ids = $ret->get_ids();
		$model = new squad_data_sector();
		$rec = self::new_sector_search_record();
		$rec->set_SectorSquad($ids);
		$returnobj = $model->search($rec);

		return self::populate_records_with_related_recs(
			$ret, $returnobj->get_results(), 'set_sectors', 'get_SectorSquad'
		);
	}

	/**
		@param $ret base_return
		Populates squads with associated sector record
		@return base_return
	 */
	public static function populate_league_sectors(base_return $ret, $league)
	{
		$ids = $ret->get_ids();
		$model = new squad_data_sector();
		$rec = self::new_sector_search_record();
		$rec->set_SectorSquad($ids);
		$rec->set_League_ID($league);
		$returnobj = $model->search($rec);

		return self::populate_records_with_related_recs(
			$ret, $returnobj->get_results(), 'set_l_sectors', 'get_SectorSquad'
		);
	}

	/**
		@param $ret base_return
		Populates squads with associated swmatches records
		@return base_return
	 */
	public static function populate_matches(base_return $ret)
	{
		$ids = $ret->get_ids();
		$rec = match_api::new_search_record();
		$rec->set_SWSquad1($ids);
		$model = new match_data_main();
		$returnobj = $model->search($rec);

		$challengers = self::populate_records_with_related_recs(
			$ret, $returnobj->get_results(), 'set_matches_challenger', 'get_SWSquad1'
		);

		$rec = match_api::new_search_record();
		$rec->set_SWSquad2($ids);
		$returnobj = $model->search($rec);

		return self::populate_records_with_related_recs(
			$ret, $returnobj->get_results(), 'set_matches_defender', 'get_SWSquad2'
		);
	}

	/**
		@param $ret base_return
		Populates squads with associated swmatches records
		@return base_return
	 */
	public static function populate_league_matches(base_return $ret, $league)
	{
		$ids = $ret->get_ids();
		$rec = match_api::new_search_record();
		$rec->set_League_ID($league);
		$rec->set_SWSquad1($ids);
		$model = new match_data_main();
		$returnobj = $model->search($rec);

		$challengers = self::populate_records_with_related_recs(
			$ret, $returnobj->get_results(), 'set_l_matches_challenger', 'get_SWSquad1'
		);

		$rec = match_api::new_search_record();
		$rec->set_League_ID($league);
		$rec->set_SWSquad2($ids);
		$returnobj = $model->search($rec);

		return self::populate_records_with_related_recs(
			$ret, $returnobj->get_results(), 'set_l_matches_defender', 'get_SWSquad2'
		);
	}

	protected static function populate_records_with_related_recs(
		$dest_returnobj, $child_item_array, $method_to_add_to_obj, $id_check = 'get_id', $multiple = true, $parent_id_check = 'get_id'
	)
	{
		$dest_array = $dest_returnobj->get_results();
		//step over each node and find its children.
		foreach($dest_array as $key => $node)
		{
			$id_we_want = $dest_array[$key]->$parent_id_check();
			$items_for_node = array();

			foreach($child_item_array as $res)
			{
				if($res->$id_check() == $id_we_want)
				{
					$items_for_node[] = $res;
				}
			}

			if(!$multiple)
			{
				$items_for_node = reset($items_for_node);
			}

			$dest_array[$key]->$method_to_add_to_obj($items_for_node);
		}

		//put the NEW result array into the return obj and give it back.
		$dest_returnobj->set_result($dest_array);
		return $dest_returnobj;
	}

	/**
		@param $ret base_return
		Populates matchhistories with associated squad records for winners and losers
		@return base_return
	 */
	public static function populate_matchhistory_squads(base_return $ret)
	{
		$ids = array_unique(array_merge($ret->get_ids('get_match_victor'), $ret->get_ids('get_match_loser')));
		$model = new squad_data_main();
		$rec = self::new_search_record();
		$rec->set_SquadID($ids);
		$returnobj = $model->search($rec);

		$ret = self::populate_records_with_related_recs(
			$ret, $returnobj->get_results(), 'set_Squad1', 'get_SquadID', false, 'get_match_victor'
		);

		return self::populate_records_with_related_recs(
			$ret, $returnobj->get_results(), 'set_Squad2', 'get_SquadID', false, 'get_match_loser'
		);
	}

	/**
		@param $ret base_return
		Populates matchhistories with associated sectors
		@return base_return
	 */
	public static function populate_matchhistory_sectors(base_return $ret)
	{
		$ids = $ret->get_ids('get_SWSector_ID');
		$model = new squad_data_sector();
		$rec = self::new_sector_search_record();
		$rec->set_SWSectors_ID($ids);
		$returnobj = $model->search($rec);

		return self::populate_records_with_related_recs(
			$ret, $returnobj->get_results(), 'set_SWSector', 'get_SWSectors_ID', false, 'get_SWSector_ID'
		);
	}

	/**
		@param $ret base_return
		Populates matchhistories with associated match_info
		@return base_return
	 */
	public static function populate_matchhistory_info(base_return $ret)
	{
		$ids = $ret->get_ids('get_MatchID');
		$model = new match_data_info();
		$rec = match_api::new_info_search_record();
		$rec->set_MatchID($ids);
		$returnobj = $model->search($rec);

		return self::populate_records_with_related_recs(
			$ret, $returnobj->get_results(), 'set_info', 'get_MatchID', false, 'get_MatchID'
		);
	}

	/**
		@param $ret base_return
		Populates sectors with associated sectorgraph
		@return base_return
	 */
	public static function populate_sector_sectorgraph(base_return $ret)
	{
		$ids = $ret->get_ids('get_SWSectors_ID');
		$model = new squad_data_sectorgraph();
		$rec = self::new_sectorgraph_search_record();
		$rec->set_SWSectors_ID($ids);
		$returnobj = $model->search($rec);

		return self::populate_records_with_related_recs(
			$ret, $returnobj->get_results(), 'set_sectorgraph', 'get_SWSectors_ID', false, 'get_SWSectors_ID'
		);
	}

	/**
		@param $ret base_return
		Populates sectors with associated sectorgraph
		@return base_return
	 */
	public static function populate_sector_squad(base_return $ret, $get_info = false)
	{
		$ids = $ret->get_ids('get_SectorSquad');
		$model = new squad_data_main();
		$rec = self::new_search_record();
		$rec->set_SquadID($ids);
		$returnobj = $model->search($rec);
		if($get_info)
		{
			$returnobj = squad_api::populate_info($returnobj);
		}

		return self::populate_records_with_related_recs(
			$ret, $returnobj->get_results(), 'set_squad', 'get_SquadID', false, 'get_SectorSquad'
		);
	}

	/**
		@param $ret base_return
		Populates sectors with associated sectorgraph
		@return base_return
	 */
	public static function populate_sectorgraph_pathsectors(base_return $ret)
	{
		$ids = array();
		for($i = 1; $i <= MAX_SECTOR_PATHS; $i++)
		{
			$ids = array_merge($ids, $ret->get_ids('get_path_'.$i));
		}
		$model = new squad_data_sector();
		$rec = self::new_sector_search_record();
		$rec->set_SWSectors_ID($ids);
		$returnobj = $model->search($rec);

		for($i = 1; $i <= MAX_SECTOR_PATHS; $i++)
		{
			$ret = self::populate_records_with_related_recs(
				$ret, $returnobj->get_results(), 'set_sector_'.$i, 'get_SWSectors_ID', false, 'get_path_'.$i
			);
		}

		return $ret;
	}

	/**
		@param $ret base_return
		Populates sectors with associated sectorgraph
		@return base_return
	 */
	public static function populate_sector_pending_matches(base_return $ret)
	{
		$ids = $ret->get_ids();
		$rec = match_api::new_search_record();
		$rec->set_SWSector_ID($ids);
		$returnobj = match_api::search($rec);

		return self::populate_records_with_related_recs(
			$ret, $returnobj->get_results(), 'set_pending_matches', 'get_SWSector_ID', true, 'get_SWSectors_ID'
		);
	}

	/**
		This allows you to change squad's password.  Can also be used
		along with squad_api::generate_password() to set to a random password

		This is not in the squad record because it is not something we want
		to use as part of a normal update.

        return 2 if old password doesn't match
        return 1 if reset worked
        return 0 if reset failed
	**/
	public static function change_password(squad_record_detail $squad, $new_pw)
	{
		$model = new squad_data_main();
		return $model->change_password($squad, $new_pw);
	}


	//return an empty instance of the detail record
	/** @return squad_record_detail **/
	public static function new_detail_record()
	{
		return new squad_record_detail();
	}

	//return an empty instance of the search record
	/** @return squad_record_search **/
	public static function new_search_record()
	{
		return new squad_record_search();
	}

	//return an empty instance of the league detail record
	public static function new_league_detail_record()
	{
		return new squad_record_league_detail();
	}

	//return an empty instance of the league search record
	public static function new_league_search_record()
	{
		return new squad_record_league_search();
	}

	//return an empty instance of the info detail record
	public static function new_info_detail_record()
	{
		return new squad_record_info_detail();
	}

	//return an empty instance of the info search record
	public static function new_info_search_record()
	{
		return new squad_record_info_search();
	}

	//return an empty instance of the matchhistory detail record
	public static function new_matchhistory_detail_record()
	{
		return new squad_record_matchhistory_detail();
	}

	//return an empty instance of the matchhistory search record
	public static function new_matchhistory_search_record()
	{
		return new squad_record_matchhistory_search();
	}

	//return an empty instance of the sector detail record
	public static function new_sector_detail_record()
	{
		return new squad_record_sector_detail();
	}

	//return an empty instance of the sector search record
	public static function new_sector_search_record()
	{
		return new squad_record_sector_search();
	}

	//return an empty instance of the sectorgraph detail record
	public static function new_sectorgraph_detail_record()
	{
		return new squad_record_sectorgraph_detail();
	}

	//return an empty instance of the sectorgraph search record
	public static function new_sectorgraph_search_record()
	{
		return new squad_record_sectorgraph_search();
	}

/*******************************************************************************

	These are here just because they are kinda squad related.

*******************************************************************************/

	public static function generate_password()
	{
		$chars = array(
			'a','b','c','d','e','f','g','h','i','j','k','l','m','n',
			'o','p','q','r','s','t','u','v','w','x','y','z','A','B','C','D',
			'E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T',
			'U','V','W','X','Y','Z','1','2','3','4','5','6','7','8','9','0',
			'!','@','#','$','%','^','&','*','(',')','_','-','+','=','{','~',
			'}','[',']','|','/','?','>','<',':',';'
		);

		$new_pw = '';
		for($i=0;$i<20;$i++)
		{
			$new_pw .= $chars[rand(0, count($chars)-1)];
		}

		return $new_pw;
	}
}
?>

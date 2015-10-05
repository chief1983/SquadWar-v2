<?php
class match_api
{

	/**
		@param $id int
		get a match by id
		@return match_record_detail
	**/
	public static function get($id)
	{
		$model = new match_data_main();
		return $model->get($id);
	}

	/**
		@param $code int
		get a match by SWCode
		@return match_record_detail
	**/
	public static function get_by_SWCode($code)
	{
		$model = new match_data_main();
		return $model->get_by_SWCode($code);
	}

	/**
		@param $record base_record
		search for any kind of match thingie.
		@return base_result or boolean
	**/
	public static function search(base_recordsearch $record)
	{
		$model = '';
		if($record instanceof match_record_search)
		{
			$model = new match_data_main();
		}
		elseif($record instanceof match_record_info_search)
		{
			$model = new match_data_info();
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
		if($record instanceof match_record_search)
		{
			$model = new match_data_main();
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
		@return match_record_search
	**/
	public static function get_match_by_SWCode($SWCode)
	{
		$model = new match_data_main();
		return $model->get_by_SWCode($SWCode);
	}

	/**
		@param $code int
		@param $sector int
		@param $first int
		@param $second int
		@param $winner int
		@param $loser int
		@param $league int

		shortcut method.
		@return match_record_search
	**/
	public static function award_match($code, $winner, $special = 0)
	{
		$match = match_api::get_by_SWCode($code);

		if(!$match)
		{
			return false;
		}

		$time = time();
		$loser = ($winner == $match->get_SWSquad1()) ? $match->get_SWSquad2() : $match->get_SWSquad1();

		$rec = squad_api::new_sector_search_record();
		$rec->set_SWSectors_ID($match->get_SWSector_ID());
		$ret = squad_api::search($rec);
		$sector = reset($ret->get_results());
		$sector->set_SectorSquad($winner);
		$sector->set_SectorTime($time);
		$sector->save();

		$rec = squad_api::new_matchhistory_detail_record();
		$rec->set_MatchID($match->get_MatchID());
		$rec->set_SWCode($code);
		$rec->set_SWSquad1($match->get_SWSquad1());
		$rec->set_SWSquad2($match->get_SWSquad2());
		$rec->set_SWSector_ID($match->get_SWSector_ID());
		$rec->set_match_victor($winner);
		$rec->set_match_loser($loser);
		$rec->set_match_time($time);
		$rec->set_League_ID($match->get_League_ID());
		$rec->set_special($special);
		$rec->save();

		$match->delete();

		return true;
	}

	/**
		@param $ret base_return
		Populates matches with associated match_info record
		@return base_return
	 */
	public static function populate_info(base_return $ret)
	{
		$ids = $ret->get_ids();
		$rec = self::new_info_search_record();
		$rec->set_id($ids);
		$model = new match_data_info();
		$returnobj = $model->search($rec);

		return self::populate_records_with_related_recs(
			$ret, $returnobj->get_results(), 'set_info', 'get_id', false
		);
	}

	/**
		@param $ret base_return
		Populates matches with associated sectors
		@return base_return
	 */
	public static function populate_sectors(base_return $ret)
	{
		$ids = $ret->get_ids('get_SWSector_ID');
		$model = new squad_data_sector();
		$rec = squad_api::new_sector_search_record();
		$rec->set_SWSectors_ID($ids);
		$returnobj = $model->search($rec);

		return self::populate_records_with_related_recs(
			$ret, $returnobj->get_results(), 'set_SWSector', 'get_SWSectors_ID', false, 'get_SWSector_ID'
		);
	}

	/**
		@param $ret base_return
		Populates matches with associated leagues
		@return base_return
	 */
	public static function populate_league(base_return $ret)
	{
		$ids = $ret->get_ids();
		$model = new league_data_main();
		$rec = league_api::new_search_record();
		$rec->set_id($ids);
		$returnobj = $model->search($rec);

		return self::populate_records_with_related_recs(
			$ret, $returnobj->get_results(), 'set_league', 'get_id', false, 'get_League_ID'
		);
	}

	/**
		@param $ret base_return
		Populates matchhistories with associated squad records for winners and losers
		@return base_return
	 */
	public static function populate_squads(base_return $ret, $get_info = false)
	{
		$ids = array_unique(array_merge($ret->get_ids('get_SWSquad1'), $ret->get_ids('get_SWSquad2')));
		$model = new squad_data_main();
		$rec = squad_api::new_search_record();
		$rec->set_SquadID($ids);
		$returnobj = $model->search($rec);
		if($get_info)
		{
			$returnobj = squad_api::populate_info($returnobj);
		}

		$ret = self::populate_records_with_related_recs(
			$ret, $returnobj->get_results(), 'set_Challenger', 'get_SquadID', false, 'get_SWSquad1'
		);

		return self::populate_records_with_related_recs(
			$ret, $returnobj->get_results(), 'set_Challenged', 'get_SquadID', false, 'get_SWSquad2'
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


	//return an empty instance of the detail record
	/** @return match_record_detail **/
	public static function new_detail_record()
	{
		return new match_record_detail();
	}

	//return an empty instance of the search record
	/** @return match_record_search **/
	public static function new_search_record()
	{
		return new match_record_search();
	}

	//return an empty instance of the info detail record
	public static function new_info_detail_record()
	{
		return new match_record_info_detail();
	}

	//return an empty instance of the info search record
	public static function new_info_search_record()
	{
		return new match_record_info_search();
	}
}

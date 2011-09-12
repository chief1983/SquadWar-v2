<?php
class fsopilot_api
{

	/**
		@param $id int
		get a pilot by id
		@return fsopilot_record_detail
	**/
	public static function get($id)
	{
		$model = new fsopilot_data_main();
		return $model->get($id);
	}

	/**
		@param $ids int
		get swpilot(s) by id
		@return fsopilot_record_swpilot_detail
	**/
	public static function get_swpilot($ids)
	{
		$rec = self::new_swpilot_search_record();
		$rec->set_id($ids);
		$model = new fsopilot_data_swpilot();
		return $model->search($rec);
	}

	/**
		get all the connection types
		@return array
	**/
	public static function get_form_connection_types()
	{
		$model = new fsopilot_data_swpilot();
		return $model->get_form_connection_types();
	}

	/**
		get all the time zones
		@return array
	**/
	public static function get_form_time_zones()
	{
		$model = new fsopilot_data_swpilot();
		return $model->get_form_time_zones();
	}

	/**
		@param $record base_record
		search for any kind of fsopilot thingie.
		@return base_result or boolean
	**/
	public static function search(base_recordsearch $record)
	{
		$model = '';
		if($record instanceof fsopilot_record_search)
		{
			$model = new fsopilot_data_main();
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
		if($record instanceof fsopilot_record_search)
		{
			$model = new fsopilot_data_main();
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
		@return fsopilot_record_search
	**/
	public static function get_pilots_by_TrackerID($TrackerID)
	{
		$rec = self::new_search_record();
		$rec->set_TrackerID($TrackerID);
		return self::search($rec);
	}

	/**
		@param $ret base_return
		Populates fsopilots with associated SWPilot record
		@return base_return
	 */
	public static function populate_swpilots(base_return $ret)
	{
		$ids = $ret->get_ids();
		$rec = self::new_swpilot_search_record();
		$rec->set_id($ids);
		$model = new fsopilot_data_swpilot();
		$returnobj = $model->search($rec);

		return self::populate_pilot_records_with_related_recs(
			$ret, $returnobj->get_results(), 'set_swpilot'
		);
	}

	protected static function populate_pilot_records_with_related_recs(
		$dest_returnobj, $child_item_array, $method_to_add_to_obj
	)
	{
		$dest_array = $dest_returnobj->get_results();
		//step over each node and find its children.
		foreach($dest_array as $key => $node)
		{
			$id_we_want = $dest_array[$key]->get_id();
			$items_for_node = array();

			foreach($child_item_array as $res)
			{
				if($res->get_id() == $id_we_want)
				{
					$items_for_node[] = $res;
				}
			}

			$dest_array[$key]->$method_to_add_to_obj($items_for_node);
		}

		//put the NEW result array into the return obj and give it back.
		$dest_returnobj->set_result($dest_array);
		return $dest_returnobj;
	}

	//return an empty instance of the detail record
	/** @return fsopilot_record_detail **/
	public static function new_detail_record()
	{
		return new fsopilot_record_detail();
	}

	//return an empty instance of the search record
	/** @return fsopilot_record_search **/
	public static function new_search_record()
	{
		return new fsopilot_record_search();
	}

	//return an empty instance of the swpilot detail record
	/** @return fsopilot_record_swpilot_detail **/
	public static function new_swpilot_detail_record()
	{
		return new fsopilot_record_swpilot_detail();
	}

	//return an empty instance of the swpilot search record
	/** @return fsopilot_record_swpilot_search **/
	public static function new_swpilot_search_record()
	{
		return new fsopilot_record_swpilot_search();
	}
}

?>

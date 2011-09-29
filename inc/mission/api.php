<?php
class mission_api
{
	/**
		@param $id int
		get a mission by id
		@return mission_record_detail
	**/
	public static function get($id)
	{
		$model = new mission_data_main();
		return $model->get($id);
	}

	/**
		@param $record base_record
		search for any kind of mission thingie.
		@return base_result or boolean
	**/
	public static function search(base_recordsearch $record)
	{
		$model = '';
		if($record instanceof mission_record_search)
		{
			$model = new mission_data_main();
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
		if($record instanceof mission_record_search)
		{
			$model = new mission_data_main();
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
		@return mission_record_detail
	**/
	public static function get_mission_by_filename($filename)
	{
		$rec = self::new_search_record();
		$rec->set_filename($filename);
		$ret = self::search($rec);
		return $ret->get_first_result();
	}

	/**
		shortcut method.
		@return mission_record_detail
	**/
	public static function get_mission_by_name($name)
	{
		$rec = self::new_search_record();
		$rec->set_name($name);
		$ret = self::search($rec);
		return $ret->get_first_result();
	}

	//return an empty instance of the detail record
	/** @return mission_record_detail **/
	public static function new_detail_record()
	{
		return new mission_record_detail();
	}

	//return an empty instance of the search record
	/** @return mission_record_search **/
	public static function new_search_record()
	{
		return new mission_record_search();
	}
}
?>

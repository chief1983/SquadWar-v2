<?php
class news_api
{

	/**
		@param $id int
		get a news entry by id
		@return news_record_detail
	**/
	public static function get($id)
	{
		$model = new news_data_main();
		return $model->get($id);
	}

	/**
		@param $record base_record
		search for any kind of news thingie.
		@return base_result or boolean
	**/
	public static function search(base_recordsearch $record)
	{
		$model = '';
		if($record instanceof news_record_search)
		{
			$model = new news_data_main();
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
		if($record instanceof news_record_search)
		{
			$model = new news_data_main();
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

	//return an empty instance of the detail record
	/** @return news_record_detail **/
	public static function new_detail_record()
	{
		return new news_record_detail();
	}

	//return an empty instance of the search record
	/** @return news_record_search **/
	public static function new_search_record()
	{
		return new news_record_search();
	}
}

?>

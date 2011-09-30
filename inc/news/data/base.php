<?php

/**
	@author cliff gordon
	@version 0.1
	@package squadwar
	@subpackage news
	@description basic news stuff.
*/
class news_data_base extends base_database
{
	protected $database = 'internetsql';
	protected $table = 'news_main';

	/**
		build snippet of where clause for Pilot_IDs
	**/
	protected function where_clause_newsID()
	{
		if(!method_exists($this->incoming_record, 'get_newsID'))
		{
			return '';
		}
		$newsID = $this->incoming_record->get_newsID();
		$where_fragment = '';
		if(!empty($newsID))
		{
			$where_fragment = " and newsID = {$this->db->quote($newsID)}";
		}
		return $where_fragment;
	}


	/**
		build snippet of where clause for ids
	**/
	protected function where_clause_id()
	{
		if(!method_exists($this->incoming_record, 'get_id'))
		{
			return '';
		}
		$id = $this->incoming_record->get_id();
		$where_fragment = '';
		if(!empty($id))
		{
			$where_fragment = " and newsID = {$this->db->quote($id)}";
		}
		return $where_fragment;
	}

}
?>

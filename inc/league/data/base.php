<?php

/**
	@author cliff gordon
	@version 0.1
	@package squadwar_models
	@subpackage league
	@description basic league stuff.
*/
class league_data_base extends base_database
{
	protected $database = SQUADWAR_DB;
	protected $table = 'SWLeagues';

	/**
		build snippet of where clause for League_IDs
	**/
	protected function where_clause_League_ID()
	{
		if(!method_exists($this->incoming_record, 'get_League_ID'))
		{
			return '';
		}
		$League_ID = $this->incoming_record->get_League_ID();
		$where_fragment = '';
		if(!empty($League_ID))
		{
			$where_fragment = " and League_ID = {$this->db->quote($League_ID)}";
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
			$where_fragment = " and id = {$this->db->quote($id)}";
		}
		return $where_fragment;
	}

}
?>

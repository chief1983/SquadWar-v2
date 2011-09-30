<?php

/**
	@author cliff gordon
	@version 0.1
	@package squadwar_models
	@subpackage mission
	@description basic mission stuff.
*/
class mission_data_base extends base_database
{
	protected $database = SQUADWAR_DB;
	protected $table = 'missions';
	protected $abbr = 'm';

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
			$id = (array)$id;
			foreach($id as $key => $single_id)
			{
				$id[$key] = $this->db->quote($single_id);
			}
			$where_fragment = " and id in (".implode(',',$id).")";
		}
		return $where_fragment;
	}

}
?>

<?php

/**
	@author cliff gordon
	@version 0.1
	@package squadwar_models
	@subpackage user
	@description basic user stuff.
*/
class user_data_base extends base_database
{
	protected $table = 'squadwar.Users';

	/**
		build snippet of where clause for Tracker_IDs
	**/
	protected function where_clause_TrackerID()
	{
		if(!method_exists($this->incoming_record, 'get_TrackerID'))
		{
			return '';
		}
		$TrackerID = $this->incoming_record->get_TrackerID();
		$where_fragment = '';
		if(!empty($TrackerID))
		{
			$TrackerID = (array)$TrackerID;
			foreach($TrackerID as $key => $id)
			{
				$TrackerID[$key] = $this->db->quote($id);
			}
			$where_fragment = " and TrackerID in (".implode(',', $TrackerID).")";
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
			$where_fragment = " and TrackerID = {$this->db->quote($id)}";
		}
		return $where_fragment;
	}

}
?>

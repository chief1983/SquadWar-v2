<?php

/**
	@author cliff gordon
	@version 0.1
	@package squadwar
	@subpackage fsopilot
	@description basic fsopilot stuff.
*/
class fsopilot_data_base extends base_database
{
	protected $table = 'squadwar.FreeSpace2Full';

	/**
		build snippet of where clause for Pilot_IDs
	**/
	protected function where_clause_PilotID()
	{
		if(!method_exists($this->incoming_record, 'get_PilotID'))
		{
			return '';
		}
		$PilotID = $this->incoming_record->get_PilotID();
		$where_fragment = '';
		if(!empty($PilotID))
		{
			$PilotID = (array)$PilotID;
			foreach($PilotID as $key => $id)
			{
				$PilotID[$key] = $this->db->quote($id);
			}
			$where_fragment = " and PilotID in (".implode(',',$PilotID).")";
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
			$id = (array)$id;
			foreach($id as $key => $single_id)
			{
				$id[$key] = $this->db->quote($single_id);
			}
			$where_fragment = " and PilotID in (".implode(',',$id).")";
		}
		return $where_fragment;
	}

}
?>

<?php

/**
	@author cliff gordon
	@version 0.1
	@package squadwar
	@subpackage squad
	@description basic squad stuff.
*/
class squad_data_base extends base_database
{
	protected $database = SQUADWAR_DB;
	protected $table = 'SWSquads';

	/**
		build snippet of where clause for user_ids
	**/
	protected function where_clause_SquadID()
	{
		if(!method_exists($this->incoming_record, 'get_SquadID'))
		{
			return '';
		}
		$SquadID = $this->incoming_record->get_SquadID();
		$where_fragment = '';
		if(!empty($SquadID))
		{
			$SquadID = (array)$SquadID;
			foreach($SquadID as $key => $id)
			{
				$SquadID[$key] = $this->db->quote($id);
			}
			$where_fragment = " and SquadID in (".implode(',',$SquadID).")";
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
			$where_fragment = " and SquadID in (".implode(',',$id).")";
		}
		return $where_fragment;
	}

}
?>

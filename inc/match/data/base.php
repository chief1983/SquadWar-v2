<?php

/**
	@author Cliff Gordon
	@version 0.1
	@package squadwar
	@subpackage match
	@description basic match stuff.
*/
class match_data_base extends base_database
{
	protected $database = SQUADWAR_DB;
	protected $table = 'SWMatches';
	protected $abbr = 'm';

	/**
		build snippet of where clause for user_ids
	**/
	protected function where_clause_MatchID()
	{
		if(!method_exists($this->incoming_record, 'get_MatchID'))
		{
			return '';
		}
		$MatchID = $this->incoming_record->get_MatchID();
		$where_fragment = '';
		if(!empty($MatchID))
		{
			$MatchID = (array)$MatchID;
			foreach($MatchID as $key => $id)
			{
				$MatchID[$key] = $this->db->quote($id);
			}
			$where_fragment = " and MatchID in (".implode(',',$MatchID).")";
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
			$where_fragment = " and MatchID in (".implode(',',$id).")";
		}
		return $where_fragment;
	}

}
?>

<?php

class squad_data_league extends squad_data_base
{
	protected $table = 'squadwar.SWSquads_Leagues';
	protected $all_fields = array('id', 'SWSquad_SquadID', 'Leagues', 'League_PW');
	protected $primary_fields = array('SWSquad_SquadID');
	protected $detail_record = 'squad_record_league_detail';

	public function __construct()
	{
		parent::__construct();
	}

	public function get($id)
	{
		$sql = "
			select ".implode(', ', $this->all_fields)."
			from ".$this->table."
			where SWSquad_SquadID = {$this->db->quote($id)}
		";

		return $this->exec_sql_return_record($sql, $this->detail_record);
	}

	public function search(squad_record_league_search $record)
	{
		return parent::search($record);
	}

	public function save(squad_record_league_detail $record)
	{
		return parent::save($record);
	}


	public function create(squad_record_league_detail $record)
	{
		return parent::create($record);
	}


	/**
		update table
	**/
	protected function update(squad_record_league_detail $record)
	{
		return parent::update($record);
	}


	public function delete(squad_record_league_detail $record)
	{
		return parent::delete($record);
	}

	/**
		build where clause.
	**/
	protected function build_where_clause()
	{
		$where_clause = "";
		$where_clause .= $this->where_clause_SWSquad_SquadID();
		$where_clause .= $this->where_clause_Leagues();
		$where_clause .= $this->where_clause_League_PW();
		
		if($where_clause == '')
		{
			//no where clause.  get nothing.
			$where_clause = ' where 1=0 ';
		}
		else
		{
			$where_clause = ' where 1=1 ' . $where_clause;
		}

		return $where_clause;
	}


	/**
		build snippet of where clause for SWSquad_SquadID
	**/
	protected function where_clause_SWSquad_SquadID()
	{
		if(!method_exists($this->incoming_record, 'get_SWSquad_SquadID'))
		{
			return '';
		}
		$SWSquad_SquadID = $this->incoming_record->get_SWSquad_SquadID();
		$where_fragment = '';
		if(!empty($SWSquad_SquadID))
		{
			$SWSquad_SquadID = (array)$SWSquad_SquadID;
			foreach($SWSquad_SquadID as $key => $id)
			{
				$SWSquad_SquadID[$key] = $this->db->quote($id);
			}
			$where_fragment = " and SWSquad_SquadID in (".implode(',',$SWSquad_SquadID).")";
		}
		return $where_fragment;
	}

	/**
		build snippet of where clause for Leagues
	**/
	protected function where_clause_Leagues()
	{
		if(!method_exists($this->incoming_record, 'get_Leagues'))
		{
			return '';
		}
		$Leagues = $this->incoming_record->get_Leagues();
		$where_fragment = '';
		if(!empty($Leagues))
		{
			$where_fragment = " and Leagues = {$this->db->quote($Leagues)}";
		}
		return $where_fragment;
	}

	/**
		build snippet of where clause for League_PW
	**/
	protected function where_clause_League_PW()
	{
		if(!method_exists($this->incoming_record, 'get_League_PW'))
		{
			return '';
		}
		$League_PW = $this->incoming_record->get_League_PW();
		$where_fragment = '';
		if(!empty($League_PW))
		{
			$where_fragment = " and League_PW = {$this->db->quote($League_PW)}";
		}
		return $where_fragment;
	}
}

?>

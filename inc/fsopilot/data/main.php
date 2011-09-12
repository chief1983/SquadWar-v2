<?php
/**
    this touches the main user table, fsopilot table

    it gets, searches, creares, authenticates users.
**/
class fsopilot_data_main extends fsopilot_data_base
{
	protected $tables = array();

	public function get($id)
	{
		$sql = "
			select p.PilotID, p.TrackerID, p.Pilot, p.Rank, p.Score, p.Medals,
				p.Kills, p.Assists, p.KillCount, p.KillCountOK, p.PShotsFired,
				p.SShotsFired, p.PShotsHit, p.SShotsHit, p.PBoneheadHits,
				p.SBoneheadHits, p.BoneheadKills, p.MissionsFlown,
				p.FlightTime, p.LastFlown
			from ".$this->table." p
			where p.PilotID = {$this->db->quote($id)}
		";

		return $this->exec_sql_return_record($sql, 'fsopilot_record_detail');
	}


	public function search(fsopilot_record_search $record)
	{
		$this->init_return($record);

		$sql = "
			select p.PilotID, p.TrackerID, p.Pilot, p.Rank, p.Score, p.Medals,
				p.Kills, p.Assists, p.KillCount, p.KillCountOK, p.PShotsFired,
				p.SShotsFired, p.PShotsHit, p.SShotsHit, p.PBoneheadHits,
				p.SBoneheadHits, p.BoneheadKills, p.MissionsFlown,
				p.FlightTime, p.LastFlown
			from ".$this->table." p
		";

		$sql_where = $this->build_where_clause();
		$sql_from = $this->build_from_clause();
		$sql .= $sql_from . $sql_where;
		$sql .= $this->build_order_clause();
		$sql .= $this->build_limit_clause();

		if(!$this->errors)
		{
			$this->exec_sql_populate_return($sql, 'fsopilot_record_detail');
			$this->return->end();
		}

		$pag = new base_pagination(
			$this->search_db_get_recordcount($sql_from, $sql_where),
			$this->incoming_record->get_page_size(),
			$this->incoming_record->get_page_number()
		);

		$this->return->set_pagination($pag);

		$this->return->end();
		return $this->return;
	}


	public function get_count(fsopilot_record_search $record)
	{
		$this->init_return($record);

		$sql_where = $this->build_where_clause();
		$sql_from = $this->build_from_clause();

		return $this->search_db_get_recordcount($sql_from, $sql_where);
	}


	/**
		call the appropriate method based on whether we have an id.
	**/
	public function save(fsopilot_record_detail $record)
	{
		parent::save($record);
	}


	/**
		insert into fsopilot table
	**/
	protected function create(fsopilot_record_detail $record)
	{
		$sql = "
			insert into ".$this->table."
			(
				TrackerID, Pilot, Rank, Score, Medals, Kills, Assists,
				KillCount, KillCountOK, PShotsFired, SShotsFired, PShotsHit,
				SShotsHit, PBoneheadHits, SBoneheadHits, BoneheadKills,
				MissionsFlown, FlightTime, LastFlown
			)
			values
			(
				{$this->db->quote(SITE_CODE)},
				{$this->db->quote($record->get_TrackerID())},
				{$this->db->quote($record->get_Pilot())},
				{$this->db->quote($record->get_Rank())},
				{$this->db->quote($record->get_Score())},
				{$this->db->quote($record->get_Medals())},
				{$this->db->quote($record->get_Kills())},
				{$this->db->quote($record->get_Assists())},
				{$this->db->quote($record->get_KillCount())},
				{$this->db->quote($record->get_KillCountOK())},
				{$this->db->quote($record->get_PShotsFired())},
				{$this->db->quote($record->get_SShotsFired())},
				{$this->db->quote($record->get_PShotsHit())},
				{$this->db->quote($record->get_SShotsHit())},
				{$this->db->quote($record->get_PBoneheadHits())},
				{$this->db->quote($record->get_SBoneheadHits())},
				{$this->db->quote($record->get_BoneheadKills())},
				{$this->db->quote($record->get_MissionsFlown())},
				{$this->db->quote($record->get_FlightTime())},
				{$this->db->quote($record->get_LastFlown())}
			)
		";

		$id = $this->exec_sql_return_id($sql);

		return $id;
	}


	/**
		update fsopilot table
	**/
	protected function update(fsopilot_record_detail $record)
	{

		$sql = "
			update ".$this->table."
			set
				TrackerID = {$this->db->quote($record->get_TrackerID())},
				Pilot = {$this->db->quote($record->get_Pilot())},
				Rank = {$this->db->quote($record->get_Rank())},
				Score = {$this->db->quote($record->get_Score())},
				Medals = {$this->db->quote($record->get_Medals())},
				Kills = {$this->db->quote($record->get_Kills())},
				Assists = {$this->db->quote($record->get_Assists())},
				KillCount = {$this->db->quote($record->get_KillCount())},
				PShotsFired = {$this->db->quote($record->get_PShotsFired())},
				SShotsFired = {$this->db->quote($record->get_SShotsFired())},
				PShotsHit = {$this->db->quote($record->get_PShotsHit())},
				SShotsHit = {$this->db->quote($record->get_SShotsHit())},
				PBoneheadHits = {$this->db->quote($record->get_PBoneheadHits())},
				SBoneheadHits = {$this->db->quote($record->get_SBoneheadHits())},
				BoneheadKills = {$this->db->quote($record->get_BoneheadKills())},
				MissionsFlown = {$this->db->quote($record->get_MissionsFlown())},
				FlightTime = {$this->db->quote($record->get_FlightTime())},
				LastFlown = {$this->db->quote($record->get_LastFlown())}
			where PilotID = {$this->db->quote($record->get_id())}
		";

		$status = $this->exec_sql_return_status($sql);
		return $status;
	}

	public function delete(fsopilot_record_detail $record)
	{
		$sql = "
			delete from ".$this->table."
			where PilotID = {$this->db->quote($record->get_id())}
		";

		return $this->exec_sql_return_status($sql);

	}


    //used by search method to find total records.
	protected function search_db_get_recordcount($sql_from, $sql_where)
	{
		$sql = "
			select count(*) as recordcount
			from ".$this->table." p
		";
		$sql .= $sql_from;
		$sql .= $sql_where;

		$results = $this->exec_sql_return_array($sql);

		$recordcount = 0;

		if( array_key_exists("0", $results)
			&& array_key_exists("recordcount", $results[0])
		)
		{
			return $results[0]['recordcount'];
		}
	}


	protected function build_where_clause()
	{
		$sql = " where 1=1 ";
		$sql .= $this->where_clause_TrackerID();
		$sql .= $this->where_clause_Pilot();
		$sql .= $this->where_clause_Recruitme();
		return $sql;
	}


	/**
		when we build the where clause, we also record which tables are needed.
	**/
	protected function build_from_clause()
	{
		$sql = " ";
		//check to see which tables are required.
		if(in_array("SWPilots", $this->tables))
		{
			$sql .= ' inner join squadwar.SWPilots sp on p.PilotID = sp.PilotID ';
		}

		return $sql;
	}


	/**
		build snippet of where clause for first (name)
	**/
	protected function where_clause_TrackerID()
	{
		if(!method_exists($this->incoming_record, 'get_TrackerID'))
		{
			return '';
		}

		$TrackerID = $this->incoming_record->get_TrackerID();
		$where_fragment = '';
		if($TrackerID != '')
		{
			$where_fragment=" and p.TrackerID = {$this->db->quote($TrackerID)}";
		}
		return $where_fragment;
	}


	/**
		build snippet of where clause for Pilot
	**/
	protected function where_clause_Pilot()
	{
		if(!method_exists($this->incoming_record, 'get_Pilot'))
		{
			return '';
		}

		$Pilot = $this->incoming_record->get_Pilot();
		$where_fragment = '';
		if($Pilot != '')
		{
			$where_fragment=" and p.Pilot = {$this->db->quote($Pilot)}";
		}
		return $where_fragment;
	}


	/**
		build snippet of where clause for squad
	**/
	protected function where_clause_Recruitme()
	{
		if(!method_exists($this->incoming_record, 'get_Recruitme'))
		{
			return '';
		}

		$Recruitme = $this->incoming_record->get_Recruitme();
		$where_fragment = '';
		if($Recruitme != '')
		{
			$where_fragment=" and sp.Recruitme = {$this->db->quote($Recruitme)}";
			$this->tables[] = 'SWPilots';
		}
		return $where_fragment;
	}
}

?>

<?php
/**
    this touches the main user table, fsopilot table

    it gets, searches, creares, authenticates users.
**/
class fsopilot_data_main extends fsopilot_data_base
{
	protected $tables = array();
	protected $all_fields = array('p.id', 'p.user_id', 'p.pilot_name',
		'p.score', 'p.missions_flown', 'p.flight_time', 'p.last_flown',
		'p.kill_count', 'p.kill_count_ok', 'p.assists', 'p.p_shots_fired',
		'p.p_shots_hit', 'p.p_bonehead_hits', 'p.s_shots_fired',
		'p.s_shots_hit', 'p.s_bonehead_hits', 'p.rank', 'p.num_ship_kills',
		'p.ship_kills', 'p.num_medals', 'p.medals', 'p.d_mod');
	protected $primary_fields = array('p.id');
	protected $detail_record = 'fsopilot_record_detail';

	public function search(fsopilot_record_search $record)
	{
		return parent::search($record);
	}


	public function get_count(fsopilot_record_search $record)
	{
		return parent::get_count($record);
	}


	/**
		call the appropriate method based on whether we have an id.
	**/
	public function save(fsopilot_record_detail $record)
	{
		return parent::save($record);
	}


	/**
		insert into fsopilot table
	**/
	protected function create(fsopilot_record_detail $record)
	{
		return parent::create($record);
	}


	/**
		update fsopilot table
	**/
	protected function update(fsopilot_record_detail $record)
	{
		return parent::update($record);
	}

	public function delete(fsopilot_record_detail $record)
	{
		return parent::delete($record);
	}


	protected function build_where_clause()
	{
		$sql = " where 1=1 ";
		$sql .= $this->where_clause_id();
		$sql .= $this->where_clause_user_id();
		$sql .= $this->where_clause_pilot_name();
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
			$sql .= ' inner join `'.SQUADWAR_DB.'`.`'.self::table('SWPilots').'` sp on p.id = sp.PilotID ';
		}

		return $sql;
	}


	/**
		build snippet of where clause for user_id
	**/
	protected function where_clause_user_id()
	{
		if(!method_exists($this->incoming_record, 'get_user_id'))
		{
			return '';
		}

		$user_id = $this->incoming_record->get_user_id();
		$where_fragment = '';
		if($user_id != '')
		{
			$where_fragment=" and p.user_id = {$this->db->quote($user_id)}";
		}
		return $where_fragment;
	}


	/**
		build snippet of where clause for pilot_name
	**/
	protected function where_clause_pilot_name()
	{
		if(!method_exists($this->incoming_record, 'get_pilot_name'))
		{
			return '';
		}

		$pilot_name = $this->incoming_record->get_pilot_name();
		$where_fragment = '';
		if($pilot_name != '')
		{
			$where_fragment=" and p.pilot_name = {$this->db->quote($pilot_name)}";
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
		if(!is_null($Recruitme))
		{
			$where_fragment=" and sp.Recruitme = {$this->db->quote($Recruitme)}";
			$this->tables[] = 'SWPilots';
		}
		return $where_fragment;
	}
}

?>

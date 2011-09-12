<?php

class squad_data_info extends squad_data_base
{
	protected $table = 'squadwar.SWSquad_Info';

	public function __construct()
	{
		parent::__construct();
	}

	public function get($id)
	{
		$sql = "
			select SquadID, Squad_Leader_ID, Squad_Leader_ICQ, Squad_IRC,
				Squad_Email, Squad_Join_PW, Squad_Logo, Approved, Rest,
				time_registered, Squad_Red, Squad_Green, Squad_Blue,
				Squad_Time_Zone, Squad_Web_Link, Squad_Statement, Abbrv,
				ribbon_1, ribbon_2, ribbon_3, ribbon_4, ribbon_5, ribbon_6,
				medal_1, medal_2, medal_3, suspended, win_loss, power_rating
			from ".$this->table."
			where SquadID = {$this->db->quote($id)}
		";

		return $this->exec_sql_return_record($sql, 'squad_record_info_detail');
	}

	public function search(squad_record_info_search $record)
	{
		$this->init_return($record);

		$sql = "
			select SquadID, Squad_Leader_ID, Squad_Leader_ICQ, Squad_IRC,
				Squad_Email, Squad_Join_PW, Squad_Logo, Approved, Rest,
				time_registered, Squad_Red, Squad_Green, Squad_Blue,
				Squad_Time_Zone, Squad_Web_Link, Squad_Statement, Abbrv,
				ribbon_1, ribbon_2, ribbon_3, ribbon_4, ribbon_5, ribbon_6,
				medal_1, medal_2, medal_3, suspended, win_loss, power_rating
			from ".$this->table."
		";

		$sql_where = $this->build_where_clause();
		$sql .= $sql_where;
		$sql .= $this->build_order_clause();
		$sql .= $this->build_limit_clause();

		if(!$this->errors)
		{
			$this->exec_sql_populate_return($sql, 'squad_record_info_detail');
			$this->return->end();
		}

		$pag = new base_pagination(
			$this->search_get_recordcount($sql_where),
			$this->incoming_record->get_page_size(),
			$this->incoming_record->get_page_number()
		);

		$this->return->set_pagination($pag);

		$this->return->end();
		return $this->return;
	}

	protected function search_get_recordcount($sql_where)
	{
		$sql = "
			select count(*) as recordcount
			from ".$this->table."
		";
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

	/**
		call the appropriate method based on whether we have an id.
	**/
	public function save(squad_record_info_detail $record)
	{
		$this->init_return($record);
		$id = $record->get_id();
		if(empty($id))
		{
			return false;
		}
		else
		{
			$rec = $this->get($id);
			if(empty($rec))
			{
				return $this->create($record);
			}
			else
			{
				$status = $this->update($record);
				return $id;
			}
		}
	}


	public function create(squad_record_info_detail $record)
	{
		$sql = "
			insert into ".$this->table."
			(SquadID, Squad_Leader_ID, Squad_Leader_ICQ, Squad_IRC,
				Squad_Email, Squad_Join_PW, Squad_Logo, Approved, Rest,
				time_registered, Squad_Red, Squad_Green, Squad_Blue,
				Squad_Time_Zone, Squad_Web_Link, Squad_Statement, Abbrv,
				ribbon_1, ribbon_2, ribbon_3, ribbon_4, ribbon_5, ribbon_6,
				medal_1, medal_2, medal_3, suspended, win_loss, power_rating)
			values
			(
				{$this->db->quote($record->get_SquadID())},
				{$this->db->quote($record->get_Squad_Leader_ID())},
				{$this->db->quote($record->get_Squad_Leader_ICQ())},
				{$this->db->quote($record->get_Squad_IRC())},
				{$this->db->quote($record->get_Squad_Email())},
				{$this->db->quote($record->get_Squad_Join_PW())},
				{$this->db->quote($record->get_Squad_Logo())},
				{$this->db->quote($record->get_Approved())},
				{$this->db->quote($record->get_Rest())},
				{$this->db->quote($record->get_time_registered())},
				{$this->db->quote($record->get_Squad_Red())},
				{$this->db->quote($record->get_Squad_Green())},
				{$this->db->quote($record->get_Squad_Blue())},
				{$this->db->quote($record->get_Squad_Time_Zone())},
				{$this->db->quote($record->get_Squad_Web_Link())},
				{$this->db->quote($record->get_Squad_Statement())},
				{$this->db->quote($record->get_Abbrv())},
				{$this->db->quote($record->get_ribbon_1())},
				{$this->db->quote($record->get_ribbon_2())},
				{$this->db->quote($record->get_ribbon_3())},
				{$this->db->quote($record->get_ribbon_4())},
				{$this->db->quote($record->get_ribbon_5())},
				{$this->db->quote($record->get_ribbon_6())},
				{$this->db->quote($record->get_medal_1())},
				{$this->db->quote($record->get_medal_2())},
				{$this->db->quote($record->get_medal_3())},
				{$this->db->quote($record->get_suspended())},
				{$this->db->quote($record->get_win_loss())},
				{$this->db->quote($record->get_power_rating())}
			)
		";

		$id = $this->exec_sql_return_id($sql);

		return $id;
	}


	/**
		update squad_info table
	**/
	protected function update(squad_record_info_detail $record)
	{

		$sql = "
			update ".$this->table."
			set
				Squad_Leader_ID = {$this->db->quote($record->get_Squad_Leader_ID())},
				Squad_Leader_ICQ = {$this->db->quote($record->get_Squad_Leader_ICQ())},
				Squad_IRC = {$this->db->quote($record->get_Squad_IRC())},
				Squad_Email = {$this->db->quote($record->get_Squad_Email())},
				Squad_Join_PW = {$this->db->quote($record->get_Squad_Join_PW())},
				Squad_Logo = {$this->db->quote($record->get_Squad_Logo())},
				Approved = {$this->db->quote($record->get_Approved())},
				Rest = {$this->db->quote($record->get_Rest())},
				Squad_Red = {$this->db->quote($record->get_Squad_Red())},
				Squad_Green = {$this->db->quote($record->get_Squad_Green())},
				Squad_Blue = {$this->db->quote($record->get_Squad_Blue())},
				Squad_Time_Zone = {$this->db->quote($record->get_Squad_Time_Zone())},
				Squad_Web_Link = {$this->db->quote($record->get_Squad_Web_Link())},
				Squad_Statement = {$this->db->quote($record->get_Squad_Statement())},
				Abbrv = {$this->db->quote($record->get_Abbrv())},
				ribbon_1 = {$this->db->quote($record->get_ribbon_1())},
				ribbon_2 = {$this->db->quote($record->get_ribbon_2())},
				ribbon_3 = {$this->db->quote($record->get_ribbon_3())},
				ribbon_4 = {$this->db->quote($record->get_ribbon_4())},
				ribbon_5 = {$this->db->quote($record->get_ribbon_5())},
				ribbon_6 = {$this->db->quote($record->get_ribbon_6())},
				medal_1 = {$this->db->quote($record->get_medal_1())},
				medal_2 = {$this->db->quote($record->get_medal_2())},
				medal_3 = {$this->db->quote($record->get_medal_3())},
				suspended = {$this->db->quote($record->get_suspended())},
				win_loss = {$this->db->quote($record->get_win_loss())},
				power_rating = {$this->db->quote($record->get_power_rating())}
			where SquadID = {$this->db->quote($record->get_id())}
		";

		$status = $this->exec_sql_return_status($sql);
		return $status;
	}


	public function delete(squad_record_info_detail $record)
	{
		$sql = "
			delete from ".$this->table."
			where SquadID = {$this->db->quote($record->get_id())}
		";

		return $this->exec_sql_return_status($sql);
	}


	/**
		build where clause.
	**/
	protected function build_where_clause()
	{
		$where_clause = "";
		$where_clause .= $this->where_clause_SquadID();
		$where_clause .= $this->where_clause_Squad_Leader_ID();
		$where_clause .= $this->where_clause_Approved();
		$where_clause .= $this->where_clause_Abbrv();

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
		build snippet of where clause for ids
	**/
	protected function where_clause_Squad_Leader_ID()
	{
		if(!method_exists($this->incoming_record, 'get_Squad_Leader_ID'))
		{
			return '';
		}
		$id = $this->incoming_record->get_Squad_Leader_ID();
		$where_fragment = '';
		if(!empty($id))
		{
			$where_fragment = " and Squad_Leader_ID = {$this->db->quote($id)}";
		}
		return $where_fragment;
	}


	/**
		build snippet of where clause for Approved
	**/
	protected function where_clause_Approved()
	{
		if(!method_exists($this->incoming_record, 'get_Approved'))
		{
			return '';
		}
		$Approved = $this->incoming_record->get_Approved();
		$where_fragment = '';
		if(!empty($Approved))
		{
			$where_fragment = " and Approved = {$this->db->quote($Approved)}";
		}
		return $where_fragment;
	}


	/**
		build snippet of where clause for Abbrv
	**/
	protected function where_clause_Abbrv()
	{
		if(!method_exists($this->incoming_record, 'get_Abbrv'))
		{
			return '';
		}
		$Abbrv = $this->incoming_record->get_Abbrv();
		$where_fragment = '';
		if(!empty($Abbrv))
		{
			$where_fragment = " and Abbrv = {$this->db->quote($Abbrv)}";
		}
		return $where_fragment;
	}

}

?>

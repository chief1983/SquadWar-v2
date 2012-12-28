<?php
/**
    this touches the main swpilot table

    it gets, searches, creates swpilots.
**/
class fsopilot_data_swpilot extends fsopilot_data_base
{
	protected $database = SQUADWAR_DB;
	protected $tables = array();
	protected $table = 'SWPilots';
	protected $abbr = 'sp';
	protected $remote = false;
	
	public function get($id)
	{
		$sql = "
			select sp.PilotID, sp.user_id, sp.ICQ, sp.connection_type,
				sp.time_zone, sp.Member_Since, sp.show_email, sp.email,
				sp.Pilot_Name, sp.Recruitme, sp.Special, sp.Active,
				tz.description as fetch_time_zone,
				tz.value_hours as fetch_time_zone_hours,
				tz.value_minutes as fetch_time_zone_minutes,
				c.type as fetch_connection_type
			from `".$this->database."`.`".$this->table."` sp
				inner join form_time_zones tz on tz.ID = sp.time_zone
				inner join form_connection_type c on c.ID = sp.connection_type
			where sp.PilotID = {$this->db->quote($id)}
		";

		return $this->exec_sql_return_record($sql, 'fsopilot_record_swpilot_detail');
	}


	public function get_form_connection_types()
	{
		$sql = "
			select *
			from `".$this->database."`.`".self::table('form_connection_type')."`
			order by ID
		";

		return $this->exec_sql_return_array($sql);
	}

	public function get_form_time_zones()
	{
		$sql = "
			select *
			from `".$this->database."`.`".self::table('form_time_zones')."`
			order by ID
		";

		return $this->exec_sql_return_array($sql);
	}


	public function search(fsopilot_record_swpilot_search $record)
	{
		$this->init_return($record);

		$sql = "
			select sp.PilotID, sp.user_id, sp.ICQ, sp.connection_type,
				sp.time_zone, sp.Member_Since, sp.show_email, sp.email,
				sp.Pilot_Name, sp.Recruitme, sp.Special, sp.Active,
				tz.description as fetch_time_zone,
				tz.value_hours as fetch_time_zone_hours,
				tz.value_minutes as fetch_time_zone_minutes,
				c.type as fetch_connection_type
			from `".$this->database."`.`".$this->table."` sp
				inner join `".$this->database."`.`".self::table('form_time_zones')."` tz on tz.ID = sp.time_zone
				inner join`".$this->database."`.`".self::table('form_connection_type')."` c on c.ID = sp.connection_type
		";

		$sql_where = $this->build_where_clause();
		$sql_from = $this->build_from_clause();
		$sql .= $sql_from . $sql_where;
		$sql .= $this->build_order_clause();
		$sql .= $this->build_limit_clause();

		if(!$this->errors)
		{
			$this->exec_sql_populate_return($sql, 'fsopilot_record_swpilot_detail');
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


	public function get_count(fsopilot_record_swpilot_search $record)
	{
		$this->init_return($record);

		$sql_where = $this->build_where_clause();
		$sql_from = $this->build_from_clause();

		return $this->search_db_get_recordcount($sql_from, $sql_where);
	}


	/**
		call the appropriate method based on whether we have an id.
	**/
	public function save(fsopilot_record_swpilot_detail $record)
	{
		parent::save($record);
		$this->init_return($record);
		$id = $record->get_id();
		if(empty($id))
		{
			return false;
		}
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


	/**
		insert into fsopilot table
	**/
	protected function create(fsopilot_record_swpilot_detail $record)
	{
		$sql = "
			insert into `".$this->database."`.`".$this->table."`
			(
				PilotID, user_id, ICQ, connection_type, time_zone,
				Member_Since, show_email, email, Pilot_Name, Recruitme,
				Special, Active
			)
			values
			(
				{$this->db->quote($record->get_PilotID())},
				{$this->db->quote($record->get_user_id())},
				{$this->db->quote($record->get_ICQ())},
				{$this->db->quote($record->get_connection_type())},
				{$this->db->quote($record->get_time_zone())},
				{$this->db->quote($record->get_Member_Since())},
				{$this->db->quote($record->get_show_email())},
				{$this->db->quote($record->get_email())},
				{$this->db->quote($record->get_Pilot_Name())},
				{$this->db->quote($record->get_Recruitme())},
				{$this->db->quote($record->get_Special())},
				{$this->db->quote($record->get_Active())}
			)
		";

		$id = $this->exec_sql_return_id($sql);

		return $id;
	}


	/**
		update fsopilot table
	**/
	protected function update(fsopilot_record_swpilot_detail $record)
	{

		$sql = "
			update `".$this->database."`.`".$this->table."`
			set
				user_id = {$this->db->quote($record->get_user_id())},
				ICQ = {$this->db->quote($record->get_ICQ())},
				connection_type = {$this->db->quote($record->get_connection_type())},
				time_zone = {$this->db->quote($record->get_time_zone())},
				Member_Since = {$this->db->quote($record->get_Member_Since())},
				show_email = {$this->db->quote($record->get_show_email())},
				email = {$this->db->quote($record->get_email())},
				Pilot_Name = {$this->db->quote($record->get_Pilot_Name())},
				Recruitme = {$this->db->quote($record->get_Recruitme())},
				Special = {$this->db->quote($record->get_Special())},
				Active = {$this->db->quote($record->get_Active())}
			where PilotID = {$this->db->quote($record->get_id())}
		";

		$status = $this->exec_sql_return_status($sql);
		return $status;
	}

	public function delete(fsopilot_record_swpilot_detail $record)
	{
		$sql = "
			delete from `".$this->database."`.`".$this->table."`
			where PilotID = {$this->db->quote($record->get_id())}
		";

		return $this->exec_sql_return_status($sql);

	}


    //used by search method to find total records.
	protected function search_db_get_recordcount($sql_from, $sql_where)
	{
		$sql = "
			select count(*) as recordcount
			from `".$this->database."`.`".$this->table."` sp
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
		$sql .= $this->where_clause_PilotID();
		$sql .= $this->where_clause_user_id();
		$sql .= $this->where_clause_Pilot_Name();
		$sql .= $this->where_clause_Recruitme();
		return $sql;
	}


	/**
		build snippet of where clause for PilotIDs
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
			foreach($PilotID as $key => $single_id)
			{
				$PilotID[$key] = $this->db->quote($single_id);
			}
			$where_fragment = " and sp.PilotID in (".implode(',',$PilotID).")";
		}
		return $where_fragment;
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
			$where_fragment=" and sp.user_id = {$this->db->quote($user_id)}";
		}
		return $where_fragment;
	}


	/**
		build snippet of where clause for Pilot_Name
	**/
	protected function where_clause_Pilot_Name()
	{
		if(!method_exists($this->incoming_record, 'get_Pilot_Name'))
		{
			return '';
		}

		$Pilot_Name = $this->incoming_record->get_Pilot_Name();
		$where_fragment = '';
		if($Pilot_Name != '')
		{
			$where_fragment=" and sp.Pilot_Name = {$this->db->quote($Pilot_Name)}";
		}
		return $where_fragment;
	}


	/**
		build snippet of where clause for Recruitme
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
		}
		return $where_fragment;
	}
}

?>

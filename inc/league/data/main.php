<?php
/**
    this touches the main leagues table

    it gets, searches, creates, updates, etc leagues
**/
class league_data_main extends league_data_base
{
	protected $tables = array();

	public function get($id)
	{
		$sql = "
			select l.League_ID, l.Title, l.Description, l.Active, l.Archived, 
				l.Closed, l.challenged_max, l.map_location, l.map_graphics
			from `".$this->database."`.`".$this->table."` l
			where l.League_ID = {$this->db->quote($id)}
		";

		return $this->exec_sql_return_record($sql, 'league_record_detail');
	}


	public function search(league_record_search $record)
	{
		$this->init_return($record);

		$sql = "
			select l.League_ID, l.Title, l.Description, l.Active, l.Archived, 
				l.Closed, l.challenged_max, l.map_location, l.map_graphics
			from `".$this->database."`.`".$this->table."` l
		";

		$sql_where = $this->build_where_clause();
		$sql_from = $this->build_from_clause();
		$sql .= $sql_from . $sql_where;
		$sql .= $this->build_order_clause();
		$sql .= $this->build_limit_clause();

		if(!$this->errors)
		{
			$this->exec_sql_populate_return($sql, 'league_record_detail');
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


	public function get_count(league_record_search $record)
	{
		$this->init_return($record);

		$sql_where = $this->build_where_clause();
		$sql_from = $this->build_from_clause();

		return $this->search_db_get_recordcount($sql_from, $sql_where);
	}


	/**
		call the appropriate method based on whether we have an id.
	**/
	public function save(league_record_detail $record)
	{
		$this->init_return($record);
		$league_id = $record->get_id();
		if(empty($league_id))
		{
			return $this->create($record);
		}
		else
		{
			$status = $this->update($record);
			return $league_id;
		}
	}


	/**
		insert into leagues table
	**/
	protected function create(league_record_detail $record)
	{
		$sql = "
			insert into `".$this->database."`.`".$this->table."`
			(
				Title, Description, Active, Archived, Closed, challenged_max,
				map_location, map_graphics
			)
			values
			(
				{$this->db->quote($record->get_Title())},
				{$this->db->quote($record->get_Description())},
				{$this->db->quote($record->get_Active())},
				{$this->db->quote($record->get_Archived())},
				{$this->db->quote($record->get_Closed())},
				{$this->db->quote($record->get_challenged_max())},
				{$this->db->quote($record->get_map_location())},
				{$this->db->quote($record->get_map_graphics())}
			)
		";

		$id = $this->exec_sql_return_id($sql);

		return $id;
	}


	/**
		update league table
	**/
	protected function update(league_record_detail $record)
	{

		$sql = "
			update `".$this->database."`.`".$this->table."`
			set
				Title = {$this->db->quote($record->get_Title())},
				Description = {$this->db->quote($record->get_Description())},
				Active = {$this->db->quote($record->get_Active())},
				Archived = {$this->db->quote($record->get_Archived())},
				Closed = {$this->db->quote($record->get_Closed())},
				challenged_max = {$this->db->quote($record->get_challenged_max())},
				map_location = {$this->db->quote($record->get_map_location())},
				map_graphics = {$this->db->quote($record->get_map_graphics())}
			where League_ID = {$this->db->quote($record->get_id())}
		";

		$status = $this->exec_sql_return_status($sql);
		return $status;
	}

	public function delete(league_record_detail $record)
	{
		$sql = "
			delete from `".$this->database."`.`".$this->table."`
			where League_ID = {$this->db->quote($record->get_id())}
		";

		return $this->exec_sql_return_status($sql);

	}


	//used by search method to find total records.
	protected function search_db_get_recordcount($sql_from, $sql_where)
	{
		$sql = "
			select count(*) as recordcount
			from `".$this->database."`.`".$this->table."` l
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
		$sql = " where 1 = 1 ";
		$sql .= $this->where_clause_Title();
		$sql .= $this->where_clause_Description();
		$sql .= $this->where_clause_Active();
		$sql .= $this->where_clause_Archived();
		$sql .= $this->where_clause_Closed();
		$sql .= $this->where_clause_challenged_max();
		$sql .= $this->where_clause_map_location();
		$sql .= $this->where_clause_map_graphics();
		$sql .= $this->where_clause_squad();
		return $sql;
	}


	/**
		when we build the where clause, we also record which tables are needed.
	**/
	protected function build_from_clause()
	{
		$sql = " ";
		//check to see which tables are required.
		if(in_array("SWSquads_Leagues", $this->tables))
		{
			$sql .= ' inner join `'.SQUADWAR_DB.'`.`'.self::table('SWSquads_Leagues').'` sl on l.League_ID = sl.Leagues ';
		}

		return $sql;
	}


	/**
		build snippet of where clause for Title
	**/
	protected function where_clause_Title()
	{
		if(!method_exists($this->incoming_record, 'get_Title'))
		{
			return '';
		}

		$Title = $this->incoming_record->get_Title();
		$where_fragment = '';
		if($Title != '')
		{
			$where_fragment=" and l.Title = {$this->db->quote($Title)}";
		}
		return $where_fragment;
	}


	/**
		build snippet of where clause for Description
	**/
	protected function where_clause_Description()
	{
		if(!method_exists($this->incoming_record, 'get_Description'))
		{
			return '';
		}

		$Description = $this->incoming_record->get_Description();
		$where_fragment = '';
		if($Description != '')
		{
			$where_fragment=" and l.Description like {$this->db->quote('%'.$Description.'%')}";
		}
		return $where_fragment;
	}


	/**
		build snippet of where clause for Active
	**/
	protected function where_clause_Active()
	{
		if(!method_exists($this->incoming_record, 'get_Active'))
		{
			return '';
		}

		$Active = $this->incoming_record->get_Active();
		$where_fragment = '';
		if($Active != '')
		{
			$where_fragment=" and l.Active = {$this->db->quote($Active)}";
		}
		return $where_fragment;
	}


	/**
		build snippet of where clause for Archived
	**/
	protected function where_clause_Archived()
	{
		if(!method_exists($this->incoming_record, 'get_Archived'))
		{
			return '';
		}

		$Archived = $this->incoming_record->get_Archived();
		$where_fragment = '';
		if(!is_null($Archived))
		{
			$where_fragment=" and l.Archived = {$this->db->quote($Archived)}";
		}
		return $where_fragment;
	}


	/**
		build snippet of where clause for Closed
	**/
	protected function where_clause_Closed()
	{
		if(!method_exists($this->incoming_record, 'get_Closed'))
		{
			return '';
		}

		$Closed = $this->incoming_record->get_Closed();
		$where_fragment = '';
		if($Closed != '')
		{
			$where_fragment=" and l.Closed = {$this->db->quote($Closed)}";
		}
		return $where_fragment;
	}


	/**
		build snippet of where clause for challenged_max
	**/
	protected function where_clause_challenged_max()
	{
		if(!method_exists($this->incoming_record, 'get_challenged_max'))
		{
			return '';
		}

		$challenged_max = $this->incoming_record->get_challenged_max();
		$where_fragment = '';
		if($challenged_max != '')
		{
			$where_fragment=" and l.challenged_max = {$this->db->quote($challenged_max)}";
		}
		return $where_fragment;
	}


	/**
		build snippet of where clause for map_location
	**/
	protected function where_clause_map_location()
	{
		if(!method_exists($this->incoming_record, 'get_map_location'))
		{
			return '';
		}

		$map_location = $this->incoming_record->get_map_location();
		$where_fragment = '';
		if($map_location != '')
		{
			$where_fragment=" and l.map_location = {$this->db->quote($map_location)}";
		}
		return $where_fragment;
	}


	/**
		build snippet of where clause for map_graphics
	**/
	protected function where_clause_map_graphics()
	{
		if(!method_exists($this->incoming_record, 'get_map_graphics'))
		{
			return '';
		}

		$map_graphics = $this->incoming_record->get_map_graphics();
		$where_fragment = '';
		if($map_graphics != '')
		{
			$where_fragment=" and l.map_graphics = {$this->db->quote($map_graphics)}";
		}
		return $where_fragment;
	}


	/**
		build snippet of where clause for squad
	**/
	protected function where_clause_squad()
	{
		if(!method_exists($this->incoming_record, 'get_squad'))
		{
			return '';
		}

		$squad = $this->incoming_record->get_squad();
		if(is_object($squad) && get_class($squad) == 'squad_record_detail')
		{
			$squad_id = $squad->get_id();
		}
		else
		{
			$squad_id = $squad;
		}
		$where_fragment = '';
		if($squad_id != '')
		{
			$where_fragment=" and sl.SWSquad_SquadID = {$this->db->quote($squad_id)}";
			$this->tables[] = 'SWSquads_Leagues';
		}
		return $where_fragment;
	}
}

?>

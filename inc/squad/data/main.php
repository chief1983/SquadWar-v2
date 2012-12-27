<?php
/**
    this touches the main squad table, SWSquads

    it gets, searches, creates, squads.
**/
class squad_data_main extends squad_data_base
{
	protected $tables = array();

	public function get($id)
	{
		$sql = "
			select s.SquadID, s.SquadName, s.SquadPassword, s.SquadMembers,
				s.Active
			from `".$this->database."`.`".$this->table."` s
			where s.SquadID = {$this->db->quote($id)}
		";

		return $this->exec_sql_return_record($sql, 'squad_record_detail');
	}


	public function search(squad_record_search $record)
	{
		$this->init_return($record);

		$sql = "
			select s.SquadID, s.SquadName, s.SquadPassword, s.SquadMembers,
				s.Active
			from `".$this->database."`.`".$this->table."` s
		";

		$sql_where = $this->build_where_clause();
		$sql_from = $this->build_from_clause();
		$sql .= $sql_from . $sql_where;
		$sql .= " group by s.SquadID ";
		$sql .= $this->build_order_clause();
		$sql .= $this->build_limit_clause();

		if(!$this->errors)
		{
			$this->exec_sql_populate_return($sql, 'squad_record_detail');
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


	public function get_count(squad_record_search $record)
	{
		$this->init_return($record);

		$sql_where = $this->build_where_clause();
		$sql_from = $this->build_from_clause();

		return $this->search_db_get_recordcount($sql_from, $sql_where);
	}


	/**
		call the appropriate method based on whether we have an id.
	**/
	public function save(squad_record_detail $record)
	{
		$this->init_return($record);
		$id = $record->get_id();
		if(empty($id))
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
		insert into squad table
	**/
	protected function create(squad_record_detail $record)
	{
		$sql = "
			insert into `".$this->database."`.`".$this->table."`
			(
				SquadName, SquadPassword, SquadMembers, Active
			)
			values
			(
				{$this->db->quote($record->get_SquadName())},
				{$this->db->quote($record->get_SquadPassword())},
				{$this->db->quote(implode(',',$record->get_SquadMembers()))},
				{$this->db->quote($record->get_Active())}
			)
		";

		$id = $this->exec_sql_return_id($sql);
//		$this->set_up_default_info($id);

		return $id;
	}


	/**
		this exists because for some info we want to init
	**/
	protected function set_up_default_info($SquadID)
	{
		$squad_info = squad_api::new_info_detail_record();
		$squad_info->set_SquadID($SquadID);
		$squad_info->save();
	}


	/**
		update squad table
	**/
	protected function update(squad_record_detail $record)
	{

		$sql = "
			update `".$this->database."`.`".$this->table."`
			set
				SquadName = {$this->db->quote($record->get_SquadName())},
				SquadPassword = {$this->db->quote($record->get_SquadPassword())},
				SquadMembers = {$this->db->quote(implode(',',$record->get_SquadMembers()))},
				Active = {$this->db->quote($record->get_Active())}
			where SquadID = {$this->db->quote($record->get_id())}
		";

		$status = $this->exec_sql_return_status($sql);
		return $status;
	}

	public function delete(squad_record_detail $record)
	{
		$sql = "
			delete from `".$this->database."`.`".$this->table."`
			where SquadID = {$this->db->quote($record->get_id())}
		";

		return $this->exec_sql_return_status($sql);

	}


    /**
        reset squad password.
        from old to new.

        return 2 if old password doesn't match
        return 1 if reset worked
        return 0 if reset failed
    **/
    public function change_password(squad_record_detail $record, $newpw)
    {
        $sql = "
            select SquadID
            from `".$this->database."`.`".$this->table."`
            where SquadID = {$this->db->quote($record->get_id())}
                and SquadPassword = {$this->db->quote($record->get_SquadPassword())}
        ";

        $found = $this->exec_sql_return_status($sql);
        if(empty($found))
        {
            return 2;
        }
        else
        {
            $sql = "
                update `".$this->database."`.`".$this->table."`
                set SquadPassword = {$this->db->quote($this->salt_hash_password($newpw))}
                where SquadID = {$this->db->quote($record->get_id())}
            ";

            $reset = $this->exec_sql_return_status($sql);

            if(empty($reset))
            {
                return 0;
            }
            else
            {
                return 1;
            }
        }
    }


    //used by search method to find total records.
	protected function search_db_get_recordcount($sql_from, $sql_where)
	{
		$sql = "
			select count(*) as recordcount
			from `".$this->database."`.`".$this->table."` s
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
		$sql .= $this->where_clause_SquadID();
		$sql .= $this->where_clause_SquadName();
		$sql .= $this->where_clause_Active();
		$sql .= $this->where_clause_suspended();
		$sql .= $this->where_clause_league();
		$sql .= $this->where_clause_has_sectors();
		return $sql;
	}


	/**
		when we build the where clause, we also record which tables are needed.
	**/
	protected function build_from_clause()
	{
		$sql = " ";
		//check to see which tables are required.
		if(in_array("league", $this->tables))
		{
			$sql .= ' inner join `'.SQUADWAR_DB.'`.`'.squad_data_league::table().'` sl on s.SquadID = sl.SWSquad_SquadID ';
		}
		if(in_array("info", $this->tables))
		{
			$sql .= ' inner join `'.SQUADWAR_DB.'`.`'.squad_data_info::table().'` si on s.SquadID = si.SquadID ';
		}
		if(in_array("sectors", $this->tables))
		{
			$sql .= ' inner join `'.SQUADWAR_DB.'`.`'.squad_data_sector::table().'` sec on s.SquadID = sec.SectorSquad ';
		}

		return $sql;
	}


	/**
		build snippet of where clause for SquadName
	**/
	protected function where_clause_SquadName()
	{
		if(!method_exists($this->incoming_record, 'get_SquadName'))
		{
			return '';
		}

		$SquadName = $this->incoming_record->get_SquadName();
		$where_fragment = '';
		if($SquadName != '')
		{
			$where_fragment=" and s.SquadName = {$this->db->quote($SquadName)}";
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
		if(!is_null($Active))
		{
			$where_fragment=" and s.Active = {$this->db->quote($Active)}";
		}
		return $where_fragment;
	}


	/**
		build snippet of where clause for suspended
	**/
	protected function where_clause_suspended()
	{
		if(!method_exists($this->incoming_record, 'get_suspended'))
		{
			return '';
		}

		$suspended = $this->incoming_record->get_suspended();
		$where_fragment = '';
		if(!is_null($suspended))
		{
			$where_fragment=" and si.suspended = {$this->db->quote($suspended)}";
			$this->tables[] = 'info';
		}
		return $where_fragment;
	}


	/**
		build snippet of where clause for league
	**/
	protected function where_clause_league()
	{
		if(!method_exists($this->incoming_record, 'get_league'))
		{
			return '';
		}

		$league = $this->incoming_record->get_league();
		$where_fragment = '';
		if($league != '')
		{
			$where_fragment=" and sl.Leagues = {$this->db->quote($league)}";
			$this->tables[] = 'league';
		}
		return $where_fragment;
	}


	/**
		build snippet of where clause for has_sectors
	**/
	protected function where_clause_has_sectors()
	{
		if(!method_exists($this->incoming_record, 'get_has_sectors'))
		{
			return '';
		}

		$has_sectors = $this->incoming_record->get_has_sectors();
		$where_fragment = '';
		if(!is_null($has_sectors))
		{
			if(method_exists($this->incoming_record, 'get_league'))
			{
				$league = $this->incoming_record->get_league();
			}
			if($has_sectors)
			{
				if(!empty($league))
				{
					$where_fragment.=" and sec.League_ID = {$this->db->quote($league)}";
				}
				$where_fragment.=" and s.SquadID = sec.SectorSquad";
				$this->tables[] = 'sectors';
			}
			else
			{
				$where_fragment.=" and (SELECT COUNT(SectorName) FROM SWSectors WHERE (s.SquadID = SectorSquad)";
				if(!empty($league))
				{
					$where_fragment.="AND (League_ID = ".$league.") ";
				}
				$where_fragment.=" ) = 0";
			}
		}
		return $where_fragment;
	}


	/**
		salt and hash password like so much leftover ham
	**/
	protected function salt_hash_password($password)
	{
		return md5('p1ckl3MYgoat. ' . $password . '(dE8@m^;;~|../lLO_dd');
	}
}

?>

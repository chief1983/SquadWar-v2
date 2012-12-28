<?php
/**
    this touches the main squad table, SWSquads

    it gets, searches, creates, squads.
**/
class match_data_main extends match_data_base
{
	protected $tables = array();
	protected $all_fields = array('m.MatchID', 'm.SWCode', 'm.SWSquad1',
		'm.SWSquad2', 'm.SWSector_ID', 'm.SWDeleteTime', 'm.League_ID');
	protected $primary_fields = array('m.MatchID');
	protected $detail_record = 'match_record_detail';

	public function get($id)
	{
		$sql = "
			select ".implode(', ', $this->all_fields)."
			from `".$this->database."`.`".$this->table."` ".$this->abbr."
			where ".reset($this->primary_fields)." = {$this->db->quote($id)}
		";

		return $this->exec_sql_return_record($sql, $this->detail_record);
	}

	public function get_by_SWCode($SWCode)
	{
		$sql = "
			select ".implode(', ', $this->all_fields)."
			from `".$this->database."`.`".$this->table."` ".$this->abbr."
			where SWCode = {$this->db->quote($SWCode)}
		";

		return $this->exec_sql_return_record($sql, $this->detail_record);
	}


	public function search(match_record_search $record)
	{
		return parent::search($record);
	}


	public function get_count(match_record_search $record)
	{
		return parent::get_count($record);
	}


	/**
		call the appropriate method based on whether we have an id.
	**/
	public function save(match_record_detail $record)
	{
		return parent::save($record);
	}


	public function create(match_record_detail $record)
	{
		return parent::create($record);
	}


	/**
		update table
	**/
	protected function update(match_record_detail $record)
	{
		return parent::update($record);
	}


	public function delete(match_record_detail $record)
	{
		return parent::delete($record);
	}


	protected function build_where_clause()
	{
		$sql = " where 1=1 ";
		$sql .= $this->where_clause_SWCode();
		$sql .= $this->where_clause_SWSquad1();
		$sql .= $this->where_clause_SWSquad2();
		$sql .= $this->where_clause_either_squad();
		$sql .= $this->where_clause_SWSector_ID();
		$sql .= $this->where_clause_League_ID();
		return $sql;
	}


	/**
		when we build the where clause, we also record which tables are needed.
	**/
	protected function build_from_clause()
	{
		$sql = " ";
		//check to see which tables are required.
		if(in_array("info", $this->tables))
		{
			$sql .= ' inner join `'.SQUADWAR_DB.'`.`SWMatches_Info` mi on m.SWCode = mi.SWCode ';
		}

		return $sql;
	}


	/**
		build snippet of where clause for SWCode
	**/
	protected function where_clause_SWCode()
	{
		if(!method_exists($this->incoming_record, 'get_SWCode'))
		{
			return '';
		}

		$SWCode = $this->incoming_record->get_SWCode();
		$where_fragment = '';
		if($SWCode != '')
		{
			$where_fragment=" and SWCode = {$this->db->quote($SWCode)}";
		}
		return $where_fragment;
	}


	/**
		build snippet of where clause for SWSquad1
	**/
	protected function where_clause_SWSquad1()
	{
		if(!method_exists($this->incoming_record, 'get_SWSquad1'))
		{
			return '';
		}

		$SWSquad1 = $this->incoming_record->get_SWSquad1();
		$where_fragment = '';
		if(!empty($SWSquad1))
		{
			$SWSquad1 = (array)$SWSquad1;
			foreach($SWSquad1 as $key => $id)
			{
				$SWSquad1[$key] = $this->db->quote($id);
			}
			$where_fragment = " and SWSquad1 in (".implode(',',$SWSquad1).")";
		}
		return $where_fragment;
	}


	/**
		build snippet of where clause for SWSquad2
	**/
	protected function where_clause_SWSquad2()
	{
		if(!method_exists($this->incoming_record, 'get_SWSquad2'))
		{
			return '';
		}

		$SWSquad2 = $this->incoming_record->get_SWSquad2();
		$where_fragment = '';
		if(!empty($SWSquad2))
		{
			$SWSquad2 = (array)$SWSquad2;
			foreach($SWSquad2 as $key => $id)
			{
				$SWSquad2[$key] = $this->db->quote($id);
			}
			$where_fragment = " and SWSquad2 in (".implode(',',$SWSquad2).")";
		}
		return $where_fragment;
	}


	/**
		build snippet of where clause for either squad
	**/
	protected function where_clause_either_squad()
	{
		if(!method_exists($this->incoming_record, 'get_either_squad'))
		{
			return '';
		}

		$squad = $this->incoming_record->get_either_squad();
		$where_fragment = '';
		if(!empty($squad))
		{
			$squad = (array)$squad;
			foreach($squad as $key => $id)
			{
				$squad[$key] = $this->db->quote($id);
			}
			$where_fragment .= " and ( SWSquad1 in (".implode(',',$squad).")";
			$where_fragment .= " or SWSquad2 in (".implode(',',$squad).") )";
		}
		return $where_fragment;
	}


	/**
		build snippet of where clause for SWSector_ID
	**/
	protected function where_clause_SWSector_ID()
	{
		if(!method_exists($this->incoming_record, 'get_SWSector_ID'))
		{
			return '';
		}

		$SWSector_ID = $this->incoming_record->get_SWSector_ID();
		$where_fragment = '';
		if(!empty($SWSector_ID))
		{
			$SWSector_ID = (array)$SWSector_ID;
			foreach($SWSector_ID as $key => $id)
			{
				$SWSector_ID[$key] = $this->db->quote($id);
			}
			$where_fragment = " and SWSector_ID in (".implode(',',$SWSector_ID).")";
		}
		return $where_fragment;
	}


	/**
		build snippet of where clause for League_ID
	**/
	protected function where_clause_League_ID()
	{
		if(!method_exists($this->incoming_record, 'get_League_ID'))
		{
			return '';
		}

		$League_ID = $this->incoming_record->get_League_ID();
		$where_fragment = '';
		if($League_ID != '')
		{
			$where_fragment=" and League_ID = {$this->db->quote($League_ID)}";
		}
		return $where_fragment;
	}
}

?>

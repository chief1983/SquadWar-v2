<?php

class squad_data_matchhistory extends squad_data_base
{
	protected $table = 'match_history';
	protected $all_fields = array('MatchID', 'SWCode', 'SWSquad1', 'SWSquad2',
		'SWSector_ID', 'match_victor', 'match_loser', 'match_time',
		'League_ID', 'special');
	protected $primary_fields = array('MatchID');
	protected $detail_record = 'squad_record_matchhistory_detail';

	public function get($id)
	{
		$sql = "
			select ".implode(', ', $this->all_fields)."
			from `".$this->database."`.`".$this->table."`
			where MatchID = {$this->db->quote($id)}
		";

		return $this->exec_sql_return_record($sql, $this->detail_record);
	}

	public function search(squad_record_matchhistory_search $record)
	{
		return parent::search($record);
	}

	/**
		call the appropriate method based on whether we have an id.
	**/
	public function save(squad_record_matchhistory_detail $record)
	{
		return parent::save_child($record);
	}


	public function create(squad_record_matchhistory_detail $record)
	{
		return parent::create($record, 'status');
	}


	/**
		update table
	**/
	protected function update(squad_record_matchhistory_detail $record)
	{
		return parent::update($record);
	}


	public function delete(squad_record_matchhistory_detail $record)
	{
		return parent::delete($record);
	}


	/**
		build where clause.
	**/
	protected function build_where_clause()
	{
		$where_clause = "";
		$where_clause .= $this->where_clause_MatchID();
		$where_clause .= $this->where_clause_SWCode();
		$where_clause .= $this->where_clause_SWSquad1();
		$where_clause .= $this->where_clause_SWSquad2();
		$where_clause .= $this->where_clause_match_victor();
		$where_clause .= $this->where_clause_match_loser();
		$where_clause .= $this->where_clause_League_ID();
		$where_clause .= $this->where_clause_special();
		$where_clause .= $this->where_clause_match_time_oldest();
		$where_clause .= $this->where_clause_match_time_newest();

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
		build snippet of where clause for match_victor
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
		if(!empty($SWCode))
		{
			$where_fragment = " and SWCode = {$this->db->quote($SWCode)}";
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
			$where_fragment = " and SWSquad1 = {$this->db->quote($SWSquad1)}";
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
			$where_fragment = " and SWSquad2 = {$this->db->quote($SWSquad2)}";
		}
		return $where_fragment;
	}


	/**
		build snippet of where clause for match_victor
	**/
	protected function where_clause_match_victor()
	{
		if(!method_exists($this->incoming_record, 'get_match_victor'))
		{
			return '';
		}
		$match_victor = $this->incoming_record->get_match_victor();
		$where_fragment = '';
		if(!empty($match_victor))
		{
			$match_victor = (array)$match_victor;
			foreach($match_victor as $key => $id)
			{
				$match_victor[$key] = $this->db->quote($id);
			}
			$where_fragment = " and match_victor in (".implode(',',$match_victor).")";
		}
		return $where_fragment;
	}


	/**
		build snippet of where clause for match_loser
	**/
	protected function where_clause_match_loser()
	{
		if(!method_exists($this->incoming_record, 'get_match_loser'))
		{
			return '';
		}
		$match_loser = $this->incoming_record->get_match_loser();
		$where_fragment = '';
		if(!empty($match_loser))
		{
			$match_loser = (array)$match_loser;
			foreach($match_loser as $key => $id)
			{
				$match_loser[$key] = $this->db->quote($id);
			}
			$where_fragment = " and match_loser in (".implode(',',$match_loser).")";
		}
		return $where_fragment;
	}


	/**
		build snippet of where clause for either_squad
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
			$match_loser = (array)$squad;
			foreach($squad as $key => $id)
			{
				$squad[$key] = $this->db->quote($id);
			}
			$where_fragment .= " and ( match_victor in (".implode(',',$squad).")";
			$where_fragment .= " or match_loser in (".implode(',',$squad).") )";
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
		if(!empty($League_ID))
		{
			$where_fragment = " and League_ID = {$this->db->quote($League_ID)}";
		}
		return $where_fragment;
	}


	/**
		build snippet of where clause for special
	**/
	protected function where_clause_special()
	{
		if(!method_exists($this->incoming_record, 'get_special'))
		{
			return '';
		}
		$special = $this->incoming_record->get_special();
		$where_fragment = '';
		if(!is_null($special))
		{
			$where_fragment = " and special = {$this->db->quote($special)}";
		}
		return $where_fragment;
	}


	/**
		build snippet of where clause for match_time_oldest
	**/
	protected function where_clause_match_time_oldest()
	{
		if(!method_exists($this->incoming_record, 'get_match_time_oldest'))
		{
			return '';
		}
		$match_time_oldest = $this->incoming_record->get_match_time_oldest();
		$where_fragment = '';
		if(!is_null($match_time_oldest))
		{
			$where_fragment = " and match_time > {$this->db->quote($match_time_oldest)}";
		}
		return $where_fragment;
	}


	/**
		build snippet of where clause for match_time_newest
	**/
	protected function where_clause_match_time_newest()
	{
		if(!method_exists($this->incoming_record, 'get_match_time_newest'))
		{
			return '';
		}
		$match_time_newest = $this->incoming_record->get_match_time_newest();
		$where_fragment = '';
		if(!is_null($match_time_newest))
		{
			$where_fragment = " and match_time < {$this->db->quote($match_time_newest)}";
		}
		return $where_fragment;
	}

}

?>

<?php

class match_data_info extends match_data_base
{
	protected $table = 'SWMatches_Info';
	protected $abbr = 'mi';
	protected $all_fields = array('mi.MatchID','mi.SWCode','mi.match_time1',
		'mi.match_time2','mi.proposed_final_time','mi.proposed_alternate_time',
		'mi.squad_last_proposed','mi.final_match_time','mi.time_created',
		'mi.Mission','mi.Pilots','mi.AI','mi.dispute','mi.status_last_changed',
		'mi.swsquad1_reports_noshow','mi.swsquad1_noshow_datetime',
		'mi.swsquad2_reports_noshow','mi.swsquad2_noshow_datetime',
		'mi.swsquad1_protest','mi.swsquad1_protest_datetime',
		'mi.swsquad2_protest','mi.swsquad2_protest_datetime','mi.mail_sent');
	protected $primary_fields = array('mi.MatchID');
	protected $detail_record = 'match_record_info_detail';

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
			from `".$this->database."`.`".$this->table."`
			where SWCode = {$this->db->quote($SWCode)}
		";

		return $this->exec_sql_return_record($sql, $this->detail_record);
	}

	public function search(match_record_info_search $record)
	{
		return parent::search($record);
	}

	/**
		call the appropriate method based on whether we have an id.
	**/
	public function create(match_record_info_detail $record)
	{
		return parent::create($record, 'status');
	}


	/**
		update table
	**/
	protected function update(match_record_info_detail $record)
	{
		return parent::update($record);
	}


	public function delete(match_record_info_detail $record)
	{
		return parent::delete($record);
	}


	/**
		call the appropriate method based on whether we have an id.
	**/
	public function save(match_record_info_detail $record)
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
				if($status)
				{
					return $id;
				}
				else
				{
					return false;
				}
			}
		}
	}


	/**
		build where clause.
	**/
	protected function build_where_clause()
	{
		$where_clause = "";
		$where_clause .= $this->where_clause_MatchID();
		$where_clause .= $this->where_clause_SWCode();
		$where_clause .= $this->where_clause_Mission();

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
		build snippet of where clause for Mission
	**/
	protected function where_clause_Mission()
	{
		if(!method_exists($this->incoming_record, 'get_Mission'))
		{
			return '';
		}
		$Mission = $this->incoming_record->get_Mission();
		$where_fragment = '';
		if(!empty($Mission))
		{
			$where_fragment = " and Mission = {$this->db->quote($Mission)}";
		}
		return $where_fragment;
	}

}

?>

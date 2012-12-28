<?php

class squad_data_sector extends squad_data_base
{
	protected $table = 'SWSectors';
	protected $all_fields = array('SWSectors_ID', 'SectorName', 'SectorSquad',
		'SectorTime', 'Entry_Node', 'League_ID', 'Value', 'Graph', 'Active');
	protected $primary_fields = array('SWSectors_ID');
	protected $detail_record = 'squad_record_sector_detail';

	public function __construct()
	{
		parent::__construct();
	}

	public function get($id)
	{
		$sql = "
			select ".implode(', ', $this->all_fields)."
			from `".$this->database."`.`".$this->table."`
			where SWSectors_ID = {$this->db->quote($id)}
		";

		return $this->exec_sql_return_record($sql, $this->detail_record);
	}

	public function search(squad_record_sector_search $record)
	{
		return parent::search($record);
	}

	/**
		call the appropriate method based on whether we have an id.
	**/
	public function save(squad_record_sector_detail $record)
	{
		return parent::save($record);
	}


	public function create(squad_record_sector_detail $record)
	{
		return parent::create($record);
	}


	/**
		update table
	**/
	protected function update(squad_record_sector_detail $record)
	{
		return parent::update($record);
	}


	public function delete(squad_record_sector_detail $record)
	{
		return parent::delete($record);
	}


	/**
		build where clause.
	**/
	protected function build_where_clause()
	{
		$where_clause = "";
		$where_clause .= $this->where_clause_SWSectors_ID();
		$where_clause .= $this->where_clause_SectorName();
		$where_clause .= $this->where_clause_SectorSquad();
		$where_clause .= $this->where_clause_SectorSquad_not();
		$where_clause .= $this->where_clause_Entry_Node();
		$where_clause .= $this->where_clause_League_ID();
		$where_clause .= $this->where_clause_Active();

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
		build snippet of where clause for SWSectors_ID
	**/
	protected function where_clause_SWSectors_ID()
	{
		if(!method_exists($this->incoming_record, 'get_SWSectors_ID'))
		{
			return '';
		}
		$SWSectors_ID = $this->incoming_record->get_SWSectors_ID();
		$where_fragment = '';
		if(!empty($SWSectors_ID))
		{
			$SWSectors_ID = (array)$SWSectors_ID;
			foreach($SWSectors_ID as $key => $id)
			{
				$SWSectors_ID[$key] = $this->db->quote($id);
			}
			$where_fragment = " and SWSectors_ID in (".implode(',',$SWSectors_ID).")";
		}
		return $where_fragment;
	}


	/**
		build snippet of where clause for SectorName
	**/
	protected function where_clause_SectorName()
	{
		if(!method_exists($this->incoming_record, 'get_SectorName'))
		{
			return '';
		}
		$SectorName = $this->incoming_record->get_SectorName();
		$where_fragment = '';
		if(!empty($SectorName))
		{
			$where_fragment = " and SectorName = {$this->db->quote($SectorName)}";
		}
		return $where_fragment;
	}


	/**
		build snippet of where clause for SectorSquad
	**/
	protected function where_clause_SectorSquad()
	{
		if(!method_exists($this->incoming_record, 'get_SectorSquad'))
		{
			return '';
		}
		$SectorSquad = $this->incoming_record->get_SectorSquad();
		$where_fragment = '';
		if(!is_null($SectorSquad))
		{
			$SectorSquad = (array)$SectorSquad;
			foreach($SectorSquad as $key => $id)
			{
				$SectorSquad[$key] = $this->db->quote($id);
			}
			$where_fragment = " and SectorSquad in (".implode(',',$SectorSquad).")";
		}
		return $where_fragment;
	}


	/**
		build snippet of where clause for SectorSquad_not
	**/
	protected function where_clause_SectorSquad_not()
	{
		if(!method_exists($this->incoming_record, 'get_SectorSquad_not'))
		{
			return '';
		}
		$SectorSquad_not = $this->incoming_record->get_SectorSquad_not();
		$where_fragment = '';
		if(!is_null($SectorSquad_not))
		{
			$SectorSquad_not = (array)$SectorSquad_not;
			foreach($SectorSquad_not as $key => $id)
			{
				$SectorSquad_not[$key] = $this->db->quote($id);
			}
			$where_fragment = " and SectorSquad not in (".implode(',',$SectorSquad_not).")";
		}
		return $where_fragment;
	}


	/**
		build snippet of where clause for Entry_Node
	**/
	protected function where_clause_Entry_Node()
	{
		if(!method_exists($this->incoming_record, 'get_Entry_Node'))
		{
			return '';
		}
		$Entry_Node = $this->incoming_record->get_Entry_Node();
		$where_fragment = '';
		if(!is_null($Entry_Node))
		{
			$where_fragment = " and Entry_Node = {$this->db->quote($Entry_Node)}";
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
		if(!empty($Active))
		{
			$where_fragment = " and Active = {$this->db->quote($Active)}";
		}
		return $where_fragment;
	}

}

?>

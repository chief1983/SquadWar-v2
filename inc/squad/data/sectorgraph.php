<?php

class squad_data_sectorgraph extends squad_data_base
{
	protected $table = 'squadwar.SWSectors_Graph';
	protected $all_fields = array('SWSectors_ID', 'path_1', 'path_2', 'path_3',
		'path_4', 'path_5');
	protected $primary_fields = array('SWSectors_ID');
	protected $detail_record = 'squad_record_sectorgraph_detail';

	public function __construct()
	{
		parent::__construct();
	}

	public function get($id)
	{
		$sql = "
			select ".implode(', ', $this->all_fields)."
			from ".$this->table."
			where SWSectors_ID = {$this->db->quote($id)}
		";

		return $this->exec_sql_return_record($sql, $this->detail_record);
	}

	public function search(squad_record_sectorgraph_search $record)
	{
		return parent::search($record);
	}

	/**
		call the appropriate method based on whether we have an id.
	**/
	public function save(squad_record_sectorgraph_detail $record)
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


	public function create(squad_record_sectorgraph_detail $record)
	{
		return parent::create($record);
	}


	/**
		update table
	**/
	protected function update(squad_record_sectorgraph_detail $record)
	{
		return parent::update($record);
	}


	public function delete(squad_record_sectorgraph_detail $record)
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
}

?>

<?php
/**
    this touches the main mission table

    it gets, searches, creates.
**/
class mission_data_main extends mission_data_base
{
	protected $tables = array();
	protected $all_fields = array('m.id', 'm.filename', 'm.name',
		'm.description', 'm.specifics', 'm.respawns', 'm.players');
	protected $primary_fields = array('m.id');
	protected $detail_record = 'mission_record_detail';

	public function search(mission_record_search $record)
	{
		return parent::search($record);
	}


	public function get_count(mission_record_search $record)
	{
		return parent::get_count($record);
	}


	/**
		call the appropriate method based on whether we have an id.
	**/
	public function save(mission_record_detail $record)
	{
		return parent::save($record);
	}


	/**
		insert into missions table
	**/
	protected function create(mission_record_detail $record)
	{
		return parent::create($record);
	}


	/**
		update missions table
	**/
	protected function update(mission_record_detail $record)
	{
		return parent::update($record);
	}

	public function delete(mission_record_detail $record)
	{
		return parent::delete($record);
	}


	protected function build_where_clause()
	{
		$sql = " where 1 = 1 ";
		$sql .= $this->where_clause_id();
		$sql .= $this->where_clause_filename();
		$sql .= $this->where_clause_name();
		return $sql;
	}


	/**
		build snippet of where clause for filename
	**/
	protected function where_clause_filename()
	{
		if(!method_exists($this->incoming_record, 'get_filename'))
		{
			return '';
		}

		$filename = $this->incoming_record->get_filename();
		$where_fragment = '';
		if($filename != '')
		{
			$where_fragment=" and m.filename = {$this->db->quote($filename)}";
		}
		return $where_fragment;
	}


	/**
		build snippet of where clause for name
	**/
	protected function where_clause_name()
	{
		if(!method_exists($this->incoming_record, 'get_name'))
		{
			return '';
		}

		$name = $this->incoming_record->get_name();
		$where_fragment = '';
		if($name != '')
		{
			$where_fragment=" and m.name = {$this->db->quote($name)}";
		}
		return $where_fragment;
	}


	/**
		build snippet of where clause for status, 1 is enabled, 0 is disabled currently
	**/
	protected function where_clause_status()
	{
		if(!method_exists($this->incoming_record, 'get_status'))
		{
			return '';
		}

		$banned = $this->incoming_record->get_status();
		$where_fragment = '';
		if(!is_null($status))
		{
			$where_fragment=" and m.status = {$this->db->quote($status)}";
		}
		return $where_fragment;
	}
}

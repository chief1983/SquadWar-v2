<?php

class mission_record_search extends base_recordsearch
{
	protected $id;
	protected $filename;
	protected $name;
	protected $status;

	public function get_id()
	{
		return $this->id;
	}
	public function set_id($val)
	{
		$this->id = $val;
	}

	public function get_filename()
	{
		return $this->filename;
	}
	public function set_filename($val)
	{
		$this->filename = $val;
	}

	public function get_name()
	{
		return $this->name;
	}
	public function set_name($val)
	{
		$this->name = $val;
	}

	public function get_status()
	{
		return $this->status;
	}
	public function set_status($val)
	{
		$this->status = $val;
	}
}

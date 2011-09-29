<?php

class mission_record_detail extends base_recorddetail
{
	/* each of these represent a field in the mission table. */
	protected $filename;
	protected $name;
	protected $description;
	protected $specifics;
	protected $respawns;
	protected $players;

	public function __construct()
	{
		parent::__construct();
		$this->datamodel_name = 'mission_data_main';
		$this->required_fields  = array(
			'filename','name'
		);
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

	public function get_description()
	{
		return $this->description;
	}
	public function set_description($val)
	{
		$this->description = $val;
	}

	public function get_specifics()
	{
		return $this->specifics;
	}
	public function set_specifics($val)
	{
		$this->specifics = $val;
	}

	public function get_respawns()
	{
		return $this->respawns;
	}
	public function set_respawns($val)
	{
		$this->respawns = $val;
	}

	public function get_players()
	{
		return $this->players;
	}
	public function set_players($val)
	{
		$this->players = $val;
	}
}
?>

<?php

class league_record_detail extends base_recorddetail
{
	/* each of these represent a field in the user table. */
	protected $League_ID;
	protected $Title;
	protected $Description;
	protected $Active;
	protected $Archived;
	protected $Closed;
	protected $challenged_max;
	protected $map_location;
	protected $map_graphics;

	/* these are not in the user table. */
	protected $Squads = array(); // Array of Squad detail records, populated upon instantiation

	public function __construct()
	{
		parent::__construct();
		$this->datamodel_name = 'league_data_main';
		$this->required_fields  = array(
			'Title'
		);

		//set timestamps to time this record was instantiated
		$this->created_time = $this->created;
		$this->modified_time = $this->created;
	}

	public function get_id()
	{
		return $this->League_ID;
	}
	public function set_id($val)
	{
		$this->League_ID = $val;
	}

	public function get_League_ID()
	{
		return $this->League_ID;
	}
	public function set_League_ID($val)
	{
		$this->League_ID = $val;
	}

	public function get_Title()
	{
		return $this->Title;
	}
	public function set_Title($val)
	{
		$this->Title = $val;
	}

	public function get_Description()
	{
		return $this->Description;
	}
	public function set_Description($val)
	{
		$this->Description = $val;
	}

	public function get_Active()
	{
		return $this->Active;
	}
	public function set_Active($val)
	{
		$this->Active = $val;
	}

	public function get_Archived()
	{
		return $this->Archived;
	}
	public function set_Archived($val)
	{
		$this->Archived = $val;
	}

	public function get_Closed()
	{
		return $this->Closed;
	}
	public function set_Closed($val)
	{
		$this->Closed = $val;
	}

	public function get_challenged_max()
	{
		return $this->challenged_max;
	}
	public function set_challenged_max($val)
	{
		$this->challenged_max = $val;
	}

	public function get_map_location()
	{
		return $this->map_location;
	}
	public function set_map_location($val)
	{
		$this->map_location = $val;
	}

	public function get_map_graphics()
	{
		return $this->map_graphics;
	}
	public function set_map_graphics($val)
	{
		$this->map_graphics = $val;
	}

	public function get_Squads()
	{
		return $this->Squads;
	}
	public function set_Squads($val)
	{
		$this->Squads = $val;
	}
}
?>

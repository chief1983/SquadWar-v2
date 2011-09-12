<?php

class league_record_search extends base_recordsearch
{
	protected $League_ID;
	protected $Title;
	protected $Description;
	protected $Active;
	protected $Archived;
	protected $Closed;
	protected $challenged_max;
	protected $map_location;
	protected $map_graphics;
	protected $squad;

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

	public function get_squad()
	{
		return $this->squad;
	}
	public function set_squad($val)
	{
		$this->squad = $val;
	}
}

?>

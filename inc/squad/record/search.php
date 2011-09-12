<?php

class squad_record_search extends base_recordsearch
{
	protected $SquadID;
	protected $SquadName;
	protected $Active;
	protected $suspended;
	protected $league;
	protected $has_sectors;


	public function get_SquadID()
	{
		return $this->SquadID;
	}
	public function set_SquadID($val)
	{
		$this->SquadID = $val;
	}

	public function get_SquadName()
	{
		return $this->SquadName;
	}
	public function set_SquadName($val)
	{
		$this->SquadName = $val;
	}

	public function get_Active()
	{
		return $this->Active;
	}
	public function set_Active($val)
	{
		$this->Active = $val;
	}

	public function get_suspended()
	{
		return $this->suspended;
	}
	public function set_suspended($val)
	{
		$this->suspended = $val;
	}

	public function get_league()
	{
		return $this->league;
	}
	public function set_league($val)
	{
		$this->league = $val;
	}

	public function get_has_sectors()
	{
		return $this->has_sectors;
	}
	public function set_has_sectors($val)
	{
		$this->has_sectors = $val;
	}
}

?>

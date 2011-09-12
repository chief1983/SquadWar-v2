<?php

class fsopilot_record_swpilot_search extends base_recordsearch
{
	protected $PilotID;
	protected $TrackerID;
	protected $Pilot_Name;
	protected $Recruitme;


	public function get_id()
	{
		return $this->PilotID;
	}
	public function set_id($val)
	{
		$this->PilotID = $val;
	}

	public function get_PilotID()
	{
		return $this->PilotID;
	}
	public function set_PilotID($val)
	{
		$this->PilotID = $val;
	}

	public function get_TrackerID()
	{
		return $this->TrackerID;
	}
	public function set_TrackerID($val)
	{
		$this->TrackerID = $val;
	}

	public function get_Pilot_Name()
	{
		return $this->Pilot_Name;
	}
	public function set_Pilot_Name($val)
	{
		$this->Pilot_Name = $val;
	}

	public function get_Recruitme()
	{
		return $this->Recruitme;
	}
	public function set_Recruitme($val)
	{
		$this->Recruitme = $val;
	}
}

?>

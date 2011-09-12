<?php

class fsopilot_record_search extends base_recordsearch
{
	protected $TrackerID;
	protected $Pilot;
	protected $Recruitme;


	public function get_TrackerID()
	{
		return $this->TrackerID;
	}
	public function set_TrackerID($val)
	{
		$this->TrackerID = $val;
	}

	public function get_Pilot()
	{
		return $this->Pilot;
	}
	public function set_Pilot($val)
	{
		$this->Pilot = $val;
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

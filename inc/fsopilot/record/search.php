<?php

class fsopilot_record_search extends base_recordsearch
{
	protected $id;
	protected $user_id;
	protected $pilot_name;
	protected $Recruitme;


	public function get_id()
	{
		return $this->id;
	}
	public function set_id($val)
	{
		$this->id = $val;
	}

	public function get_user_id()
	{
		return $this->user_id;
	}
	public function set_user_id($val)
	{
		$this->user_id = $val;
	}

	public function get_pilot_name()
	{
		return $this->pilot_name;
	}
	public function set_pilot_name($val)
	{
		$this->pilot_name = $val;
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

<?php

class user_record_search extends base_recordsearch
{
	protected $TrackerID;
	protected $Login;
	protected $email;
	protected $firstname;
	protected $lastname;
	protected $Validated;
	protected $id_hash;


	public function get_id()
	{
		return $this->TrackerID;
	}
	public function set_id($val)
	{
		$this->TrackerID = $val;
	}
	
	public function get_TrackerID()
	{
		return $this->TrackerID;
	}
	public function set_TrackerID($val)
	{
		$this->TrackerID = $val;
	}
	
	public function get_Login()
	{
		return $this->Login;
	}
	public function set_Login($val)
	{
		$this->Login = $val;
	}
	
	public function get_email()
	{
		return $this->email;
	}
	public function set_email($val)
	{
		$this->email = $val;
	}
	
	public function get_firstname()
	{
		return $this->firstname;
	}
	public function set_firstname($val)
	{
		$this->firstname = $val;
	}

	public function get_lastname()
	{
		return $this->lastname;
	}
	public function set_lastname($val)
	{
		$this->lastname = $val;
	}

	public function get_Validated()
	{
		return $this->Validated;
	}
	public function set_Validated($val)
	{
		$this->Validated = $val;
	}

	public function get_id_hash()
	{
		return $this->id_hash;
	}
	public function set_id_hash($val)
	{
		$this->id_hash = $val;
	}
}

?>

<?php

class user_record_search extends base_recordsearch
{
	protected $id;
	protected $user_name;
	protected $email;
	protected $banned;


	public function get_id()
	{
		return $this->id;
	}
	public function set_id($val)
	{
		$this->id = $val;
	}

	public function get_user_name()
	{
		return $this->user_name;
	}
	public function set_user_name($val)
	{
		$this->user_name = $val;
	}

	public function get_email()
	{
		return $this->email;
	}
	public function set_email($val)
	{
		$this->email = $val;
	}

	public function get_banned()
	{
		return $this->banned;
	}
	public function set_banned($val)
	{
		$this->banned = $val;
	}
}

?>

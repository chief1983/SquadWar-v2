<?php

class match_record_search extends base_recordsearch
{
	protected $SWCode;
	protected $SWSquad1;
	protected $SWSquad2;
	protected $either_squad;
	protected $SWSector_ID;
	protected $League_ID;


	public function get_SWCode()
	{
		return $this->SWCode;
	}
	public function set_SWCode($val)
	{
		$this->SWCode = $val;
	}

	public function get_SWSquad1()
	{
		return $this->SWSquad1;
	}
	public function set_SWSquad1($val)
	{
		$this->SWSquad1 = $val;
	}

	public function get_SWSquad2()
	{
		return $this->SWSquad2;
	}
	public function set_SWSquad2($val)
	{
		$this->SWSquad2 = $val;
	}

	public function get_either_squad()
	{
		return $this->either_squad;
	}
	public function set_either_squad($val)
	{
		$this->either_squad = $val;
	}

	public function get_SWSector_ID()
	{
		return $this->SWSector_ID;
	}
	public function set_SWSector_ID($val)
	{
		$this->SWSector_ID = $val;
	}

	public function get_League_ID()
	{
		return $this->League_ID;
	}
	public function set_League_ID($val)
	{
		$this->League_ID = $val;
	}
}

?>

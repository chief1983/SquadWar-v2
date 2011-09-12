<?php
class match_record_info_search extends base_recordsearch
{
	protected $MatchID;
	protected $SWCode;
	protected $Mission;

	public function set_id($value)
	{
		$this->MatchID = $value;
	}
	public function get_id()
	{
		return $this->MatchID;
	}

	public function set_MatchID($value)
	{
		$this->MatchID = $value;
	}
	public function get_MatchID()
	{
		return $this->MatchID;
	}

	public function set_SWCode($value)
	{
		$this->SWCode = $value;
	}
	public function get_SWCode()
	{
		return $this->SWCode;
	}

	public function set_Mission($value)
	{
		$this->Mission = $value;
	}
	public function get_Mission()
	{
		return $this->Mission;
	}
}
?>

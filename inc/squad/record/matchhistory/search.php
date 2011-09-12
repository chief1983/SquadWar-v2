<?php
class squad_record_matchhistory_search extends base_recordsearch
{
	protected $MatchID;
	protected $SWCode;
	protected $SWSquad1;
	protected $SWSquad2;
	protected $match_victor;
	protected $match_loser;
	protected $either_squad;
	protected $League_ID;
	protected $special;
	protected $match_time_oldest;
	protected $match_time_newest;

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

	public function set_SWSquad1($value)
	{
		$this->SWSquad1 = $value;
	}
	public function get_SWSquad1()
	{
		return $this->SWSquad1;
	}

	public function set_SWSquad2($value)
	{
		$this->SWSquad2 = $value;
	}
	public function get_SWSquad2()
	{
		return $this->SWSquad2;
	}

	public function set_match_victor($value)
	{
		$this->match_victor = $value;
	}
	public function get_match_victor()
	{
		return $this->match_victor;
	}

	public function set_match_loser($value)
	{
		$this->match_loser = $value;
	}
	public function get_match_loser()
	{
		return $this->match_loser;
	}

	public function get_either_squad()
	{
		return explode(',', $this->either_squad);
	}
	public function set_either_squad($val)
	{
		$this->either_squad = $val;
	}

	public function set_League_ID($value)
	{
		$this->League_ID = $value;
	}
	public function get_League_ID()
	{
		return $this->League_ID;
	}

	public function set_special($value)
	{
		$this->special = $value;
	}
	public function get_special()
	{
		return $this->special;
	}

	public function set_match_time_oldest($value)
	{
		$this->match_time_oldest = $value;
	}
	public function get_match_time_oldest()
	{
		return $this->match_time_oldest;
	}

	public function set_match_time_newest($value)
	{
		$this->match_time_newest = $value;
	}
	public function get_match_time_newest()
	{
		return $this->match_time_newest;
	}
}

?>

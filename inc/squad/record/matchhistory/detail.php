<?php

class squad_record_matchhistory_detail extends base_recorddetail
{
	protected $MatchID;
	protected $SWCode;
	protected $SWSquad1;
	protected $SWSquad2;
	protected $SWSector_ID;
	protected $match_victor;
	protected $match_loser;
	protected $match_time;
	protected $League_ID;
	protected $special;

	protected $Squad1;
	protected $Squad2;
	protected $SWSector;
	protected $info;

	public function __construct()
	{
		parent::__construct();
		$this->datamodel_name  = 'squad_data_matchhistory';
		$this->required_fields  = array(
			'MatchID','SWCode','SWSquad1','SWSquad2','SWSector_ID',
			'match_victor','match_loser','match_time','League_ID','special'
		);

		//set timestamps to time this record was instantiated
		$this->time_registered = $this->created;
	}

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

	public function set_SWSector_ID($value)
	{
		$this->SWSector_ID = $value;
	}
	public function get_SWSector_ID()
	{
		return $this->SWSector_ID;
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

	public function set_match_time($value)
	{
		$this->match_time = $value;
	}
	public function get_match_time()
	{
		return $this->match_time;
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

	/* These are populated by the API, not in the match_history table. */
	public function set_Squad1($value)
	{
		$this->Squad1 = $value;
	}
	public function get_Squad1()
	{
		return $this->Squad1;
	}

	public function set_Squad2($value)
	{
		$this->Squad2 = $value;
	}
	public function get_Squad2()
	{
		return $this->Squad2;
	}

	public function set_SWSector($value)
	{
		$this->SWSector = $value;
	}
	public function get_SWSector()
	{
		return $this->SWSector;
	}

	public function set_info($value)
	{
		$this->info = $value;
	}
	public function get_info()
	{
		return $this->info;
	}
}
?>

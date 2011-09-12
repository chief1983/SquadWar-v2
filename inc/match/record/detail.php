<?php

class match_record_detail extends base_recorddetail
{
	/* each of these represent a field in the match table. */
	protected $MatchID;
	protected $SWCode;
	protected $SWSquad1;
	protected $SWSquad2;
	protected $SWSector_ID;
	protected $SWDeleteTime;
	protected $League_ID;

	/* these are not in the match table. */
	protected $info;
	protected $league;
	protected $SWSector;
	protected $Challenger;
	protected $Challenged;

	public function __construct()
	{
		parent::__construct();
		$this->datamodel_name = 'match_data_main';
		$this->required_fields  = array(
			'SWCode','SWSquad1','SWSquad2','SWSector_ID','League_ID'
		);

		//set timestamps to time this record was instantiated
		$this->created_time = $this->created;
		$this->modified_time = $this->created;
	}

	public function get_id()
	{
		return $this->MatchID;
	}
	public function set_id($val)
	{
		$this->MatchID = $val;
	}

	public function get_MatchID()
	{
		return $this->MatchID;
	}
	public function set_MatchID($val)
	{
		$this->MatchID = $val;
	}

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

	public function get_SWSector_ID()
	{
		return $this->SWSector_ID;
	}
	public function set_SWSector_ID($val)
	{
		$this->SWSector_ID = $val;
	}

	public function get_SWDeleteTime()
	{
		return $this->SWDeleteTime;
	}
	public function set_SWDeleteTime($val)
	{
		$this->SWDeleteTime = $val;
	}

	public function get_League_ID()
	{
		return $this->League_ID;
	}
	public function set_League_ID($val)
	{
		$this->League_ID = $val;
	}

	public function get_info()
	{
		return $this->info;
	}
	public function set_info($val)
	{
		$this->info = $val;
	}

	public function get_league()
	{
		return $this->league;
	}
	public function set_league($val)
	{
		$this->league = $val;
	}

	public function get_SWSector()
	{
		return $this->SWSector;
	}
	public function set_SWSector($val)
	{
		$this->SWSector = $val;
	}

	public function get_Challenger()
	{
		return $this->Challenger;
	}
	public function set_Challenger($val)
	{
		$this->Challenger = $val;
	}

	public function get_Challenged()
	{
		return $this->Challenged;
	}
	public function set_Challenged($val)
	{
		$this->Challenged = $val;
	}

	public function validate()
	{
		$valid = parent::validate();

		//check to make sure email address isn't duplicate.
		$match = match_api::get_by_SWCode($this->SWCode);
		//is this user record's ID the same as the one we found for the email?
		if($match && $this->get_id() != $match->get_id())
		{
			$valid = false;
			$this->throw_notice('code is being used already.');
		}

		return $valid;
	}
}
?>

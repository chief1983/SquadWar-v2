<?php

class squad_record_info_detail extends base_recorddetail
{
	protected $SquadID;
	protected $Squad_Leader_ID;
	protected $Squad_Leader_ICQ;
	protected $Squad_IRC;
	protected $Squad_Email;
	protected $Squad_Join_PW;
	protected $Squad_Logo;
	protected $Approved;
	protected $Rest;
	protected $time_registered;
	protected $Squad_Red;
	protected $Squad_Green;
	protected $Squad_Blue;
	protected $Squad_Time_Zone;
	protected $Squad_Web_Link;
	protected $Squad_Statement;
	protected $Abbrv;
	protected $ribbon_1;
	protected $ribbon_2;
	protected $ribbon_3;
	protected $ribbon_4;
	protected $ribbon_5;
	protected $ribbon_6;
	protected $medal_1;
	protected $medal_2;
	protected $medal_3;
	protected $suspended;
	protected $win_loss;
	protected $power_rating;

	public function __construct()
	{
		parent::__construct();
		$this->datamodel_name  = 'squad_data_info';
		$this->required_fields  = array(
			'SquadID','Squad_Leader_ID','Squad_Email','Squad_Join_PW',
			'time_registered','Squad_Time_Zone'
		);

		//set timestamps to time this record was instantiated
		$this->time_registered = $this->created;
	}

	public function get_id()
	{
		return $this->SquadID;
	}
	public function set_id($val)
	{
		$this->SquadID = $val;
	}

	public function get_SquadID()
	{
		return $this->SquadID;
	}
	public function set_SquadID($val)
	{
		$this->SquadID = $val;
	}

	public function get_Squad_Leader_ID()
	{
		return $this->Squad_Leader_ID;
	}
	public function set_Squad_Leader_ID($val)
	{
		$this->Squad_Leader_ID = $val;
	}

	public function get_Squad_Leader_ICQ()
	{
		return $this->Squad_Leader_ICQ;
	}
	public function set_Squad_Leader_ICQ($val)
	{
		$this->Squad_Leader_ICQ = $val;
	}

	public function get_Squad_IRC()
	{
		return $this->Squad_IRC;
	}
	public function set_Squad_IRC($val)
	{
		$this->Squad_IRC = $val;
	}

	public function get_Squad_Email()
	{
		return $this->Squad_Email;
	}
	public function set_Squad_Email($val)
	{
		$this->Squad_Email = $val;
	}

	public function get_Squad_Join_PW()
	{
		return $this->Squad_Join_PW;
	}
	public function set_Squad_Join_PW($val)
	{
		$this->Squad_Join_PW = $val;
	}

	public function get_Squad_Logo()
	{
		return $this->Squad_Logo;
	}
	public function set_Squad_Logo($val)
	{
		$this->Squad_Logo = $val;
	}

	public function get_Approved()
	{
		return $this->Approved;
	}
	public function set_Approved($val)
	{
		$this->Approved = $val;
	}

	public function get_Rest()
	{
		return $this->Rest;
	}
	public function set_Rest($val)
	{
		$this->Rest = $val;
	}

	public function get_time_registered()
	{
		return $this->time_registered;
	}
	public function set_time_registered($val)
	{
		$this->time_registered = $val;
	}

	public function get_Squad_Red()
	{
		return $this->Squad_Red;
	}
	public function set_Squad_Red($val)
	{
		$this->Squad_Red = $val;
	}

	public function get_Squad_Green()
	{
		return $this->Squad_Green;
	}
	public function set_Squad_Green($val)
	{
		$this->Squad_Green = $val;
	}

	public function get_Squad_Blue()
	{
		return $this->Squad_Blue;
	}
	public function set_Squad_Blue($val)
	{
		$this->Squad_Blue = $val;
	}

	public function get_teamcolor()
	{
		if($this->get_Squad_Red() == '' || $this->get_Squad_Green() == '' || $this->get_Squad_Blue() == '')
		{
			$teamcolor = '000000';
		}
		else
		{
			$teamcolor = util::colorstring($this->get_Squad_Red(), $this->get_Squad_Green(), $this->get_Squad_Blue());
		}
		return $teamcolor;
	}

	public function get_Squad_Time_Zone()
	{
		return $this->Squad_Time_Zone;
	}
	public function set_Squad_Time_Zone($val)
	{
		$this->Squad_Time_Zone = $val;
	}

	public function get_Squad_Web_Link()
	{
		return $this->Squad_Web_Link;
	}
	public function set_Squad_Web_Link($val)
	{
		$this->Squad_Web_Link = $val;
	}

	public function get_Squad_Statement()
	{
		return $this->Squad_Statement;
	}
	public function set_Squad_Statement($val)
	{
		$this->Squad_Statement = $val;
	}

	public function get_Abbrv()
	{
		return $this->Abbrv;
	}
	public function set_Abbrv($val)
	{
		$this->Abbrv = $val;
	}

	public function get_ribbon_1()
	{
		return $this->ribbon_1;
	}
	public function set_ribbon_1($val)
	{
		$this->ribbon_1 = $val;
	}

	public function get_ribbon_2()
	{
		return $this->ribbon_2;
	}
	public function set_ribbon_2($val)
	{
		$this->ribbon_2 = $val;
	}

	public function get_ribbon_3()
	{
		return $this->ribbon_3;
	}
	public function set_ribbon_3($val)
	{
		$this->ribbon_3 = $val;
	}

	public function get_ribbon_4()
	{
		return $this->ribbon_4;
	}
	public function set_ribbon_4($val)
	{
		$this->ribbon_4 = $val;
	}

	public function get_ribbon_5()
	{
		return $this->ribbon_5;
	}
	public function set_ribbon_5($val)
	{
		$this->ribbon_5 = $val;
	}

	public function get_ribbon_6()
	{
		return $this->ribbon_6;
	}
	public function set_ribbon_6($val)
	{
		$this->ribbon_6 = $val;
	}

	public function get_medal_1()
	{
		return $this->medal_1;
	}
	public function set_medal_1($val)
	{
		$this->medal_1 = $val;
	}

	public function get_medal_2()
	{
		return $this->medal_2;
	}
	public function set_medal_2($val)
	{
		$this->medal_2 = $val;
	}

		public function get_medal_3()
	{
		return $this->medal_3;
	}
	public function set_medal_3($val)
	{
		$this->medal_3 = $val;
	}

	public function get_suspended()
	{
		return $this->suspended;
	}
	public function set_suspended($val)
	{
		$this->suspended = $val;
	}

	public function get_win_loss()
	{
		return $this->win_loss;
	}
	public function set_win_loss($val)
	{
		$this->win_loss = $val;
	}

	public function get_power_rating()
	{
		return $this->power_rating;
	}
	public function set_power_rating($val)
	{
		$this->power_rating = $val;
	}
}
?>

<?php

class squad_record_detail extends base_recorddetail
{
	/* each of these represent a field in the squad table. */
	protected $SquadID;
	protected $SquadName;
	protected $SquadPassword;
	protected $SquadMembers;
	protected $Active;

	/* these are not in the squad table. */
	protected $last_activity;
	protected $info;
	protected $matches_won;
	protected $matches_lost;
	protected $l_matches_won;
	protected $l_matches_lost;
	protected $matches_challenger;
	protected $matches_defender;
	protected $l_matches_challenger;
	protected $l_matches_defender;
	protected $league;
	protected $sectors;
	protected $l_sectors;

	public function __construct()
	{
		parent::__construct();
		$this->datamodel_name = 'squad_data_main';
		$this->required_fields  = array(
			'SquadName','SquadPassword','SquadMembers'
		);

		//set timestamps to time this record was instantiated
		$this->created_time = $this->created;
		$this->modified_time = $this->created;
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

	public function get_SquadName()
	{
		return $this->SquadName;
	}
	public function set_SquadName($val)
	{
		$this->SquadName = $val;
	}

	public function get_SquadPassword()
	{
		return $this->SquadPassword;
	}
	public function set_SquadPassword($val)
	{
		$this->SquadPassword = $val;
	}
	public function change_password($new_pw)
	{
		$model = new squad_data_main();
		return $model->change_password($this, $new_pw);
	}

	public function get_SquadMembers()
	{
		return explode(',', $this->SquadMembers);
	}
	public function set_SquadMembers($val)
	{
		if(is_array($val))
		{
			$this->SquadMembers = implode(',', $val);
		}
		else
		{
			$this->SquadMembers = "{$val}";
		}
	}

	public function get_Active()
	{
		return $this->Active;
	}
	public function set_Active($val)
	{
		$this->Active = $val;
	}

	public function get_info()
	{
		return $this->info;
	}
	public function set_info($val)
	{
		$this->info = $val;
	}

	public function get_matches_won()
	{
		return $this->matches_won;
	}
	public function set_matches_won($val)
	{
		$this->matches_won = $val;
	}

	public function get_matches_lost()
	{
		return $this->matches_lost;
	}
	public function set_matches_lost($val)
	{
		$this->matches_lost = $val;
	}

	public function get_l_matches_won()
	{
		return $this->l_matches_won;
	}
	public function set_l_matches_won($val)
	{
		$this->l_matches_won = $val;
	}

	public function get_l_matches_lost()
	{
		return $this->l_matches_lost;
	}
	public function set_l_matches_lost($val)
	{
		$this->l_matches_lost = $val;
	}

	public function get_matches_challenger()
	{
		return $this->matches_challenger;
	}
	public function set_matches_challenger($val)
	{
		$this->matches_challenger = $val;
	}

	public function get_matches_defender()
	{
		return $this->matches_defender;
	}
	public function set_matches_defender($val)
	{
		$this->matches_defender = $val;
	}

	public function get_l_matches_challenger()
	{
		return $this->l_matches_challenger;
	}
	public function set_l_matches_challenger($val)
	{
		$this->l_matches_challenger = $val;
	}

	public function get_l_matches_defender()
	{
		return $this->l_matches_defender;
	}
	public function set_l_matches_defender($val)
	{
		$this->l_matches_defender = $val;
	}

	public function get_league()
	{
		return $this->league;
	}
	public function set_league($val)
	{
		$this->league = $val;
	}

	public function get_sectors()
	{
		return $this->sectors;
	}
	public function set_sectors($val)
	{
		$this->sectors = $val;
	}

	public function get_l_sectors()
	{
		return $this->l_sectors;
	}
	public function set_l_sectors($val)
	{
		$this->l_sectors = $val;
	}

	public function validate()
	{
		$valid = parent::validate();
		
		//check to make sure email address isn't duplicate.
		$squad = squad_api::get_squad_by_name($this->SquadName);
		//is this user record's ID the same as the one we found for the email?
		if($squad && $this->get_id() != $squad->get_id())
		{
			$valid = false;
			$this->throw_notice('name is being used already.');
		}

		return $valid;
	}

/*******************************************************************************

								HELPER METHODS

*******************************************************************************/
	/**
		find if the squad is in a league.
	**/
	public function in_league($league_id)
	{
		$league = $this->get_league();
		if($league && $league->get_Leagues() == $league_id)
		{
			return true;
		}
		return false;
	}

	/**
		for this user, get a certain info.
	**/
	public function get_info_value($infoname)
	{
		$info = $this->get_info();
		$method = 'get_'.$infoname;
		if(method_exists($info, $method))
		{
			return $info->$method();
		}
		else
		{
			trigger_error("Could not get value ".$infoname);
			return null;
		}
	}

	/**
		search for this user's prefs, return an array of prefdetail objects.
	**/
//	public function get_prefs()
//	{
//		if(!is_array($this->prefs))
//		{
//			$model = new user_data_pref();
//			$rec = new user_record_pref_search();
//			$rec->set_user_id($this->id);
//			$rec->set_page_size(100);
//			$ret = $model->search($rec);
//			$this->prefs = $ret->get_results();
//		}
//		return $this->prefs;
//	}

	/**
		search for this user's groups, return an array of groupdetail objects.
	**/
//	public function get_groups()
//	{
//		if(!is_array($this->groups))
//		{
//			$model = new user_data_group();
//			$rec = new user_record_group_search();
//			$rec->set_user_id($this->id);
//			$rec->set_page_size(100);
//			$ret = $model->search($rec);
//			$this->groups = $ret->get_results();
//		}
//		return $this->groups;
//	}
}
?>

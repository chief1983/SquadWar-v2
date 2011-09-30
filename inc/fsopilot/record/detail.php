<?php

class fsopilot_record_detail extends base_recorddetail
{
	/* each of these represent a field in the fsopilot table. */
	protected $id;
	protected $user_id;
	protected $pilot_name;
	protected $score;
	protected $missions_flown;
	protected $flight_time;
	protected $last_flown;
	protected $kill_count;
	protected $kill_count_ok;
	protected $assists;
	protected $p_shots_fired;
	protected $p_shots_hit;
	protected $p_bonehead_hits;
	protected $s_shots_fired;
	protected $s_shots_hit;
	protected $s_bonehead_hits;
	protected $rank;
	protected $num_ship_kills;
	protected $ship_kills;
	protected $num_medals;
	protected $medals;
	protected $d_mod;

	/* these are not in the fsopilot table. */
	protected $swpilot;
	protected $last_activity;

	public function __construct()
	{
		parent::__construct();
		$this->datamodel_name = 'fsopilot_data_main';
		$this->required_fields  = array(
			'user_id','pilot_name'
		);

		//set timestamps to time this record was instantiated
		$this->created_time = $this->created;
		$this->modified_time = $this->created;
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

	public function get_score()
	{
		return $this->score;
	}
	public function set_score($val)
	{
		$this->score = $val;
	}

	public function get_missions_flown()
	{
		return $this->missions_flown;
	}
	public function set_missions_flown($val)
	{
		$this->missions_flown = $val;
	}

	public function get_flight_time()
	{
		return $this->flight_time;
	}
	public function set_flight_time($val)
	{
		$this->flight_time = $val;
	}

	public function get_last_flown()
	{
		return $this->last_flown;
	}
	public function set_last_flown($val)
	{
		$this->last_flown = $val;
	}

	public function get_kill_count()
	{
		return $this->kill_count;
	}
	public function set_kill_count($val)
	{
		$this->kill_count = $val;
	}

	public function get_kill_count_ok()
	{
		return $this->kill_count_ok;
	}
	public function set_kill_count_ok($val)
	{
		$this->kill_count_ok = $val;
	}

	public function get_assists()
	{
		return $this->assists;
	}
	public function set_assists($val)
	{
		$this->assists = $val;
	}

	public function get_p_shots_fired()
	{
		return $this->p_shots_fired;
	}
	public function set_p_shots_fired($val)
	{
		$this->p_shots_fired = $val;
	}

	public function get_p_shots_hit()
	{
		return $this->p_shots_hit;
	}
	public function set_p_shots_hit($val)
	{
		$this->p_shots_hit = $val;
	}

	public function get_p_bonehead_hits()
	{
		return $this->p_bonehead_hits;
	}
	public function set_p_bonehead_hits($val)
	{
		$this->p_bonehead_hits = $val;
	}

	public function get_s_shots_fired()
	{
		return $this->s_shots_fired;
	}
	public function set_s_shots_fired($val)
	{
		$this->s_shots_fired = $val;
	}

	public function get_s_shots_hit()
	{
		return $this->s_shots_hit;
	}
	public function set_s_shots_hit($val)
	{
		$this->s_shots_hit = $val;
	}

	public function set_s_bonehead_hits($val)
	{
        $this->s_bonehead_hits = $val;
	}
	public function get_s_bonehead_hits()
	{
		return $this->s_bonehead_hits;
	}

	public function set_rank($val)
	{
        $this->rank = $val;
	}
	public function get_rank()
	{
		return $this->rank;
	}

	public function get_num_ship_kills()
	{
		return $this->num_ship_kills;
	}
	public function set_num_ship_kills($val)
	{
		$this->num_ship_kills = $val;
	}

	public function get_ship_kills()
	{
		return $this->ship_kills;
	}
	public function set_ship_kills($val)
	{
		$this->ship_kills = $val;
	}

	public function get_num_medals()
	{
		return $this->num_medals;
	}
	public function set_num_medals($val)
	{
		$this->num_medals = $val;
	}

	public function get_medals()
	{
		return $this->medals;
	}
	public function set_medals($val)
	{
		$this->medals = $val;
	}

	public function get_d_mod()
	{
		return $this->d_mod;
	}
	public function set_d_mod($val)
	{
		$this->d_mod = $val;
	}

	public function get_swpilot()
	{
		return $this->swpilot;
	}
	public function set_swpilot($val)
	{
		if(is_array($val))
		{
			$val = reset($val);
		}
		$this->swpilot = $val;
	}

	public function validate()
	{
		$valid = parent::validate();

		return $valid;
	}

/*******************************************************************************

								HELPER METHODS

*******************************************************************************/
	/**
		for this user, get the last bit of content (gnosis node) created.
	**/
	public function get_last_activity()
	{
		//TODO make this work
	}
}
?>

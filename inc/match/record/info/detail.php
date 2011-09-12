<?php

class match_record_info_detail extends base_recorddetail
{
	protected $MatchID;
	protected $SWCode;
	protected $match_time1;
	protected $match_time2;
	protected $proposed_final_time;
	protected $proposed_alternate_time;
	protected $squad_last_proposed;
	protected $final_match_time;
	protected $time_created;
	protected $Mission;
	protected $Pilots;
	protected $AI;
	protected $dispute;
	protected $status_last_changed;
	protected $swsquad1_reports_noshow;
	protected $swsquad1_noshow_datetime;
	protected $swsquad2_reports_noshow;
	protected $swsquad2_noshow_datetime;
	protected $swsquad1_protest;
	protected $swsquad1_protest_datetime;
	protected $swsquad2_protest;
	protected $swsquad2_protest_datetime;
	protected $mail_sent;

	public function __construct()
	{
		parent::__construct();
		$this->datamodel_name  = 'match_data_info';
		$this->required_fields  = array(
			'MatchID','SWCode','time_created'
		);

		//set timestamps to time this record was instantiated
		$this->time_created = $this->created;
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

	public function get_match_time1()
	{
		return $this->match_time1;
	}
	public function set_match_time1($val)
	{
		$this->match_time1 = $val;
	}

	public function get_match_time2()
	{
		return $this->match_time2;
	}
	public function set_match_time2($val)
	{
		$this->match_time2 = $val;
	}

	public function get_proposed_final_time()
	{
		return $this->proposed_final_time;
	}
	public function set_proposed_final_time($val)
	{
		$this->proposed_final_time = $val;
	}

	public function get_proposed_alternate_time()
	{
		return $this->proposed_alternate_time;
	}
	public function set_proposed_alternate_time($val)
	{
		$this->proposed_alternate_time = $val;
	}

	public function get_squad_last_proposed()
	{
		return $this->squad_last_proposed;
	}
	public function set_squad_last_proposed($val)
	{
		$this->squad_last_proposed = $val;
	}

	public function get_final_match_time()
	{
		return $this->final_match_time;
	}
	public function set_final_match_time($val)
	{
		$this->final_match_time = $val;
	}

	public function get_time_created()
	{
		return $this->time_created;
	}
	public function set_time_created($val)
	{
		$this->time_created = $val;
	}

	public function get_Mission()
	{
		return $this->Mission;
	}
	public function set_Mission($val)
	{
		$this->Mission = $val;
	}

	public function get_Pilots()
	{
		return $this->Pilots;
	}
	public function set_Pilots($val)
	{
		$this->Pilots = $val;
	}

	public function get_AI()
	{
		return $this->AI;
	}
	public function set_AI($val)
	{
		$this->AI = $val;
	}

	public function get_dispute()
	{
		return $this->dispute;
	}
	public function set_dispute($val)
	{
		$this->dispute = $val;
	}

	public function get_status_last_changed()
	{
		return $this->status_last_changed;
	}
	public function set_status_last_changed($val)
	{
		$this->status_last_changed = $val;
	}

	public function get_swsquad1_reports_noshow()
	{
		return $this->swsquad1_reports_noshow;
	}
	public function set_swsquad1_reports_noshow($val)
	{
		$this->swsquad1_reports_noshow = $val;
	}

	public function get_swsquad1_noshow_datetime()
	{
		return $this->swsquad1_noshow_datetime;
	}
	public function set_swsquad1_noshow_datetime($val)
	{
		$this->swsquad1_noshow_datetime = $val;
	}

	public function get_swsquad2_reports_noshow()
	{
		return $this->swsquad2_reports_noshow;
	}
	public function set_swsquad2_reports_noshow($val)
	{
		$this->swsquad2_reports_noshow = $val;
	}

	public function get_swsquad2_noshow_datetime()
	{
		return $this->swsquad2_noshow_datetime;
	}
	public function set_swsquad2_noshow_datetime($val)
	{
		$this->swsquad2_noshow_datetime = $val;
	}

	public function get_swsquad1_protest()
	{
		return $this->swsquad1_protest;
	}
	public function set_swsquad1_protest($val)
	{
		$this->swsquad1_protest = $val;
	}

	public function get_swsquad1_protest_datetime()
	{
		return $this->swsquad1_protest_datetime;
	}
	public function set_swsquad1_protest_datetime($val)
	{
		$this->swsquad1_protest_datetime = $val;
	}

	public function get_swsquad2_protest()
	{
		return $this->swsquad2_protest;
	}
	public function set_swsquad2_protest($val)
	{
		$this->swsquad2_protest = $val;
	}

	public function get_swsquad2_protest_datetime()
	{
		return $this->swsquad2_protest_datetime;
	}
	public function set_swsquad2_protest_datetime($val)
	{
		$this->swsquad2_protest_datetime = $val;
	}

	public function get_mail_sent()
	{
		return $this->mail_sent;
	}
	public function set_mail_sent($val)
	{
		$this->mail_sent = $val;
	}

	// Helper to get the current phase
	public function get_current_phase()
	{
		$current_phase = 1;
		if($this->get_match_time2() != '0000-00-00 00:00:00'): $current_phase = 2; endif;
		if($this->get_proposed_final_time() != '0000-00-00 00:00:00' || $this->get_proposed_alternate_time() != '0000-00-00 00:00:00'): $current_phase = 3; endif;
		if($this->get_final_match_time() != '0000-00-00 00:00:00'): $current_phase = 4; endif;
		return $current_phase;
	}
}
?>

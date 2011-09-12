<?php

class fsopilot_record_swpilot_detail extends base_recorddetail
{
	/* each of these represent a field in the fsopilot table. */
	protected $PilotID;
	protected $TrackerID;
	protected $ICQ;
	protected $connection_type;
	protected $time_zone;
	protected $Member_Since;
	protected $show_email;
	protected $email;
	protected $Pilot_Name;
	protected $Recruitme;
	protected $Special;
	protected $Active;

	/* these are in external tables that we will always join */
	protected $fetch_connection_type;
	protected $fetch_time_zone;
	protected $fetch_time_zone_hours;
	protected $fetch_time_zone_minutes;
	
	/* these are not in the fsopilot table. */
	protected $last_activity;

	public function __construct()
	{
		parent::__construct();
		$this->datamodel_name = 'fsopilot_data_swpilot';
		$this->required_fields  = array(
			'PilotID','TrackerID','Pilot_Name','Member_Since','email'
		);

		//set timestamps to time this record was instantiated
		$this->Member_Since = $this->created;
		$this->modified_time = $this->created;
	}

	public function get_id()
	{
		return $this->PilotID;
	}
	public function set_id($val)
	{
		$this->PilotID = $val;
	}

	public function get_PilotID()
	{
		return $this->PilotID;
	}
	public function set_PilotID($val)
	{
		$this->PilotID = $val;
	}

	public function get_TrackerID()
	{
		return $this->TrackerID;
	}
	public function set_TrackerID($val)
	{
		$this->TrackerID = $val;
	}

	public function get_ICQ()
	{
		return $this->ICQ;
	}
	public function set_ICQ($val)
	{
		$this->ICQ = $val;
	}

	public function get_connection_type()
	{
		return $this->connection_type;
	}
	public function set_connection_type($val)
	{
		$this->connection_type = $val;
	}

	public function get_time_zone()
	{
		return $this->time_zone;
	}
	public function set_time_zone($val)
	{
		$this->time_zone = $val;
	}

	public function get_Member_Since()
	{
		return $this->Member_Since;
	}
	public function set_Member_Since($val)
	{
		$this->Member_Since = $val;
	}

	public function get_show_email()
	{
		return $this->show_email;
	}
	public function set_show_email($val)
	{
		$this->show_email = $val;
	}

	public function get_email()
	{
		return $this->email;
	}
	public function set_email($val)
	{
		$this->email = $val;
	}

	public function get_Pilot_Name()
	{
		return $this->Pilot_Name;
	}
	public function set_Pilot_Name($val)
	{
		$this->Pilot_Name = $val;
	}
	
	public function get_Recruitme()
	{
		return $this->Recruitme;
	}
	public function set_Recruitme($val)
	{
		$this->Recruitme = $val;
	}

	public function get_Special()
	{
		return $this->Special;
	}
	public function set_Special($val)
	{
		$this->Special = $val;
	}

	public function get_Active()
	{
		return $this->Active;
	}
	public function set_Active($val)
	{
		$this->Active = $val;
	}

	public function get_fetch_time_zone()
	{
		return $this->fetch_time_zone;
	}
	public function set_fetch_time_zone($val)
	{
		$this->fetch_time_zone = $val;
	}
	
	public function get_fetch_time_zone_hours()
	{
		return $this->fetch_time_zone_hours;
	}
	public function set_fetch_time_zone_hours($val)
	{
		$this->fetch_time_zone_hours = $val;
	}
	
	public function get_fetch_time_zone_minutes()
	{
		return $this->fetch_time_zone_minutes;
	}
	public function set_fetch_time_zone_minutes($val)
	{
		$this->fetch_time_zone_minutes = $val;
	}
	
	public function get_fetch_connection_type()
	{
		return $this->fetch_connection_type;
	}
	public function set_fetch_connection_type($val)
	{
		$this->fetch_connection_type = $val;
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

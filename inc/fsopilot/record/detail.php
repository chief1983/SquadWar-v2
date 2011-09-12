<?php

class fsopilot_record_detail extends base_recorddetail
{
	/* each of these represent a field in the fsopilot table. */
	protected $PilotID;
	protected $TrackerID;
	protected $Pilot;
	protected $Rank;
	protected $Score;
	protected $Medals;
	protected $Kills;
	protected $Assists;
	protected $KillCount;
	protected $KillCountOK;
	protected $PShotsFired;
	protected $SShotsFired;
	protected $PShotsHit;
	protected $SShotsHit;
	protected $PBoneheadHits;
	protected $SBoneheadHits;
	protected $BoneheadKills;
	protected $MissionsFlown;
	protected $FlightTime;
	protected $LastFlown;
	
	/* these are not in the fsopilot table. */
	protected $swpilot;
	protected $last_activity;

	public function __construct()
	{
		parent::__construct();
		$this->datamodel_name = 'fsopilot_data_main';
		$this->required_fields  = array(
			'TrackerID','Pilot'
		);

		//set timestamps to time this record was instantiated
		$this->created_time = $this->created;
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

	public function get_Pilot()
	{
		return $this->Pilot;
	}
	public function set_Pilot($val)
	{
		$this->Pilot = $val;
	}

	public function get_Rank()
	{
		return $this->Rank;
	}
	public function set_Rank($val)
	{
		$this->Rank = $val;
	}

	public function get_Score()
	{
		return $this->Score;
	}
	public function set_Score($val)
	{
		$this->Score = $val;
	}

	public function get_Medals()
	{
		return $this->Medals;
	}
	public function set_Medals($val)
	{
		$this->Medals = $val;
	}

	public function get_Kills()
	{
		return $this->Kills;
	}
	public function set_Kills($val)
	{
		$this->Kills = $val;
	}

	public function get_Assists()
	{
		return $this->Assists;
	}
	public function set_Assists($val)
	{
		$this->Assists = $val;
	}

	public function get_KillCount()
	{
		return $this->KillCount;
	}
	public function set_KillCount($val)
	{
		$this->KillCount = $val;
	}

	public function get_KillCountOK()
	{
		return $this->KillCountOK;
	}
	public function set_KillCountOK($val)
	{
		$this->KillCountOK = $val;
	}

	public function get_PShotsFired()
	{
		return $this->PShotsFired;
	}
	public function set_PShotsFired($val)
	{
		$this->PShotsFired = $val;
	}

	public function get_SShotsFired()
	{
		return $this->SShotsFired;
	}
	public function set_SShotsFired($val)
	{
		$this->SShotsFired = $val;
	}

	public function get_PShotsHit()
	{
		return $this->PShotsHit;
	}
	public function set_PShotsHit($val)
	{
		$this->PShotsHit = $val;
	}

	public function get_SShotsHit()
	{
		return $this->SShotsHit;
	}
	public function set_SShotsHit($val)
	{
		$this->SShotsHit = $val;
	}

	public function set_PBoneheadHits($val)
	{
        $this->PBoneheadHits = $val;
	}
	public function get_PBoneheeadHits()
	{
		return $this->PBoneheadHits;
	}

	public function set_SBoneheadHits($val)
	{
        $this->SBoneheadHits = $val;
	}
	public function get_SBoneheeadHits()
	{
		return $this->SBoneheadHits;
	}

	public function get_BoneheadKills()
	{
		return $this->BoneheadKills;
	}
	public function set_BoneheadKills($val)
	{
		$this->BoneheadKills = $val;
	}

	public function get_MissionsFlown()
	{
		return $this->MissionsFlown;
	}
	public function set_MissionsFlown($val)
	{
		$this->MissionsFlown = $val;
	}

	public function get_FlightTime()
	{
		return $this->FlightTime;
	}
	public function set_FlightTime($val)
	{
		$this->FlightTime = $val;
	}

	public function get_LastFlown()
	{
		return $this->LastFlown;
	}
	public function set_LastFlown($val)
	{
		$this->LastFlown = $val;
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
		
		//check to make sure TrackerID isn't duplicate.
		$fsopilot = fsopilot_api::get_fsopilot_by_TrackerID($this->TrackerID);
		//is this fsopilot record's ID the same as the one we found for the TrackerID?
		if($fsopilot && $this->id != $fsopilot->get_id())
		{
			$valid = false;
			$this->throw_notice('TrackerID is being used already.');
		}

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

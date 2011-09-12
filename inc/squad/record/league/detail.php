<?php

class squad_record_league_detail extends base_recorddetail
{
	protected $SWSquad_SquadID;
	protected $Leagues;
	protected $League_PW;

	public function __construct()
	{
		parent::__construct();
		$this->datamodel_name  = 'squad_data_league';
		$this->required_fields  = array(
			'SWSquad_SquadID','Leagues'
		);
	}

	public function get_SWSquad_SquadID()
	{
		return $this->SWSquad_SquadID;
	}
	public function set_SWSquad_SquadID($val)
	{
		$this->SWSquad_SquadID = $val;
	}

	public function get_Leagues()
	{
		return $this->Leagues;
	}
	public function set_Leagues($val)
	{
		$this->Leagues = $val;
	}

	public function get_League_PW()
	{
		return $this->League_PW;
	}
	public function set_League_PW($val)
	{
		$this->League_PW = $val;
	}
}
?>

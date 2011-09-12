<?php
class squad_record_league_search extends base_recordsearch
{
	protected $SWSquad_SquadID;
	protected $Leagues;
	protected $League_PW;
	
	public function set_SWSquad_SquadID($value)
	{
		$this->SWSquad_SquadID = $value;
	}
	public function get_SWSquad_SquadID()
	{
		return $this->SWSquad_SquadID;
	}

	public function set_Leagues($value)
	{
		$this->Leagues = $value;
	}
	public function get_Leagues()
	{
		return $this->Leagues;
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

<?php
class squad_record_info_search extends base_recordsearch
{
	protected $SquadID;
	protected $Squad_Leader_ID;
	protected $Approved;
	protected $Abbrv;

	public function set_id($value)
	{
		$this->SquadID = $value;
	}
	public function get_id()
	{
		return $this->SquadID;
	}

	public function set_SquadID($value)
	{
		$this->SquadID = $value;
	}
	public function get_SquadID()
	{
		return $this->SquadID;
	}

	public function set_Squad_Leader_ID($value)
	{
		$this->Squad_Leader_ID = $value;
	}
	public function get_Squad_Leader_ID()
	{
		return $this->Squad_Leader_ID;
	}

	public function set_Approved($value)
	{
		$this->Approved = $value;
	}
	public function get_Approved()
	{
		return $this->Approved;
	}

	public function set_Abbrv($value)
	{
		$this->Abbrv = $value;
	}
	public function get_Abbrv()
	{
		return $this->Abbrv;
	}
}

?>

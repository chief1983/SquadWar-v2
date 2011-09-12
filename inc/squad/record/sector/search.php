<?php
class squad_record_sector_search extends base_recordsearch
{
	protected $SWSectors_ID;
	protected $SectorName;
	protected $SectorSquad;
	protected $SectorSquad_not;
	protected $Entry_Node;
	protected $League_ID;
	protected $Active;

	public function set_SWSectors_ID($value)
	{
		$this->SWSectors_ID = $value;
	}
	public function get_SWSectors_ID()
	{
		return $this->SWSectors_ID;
	}

	public function set_SectorName($value)
	{
		$this->SectorName = $value;
	}
	public function get_SectorName()
	{
		return $this->SectorName;
	}

	public function set_SectorSquad($value)
	{
		$this->SectorSquad = $value;
	}
	public function get_SectorSquad()
	{
		return $this->SectorSquad;
	}

	public function set_SectorSquad_not($value)
	{
		$this->SectorSquad_not = $value;
	}
	public function get_SectorSquad_not()
	{
		return $this->SectorSquad_not;
	}

	public function set_Entry_Node($value)
	{
		$this->Entry_Node = $value;
	}
	public function get_Entry_Node()
	{
		return $this->Entry_Node;
	}

	public function set_League_ID($value)
	{
		$this->League_ID = $value;
	}
	public function get_League_ID()
	{
		return $this->League_ID;
	}

	public function set_Active($value)
	{
		$this->Active = $value;
	}
	public function get_Active()
	{
		return $this->Active;
	}
}
?>

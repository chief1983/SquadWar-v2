<?php

class squad_record_sector_detail extends base_recorddetail
{
	protected $SWSectors_ID;
	protected $SectorName;
	protected $SectorSquad;
	protected $SectorTime;
	protected $Entry_Node;
	protected $League_ID;
	protected $Value;
	protected $Graph;
	protected $Active;

	protected $sectorgraph;
	protected $squad;
	protected $pending_matches;

	public function __construct()
	{
		parent::__construct();
		$this->datamodel_name  = 'squad_data_sector';
		$this->required_fields  = array(
			'SectorName','League_ID','Graph'
		);

		//set timestamps to time this record was instantiated
		$this->time_registered = $this->created;
	}

	public function set_id($value)
	{
		$this->SWSectors_ID = $value;
	}
	public function get_id()
	{
		return $this->SWSectors_ID;
	}

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

	public function set_SectorTime($value)
	{
		$this->SectorTime = $value;
	}
	public function get_SectorTime()
	{
		return $this->SectorTime;
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

	public function set_Value($value)
	{
		$this->Value = $value;
	}
	public function get_Value()
	{
		return $this->Value;
	}

	public function set_Graph($value)
	{
		$this->Graph = $value;
	}
	public function get_Graph()
	{
		return $this->Graph;
	}

	public function set_Active($value)
	{
		$this->Active = $value;
	}
	public function get_Active()
	{
		return $this->Active;
	}

	public function set_sectorgraph($value)
	{
		$this->sectorgraph = $value;
	}
	public function get_sectorgraph()
	{
		return $this->sectorgraph;
	}

	public function set_squad($value)
	{
		$this->squad = $value;
	}
	public function get_squad()
	{
		return $this->squad;
	}

	public function set_pending_matches($value)
	{
		$this->pending_matches = $value;
	}
	public function get_pending_matches()
	{
		return $this->pending_matches;
	}
}
?>

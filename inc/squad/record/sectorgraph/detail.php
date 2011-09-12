<?php

class squad_record_sectorgraph_detail extends base_recorddetail
{
	protected $SWSectors_ID;
	protected $path_1;
	protected $path_2;
	protected $path_3;
	protected $path_4;
	protected $path_5;

	protected $sector_1;
	protected $sector_2;
	protected $sector_3;
	protected $sector_4;
	protected $sector_5;

	public function __construct()
	{
		parent::__construct();
		$this->datamodel_name  = 'squad_data_sector';
		$this->required_fields  = array(
			'SWSectors_ID'
		);

		//set timestamps to time this record was instantiated
		$this->time_registered = $this->created;
	}

	public function set_SWSectors_ID($value)
	{
		$this->SWSectors_ID = $value;
	}
	public function get_SWSectors_ID()
	{
		return $this->SWSectors_ID;
	}

	public function set_path_1($value)
	{
		$this->path_1 = $value;
	}
	public function get_path_1()
	{
		return $this->path_1;
	}

	public function set_path_2($value)
	{
		$this->path_2 = $value;
	}
	public function get_path_2()
	{
		return $this->path_2;
	}

	public function set_path_3($value)
	{
		$this->path_3 = $value;
	}
	public function get_path_3()
	{
		return $this->path_3;
	}

	public function set_path_4($value)
	{
		$this->path_4 = $value;
	}
	public function get_path_4()
	{
		return $this->path_4;
	}

	public function set_path_5($value)
	{
		$this->path_5 = $value;
	}
	public function get_path_5()
	{
		return $this->path_5;
	}

	public function set_sector_1($value)
	{
		$this->sector_1 = $value;
	}
	public function get_sector_1()
	{
		return $this->sector_1;
	}

	public function set_sector_2($value)
	{
		$this->sector_2 = $value;
	}
	public function get_sector_2()
	{
		return $this->sector_2;
	}

	public function set_sector_3($value)
	{
		$this->sector_3 = $value;
	}
	public function get_sector_3()
	{
		return $this->sector_3;
	}

	public function set_sector_4($value)
	{
		$this->sector_4 = $value;
	}
	public function get_sector_4()
	{
		return $this->sector_4;
	}

	public function set_sector_5($value)
	{
		$this->sector_5 = $value;
	}
	public function get_sector_5()
	{
		return $this->sector_5;
	}
}
?>

<?php
class squad_record_sectorgraph_search extends base_recordsearch
{
	protected $SWSectors_ID;

	public function set_SWSectors_ID($value)
	{
		$this->SWSectors_ID = $value;
	}
	public function get_SWSectors_ID()
	{
		return $this->SWSectors_ID;
	}
}

?>

<?php

class news_record_search extends base_recordsearch
{
	protected $newsID;
	protected $title;
	protected $news_item;
	protected $New;
	protected $SquadWar;
	protected $PXO;
	protected $newer_than;
	protected $older_than;

	public function get_newsID()
	{
		return $this->newsID;
	}
	public function set_newsID($val)
	{
		$this->newsID = $val;
	}

	public function get_title()
	{
		return $this->title;
	}
	public function set_title($val)
	{
		$this->title = $val;
	}

	public function get_news_item()
	{
		return $this->news_item;
	}
	public function set_news_item($val)
	{
		$this->news_item = $val;
	}
	
	public function get_New()
	{
		return $this->New;
	}
	public function set_New($val)
	{
		$this->New = $val;
	}
	
	public function get_SquadWar()
	{
		return $this->SquadWar;
	}
	public function set_SquadWar($val)
	{
		$this->SquadWar = $val;
	}

	public function get_PXO()
	{
		return $this->PXO;
	}
	public function set_PXO($val)
	{
		$this->PXO = $val;
	}

	public function get_newer_than($format='F j, o')
	{
		if(empty($this->newer_than))
		{
			return '';
		}
		$date = strtotime($this->newer_than);
		return date($format, $date);
	}
	public function set_newer_than($val)
	{
		$this->newer_than = $val;
	}

	public function get_older_than($format='F j, o')
	{
		if(empty($this->older_than))
		{
			return '';
		}
		$date = strtotime($this->older_than);
		return date($format, $date);
	}
	public function set_older_than($val)
	{
		$this->older_than = $val;
	}
}

?>

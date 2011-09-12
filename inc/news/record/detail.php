<?php

class news_record_detail extends base_recorddetail
{
	/* each of these represent a field in the news table. */
	protected $newsID;
	protected $date_posted;
	protected $title;
	protected $news_item;
	protected $New;
	protected $SquadWar;
	protected $PXO;
	
	public function __construct()
	{
		parent::__construct();
		$this->datamodel_name = 'news_data_main';
		$this->required_fields  = array(
			'date_posted','title','news_item'
		);

		//set timestamps to time this record was instantiated
		$this->date_posted = $this->created;
	}

	public function get_id()
	{
		return $this->newsID;
	}
	public function set_id($val)
	{
		$this->newsID = $val;
	}

	public function get_newsID()
	{
		return $this->newsID;
	}
	public function set_newsID($val)
	{
		$this->newsID = $val;
	}

	public function get_date_posted($format='F j, o')
	{
		if(empty($this->date_posted))
		{
			return '';
		}
		$date = strtotime($this->date_posted);
		return date($format, $date);
	}
	public function set_date_posted($val)
	{
		$this->date_posted = $val;
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
}
?>

<?php
/**
	this is designed to be sent to searches.  If you need more than keyword
	then extend away.
*/
class base_recordsearch extends base_record
{

	//don't init these pagination vars to something other than null.
	// since we have two ways of doing pagination, we need
	// to be able to determine which is used by checking to see what is null.
	protected $keyword;
	protected $limit = null;
	protected $offset = null;
	protected $sort_by;
	protected $sort_dir;
	protected $page_number = null;
	protected $page_size = null;
	protected $cache = null;

	public function set_keyword($val)
	{
		$this->keyword = $val;
	}
	public function get_keyword()
	{
		return $this->keyword;
	}

	public function set_limit($val)
	{
		if($this->validate_int($val))
		{
			$this->limit = $val;
		}
		else
		{
			$this->limit = 10;
		}
	}
	public function get_limit()
	{
		return $this->limit;
	}

	public function set_offset($val)
	{
		if($this->validate_int($val))
		{
			$this->offset = $val;
		}
		else
		{
			$this->offset = 0;
		}
	}
	public function get_offset()
	{
		return $this->offset;
	}

	public function set_sort_by($val)
	{
		if(is_null($val))
		{
			return false;
		}

		$this->sort_by = $val;
	}
	public function get_sort_by()
	{
		return $this->sort_by;
	}

	public function set_sort_dir($val)
	{
		if(is_null($val))
		{
			return false;
		}

		if($this->value_is_one_of(strtolower($val), array('asc','desc')))
		{
			$this->sort_dir = $val;
		}
		else
		{
			$this->sort_dir = 'asc';
		}

	}
	public function get_sort_dir()
	{
		return $this->sort_dir;
	}

	public function set_page_number($val)
	{
		if($this->validate_int($val))
		{
			$this->page_number = $val;
		}
		else
		{
			$this->page_number = 1;
		}
	}
	public function get_page_number()
	{
		return $this->page_number;
	}

	public function set_page_size($val)
	{
		if($this->validate_int($val))
		{
			$this->page_size = $val;
		}
		else
		{
			$this->page_size = 10;
		}
	}
	public function get_page_size()
	{
		return $this->page_size;
	}

	public function set_cache($val)
	{
		$this->cache = $val;
	}
	public function get_cache()
	{
		return $this->cache;
	}

}

?>

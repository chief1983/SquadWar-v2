<?php
class base_pagination extends base_record
{
	protected $recordcount;
	protected $page_recordcount;
	protected $page;
	protected $size;
	protected $next;
	protected $previous;
	protected $first;
	protected $last;
	protected $start;
	protected $end;

	public function __construct($recordcount, $size, $page)
	{
		//trap lamebrained input
		if($size < 1 || !is_numeric($size))
		{
			$size = 1;
		}
		if($page < 1 || !is_numeric($page))
		{
			$page = 1;
		}
		if($recordcount < 0 || !is_numeric($recordcount))
		{
			$recordcount = 0;
		}

		$this->recordcount = $recordcount;
		$this->size = $size;
		$this->page = $page;
		$this->populate_pagination_details();
	}

	public function get_recordcount()
	{
		return $this->recordcount;
	}

	public function get_page_recordcount()
	{
		return $this->page_recordcount;
	}

	public function get_page()
	{
		return $this->page;
	}

	public function get_size()
	{
		return $this->size;
	}

	public function get_next()
	{
		return $this->next;
	}

	public function get_previous()
	{
		return $this->previous;
	}

	public function get_first()
	{
		return $this->first;
	}

	public function get_last()
	{
		return $this->last;
	}

	public function get_start()
	{
		return $this->start;
	}

	public function get_end()
	{
		return $this->end;
	}

	protected function populate_pagination_details()
	{
		$this->next = $this->page + 1;

		//prev page is this page -1, unless we are on first page
		$this->previous = $this->page - 1;
		if($this->previous < 1)
		{
			$this->previous = 1;
		}

		//first page is always 1.
		$this->first = 1;

		//last page is an int being equal to count/size
		$this->last = (int)($this->recordcount / $this->size);

		//if last comes out weird due to count = 0, make it equal to 1
		if($this->last < 1)
		{
			$this->last = 1;
		}

		//if last * size doesn't get all the way to the last records, increment
		if(($this->last * $this->size) < $this->recordcount)
		{
			$this->last++;
		}

		//if user specified a page that exceeds the number of pages
		// we need to put it within range, and adjust prev page accordingly
		if($this->page > $this->last)
		{
			$this->page = $this->last;
			$this->previous = $this->page - 1;
		}

		//oddball cases, but technically possible
		if($this->previous > $this->last)
		{
			$this->previous = $this->last;
		}
		if($this->next > $this->last)
		{
			$this->next = $this->last;
		}

		//should be (page (zero based for this calc) * size) + 1
		$this->start = ($this->page-1) * $this->size + 1;

		//should be (page (0 based) * size) + size
		$this->end = ($this->start + $this->size) - 1;

		//end results cannot be more than total records, so...
		if($this->end > $this->recordcount)
		{
			$this->end = $this->recordcount;
		}

		//num of records for THIS page.
		$this->page_recordcount = ($this->end - $this->start) + 1;

		//in case there are no records...
		if($this->recordcount < 1)
		{
			$this->start = 0;
			$this->end = 0;
		}

	}

}
?>

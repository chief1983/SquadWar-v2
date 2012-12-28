<?php
/**
    this touches the main news table

    it gets, searches, creates news.
**/
class news_data_main extends news_data_base
{
	protected $tables = array();

	public function get($id)
	{
		$sql = "
			select n.newsID, n.date_posted, n.title, n.news_item, n.New,
				n.SquadWar,	n.PXO
			from `".$this->database."`.`".$this->table."` n
			where n.newsID = {$this->db->quote($id)}
		";

		return $this->exec_sql_return_record($sql, 'news_record_detail');
	}


	public function search(news_record_search $record)
	{
		$this->init_return($record);

		$sql = "
			select n.newsID, n.date_posted, n.title, n.news_item, n.New,
				n.SquadWar,	n.PXO
			from `".$this->database."`.`".$this->table."` n
		";

		$sql_where = $this->build_where_clause();
		$sql_from = $this->build_from_clause();
		$sql .= $sql_from . $sql_where;
		$sql .= $this->build_order_clause();
		$sql .= $this->build_limit_clause();

		if(!$this->errors)
		{
			$this->exec_sql_populate_return($sql, 'news_record_detail');
			$this->return->end();
		}

		$pag = new base_pagination(
			$this->search_db_get_recordcount($sql_from, $sql_where),
			$this->incoming_record->get_page_size(),
			$this->incoming_record->get_page_number()
		);

		$this->return->set_pagination($pag);

		$this->return->end();
		return $this->return;
	}


	public function get_count(news_record_search $record)
	{
		$this->init_return($record);

		$sql_where = $this->build_where_clause();
		$sql_from = $this->build_from_clause();

		return $this->search_db_get_recordcount($sql_from, $sql_where);
	}


	/**
		call the appropriate method based on whether we have an id.
	**/
	public function save(news_record_detail $record)
	{
		$this->init_return($record);
		$id = $record->get_id();
		if(empty($id))
		{
			return $this->create($record);
		}
		else
		{
			$status = $this->update($record);
			return $id;
		}
	}


	/**
		insert into news table
	**/
	protected function create(news_record_detail $record)
	{
		$sql = "
			insert into `".$this->database."`.`".$this->table."`
			(
				date_posted, title, news_item, New, SquadWar, PXO
			)
			values
			(
				{$this->db->quote($record->get_date_posted('Y-m-d H:i:s'))},
				{$this->db->quote($record->get_title())},
				{$this->db->quote($record->get_news_item())},
				{$this->db->quote($record->get_New())},
				{$this->db->quote($record->get_SquadWar())},
				{$this->db->quote($record->get_PXO())}
			)
		";

		$id = $this->exec_sql_return_id($sql);

		return $id;
	}


	/**
		update news table
	**/
	protected function update(news_record_detail $record)
	{

		$sql = "
			update `".$this->database."`.`".$this->table."`
			set
				title = {$this->db->quote($record->get_title())},
				news_item = {$this->db->quote($record->get_news_item())},
				New = {$this->db->quote($record->get_New())},
				SquadWar = {$this->db->quote($record->get_SquadWar())},
				PXO = {$this->db->quote($record->get_PXO())}
			where newsID = {$this->db->quote($record->get_id())}
		";

		$status = $this->exec_sql_return_status($sql);
		return $status;
	}

	public function delete(news_record_detail $record)
	{
		$sql = "
			delete from `".$this->database."`.`".$this->table."`
			where newsID = {$this->db->quote($record->get_id())}
		";

		return $this->exec_sql_return_status($sql);

	}


    //used by search method to find total records.
	protected function search_db_get_recordcount($sql_from, $sql_where)
	{
		$sql = "
			select count(*) as recordcount
			from `".$this->database."`.`".$this->table."` n
		";
		$sql .= $sql_from;
		$sql .= $sql_where;

		$results = $this->exec_sql_return_array($sql);

		$recordcount = 0;

		if( array_key_exists("0", $results)
			&& array_key_exists("recordcount", $results[0])
		)
		{
			return $results[0]['recordcount'];
		}
	}


	protected function build_where_clause()
	{
		$sql = " where 1=1 ";
		$sql .= $this->where_clause_newsID();
		$sql .= $this->where_clause_title();
		$sql .= $this->where_clause_news_item();
		$sql .= $this->where_clause_New();
		$sql .= $this->where_clause_SquadWar();
		$sql .= $this->where_clause_PXO();
		$sql .= $this->where_clause_newer_than();
		$sql .= $this->where_clause_older_than();
		return $sql;
	}


	/**
		when we build the where clause, we also record which tables are needed.
	**/
	protected function build_from_clause()
	{
		$sql = " ";
		//check to see which tables are required.

		return $sql;
	}


	/**
		build snippet of where clause for newsID
	**/
	protected function where_clause_newsID()
	{
		if(!method_exists($this->incoming_record, 'get_newsID'))
		{
			return '';
		}

		$newsID = $this->incoming_record->get_newsID();
		$where_fragment = '';
		if($newsID != '')
		{
			$where_fragment=" and n.newsID = {$this->db->quote($newsID)}";
		}
		return $where_fragment;
	}


	/**
		build snippet of where clause for title
	**/
	protected function where_clause_title()
	{
		if(!method_exists($this->incoming_record, 'get_title'))
		{
			return '';
		}

		$title = $this->incoming_record->get_title();
		$where_fragment = '';
		if($title != '')
		{
			$where_fragment=" and n.title like {$this->db->quote('%'.$title.'%')}";
		}
		return $where_fragment;
	}


	/**
		build snippet of where clause for title
	**/
	protected function where_clause_news_item()
	{
		if(!method_exists($this->incoming_record, 'get_news_item'))
		{
			return '';
		}

		$news_item = $this->incoming_record->get_news_item();
		$where_fragment = '';
		if($news_item != '')
		{
			$where_fragment=" and n.news_item like {$this->db->quote('%'.$news_item.'%')}";
		}
		return $where_fragment;
	}


	/**
		build snippet of where clause for title
	**/
	protected function where_clause_New()
	{
		if(!method_exists($this->incoming_record, 'get_New'))
		{
			return '';
		}

		$New = $this->incoming_record->get_New();
		$where_fragment = '';
		if($New != '')
		{
			$where_fragment=" and n.New = {$this->db->quote($New)}";
		}
		return $where_fragment;
	}


	/**
		build snippet of where clause for title
	**/
	protected function where_clause_SquadWar()
	{
		if(!method_exists($this->incoming_record, 'get_SquadWar'))
		{
			return '';
		}

		$SquadWar = $this->incoming_record->get_SquadWar();
		$where_fragment = '';
		if($SquadWar != '')
		{
			$where_fragment=" and n.SquadWar = {$this->db->quote($SquadWar)}";
		}
		return $where_fragment;
	}


	/**
		build snippet of where clause for title
	**/
	protected function where_clause_PXO()
	{
		if(!method_exists($this->incoming_record, 'get_PXO'))
		{
			return '';
		}

		$PXO = $this->incoming_record->get_PXO();
		$where_fragment = '';
		if($PXO != '')
		{
			$where_fragment=" and n.PXO = {$this->db->quote($PXO)}";
		}
		return $where_fragment;
	}


	/**
		build snippet of where clause for title
	**/
	protected function where_clause_newer_than()
	{
		if(!method_exists($this->incoming_record, 'get_newer_than'))
		{
			return '';
		}

		$newer_than = $this->incoming_record->get_newer_than('Y-m-d H:i:s');
		$where_fragment = '';
		if($newer_than != '')
		{
			$where_fragment=" and n.date_posted > {$this->db->quote($newer_than)}";
		}
		return $where_fragment;
	}


	/**
		build snippet of where clause for title
	**/
	protected function where_clause_older_than()
	{
		if(!method_exists($this->incoming_record, 'get_older_than'))
		{
			return '';
		}

		$older_than = $this->incoming_record->get_older_than('Y-m-d H:i:s');
		$where_fragment = '';
		if($older_than != '')
		{
			$where_fragment=" and n.date_posted <= {$this->db->quote($older_than)}";
		}
		return $where_fragment;
	}
}

?>

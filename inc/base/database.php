<?php
/**
	@author cliff gordon
	@version 0.1
	@package squadwar
	@description this is here for models that query databases.
		* inits a DB connection
		* runs queries and populates a return
*/
class base_database
{
	protected $errors;
	protected $db;
	protected $return;
	protected $incoming_record;
	protected $database = '';
	protected $table = '';
	protected $abbr = '';

	public function __construct()
	{
		$this->set_db();
		$this->errors = false;
		if(defined('TABLE_PREFIX') && !empty($this->table))
		{
			$this->table = TABLE_PREFIX . $this->table;
		}
	}


	/**
		init the db connection!
		@return void
	**/
	private function set_db()
	{
		if(is_null($this->db))
		{
			$this->db = dbm::get(SITE_CODE);
		}
	}


	/**
		@param $sql string
		@return boolean
		@description take a sql statement

		if there was an error, log it and return false.  else return true.
	**/
	protected function exec_sql_return_status($sql)
	{

		$this->db->query($sql);

		if($this->db->error())
		{
			$msg = 'Error while executing query: ' . $result->get_errors();
			$this->stop_processing_err_out($msg);

			$result = false;
		}
		else
		{
			$result = true;
		}

		return $result;
	}

	/**
		@param $sql string
		@return mixed
		@description take a sql statement

		if there was an error, log it and return false.  else return the ID
	**/
	protected function exec_sql_return_id($sql)
	{
		$this->db->query($sql);

		if($this->db->error())
		{
			$msg = 'Error while executing query: ' . $result->get_errors();
			$this->stop_processing_err_out($msg);

			$result = false;
		}
		else
		{
			$result = $this->db->insert_id();
		}

		return $result;
	}


	/**
		@param $sql string
		@param $detail_obj_name string.  name of a record_ class
		@description take sql.  run it, loop over array

		if 'array' is passed in as the record_obj_name then we'll
		just write the raw resultset array to the return's result var
.
		@return void
	**/
	protected function exec_sql_populate_return($sql, $detail_obj_name)
	{
		$result = $this->db->query_all($sql);

		if($this->db->error())
		{
			$msg = 'Error while executing query: ' . $result->get_errors();
			$this->stop_processing_err_out($msg);

			$result = false; //so we don't later treat as a valid result
		}

		//if we have results, load it into return
		if(is_array($result))
		{
			if($detail_obj_name == 'array') //no obj, just use raw array
			{
				$this->return->add_result($result);
			}
			else //an object.
			{
				foreach($result as $row)
				{
					$object = new $detail_obj_name;
					$object->populate_from_array($row);
					$this->return->add_result($object);
				}
			}
		}

		$this->return->end();
	}


	/**
		@param $sql string
		@description take sql, run it.
		@return array
	**/
	protected function exec_sql_return_array($sql)
	{
		$result = $this->db->query_all($sql);

		if($this->db->error())
		{
			$msg = 'Error while executing query: ' . $result->get_errors();
			$this->stop_processing_err_out($msg);

			$result = false; //so we don't later treat as a valid result
		}

		$results = array();

		//if we have an array, either from DB or cache, load it into results
		if(is_array($result))
		{
			foreach($result as $row)
			{
				$results[] = $row;
			}
		}

		return $results;
	}


	/**
		@param $sql string
		@param $detail_obj_name name of detail record
		@return $obj populated version of object, or null if we got nothing.
		@description take sql.  run it
		Returns ONE record.  Just takes the FIRST result.  Ideal for gets.
	**/
	protected function exec_sql_return_record($sql, $detail_obj_name)
	{
		$result = $this->db->query_all($sql);

		if($this->db->error())
		{
			$msg = 'Error while executing query: ' . $result->get_errors();
			$this->stop_processing_err_out($msg);

			$result = false; //so we don't later treat as a valid result
		}

		//if we have an array, either from DB or cache, load it into results
		if(is_array($result) && array_key_exists("0", $result))
		{
			$obj = new $detail_obj_name;
			$obj->populate_from_array($result[0]);
			return $obj;
		}

		return false;
	}


	protected function stop_processing_err_out($message)
	{
		if(! $this->return instanceof base_return)
		{
			$this->return = new base_return();
			$this->return->start();
		}

		$this->return->add_error($message);
		$this->return->end();
		$this->errors = true;
	}


	/**
		init the return, press play,
		take a record and stick its contents into the return
	**/
	protected function init_return($record)
	{
		$this->return = new base_return();
		$this->return->start();
		$this->incoming_record = $record;
		if(is_object($record))
		{
			if(method_exists($record,'get_cache') && !is_null($record->get_cache()))
			{
				$this->cache = $record->get_cache();
			}
			$this->return->add_parameters(get_object_vars($record));
		}
		else
		{
			$this->return->add_parameters($record);
		}
	}


	/**
		when we build the where clause, we also record which tables are needed.
	**/
	protected function build_from_clause()
	{
		$sql = " ";

		return $sql;
	}


	/**
		Build MySQL orderby clause
	**/
	protected function build_order_clause()
	{
		$sort_string = '';

		//order by rand, use sparingly!!
		if($this->incoming_record->get_sort_by() == 'random')
		{
			$sort_string = ' order by rand() ';
		}
		else if($this->incoming_record->get_sort_by() != '')
		{
			$sort_string = ' order by ' . $this->incoming_record->get_sort_by();
		}
		else
		{
			return $sort_string;
		}

		if($this->incoming_record->get_sort_dir() != '')
		{
			$sort_string .= ' ' . $this->incoming_record->get_sort_dir();
		}
		return $sort_string;
	}


	/**
		Build MySQL limit clause
	**/
	protected function build_limit_clause()
	{
		$limit_string = '';

		$rows = $this->incoming_record->get_page_size();
		$pnum = $this->incoming_record->get_page_number();
		$limit = $this->incoming_record->get_limit();
		$offset = $this->incoming_record->get_offset();

		//first, try pagination vars.
		if(!is_null($rows) && !is_null($pnum))
		{
			//pagination is 1 based, but for this we need zero based start
			$start = ($pnum * $rows) - $rows;

			if($start == 0 && $rows != 0)
			{
				$limit_string = ' limit ' . $rows;
			}
			else if($rows != 0)
			{
				$limit_string = ' limit ' . $start . ', ' . $rows;
			}
		}
		//next, try limit + offset vars.
		else if (!is_null($limit) && !is_null($offset))
		{
			$limit_string = ' limit ' . $offset . ', ' . $limit;
		}
		//next, try limit vars.
		else if (!is_null($limit))
		{
			$limit_string = ' limit ' . $limit;
		}

		//just so we have *something* to prevent runaway queries
		if($limit_string == '')
		{
			$limit_string = ' limit 100 ';
		}

		return $limit_string;
	}


	public function get($id)
	{
		$sql = "
			select ".implode(', ', $this->all_fields)."
			from `".$this->database."`.`".$this->table."` ".$this->abbr."
			where ".reset($this->primary_fields)." = {$this->db->quote($id)}
		";

		return $this->exec_sql_return_record($sql, $this->detail_record);
	}


	protected function search(base_recordsearch $record)
	{
		$this->init_return($record);

		$sql = "
			select ".implode(', ', $this->all_fields)."
			from `".$this->database."`.`".$this->table."` ".$this->abbr."
		";

		$sql_where = $this->build_where_clause();
		$sql_from = $this->build_from_clause();
		$sql .= $sql_from . $sql_where;
		$sql .= $this->build_order_clause();
		$sql .= $this->build_limit_clause();

		if(!$this->errors)
		{
			$this->exec_sql_populate_return($sql, $this->detail_record);
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

	protected function get_count(base_recordsearch $record)
	{
		$this->init_return($record);

		$sql_where = $this->build_where_clause();
		$sql_from = $this->build_from_clause();

		return $this->search_db_get_recordcount($sql_from, $sql_where);
	}

	/**
		call the appropriate method based on whether we have an id.
	**/
	protected function save(base_recorddetail $record)
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
		call the appropriate method based on whether we have an id.
	**/
	protected function save_child(base_recorddetail $record)
	{
		$this->init_return($record);
		$id = $record->get_id();
		if(empty($id))
		{
			return false;
		}
		else
		{
			$rec = $this->get($id);
			if(empty($rec))
			{
				return $this->create($record);
			}
			else
			{
				$status = $this->update($record);
				return $id;
			}
		}
	}


	protected function create(base_recorddetail $record, $returntype = 'id')
	{
		$values = array();
		foreach($this->all_fields as $key => $field)
		{
			if(strpos($field, '.') !== false)
			{
				$parts = explode('.', $field);
				$field = end($parts);
			}
			$use_fields[] = $field;
			$func = 'get_'.$field;
			if(method_exists($record, $func))
			{
				$values[] = $this->db->quote($record->$func());
			}
			else
			{
				trigger_error('Function '.$func.' does not exist in record');
				// Fields mismatch
				return false;
			}
		}
		$sql = "
			insert into `".$this->database."`.`".$this->table."`
			(".implode(', ', $use_fields).")
			values
			(
				".implode(", ", $values)."
			)
		";

		if($returntype == 'id')
		{
			$return = $this->exec_sql_return_id($sql);
		}
		elseif($returntype = 'status')
		{
			$return = $this->exec_sql_return_status($sql);
		}
		else
		{
			$return = false;
			trigger_error('Unknown returntype '.$returntype.' requested.');
		}

		return $return;
	}


	/**
		update table
	**/
	protected function update(base_recorddetail $record)
	{
		$assignments = array();
		foreach($this->all_fields as $field)
		{
			if(!in_array($field, $this->primary_fields))
			{
				if(strpos($field, '.') !== false)
				{
					$parts = explode('.', $field);
					$field = end($parts);
				}
				$func = 'get_'.$field;
				if(method_exists($record, $func))
				{
					$assignments[] = $field.' = '.$this->db->quote($record->$func());
				}
				else
				{
					// Fields mismatch
					trigger_notice('Could not find field '.$field.' in model.');
					return false;
				}
			}
		}
		$where = array();
		foreach($this->primary_fields as $field)
		{
			if(strpos($field, '.') !== false)
			{
				$parts = explode('.', $field);
				$field = end($parts);
			}
			$func = 'get_'.$field;
			if(method_exists($record, $func))
			{
				$where[] = $field.' = '.$this->db->quote($record->$func());
			}
			else
			{
				// Fields mismatch
				trigger_notice('Could not find field '.$field.' in model.');
				return false;
			}
		}

		$sql = "
			update `".$this->database."`.`".$this->table."`
			set
				".implode(', ', $assignments)."
			where ".implode(' and ', $where)."
		";

		$status = $this->exec_sql_return_status($sql);
		return $status;
	}


	protected function delete(base_recorddetail $record)
	{
		$where = array();
		foreach($this->primary_fields as $field)
		{
			if(strpos($field, '.') !== false)
			{
				$parts = explode('.', $field);
				$field = end($parts);
			}
			$func = 'get_'.$field;
			if(method_exists($record, $func))
			{
				$where[] = $field.' = '.$this->db->quote($record->$func());
			}
			else
			{
				// Fields mismatch
				return false;
			}
		}
		if(empty($where))
		{
			return false;
		}
		$sql = "
			delete from `".$this->database."`.`".$this->table."`
			where ".implode(' and ', $where)."
		";

		return $this->exec_sql_return_status($sql);
	}

	//used by search method to find total records.
	protected function search_db_get_recordcount($sql_from, $sql_where)
	{
		$sql = "
			select count(*) as recordcount
			from `".$this->database."`.`".$this->table."` ".$this->abbr."
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
}
?>

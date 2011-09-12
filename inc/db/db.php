<?php
/*
	the following acquire a query object and perform actions on it
	with debug mode set, query objects aren't lost, but put on the stack

	query			sql
	
	query_one		sql
	query_row		sql
	query_all		sql
	query_all_index		sql, name
	
	mc_query_one		sql, key, ttl, refresh
	mc_query_row		sql, key, ttl, refresh
	mc_query_all		sql, key, ttl, refresh
	mc_query_all_index	sql, name, key, ttl, refresh
*/
class db_db
{
	protected $con = NULL;
	protected $connection_name = NULL;

	// this is for db(this) errors
	protected $error = NULL;
	protected $error_messages = array();

	// query object and stack
	protected $query_object = NULL;
	protected $query_object_stack = array();
	protected $query_object_class_name = 'query';

	// if we need to keep all query objects (query object statck)
	protected $debug = FALSE;

	protected $methods_needing_connection = array(
			'quote','escape',
			'query',
			'query_one','query_row','query_all','query_all_index',
			'mc_query_one','mc_query_row','mc_query_all','mc_query_all_index'
			);

	public function __construct($connection_name,$con)
	{
		$this->connection_name = $connection_name;
		$this->con = $con;
	}

	public function __call($name,$arguements)
	{
		$method_name = '_'.$name;

		if(!method_exists($this,$method_name))
		{
			trigger_error("Member function: '$name' not found",E_USER_ERROR);
		}

		if(in_array($name,$this->methods_needing_connection) !== FALSE)
		{
			if($this->connect() === FALSE)
			{
				// if we had problems, we set error and return
				// TODO: not sure if i need to do this here as well
				// TODO: i'm doing this in connect() already
				$this->set_error_status(TRUE,$this->con->error);
				return NULL;
			}
		}

		return call_user_func_array(array($this,$method_name),$arguements);
	}

	/*
		RETURN:
			FAILURE: NULL
			SUCCESS: Query Object
	*/
	protected function _query($sql)
	{
		return $this->add_query_object($sql);
	}

	/*
		RETURN:
			FAILURE: NULL
			SUCCESS: Query Object
	*/
	protected function add_query_object($sql)
	{
		if(!empty($_GET['sqldebug']))
		{
			util::debug($sql);
		}
		// old way
		//$this->query_object = new query($this->con,$sql);
		// i think we should be adding in the error flag from this class into the query object
		$this->query_object = new db_query($this->con,$sql,$this->db_error(), implode("\n",$this->get_db_errors()) );
		if($this->debug === TRUE)
		{
			$this->query_object_stack[] = $query_object;
		}
		return $this->query_object;
	}

	/*
		RETURN:
			FAILURE: Empty array
			SUCCESS: Array of results
	*/
	protected function _query_all($sql)
	{
		$this->add_query_object($sql);

		$return_array = Array();

		if($this->query_object->error() !== TRUE)
		{
			while($row = $this->query_object->fetch())
			{
				$return_array[] = $row;
			}
		}
		return $return_array;
	}

	/*
		RETURN:
			FAILURE: Empty array
			SUCCESS: Array of results indexed by $name
	*/
	protected function _query_all_index($sql,$name)
	{
		$this->add_query_object($sql);

		$return_array = Array();

		if($this->query_object->error() !== TRUE)
		{
			while($row = $this->query_object->fetch())
			{
				if(isset($row[$name]))
				{
					$return_array[$row[$name]] = $row;
				}
				else
				{
					return Array();
				}
			}
		}
		return $return_array;
	}

	/*
		RETURN:
			FAILURE: Empty array
			SUCCESS: Single array of first result row
	*/
	protected function _query_row($sql)
	{
		$this->add_query_object($sql);

		$return_array = Array();

		if($this->query_object->error() !== TRUE)
		{
			$return_array = $this->query_object->fetch();
		}
		return $return_array;
	}

	/*
		RETURN:
			FAILURE: NULL
			SUCCESS: Single value of first result row
	*/
	protected function _query_one($sql)
	{
		$this->add_query_object($sql);

		$return_value = NULL;

		if($this->query_object->error() !== TRUE)
		{
			$data = $this->query_object->fetch();
			if(!empty($data) && is_array($data)) $return_value = reset($data);
		}
		return $return_value;
	}

	/*
		RETURN:
			FAILURE: Empty array
			SUCCESS: Array of results
	*/
	protected function _mc_query_all($sql, $force_refresh, $ttl, $memcache_key = '')
	{
		if(empty($memcache_key)) $memcache_key = $this->make_mc_key($sql);
		
		if($force_refresh === FALSE)
		{
			$memcache_result = mc::get($memcache_key);
			if(mc::is_valid())
			{
				return $memcache_result;
			}
			unset($memcache_result);
		}

		$result = $this->query_all($sql);

		$this->_mc_helper_set($result,$ttl,$memcache_key);

		return $result;
	}

	/*
		RETURN:
			FAILURE: Empty array
			SUCCESS: Array of results indexed by $name
	*/
	protected function _mc_query_all_index($sql, $name, $force_refresh, $ttl, $memcache_key = '')
	{
		if(empty($memcache_key)) $memcache_key = $this->make_mc_key($sql);
		
		if($force_refresh === FALSE)
		{
			$memcache_result = mc::get($memcache_key);
			if(mc::is_valid())
			{
				return $memcache_result;
			}
			unset($memcache_result);
		}

		$result = $this->_query_all_index($sql,$name);

		$this->_mc_helper_set($result,$ttl,$memcache_key);

		return $result;
	}

	/*
		RETURN:
			FAILURE: Empty array
			SUCCESS: Single array of first result row
	*/
	protected function _mc_query_row($sql, $force_refresh, $ttl, $memcache_key = '')
	{
		if(empty($memcache_key)) $memcache_key = $this->make_mc_key($sql);
	
		if($force_refresh === FALSE)
		{
			$memcache_result = mc::get($memcache_key);
			if(mc::is_valid())
			{
				return $memcache_result;
			}
			unset($memcache_result);
		}

		$result = $this->_query_row($sql);

		$this->_mc_helper_set($result,$ttl,$memcache_key);

		return $result;
	}

	/*
		RETURN:
			FAILURE: NULL
			SUCCESS: Single value of first result row
	*/
	protected function _mc_query_one($sql, $index_name, $force_refresh, $ttl, $memcache_key = '')
	{
		if(empty($memcache_key)) $memcache_key = $this->make_mc_key($sql);
	
		if($force_refresh === FALSE)
		{
			$memcache_result = mc::get($memcache_key);
			if(mc::is_valid())
			{
				return $memcache_result;
			}
			unset($memcache_result);
		}

		$result = $this->_query_one($sql);

		$this->_mc_helper_set($result,$ttl,$memcache_key);

		return $result;
	}

	/*
		RETURN:
			FAILURE: N/A
			SUCCESS: Next row of results or FALSE if no more rows
	*/
	protected function _fetch()
	{
		return $this->query_object->fetch();
	}

	/*
		RETURN:
			FAILURE: N/A
			SUCCESS: Number of rows in most recent query. Results will be inaccurate for unbuffered queries.
	*/
	protected function _insert_id()
	{
		if($this->query_object !== NULL)
		{
			return $this->query_object->insert_id();
		}
		return FALSE;
	}

	/*
		RETURN:
			FAILURE: N/A
			SUCCESS: Number of rows in most recent query. Results will be inaccurate for unbuffered queries.
	*/
	protected function _num_rows()
	{
		if($this->query_object !== NULL)
		{
			return $this->query_object->num_rows();
		}
		return 0;
	}

	/*
		RETURN:
			FAILURE: Returns NULL
			SUCCESS: Returns escaped variable
	*/
	protected function _escape($var,$mode=NULL)
	{
		if($mode !== NULL)
		{
			switch($mode)
			{
				case 'text':
					$var = (string)$var;
				break;
				case 'integer':
					$var = (integer)$var;
				break;
				case 'boolean':
					$var = (boolean)$var;
				break;
				default:
			}
		}
		return $this->con->real_escape_string($var);
	}

	/*
		RETURN:
			FAILURE: Returns NULL
			SUCCESS: Returns quoted (escaped with quotes) variable
	*/
	public function quote($var,$mode=NULL)
	{
		return "'".$this->escape($var,$mode)."'";
	}

	/*
		RETURN:
			FAILURE: Returns FALSE
			SUCCESS: Returns SQL
	*/
	protected function _create_insert($table,$params)
	{
		if(is_array($params) === FALSE)
		{
			return FALSE;
		}
		$sql = "INSERT INTO ".$this->escape($table)." (".implode(',',array_map(array($this,'escape'),array_keys($params))).")
			VALUES (".implode(',',array_map(array($this,'quote'),$params)).")
			";
		return $sql;
	}

	/*
		RETURN:
			FAILURE: Returns FALSE
			SUCCESS: Returns SQL
	*/
	protected function _create_update($table,$params,$where_params)
	{
		if(is_array($params) === FALSE)
		{
			return FALSE;
		}
		$sql = "UPDATE ".$this->escape($table)."
			SET ";
		foreach($params as $index=>$value)
		{
			$sql .= $this->escape($index).' = '.$this->quote($value).',';
		}
		$sql = substr($sql,0,-1);
		$sql .= " WHERE ";
		foreach($where_params as $index=>$value)
		{
			$sql .= $this->escape($index).' = '.$this->quote($value).' and ';
		}
		$sql = substr($sql,0,-5);
		return $sql;
	}

	/*
		RETURN:
			FAILURE: NULL
			SUCCESS: NULL
	*/
	protected function _mc_helper_set($result,$ttl,$memcache_key)
	{
		if($ttl > 0)
		{
			mc::set($memcache_key,$result,$ttl);
		}
	}

	public function error()
	{
		// if this class has an error or if we have a query_object and it has an error
		if($this->db_error() === TRUE || ( get_class($this->query_object) === $this->query_object_class_name && $this->query_object->error() === TRUE ))
		{
			return TRUE;
		}
		return FALSE;
	}

	protected function db_error()
	{
		return $this->error;
	}

	public function get_errors()
	{
		// if we have a query object, combine error messages and return
		if(get_class($this->query_object) === $this->query_object_class_name)
		{
			return array_merge($this->get_db_errors(), $this->query_object->get_errors());
		}
		return $this->get_db_errors();
	}

	protected function get_db_errors()
	{
		return $this->error_messages;
	}

	protected function set_error_status($error,$error_message='')
	{
		$this->error = $error;
		if($error_message !== '')
		{
			$this->error_messages[] = $error_message;
		}
	}

	public function get_connection_name()
	{
		return $this->connection_name;
	}

	protected function connect()
	{
		if($this->con === NULL)
		{
			$con_response = dbm::get_con($this->connection_name);
			// we didn't get a connection back
			if($con_response['connection'] == NULL)
			{
				// we must have an error, return false
				// set con to false to prevent reconnection attempts
				$this->con = FALSE;
				$this->set_error_status(TRUE,$con_response['error']);
				return FALSE;
			}
			else
			{
				// we're all good, return true
				$this->con = $con_response['connection'];
				return TRUE;
			}
		}
		elseif($this->con === FALSE)
		{
			// we've already done this before and it turned out bad
			// not sure if we should try to reconnect, i think most of the time we shouldn't attempt to reconnect
			return FALSE;
		}
		else
		{
			// we've already done this before and it turned out good
			return TRUE;
		}
	}
	
	protected function make_mc_key($query)
	{
		return 'db_db'.md5($query);
	}
}
?>

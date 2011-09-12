<?php

class base_return extends base_record
{
	private $start_time;
	private $end_time;
	private $warnings;
	private $errors;
	private $results;
	private $meta;
	private $parameters;
	private $pagination;

	/**
	 *
	 * Creates a new return object object.  There is the concept of meta data and
	 * parameters.  Meta data might be information about the calling client
	 *
	 *		meta => array(
	 *			memcache => array(
	 *				key=>ABC123,
	 *				maybe=>others
	 *			)
	 *		)
	 *
	 * While parameters would be the parameters initially passed to the calling client:
	 *
	 *	parameters => array(
	 *		query => shoes,
	 *		site => jcl,
	 *	)
	 *
	 * @param mixed a key/value pair array of parameters
	 * @param mixed a key/value pair array of meta data
	 *
	 **/
	public function __construct(array $parameters=null, array $meta=null)
	{
		$this->start_time = $this->_get_time();
		$this->end_time = $this->_get_time();
		$this->warnings = array();
		$this->errors = array();
		$this->results = array();
		$this->meta = array();
		$this->parameters = array();

		$this->add_parameters($parameters);
		$this->add_meta($meta);
	}

	/**
	 *
	 * Starts the process timer.
	 * Note: This can be called multiple times and is reset with each call.
	 *
	 * @return void
	 *
	 **/
	public function start()
	{
		$this->start_time = $this->_get_time();
	}

	/**
	 *
	 * Returns formatted process start time.
	 *
	 * @param string date/time format (see php's date function for format strings); optional
	 * @return int
	 *
	 **/
	public function get_start_time($format='Y-m-d H:i:s.u')
	{
		return date($format, $this->start_time);
	}

	/**
	 *
	 * Ends the process timer.
	 * Note: This can be called multiple times and is reset with each call.
	 *
	 * @return void
	 *
	 **/
	public function end()
	{
		$this->end_time = $this->_get_time();
	}

	/**
	 *
	 * Returns formatted process end time.
	 *
	 * @param string date/time format (see php's date function for format strings); optional
	 * @return int
	 *
	 **/
	public function get_end_time($format='Y-m-d H:i:s.u')
	{
		return date($format, $this->end_time);
	}

	/**
	 *
	 * Returns the number of milliseconds the process has run for.
	 *
	 * @return int
	 *
	 **/
	public function get_process_time()
	{
		return $this->end_time - $this->start_time;
	}

	/**
	 *
	 * Add to the list of warnings.
	 * Warning message must not be null or an empty string.
	 * Warning message will be trimmed.
	 *
	 * @param string|mixed
	 * @return void
	 *
	 **/
	public function add_warning($message)
	{
		$is_object = false;
		$this->warnings = $this->_add_element_or_list_of_elements($message, $this->warnings, $is_object);
	}

	/**
	 *
	 * Add to the list of errors.
	 * Error message must not be null or an empty string.
	 * Error message will be trimmed.
	 *
	 * @param string|mixed
	 * @return void
	 *
	 **/
	public function add_error($message)
	{
		$is_object = false;
		$this->errors = $this->_add_element_or_list_of_elements($message, $this->errors, $is_object);
	}

	/**
	 *
	 * Add to the list of results.
	 *
	 * @param object|mixed
	 * @return void
	 *
	 **/
	public function add_result($result)
	{
		$is_object = true;
		$this->results = $this->_add_element_or_list_of_elements($result, $this->results, $is_object);
	}
	
	public function set_result($result)
	{
		$this->results = $result;
	}

	/**
	 *
	 * Returns warning message(s).
	 *
	 * @return mixed
	 *
	 **/
	public function get_warnings()
	{
		return $this->warnings;
	}

	/**
	 *
	 * Were there any warnings.
	 *
	 * @return boolean
	 *
	 **/
	public function has_warnings()
	{
		return 0 < count($this->warnings);
	}

	/**
	 *
	 * Returns error message(s).
	 *
	 * @return mixed
	 *
	 **/
	public function get_errors()
	{
		return $this->errors;
	}

	/**
	 *
	 * Were there any errors.
	 *
	 * @return boolean
	 *
	 **/
	public function has_errors()
	{
		return 0 < count($this->errors);
	}

	/**
	 *
	 * Returns results(s).
	 *
	 * @return mixed
	 *
	 **/
	public function get_results()
	{
		return $this->results;
	}
	
	/**
	 *
	 * Returns ids found within
	 *
	 * @return mixed
	 *
	 **/
	public function get_ids($func = 'get_id')
	{
		$rtn = array();
		if (is_array($this->results))
		{
			foreach($this->results as $result)
			{
				if (method_exists($result, $func))
				{
					$rtn[] = $result->$func();
				}
			}
		}else if(!empty($this->results)){
			if (method_exists($this->results, $func))
			{
				$rtn[] = $this->results->$func();
			}
		}
		return $rtn;
	}

	/**
	 *
	 * Add key/value pair(s) to meta information.
	 *
	 * @param string key
	 * @param string|mixed value
	 * @return void
	 *
	 **/
	public function add_meta_key_value($key, $value)
	{
		if(!is_null($key) && strlen(trim($key)) > 0)
		{
			$this->meta[$key] = $value;
		}
	}

	/**
	 *
	 * Add key/value pair(s) to meta information.
	 *
	 * @param mixed array of key/value pairs
	 * @return void
	 *
	 **/
	public function add_meta(array $meta=null)
	{
		if(is_array($meta))
		{
			foreach($meta as $key => $value)
			{
				$this->add_meta_key_value($key, $value);
			}
		}
	}

	/**
	 *
	 * Retrieve the meta data.
	 *
	 * @return mixed
	 *
	 **/
	public function get_meta()
	{
		$return_array = array();
		foreach($this->meta as $key => $value)
		{
			$return_array[$key] = $value;
		}
		return $return_array;
	}

	/**
	 *
	 * Add key/value pair(s) to parameters information.
	 *
	 * @param string key
	 * @param string|mixed value
	 * @return void
	 *
	 **/
	public function add_parameters_key_value($key, $value)
	{
		if(!is_null($key) && strlen(trim($key)) > 0)
		{
			$this->parameters[$key] = $value;
		}
	}

	/**
	 *
	 * Add key/value pair(s) to parameters information.
	 *
	 * @param mixed array of key/value pairs
	 * @return void
	 *
	 **/
	public function add_parameters(array $params=null)
	{
		if(is_array($params))
		{
			foreach($params as $key => $value)
			{
				$this->add_parameters_key_value($key, $value);
			}
		}
	}

	/**
	 *
	 * Retrieve the parameters data.
	 *
	 * @return mixed
	 *
	 **/
	public function get_parameters()
	{
		$return_array = array();
		foreach($this->parameters as $key => $value)
		{
			$return_array[$key] = $value;
		}
		return $return_array;
	}


	/**
	 *
	 * Returns an array representation of this object.
	 *
	 * @return mixed
	 *
	 **/
	public function to_array()
	{
		$array = array_merge(get_object_vars($this),
			array(
				'process_time' => $this->get_process_time(),
				'has_warnings' => $this->has_warnings(),
				'has_errors' => $this->has_errors()));

		return $array;
	}

	
	public function set_pagination(base_pagination $val)
	{
		$this->pagination = $val;
	}
	
	
	public function get_pagination()
	{
		return $this->pagination;
	}


	/**
	 *
	 * Add a single item (e.g., string, object) or a list of items to the list
	 * passed in.
	 * If item being added ($incoming) is null that item will not be added.
	 * If item being added ($incoming) is a string and that string is empty that
	 * item will not be added.
	 * If the item being add is an array the above 2 rules apply to each element of
	 * the array.
	 *
	 * @param string|mixed to be added
	 * @param mixed list to add to
	 * @param boolean is element an object
	 * @return augmented list 
	 *
	 **/
	private function _add_element_or_list_of_elements($incoming, $list, $is_object)
	{
		if(!is_null($incoming))
		{
			if(is_array($incoming))
			{
				foreach($incoming as $key => $value)
				{
					$list[$key] = $value;
				}
			}
			else
			{
				if(!is_object($incoming) && strlen(trim($incoming)) > 0)
				{
					$incoming = trim($incoming);
				}
				if($this->_memory_available())
				{
					$list[] = $incoming;
				}
				if(!is_array($list))
				{
					$list = array();
				}
			}
		}
		return $list;
	}
	
	private function _memory_available()
	{
		$ini    = ini_get('memory_limit');
		$mem 	= preg_replace( "/[^0-9]/", "", $ini );
		$usage 	= memory_get_usage();
		$memory = $mem;
		
		if(preg_match("/([A-Za-z])/", $ini, $matches))
		{
			$multiplier = strtoupper($matches[1]);
			switch($multiplier)
			{
				case 'M': $memory = $mem * 1024 * 1024;
					break;
				case 'G': $memory = $mem * 1024 * 1024 * 1024;
					break;
				default:
					$memory = $mem;
					break;
			}
		}
		
		//Give a 1MB leeway in usage
		if(($usage - 1048576) < $memory)
		{
			return true;
		}
		return false;
	}
	
	
	/**
		Get the first result from the results array.  
	**/
	public function get_first_result()
	{
		if(is_array($this->results) && array_key_exists("0", $this->results))
		{
			return $this->results[0];
		}
		else
		{
			return $this->results;
		}
	}


	/**
	 *
	 * Return the current time.  Used for calculating start/end/elapsed time.
	 * In one method so that if there are any suggested changes to this it can be
	 * handled in one place.
	 *
	 * @return float
	 *
	 **/
	private function _get_time()
	{
		return microtime(true);
	}
}

<?php

class util
{
	protected static $head_elements = array();
	protected static $footer_elements = array();

	public static function custom_die($msg)
	{
		ob_end_clean();
		trigger_error($msg, E_USER_ERROR);
		exit;
	}

	public static function push_head($text)
	{
		self::$head_elements[] = $text;
	}

	public static function get_head()
	{
		return self::$head_elements;
	}

	public static function push_footer($text)
	{
		self::$footer_elements[] = $text;
	}

	public static function get_footer()
	{
		return self::$footer_elements;
	}

	public function str_rank($rank_id)
	{
		$ranks = array(
			0	=>	'Ensign',
			1	=>	'Lt.J.G.',
			2	=>	'Lt.',
			3	=>	'Lt.Cmdr.',
			4	=>	'Cmdr.',
			5	=>	'Capt.',
			6	=>	'Cmdore.',
			7	=>	'R.Admir',
			8	=>	'V.Admir',
			9	=>	'Admiral'
		);

		if(array_key_exists($rank_id, $ranks))
		{
			return $ranks[$rank_id];
		}
		else
		{
			return $ranks[0];
		}
	}

	public function str_time($flighttime)
	{
		$str_days = (int) ($flighttime / 86400);
		$str_hours = (int) (($flighttime - (86400 * $str_days)) / 3600);
		$str_minutes = (int) (($flighttime - (86400 * $str_days) - (3600 * $str_hours)) / 60);
		$str_seconds = $flighttime - (86400 * $str_days) - (3600 * $str_hours) - (60 * $str_minutes);

		if($str_hours < 10)
		{
			$str_hours = '0'.$str_hours; 
		}
		if($str_minutes < 10)
		{
			$str_minutes = '0'.$str_minutes;
		}
		if($str_seconds < 10)
		{
			$str_seconds = '0'.$str_seconds;
		}

		return $str_days.'.'.$str_hours.'.'.$str_minutes;
	}

	public static function check_email_address($email)
	{
	    // First, we check that there's one @ symbol, and that the lengths are right
	    if (!ereg("^[^@]{1,64}@[^@]{1,255}$", $email)) {
	        // Email invalid because wrong number of characters in one section, or wrong number of @ symbols.
	        return false;
	    }
	    // Split it into sections to make life easier
	    $email_array = explode("@", $email);
	    $local_array = explode(".", $email_array[0]);
	    for ($i = 0; $i < sizeof($local_array); $i++) {
	         if (!ereg("^(([A-Za-z0-9!#$%&'*+/=?^_`{|}~-][A-Za-z0-9!#$%&'*+/=?^_`{|}~\.-]{0,63})|(\"[^(\\|\")]{0,62}\"))$", $local_array[$i])) {
	            return false;
	        }
	    }    
	    if (!ereg("^\[?[0-9\.]+\]?$", $email_array[1])) { // Check if domain is IP. If not, it should be valid domain name
	        $domain_array = explode(".", $email_array[1]);
	        if (sizeof($domain_array) < 2) {
	                return false; // Not enough parts to domain
	        }
	        for ($i = 0; $i < sizeof($domain_array); $i++) {
	            if (!ereg("^(([A-Za-z0-9][A-Za-z0-9-]{0,61}[A-Za-z0-9])|([A-Za-z0-9]+))$", $domain_array[$i])) {
	                return false;
	            }
	        }
	    }
	    return true;
	}

	public static function colorstring($red, $green, $blue)
	{
		$convert_red = base_convert($red, 10, 16);
		$convert_green = base_convert($green, 10, 16);
		$convert_blue = base_convert($blue, 10, 16);
		if($convert_red == '0')
		{
			$convert_red = '00';
		}
		if($convert_green == '0')
		{
			$convert_green = '00';
		}
		if($convert_blue == '0')
		{
			$convert_blue = '00';
		}

		return $convert_red . $convert_green . $convert_blue;
	}

	public static function location($location)
	{
		ob_end_clean();
		header('Location: '.$location);
		exit;
	}

	public static function debug($my_var,$format=0,$javascript=FALSE)
	{
		// ---
		// FORMAT:
		// 0 - PRINT_R TO SCREEN
		// 1 - VAR_DUMP TO SCREEN
		// 2 - FIREPHP
		// ---

		// ---
		// JAVASCRIPT (ALLOWS DEBUG OUTPUT TO NOT BREAK JAVASCRIPT):
		// FALSE: NO /* */ ENCAPSULATIONS
		// TRUE: /* */ ENCAPSULATION
		// ---

		$format = strtolower($format);
		if($format === '1' || $format == 'vardump' || $format == 'var_dump')
		{
			if($javascript)
			{
				print '/*';
			}
			print "<pre>";
			print var_dump($my_var);
			print "</pre>";
			if($javascript)
			{
				print '*/';
			}
		}
		else
		{
			if($javascript)
			{
				print '/*';
			}
			print "<pre>";
			print_r($my_var);
			print "</pre>";
			if($javascript)
			{
				print '*/';
			}
		}
	}

	// Usage: utility::get(object, method[, arg1, arg2, ...]);
	// or:    utility::get(array, index);
	public static function get($set, $find, $default = '')
	{
		$return = null;
		if(!is_object($set) && !is_array($set))
		{
			$caller = next(debug_backtrace());
			trigger_error("Non-object was passed by ".$caller['file'].", line ".$caller['line']." into utility::get ");
			return $return;
		}
		if(!isset($find) || (!is_string($find) && !is_int($find)))
		{
			$caller = next(debug_backtrace());
			trigger_error("Invalid index or method name was passed by ".$caller['file'].", line ".$caller['line']." into utility::get ");
			return $return;
		}
		if(is_object($set) && method_exists($set, $find))
		{
			$params = null;

			// This bit will take the following arguments to get and pass them to the
			// method. Unfortunately, it currently passes them as an array.
			// Need a better way to handle that, if it becomes an issue.
			$args = func_get_args();
			array_shift($args);
			array_shift($args);
			if(count($args))
			{
				$params = $args;
			}

			$return = $set->$find($params);
		}
		else if (is_array($set) && array_key_exists($find, $set))
		{
			$return = $set[$find];
		}

		if (empty($return) && !empty($default))
		{
			$return = $default;
		}
		return $return;
	}

	/*
	 * @param $ret base_return
	 * @param $field string
	 * @param $child_field string
	 * @param $sort_args array
	 * Since you can't sort a return object on populated date on the initial search,
	 * this should be able to sort based on a variety of 1:1 child data.
	 * @return base_return
	 */
	public static function sort_results_on_child_count(base_return $ret, $field, $sort_args = array(SORT_ASC))
	{
		$results = $ret->get_results();
		$sort_array = array();
		foreach($results as $key => $result)
		{
			$func = 'get_'.$field;
			if(method_exists($result, $func))
			{
				$sort_array[$key] = count($result->$func());
			}
			else
			{
			  	trigger_error('Function '.$func.' does not exist.');
			}
		}

		$args = array_merge(array(&$sort_array), $sort_args, array(&$results));
		call_user_func_array('array_multisort', $args);

		$ret->set_result($results);

		return $ret;
	}

	/*
	 * @param $ret base_return
	 * @param $field string
	 * @param $child_field string
	 * @param $sort_args array
	 * Since you can't sort a return object on populated date on the initial search,
	 * this should be able to sort based on a variety of 1:1 child data.
	 * @return base_return
	 */
	public static function sort_results_on_child_data(base_return $ret, $field,
		$child_field, $sort_args = array(SORT_ASC))
	{
		$results = $ret->get_results();
		$sort_array = array();
		foreach($results as $result)
		{
			$func = 'get_'.$field;
			$child_func = 'get_'.$child_field;
			if(method_exists($result, $func) &&
				method_exists($result->$func(), $child_func))
			{
				$sort_array[] = $result->$func()->$child_func();
			}
			else
			{
				trigger_error('Function '.$func.' or '.$child_func.
					' does not exist.');
			}
		}

		$args = array_merge(array(&$sort_array), $sort_args, array(&$results));
		call_user_func_array('array_multisort', $args);

		$ret->set_result($results);

		return $ret;
	}
}
?>

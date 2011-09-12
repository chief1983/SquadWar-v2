<?php
/**
	contains some nice utility methods for the record objects.
*/
class base_record
{

	public function __construct($arr = array())
	{
		if (!empty($arr) && is_array($arr))
		{
			$this->populate_from_array($arr);
		}
	}

	public function populate_from_array($arr)
	{
		foreach($arr as $key=>$value)
		{
			//using method_exists, because is_callable doesn't seem to work
			// in this context.  If you have a protected or private setter,
			// this will err out.  Fix: make setters public if you wanna use
			// them in a public context.  Duh!
			if(method_exists($this, 'set_'.$key))
			{
				$method = 'set_'.$key;
				$this->$method($value);
			}
		}
	}

	protected function resolve_to_array($val)
	{
		if(is_array($val))
		{
			//an array.  great! om nom nom nom
			return $val;
		}
		else if($this->validate_int($val))
		{
			//a single number.  make into array, then om nom nom nom
			return array($val);
		}
		else
		{
			//probably a list.  yuck.
			return explode(",", $val);
		}
	}

	protected function value_is_one_of($value, array $values)
	{
		return in_array($value, $values);
	}

	protected function validate_non_array($val)
	{
		return !is_array($val);
	}

	protected function normalize_datetime($val)
	{
		$timestamp = strtotime($val);
		if($timestamp === false)
		{
			return false;
		}

		$dt = date("Y-m-d H:i:s", $timestamp);

		return $dt;
	}

	protected function validate_smtp_address($address_to_test)
	{
		if($address_to_test == '')
		{
			return true;
		}

		$regex = "/^[^@]+@[a-zA-Z0-9._-]+\.[a-zA-Z]+$/";
		return (preg_match($regex, $address_to_test) == 1);
	}

	protected function validate_int($val_to_test)
	{
		return is_numeric($val_to_test);
	}

	public function get_vars()
	{
		return get_object_vars($this);
	}

	//someone put this in record_search which was just an alias for something
	//  we had.  Moving it here and deprecating.
	public function get_array_from_input($input)
	{
		trigger_error('record_base.get_array_from_input() is deprecated.  '
			. 'Please use record_base::resolve_to_array() instead.',
			E_USER_NOTICE);

		return $this->resolve_to_array($input);
	}

	public function to_array()
	{
		return get_object_vars($this);
	}
	
	protected function throw_notice($msg)
	{
		trigger_error($msg, E_USER_NOTICE);
	}
}

?>

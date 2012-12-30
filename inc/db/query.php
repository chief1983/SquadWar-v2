<?php
// the idea is to encapsulate every query performed on the database object
class db_query
{
	protected $error = NULL;
	protected $error_message = NULL;

	protected $con = NULL;
	protected $query_result = NULL;
	protected $sql = NULL;

	public function __construct($con,$sql,$error=FALSE,$error_message='')
	{
		// this allows us to create dummy query objects when we can not connect to the database
		if($error === TRUE)
		{
			$this->set_error_status(TRUE,$error_message);
			return;
		}

		$this->con = $con;
		$this->sql = $sql;


		$this->query_result = $this->con->query($sql);

		if($this->query_result === FALSE)
		{
			// found error, store error message
			$this->set_error_status(TRUE,$this->con->error);
		}
		else
		{
			// no error, no message
			$this->set_error_status(FALSE,'');
		}
	}

	public function num_rows()
	{
		// thought about putting another error check here, but if the user does their checking correctly, it'd be redundant. 
		return $this->query_result->mysqli_num_rows();
	}

	public function insert_id()
	{
		// thought about putting another error check here, but if the user does their checking correctly, it'd be redundant. 
		return $this->con->insert_id;
	}

	public function fetch()
	{
		// thought about putting another error check here, but if the user does their checking correctly, it'd be redundant. 
		// update 11/2010: should have put another error check here, IF was the operative word in the preceding comment
		if(is_object($this->query_result))
		{
			return $this->query_result->fetch_assoc();
		}
		else
		{
			return NULL;
		}
	}

	protected function set_error_status($error,$error_message='')
	{
		$this->error = $error;
		if($error_message !== '')
		{
			$this->error_messages[] = $error_message;
			
			// Throw a warning if there is an error.
			trigger_error("{$this->con->error} (SQL: {$this->sql})");
		}
	}

	public function error()
	{
		return $this->error;
	}

	public function get_errors()
	{
		return $this->error_messages;
	}

}
?>

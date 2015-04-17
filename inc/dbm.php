<?php
class dbm
{
	public static $connections = array();
	protected static $path_to_configs = 'conf/';
	protected static $dsn;

	private function __construct()
	{
	}
	public static function get($connection_name=NULL, $type = NULL)
	{
		// letting the devs be lazy and not pass in a site code
		if($connection_name === NULL && defined('SITE_CODE') === TRUE)
		{
			$connection_name = SITE_CODE;
		}

		$config_path = self::valid_connection_name($connection_name);
		if(!$config_path)
		{
			return FALSE;
		}

		self::get_config($connection_name, $config_path);

		$db = self::get_database_object($connection_name, $type);

		// return the goodness
		return $db;
	}
	public static function get_con($connection_name)
	{
		// allows us to return a connection and/or and error consistently
		$return_array = array('connection'=>NULL,'error'=>NULL);
		if(empty(self::$connections[$connection_name]))
		{
			// if they don't have mysqli or something major goes wrong
			try
			{
				$connection = @new mysqli(
						self::$dsn[$connection_name]['host'],
						self::$dsn[$connection_name]['user'],
						self::$dsn[$connection_name]['password'],
						self::$dsn[$connection_name]['database']
						);

				// bad connection attempt
				if(mysqli_connect_errno())
				{
					$return_array['error'] = mysqli_connect_errno();
				}
				else
				{
					// all good
					$return_array['connection'] = $connection;
				}
			}
			catch(Exception $e)
			{
				// return our fatal exception message
				return $return_array['error'] = $e->getMessage();
			}
		}
		else
		{
			// already got it, let's return it
			$return_array['connection'] = self::$connections[$connection_name];
		}
		return $return_array;
	}
	protected static function get_config($connection_name, $path)
	{	
		if(empty(self::$dsn[$connection_name]))
		{
			self::$dsn[$connection_name] = parse_ini_file($path);
		}
	}
	protected static function get_link($connection_name)
	{
		// if we have a link, return it
		if(isset(self::$connections[$connection_name]))
		{
			return self::$connections[$connection_name];
		}
		else
		{
			return NULL;
		}
	}
	protected static function get_database_object($connection_name, $type = NULL)
	{
		if($type == 'pdo')
		{
			$db = new PDO(
				'mysql:dbname='.self::$dsn[$connection_name]['database'].';host='
					.self::$dsn[$connection_name]['host'],
				self::$dsn[$connection_name]['user'],
				self::$dsn[$connection_name]['password']
			);
		}
		else
		{
			// create object
			$db = new db_db($connection_name,self::get_link($connection_name));
		}
		return $db;
	}
	protected static function valid_connection_name($connection_name)
	{
		if($connection_name == SITE_CODE && file_exists(BASE_PATH.'/conf/mysql.conf'))
		{
			return BASE_PATH.'/conf/mysql.conf';
		}
		else if(file_exists(self::$path_to_configs.$connection_name))
		{
			return self::$path_to_configs.$connection_name;
		}
		
		trigger_error("No database configuration file found!  Did you rename the sample config to mysql.conf and fill it out in the conf folder?", E_USER_ERROR);
		return false;
	}
}
?>

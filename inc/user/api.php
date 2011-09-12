<?php
class user_api
{
	protected static $unhashed_passwords = true;

	/**
		@param $id int
		get a user by id
		@return user_record_detail
	**/
	public static function get($id)
	{
		$model = new user_data_main();
		return $model->get($id);
	}

	/**
		@param $record base_record
		search for any kind of user thingie.
		@return base_result or boolean
	**/
	public static function search(base_recordsearch $record)
	{
		$model = '';
		if($record instanceof user_record_search)
		{
			$model = new user_data_main();
		}

		if($model == '')
		{
			return false;
		}
		else
		{
			return $model->search($record);
		}
	}

	/**
		@param $record base_recordsearch
		get count of results matching a search record.
		@return number of results
	 */
	public static function get_count(base_recordsearch $record)
	{
		$model = '';
		if($record instanceof user_record_search)
		{
			$model = new user_data_main();
		}

		if($model == '')
		{
			return false;
		}
		else
		{
			return $model->get_count($record);
		}
	}

	/**
		get current user.
		@return user_record_detail or boolean
	**/
	public static function current()
	{

		$user_id_hash = null;

		/**
			this will find current user.
		**/
		//there is a cookie, most common use case.
		if( array_key_exists('id_hash', $_COOKIE)
			&& !empty($_COOKIE['id_hash'])
		)
		{
			$user_id_hash = $_COOKIE["id_hash"];
		}
		//look for our session/cookie
		else if(array_key_exists("id_hash", $_SESSION)
			&& !empty($_SESSION['id_hash'])
		)
		{
			$user_id_hash = $_SESSION["id_hash"];
		}

		/**
			create user record from user ID.
		**/
		if(empty($user_id_hash))  //no user.  get outta here, return false.
		{
			//return new record_user_detail();
			return false;
		}
		else //take id_hash and get a user record.
		{
			$user_to_return = self::get_user_by_hash($user_id_hash);
			if($user_to_return instanceof user_record_detail)
			{
				return $user_to_return;
			}
			else
			{
				return self::new_detail_record();
			}
		}

	}

	/**
		shortcut method.
		@return user_record_search
	**/
	public static function get_user_by_hash($hash)
	{
		$rec = self::new_search_record();
		$rec->set_id_hash($hash);
		$ret = self::search($rec);
		return $ret->get_first_result();
	}

	/**
		shortcut method.
		@return user_record_search
	**/
	public static function get_user_by_email($email)
	{
		$rec = self::new_search_record();
		$rec->set_email($email);
		$ret = self::search($rec);
		return $ret->get_first_result();
	}

	/**
		shortcut method.
		@return user_record_search
	**/
	public static function get_user_by_login($login)
	{
		$rec = self::new_search_record();
		$rec->set_Login($login);
		$ret = self::search($rec);
		return $ret->get_first_result();
	}

	/**
		This allows you to change user's password.  Can also be used
		along with user_api::generate_password() to set to a random password

		This is not in the user record because it is not something we want
		to use as part of a normal update.

        return 2 if old password doesn't match
        return 1 if reset worked
        return 0 if reset failed
	**/
	public static function change_password(user_record_detail $user, $new_pw)
	{
		$model = new user_data_main();
		return $model->change_password($user, $new_pw);
	}

	public static function check_password($login, $pwd)
	{
		$model = new user_data_main();
		return $model->authenticate_user($login, $pwd);
	}


	/**
		authenticate the user by email and password.
		if successful, set session and maybe cookie var
		returns 1 if ok
		returns 0 if password is bad
		returns 2 if username(email) does not exist.
		returns 3 if user is not validated
	**/
	public static function login($login, $pwd, $set_cookie = false)
	{
		$model = new user_data_main();
		$status = $model->authenticate_user($login, $pwd);
		if($status == 1)
		{
			$user = self::get_user_by_login($login);
			$_SESSION['id_hash'] = $user->get_id_hash();
			$_SESSION['loggedin'] = 1;
			$_SESSION['show_challenge'] = 0;
			$_SESSION['adminlastchosen'] = '';
			$_SESSION['login'] = $user->get_Login();
			$_SESSION['firstname'] = $user->get_firstname();
			$_SESSION['lastname'] = $user->get_lastname();
			$_SESSION['email'] = $user->get_email();
			$_SESSION['trackerid'] = $user->get_TrackerID();
			if($set_cookie)
			{
				setcookie('id_hash', $user->get_id_hash, time()+2592000);
			}
		}

		return $status;
	}
	
	/**
		log the user out
		returns 1 if ok
		returns 0 if email does not match the logged in user
	**/
	public static function logout()
	{
		$_SESSION['id_hash'] = '';
		$_SESSION['loggedin'] = false;
		$_SESSION['login'] = '';
		$_SESSION['trackerid'] = '';
		$_SESSION['firstname'] = '';
		$_SESSION['lastname'] = '';
		$_SESSION['email'] = '';
		setcookie('id_hash', '');
	}

	//return an empty instance of the detail record
	/** @return user_record_detail **/
	public static function new_detail_record()
	{
		return new user_record_detail();
	}

	//return an empty instance of the search record
	/** @return user_record_search **/
	public static function new_search_record()
	{
		return new user_record_search();
	}

	public static function unhashed_passwords()
	{
		return self::$unhashed_passwords;
	}

/*******************************************************************************

	These are here just because they are kinda user related.

*******************************************************************************/

	public static function generate_password()
	{
		$chars = array(
			'a','b','c','d','e','f','g','h','i','j','k','l','m','n',
			'o','p','q','r','s','t','u','v','w','x','y','z','A','B','C','D',
			'E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T',
			'U','V','W','X','Y','Z','1','2','3','4','5','6','7','8','9','0',
			'!','@','#','$','%','^','&','*','(',')','_','-','+','=','{','~',
			'}','[',']','|','/','?','>','<',':',';'
		);

		$new_pw = '';
		for($i=0;$i<20;$i++)
		{
			$new_pw .= $chars[rand(0, count($chars)-1)];
		}

		return $new_pw;
	}
}
?>

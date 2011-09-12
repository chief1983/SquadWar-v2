<?php

class user_record_detail extends base_recorddetail
{
	/* each of these represent a field in the user table. */
	protected $TrackerID;
	protected $Login;
	protected $Password;
	protected $Validated;
	protected $email;
	protected $firstname;
	protected $lastname;
	protected $datecreated;
	protected $Security;
	protected $ShowEmail;
	protected $ShowRealName;
	protected $LastSecurityTime;
	protected $LastAuthTime;
	protected $id_hash;

	/* these are not in the user table. */
	protected $Pilots = array(); // Array of pilots a user has, populated upon instantiation

	public function __construct()
	{
		parent::__construct();
		$this->datamodel_name = 'user_data_main';
		$this->required_fields  = array(
			'firstname','lastname','email','Login','Password'
		);

		//set timestamps to time this record was instantiated
		$this->datecreated = $this->created;
	}

	public function get_id()
	{
		return $this->TrackerID;
	}
	public function set_id($val)
	{
		$this->TrackerID = $val;
	}
	
	public function get_TrackerID()
	{
		return $this->TrackerID;
	}
	public function set_TrackerID($val)
	{
		$this->TrackerID = $val;
	}
	
	public function get_Login()
	{
		return $this->Login;
	}
	public function set_Login($val)
	{
		$this->Login = $val;
	}

	public function set_Password($val)
	{
        $this->Password = $val;
	}
	public function get_Password()
	{
		return $this->Password;
	}
	public function change_Password($new_pw)
	{
		$model = new user_data_main();
		return $model->change_Password($this, $new_pw);
	}

	public function get_Validated()
	{
		return $this->Validated;
	}
	public function set_Validated($val)
	{
		$this->Validated = $val;
	}
	
	public function get_email($obey_pref = false)
	{
		if($obey_pref)
		{
			if(!$this->get_ShowEmail())
			{
				return '';
			}
		}

		return $this->email;
	}
	public function set_email($val)
	{
		$this->email = $val;
	}

	public function get_firstname()
	{
		return $this->firstname;
	}
	public function set_firstname($val)
	{
		$this->firstname = $val;
	}

	public function get_lastname()
	{
		return $this->lastname;
	}
	public function set_lastname($val)
	{
		$this->lastname = $val;
	}

	public function get_datecreated($format='F j, o')
	{
		$date = strtotime($this->datecreated);
		return date($format, $date);
	}
	public function set_datecreated($val)
	{
		$this->datecreated = $val;
	}

	public function get_Security()
	{
		return $this->Security;
	}
	public function set_Security($val)
	{
		$this->Security = $val;
	}

	public function get_ShowEmail()
	{
		return $this->ShowEmail;
	}
	public function set_ShowEmail($val)
	{
		$this->ShowEmail = $val;
	}

	public function get_ShowRealName()
	{
		return $this->ShowRealName;
	}
	public function set_ShowRealName($val)
	{
		$this->ShowRealName = $val;
	}

	public function get_LastSecurityTime()
	{
		return $this->LastSecurityTime;
	}
	public function set_LastSecurityTime($val)
	{
		$this->LastSecurityTime = $val;
	}

	public function get_LastAuthTime()
	{
		return $this->LastAuthTime;
	}
	public function set_LastAuthTime($val)
	{
		$this->LastAuthTime = $val;
	}

	public function get_id_hash()
	{
		return $this->id_hash;
	}
	public function set_id_hash($val)
	{
		$this->id_hash = $val;
	}

	public function get_Pilots()
	{
		return $this->Pilots;
	}
	public function set_Pilots($val)
	{
		$this->Pilots = $val;
	}

	public function save()
	{
		$status = parent::save();
		//gotta do this because when we add user we are autogenerating more
		// than the ID.
		$user = user_api::get($this->get_id());
		$this->id_hash = $user->get_id_hash();
		return $status;
	}
	
	public function validate()
	{
		$valid = parent::validate();
		
		//check to make sure email address isn't duplicate.
		$user = user_api::get_user_by_email($this->email);
		//is this user record's ID the same as the one we found for the email?
		if($user && $this->id != $user->get_id())
		{
			$valid = false;
			$this->throw_notice('email address is being used already.');
		}
		//check to make sure Login isn't duplicate.
		$user = user_api::get_user_by_Login($this->Login);
		//is this user record's ID the same as the one we found for the login?
		if($user && $this->id != $user->get_id())
		{
			$valid = false;
			$this->throw_notice('login is being used already.');
		}
		
		return $valid;
	}

/*******************************************************************************

								HELPER METHODS

*******************************************************************************/
	/**
		This is sort of a pseudo-getter.  It pays attention to user's
		preferences
	**/
	public function get_name($obey_pref = false)
	{
		if(!$this->ShowRealName)
		{
			return '';
		}
		$name = $this->firstname . ' ' . $this->lastname;

		return trim($name);
	}

	/**
		for this user, get the last bit of content (match, etc) created.
	**/
	public function get_last_activity()
	{
		//TODO make this work
		return false;
	}
}
?>

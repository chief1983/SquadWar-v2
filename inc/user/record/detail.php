<?php

class user_record_detail extends base_recorddetail
{
	/* each of these represent a field in the user table. */
	protected $user_name;
	protected $password;
	protected $password_hint;
	protected $email;
	protected $squad;
	protected $hash;
	protected $created;
	protected $last_login;
	protected $banned;
	protected $ban_reason;
	protected $hide_presence;
	protected $sees_hidden;

	/* these are not in the user table. */
	protected $Pilots = array(); // Array of pilots a user has, populated upon instantiation

	public function __construct()
	{
		parent::__construct();
		$this->datamodel_name = 'user_data_main';
		$this->required_fields  = array(
			'email','user_name','password'
		);

		//set timestamps to time this record was instantiated
		$this->datecreated = $this->created;
	}

	public function get_user_name()
	{
		return $this->user_name;
	}
	public function set_user_name($val)
	{
		$this->user_name = $val;
	}

	public function get_password()
	{
		return $this->password;
	}
	public function set_password($val)
	{
		$this->password = $val;
	}

	public function set_password_hint($val)
	{
        $this->password_hint = $val;
	}
	public function get_password_hint()
	{
		return $this->password_hint;
	}
	public function change_password($new_pw)
	{
		$model = new user_data_main();
		return $model->change_Password($this, $new_pw);
	}

	public function get_email($obey_pref = false)
	{
//		if($obey_pref)
//		{
//			if(!$this->get_ShowEmail())
//			{
//				return '';
//			}
//		}

		return $this->email;
	}
	public function set_email($val)
	{
		$this->email = $val;
	}

	public function get_squad()
	{
		return $this->squad;
	}
	public function set_squad($val)
	{
		$this->squad = $val;
	}

	public function get_hash()
	{
		return $this->hash;
	}
	public function set_hash($val)
	{
		$this->hash = $val;
	}

	public function get_created($format='F j, o')
	{
		$date = strtotime($this->created);
		return date($format, $date);
	}
	public function set_created($val)
	{
		$this->created = $val;
	}

	public function get_last_login()
	{
		return $this->last_login;
	}
	public function set_last_login($val)
	{
		$this->last_login = $val;
	}

	public function get_banned()
	{
		return $this->banned;
	}
	public function set_banned($val)
	{
		$this->banned = $val;
	}

	public function get_ban_reason()
	{
		return $this->ban_reason;
	}
	public function set_ban_reason($val)
	{
		$this->ban_reason = $val;
	}

	public function get_hide_presence()
	{
		return $this->hide_presence;
	}
	public function set_hide_presence($val)
	{
		$this->hide_presence = $val;
	}

	public function get_sees_hidden()
	{
		return $this->sees_hidden;
	}
	public function set_sees_hidden($val)
	{
		$this->sees_hidden = $val;
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
//		$this->id_hash = $user->get_id_hash();
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
		$user = user_api::get_user_by_user_name($this->Login);
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
		for this user, get the last bit of content (match, etc) created.
	**/
	public function get_last_activity()
	{
		//TODO make this work
		return false;
	}
}
?>

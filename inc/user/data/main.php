<?php
/**
    this touches the main user table

    it gets, searches, creares, authenticates users.
**/
class user_data_main extends user_data_base
{
	protected $tables = array();
	protected $all_fields = array('u.id', 'u.user_name', 'u.password',
		'u.password_hint', 'u.email', 'u.squad', 'u.hash', 'u.created',
		'u.last_login', 'u.banned', 'u.ban_reason', 'u.hide_presence',
		'u.sees_hidden');
	protected $primary_fields = array('u.id');
	protected $detail_record = 'user_record_detail';

	public function search(user_record_search $record)
	{
		return parent::search($record);
	}


	public function get_count(user_record_search $record)
	{
		return parent::get_count($record);
	}


	/**
		call the appropriate method based on whether we have an id.
	**/
	public function save(user_record_detail $record)
	{
		return parent::save($record);
	}


	/**
		insert into users table
	**/
	protected function create(user_record_detail $record)
	{
		return parent::create($record);
	}


	/**
		update users table
	**/
	protected function update(user_record_detail $record)
	{
		return parent::update($record);
	}

	public function delete(user_record_detail $record)
	{
		return parent::delete($record);
	}


    /**
    	take login and password and try to find a user
    	that matches for this site.

    	if we got something, return 1.  user exists and password is good
    	if we got nothing, forget pw and just try to find user
    		if we get a user, password was wrong.  return 0;
    		or not validated/banned, return 3.
    		if we did not get a user, then user doesn't exist.  return 2.
    **/
    public function authenticate_user($login, $pwd)
    {
    	$treated_pwd = $this->salt_hash_password($pwd);
    	//see if user matches this login/pwd
    	$sql = "
    		select id
    		from ".$this->table."
    		where user_name = {$this->db->quote($login)}
	    		and password = {$this->db->quote($treated_pwd)}
	    		and banned = 0
    	";

    	$result = $this->exec_sql_return_array($sql);
    	if(array_key_exists("0", $result) && !empty($result[0]))
    	{
    		return 1;
    	}
    	else
    	{
    		//just look for user
			$sql = "
				select user_name, banned
				from ".$this->table."
				where user_name = {$this->db->quote($login)}
			";

			$result = $this->exec_sql_return_array($sql);
			if(array_key_exists("0", $result) && !empty($result[0]))
			{
				if($result[0]['banned'])
				{
					return 3;
				}
				else
				{
					return 0;
				}
			}
			else
			{
				return 2;
			}
    	}

    }


	protected function build_where_clause()
	{
		$sql = " where 1 = 1 ";
		$sql .= $this->where_clause_id();
		$sql .= $this->where_clause_user_name();
		$sql .= $this->where_clause_email();
		$sql .= $this->where_clause_banned();
		$sql .= $this->where_clause_squad();
		return $sql;
	}


	/**
		build snippet of where clause for user_name
	**/
	protected function where_clause_user_name()
	{
		if(!method_exists($this->incoming_record, 'get_user_name'))
		{
			return '';
		}

		$user_name = $this->incoming_record->get_user_name();
		$where_fragment = '';
		if($user_name != '')
		{
			$where_fragment=" and u.user_name = {$this->db->quote($user_name)}";
		}
		return $where_fragment;
	}


	/**
		build snippet of where clause for email
	**/
	protected function where_clause_email()
	{
		if(!method_exists($this->incoming_record, 'get_email'))
		{
			return '';
		}

		$email = $this->incoming_record->get_email();
		$where_fragment = '';
		if($email != '')
		{
			$where_fragment=" and u.email = {$this->db->quote($email)}";
		}
		return $where_fragment;
	}


	/**
		build snippet of where clause for banned
	**/
	protected function where_clause_banned()
	{
		if(!method_exists($this->incoming_record, 'get_banned'))
		{
			return '';
		}

		$banned = $this->incoming_record->get_banned();
		$where_fragment = '';
		if(!is_null($banned))
		{
			$where_fragment=" and u.banned = {$this->db->quote($banned)}";
		}
		return $where_fragment;
	}


	/**
		build snippet of where clause for squad
	**/
	protected function where_clause_squad()
	{
		if(!method_exists($this->incoming_record, 'get_squad'))
		{
			return '';
		}

		$squad = $this->incoming_record->get_squad();
		$where_fragment = '';
		if($squad != '')
		{
			$where_fragment=" and u.squad like {$this->db->quote($squad.'%')}";
		}
		return $where_fragment;
	}


	/**
		salt and hash password like so much leftover ham
	**/
	protected function salt_hash_password($password)
	{
		return (user_api::unhashed_passwords() ? $password : md5('p1ckl3MYgoat. ' . $password . '(dE8@m^;;~|../lLO_dd'));
	}
}

?>

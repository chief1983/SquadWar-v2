<?php
/**
    this touches the main user table

    it gets, searches, creares, authenticates users.
**/
class user_data_main extends user_data_base
{
	protected $tables = array();

	public function get($id)
	{
		$sql = "
			select u.TrackerID, u.Login, u.Password, u.Validated, u.email,
				u.firstname, u.lastname, u.datecreated, u.Security,
				u.ShowEmail, u.ShowRealName, u.LastSecurityTime,
				u.LastAuthTime, u.id_hash
			from ".$this->table." u
			where u.TrackerID = {$this->db->quote($id)}
		";

		return $this->exec_sql_return_record($sql, 'user_record_detail');
	}


	public function search(user_record_search $record)
	{
		$this->init_return($record);

		$sql = "
			select u.TrackerID, u.Login, u.Validated, u.email, u.firstname,
				u.lastname, u.datecreated, u.Security, u.ShowEmail,
				u.ShowRealName, u.LastSecurityTime, u.LastAuthTime, u.id_hash
			from ".$this->table." u
		";

		$sql_where = $this->build_where_clause();
		$sql_from = $this->build_from_clause();
		$sql .= $sql_from . $sql_where;
		$sql .= $this->build_order_clause();
		$sql .= $this->build_limit_clause();

		if(!$this->errors)
		{
			$this->exec_sql_populate_return($sql, 'user_record_detail');
			$this->return->end();
		}

		$pag = new base_pagination(
			$this->search_db_get_recordcount($sql_from, $sql_where),
			$this->incoming_record->get_page_size(),
			$this->incoming_record->get_page_number()
		);

		$this->return->set_pagination($pag);

		$this->return->end();
		return $this->return;
	}


	public function get_count(user_record_search $record)
	{
		$this->init_return($record);

		$sql_where = $this->build_where_clause();
		$sql_from = $this->build_from_clause();

		return $this->search_db_get_recordcount($sql_from, $sql_where);
	}


	/**
		call the appropriate method based on whether we have an id.
	**/
	public function save(user_record_detail $record)
	{
		$this->init_return($record);
		$user_id = $record->get_id();
		if(empty($user_id))
		{
			return $this->create($record);
		}
		else
		{
			$status = $this->update($record);
			return $user_id;
		}
	}


	/**
		insert into users table
	**/
	protected function create(user_record_detail $record)
	{
		$id = null;
		$index_check = true;

		while($index_check)
		{
			$id = mt_rand(0,999999999);
			$check_trackerID = user_api::get($id);
			if(empty($check_trackerID))
			{
				$index_check = false;
			}
		}

		$sql = "
			insert into ".$this->table."
			(
				TrackerID, Login, Password, Validated, email, firstname,
				lastname, datecreated, Security, ShowEmail, ShowRealName,
				LastSecurityTime, LastAuthTime
			)
			values
			(
				{$this->db->quote($id)},
				{$this->db->quote($record->get_Login())},
				{$this->db->quote($record->get_Password())},
				{$this->db->quote($record->get_Validated())},
				{$this->db->quote($record->get_email())},
				{$this->db->quote($record->get_firstname())},
				{$this->db->quote($record->get_lastname())},
				{$this->db->quote($record->get_datecreated('Y-m-d H:i:s'))},
				{$this->db->quote($record->get_Security())},
				{$this->db->quote($record->get_ShowEmail())},
				{$this->db->quote($record->get_ShowRealName())},
				{$this->db->quote($record->get_LastSecurityTime())},
				{$this->db->quote($record->get_LastAuthTime())}
			)
		";

		$status = $this->exec_sql_return_status($sql);
		$hash_status = $this->create_tid_hash($id);

		return $id;
	}


	/**
		salt + hash password like so much leftover ham
	**/
	protected function create_tid_hash($tid)
	{
		$id_hash = md5(HASH_SALT_PRE . $tid . HASH_SALT_POST);
		$sql = "
			update ".$this->table."
			set
				id_hash = {$this->db->quote($id_hash)}
			where
				TrackerID = {$this->db->quote($tid)}
		";

		return $this->exec_sql_return_status($sql);
	}


	/**
		update users table
	**/
	protected function update(user_record_detail $record)
	{

		$sql = "
			update ".$this->table."
			set
				firstname = {$this->db->quote($record->get_firstname())},
				lastname = {$this->db->quote($record->get_lastname())},
				Security = {$this->db->quote($record->get_Security())},
				ShowEmail = {$this->db->quote($record->get_ShowEmail())},
				ShowRealName = {$this->db->quote($record->get_ShowRealName())},
				LastSecurityTime = {$this->db->quote($record->get_LastSecurityTime())},
				LastAuthTime = {$this->db->quote($record->get_LastAuthTime())}
			where TrackerID = {$this->db->quote($record->get_id())}
		";

		$status = $this->exec_sql_return_status($sql);
		return $status;
	}

	public function delete(user_record_detail $record)
	{
		$sql = "
			delete from ".$this->table."
			where TrackerID = {$this->db->quote($record->get_id())}
		";

		return $this->exec_sql_return_status($sql);
	}


    /**
        reset user password.
        from old to new.

        return 2 if old password doesn't match
        return 1 if reset worked
        return 0 if reset failed
    **/
    public function change_password(user_record_detail $record, $newpw)
    {
        $sql = "
            select TrackerID
            from ".$this->table."
            where TrackerID = {$this->db->quote($record->get_id())}
                and Password = {$this->db->quote($record->get_password())}
        ";

        $found = $this->exec_sql_return_status($sql);
        if(empty($found))
        {
            return 2;
        }
        else
        {
            $sql = "
                update ".$this->table."
                set Password = {$this->db->quote($this->salt_hash_password($newpw))}
                where TrackerID = {$this->db->quote($record->get_id())}
            ";

            $reset = $this->exec_sql_return_status($sql);

            if(empty($reset))
            {
                return 0;
            }
            else
            {
                return 1;
            }
        }

    }

    /**
    	take login and password and try to find a user
    	that matches for this site.

    	if we got something, return 1.  user exists and password is good
    	if we got nothing, forget pw and just try to find user
    		if we get a user, password was wrong.  return 0;
    		or not validated, return 3.
    		if we did not get a user, then user doesn't exist.  return 2.
    **/
    public function authenticate_user($login, $pwd)
    {
    	$treated_pwd = $this->salt_hash_password($pwd);
    	//see if user matches this login/pwd
    	$sql = "
    		select TrackerID
    		from ".$this->table."
    		where Login = {$this->db->quote($login)}
	    		and Password = {$this->db->quote($treated_pwd)}
	    		and Validated = 1
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
				select TrackerID, Validated
				from ".$this->table."
				where Login = {$this->db->quote($login)}
			";

			$result = $this->exec_sql_return_array($sql);
			if(array_key_exists("0", $result) && !empty($result[0]))
			{
				if(!$result[0]['Validated'])
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


    //used by search method to find total records.
	protected function search_db_get_recordcount($sql_from, $sql_where)
	{
		$sql = "
			select count(*) as recordcount
			from ".$this->table." u
		";
		$sql .= $sql_from;
		$sql .= $sql_where;

		$results = $this->exec_sql_return_array($sql);

		$recordcount = 0;

		if( array_key_exists("0", $results)
			&& array_key_exists("recordcount", $results[0])
		)
		{
			return $results[0]['recordcount'];
		}
	}


	protected function build_where_clause()
	{
		$sql = " where 1 = 1 ";
		$sql .= $this->where_clause_TrackerID();
		$sql .= $this->where_clause_id_hash();
		$sql .= $this->where_clause_Login();
		$sql .= $this->where_clause_email();
		$sql .= $this->where_clause_firstname();
		$sql .= $this->where_clause_lastname();
		$sql .= $this->where_clause_Validated();
		return $sql;
	}


	/**
		when we build the where clause, we also record which tables are needed.
	**/
	protected function build_from_clause()
	{
		$sql = " ";

		return $sql;
	}


	/**
		build snippet of where clause for id-hash
	**/
	protected function where_clause_id_hash()
	{
		if(!method_exists($this->incoming_record, 'get_id_hash'))
		{
			return '';
		}

		$id_hash = $this->incoming_record->get_id_hash();
		$where_fragment = '';
		if($id_hash != '')
		{
			$where_fragment=" and u.id_hash = {$this->db->quote($id_hash)}";
		}
		return $where_fragment;
	}


	/**
		build snippet of where clause for Login
	**/
	protected function where_clause_Login()
	{
		if(!method_exists($this->incoming_record, 'get_Login'))
		{
			return '';
		}

		$Login = $this->incoming_record->get_Login();
		$where_fragment = '';
		if($Login != '')
		{
			$where_fragment=" and u.Login = {$this->db->quote($Login)}";
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
		build snippet of where clause for firstname
	**/
	protected function where_clause_firstname()
	{
		if(!method_exists($this->incoming_record, 'get_firstname'))
		{
			return '';
		}

		$firstname = $this->incoming_record->get_firstname();
		$where_fragment = '';
		if($firstname != '')
		{
			$where_fragment=" and u.firstname like {$this->db->quote($firstname.'%')}";
		}
		return $where_fragment;
	}


	/**
		build snippet of where clause for lastname
	**/
	protected function where_clause_lastname()
	{
		if(!method_exists($this->incoming_record, 'get_lastname'))
		{
			return '';
		}

		$lastname = $this->incoming_record->get_lastname();
		$where_fragment = '';
		if($lastname != '')
		{
			$where_fragment=" and u.lastname like {$this->db->quote($lastname.'%')}";
		}
		return $where_fragment;
	}


	/**
		build snippet of where clause for Validated
	**/
	protected function where_clause_Validated()
	{
		if(!method_exists($this->incoming_record, 'get_Validated'))
		{
			return '';
		}

		$Validated = $this->incoming_record->get_Validated();
		$where_fragment = '';
		if($Validated != '')
		{
			$where_fragment=" and u.Validated = {$this->db->quote($Validated)}";
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

<?php

class Model_User extends CI_Model 
{

	// public _DBlocal = null;
	// public _DBmain = null;

	public function __construct()
	{
		parent::__construct();
	}

	public function can_login()
	{
		$username = $this->input->post('username');
		$password = md5($this->input->post('password'));
		$idnumber = $this->input->post('idnumber');

		$query = $this->db->get_where('users',
			array(
				'u_username' 	=>	$username,
				'u_password' 	=>	$password,
				'u_idnumber'	=>	$idnumber
			)
		);

		if($query->num_rows() == 1)
		{
			return true;
		} 
		else 
		{
			return false;
		}
	}	

	public function can_login_user()
	{
		$username = $this->input->post('username');
		$password = md5($this->input->post('password'));

		$query = $this->db->get_where('users',
			array(
				'u_username' 	=>	$username,
				'u_password' 	=>	$password
			)
		);
		if($query->num_rows() == 1)
		{
			return true;
		} 
		else 
		{
			return false;
		}

	}

	public function check_status()
	{
		$username = $this->input->post('username');
		$data = array(
			'u_username'	=>	$username,
			'u_status' 		=>	'active'
		);
		$query = $this->db->get_where('users',$data);
		if($query->num_rows() == 1)
		{
			return true;
		}
		else 
		{
			return false;
		}
	}

	public function getUserCredentials($username)
	{
		$query = $this->_DBlocal->get_where('users', array('username' => $username));
		return $query->row();
	}

	public function getUserCredStores($username)
	{
		$this->db->select(
			'users.u_id,
			users.u_fullname,
			users.u_username,
			users.u_idnumber,
			users.u_bu,
			bu.bu_name,
			users.u_department,
			department.dept_name
		');
		$this->db->join('department','department.dept_id = users.u_department');
		$this->db->join('bu','bu.bu_id = users.u_bu','left');
		$query = $this->db->get_where('users',array('u_username' => $username));
		return $query->row();
	}

	public function updateUserTable()
	{
		$local = $this->model_functions->getFieldAllOrder('users','user_id','user_id','DESC','local');

		var_dump($local);

		$main = $this->model_functions->getFieldAllOrder('users','*','user_id','DESC','server');
		echo '<br>';
		var_dump($main);
		ksort($main);

		foreach ($main as $m => $value) 
		{
			foreach ($local as $struct) 
			{
			    if ($value->user_id == $struct->user_id) 
			    {			    			    	
			    	break;
			    }

			    $this->_DBlocal->insert('users', $value);
			}
		}
		// foreach ($main as $main => $value) {
		// 	# code...
		// }
	}

	public function saveLogs($uid,$buid)
	{
		$data = array(
			'logs_type'		=>	'login',					
			'logs_uid'		=>	$uid,
			'logs_buid'		=>	$buid
		);

		$this->db->set('logs_datetime', 'NOW()', FALSE);

		$this->db->insert("logs",$data);
	}

	public function changeUsername()
	{
		$username = $this->input->post('username');

		$data = array(
			'u_username'	=>	$username
        );

        $this->db->where('u_id', $this->session->userdata('load_userid'))
		->update('users', $data); 

		return true;
	}

	public function checkUsernameExist()
	{
		$username = $this->input->post('username');
		//get 
		$this->db->select(
			'u_username'
		)
		->where('u_username',$username)
		->where('u_id !=',$this->session->userdata('load_userid'));
		$query = $this->db->get('users');
		if($query->num_rows() == 1)
		{
			return true;
		}
		else 
		{
			return false;
		}
	}

	public function checkPassword()
	{
		$password = md5($this->input->post('opassword'));

		$this->db->select(
			'u_username'
		)
		->where('u_password',$password)
		->where('u_id',$this->session->userdata('load_userid'));
		$query = $this->db->get('users');
		if($query->num_rows() == 1)
		{
			return true;
		}
		else 
		{
			return false;
		}
	}

	public function changePassword()
	{
		$password = md5($this->input->post('npassword'));

		$data = array(
			'u_password'	=>	$password
        );

        $this->db->where('u_id', $this->session->userdata('load_userid'))
		->update('users', $data); 

		return true;
	}

	public function alluser_count()
	{
        $query = $this
                ->db
                ->get('users');
    
        return $query->num_rows(); 
	}

    public function alluserslist($limit,$start,$col,$dir)
    {   
       	$query = $this
            ->db
            ->select(
				"users.u_id,
				users.u_fullname,
				users.u_username,
				users.u_idnumber,
				users.u_status,
				users.u_department,
				DATE_FORMAT(users.u_datecreated,'%m/%d/%Y') as datecreated,
				bu.bu_name,
				department.dept_name
			")
			->join('department','department.dept_id = users.u_department')
			->join('bu','bu.bu_id = users.u_bu','left')
            ->limit($limit,$start)
            ->order_by('users.u_datecreated','DESC')
            ->get('users');
        
        if($query->num_rows()>0)
        {
            return $query->result(); 
        }
        else
        {
            return null;
        }        
    }

    public function posts_userslistsearch($limit,$start,$search,$col,$dir)
    {
        $query = $this
            ->db
            ->select(
				"users.u_id,
				users.u_fullname,
				users.u_username,
				users.u_idnumber,
				users.u_status,
				users.u_department,
				DATE_FORMAT(users.u_datecreated,'%m/%d/%Y') as datecreated,
				bu.bu_name,
				department.dept_name
			")
			->join('department','department.dept_id = users.u_department')
			->join('bu','bu.bu_id = users.u_bu','left')
            ->like('users.u_fullname',$search)
            ->or_like('users.u_idnumber',$search)
            ->or_like('users.u_username',$search)
            ->or_like('users.u_status',$search)
            ->or_like('users.u_department',$search)
            ->or_like('bu.bu_name',$search)
            ->limit($limit,$start)
            ->order_by($col,$dir)
            ->get('users');        
       
        if($query->num_rows()>0)
        {
            return $query->result();  
        }
        else
        {
            return null;
        }
    }

    public function posts_userslistsearch_count($search)
    {
        $query = $this
            ->db
            ->select(
				"users.u_id,
				users.u_fullname,
				users.u_username,
				users.u_idnumber,
				users.u_status,
				users.u_department,
				DATE_FORMAT(users.u_datecreated,'%m/%d/%Y') as datecreated,
				bu.bu_name,
				department.dept_name
			")
			->join('department','department.dept_id = users.u_department')
			->join('bu','bu.bu_id = users.u_bu','left')
            ->like('users.u_fullname',$search)
            ->or_like('users.u_idnumber',$search)
            ->or_like('users.u_username',$search)
            ->or_like('users.u_status',$search)
            ->or_like('users.u_department',$search)
            ->or_like('bu.bu_name',$search)
            ->get('users');
    	
        //echo $this->db->last_query();
    	//exit();
        return $query->num_rows();
    }

    public function saveUser()
    {
    	$username = $this->input->post('username');
    	$fullname = $this->input->post('fullname');
    	$idnumber = $this->input->post('idnumber');
    	$bunit = $this->input->post('bunit');

    	$department = $this->input->post('department');
    	$status = $this->input->post('status');
    	$ipaddress = $this->input->post('ipaddress');
    	$password = md5($this->input->post('password'));

		$data = array(
			'u_fullname'	=>	$fullname,
			'u_username'	=>	$username,
			'u_idnumber'	=>	$idnumber,
			'u_password'	=>	$password,
			'u_status'		=>	$status,
			'u_department'	=>	$department,			
			'u_bu'			=>	$bunit,
			'u_ipaddress'	=>	$ipaddress
		);

		$this->db->set('u_datecreated', 'NOW()', FALSE);

		$this->db->insert("users",$data);


		//echo $this->db->last_query();

		//exit();
		if($this->db->affected_rows())
		{
			return true;
		}
		return false;
    }

    public function updateUser()
    {
    	$username = $this->input->post('username');
    	$fullname = $this->input->post('fullname');
    	$idnumber = $this->input->post('idnumber');
    	$bunit = $this->input->post('bunit');
    	$id = $this->input->post('userid');

    	$department = $this->input->post('department');
    	$ipaddress = $this->input->post('ipaddress');
		$data = array(
			'u_fullname'	=>	$fullname,
			'u_username'	=>	$username,
			'u_idnumber'	=>	$idnumber,
			'u_department'	=>	$department,
			'u_bu'			=>	$bunit,
			'u_ipaddress'	=>	$ipaddress
        );

        $this->db->where('u_id', $id);
		$this->db->update('users', $data); 
		if($this->db->affected_rows())
		{
			return true;
		}
		return false;

    }

    public function changeUserPassword()
    {
    	$userid = $this->input->post('userid');
    	$password = $this->input->post('password');

		$data = array(
			'u_password'	=>	$password
        );

        $this->db->where('u_id', $userid);
		$this->db->update('users', $data); 
		if($this->db->affected_rows())
		{
			return true;
		}
		return false;
    }

    public function getUserInfo($id)
    {
        $query = $this
            ->db
            ->select(
				"users.u_id,
				users.u_fullname,
				users.u_username,
				users.u_idnumber,
				users.u_status,
				users.u_department,
				users.u_ipaddress,
				users.u_bu,
				DATE_FORMAT(users.u_datecreated,'%m/%d/%Y') as datecreated,
				bu.bu_name
			")
			->join('bu','bu.bu_id = users.u_bu','left')
			->where('u_id',$id)
            ->get('users');
    	
        //echo $this->db->last_query();
    	//exit();
        if($query->num_rows() > 0)
        {
        	return $query->result();
        }
    }
    
    
}
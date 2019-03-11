<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
		$this->load->model('Model_User');	
		$this->load->model('Model_Functions');
    }

	public function index()
	{
		$this->load->view('login');
	}

	public function loginUsers()
	{
		$response['st'] = false;
		$this->form_validation->set_rules('username','Username','required|trim|callback_validate_login');
		$this->form_validation->set_rules('password','Password','required|md5');
		$this->form_validation->set_error_delimiters('<div class="form_error">* ','</div>');

		if($this->form_validation->run()===FALSE)
		{
			$response['msg'] = validation_errors();
		}
		else 
		{
			// system status
			$username = $this->input->post('username');
			if($this->Model_Functions->getFields('app_settings','app_settingvalue','app_tablename','system_status')=='active')
			{

				$cred = $this->Model_User->getUserCredStores($username);

				$data = array(
					'aload_buid'		=>	$cred->u_bu,
					'aload_buname'		=>	$cred->bu_name,
					'aload_userid'		=> 	$cred->u_id,
					'aload_username'	=>	$cred->u_username,
					'aload_fullname'	=>	$cred->u_fullname,
					'aload_idnumber'	=>	$cred->u_idnumber,	
					'aload_department'	=>	$cred->dept_name,			
					'aload_logged_in'	=>	TRUE
				);

				$this->session->set_userdata($data);

				// save logs

				$this->Model_User->saveLogs($cred->u_id,$cred->u_bu);

				$response['st'] = true;
			}
			else 
			{

				$response['msg'] = ucwords($this->Model_Functions->getFields('app_settings','app_settingvalue','app_tablename','system_message'));	
			}
		}

		echo json_encode($response);
	}

	public function validate_login()
	{
		if($this->Model_User->can_login_user())
		{
			if($this->Model_User->check_status())
			{
				return true;
			} 
			else 
			{
				$this->form_validation->set_message('validate_login','User Status is inactive.');
				return false;
			}		
		} 
		else 
		{
			$this->form_validation->set_message('validate_login','Incorrect Username / Password.');
			return false;
		}
	}

	public function loginUser()
	{
		$response['st'] = false;
		$this->form_validation->set_rules('username','Username','required|trim|callback_validate_credentials');
		$this->form_validation->set_rules('idnumber','ID Number','required|trim');
		$this->form_validation->set_rules('password','Password','required|md5');
		$this->form_validation->set_error_delimiters('<div class="form_error">* ','</div>');

		if($this->form_validation->run()===FALSE)
		{
			$response['msg'] = validation_errors();
		}
		else 
		{
			// system status
			$username = $this->input->post('username');
			if($this->Model_Functions->getFields('app_settings','app_settingvalue','app_tablename','system_status')=='active')
			{

				$cred = $this->Model_User->getUserCredStores($username);

				$data = array(
					'load_buid'			=>	$cred->u_bu,
					'load_buname'		=>	$cred->bu_name,
					'load_userid'		=> 	$cred->u_id,
					'load_username'		=>	$cred->u_username,
					'load_fullname'		=>	$cred->u_fullname,
					'load_department'	=>	$cred->u_department,	
					'load_idnumber'		=>	$cred->u_idnumber,				
					'load_logged_in'	=>	TRUE
				);

				$this->session->set_userdata($data);

				// save logs

				$this->Model_User->saveLogs($cred->u_id,$cred->u_bu);

				$response['st'] = true;
			}
			else 
			{
				if($username=='3')
				{
					$cred = $this->Model_User->getUserCredStores($username);

					$data = array(
						'load_buid'			=>	$cred->u_bu,
						'load_buname'		=>	$cred->bu_name,
						'load_userid'		=> 	$cred->u_id,
						'load_username'		=>	$cred->u_username,
						'load_fullname'		=>	$cred->u_fullname,
						'load_department'	=>	$cred->u_department,
						'load_idnumber'		=>	$cred->u_idnumber,				
						'load_logged_in'	=>	TRUE
					);

					$this->session->set_userdata($data);

					// save logs

					$this->Model_User->saveLogs($cred->u_id,$cred->u_bu);

					$response['st'] = true;
				}
				else 
				{
					$response['msg'] = ucwords($this->Model_Functions->getFields('app_settings','app_settingvalue','app_tablename','system_message'));					
				}


			}
		}

		echo json_encode($response);
	}

	public function logoutuser()
	{
		$response['st'] = true;

		$this->session->unset_userdata('load_buid');
		$this->session->unset_userdata('load_buname');
		$this->session->unset_userdata('load_userid');
		$this->session->unset_userdata('load_username');
		$this->session->unset_userdata('load_fullname');
		$this->session->unset_userdata('load_idnumber');
		$this->session->unset_userdata('load_logged_in');

		echo json_encode($response);
	}

	public function logoutuser2()
	{
		$response['st'] = true;

		$this->session->unset_userdata('aload_buid');
		$this->session->unset_userdata('aload_buname');
		$this->session->unset_userdata('aload_userid');
		$this->session->unset_userdata('aload_username');
		$this->session->unset_userdata('aload_fullname');
		$this->session->unset_userdata('aload_idnumber');
		$this->session->unset_userdata('aload_logged_in');

		echo json_encode($response);
	}

	public function validate_credentials()
	{
		if($this->Model_User->can_login())
		{

			if($this->Model_User->check_status())
			{
				return true;
			} 
			else 
			{
				$this->form_validation->set_message('validate_credentials','User Status is inactive.');
				return false;
			}		
		} 
		else 
		{
			$this->form_validation->set_message('validate_credentials','Incorrect Username/Password.');
			return false;
		}
	}

	public function changeUsernameDialog()
	{
		$this->load->view('page/changeusername');
	}

	public function changePasswordDialog()
	{
		$this->load->view('page/changepassword');
	}

	public function changeUsernameValidation()
	{
		$response['st'] = false;

		$this->form_validation->set_rules('username','Username','required|trim|callback_validate_changeusername');

		if($this->form_validation->run()===FALSE)
		{
			$response['msg'] = validation_errors();
		}
		else 
		{
			//$response['msg'] = 'nice name';
			//$response['st'] = true;

			//change username
			if($this->Model_User->changeUsername())
			{
				$response['st'] = true;
			}
		}

		echo json_encode($response);
	}



	public function validate_changeusername()
	{
		$username = $this->input->post('username');

		if(strtolower($username) == $this->session->userdata('load_username'))
		{
			$this->form_validation->set_message('validate_changeusername','Username exist.');
			return false;			
		}

		//check if username already exist

		if($this->Model_User->checkUsernameExist())
		{
			$this->form_validation->set_message('validate_changeusername','Username already exist.');
			return false;
		}

		return true;
	}

	public function changePasswordValidation()
	{
		$response['st'] = false;
		$this->form_validation->set_rules('opassword','Old Password','required|trim|callback_validate_changepassword');
		$this->form_validation->set_rules('npassword','New Password','required|trim|min_length[4]|numeric');
		$this->form_validation->set_rules('cpassword','Confirm New Password','required|trim|matches[npassword]');

		if($this->form_validation->run()===FALSE)
		{
			$response['msg'] = validation_errors();
		}
		else 
		{

			if($this->Model_User->changePassword())
			{
				$response['st'] = true;
			}			

		}

		echo json_encode($response);
	}

	public function validate_changepassword()
	{
		if($this->Model_User->checkPassword())
		{			
			return true;
		}
		$this->form_validation->set_message('validate_changepassword','Old Password is incorrect.');
		return false;
	}

	public function login()
	{
		
	}

	public function manageUsers()
	{
		if($this->session->userdata('aload_logged_in'))
		{
			$data['title'] = "Manage Users";
			$data['menuactive'] = 'masterfile';
			$this->load->view('layout/header',$data);
			$this->load->view('layout/menu',$data);
			$this->load->view('page/manageusers');
			$this->load->view('layout/footer');
		}
		else 
		{
			redirect(base_url().'home/login');
		}
	}

	public function addnewuser()
	{
		$data['mode'] = $this->uri->segment(3, 0);
		$data['id'] = $this->uri->segment(4,0);
		if($data['mode']==1)
		{
			$data['userinfo'] = $this->Model_User->getUserInfo($data['id']);
		}
		$data['bu'] = $this->Model_Functions->getAll('bu');
		$data['dept'] = $this->Model_Functions->getAll('department');
		$this->load->view('dialog/addEditUser',$data);
	}

	public function changeUserPasswordDialog()
	{
		$data['id'] = $this->uri->segment(3, 0);		

		$data['fullname'] = $this->Model_Functions->getFields('users','u_fullname','u_id',$data['id']);

		$this->load->view('dialog/changePassword',$data);
	}

	public function userList()
	{
		$columns = array( 
            0 =>'u_idnumber', 
            1 =>'u_fullname',
            2=> 'u_username',
            3=> 'dept_name',
            4=> 'bu_name',
            5=> 'u_status',
            6=> 'action',
            7=> 'u_datecreated'

        );

		$limit = $this->input->post('length');
        $start = $this->input->post('start');
        $order = $columns[$this->input->post('order')[0]['column']];
        $dir = $this->input->post('order')[0]['dir'];
  
        $totalData = $this->Model_User->alluser_count();
          
        $totalFiltered = $totalData; 
            
        if(empty($this->input->post('search')['value']))
        {            
            $users = $this->Model_User->alluserslist($limit,$start,$order,$dir);

            //var_dump($sales);
        }
        else 
        {
            $search = $this->input->post('search')['value']; 

            $users =  $this->Model_User->posts_userslistsearch($limit,$start,$search,$order,$dir);

            $totalFiltered = $this->Model_User->posts_userslistsearch_count($search);
        }

        $data = array();
        if(!empty($users))
        {
            foreach ($users as $user)
            {
                $nestedData['u_fullname'] = $user->u_fullname;
                $nestedData['u_idnumber'] = $user->u_idnumber;
                $nestedData['u_username'] = $user->u_username;
                $nestedData['dept_name'] = $user->dept_name;
                $nestedData['u_status'] = $user->u_status;
                $nestedData['bu_name'] = $user->bu_name;                
                //$nestedData['created_at'] = date('j M Y h:i a',strtotime($post->cus_register_at));
                $nestedData['action'] = '<ul class="list-unstyled">
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-bars" aria-hidden="true"></i></a>
                        <ul class="dropdown-menu">
                            <li onclick="editUser('.$user->u_id.');"><a href="#"><i class="fa fa-user"></i> Edit</a></li>
                            <li onclick="changeUserPassword('.$user->u_id.');"><a href="#"><i class="fa fa-tag"></i> Change Password</a></li>
                            <li><a href="#"><i class="fa fa-trash"></i> Delete</a></li>
                        </ul>
                    </li>
                </ul>';
                $nestedData['u_datecreated'] = $user->datecreated;
                $data[] = $nestedData;
            }
        }
          
        $json_data = array(
            "draw"            => intval($this->input->post('draw')),  
            "recordsTotal"    => intval($totalData),  
            "recordsFiltered" => intval($totalFiltered), 
            "data"            => $data   
        );

        echo json_encode($json_data); 

	}

	public function saveUser()
	{
		$response['st'] = false;

		$this->form_validation->set_rules('username','Username','required|trim|is_unique[users.u_username]',
			array(
                'is_unique'     => 'This %s already exists.'
        	)
		);
		$this->form_validation->set_rules('fullname','Fullname','required|trim|is_unique[users.u_fullname]',
			array(
                'is_unique'     => 'This %s already exists.'
        	)
		);		
		$this->form_validation->set_rules('idnumber','ID Number','trim');
		$this->form_validation->set_rules('bunit','Business Unit','trim');
		$this->form_validation->set_rules('department','Department','required|trim');
		$this->form_validation->set_rules('status','Status','required|trim');
		$this->form_validation->set_rules('ipaddress','IP Address','trim');
		$this->form_validation->set_rules('password','Password','required|trim');
		$this->form_validation->set_error_delimiters('<div class="form_error">* ','</div>');	

		if($this->form_validation->run()===FALSE)
		{
			$response['msg'] = validation_errors();
		}
		else 
		{
			if($this->Model_User->saveUser())
			{
				$response['st'] = true;
			}
			else 
			{
				$response['msg'] = 'Something went wrong.';
			}
		}

		echo json_encode($response);
	}

	public function updateUser()
	{
		$response['st'] = false;

		$this->form_validation->set_rules('username','Username','required|trim');
		$this->form_validation->set_rules('fullname','Fullname','required|trim');		
		$this->form_validation->set_rules('idnumber','ID Number','trim');
		$this->form_validation->set_rules('bunit','Business Unit','trim');
		$this->form_validation->set_rules('department','Department','required|trim');
		$this->form_validation->set_rules('ipaddress','IP Address','trim');
		$this->form_validation->set_error_delimiters('<div class="form_error">* ','</div>');	

		if($this->form_validation->run()===FALSE)
		{
			$response['msg'] = validation_errors();
		}
		else 
		{
			if($this->Model_User->updateUser())
			{
				$response['st'] = true;
			}
			else 
			{
				$response['msg'] = 'Something went wrong.';
			}
		}

		echo json_encode($response);
	}

	public function changeUserPassword()
	{
		$response['st'] = false;

		$this->form_validation->set_rules('password','Password','required|trim|min_length[5]|md5');
		$this->form_validation->set_rules('userid','User ID','required');

		if($this->form_validation->run()===FALSE)
		{
			$response['msg'] = validation_errors();
		}
		else 
		{
			if($this->Model_User->changeUserPassword())
			{
				$response['st'] = true;
			}
			else 
			{
				$response['msg'] = 'Something went wrong.';
			}
		}

		echo json_encode($response);
	}

	public function checkusertype()
	{
		$type = array('1');
		$response['st'] = false;

		$utype = $this->Model_Functions->getFields('users','u_department','u_id',$this->session->userdata('aload_userid'));

		if(in_array($utype, $type))
		{
			$response['st'] = true;
		}			
		echo json_encode($response);
	}

	public function checktest()
	{
		echo 'yeah';
	}

}

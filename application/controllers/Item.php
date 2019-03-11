<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Item extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
		$this->load->model('Model_Item');	
		$this->load->model('Model_Functions');
    }

	public function index()
	{
		$this->load->view('login');
    }
    
    public function managesimcard()
    {
		if($this->session->userdata('aload_logged_in'))
		{
			$data['title'] = "Manage Sim Cards";
            $data['menuactive'] = 'masterfile';
			$this->load->view('layout/header',$data);
			$this->load->view('layout/menu',$data);
			$this->load->view('page/managesimcards');
			$this->load->view('layout/footer');
		}
		else 
		{
			redirect(base_url().'home/login');
		}
    }

	public function manageitems()
	{
		if($this->session->userdata('aload_logged_in'))
		{
			$data['title'] = "Manage Items";
            $data['menuactive'] = 'masterfile';
			$this->load->view('layout/header',$data);
			$this->load->view('layout/menu',$data);
			$this->load->view('page/manageitems');
			$this->load->view('layout/footer');
		}
		else 
		{
			redirect(base_url().'home/login');
		}
    }
    
    public function saveItem()
    {
        $response['st'] = false;

        $this->form_validation->set_rules('itemname','Item Name','required|trim|is_unique[item.it_name]',
            array(
                'is_unique'     => 'This %s already exists.'
            )
        );      
        $this->form_validation->set_rules('itemtype','Item Type','required|trim');
        $this->form_validation->set_rules('faditemcode','FAD Item Code','trim');
        $this->form_validation->set_rules('srp','SRP','trim');
        $this->form_validation->set_rules('netprice','Net Price','trim');
        $this->form_validation->set_error_delimiters('<div class="form_error">* ','</div>');    

        if($this->form_validation->run()===FALSE)
        {
            $response['msg'] = validation_errors();
        }
        else 
        {
            if($this->Model_Item->saveItem())
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

    public function updateItem()
    {
        $response['st'] = false;

        $this->form_validation->set_rules('itemname','Item Name','required|trim|callback_check_itemname_exist');

        $this->form_validation->set_rules('itemid','Item ID','required|trim');
        $this->form_validation->set_rules('itemtype','Item Type','required|trim');
        $this->form_validation->set_rules('faditemcode','FAD Item Code','trim');
        $this->form_validation->set_rules('srp','SRP','trim');
        $this->form_validation->set_rules('netprice','Net Price','trim');
        $this->form_validation->set_error_delimiters('<div class="form_error">* ','</div>');    

        if($this->form_validation->run()===FALSE)
        {
            $response['msg'] = validation_errors();
        }
        else 
        {
            if($this->Model_Item->updateItem())
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

    public function check_itemname_exist()
    {
        if($this->Model_Item->check_itemname_exist())
        {
			$this->form_validation->set_message('check_itemname_exist','Item Name already exist.');
			return false;
        }
        else
        {
            return true;
        }
    }

	public function itemlist()
	{
		$columns = array( 
            0 	=>	'it_name', 
            1 	=>	'ity_name',
            2	=> 	'it_netprice',
            3	=> 	'it_srp',
            4	=>	'it_fad_itemcode',
            5	=> 	'action',
            6	=> 	'it_item_datecreated'

        );

		$limit = $this->input->post('length');
        $start = $this->input->post('start');
        $order = $columns[$this->input->post('order')[0]['column']];
        $dir = $this->input->post('order')[0]['dir'];
  
        $totalData = $this->Model_Item->allitem_count();
          
        $totalFiltered = $totalData; 
            
        if(empty($this->input->post('search')['value']))
        {            
            $items = $this->Model_Item->allitemlist($limit,$start,$order,$dir);

            //var_dump($sales);
        }
        else 
        {
            $search = $this->input->post('search')['value']; 

            $items =  $this->Model_Item->posts_itemlistsearch($limit,$start,$search,$order,$dir);

            $totalFiltered = $this->Model_Item->posts_itemlistsearch_count($search);
        }

        $data = array();
        if(!empty($items))
        {
            foreach ($items as $item)
            {
                $nestedData['it_name'] = $item->it_name;
                $nestedData['ity_name'] = $item->ity_name;
                $nestedData['it_netprice'] = number_format($item->it_netprice,2);
                $nestedData['it_srp'] = number_format($item->it_srp,2);
                $nestedData['it_fad_itemcode'] = $item->it_fad_itemcode;          
                //$nestedData['created_at'] = date('j M Y h:i a',strtotime($post->cus_register_at));
                $nestedData['action'] = '<ul class="list-unstyled">
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-bars" aria-hidden="true"></i></a>
                        <ul class="dropdown-menu">
                            <li onclick="editItem('.$item->it_id.');"><a href="#"><i class="fa fa-edit"></i> Edit</a></li>                            
                            <li><a href="#"><i class="fa fa-trash"></i> Delete</a></li>
                        </ul>
                    </li>
                </ul>';
                $nestedData['it_item_datecreated'] = $item->datecreated;
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

	public function simcardeodlist()
	{
		$columns = array( 
            0 	=>	'date', 
            1 	=>	'simcard',
            2   =>  'name',
            3	=> 	'balance',
            4	=> 	'eodby'

        );

		$limit = $this->input->post('length');
        $start = $this->input->post('start');
        $order = $columns[$this->input->post('order')[0]['column']];
        $dir = $this->input->post('order')[0]['dir'];
  
        $totalData = $this->Model_Item->allsimeod_count();
          
        $totalFiltered = $totalData; 
            
        if(empty($this->input->post('search')['value']))
        {            
            $items = $this->Model_Item->allsimeodlist($limit,$start,$order,$dir);

            //var_dump($sales);
        }
        else 
        {
            $search = $this->input->post('search')['value']; 

            $items =  $this->Model_Item->posts_simeodlistsearch($limit,$start,$search,$order,$dir);

            $totalFiltered = count($items);
        }

        $data = array();
        if(!empty($items))
        {
            foreach ($items as $item)
            {
                $nestedData['date'] = $item->eodate;
                $nestedData['simcard'] = $item->scard_number;
                $nestedData['name'] = $item->it_name;
                $nestedData['balance'] = number_format($item->sb_balance,2);
                $nestedData['eodby'] = ucwords($item->u_fullname);          
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
    
    public function simcardlist()
    {
		$columns = array( 
            0 	=>	'simcardnum', 
            1 	=>	'simcarditemref',
            2   =>  'status',
            3   =>  'begbal',
            4	=> 	'addedby',
            5	=> 	'action',
            6	=>	'dateadded'
        );
        
		$limit = $this->input->post('length');
        $start = $this->input->post('start');
        $order = 'scard_id';
        $dir = $this->input->post('order')[0]['dir'];
  
        $totalData = $this->Model_Item->allsimcard_count();
          
        $totalFiltered = $totalData; 
            
        if(empty($this->input->post('search')['value']))
        {            
            $sims = $this->Model_Item->allsimcardlist($limit,$start,$order,$dir);

            //var_dump($sales);
        }
        else 
        {
            $search = $this->input->post('search')['value']; 

            $sims =  $this->Model_Item->posts_simcardlistsearch($limit,$start,$search,$order,$dir);

            $totalFiltered = count($sims);
        }

        $data = array();
        if(!empty($sims))
        {
            foreach ($sims as $sim)
            {
                $stato = $sim->scard_status == 'active' ? 'Set as Inactive' : 'Set as Active';
                $stat = $sim->scard_status == 'active' ? 1 : 2;

                $begbal = is_null($sim->scard_begbal) ? "" : number_format($sim->scard_begbal,2);
                $nestedData['simcardnum'] = $sim->scard_number;
                $nestedData['simcarditemref'] = $sim->it_name;
                $nestedData['status'] = strtoupper($sim->scard_status);
                $nestedData['begbal'] = $begbal;
                $nestedData['addedby'] = ucwords($sim->u_fullname);
                //$nestedData['created_at'] = date('j M Y h:i a',strtotime($post->cus_register_at));
                $nestedData['action'] = '<ul class="list-unstyled">
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-bars" aria-hidden="true"></i></a>
                        <ul class="dropdown-menu">
                            <li onclick="editSimcard('.$sim->scard_id.');"><a href="#"><i class="fa fa-edit"></i> Edit</a></li>
                            <li onclick="simStatus('.$sim->scard_id.','.$stat.');"><a href="#"><i class="fa fa-retweet"></i> '.$stato.'</a></li>                            
                            <li><a href="#"><i class="fa fa-trash"></i> Delete</a></li>
                        </ul>
                    </li>
                </ul>';
                $nestedData['dateadded'] = $sim->datecreated;
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

	public function addedNewItemDialog()
	{
		$data['mode'] = $this->uri->segment(3, 0);
		$data['id'] = $this->uri->segment(4,0);

		$data['itemtype'] = $this->Model_Functions->getAll('item_type');
		
		if($data['mode']==1)
		{
            $data['itemdetails'] = $this->Model_Item->getItemDetails($data['id']);           
		}		
		$this->load->view('dialog/addEditItem',$data);
    }

	public function addNewSimcardDialog()
	{
		$data['mode'] = $this->uri->segment(3, 0);
		$data['id'] = $this->uri->segment(4,0);

		$data['itemdesc'] = $this->Model_Item->getEloadItems();
		
		if($data['mode']==1)
		{
            $data['itemdetails'] = $this->Model_Item->getItemDetails($data['id']);           
		}		
		$this->load->view('dialog/addEditSimcard',$data);
	}

    public function changeTransactionDate()
    {
        $data['salestrid'] = $this->uri->segment(3, 0);
        $this->load->view('dialog/changeTransactionDate',$data);
    }

    public function saveSimcard()
    {
        $response['st'] = false;

        $this->form_validation->set_rules('simcardnum','Sim Card Number','required|trim|is_unique[simcards.scard_number]',
            array(
                'is_unique'     => 'This %s already exists.'
            )
        );      
        $this->form_validation->set_rules('simtype','Sim Type','trim');
        $this->form_validation->set_rules('begbal','Beg Bal','trim');
        $this->form_validation->set_error_delimiters('<div class="form_error">* ','</div>');    

        if($this->form_validation->run()===FALSE)
        {
            $response['msg'] = validation_errors();
        }
        else 
        {
            if(!$this->session->userdata('aload_buid'))
            {
                $response['msg'] = 'Session expired please reload.';
            }
            else 
            {
                if($this->Model_Item->saveNewSimcard())
                {
                    $response['st'] = true;
                }
                else 
                {
                    $response['msg'] = 'Something went wrong.';
                }
            }

        }

        echo json_encode($response);
    }

    public function updateSimcardStatus()
    {
        $response['st'] = false;

        if(!$this->session->userdata('aload_buid'))
        {
            $response['msg'] = 'Session expired please reload.';
        }
        else 
        {
            if($this->Model_Item->updateSimcardStatus())
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
}

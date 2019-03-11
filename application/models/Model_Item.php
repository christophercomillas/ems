<?php

class Model_Item extends CI_Model 
{

	// public _DBlocal = null;
	// public _DBmain = null;

	public function __construct()
	{
		parent::__construct();
	}

	public function index()
	{

	}

	public function allitem_count()
	{
        $query = $this
                ->db
                ->get('item');
    
        return $query->num_rows(); 
    }

    public function allitemlist($limit,$start,$col,$dir)
    {   
       	$query = $this
            ->db
            ->select(
				"item.it_id,
				item.it_name,
				item.it_netprice,
				item.it_srp,
				item.it_fad_itemcode,
				item.it_item_oum,
				item_type.ity_name,
				DATE_FORMAT(item.it_item_datecreated,'%m/%d/%Y') as datecreated
			")
			->join('item_type','item_type.ity_id = item.it_type','left')
            ->limit($limit,$start)
            ->order_by('item.it_item_datecreated','DESC')
            ->get('item');
        
        if($query->num_rows()>0)
        {
            return $query->result(); 
        }
        else
        {
            return null;
        }        
    }

    public function posts_itemlistsearch($limit,$start,$search,$col,$dir)
    {
        $query = $this
            ->db
            ->select(
				"item.it_id,
				item.it_name,
				item.it_netprice,
				item.it_srp,
				item.it_fad_itemcode,
				item.it_item_oum,
				item_type.ity_name,
				DATE_FORMAT(item.it_item_datecreated,'%m/%d/%Y') as datecreated
			")
			->join('item_type','item_type.ity_id = item.it_type','left')
            ->like('item.u_fullname',$search)
            ->or_like('item.it_name',$search)
            ->or_like('item.it_netprice',$search)
            ->or_like('item.it_srp',$search)
            ->or_like('item.it_fad_itemcode',$search)
            ->or_like('item_type.ity_name',$search)
            ->limit($limit,$start)
            ->order_by($col,$dir)
            ->get('item');        
       
        if($query->num_rows()>0)
        {
            return $query->result();  
        }
        else
        {
            return null;
        }
    }

    public function posts_itemlistsearch_count($search)
    {
        $query = $this
            ->db
            ->select(
				"item.it_id,
				item.it_name,
				item.it_netprice,
				item.it_srp,
				item.it_fad_itemcode,
				item.it_item_oum,
				item_type.ity_name,
				DATE_FORMAT(item.it_item_datecreated,'%m/%d/%Y') as datecreated
			")
			->join('item_type','item_type.ity_id = item.it_type','left')
            ->like('item.u_fullname',$search)
            ->or_like('item.it_name',$search)
            ->or_like('item.it_netprice',$search)
            ->or_like('item.it_srp',$search)
            ->or_like('item.it_fad_itemcode',$search)
            ->or_like('item_type.ity_name',$search)
            ->limit($limit,$start)
            ->order_by($col,$dir)
            ->get('item');   
    	
        //echo $this->db->last_query();
    	//exit();
        return $query->num_rows();
    }
    
	public function allsimeod_count()
	{
        $query = $this
                ->db
                ->get('simcard_balance');
    
        return $query->num_rows(); 
    }

    public function allsimeodlist($limit,$start,$col,$dir)
    {   
       	$query = $this
            ->db
            ->select(
                "simcard_balance.sb_balance,
                simcards.scard_number,
                item.it_name,
                users.u_fullname,
                DATE_FORMAT(eod.eod_datetime,'%m/%d/%Y') as eodate
			")
            ->join('simcards','simcards.scard_id = simcard_balance.sb_simcardid','left')
            ->join('item','item.it_id = simcards.scard_itemid','left')
            ->join('eod','eod.eod_id = simcard_balance.sb_eodid','left')
            ->join('users','users.u_id = eod.eod_uid','left')
            ->where("eod.eod_buid", $this->session->userdata('aload_buid'))
            ->limit($limit,$start)
            ->order_by('simcard_balance.sb_id','DESC')
            ->get('simcard_balance');
        
        if($query->num_rows()>0)
        {
            return $query->result(); 
        }
        else
        {
            return null;
        }        
    }

    public function posts_simeodlistsearch($limit,$start,$search,$col,$dir)
    {
        $query = $this
            ->db
            ->select(
                "simcard_balance.sb_balance,
                simcards.scard_number,
                item.it_name,
                users.u_fullname,
                DATE_FORMAT(eod.eod_datetime,'%m/%d/%Y') as eodate
			")
            ->join('simcards','simcards.scard_id = simcard_balance.sb_simcardid','left')
            ->join('item','item.it_id = simcards.scard_itemid','left')
            ->join('eod','eod.eod_id = simcard_balance.sb_eodid','left')
            ->join('users','users.u_id = eod.eod_uid','left')
            ->like('users.u_fullname',$search)
            ->or_like('simcard_balance.sb_balance',$search)
            ->or_like('simcards.scard_number',$search)
            ->or_like("DATE_FORMAT(eod.eod_datetime,'%m/%d/%Y')",$search)
            ->limit($limit,$start)
            ->order_by('simcard_balance.sb_id','DESC')
            ->get('simcard_balance');        

            //echo $this->db->last_query();
       
        if($query->num_rows()>0)
        {
            return $query->result();  
        }
        else
        {
            return null;
        }
    }

    public function saveItem()
    {
        $itemtype = $this->input->post('itemtype');        

        $this->db->trans_start();

            $itemid = $this->saveItemDetails();       

        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE)
        {
                // generate an error... or use the log_message() function to log your error
                //log_message();
                return false;
        }
        else
        {
            return true;
        }
    }

    public function saveItemDetails()
    {
        $itemname = $this->input->post('itemname');
        $itemtype = $this->input->post('itemtype');
        $faditemcode = $this->input->post('faditemcode');
        $srp = $this->input->post('srp');
        $netprice = $this->input->post('netprice');

        $oum = "";
        if($itemtype==1)
        {
            $oum = 'load';
        }

        $data = array(
            'it_name'           =>  $itemname,
            'it_type'           =>  $itemtype,
            'it_netprice'       =>  $netprice,
            'it_srp'            =>  $srp,
            'it_fad_itemcode'   =>  $faditemcode,
            'it_item_oum'       =>  $oum,            
            'it_item_createdby' =>  $this->session->userdata('aload_userid')
        );

        $this->db->set('it_item_datecreated', 'NOW()', FALSE);

        $this->db->insert("item",$data);

        $itemid = $this->db->insert_id();
        //echo $this->db->last_query();

        //exit();
        return $itemid;
    }

    public function updateItem()
    {
        $id = $this->input->post('itemid');
        $itemname = $this->input->post('itemname');
        $itemtype = $this->input->post('itemtype');
        $faditemcode = $this->input->post('faditemcode');
        $srp = $this->input->post('srp');
        $netprice = $this->input->post('netprice');

        $oum = "";
        if($itemtype==1)
        {
            $oum = 'load';
        }

        $data = array(
            'it_name'           =>  $itemname,
            'it_type'           =>  $itemtype,
            'it_netprice'       =>  $netprice,
            'it_srp'            =>  $srp,
            'it_fad_itemcode'   =>  $faditemcode,
            'it_item_oum'       =>  $oum
        );

        $this->db->where('it_id', $id)
		->update('item', $data); 
        //echo $this->db->last_query();
        if($this->db->affected_rows() > 0)
        {
            return true;
        }
        return false;

    }

    public function getItemDetails($id)
    {
        $query = $this
            ->db
            ->select(
                "item.it_id,
				item.it_name,
				item.it_type,
				item.it_netprice,
				item.it_srp,
                item.it_fad_itemcode
            ")
			->where('it_id',$id)
            ->get('item');
    	
        //echo $this->db->last_query();
    	//exit();
        if($query->num_rows() > 0)
        {
        	return $query->row();
        }
    }

    public function check_itemname_exist()
    {
        $itemname = $this->input->post('itemname');
        $id = $this->input->post('itemid');
        $query = $this
            ->db
            ->select(
				"item.it_name				
			")
            ->where('item.it_name',$itemname)
            ->where('item.it_id !=',$id)
            ->get('item');   
    	
        //echo $this->db->last_query();
    	//exit();
        if($query->num_rows() > 0)
        {
            return true;
        }
        return false;
    }

	public function allsimcard_count()
	{
        $query = $this
                ->db
                ->where("scard_bu", $this->session->userdata('aload_buid'))
                ->get('simcards');
                
        return $query->num_rows(); 
    }

    public function allsimcardlist($limit,$start,$col,$dir)
    {   
       	$query = $this
            ->db
            ->select(
				"simcards.scard_id,
				simcards.scard_itemid,
				simcards.scard_number,
                simcards.scard_bu,
                simcards.scard_status,
                simcards.scard_by,   
                simcards.scard_begbal,   
                users.u_fullname,         
                item.it_name,
				DATE_FORMAT(simcards.scard_datetime,'%m/%d/%Y') as datecreated
			")
            ->join('item','item.it_id = simcards.scard_itemid','left')
            ->join('users','users.u_id = simcards.scard_by', 'left')
            ->where("scard_bu", $this->session->userdata('aload_buid'))
            ->limit($limit,$start)
            ->order_by('simcards.scard_id','DESC')
            ->get('simcards');
        //echo $this->db->last_query();
        if($query->num_rows()>0)
        {
            return $query->result(); 
        }
        else
        {
            return null;
        }        
    }

    public function posts_simcardlistsearch($limit,$start,$search,$col,$dir)
    {
        $query = $this
            ->db
            ->select(
				"simcards.scard_id,
				simcards.scard_itemid,
				simcards.scard_number,
                simcards.scard_bu,
                simcards.scard_status,
                simcards.scard_by,     
                simcards.scard_begbal,     
                users.u_fullname,         
                item.it_name,
				DATE_FORMAT(simcards.scard_datetime,'%m/%d/%Y') as datecreated
			")
            ->join('item','item.it_id = simcards.scard_itemid','left')
            ->join('users','users.u_id = simcards.scard_by', 'left')
            ->where("scard_bu", $this->session->userdata('aload_buid'))
            ->like('simcards.scard_number',$search)
            ->limit($limit,$start)
            ->order_by($col,$dir)
            ->get('simcards');        
        //echo $this->db->last_query();
        if($query->num_rows()>0)
        {
            return $query->result();  
        }
        else
        {
            return null;
        }
    }

    public function getEloadItems()
    {
        $query = $this
            ->db
            ->select(
                "item.it_id,
                item.it_name"
            )
            ->where("it_type", "1")
            ->get('item');        
        //echo $this->db->last_query();
        if($query->num_rows()>0)
        {
            return $query->result();  
        }
        else
        {
            return null;
        }
    }

    public function saveNewSimcard()
    {
        $simcardnum = $this->input->post('simcardnum');  
        $simtype = $this->input->post('simtype');  
        $begbaln = $this->input->post('begbaln');      

        $this->db->trans_start();

        $data = array(
            'scard_itemid'  =>  $simtype,
            'scard_number'  =>  $simcardnum,
            'scard_begbal'  =>  $begbaln,
            'scard_status'  =>  'active',
            'scard_bu'      =>  $this->session->userdata('aload_buid'),  
            'scard_by'      =>  $this->session->userdata('aload_userid')           
        );

        $this->db->set('scard_datetime', 'NOW()', FALSE);

        $this->db->insert("simcards",$data);  

        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE)
        {
                // generate an error... or use the log_message() function to log your error
                //log_message();
                return false;
        }
        else
        {
            return true;
        }
    }

    public function updateSimcardStatus()
    {
        $id = $this->input->post('id');
        $st = $this->input->post('st');

        $stato = $st == '1' ? 'inactive' : 'active';

        $data = array(
            'scard_status'  =>  $stato
        );

        $this->db->where('scard_id', $id)
		->update('simcards', $data); 
        //echo $this->db->last_query();
        if($this->db->affected_rows() > 0)
        {
            return true;
        }
        return false;
        
    }

}
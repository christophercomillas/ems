<?php

class Model_Transaction extends CI_Model 
{

	public function __construct()
	{		
		parent::__construct();

		$this->lastid = null;

		$CI =& get_instance();
		$CI->load->model('Model_Functions');

	}

	public function saveCashPayment($totalamt,$amttender,$change)
	{
		$this->db->trans_start();

			$trid = $this->saveTransactionDetails();

			$this->saveTransactionItems($trid);

			$this->saveTransactionPayment('cash',$amttender,$totalamt,$change,$trid);

		$this->db->trans_complete();

		if ($this->db->trans_status() === FALSE)
		{
		        // generate an error... or use the log_message() function to log your error
				return false;
		}
		else
		{
			return true;
		}

	}

	public function saveReceiving($sinum,$ponum,$refnum,$checkedby)
	{
		$this->db->trans_start();

			$trid = $this->saveReceivingDetails($sinum,$ponum,$refnum,$checkedby);

			$this->saveRecevingItems($trid);
			
		$this->db->trans_complete();

		if ($this->db->trans_status() === FALSE)
		{
		        // generate an error... or use the log_message() function to log your error
				return false;
		}
		else
		{
			return true;
		}
	}

	public function saveReceivingDetails($sinum,$ponum,$refnum,$checkedby)
	{
		$recnum =  $this->Model_Functions->getTrNumber('receiving_transaction','rtr_recnum','rtr_bu',$this->session->userdata('load_buid'),'rtr_recnum','DESC');

		$data = array(
			'rtr_recnum'	=>	$recnum,
			'rtr_bu'		=>	$this->session->userdata('load_buid'),
			'rtr_si'		=>	$sinum,
			'rtr_po'		=>	$ponum,
			'rtr_ref'		=>	$refnum,
			'rtr_checkby'	=>	$checkedby,			
			'rtr_recby'		=>	$this->session->userdata('load_userid')
		);

		$this->db->set('rtr_datetime', 'NOW()', FALSE);

		$query = $this->db->insert("receiving_transaction",$data);

		$insertid = $this->db->insert_id();

		return $insertid;
	}

	public function saveTransactionDetails()
	{
		$trnum =  $this->Model_Functions->getTrNumber('sales_transaction','st_trnum','st_bu',$this->session->userdata('load_buid'),'st_trnum','DESC');

		$data = array(
			'st_trnum'		=>	$trnum,
			'st_bu'			=>	$this->session->userdata('load_buid'),
			'st_cashier'	=>	$this->session->userdata('load_userid')
		);

		$this->db->set('st_datetime', 'NOW()', FALSE);

		$query = $this->db->insert("sales_transaction",$data);

		$insertid = $this->db->insert_id();

		return $insertid;
	}

	public function saveTransactionItems($trid)
	{
		if($this->session->userdata('cart'))
		{
			if(count($this->session->userdata('cart'))>0)
			{

				/// check if last item balance is not zero




				$cartarr = $this->session->userdata('cart');
				foreach ($cartarr as $c)
				{
					$led = 0;
					$data = array(
						'si_trid'		=>	$trid,
						'si_itemid'		=>	$c['itemid'],					
						'si_qty'		=>	$c['qty'],
						'si_srp'		=>	$c['srp'],
						'si_netprice'	=>	$c['net'],
						'si_linedisc'	=>	0
					);

					$this->db->insert("sales_items",$data);
					$salesitemid = $this->db->insert_id();

					if($c['is_load'])
					{	
						$led = $c['net'];					

						$data = array(
							'sld_trid'			=>	$salesitemid,
							'sld_refnum'		=>	$c['ref'],					
							'sld_mobilenum'		=>	$c['mobnum'],
							'sld_type'			=>  'sold'
						);

						$this->db->insert("sales_load_details",$data);
					}
					else 
					{
						$led = $c['qty'];
					}

					//get ledger number
					$lednum =  $this->Model_Functions->getTrNumber('item_ledger','il_lnum','il_bu',$this->session->userdata('load_buid'),'il_lnum','DESC');

					//save to ledger

					$data = array(
						'il_type'		=>	'sold',
						'ilsalerecid'	=>	$salesitemid,					
						'il_lnum'		=>	$lednum,
						'il_bu'			=>	$this->session->userdata('load_buid'),
						'il_ledger_credit'	=>	$led
					);
					$this->db->insert(" item_ledger",$data);

				}
			} // end count
		} // end check session
	}

	public function saveRecevingItems($trid)
	{
		if($this->session->userdata('cart'))
		{
			if(count($this->session->userdata('cart'))>0)
			{

				$cartarr = $this->session->userdata('cart');
				foreach ($cartarr as $c)
				{
					$led = 0;
					$data = array(
						'rei_recid'		=>	$trid,
						'rei_itemid'		=>	$c['itemid'],					
						'rei_qty'		=>	$c['qty'],
						'rei_srp'		=>	$c['srp'],
						'rei_netprice'	=>	0
					);

					$this->db->insert("receiving_items",$data);

					$recid = $this->db->insert_id();

					if($c['is_load'])
					{
						$led = $c['srp'];

						$data = array(
							'sld_trid'			=>	$recid,
							'sld_refnum'		=>	$c['ref'],					
							'sld_mobilenum'		=>	$c['mobnum'],
							'sld_type'			=>  'received'
						);

						$this->db->insert("sales_load_details",$data);
					}
					else 
					{
						$led = $c['qty'];
					}

					//get ledger number
					$lednum =  $this->Model_Functions->getTrNumber('item_ledger','il_lnum','il_bu',$this->session->userdata('load_buid'),'il_lnum','DESC');

					//save to ledger

					$data = array(
						'il_type'			=> 'receiving',
						'ilsalerecid'	=>	$recid,					
						'il_lnum'		=>	$lednum,
						'il_bu'			=>	$this->session->userdata('load_buid'),
						'il_ledger_debit'	=>	$led
					);

					$this->db->insert(" item_ledger",$data);

				}
			} // end count
		} // end check session
	}

	public function saveTransactionPayment($ptype,$amttender,$totamt,$change,$trid)
	{
		$data = array(
			'sp_trid'			=>	$trid,
			'sp_paymenttype'	=>	$ptype,					
			'sp_amtrec'			=>	$amttender,
			'sp_totpayable'		=>	$totamt,
			'sp_change'			=>	$change
		);

		$this->db->insert("sales_payment",$data);

	}

	public function getReceivingNumber()
	{
		$response['recnum'] =  $this->Model_Functions->getTrNumber('receiving_transaction','rtr_recnum','rtr_bu',$this->session->userdata('load_buid'),'rtr_recnum','DESC');
		$response['st'] = true;

		echo json_encode($response);
	}

	public function processEOD()
	{               

		$this->db->trans_start();

			$trnum = $this->Model_Functions->getTrNumber('eod','eod_num','eod_buid',$this->session->userdata('load_buid'),'eod_num','DESC');

			$trid = $this->insertEOD($trnum);

            $this->simcardBalance($trid);

			$this->updateTransactionSalesEOD($trid);

		$this->db->trans_complete();

		if ($this->db->trans_status() === FALSE)
		{
		        // generate an error... or use the log_message() function to log your error
				return false;
		}
		else
		{
			return array(true,$trnum);
		}		
    }


	public function insertEOD($trnum)
	{
		$data = array(
			'eod_num'	=>	$trnum,
			'eod_buid'	=>	$this->session->userdata('load_buid'),
			'eod_uid'	=>	$this->session->userdata('load_userid')
		);

		$this->db->set('eod_datetime', 'NOW()', FALSE);

		$query = $this->db->insert("eod",$data);

		$insertid = $this->db->insert_id();

		return $insertid;
    }
    
    public function simcardBalance($trid)
    {
        $formValues = $this->input->post(NULL, FALSE);
        if(count($formValues)> 0)
        {
            foreach($formValues as $key => $value) 
            {
                $str = substr($key, 0, 4);
                if($str == 'sim-')
                {
                    $id = substr($key, 4);
                    $value = str_replace(",","",$value);
                    $data = array(
                        'sb_simcardid'	=>	$id,
                        'sb_balance'	=>	$value,
                        'sb_eodid'      =>	$trid                        		
                    );                  
        
                    $query = $this->db->insert(" simcard_balance",$data);                 

                }
            }
        }
    }

	public function updateTransactionSalesEOD($trid)
	{
		$data = array(
			'st_eod_id'	=>	$trid
        );

        $this->db->where('st_bu', $this->session->userdata('load_buid'))
        ->where('st_eod_id','')
		->update('sales_transaction', $data); 
	}

	public function getEODTRByNum($trnum)
	{
		$this->db->select(
			'eod.eod_id,
			eod.eod_buid,
			eod.eod_uid,
			eod.eod_datetime,
			users.u_fullname
		')
		->join('users','users.u_id = eod.eod_uid','left')
		->where(' eod.eod_num',$trnum)
		->where('eod.eod_buid',$this->session->userdata('load_buid'));
		$query = $this->db->get('eod');
		return $query->row();
	}

	public function getEODSalesTRByID($trid)
	{
		$this->db->select(
			'*'
		)
		->where('st_eod_id',$trid)
		->order_by('st_trnum', 'ASC');
		$query  = $this->db->get('sales_transaction');
		return $query->result();
	}

	public function getEODItemsByID($trid)
	{
		$this->db->select(
			'sales_transaction.st_trnum,
			sales_transaction.st_datetime,
            item.it_name,
            item.it_item_oum,
            sales_items.si_netprice,
			sales_items.si_srp,
			sales_items.si_linedisc,
			sales_items.si_qty,
			users.u_fullname
		')
		->join('sales_transaction','sales_transaction.st_id = sales_items.si_trid')
		->join('item','item.it_id = sales_items.si_itemid')
		->join('users','users.u_id = sales_transaction.st_cashier')
		->where('sales_items.si_trid',$trid)
		->where('sales_transaction.st_bu',$this->session->userdata('load_buid'));
		$query = $this->db->get('sales_items');
		return $query->result();
		// ->where('sales_load_details.sld_trid',$trid)
		// ->where('sld_type.sld_trid','sold')

		// $this->db->select(
		// 	'sales_transaction.st_trnum,
		// 	sales_transaction.st_datetime,
		// 	item.it_name,
		// 	sales_items.si_srp,
		// 	sales_items.si_qty,
		// 	users.u_fullname,
		// 	sales_load_details.sld_refnum,
		// 	sales_load_details.sld_mobilenum

		// ')
		// ->join('sales_transaction','sales_transaction.st_id = sales_items.si_id')
		// ->join('item','item.it_id = sales_items.si_itemid')
		// ->join('users','users.u_id = sales_transaction.st_cashier')
		// ->join('sales_load_details','sales_load_details.sld_trid = sales_transaction.st_id','left')
		// ->where('sales_items.si_id',$trid);
		// $query = $this->db->get('sales_items');
		// return $query->result();
	}

	public function getAllTransactionByRange($d1,$d2,$trans)
	{

		$d1 = _dateFormatoSql($d1);
		$d2 = _dateFormatoSql($d2);

		if($trans==1)
		{			
			if($d1 === $d2)
			{
				
				$this->db->select(
					'sales_transaction.st_trnum,
					sales_transaction.st_datetime
				')
				->where('sales_transaction.st_bu',$this->session->userdata('load_buid'))
				->where('sales_transaction.st_cashier',$this->session->userdata('load_userid'))
				->where("DATE_FORMAT(sales_transaction.st_datetime,'%Y-%m-%d')",$d1);
				//->where('sales_transaction.st_datetime',$d1);	
				$query = $this->db->get('sales_transaction');
				return $query->result();
			}
			else 
			{
				$this->db->select(
					"sales_transaction.st_trnum,
					sales_transaction.st_datetime
				")
				->where('sales_transaction.st_bu',$this->session->userdata('load_buid'))
				->where('sales_transaction.st_datetime >=',$d1)
				->where('sales_transaction.st_datetime <=',$d2);
				$query = $this->db->get('sales_transaction');
				
				return $query->result();

			}
		}
		else 
		{

			if($d1 === $d2)
			{
				$this->db->select(
					'sales_transaction.st_trnum,
					sales_transaction.st_datetime
				')
				->where('sales_transaction.st_bu',$this->session->userdata('load_buid'))
				->where("DATE_FORMAT(sales_transaction.st_datetime,'%Y-%m-%d')",$d1);
				$query = $this->db->get('sales_transaction');
				return $query->result();
			}
			else 
			{
				$this->db->select(
					'sales_transaction.st_trnum,
					sales_transaction.st_datetime
				')
				->where('sales_transaction.st_bu',$this->session->userdata('load_buid'))
				->where('sales_transaction.st_datetime >=',$d1)
				->where('sales_transaction.st_datetime <=',$d2);		
				$query = $this->db->get('sales_transaction');
				return $query->result();
			}

		}
	}

	public function getAllTransactionByRangeDetailed($d1,$d2,$trans)
	{
		if($trans==1)
		{			
			if($d1 === $d2)
			{				
				$this->db->select(
					"sales_transaction.st_trnum,
					sales_transaction.st_datetime,
					sales_transaction.st_id,
				    bu.bu_name,
				   	users.u_fullname
				")
				->join('users','users.u_id = sales_transaction.st_cashier')
				->join('bu','bu.bu_id = sales_transaction.st_bu')
				->where('sales_transaction.st_bu',$this->session->userdata('load_buid'))
				->where('sales_transaction.st_cashier',$this->session->userdata('load_userid'))
				->where("DATE_FORMAT(sales_transaction.st_datetime,'%Y-%m-%d')",$d1);
				$query = $this->db->get('sales_transaction');
				return $query->result();
			}
			else 
			{
				$this->db->select(
					"sales_transaction.st_trnum,
					sales_transaction.st_datetime,
					sales_transaction.st_id,
				    bu.bu_name,
				   	users.u_fullname
				")
				->join('users','users.u_id = sales_transaction.st_cashier')
				->join('bu','bu.bu_id = sales_transaction.st_bu')
				->where('sales_transaction.st_bu',$this->session->userdata('load_buid'))
				->where('sales_transaction.st_cashier',$this->session->userdata('load_userid'))
				->where('sales_transaction.st_datetime >=',$d1)
				->where('sales_transaction.st_datetime <=',$d2);		
				$query = $this->db->get('sales_transaction');
				return $query->result();

			}
		}
		else 
		{
			if($d1 === $d2)
			{
				$this->db->select(
					"sales_transaction.st_trnum,
					sales_transaction.st_datetime,
					sales_transaction.st_id,
				    bu.bu_name,
				   	users.u_fullname
				")
				->join('users','users.u_id = sales_transaction.st_cashier')
				->join('bu','bu.bu_id = sales_transaction.st_bu')
				->where('sales_transaction.st_bu',$this->session->userdata('load_buid'))
				->where("DATE_FORMAT(sales_transaction.st_datetime,'%Y-%m-%d')",$d1);
				$query = $this->db->get('sales_transaction');
				return $query->result();
			}
			else 
			{
				$this->db->select(
					"sales_transaction.st_trnum,
					sales_transaction.st_datetime,
					sales_transaction.st_id,
				    bu.bu_name,
				   	users.u_fullname
				")
				->join('users','users.u_id = sales_transaction.st_cashier')
				->join('bu','bu.bu_id = sales_transaction.st_bu')
				->where('sales_transaction.st_bu',$this->session->userdata('load_buid'))
				->where('sales_transaction.st_datetime >=',$d1)
				->where('sales_transaction.st_datetime <=',$d2);		
				$query = $this->db->get('sales_transaction');
				return $query->result();
			}

		}
	}

	public function getAllTransactionByRangeDetailedQuery($d1,$d2,$storeid)
	{
		if($d1 === $d2)
		{
			$this->db->select(
				"sales_transaction.st_trnum,
				sales_transaction.st_datetime,
				sales_transaction.st_id,
			    bu.bu_name,
			   	users.u_fullname
			")
			->join('users','users.u_id = sales_transaction.st_cashier')
			->join('bu','bu.bu_id = sales_transaction.st_bu')
			->where('sales_transaction.st_bu',$storeid)
			->where("DATE_FORMAT(sales_transaction.st_datetime,'%Y-%m-%d')",$d1);
			$query = $this->db->get('sales_transaction');
			return $query->result();
		}
		else 
		{
			$this->db->select(
				"sales_transaction.st_trnum,
				sales_transaction.st_datetime,
				sales_transaction.st_id,
			    bu.bu_name,
			   	users.u_fullname
			")
			->join('users','users.u_id = sales_transaction.st_cashier')
			->join('bu','bu.bu_id = sales_transaction.st_bu')
			->where('sales_transaction.st_bu',$storeid)
			->where('sales_transaction.st_datetime >=',$d1)
			->where('sales_transaction.st_datetime <=',$d2);		
			$query = $this->db->get('sales_transaction');
			return $query->result();
		}
	}

	public function getAllTransactionItemsBytrID($trid)
	{
		$this->db->select(
			"sales_items.si_qty,
			sales_items.si_srp,
			sales_items.si_linedisc,
			item.it_name,
			sales_items.si_itemid,
			sales_items.si_id,
			item.it_item_oum,
			sales_items.si_netprice		
		")
		->join('item','item.it_id = sales_items.si_itemid ')
		->where('sales_items.si_trid',$trid);		
		$query = $this->db->get('sales_items');
		return $query->result();
	}

	public function getAllLoadItems()
	{
		$this->db->select(
			"it_id,
			it_name
		")
		->where('it_item_oum','load');		
		$query = $this->db->get('item');
		return $query->result();		
	}

	public function getAllSalesLoadDetails($itid)
	{
		$this->db->select(
			"sales_load_details.sld_id,
			sales_load_details.sld_trid,
			sales_load_details.sld_type,
			sales_load_details.sld_refnum,
			sales_load_details.sld_mobilenum	
		")
		->where('sales_load_details.sld_trid',$itid)
		->where('sales_load_details.sld_type','sold');		
		$query = $this->db->get('sales_load_details');
		return $query->result();
	}

	public function changeTransactionDate($newdate,$trid)
	{
		$date = $this->Model_Functions->getFields('sales_transaction','st_datetime','st_id',$trid);
		$datearr = explode(" ", $date);
		$datenew = $newdate.' '.$datearr[1];

		//save info first

		$this->db->trans_start();

			$data = array(
				'trchid_trid'		=>	$trid,
				'trchid_orig_date'	=>	$datearr[0],
				'trchid_change_date'=>	$datenew,
				'trchid_by'			=> 	$this->session->userdata('aload_userid')					
			);

			$this->db->set('trchid_datechanged', 'NOW()', FALSE);

			$query = $this->db->insert(" transaction_datechange",$data);

			$data = array(
				'st_datetime'	=>	$datenew
	        );

	        $this->db->where('st_id', $trid)
			->update('sales_transaction', $data);

		$this->db->trans_complete();

		if ($this->db->trans_status() === FALSE)
		{
		        // generate an error... or use the log_message() function to log your error
				return false;
		}
		else
		{
			return true;
		}	
	}

	public function getReceivedList()
	{
		if(trim($this->session->userdata('aload_buid'))!='')
		{
			$this->db->select(
				"receiving_transaction.rtr_recnum,
				receiving_transaction.rtr_si,
				receiving_transaction.rtr_po,
				receiving_transaction.rtr_ref,
				receiving_transaction.rtr_datetime,
				receiving_transaction.rtr_checkby,
				receiving_transaction.rtr_id	
			")
			->join('users','users.u_id = receiving_transaction.rtr_recby')
			->where('receiving_transaction.rtr_bu',$this->session->userdata('aload_buid'));		
			$query = $this->db->get('receiving_transaction');
			return $query->result();
		}

		$this->db->select(
			"receiving_transaction.rtr_recnum,
			receiving_transaction.rtr_si,
			receiving_transaction.rtr_po,
			receiving_transaction.rtr_ref,
			receiving_transaction.rtr_datetime,
			receiving_transaction.rtr_checkby,
			receiving_transaction.rtr_id	
		")
		->join('users','users.u_id = receiving_transaction.rtr_recby');
		$query = $this->db->get('receiving_transaction');
		return $query->result();

	}

	public function getAllReceivedItemsBytrID($trid)
	{
		$this->db->select(
			"receiving_items.rei_id,
			receiving_items.rei_srp,
			receiving_items.rei_qty,
			item.it_item_oum,
			item.it_name,
			item.it_id
		")
		->join('item','item.it_id = receiving_items.rei_itemid')
		->where('receiving_items.rei_recid',$trid);		
		$query = $this->db->get('receiving_items');
		return $query->result();
	}

	public function getReceivingLoadDetails($trid)
	{
		$this->db->select(
			"sales_load_details.sld_refnum,
			sales_load_details.sld_mobilenum
		")
		->where('sales_load_details.sld_trid',$trid)
		->where('sales_load_details.sld_type','received');		
		$query = $this->db->get('sales_load_details');
		return $query->result();
	}

	public function deletetransactionbyid($id)
	{
		$this->db->trans_begin();

			// delete sales transaction by id
			$this->deletesalestransactionbytrid($id);

			// delete payment details
			$this->deletepaymentbyid($id);

			//get all items by trid

			$items = $this->Model_Functions->getTableFields('sales_items','si_id','si_trid',$id);

			foreach ($items as $i) 
			{
				//delete sales items
				$this->deletesalesitembyid($i->si_id);

				//delete item ledger
				$this->deleteitemledgerbyid($i->si_id);

				//delete load details
				$this->deleteloaddetailsbyid($i->si_id);
			}

		if ($this->db->trans_status() === FALSE)
		{
		        // generate an error... or use the log_message() function to log your error
				$this->db->trans_rollback();
				return false;
		}
		else
		{
			$this->db->trans_commit();
			return true;
		}
	}

	public function deletesalestransactionbytrid($id)
	{
		$this->db->where('st_id', $id)
		->delete('sales_transaction');
	}

	public function deletesalesitembyid($id)
	{
		$this->db->where('si_id', $id)
		->delete('sales_items');
	}

	public function deletepaymentbyid($id)
	{
		$this->db->where('sp_trid', $id)
		->delete('sales_payment');
	}

	public function deleteitemledgerbyid($id)
	{
		$this->db->where('ilsalerecid', $id)
		->where('il_type','sold')
		->delete('item_ledger');
	}

	public function deleteloaddetailsbyid($id)
	{
		$this->db->where('sld_trid',$id)
		->where('sld_type','sold')
		->delete('sales_load_details');
	}

    public function allsales_count()
    {   
        $query = $this
                ->db
                ->get('sales_items');
    
        return $query->num_rows(); 
    }

    public function allsaleslist($limit,$start,$col,$dir)
    {   
       	$query = $this
            ->db
            ->select(
				"sales_transaction.st_datetime,
				sales_transaction.st_trnum,
				item.it_name,
				sales_items.si_qty,
				sales_items.si_srp
			")
			->join('sales_transaction','sales_transaction.st_id = sales_items.si_trid')
			->join('item','item.it_id = sales_items.si_itemid')
            ->limit($limit,$start)
            ->order_by($col,$dir)
            ->get('sales_items');
        
        if($query->num_rows()>0)
        {
            return $query->result(); 
        }
        else
        {
            return null;
        }        
    }

    public function posts_saleslistsearch($limit,$start,$search,$col,$dir)
    {
        $query = $this
                ->db
	            ->select(
					"sales_transaction.st_datetime,
					sales_transaction.st_trnum,
					item.it_name,
					sales_items.si_qty,
					sales_items.si_srp
				")
				->join('sales_transaction','sales_transaction.st_id = sales_items.si_trid')
				->join('item','item.it_id = sales_items.si_itemid')
                ->like('sales_transaction.st_datetime',$search)
                ->or_like('sales_transaction.st_trnum',$search)
                ->or_like('item.it_name',$search)
                ->limit($limit,$start)
                ->order_by($col,$dir)
                ->get('sales_items');        
       
        if($query->num_rows()>0)
        {
            return $query->result();  
        }
        else
        {
            return null;
        }
    }

    public function posts_saleslistsearch_count($search)
    {
        $query = $this
            ->db
            ->select(
				"sales_transaction.st_datetime,
				sales_transaction.st_trnum,
				item.it_name,
				sales_items.si_qty,
				sales_items.si_srp
			")
			->join('sales_transaction','sales_transaction.st_id = sales_items.si_trid')
			->join('item','item.it_id = sales_items.si_itemid')
            ->like('sales_transaction.st_datetime',$search)
            ->or_like('sales_transaction.st_trnum',$search)
            ->or_like('item.it_name',$search)
            ->get('sales_items');
    
        return $query->num_rows();
    }

    public function getAllSimCards()
    {
		$this->db->select(
			"simcards.scard_id,
			item.it_name,
			simcards.scard_number,
			simcards.scard_itemid
		")
		->join('item','item.it_id = simcards.scard_itemid')
		->where('simcards.scard_bu',$this->session->userdata('load_buid'));		
		$query = $this->db->get('simcards');
		return $query->result();
    }

    public function getAllActiveSimCards()
    {
		$this->db->select(
			"simcards.scard_id,
			item.it_name,
			simcards.scard_number,
			simcards.scard_itemid
		")
		->join('item','item.it_id = simcards.scard_itemid')
        ->where('simcards.scard_bu',$this->session->userdata('load_buid'))		
        ->where('simcards.scard_status','active');
		$query = $this->db->get('simcards');
		return $query->result();
    }

    public function getSimCardNumberByItemID($itemid)
    {	
		$this->db->select('scard_number')
		->where('scard_itemid', $itemid);

		$query = $this->db->get('simcards');

		if($query->num_rows() > 0)
		{
			$scardnumber = $query->row()->scard_number;
			return array(true,$scardnumber);
		}
		else 
		{
			return false;
		}
	}
	
    public function getSimCardNumberBySimID($itemid)
    {	
		$this->db->select('scard_number')
		->where('scard_id', $itemid);

		$query = $this->db->get('simcards');

		if($query->num_rows() > 0)
		{
			$scardnumber = $query->row()->scard_number;
			return array(true,$scardnumber);
		}
		else 
		{
			return false;
		}
    }

    public function saveTransferLoad()
    {
    	$simfrom = $this->input->post('simfrom');
    	$simto = $this->input->post('simto');
    	$loadamt = $this->input->post('loadamt');

    	$loadamt = str_replace(",", "", $loadamt);

    	//get itemid simfrom
    	$simfromItemID = $this->Model_Functions->getFields('simcards','scard_itemid','scard_id',$simfrom);
    	//get itemid simto
    	$simToItemID = $this->Model_Functions->getFields('simcards','scard_itemid','scard_id',$simto);	

		$this->db->trans_start();

			$id = $this->transferLoad($simfrom,$simto,$loadamt);
			$this->insertLoadTransferToItemLedger($id,$loadamt,'il_ledger_credit','loadtransferout');
			$this->insertLoadTransferToItemLedger($id,$loadamt,'il_ledger_debit','loadtransferin');				

		$this->db->trans_complete();
		
		if ($this->db->trans_status() === FALSE)
		{
		        // generate an error... or use the log_message() function to log your error
				$this->db->trans_rollback();
				return false;
		}
		else
		{
			$this->db->trans_commit();
			return true;
		}
    	//dri
    }

    public function insertLoadTransferToItemLedger($id,$loadamt,$col,$type)
    {
    	$lednum =  $this->Model_Functions->getTrNumber('item_ledger','il_lnum','il_bu',$this->session->userdata('load_buid'),'il_lnum','DESC');

		$data = array(
			'il_type'			=>  $type,
			'ilsalerecid'		=>	$id,
			'il_lnum'			=>	$lednum,
			'il_bu'				=>	$this->session->userdata('load_buid'),
			$col	            =>	$loadamt
        );

		$this->db->insert("item_ledger",$data);
    }

    public function transferLoad($simfrom,$simto,$loadamt)
    {
		$data = array(
			'str_trsimcardid_fr'	=>	$simfrom,
			'str_trsimcardid_to'	=>	$simto,
			'str_loadamt'			=>	$loadamt,
			'str_transferby'		=>	$this->session->userdata('load_userid')
        );

		$this->db->set('str_datetransfer', 'NOW()', FALSE);

		$query = $this->db->insert("load_transfer",$data);

		$insertid = $this->db->insert_id();

		return $insertid;
    }

    public function updateLoadDetails()
    {
    	$itrid = $this->input->post('itrid');
    	$netprice = $this->input->post('netprice');
    	$newitemid = $this->input->post('itemid');
    	//$this->updateItemLedger($itrid,$netprice);
    	//$this->updateSalesItemItemIDItemNet($itrid,$netprice,$newitemid);
		$this->db->trans_begin();

			// delete sales transaction by id
			$this->updateSalesItemItemIDItemNet($itrid,$netprice,$newitemid);
			$this->updateItemLedger($itrid,$netprice);

		if ($this->db->trans_status() === FALSE)
		{
		        // generate an error... or use the log_message() function to log your error
				$this->db->trans_rollback();
				return false;
		}
		else
		{
			$this->db->trans_commit();
			return true;
		}
    }

    public function updateSalesItemItemIDItemNet($itrid,$netprice,$newitemid)
    {
		$data = array(
			'si_netprice'	=>	$netprice,
			'si_itemid'		=>	$newitemid
        );

        $this->db->where('si_id', $itrid)
		->update('sales_items', $data); 
    }

    public function updateItemLedger($itrid,$netprice)
    {
		$data = array(
			'il_ledger_credit'	=>	$netprice
        );

        $this->db->where('ilsalerecid', $itrid)
        ->where('il_type', 'sold')
		->update('item_ledger', $data); 
    }

    public function getTransactionPerDayLoad($date,$itemoum)
    {    	
		$this->db->select(
			"sales_items.si_qty,
			sales_items.si_id,
		    sales_items.si_netprice,
		    sales_items.si_srp,
		    item.it_name,
		    DATE_FORMAT(sales_transaction.st_datetime,'%m/%d/%Y') as datesold
		")
		->join('sales_transaction','sales_transaction.st_id = sales_items.si_trid')
		->join('item','item.it_id = sales_items.si_itemid')
		->where('sales_transaction.st_bu',$this->session->userdata('aload_buid'))
		->where("DATE_FORMAT(sales_transaction.st_datetime,'%Y-%m-%d')",$date)
		->where("item.it_item_oum",$itemoum)
		->order_by('sales_transaction.st_datetime', 'ASC');
		$query = $this->db->get('sales_items');

		//echo $this->db->last_query();
		return $query->result();
    }

    public function getLoadDetailsBySalesID($sid)
    {
		$this->db->select(
			"sales_load_details.sld_refnum,
			sales_load_details.sld_mobilenum
		")
		->where('sales_load_details.sld_trid',$sid)
		->where("sales_load_details.sld_type",'sold');
		$query = $this->db->get('sales_load_details');

		//echo $this->db->last_query();
		return $query->result();
    }

    public function getTransactionPerDay($date,$itemoum)
    {
		$this->db->select(
			"sales_items.si_qty,
		    sales_items.si_netprice,
		    sales_items.si_srp,
		    item.it_name,
		    item.it_netprice,
		    DATE_FORMAT(sales_transaction.st_datetime,'%m/%d/%Y') as datesold
		")
		->join('sales_transaction','sales_transaction.st_id = sales_items.si_trid')
		->join('item','item.it_id = sales_items.si_itemid')
		->where('sales_transaction.st_bu',$this->session->userdata('aload_buid'))
		->where("DATE_FORMAT(sales_transaction.st_datetime,'%Y-%m-%d')",$date)
		->where("item.it_item_oum",$itemoum)
		->order_by('sales_transaction.st_datetime', 'ASC');
		$query = $this->db->get('sales_items');

		//echo $this->db->last_query();
		return $query->result();
    }

    public function getTransactionPerDayByRange($sdate,$edate)
    {
		$this->db->select(
			"sales_items.si_qty,
		    sales_items.si_netprice,
		    sales_items.si_srp,
		    item.it_name,
		    item.it_netprice,
		    DATE_FORMAT(sales_transaction.st_datetime,'%m/%d/%Y') as datesold
		")
		->join('sales_transaction','sales_transaction.st_id = sales_items.si_trid')
		->join('item','item.it_id = sales_items.si_itemid')
		->join('item_ledger','item_ledger.ilsalerecid = sales_items.si_id')
		->where('sales_transaction.st_bu',$this->session->userdata('aload_buid'))        
		->group_start()
			 ->where("DATE_FORMAT(sales_transaction.st_datetime,'%Y-%m-%d') >=",$sdate)
			 ->where("DATE_FORMAT(sales_transaction.st_datetime,'%Y-%m-%d') <=",$edate)
        ->group_end()
		->order_by('sales_transaction.st_datetime', 'ASC');
		$query = $this->db->get('sales_items');

		//echo $this->db->last_query();
		return $query->result();
    }    

    public function getTransactionByRange($sdate,$edate,$itemoum)
    {    	
		$this->db->select(
			"sales_items.si_qty,
			sales_items.si_id,
		    sales_items.si_netprice,
		    sales_items.si_srp,
		    item.it_name,
		    DATE_FORMAT(sales_transaction.st_datetime,'%m/%d/%Y') as datesold
		")
		->join('sales_transaction','sales_transaction.st_id = sales_items.si_trid')
		->join('item','item.it_id = sales_items.si_itemid')
        ->where('sales_transaction.st_bu',$this->session->userdata('aload_buid'))
		->group_start()
			 ->where("DATE_FORMAT(sales_transaction.st_datetime,'%Y-%m-%d') >=",$sdate)
			 ->where("DATE_FORMAT(sales_transaction.st_datetime,'%Y-%m-%d') <=",$edate)
        ->group_end()
		->where("item.it_item_oum",$itemoum)
		->order_by('sales_transaction.st_datetime', 'ASC');
		$query = $this->db->get('sales_items');

		//echo $this->db->last_query();
		return $query->result();
    }

    public function getDateRange($sdate,$edate)
    {
		$this->db->select(
			"DATE_FORMAT(sales_transaction.st_datetime,'%Y-%m-%d') as dates
		")
        ->where("DATE_FORMAT(sales_transaction.st_datetime,'%Y-%m-%d') >=",$sdate)
        ->where("DATE_FORMAT(sales_transaction.st_datetime,'%Y-%m-%d') <=",$edate)
        ->group_by("DATE_FORMAT(sales_transaction.st_datetime,'%Y-%m-%d')")
        ->order_by('sales_transaction.st_datetime', 'ASC');       
        $query = $this->db->get('sales_transaction');
		//echo $this->db->last_query();
		return $query->result();
    }

    public function getTotalPerDateLoad($date)
    {    
		$this->db->select(
            "IFNULL(SUM(sales_items.si_netprice),0.00) as pdaynet,
		    IFNULL(SUM(sales_items.si_srp),0.00) as pdaysrp
		")
        ->join('sales_transaction','sales_transaction.st_id = sales_items.si_trid')
        ->join('item','item.it_id = sales_items.si_itemid')
        ->where('sales_transaction.st_bu',$this->session->userdata('aload_buid'))
		->where("DATE_FORMAT(sales_transaction.st_datetime,'%Y-%m-%d') ",$date)
		->where("item.it_item_oum",'load')
		->order_by('sales_transaction.st_datetime', 'ASC');
		$query = $this->db->get('sales_items');

		//echo $this->db->last_query();
		return $query->result();
    }

    public function getTotalPerDateOtherItems($date)
    {
		$this->db->select(
            "sales_items.si_qty,
			sales_items.si_id,
		    sales_items.si_netprice,
		    sales_items.si_srp
		")
        ->join('sales_transaction','sales_transaction.st_id = sales_items.si_trid')
        ->join('item','item.it_id = sales_items.si_itemid')
        ->where('sales_transaction.st_bu',$this->session->userdata('aload_buid'))
		->where("DATE_FORMAT(sales_transaction.st_datetime,'%Y-%m-%d') ",$date)
		->where("item.it_item_oum",'')
		->order_by('sales_transaction.st_datetime', 'ASC');
		$query = $this->db->get('sales_items');

		//echo $this->db->last_query();
		return $query->result();
    }

    public function getLoadTransferList()
    {
    	if($this->session->userdata('aload_buid')=='')
    	{
			$this->db->select(
				"load_transfer.str_loadamt,
			    frm.scard_number as frmsimcard,
			    tom.scard_number as tosimcard,
			    namefrom.it_name as namefromm,
			    nameto.it_name as nametom,
			    DATE_FORMAT(load_transfer.str_datetransfer,'%m/%d/%Y') as datetransfer,
			    users.u_fullname			    
			")
			->join('simcards as frm','frm.scard_id = load_transfer.str_trsimcardid_fr')
			->join('simcards as tom','tom.scard_id = load_transfer.str_trsimcardid_to')
			->join('item as namefrom','namefrom.it_id = frm.scard_itemid')
			->join('item as nameto','nameto.it_id = tom.scard_itemid')
			->join('users','users.u_id = load_transfer.str_transferby')
			->order_by('load_transfer.str_datetransfer', 'ASC');			
			$query = $this->db->get('load_transfer');
			//echo $this->db->last_query();
			return $query->result();
    	}

		$this->db->select(
			"load_transfer.str_loadamt,
		    frm.scard_number as frmsimcard,
		    tom.scard_number as tosimcard,
		    namefrom.it_name as namefromm,
		    nameto.it_name as nametom,
		    DATE_FORMAT(load_transfer.str_datetransfer,'%m/%d/%Y') as datetransfer,
		    users.u_fullname			    
		")
		->join('simcards as frm','frm.scard_id = load_transfer.str_trsimcardid_fr')
		->join('simcards as tom','tom.scard_id = load_transfer.str_trsimcardid_to')
		->join('item as namefrom','namefrom.it_id = frm.scard_itemid')
		->join('item as nameto','nameto.it_id = tom.scard_itemid')
		->join('users','users.u_id = load_transfer.str_transferby')
		->join('item_ledger','item_ledger.ilsalerecid = load_transfer.str_id')
		->where('item_ledger.il_type','loadtransferout')
		->where('item_ledger.il_bu',$this->session->userdata('aload_buid'))
		->order_by('load_transfer.str_datetransfer', 'ASC');			
		$query = $this->db->get('load_transfer');
		//echo $this->db->last_query();
		return $query->result();
    }

    public function getTotalLoadReceived($date)
    {
    	if($this->session->userdata('aload_buid')=='')
    	{

    	}
    	else 
    	{
			$this->db->select(
				"IFNULL(SUM(receiving_items.rei_srp),0.00) as total		    
			")
			->join('receiving_transaction','receiving_transaction.rtr_id = receiving_items.rei_recid')
			->join('item','item.it_id = receiving_items.rei_itemid')
			->where('receiving_transaction.rtr_bu',$this->session->userdata('aload_buid'))
			->where('item.it_type','1')
			->where("DATE(receiving_transaction.rtr_datetime) <=",$date);
			$query = $this->db->get('receiving_items');
			//echo $this->db->last_query();	
			return $query->row()->total;
    	}
    }

    public function getLoadTotalSales($date)
    {
    	if($this->session->userdata('aload_buid')=='')
    	{

    	}
    	else 
    	{
			$this->db->select(
				"IFNULL(SUM(sales_items.si_netprice),0.00) as totsales
			")
			->join('sales_transaction','sales_transaction.st_id = sales_items.si_trid')
			->join('item','item.it_id = sales_items.si_itemid')
			->join('item_ledger','item_ledger.ilsalerecid = sales_items.si_id')
			->join('sales_load_details','sales_load_details.sld_trid = sales_items.si_id')
			->where('sales_transaction.st_bu',$this->session->userdata('aload_buid'))
			->where("DATE(sales_transaction.st_datetime) <=",$date)
			->where("item.it_type",'1')
			->where("sales_load_details.sld_type","sold")
			->where("item_ledger.il_type","sold");
			$query = $this->db->get('sales_items');
			//echo $this->db->last_query();
			return $query->row()->totsales;
    	}
    }

    public function getSalesPerDate($date)
    {    	
		$this->db->select(
			"sales_items.si_qty,
			sales_transaction.st_id,
		    sales_items.si_netprice,
            sales_items.si_srp,
            sales_items.si_id,
		    item.it_name,
			item.it_item_oum,
			users.u_fullname,
		    DATE_FORMAT(sales_transaction.st_datetime,'%m/%d/%Y') as datesold
		")
		->join('sales_transaction','sales_transaction.st_id = sales_items.si_trid')
		->join('item','item.it_id = sales_items.si_itemid')
		->join('users','users.u_id= sales_transaction.st_cashier')
		->where('sales_transaction.st_bu',$this->session->userdata('aload_buid'))
		->where("DATE_FORMAT(sales_transaction.st_datetime,'%Y-%m-%d')",$date)
		->order_by('sales_transaction.st_datetime', 'ASC');
		$query = $this->db->get('sales_items');

		//echo $this->db->last_query();
		return $query->result();
	}

	public function getItemName()
    {
        	$query = $this
                ->db->select(
                	"it_name,
                	it_id
                	")
                ->get('item');
    	// echo $this->db->last_query();
        return $query->result(); 
    }

	public function getSalesPerItem($date,$it_id)
    {    	
		$this->db->select(
			"sales_items.si_qty,
			sales_transaction.st_id,
		    sales_items.si_netprice,
            sales_items.si_srp,
            sales_items.si_id,
		    item.it_name,
			item.it_item_oum,
			users.u_fullname,
		    DATE_FORMAT(sales_transaction.st_datetime,'%m/%d/%Y') as datesold
		")
		->join('sales_transaction','sales_transaction.st_id = sales_items.si_trid')
		->join('item','item.it_id = sales_items.si_itemid')
		->join('users','users.u_id= sales_transaction.st_cashier')
		->where('sales_transaction.st_bu',$this->session->userdata('aload_buid'))
		->where("DATE_FORMAT(sales_transaction.st_datetime,'%Y-%m-%d')",$date)
		->where('item.it_id',$it_id)
		->order_by('sales_transaction.st_datetime', 'ASC');
		$query = $this->db->get('sales_items');

		//echo $this->db->last_query();
		return $query->result();
	}

    public function getAllSales()
    {
		$this->db->select(
			"sales_items.si_qty,
		    sales_items.si_netprice,
		    sales_items.si_srp,
		    item.it_item_oum,
		    sales_items.si_id,
		    item.it_name,
		    DATE_FORMAT(sales_transaction.st_datetime,'%m/%d/%Y') as datesold
		")
		->join('sales_transaction','sales_transaction.st_id = sales_items.si_trid')
		->join('item','item.it_id = sales_items.si_itemid')
		->where('sales_transaction.st_bu',$this->session->userdata('aload_buid'))
		->order_by('sales_transaction.st_datetime', 'ASC');
		$query = $this->db->get('sales_items');

		//echo $this->db->last_query();
		return $query->result();
    }

    public function checkSalesItemExistInItemLedger($id)
    {
        $query = $this
                ->db
                ->where('ilsalerecid',$id)
                ->where('il_type','sold')
                ->get('item_ledger');
    
        return $query->num_rows(); 
    }

	public function updateSRPSales($salesid, $newsrp, $srp)
	{
		$this->db->trans_start();

			//insert update details
			$this->insertSRPupdateLoadDetails($salesid, $newsrp, $srp);

			//update item sales
			$this->updateSRPSalesByID($salesid,$newsrp);			

		$this->db->trans_complete();

		if ($this->db->trans_status() === FALSE)
		{
	        // generate an error... or use the log_message() function to log your error
			$this->db->trans_rollback();
			return false;
		}
		else
		{
			$this->db->trans_commit();
			return true;
		}
	}

	public function insertSRPupdateLoadDetails($salesid, $newsrp, $srp)
	{
		$data = array(
			'lact_type'			=>	'Update SRP',
			'lact_by'			=>	$this->session->userdata('aload_userid'),
			'lact_storeid'		=>	$this->session->userdata('aload_buid'),
			'tr_id'				=>	$salesid,
			'tr_frm'			=>	$srp,
			'tr_to'				=>	$newsrp
        );

		$this->db->set('lact_datetime', 'NOW()', FALSE);

		$query = $this->db->insert("logs_activity",$data);
	}

	public function updateSRPSalesByID($salesid,$newsrp)
	{
		$data = array(
			'si_srp'	=>	$newsrp
        );

        $this->db->where('si_id',$salesid)
		->update('sales_items', $data); 
	}

	public function getItemUOMBySalesID($salesid)
	{
		$this->db->select(
			"item.it_item_oum
		")
		->join('sales_transaction','sales_transaction.st_id = sales_items.si_trid')
		->join('item','item.it_id = sales_items.si_itemid')
		->where('sales_items.si_id',$salesid);
		$query = $this->db->get('sales_items');
		//echo $this->db->last_query();
		return $query->row()->it_item_oum;
	}

	public function updateNetSales($salesid, $newnet, $net)
	{
		$this->db->trans_start();

			//insert update details
			$this->insertNetupdateLoadDetails($salesid, $newnet, $net);

			//update item sales
			$this->updateNetSalesByID($salesid,$newnet);		
			$this->updateNetSalesLedgerByID($salesid,$newnet);			

		$this->db->trans_complete();

		if ($this->db->trans_status() === FALSE)
		{
	        // generate an error... or use the log_message() function to log your error
	        //echo log_message();
			$this->db->trans_rollback();
			return false;
		}
		else
		{
			$this->db->trans_commit();
			return true;
		}
	}

	public function insertNetupdateLoadDetails($salesid, $newnet, $net)
	{
		$data = array(
			'lact_type'			=>	'Update Net Sales',
			'lact_by'			=>	$this->session->userdata('aload_userid'),
			'lact_storeid'		=>	$this->session->userdata('aload_buid'),
			'tr_id'				=>	$salesid,
			'tr_frm'			=>	$net,
			'tr_to'				=>	$newnet
        );

		$this->db->set('lact_datetime', 'NOW()', FALSE);

		$query = $this->db->insert("logs_activity",$data);
	}

	public function updateNetSalesByID($salesid,$newnet)
	{
		$data = array(
			'si_netprice'	=>	$newnet
        );

        $this->db->where('si_id',$salesid)
		->update('sales_items', $data); 
	}

	public function updateNetSalesLedgerByID($salesid,$newnet)
	{
		$data = array(
			'il_ledger_debit'	=>	$newnet
        );

        $this->db->where('ilsalerecid',$salesid)
        ->where('il_type','sold')
		->update('item_ledger', $data);

		//echo $this->db->last_query();
	}

	public function getTransactionByDate($date_c)
	{
		$this->db->select(
			"st_eod_id,
			st_datetime
		")
		->where('sales_transaction.st_bu',$this->session->userdata('aload_buid'))
		->where('DATE(sales_transaction.st_datetime)',$date_c)
		->limit(1)
		->order_by('sales_transaction.st_datetime', 'DESC');
		$query = $this->db->get('sales_transaction');

		//echo $this->db->last_query();
		return $query->result();
	}

	public function updateTransactionDateTrans($trid,$tdate,$date_qn,$eodnum)
	{
		$this->db->trans_start();

			//insert update details
			$this->logsActivity($trid, $date_qn, $tdate,"Update Transaction Date");

			//update transaction date
			$this->updateTransactionDate($trid,$tdate,$eodnum);

		$this->db->trans_complete();

		if ($this->db->trans_status() === FALSE)
		{
	        // generate an error... or use the log_message() function to log your error
	        //echo log_message();
			$this->db->trans_rollback();
			return false;
		}
		else
		{
			$this->db->trans_commit();
			return true;
		}
	}

	public function logsActivity($id,$old,$new,$desc)
	{
		$data = array(
			'lact_type'			=>	$desc,
			'lact_by'			=>	$this->session->userdata('aload_userid'),
			'lact_storeid'		=>	$this->session->userdata('aload_buid'),
			'tr_id'				=>	$id,
			'tr_frm'			=>	$old,
			'tr_to'				=>	$new
        );

		$this->db->set('lact_datetime', 'NOW()', FALSE);

		$query = $this->db->insert("logs_activity",$data);
	}

	public function updateTransactionDate($trid,$tdate,$eodnum)
	{
		$data = array(
			'st_datetime'	=>	$tdate,
			'st_eod_id'		=>	$eodnum
	    );

	    $this->db->where('st_id', $trid)
		->update('sales_transaction', $data);
	}

	public function deleteItemSales($salestrid,$salesid)
	{
		$this->db->trans_start();

			//get all items by trid
			$this->deletesalesitembyid($salesid);

			//delete item ledger
			$this->deleteitemledgerbyid($salesid);

			//delete load details
			$this->deleteloaddetailsbyid($salesid);

		$this->db->trans_complete();

		if ($this->db->trans_status() === FALSE)
		{
	        // generate an error... or use the log_message() function to log your error
	        //echo log_message();
			$this->db->trans_rollback();
			return false;
		}
		else
		{
			$this->db->trans_commit();
			return true;
		}
	}

	public function deleteTransaction($salestrid,$salesid)
	{
		$this->db->trans_start();

			// delete sales transaction by id
			$this->deletesalestransactionbytrid($salestrid);

			// delete payment details
			$this->deletepaymentbyid($salestrid);

			//get all items by trid
			$this->deletesalesitembyid($salesid);

			//delete item ledger
			$this->deleteitemledgerbyid($salesid);

			//delete load details
			$this->deleteloaddetailsbyid($salesid);

		$this->db->trans_complete();

		if ($this->db->trans_status() === FALSE)
		{
	        // generate an error... or use the log_message() function to log your error
	        //echo log_message();
			$this->db->trans_rollback();
			return false;
		}
		else
		{
			$this->db->trans_commit();
			return true;
		}
	}

	public function getReceivedPerMonth()
	{
		$this->db->select(
			"MONTHNAME(receiving_transaction.rtr_datetime) as month, 
			MONTH(receiving_transaction.rtr_datetime) as monthnum,
			YEAR(receiving_transaction.rtr_datetime) as year
		")
		->order_by('rtr_datetime', 'ASC')
		->group_by('YEAR(receiving_transaction.rtr_datetime),MONTH(receiving_transaction.rtr_datetime)');
		$query = $this->db->get('receiving_transaction');

		return $query->result();
	}

	public function getReceivedItemsPerMonth($month,$year)
	{
		$this->db->select(
			"IFNULL(SUM(receiving_items.rei_netprice),0.00) as netprice,
			item.it_name, item.it_item_oum, 
			IFNULL(SUM(receiving_items.rei_srp),0.00) as srp, 
			IFNULL(SUM(receiving_items.rei_qty),0.00) as qty 
		")
		->join('receiving_items','receiving_items.rei_recid = receiving_transaction.rtr_id')
		->join('item','item.it_id = receiving_items.rei_itemid')
		->where('MONTH(rtr_datetime)',$month)
		->where('YEAR(rtr_datetime)',$year)
		->group_by('receiving_items.rei_itemid');
		$query = $this->db->get('receiving_transaction');

		return $query->result();
    }
    
	public function getTotalEloadReceivedByBU()
	{
        $totload = 0;
		$this->db->select(
			"IFNULL(SUM(receiving_items.rei_qty),0.00) as totaload 
		")
		->join('receiving_transaction','receiving_transaction.rtr_id=receiving_items.rei_recid')
		->join('item','item.it_id = receiving_items.rei_itemid')
		->where('receiving_transaction.rtr_bu',$this->session->userdata('aload_buid'))
		->where('item.it_item_oum','load');
        $query = $this->db->get('receiving_items');        
		if($query->num_rows() > 0)
		{
			$totload = $query->row()->totaload;
        }
        return $totload;
    }

    public function eloadBegBalance()
    {
        $totalbeg = 0;
		$this->db->select(
			"IFNULL(SUM(simcards.scard_begbal),0.00) as totalbeg 
        ")
        ->where('simcards.scard_bu',$this->session->userdata('aload_buid'));
        $query = $this->db->get('simcards');
        //echo $this->db->last_query();
		if($query->num_rows() > 0)
		{
			$totalbeg = $query->row()->totalbeg;
        }
        return $totalbeg;
    }
    
    public function getTotalLoadNetSales()
    {
        $totnet = 0;
		$this->db->select(
			"IFNULL(SUM(sales_items.si_netprice),0.00) as totnet 
		")
		->join('sales_transaction','sales_transaction.st_id=sales_items.si_trid')
		->where('sales_transaction.st_bu',$this->session->userdata('aload_buid'))
		->group_start()
			 ->where('sales_items.si_itemid','1')
			 ->or_where('sales_items.si_itemid','2')
        ->group_end();
        $query = $this->db->get('sales_items');
        //echo $this->db->last_query();
		if($query->num_rows() > 0)
		{
			$totnet = $query->row()->totnet;
        }
        return $totnet;
    }

    public function getTotalLoadNetSales1()
    {
        $totnet = 0;
		$this->db->select(
			"IFNULL(SUM(sales_items.si_netprice),0.00) as totnet 
		")
        ->join('sales_transaction','sales_transaction.st_id=sales_items.si_trid')
        ->join('item','item.it_id=sales_items.si_itemid')
        ->where('sales_transaction.st_bu',$this->session->userdata('aload_buid'))
        ->where('it_item_oum','load');
        $query = $this->db->get('sales_items');
        //echo $this->db->last_query();
		if($query->num_rows() > 0)
		{
			$totnet = $query->row()->totnet;
        }
        return $totnet;
    }

    public function getTotalLoadSRPSales()
    {
        $totsrp = 0;
		$this->db->select(
			"IFNULL(SUM(sales_items.si_srp),0.00) as totsrp 
		")
        ->join('sales_transaction','sales_transaction.st_id=sales_items.si_trid')
        ->join('item','item.it_id=sales_items.si_itemid')
        ->where('sales_transaction.st_bu',$this->session->userdata('aload_buid'))
        ->where('it_item_oum','load');
        $query = $this->db->get('sales_items');
        //echo $this->db->last_query();
		if($query->num_rows() > 0)
		{
			$totsrp = $query->row()->totsrp;
        }
        return $totsrp;
    }

    public function getEloadSalesByYearAndMonth($ym)
    {
        $dym = explode("-", $ym);
        $year = $dym[0];
        $month = $dym[1];
		$this->db->select(
			"IFNULL(SUM(sales_items.si_srp),0.00) as totsrp 
		")
        ->join('sales_transaction','sales_transaction.st_id=sales_items.si_trid')
        ->join('item','item.it_id=sales_items.si_itemid')
        ->where('sales_transaction.st_bu',$this->session->userdata('aload_buid'))
        ->where('YEAR(sales_transaction.st_datetime)',$year)
        ->where('MONTH(sales_transaction.st_datetime)',$month)
        ->where('it_item_oum','load');
        $query = $this->db->get('sales_items');
        //echo $this->db->last_query();
		if($query->num_rows() > 0)
		{
			$totsrp = $query->row()->totsrp;
        }
        return $totsrp;
    }

    public function checkIfDateCleared($date)
    {
		$this->db->select(
			"clearedentry_date.cd_id 
		")
        ->where('clearedentry_date.cd_date',$date);
        $query = $this->db->get('clearedentry_date');
        //echo $this->db->last_query();
		return $query->num_rows();
    }

    public function getAllTransactionPerDayCount($date)
    {    	
		$this->db->select(
			"sales_items.si_qty,
			sales_items.si_id,
		    sales_items.si_netprice,
		    DATE_FORMAT(sales_transaction.st_datetime,'%m/%d/%Y') as datesold
		")
		->join('sales_transaction','sales_transaction.st_id = sales_items.si_trid')
		->where('sales_transaction.st_bu',$this->session->userdata('aload_buid'))
		->where("DATE_FORMAT(sales_transaction.st_datetime,'%Y-%m-%d')",$date)
		->order_by('sales_transaction.st_datetime', 'ASC');
		$query = $this->db->get('sales_items');

		return $query->num_rows();
    }

    public function saveClearedDate($date)
    {
        $data = array(
            'cd_date'       =>	$date,
            'cd_clearedby'  =>  $this->session->userdata('aload_buid')
        );

        $this->db->set('cd_datetime', 'NOW()', FALSE);

        $this->db->insert("clearedentry_date",$data);
    }

    public function getLastClearedDate($nrows = null)
    {
		$this->db->select(
			"clearedentry_date.cd_id,
			clearedentry_date.cd_date,
            clearedentry_date.cd_datetime,
            users.u_fullname
		")
		->join('users','users.u_id = clearedentry_date.cd_clearedby')
        ->order_by('clearedentry_date.cd_date', 'DESC')
        ->limit($nrows);
        $query = $this->db->get('clearedentry_date');
        return $query->result();
    }

    public function getSimCardBalanceByEODID($eodid)
    {
		$this->db->select(
			"simcards.scard_number,
			simcard_balance.sb_balance
		")
		->join('simcards','simcards.scard_id = simcard_balance.sb_simcardid')
		->where('simcard_balance.sb_eodid',$eodid)
		->order_by('simcard_balance.sb_id', 'ASC');
		$query = $this->db->get('simcard_balance');

		return $query->result();   
    }

	// public function getAllTransactionItemsBytrID($trid)
	// {
	// 	$this->db->select(
	// 		"sales_items.si_qty,
	// 		sales_items.si_srp,
	// 		sales_items.si_linedisc,
	// 		item.it_name,
	// 		sales_load_details.sld_refnum,
	// 		sales_load_details.sld_mobilenum	
	// 	")
	// 	->join('item','item.it_id = sales_items.si_itemid ')
	// 	->join('sales_load_details','sales_load_details.sld_trid = sales_items.si_id ','left')
	// 	->where('sales_load_details.sld_type','sold')
	// 	->where('sales_items.si_trid',$trid);		
	// 	$query = $this->db->get('sales_items');
	// 	return $query->result();
	// }
}

// SELECT 
// 	sales_transaction.st_trnum,
// 	sales_items.si_srp,
//     sales_load_details.sld_mobilenum,
//     sales_load_details.sld_refnum,
//     DATE_FORMAT(sales_transaction.st_datetime,'%M %d %y') as datey,
//     bu.bu_name,
//    	users.u_fullname
// FROM 
// 	sales_items
// INNER JOIN
// 	sales_transaction
// ON
// 	sales_transaction.st_id = sales_items.si_trid
// INNER JOIN
// 	sales_load_details
// ON
// 	sales_load_details.sld_trid = sales_transaction.st_id 
// INNER JOIN
// 	users
// ON
// 	users.u_id = sales_transaction.st_cashier
// INNER JOIN
// 	bu
// ON
// 	bu.bu_id = sales_transaction.st_bu
// WHERE 
// 	sales_transaction.st_eod_id='6'

// $this->db->select(
// 	"sales_transaction.st_trnum,
// 	sales_transaction.st_datetime,
//     sales_items.si_qty,
//     bu.bu_name,
//    	users.u_fullname
// ")
// ->join('sales_transaction','sales_transaction.st_id = sales_items.si_trid')
// ->join('sales_transaction','sales_transaction.st_id = sales_items.si_trid')
// ->join('users','users.u_id = sales_transaction.st_cashier')
// ->join('bu','bu.bu_id = sales_transaction.st_bu')
// ->join('sales_load_details','sales_load_details.sld_trid = sales_items.si_id ','left')
// ->where('sales_transaction.st_bu',$this->session->userdata('load_buid'))
// ->where('sales_transaction.st_datetime >=',$d1)
// ->where('sales_transaction.st_datetime <=',$d2);		
// $query = $this->db->get('sales_items');
// return $query->result();
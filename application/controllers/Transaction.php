<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Transaction extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
		// $this->load->model('model_denomination');
		// $this->load->model('Model_Transaction');
		$this->load->model('Model_Functions');
		$this->load->model('Model_Transaction');
		$this->load->model('Model_Database');
    }

	public function index()
	{
		$this->load->view('cashierlogin');
	}

	public function itemSelection()
	{
		$data['itemtype'] = $this->Model_Functions->getAllTableRecordsOrder('item_type','ity_id','ASC');
		$this->load->view('page/itemselection',$data);
	}

	public function loadtransferdialog()
	{
		$data['sim'] = $this->Model_Transaction->getAllSimCards();
		$this->load->view('page/loadtransfer',$data);
	}

	public function loadtransfer()
	{
		$response['st'] = false;
		$this->form_validation->set_rules('simfrom','From Sim Card','required|trim');
		$this->form_validation->set_rules('simto','To Sim Card','required|trim');
		$this->form_validation->set_rules('loadamt','Load Transfer','required|trim');
		$this->form_validation->set_error_delimiters('<div class="form_error">* ','</div>');

		if($this->form_validation->run()===FALSE)
		{
			$response['msg'] = validation_errors();
		}
		else 
		{
			
			//save load transfer			
			if($this->Model_Transaction->saveTransferLoad())
			{
				$response['st'] = true;
			}
			else 
			{
				$response['msg'] = "Something went wrong.";
			}
		}
		echo json_encode($response);
	}

	public function itemSelectionReceiving()
	{
		$data['itemtype'] = $this->Model_Functions->getAllTableRecordsOrder('item_type','ity_id','ASC');
		$this->load->view('page/itemselectionreceiving',$data);
	}

	public function getAllItems()
	{
		$response['st'] = false;

		$id = $this->input->post('id');

		$response['items'] = $this->Model_Functions->getAllTableRecordsOrderWhereOne('item','it_id','ASC','it_type',$id);

		echo json_encode($response);
	}

	public function getSimCardNumberByItemID()
	{
		$response['st'] = false;
		$itemid = $this->input->post('itemid');
		$res = $this->Model_Transaction->getSimCardNumberByItemID($itemid);

		if(is_array($res))
		{
			$response['st'] = true;
			$response['simcard'] = $res[1];
		}
		else 
		{
			$response['msg'] = "Something went wrong.";
		}

		echo json_encode($response);
	}

	public function getSimCardNumberBySimID()
	{
		$response['st'] = false;
		$itemid = $this->input->post('itemid');
		$res = $this->Model_Transaction->getSimCardNumberBySimID($itemid);

		if(is_array($res))
		{
			$response['st'] = true;
			$response['simcard'] = $res[1];
		}
		else 
		{
			$response['msg'] = "Something went wrong.";
		}

		echo json_encode($response);
	}


	public function getSrp()
	{
		$id = $this->input->post('id');

		$response['srp'] = $this->Model_Functions->getFields('item','it_srp','it_id',$id);

		echo json_encode($response);
	}

	public function getItemSRPNETPRICE()
	{
		$id = $this->input->post('id');

		$fields = "it_srp,it_netprice";

		$detail = $this->Model_Functions->getTableFields('item',$fields,'it_id',$id);

		$srp = 0;
		$netprice = 0;


		foreach ($detail as $d) 
		{
			$srp = $d->it_srp;
			$netprice = $d->it_netprice;
		}

		$response['srp'] = $srp;
		$response['netprice'] = $netprice;

		echo json_encode($response);
	}

	public function getNetPrice()
	{
		$id = $this->input->post('id');

		$response['srp'] = $this->Model_Functions->getFields('item','it_netprice','it_id',$id);

		echo json_encode($response);
	}

	public function addItemToCartSales()
	{
		$response['st'] = false;
		
		$itemtypeid = $this->input->post('itemtype');
		$itemnameid = $this->input->post('item');

		$loadamt = $this->input->post('loadamt');
		$loadamt = str_replace(",", "", $loadamt);
		$mobnum = $this->input->post('mobnum');
		$loadref = $this->input->post('loadref');

		$loaddeduct = $this->input->post('loaddeduct');
		$loaddeduct = str_replace(",", "", $loaddeduct);

		// echo $loaddeduct;

		// exit();

		$qty = $this->input->post('qty');

		$hasError = false;

		$totalsales = 0;
		$totalqty = 0;

		if(empty($itemtypeid) || empty($itemnameid))
		{
			$response['msg'] ='Please select item type and item name.';
		}
		else 
		{

			//get item name
			$itemname = $this->Model_Functions->getFields('item','it_name','it_id',$itemnameid);

			if($itemtypeid==1)
			{

				if(empty($loadamt) || empty($mobnum) || empty($loadref) || empty($loaddeduct))
				{
					$response['msg'] = 'Please input all required fieldsx.';
				}
				else 
				{

					//check if ref # already exist

					if($this->Model_Functions->countRow('sales_load_details',$loadref,'sld_refnum') > 0 && $loadref!='none')
					{
						$response['msg'] = 'Load Reference # already exist.';
					}
					else 
					{
						if($this->session->userdata('cart'))
						{
							$oldcart =  $this->session->userdata('cart');	

							rsort($oldcart);
							$index = $oldcart[0]['itemindex'];
							$index++;

							$cart = array(
								'itemindex'	=>  $index,
						        'itemtypeid'=>	$itemtypeid,
						        'itemid'    =>	$itemnameid,
						        'itemname'	=> 	$itemname,
						        'qty'     	=>	1,
						        'ref'		=> 	$loadref,
						        'mobnum'	=>	$mobnum,
						        'disc'		=> 	0,
						        'net'		=>  $loaddeduct,
						        'srp'		=>	$loadamt,
						        'total'		=>  $loadamt,
						        'is_load'	=>	true
							);									

							array_push($oldcart, $cart);
							$this->session->set_userdata('cart', $oldcart); 
							
							//var_dump($oldcart);

						}
						else 
						{
							$cart[] = array(
								'itemindex'	=>  1,
						        'itemtypeid'=>	$itemtypeid,
						        'itemid'    =>	$itemnameid,
						        'itemname'	=> 	$itemname,
						        'qty'     	=>	1,
						        'ref'		=> 	$loadref,
						        'mobnum'	=>	$mobnum,
						        'disc'		=> 	0,
						        'net'		=>  $loaddeduct,
						        'srp'		=>	$loadamt,
						        'total'		=>	$loadamt,
						        'is_load'	=>	true
							);

							$this->session->set_userdata('cart', $cart); 
						}		

						$response['st'] = true;							
					}		
				}
			}
			else 
			{
				if(empty($qty))
				{
					$response['msg'] = 'Please input all required fields.';
				}
				else 
				{
					// get item price
					$price = 0;
					$netrprice = 0;
					$fields = "it_srp,it_netprice";
					$details = $this->Model_Functions->getTableFields('item',$fields,'it_id',$itemnameid);

					foreach ($details as $d) 
					{
						$price = $d->it_srp;
						$netprice = $d->it_netprice;
					}

					$total = $price * $qty;

					if($this->session->userdata('cart'))
					{
						$oldcart =  $this->session->userdata('cart');

						rsort($oldcart);
						$index = $oldcart[0]['itemindex'];
						$index++;

						$cart = array(
							'itemindex'	=> 	$index,
					        'itemtypeid'=>	$itemtypeid,
					        'itemid'    =>	$itemnameid,
					        'itemname'	=> 	$itemname,
					        'qty'     	=>	$qty,
					        'ref'		=> 	'',
					        'mobnum'	=>	'',
					        'disc'		=> 	0,
					        'net'		=>  $netprice,
					        'srp'		=>	$price,
					        'total'		=>	$total,
					        'is_load'	=> false
						);

						array_push($oldcart, $cart);
						$this->session->set_userdata('cart', $oldcart); 

						//var_dump($oldcart);
					}
					else 
					{
						$cart[] = array(
							'itemindex'	=>  1,
					        'itemtypeid'=>	$itemtypeid,
					        'itemid'    =>	$itemnameid,
					        'itemname'	=> 	$itemname,
					        'qty'     	=>	$qty,
					        'ref'		=> 	'',
					        'mobnum'	=>	'',
					        'disc'		=> 	0,
					        'net'		=>  $netprice,
					        'srp'		=>	$price,
					        'total'		=>	$total,
					        'is_load'	=>	false
						);

						$this->session->set_userdata('cart', $cart); 
					}	

					$response['st'] = true;
				}
			}
		}

		if($this->session->userdata('cart'))
		{
			$cartarr = $this->session->userdata('cart');
			foreach ($cartarr as $c)
			{
				$totalqty = $totalqty + $c['qty'];
				$totalsales = $totalsales + $c['total'];
			}

			//var_dump($cartarr);
		}

		//var_dump($this->session->userdata('cart'));

		//$this->session->unset_userdata('cart');

		// $cartarr =  $this->session->userdata('cart');

		//echo count($cartarr);

		// var_dump($cartarr);

		// foreach ($cartarr as $key) 
		// {
		// 	echo $key['itemname'];
		// }
		$response['totalqty'] = $totalqty;
		$response['totalsales'] = $totalsales;

		echo json_encode($response);
		
		
	}

	public function addItemToCartReceiving()
	{
		$response['st'] = false;
		
		$itemtypeid = $this->input->post('itemtype');
		$itemnameid = $this->input->post('item');

		$loadamt = $this->input->post('loadamt');
		$mobnum = $this->input->post('mobnum');
		$loadref = $this->input->post('loadref');

		$qty = $this->input->post('qty');

		$hasError = false;

		$totalsales = 0;
		$totalqty = 0;

		if(empty($itemtypeid) || empty($itemnameid))
		{
			$response['msg'] ='Please select item type and item name.';
		}
		else 
		{

			//get item name
			$itemname = $this->Model_Functions->getFields('item','it_name','it_id',$itemnameid);

			if($itemtypeid==1)
			{
				if(empty($loadamt))
				{
					$response['msg'] = 'Please input all required fields.';
				}
				else 
				{
					if($this->session->userdata('cart'))
					{
						$oldcart =  $this->session->userdata('cart');	

						rsort($oldcart);
						$index = $oldcart[0]['itemindex'];
						$index++;

						$cart = array(
							'itemindex'	=>  $index,
					        'itemtypeid'=>	$itemtypeid,
					        'itemid'    =>	$itemnameid,
					        'itemname'	=> 	$itemname,
					        'qty'     	=>	$loadamt,
					        'ref'		=> 	$loadref,
					        'mobnum'	=>	$mobnum,
					        'disc'		=> 	0,
					        'net'		=>  0,
					        'srp'		=>	$loadamt,
					        'total'		=>  $loadamt,
					        'is_load'	=>	true
						);									

						array_push($oldcart, $cart);
						$this->session->set_userdata('cart', $oldcart); 

						//var_dump($oldcart);

					}
					else 
					{
						$cart[] = array(
							'itemindex'	=>  1,
					        'itemtypeid'=>	$itemtypeid,
					        'itemid'    =>	$itemnameid,
					        'itemname'	=> 	$itemname,
					        'qty'     	=>	$loadamt,
					        'ref'		=> 	$loadref,
					        'mobnum'	=>	$mobnum,
					        'disc'		=> 	0,
					        'net'		=>  0,
					        'srp'		=>	$loadamt,
					        'total'		=>	$loadamt,
					        'is_load'	=>	true
						);

						$this->session->set_userdata('cart', $cart); 
					}		

					$response['st'] = true;			
				}
			}
			else 
			{
				if(empty($qty))
				{
					$response['msg'] = 'Please input all required fields.';
				}
				else 
				{

					// get item price

					$price = $this->Model_Functions->getFields('item','it_netprice','it_id',$itemnameid);

					$total = $price * $qty;

					if($this->session->userdata('cart'))
					{
						$oldcart =  $this->session->userdata('cart');

						rsort($oldcart);
						$index = $oldcart[0]['itemindex'];
						$index++;

						$cart = array(
							'itemindex'	=> 	$index,
					        'itemtypeid'=>	$itemtypeid,
					        'itemid'    =>	$itemnameid,
					        'itemname'	=> 	$itemname,
					        'qty'     	=>	$qty,
					        'ref'		=> 	'',
					        'mobnum'	=>	'',
					        'disc'		=> 	0,
					        'net'		=>  '',
					        'srp'		=>	$price,
					        'total'		=>	$total,
					        'is_load'	=>	false

						);

						array_push($oldcart, $cart);
						$this->session->set_userdata('cart', $oldcart); 

						//var_dump($oldcart);

					}
					else 
					{
						$cart[] = array(
							'itemindex'	=>  1,
					        'itemtypeid'=>	$itemtypeid,
					        'itemid'    =>	$itemnameid,
					        'itemname'	=> 	$itemname,
					        'qty'     	=>	$qty,
					        'ref'		=> 	'',
					        'mobnum'	=>	'',
					        'disc'		=> 	0,
					        'net'		=>  '',
					        'srp'		=>	$price,
					        'total'		=>	$total,
					        'is_load'	=>	false
						);

						$this->session->set_userdata('cart', $cart); 
					}	

					$response['st'] = true;
				}
			}
		}

		if($this->session->userdata('cart'))
		{
			$cartarr = $this->session->userdata('cart');
			foreach ($cartarr as $c)
			{
				$totalqty = $totalqty + $c['qty'];
				$totalsales = $totalsales + $c['total'];
			}

			//var_dump($cartarr);
		}

		//$this->session->unset_userdata('cart');

		// $cartarr =  $this->session->userdata('cart');

		//echo count($cartarr);

		// var_dump($cartarr);

		// foreach ($cartarr as $key) 
		// {
		// 	echo $key['itemname'];
		// }
		$response['totalqty'] = $totalqty;
		$response['totalsales'] = $totalsales;

		echo json_encode($response);
	}

	public function loadtempsalesitems()
	{
		$this->load->view('page/tablesales');
	}

	public function checkItemTableSales()
	{
		$response['st'] =false;
		$count = 0;
		if($this->session->userdata('cart'))
		{
			if(count($this->session->userdata('cart'))>0)
			{
				$count = count($this->session->userdata('cart'));
				$response['st'] = true;
			}
		}

		$response['count'] = $count;
		echo json_encode($response);
	}

	public function unsetTableItemsSales()
	{
		$response['st'] = false;
		if($this->session->userdata('cart'))
		{
			$this->session->unset_userdata('cart');
			$response['st'] = true;
		}

		echo json_encode($response);			
	}

	public function voidTableItemSalesByItemIndex()
	{
		$id = $this->input->post('id');

		$response['st'] =false;


		if($this->session->userdata('cart'))
		{
			if(count($this->session->userdata('cart'))>0)
			{
				$cart = $this->session->userdata('cart');

				foreach ($cart as $key => $value) 
				{
					if($value['itemindex']==$id)
					{
						unset($cart[$key]);
						break;
					}
				}

				$cart = $this->session->set_userdata('cart',$cart);

				$response['st'] = true;
			}
		}

		echo json_encode($response);
	}

	public function updateTableItemSalesData()
	{
		$totalsales = 0;
		$totalqty = 0;

		if($this->session->userdata('cart'))
		{
			$cartarr = $this->session->userdata('cart');
			foreach ($cartarr as $c)
			{
				$totalqty = $totalqty + $c['qty'];
				$totalsales = $totalsales + $c['total'];
			}

			//var_dump($cartarr);
		}

		$response['totalqty'] = $totalqty;
		$response['totalsales'] = $totalsales;

		echo json_encode($response);
	}

	public function cashpayment()
	{
		$this->load->view('page/cashpayment.php');
	}

	public function checktotalpaymenttotalsales()
	{
		$response['st'] = false;
		$totalamt = 0;
		$change = 0;
		$amount = $this->input->post('amttender');

		$amount = str_replace(',', '', $amount);

		if($this->session->userdata('cart'))
		{
			$cartarr = $this->session->userdata('cart');
			foreach ($cartarr as $c)
			{
				$totalamt = $totalamt + $c['total'];				
			}
		}

		if($amount >= $totalamt)
		{	
			$change = $amount - $totalamt;

			$response['change'] = $change;

			// get trnumber
			//save tr

			if($this->Model_Transaction->saveCashPayment($totalamt,$amount,$change))
			{
				$response['st'] = true;
			}		
		}
		else 
		{
			$response['msg'] = 'Insufficient amount.';
		}		

		echo json_encode($response);
	}

	public function saveReceiving()
	{
		$response['st'] = false;

		$sinum = $this->input->post('sinum');
		$ponum = $this->input->post('ponum');
		$refnum = $this->input->post('refnum');
		$checkedby = $this->input->post('checkedby');

		if($sinum=='' || $ponum=='' || $refnum=='' || $checkedby=='')
		{
			$response['msg'] = 'Please fill all required fields.';
		}
		else
		{
			if($this->Model_Transaction->saveReceiving($sinum,$ponum,$refnum,$checkedby))
			{
				$response['st'] = true;
			}
		}		

		echo json_encode($response);
	}

	public function checksession()
	{
		$response['st'] = false;
		if($this->session->userdata('load_logged_in'))
		{
			$response['st'] = true;
		}

		echo json_encode($response);
    }
    
    public function checksession1()
    {
		$response['st'] = false;
		if($this->session->userdata('aload_logged_in'))
		{
			$response['st'] = true;
		}

		echo json_encode($response);
    }

	public function getReceivingNumber()
	{
		$response['st'] = true;

		$response['recnum'] = $this->Model_Functions->getTrNumber('receiving_transaction','rtr_recnum','rtr_bu',$this->session->userdata('load_buid'),'rtr_recnum','DESC');
		echo json_encode($response);
	}

	public function truncateDB()
	{
		$this->Model_atabase->truncateDB();
		echo 'yeah';
	}

	public function changeTransactionDate()
	{
		$response['st'] = false;
		$newdate = $this->input->post('newdate');
        $trid = $this->input->post('trid');

        //get transaction date
        $trdate = $this->Model_Functions->getFields('sales_transaction','st_datetime','st_id',$trid);
        $trdate_arr = explode(" ",$trdate);
        $trdate = $trdate_arr[0];

        $newdate = _dateFormatoSql($newdate);
        
        //check if date larger than today
        $date_now = date("Y-m-d");

        if($newdate > $date_now)
        {
            $response['msg'] = 'Error changing date..date is greater than today';
        }
        else 
        {
            //check if date is not equal or greater than clearing date
            if($this->Model_Functions->getFields('app_settings','app_settingvalue','app_tablename','date_cleared') > $newdate || 
            $this->Model_Transaction->checkIfDateCleared($newdate) > 0 ||
            $this->Model_Functions->getFields('app_settings','app_settingvalue','app_tablename','date_cleared')  >= $trdate ||
            $this->Model_Transaction->checkIfDateCleared($trdate) > 0 
            )
            {   
                $response['msg'] = "Date already cleared.";
            }
            else 
            {
                if($this->Model_Transaction->changeTransactionDate($newdate,$trid))
                {
                    $response['st'] = true;                    
                    $newdate = _dateFormat($newdate);
                    $response['newdate'] = $newdate;
                }
                else 
                {
                    $response['msg'] = "Error Changing Date.";
                }
            }
            //dri
        }  
		echo json_encode($response);
	}

	public function removeTransaction()
	{
		$response['st'] = false;
		// check if trid exist
		$trid = $this->input->post('trid');

		if(!$this->Model_Functions->count1('sales_transaction','st_id','st_id',$trid) > 0)
		{
			$response['msg'] = 'Transaction not found.';
		}
		else 
		{
			if($this->Model_Transaction->deletetransactionbyid($trid))
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

	public function saleslist()
	{
		$columns = array( 
            0 =>'st_datetime', 
            1 =>'st_trnum',
            2=> 'it_name',
            3=> 'si_qty',
            4=> 'si_srp'
        );

		$limit = $this->input->post('length');
        $start = $this->input->post('start');
        $order = $columns[$this->input->post('order')[0]['column']];
        $dir = $this->input->post('order')[0]['dir'];
  
        $totalData = $this->Model_Transaction->allsales_count();
          
        $totalFiltered = $totalData; 
            
        if(empty($this->input->post('search')['value']))
        {            
            $sales = $this->Model_Transaction->allsaleslist($limit,$start,$order,$dir);

            //var_dump($sales);
        }
        else 
        {
            $search = $this->input->post('search')['value']; 

            $sales =  $this->Model_Transaction->posts_saleslistsearch($limit,$start,$search,$order,$dir);

            $totalFiltered = $this->Model_Transaction->posts_saleslistsearch_count($search);
        }

        $data = array();
        if(!empty($sales))
        {
            foreach ($sales as $sale)
            {
                $nestedData['st_datetime'] = $sale->st_datetime;
                $nestedData['st_trnum'] = $sale->st_trnum;
                $nestedData['it_name'] = $sale->it_name;
                $nestedData['si_qty'] = $sale->si_qty;
                $nestedData['si_srp'] = $sale->si_srp;
                //$nestedData['created_at'] = date('j M Y h:i a',strtotime($post->cus_register_at));
                
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

	public function updateLoadDetails()
	{
		$response['st'] = false;
		if($this->Model_Transaction->updateLoadDetails())
		{
			$response['st'] = true;
		}
		else
		{
			$response['msg'] = 'Something went wrong..';
		} 

		echo json_encode($response);
	}

	public function checkTransaction()
	{
		$response['st'] = false;
        $date = $this->input->post('date');
        $rtype = $this->input->post('reportype');
        $itemoum = '';
       	$fordate = _dateFormatoSql($date);
        if($rtype=='1')
        {
        	$itemoum = 'load';

			if(count($this->Model_Transaction->getTransactionPerDayLoad($fordate,$itemoum))> 0)
			{
				$response['st'] = true;
			}
			else 
			{
				$response['msg'] = 'No Result Found.';
			}
        }
        else 
        {        	      
			if(count($this->Model_Transaction->getTransactionPerDay($fordate,$itemoum))> 0)
			{
				$response['st'] = true;
			}
			else 
			{
				$response['msg'] = 'No Result Found.';
			}        	
        }

		echo json_encode($response);
    }
    
    public function checkTransactionByRange()
    {
		$response['st'] = false;
        $sdate = $this->input->post('sdate');
        $edate = $this->input->post('edate');

        if(count($this->Model_Transaction->getTransactionPerDayByRange($sdate,$edate))> 0)
        {
            $response['st'] = true;
        }
        //var_dump($this->Model_Transaction->getTransactionPerDayByRange($sdate,$edate));
        echo json_encode($response);
    }

	public function updateSRPSales()
	{
		$response['st'] = false;
		$salesid = $this->input->post('salesid');
        $newsrp = $this->input->post('newsrp');
        $srp = $this->input->post('srp');

        //check item uom
        $uom = $this->Model_Transaction->getItemUOMBySalesID($salesid);

		if($this->Model_Transaction->updateSRPSales($salesid,$newsrp,$srp))
		{
			$response['uom'] = $uom;
			$response['st'] = true;
		}
		else 
		{
			$response['msg'] = 'Something went wrong.';
		}

		echo json_encode($response);
	}

	public function updateNetSales()
	{
		$response['st'] = false;
		$salesid = $this->input->post('salesid');
        $newnet = $this->input->post('newnet');
        $net = $this->input->post('net');

        //check item uom
        $uom = $this->Model_Transaction->getItemUOMBySalesID($salesid);

		if($this->Model_Transaction->updateNetSales($salesid,$newnet,$net))
		{
			$response['uom'] = $uom;
			$response['st'] = true;
		}
		else 
		{
			$response['msg'] = 'Something went wrong.';
		}

		echo json_encode($response);
	}

	public function changeTransactionDateByTrID()
	{
		$trid = $this->input->post('trid');
		$date_c = $this->input->post('date_c');
		$eod = $this->input->post('eod');
		$date_q = $this->input->post('qdate');

		$date_qn = _dateFormatoSql($date_q);		

		$eodnum = "";
		$tdate = "";
		$newdate = _dateFormatoSql($date_c);

		$response['st'] = false;		
		$hasError = false;

		//get transaction original date

		//check date first 
		$data = $this->Model_Transaction->getTransactionByDate($newdate);

		foreach ($data as $key) 
		{
			$tdate = $key->st_datetime;
			$eodnum = $key->st_eod_id;
		}

		if($tdate == '')
		{
			$tdate = $newdate.' '.todays_time_24hours();
		}

		//var_dump($data);
		if($eod=="1")
		{
			if($eodnum=='')
			{
				$hasError = true;
				$response['msg'] = "EOD Number not found.";
			}
		}

		if(!$hasError)
		{
			//update transaction date 

			if($this->Model_Transaction->updateTransactionDateTrans($trid,$tdate,$date_qn,$eodnum))
			{
				$response['st'] = true;
			}
		}

		echo json_encode($response);
	}

	public function deleteItemsBySalesID()
	{
		$salestrid = $this->input->post('salestrid');
		$salesid = $this->input->post('salesid');

		$response['st'] = false;

		//check if transaction exist
		$data = $this->Model_Functions->count1('sales_items','si_id','si_trid',$salestrid);

		if(count($data) > 0)
		{
			if(count($data)>1)
			{
				if($this->Model_Transaction->deleteItemSales($salestrid,$salesid))
				{
					$response['st'] = true;
				}				
			}
			else
			{
				if($this->Model_Transaction->deleteTransaction($salestrid,$salesid))
				{
					$response['st'] = true;
				}
			}

			if(!$response['st'])
			{
				$response['msg'] = 'Something went wrong.';
			}
		}
		else
		{
			$response['msg'] = 'Transaction not found!';
		}

		echo json_encode($response);		
    }
    
	public function simBalanceDialog()
	{
        $data['sims'] = $this->Model_Transaction->getAllActiveSimCards();
		$this->load->view('dialog/simBalanceDialog',$data);
    }
    
    public function getAllActiveSimcards()
    {
        $response['st'] = false;
        if(count($this->Model_Transaction->getAllActiveSimCards())>0)
        {
            $response['st'] = true;
        }
        echo json_encode($response);
    }


	//sql-mode="ERROR_FOR_DIVISION_BY_ZERO,NO_AUTO_CREATE_USER"
	//set GLOBAL sql_mode=''

}


<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
		// $this->load->model('model_denomination');
		$this->load->model('Model_Transaction');
    }


	public function index()
	{
		$this->load->view('cashierlogin');
	}

	public function dashboard()
	{
        $data['totrecload'] = $this->Model_Transaction->getTotalEloadReceivedByBU();            
        $data['totnetsales'] = $this->Model_Transaction->getTotalLoadNetSales1();
        $data['totsrpsales'] = $this->Model_Transaction->getTotalLoadSRPSales();

		$data['title'] = 'Dashboard';			
		$data['menuactive'] = 'dashboard';	

		$this->load->view('layout/header',$data);
		$this->load->view('layout/menu',$data);
		$this->load->view('page/dashboard');
		$this->load->view('layout/footer');

	}

	public function changeDate()
	{
		if($this->session->userdata('aload_logged_in'))
		{
			$data['title'] = 'Change Date';	
			$data['menuactive'] = 'concerns';

			$this->load->view('layout/header',$data);
			$this->load->view('layout/menu',$data);
			$this->load->view('page/changedate');
			$this->load->view('layout/footer');
		}
		else 
		{
			redirect(base_url().'home/login');
		}
	}

	public function loadeducationquery()
	{
		if($this->session->userdata('aload_logged_in'))
		{
			$data['title'] = 'Load Deduction';	
			$data['menuactive'] = 'concerns';		

			$this->load->view('layout/header',$data);
			$this->load->view('layout/menu',$data);
			$this->load->view('page/loadeducationquery');
			$this->load->view('layout/footer');
		}
		else 
		{
			redirect(base_url().'home/login');
		}
	}

	public function loadeducationqueryresult()
	{
		if($this->session->userdata('aload_logged_in'))
		{
			$qdate = $this->input->post('querytrdate');
			$data['hasquery'] = true;
			$data['title'] = 'Query Date Result';	
			$data['menuactive'] = 'concerns';

			$data['loaditem'] = $this->Model_Transaction->getAllLoadItems();

			if($this->input->post('querytrdate')=="")
			{
				$data['hasquery'] = false;
			}
			else 
			{
				$drange = explode('-', $qdate);
				$drange1 = $drange[0];
				$drange2 = $drange[1];

				$data['qdate'] = $qdate;				

				$drange1 = _dateFormatoSql($drange1);
				$drange2 = _dateFormatoSql($drange2);

				$results = $this->Model_Transaction->getAllTransactionByRangeDetailedQuery($drange1,$drange2,2);

				$arr_d = array();

				foreach ($results as $r) 
				{
					$arr_items = array();
					$items = $this->Model_Transaction->getAllTransactionItemsBytrID($r->st_id);

					foreach ($items as $i) 
					{
						$ref = "";
						$mobilenum = "";

						if($i->it_item_oum=='load')
						{

							$load_det = $this->Model_Transaction->getAllSalesLoadDetails($i->si_id);

							foreach ($load_det as $ld) 
							{
								$ref = $ld->sld_refnum;
								$mobilenum = $ld->sld_mobilenum;
							}
						}

						$arr_items[] = array(
							'item_id'		=>	$i->si_itemid,
							'item_name' 	=>	$i->it_name,
							'items_srp'		=>	$i->si_srp,
							'item_linedisc'	=>	$i->si_linedisc,
							'item_qty'		=>	$i->si_qty,
							'item_ref'		=>	$ref,
							'item_mobile'	=>	$mobilenum,
							'item_unit'		=>	$i->it_item_oum,
							'item_itemtrid'	=>	$i->si_id,
							'item_net'		=>	$i->si_netprice
						);
					}	

					$arr_d[] =  array(
						'trans_id'			=>	$r->st_id,
						'trans_num' 		=>	$r->st_trnum,
						'trans_datetime'	=>	$r->st_datetime,
						'trans_store'		=>	$r->bu_name,
						'trans_cashier'		=>	$r->u_fullname,
						'items'	=>  $arr_items
					);			
				}

				$data['results'] = $arr_d;
			}

			$this->load->view('layout/header',$data);
			$this->load->view('layout/menu',$data);
			$this->load->view('page/loadeducationqueryresult');
			$this->load->view('layout/footer');
		}
		else 
		{
			redirect(base_url().'home/login');
		}
	}

	public function removetransactionquery()
	{
		if($this->session->userdata('aload_logged_in'))
		{
			$data['title'] = 'Remove Transaction';			
			$data['menuactive'] = 'concerns';

			$this->load->view('layout/header',$data);
			$this->load->view('layout/menu',$data);
			$this->load->view('page/removetransactionquery');
			$this->load->view('layout/footer');
		}
		else 
		{
			redirect(base_url().'home/login');
		}
	}

	public function removetrans()
	{
		if($this->session->userdata('aload_logged_in'))
		{
			$qdate = $this->input->post('querytrdate');
			$data['hasquery'] = true;
			$data['title'] = 'Query Date Result';
			$data['menuactive'] = 'concerns';

			if($this->input->post('querytrdate')=="")
			{
				$data['hasquery'] = false;
			}
			else 
			{
				$drange = explode('-', $qdate);
				$drange1 = $drange[0];
				$drange2 = $drange[1];

				$data['qdate'] = $qdate;				

				$drange1 = _dateFormatoSql($drange1);
				$drange2 = _dateFormatoSql($drange2);

				$results = $this->Model_Transaction->getAllTransactionByRangeDetailedQuery($drange1,$drange2,2);

				$arr_d = array();

				foreach ($results as $r) 
				{
					$arr_items = array();
					$items = $this->Model_Transaction->getAllTransactionItemsBytrID($r->st_id);

					foreach ($items as $i) 
					{
						$ref = "";
						$mobilenum = "";

						if($i->si_itemid=='1' || $i->si_itemid=='2')
						{

							$load_det = $this->Model_Transaction->getAllSalesLoadDetails($i->si_id);

							foreach ($load_det as $ld) 
							{
								$ref = $ld->sld_refnum;
								$mobilenum = $ld->sld_mobilenum;
							}
						}

						$arr_items[] = array(
							'item_id'		=>	$i->si_itemid,
							'item_name' 	=>	$i->it_name,
							'items_srp'		=>	$i->si_srp,
							'item_linedisc'	=>	$i->si_linedisc,
							'item_qty'		=>	$i->si_qty,
							'item_ref'		=>	$ref,
							'item_mobile'	=>	$mobilenum
						);
					}	

					$arr_d[] =  array(
						'trans_id'			=>	$r->st_id,
						'trans_num' 		=>	$r->st_trnum,
						'trans_datetime'	=>	$r->st_datetime,
						'trans_store'		=>	$r->bu_name,
						'trans_cashier'		=>	$r->u_fullname,
						'items'	=>  $arr_items
					);			
				}

				$data['results'] = $arr_d;
			}

			$this->load->view('layout/header',$data);
			$this->load->view('layout/menu',$data);
			$this->load->view('page/removetrans');
			$this->load->view('layout/footer');
		}
		else 
		{
			redirect(base_url().'home/login');
		}
	}

	public function rangequerydates()
	{
		if($this->session->userdata('aload_logged_in'))
		{
			$qdate = $this->input->post('querytrdate');
			$data['hasquery'] = true;
			$data['title'] = 'Query Date Result';
			$data['menuactive'] = 'concerns';	
			if($this->input->post('querytrdate')=="")
			{
				$data['hasquery'] = false;
			}
			else 
			{
				$drange = explode('-', $qdate);
				$drange1 = $drange[0];
				$drange2 = $drange[1];

				$data['qdate'] = $qdate;				

				$drange1 = _dateFormatoSql($drange1);
				$drange2 = _dateFormatoSql($drange2);

				$results = $this->Model_Transaction->getAllTransactionByRangeDetailedQuery($drange1,$drange2,2);

				$arr_d = array();

				foreach ($results as $r) 
				{
					$arr_items = array();
					$items = $this->Model_Transaction->getAllTransactionItemsBytrID($r->st_id);

					foreach ($items as $i) 
					{
						$ref = "";
						$mobilenum = "";

						if($i->si_itemid=='1' || $i->si_itemid=='2')
						{

							$load_det = $this->Model_Transaction->getAllSalesLoadDetails($i->si_id);

							foreach ($load_det as $ld) 
							{
								$ref = $ld->sld_refnum;
								$mobilenum = $ld->sld_mobilenum;
							}
						}

						$arr_items[] = array(
							'item_id'		=>	$i->si_itemid,
							'item_name' 	=>	$i->it_name,
							'items_srp'		=>	$i->si_srp,
							'item_linedisc'	=>	$i->si_linedisc,
							'item_qty'		=>	$i->si_qty,
							'item_ref'		=>	$ref,
							'item_mobile'	=>	$mobilenum
						);
					}	

					$arr_d[] =  array(
						'trans_id'			=>	$r->st_id,
						'trans_num' 		=>	$r->st_trnum,
						'trans_datetime'	=>	$r->st_datetime,
						'trans_store'		=>	$r->bu_name,
						'trans_cashier'		=>	$r->u_fullname,
						'items'	=>  $arr_items
					);			
				}

				$data['results'] = $arr_d;
			}

			
			$this->load->view('layout/header',$data);
			$this->load->view('layout/menu',$data);
			$this->load->view('page/querydateresult');
			$this->load->view('layout/footer');
		}
		else 
		{
			redirect(base_url().'home/login');
		}
	}

	public function cashiermain()
	{
		if($this->session->userdata('load_logged_in'))
		{
			$this->load->view('page/cashiermain');
		}
		else 
		{
			$this->load->view('cashierlogin');
		}		
	}

	public function login()
	{
		$this->load->view('eloadlogin');
	}

	public function receivedlist()
	{
		if($this->session->userdata('aload_logged_in'))
		{
			$data['title'] = 'Receiving List';	
			$data['menuactive'] = 'list';

			// get items by BU
			$recs = $this->Model_Transaction->getReceivedList();

			$arr_d = array();

			foreach ($recs as $r) 
			{
				$arr_items = array();

				$items = $this->Model_Transaction->getAllReceivedItemsBytrID($r->rtr_id);

				foreach ($items as $i) 
				{
					$ref = "";
					$mobilenum = "";

					if($i->it_item_oum=='load')
					{
						$load = $this->Model_Transaction->getReceivingLoadDetails($i->rei_id);
						foreach ($load as $l) 
						{
							$ref = $l->sld_refnum;
							$mobilenum = $l->sld_mobilenum;
						}
					}

					$arr_items[] = array(
						'item_id'		=>	$i->it_id,
						'item_name' 	=>	$i->it_name,
						'items_srp'		=>	$i->rei_srp,
						'item_qty'		=>	$i->rei_qty,
						'item_ref'		=>	$ref,
						'item_mobile'	=>	$mobilenum
					);
				}

				$arr_d[] =  array(
					'rec_id'		=>	$r->rtr_id,
					'rec_num' 		=>	$r->rtr_recnum,
					'rec_datetime'	=>	$r->rtr_datetime,
					'rec_si'		=>	$r->rtr_si,
					'rec_po'		=>	$r->rtr_po,
					'rec_ref'		=>  $r->rtr_ref,
					'rec_items'		=> 	$arr_items
				);
			}

			$recmonth = $this->Model_Transaction->getReceivedPerMonth();

			$arr_rc = array();
			

			foreach ($recmonth as $rc) 
			{


				$recmonthitems = $this->Model_Transaction->getReceivedItemsPerMonth($rc->monthnum,$rc->year);

				$arr_rcitems = array();

				foreach ($recmonthitems as $rci) 
				{
					$arr_rcitems[] = array(
						'reci_netprice'		=>	$rci->netprice,
						'rec_itemname' 		=>	$rci->it_name,
						'rec_oum'			=>	$rci->it_item_oum,
						'rec_srp'			=>	$rci->srp,
						'rec_qty'			=>	$rci->qty
					);
				}

				$arr_rc[] = array(
					'rec_monthnum'	=>	$rc->monthnum,
					'rec_monthname' =>	$rc->month,
					'rec_year'		=>	$rc->year,
					'rec_items'		=>	$arr_rcitems
				);

			}

			$data['list'] = $arr_d;
			$data['mlist'] = $arr_rc;

			// echo "<pre>"; 
			// print_r($arr_d);
			// echo "</pre>";

			$this->load->view('layout/header',$data);
			$this->load->view('layout/menu',$data);
			$this->load->view('page/receivedlist');
			$this->load->view('layout/footer');
		}
		else 
		{
			redirect(base_url().'home/login');
		}
	}

	public function sales()
	{
		if($this->session->userdata('aload_logged_in'))
		{
			$data['title'] = 'Sales';	
			$data['menuactive'] = 'sales';
			$this->load->view('layout/header',$data);
			$this->load->view('layout/menu',$data);
			$this->load->view('page/saleslist');
			$this->load->view('layout/footer');
		}
		else 
		{
			redirect(base_url().'home/login');
		}
	}

	public function reportaccountingsales()
	{
		$data['title'] = "Report";
		$data['menuactive'] = 'reportsales';
		$this->load->view('layout/header',$data);
		$this->load->view('layout/menu',$data);
		$this->load->view('page/reportaccountingsales');
		$this->load->view('layout/footer');
	}

	public function loadtransferlist()
	{
		$data['title'] = "Load Transfer";
		$data['menuactive'] = 'list';

		$data['transferlist'] = $this->Model_Transaction->getLoadTransferList();

		$this->load->view('layout/header',$data);
		$this->load->view('layout/menu',$data);
		$this->load->view('page/loadtransferlist');
		$this->load->view('layout/footer');
    }
    
    public function simcardeodlist()
    {
		if($this->session->userdata('aload_logged_in'))
		{
            $data['title'] = "Sim Card EOD List";
            $data['menuactive'] = 'list';
    
            $data['transferlist'] = $this->Model_Transaction->getLoadTransferList();
    
            $this->load->view('layout/header',$data);
            $this->load->view('layout/menu',$data);
            $this->load->view('page/simcardeodlist');
            $this->load->view('layout/footer');
		}
		else 
		{
			redirect(base_url().'home/login');
		}
    }

	public function itemsalesquery()
	{
		$data['title'] = "Sales Per Day";
		$data['menuactive'] = 'sales';
		$data['it_item'] = $this->Model_Transaction->getItemName();
		$this->load->view('layout/header',$data);
		$this->load->view('layout/menu',$data);
		$this->load->view('page/itemsalesquery',$data);
		$this->load->view('layout/footer');
	}

	public function salesreportperday()
	{

		$qdate = $this->input->post('date');

		$rdate = date("Y-m-d", strtotime($qdate));

		$data['title'] = "Sales Per Day Result";
		$data['menuactive'] = 'sales';

		$data['ddate'] = date("F d, Y", strtotime($qdate));

		$ledger = $this->Model_Transaction->getSalesPerDate($rdate);

		$data['ddates'] = $qdate;

		$arr_items = array();
		$total = 0;

		foreach ($ledger as $l) 
		{
			$uom = "";
			$qty = 0;
			$ref = "";
			$mobilenum = "";
			$subtotal = 0;
			if($l->it_item_oum=='load')
			{
				$uom = $l->it_item_oum;
				$subtotal = $l->si_srp;
				$qty = $l->si_netprice;

				$loadetails = $this->Model_Transaction->getAllSalesLoadDetails($l->si_id);				

				foreach ($loadetails as $ld) 
				{
					$ref = $ld->sld_refnum;
					$mobilenum = $ld->sld_mobilenum;
				}	
			}
			else 
			{
				$qty = $l->si_qty;
				$subtotal = $qty * $l->si_srp;
			}

			$total += $subtotal;

			$arr_items[] = array(
				'trans_id'				=>	$l->st_id,
				'trans_ledid' 			=>	$l->si_id,
				'trans_itemname'		=>	$l->it_name,
				'trans_itemqty'			=>	$qty,
				'trans_itemsrp'			=>	$l->si_srp,
				'trans_itemnet'			=>	$l->si_netprice,
				'trans_itemloadref'		=>	$ref,
				'trans_itemloadmobile'	=>	$mobilenum,
				'trans_subtotal'		=>	$subtotal,
				'trans_oum'				=>	$uom,
				'trans_by'				=>  $l->u_fullname
			);
		}

		$data['items'] = $arr_items;
		$data['total'] = $total;

		$this->load->view('layout/header',$data);
		$this->load->view('layout/menu',$data);
		$this->load->view('page/itemsalesresult');
		$this->load->view('layout/footer');
	}

	public function filteritemsales()
	{

		$qdate = $this->input->post('date');
		$it_id = $this->input->post('item_name');
		$rdate = date("Y-m-d", strtotime($qdate));

		$data['it_id'] = $it_id;
		$data['title'] = "Sales Per Day Result";
		$data['menuactive'] = 'sales';

		$data['ddate'] = date("F d, Y", strtotime($qdate));

		$ledger = $this->Model_Transaction->getSalesPerItem($rdate,$it_id);

		$data['ddates'] = $qdate;

		$arr_items = array();
		$total = 0;

		foreach ($ledger as $l) 
		{
			$uom = "";
			$qty = 0;
			$ref = "";
			$mobilenum = "";
			$subtotal = 0;
			if($l->it_item_oum=='load')
			{
				$uom = $l->it_item_oum;
				$subtotal = $l->si_srp;
				$qty = $l->si_netprice;

				$loadetails = $this->Model_Transaction->getAllSalesLoadDetails($l->si_id);				

				foreach ($loadetails as $ld) 
				{
					$ref = $ld->sld_refnum;
					$mobilenum = $ld->sld_mobilenum;
				}	
			}
			else 
			{
				$qty = $l->si_qty;
				$subtotal = $qty * $l->si_srp;
			}

			$total += $subtotal;

			$arr_items[] = array(
				'trans_id'				=>	$l->st_id,
				'trans_ledid' 			=>	$l->si_id,
				'trans_itemname'		=>	$l->it_name,
				'trans_itemqty'			=>	$qty,
				'trans_itemsrp'			=>	$l->si_srp,
				'trans_itemnet'			=>	$l->si_netprice,
				'trans_itemloadref'		=>	$ref,
				'trans_itemloadmobile'	=>	$mobilenum,
				'trans_subtotal'		=>	$subtotal,
				'trans_oum'				=>	$uom,
				'trans_by'				=>  $l->u_fullname
			);
		}

		$data['items'] = $arr_items;
		$data['total'] = $total;

		$this->load->view('layout/header',$data);
		$this->load->view('layout/menu',$data);
		$this->load->view('page/filteritemsales');
		$this->load->view('layout/footer');
	}

	public function checkLackingSalesItemsOnLedger()
	{
		$data['title'] = "Lacking Entries";
		$data['menuactive'] = 'lack';

		$arr_items = array();

		$datatr = $this->Model_Transaction->getAllSales();

        foreach ($datatr as $dt) 
        {
        	$qty = 0;
            if($this->Model_Transaction->checkSalesItemExistInItemLedger($dt->si_id)==0)
            {
            	if($dt->it_item_oum=='load')
            	{
            		$qty = $dt->si_netprice;
            	}
            	else 
            	{
            		$qty = $dt->si_qty;
            	}

				$arr_items[] = array(
					'trans_saleid'		=>	$dt->si_id,
					'trans_datesold'	=>	$dt->datesold,
					'trans_itemname'	=>	$dt->it_name,
					'trans_net'			=>	$dt->si_netprice,
					'tran_qty'			=>	$qty,
					'trans_srp'			=>	$dt->si_srp					
				);
            }
        }

        $data['items'] = $arr_items;

		$this->load->view('layout/header',$data);
		$this->load->view('layout/menu',$data);
		$this->load->view('page/lackingentries');
		$this->load->view('layout/footer');
    }
    
    public function eloadchart()
    {

        // get months
        $montsnew = [];
        for ($i = 0; $i <= 7; $i++) {
            $months[] = date("M Y", strtotime( date( 'Y-m-01' )." - $i months"));
        }
        for ($i = count($months) - 1; $i >= 0; $i--)
        {
            $monthsnew[] = $months[$i];
            $ym = date("Y-m",(strtotime($months[$i])));
            $sales[] = $this->Model_Transaction->getEloadSalesByYearAndMonth($ym);
        }

        //get todays date
        $arrym = explode("-",date("Y-m",(strtotime(end($months)))));

        $year = $arrym[0];
        $month = $arrym[1];

        $chartTitle = "Sales: ".date("M", mktime(0, 0, 0, $month, 10)).", ".$year." - ".date('M, Y');

        // get sales per month
        $response['chartTitle'] = $chartTitle; 
        $response['months'] = $monthsnew;
        $response['sales'] = $sales;
        echo json_encode($response);
    }

    public function rangereport()
    {
        $data['title'] = "Range Report";

		$data['menuactive'] = 'reportsales';
		$this->load->view('layout/header',$data);
		$this->load->view('layout/menu',$data);
		$this->load->view('page/rangereport',$data);
		$this->load->view('layout/footer');

    }

    public function textfileExcelConversion()
    {
        // $s = "loaded";

        // switch($s)
        // {
        //     case 'Transferred':
        //         echo 'yeah';
        //         break;
        //     default:
        //         echo 'wasap';
        //         break;
        // }

		if($this->session->userdata('aload_logged_in'))
		{
			$data['title'] = 'Textfile Upload';			
			$data['menuactive'] = 'textfiletoexcel';
			$this->load->view('layout/header',$data);
			$this->load->view('layout/menu',$data);
			$this->load->view('page/textfileupload');
			$this->load->view('layout/footer');
		}
		else 
		{
			redirect(base_url().'home/login');
		}
    }

    function test()
    {
        echo PHPinfo();
    }

    public function receivereport()
    {
        $data['title'] = "Receiving Report";

		$data['menuactive'] = 'receivereport';
		$this->load->view('layout/header',$data);
		$this->load->view('layout/menu',$data);
		$this->load->view('page/receivereport',$data);
		$this->load->view('layout/footer');

    }


}

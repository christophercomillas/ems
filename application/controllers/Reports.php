<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Reports extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
		$this->load->model('Model_User');
		$this->load->model('Model_Transaction');
		$this->load->model('Model_Functions');
    }

    public function index()
    {
    	echo 'yeah';
    }

    public function eosreport()
    {
    	$this->load->view("page/eosreport");
    }

    public function terminalreportpdf()
    {
    	$type = $this->uri->segment(3, 0);
    	$d1 = $this->uri->segment(4, 0);
    	$d2 = $this->uri->segment(5, 0);

    	$buname = preg_replace('/\s+/', '', $this->session->userdata('load_buname'));

		$this->load->library('pdfcreate');

		$pdf = new pdfcreate();
		//$pdf = new FPDF('p','mm','A4');
		$pdf->AliasNbPages();
		$pdf->AddPage("P","Letter");

		if($d1==$d2)
		{
			$subheadertext = _dateFormat($d1);
		}
		else
		{
			$subheadertext = 'From '._dateFormat($d1).' to '._dateFormat($d2);
		}
		
		$pdf->docHeaderStoreTerminalReport($this->session->userdata('load_buname'),$subheadertext);

		$pdf->subheaderTerminalReport(todays_date());

		$pdf->setReportType('tr');

		// $pdf->SetFont("Arial", "", 10);
		// $pdf->Cell(6,5,'',0,0,'R');
		// $pdf->Cell(18,5,'TR #',1,0,'C');
		// $pdf->Cell(88,5,'Item Name',1,0,'C');
		// $pdf->Cell(16,5,'Qty',1,0,'C');
		// $pdf->Cell(14,5,'Disc',1,0,'C');
		// $pdf->Cell(22,5,'SRP',1,0,'C');
		// $pdf->Cell(22,5,'Total',1,0,'C');

		$pdf->Ln();

		$tr = $this->Model_Transaction->getAllTransactionByRangeDetailed($d1,$d2,$type);

		$total = 0;
		
		$tqty = 0;
		$totdisc = 0;

		foreach ($tr as $t) 
		{
			$stotal = 0;
			$stqty = 0;
			$pdf->SetFont("Arial", "", 10);			


			$pdf->Cell(6,5,'',0,0,'R');
			$pdf->Cell(18,5,'TR #','TB',0,'R');
			$pdf->Cell(100,5,zeroes($t->st_trnum,6),'TB',0,'L');
			$pdf->Cell(18,5,'Date:','TB',0,'R');
			$pdf->Cell(48,5,_dateFormat($t->st_datetime),'TB',0,'L');
			$pdf->Ln(6);
			$items = $this->Model_Transaction->getAllTransactionItemsBytrID($t->st_id);
			$pdf->SetFont("Arial", "I", 7);	
			$pdf->Cell(8,5,'',00,0,'R');
			$pdf->Cell(110,5,'Item',0,0,'R');
			$pdf->Cell(14,5,'SRP',0,0,'R');
			$pdf->Cell(14,5,'Discount',0,0,'R');
			$pdf->Cell(18,5,'Quantity',0,0,'R');
			$pdf->Cell(18,5,'Subtotal',0,0,'R');
			$pdf->Ln();

			foreach ($items as $i) 
			{
				$sdtotal = 0;
				$sdtotal = ($i->si_srp * $i->si_qty) - $i->si_linedisc;
				$pdf->SetFont("Arial", "", 9);	
				$pdf->Cell(8,5,'',0,0,'R');
				$pdf->Cell(110,5,utf8_decode(html_entity_decode($i->it_name)),0,0,'R');
				$pdf->Cell(14,5,$i->si_srp,0,0,'R');
				$pdf->Cell(14,5,$i->si_linedisc,0,0,'R');
				$pdf->Cell(18,5,$i->si_qty,0,0,'R');
				$pdf->Cell(18,5,number_format($sdtotal,2),0,0,'R');
				//echo $i->si_srp.'=>';
				$totdisc += $i->si_linedisc;
				$stotal += $sdtotal;
				$stqty += $i->si_qty;
				if($i->si_itemid=='1' || $i->si_itemid=='2')
				{
					$load_det = $this->Model_Transaction->getAllSalesLoadDetails($i->si_id);

					foreach ($load_det as $ld) 
					{
						$pdf->Ln();
						$pdf->SetFont("Arial", "", 8);	
						$pdf->Cell(8,5,'',0,0,'R');
						$pdf->Cell(24,5,'Mobile #',0,0,'R');
						$pdf->Cell(28,5,$ld->sld_mobilenum,0,0,'R');
						$pdf->Cell(24,5,'Ref #',0,0,'R');
						$pdf->Cell(28,5,$ld->sld_refnum,0,0,'R');
					}
				}
				$pdf->Ln();
			}
			$pdf->Ln();
			$pdf->Cell(8,5,'',0,0,'R');
			$pdf->Cell(16,5,'Cashier:',0,0,'L');
			$pdf->Cell(36,5,ucwords(strtolower($t->u_fullname)),0,0,'L');
			$pdf->Cell(86,5,'',0,0,'R');
			$pdf->Cell(18,5,$stqty,'T',0,'R');
			$pdf->Cell(18,5,number_format($stotal,2),'T',0,'R');
			$pdf->Ln(8);

			$total +=$stotal;
			$tqty += $stqty;
			//echo $tqty.'<br />';
		}
		// get all transactions

		$pdf->Ln(4);
		$pdf->SetFont("Arial", "", 10);
		$pdf->Cell(60,5,'Total Discount:',0,0,'R');
		$pdf->Cell(60,5,number_format($totdisc,2),0,0,'L');
		$pdf->Ln();	
		$pdf->Cell(60,5,'Total Sales:',0,0,'R');
		$pdf->Cell(60,5,number_format($total,2),0,0,'L');
		$pdf->Ln();
		$pdf->Cell(60,5,'Items Sold:',0,0,'R');
		$pdf->Cell(60,5,number_format($tqty),0,0,'L');
		$pdf->Ln();
		$pdf->Cell(60,5,'No of Paying Customers:',0,0,'R');
		$pdf->Cell(60,5,count($tr),0,0,'L');
		$pdf->Ln();
		$pdf->Cell(60,5,'No of Transactions',0,0,'R');
		$pdf->Cell(60,5,count($tr),0,0,'L');
		$pdf->SetFont("Arial", "", 9);
		$pdf->Ln(8);
		$pdf->Cell(10,8,'',0,0,'L');
		$pdf->Cell(105,8,'Prepared by:',0,0,'L');
		$pdf->Cell(80,8,'Checked by:',0,0,'L');
		$pdf->Ln(8);
		$pdf->SetFont("Arial", "B", 9);
		$pdf->Cell(10,8,'',0,0,'L');
		$pdf->Cell(80,	8,ucwords($this->session->userdata('load_fullname')),0,0,'C');
		$pdf->Cell(34,8,'',0,0,'C');
		$pdf->Cell(60,8,"",0,0,'C');
		$pdf->Ln(4);
		$pdf->Cell(10,8,'',0,0,'L');
		$pdf->SetFont("Arial", "", 9);
		$pdf->Cell(18,	1,'',0,0,'R');
		$pdf->Cell(50,	1,'______________________________',0,0,'C');
		$pdf->Cell(36,	1,'',0,0,'C');
		$pdf->Cell(80,	1,'______________________________',0,0,'C');
		$pdf->Ln(5);
		$pdf->SetFont("Arial", "B", 7);
		$pdf->Cell(10,8,'',0,0,'L');
		$pdf->Cell(13,	1,'',0,0,'C');
		$pdf->Cell(60,	1,'(Signature over Printed name)',0,0,'C');
		$pdf->Cell(41,	1,'',0,0,'C');
		$pdf->Cell(60,	1,'(Signature over Printed name)',0,0,'C');	

		//$pdf->Output();

		$pdf->Output($_SERVER['DOCUMENT_ROOT'].'/eload/assets/reports/'.$buname.'terminalreport.pdf','F');

		redirect(base_url().'home/cashiermain/terminalreport/');

    }
    

  //   public function terminalreportpdf()
  //   {
  //   	$type = $this->uri->segment(3, 0);
  //   	$d1 = $this->uri->segment(4, 0);
  //   	$d2 = $this->uri->segment(5, 0);

  //   	$buname = preg_replace('/\s+/', '', $this->session->userdata('load_buname'));

		// $this->load->library('pdfcreate');

		// $pdf = new pdfcreate();
		// //$pdf = new FPDF('p','mm','A4');
		// $pdf->AliasNbPages();
		// $pdf->AddPage("P","Letter");

		// if($d1==$d2)
		// {
		// 	$subheadertext = _dateFormat($d1);
		// }
		// else
		// {
		// 	$subheadertext = 'From '._dateFormat($d1).' to '._dateFormat($d2);
		// }

		
		// $pdf->docHeaderStoreTerminalReport($this->session->userdata('load_buname'),$subheadertext);

		// $pdf->subheaderTerminalReport(todays_date());

		// $pdf->setReportType('tr');

		// // $pdf->SetFont("Arial", "", 10);
		// // $pdf->Cell(6,5,'',0,0,'R');
		// // $pdf->Cell(18,5,'TR #',1,0,'C');
		// // $pdf->Cell(88,5,'Item Name',1,0,'C');
		// // $pdf->Cell(16,5,'Qty',1,0,'C');
		// // $pdf->Cell(14,5,'Disc',1,0,'C');
		// // $pdf->Cell(22,5,'SRP',1,0,'C');
		// // $pdf->Cell(22,5,'Total',1,0,'C');

		// $pdf->Ln();

		// $tr = $this->Model_Transaction->getAllTransactionByRangeDetailed($d1,$d2,$type);
		// $total = 0;
		
		// $tqty = 0;
		// $totdisc = 0;

		// foreach ($tr as $t) 
		// {
		// 	$stotal = 0;
		// 	$stqty = 0;
		// 	$pdf->SetFont("Arial", "", 10);			


		// 	$pdf->Cell(6,5,'',0,0,'R');
		// 	$pdf->Cell(18,5,'TR #','TB',0,'R');
		// 	$pdf->Cell(100,5,zeroes($t->st_trnum,6),'TB',0,'L');
		// 	$pdf->Cell(18,5,'Date:','TB',0,'R');
		// 	$pdf->Cell(48,5,_dateFormat($t->st_datetime),'TB',0,'L');
		// 	$pdf->Ln(6);

		// 	$items = $this->Model_Transaction->getAllTransactionItemsBytrID($t->st_id);

		// 	$pdf->SetFont("Arial", "I", 7);	
		// 	$pdf->Cell(8,5,'',00,0,'R');
		// 	$pdf->Cell(110,5,'Item',0,0,'R');
		// 	$pdf->Cell(14,5,'SRP',0,0,'R');
		// 	$pdf->Cell(14,5,'Discount',0,0,'R');
		// 	$pdf->Cell(18,5,'Quantity',0,0,'R');
		// 	$pdf->Cell(18,5,'Subtotal',0,0,'R');
		// 	$pdf->Ln();

		// 	foreach ($items as $i) 
		// 	{
		// 		$sdtotal = 0;
		// 		$sdtotal = $i->si_srp - $i->si_linedisc;
		// 		$pdf->SetFont("Arial", "", 9);	
		// 		$pdf->Cell(8,5,'',0,0,'R');
		// 		$pdf->Cell(110,5,$i->it_name,0,0,'R');
		// 		$pdf->Cell(14,5,$i->si_srp,0,0,'R');
		// 		$pdf->Cell(14,5,$i->si_linedisc,0,0,'R');
		// 		$pdf->Cell(18,5,$i->si_qty,0,0,'R');
		// 		$pdf->Cell(18,5,number_format($sdtotal,2),0,0,'R');
		// 		//echo $i->si_srp.'=>';
		// 		$totdisc += $i->si_linedisc;
		// 		$stotal += $sdtotal;
		// 		$stqty += $i->si_qty;
		// 		if(!empty($i->sld_refnum))
		// 		{
		// 			$pdf->Ln();
		// 			$pdf->SetFont("Arial", "", 8);	
		// 			$pdf->Cell(8,5,'',0,0,'R');
		// 			$pdf->Cell(24,5,'Mobile #',0,0,'R');
		// 			$pdf->Cell(28,5,$i->sld_mobilenum,0,0,'R');
		// 			$pdf->Cell(24,5,'Ref #',0,0,'R');
		// 			$pdf->Cell(28,5,$i->sld_refnum,0,0,'R');
		// 		}
		// 	}
		// 	$pdf->Ln();
		// 	$pdf->Cell(8,5,'',0,0,'R');
		// 	$pdf->Cell(16,5,'Cashier:',0,0,'L');
		// 	$pdf->Cell(36,5,ucwords(strtolower($t->u_fullname)),0,0,'L');
		// 	$pdf->Cell(86,5,'',0,0,'R');
		// 	$pdf->Cell(18,5,$stqty,'T',0,'R');
		// 	$pdf->Cell(18,5,number_format($stotal,2),'T',0,'R');
		// 	$pdf->Ln(8);

		// 	$total +=$stotal;
		// 	$tqty += $stqty;
		// 	//echo $tqty.'<br />';
		// }
		// // get all transactions

		// $pdf->Ln(4);
		// $pdf->SetFont("Arial", "", 10);
		// $pdf->Cell(60,5,'Total Discount:',0,0,'R');
		// $pdf->Cell(60,5,number_format($totdisc,2),0,0,'L');
		// $pdf->Ln();	
		// $pdf->Cell(60,5,'Total Sales:',0,0,'R');
		// $pdf->Cell(60,5,number_format($total,2),0,0,'L');
		// $pdf->Ln();
		// $pdf->Cell(60,5,'Items Sold:',0,0,'R');
		// $pdf->Cell(60,5,number_format($tqty),0,0,'L');
		// $pdf->Ln();
		// $pdf->Cell(60,5,'No of Paying Customers:',0,0,'R');
		// $pdf->Cell(60,5,count($tr),0,0,'L');
		// $pdf->Ln();
		// $pdf->Cell(60,5,'No of Transactions',0,0,'R');
		// $pdf->Cell(60,5,count($tr),0,0,'L');
		// $pdf->SetFont("Arial", "", 9);
		// $pdf->Ln(8);
		// $pdf->Cell(10,8,'',0,0,'L');
		// $pdf->Cell(105,8,'Prepared by:',0,0,'L');
		// $pdf->Cell(80,8,'Checked by:',0,0,'L');
		// $pdf->Ln(8);
		// $pdf->SetFont("Arial", "B", 9);
		// $pdf->Cell(10,8,'',0,0,'L');
		// $pdf->Cell(80,	8,ucwords($this->session->userdata('load_fullname')),0,0,'C');
		// $pdf->Cell(34,8,'',0,0,'C');
		// $pdf->Cell(60,8,"",0,0,'C');
		// $pdf->Ln(4);
		// $pdf->Cell(10,8,'',0,0,'L');
		// $pdf->SetFont("Arial", "", 9);
		// $pdf->Cell(18,	1,'',0,0,'R');
		// $pdf->Cell(50,	1,'______________________________',0,0,'C');
		// $pdf->Cell(36,	1,'',0,0,'C');
		// $pdf->Cell(80,	1,'______________________________',0,0,'C');
		// $pdf->Ln(5);
		// $pdf->SetFont("Arial", "B", 7);
		// $pdf->Cell(10,8,'',0,0,'L');
		// $pdf->Cell(13,	1,'',0,0,'C');
		// $pdf->Cell(60,	1,'(Signature over Printed name)',0,0,'C');
		// $pdf->Cell(41,	1,'',0,0,'C');
		// $pdf->Cell(60,	1,'(Signature over Printed name)',0,0,'C');	

		// //$pdf->Output();

		// $pdf->Output($_SERVER['DOCUMENT_ROOT'].'/eload/assets/reports/'.$buname.'terminalreport.pdf','F');

		// redirect(base_url().'home/cashiermain/terminalreport/');

  //   }



	// public function eodreportpdf()
	// {
	// 	$trnum = $this->uri->segment(3, 0);

	// 	$buname = preg_replace('/\s+/', '', $this->session->userdata('load_buname'));

	// 	//get all transactions based on eod tr number

	// 	$eod = $this->Model_Transaction->getEODTRByNum($trnum);

	// 	$tr = $this->Model_Transaction->getEODSalesTRByID($eod->eod_id);

	// 	//$items = $this->Model_Transaction->getEODItemsByID($trnum);

	// 	$this->load->library('pdfcreate');

	// 	$pdf = new pdfcreate();
	// 	//$pdf = new FPDF('p','mm','A4');
	// 	$pdf->AliasNbPages();
	// 	$pdf->AddPage("P","Letter");

	// 	$pdf->docHeaderStoreSalesReport($this->session->userdata('load_buname'));

	// 	$pdf->subheaderEODSalesReport($trnum,$eod->u_fullname,$eod->eod_datetime);

	// 	$pdf->setReportType('eod');

	// 	//$pdf->displayAllTransactionsEOD($items);

	// 	$pdf->SetFont("Arial", "", 10);
	// 	$pdf->Cell(6,5,'',0,0,'R');
	// 	$pdf->Cell(18,5,'TR #',1,0,'C');
	// 	$pdf->Cell(88,5,'Item Name',1,0,'C');
	// 	$pdf->Cell(16,5,'Qty',1,0,'C');
	// 	$pdf->Cell(14,5,'Disc',1,0,'C');
	// 	$pdf->Cell(22,5,'SRP',1,0,'C');
	// 	$pdf->Cell(22,5,'Total',1,0,'C');

	// 	$pdf->Ln();
	// 	$nrow = 1;
	// 	$totalamt = 0;
	// 	$totqty = 0;
	// 	$totdisc = 0;
	// 	foreach ($tr as $key) 
	// 	{			
	// 		$items = $this->Model_Transaction->getEODItemsByID($key->st_id);
	// 		foreach ($items as $i) 
	// 		{
	// 			$stot = 0;
	// 			$h = 6;
	// 			if(strlen($i->it_name) > 35)
	// 			{
	// 				$nrow = strlen($i->it_name) / 35;
	// 				$nrow = ceil($nrow);
	// 			}
	// 			$h = $h*$nrow;
	// 			$pdf->SetFont("Arial", "", 9);
	// 			$pdf->Cell(6,6,'',0,0,'R');
	// 			$pdf->Cell(18,6,zeroes($key->st_trnum,6),1,0,'C');
	// 			$pdf->Cell(88,6,$i->it_name,1,0,'L');
	// 			//$pdf->MultiCell(70,$h,$i->it_name,1,'C',false);
	// 			$pdf->Cell(16,6,$i->si_qty,1,0,'R');
	// 			$pdf->Cell(14,6,$i->si_linedisc,1,0,'R');
	// 			$pdf->Cell(22,6,number_format($i->si_srp,2),1,0,'R');
	// 			$totdisc += $i->si_linedisc;
	// 			$stot = $i->si_qty * $i->si_srp;
	// 			$totqty += $i->si_qty;
	// 			$totalamt += $stot;
	// 			$pdf->Cell(22,6,number_format($stot,2),1,0,'R');
	// 			$pdf->Ln();
	// 		}
	// 	}
	// 	$pdf->Ln(4);
	// 	$pdf->SetFont("Arial", "", 10);
	// 	$pdf->Cell(60,5,'Total Discount:',0,0,'R');
	// 	$pdf->Cell(60,5,number_format($totdisc,2),0,0,'L');
	// 	$pdf->Ln();	
	// 	$pdf->Cell(60,5,'Total Sales:',0,0,'R');
	// 	$pdf->Cell(60,5,number_format($totalamt,2),0,0,'L');
	// 	$pdf->Ln();
	// 	$pdf->Cell(60,5,'Items Sold:',0,0,'R');
	// 	$pdf->Cell(60,5,number_format($totqty),0,0,'L');
	// 	$pdf->Ln();
	// 	$pdf->Cell(60,5,'No of Paying Customers:',0,0,'R');
	// 	$pdf->Cell(60,5,count($tr),0,0,'L');
	// 	$pdf->Ln();
	// 	$pdf->Cell(60,5,'No of Transactions',0,0,'R');
	// 	$pdf->Cell(60,5,count($tr),0,0,'L');
	// 	$pdf->SetFont("Arial", "", 9);
	// 	$pdf->Ln(8);
	// 	$pdf->Cell(10,8,'',0,0,'L');
	// 	$pdf->Cell(105,8,'Prepared by:',0,0,'L');
	// 	$pdf->Cell(80,8,'Checked by:',0,0,'L');
	// 	$pdf->Ln(8);
	// 	$pdf->SetFont("Arial", "B", 9);
	// 	$pdf->Cell(10,8,'',0,0,'L');
	// 	$pdf->Cell(80,	8,ucwords($eod->u_fullname),0,0,'C');
	// 	$pdf->Cell(34,8,'',0,0,'C');
	// 	$pdf->Cell(60,8,"",0,0,'C');
	// 	$pdf->Ln(4);
	// 	$pdf->Cell(10,8,'',0,0,'L');
	// 	$pdf->SetFont("Arial", "", 9);
	// 	$pdf->Cell(18,	1,'',0,0,'R');
	// 	$pdf->Cell(50,	1,'______________________________',0,0,'C');
	// 	$pdf->Cell(36,	1,'',0,0,'C');
	// 	$pdf->Cell(80,	1,'______________________________',0,0,'C');
	// 	$pdf->Ln(5);
	// 	$pdf->SetFont("Arial", "B", 7);
	// 	$pdf->Cell(10,8,'',0,0,'L');
	// 	$pdf->Cell(13,	1,'',0,0,'C');
	// 	$pdf->Cell(60,	1,'(Signature over Printed name)',0,0,'C');
	// 	$pdf->Cell(41,	1,'',0,0,'C');
	// 	$pdf->Cell(60,	1,'(Signature over Printed name)',0,0,'C');	

	// 	$pdf->Output($_SERVER['DOCUMENT_ROOT'].'/eload/assets/reports/'.$buname.'eod.pdf','F');

	// 	redirect(base_url().'home/cashiermain/endofday/'.$trnum);
	// 	//$pdf -> output ('your_file_pdf.pdf','D');     
	// 	//$pdf->Output();
    // }
	public function eodreportpdfv2()
	{
		$trnum = $this->uri->segment(3, 0);

		$buname = preg_replace('/\s+/', '', $this->session->userdata('load_buname'));

		//get all transactions based on eod tr number

        $totalload = 0;
        $totalothers = 0;

		$eod = $this->model_transaction->getEODTRByNum($trnum);

		$tr = $this->model_transaction->getEODSalesTRByID($eod->eod_id);

		//$items = $this->Model_Transaction->getEODItemsByID($trnum);

		$this->load->library('pdfcreate');

		$pdf = new pdfcreate();
		//$pdf = new FPDF('p','mm','A4');
		$pdf->AliasNbPages();
		$pdf->AddPage("P","Letter");

		$pdf->docHeaderStoreSalesReport($this->session->userdata('load_buname'));

		$pdf->subheaderEODSalesReport($trnum,$eod->u_fullname,$eod->eod_datetime);

		$pdf->setReportType('eod');

		//$pdf->displayAllTransactionsEOD($items);

		$pdf->Ln();
		$nrow = 1;
        $totalsales = 0;
        $totalloadsales = 0;
        $totalotheritemssales = 0;
        $totalothersalesqty = 0;
        $netloadsales = 0;
        $netotheritemssales = 0;
		$totaldiscount = 0;
		foreach ($tr as $key) 
		{			
			$items = $this->model_transaction->getEODItemsByID($key->st_id);
			foreach ($items as $i) 
			{
                $stot = 0;
                if($i->it_item_oum=='load')
                {                    
                    $totalloadsales += $i->si_srp;
                    $netloadsales += $i->si_netprice;
                    $stot = $i->si_srp;
                    
                }
                else 
                {
                    $totalotheritemssales += $i->si_qty * $i->si_srp;
                    $stot = $i->si_qty * $i->si_srp;
                    $totalothersalesqty += $i->si_qty;
                    $netotheritemssales += $i->si_qty * $i->si_netprice;
                    // $totalotheritemssales = 
                    // $qtyload = $i->si_qty;
                    // $totalothers += $i->si_qty;
                }

                //$itemname = html_entity_decode($i->it_name,ENT_QUOTES,'UTF-8');                
                $totaldiscount += $i->si_linedisc;
				$totalsales += $stot;
			}
		}
        
        $left = [];
        $left[] = array("title" => "Total Discount: ","value" => number_format($totaldiscount,2));
        $left[] = array("title" => "Total Sales: ","value" => number_format($totalsales,2));
        $left[] = array("title" => "Load Sales: ","value" => number_format($totalloadsales,2));
        $left[] = array("title" => "Other Items Sales: ","value" => number_format($totalotheritemssales,2));
        $left[] = array("title" => "Other Items Sold Count: ","value" => number_format($totalothersalesqty));
        $left[] = array("title" => "Load Net Sold: ","value" => number_format($netloadsales,2));
        $left[] = array("title" => "Other Item Net Sold: ","value" => number_format($netotheritemssales,2));
        $left[] = array("title" => "No of Paying Customers: ","value" => count($tr));
        $left[] = array("title" => "No of Transactions: ","value" => count($tr));

        //get simcards
        $sbal = $this->model_transaction->getSimCardBalanceByEODID($eod->eod_id);
        $right = [];
        if(count($sbal) > 0)
        {
            $right[] = array("title" => "header","value" => "Sim Card Balance");

            foreach($sbal as $bal)
            {
                $right[] = array("title" => $bal->scard_number,"value" => $bal->sb_balance);
            }
        }
        $h = 0;
        if(count($right) > count($right))
        {
            $h = count($right);
        }
        else 
        {
            $h = count($left);
        }

        //echo $eodid;
        $pdf->SetFont("Arial", "", 10);
        for($x = 1; $x<=$h; $x++)
        {
            if($x <= count($left))
            {
                $pdf->Cell(60,5,$left[$x-1]['title'],0,0,'R');
                $pdf->Cell(30,5,$left[$x-1]['value'],0,0,'L');
            }

            if($x <= count($right))
            {
                if($right[$x-1]['title']=='header')
                {
                    $pdf->Cell(80,5,$right[$x-1]['value'],0,0,'C');
                }
                else 
                {
                    $pdf->Cell(50,5,$right[$x-1]['title'],0,0,'R');
                    $pdf->Cell(30,5,number_format($right[$x-1]['value'],2),0,0,'R');
                }
                
            }
            //echo $left[$x-1]['title'];
            // if($x <= count($left))
            // {
            //     echo $right[$x-1]['title'];
            // }
            $pdf->Ln();	
        }
        $pdf->Ln(4);

		// $pdf->SetFont("Arial", "", 10);
		// $pdf->Cell(60,5,'Total Discount:',0,0,'R');
        // $pdf->Cell(60,5,number_format($totdisc,2),0,0,'L');
		// $pdf->Ln();	
		// $pdf->Cell(60,5,'Total Sales:',0,0,'R');
		// $pdf->Cell(60,5,number_format($totalamt,2),0,0,'L');
		// $pdf->Ln();
		// $pdf->Cell(60,5,'Items Sold:',0,0,'R');
		// $pdf->Cell(60,5,number_format($totalothers),0,0,'L');
        // $pdf->Ln();
		// $pdf->Cell(60,5,'Load Sold:',0,0,'R');
		// $pdf->Cell(60,5,number_format($totalload,2),0,0,'L');
		// $pdf->Ln();
		// $pdf->Cell(60,5,'No of Paying Customers:',0,0,'R');
		// $pdf->Cell(60,5,count($tr),0,0,'L');
		// $pdf->Ln();
		// $pdf->Cell(60,5,'No of Transactions:',0,0,'R');
		// $pdf->Cell(60,5,count($tr),0,0,'L');
		// $pdf->SetFont("Arial", "", 9);
		// $pdf->Ln(8);
		$pdf->Cell(10,8,'',0,0,'L');
		$pdf->Cell(105,8,'Prepared by:',0,0,'L');
		$pdf->Cell(80,8,'Checked by:',0,0,'L');
		$pdf->Ln(8);
		$pdf->SetFont("Arial", "B", 9);
		$pdf->Cell(10,8,'',0,0,'L');
		$pdf->Cell(80,	8,ucwords($eod->u_fullname),0,0,'C');
		$pdf->Cell(34,8,'',0,0,'C');
		$pdf->Cell(60,8,"",0,0,'C');
		$pdf->Ln(4);
		$pdf->Cell(10,8,'',0,0,'L');
		$pdf->SetFont("Arial", "", 9);
		$pdf->Cell(18,	1,'',0,0,'R');
		$pdf->Cell(50,	1,'______________________________',0,0,'C');
		$pdf->Cell(36,	1,'',0,0,'C');
		$pdf->Cell(80,	1,'______________________________',0,0,'C');
		$pdf->Ln(5);
		$pdf->SetFont("Arial", "B", 7);
		$pdf->Cell(10,8,'',0,0,'L');
		$pdf->Cell(13,	1,'',0,0,'C');
		$pdf->Cell(60,	1,'(Signature over Printed name)',0,0,'C');
		$pdf->Cell(41,	1,'',0,0,'C');
		$pdf->Cell(60,	1,'(Signature over Printed name)',0,0,'C');	

		//$pdf->Output($_SERVER['DOCUMENT_ROOT'].'/eload/assets/reports/'.$buname.'eod.pdf','F');

		//redirect(base_url().'home/cashiermain/endofday/'.$trnum);
		//$pdf -> output ('your_file_pdf.pdf','D');     
		$pdf->Output();
	}
    
	public function eodreportpdf()
	{
		$trnum = $this->uri->segment(3, 0);

		$buname = preg_replace('/\s+/', '', $this->session->userdata('load_buname'));

		//get all transactions based on eod tr number

        $totalload = 0;
        $totalothers = 0;

		$eod = $this->Model_Transaction->getEODTRByNum($trnum);

		$tr = $this->Model_Transaction->getEODSalesTRByID($eod->eod_id);

		//$items = $this->Model_Transaction->getEODItemsByID($trnum);

		$this->load->library('pdfcreate');

		$pdf = new pdfcreate();
		//$pdf = new FPDF('p','mm','A4');
		$pdf->AliasNbPages();
		$pdf->AddPage("P","Letter");

		$pdf->docHeaderStoreSalesReport($this->session->userdata('load_buname'));

		$pdf->subheaderEODSalesReport($trnum,$eod->u_fullname,$eod->eod_datetime);

		$pdf->setReportType('eod');

		//$pdf->displayAllTransactionsEOD($items);

		$pdf->SetFont("Arial", "", 10);
		$pdf->Cell(6,5,'',0,0,'R');
		$pdf->Cell(18,5,'TR #',1,0,'C');
		$pdf->Cell(88,5,'Item Name',1,0,'C');
		$pdf->Cell(18,5,'Qty / Load',1,0,'C');
		$pdf->Cell(14,5,'Disc',1,0,'C');
		$pdf->Cell(20,5,'SRP',1,0,'C');
		$pdf->Cell(22,5,'Total',1,0,'C');

		$pdf->Ln();
		$nrow = 1;
		$totalamt = 0;
		$totqty = 0;
		$totdisc = 0;
		foreach ($tr as $key) 
		{			
			$items = $this->Model_Transaction->getEODItemsByID($key->st_id);
			foreach ($items as $i) 
			{
                $qtyload = 0;
                if($i->it_item_oum=='load')
                {
                    $qtyload = $i->si_netprice;
                    $totalload += $i->si_netprice;
                }
                else 
                {
                    $qtyload = $i->si_qty;
                    $totalothers += $i->si_qty;
                }

                //$itemname = html_entity_decode($i->it_name,ENT_QUOTES,'UTF-8');
                $itemname = iconv('UTF-8', 'windows-1252', $i->it_name);
				$stot = 0;
				$h = 6;
				if(strlen($i->it_name) > 35)
				{
					$nrow = strlen($i->it_name) / 35;
					$nrow = ceil($nrow);
				}
				$h = $h*$nrow;
				$pdf->SetFont("Arial", "", 9);
				$pdf->Cell(6,6,'',0,0,'R');
				$pdf->Cell(18,6,zeroes($key->st_trnum,6),1,0,'C');
				$pdf->Cell(88,6,$itemname,1,0,'L');
				//$pdf->MultiCell(70,$h,$i->it_name,1,'C',false);
				$pdf->Cell(18,6,$qtyload,1,0,'R');
				$pdf->Cell(14,6,$i->si_linedisc,1,0,'R');
				$pdf->Cell(20,6,number_format($i->si_srp,2),1,0,'R');
				$totdisc += $i->si_linedisc;
				$stot = $i->si_qty * $i->si_srp;
				$totqty += $i->si_qty;
				$totalamt += $stot;
				$pdf->Cell(22,6,number_format($stot,2),1,0,'R');
				$pdf->Ln();
			}
		}
        $pdf->Ln(4);
        
        $left = [];
        $left[] = array("title" => "Total Discount: ","value" => number_format($totdisc,2));
        $left[] = array("title" => "Total Sales: ","value" => number_format($totalamt,2));
        $left[] = array("title" => "Items Sold: ","value" => number_format($totalothers));
        $left[] = array("title" => "Load Sold: ","value" => number_format($totalload,2));
        $left[] = array("title" => "No of Paying Customers: ","value" => count($tr));
        $left[] = array("title" => "No of Transactions: ","value" => count($tr));

        //get simcards
        $sbal = $this->Model_Transaction->getSimCardBalanceByEODID($eod->eod_id);
        $right = [];
        if(count($sbal) > 0)
        {
            $right[] = array("title" => "header","value" => "Sim Card Balance");

            foreach($sbal as $bal)
            {
                $right[] = array("title" => $bal->scard_number,"value" => $bal->sb_balance);
            }
        }
        $h = 0;
        if(count($right) > count($right))
        {
            $h = count($right);
        }
        else 
        {
            $h = count($left);
        }

        //echo $eodid;
        $pdf->SetFont("Arial", "", 10);
        for($x = 1; $x<=$h; $x++)
        {
            if($x <= count($left))
            {
                $pdf->Cell(60,5,$left[$x-1]['title'],0,0,'R');
                $pdf->Cell(30,5,$left[$x-1]['value'],0,0,'L');
            }

            if($x <= count($right))
            {
                if($right[$x-1]['title']=='header')
                {
                    $pdf->Cell(80,5,$right[$x-1]['value'],0,0,'C');
                }
                else 
                {
                    $pdf->Cell(50,5,$right[$x-1]['title'],0,0,'R');
                    $pdf->Cell(30,5,number_format($right[$x-1]['value'],2),0,0,'R');
                }
                
            }
            //echo $left[$x-1]['title'];
            // if($x <= count($left))
            // {
            //     echo $right[$x-1]['title'];
            // }
            $pdf->Ln();	
        }
        $pdf->Ln(4);

		// $pdf->SetFont("Arial", "", 10);
		// $pdf->Cell(60,5,'Total Discount:',0,0,'R');
        // $pdf->Cell(60,5,number_format($totdisc,2),0,0,'L');
		// $pdf->Ln();	
		// $pdf->Cell(60,5,'Total Sales:',0,0,'R');
		// $pdf->Cell(60,5,number_format($totalamt,2),0,0,'L');
		// $pdf->Ln();
		// $pdf->Cell(60,5,'Items Sold:',0,0,'R');
		// $pdf->Cell(60,5,number_format($totalothers),0,0,'L');
        // $pdf->Ln();
		// $pdf->Cell(60,5,'Load Sold:',0,0,'R');
		// $pdf->Cell(60,5,number_format($totalload,2),0,0,'L');
		// $pdf->Ln();
		// $pdf->Cell(60,5,'No of Paying Customers:',0,0,'R');
		// $pdf->Cell(60,5,count($tr),0,0,'L');
		// $pdf->Ln();
		// $pdf->Cell(60,5,'No of Transactions:',0,0,'R');
		// $pdf->Cell(60,5,count($tr),0,0,'L');
		// $pdf->SetFont("Arial", "", 9);
		// $pdf->Ln(8);
		$pdf->Cell(10,8,'',0,0,'L');
		$pdf->Cell(105,8,'Prepared by:',0,0,'L');
		$pdf->Cell(80,8,'Checked by:',0,0,'L');
		$pdf->Ln(8);
		$pdf->SetFont("Arial", "B", 9);
		$pdf->Cell(10,8,'',0,0,'L');
		$pdf->Cell(80,	8,ucwords($eod->u_fullname),0,0,'C');
		$pdf->Cell(34,8,'',0,0,'C');
		$pdf->Cell(60,8,"",0,0,'C');
		$pdf->Ln(4);
		$pdf->Cell(10,8,'',0,0,'L');
		$pdf->SetFont("Arial", "", 9);
		$pdf->Cell(18,	1,'',0,0,'R');
		$pdf->Cell(50,	1,'______________________________',0,0,'C');
		$pdf->Cell(36,	1,'',0,0,'C');
		$pdf->Cell(80,	1,'______________________________',0,0,'C');
		$pdf->Ln(5);
		$pdf->SetFont("Arial", "B", 7);
		$pdf->Cell(10,8,'',0,0,'L');
		$pdf->Cell(13,	1,'',0,0,'C');
		$pdf->Cell(60,	1,'(Signature over Printed name)',0,0,'C');
		$pdf->Cell(41,	1,'',0,0,'C');
		$pdf->Cell(60,	1,'(Signature over Printed name)',0,0,'C');	

		$pdf->Output($_SERVER['DOCUMENT_ROOT'].'/eload/assets/reports/'.$buname.'eod.pdf','F');

		//redirect(base_url().'home/cashiermain/endofday/'.$trnum);
		//$pdf -> output ('your_file_pdf.pdf','D');     
		$pdf->Output();
	}

	public function generateTerminalReport()
	{
		$this->load->view("page/terminalreportrange");
	}

	// public function endofday()
	// {
	// 	$response['st'] = false;

	// 	// check sales
	// 	//countRowTwoArg($table,$var1,$var2,$field1,$field2)
	// 	if($this->Model_Functions->countRowTwoArg('sales_transaction','',$this->session->userdata('load_buid'),'st_eod_id','st_bu') > 0)
	// 	{
	// 		// process eod

	// 		$result = $this->Model_Transaction->processEOD();

	// 		if(is_array($result))
	// 		{
	// 			$response['trnum'] = $result[1];
	// 			$response['st'] = true;
	// 		}
	// 		else 
	// 		{
	// 			$response['msg'] = 'Error processing End of Day.';
	// 		}			
	// 		// $response['cnt'] = $this->Model_Functions->countRowThreeArgDate('sales_transaction','','CURDATE()',$this->session->userdata('load_buid'),'st_eod_id','st_datetime','st_bu');
	// 	}
	// 	else 
	// 	{
	// 		$response['msg'] = 'There were no transactions to process.';
	// 	}
	// 	echo json_encode($response);
    // }
    
	public function endofday()
	{
		$response['st'] = false;

		// check sales
		//countRowTwoArg($table,$var1,$var2,$field1,$field2)
		if($this->Model_Functions->countRowTwoArg('sales_transaction','',$this->session->userdata('load_buid'),'st_eod_id','st_bu') > 0)
		{
			// process eod

			$result = $this->Model_Transaction->processEOD();

			if(is_array($result))
			{
				$response['trnum'] = $result[1];
				$response['st'] = true;
			}
			else 
			{
				$response['msg'] = 'Error processing End of Day.';
			}			
			// $response['cnt'] = $this->Model_Functions->countRowThreeArgDate('sales_transaction','','CURDATE()',$this->session->userdata('load_buid'),'st_eod_id','st_datetime','st_bu');
		}
		else 
		{
			$response['msg'] = 'There were no transactions to process.';
		}
		echo json_encode($response);
	}

	public function displayPDF()
	{
		$trnum = $this->uri->segment(3, 0);

		$data['trnum'] = $trnum;
		$data['trtype'] = 'eod';

		$this->load->view('page/displayPDF',$data);
	}

	public function displayPDFTerminalReport()
	{
		$this->load->view('page/displayPDFTerminal');
	}

	public function terminalreportvalidation()
	{
		$response['st'] = false;
		$d1 = $_POST['d1'];
		$d2 = $_POST['d2'];

		$trans = $_POST['trans'];

		//echo count($this->Model_Transaction->getAllTransactionByRange($d1,$d2,$trans));

		//get number of transactions

		if(count($this->Model_Transaction->getAllTransactionByRange($d1,$d2,$trans))>0)
		{
			$response['st'] = true;
		}
		else 
		{
			$response['msg'] = 'Empty Result.';
		}

		echo json_encode($response);
	}

	public function eodTable()
	{
		$this->load->view('page/eodreport');
	}

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

//986197 - 200
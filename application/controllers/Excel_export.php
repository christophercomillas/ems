<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Excel_export extends CI_Controller {
 
    public function __construct()
    {
        parent::__construct();
        // $this->load->model('model_denomination');
        $this->load->model('Model_Transaction');
    }

    function index()
    {

    }

    public function allreceived()
    {
        $filename = 'ems_received';
        $this->load->library("excel");
        $object = new PHPExcel();

        $object->setActiveSheetIndex(0);

        $recs = $this->Model_Transaction->getReceivedList();

        $period = "";

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
                    'item_id'       =>  $i->it_id,
                    'item_name'     =>  $i->it_name,
                    'items_srp'     =>  $i->rei_srp,
                    'item_qty'      =>  $i->rei_qty,
                    'item_ref'      =>  $ref,
                    'item_mobile'   =>  $mobilenum
                );
            }

            $arr_d[] =  array(
                'rec_id'        =>  $r->rtr_id,
                'rec_num'       =>  $r->rtr_recnum,
                'rec_datetime'  =>  $r->rtr_datetime,
                'rec_si'        =>  $r->rtr_si,
                'rec_po'        =>  $r->rtr_po,
                'rec_ref'       =>  $r->rtr_ref,
                'rec_items'     =>  $arr_items
            );
        }

        if(count($arr_d)>1)
        {
            $period = _dateFormat($arr_d[0]['rec_datetime']).' to '._dateFormat($arr_d[count($arr_d)-1]['rec_datetime']);
        }
        elseif(count($arr_d)==1) 
        {
            $period = _dateFormat($arr_d[0]['rec_datetime']);
        }

        $object->getActiveSheet()->getColumnDimension('A')->setWidth(20);   
        $object->getActiveSheet()->getColumnDimension('B')->setWidth(20);    
        $object->getActiveSheet()->getColumnDimension('C')->setWidth(20);   
        $object->getActiveSheet()->getColumnDimension('D')->setWidth(20);   
        $object->getActiveSheet()->getColumnDimension('E')->setWidth(20);   
        $object->getActiveSheet()->getColumnDimension('F')->setWidth(20);   
        $object->getActiveSheet()->getColumnDimension('G')->setWidth(20);  

        $object->getActiveSheet()->mergeCells('A1:G1')->getStyle("A1:G1")->getFont()->setBold( true );
        $object->getActiveSheet()->getStyle('A1:G1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $object->getActiveSheet()->mergeCells('A2:G2')->getStyle("A2:G2")->getFont()->setBold( true );
        $object->getActiveSheet()->getStyle('A2:G2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $object->getActiveSheet()->mergeCells('A3:G3')->getStyle("A3:G3")->getFont()->setBold( true );
        $object->getActiveSheet()->getStyle('A3:G3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $object->getActiveSheet()->mergeCells('A5:G5')->getStyle("A4:G4")->getFont()->setBold( true );
        $object->getActiveSheet()->getStyle('A5:G5')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);


        $object->setActiveSheetIndex(0)
            ->setCellValue('A1','ALTURAS GROUP OF COMPANIES')
            ->setCellValue('A2','Eload Monitoring System Received List')
            ->setCellValue('A3','From '.$period)
            ->setCellValue('A5','BUSINESS UNIT: '.$this->session->userdata('aload_buname'));


        $excel_row = 7;

        foreach ($arr_d as $arr) 
        {
            $object->getActiveSheet()->setCellValueByColumnAndRow(0, $excel_row,'Receiving #');
            $object->getActiveSheet()->getStyle('A'.$excel_row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
            $object->getActiveSheet()->setCellValueByColumnAndRow(1, $excel_row,$arr['rec_num']);
            $object->getActiveSheet()->getStyle('B'.$excel_row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
            $excel_row++;
            $object->getActiveSheet()->setCellValueByColumnAndRow(0, $excel_row,'Date:');
            $object->getActiveSheet()->getStyle('A'.$excel_row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);            
            $object->getActiveSheet()->setCellValueByColumnAndRow(1, $excel_row,_dateFormat($arr['rec_datetime']));
            $object->getActiveSheet()->getStyle('B'.$excel_row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);    
            $excel_row++;
            $object->getActiveSheet()->mergeCells('B'.$excel_row.':C'.$excel_row)->getStyle('B'.$excel_row.':C'.$excel_row)->getFont()->setBold( true );
            $object->getActiveSheet()->setCellValueByColumnAndRow(1, $excel_row, "Item Description");
            $object->getActiveSheet()->getStyle('B'.$excel_row.':C'.$excel_row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $object->getActiveSheet()->getStyle('D'.$excel_row)->getFont()->setBold( true );
            $object->getActiveSheet()->setCellValueByColumnAndRow(3, $excel_row,"Quantity/Load");
            $object->getActiveSheet()->getStyle('B'.$excel_row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);  
            $excel_row++;
            foreach ($arr['rec_items'] as $i => $ivalue)
            {
                $object->getActiveSheet()->mergeCells('B'.$excel_row.':C'.$excel_row);
                $object->getActiveSheet()->setCellValueByColumnAndRow(1, $excel_row,$ivalue['item_name']);
                $object->getActiveSheet()->getStyle('B'.$excel_row.':C'.$excel_row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
                $object->getActiveSheet()->setCellValueByColumnAndRow(3, $excel_row,$ivalue['item_qty']);
                $object->getActiveSheet()->getStyle('D'.$excel_row)->getNumberFormat()->setFormatCode('#,##0.00');
                $object->getActiveSheet()->getStyle('D'.$excel_row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);                
                $excel_row++;
            }
            $excel_row++;
        }


        $object_writer = PHPExcel_IOFactory::createWriter($object, 'Excel5');
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="'.$filename.'.xls"');
        $object_writer->save('php://output');
    }

    public function reportAccountingByRange()
    {
        $sdate = $this->uri->segment(3, 0);
        $edate = $this->uri->segment(4, 0);

        $start = strtotime($sdate);
        $end = strtotime($edate);

        $days_between = ceil(abs($end - $start) / 86400);

        $filename = "";
        if($sdate==$edate)
        {
            $filename = 'emsreport'.$sdate;
        }
        else 
        {
            $filename = 'emsreport'.$sdate.'to'.$edate;
        }  
        
        $this->load->library("excel");
        $object = new PHPExcel();

        $object->setActiveSheetIndex(0);

        $items = $this->Model_Transaction->getTransactionByRange($sdate,$edate,'LOAD');

        //get load received

        $totrec = $this->Model_Transaction->getTotalLoadReceived($sdate);

        $totsales = $this->Model_Transaction->getLoadTotalSales($sdate);
        //$totsales 

        $balance = $totrec - $totsales;

        //check if receiving
        $object->getActiveSheet()->getColumnDimension('A')->setWidth(20);
        $object->getActiveSheet()->getColumnDimension('B')->setWidth(20);
        $object->getActiveSheet()->getColumnDimension('C')->setWidth(20);
        $object->getActiveSheet()->getColumnDimension('D')->setWidth(20);
        $object->getActiveSheet()->getColumnDimension('E')->setWidth(20);
        $object->getActiveSheet()->getColumnDimension('F')->setWidth(20);
        $object->getActiveSheet()->getColumnDimension('G')->setWidth(20);

        $table_columns = array("DATE", "LOAD AMOUNT", "MOBILE NO.", "TRANSACTION NO.", "LOAD BALANCE","COST","INCOME");

        $column = 0;

        foreach($table_columns as $field)
        {
            $object->getActiveSheet()->setCellValueByColumnAndRow($column, 1, $field);
            $column++;
        }
        $object->getActiveSheet()->getStyle("A1:G1")->getFont()->setBold( true );
        $object->getActiveSheet()->freezePane('A2');

        //$employee_data = $this->excel_export_model->fetch_data();

        $excel_row = 2;

        $nettotal = 0;
        $totloadamt = 0;
        $totincome = 0;

        foreach($items as $item)
        {

            $mobnum = "";
            $refnum = "";

            $balance -= $item->si_netprice;
            $sub = 0;
            $nettotal += $item->si_netprice;
            $totloadamt += $item->si_srp;                
            if(floatval($item->si_netprice)==floatval($item->si_srp))
            {
                $sub = 0;
            }
            else if(floatval($item->si_netprice)==0)
            {
                $sub = 0;
            }
            else  
            {
                $sub = floatval($item->si_srp) - floatval($item->si_netprice);
            } 

            $totincome += $sub;

            //get load details by sales id

            $ldet = $this->Model_Transaction->getLoadDetailsBySalesID($item->si_id);

            if(count($ldet)>0)
            {
                foreach ($ldet as $l) 
                {
                    $mobnum = $l->sld_mobilenum;
                    $refnum = $l->sld_refnum;
                }
            }

            $object->getActiveSheet()->setCellValueByColumnAndRow(0, $excel_row, $item->datesold);
            $object->getActiveSheet()->getStyle('B'.$excel_row)->getNumberFormat()->setFormatCode('#,##0.00');
            $object->getActiveSheet()->setCellValueByColumnAndRow(1, $excel_row, $item->si_srp);
            $object->getActiveSheet()->getStyle('C'.$excel_row)->getNumberFormat()->setFormatCode('0');
            $object->getActiveSheet()->setCellValueByColumnAndRow(2, $excel_row, $mobnum);
            $object->getActiveSheet()->getStyle('D'.$excel_row)->getNumberFormat()->setFormatCode('0');
            $object->getActiveSheet()->getStyle('D'.$excel_row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
            $object->getActiveSheet()->setCellValueByColumnAndRow(3, $excel_row, $refnum);
            $object->getActiveSheet()->setCellValueByColumnAndRow(4, $excel_row, $balance);
            $object->getActiveSheet()->getStyle('E'.$excel_row)->getNumberFormat()->setFormatCode('#,##0.00');
            $object->getActiveSheet()->setCellValueByColumnAndRow(5, $excel_row, $item->si_netprice);
            $object->getActiveSheet()->getStyle('F'.$excel_row)->getNumberFormat()->setFormatCode('#,##0.00');
            $object->getActiveSheet()->setCellValueByColumnAndRow(6, $excel_row, $sub);
            $excel_row++;

        }

        $object->getActiveSheet()->setCellValueByColumnAndRow(0, $excel_row, 'TOTAL');
        $object->getActiveSheet()->getStyle('B'.$excel_row)->getNumberFormat()->setFormatCode('#,##0.00');
        $object->getActiveSheet()->setCellValueByColumnAndRow(1, $excel_row, $totloadamt);
        // $object->getActiveSheet()->getStyle('F'.$excel_row)->getNumberFormat()->setFormatCode('#,##0.00');
        // $object->getActiveSheet()->setCellValueByColumnAndRow(4, $excel_row, $balance);
        $object->getActiveSheet()->getStyle('F'.$excel_row)->getNumberFormat()->setFormatCode('#,##0.00');            
        $object->getActiveSheet()->setCellValueByColumnAndRow(5, $excel_row, $nettotal);
        $object->getActiveSheet()->getStyle('G'.$excel_row)->getNumberFormat()->setFormatCode('#,##0.00');
            $object->getActiveSheet()->setCellValueByColumnAndRow(6, $excel_row, $totincome);
        $object->getActiveSheet()
        ->getStyle('A'.$excel_row.':G'.$excel_row)
        ->applyFromArray(
            array(
                'fill' => array(
                    'type' => PHPExcel_Style_Fill::FILL_SOLID,
                    'color' => array('rgb' => '54c0f9')
                )
            )
        );

        $object->getActiveSheet()->setTitle("LOAD");
        $otitems = $this->Model_Transaction->getTransactionByRange($sdate,$edate,'');
        if(count($otitems) > 0)
        {
            $object->createSheet(1);
            $object->setActiveSheetIndex(1);
            $object->getActiveSheet()->setTitle("OTHER ITEMS");

            //$otitems = $this->Model_Transaction->getTransactionPerDay($date,"");

            $object->getActiveSheet()->getColumnDimension('A')->setWidth(20);
            $object->getActiveSheet()->getColumnDimension('B')->setWidth(40);
            $object->getActiveSheet()->getColumnDimension('C')->setWidth(20);
            $object->getActiveSheet()->getColumnDimension('D')->setWidth(20);
            $object->getActiveSheet()->getColumnDimension('E')->setWidth(20);
            $object->getActiveSheet()->getColumnDimension('F')->setWidth(20);
            $object->getActiveSheet()->getColumnDimension('G')->setWidth(20);
            $object->getActiveSheet()->getColumnDimension('H')->setWidth(20);

            $table_columns = array("DATE", "ITEM NAME","QTY","SRP", "NET PRICE","TOTAL SRP","TOTAL NET","INCOME");

            $column = 0;

            foreach($table_columns as $field)
            {
                $object->getActiveSheet()->setCellValueByColumnAndRow($column, 1, $field);
                $column++;
            }

            $object->getActiveSheet()->getStyle("A1:H1")->getFont()->setBold( true );
            $object->getActiveSheet()->freezePane('A2');

            $excel_row = 2;

            $ototsrp = 0;
            $ototnet = 0;
            $ototinc = 0;

            foreach($otitems as $otitem)
            {
                $sub = 0;

                $totsrp = floatval($otitem->si_srp) * floatval($otitem->si_qty);

                $totnetprice = floatval($otitem->si_netprice) * floatval($otitem->si_qty);

                $sub = floatval($totsrp) - floatval($totnetprice);

                $ototsrp += $totsrp;
                $ototnet += $totnetprice;
                $ototinc += $sub;

                $object->getActiveSheet()->setCellValueByColumnAndRow(0, $excel_row, $otitem->datesold);
                $object->getActiveSheet()->setCellValueByColumnAndRow(1, $excel_row, $otitem->it_name);

                $object->getActiveSheet()->getStyle('C'.$excel_row)->getNumberFormat()->setFormatCode('0');
                $object->getActiveSheet()->setCellValueByColumnAndRow(2, $excel_row, $otitem->si_qty);

                $object->getActiveSheet()->getStyle('D'.$excel_row)->getNumberFormat()->setFormatCode('#,##0.00');
                $object->getActiveSheet()->setCellValueByColumnAndRow(3, $excel_row, $otitem->si_srp);

                $object->getActiveSheet()->getStyle('E'.$excel_row)->getNumberFormat()->setFormatCode('#,##0.00');
                $object->getActiveSheet()->setCellValueByColumnAndRow(4, $excel_row, $otitem->si_netprice);

                $object->getActiveSheet()->getStyle('F'.$excel_row)->getNumberFormat()->setFormatCode('#,##0.00');
                $object->getActiveSheet()->setCellValueByColumnAndRow(5, $excel_row, $totsrp);

                $object->getActiveSheet()->getStyle('G'.$excel_row)->getNumberFormat()->setFormatCode('#,##0.00');
                $object->getActiveSheet()->setCellValueByColumnAndRow(6, $excel_row, $totnetprice);

                $object->getActiveSheet()->getStyle('H'.$excel_row)->getNumberFormat()->setFormatCode('#,##0.00');
                $object->getActiveSheet()->setCellValueByColumnAndRow(7, $excel_row, $sub);
                $excel_row++;
            }
            $object->getActiveSheet()->setCellValueByColumnAndRow(0, $excel_row, 'TOTAL');
            $object->getActiveSheet()->getStyle('F'.$excel_row)->getNumberFormat()->setFormatCode('#,##0.00');
            $object->getActiveSheet()->setCellValueByColumnAndRow(5, $excel_row, $ototsrp);
            $object->getActiveSheet()->getStyle('G'.$excel_row)->getNumberFormat()->setFormatCode('#,##0.00');            
            $object->getActiveSheet()->setCellValueByColumnAndRow(6, $excel_row, $ototnet);
            $object->getActiveSheet()->getStyle('H'.$excel_row)->getNumberFormat()->setFormatCode('#,##0.00');
                $object->getActiveSheet()->setCellValueByColumnAndRow(7, $excel_row, $ototinc);
            $object->getActiveSheet()
            ->getStyle('A'.$excel_row.':H'.$excel_row)
            ->applyFromArray(
                array(
                    'fill' => array(
                        'type' => PHPExcel_Style_Fill::FILL_SOLID,
                        'color' => array('rgb' => '54c0f9')
                    )
                )
            );            
        }

        $object->createSheet(2);
        $object->setActiveSheetIndex(2);
        $object->getActiveSheet()->setTitle("TOTAL PER DAY");
        //getTransactionByRange

        $object->getActiveSheet()->getColumnDimension('A')->setWidth(20);
        $object->getActiveSheet()->getColumnDimension('B')->setWidth(20);
        $object->getActiveSheet()->getColumnDimension('C')->setWidth(20);
        $object->getActiveSheet()->getColumnDimension('D')->setWidth(20);
        $object->getActiveSheet()->getColumnDimension('E')->setWidth(26);
        $object->getActiveSheet()->getColumnDimension('F')->setWidth(26);
        $object->getActiveSheet()->getColumnDimension('G')->setWidth(26);
        $object->getActiveSheet()->getColumnDimension('H')->setWidth(26);

        $table_columns = array(
            "DATE",
            "LOAD SRP",
            "LOAD NET",
            "LOAD INCOME",
            "OTHER ITEMS SRP",
            "OTHER ITEMS NET",
            "OTHER ITEMS INCOME",
            "TOTAL SRP"
        );

        $column = 0;

        foreach($table_columns as $field)
        {
            $object->getActiveSheet()->setCellValueByColumnAndRow($column, 1, $field);
            $column++;
        }
        $object->getActiveSheet()->getStyle("A1:H1")->getFont()->setBold( true );
        $object->getActiveSheet()->freezePane('A2');
        $dates = $this->Model_Transaction->getDateRange($sdate,$edate);

        $excel_row = 2;

        $pdaytotsrp = 0;
        
        foreach($dates as $date)
        {
            $pdaysubsrp = 0;
            $pdayloadnet = 0;
            $pdayloadsrp = 0;
            $pdayloadincome = 0;

            $pdayothernet = 0;
            $pdayothersrp = 0;
            $pdayotherincome = 0;

            //get total per days
            $dal = $this->Model_Transaction->getTotalPerDateLoad($date->dates);
            $dao = $this->Model_Transaction->getTotalPerDateOtherItems($date->dates);

            foreach($dal as $d)
            {
                $pdayloadnet = $d->pdaynet;
                $pdayloadsrp = $d->pdaysrp;
            }

            foreach($dao as $do)
            {
                $osubnet = 0;
                $osubsrp = 0;

                $osubnet = $do->si_qty * $do->si_netprice;
                $pdayothernet += $osubnet;

                $osubsrp = $do->si_qty * $do->si_srp;
                $pdayothersrp += $osubsrp;
            }

            $pdaysubsrp += $pdayothersrp;
            $pdaysubsrp += $pdayloadsrp;

            $pdaytotsrp += $pdaysubsrp;

            $pdayotherincome = $pdayothersrp - $pdayothernet;

            $pdayloadincome = $pdayloadsrp - $pdayloadnet;

            $object->getActiveSheet()->setCellValueByColumnAndRow(0, $excel_row, $date->dates);
            $object->getActiveSheet()->getStyle('B'.$excel_row)->getNumberFormat()->setFormatCode('#,##0.00');
            $object->getActiveSheet()->setCellValueByColumnAndRow(1, $excel_row, $pdayloadsrp);
            $object->getActiveSheet()->getStyle('C'.$excel_row)->getNumberFormat()->setFormatCode('#,##0.00');
            $object->getActiveSheet()->setCellValueByColumnAndRow(2, $excel_row, $pdayloadnet);
            $object->getActiveSheet()->getStyle('D'.$excel_row)->getNumberFormat()->setFormatCode('#,##0.00');
            $object->getActiveSheet()->setCellValueByColumnAndRow(3, $excel_row, $pdayloadincome);
            $object->getActiveSheet()->getStyle('E'.$excel_row)->getNumberFormat()->setFormatCode('#,##0.00');
            $object->getActiveSheet()->setCellValueByColumnAndRow(4, $excel_row, $pdayothersrp);
            $object->getActiveSheet()->getStyle('F'.$excel_row)->getNumberFormat()->setFormatCode('#,##0.00');
            $object->getActiveSheet()->setCellValueByColumnAndRow(5, $excel_row, $pdayothernet);
            $object->getActiveSheet()->getStyle('G'.$excel_row)->getNumberFormat()->setFormatCode('#,##0.00');
            $object->getActiveSheet()->setCellValueByColumnAndRow(6, $excel_row, $pdayotherincome);
            $object->getActiveSheet()->getStyle('H'.$excel_row)->getNumberFormat()->setFormatCode('#,##0.00');
            $object->getActiveSheet()->setCellValueByColumnAndRow(7, $excel_row, $pdaysubsrp);
            $excel_row++;
        }

        $object->getActiveSheet()->setCellValueByColumnAndRow(0, $excel_row, 'TOTAL');
        $object->getActiveSheet()->getStyle('H'.$excel_row)->getNumberFormat()->setFormatCode('#,##0.00');
            $object->getActiveSheet()->setCellValueByColumnAndRow(7, $excel_row, $pdaytotsrp);
        $object->getActiveSheet()
        ->getStyle('A'.$excel_row.':H'.$excel_row)
        ->applyFromArray(
            array(
                'fill' => array(
                    'type' => PHPExcel_Style_Fill::FILL_SOLID,
                    'color' => array('rgb' => '54c0f9')
                )
            )
        );    

        $object->setActiveSheetIndex(0);

        $object_writer = PHPExcel_IOFactory::createWriter($object, 'Excel5');
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="'.$filename.'.xls"');
        $object_writer->save('php://output');

        $response['st'] = true;
        echo json_encode($response);
    }


    public function reportAccounting()
    {
        $date = $this->uri->segment(3, 0);
        $type = $this->uri->segment(4, 0);
        
        //$date = $this->input->post('date');

        //$fordate = _dateFormatoSql($date);

        $itemoum = "";
        if($type=='1')
        {
            $filename = 'ems_loadsales_'.$date;
            $itemoum = 'load';
        }
        else 
        {
             $filename = 'ems_sales_'.$date;
        }      

        $this->load->library("excel");
        $object = new PHPExcel();

        $object->setActiveSheetIndex(0);

        if($type=='1')
        {
            $items = $this->Model_Transaction->getTransactionPerDayLoad($date,$itemoum);

            //get load received

            $totrec = $this->Model_Transaction->getTotalLoadReceived($date);

            $totrec += $this->Model_Transaction->eloadBegBalance();

            $totsales = $this->Model_Transaction->getLoadTotalSales($date);
            //$totsales 

            $balance = $totrec - $totsales;
            //echo 'Total Received = '.$totrec.'<br />';
            //echo 'Total Sales = '.$totsales.'<br />';
            //exit();

            //check if receiving
            $object->getActiveSheet()->getColumnDimension('A')->setWidth(20);
            $object->getActiveSheet()->getColumnDimension('B')->setWidth(30);
            $object->getActiveSheet()->getColumnDimension('C')->setWidth(20);
            $object->getActiveSheet()->getColumnDimension('D')->setWidth(20);
            $object->getActiveSheet()->getColumnDimension('E')->setWidth(20);
            $object->getActiveSheet()->getColumnDimension('F')->setWidth(20);
            $object->getActiveSheet()->getColumnDimension('G')->setWidth(20);
            $object->getActiveSheet()->getColumnDimension('H')->setWidth(20);

            $table_columns = array("DATE", "ITEM NAME", "LOAD AMOUNT", "MOBILE NO.", "TRANSACTION NO.", "LOAD BALANCE","COST","INCOME");

            $column = 0;

            foreach($table_columns as $field)
            {
                $object->getActiveSheet()->setCellValueByColumnAndRow($column, 1, $field);
                $column++;
            }
            $object->getActiveSheet()->getStyle("A1:H1")->getFont()->setBold( true );
            $object->getActiveSheet()->getStyle("A1:H1")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $object->getActiveSheet()->freezePane('A2');

            //$employee_data = $this->excel_export_model->fetch_data();

            $excel_row = 2;

            $nettotal = 0;
            $totloadamt = 0;
            $totincome = 0;

            foreach($items as $item)
            {
                $mobnum = "";
                $refnum = "";

                $balance -= $item->si_netprice;
                $sub = 0;
                $nettotal += $item->si_netprice;
                $totloadamt += $item->si_srp;                
                if(floatval($item->si_netprice)==floatval($item->si_srp))
                {
                    $sub = 0;
                }
                else if(floatval($item->si_netprice)==0)
                {
                    $sub = 0;
                }
                else  
                {
                    $sub = floatval($item->si_srp) - floatval($item->si_netprice);
                } 

                $totincome += $sub;

                //get load details by sales id

                $ldet = $this->Model_Transaction->getLoadDetailsBySalesID($item->si_id);

                if(count($ldet)>0)
                {
                    foreach ($ldet as $l) 
                    {
                        $mobnum = $l->sld_mobilenum;
                        $refnum = $l->sld_refnum;
                    }
                }

                $object->getActiveSheet()->setCellValueByColumnAndRow(0, $excel_row, $item->datesold);
                $object->getActiveSheet()->getStyle('A'.$excel_row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                $object->getActiveSheet()->getStyle('B'.$excel_row)->getNumberFormat()->setFormatCode('#,##0.00');
                $object->getActiveSheet()->getStyle('B'.$excel_row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                $object->getActiveSheet()->setCellValueByColumnAndRow(1, $excel_row, $item->it_name);
                $object->getActiveSheet()->getStyle('C'.$excel_row)->getNumberFormat()->setFormatCode('#,##0.00');
                $object->getActiveSheet()->getStyle('C'.$excel_row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                $object->getActiveSheet()->setCellValueByColumnAndRow(2, $excel_row, $item->si_srp);
                $object->getActiveSheet()->getStyle('D'.$excel_row)->getNumberFormat()->setFormatCode('0');
                $object->getActiveSheet()->getStyle('D'.$excel_row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                $object->getActiveSheet()->setCellValueByColumnAndRow(3, $excel_row, $mobnum);
                $object->getActiveSheet()->getStyle('E'.$excel_row)->getNumberFormat()->setFormatCode('0');
                $object->getActiveSheet()->getStyle('E'.$excel_row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                $object->getActiveSheet()->setCellValueByColumnAndRow(4, $excel_row, $refnum);
                $object->getActiveSheet()->setCellValueByColumnAndRow(5, $excel_row, $balance);
                $object->getActiveSheet()->getStyle('F'.$excel_row)->getNumberFormat()->setFormatCode('#,##0.00');
                $object->getActiveSheet()->getStyle('F'.$excel_row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                $object->getActiveSheet()->setCellValueByColumnAndRow(6, $excel_row, $item->si_netprice);
                $object->getActiveSheet()->getStyle('G'.$excel_row)->getNumberFormat()->setFormatCode('#,##0.00');
                $object->getActiveSheet()->getStyle('G'.$excel_row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                $object->getActiveSheet()->setCellValueByColumnAndRow(7, $excel_row, $sub);
                $excel_row++;
            }
            $object->getActiveSheet()->setCellValueByColumnAndRow(0, $excel_row, 'TOTAL');
            $object->getActiveSheet()->getStyle('C'.$excel_row)->getNumberFormat()->setFormatCode('#,##0.00');
            $object->getActiveSheet()->getStyle('C'.$excel_row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $object->getActiveSheet()->setCellValueByColumnAndRow(2, $excel_row, $totloadamt);
            // $object->getActiveSheet()->getStyle('F'.$excel_row)->getNumberFormat()->setFormatCode('#,##0.00');
            // $object->getActiveSheet()->setCellValueByColumnAndRow(4, $excel_row, $balance);
            $object->getActiveSheet()->getStyle('G'.$excel_row)->getNumberFormat()->setFormatCode('#,##0.00');
            $object->getActiveSheet()->getStyle('G'.$excel_row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);            
            $object->getActiveSheet()->setCellValueByColumnAndRow(6, $excel_row, $nettotal);
            $object->getActiveSheet()->getStyle('H'.$excel_row)->getNumberFormat()->setFormatCode('#,##0.00');
            $object->getActiveSheet()->getStyle('H'.$excel_row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $object->getActiveSheet()->setCellValueByColumnAndRow(7, $excel_row, $totincome);
            $object->getActiveSheet()
            ->getStyle('A'.$excel_row.':H'.$excel_row)
            ->applyFromArray(
                array(
                    'fill' => array(
                        'type' => PHPExcel_Style_Fill::FILL_SOLID,
                        'color' => array('rgb' => '54c0f9')
                    )
                )
            );

            $object->getActiveSheet()->setTitle("LOAD");

            if(count($this->Model_Transaction->getTransactionPerDay($date,"")) > 0)
            {
                $object->createSheet(1);
                $object->setActiveSheetIndex(1);
                $object->getActiveSheet()->setTitle("OTHER ITEMS");

                $otitems = $this->Model_Transaction->getTransactionPerDay($date,"");

                $object->getActiveSheet()->getColumnDimension('A')->setWidth(20);
                $object->getActiveSheet()->getColumnDimension('B')->setWidth(40);
                $object->getActiveSheet()->getColumnDimension('C')->setWidth(20);
                $object->getActiveSheet()->getColumnDimension('D')->setWidth(20);
                $object->getActiveSheet()->getColumnDimension('E')->setWidth(20);
                $object->getActiveSheet()->getColumnDimension('F')->setWidth(20);
                $object->getActiveSheet()->getColumnDimension('G')->setWidth(20);
                $object->getActiveSheet()->getColumnDimension('H')->setWidth(20);

                $table_columns = array("DATE", "ITEM NAME","QTY","SRP", "NET PRICE","TOTAL SRP","TOTAL NET","INCOME");

                $column = 0;

                foreach($table_columns as $field)
                {
                    $object->getActiveSheet()->setCellValueByColumnAndRow($column, 1, $field);
                    $column++;
                }

                $object->getActiveSheet()->getStyle("A1:H1")->getFont()->setBold( true );
                $object->getActiveSheet()->freezePane('A2');

                $excel_row = 2;

                foreach($otitems as $otitem)
                {
                    $sub = 0;

                    $totsrp = floatval($otitem->si_srp) * floatval($otitem->si_qty);

                    $totnetprice = floatval($otitem->it_netprice) * floatval($otitem->si_qty);

                    $sub = floatval($totsrp) - floatval($totnetprice);

                    $object->getActiveSheet()->setCellValueByColumnAndRow(0, $excel_row, $otitem->datesold);
                    $object->getActiveSheet()->setCellValueByColumnAndRow(1, $excel_row, $otitem->it_name);

                    $object->getActiveSheet()->getStyle('C'.$excel_row)->getNumberFormat()->setFormatCode('0');
                    $object->getActiveSheet()->setCellValueByColumnAndRow(2, $excel_row, $otitem->si_qty);

                    $object->getActiveSheet()->getStyle('D'.$excel_row)->getNumberFormat()->setFormatCode('#,##0.00');
                    $object->getActiveSheet()->setCellValueByColumnAndRow(3, $excel_row, $otitem->si_srp);

                    $object->getActiveSheet()->getStyle('E'.$excel_row)->getNumberFormat()->setFormatCode('#,##0.00');
                    $object->getActiveSheet()->setCellValueByColumnAndRow(4, $excel_row, $otitem->si_netprice);

                    $object->getActiveSheet()->getStyle('F'.$excel_row)->getNumberFormat()->setFormatCode('#,##0.00');
                    $object->getActiveSheet()->setCellValueByColumnAndRow(5, $excel_row, $totsrp);

                    $object->getActiveSheet()->getStyle('G'.$excel_row)->getNumberFormat()->setFormatCode('#,##0.00');
                    $object->getActiveSheet()->setCellValueByColumnAndRow(6, $excel_row, $totnetprice);

                    $object->getActiveSheet()->getStyle('H'.$excel_row)->getNumberFormat()->setFormatCode('#,##0.00');
                    $object->getActiveSheet()->setCellValueByColumnAndRow(7, $excel_row, $sub);
                    $excel_row++;
                }


                $object->setActiveSheetIndex(0);
            }

            if(count($this->Model_Transaction->getTransactionPerDay($date,"")) > 0)
            {
                $object->createSheet(2);
                $object->setActiveSheetIndex(2);
                $object->getActiveSheet()->setTitle("TYPE");

                $otitems = $this->Model_Transaction->getTransactionPerDay($date,"");

                $object->getActiveSheet()->getColumnDimension('A')->setWidth(20);
                $object->getActiveSheet()->getColumnDimension('B')->setWidth(40);
                $object->getActiveSheet()->getColumnDimension('C')->setWidth(20);
                $object->getActiveSheet()->getColumnDimension('D')->setWidth(20);
                $object->getActiveSheet()->getColumnDimension('E')->setWidth(20);
                $object->getActiveSheet()->getColumnDimension('F')->setWidth(20);
                $object->getActiveSheet()->getColumnDimension('G')->setWidth(20);
                $object->getActiveSheet()->getColumnDimension('H')->setWidth(20);

                $table_columns = array("DATE", "ITEM NAME", "TOTAL NET","INCOME");

                $column = 0;

                 foreach($table_columns as $field)
                {
                    $object->getActiveSheet()->setCellValueByColumnAndRow($column, 1, $field);
                    $column++;
                }

                $object->getActiveSheet()->getStyle("A1:H1")->getFont()->setBold( true );
                $object->getActiveSheet()->freezePane('A2');

                $excel_row = 2;

                foreach($otitems as $otitem)
                {
                    $sub = 0;

                    $totsrp = floatval($otitem->si_srp) * floatval($otitem->si_qty);

                    $totnetprice = floatval($otitem->it_netprice) * floatval($otitem->si_qty);

                    $sub = floatval($totsrp) - floatval($totnetprice);

                    $object->getActiveSheet()->setCellValueByColumnAndRow(0, $excel_row, $otitem->datesold);
                    $object->getActiveSheet()->setCellValueByColumnAndRow(1, $excel_row, $otitem->it_name);


                    $object->getActiveSheet()->getStyle('G'.$excel_row)->getNumberFormat()->setFormatCode('#,##0.00');
                    $object->getActiveSheet()->setCellValueByColumnAndRow(2, $excel_row, $totnetprice);

                    $object->getActiveSheet()->getStyle('H'.$excel_row)->getNumberFormat()->setFormatCode('#,##0.00');
                    $object->getActiveSheet()->setCellValueByColumnAndRow(3, $excel_row, $sub);
                    $excel_row++;
                }

                $object->setActiveSheetIndex(0);
            }

            // $object->createSheet();
            // $object->setActiveSheetIndex(1);

            // $table_columns = array("DATE", "LOAD AMOUNT", "MOBILE NO.", "TRANSACTION NO.", "LOAD BALANCE","COST","INCOME");

            // $column = 0;

            // foreach($table_columns as $field)
            // {
            //     $object->getActiveSheet()->setCellValueByColumnAndRow($column, 1, $field);
            //     $column++;
            // }

        }
        else 
        {
                $items = $this->Model_Transaction->getTransactionPerDay($date,$itemoum);

                $object->getActiveSheet()->getColumnDimension('A')->setWidth(20);
                $object->getActiveSheet()->getColumnDimension('B')->setWidth(40);
                $object->getActiveSheet()->getColumnDimension('C')->setWidth(20);
                $object->getActiveSheet()->getColumnDimension('D')->setWidth(20);
                $object->getActiveSheet()->getColumnDimension('E')->setWidth(20);
                $object->getActiveSheet()->getColumnDimension('F')->setWidth(20);
                $object->getActiveSheet()->getColumnDimension('G')->setWidth(20);
                $object->getActiveSheet()->getColumnDimension('H')->setWidth(20);

                $table_columns = array("DATE", "ITEM NAME","QTY","SRP", "NET PRICE","TOTAL SRP","TOTAL NET","INCOME");

                $column = 0;

                foreach($table_columns as $field)
                {
                    $object->getActiveSheet()->setCellValueByColumnAndRow($column, 1, $field);
                    $column++;
                }

                $object->getActiveSheet()->getStyle("A1:H1")->getFont()->setBold( true );
                $object->getActiveSheet()->freezePane('A2');

                $excel_row = 2;

                foreach($items as $item)
                {
                    $sub = 0;

                    $totsrp = floatval($item->si_srp) * floatval($item->si_qty);

                    $totnetprice = floatval($item->it_netprice) * floatval($item->si_qty);

                    $sub = floatval($totsrp) - floatval($totnetprice);

                    $object->getActiveSheet()->setCellValueByColumnAndRow(0, $excel_row, $item->datesold);
                    $object->getActiveSheet()->setCellValueByColumnAndRow(1, $excel_row, $item->it_name);

                    $object->getActiveSheet()->getStyle('C'.$excel_row)->getNumberFormat()->setFormatCode('0');
                    $object->getActiveSheet()->setCellValueByColumnAndRow(2, $excel_row, $item->si_qty);

                    $object->getActiveSheet()->getStyle('D'.$excel_row)->getNumberFormat()->setFormatCode('#,##0.00');
                    $object->getActiveSheet()->setCellValueByColumnAndRow(3, $excel_row, $item->si_srp);

                    $object->getActiveSheet()->getStyle('E'.$excel_row)->getNumberFormat()->setFormatCode('#,##0.00');
                    $object->getActiveSheet()->setCellValueByColumnAndRow(4, $excel_row, $item->si_netprice);

                    $object->getActiveSheet()->getStyle('F'.$excel_row)->getNumberFormat()->setFormatCode('#,##0.00');
                    $object->getActiveSheet()->setCellValueByColumnAndRow(5, $excel_row, $totsrp);

                    $object->getActiveSheet()->getStyle('G'.$excel_row)->getNumberFormat()->setFormatCode('#,##0.00');
                    $object->getActiveSheet()->setCellValueByColumnAndRow(6, $excel_row, $totnetprice);

                    $object->getActiveSheet()->getStyle('H'.$excel_row)->getNumberFormat()->setFormatCode('#,##0.00');
                    $object->getActiveSheet()->setCellValueByColumnAndRow(7, $excel_row, $sub);
                    $excel_row++;
                }
        }

        $object_writer = PHPExcel_IOFactory::createWriter($object, 'Excel5');
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="'.$filename.'.xls"');
        $object_writer->save('php://output');

        $response['st'] = true;
        echo json_encode($response);
    } 

    public function textfiletoexcel()
    {
        set_time_limit(0);
        ob_implicit_flush(true);
        ob_end_flush();       

        $hasError = false;

        //var_dump($_FILES);
        $line = "";
        $date = "";
        $arr_con = [];
        $arr_non = [];
        $headerError= false;
        foreach ($_FILES as $key => $value) 
        {
            $arr_f = [];

            if($value['type']=="text/plain")
            {
                usleep(80000);
                $r_f = fopen($value['tmp_name'],'r');
                $fline = 0;
                while(!feof($r_f)) 
                {
                    //usleep(80000);
                    echo json_encode([
                        'status'	=> 'data-process',
                        'message'	=> 'Data Mining Line #'.$fline.' Please Wait..'
                    ]);
                    // if(trim(fgets($r_f))!=="")
                    // {
                    //     $arr_f[] = fgets($r_f);
                    // }
                    $arr_f[] = fgets($r_f);
                    $fline++;
                }
                
                $name = "";
                $phonenumber = "";
                $cntlines = 1;
                $arrlength = count($arr_f);
                $percent = 0;
                for ($i=0; $i < count($arr_f); $i++) 
                { 

                    //$percent = ceil(($cntlines * $arrlength) / 100);
                    echo json_encode([
                        'status'	=> 'data-matching',
                        'message'	=> 'Matching Data '.$cntlines." of ".$arrlength
                    ]);
                    //usleep(80000);
                    if($headerError)
                    {
                        if (strpos($arr_f[0],'ID') !== false) 
                        {
                            $headerError = true;       
                            break;
                        }
                    }


                    if (strpos($arr_f[$i],'Name') !== false) 
                    {
                        $name = explode(':',$arr_f[$i]);
                        $name = $name[1];
                    }

                    if (strpos($arr_f[$i],'Phone Number') !== false) 
                    {
                        $phonenumber = explode(':',$arr_f[$i]);
                        $phonenumber = $phonenumber[1];
                    }

                    if (strpos($arr_f[$i],'Received') !== false)
                    {
                        $line = "";
                        $date = "";
                        $amount = 0;
                        $transferloadto = "";
                        $from ="";
                        $balance = 0;
                        $ref = "";
                        $net = 0;
                        $line = $arr_f[$i].' '.$arr_f[$i+1];
                        //echo $line.'end<br />';

                        // if(strpos($line,'transferred') !== false)
                        // {
                        //    $date = $this->getTxtfileDate($line);

                        //     $amount = $this->getAmountTransferred($line);

                        //     $transferto = $this->getTransferredTo($line);

                        //     $from = $this->getFromTransferred($line);

                        //     $balance = $this->getBalanceTransferred($line);

                        //     $ref = $this->getRef($line);

                        //     $arr_con[] = array(
                        //         "transaction"       =>  'Transferred',
                        //         "date"              =>  $date,
                        //         "amount_transfer"   =>  $amount,
                        //         "transfer_to"       =>  $transferto,
                        //         "from"              =>  $from,
                        //         "balance"           =>  $balance,
                        //         "ref"               =>  $ref
                        //     );
                        // }
                        
                        // if(strpos($line,'Transfer') !== false)
                        // {
                        //     $date = $this->getTxtfileDate($line);  
                            
                        //     $amount = $this->getAmountTransfer($line);

                        //     $transferto = $this->getTransferTo($line);

                        //     $from = $this->getFromTransfer($line);

                        //     $balance = $this->getBalanceTransfer($line);

                        //     $ref = $this->getRef($line);
                            
                        //     $arr_con[] = array(
                        //         "transaction"       =>  'Transfer',
                        //         "date"              =>  $date,
                        //         "amount_transfer"   =>  $amount,
                        //         "transfer_to"       =>  $transferto,
                        //         "from"              =>  $from,
                        //         "balance"           =>  $balance,
                        //         "ref"               =>  $ref
                        //     );
                        // }

                        // if(strpos($line,'loaded to Load Wallet') !== false)
                        // {
                        //     $date = $this->getTxtfileDate($line);  
                            
                        //     // $amount = $this->getAmountTransfer($line);

                        //     // $transferto = $this->getTransferTo($line);

                        //     // $from = $this->getFromTransfer($line);

                        //     // $balance = $this->getBalanceTransfer($line);

                        //     // $ref = $this->getRef($line);
                            
                        //     $arr_con[] = array(
                        //         "transaction"       =>  'Loaded To Load Wallet',
                        //         "date"              =>  $date,
                        //         "amount_transfer"   =>  $amount,
                        //         "transfer_to"       =>  $transferto,
                        //         "from"              =>  $from,
                        //         "balance"           =>  $balance,
                        //         "ref"               =>  $ref
                        //     );
                        // }

                        switch ($line) 
                        {
                            case strpos($line,'transferred') !== false:
                                $date = $this->getTxtfileDate($line);

                                $amount = $this->getAmountTransferred($line);

                                $transferloadto = $this->getTransferredTo($line);

                                $from = $this->getFromTransferred($line);

                                $balance = $this->getBalanceTransferred($line);

                                $ref = $this->getRef($line);

                                $arr_con[] = array(
                                    "transaction"               =>  'Transfer',
                                    "date"                      =>  $date,
                                    "amount_transfer_loaded"    =>  $amount,
                                    "transferloaded_to"         =>  $transferloadto,
                                    "from"                      =>  $from,
                                    "balance"                   =>  $balance,
                                    "ref"                       =>  $ref,
                                    "net"                       =>  $net
                                );
                                break;
                            
                            case strpos($line,'Transfer') !== false:
                                
                                $date = $this->getTxtfileDate($line);  
                                
                                $amount = $this->getAmountTransfer($line);

                                $transferloadto = $this->getTransferTo($line);

                                $from = $this->getFromTransfer($line);

                                $balance = $this->getBalanceTransfer($line);

                                $ref = $this->getRef($line);
                                
                                $arr_con[] = array(
                                    "transaction"               =>  'Transfer',
                                    "date"                      =>  $date,
                                    "amount_transfer_loaded"    =>  $amount,
                                    "transferloaded_to"         =>  $transferloadto,
                                    "from"                      =>  $from,
                                    "balance"                   =>  $balance,
                                    "ref"                       =>  $ref,
                                    "net"                       =>  $net
                                );

                                break;

                            case strpos($line,'loaded to Load Wallet') !== false:
                                $date = $this->getTxtfileDate($line);  
                                
                                $amount = $this->getAmountLoadedToLoadWallet($line);

                                $transferloadto = $this->getTransferLoadWallet($line);

                                $from = $this->getFromTransferLoadWallet($line);

                                $balance = $this->getBalanceTransferredLoadWallet($line);

                                $ref = $this->getRef($line);
                                
                                $arr_con[] = array(
                                    "transaction"               =>  'Load',
                                    "date"                      =>  $date,
                                    "amount_transfer_loaded"    =>  $amount,
                                    "transferloaded_to"         =>  $transferloadto,
                                    "from"                      =>  $from,
                                    "balance"                   =>  $balance,
                                    "ref"                       =>  $ref,
                                    "net"                       =>  $net
                                );

                                break;
                            
                            case strpos($line,'has loaded') !== false:
                            
                                $date = $this->getTxtfileDate($line);                                      
                               
                                $amount = $this->getAmountLoadedToCustormer($line);

                                $transferloadto = $this->getLoadedNumber($line);

                                $from = $this->getFromNumber($line);

                                $balance = $this->getBalanceAfterLoad($line);

                                $ref = $this->getRef($line);

                                $net = $this->getNet($line);

                                $arr_con[] = array(
                                    "transaction"               =>  'Load',
                                    "date"                      =>  $date,
                                    "amount_transfer_loaded"    =>  $amount,
                                    "transferloaded_to"         =>  $transferloadto,
                                    "from"                      =>  $from,
                                    "balance"                   =>  $balance,
                                    "ref"                       =>  $ref,
                                    "net"                       =>  $net
                                );

                                break; 

                            default:
                                $arr_non[] = $line;
                            break;                            
                        }

                        
                    } 

                    $cntlines++;
                }
            }
        }

        if($headerError)
        {
            echo json_encode([
                'status'	=> 'error',
                'message'	=> 'Invalid Textfile'
            ]);

            exit();
        }

        usleep(80000);
        echo json_encode([
            'status'	=> 'data-matched',
            'message'	=> 'Total Data Matched '.count($arr_con)
        ]);

        usleep(80000);
        echo json_encode([
            'status'	=> 'data-matched',
            'message'	=> 'Converting to Excel..Please Wait...'
        ]);


        if(count($arr_con)>0)
        {
            $this->load->library("excel");
            $object = new PHPExcel();
    
            $object->setActiveSheetIndex(0);            
            $object->getActiveSheet()->getColumnDimension('A')->setWidth(20);
            $object->getActiveSheet()->getColumnDimension('B')->setWidth(20);
            $object->getActiveSheet()->getColumnDimension('C')->setWidth(20);
            $object->getActiveSheet()->getColumnDimension('D')->setWidth(30);
            $object->getActiveSheet()->getColumnDimension('E')->setWidth(30);
            $object->getActiveSheet()->getColumnDimension('F')->setWidth(20);
            $object->getActiveSheet()->getColumnDimension('G')->setWidth(20);
            $object->getActiveSheet()->getColumnDimension('H')->setWidth(20);

            $table_columns = array("DATE", "TRANSACTION TYPE", "LOAD", "TO", "FROM", "BALANCE","REF","NET");

            $column = 0;

            foreach($table_columns as $field)
            {
                $object->getActiveSheet()->setCellValueByColumnAndRow($column, 1, $field);
                $column++;
            }

            $object->getActiveSheet()->getStyle("A1:H1")->getFont()->setBold( true );
            $object->getActiveSheet()->freezePane('A2');

            $excel_row = 2;
            $d_arr = count($arr_con);
            $d_cnt = 1;
            foreach($arr_con as $d)
            {
                //usleep(80000);
                echo json_encode([
                    'status'	=> 'data-matched',
                    'message'	=> 'Saving Transaction '.$d_cnt.' of '.$d_arr
                ]);
                $object->getActiveSheet()->setCellValueByColumnAndRow(0, $excel_row, $d['date']);
                $object->getActiveSheet()->setCellValueByColumnAndRow(1, $excel_row, $d['transaction']);
                $object->getActiveSheet()->setCellValueByColumnAndRow(2, $excel_row, $d['amount_transfer_loaded']);
                $object->getActiveSheet()->getStyle('C'.$excel_row)->getNumberFormat()->setFormatCode('#,##0.00');
                $object->getActiveSheet()->getStyle('C'.$excel_row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
                $object->getActiveSheet()->setCellValueByColumnAndRow(3, $excel_row, $d['transferloaded_to']);
                $object->getActiveSheet()->setCellValueByColumnAndRow(4, $excel_row, $d['from']);
                $object->getActiveSheet()->getStyle('E'.$excel_row)->getNumberFormat()->setFormatCode('0');
                $object->getActiveSheet()->setCellValueByColumnAndRow(5, $excel_row, $d['balance']);
                $object->getActiveSheet()->getStyle('F'.$excel_row)->getNumberFormat()->setFormatCode('#,##0.00');
                $object->getActiveSheet()->setCellValueByColumnAndRow(6, $excel_row, $d['ref']);
                $object->getActiveSheet()->setCellValueByColumnAndRow(7, $excel_row, $d['net']);
                $object->getActiveSheet()->getStyle('H'.$excel_row)->getNumberFormat()->setFormatCode('#,##0.00');
                $excel_row++;
                $d_cnt++;
            }

            // usleep(80000);
            // echo json_encode([
            //     'status'	=> 'data-matched',
            //     'message'	=> 'Saving Transaction '.$d_cnt.' of '.$d_arr
            // ]);

            $object->getActiveSheet()->setTitle("LOAD");

            // $arr_con[] = array(
            //     "transaction"               =>  'Transferred',
            //     "date"                      =>  $date,
            //     "amount_transfer_loaded"    =>  $amount,
            //     "transferloaded_to"         =>  $transferloadto,
            //     "from"                      =>  $from,
            //     "balance"                   =>  $balance,
            //     "ref"                       =>  $ref,
            //     "net"                       =>  $net
            // );

            $filename = "excelfiles/txtfiletoexcel.xls";

            $object_writer = PHPExcel_IOFactory::createWriter($object, 'Excel5');
            // header('Content-Type: application/vnd.ms-excel');
            // header('Content-Disposition: attachment;filename="'.$filename.'"');
            $object_writer->save($filename);

            usleep(80000);
            echo json_encode([
                'status'	=> 'complete',
                'message'	=> 'Complete..Reloading..'
            ]);
        }



    }

    public function getNet($line)
    {
        $net = 0;
        $arr = preg_match_all('/[(].{1,20}[)]/',$line,$matches);

        if(count($arr)>0)
        {
            $net = $matches[0][0];

            $net = ltrim($net,"(");
            $net = ltrim($net,"P");
            $net = rtrim($net,")");           
        }
        return trim($net);
    }

    public function getRef($line)
    {
        $ref = "";
        $arr = preg_match_all('/Ref:.{1,30}/',$line,$matches);

        if(count($arr)>0)
        {
            $ref = $matches[0][0];
        }
        return trim($ref);
    }

    public function getBalanceTransferred($line)
    {
        $balance = "";
        $arr = preg_match_all('/Balance:\s.{1,30}Ref/',$line,$matches);

        if(count($arr)>0)
        {
            $bal = $matches[0][0];
            $bal = explode(" ",$bal);
            $balance = ltrim($bal[1], 'P');
        }
        return trim($balance);
    }
    
    public function getBalanceTransferredLoadWallet($line)
    {
        $balance = "";
        $arr = preg_match_all('/Balance:P.{1,12}\s/',$line,$matches);

        if(count($arr)>0)
        {
            $bal = $matches[0][0];
            $bal = explode(":",$bal);
            $balance = ltrim($bal[1], 'P');
        }
        return trim($balance);
    }

    public function getBalanceAfterLoad($line)
    {
        $balance = 0;
        $arr = preg_match_all('/Balance:.{1,20}\./',$line,$matches);

        if(count($arr)>0)
        {
            $bal = $matches[0][0];
            $bal = explode(":",$bal);
            $balance = ltrim($bal[1], 'P');
            $balance = rtrim($balance,".");
        }
        return trim($balance);
    }

    public function getBalanceTransfer($line)
    {
        $balance = 0;
        $arr = preg_match_all('/Bal:.{1,30}Ref/',$line,$matches);

        if(count($arr)>0)
        {
            $bal = $matches[0][0];
            $bal = explode(" ",$bal);
            $balance = ltrim($bal[0], 'Bal:P');
        }
        return trim($balance);
        
    }

    public function getFromTransferred($line)
    {
        $from = "";

        $arr = preg_match_all('/from\s.{1,40}(.New)/',$line,$matches);

        if(count($arr)>0)
        {
            $fr = $matches[0][0];
            $fr = explode(".",$fr);
            $from = ltrim($fr[0], 'from');
        }
        return trim($from);
    }

    public function getFromTransfer($line)
    {
        $from = "";

        $arr = preg_match_all('/from.{1,60}to/',$line,$matches);

        if(count($arr)>0)
        {
            $fr = $matches[0][0];
            $fr = ltrim($fr, 'from');
            $from = rtrim($fr, 'to');
        }
        return trim($from);
    }

    public function getFromTransferLoadWallet($line)
    {
        $from = "";
        $arr = preg_match_all('/from\s.{1,50}\.New/',$line,$matches);

        if(count($arr)>0)
        {
            $fr = $matches[0][0];
            $fr = explode(".",$fr);
            $from = ltrim($fr[0],'from');
        }
        return trim($from);
    }

    public function getFromNumber($line)
    {
        $from = "";
        $arr = preg_match_all('/[:]\d{1,2}[:].{1,19}\sh/',$line,$matches);

        if(count($arr)>0)
        {
            $fr = $matches[0][0];
            $fr = explode(":",$fr);
            $from = ltrim($fr[2],'from');     
            $from = rtrim($from, 'h');  
        }
        return trim($from);
    }

    public function getTransferredTo($line)
    {
        $to = "";
        $arr = preg_match_all('/\Card\sNo.\s\d{1,30}/',$line,$matches);

        if(count($arr)>0)
        {
            $to = $matches[0][0];
        }
        return $to;
    }

    public function getTransferTo($line)
    {
        $to = 0;
        $arr = preg_match_all('/Load Wallet\s\d{1,13}/',$line,$matches);


        if(count($arr)>0)
        {
            $to = $matches[0][0];
        }
        return $to;

    }

    public function getTransferLoadWallet($line)
    {
        $to = 0;
        $arr = preg_match_all('/Load Wallet of\s\d{1,13}/',$line,$matches);


        if(count($arr)>0)
        {
            $to = $matches[0][0];
        }
        return $to;
    }

    public function getLoadedNumber($line)
    {
        $to = 0;
        $arr = preg_match_all('/to\s\d{1,13}/',$line,$matches);


        if(count($arr)>0)
        {
            $to = $matches[0][0];
            $to = explode(" ",$to);
            $to = trim($to[1]);
        }
        return $to;
    }

    public function getAmountTransferred($line)
    {
        $amount = 0;
        $arr = preg_match_all('/\d{2}[:]\d{2}[:]\s.{1,12}is/',$line,$matches);

        if(count($arr)>0)
        {
            $amount = $matches[0][0];

            $amt = explode(" ",$amount);
            $amount = $amt[1];
            $amount = ltrim($amount, 'P');
        }
        return $amount;
        
    }

    public function getAmountTransfer($line)
    {
        $amount = 0;
        
        $arr = preg_match_all('/Transfer of\s.{1,16}from/',$line,$matches);

        if(count($arr)>0)
        {
            $amount = $matches[0][0];

            $amt = explode(" ",$amount);
            $amount = $amt[2];
            $amount = ltrim($amount, 'P');
        }
        return $amount;
    }

    public function getAmountLoadedToLoadWallet($line)
    {
        $amount = 0;
        
        $arr = preg_match_all('/P.{1,30}is/',$line,$matches);

        if(count($arr)>0)
        {
            $amount = $matches[0][0];

            $amt = explode(" ",$amount);
            $amount = $amt[0];
            $amount = ltrim($amount, 'P');
        }
        return $amount;
    }

    public function getAmountLoadedToCustormer($line)
    {
        $amount = 0;
        
        $arr = preg_match_all('/loaded.{1,50}[(]/',$line,$matches);

        if(count($arr)>0)
        {
            echo $amount = $matches[0][0];

            $amount = ltrim($amount,"loaded");
            $amount = rtrim($amount,"(");
            $amount = trim($amount);
        }
        return $amount;
    }


    public function getTxtfileDate($line)
    {
        $date = "";
        $arr = preg_match_all('/\d{1,4}[-]\d{1,2}[-]\d{1,2}/',$line,$matches);
        if(count($arr)>0)
        {
            $date = $matches[0][0];
        }
        return $date;
    }

 
 
}

// I assume you're trying to Freeze columns and rows both.

// freezePane will obviously overwrite any previous parameters you might have given to it.

// As per your current scenario, I see that you're trying to freeze the top row and the left-most 3 columns

// Try this:

// $objPHPExcel->getActiveSheet()->freezePane('D2');
// This will freeze Row 1 and Columns A,B & C

// This should get your work done!

// Note: freezePane works exactly how you use it in MS Excel. You select a cell and select Freeze. And it freezes whatever rows are above it, and the columns which are left to it.
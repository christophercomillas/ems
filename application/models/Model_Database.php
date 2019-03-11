<?php

class Model_Database extends CI_Model 
{
	public function __construct()
	{		
		parent::__construct();
	}

	public function truncateDB()
	{
		$this->db->from('eod');
		$this->db->truncate();

		$this->db->from('item_ledger');
		$this->db->truncate();

		$this->db->from('logs');
		$this->db->truncate();

		$this->db->from('receiving_items');
		$this->db->truncate();		

		$this->db->from('receiving_transaction');
		$this->db->truncate();

		$this->db->from('sales_items');
		$this->db->truncate();		

		$this->db->from('sales_load_details');
		$this->db->truncate();

		$this->db->from('sales_payment');
		$this->db->truncate();

		$this->db->from('sales_transaction');
		$this->db->truncate();
	}
}
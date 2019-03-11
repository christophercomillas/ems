<?php

class Model_Functions extends CI_Model 
{
	public function __construct()
	{		
		parent::__construct();

	}

	// public function getTrNumber($table,$select,$field,$var,$order,$varorder)
	// {
	// 	$trnum = 0;
	// 	$this->_Server->select($select)
	// 	->where($field, $var)
	// 	->order_by($order, $varorder);
	// 	$query = $this->db>get($table);

	// 	if($query->num_rows > 0)
	// 	{
	// 		$trnum = $query->row()->$select;
	// 		$trnum++;
	// 	}
	// 	else 
	// 	{
	// 		$trnum = 1;
	// 	}
	// 	return $trnum;	

	// }

	public function getTrNumber($table,$select,$field,$var,$orderfield,$ordervar)
	{
		$trnum = 0;
		$this->db->select($select)
		->where($field, $var)
		->order_by($orderfield, $ordervar);

		$query = $this->db->get($table);

		if($query->num_rows() > 0)
		{
			$trnum = $query->row()->$select;
			$trnum++;
		}
		else 
		{
			$trnum = 1; 
		}

		return $trnum;
	}

	public function getFieldAllOrder($table,$select,$orderf,$orderv,$ser)
	{
		$this->assignDB($ser);

		$this->_Server->select($select)
		->order_by($orderf, $orderv);
		$query = $this->_Server->get($table);
		return  $query->result();
	}

	public function getAll($table)
	{
		$this->db->select('*');
		$query = $this->db->get($table);
		return $query->result();
	}

	public function getField($table,$field,$var,$ser)
	{
		$this->assignDB($ser);

		$this->_Server->select($field)
		->where($field, $id);
		$query = $this->_Server->get($table);
		return $query->result();
	}

	public function getFields($table,$select,$field,$var)
	{
		$this->db->select($select)
		->where($field, $var);
		$query = $this->db->get($table);
		return $query->row()->$select;
	}

	public function count1($table,$select,$field,$var)
	{
		$this->db->select($field)
		->where($field,$var);
		$query = $this->db->get($table);
		return $query->result();
	}

	public function getFieldValue($table,$select,$get,$field,$var)
	{
		$this->db->select($select)
		->where($field, $var);
		$query = $this->db->get($table);
		return $query->row()->$get;
	}

	public function getTableFields($table,$select,$field,$var)
	{
		$this->db->select($select)
		->where($field, $var);
		$query = $this->db->get($table);
		return $query->result();
	}

	public function updateOne($table,$row,$var,$where,$var1,$ser)
	{

		$this->assignDB($ser);

		$data = array(
			$row		=>	$var
        );

        $this->_Server->where($where, $var1)
		->update($table, $data); 
	}

	public function updateOneWhereTwo($table,$fieldup,$varup,$where1,$where2,$var1,$var2,$ser)
	{
		$this->assignDB($ser);

		$data = array(
			$fieldup		=>	$varup
        );

        $this->_Server->where($where1, $var1)
        ->where($where2,$var2)
		->update($table, $data); 
	}

	public function getTwoWhereOne($table,$select1,$select2,$field1,$var1,$ser)
	{
		$this->assignDB($ser);

		$this->_Server->select($select1,$select2)
		->where($field1,$var1);
		$query = $this->_Server->get($table);
		return $query->result();
	}

	public function updateTwoWhere($table,$row,$var,$where1,$where2,$var1,$var2,$ser)
	{
		$this->assignDB($ser);

		$data = array(
			$row		=>	$var
        );

        $this->_DBlocal->where($where1, $var1)
        ->where($where2,$var2)
		->update($table, $data); 
	}

	public function countRowNoArg($table,$ser)
	{
		$this->assignDB($ser);
		return $this->_Server->count_all_results($table);		
	}

	public function countRow($table,$var,$field)
	{
		$this->db->where($field,$var);
		return $this->db->count_all_results($table);		
	}

	public function countRowTwoArg($table,$var1,$var2,$field1,$field2)
	{
		$this->db->where($field1,$var1)
		->where($field2,$var2);
		return $this->db->count_all_results($table);
	}

	public function countRowThreeArgDate($table,$var1,$var2,$var3,$field1,$field2,$field3)
	{
		$this->db->where($field1,$var1)
		->where($field2.'<=', $var2, FALSE)
		->where($field3,$var3);
		return $this->db->count_all_results($table);
	}

	public function getAllTableRecordsOrder($table,$field,$var)
	{
		$this->db->select('*')
		->order_by($field, $var);

		$query = $this->db->get($table);
		return $query->result();
	}

	public function getAllTableRecordsOrderWhereOne($table,$ordefield,$ordervar,$field,$var)
	{
		$this->db->select('*')
		->where($field,$var)
		->order_by($field, $var);

		$query = $this->db->get($table);
		return $query->result();
	}

	public function is_in_array($array, $key, $key_value)
	{
		$within_array = false;
		foreach( $array as $k=>$v )
		{
			if( is_array($v) )
			{
			    $within_array = is_in_array($v, $key, $key_value);
			    if( $within_array == true )
			    {
			        break;
			    }
			} 
			else 
			{
			    if( $v == $key_value && $k == $key )
			    {
			        $within_array = true;
			        break;
			    }
			}
		}
		return $within_array;
	}

	public function search_arr($array, $key, $value)
	{
		$found = false;
	    if (is_array($array)) {
	        if (isset($array[$key]) && $array[$key] == $value) {
	           $found = true;	           
	        }
	    }
	    return $found;
	}

	function findObjectById($arr,$key,$keyvalue)
	{
	    $array = array($arr);
	    if($this->search_arr($array,$key,$keyvalue))
	    {
	    	return true;
	    }
	    return false;
	}
	// public function getField($table,$var,$field,$ser)
	// {

	// }


}
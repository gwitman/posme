<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Relationship_Model extends CI_Model  {
   function __construct(){
      parent::__construct();
   } 
  function delete($employeeID,$customerID){		  		
		$this->db->where('employeeID', $employeeID);
		$this->db->where('customerID', $customerID);	
		$this->db->delete('tb_relationship');
		return $this->db->affected_rows(); 
   }
   function insert($data){	
		$result			= $this->db->insert('tb_relationship', $data);
		$autoIncrement	= $this->db->insert_id(); 		
		return $result ? $autoIncrement : $result; 
   }
}
?>
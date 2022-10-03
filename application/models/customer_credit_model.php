<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Customer_Credit_Model extends CI_Model  {
   function __construct(){
      parent::__construct();
   }
   function update($companyID,$branchID,$entityID,$data){
		$this->db->where('companyID', $companyID);
		$this->db->where('branchID', $branchID);	
		$this->db->where('entityID', $entityID);	
		$this->db->update('tb_customer_credit', $data);
		return $this->db->affected_rows(); 
   }
   function insert($data){
		$result			 		= $this->db->insert('tb_customer_credit', $data);
		return $result;
   }   
   function get_rowByPK($companyID,$branchID,$entityID){    
		$this->db->select("companyID, branchID, entityID, limitCreditDol, balanceDol, incomeDol");
		$this->db->from("tb_customer_credit i");		
		$this->db->where("i.companyID",$companyID);
		$this->db->where("i.branchID",$branchID);
		$this->db->where("i.entityID",$entityID);
		
		//Ejecutar Consulta
		$recordSet = $this->db->get();
		
		//Obtener errores
		if($this->db->_error_message())  
		return null; 
		
		if($recordSet->num_rows() == 0)
		return null;
		
		//Resultado
		return $recordSet->row();
   }
}
?>
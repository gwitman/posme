<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Transaction_Model extends CI_Model  {
   function __construct(){
      parent::__construct();
   }
   function getByCompanyAndTransaction($companyID,$transactionID){
		$this->db->select("companyID, transactionID, name, description, workflowID, isCountable, reference1, reference2, reference3, generateTransactionNumber, decimalPlaces, journalTypeID, signInventory, isActive");
		$this->db->from("tb_transaction");
		$this->db->where("companyID",$companyID);		
		$this->db->where("transactionID",$transactionID);		
		$this->db->where("isActive",1);		
		
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
   function getTransactionContabilizable($companyID){
		
		$this->db->select("companyID, transactionID, name, description, workflowID, isCountable, reference1, reference2, reference3, generateTransactionNumber, decimalPlaces, journalTypeID, signInventory, isActive");
		$this->db->from("tb_transaction");
		$this->db->where("companyID",$companyID);		
		$this->db->where("isCountable",1);		
		$this->db->where("isActive",1);		
		
		//Ejecutar Consulta
		$recordSet = $this->db->get();
		
		//Obtener errores
		if($this->db->_error_message())  
		return null; 
		
		if($recordSet->num_rows() == 0)
		return null;
		
		//Resultado
		return $recordSet->result();
		
   }
   function update($companyID,$transactionID,$data){
		$this->db->where('companyID', $companyID);
		$this->db->where('transactionID', $transactionID);	
		$this->db->update('tb_transaction', $data);
		return $this->db->affected_rows(); 
   }
}
?>
<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Transaction_Model extends CI_Model  {
   function __construct(){
      parent::__construct();
   }  
   function get_rowByPK($companyID,$name){    
		$this->db->select("companyID,transactionID,name,description,workflowID,accountID,isCountable,reference1,reference2,reference3,generateTransactionNumber,decimalPlaces,isActive,classID,journalTypeID");
		$this->db->from("tb_transaction");
		$this->db->where("companyID",$companyID);
		$this->db->where("name",$name);
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
   function getCounterTransactionMaster($companyID,$transactionID,$statusID){
		
		$this->db->from('tb_transaction tb');
		$this->db->join('tb_transaction_master tm','tb.transactionID = tm.transactionID ');
		$this->db->where('tm.isActive', 1);
		$this->db->where('tm.companyID', $companyID);
		$this->db->where('tm.transactionID', $transactionID);		
		$this->db->where('tm.statusID', $statusID);		
   		return $this->db->count_all_results();
   }
   
   function getCountInput($companyID){
		
		$this->db->from('tb_transaction tb');
		$this->db->join('tb_transaction_master tm','tb.transactionID = tm.transactionID ');
		$this->db->where('tm.isActive', 1);
		$this->db->where('tm.companyID', $companyID);
		$this->db->where('tb.signInventory', 1);
		
   		return $this->db->count_all_results();
   }
   function getCountOutput($companyID){
		
		$this->db->from('tb_transaction tb');
		$this->db->join('tb_transaction_master tm','tb.transactionID = tm.transactionID ');
		$this->db->where('tm.isActive', 1);
		$this->db->where('tm.companyID', $companyID);
		$this->db->where('tb.signInventory', -1);
		
   		return $this->db->count_all_results();
   }
}
?>
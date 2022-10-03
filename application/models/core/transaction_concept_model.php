<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Transaction_Concept_Model extends CI_Model  {
   function __construct(){
      parent::__construct();
   }  
   function get_rowByPK($companyID,$transactionID,$name){    
		$this->db->select("companyID,transactionID,conceptID,name,orden,sign,visible,isActive");
		$this->db->from("tb_transaction_concept");
		$this->db->where("companyID",$companyID);
		$this->db->where("transactionID",$transactionID);
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
}
?>
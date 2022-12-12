<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Transaction_Master_Concept_Model extends CI_Model  {
   function __construct(){
      parent::__construct();
   }
   function get_rowByTransactionMasterConcept($companyID,$transactionID,$transactionMasterID,$componentID){
	    $this->db->select("cc.companyID,cc.componentID,cc.componentItemID,cc.name,cc.valueIn,cc.valueOut");
	    $this->db->from("tb_transaction_master tm");
		$this->db->join("tb_transaction_master_detail td","tm.companyID = td.companyID and tm.transactionID = td.transactionID and tm.transactionMasterID = td.transactionMasterID");
		$this->db->join("tb_item i","td.companyID = i.companyID and td.componentItemID = i.itemID");		
		$this->db->join("tb_company_component_concept cc","cc.componentItemID = i.itemID");
		$this->db->where("td.companyID",$companyID);
		$this->db->where("td.transactionID",$transactionID);		
		$this->db->where("td.transactionMasterID",$transactionMasterID);		
		$this->db->where("cc.componentID",$componentID);		
		$this->db->where("td.isActive",1);		
		
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
  
}
?>
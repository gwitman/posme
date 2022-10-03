<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Transaction_Master_Model extends CI_Model  {
   function __construct(){
      parent::__construct();
   }
   function delete($companyID,$transactionID,$transactionMasterID){
		$data["isActive"] = 0;
		$this->db->where('companyID', $companyID);
		$this->db->where('transactionID', $transactionID);	
		$this->db->where('transactionMasterID', $transactionMasterID);	
		$this->db->update('tb_transaction_master', $data);
		return $this->db->affected_rows(); 
   }
   function insert($data){
		$result			 		= $this->db->insert('tb_transaction_master', $data);
		$autoIncrement			= $this->db->insert_id(); 		
		return $result ? $autoIncrement : $result; 
   }
   function update($companyID,$transactionID,$transactionMasterID,$data){
		$this->db->where('companyID', $companyID);
		$this->db->where('transactionID', $transactionID);	
		$this->db->where('transactionMasterID', $transactionMasterID);	
		$this->db->update('tb_transaction_master', $data);
		return $this->db->affected_rows(); 
   }
   function get_rowByPK($companyID,$transactionID,$transactionMasterID){    
		$this->db->select("tm.companyID, tm.transactionID, tm.transactionMasterID, tm.branchID, tm.transactionNumber, tm.transactionCausalID,tm.entityID, tm.transactionOn, tm.statusIDChangeOn, tm.componentID,tm.tax1,tm.tax2,tm.tax3,tm.tax4,tm.discount,tm.subAmount, tm.note, tm.sign, tm.currencyID, tm.currencyID2, tm.exchangeRate, tm.reference1, tm.reference2, tm.reference3, tm.reference4, tm.statusID, tm.amount, tm.isApplied, tm.journalEntryID, tm.classID, tm.areaID, tm.sourceWarehouseID, tm.targetWarehouseID, tm.createdBy, tm.createdAt, tm.createdOn, tm.createdIn, tm.isActive, ws.name as workflowStageName,tm.priorityID, tm.transactionOn2 ");
		$this->db->from("tb_transaction_master tm");		
		$this->db->join("tb_workflow_stage ws","tm.statusID = ws.workflowStageID");
		$this->db->where("tm.transactionMasterID",$transactionMasterID);
		$this->db->where("tm.transactionID",$transactionID);
		$this->db->where("tm.companyID",$companyID);
		$this->db->where("tm.isActive",1);		
		
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
   function get_rowByTransactionMasterID($companyID,$transactionMasterID){   
		$this->db->select("tm.companyID, tm.transactionID, tm.transactionMasterID, tm.branchID, tm.transactionNumber, tm.transactionCausalID,tm.entityID, tm.transactionOn, tm.statusIDChangeOn, tm.componentID,tm.tax1,tm.tax2,tm.tax3,tm.tax4,tm.discount,tm.subAmount, tm.note, tm.sign, tm.currencyID, tm.currencyID2, tm.exchangeRate, tm.reference1, tm.reference2, tm.reference3, tm.reference4, tm.statusID, tm.amount, tm.isApplied, tm.journalEntryID, tm.classID, tm.areaID, tm.sourceWarehouseID, tm.targetWarehouseID, tm.createdBy, tm.createdAt, tm.createdOn, tm.createdIn, tm.isActive ,tm.priorityID, tm.transactionOn2 ");
		$this->db->from("tb_transaction_master tm");		
		$this->db->where("tm.transactionMasterID",$transactionMasterID);
		$this->db->where("tm.companyID",$companyID);
		$this->db->where("tm.isActive",1);		
		
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
   
   function get_rowByTransactionNumber($companyID,$transactionNumber){    
		$this->db->select("tm.companyID, tm.transactionID, tm.transactionMasterID, tm.branchID, tm.transactionNumber, tm.transactionCausalID,tm.entityID, tm.transactionOn, tm.statusIDChangeOn, tm.componentID,tm.tax1,tm.tax2,tm.tax3,tm.tax4,tm.discount,tm.subAmount, tm.note, tm.sign, tm.currencyID, tm.currencyID2, tm.exchangeRate, tm.reference1, tm.reference2, tm.reference3, tm.reference4, tm.statusID, tm.amount, tm.isApplied, tm.journalEntryID, tm.classID, tm.areaID, tm.sourceWarehouseID, tm.targetWarehouseID, tm.createdBy, tm.createdAt, tm.createdOn, tm.createdIn, tm.isActive ,tm.priorityID , tm.transactionOn2");
		$this->db->from("tb_transaction_master tm");		
		$this->db->where("tm.transactionNumber",$transactionNumber);
		$this->db->where("tm.companyID",$companyID);
		$this->db->where("tm.isActive",1);		
		
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
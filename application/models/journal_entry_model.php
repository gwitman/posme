<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Journal_Entry_Model extends CI_Model  {
   function __construct(){
      parent::__construct();
   } 
  function delete($companyID,$journalEntryID){		
  		$data["isActive"] = 0;
		$this->db->where('companyID', $companyID);
		$this->db->where('journalEntryID', $journalEntryID);	
		$this->db->update('tb_journal_entry', $data);
		return $this->db->affected_rows(); 
   } 
   
   function update($companyID,$journalEntryID,$data){		
		$this->db->where('companyID', $companyID);
		$this->db->where('journalEntryID', $journalEntryID);	
		$this->db->update('tb_journal_entry', $data);
		return $this->db->affected_rows(); 
   }
   function insert($data){	
		$result			 		= $this->db->insert('tb_journal_entry', $data);
		$autoIncrement			= $this->db->insert_id(); 		
		return $result ? $autoIncrement : $result; 
		 
   }
   function get_rowByCode($companyID,$journalNumber){    
		$this->db->select("je.journalEntryID,je.companyID,je.journalNumber,je.entryName,je.journalDate,je.tb_exchange_rate,je.createdOn,je.createdIn,je.createdAt,je.createdBy,je.isActive,je.isApplied,je.statusID,je.note,je.reference1,je.reference2,je.reference3,je.journalTypeID,je.currencyID,je.accountingCycleID,ws.name as workflowStageName,ci.display as journalTypeName,cu.name currencyName,je.isModule,je.transactionMasterID");
		$this->db->from("tb_journal_entry je");
		$this->db->join("tb_workflow_stage ws","je.statusID = ws.workflowStageID");
		$this->db->join("tb_catalog_item ci","je.journalTypeID = ci.catalogItemID");
		$this->db->join("tb_currency cu","je.currencyID = cu.currencyID");
		$this->db->where("je.companyID",$companyID);
		$this->db->where("je.journalNumber",$journalNumber);
		$this->db->where("je.isActive",1);		
		
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
   function get_rowByPK($companyID,$journalEntryID){    
		$this->db->select("je.journalEntryID,je.companyID,je.journalNumber,je.entryName,je.journalDate,je.tb_exchange_rate,je.createdOn,je.createdIn,je.createdAt,je.createdBy,je.isActive,je.isApplied,je.statusID,je.note,je.reference1,je.reference2,je.reference3,je.journalTypeID,je.currencyID,je.accountingCycleID,ws.name as workflowStageName,ci.display as journalTypeName,cu.name currencyName,je.isModule,je.transactionMasterID,je.isTemplated,je.titleTemplated");
		$this->db->from("tb_journal_entry je");
		$this->db->join("tb_workflow_stage ws","je.statusID = ws.workflowStageID");
		$this->db->join("tb_catalog_item ci","je.journalTypeID = ci.catalogItemID");
		$this->db->join("tb_currency cu","je.currencyID = cu.currencyID");
		$this->db->where("je.companyID",$companyID);
		$this->db->where("je.journalEntryID",$journalEntryID);
		$this->db->where("je.isActive",1);		
		
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
   function get_rowByPK_Next($companyID,$journalEntryID){    
		$this->db->select("je.journalEntryID,je.companyID,je.journalNumber,je.entryName,je.journalDate,je.tb_exchange_rate,je.createdOn,je.createdIn,je.createdAt,je.createdBy,je.isActive,je.isApplied,je.statusID,je.note,je.reference1,je.reference2,je.reference3,je.journalTypeID,je.currencyID,je.accountingCycleID,je.isModule,je.transactionMasterID");
		$this->db->from("tb_journal_entry je");
		
		$this->db->where("je.companyID",$companyID);
		$this->db->where("je.journalEntryID >",$journalEntryID);
		$this->db->where("je.isActive",1);		
		
		$this->db->order_by("je.journalEntryID", "asc");
		
		$this->db->limit(1);
		
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
	function get_rowByPK_Back($companyID,$journalEntryID){    
		$this->db->select("je.journalEntryID,je.companyID,je.journalNumber,je.entryName,je.journalDate,je.tb_exchange_rate,je.createdOn,je.createdIn,je.createdAt,je.createdBy,je.isActive,je.isApplied,je.statusID,je.note,je.reference1,je.reference2,je.reference3,je.journalTypeID,je.currencyID,je.accountingCycleID,je.isModule,je.transactionMasterID");
		$this->db->from("tb_journal_entry je");
		
		$this->db->where("je.companyID",$companyID);
		$this->db->where("je.journalEntryID <",$journalEntryID);
		$this->db->where("je.isActive",1);		
		
		$this->db->order_by("je.journalEntryID", "desc");
		
		$this->db->limit(1);
		
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
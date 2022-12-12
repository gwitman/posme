<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Journal_Entry_Detail_Model extends CI_Model  {
   function __construct(){
      parent::__construct();
   } 
  function delete($companyID,$journalEntryID,$journalEntryDetailID){		
  		$data["isActive"] = 0;
		$this->db->where('companyID', $companyID);
		$this->db->where('journalEntryID', $journalEntryID);	
		$this->db->where('journalEntryDetailID', $journalEntryDetailID);	
		$this->db->update('tb_journal_entry_detail', $data);
		return $this->db->affected_rows(); 
   } 
   function deleteWhereIDNotIn($companyID,$journalEntryID,$listDetailID){
		$data["isActive"] = 0;
		$this->db->where('companyID', $companyID);
		$this->db->where('journalEntryID', $journalEntryID);	
		$this->db->where_not_in('journalEntryDetailID', $listDetailID);	
		$this->db->update('tb_journal_entry_detail', $data);
		return $this->db->affected_rows(); 
   }
   function update($companyID,$journalEntryID,$journalEntryDetailID,$data){		
		$this->db->where('companyID', $companyID);
		$this->db->where('journalEntryID', $journalEntryID);	
		$this->db->where('journalEntryDetailID', $journalEntryDetailID);	
		$this->db->update('tb_journal_entry_detail', $data);
		return $this->db->affected_rows(); 
   }
   function insert($data){	
		$result			 		= $this->db->insert('tb_journal_entry_detail', $data);
		$autoIncrement			= $this->db->insert_id(); 		
		return $result ? $autoIncrement : $result; 
		 
   }
   function get_rowByJournalEntryID($companyID,$journalEntryID){
		$this->db->select("jed.journalEntryDetailID,jed.journalEntryID,jed.companyID,jed.accountID,jed.isActive,jed.classID,jed.debit,jed.credit,jed.note,jed.isApplied,jed.branchID,jed.tb_exchange_rate,cc.number as classNumber,a.accountNumber,a.name as accountName");
		$this->db->from("tb_journal_entry_detail jed");
		$this->db->join("tb_account a","jed.accountID = a.accountID");
		$this->db->join("tb_center_cost cc","jed.classID = cc.classID","left");
		$this->db->where("jed.companyID",$companyID);
		$this->db->where("jed.journalEntryID",$journalEntryID);		
		$this->db->where("jed.isActive",1);		
		
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
   function get_rowByPK($companyID,$journalEntryID,$journalEntryDetailID){    
		$this->db->select("jed.journalEntryDetailID,jed.journalEntryID,jed.companyID,jed.accountID,jed.isActive,jed.classID,jed.debit,jed.credit,jed.note,jed.isApplied,jed.branchID,jed.tb_exchange_rate,cc.number as classNumber,a.accountNumber");
		$this->db->from("tb_journal_entry_detail jed");
		$this->db->join("tb_account a","jed.accountID = a.accountID");
		$this->db->join("tb_center_cost cc","jed.classID = cc.classID","left");
		$this->db->where("jed.companyID",$companyID);
		$this->db->where("jed.journalEntryID",$journalEntryID);
		$this->db->where("jed.journalEntryDetailID",$journalEntryDetailID);
		$this->db->where("jed.isActive",1);		
		
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
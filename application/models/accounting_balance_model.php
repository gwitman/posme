<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Accounting_Balance_Model extends CI_Model  {
   function __construct(){
      parent::__construct();
   } 
   function updateBalance($companyID,$periodID,$cycleID,$accountID,$balance,$debit,$credit){
		$sql = "		
		UPDATE tb_accounting_balance SET 
			balance 	= balance + $balance , 
			debit 		= debit   + $debit , 
			credit 		= credit  + $credit 
		WHERE 
			companyID 			= $companyID and 
			accountID 			= $accountID and 
			componentPeriodID 	= $periodID and 
			componentCycleID 	= $cycleID;
		";
		
		$r 	= $this->db->query($sql);	
   }
   function getMinAccount($companyID,$branchID,$loginID){
		$this->db->where("companyID",$companyID);
		$this->db->where("branchID",$branchID);
		$this->db->where("loginID",$loginID);
		$this->db->select_min('accountID');
		$r 			= $this->db->get('tb_journal_entry_detail_summary')->result();
		$accountID 	= $r[0]->accountID;
		
		if ($accountID == null)
			return null;
		else
			return $accountID;
   }
   function getMinAccountBy($companyID,$branchID,$loginID,$accountID){
		
		$this->db->where("companyID",$companyID);
		$this->db->where("branchID",$branchID);
		$this->db->where("loginID",$loginID);
		$this->db->where("accountID > ",$accountID);
		$this->db->select_min('accountID');
		$r 			= $this->db->get('tb_journal_entry_detail_summary')->result();		
		
		$accountID 	= $r[0]->accountID;
		if ($accountID == null)
			return null;
		else
			return $accountID;
   }
   function getMaxAccount($companyID,$branchID,$loginID){
		$this->db->where("companyID",$companyID);
		$this->db->where("branchID",$branchID);
		$this->db->where("loginID",$loginID);
		$this->db->select_max('accountID');
		$r 			= $this->db->get('tb_journal_entry_detail_summary')->result();
		$accountID 	= $r[0]->accountID;
		
		if ($accountID == null)
			return null;
		else
			return $accountID;
   }
   function getInfoAccount($companyID,$branchID,$loginID,$accountID){
		$this->db->select("e.debit,e.credit");
		$this->db->from("tb_journal_entry_detail_summary e");
		$this->db->where("e.companyID",$companyID);	
		$this->db->where("e.branchID",$branchID);	
		$this->db->where("e.loginID",$loginID);	
		$this->db->where("e.accountID",$accountID);	
		
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
   function clearCycle($companyID,$periodID,$cycleID){
		$data["debit"]	= 0;
		$data["credit"]	= 0;
		$this->db->where('companyID', $companyID);
		$this->db->where('componentPeriodID', $periodID);	
		$this->db->where('componentCycleID', $cycleID);	
		$this->db->update('tb_accounting_balance', $data);
		return $this->db->affected_rows(); 
   }
   function deleteJournalEntryDetailSummary($companyID,$branchID,$loginID){		
		$this->db->where('companyID', $companyID);
		$this->db->where('branchID', $branchID);	
		$this->db->where('loginID', $loginID);	
		$this->db->delete('tb_journal_entry_detail_summary');
		return $this->db->affected_rows(); 
		
   } 
   function setAccountBalance($companyID,$branchID,$loginID,$cycleID,$periodID,$componentAccountID){
		$sql = "
		INSERT INTO tb_accounting_balance (componentCycleID,componentPeriodID,companyID,componentID,accountID,branchID,balance,debit,credit,classID,isActive)
		SELECT 
			$cycleID,
			$periodID,
			$companyID,
			$componentAccountID,
			a.accountID,
			$branchID,
			0 AS balance,
			0 as debit,
			0 as credit,
			0 as classID,
			1 AS isActive
		FROM 
			tb_account a
		WHERE  
			a.companyID = $companyID and 
			a.accountID NOT IN (SELECT accountID FROM tb_accounting_balance where companyID = $companyID and componentPeriodID = $periodID and componentCycleID = $cycleID and isActive = 1) AND 
			a.isActive = 1;
		";
		
		$r 	= $this->db->query($sql);		
	
   }
   function setJournalSummary($companyID,$branchID,$loginID,$cycleID,$journalTypeClosed){
		$sql = "
		INSERT INTO tb_journal_entry_detail_summary(companyID,branchID,loginID,journalEntryID,accountID,parentAccountID,debit,credit)
		SELECT 
			$companyID,
			$branchID,
			$loginID,
			je.journalEntryID,
			a.accountID,
			a.parentAccountID,
			sum(jed.debit),
			sum(jed.credit)
		FROM
			tb_journal_entry je 
			inner join tb_journal_entry_detail jed on 
					je.journalEntryID = jed.journalEntryID and je.companyID = jed.companyID 
			inner join tb_workflow_stage ws on
					je.statusID = ws.workflowStageID 
			inner join tb_account a on 
					jed.accountID = a.accountID 
		WHERE
			je.companyID = $companyID and 
			je.accountingCycleID = $cycleID and 		
			je.isActive = 1 and 
			jed.isActive = 1 and 
			je.journalTypeID != $journalTypeClosed and 
			(jed.debit + jed.credit)  > 0  
		group by
			accountID;
		";
		
		$r 	= $this->db->query($sql);		
		
	}
	
}
?>
<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Customer_Credit_Amortization_Model extends CI_Model  {
   function __construct(){
      parent::__construct();
   }
   function update($creditAmortizationID,$data){
		$this->db->where('creditAmortizationID', $creditAmortizationID);	
		$this->db->update('tb_customer_credit_amoritization', $data);
		return $this->db->affected_rows(); 
   }
   function delete($creditAmortizationID){		
  		$data["isActive"] = 0;
		$this->db->where('creditAmortizationID', $creditAmortizationID);	
		$this->db->update('tb_customer_credit_amoritization', $data);
		return $this->db->affected_rows(); 
   } 
   function insert($data){
		$result			 		= $this->db->insert('tb_customer_credit_amoritization', $data);
		$autoIncrement			= $this->db->insert_id(); 		
		return $result ? $autoIncrement : $result; 
   }   
   function get_rowByPK($creditAmortizationID){
	    $this->db->select("creditAmortizationID, customerCreditDocumentID, balanceStart,dateApply, interest, capital, `share`, balanceEnd, remaining, dayDelay, note, statusID, isActive,shareCapital");
		$this->db->from("tb_customer_credit_amoritization i");		
		$this->db->where("i.creditAmortizationID",$creditAmortizationID);
		$this->db->where("i.isActive",1);		
		
		//Ejecutar Consulta
		$recordSet = $this->db->get();
		
		//Obtener errores
		if($this->db->_error_message())  
		return null; 		
		
		if($recordSet->num_rows() == 0)
		return null;
		
		//Resultado
		return  $recordSet->row();
   }
   function get_rowByDocument($customerCreditDocumentID){    
		$this->db->select("creditAmortizationID, customerCreditDocumentID,balanceStart, dateApply, interest, capital, `share`, balanceEnd, remaining, dayDelay, note, statusID, isActive,shareCapital");
		$this->db->from("tb_customer_credit_amoritization i");		
		$this->db->where("i.customerCreditDocumentID",$customerCreditDocumentID);
		$this->db->where("i.isActive",1);		
		$this->db->order_by("i.creditAmortizationID", "asc"); 
		
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
   function get_rowByDocumentAndVinculable($customerCreditDocumentID){    
		
		$this->db->select("i.creditAmortizationID, i.customerCreditDocumentID,i.balanceStart, i.dateApply, i.interest, i.capital, i.`share`, i.balanceEnd, i.remaining, i.dayDelay, i.note, i.statusID, i.isActive,i.shareCapital");
		$this->db->from("tb_customer_credit_amoritization i");		
		$this->db->join("tb_workflow_stage ws","i.statusID = ws.workflowStageID");		
		$this->db->where("i.customerCreditDocumentID",$customerCreditDocumentID);
		$this->db->where("i.isActive",1);
		$this->db->where("ws.vinculable",1);
		$this->db->order_by("i.dateApply", "asc"); 

		
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
   function get_rowByDocumentAndNonVinculable($customerCreditDocumentID){    
		$this->db->select("i.creditAmortizationID, i.customerCreditDocumentID,i.balanceStart, i.dateApply, i.interest, i.capital, i.`share`, i.balanceEnd, i.remaining, i.dayDelay, i.note, i.statusID, i.isActive,i.shareCapital");
		$this->db->from("tb_customer_credit_amoritization i");		
		$this->db->join("tb_workflow_stage ws","i.statusID = ws.workflowStageID");		
		$this->db->where("i.customerCreditDocumentID",$customerCreditDocumentID);
		$this->db->where("i.isActive",1);
		$this->db->where("ws.vinculable",0);
		$this->db->order_by("i.creditAmortizationID", "asc"); 

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
   function get_rowShareLate($companyID){
	    $this->db->select("c.customerNumber,n.firstName,n.lastName,c.birthDate,ccd.documentNumber,ccd.currencyID,ccd.reportSinRiesgo,cca.dateApply,cca.remaining,cca.shareCapital");
		$this->db->from("tb_customer c");
		$this->db->join("tb_naturales n","n.entityID = c.entityID");		
		$this->db->join("tb_customer_credit_document ccd","c.entityID = ccd.entityID");		
		$this->db->join("tb_customer_credit_amoritization cca","ccd.customerCreditDocumentID = cca.customerCreditDocumentID");		
		$this->db->join("tb_workflow_stage cca_status","cca_status.workflowStageID = cca.statusID");
		$this->db->join("tb_workflow_stage ccd_status","ccd_status.workflowStageID = ccd.statusID");
		$this->db->where("c.companyID",$companyID);
		$this->db->where("ccd_status.vinculable",1);
		$this->db->where("c.isActive",1);
		$this->db->where("cca.remaining > ",0);
		$this->db->where("cca.dateApply < CURDATE()");
		
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
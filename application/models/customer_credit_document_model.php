<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Customer_Credit_Document_Model extends CI_Model  {
   function __construct(){
      parent::__construct();
   }
   function update($customerCreditDocumentID,$data){
		$this->db->where('customerCreditDocumentID', $customerCreditDocumentID);	
		$this->db->update('tb_customer_credit_document', $data);
		return $this->db->affected_rows(); 
   }
   function delete($customerCreditDocumentID){		
  		$data["isActive"] = 0;
		$this->db->where('customerCreditDocumentID', $customerCreditDocumentID);	
		$this->db->update('tb_customer_credit_document', $data);
		return $this->db->affected_rows(); 
   } 
   function insert($data){
		$result			 		= $this->db->insert('tb_customer_credit_document', $data);
		$autoIncrement			= $this->db->insert_id(); 		
		return $result ? $autoIncrement : $result; 
   }   
   function get_rowByPK($customerCreditDocumentID){
	    $this->db->select("i.customerCreditDocumentID, i.companyID, i.entityID, i.customerCreditLineID,i.documentNumber, i.dateOn, i.amount, i.interes, i.term,i.exchangeRate, i.reference1, i.reference2, i.reference3, i.statusID, i.isActive,i.balance,i.balanceProvicioned,i.currencyID,cur.name as currencyName,cur.simbol as currencySimbol,	(select sum(tccda.remaining) from tb_customer_credit_document tccd inner join tb_customer_credit_amoritization tccda on tccd.customerCreditDocumentID = tccda.customerCreditDocumentID where tccd.customerCreditDocumentID = i.customerCreditDocumentID)  as balanceNew,i.reportSinRiesgo ");
		$this->db->from("tb_customer_credit_document i");	
		$this->db->join("tb_currency cur","i.currencyID = cur.currencyID");
		$this->db->where("i.customerCreditDocumentID",$customerCreditDocumentID);
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
   function get_rowByEntity($companyID,$entityID){    
		$this->db->select("customerCreditDocumentID, companyID, entityID, customerCreditLineID,documentNumber, dateOn, amount, interes, term,exchangeRate, reference1, reference2, reference3, statusID, isActive,balance,i.currencyID,i.reportSinRiesgo");
		$this->db->from("tb_customer_credit_document i");		
		$this->db->where("i.companyID",$companyID);
		$this->db->where("i.entityID",$entityID);
		$this->db->where("i.isActive",1);		
		
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
   function get_rowByEntityApplied($companyID,$entityID){    
		$sql = "		
		select 
			i.customerCreditDocumentID, i.companyID, i.entityID, i.customerCreditLineID,i.documentNumber, 
			i.dateOn, i.amount, i.interes, i.term, i.exchangeRate, i.reference1, i.reference2, 
			i.reference3, i.statusID, i.isActive,i.balance,i.currencyID,
			i.reportSinRiesgo,
			sum(cc.remaining) as remaining
		from 
			tb_customer_credit_document i
			inner join tb_customer_credit_amoritization cc on cc.customerCreditDocumentID = i.customerCreditDocumentID
			inner join tb_workflow_stage a on a.workflowStageID = i.statusID 
		where 
			i.companyID = $companyID and 
			i.entityID = $entityID and 
			i.isActive = 1 and 
			a.aplicable= 1
		group by 
			i.customerCreditDocumentID, i.companyID, i.entityID, i.customerCreditLineID,i.documentNumber, 
			i.dateOn, i.amount, i.interes, i.term, i.exchangeRate, i.reference1, i.reference2, 
			i.reference3, i.statusID, i.isActive,i.balance,i.currencyID,
			i.reportSinRiesgo

		";
		$r 	= $this->db->query($sql);
		return $r->result();
		/*
		$this->db->select("i.customerCreditDocumentID, i.companyID, i.entityID, i.customerCreditLineID,i.documentNumber, i.dateOn, i.amount, i.interes, i.term, i.exchangeRate, i.reference1, i.reference2, i.reference3, i.statusID, i.isActive,i.balance,i.currencyID,i.reportSinRiesgo,cc.remaining");
		$this->db->from("tb_customer_credit_document i");		
		$this->db->join("tb_workflow_stage a","a.workflowStageID = i.statusID");		
		$this->db->join("tb_customer_credit_amoritization cc","cc.customerCreditDocumentID = i.customerCreditDocumentID");		
		$this->db->where("i.companyID",$companyID);
		$this->db->where("i.entityID",$entityID);
		$this->db->where("i.isActive",1);		
		$this->db->where("a.aplicable",1);		
		
		//Ejecutar Consulta
		$recordSet = $this->db->get();
		
		//Obtener errores
		if($this->db->_error_message())  
		return null; 
		
		if($recordSet->num_rows() == 0)
		return null;
		
		//Resultado
		return $recordSet->result();
		*/
   }
   
   function get_rowByEntityCreditLine($companyID,$entityID,$creditLineID){    
		$this->db->select("customerCreditDocumentID, companyID, entityID, customerCreditLineID,documentNumber, dateOn, amount, interes, term,exchangeRate, reference1, reference2, reference3, statusID, isActive,balance,i.currencyID,i.reportSinRiesgo");
		$this->db->from("tb_customer_credit_document i");		
		$this->db->where("i.companyID",$companyID);
		$this->db->where("i.entityID",$entityID);
		$this->db->where("i.customerCreditLineID",$creditLineID);
		$this->db->where("i.isActive",1);		
		
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
   function get_rowByDocument($companyID,$entityID,$documentNumber){
	    $this->db->select("customerCreditDocumentID, companyID, entityID, customerCreditLineID,documentNumber, dateOn, amount, interes, term,exchangeRate, reference1, reference2, reference3, statusID, isActive,balance,i.currencyID,i.reportSinRiesgo");
		$this->db->from("tb_customer_credit_document i");		
		$this->db->where("i.companyID",$companyID);
		$this->db->where("i.entityID",$entityID);
		$this->db->where("i.documentNumber",$documentNumber);
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
   
}
?>
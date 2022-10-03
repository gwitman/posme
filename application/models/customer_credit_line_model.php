<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Customer_Credit_Line_Model extends CI_Model  {
   function __construct(){
      parent::__construct();
   }
   function update($customerCreditLineID,$data){
		$this->db->where('customerCreditLineID', $customerCreditLineID);
		$this->db->update('tb_customer_credit_line', $data);
		return $this->db->affected_rows(); 
   }
   function insert($data){
		$result			 		= $this->db->insert('tb_customer_credit_line', $data);
		$autoIncrement			= $this->db->insert_id(); 		
		return $result ? $autoIncrement : $result; 
   }   
   function delete($customerCreditLineID){		
  		$data["isActive"] = 0;
		$this->db->where('customerCreditLineID', $customerCreditLineID);
		$this->db->update('tb_customer_credit_line', $data);
		return $this->db->affected_rows(); 
   } 
   function deleteWhereIDNotIn($companyID,$branchID,$entityID,$listCustomerCreditLineID){
		$data["isActive"] = 0;
		$this->db->where('companyID', $companyID);
		$this->db->where('branchID', $branchID);	
		$this->db->where('entityID', $entityID);	
		$this->db->where_not_in('customerCreditLineID', $listCustomerCreditLineID);	
		$this->db->update('tb_customer_credit_line', $data);
		return $this->db->affected_rows(); 
   }
   function get_rowByEntityAndLine($companyID,$branchID,$entityID,$creditLineID){    
		$this->db->select("i.customerCreditLineID, i.companyID, i.branchID, i.entityID, i.creditLineID, i.accountNumber, i.currencyID, i.limitCredit, i.balance, i.interestYear, i.interestPay, i.totalPay, i.totalDefeated, i.dateOpen, i.periodPay, i.dateLastPay, i.term, i.note, i.statusID, i.isActive,cr.name as currencyName,i.typeAmortization");
		$this->db->from("tb_customer_credit_line i");
		$this->db->join("tb_currency cr","i.currencyID = cr.currencyID");
		$this->db->where("i.companyID",$companyID);
		$this->db->where("i.branchID",$branchID);
		$this->db->where("i.entityID",$entityID);
		$this->db->where("i.creditLineID",$creditLineID);
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
   function get_rowByEntity($companyID,$branchID,$entityID){    
		$this->db->select("i.customerCreditLineID, i.companyID, i.branchID, i.entityID, i.creditLineID, i.accountNumber, i.currencyID, i.limitCredit, i.balance, i.interestYear, i.interestPay, i.totalPay, i.totalDefeated, i.dateOpen, i.periodPay, i.dateLastPay, i.term, i.note, i.statusID, i.isActive,cl.name as line,ws.name as statusName,cr.name as currencyName,i.typeAmortization,ci3.name as typeAmortizationLabel,ci2.name as periodPayLabel");
		$this->db->from("tb_customer_credit_line i");		
		$this->db->join("tb_credit_line cl","i.creditLineID = cl.creditLineID");
		$this->db->join("tb_workflow_stage ws","ws.workflowStageID = i.statusID");
		$this->db->join("tb_currency cr","i.currencyID = cr.currencyID");
		$this->db->join("tb_catalog_item ci2","i.periodPay = ci2.catalogItemID");
		$this->db->join("tb_catalog_item ci3","i.typeAmortization = ci3.catalogItemID");
		$this->db->where("i.companyID",$companyID);
		$this->db->where("i.branchID",$branchID);
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
   function get_rowByPK($customerCreditLineID){    
		$this->db->select("customerCreditLineID, companyID, branchID, entityID, creditLineID, accountNumber, currencyID, limitCredit, balance, interestYear, interestPay, totalPay, totalDefeated, dateOpen, periodPay, dateLastPay, term, note, statusID, isActive,typeAmortization");
		$this->db->from("tb_customer_credit_line i");		
		$this->db->where("i.customerCreditLineID",$customerCreditLineID);
		$this->db->where("i.isActive",1);
		
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
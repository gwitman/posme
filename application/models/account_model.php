<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Account_Model extends CI_Model  {
   function __construct(){
      parent::__construct();
   } 
  function delete($companyID,$accountID){		
  		$data["isActive"] = 0;
		$this->db->where('companyID', $companyID);
		$this->db->where('accountID', $accountID);	
		$this->db->update('tb_account', $data);
		return $this->db->affected_rows(); 
   } 
   function update($companyID,$accountID,$data){		
		$this->db->where('companyID', $companyID);
		$this->db->where('accountID', $accountID);	
		$this->db->update('tb_account', $data);
		return $this->db->affected_rows(); 
   }
   function insert($data){	
		$result			 		= $this->db->insert('tb_account', $data);
		$autoIncrement			= $this->db->insert_id(); 		
		return $result ? $autoIncrement : $result; 
		 
   }
   function get_isParent($companyID,$accountID){
		$this->db->where('isActive', 1);
		$this->db->where('companyID', $companyID);
		$this->db->where('parentAccountID', $accountID);
		$this->db->from('tb_account');
   		return $this->db->count_all_results();
   }
   function get_countAccount($companyID){
		$this->db->where('isActive', 1);
		$this->db->where('companyID', $companyID);
		$this->db->from('tb_account');
   		return $this->db->count_all_results();
   }
   function getByAccountNumber($accountNumber,$companyID){
		$this->db->select("companyID,accountID,accountTypeID,accountLevelID,parentAccountID,accountNumber,name,description,isOperative,statusID,currencyID,createdBy,createdOn,createdIn,createdAt,isActive");
		$this->db->from("tb_account");
		$this->db->where("companyID",$companyID);
		$this->db->where("accountNumber",$accountNumber);
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
   function get_rowByPK($companyID,$accountID){    
		$this->db->select("companyID,accountID,accountTypeID,accountLevelID,parentAccountID,classID,accountNumber,name,description,isOperative,statusID,currencyID,createdBy,createdOn,createdIn,createdAt,isActive");
		$this->db->from("tb_account");
		$this->db->where("companyID",$companyID);
		$this->db->where("accountID",$accountID);
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
	function getByCompany($companyID){
		$this->db->select("companyID,accountID,accountTypeID,accountLevelID,parentAccountID,accountNumber,name,description,isOperative,statusID,currencyID,createdBy,createdOn,createdIn,createdAt,isActive");
		$this->db->from("tb_account");
		$this->db->where("companyID",$companyID);		
		$this->db->where("isActive",1);	
		$this->db->order_by("accountNumber","asc");
		
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
	function getByCompanyOperative($companyID){
		$this->db->select("companyID,accountID,accountTypeID,accountLevelID,parentAccountID,accountNumber,name,description,isOperative,statusID,currencyID,createdBy,createdOn,createdIn,createdAt,isActive");
		$this->db->from("tb_account");
		$this->db->where("companyID",$companyID);		
		$this->db->where("isActive",1);	
		$this->db->where("isOperative",1);
		$this->db->order_by("accountNumber","asc");
		
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
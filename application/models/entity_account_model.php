<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Entity_Account_Model extends CI_Model  {
   function __construct(){
      parent::__construct();
   }
   function update($entityAccountID,$data){
		$this->db->where('entityAccountID', $entityAccountID);
		$this->db->update('tb_entity_account', $data);
		return $this->db->affected_rows(); 
   }
   function insert($data){
		$result			 		= $this->db->insert('tb_entity_account', $data);
		$autoIncrement			= $this->db->insert_id(); 		
		return $result ? $autoIncrement : $result; 
   }   
   function delete($entityAccountID){		
  		$data["isActive"] = 0;
		$this->db->where('entityAccountID', $entityAccountID);
		$this->db->update('tb_entity_account', $data);
		return $this->db->affected_rows(); 
   } 
   function get_rowByEntity($companyID,$componentID,$componentItemID){    
		$this->db->select("entityAccountID, companyID, componentID, componentItemID, name, description, accountTypeID, currencyID, classID, balance, creditLimit, maxCredit, debitLimit, maxDebit, statusID, accountID, createdBy, createdOn, createdIn, createdAt,isActive");
		$this->db->from("tb_entity_account i");		
		$this->db->where("i.companyID",$companyID);
		$this->db->where("i.componentID",$componentID);
		$this->db->where("i.componentItemID",$componentItemID);
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
   function get_rowByPK($entityAccountID){    
		$this->db->select("entityAccountID, companyID, componentID, componentItemID, name, description, accountTypeID, currencyID, classID, balance, creditLimit, maxCredit, debitLimit, maxDebit, statusID, accountID, createdBy, createdOn, createdIn, createdAt,isActive");
		$this->db->from("tb_entity_account i");		
		$this->db->where("i.entityAccountID",$entityAccountID);
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
   function get_rowByAccountID($companyID,$componentID,$componentItemID,$accountID){    
		$this->db->select("entityAccountID, companyID, componentID, componentItemID, name, description, accountTypeID, currencyID, classID, balance, creditLimit, maxCredit, debitLimit, maxDebit, statusID, accountID, createdBy, createdOn, createdIn, createdAt,isActive");
		$this->db->from("tb_entity_account i");		
		$this->db->where("i.companyID",$companyID);
		$this->db->where("i.componentID",$componentID);
		$this->db->where("i.componentItemID",$componentItemID);
		$this->db->where("i.accountID",$accountID);
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
<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Account_Type_Model extends CI_Model  {
   function __construct(){
      parent::__construct();
   } 
  function delete($companyID,$accountTypeID){		
  		$data["isActive"] = 0;
		$this->db->where('companyID', $companyID);
		$this->db->where('accountTypeID', $accountTypeID);	
		$this->db->update('tb_account_type', $data);
		return $this->db->affected_rows(); 
   } 
   function update($companyID,$accountTypeID,$data){		
		$this->db->where('companyID', $companyID);
		$this->db->where('accountTypeID', $accountTypeID);	
		$this->db->update('tb_account_type', $data);
		return $this->db->affected_rows(); 
   }
   function insert($data){	
		$result			 		= $this->db->insert('tb_account_type', $data);
		$autoIncrement			= $this->db->insert_id(); 		
		return $result ? $autoIncrement : $result; 
		 
   }
   function get_countInAccount($companyID,$accountTypeID){
		$this->db->where('isActive', 1);
		$this->db->where('companyID', $companyID);
		$this->db->where('accountTypeID', $accountTypeID);
		$this->db->from('tb_account');
   		return $this->db->count_all_results();
   }
   function getByCompany($companyID){
	$this->db->select("companyID,accountTypeID,name,naturaleza,description,createdBy,createdAt,createdOn,createdIn,isActive");
		$this->db->from("tb_account_type");
		$this->db->where("companyID",$companyID);		
		$this->db->where("isActive",1);		
		
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
   function get_rowByPK($companyID,$accountTypeID){    
		$this->db->select("companyID,accountTypeID,name,naturaleza,description,createdBy,createdAt,createdOn,createdIn,isActive");
		$this->db->from("tb_account_type");
		$this->db->where("companyID",$companyID);
		$this->db->where("accountTypeID",$accountTypeID);
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
  
}
?>
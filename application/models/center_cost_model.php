<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Center_Cost_Model extends CI_Model  {
   function __construct(){
      parent::__construct();
   } 
  function delete($companyID,$classID){		
  		$data["isActive"] = 0;
		$this->db->where('companyID', $companyID);
		$this->db->where('classID', $classID);	
		$this->db->update('tb_center_cost', $data);
		return $this->db->affected_rows(); 
   } 
   function update($companyID,$classID,$data){		
		$this->db->where('companyID', $companyID);
		$this->db->where('classID', $classID);	
		$this->db->update('tb_center_cost', $data);
		return $this->db->affected_rows(); 
   }
   function insert($data){	
		$result			 		= $this->db->insert('tb_center_cost', $data);
		$autoIncrement			= $this->db->insert_id(); 		
		return $result ? $autoIncrement : $result; 
		 
   }
   function getByClassNumber($classNumber,$companyID){
		$this->db->select("classID,companyID,accountLevelID,parentAccountID,parentClassID,number,description,isActive,createdBy,createdOn,createdIn,createdAt");
		$this->db->from("tb_center_cost");
		$this->db->where("companyID",$companyID);
		$this->db->where("number",$classNumber);
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
   function get_rowByPK($companyID,$classID){    
		$this->db->select("classID,companyID,accountLevelID,parentAccountID,parentClassID,number,description,isActive,createdBy,createdOn,createdIn,createdAt");
		$this->db->from("tb_center_cost");
		$this->db->where("companyID",$companyID);
		$this->db->where("classID",$classID);
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
		$this->db->select("classID,companyID,accountLevelID,parentAccountID,parentClassID,number,description,isActive,createdBy,createdOn,createdIn,createdAt");
		$this->db->from("tb_center_cost");
		$this->db->where("companyID",$companyID);
		$this->db->where("isActive",1);		
		$this->db->order_by("number","asc");
		
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
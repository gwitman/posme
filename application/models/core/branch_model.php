<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Branch_Model extends CI_Model  {
   function __construct(){
      parent::__construct();
   }  
   function get_rowByPK($companyID,$branchID){    
		$this->db->select("companyID,branchID,name,createdOn");
		$this->db->from("tb_branch");
		$this->db->where("companyID",$companyID);
		$this->db->where("branchID",$branchID);
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
	$this->db->select("companyID, branchID, name, createdOn, isActive");
		$this->db->from("tb_branch");
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
}
?>
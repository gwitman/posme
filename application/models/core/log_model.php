<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Log_Model extends CI_Model  {
   function __construct(){
      parent::__construct();
   }  
   function get_rowByPK($companyID,$branchID,$loginID,$token){    
		$this->db->select("logID,companyID,branchID,loginID,token,procedureName,code,description,createdOn");
		$this->db->from("tb_log");
		$this->db->where("companyID",$companyID);
		$this->db->where("branchID",$branchID);
		$this->db->where("loginID",$loginID);
		$this->db->where("token",$token);
		$this->db->order_by("createdOn", "desc");
		$this->db->limit(1);
		
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
   function get_rowByNameParameterOutput($companyID,$branchID,$loginID,$token,$nameParameterOutput){    
	$this->db->select("logID,companyID,branchID,loginID,token,procedureName,code,description,createdOn");
	$this->db->from("tb_log");
	$this->db->where("companyID",$companyID);
	$this->db->where("branchID",$branchID);
	$this->db->where("loginID",$loginID);
	$this->db->where("token",$token);
	$this->db->where("procedureName",$nameParameterOutput);
	$this->db->order_by("createdOn", "desc");
	$this->db->limit(1);
	
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
<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Company_Log_Model extends CI_Model  {
   function __construct(){
      parent::__construct();
   }
   function getInfo($companyID,$branchID,$loginID,$app){
		$this->db->select("companyLogID,companyID,branchID,loginID,createdOn,sourceName,description");
		$this->db->from("tb_company_log");
		$this->db->where("companyID",$companyID);		
		$this->db->where("branchID",$branchID);		
		$this->db->where("loginID",$loginID);		
		$this->db->where("sourceName",$app);		
		
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
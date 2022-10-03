<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Company_Model extends CI_Model  {
   function __construct(){
      parent::__construct();
   }  
   function get_rowByPK($companyID){    
		$this->db->select("companyID,name,createdOn,address");
		$this->db->from("tb_company");
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
		return $recordSet->row();
   }
   function get_rows(){    
		$this->db->select("companyID,name,createdOn,address");
		$this->db->from("tb_company");
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
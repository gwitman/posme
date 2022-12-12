<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Company_SubElement_Audit_Model extends CI_Model  {
   function __construct(){
      parent::__construct();
   }    
   function listElementAudit($companyID,$elementID){
		$this->db->select("x.companyID,x.elementID,x.subElementID,s.name");
		$this->db->from("tb_company_subelement_audit x ");
		$this->db->join('tb_subelement s','x.subElementID = s.subElementID');		
		$this->db->where("x.companyID",$companyID);	
		$this->db->where("x.elementID",$elementID);	
		
		//Ejecutar
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
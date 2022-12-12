<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Company_Default_Data_View_Model extends CI_Model  {
   function __construct(){
      parent::__construct();
   }  
   function get_rowBy_CCCT($companyID,$componentID,$callerID,$targetComponentID){    
		$this->db->select("companyID,componentID,callerID,dataViewID,targetComponentID");
		$this->db->from("tb_company_default_dataview");
		$this->db->where("companyID",$companyID);
		$this->db->where("componentID",$componentID);
		$this->db->where("callerID",$callerID);
		$this->db->where("targetComponentID",$targetComponentID);		
		
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
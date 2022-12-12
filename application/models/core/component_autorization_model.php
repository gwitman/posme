<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Component_Autorization_Model extends CI_Model  {
    function __construct(){
      parent::__construct();
    }
    function get_rowByCompanyID($companyID){   
		$this->db->select("ca.companyID,ca.componentAutorizationID,ca.name");
		$this->db->from("tb_component_autorization ca ");
		$this->db->where("ca.companyID",$companyID);
		
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
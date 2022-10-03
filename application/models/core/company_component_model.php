<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Company_Component_Model extends CI_Model  {
   function __construct(){
      parent::__construct();
   }  
   function get_rowByPK($companyID,$componentID){ 
		
		$this->db->select("companyID,componentID");
		$this->db->from("tb_company_component");
		$this->db->where("companyID",$companyID);
		$this->db->where("componentID",$componentID);
		
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
<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Company_Component_Flavor_Model extends CI_Model  {
   function __construct(){
      parent::__construct();
   }    
   function get_rowByCompanyAndComponentAndComponentItemID($companyID,$componentID,$componentItemID){
		$this->db->select("e.companyID,e.componentID,e.componentItemID,e.flavorID");
		$this->db->from("tb_company_component_flavor e");
		$this->db->where("e.companyID",$companyID);
		$this->db->where("e.componentID",$componentID);		
		$this->db->where("e.componentItemID",$componentItemID);		
		
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
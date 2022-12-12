<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Company_Parameter_Model extends CI_Model  {
   function __construct(){
      parent::__construct();
   }  
   function update($companyID,$parameterID,$data){
		$this->db->where('companyID', $companyID);
		$this->db->where('parameterID', $parameterID);	
		$this->db->update('tb_company_parameter', $data);		
		return $this->db->affected_rows(); 
   }
   function get_rowByParameterID_CompanyID($companyID,$parameterID){ 
		
		$this->db->select("companyID,parameterID,display,description,value,customValue");
		$this->db->from("tb_company_parameter");
		$this->db->where("companyID",$companyID);
		$this->db->where("parameterID",$parameterID);
		
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
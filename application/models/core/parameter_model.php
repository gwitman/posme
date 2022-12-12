<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Parameter_Model extends CI_Model  {
   function __construct(){
      parent::__construct();
   }  
   function get_rowByName($name){ 
		
		$this->db->select("parameterID,name,description,isEdited,isRequiered");
		$this->db->from("tb_parameter");
		$this->db->where("name",$name);		
		
		
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
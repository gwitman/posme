<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Component_Model extends CI_Model  {
   function __construct(){
      parent::__construct();
   }  
   function get_rowByName($name){    
		$this->db->select("componentID,name as componentName");
		$this->db->from("tb_component");
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
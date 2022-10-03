<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Sub_Element_Model extends CI_Model  {
   function __construct(){
      parent::__construct();
   }    
   function get_rowByNameAndElementID($elementID,$name){
		$this->db->select("e.elementID,e.subElementID,e.name,e.workflowID,e.catalogID");
		$this->db->from("tb_subelement e");
		$this->db->where("e.name",$name);
		$this->db->where("e.elementID",$elementID);		
		
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
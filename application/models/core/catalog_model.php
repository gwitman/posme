<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Catalog_Model extends CI_Model  {
   function __construct(){
      parent::__construct();
   }    
   function get_rowByCatalogID($catalogID){
		$this->db->select("e.catalogID,e.name,e.description,e.isActive");
		$this->db->from("tb_catalog e");
		$this->db->where("e.catalogID",$catalogID);	
		$this->db->where("e.isActive",1);	
		
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
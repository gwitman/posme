<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Catalog_Item_Model extends CI_Model  {
   function __construct(){
      parent::__construct();
   }    
   function get_rowByCatalogIDAndFlavorID($catalogID,$flavorID){
		$this->db->select("e.catalogID,e.catalogItemID,e.name,e.display,e.flavorID,e.description,e.sequence,e.parentCatalogID,e.parentCatalogItemID");
		$this->db->from("tb_catalog_item e");
		$this->db->where("e.catalogID",$catalogID);	
		$this->db->where("e.flavorID",$flavorID);	
		
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
   function get_rowByCatalogIDAndFlavorID_Parent($catalogID,$flavorID,$parentCatalogItemID){
		$this->db->select("e.catalogID,e.catalogItemID,e.name,e.display,e.flavorID,e.description,e.sequence,e.parentCatalogID,e.parentCatalogItemID");
		$this->db->from("tb_catalog_item e");
		$this->db->where("e.catalogID",$catalogID);	
		$this->db->where("e.flavorID",$flavorID);	
		$this->db->where("e.parentCatalogItemID",$parentCatalogItemID);
		
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
   function get_rowByCatalogItemID($catalogItemID){
		$this->db->select("e.catalogID,e.catalogItemID,e.name,e.display,e.flavorID,e.description,e.sequence,e.parentCatalogID,e.parentCatalogItemID");
		$this->db->from("tb_catalog_item e");
		$this->db->where("e.catalogItemID",$catalogItemID);	
		
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
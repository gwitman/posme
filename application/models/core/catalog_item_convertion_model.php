<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Catalog_Item_Convertion_Model extends CI_Model  {
   function __construct(){
      parent::__construct();
   }
   function get_default($companyID,$catalogID){
   		$this->db->select("companyID,componentID,catalogItemConvertionID,catalogID,catalogItemID,targetCatalogItemID,ratio,registerDate,isActive");
		$this->db->from("tb_catalog_item_convertion");
		$this->db->where("companyID",$companyID);		
		$this->db->where("catalogID",$catalogID);
		$this->db->where("isActive",1);
		$this->db->where("ratio",1);
		
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
   function get_rowByPK($companyID,$catalogID,$catalogItemIDSource,$catalogItemIDTarget){    
		$this->db->select("companyID,componentID,catalogItemConvertionID,catalogID,catalogItemID,targetCatalogItemID,ratio,registerDate,isActive");
		$this->db->from("tb_catalog_item_convertion");
		$this->db->where("companyID",$companyID);
		$this->db->where("catalogItemID",$catalogItemIDSource);
		$this->db->where("targetCatalogItemID",$catalogItemIDTarget);
		$this->db->where("catalogID",$catalogID);
		$this->db->where("isActive",1);
		
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
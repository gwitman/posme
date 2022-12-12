<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Element_Model extends CI_Model  {
   function __construct(){
      parent::__construct();
   }  
   function get_rowByComponentIDNotIn($componentID,$elementTypeID){
		$this->db->select("e.elementID,e.name as elementName");
		$this->db->from("tb_element e");
		$this->db->join("tb_component_element ce","e.elementID=ce.elementID");
		$this->db->where_not_in("ce.componentID",$componentID);
		$this->db->where("e.elementTypeID",$elementTypeID);
		
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
   function get_rowByName($name,$elementTypeID){
		$this->db->select("e.elementID,e.name,e.elementTypeID,e.columnAutoIncrement");
		$this->db->from("tb_element e");
		$this->db->where("e.name",$name);
		$this->db->where("e.elementTypeID",$elementTypeID);		
		
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
   function get_rowByTypeAndLayout($elementTypeID,$layoutID){
		$this->db->select("e.elementID,e.name as elementName");
		$this->db->from("tb_element e");
		$this->db->join("tb_component_element ce","e.elementID=ce.elementID");
		$this->db->join("tb_menu_element me","e.elementID=me.elementID");
		$this->db->where("me.typeMenuElementID",$layoutID);
		$this->db->where("e.elementTypeID",$elementTypeID);
		
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
   function get_rowByPK($componentID,$elementTypeID){ 
		$this->db->select("e.elementID,e.name as elementName");
		$this->db->from("tb_element e");
		$this->db->join("tb_component_element ce","e.elementID=ce.elementID");
		$this->db->where("ce.componentID",$componentID);
		$this->db->where("e.elementTypeID",$elementTypeID);
		
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
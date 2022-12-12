<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Menu_Element_Model extends CI_Model  {
   function __construct(){
      parent::__construct();
   }  
   function get_rowByCompanyIDyElementID($companyID,$elementID){ 	
				
		$this->db->select("x.companyID,x.elementID,x.menuElementID,x.parentMenuElementID,x.display,x.address,x.orden,x.icon,x.template,x.nivel");
		$this->db->from("tb_menu_element x");		
		$this->db->join("tb_element e","e.elementID = x.elementID");
		$this->db->join("tb_component_element ce","e.elementID = ce.elementID");
		$this->db->join("tb_company_component cco","ce.componentID = cco.componentID");
		$this->db->where("x.companyID",$companyID);
		
		$this->db->where("x.isActive",true);
		$this->db->where("cco.companyID",$companyID);
		$this->db->where_in("x.elementID",$elementID);
		$this->db->order_by("x.orden","asc");
		
		
		//Ejecutar
		$recordSet = $this->db->get();
		
		//Obtener errores
		if($this->db->_error_message())
		return null; 
		
		if($recordSet->num_rows() == 0)
		return null;
		
		//Resultado
		$result = $recordSet->result();
		log_message("error",$this->db->last_query());
		return $result;
		
   }
   function get_rowByCompanyID($companyID){ 	
		$this->db->select("tb_menu_element.companyID,tb_menu_element.elementID,tb_menu_element.menuElementID,tb_menu_element.parentMenuElementID,tb_menu_element.display,tb_menu_element.address,tb_menu_element.orden,tb_menu_element.icon,tb_menu_element.template,tb_menu_element.nivel");
		$this->db->from("tb_menu_element");
		$this->db->join("tb_element","tb_menu_element.elementID = tb_element.elementID");
		$this->db->where("tb_menu_element.companyID",$companyID);		
		
		$this->db->where("tb_menu_element.isActive",true);
		
		$this->db->order_by("tb_menu_element.orden","asc");  
		
		//Ejecutar
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
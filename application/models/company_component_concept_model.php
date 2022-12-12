<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Company_Component_Concept_Model extends CI_Model  {
   function __construct(){
      parent::__construct();
   }
   
   function insert($data){
		$result			 		= $this->db->insert('tb_company_component_concept', $data);
		return $result;
   }
   function update($companyID,$componentID,$componentItemID,$name,$data){
		$this->db->where('companyID', $companyID);
		$this->db->where('componentID', $componentID);	
		$this->db->where('componentItemID', $componentItemID);	
		$this->db->where('name', $name);	
		$this->db->update('tb_company_component_concept', $data);
		return $this->db->affected_rows(); 
   }
   function get_rowByPK($companyID,$componentID,$componentItemID,$name){    
		$this->db->select("companyID,componentID,componentItemID, name,valueIn,valueOut");
		$this->db->from("tb_company_component_concept td");
		$this->db->where("td.companyID",$companyID);
		$this->db->where("td.componentID",$componentID);		
		$this->db->where("td.componentItemID",$componentItemID);		
		$this->db->where("td.name",$name);	
		
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
   function get_rowByComponentItemID($companyID,$componentID,$componentItemID){
		
		$this->db->select("companyID,componentID,componentItemID, name,valueIn,valueOut");
		$this->db->from("tb_company_component_concept td");
		$this->db->where("td.companyID",$companyID);
		$this->db->where("td.componentID",$componentID);		
		$this->db->where("td.componentItemID",$componentItemID);		
		
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
   function deleteWhereComponentItemID($companyID,$componentID,$componentItemID){
		$this->db->where('companyID', $companyID);
		$this->db->where('componentID', $componentID);	
		$this->db->where('componentItemID', $componentItemID);			
		$this->db->delete('tb_company_component_concept');
		return $this->db->affected_rows(); 
   }
}
?>
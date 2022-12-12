<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class ItemCategory_Model extends CI_Model  {
   function __construct(){
      parent::__construct();
   }
   function delete($companyID,$itemCategoryID){
		$data["isActive"] = 0;
		$this->db->where('companyID', $companyID);
		$this->db->where('inventoryCategoryID', $itemCategoryID);	
		$this->db->update('tb_item_category', $data);
		return $this->db->affected_rows(); 
   }
   function insert($data){
		$result			 		= $this->db->insert('tb_item_category', $data);
		$autoIncrement			= $this->db->insert_id(); 		
		return $result ? $autoIncrement : $result; 
   }
   function update($companyID,$itemCategoryID,$data){
		$this->db->where('companyID', $companyID);
		$this->db->where('inventoryCategoryID', $itemCategoryID);
		$this->db->update('tb_item_category', $data);
		return $this->db->affected_rows(); 
   }
   function getByCompany($companyID){
		$this->db->select("companyID,branchID,inventoryCategoryID,name,description,isActive");
		$this->db->from("tb_item_category");
		$this->db->where("companyID",$companyID);		
		$this->db->where("isActive",1);		
		
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
   function getByPK($companyID,$itemCategoryID){
		$this->db->select("companyID,branchID,inventoryCategoryID,name,description,isActive");
		$this->db->from("tb_item_category");
		$this->db->where("companyID",$companyID);		
		$this->db->where("isActive",1);		
		$this->db->where("inventoryCategoryID",$itemCategoryID);		
		
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
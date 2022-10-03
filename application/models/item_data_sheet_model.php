<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Item_Data_Sheet_Model extends CI_Model  {
   function __construct(){
      parent::__construct();
   }
   function update($itemDataSheetID,$data){
		$this->db->where('itemDataSheetID', $itemDataSheetID);	
		$this->db->update('tb_item_data_sheet', $data);
		return $this->db->affected_rows(); 
   }
   function delete($itemDataSheetID){		
  		$data["isActive"] = 0;
		$this->db->where('itemDataSheetID', $itemDataSheetID);
		$this->db->update('tb_item_data_sheet', $data);
		return $this->db->affected_rows(); 
   } 
   function insert($data){
		$result			 		= $this->db->insert('tb_item_data_sheet', $data);
		$autoIncrement			= $this->db->insert_id(); 		
		return $result ? $autoIncrement : $result; 
   }
   function get_rowByPK($itemDataSheetID){
		$this->db->select("i.itemDataSheetID,i.itemID,i.version,i.statusID,i.name,i.description,i.createdOn,i.createdBy,i.createdIn,i.createdAt,i.isActive");
		$this->db->from("tb_item_data_sheet i");		
		$this->db->where("i.itemDataSheetID",$itemDataSheetID);
		$this->db->where("i.isActive",1);		
		
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
   function get_rowByItemID($itemID){    
		$this->db->select("i.itemDataSheetID,i.itemID,i.version,i.statusID,i.name,i.description,i.createdOn,i.createdBy,i.createdIn,i.createdAt,i.isActive");
		$this->db->from("tb_item_data_sheet i");		
		$this->db->where("i.itemID",$itemID);
		$this->db->where("i.isActive",1);
		$this->db->order_by("i.version","desc");
		$this->db->limit(1);
		
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
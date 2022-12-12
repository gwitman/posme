<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Item_Data_Sheet_Detail_Model extends CI_Model  {
   function __construct(){
      parent::__construct();
   }
   function update($itemDataSheetDetailID,$data){
		$this->db->where('itemDataSheetDetailID', $itemDataSheetDetailID);
		$this->db->update('tb_item_data_sheet_detail', $data);

		return $this->db->affected_rows(); 
   }
   function delete($itemDataSheetDetailID){		
  		$data["isActive"] = 0;
		$this->db->where('itemDataSheetDetailID', $itemDataSheetDetailID);
		$this->db->update('tb_item_data_sheet_detail', $data);
		return $this->db->affected_rows(); 
   } 
   function deleteWhereDataSheet($itemDataSheetID){
		$data["isActive"] = 0;
		$this->db->where('itemDataSheetID', $itemDataSheetID);
		$this->db->update('tb_item_data_sheet_detail', $data);
		return $this->db->affected_rows(); 
	}
	function deleteWhereIDNotIn($itemDataSheetID,$listDSD_ID){
		$data["isActive"] = 0;
		$this->db->where('itemDataSheetID', $itemDataSheetID);
		$this->db->where_not_in('itemDataSheetDetailID', $listDSD_ID);
		$this->db->update('tb_item_data_sheet_detail', $data);
		return $this->db->affected_rows(); 
	}

   function insert($data){
		$result			 		= $this->db->insert('tb_item_data_sheet_detail', $data);
		$autoIncrement			= $this->db->insert_id(); 		
		return $result ? $autoIncrement : $result; 
   }
   function get_rowByPK($itemDataSheetDetailID){
		$this->db->select("i.itemDataSheetDetailID,i.itemDataSheetID,i.itemID,i.quantity,i.relatedItemID,i.isActive ,tm.itemNumber,tm.name ");
		$this->db->from("tb_item_data_sheet_detail i");
        $this->db->join("tb_item tm","i.itemID = tm.itemID");
		$this->db->where("i.itemDataSheetDetailID",$itemDataSheetDetailID);
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
   function get_rowByItemDataSheet($itemDataSheetID){    
		$this->db->select("i.itemDataSheetDetailID,i.itemDataSheetID,i.itemID,i.quantity,i.relatedItemID,i.isActive,tm.itemNumber,tm.name ");
		$this->db->from("tb_item_data_sheet_detail i");		
        $this->db->join("tb_item tm","i.itemID = tm.itemID");
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
		return $recordSet->result();
   }
}
?>
<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class ItemWarehouse_Model extends CI_Model  {
   function __construct(){
      parent::__construct();
   }
   function deleteWhereIDNotIn($companyID,$itemID,$listWarehouseID){
		$this->db->where('companyID', $companyID);
		$this->db->where('itemID', $itemID);	
		$this->db->where_not_in('warehouseID', $listWarehouseID);	
		$this->db->where('quantity',0);
		$this->db->delete('tb_item_warehouse');
		return $this->db->affected_rows(); 
   }
   function update($companyID,$itemID,$warehouseID,$data){
		$this->db->where('companyID', $companyID);
		$this->db->where('warehouseID', $warehouseID);
		$this->db->where('itemID', $itemID);	
		$this->db->update('tb_item_warehouse', $data);
		return $this->db->affected_rows(); 
   }
   function insert($data){
		$result			 		= $this->db->insert('tb_item_warehouse', $data);
		return $this->db->affected_rows(); 		
   }
   function getByWarehouse($companyID,$warehouseID){
		$this->db->select("i.itemNumber as CODIGO,i.name AS PRODUCTO,ci.display as UM");
		$this->db->from("tb_item i");
		$this->db->join("tb_item_warehouse w","i.itemID = w.itemID");
		$this->db->join("tb_catalog_item  ci","i.unitMeasureID = ci.catalogItemID");
		$this->db->where("i.companyID",$companyID);
		$this->db->where("w.warehouseID",$warehouseID);		
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
   function get_rowLowMinimus($companyID){
		$this->db->select("i.itemNumber,i.name as itemName,iw.quantity,iw.quantityMin,w.number as warehouseNumber,w.name as warehouseName");
		$this->db->from("tb_item_warehouse iw");
		$this->db->join("tb_warehouse w","iw.warehouseID = w.warehouseID");
		$this->db->join("tb_item i","i.itemID = iw.itemID");
		$this->db->where("i.companyID",$companyID);
		$this->db->where("i.isActive",1);
		$this->db->where("w.isActive",1);
		$this->db->where("iw.quantity < iw.quantityMin");

		
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
   function get_rowByItemID($companyID,$itemID){
		$this->db->select("i.companyID, i.branchID, i.warehouseID, i.itemID, i.quantity, i.quantityMax, i.quantityMin,w.name as warehouseName");
		$this->db->from("tb_item_warehouse i");
		$this->db->join("tb_warehouse w","i.warehouseID = w.warehouseID");
		$this->db->where("i.companyID",$companyID);
		$this->db->where("i.itemID",$itemID);		
		
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
   function getByPK($companyID,$itemID,$warehouseID){
		$this->db->select("i.companyID, i.branchID, i.warehouseID, i.itemID, i.quantity, i.quantityMax, i.quantityMin,w.name as warehouseName");
		$this->db->from("tb_item_warehouse i");
		$this->db->join("tb_warehouse w","i.warehouseID = w.warehouseID");
		$this->db->where("i.companyID",$companyID);
		$this->db->where("i.itemID",$itemID);		
		$this->db->where("i.warehouseID",$warehouseID);		
		
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
   function warehouseIsEmpty($companyID,$warehouseID){
		$this->db->where('companyID', $companyID);
		$this->db->where('warehouseID', $warehouseID);
		$this->db->where('quantity > ',0);
		$this->db->from('tb_item_warehouse');
   		return $this->db->count_all_results();
   }
}
?>
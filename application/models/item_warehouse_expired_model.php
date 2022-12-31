<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Item_Warehouse_Expired_Model extends CI_Model  {
   function __construct(){
      parent::__construct();
   }
   function deleteByPk($companyID,$itemWarehouseExpiredID){		
		$this->db->where('itemWarehouseExpiredID', $itemWarehouseExpiredID);	
		$this->db->where('companyID',$companyID);
		$this->db->delete('tb_item_warehouse_expired');
		return $this->db->affected_rows(); 
   }
   function update($companyID,$itemWarehouseExpiredID,$data){
		$this->db->where('companyID', $companyID);
		$this->db->where('itemWarehouseExpiredID', $itemWarehouseExpiredID);
		$this->db->update('tb_item_warehouse_expired', $data);
		return $this->db->affected_rows(); 
   }
   function insert($data){
		$result			 		= $this->db->insert('tb_item_warehouse_expired', $data);
		return $this->db->affected_rows(); 		
   }
   function getBy_ItemIDAndWarehouse($companyID,$warehouseID,$itemID){
		$this->db->select("i.itemWarehouseExpiredID,i.warehouseID,i.itemID,i.companyID,i.quantity,i.lote,i.dateExpired");
		$this->db->from("tb_item_warehouse_expired i");
		$this->db->where("i.companyID",$companyID);
		$this->db->where("i.warehouseID",$warehouseID);		
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
   function getBy_ItemIDAndWarehouseAndLote($companyID,$warehouseID,$itemID,$lote){
		$this->db->select("i.itemWarehouseExpiredID,i.warehouseID,i.itemID,i.companyID,i.quantity,i.lote,i.dateExpired");
		$this->db->from("tb_item_warehouse_expired i");
		$this->db->where("i.companyID",$companyID);
		$this->db->where("i.warehouseID",$warehouseID);		
		$this->db->where("i.itemID",$itemID);		
		$this->db->where("i.lote",$lote);
		
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
   function getBy_ItemIDAndWarehouseAndLoteAndExpired($companyID,$warehouseID,$itemID,$lote,$expired){
		$this->db->select("i.itemWarehouseExpiredID,i.warehouseID,i.itemID,i.companyID,i.quantity,i.lote,i.dateExpired");
		$this->db->from("tb_item_warehouse_expired i");
		$this->db->where("i.companyID",$companyID);
		$this->db->where("i.warehouseID",$warehouseID);		
		$this->db->where("i.itemID",$itemID);		
		$this->db->where("i.lote",$lote);
		$this->db->where("i.dateExpired",$expired);
		
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
   function getByPK($companyID,$itemWarehouseExpiredID){
		$this->db->select("i.itemWarehouseExpiredID,i.warehouseID,i.itemID,i.companyID,i.quantity,i.lote,i.dateExpired");
		$this->db->from("tb_item_warehouse_expired i");
		$this->db->where("i.companyID",$companyID);
		$this->db->where("i.itemWarehouseExpiredID",$itemWarehouseExpiredID);		
		
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
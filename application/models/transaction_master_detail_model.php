<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Transaction_Master_Detail_Model extends CI_Model  {
   function __construct(){
      parent::__construct();
   }
   
   function insert($data){
		$result			 		= $this->db->insert('tb_transaction_master_detail', $data);
		$autoIncrement			= $this->db->insert_id(); 		
		return $result ? $autoIncrement : $result; 
   }
   function update($companyID,$transactionID,$transactionMasterID,$transactionMasterDetailID,$data){
		$this->db->where('companyID', $companyID);
		$this->db->where('transactionID', $transactionID);	
		$this->db->where('transactionMasterID', $transactionMasterID);	
		$this->db->where('transactionMasterDetailID', $transactionMasterDetailID);	
		$this->db->update('tb_transaction_master_detail', $data);
		return $this->db->affected_rows(); 
   }
   function get_rowByPK($companyID,$transactionID,$transactionMasterID,$transactionMasterDetailID,$componentID=33){    
   
		if($componentID == 33 /*33 component:tb_item*/){
			$this->db->select("td.companyID, td.transactionID, td.transactionMasterID, td.transactionMasterDetailID, td.componentID, td.componentItemID, td.promotionID, td.amount, td.cost, td.quantity, td.discount, td.unitaryAmount, td.unitaryCost, td.unitaryPrice, td.reference1, td.reference2, td.reference3,td.reference4,td.reference5,td.reference6,td.reference7, td.catalogStatusID, td.inventoryStatusID, td.isActive, td.quantityStock, td.quantiryStockInTraffic, td.quantityStockUnaswared, td.remaingStock, td.expirationDate, td.inventoryWarehouseSourceID, td.inventoryWarehouseTargetID,i.itemNumber,i.name as itemName,ci.name as unitMeasureName,td.descriptionReference,td.exchangeRateReference");
			$this->db->from("tb_transaction_master_detail td");
			$this->db->join("tb_item i","td.companyID = i.companyID and td.componentItemID = i.itemID");
			$this->db->join("tb_catalog_item ci","i.unitMeasureID = ci.catalogItemID");
			$this->db->where("td.companyID",$companyID);
			$this->db->where("td.transactionID",$transactionID);		
			$this->db->where("td.transactionMasterID",$transactionMasterID);		
			$this->db->where("td.transactionMasterDetailID",$transactionMasterDetailID);		
			$this->db->where("td.isActive",1);	
		}
		else if($componentID == 64 /*64 component:tb_transaction_master_share*/){
			$this->db->select("td.companyID, td.transactionID, td.transactionMasterID, td.transactionMasterDetailID, td.componentID, td.componentItemID, td.promotionID, td.amount, td.cost, td.quantity, td.discount, td.unitaryAmount, td.unitaryCost, td.unitaryPrice, td.reference1, td.reference2, td.reference3,td.reference4,td.reference5,td.reference6,td.reference7, td.catalogStatusID, td.inventoryStatusID, td.isActive, td.quantityStock, td.quantiryStockInTraffic, td.quantityStockUnaswared, td.remaingStock, td.expirationDate, td.inventoryWarehouseSourceID, td.inventoryWarehouseTargetID,td.descriptionReference,td.exchangeRateReference");
			$this->db->from("tb_transaction_master_detail td");
			$this->db->join("tb_customer_credit_document i","td.companyID = i.companyID and td.componentItemID = i.customerCreditDocumentID");
			$this->db->where("td.companyID",$companyID);
			$this->db->where("td.transactionID",$transactionID);		
			$this->db->where("td.transactionMasterID",$transactionMasterID);		
			$this->db->where("td.transactionMasterDetailID",$transactionMasterDetailID);		
			$this->db->where("td.isActive",1);			
		}	
		else {
			$this->db->select("td.companyID, td.transactionID, td.transactionMasterID, td.transactionMasterDetailID, td.componentID, td.componentItemID, td.promotionID, td.amount, td.cost, td.quantity, td.discount, td.unitaryAmount, td.unitaryCost, td.unitaryPrice, td.reference1, td.reference2, td.reference3,td.reference4,td.reference5,td.reference6,td.reference7, td.catalogStatusID, td.inventoryStatusID, td.isActive, td.quantityStock, td.quantiryStockInTraffic, td.quantityStockUnaswared, td.remaingStock, td.expirationDate, td.inventoryWarehouseSourceID, td.inventoryWarehouseTargetID,td.descriptionReference,td.exchangeRateReference");
			$this->db->from("tb_transaction_master_detail td");
		
			$this->db->where("td.companyID",$companyID);
			$this->db->where("td.transactionID",$transactionID);		
			$this->db->where("td.transactionMasterID",$transactionMasterID);		
			$this->db->where("td.transactionMasterDetailID",$transactionMasterDetailID);		
			$this->db->where("td.isActive",1);	
		}
		
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
   
   function get_rowByTransactionAndWarehouse($companyID,$transactionID,$transactionMasterID){
	    $this->db->select("w.companyID,w.branchID,w.warehouseID,w.itemID,w.quantity,w.cost,w.quantityMax,w.quantityMin,td.descriptionReference,td.exchangeRateReference");
	    $this->db->from("tb_transaction_master tm");
		$this->db->join("tb_transaction_master_detail td","tm.companyID = td.companyID and tm.transactionID = td.transactionID and tm.transactionMasterID = td.transactionMasterID");
		$this->db->join("tb_item i","td.companyID = i.companyID and td.componentItemID = i.itemID");		
		$this->db->join("tb_item_warehouse w","w.warehouseID = tm.sourceWarehouseID and w.itemID = i.itemID");
		$this->db->where("td.companyID",$companyID);
		$this->db->where("td.transactionID",$transactionID);		
		$this->db->where("td.transactionMasterID",$transactionMasterID);		
		$this->db->where("td.isActive",1);		
		
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
   function get_rowByTransaction($companyID,$transactionID,$transactionMasterID){
	   		
		$this->db->select("td.companyID, td.transactionID, td.transactionMasterID, td.transactionMasterDetailID, td.componentID, td.componentItemID, td.promotionID, td.amount, td.cost, td.quantity, td.discount, td.unitaryAmount, td.unitaryCost, td.unitaryPrice,td.tax1,td.tax2,td.tax3,td.tax4, td.reference1, td.reference2, td.reference3,td.reference4,td.reference5,td.reference6,td.reference7, td.catalogStatusID, td.inventoryStatusID, td.isActive, td.quantityStock, td.quantiryStockInTraffic, td.quantityStockUnaswared, td.remaingStock, td.expirationDate, td.inventoryWarehouseSourceID, td.inventoryWarehouseTargetID,i.itemNumber,i.name as itemName,ci.name as unitMeasureName,td.descriptionReference,td.exchangeRateReference");
		$this->db->from("tb_transaction_master_detail td");
		$this->db->join("tb_item i","td.companyID = i.companyID and td.componentItemID = i.itemID");
		$this->db->join("tb_catalog_item ci","i.unitMeasureID = ci.catalogItemID");
		$this->db->where("td.companyID",$companyID);
		$this->db->where("td.transactionID",$transactionID);		
		$this->db->where("td.transactionMasterID",$transactionMasterID);		
		$this->db->where("td.isActive",1);		
	
		//Ejecutar Consulta
		$recordSet = $this->db->get();
		log_message("ERROR",print_r($this->db->last_query(),true));
		//Obtener errores
		if($this->db->_error_message())  
		return null; 
		
		if($recordSet->num_rows() == 0)
		return null;
		
		//Resultado
		return $recordSet->result();
   }
   function get_rowByTransactionToShare($companyID,$transactionID,$transactionMasterID){
		$this->db->select("td.companyID, td.transactionID, td.transactionMasterID, td.transactionMasterDetailID, td.componentID, td.componentItemID, td.promotionID, td.amount, td.cost, td.quantity, td.discount, td.unitaryAmount, td.unitaryCost, td.unitaryPrice, td.reference1, td.reference2, td.reference3,td.reference4,td.reference5,td.reference6,td.reference7, td.catalogStatusID, td.inventoryStatusID, td.isActive, td.quantityStock, td.quantiryStockInTraffic, td.quantityStockUnaswared, td.remaingStock, td.expirationDate, td.inventoryWarehouseSourceID, td.inventoryWarehouseTargetID,td.descriptionReference,td.exchangeRateReference");
		$this->db->from("tb_transaction_master_detail td");
		$this->db->where("td.companyID",$companyID);
		$this->db->where("td.transactionID",$transactionID);		
		$this->db->where("td.transactionMasterID",$transactionMasterID);		
		$this->db->where("td.isActive",1);		
		
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
   function deleteWhereTM($companyID,$transactionID,$transactionMasterID){
		$data["isActive"] = 0;
		$this->db->where('companyID', $companyID);
		$this->db->where('transactionID', $transactionID);	
		$this->db->where('transactionMasterID', $transactionMasterID);			
		$this->db->update('tb_transaction_master_detail', $data);
		return $this->db->affected_rows(); 
   }
   function deleteWhereIDNotIn($companyID,$transactionID,$transactionMasterID,$listTMD_ID){
		$data["isActive"] = 0;
		$this->db->where('companyID', $companyID);
		$this->db->where('transactionID', $transactionID);	
		$this->db->where('transactionMasterID', $transactionMasterID);	
		$this->db->where_not_in('transactionMasterDetailID', $listTMD_ID);
		$this->db->update('tb_transaction_master_detail', $data);
		return $this->db->affected_rows(); 
   }
}
?>
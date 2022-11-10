<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');



class Item_Model extends CI_Model  {

   function __construct(){

      parent::__construct();

   }

   function update($companyID,$itemID,$data){

		$this->db->where('companyID', $companyID);

		$this->db->where('itemID', $itemID);	

		$this->db->update('tb_item', $data);

		return $this->db->affected_rows(); 

   }

   function delete($companyID,$itemID){		

  		$data["isActive"] = 0;

		$this->db->where('companyID', $companyID);

		$this->db->where('itemID', $itemID);	

		$this->db->update('tb_item', $data);

		return $this->db->affected_rows(); 

   } 

   function insert($data){

		$result			 		= $this->db->insert('tb_item', $data);

		$autoIncrement			= $this->db->insert_id(); 		

		return $result ? $autoIncrement : $result; 

   }

   function get_rowByCode($companyID,$itemNumber){

		$this->db->select("i.companyID, i.branchID, i.inventoryCategoryID, i.itemID, i.familyID, i.itemNumber, i.barCode, i.name, i.description, i.unitMeasureID, i.displayID, i.capacity, i.displayUnitMeasureID, i.defaultWarehouseID, i.quantity, i.quantityMax, i.quantityMin, i.cost, i.reference1, i.reference2, i.statusID, i.isPerishable, i.factorBox, i.factorProgram, i.createdIn, i.createdAt, i.createdBy, i.createdOn, i.isActive,i.isInvoiceQuantityZero ");

		$this->db->from("tb_item i");		

		$this->db->where("i.companyID",$companyID);

		$this->db->where("i.itemNumber",$itemNumber);

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

   function get_rowByPK($companyID,$itemID){    

		$this->db->select("i.companyID, i.branchID, i.inventoryCategoryID, i.itemID, i.familyID, i.itemNumber, i.barCode, i.name, i.description, i.unitMeasureID, i.displayID, i.capacity, i.displayUnitMeasureID, i.defaultWarehouseID, i.quantity, i.quantityMax, i.quantityMin, i.cost, i.reference1, i.reference2, i.statusID, i.isPerishable, i.factorBox, i.factorProgram, i.createdIn, i.createdAt, i.createdBy, i.createdOn, i.isActive,i.isInvoiceQuantityZero");

		$this->db->from("tb_item i");		

		$this->db->where("i.companyID",$companyID);

		$this->db->where("i.itemID",$itemID);

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
   
   function get_rowsByPK($companyID,$listItem){    

		$this->db->select("i.companyID, i.branchID, i.inventoryCategoryID, i.itemID, i.familyID, i.itemNumber, i.barCode, i.name, i.description, i.unitMeasureID, i.displayID, i.capacity, i.displayUnitMeasureID, i.defaultWarehouseID, i.quantity, i.quantityMax, i.quantityMin, i.cost, i.reference1, i.reference2, i.statusID, i.isPerishable, i.factorBox, i.factorProgram, i.createdIn, i.createdAt, i.createdBy, i.createdOn, i.isActive,i.isInvoiceQuantityZero");

		$this->db->from("tb_item i");		

		$this->db->where("i.companyID",$companyID);

		$this->db->where_in("i.itemID",$listItem);

		$this->db->where("i.isActive",1);		

		

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

   function get_rowByCompany($companyID){    

		$this->db->select("i.companyID, i.branchID, i.inventoryCategoryID, i.itemID, i.familyID, i.itemNumber, i.barCode, i.name, i.description, i.unitMeasureID, i.displayID, i.capacity, i.displayUnitMeasureID, i.defaultWarehouseID, i.quantity, i.quantityMax, i.quantityMin, i.cost, i.reference1, i.reference2, i.statusID, i.isPerishable, i.factorBox, i.factorProgram, i.createdIn, i.createdAt, i.createdBy, i.createdOn, i.isActive,i.isInvoiceQuantityZero");

		$this->db->from("tb_item i");		

		$this->db->where("i.companyID",$companyID);

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

   function getCount($companyID){

		$this->db->where('isActive', 1);

		$this->db->where('companyID', $companyID);

		$this->db->from('tb_item');

   		return $this->db->count_all_results();

   }

}

?>
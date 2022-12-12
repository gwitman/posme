<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Price_Model extends CI_Model  {
   function __construct(){
      parent::__construct();
   }
   function delete($companyID,$listPriceID){		
  		
		$this->db->where('companyID', $companyID);
		$this->db->where('listPriceID', $listPriceID);			
		$this->db->delete('tb_price');
		return $this->db->affected_rows(); 
   } 
   function insert($data){
		$result			 		= $this->db->insert('tb_price', $data);
		$autoIncrement			= $this->db->insert_id(); 		
		return $result ? $autoIncrement : $result; 
   }   
   
   function update($companyID,$listPriceID,$itemID,$typePriceID,$data){		
		$this->db->where('companyID', $companyID);
		$this->db->where('listPriceID', $listPriceID);	
		$this->db->where('itemID', $itemID);	
		$this->db->where('typePriceID', $typePriceID);	
		$this->db->update('tb_price', $data);
		return $this->db->affected_rows(); 
   }
   
   function get_rowByAll($companyID,$listPriceID){
		$this->db->select("i.companyID,i.listPriceID,i.itemID,i.priceID,i.typePriceID,i.percentage,i.price,ci.name as tipoPrice,it.itemNumber,it.name as itemName,it.cost");
		$this->db->from("tb_price i");
		$this->db->join("tb_item it","i.itemID = it.itemID");
		$this->db->join("tb_catalog_item ci","ci.catalogItemID = i.typePriceID");
		$this->db->where("i.companyID",$companyID);
		$this->db->where("i.listPriceID",$listPriceID);
		
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
   function get_rowByPK($companyID,$listPriceID,$itemID,$typePriceID){    
		$this->db->select("companyID,listPriceID,itemID,priceID,typePriceID,percentage,price");
		$this->db->from("tb_price i");		
		$this->db->where("i.companyID",$companyID);
		$this->db->where("i.listPriceID",$listPriceID);		
		$this->db->where("i.itemID",$itemID);		
		$this->db->where("i.typePriceID",$typePriceID);		
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
   function get_rowByItemID($companyID,$listPriceID,$itemID){    
	$this->db->select("companyID,listPriceID,itemID,priceID,typePriceID,percentage,price,c.name as nameTypePrice");
	$this->db->from("tb_price i");		
	$this->db->join("tb_catalog_item c","c.catalogItemID = i.typePriceID");
	$this->db->where("i.companyID",$companyID);
	$this->db->where("i.listPriceID",$listPriceID);		
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
   
   
}
?>
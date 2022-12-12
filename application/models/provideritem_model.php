<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class ProviderItem_Model extends CI_Model  {
   function __construct(){
      parent::__construct();
   }
   function deleteWhereItemID($companyID,$itemID){
		$this->db->where('companyID', $companyID);
		$this->db->where('itemID', $itemID);	
		$this->db->delete('tb_provider_item');
		return $this->db->affected_rows(); 
   }
   function deleteWhereItemIdyProviderId($companyID,$itemID,$providerID){
		$this->db->where('companyID', $companyID);
		$this->db->where('itemID', $itemID);	
		$this->db->where('entityID', $providerID);
		$this->db->delete('tb_provider_item');
		return $this->db->affected_rows(); 
   }
   function insert($data){
		$result			 		= $this->db->insert('tb_provider_item', $data);
		return $this->db->affected_rows(); 		
   }   
   function get_rowByItemID($companyID,$itemID){
		$this->db->select("ip.entityID,p.providerNumber,n.firstName,n.lastName,l.comercialName");
		$this->db->from("tb_provider_item ip");
		$this->db->join("tb_provider p","ip.entityID = p.entityID");
		$this->db->join("tb_legal l","p.entityID = l.entityID","left");
		$this->db->join("tb_naturales n","p.entityID = n.entityID","left");
		$this->db->where("ip.companyID",$companyID);
		$this->db->where("ip.itemID",$itemID);		
		
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
   function getByPK($companyID,$itemID,$providerID){
		$this->db->select("ip.entityID,p.providerNumber,n.firstName,n.lastName,l.comercialName");
		$this->db->from("tb_provider_item ip");
		$this->db->join("tb_provider p","ip.entityID = p.entityID");
		$this->db->join("tb_legal l","p.entityID = l.entityID","left");
		$this->db->join("tb_naturales n","p.entityID = n.entityID","left");
		$this->db->where("ip.companyID",$companyID);
		$this->db->where("ip.itemID",$itemID);		
		$this->db->where("ip.entityID",$providerID);		
		
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
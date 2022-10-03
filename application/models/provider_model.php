<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Provider_Model extends CI_Model  {
   function __construct(){
      parent::__construct();
   }
   function update($companyID,$branchID,$entityID,$data){
		$this->db->where('companyID', $companyID);
		$this->db->where('branchID', $branchID);	
		$this->db->where('entityID', $entityID);	
		$this->db->update('tb_provider', $data);
		return $this->db->affected_rows(); 
   }
   function delete($companyID,$branchID,$entityID){		
  		$data["isActive"] = 0;
		$this->db->where('companyID', $companyID);
		$this->db->where('branchID', $branchID);	
		$this->db->where('entityID', $entityID);	
		$this->db->update('tb_provider', $data);
		return $this->db->affected_rows(); 
   } 
   function insert($data){
		$result			 		= $this->db->insert('tb_provider', $data);
		$autoIncrement			= $this->db->insert_id(); 		
		return $result ? $autoIncrement : $result; 
   }   
   function get_rowByEntity($companyID,$entityID){    
		$this->db->select("companyID, branchID, entityID, providerNumber, numberIdentification, identificationTypeID, providerType, providerCategoryID, providerClasificationID, reference1, reference2, payConditionID, isLocal, countryID, stateID, cityID, address, currencyID, statusID, deleveryDay, deleveryDayReal, distancia, createdIn, createdBy, createdAt, createdOn, isActive");
		$this->db->from("tb_provider i");		
		$this->db->where("i.companyID",$companyID);
		$this->db->where("i.entityID",$entityID);
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
   }   function get_rowByCompany($companyID){		$this->db->select("p.entityID,p.companyID,p.providerNumber,p.numberIdentification,nat.firstName,nat.lastName");		$this->db->from("tb_provider p");		$this->db->join("tb_entity e","p.entityID = e.entityID");		$this->db->join("tb_naturales nat","p.entityID = nat.entityID");		$this->db->where("p.companyID",$companyID);		$this->db->where("p.isActive",1);						//Ejecutar Consulta		$recordSet = $this->db->get();				//Obtener errores		if($this->db->_error_message())  		return null; 				if($recordSet->num_rows() == 0)		return null;				//Resultado		return $recordSet->result();   }
   function get_rowByPK($companyID,$branchID,$entityID){    
		$this->db->select("companyID, branchID, entityID, providerNumber, numberIdentification, identificationTypeID, providerType, providerCategoryID, providerClasificationID, reference1, reference2, payConditionID, isLocal, countryID, stateID, cityID, address, currencyID, statusID, deleveryDay, deleveryDayReal, distancia, createdIn, createdBy, createdAt, createdOn, isActive");
		$this->db->from("tb_provider i");		
		$this->db->where("i.companyID",$companyID);
		$this->db->where("i.branchID",$branchID);
		$this->db->where("i.entityID",$entityID);
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
}
?>
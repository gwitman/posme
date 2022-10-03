<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Customer_Model extends CI_Model  {
   function __construct(){
      parent::__construct();
   }
   function update($companyID,$branchID,$entityID,$data){
		$this->db->where('companyID', $companyID);
		$this->db->where('branchID', $branchID);	
		$this->db->where('entityID', $entityID);	
		$this->db->update('tb_customer', $data);
		return $this->db->affected_rows(); 
   }
   function delete($companyID,$branchID,$entityID){		
  		$data["isActive"] = 0;
		$this->db->where('companyID', $companyID);
		$this->db->where('branchID', $branchID);	
		$this->db->where('entityID', $entityID);	
		$this->db->update('tb_customer', $data);
		return $this->db->affected_rows(); 
   } 
   function insert($data){
		$result			 		= $this->db->insert('tb_customer', $data);
		$autoIncrement			= $this->db->insert_id(); 		
		return $result ? $autoIncrement : $result; 
   }   
   function get_happyBirthDay($companyID){
	    $this->db->select("c.customerNumber,n.firstName,n.lastName,c.birthDate");
		$this->db->from("tb_customer c");
		$this->db->join("tb_naturales n","n.entityID = c.entityID");				
		$this->db->where("c.companyID",$companyID);
		$this->db->where("c.isActive",1);
		$this->db->where("c.birthDate <= CURDATE()");
		
		
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
   function get_rowByCode($companyID,$customerCode){
		
	    $this->db->select("companyID, branchID, entityID, customerNumber, identificationType, identification, countryID, stateID, cityID, location, address, currencyID, clasificationID, categoryID, subCategoryID, customerTypeID, birthDate, statusID, typePay, payConditionID, sexoID, reference1, reference2, createdIn, createdBy, createdOn, createdAt, isActive,typeFirm");
		$this->db->from("tb_customer i");		
		$this->db->where("i.companyID",$companyID);
		$this->db->where("i.customerNumber",$customerCode);		
		$this->db->where("i.isActive",1);		
		
		//Ejecutar Consulta
		$recordSet = $this->db->get();
		
		//Obtener errores
		if($this->db->_error_message())  
		return null; 		
		
		if($recordSet->num_rows() == 0)
		return null;
		
		//Resultado
		return  $recordSet->row();
   }
   function get_rowByCompany($companyID){
			
		$this->db->select("
		i.companyID, i.branchID, i.entityID, i.customerNumber, i.identificationType, i.identification, i.countryID, i.stateID, i.cityID, 
		i.location, i.address, i.currencyID, i.clasificationID, i.categoryID, i.subCategoryID, i.customerTypeID, i.birthDate, i.statusID, i.typePay, 
		i.payConditionID, i.sexoID, i.reference1, i.reference2, i.createdIn, i.createdBy, i.createdOn, i.createdAt, i.isActive,
		nat.firstName,nat.lastName,i.typeFirm
		");
		$this->db->from("tb_customer i");
		$this->db->join("tb_naturales nat","nat.entityID = i.entityID");				
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
		return  $recordSet->result();
	}
   function get_rowByEntity($companyID,$entityID){    
		$this->db->select("i.companyID, i.branchID, i.entityID, i.customerNumber, i.identificationType, i.identification, i.countryID, i.stateID, i.cityID, i.location, i.address, i.currencyID, i.clasificationID, i.categoryID, i.subCategoryID, i.customerTypeID, i.birthDate, i.statusID, i.typePay, i.payConditionID, i.sexoID, i.reference1, i.reference2, i.createdIn, i.createdBy, i.createdOn, i.createdAt, i.isActive, i.typeFirm, n.firstName,n.lastName");
		$this->db->from("tb_customer i");						$this->db->join("tb_naturales n","n.entityID = i.entityID");				
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
   }
   function get_rowByPK($companyID,$branchID,$entityID){    
		$this->db->select("companyID, branchID, entityID, customerNumber, identificationType, identification, countryID, stateID, cityID, location, address, currencyID, clasificationID, categoryID, subCategoryID, customerTypeID, birthDate, statusID, typePay, payConditionID, sexoID, reference1, reference2, createdIn, createdBy, createdOn, createdAt, isActive,i.typeFirm");
		$this->db->from("tb_customer i");		
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
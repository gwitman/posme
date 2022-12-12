<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Employee_Model extends CI_Model  {
   function __construct(){
      parent::__construct();
   }
   function update($companyID,$branchID,$entityID,$data){
		$this->db->where('companyID', $companyID);
		$this->db->where('branchID', $branchID);	
		$this->db->where('entityID', $entityID);	
		$this->db->update('tb_employee', $data);
		return $this->db->affected_rows(); 
   }
   function delete($companyID,$branchID,$entityID){		
  		$data["isActive"] = 0;
		$this->db->where('companyID', $companyID);
		$this->db->where('branchID', $branchID);	
		$this->db->where('entityID', $entityID);	
		$this->db->update('tb_employee', $data);
		return $this->db->affected_rows(); 
   } 
   function insert($data){
		$result			 		= $this->db->insert('tb_employee', $data);
		$autoIncrement			= $this->db->insert_id(); 		
		return $result ? $autoIncrement : $result; 
   }   
   function get_rowByPK($companyID,$branchID,$entityID){    
		$this->db->select("companyID, branchID, entityID, employeNumber, numberIdentification, identificationTypeID, socialSecurityNumber, address, countryID, stateID, cityID, departamentID, areaID, clasificationID, categoryID, reference1, reference2, typeEmployeeID, hourCost, parentEmployeeID, startOn, endOn,statusID, createdOn, createdIn, createdAt, createdBy, isActive");
		$this->db->from("tb_employee i");		
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
   function get_rowByEntityID($companyID,$entityID){    
		$this->db->select("companyID, branchID, entityID, employeNumber, numberIdentification, identificationTypeID, socialSecurityNumber, address, countryID, stateID, cityID, departamentID, areaID, clasificationID, categoryID, reference1, reference2, typeEmployeeID, hourCost, parentEmployeeID, startOn, endOn,statusID, createdOn, createdIn, createdAt, createdBy, isActive");
		$this->db->from("tb_employee i");		
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
}
?>
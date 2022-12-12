<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Fixed_Assent_Model extends CI_Model  {
   function __construct(){
      parent::__construct();
   }
   function update($companyID,$branchID,$fixedAssentID,$data){
		$this->db->where('companyID', $companyID);
		$this->db->where('branchID', $branchID);	
		$this->db->where('fixedAssentID', $fixedAssentID);	
		$this->db->update('tb_fixed_assent', $data);
		return $this->db->affected_rows(); 
   }
   function delete($companyID,$branchID,$fixedAssentID){		  		
		$data["isActive"]	= 0;
		$this->db->where('companyID', $companyID);
		$this->db->where('branchID', $branchID);	
		$this->db->where('fixedAssentID', $fixedAssentID);	
		$this->db->update('tb_fixed_assent', $data);
		return $this->db->affected_rows(); 
   } 
   function insert($data){
		$result			= $this->db->insert('tb_fixed_assent', $data);
		$autoIncrement	= $this->db->insert_id(); 		
		return $result ? $autoIncrement : $result; 
   }
   function get_rowByPK($companyID,$branchID,$fixedAssentID){    
		$this->db->select("companyID, branchID, fixedAssentID, fixedAssentCode, name, description, modelNumber, marca, colorID, chasisNumber, reference1, reference2, year, asignedEmployeeID, categoryID, typeID, typeDepresiationID, yearOfUtility, priceStart, isForaneo, statusID,createdIn, createdOn, createdAt, createdBy, isActive");
		$this->db->from("tb_fixed_assent i");		
		$this->db->where("i.companyID",$companyID);
		$this->db->where("i.branchID",$branchID);
		$this->db->where("i.fixedAssentID",$fixedAssentID);
		
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
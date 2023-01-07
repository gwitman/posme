<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Warehouse_Model extends CI_Model  {
   function __construct(){
      parent::__construct();
   }
   function delete($companyID,$warehouseID){		
  		$data["isActive"] = 0;
		$this->db->where('companyID', $companyID);
		$this->db->where('warehouseID', $warehouseID);	
		$this->db->update('tb_warehouse', $data);
		return $this->db->affected_rows(); 
   }
   function update($companyID,$branchID,$warehouseID,$data){
		$this->db->where('companyID', $companyID);
		$this->db->where('branchID', $branchID);
		$this->db->where('warehouseID', $warehouseID);	
		$this->db->update('tb_warehouse', $data);
		return $this->db->affected_rows(); 
   }
   function insert($data){	
		$result			 		= $this->db->insert('tb_warehouse', $data);
		$autoIncrement			= $this->db->insert_id(); 		
		return $result ? $autoIncrement : $result; 
   }
   function getByCode($companyID,$code){
		$this->db->select("companyID, warehouseID, branchID, number, name, statusID, isActive, typeWarehouse");
		$this->db->from("tb_warehouse");
		$this->db->where("companyID",$companyID);		
		$this->db->where("isActive",1);		
		$this->db->where("number",$code);	
		
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
   function get_rowByPK($companyID,$warehouseID){
		$this->db->select("companyID, warehouseID, branchID, number, name, statusID, isActive,address,typeWarehouse");
		$this->db->from("tb_warehouse");
		$this->db->where("companyID",$companyID);		
		$this->db->where("isActive",1);		
		$this->db->where("warehouseID",$warehouseID);	
		
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
   function getByCompany($companyID){
		$this->db->select("companyID, warehouseID, branchID, number, name, statusID, isActive,typeWarehouse");
		$this->db->from("tb_warehouse");
		$this->db->where("companyID",$companyID);		
		$this->db->where("isActive",1);		
		
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
<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class UserWarehouse_Model extends CI_Model  {
   function __construct(){
      parent::__construct();
   }
   function deleteByUser($companyID,$userID){		  		
		$this->db->where('companyID', $companyID);
		$this->db->where('userID', $userID);
		$this->db->delete("tb_user_warehouse");
   }
   function insert($data){
		$result	= $this->db->insert('tb_user_warehouse', $data);
		return $result;
   }
   function getRowByUserID($companyID,$userID){
		$this->db->select("uw.companyID, uw.warehouseID, uw.branchID,uw.userID, w.number, w.name, w.statusID, w.isActive");
		$this->db->from("tb_user_warehouse uw");
		$this->db->join("tb_warehouse w","uw.warehouseID = w.warehouseID");
		$this->db->where("uw.companyID",$companyID);		
		$this->db->where("uw.userID",$userID);		
		$this->db->where("w.isActive",1);		
		
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
   function getRowByBranchID($companyID,$branchID){
		$this->db->select("uw.companyID, uw.warehouseID, uw.branchID,uw.userID, w.number, w.name, w.statusID, w.isActive");
		$this->db->from("tb_user_warehouse uw");
		$this->db->join("tb_warehouse w","uw.warehouseID = w.warehouseID");
		$this->db->where("uw.companyID",$companyID);		
		$this->db->where("uw.branchID",$branchID);		
		$this->db->where("w.isActive",1);		
		
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
   function getRowByCompanyID($companyID){
		$this->db->select("uw.companyID,uw.branchID,uw.number, uw.name, uw.statusID, uw.isActive");
		$this->db->from("tb_warehouse uw");		
		$this->db->where("uw.companyID",$companyID);		
		$this->db->where("uw.isActive",1);		
		
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
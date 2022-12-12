<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Role_Model extends CI_Model  {
   function __construct(){
      parent::__construct();
   }  
   function update($companyID,$branchID,$roleID,$obj){
	
		$data["name"] 			= $obj["name"];
		$data["description"] 	= $obj["description"];
		$data["urlDefault"] 	= $obj["urlDefault"];
		$data["isAdmin"] 		= $obj["isAdmin"];		
		$data["isActive"] 		= $obj["isActive"];   
		
		$this->db->where('companyID', $companyID);
		$this->db->where('branchID', $branchID);
		$this->db->where('roleID', $roleID);  
		$result			 		= $this->db->update('tb_role', $data);
		return $result; 
   }
   function insert($obj){
		$data["companyID"] 		= $obj["companyID"];		
		$data["branchID"] 		= $obj["branchID"];
		$data["name"] 			= $obj["name"];
		$data["description"] 	= $obj["description"];
		$data["urlDefault"] 	= $obj["urlDefault"];
		$data["isAdmin"] 		= $obj["isAdmin"];
		$data["createdOn"] 		= date("Y-m-d H:i:s");
		$data["createdBy"] 		= $obj["createdBy"];
		$data["isActive"] 		= $obj["isActive"];
		
		$result			 		= $this->db->insert('tb_role', $data);
		$roleID 				= $this->db->insert_id(); 		
		return $result ? $roleID : $result; 
		 
   }
   function get_rowByCompanyIDyBranchID($companyID,$branchID){    
		$this->db->select("companyID,branchID,roleID,name,description,isAdmin,createdOn,isActive,urlDefault,createdBy");
		$this->db->from("tb_role");
		$this->db->where("companyID",$companyID);
		$this->db->where("branchID",$branchID);
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
   function get_rowByPK($companyID,$branchID,$roleID){    
		$this->db->select("companyID,branchID,roleID,name,description,isAdmin,createdOn,isActive,urlDefault,createdBy");
		$this->db->from("tb_role");
		$this->db->where("companyID",$companyID);
		$this->db->where("branchID",$branchID); 
		$this->db->where("roleID",$roleID);
		$this->db->where("isActive",1);		
		
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
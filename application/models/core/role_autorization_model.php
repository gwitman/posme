<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Role_Autorization_Model extends CI_Model  {
   function __construct(){
      parent::__construct();
   }
   function deleteByRole($companyID,$branchID,$roleID){
   	 	$this->db->where("companyID",$companyID);
   	    $this->db->where("branchID",$branchID);
   	    $this->db->where("roleID",$roleID);   	    
   	    $this->db->delete("tb_role_autorization");   	    
   	    return $this->db->affected_rows();    	
   }
   function delete($companyID,$branchID,$roleID,$componentAutorizationID){
   	    $this->db->where("companyID",$companyID);
   	    $this->db->where("branchID",$branchID);
   	    $this->db->where("roleID",$roleID);
   	    $this->db->where("componentAutorizationID",$componentAutorizationID);
   	    $this->db->delete("tb_role_autorization");
   	    return $this->db->affected_rows(); 
   }
   function insert($obj){
		$data["companyID"] 					= $obj["companyID"];		
		$data["branchID"] 					= $obj["branchID"];
		$data["roleID"] 					= $obj["roleID"];
		$data["componentAutorizationID"] 	= $obj["componentAutorizationID"];
		
		$this->db->insert('tb_role_autorization', $data);		
		return $this->db->affected_rows(); 
		 
   }
   function get_rowByRoleAutorization($companyID,$branchID,$roleID){
   		$this->db->select("ra.companyID,ra.branchID,ra.roleID,ra.componentAutorizationID,ca.name");
		$this->db->from("tb_role_autorization ra");
		$this->db->join("tb_component_autorization ca ","ra.companyID = ca.companyID and ra.componentAutorizationID = ca.componentAutorizationID");		
		$this->db->where("ra.companyID",$companyID);
		$this->db->where("ra.branchID",$branchID);
		$this->db->where("ra.roleID",$roleID);		
		
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
   function get_rowByRole($companyID,$branchID,$roleID){
   		$this->db->select("ra.companyID,ra.branchID,ra.roleID,cad.componentAutorizationID,cad.componentID,cad.workflowID,cad.workflowStageID");
		$this->db->from("tb_role_autorization ra");
		$this->db->join("tb_component_autorization ca ","ra.companyID = ca.companyID and ra.componentAutorizationID = ca.componentAutorizationID");
		$this->db->join("tb_component_autorization_detail cad ","ca.companyID = cad.companyID and ca.componentAutorizationID = cad.componentAutorizationID");
		$this->db->where("ra.companyID",$companyID);
		$this->db->where("ra.branchID",$branchID);
		$this->db->where("ra.roleID",$roleID);		
		
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
   function get_rowByPK($companyID,$branchID,$roleID,$componentAutorizationID){    
		$this->db->select("ra.companyID,ra.branchID,ra.roleID,cad.componentAutorizationID,cad.componentID,cad.workflowID,cad.workflowStageID");
		$this->db->from("tb_role_autorization ra");
		$this->db->join("tb_component_autorization ca ","ra.companyID = ca.companyID and ra.componentAutorizationID = ca.componentAutorizationID");
		$this->db->join("tb_component_autorization_detail cad ","ca.companyID = cad.companyID and ca.componentAutorizationID = cad.componentAutorizationID");
		$this->db->where("ra.companyID",$companyID);
		$this->db->where("ra.branchID",$branchID);
		$this->db->where("ra.roleID",$roleID);
		$this->db->where("ra.componentAutorizationID",$componentAutorizationID);						
		
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
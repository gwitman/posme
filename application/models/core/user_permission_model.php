<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class User_Permission_Model extends CI_Model  {
   function __construct(){
      parent::__construct();
   }  
   function insert($data){		
		$result	= $this->db->insert('tb_user_permission',$data);
		return $result; 
   }
   function delete_ByRole($companyID,$branchID,$roleID){	
		$this->db->where('companyID', $companyID);
		$this->db->where('branchID', $branchID);
		$this->db->where('roleID', $roleID);  		
		$result = $this->db->delete('tb_user_permission'); 
		return $result;  
   }
   function get_rowByCompanyIDyBranchIDyRoleID($companyID,$branchID,$roleID){ 	
		$this->db->select("tb_user_permission.companyID,tb_user_permission.branchID,tb_user_permission.roleID,tb_user_permission.elementID,tb_user_permission.selected,tb_user_permission.inserted,tb_user_permission.deleted,tb_user_permission.edited,tb_menu_element.orden,tb_menu_element.display");
		$this->db->from("tb_user_permission");
		$this->db->join("tb_element","tb_element.elementID = tb_user_permission.elementID");
		$this->db->join("tb_menu_element","tb_menu_element.elementID = tb_user_permission.elementID");
		$this->db->where("tb_user_permission.companyID",$companyID);
		$this->db->where("tb_user_permission.branchID",$branchID);
		$this->db->where("tb_user_permission.roleID",$roleID);		
		$this->db->where("tb_menu_element.companyID",$companyID);	
		
		//Ejecutar 
		$recordSet = $this->db->get();
						  
		//Obtener errores
		if($this->db->_error_message())
		return null; 
		
		if($recordSet->num_rows() == 0)
		return null;
		
		//Resultado
		return $recordSet->result();
   }
   function get_rowByPK($companyID,$branchID,$roleID,$elementID){
		$this->db->select("companyID,branchID,roleID,elementID,selected,inserted,deleted,edited");
		$this->db->from("tb_user_permission");
		$this->db->where("companyID",$companyID);
		$this->db->where("branchID",$branchID);
		$this->db->where("roleID",$roleID);
		$this->db->where("elementID",$elementID);		
		
		//Ejecutar
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
<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Membership_Model extends CI_Model  {
   function __construct(){
      parent::__construct();
   }  
   function delete($companyID,$branchID,$userID){
		$this->db->where('companyID', $companyID);
		$this->db->where('branchID', $branchID);
		$this->db->where('userID', $userID); 				
		$result = $this->db->delete('tb_membership'); 
		return $result;  
   }
   function insert($data){		
		$result	= $this->db->insert('tb_membership',$data);
		return $result; 
   }
   function get_rowByCompanyIDBranchIDUserID($companyID,$branchID,$userID){    
		$this->db->select("companyID,branchID,userID,roleID");
		$this->db->from("tb_membership");
		$this->db->where("companyID",$companyID);
		$this->db->where("branchID",$branchID);
		$this->db->where("userID",$userID);		
		
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
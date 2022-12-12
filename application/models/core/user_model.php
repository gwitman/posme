<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class User_Model extends CI_Model  {
   function __construct(){
      parent::__construct();
   }  
   function get_rowByExistNickname($nickname){
		$this->db->select("companyID,branchID,userID,nickname,password,email,createdOn,createdBy,employeeID");
		$this->db->from("tb_user");
		$this->db->where("nickname",$nickname);
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
   function get_rowByNiknamePassword($nickname,$password){    
		$this->db->select("companyID,branchID,userID,nickname,password,email,createdOn,createdBy,employeeID");
		$this->db->from("tb_user");
		$this->db->where("nickname",$nickname);
		$this->db->where("password",$password);
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
   function get_rowByEmail($email){
		$this->db->select("companyID,branchID,userID,nickname,password,email,createdOn,createdBy,employeeID");
		$this->db->from("tb_user");
		$this->db->where("email",$email);
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
   function get_rowByPK($companyID,$branchID,$userID){
		$this->db->select("companyID,branchID,userID,nickname,password,email,createdOn,isActive,createdBy,employeeID");
		$this->db->from("tb_user");
		$this->db->where("companyID",$companyID);
		$this->db->where("branchID",$branchID);		
		$this->db->where("userID",$userID);		
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
   function get_countUser($companyID){
		$this->db->where('isActive', 1);
		$this->db->where('companyID', $companyID);
		$this->db->from('tb_user');
   		return $this->db->count_all_results();
   }
    function insert($data){   
		$result			 		= $this->db->insert('tb_user', $data);
		$userID 				= $this->db->insert_id(); 		
		return $result ? $userID : $result; 	
   }
   function getCount($companyID){		
		$this->db->where('isActive', 1);
		$this->db->where('companyID', $companyID);
		$this->db->from('tb_user');
   		return $this->db->count_all_results();
   }
   function update($companyID,$branchID,$userID,$data){   
		$this->db->where('branchID', $branchID);
		$this->db->where('companyID', $companyID);
		$this->db->where('userID', $userID);
		return $this->db->update('tb_user', $data); 		
   }
}
?>
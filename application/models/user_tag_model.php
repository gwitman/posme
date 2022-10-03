<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class User_Tag_Model extends CI_Model  {
   function __construct(){
      parent::__construct();
   }
   function deleteByUser($userID){
		$this->db->where('userID', $userID);
		$this->db->delete("tb_user_tag");
   }
   function delete($tagID,$userID){
		$this->db->where('tagID', $tagID);
		$this->db->where('userID', $userID);
		$this->db->delete("tb_user_tag");
   }
   function insert($data){
		$result	= $this->db->insert('tb_user_tag', $data);
		return $result;
   }
   function get_rowByUser($userID){
		$this->db->select("ut.tagID,ut.userID,ut.companyID,ut.branchID,u.userID,u.email,t.name,t.description,t.sendEmail,t.sendNotificationApp,t.sendSMS,t.isActive");
		$this->db->from("tb_user_tag ut");
		$this->db->join("tb_user u","ut.userID = u.userID");
		$this->db->join("tb_tag t","ut.tagID = t.tagID");
		$this->db->where("u.userID",$userID);	
		$this->db->where("t.isActive",1);	
		
		
		
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
   function get_rowByPK($tagID){    
		$this->db->select("ut.tagID,ut.userID,ut.companyID,ut.branchID,u.userID,u.email");
		$this->db->from("tb_user_tag ut");
		$this->db->join("tb_user u","ut.userID = u.userID");
		$this->db->join("tb_tag t","ut.tagID = t.tagID");
		$this->db->where("ut.tagID",$tagID);		
		$this->db->where("t.isActive",1);	
		
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
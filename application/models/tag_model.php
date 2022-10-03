<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Tag_Model extends CI_Model  {
   function __construct(){
      parent::__construct();
   }
   function update($tagID,$data){
		$this->db->where('tagID', $tagID);
		$this->db->update('tb_tag', $data);
		return $this->db->affected_rows(); 
   }
   function delete($tagID){		
  		$data["isActive"] = 0;
		$this->db->where('tagID', $tagID);
		$this->db->update('tb_tag', $data);
		return $this->db->affected_rows(); 
   } 
   function insert($data){
		$result	= $this->db->insert('tb_tag', $data);
		return $result;
   }
   function get_rows(){
		$this->db->select("tagID,name,description,sendEmail,sendNotificationApp,sendSMS,isActive");
		$this->db->from("tb_tag n");
		$this->db->where("n.isActive",1);		
		
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
		$this->db->select("tagID,name,description,sendEmail,sendNotificationApp,sendSMS,isActive");
		$this->db->from("tb_tag n");
		$this->db->where("n.isActive",1);		
		$this->db->where("n.tagID",$tagID);		
		
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
   function get_rowByName($name){    
		$this->db->select("tagID,name,description,sendEmail,sendNotificationApp,sendSMS,isActive");
		$this->db->from("tb_tag n");
		$this->db->where("n.isActive",1);		
		$this->db->where("n.name",$name);		
		
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
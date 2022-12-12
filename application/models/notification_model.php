<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Notification_Model extends CI_Model  {
   function __construct(){
      parent::__construct();
   }
   function update($notificationID,$data){
		$this->db->where('notificationID', $notificationID);
		$this->db->update('tb_notification', $data);
		return $this->db->affected_rows(); 
   }
   function delete($notificationID){		
  		$data["isActive"] = 0;
		$this->db->where('notificationID', $notificationID);
		$this->db->update('tb_notification', $data);
		return $this->db->affected_rows(); 
   } 
   function insert($data){
		$result	= $this->db->insert('tb_notification', $data);
		return $result;
   }
   function get_rowByPK($notificationID){    
		$this->db->select("notificationID,errorID,from,to,subject,message,summary,title,tagID,createdOn,sendOn,isActive");
		$this->db->from("tb_notification n");
		$this->db->where("n.isActive",1);		
		$this->db->where("n.notificationID",$notificationID);		
		
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
   function get_rows($top){    
		$this->db->select("notificationID,errorID,from,to,subject,message,summary,title,tagID,createdOn,sendOn,isActive");
		$this->db->from("tb_notification n");
		$this->db->where("n.isActive",1);
		$this->db->where("n.sendOn is null");
		$this->db->limit($top);
		
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
   function get_rowsByToMessage($to,$message){    
		$this->db->select("notificationID,errorID,from,to,subject,message,summary,title,tagID,createdOn,sendOn,isActive");
		$this->db->from("tb_notification n");
		$this->db->where("n.isActive",1);
		$this->db->where("n.to",$to);
		$this->db->where("n.message",$message);		
		
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
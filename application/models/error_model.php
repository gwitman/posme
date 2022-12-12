<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Error_Model extends CI_Model  {
   function __construct(){
      parent::__construct();
   }
   function update($errorID,$data){
		$this->db->where('errorID', $errorID);
		$this->db->update('tb_error', $data);
		return $this->db->affected_rows(); 
   }
   function updateTagID($tagID,$companyID,$data){	
		$this->db->where('tagID', $tagID);	
		$this->db->update('tb_error',$data);
		return $this->db->affected_rows(); 
   }
   function delete($errorID){		
  		$data["isActive"] = 0;
		$this->db->where('errorID', $errorID);
		$this->db->update('tb_error', $data);
		return $this->db->affected_rows(); 
   } 
   
   function deleteByTagID($tagID,$companyID){	
		$this->db->where('tagID', $tagID);	
		$this->db->delete('tb_error');
		return $this->db->affected_rows(); 
   } 
   function insert($data){
		$result	= $this->db->insert('tb_error', $data);
		return $result;
   }
   function get_rowByPK($errorID){    
		$this->db->select("errorID,tagID,notificated,message,isActive,isRead,createdOn,readOn");
		$this->db->from("tb_error n");
		$this->db->where("n.isActive",1);		
		$this->db->where("n.errorID",$errorID);		
		
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
   function get_rowByUser($userID){    
		$this->db->select("n.errorID,n.tagID,n.notificated,n.message,n.isActive,n.isRead,n.createdOn,n.readOn,n.userID");
		$this->db->from("tb_error n");
		$this->db->where("n.isActive",1);		
		$this->db->where("n.userID",$userID);
		$this->db->where("n.isRead",null);
		$this->db->limit(5);
		
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
   function get_rowByUserCount($userID){    
		
		$this->db->from("tb_error n");
		$this->db->where("n.isActive",1);		
		$this->db->where("n.userID",$userID);
		$this->db->where("n.isRead",null);				

		//contar filas
		return $this->db->count_all_results();
		
   }
   function get_rowByUserAllAndTagID($userID,$tagID){    
		$this->db->select("n.errorID,n.tagID,n.notificated,n.message,n.isActive,n.isRead,n.createdOn,n.readOn,n.userID");
		$this->db->from("tb_error n");
		$this->db->where("n.isActive",1);		
		$this->db->where("n.userID",$userID);
		$this->db->where("n.isRead",null);
		$this->db->where("n.tagID",$tagID);
		
		
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
   function get_rowByUserAll($userID){    
		$this->db->select("n.errorID,n.tagID,n.notificated,n.message,n.isActive,n.isRead,n.createdOn,n.readOn,n.userID");
		$this->db->from("tb_error n");
		$this->db->where("n.isActive",1);		
		$this->db->where("n.userID",$userID);
		$this->db->where("n.isRead",null);
		
		
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
   function get_rowByMessageUser($userID,$message){
		$this->db->select("errorID,tagID,notificated,message,isActive,isRead,createdOn,readOn,n.userID");
		$this->db->from("tb_error n");
		$this->db->where("n.isActive",1);
		
		if ($userID == 0)
		$this->db->where("n.userID is null",NULL,false);
		else		
		$this->db->where("n.userID",$userID);
	
		$this->db->where("n.message",$message);
		$this->db->limit(1);
		
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
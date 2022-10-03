<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Entity_Model extends CI_Model  {
   function __construct(){
      parent::__construct();
   }
   function update($companyID,$branchID,$entityID,$data){
		$this->db->where('companyID', $companyID);
		$this->db->where('branchID', $branchID);	
		$this->db->where('entityID', $entityID);	
		$this->db->update('tb_entity', $data);
		return $this->db->affected_rows(); 
   }
   function delete($companyID,$branchID,$entityID){		  		
		$this->db->where('companyID', $companyID);
		$this->db->where('branchID', $branchID);	
		$this->db->where('entityID', $entityID);	
		$this->db->delete('tb_entity', $data);
		return $this->db->affected_rows(); 
   } 
   function insert($data){
		$result			= $this->db->insert('tb_entity', $data);
		$autoIncrement	= $this->db->insert_id(); 		
		return $result ? $autoIncrement : $result; 
   }
   function get_rowByPK($companyID,$branchID,$entityID){    
		$this->db->select("i.companyID,i.branchID,i.entityID,i.createdAt,i.createdOn,i.createdBy");
		$this->db->from("tb_entity i");		
		$this->db->where("i.companyID",$companyID);
		$this->db->where("i.branchID",$branchID);
		$this->db->where("i.entityID",$entityID);
		
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
      function get_rowByEntity($companyID,$entityID){    
		$this->db->select("i.companyID,i.branchID,i.entityID,i.createdAt,i.createdOn,i.createdBy");
		$this->db->from("tb_entity i");		
		$this->db->where("i.companyID",$companyID);		
		$this->db->where("i.entityID",$entityID);
		
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
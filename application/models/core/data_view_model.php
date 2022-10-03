<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Data_View_Model extends CI_Model  {
   function __construct(){
      parent::__construct();  
   }  
   function getListBy_CompanyComponentCaller($componentID,$callerID){  
		 
		$this->db->select("componentID,callerID,dataViewID");
		$this->db->from("tb_dataview"); 		
		$this->db->where("componentID",$componentID);
		$this->db->where("callerID",$callerID);		
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
   function getViewByName($componentID,$name,$callerID){
		$this->db->select("componentID,callerID,dataViewID");
		$this->db->from("tb_dataview"); 		
		$this->db->where("componentID",$componentID);
		$this->db->where("callerID",$callerID);		
		$this->db->where("name",$name);		
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
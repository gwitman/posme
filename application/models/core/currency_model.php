<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Currency_Model extends CI_Model  {
   function __construct(){
      parent::__construct();
   }  
   function get_rowName($name){    
		$this->db->select("currencyID,simbol,name,description,isActive");
		$this->db->from("tb_currency");
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
   function get_rowByPK($currencyID){
	    $this->db->select("currencyID,simbol,name,description,isActive");
		$this->db->from("tb_currency");
		$this->db->where("currencyID",$currencyID);		
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
   function getList(){    
		$this->db->select("currencyID,simbol,name,description,isActive");
		$this->db->from("tb_currency");			
		$this->db->where("isActive",1);
		
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
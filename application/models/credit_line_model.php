<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Credit_Line_Model extends CI_Model  {
   function __construct(){
      parent::__construct();
   }
   function update($companyID,$creditLineID,$data){
		$this->db->where('companyID', $companyID);
		$this->db->where('creditLineID', $creditLineID);	
		$this->db->update('tb_credit_line', $data);
		return $this->db->affected_rows(); 
   }
   function delete($companyID,$creditLineID){		
  		$data["isActive"] = 0;
		$this->db->where('companyID', $companyID);
		$this->db->where('creditLineID', $creditLineID);	
		$this->db->update('tb_credit_line', $data);
		return $this->db->affected_rows(); 
   } 
   function insert($data){
		$result			 		= $this->db->insert('tb_credit_line', $data);
		$autoIncrement			= $this->db->insert_id(); 		
		return $result ? $autoIncrement : $result; 
   }   
   function get_rowByCompany($companyID){    
		$this->db->select("companyID, creditLineID,name,description,isActive");
		$this->db->from("tb_credit_line i");		
		$this->db->where("i.companyID",$companyID);
		$this->db->where("i.isActive",1);		
		
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
   function get_rowByPK($companyID,$creditLineID){    
		$this->db->select("companyID, creditLineID,name,description,isActive");
		$this->db->from("tb_credit_line i");		
		$this->db->where("i.companyID",$companyID);
		$this->db->where("i.creditLineID",$creditLineID);
		$this->db->where("i.isActive",1);		
		
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
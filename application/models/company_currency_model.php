<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Company_Currency_Model extends CI_Model  {
   function __construct(){
      parent::__construct();
   }    
   function delete($companyID,$currencyID){
		$this->db->where('companyID', $companyID);
		$this->db->where('currencyID', $currencyID);	
		$this->db->delete("tb_company_currency");
		return $this->db->affected_rows(); 
   }
   function update($companyID,$currencyID,$data){
		$this->db->where('companyID', $companyID);
		$this->db->where('currencyID', $currencyID);	
		$this->db->update('tb_company_currency', $data);		
		return $this->db->affected_rows(); 
   }
   function insert($data){
		$result			 		= $this->db->insert('tb_company_currency', $data);
		return $result;
   }
   function get_rowByPK($companyID,$currencyID){
		$this->db->select("cc.companyID,cc.currencyID,cc.simb");
		$this->db->from("tb_company_currency cc");
		$this->db->join("tb_currency c","cc.currencyID = c.currencyID");
		$this->db->where("cc.currencyID",$currencyID);
		$this->db->where("cc.companyID",$companyID);			
		$this->db->where("c.isActive",1);	
		
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
   function getByCompany($companyID){    
		$this->db->select("cc.companyID,cc.currencyID,cc.simb,c.simbol,c.name");
		$this->db->from("tb_company_currency cc");
		$this->db->join("tb_currency c","cc.currencyID = c.currencyID");
		$this->db->where("cc.companyID",$companyID);		
		$this->db->where("c.isActive",1);		
		
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
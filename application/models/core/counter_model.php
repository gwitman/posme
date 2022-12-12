<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Counter_Model extends CI_Model  {
   function __construct(){
      parent::__construct();
   }    
   function get_rowByPK($companyID,$branchID,$componentID,$componentItemID){
		$this->db->select("e.companyID,e.branchID,e.componentID,e.componentItemID,e.initialValue,e.currentValue,e.seed,e.serie,e.length");
		$this->db->from("tb_counter e");
		$this->db->where("e.companyID",$companyID);	
		$this->db->where("e.branchID",$branchID);	
		$this->db->where("e.componentID",$componentID);	
		$this->db->where("e.componentItemID",$componentItemID);	
		
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
   function update($companyID,$branchID,$componentID,$componentItemID,$data){
		$this->db->where('companyID', $companyID);
		$this->db->where('branchID', $branchID);
		$this->db->where('componentID', $componentID);
		$this->db->where('componentItemID', $componentItemID);
		return $this->db->update('tb_counter', $data); 
   }
}
?>
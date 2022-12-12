<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Company_Data_View_Model extends CI_Model  {
   function __construct(){
      parent::__construct();
   }  
   function get_rowBy_companyIDDataViewID($companyID,$dataViewID,$callerID,$componentID){    
		$this->db->select("companyID,componentID,callerID,dataViewID,companyDataViewID,name,description,sqlScript,visibleColumns,nonVisibleColumns,isActive");
		$this->db->from("tb_company_dataview");
		$this->db->where("companyID",$companyID);
		$this->db->where("componentID",$componentID);
		$this->db->where("callerID",$callerID);
		$this->db->where("dataViewID",$dataViewID);
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
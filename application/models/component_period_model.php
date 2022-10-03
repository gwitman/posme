<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Component_Period_Model extends CI_Model  {
   function __construct(){
      parent::__construct();
   } 
  function delete($companyID,$componentID,$componentPeriodID){		
		$obj["isActive"] = 0;
  		$this->db->where('companyID', $companyID);
		$this->db->where('componentID', $componentID);
		$this->db->where('componentPeriodID', $componentPeriodID);		
		$this->db->update('tb_accounting_period', $obj);
		return $this->db->affected_rows(); 
   } 
   function update($companyID,$componentID,$componentPeriodID,$obj){		
		$this->db->where('companyID', $companyID);
		$this->db->where('componentID', $componentID);
		$this->db->where('componentPeriodID', $componentPeriodID);	
		$this->db->update('tb_accounting_period', $obj);
		return $this->db->affected_rows(); 
   }
   function insert($data){			
		$result			 		= $this->db->insert('tb_accounting_period', $data);
		$autoIncrement			= $this->db->insert_id(); 		
		return $result ? $autoIncrement : $result; 
   }
   function validateTime($companyID,$componentID,$dateStart,$dateEnd){
		$query = $this->db->query("
		SELECT
			componentPeriodID,companyID,componentID,number,name,description,startOn,endOn,statusID,isActive,createdBy,createdOn,createdIn,createdAt 
		FROM
			tb_accounting_period
		WHERE					
			isActive 	= 1 and
			companyID	= $companyID  and 
			componentID = $componentID and (
			'$dateStart' between  startOn and endOn  or   
			'$dateEnd'   between  startOn and endOn ) 
		ORDER BY 
			startOn
		");
		
		//Resultado
		return $query->result();
   }
   function get_rowByCompanyIDFecha($companyID,$dateStart)
   {
		$query = $this->db->query("
		SELECT
			componentPeriodID, companyID, componentID, number, name, description, startOn, endOn, statusID, isActive, createdBy, createdOn, createdAt, createdIn
		FROM
			tb_accounting_period
		WHERE					
			isActive 	= 1 and
			companyID	= $companyID  and 			
			'$dateStart' between  startOn and endOn
		");
		
		
		//Obtener errores
		if($this->db->_error_message())  
		return null; 
		
		if($query->num_rows() == 0)
		return null;
		
		//Resultado
		return $query->row();
		
   }
   function get_countPeriod($companyID){
		$this->db->where('isActive', 1);
		$this->db->where('companyID', $companyID);
		$this->db->from('tb_accounting_period');
   		return $this->db->count_all_results();
   }
   function get_rowByPK($componentPeriodID){    
		$this->db->select("componentPeriodID,companyID,componentID,number,name,description,startOn,endOn,statusID,isActive,createdBy,createdOn,createdIn,createdAt");
		$this->db->from("tb_accounting_period");		
		$this->db->where("componentPeriodID",$componentPeriodID);
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
   function get_rowByNotClosed($companyID,$workflowStageClosed){
		$query = $this->db->query("
		SELECT
			componentPeriodID,companyID,componentID,number,name,description,startOn,endOn,statusID,isActive,createdBy,createdOn,createdAt,createdIn
		FROM
			tb_accounting_period
		WHERE					
			isActive 	= 1 and
			companyID	= $companyID and 
			statusID    != $workflowStageClosed 
		ORDER BY
			startOn 
		");
		
		
		//Obtener errores
		if($this->db->_error_message())  
		return null; 
		
		if($query->num_rows() == 0)
		return null;
		
		//Resultado
		return $query->result();
		
   }
   function get_rowByCompany($companyID){
		$query = $this->db->query("
		SELECT
			componentPeriodID,companyID,componentID,number,name,description,startOn,endOn,statusID,isActive,createdBy,createdOn,createdAt,createdIn
		FROM
			tb_accounting_period
		WHERE					
			isActive 	= 1 and
			companyID	= $companyID 
		ORDER BY
			startOn 
		");
		
		
		//Obtener errores
		if($this->db->_error_message())  
		return null; 
		
		if($query->num_rows() == 0)
		return null;
		
		//Resultado
		return $query->result();
		
   }
   
   function countJournalInPeriod($periodID,$companyID){
		$query = $this->db->query("
		SELECT
			COUNT(*) AS count_
		FROM
			tb_journal_entry je
			inner join tb_accounting_cycle ce on 
				je.accountingCycleID = ce.componentCycleID 
		WHERE					
			je.isActive 	= 1 and
			je.companyID	= $companyID and
			ce.componentPeriodID = $periodID 
		");
		
		
		//Obtener errores
		if($this->db->_error_message())  
		return null; 
		
		if($query->num_rows() == 0)
		return null;
		
		//Resultado
		$count_ = $query->row()->count_;
		return $count_;
   }
}
?>
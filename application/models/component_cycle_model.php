<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Component_Cycle_Model extends CI_Model  {
   function __construct(){
      parent::__construct();
   } 
  function delete($componentCycleID){		
		$data["isActive"] = 0;
  		$this->db->where('componentCycleID', $componentCycleID);	
		$this->db->update('tb_accounting_cycle', $data);
		return $this->db->affected_rows(); 
   }
   function deleteNotInArray($companyID,$componentID,$componentPeriodID,$Array){		
		$data["isActive"] = 0;
  		$this->db->where('companyID', $companyID);	
		$this->db->where('componentID', $componentID);	
		$this->db->where('componentPeriodID', $componentPeriodID);	
		$this->db->where_not_in('componentCycleID', $Array);	
		$this->db->update('tb_accounting_cycle', $data);
		return $this->db->affected_rows(); 
   }   
   function update($componentCycleID,$data){				
		$this->db->where('componentCycleID', $componentCycleID);	
		$this->db->update('tb_accounting_cycle', $data);
		return $this->db->affected_rows(); 
   }
   function insert($data){	
		$result			 		= $this->db->insert('tb_accounting_cycle', $data);
		$autoIncrement			= $this->db->insert_id(); 		
		return $result ? $autoIncrement : $result;  
   }
   function getByComponentPeriodID($componentPeriodID){
	   $query	= $this->db->query("
	   SELECT 
			componentCycleID,componentPeriodID,companyID,componentID,number,name,description,
			startOn,endOn,
			DATE_FORMAT( startOn,\"%Y-%M-%d\") as startOnFormat,
			DATE_FORMAT( endOn,\"%Y-%M-%d\") as endOnFormat,
			statusID,isActive,createdBy,createdOn,createdAt,createdIn
		FROM 
			tb_accounting_cycle i 
		WHERE 
			i.isActive = 1 AND 
			i.componentPeriodID = $componentPeriodID 
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
   function get_rowByNotClosed($companyID,$componentPeriodID,$workflowStageClosed){
		$query = $this->db->query("
		SELECT
			componentCycleID,componentPeriodID,companyID,componentID,number,name,description,
			startOn,endOn,
			DATE_FORMAT(startOn,\"%Y-%M-%d\") as startOnFormat,DATE_FORMAT(endOn,\"%Y-%M-%d\") as endOnFormat,
			statusID,isActive,createdBy,createdOn,createdAt,createdIn
		FROM
			tb_accounting_cycle
		WHERE					
			isActive 			= 1 and 
			companyID			= $companyID and  
			statusID 			!= $workflowStageClosed and  
			componentPeriodID 	= $componentPeriodID  
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
   
   function get_rowByCompanyIDFecha($companyID,$dateStart){
		$query = $this->db->query("
		SELECT
			componentCycleID,componentPeriodID,companyID,componentID,number,name,description,startOn,endOn,statusID,isActive,createdBy,createdOn,createdAt,createdIn
		FROM
			tb_accounting_cycle
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
   
   function get_rowByCompanyID_TopCycleOpenAscAndOpen($companyID,$top,$workflowStageClosed){
		$query = $this->db->query("
		SELECT
			componentCycleID,componentPeriodID,companyID,componentID,number,name,description,startOn,endOn,statusID,isActive,createdBy,createdOn,createdAt,createdIn
		FROM
			tb_accounting_cycle
		WHERE					
			isActive 	= 1 and
			companyID	= $companyID and 
			statusID 	!= $workflowStageClosed 
		ORDER BY 
			componentCycleID 
		LIMIT 0,$top
		");
		
		//Obtener errores
		if($this->db->_error_message())  
		return null; 
		
		if($query->num_rows() == 0)
		return null;
		
		//Resultado
		return $query->result();
		
   }
   
   function get_rowByPK($periodID,$cycleID){    
		$query = $this->db->query("
		SELECT
			componentCycleID,componentPeriodID,companyID,componentID,number,name,description,startOn,endOn,statusID,isActive,createdBy,createdOn,createdAt,createdIn
		FROM
			tb_accounting_cycle
		WHERE					
			isActive 	= 1 and
			componentPeriodID	= $periodID  and 			
			componentCycleID	= $cycleID  	
		");
		
		
		//Obtener errores
		if($this->db->_error_message())  
		return null; 
		
		if($query->num_rows() == 0)
		return null;
		
		//Resultado
		return $query->row();
   }
   function get_rowByCycleID($cycleID){    
		$query = $this->db->query("
		SELECT
			componentCycleID,componentPeriodID,companyID,componentID,number,name,description,startOn,endOn,statusID,isActive,createdBy,createdOn,createdAt,createdIn
		FROM
			tb_accounting_cycle
		WHERE					
			isActive 	= 1 and 					
			componentCycleID	= $cycleID  	
		");
		
		
		//Obtener errores
		if($this->db->_error_message())  
		return null; 
		
		if($query->num_rows() == 0)
		return null;
		
		//Resultado
		return $query->row();
   }
   function get_rowByCycleNotIn($companyID,$componentID,$componentPeriodID,$Array){		
		
		$this->db->select("componentCycleID,componentPeriodID,companyID,componentID,number,name,description,startOn,endOn,statusID,isActive,createdBy,createdOn,createdAt,createdIn");
		$this->db->from("tb_accounting_cycle");		
		$this->db->where('companyID', $companyID);	
		$this->db->where('componentID', $componentID);	
		$this->db->where('componentPeriodID', $componentPeriodID);	
		$this->db->where('isActive', 1);	
		$this->db->where_not_in('componentCycleID', $Array);	
		
		
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
   function countJournalInCycle($cycleID,$companyID){		
		$this->db->from('tb_journal_entry je');
		$this->db->where('je.isActive', 1);
		$this->db->where('je.companyID', $companyID);
		$this->db->where('je.accountingCycleID', $cycleID);
   		return $this->db->count_all_results();
   }
}
?>
<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Remember_Model extends CI_Model  {
   function __construct(){
      parent::__construct();
   } 
  function delete($rememberID){		
  		$data["isActive"] = 0;
		$this->db->where('rememberID', $rememberID);
		$this->db->update('tb_remember', $data);
		return $this->db->affected_rows(); 
   } 
   function update($rememberID,$data){		
		$this->db->where('rememberID', $rememberID);
		$this->db->update('tb_remember', $data);
		return $this->db->affected_rows(); 
   }
   function insert($data){	
		$result			 		= $this->db->insert('tb_remember', $data);
		$autoIncrement			= $this->db->insert_id(); 		
		return $result ? $autoIncrement : $result; 
   }
   function get_rowByPK($rememberID){    
		$this->db->select("companyID,rememberID,title,description,period,day,statusID,lastNotificationOn,isTemporal,createdBy,createdOn,createdIn,createdAt,isActive");
		$this->db->from("tb_remember");
		$this->db->where("rememberID",$rememberID);
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
	function getByCompany($companyID){
		$this->db->select("companyID,rememberID,title,description,period,day,statusID,lastNotificationOn,isTemporal,createdBy,createdOn,createdIn,createdAt,isActive");
		$this->db->from("tb_remember");
		$this->db->where("companyID",$companyID);
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
	function getNotificationCompany($companyID)
	{
		$query = $this->db->query("
		select			
			c.companyID, 
			c.rememberID, 
			c.lastNotificationOn,
			c.day 
		from 
			tb_remember  c
			inner join tb_catalog_item ci on 
				c.period = ci.catalogItemID 
			inner join tb_workflow_stage ws on 
				c.statusID = ws.workflowStageID 
		where 
			c.isActive = 1 
			and ws.vinculable = 1  
			and c.companyID = $companyID  
		");
		return $query->result();
	}
	function getProcessNotification($rememberID,$fechaProcess){
		
		$query = $this->db->query("
		select
			
			case 
				when ci.sequence = 30 then 
					dayofmonth('".$fechaProcess."') 
				when ci.sequence = 15 then 
					case 
						when dayofmonth('".$fechaProcess."') <= 15 then 
							dayofmonth('".$fechaProcess."') 
						else 
							dayofmonth('".$fechaProcess."') - 15
					end
				when ci.sequence = 7 then 
					dayofweek('".$fechaProcess."') 
				when  ci.sequence = 365 then 
					dayofyear('".$fechaProcess."') 
				else 
					0
			end diaProcesado,
			'".$fechaProcess."' as Fecha,
			c.title,
			c.description
		from 
			tb_remember c
			inner join tb_catalog_item ci on 
				c.period = ci.catalogItemID 
		where 
			c.rememberID = ".$rememberID." 
		");			$query_temp = $query->row();		//log_message("ERROR",print_r($this->db->last_query(),true));
		return $query_temp;
		
	}
	
}
?>
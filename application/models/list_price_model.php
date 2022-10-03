<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class List_Price_Model extends CI_Model  {
   function __construct(){
      parent::__construct();
   }
   function update($companyID,$listPriceID,$data){
		$this->db->where('companyID', $companyID);
		$this->db->where('listPriceID', $listPriceID);	
		$this->db->update('tb_list_price', $data);
		return $this->db->affected_rows(); 
   }
   function delete($companyID,$listPriceID){		
  		$data["isActive"] = 0;
		$this->db->where('companyID', $companyID);
		$this->db->where('listPriceID', $listPriceID);			
		$this->db->update('tb_list_price', $data);
		return $this->db->affected_rows(); 
   } 
   function insert($data){
		$result			 		= $this->db->insert('tb_list_price', $data);
		$autoIncrement			= $this->db->insert_id(); 		
		return $result ? $autoIncrement : $result; 
   }   
   function get_rowByPK($companyID,$listPriceID){    
		$this->db->select("i.companyID,i.listPriceID,i.startOn,i.endOn,i.name,i.description,i.statusID,i.createdOn,i.createdIn,i.createdBy,i.createdAt,i.isActive,ws.name as statusName");
		$this->db->from("tb_list_price i");		
		$this->db->join("tb_workflow_stage ws","i.statusID = ws.workflowStageID");	
		$this->db->where("i.companyID",$companyID);
		$this->db->where("i.listPriceID",$listPriceID);		
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
   function getListPriceToApply($companyID){
	    
		$rd = $this->db->query(
		"
		select 
			companyID,listPriceID,startOn,endOn,name,description,statusID,createdOn,createdIn,createdBy,createdAt,isActive
		from 
			tb_list_price c 
		where
			current_date() between c.startOn and c.endOn and 
			c.isActive = 1 and 
			c.companyID = $companyID 
		order by 
			c.listPriceID desc 
		limit 0,1 
		");
	
		//Ejecutar Consulta
		$recordSet = $rd->row();
		
		//Obtener errores
		if($this->db->_error_message())  
		return null; 
		
		if($rd->num_rows() == 0)
		return null;
		
		//Resultado
		return $recordSet;
   }
}
?>
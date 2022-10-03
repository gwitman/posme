<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Employee_Calendar_Pay_Model extends CI_Model  {
   function __construct(){
      parent::__construct();
   }
   function update($calendarID,$data){
		$this->db->where('calendarID', $calendarID);
		$this->db->update('tb_employee_calendar_pay', $data);
		return $this->db->affected_rows(); 
   }
   function delete($calendarID){		  		
		$data["isActive"]	= 0;
		$this->db->where('calendarID', $calendarID);
		$this->db->update('tb_employee_calendar_pay', $data);
		return $this->db->affected_rows(); 
   } 
   function insert($data){
		$result			= $this->db->insert('tb_employee_calendar_pay', $data);
		$autoIncrement	= $this->db->insert_id(); 		
		return $result ? $autoIncrement : $result; 
   }
   function get_rowByPK($calendarID){    
		$this->db->select("i.calendarID,i.companyID,i.accountingCycleID,i.name,i.number,i.typeID,i.currencyID,i.statusID,i.description,i.createdBy,i.createdAt,i.createdOn,i.createdIn,i.isActive");
		$this->db->from("tb_employee_calendar_pay i");		
		$this->db->where("i.calendarID",$calendarID);
		
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
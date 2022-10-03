<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Employee_Calendar_Pay_Detail_Model extends CI_Model  {
   function __construct(){
      parent::__construct();
   }
   function update($calendarDetailID,$data){
		$this->db->where('calendarDetailID', $calendarDetailID);
		$this->db->update('tb_employee_calendar_pay_detail', $data);
		return $this->db->affected_rows(); 
   }
   function deleteWhereIDNotIn($calendarID,$arrayID){
	   $data["isActive"] = 0;
		$this->db->where('calendarID', $calendarID);		
		$this->db->where_not_in('calendarDetailID', $arrayID);
		$this->db->update('tb_employee_calendar_pay_detail', $data);
		return $this->db->affected_rows(); 
   }
   function delete($calendarDetailID){		  		
		$data["isActive"]	= 0;
		$this->db->where('calendarDetailID', $calendarDetailID);
		$this->db->update('tb_employee_calendar_pay_detail', $data);
		return $this->db->affected_rows(); 
   } 
   function insert($data){
		$result			= $this->db->insert('tb_employee_calendar_pay_detail', $data);
		$autoIncrement	= $this->db->insert_id(); 		
		return $result ? $autoIncrement : $result; 
   }
   function get_rowByPK($calendarDetailID){    
		$this->db->select("	
			i.calendarDetailID,
			i.calendarID,
			i.employeeID,
			i.salary,
			i.commission,
			i.adelantos,
			i.neto,
			i.isActive  ,
			n.firstName,
			n.lastName ,
			e.employeNumber ,
			e.hourCost,
			e.comissionPorcentage 
		");
		$this->db->from("tb_employee_calendar_pay_detail i");		
		$this->db->join("tb_employee e","i.employeeID = e.entityID");
		$this->db->join("tb_naturales n","i.employeeID = n.entityID");
		$this->db->where("i.calendarDetailID",$calendarDetailID);
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
   function get_rowByCalendarID($calendarID){    
		$this->db->select("	
			i.calendarDetailID,
			i.calendarID,
			i.employeeID,
			i.salary,
			i.commission,
			i.adelantos,
			i.neto,
			i.isActive  ,
			n.firstName,
			n.lastName ,
			e.employeNumber ,
			e.hourCost,
			e.comissionPorcentage 
		");
		$this->db->from("tb_employee_calendar_pay_detail i");		
		$this->db->join("tb_employee e","i.employeeID = e.entityID");
		$this->db->join("tb_naturales n","i.employeeID = n.entityID");
		$this->db->where("i.calendarID",$calendarID);
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
}
?>
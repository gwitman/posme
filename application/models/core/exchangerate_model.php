<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class ExchangeRate_Model extends CI_Model  {
   function __construct(){
      parent::__construct();
   }
   function update($companyID,$date,$currencyIDSource,$currencyIDTarget,$data){
		$this->db->where('companyID', $companyID);
		$this->db->where('date', $date);	
		$this->db->where('currencyID', $currencyIDSource);	
		$this->db->where('targetCurrencyID', $currencyIDTarget);	
		$this->db->update('tb_exchange_rate', $data);		
		$affected_rows = $this->db->affected_rows(); 
		return $affected_rows;
   }   
   function insert($data){
		$result		= $this->db->insert('tb_exchange_rate', $data);
		return $result;
   }
   function get_default($companyID){
   		$this->db->select("currencyID,companyID,date,targetCurrencyID,ratio");
		$this->db->from("tb_exchange_rate");
		$this->db->where("companyID",$companyID);
		$this->db->where("ratio",1);
		$this->db->limit(1);
		
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
   function get_rowByPK($companyID,$date,$currencyIDSource,$currencyIDTarget){    
		$this->db->select("currencyID,companyID,date,targetCurrencyID,ratio");
		$this->db->from("tb_exchange_rate");
		$this->db->where("companyID",$companyID);
		$this->db->where("currencyID",$currencyIDSource);
		$this->db->where("targetCurrencyID",$currencyIDTarget);
		$this->db->where("date",$date);
		$this->db->limit(1);
		
		//Ejecutar Consulta
		$recordSet = $this->db->get();
		
		
		//Obtener errores
		if($this->db->_error_message())  
		return null; 
		
		if($recordSet->num_rows() == 0)
		return null;
		
		//Resultado
		$row = $recordSet->row();
		return $row;
   }
   function getByCompanyAndDate($companyID,$dateStartOn,$dateEndOn){    
		$query = $this->db->query("
			select 
				er.date,
				er.value,
				er.ratio,
				c.name as nameSource,
				cc.simb as simbSource,				
				ct.name as nameTarget,
				cct.simb as simbTarget
			from
				tb_exchange_rate er 
				inner join  tb_company_currency cc on 
					er.companyID = cc.companyID and 
					er.currencyID = cc.currencyID 
				inner join tb_currency c on 
					cc.currencyID = c.currencyID
				inner join tb_company_currency cct on 
					er.companyID = cct.companyID and 
					er.targetCurrencyID = cct.currencyID 
				inner join tb_currency ct on 
					cct.currencyID = ct.currencyID 
			where
				er.companyID = $companyID and 
				er.date between '$dateStartOn' and '$dateEndOn' 				
			order by
				er.date,c.name
		");
		return $query->result_array();
		
   }
   
}
?>
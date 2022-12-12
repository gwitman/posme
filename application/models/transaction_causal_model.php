<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Transaction_Causal_Model extends CI_Model  {
   function __construct(){
      parent::__construct();
   }
   function getCausalByBranch($companyID,$transactionID,$branchID){
	    $this->db->select("tc.companyID, tc.transactionID, tc.transactionCausalID, tc.branchID, tc.name, tc.warehouseSourceID, tc.warehouseTargetID, tc.isDefault, tc.isActive");
		$this->db->from("tb_transaction_causal tc");		
		$this->db->where("tc.companyID",$companyID);		
		$this->db->where("tc.transactionID",$transactionID);		
		$this->db->where("tc.branchID",$branchID);		
		$this->db->where("tc.isActive",1);		
		
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
   function getCausalDefaultID($companyID,$transactionID){
		$this->db->select("tc.companyID, tc.transactionID, tc.transactionCausalID, tc.branchID, tc.name, tc.warehouseSourceID, tc.warehouseTargetID, tc.isDefault, tc.isActive");
		$this->db->from("tb_transaction_causal tc");
		$this->db->where("tc.companyID",$companyID);		
		$this->db->where("tc.transactionID",$transactionID);		
		$this->db->where("tc.isActive",1);		
		$this->db->where("tc.isDefault",1);	
		
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
   function getByCompanyAndTransaction($companyID,$transactionID){
		$this->db->select("tc.companyID, tc.transactionID, tc.transactionCausalID, tc.branchID, tc.name, tc.warehouseSourceID, tc.warehouseTargetID, tc.isDefault, tc.isActive,b.name as branch,w.name as warehouseSourceDescription, w2.name as warehouseTargetDescription");
		$this->db->from("tb_transaction_causal tc");
		$this->db->join('tb_branch b', 'tc.branchID = b.branchID');
		$this->db->join('tb_warehouse w', 'tc.warehouseSourceID = w.warehouseID', 'left');
		$this->db->join('tb_warehouse w2', 'tc.warehouseTargetID = w2.warehouseID', 'left');
		$this->db->where("tc.companyID",$companyID);		
		$this->db->where("tc.transactionID",$transactionID);		
		$this->db->where("tc.isActive",1);		
		
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
   function getByCompanyAndTransactionAndCausal($companyID,$transactionID,$causalID){
		$this->db->select("companyID, transactionID, transactionCausalID, branchID, name, warehouseSourceID, warehouseTargetID, isDefault, isActive");
		$this->db->from("tb_transaction_causal tc");		
		$this->db->where("tc.companyID",$companyID);		
		$this->db->where("tc.transactionID",$transactionID);		
		$this->db->where("tc.transactionCausalID",$causalID);		
		$this->db->where("tc.isActive",1);		
		
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
   function delete($companyID,$transactionID,$listCausal){
		$data["isActive"] = 0;
		$this->db->where('companyID', $companyID);
		$this->db->where('transactionID', $transactionID);	
		$this->db->where_not_in('transactionCausalID', $listCausal);	
		$this->db->update('tb_transaction_causal', $data);
		return $this->db->affected_rows(); 
   }
   function insert($data){	
		$result			 		= $this->db->insert('tb_transaction_causal', $data);
		$autoIncrement			= $this->db->insert_id(); 		
		return $result ? $autoIncrement : $result; 
   }
   function update($companyID,$transactionID,$causalID,$data){		
		$this->db->where('companyID', $companyID);
		$this->db->where('transactionID', $transactionID);	
		$this->db->where('transactionCausalID', $causalID);	
		$this->db->update('tb_transaction_causal', $data);
		return $this->db->affected_rows(); 
   }
   function countCausalDefault($companyID,$transactionID){
		$this->db->where('isActive', 1);
		$this->db->where('isDefault', 1);
		$this->db->where('companyID', $companyID);
		$this->db->where('transactionID', $transactionID);
		$this->db->from('tb_transaction_causal');
   		return $this->db->count_all_results();
   }
}
?>
<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Transaction_Master_Info_Model extends CI_Model  {
   function __construct(){
      parent::__construct();
   }
   function delete($companyID,$transactionID,$transactionMasterID){
		$this->db->where('companyID', $companyID);
		$this->db->where('transactionID', $transactionID);	
		$this->db->where('transactionMasterID', $transactionMasterID);	
		$this->db->delete('tb_transaction_master_info');
		return $this->db->affected_rows(); 
   }
   function insert($data){
		$result			 		= $this->db->insert('tb_transaction_master_info', $data);
		$autoIncrement			= $this->db->insert_id(); 		
		return $result ? $autoIncrement : $result; 
   }
   function update($companyID,$transactionID,$transactionMasterID,$data){
		$this->db->where('companyID', $companyID);
		$this->db->where('transactionID', $transactionID);	
		$this->db->where('transactionMasterID', $transactionMasterID);	
		$this->db->update('tb_transaction_master_info', $data);
		return $this->db->affected_rows(); 
   }
   function get_rowByPK($companyID,$transactionID,$transactionMasterID){    
		$this->db->select("companyID,transactionID,transactionMasterID,zoneID,routeID,referenceClientName,referenceClientIdentifier,receiptAmount,receiptAmountDol,reference1,reference2,changeAmount");
		$this->db->from("tb_transaction_master_info tm");		
		$this->db->where("tm.transactionMasterID",$transactionMasterID);
		$this->db->where("tm.transactionID",$transactionID);
		$this->db->where("tm.companyID",$companyID);
		
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
<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Transaction_Master_Detail_Credit_Model extends CI_Model  {
   function __construct(){
      parent::__construct();
   }
   
   function insert($data){
		$result			 		= $this->db->insert('tb_transaction_master_detail_credit', $data);
		$autoIncrement			= $this->db->insert_id(); 		
		return $result ? $autoIncrement : $result; 
   }
   function update($transactionMasterDetailID,$data){
		$this->db->where('transactionMasterDetailID', $transactionMasterDetailID);	
		$this->db->update('tb_transaction_master_detail_credit', $data);
		return $this->db->affected_rows(); 
   }
   function delete($transactionMasterDetailID){
		$this->db->where('transactionMasterDetailID', $transactionMasterDetailID);	
		$this->db->delete('tb_transaction_master_detail_credit');
		return $this->db->affected_rows(); 
   }
   function get_rowByPK($transactionMasterDetailID){
		$this->db->select("td.transactionMasterDetailCreditID,td.transactionMasterDetailID,td.capital,td.interest,td.dayDalay,td.interestMora,td.currencyID,td.exchangeRate,td.reference1,td.reference2,td.reference3,td.reference4,td.reference5,td.reference6,td.reference7,td.reference8,td.reference9");
		$this->db->from("tb_transaction_master_detail_credit td");
		$this->db->where("td.transactionMasterDetailID",$transactionMasterDetailID);		
		
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
   function deleteWhereIDNotIn( $transactionMasterID,$listTMD_ID){	
		$this->db->where('transactionMasterID', $transactionMasterID);	   
		$this->db->where_not_in('transactionMasterDetailID', $listTMD_ID);
		$this->db->delete('tb_transaction_master_detail_credit');
		return $this->db->affected_rows(); 
   }
   
   
}
?>
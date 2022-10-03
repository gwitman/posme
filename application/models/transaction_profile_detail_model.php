<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Transaction_Profile_Detail_Model extends CI_Model  {
   function __construct(){
      parent::__construct();
   }
   function getByCompanyAndTransactionAndCausal($companyID,$transactionID,$causalID){
		$this->db->select("tc.companyID, tc.transactionID, tc.transactionCausalID, tc.profileDetailID, tc.conceptID, tc.accountID, tc.classID, tc.sign,tp.name as conceptDescription,a.accountNumber as accountDescription,cc.number as centerCostDescription");
		$this->db->from("tb_transaction_profile_detail tc");		
		$this->db->join("tb_transaction_concept tp","tc.conceptID = tp.conceptID and tc.transactionID = tp.transactionID ");	
		$this->db->join("tb_account a","tc.accountID = a.accountID and tc.companyID = a.companyID");	
		$this->db->join("tb_center_cost cc","tc.classID = cc.classID and tc.companyID = cc.companyID",'left');	
		$this->db->where("tc.companyID",$companyID);		
		$this->db->where("tc.transactionID",$transactionID);		
		$this->db->where("tc.transactionCausalID",$causalID);				
		
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
   function getByCompanyAndTransactionAndCausalAndProfileDetailID($companyID,$transactionID,$causalID,$profileDetailID){
		$this->db->select("companyID, transactionID, transactionCausalID, profileDetailID, conceptID, accountID, classID, sign");
		$this->db->from("tb_transaction_profile_detail tc");		
		$this->db->where("tc.companyID",$companyID);		
		$this->db->where("tc.transactionID",$transactionID);		
		$this->db->where("tc.transactionCausalID",$causalID);	
		$this->db->where("tc.profileDetailID",$profileDetailID);	
		
		
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
   function delete($companyID,$transactionID,$causalID,$profileDetailID){
		$this->db->where('companyID', $companyID);
		$this->db->where('transactionID', $transactionID);	
		$this->db->where('transactionCausalID', $causalID);	
		$this->db->where('profileDetailID', $profileDetailID);	
		$this->db->delete("tb_transaction_profile_detail");
		return $this->db->affected_rows(); 
   }
   function insert($data){
   		$result			 		= $this->db->insert('tb_transaction_profile_detail', $data);
		$autoIncrement			= $this->db->insert_id(); 		
		return $result ? $autoIncrement : $result; 
   }
}
?>
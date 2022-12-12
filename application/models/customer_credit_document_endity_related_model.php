<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Customer_Credit_Document_Endity_Related_Model extends CI_Model  {
   function __construct(){
      parent::__construct();
   }
   function update($customerCreditDocumentID,$entityID,$data){
        $this->db->where('customerCreditDocumentID', $customerCreditDocumentID);	
        $this->db->where('entityID', $entityID);	
		$this->db->update('tb_customer_credit_document_entity_related', $data);
		return $this->db->affected_rows(); 
   }
   function delete($customerCreditDocumentID,$entityID){		
  		$data["isActive"] = 0;
        $this->db->where('customerCreditDocumentID', $customerCreditDocumentID);	
        $this->db->where('entityID', $entityID);	
		$this->db->update('tb_customer_credit_document_entity_related', $data);
		return $this->db->affected_rows(); 
   } 
   function insert($data){
		$result			 		= $this->db->insert('tb_customer_credit_document_entity_related', $data);
		$autoIncrement			= $this->db->insert_id(); 		
		return $result ? $autoIncrement : $result; 
   }   
   function get_rowByPK($ccEntityRelatedID){
	    $this->db->select("ccEntityRelatedID,customerCreditDocumentID,entityID,type,createdOn,createdBy,createdIn,createdAt,isActive");
		$this->db->from("tb_customer_credit_document_entity_related i");	
		$this->db->where("i.ccEntityRelatedID",$ccEntityRelatedID);
		$this->db->where("i.isActive",1);		
		
		//Ejecutar Consulta
		$recordSet = $this->db->get();
		
		//Obtener errores
		if($this->db->_error_message())  
		return null; 		
		
		if($recordSet->num_rows() == 0)
		return null;
		
		//Resultado
		return  $recordSet->row();
   }
   function get_rowByEntity($customerCreditDocumentID,$entityID){    
        $this->db->select("ccEntityRelatedID,customerCreditDocumentID,entityID,type,createdOn,createdBy,createdIn,createdAt,isActive");
        $this->db->from("tb_customer_credit_document_entity_related i");	
        $this->db->where("i.customerCreditDocumentID",$customerCreditDocumentID);
        $this->db->where("i.entityID",$entityID);
        $this->db->where("i.isActive",1);		
        
        //Ejecutar Consulta
        $recordSet = $this->db->get();
        
        //Obtener errores
        if($this->db->_error_message())  
        return null; 		
        
        if($recordSet->num_rows() == 0)
        return null;
        
        //Resultado
        return  $recordSet->row();
   }
   function get_rowByDocument($customerCreditDocumentID){    
		$this->db->select("ccEntityRelatedID,customerCreditDocumentID,entityID,type,createdOn,createdBy,createdIn,createdAt,isActive");
		$this->db->from("tb_customer_credit_document_entity_related i");		
		$this->db->where("i.customerCreditDocumentID",$customerCreditDocumentID);
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
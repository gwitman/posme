<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');



class Entity_Email_Model extends CI_Model  {

   function __construct(){

      parent::__construct();

   }

    function delete($companyID,$branchID,$entityID,$entityEmailID){

		

		$this->db->where('companyID', $companyID);

		$this->db->where('branchID', $branchID);	

		$this->db->where('entityID', $entityID);	

		$this->db->where('entityEmailID', $entityEmailID);	

		$this->db->delete('tb_entity_email');

		return $this->db->affected_rows(); 

   }

   function deleteByEntity($companyID,$branchID,$entityID){

		

		$this->db->where('companyID', $companyID);

		$this->db->where('branchID', $branchID);	

		$this->db->where('entityID', $entityID);			

		$this->db->delete('tb_entity_email');

		return $this->db->affected_rows(); 

   }

   function insert($data){

		$result			 		= $this->db->insert('tb_entity_email', $data);

		$autoIncrement			= $this->db->insert_id(); 		

		return $result ? $autoIncrement : $result; 

   }

   function update($companyID,$branchID,$entityID,$entityEmailID,$data){

		$this->db->where('companyID', $companyID);

		$this->db->where('branchID', $branchID);	

		$this->db->where('entityID', $entityID);	

		$this->db->where('entityEmailID', $entityEmailID);	

		$this->db->update('tb_entity_email', $data);

		return $this->db->affected_rows(); 

   }

   function get_rowByPK($companyID,$branchID,$entityID,$entityEmailID){    

		$this->db->select("tm.companyID,tm.branchID,tm.entityID,tm.entityEmailID,tm.email,tm.isPrimary");

		$this->db->from("tb_entity_email tm");		

		$this->db->where("tm.entityEmailID",$entityEmailID);

		$this->db->where("tm.entityID",$entityID);

		$this->db->where("tm.branchID",$branchID);

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

   function get_rowByEntity($companyID,$branchID,$entityID){    

		$this->db->select("tm.companyID,tm.branchID,tm.entityID,tm.entityEmailID,tm.email,tm.isPrimary");

		$this->db->from("tb_entity_email tm");				

		$this->db->where("tm.entityID",$entityID);

		$this->db->where("tm.branchID",$branchID);

		$this->db->where("tm.companyID",$companyID);

		

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
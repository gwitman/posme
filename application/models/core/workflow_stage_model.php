<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');



class Workflow_Stage_Model extends CI_Model  {

   function __construct(){

      parent::__construct();

   }    

   function get_rowByPK($ListWorkflowStageRole){

   		$this->db->select("e.workflowID,e.componentID,e.workflowStageID,e.name,e.description,e.display,e.flavorID,e.editableParcial,e.editableTotal,e.eliminable,e.aplicable,e.vinculable,e.isActive");

		$this->db->from("tb_workflow_stage e");

		

		//Filtrar los estados

		if($ListWorkflowStageRole)

		foreach($ListWorkflowStageRole as $i){			

			$this->db->or_where("(  e.componentID = ".$i->componentID."  and e.workflowID = ".$i->workflowID." and e.workflowStageID = ".$i->workflowStageID." and e.isActive = 1 )");			

		}	

		

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

   function get_rowByWorkflowIDAndFlavorID($workflowID,$flavorID){

		$this->db->select("e.workflowID,e.componentID,e.workflowStageID,e.name,e.description,e.display,e.flavorID,e.editableParcial,e.editableTotal,e.eliminable,e.aplicable,e.vinculable,e.isActive");

		$this->db->from("tb_workflow_stage e");

		$this->db->where("e.workflowID",$workflowID);	

		$this->db->where("e.flavorID",$flavorID);	

		$this->db->where("e.isActive",1);	

		

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

   function get_rowByWorkflowStageID($workflowID,$flavorID,$workflowStageID){

		$this->db->select("e.workflowID,e.componentID,e.workflowStageID,e.name,e.description,e.display,e.flavorID,e.editableParcial,e.editableTotal,e.eliminable,e.aplicable,e.vinculable,e.isActive");

		$this->db->from("tb_workflow_stage e");

		$this->db->where("e.workflowID",$workflowID);	

		$this->db->where("e.flavorID",$flavorID);	

		$this->db->where("e.workflowStageID",$workflowStageID);	

		$this->db->where("e.isActive",1);

		

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
   function get_rowByWorkflowStageIDOnly($workflowStageID){

		$this->db->select("e.workflowID,e.componentID,e.workflowStageID,e.name,e.description,e.display,e.flavorID,e.editableParcial,e.editableTotal,e.eliminable,e.aplicable,e.vinculable,e.isActive");

		$this->db->from("tb_workflow_stage e");

		$this->db->where("e.workflowStageID",$workflowStageID);	

		$this->db->where("e.isActive",1);

		

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

   function get_rowByWorkflowIDAndFlavorID_Init($workflowID,$flavorID){

		$this->db->select("e.workflowID,e.componentID,e.workflowStageID,e.name,e.description,e.display,e.flavorID,e.editableParcial,e.editableTotal,e.eliminable,e.aplicable,e.vinculable,e.isActive");

		$this->db->from("tb_workflow_stage e");

		$this->db->where("e.workflowID",$workflowID);	

		$this->db->where("e.flavorID",$flavorID);	

		$this->db->where("e.isActive",1);	

		$this->db->where("e.isInit",1);

		

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

   function get_rowByWorkflowIDAndFlavorID_ApplyFirst($workflowID,$flavorID){

		$this->db->select("e.workflowID,e.componentID,e.workflowStageID,e.name,e.description,e.display,e.flavorID,e.editableParcial,e.editableTotal,e.eliminable,e.aplicable,e.vinculable,e.isActive");

		$this->db->from("tb_workflow_stage e");

		$this->db->where("e.workflowID",$workflowID);	

		$this->db->where("e.flavorID",$flavorID);	

		$this->db->where("e.isActive",1);	
		
		$this->db->where("e.aplicable",1);

		

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
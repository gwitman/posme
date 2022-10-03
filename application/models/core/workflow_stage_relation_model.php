<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Workflow_Stage_Relation_Model extends CI_Model  {
   function __construct(){
      parent::__construct();
   }    
   function get_rowBySourceWorkflowStageID($workflowID,$flavorID,$sourceStageID){		
		$query = $this->db->query("
		select 
			ws2.componentID			,
			ws2.workflowID			,
			ws2.workflowStageID		,
			ws2.name				,
			ws2.description			,
			ws2.display				,
			ws2.flavorID           	,
			ws2.isInit             	,
			ws2.editableParcial    	,
			ws2.editableTotal      	,
			ws2.eliminable         	,
			ws2.aplicable          	,
			ws2.vinculable         	,
			ws2.isActive           
		from 
			tb_workflow w
			inner join tb_workflow_stage ws on
					w.workflowID  = ws.workflowID and
					w.componentID = ws.componentID
			inner join tb_workflow_stage_relation wsr on
					ws.workflowID      = wsr.workflowID and
					ws.workflowStageID = wsr.workflowStageID
			inner join tb_workflow_stage ws2 on
					wsr.workflowID 				= ws2.workflowID and
					wsr.workflowStageTargetID 	= ws2.workflowStageID
		where
			w.isActive 			= 1 and
			ws.isActive 		= 1 and
			ws2.isActive 		= 1	and
			w.workflowID 		= $workflowID and
			ws.flavorID 		= $flavorID and
			ws2.flavorID 		= $flavorID and
			ws.workflowStageID 	= $sourceStageID
		");
		return $query->result();
   }
}
?>
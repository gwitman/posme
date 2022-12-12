<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Component_Audit_Detail_Model extends CI_Model  {
    function __construct(){
      parent::__construct();
    }
    function insert($data){   
		$result			 						= $this->db->insert('tb_component_audit_detail', $data);
		$componentauditdetailid 				= $this->db->insert_id(); 		
		return $result ? $componentauditdetailid : $result; 	
   }
   function getAuditDetail($companyID,$id,$elementID){				
		$query = $this->db->query("
			SELECT 
				`cu`.`modifiedOn`, `u`.`nickname`, `se`.`name`, `cud`.`oldValue`,`cud`.`newValue` as newValue
			FROM 
				(`tb_component_audit` cu)
				JOIN `tb_component_audit_detail` cud ON `cu`.`companyID` = `cud`.`companyID` and cu.componentAuditID = cud.componentAuditID
				JOIN `tb_user` u ON `cu`.`modifiedBy` = `u`.`userID`
				JOIN `tb_subelement` se ON `cud`.`fieldID` = `se`.`subElementID`
				LEFT JOIN `tb_workflow_stage` ws ON `cud`.`oldValue` = `ws`.`workflowStageID`
				LEFT JOIN `tb_workflow_stage` ws2 ON `cud`.`oldValue` = `ws2`.`workflowStageID`
				LEFT JOIN `tb_catalog_item` ci ON `cud`.`oldValue` = `ci`.`catalogItemID`
				LEFT JOIN `tb_catalog_item` ci2 ON `cud`.`oldValue` = `ci2`.`catalogItemID`
			WHERE 
				`cu`.`companyID` =  '".$companyID."'
				AND `cu`.`elementItemID` =  '".$id."'
				AND `cu`.`elementID` =  '".$elementID."'
			ORDER BY 
				`cu`.`modifiedOn` desc
		");
		
		$result = $query->result();
		return $result;
			
   }
}
?>
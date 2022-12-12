<?php if ( ! defined('BASEPATH')) exit('No se permite el acceso directo al script');
class core_web_workflow {
   
   /**********************Variables Estaticas********************/
   /*************************************************************/
   /*************************************************************/
   /*************************************************************/
	private $CI; 
	
	
   /**********************Funciones******************************/
   /*************************************************************/
   /*************************************************************/
   /*************************************************************/
   public function __construct(){		
        $this->CI = & get_instance(); 
   }
   //Obtener todos los estados
   function getWorkflowAllStage($table,$field,$companyID,$branchID,$roleID){
		$this->CI->load->model('core/Component_Model');
		$this->CI->load->model('core/Element_Model');
		$this->CI->load->model('core/Sub_Element_Model');
		$this->CI->load->model('core/Company_Component_Flavor_Model');		
		$this->CI->load->model('core/Workflow_Model');
		$this->CI->load->model('core/Workflow_Stage_Model');
		$this->CI->load->model('core/Workflow_Stage_Relation_Model');
		$this->CI->load->model('core/Role_Model');
		$this->CI->load->model('core/Role_Autorization_Model');
		
		//obtener elemento 
		$objElement 	= $this->CI->Element_Model->get_rowByName($table,ELEMENT_TYPE_TABLE);
		if(!$objElement)
		throw new Exception("NO EXISTE LA TABLA '".$table."' DENTRO DE LOS REGISTROS DE ELEMENT ");
		
		//obtener subelement
		$objSubElement 	= $this->CI->Sub_Element_Model->get_rowByNameAndElementID($objElement->elementID,$field); 
		if(!$objSubElement)
		throw new Exception("NO EXISTE EL CAMPO '".$field."' DENTRO DE LOS REGISTROS DE SUBELEMENT PARA EL ELEMENTO '".$table."' ");
		
		//obtener componente workflow
		$objComponent = $this->CI->Component_Model->get_rowByName("tb_workflow");
		if(!$objComponent)
		throw new Exception("NO EXISTE EL COMPONENTE 'tb_workflow' DENTROS DE LOS REGISTROS DE 'Component' ");
		
		//obtener el workflow
		if(!$objSubElement->workflowID)
		throw new Exception("EN LA TABLA SUBELEMENT PARA '".$field."' NO EXISTE EL WORKFLOW CONFIGURADO");
		
		$objWorkflow = $this->CI->Workflow_Model->get_rowByWorkflowID($objSubElement->workflowID);
		if(!$objWorkflow)
		throw new Exception("NO EXISTE EL WORKFLOW ");
				
		//obtener flavor
		$objCompanyComponentFlavor = $this->CI->Company_Component_Flavor_Model->get_rowByCompanyAndComponentAndComponentItemID($companyID,$objComponent->componentID,$objWorkflow->workflowID);
		if(!$objCompanyComponentFlavor)
		throw new Exception("NO EXISTE EL FLAVOR PARA EL COMPONENTE DE WORKFLOW ");
		
		
		//obtener la lista de workflowStage
		$objWorkflowStage 		= $this->CI->Workflow_Stage_Model->get_rowByWorkflowIDAndFlavorID($objWorkflow->workflowID,$objCompanyComponentFlavor->flavorID);
				
		//obtener los workflowdel usuario
		$objWorkflowStageRole 	= $this->CI->Role_Autorization_Model->get_rowByRole($companyID,$branchID,$roleID);
		
		//obtener el rol del usuario
		$objRole = $this->CI->Role_Model->get_rowByPK($companyID,$branchID,$roleID);
		
		
		if($objRole->isAdmin){
			//el usuario puede ver todos los workflow
			return $objWorkflowStage;
		} 
		else if (!$objWorkflowStageRole){
			 //el usuario no pueder ver ningun workflow
			 return $objWorkflowStageRole;
		}			
		else if (!$objWorkflowStage){
			//no hay ningun workflow
			return $objWorkflowStage;
		}
		else{
				foreach($objWorkflowStage as &$i){
					$exists = false;
					foreach($objWorkflowStageRole as $ii){									
						if($ii->workflowStageID == $i->workflowStageID)
						$exists = true;
					}		
					if(!$exists)
					$i = null;
				}				
				return $objWorkflowStage; 
		}
		
		
   }
   //Obtener el estado inicial
   function getWorkflowInitStage($table,$field,$companyID,$branchID,$roleID){
		$this->CI->load->model('core/Component_Model');
		$this->CI->load->model('core/Element_Model');
		$this->CI->load->model('core/Sub_Element_Model');
		$this->CI->load->model('core/Company_Component_Flavor_Model');		
		$this->CI->load->model('core/Workflow_Model');
		$this->CI->load->model('core/Workflow_Stage_Model');
		$this->CI->load->model('core/Workflow_Stage_Relation_Model');
		$this->CI->load->model('core/Role_Model');
		$this->CI->load->model('core/Role_Autorization_Model');
		
		
		//obtener elemento 
		$objElement 	= $this->CI->Element_Model->get_rowByName($table,ELEMENT_TYPE_TABLE);
		if(!$objElement)
		throw new Exception("NO EXISTE LA TABLA '".$table."' DENTRO DE LOS REGISTROS DE ELEMENT ");
		
		//obtener subelement
		$objSubElement 	= $this->CI->Sub_Element_Model->get_rowByNameAndElementID($objElement->elementID,$field); 
		if(!$objSubElement)
		throw new Exception("NO EXISTE EL CAMPO '".$field."' DENTRO DE LOS REGISTROS DE SUBELEMENT PARA EL ELEMENTO '".$table."' ");
		
		//obtener componente workflow
		$objComponent = $this->CI->Component_Model->get_rowByName("tb_workflow");
		if(!$objComponent)
		throw new Exception("NO EXISTE EL COMPONENTE 'tb_workflow' DENTROS DE LOS REGISTROS DE 'Component' ");
		
		//obtener el workflow
		if(!$objSubElement->workflowID)
		throw new Exception("EN LA TABLA SUBELEMENT PARA '".$field."' NO EXISTE EL WORKFLOW CONFIGURADO");
		
		$objWorkflow = $this->CI->Workflow_Model->get_rowByWorkflowID($objSubElement->workflowID);
		if(!$objWorkflow)
		throw new Exception("NO EXISTE EL WORKFLOW ");
				
		//obtener flavor
		$objCompanyComponentFlavor = $this->CI->Company_Component_Flavor_Model->get_rowByCompanyAndComponentAndComponentItemID($companyID,$objComponent->componentID,$objWorkflow->workflowID);
		if(!$objCompanyComponentFlavor)
		throw new Exception("NO EXISTE EL FLAVOR PARA EL COMPONENTE DE WORKFLOW ");
		
		//obtener la lista de workflowStage
		$objWorkflowStage 		= $this->CI->Workflow_Stage_Model->get_rowByWorkflowIDAndFlavorID_Init($objWorkflow->workflowID,$objCompanyComponentFlavor->flavorID);
		
		//obtener los workflowdel usuario
		$objWorkflowStageRole 	= $this->CI->Role_Autorization_Model->get_rowByRole($companyID,$branchID,$roleID);
		
		//obtener el rol del usuario
		$objRole 				= $this->CI->Role_Model->get_rowByPK($companyID,$branchID,$roleID);
		
		
		if($objRole->isAdmin){		
			//el usuario puede ver todos los workflow
			return $objWorkflowStage;
		}
		else if(!$objWorkflowStageRole){			
			//el usuario no pueder ver ningun workflow
			 return $objWorkflowStageRole;
		}			
		else if (!$objWorkflowStage){			
			//no hay ningun workflow
			return $objWorkflowStage;
		}
		else{
			$exists = false;
			foreach($objWorkflowStageRole as $ii){									
				if($ii->workflowStageID == $objWorkflowStage[0]->workflowStageID)
				$exists = true;
			}		
			
			if(!$exists)
			return null;
			
			return $objWorkflowStage; 
		}
	
		
   }
   //Obtener el primer estado de aplicacion
   function getWorkflowStageApplyFirst($table,$field,$companyID,$branchID,$roleID){
		$this->CI->load->model('core/Component_Model');
		$this->CI->load->model('core/Element_Model');
		$this->CI->load->model('core/Sub_Element_Model');
		$this->CI->load->model('core/Company_Component_Flavor_Model');		
		$this->CI->load->model('core/Workflow_Model');
		$this->CI->load->model('core/Workflow_Stage_Model');
		$this->CI->load->model('core/Workflow_Stage_Relation_Model');
		$this->CI->load->model('core/Role_Model');
		$this->CI->load->model('core/Role_Autorization_Model');
		
		
		//obtener elemento 
		$objElement 	= $this->CI->Element_Model->get_rowByName($table,ELEMENT_TYPE_TABLE);
		if(!$objElement)
		throw new Exception("NO EXISTE LA TABLA '".$table."' DENTRO DE LOS REGISTROS DE ELEMENT ");
		
		//obtener subelement
		$objSubElement 	= $this->CI->Sub_Element_Model->get_rowByNameAndElementID($objElement->elementID,$field); 
		if(!$objSubElement)
		throw new Exception("NO EXISTE EL CAMPO '".$field."' DENTRO DE LOS REGISTROS DE SUBELEMENT PARA EL ELEMENTO '".$table."' ");
		
		//obtener componente workflow
		$objComponent = $this->CI->Component_Model->get_rowByName("tb_workflow");
		if(!$objComponent)
		throw new Exception("NO EXISTE EL COMPONENTE 'tb_workflow' DENTROS DE LOS REGISTROS DE 'Component' ");
		
		//obtener el workflow
		if(!$objSubElement->workflowID)
		throw new Exception("EN LA TABLA SUBELEMENT PARA '".$field."' NO EXISTE EL WORKFLOW CONFIGURADO");
		
		$objWorkflow = $this->CI->Workflow_Model->get_rowByWorkflowID($objSubElement->workflowID);
		if(!$objWorkflow)
		throw new Exception("NO EXISTE EL WORKFLOW ");
				
		//obtener flavor
		$objCompanyComponentFlavor = $this->CI->Company_Component_Flavor_Model->get_rowByCompanyAndComponentAndComponentItemID($companyID,$objComponent->componentID,$objWorkflow->workflowID);
		if(!$objCompanyComponentFlavor)
		throw new Exception("NO EXISTE EL FLAVOR PARA EL COMPONENTE DE WORKFLOW ");
		
		//obtener la lista de workflowStage
		$objWorkflowStage 		= $this->CI->Workflow_Stage_Model->get_rowByWorkflowIDAndFlavorID_ApplyFirst($objWorkflow->workflowID,$objCompanyComponentFlavor->flavorID);
		
		//obtener los workflowdel usuario
		$objWorkflowStageRole 	= $this->CI->Role_Autorization_Model->get_rowByRole($companyID,$branchID,$roleID);
		
		//obtener el rol del usuario
		$objRole 				= $this->CI->Role_Model->get_rowByPK($companyID,$branchID,$roleID);
		
		
		if($objRole->isAdmin){		
			//el usuario puede ver todos los workflow
			return $objWorkflowStage;
		}
		else if(!$objWorkflowStageRole){			
			//el usuario no pueder ver ningun workflow
			 return $objWorkflowStageRole;
		}			
		else if (!$objWorkflowStage){			
			//no hay ningun workflow
			return $objWorkflowStage;
		}
		else{
			$exists = false;
			foreach($objWorkflowStageRole as $ii){									
				if($ii->workflowStageID == $objWorkflowStage[0]->workflowStageID)
				$exists = true;
			}		
			
			if(!$exists)
			return null;
			
			return $objWorkflowStage; 
		}
	
   }
   //Obtener un Estado
   function getWorkflowStage($table,$field,$stageID,$companyID,$branchID,$roleID){
		$this->CI->load->model('core/Component_Model');
		$this->CI->load->model('core/Element_Model');
		$this->CI->load->model('core/Sub_Element_Model');
		$this->CI->load->model('core/Company_Component_Flavor_Model');		
		$this->CI->load->model('core/Workflow_Model');
		$this->CI->load->model('core/Workflow_Stage_Model');
		$this->CI->load->model('core/Workflow_Stage_Relation_Model');
		$this->CI->load->model('core/Role_Model');
		$this->CI->load->model('core/Role_Autorization_Model');
		
		
		//obtener elemento 
		$objElement 	= $this->CI->Element_Model->get_rowByName($table,ELEMENT_TYPE_TABLE);
		if(!$objElement)
		throw new Exception("NO EXISTE LA TABLA '".$table."' DENTRO DE LOS REGISTROS DE ELEMENT ");
		
		//obtener subelement
		$objSubElement 	= $this->CI->Sub_Element_Model->get_rowByNameAndElementID($objElement->elementID,$field); 
		if(!$objSubElement)
		throw new Exception("NO EXISTE EL CAMPO '".$field."' DENTRO DE LOS REGISTROS DE SUBELEMENT PARA EL ELEMENTO '".$table."' ");
		
		//obtener componente workflow
		$objComponent = $this->CI->Component_Model->get_rowByName("tb_workflow");
		if(!$objComponent)
		throw new Exception("NO EXISTE EL COMPONENTE 'tb_workflow' DENTROS DE LOS REGISTROS DE 'Component' ");
		
		//obtener el workflow
		if(!$objSubElement->workflowID)
		throw new Exception("EN LA TABLA SUBELEMENT PARA '".$field."' NO EXISTE EL WORKFLOW CONFIGURADO");
		
		$objWorkflow = $this->CI->Workflow_Model->get_rowByWorkflowID($objSubElement->workflowID);
		if(!$objWorkflow)
		throw new Exception("NO EXISTE EL WORKFLOW ");
				
		//obtener flavor
		$objCompanyComponentFlavor = $this->CI->Company_Component_Flavor_Model->get_rowByCompanyAndComponentAndComponentItemID($companyID,$objComponent->componentID,$objWorkflow->workflowID);
		if(!$objCompanyComponentFlavor)
		throw new Exception("NO EXISTE EL FLAVOR PARA EL COMPONENTE DE WORKFLOW ");
		
		//obtener la lista de workflow
		$objWorkflowStage = $this->CI->Workflow_Stage_Model->get_rowByWorkflowStageID($objWorkflow->workflowID,$objCompanyComponentFlavor->flavorID,$stageID);
   		
		//obtener los workflowdel usuario
		$objWorkflowStageRole 	= $this->CI->Role_Autorization_Model->get_rowByRole($companyID,$branchID,$roleID);
		
		//obtener el rol del usuario
		$objRole 				= $this->CI->Role_Model->get_rowByPK($companyID,$branchID,$roleID);
		
		if($objRole->isAdmin){
			//el usuario puede ver todos los workflow
			return $objWorkflowStage;
		} 
		else if (!$objWorkflowStageRole){
			 //el usuario no pueder ver ningun workflow
			 return $objWorkflowStageRole;
		}			
		else if (!$objWorkflowStage){
			//no hay ningun workflow
			return $objWorkflowStage;
		}
		else{
				foreach($objWorkflowStage as &$i){
					$exists = false;
					foreach($objWorkflowStageRole as $ii){									
						if($ii->workflowStageID == $i->workflowStageID)
						$exists = true;
					}		
					if(!$exists)
					$i = null;
				}				
				return $objWorkflowStage; 
		}
		
   }
   //Obtener todos los estados destinos apartir de un estado origen
   function getWorkflowStageByStageInit($table,$field,$startStageID,$companyID,$branchID,$roleID){
		$this->CI->load->model('core/Component_Model');
		$this->CI->load->model('core/Element_Model');
		$this->CI->load->model('core/Sub_Element_Model');
		$this->CI->load->model('core/Company_Component_Flavor_Model');		
		$this->CI->load->model('core/Workflow_Model');
		$this->CI->load->model('core/Workflow_Stage_Model');
		$this->CI->load->model('core/Workflow_Stage_Relation_Model');
		$this->CI->load->model('core/Role_Model');
		$this->CI->load->model('core/Role_Autorization_Model');
		
		//obtener elemento 
		$objElement 	= $this->CI->Element_Model->get_rowByName($table,ELEMENT_TYPE_TABLE);
		if(!$objElement)
		throw new Exception("NO EXISTE LA TABLA '".$table."' DENTRO DE LOS REGISTROS DE ELEMENT ");
		
		//obtener subelement
		$objSubElement 	= $this->CI->Sub_Element_Model->get_rowByNameAndElementID($objElement->elementID,$field); 
		if(!$objSubElement)
		throw new Exception("NO EXISTE EL CAMPO '".$field."' DENTRO DE LOS REGISTROS DE SUBELEMENT PARA EL ELEMENTO '".$table."' ");
		
		//obtener componente workflow
		$objComponent = $this->CI->Component_Model->get_rowByName("tb_workflow");
		if(!$objComponent)
		throw new Exception("NO EXISTE EL COMPONENTE 'tb_workflow' DENTROS DE LOS REGISTROS DE 'Component' ");
		
		//obtener el workflow
		if(!$objSubElement->workflowID)
		throw new Exception("EN LA TABLA SUBELEMENT PARA '".$field."' NO EXISTE EL WORKFLOW CONFIGURADO");
		
		$objWorkflow = $this->CI->Workflow_Model->get_rowByWorkflowID($objSubElement->workflowID);
		if(!$objWorkflow)
		throw new Exception("NO EXISTE EL WORKFLOW ");
				
		//obtener flavor
		$objCompanyComponentFlavor = $this->CI->Company_Component_Flavor_Model->get_rowByCompanyAndComponentAndComponentItemID($companyID,$objComponent->componentID,$objWorkflow->workflowID);
		if(!$objCompanyComponentFlavor)
		throw new Exception("NO EXISTE EL FLAVOR PARA EL COMPONENTE DE WORKFLOW ");
		
		//obtener la lista de workflowStage
		$objWorkflowStage = $this->CI->Workflow_Stage_Relation_Model->get_rowBySourceWorkflowStageID($objWorkflow->workflowID,$objCompanyComponentFlavor->flavorID,$startStageID);
		
   		//obtener los workflowdel usuario
		$objWorkflowStageRole 	= $this->CI->Role_Autorization_Model->get_rowByRole($companyID,$branchID,$roleID);
		
		//obtener el rol del usuario
		$objRole 				= $this->CI->Role_Model->get_rowByPK($companyID,$branchID,$roleID);
		
		if($objRole->isAdmin){
			//el usuario puede ver todos los workflow
			return $objWorkflowStage;
		} 
		else if (!$objWorkflowStageRole){
			 //el usuario no pueder ver ningun workflow
			 return $objWorkflowStageRole;
		}			
		else if (!$objWorkflowStage){
			//no hay ningun workflow
			return $objWorkflowStage;
		}
		else{
			foreach($objWorkflowStage as &$i){
				$exists = false;
				foreach($objWorkflowStageRole as $ii){									
					if($ii->workflowStageID == $i->workflowStageID)
					$exists = true;
				}		
				if(!$exists)
				$i = null;
			}				
			return $objWorkflowStage; 
		}
		
   }  
   
   //Validar el Estado
   function validateWorkflowStage($table,$field,$stageID,$cmd,$companyID,$branchID,$roleID){
		$this->CI->load->model('core/Component_Model');
		$this->CI->load->model('core/Element_Model');
		$this->CI->load->model('core/Sub_Element_Model');
		$this->CI->load->model('core/Company_Component_Flavor_Model');		
		$this->CI->load->model('core/Workflow_Model');
		$this->CI->load->model('core/Workflow_Stage_Model');
		$this->CI->load->model('core/Workflow_Stage_Relation_Model');
		$this->CI->load->model('core/Role_Model');
		$this->CI->load->model('core/Role_Autorization_Model');
		
		//obtener el workflow
		$objWorkflowStage	= $this->getWorkflowStage($table,$field,$stageID,$companyID,$branchID,$roleID);
		if(!$objWorkflowStage)
		throw new Exception("NO EXISTE EL WORKFLOW STAGE TABLE: $table , FIELD:$field , COMPANY: $companyID, WORKFLOWSTAGEID: $stageID ");
		
		
			
		if($cmd == COMMAND_VINCULATE){
			return $objWorkflowStage[0]->vinculable;
		}
		else if($cmd == COMMAND_EDITABLE){
			return $objWorkflowStage[0]->editableParcial;
		}
		else if($cmd == COMMAND_EDITABLE_TOTAL){
			return $objWorkflowStage[0]->editableTotal;
		}
		else if($cmd == COMMAND_ELIMINABLE){
			return $objWorkflowStage[0]->eliminable;
		}
		else if($cmd == COMMAND_APLICABLE){
			return $objWorkflowStage[0]->aplicable;
		}
		else {
			return 0;
		}
		
   }
}
?>
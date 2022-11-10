<?php if ( ! defined('BASEPATH')) exit('No se permite el acceso directo al script');

class core_web_auditoria {
   
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

   
   function setAuditCreated(&$obj,$dataUser){
   			$obj["createdOn"]			= date("Y-m-d H:i:s");					
			$obj["createdBy"]			= $dataUser["user"]->userID;
			$obj["createdIn"]			= $dataUser["ip_address"];
			$obj["createdAt"]			= $dataUser["user"]->branchID;
   }
   function getAuditDetail($companyID,$id,$tableName){
		$this->CI->load->model('core/Element_Model');
		$this->CI->load->model('core/Component_Audit_Detail_Model');
		
		//Obtener Elemento
		$objElement = $this->CI->Element_Model->get_rowByName($tableName,ELEMENT_TYPE_TABLE);
		if(!$objElement)
		throw new Exception("NO EXISTE LA TABLA '".$tableName."' DENTRO DE LOS REGISTROS DE ELEMENT ");
		
		$result = $this->CI->Component_Audit_Detail_Model->getAuditDetail($companyID,$id,$objElement->elementID);
		return $result;
   }
   function setAudit($tableName,$old,$new,$session){
		$this->CI->load->model('core/Element_Model');
		$this->CI->load->model('core/Sub_Element_Model');
		$this->CI->load->model('core/Company_Model');
		$this->CI->load->model('core/Company_SubElement_Audit_Model');
		$this->CI->load->model('core/Component_Audit_Model');
		$this->CI->load->model('core/Component_Audit_Detail_Model');
		
		//Obtener Elemento
		$objElement = $this->CI->Element_Model->get_rowByName($tableName,ELEMENT_TYPE_TABLE);
		if(!$objElement)
		throw new Exception("NO EXISTE LA TABLA '".$tableName."' DENTRO DE LOS REGISTROS DE ELEMENT ");
		
		//Obtener subElementos Auditables
		$listSubElementAuditables = $this->CI->Company_SubElement_Audit_Model->listElementAudit($session["user"]->companyID,$objElement->elementID);
		if(!$listSubElementAuditables)
		return;
		
		if(gettype($old) != gettype($new))
		throw new Exception("LOS OBJET. EN LA AUDITORIA NO SON DE IGUAL TIPO");
		
		$elementSalvar 		 = array();
		$i 					 = 0;
		$columnAutoIncrement = $objElement->columnAutoIncrement;
		if(!$columnAutoIncrement)
		throw new Exception("LA TABLA NO TIENE UNA COLUMNA AUTO IDENTIFICADORA");
		
		//Recorrer los elementos auditables
		foreach ($listSubElementAuditables as $elementAuditable){
				$fielName 		= $elementAuditable->name;
				$fielID 		= $elementAuditable->subElementID;
				$fieldValueOld 	= "";
				$fieldValueNew 	= "";
				$auditar  		= false; 			
				if(is_array($old)){
					if($old[$fielName] === $new[$fielName])
					continue;
					$auditar 		= true;
					$fieldValueOld 	= $old[$fielName];
					$fieldValueNew 	= $new[$fielName];
				}
				else if(is_object($old)){
					if($old->$fielName === $new->$fielName)
					continue;					
					$auditar 		= true;
					$fieldValueOld 	= $old->$fielName;
					$fieldValueNew 	= $new->$fielName;
				}
				if($auditar){
					$elementSalvar[$i] = array("subelementid" => $fielID,"oldvalue" => $fieldValueOld,"newvalue" => $fieldValueNew);
					$i++;			
				}
		}
		
		if(!$elementSalvar)
		return;
		
		
		//Guardar el Maestro de la Auditoria
		$data["companyID"] 		= $session["user"]->companyID;
		$data["branchID"] 		= $session["user"]->branchID;
		$data["elementID"] 		= $objElement->elementID;
		$data["elementItemID"] 	= is_array($old) ? $old[$columnAutoIncrement] : $old->$columnAutoIncrement;
		$data["modifiedOn"] 	= date("Y-m-d H-i-s");
		$data["modifiedAt"] 	= $session["user"]->branchID;
		$data["modifiedIn"] 	= $session["ip_address"];
		$data["modifiedBy"] 	= $session["user"]->userID;
		$componentAuditID 		= $this->CI->Component_Audit_Model->insert($data);
		
		//Guardar el Detalle de la Auditoria
		foreach($elementSalvar as $elem_){
			$data_["companyID"]				= $data["companyID"];
			$data_["branchID"] 				= $data["branchID"];
			$data_["componentAuditID"] 		= $componentAuditID;
			$data_["fieldID"] 				= $elem_["subelementid"];
			$data_["oldValue"]				= $elem_["oldvalue"];
			$data_["newValue"]				= $elem_["newvalue"];
			$this->CI->Component_Audit_Detail_Model->insert($data_);	
		}
		
   }
}
?>
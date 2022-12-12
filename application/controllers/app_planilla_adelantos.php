<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class App_Planilla_Adelantos extends CI_Controller {
	
    public function __construct() {
       parent::__construct();
    }
	function searchTransactionMaster(){
		try{ 
			//AUTENTICADO
			if(!$this->core_web_authentication->isAuthenticated())
			throw new Exception(USER_NOT_AUTENTICATED);
			$dataSession		= $this->session->all_userdata();
			
			//PERMISO SOBRE LA FUNCTION
			if(APP_NEED_AUTHENTICATION == true){
						$permited = false;
						$permited = $this->core_web_permission->urlPermited($this->router->class,"index",$this->config->item('url_suffix'),$dataSession["menuTop"],$dataSession["menuLeft"],$dataSession["menuBodyReport"],$dataSession["menuBodyTop"],$dataSession["menuHiddenPopup"]);
						
						if(!$permited)
						throw new Exception(NOT_ACCESS_CONTROL);
						
						$resultPermission		= $this->core_web_permission->urlPermissionCmd($this->router->class,"index",$this->config->item('url_suffix'),$dataSession,$dataSession["menuTop"],$dataSession["menuLeft"],$dataSession["menuBodyReport"],$dataSession["menuBodyTop"],$dataSession["menuHiddenPopup"]);
						if ($resultPermission 	== PERMISSION_NONE)
						throw new Exception(NOT_ACCESS_FUNCTION);			
			
			}	
			
			//Load Modelos
			//
			////////////////////////////////////////
			////////////////////////////////////////
			////////////////////////////////////////
			$this->load->model("Transaction_Master_Model");  
			$transactionNumber 	= $this->input->post("transactionNumber");
			
			
			if(!$transactionNumber){
					throw new Exception(NOT_PARAMETER);			
			} 			
			$objTM 	= $this->Transaction_Master_Model->get_rowByTransactionNumber($dataSession["user"]->companyID,$transactionNumber);	
			
			if(!$objTM)
			throw new Exception("NO SE ENCONTRO EL DOCUMENTO");	
			
			
			$this->output->set_content_type('application/json');
			$this->output->set_output(json_encode(array(
				'error'   				=> false,
				'message' 				=> SUCCESS,
				'companyID' 			=> $objTM->companyID,
				'transactionID'			=> $objTM->transactionID,
				'transactionMasterID'	=> $objTM->transactionMasterID
			)));
			
		}
		catch(Exception $ex){
			$this->output->set_content_type('application/json');
			$this->output->set_output(json_encode(array(
				'error'   => true,
				'message' => $ex->getLine()." ".$ex->getMessage()
			)));
		}
	}
    function edit(){ 
		 try{ 
			//AUTENTICADO
			if(!$this->core_web_authentication->isAuthenticated())
			throw new Exception(USER_NOT_AUTENTICATED);
			$dataSession		= $this->session->all_userdata();
			
			//PERMISO SOBRE LA FUNCTION
			if(APP_NEED_AUTHENTICATION == true){
						$permited = false;
						$permited = $this->core_web_permission->urlPermited($this->router->class,"index",$this->config->item('url_suffix'),$dataSession["menuTop"],$dataSession["menuLeft"],$dataSession["menuBodyReport"],$dataSession["menuBodyTop"],$dataSession["menuHiddenPopup"]);
						
						if(!$permited)
						throw new Exception(NOT_ACCESS_CONTROL);
						
						$resultPermission		= $this->core_web_permission->urlPermissionCmd($this->router->class,"edit",$this->config->item('url_suffix'),$dataSession,$dataSession["menuTop"],$dataSession["menuLeft"],$dataSession["menuBodyReport"],$dataSession["menuBodyTop"],$dataSession["menuHiddenPopup"]);
						if ($resultPermission 	== PERMISSION_NONE)
						throw new Exception(NOT_ALL_EDIT);			

			
			}	
			
			//Cargar Librerias
			$this->load->model("Company_Currency_Model");
			$this->load->model("Component_Cycle_Model");
			$this->load->model("Employee_Calendar_Pay_Detail_Model");
			$this->load->model("Employee_Calendar_Pay_Model");
			$this->load->model("Transaction_Master_Model"); 			
			$this->load->model("Transaction_Master_Detail_Model");
			$this->load->model("Employee_Model");
			$this->load->model("Natural_Model");
			
			//Obtener el componente de Item
			$objComponentCalendarPay	= $this->core_web_tools->getComponentIDBy_ComponentName("tb_transaction_master_rrhh_adelantos");
			if(!$objComponentCalendarPay)
			throw new Exception("EL COMPONENTE 'tb_transaction_master_rrhh_adelantos' NO EXISTE...");
		
			$objComponentEmployee	= $this->core_web_tools->getComponentIDBy_ComponentName("tb_employee");
			if(!$objComponentEmployee)
			throw new Exception("EL COMPONENTE 'tb_employee' NO EXISTE...");
			
			
			//Redireccionar datos
			$uri					= $this->uri->uri_to_assoc(3);
			$companyID				= $uri["companyID"];
			$transactionID			= $uri["transactionID"];
			$transactionMasterID	= $uri["transactionMasterID"];
			$companyID 				= $dataSession["user"]->companyID;
			$branchID 				= $dataSession["user"]->branchID;
			$roleID 				= $dataSession["role"]->roleID;			
			$userID					= $dataSession["user"]->userID;
			
			if((!$companyID) || (!$transactionID) || (!$transactionMasterID)  )
			{ 
				redirect('app_planilla_adelantos/add');	
			} 		
			
			$dataView["companyID"]				= $dataSession["user"]->companyID;
			$dataView["userID"]					= $dataSession["user"]->userID;
			$dataView["userName"]				= $dataSession["user"]->nickname;
			$dataView["roleID"]					= $dataSession["role"]->roleID;
			$dataView["roleName"]				= $dataSession["role"]->name;
			$dataView["branchID"]				= $dataSession["branch"]->branchID;
			$dataView["branchName"]				= $dataSession["branch"]->name;
			
			
			log_message("ERROR","punto de interrupcion app_planilla_adelantos->edit->001");
			$dataView["objComponentCalendarPay"]= $objComponentCalendarPay;
			log_message("ERROR","punto de interrupcion app_planilla_adelantos->edit->002");
			$dataView["objComponentEmployee"]	= $objComponentEmployee;
			$dataView["objTM"]					= $this->Transaction_Master_Model->get_rowByPK($companyID,$transactionID,$transactionMasterID);
			$dataView["objTMD"]					= $this->Transaction_Master_Detail_Model->get_rowByTransaction($companyID,$transactionID,$transactionMasterID);
			$dataView["objTMDNatural"]			= NULL;
			$dataView["objTMDEmployee"]			= NULL;
			$dataView["objListCycle"]			= $this->Component_Cycle_Model->get_rowByCycleID($dataView["objTM"]->reference2);
			log_message("ERROR","punto de interrupcion app_planilla_adelantos->edit->003");
			$dataView["objListType"]			= $this->core_web_catalog->getCatalogAllItem("tb_transaction_master_rrhh_adelantos","typeID",$dataView["companyID"]);
			$dataView["objListCurrency"]		= $this->Company_Currency_Model->getByCompany($dataView["companyID"]);
			$dataView["objListWorkflowStage"]	= $this->core_web_workflow->getWorkflowStageByStageInit("tb_transaction_master_rrhh_adelantos","statusID",$dataView["objTM"]->statusID,$companyID,$branchID,$roleID);
			log_message("ERROR","punto de interrupcion app_planilla_adelantos->edit->004");
			
			$objTMDEmployee	= NULL;
			$objTMDNatural 	= NULL;
			//Obtener Lista de Colaboradores
			if($dataView["objTMD"])
			foreach($dataView["objTMD"] as $key => $value ){
				log_message("ERROR","punto de interrupcion app_planilla_adelantos->edit->005");
				$objTMDEmployee[$key]		= $this->Employee_Model->get_rowByPK($companyID,$branchID,$dataView["objTMD"][$key]->componentItemID);
				log_message("ERROR","punto de interrupcion app_planilla_adelantos->edit->006");
				$objTMDNatural[$key] 		= $this->Natural_Model->get_rowByPK($companyID,$branchID,$dataView["objTMD"][$key]->componentItemID);
				log_message("ERROR","punto de interrupcion app_planilla_adelantos->edit->007");
			}
			
			$dataView["objTMDNatural"]			= $objTMDNatural;
			$dataView["objTMDEmployee"]			= $objTMDEmployee;
			log_message("ERROR","punto de interrupcion app_planilla_adelantos->edit->008");
			//Renderizar Resultado 
			$dataSession["notification"]	= $this->core_web_error->get_error($dataSession["user"]->userID);
			$dataSession["message"]			= $this->core_web_notification->get_message();
			$dataSession["head"]			= $this->load->view('app_planilla_adelantos/edit_head',$dataView,true);
			$dataSession["body"]			= $this->load->view('app_planilla_adelantos/edit_body',$dataView,true);
			$dataSession["script"]			= $this->load->view('app_planilla_adelantos/edit_script',$dataView,true);
			$dataSession["footer"]			= "";
			$this->load->view("core_masterpage/default_masterpage",$dataSession);	
			
		}
		catch(Exception $ex){
			show_error($ex->getLine()." ".$ex->getMessage() ,500 );
		}	
	}	
	function delete(){
		try{ 
			//AUTENTICADO
			if(!$this->core_web_authentication->isAuthenticated())
			throw new Exception(USER_NOT_AUTENTICATED);
			$dataSession		= $this->session->all_userdata();
			
			//PERMISO SOBRE LA FUNCTION
			if(APP_NEED_AUTHENTICATION == true){
						$permited = false;
						$permited = $this->core_web_permission->urlPermited($this->router->class,"index",$this->config->item('url_suffix'),$dataSession["menuTop"],$dataSession["menuLeft"],$dataSession["menuBodyReport"],$dataSession["menuBodyTop"],$dataSession["menuHiddenPopup"]);
						
						if(!$permited)
						throw new Exception(NOT_ACCESS_CONTROL);
						
						$resultPermission		= $this->core_web_permission->urlPermissionCmd($this->router->class,"delete",$this->config->item('url_suffix'),$dataSession,$dataSession["menuTop"],$dataSession["menuLeft"],$dataSession["menuBodyReport"],$dataSession["menuBodyTop"],$dataSession["menuHiddenPopup"]);
						if ($resultPermission 	== PERMISSION_NONE)
						throw new Exception(NOT_ALL_DELETE);			
			
			}	
			
			//Load Modelos
			//
			////////////////////////////////////////
			////////////////////////////////////////
			////////////////////////////////////////
			$this->load->model("Transaction_Master_Model");
			$this->load->model("Transaction_Master_Detail_Model");	
			$this->load->model('core/Bd_Model');
			
			//Nuevo Registro
			$companyID 				= $this->input->post("companyID");
			$transactionID 			= $this->input->post("transactionID");				
			$transactionMasterID 	= $this->input->post("transactionMasterID");				
			
			
			if((!$companyID && !$transactionID && !$transactionMasterID)){
					throw new Exception(NOT_PARAMETER);								 
			} 
			
			$objTM	 				= $this->Transaction_Master_Model->get_rowByPK($companyID,$transactionID,$transactionMasterID);			
			if ($resultPermission 	== PERMISSION_ME && ($objTM->createdBy != $dataSession["user"]->userID))
			throw new Exception(NOT_DELETE);
			
			if($this->core_web_accounting->cycleIsCloseByDate($companyID,$objTM->transactionOn))
			throw new Exception("EL DOCUMENTO NO PUEDE ELIMINARSE, EL CICLO CONTABLE ESTA CERRADO");
				

			//Si el documento esta aplicado crear el contra documento			
			if( !$this->core_web_workflow->validateWorkflowStage("tb_transaction_master_rrhh_adelantos","statusID",$objTM->statusID,COMMAND_ELIMINABLE,$dataSession["user"]->companyID,$dataSession["user"]->branchID,$dataSession["role"]->roleID))
			throw new Exception(NOT_WORKFLOW_DELETE);
			
			
			//Actualizar Planilla.
			if($this->core_web_workflow->validateWorkflowStage("tb_transaction_master_rrhh_adelantos","statusID",$objTM->statusID,COMMAND_APLICABLE,$dataSession["user"]->companyID,$dataSession["user"]->branchID,$dataSession["role"]->roleID)){
				$query			= "CALL pr_planilla_remove_adelanto ('".$objTM->companyID."','".$objTM->transactionID."','".$objTM->transactionMasterID."');";			
				$resultQuery	= $this->Bd_Model->executeRender($query);	
			}
			
			//Eliminar el Registro			
			$this->Transaction_Master_Model->delete($companyID,$transactionID,$transactionMasterID);
			$this->Transaction_Master_Detail_Model->deleteWhereTM($companyID,$transactionID,$transactionMasterID);			

			$this->output->set_content_type('application/json');
			$this->output->set_output(json_encode(array(
				'error'   => false,
				'message' => SUCCESS
			)));
			
			
			
		}
		catch(Exception $ex){
			$this->output->set_content_type('application/json');
			$this->output->set_output(json_encode(array(
				'error'   => true,
				'message' => $ex->getLine()." ".$ex->getMessage()
			)));
			$this->core_web_notification->set_message(true,$ex->getLine()." ".$ex->getMessage());
		}		
			
	}
	function updateElement($dataSession){
		try{
			//PERMISO SOBRE LA FUNCTION
			if(APP_NEED_AUTHENTICATION == true){
				$permited = false;
				$permited = $this->core_web_permission->urlPermited($this->router->class,"index",$this->config->item('url_suffix'),$dataSession["menuTop"],$dataSession["menuLeft"],$dataSession["menuBodyReport"],$dataSession["menuBodyTop"],$dataSession["menuHiddenPopup"]);
				
				if(!$permited)
				throw new Exception(NOT_ACCESS_CONTROL);
				
				$resultPermission		= $this->core_web_permission->urlPermissionCmd($this->router->class,"edit",$this->config->item('url_suffix'),$dataSession,$dataSession["menuTop"],$dataSession["menuLeft"],$dataSession["menuBodyReport"],$dataSession["menuBodyTop"],$dataSession["menuHiddenPopup"]);
				if ($resultPermission 	== PERMISSION_NONE)
				throw new Exception(NOT_ALL_EDIT);	
			}
			
			$this->load->model("Employee_Calendar_Pay_Detail_Model");
			$this->load->model("Employee_Calendar_Pay_Model");			
			$this->load->model("Company_Currency_Model");
			$this->load->model("Component_Cycle_Model");
			$this->load->model('core/Bd_Model');
			
			$this->load->model("Transaction_Model");	
			$this->load->model("Transaction_Master_Model");
			$this->load->model("Transaction_Master_Detail_Model");	
			$this->load->model("Transaction_Master_Concept_Model");	
			
			//Obtener el Componente de Transacciones Other Input to Inventory
			$objComponent							= $this->core_web_tools->getComponentIDBy_ComponentName("tb_transaction_master_rrhh_adelantos");
			if(!$objComponent)
			throw new Exception("EL COMPONENTE 'tb_transaction_master_rrhh_adelantos' NO EXISTE...");
		
			$objComponentEmployee							= $this->core_web_tools->getComponentIDBy_ComponentName("tb_employee");
			if(!$objComponentEmployee)
			throw new Exception("EL COMPONENTE 'tb_employee' NO EXISTE...");
			
			//Obtener el Ciclo
			
			$objCycle 							= $this->Component_Cycle_Model->get_rowByCycleID($this->input->post("txtCycleID"));
			
			if($this->core_web_accounting->cycleIsCloseByDate($dataSession["user"]->companyID,$objCycle->startOn))
			throw new Exception("EL DOCUMENTO NO PUEDE INGRESAR, EL CICLO CONTABLE ESTA CERRADO");
			
			
			//Obtener transaccion
			$transactionMasterID					= $this->input->post("txtTransactionMasterID");			
			$transactionID 							= $this->core_web_transaction->getTransactionID($dataSession["user"]->companyID,"tb_transaction_master_rrhh_adelantos",0);
			$companyID 								= $dataSession["user"]->companyID;
			$objT 									= $this->Transaction_Model->getByCompanyAndTransaction($dataSession["user"]->companyID,$transactionID);
			$objTransactionMaster					= $this->Transaction_Master_Model->get_rowByPK($companyID,$transactionID,$transactionMasterID);
			
			
			$branchID 								= $dataSession["user"]->branchID;
			$roleID 								= $dataSession["role"]->roleID;
			$companyID 								= $dataSession["user"]->companyID;
			$userID 								= $dataSession["user"]->userID;
			
			$objTM["companyID"]						= $companyID;
			$objTM["transactionID"]					= $transactionID;
			$objTM["statusIDChangeOn"]				= date("Y-m-d H:m:s");			
			$objTM["note"] 							= $this->input->post("txtNote",'');
			$objTM["currencyID"]					= $this->input->post("txtCurrencyID",'');
			$objTM["currencyID2"]					= $this->core_web_currency->getTarget($companyID,$objTM["currencyID"]);
			$objTM["exchangeRate"]					= $this->core_web_currency->getRatio($dataSession["user"]->companyID,date("Y-m-d"),1,$objTM["currencyID"],$objTM["currencyID2"]);
			$objTM["reference1"] 					= $this->input->post("txtTypeID");
			$objTM["reference2"] 					= $this->input->post("txtCycleID");
			$objTM["descriptionReference"] 			= "reference1:Tipo de Adelanto,reference2: Ciclo contable del adelanto";
			$objTM["statusID"] 						= $this->input->post("txtStatusID");
			
			
			//Validar Edicion por el Usuario
			if ($resultPermission 	== PERMISSION_ME && ($objTransactionMaster->createdBy != $userID))
			throw new Exception(NOT_EDIT);
			
			//Validar si el estado permite editar
			if(!$this->core_web_workflow->validateWorkflowStage("tb_transaction_master_rrhh_adelantos","statusID",$objTransactionMaster->statusID,COMMAND_EDITABLE_TOTAL,$dataSession["user"]->companyID,$dataSession["user"]->branchID,$dataSession["role"]->roleID))
			throw new Exception(NOT_WORKFLOW_EDIT);					
			
			if($this->core_web_accounting->cycleIsCloseByDate($companyID,$objCycle->startOn))
			throw new Exception("EL DOCUMENTO NO PUEDE ACTUALIZARCE, EL CICLO CONTABLE ESTA CERRADO");
			
			
			$this->db->trans_begin();			
			//El Estado solo permite editar el workflow
			if($this->core_web_workflow->validateWorkflowStage("tb_transaction_master_rrhh_adelantos","statusID",$objTransactionMaster->statusID,COMMAND_EDITABLE,$dataSession["user"]->companyID,$dataSession["user"]->branchID,$dataSession["role"]->roleID)){
				$objTMNew								= array();
				$objTMNew["statusID"] 					= $this->input->post("txtStatusID");
				$this->Transaction_Master_Model->update($companyID,$transactionID,$transactionMasterID,$objTMNew);
			}
			else{
				$this->Transaction_Master_Model->update($companyID,$transactionID,$transactionMasterID,$objTM);
			}
			
			
			//Recorrer la lista del detalle del documento
			$arrayListTransactionMasterDetailID	= $this->input->post("txtTransactionMasterDetailID");
			$arrayListEmployeeID				= $this->input->post("txtEmployeeID");
			$arrayListNeto						= $this->input->post("txtNeto");
			
			//Eliminar Para Crear Nuevamente.
			$this->Transaction_Master_Detail_Model->deleteWhereIDNotIn($companyID,$transactionID,$transactionMasterID,$arrayListTransactionMasterDetailID);
			$Total		= 0;
			
			if(!empty($arrayListTransactionMasterDetailID)){
				foreach($arrayListTransactionMasterDetailID as $key => $value){
					$transactionMasterDetailID	= $value;
					$EmployeeID					= $arrayListEmployeeID[$key];
					$Neto 						= helper_StringToNumber($arrayListNeto[$key]);
					log_message("ERROR","punto de interrupcion **variable neto:");
					//Nuevo Detalle
					if($transactionMasterDetailID == 0){	
						$objTMD["companyID"] 					= $objTM["companyID"];
						$objTMD["transactionID"] 				= $objTM["transactionID"];
						$objTMD["transactionMasterID"] 			= $transactionMasterID;
						$objTMD["componentID"]					= $objComponentEmployee->componentID;
						$objTMD["componentItemID"] 				= $EmployeeID;
						$objTMD["quantity"] 					= 1;
						$objTMD["unitaryCost"]					= 1;
						$objTMD["cost"] 						= 1;
						
						$objTMD["unitaryAmount"]				= 0;
						$objTMD["amount"] 						= $Neto;									
						$objTMD["discount"]						= 0;
						$objTMD["unitaryPrice"]					= 0;
						$objTMD["promotionID"] 					= 0;
						
						$objTMD["reference1"]					= '';
						$objTMD["reference2"]					= '';
						$objTMD["reference3"]					= '';
						$objTMD["catalogStatusID"]				= 0;
						$objTMD["inventoryStatusID"]			= 0;
						$objTMD["isActive"]						= 1;
						$objTMD["quantityStock"]				= 0;
						$objTMD["quantiryStockInTraffic"]		= 0;
						$objTMD["quantityStockUnaswared"]		= 0;
						$objTMD["remaingStock"]					= 0;
						$objTMD["expirationDate"]				= NULL;
						$objTMD["inventoryWarehouseSourceID"]	= NULL;
						$objTMD["inventoryWarehouseTargetID"]	= NULL;
						$Total 									= $Total + $objTMD["amount"];
						$EntityID								= $EmployeeID;
						$this->Transaction_Master_Detail_Model->insert($objTMD);
					}					
					//Editar Detalle
					else{						
						$objTMD 						= $this->Transaction_Master_Detail_Model->get_rowByPK($companyID,$transactionID,$transactionMasterID,$transactionMasterDetailID);
						$objTMDNew["amount"]			= $Neto;
						$objTMDNew["componentItemID"] 	= $EmployeeID;
						
						$Total 							= $Total + $objTMDNew["amount"];
						$EntityID						= $EmployeeID;
						$this->Transaction_Master_Detail_Model->update($companyID,$transactionID,$transactionMasterID,$transactionMasterDetailID,$objTMDNew);						
					}
					
					
				}
			}			
			
			//Actualizar Transaccion
			$objTM["amount"] 	= $Total;
			$objTM["entityID"] 	= $EntityID;
			$this->Transaction_Master_Model->update($companyID,$transactionID,$transactionMasterID,$objTM);
			
			
			//Aplicar el Documento?
			if( $this->core_web_workflow->validateWorkflowStage("tb_transaction_master_rrhh_adelantos","statusID",$objTM["statusID"],COMMAND_APLICABLE,$dataSession["user"]->companyID,$dataSession["user"]->branchID,$dataSession["role"]->roleID) &&  $objTransactionMaster->statusID != $objTM["statusID"] ){
				
				//Actualizar Planilla.
				$query			= "CALL pr_planilla_update_adelanto ('".$companyID."','".$transactionID."','".$transactionMasterID."');";			
				$resultQuery	= $this->Bd_Model->executeRender($query);	
				
				//Crear Conceptos
				$this->core_web_concept->salaryAdvance($companyID,$transactionID,$transactionMasterID);
			
			}
			
			
			if($this->db->trans_status() !== false){
				$this->db->trans_commit();						
				$this->core_web_notification->set_message(false,SUCCESS);
				redirect('app_planilla_adelantos/edit/companyID/'.$companyID.'/transactionID/'.$transactionID.'/transactionMasterID/'.$transactionMasterID);
			}
			else{
				$this->db->trans_rollback();						
				$this->core_web_notification->set_message(true,$this->db->_error_message());
				redirect('app_planilla_adelantos/add');	
			}
			
		}
		catch(Exception $ex){			
			show_error($ex->getLine()." ".$ex->getMessage() ,500 );
		}
		
	}
	function insertElement($dataSession){
		try{
			//PERMISO SOBRE LA FUNCTION
			if(APP_NEED_AUTHENTICATION == true){
				$permited = false;
				$permited = $this->core_web_permission->urlPermited($this->router->class,"index",$this->config->item('url_suffix'),$dataSession["menuTop"],$dataSession["menuLeft"],$dataSession["menuBodyReport"],$dataSession["menuBodyTop"],$dataSession["menuHiddenPopup"]);
				
				if(!$permited)
				throw new Exception(NOT_ACCESS_CONTROL);
				
				$resultPermission		= $this->core_web_permission->urlPermissionCmd($this->router->class,"add",$this->config->item('url_suffix'),$dataSession,$dataSession["menuTop"],$dataSession["menuLeft"],$dataSession["menuBodyReport"],$dataSession["menuBodyTop"],$dataSession["menuHiddenPopup"]);
				if ($resultPermission 	== PERMISSION_NONE)
				throw new Exception(NOT_ALL_INSERT);	
			}
			
			
			$this->load->model("Employee_Calendar_Pay_Detail_Model");
			$this->load->model("Employee_Calendar_Pay_Model");			
			$this->load->model("Company_Currency_Model");
			$this->load->model("Component_Cycle_Model");
			$this->load->model('core/Bd_Model');
			
			$this->load->model("Transaction_Model");	
			$this->load->model("Transaction_Master_Model");
			$this->load->model("Transaction_Master_Detail_Model");	
			$this->load->model("Transaction_Master_Concept_Model");	
			
			$this->core_web_permission->getValueLicense($dataSession["user"]->companyID,$this->router->class."/"."index");
			//Obtener el Componente de Transacciones Other Input to Inventory
			$objComponent							= $this->core_web_tools->getComponentIDBy_ComponentName("tb_transaction_master_rrhh_adelantos");
			if(!$objComponent)
			throw new Exception("EL COMPONENTE 'tb_transaction_master_rrhh_adelantos' NO EXISTE...");
		
			$objComponentEmployee							= $this->core_web_tools->getComponentIDBy_ComponentName("tb_employee");
			if(!$objComponentEmployee)
			throw new Exception("EL COMPONENTE 'tb_employee' NO EXISTE...");
			
			//Obtener el Ciclo
			$objCycle 							= $this->Component_Cycle_Model->get_rowByCycleID($this->input->post("txtCycleID"));
			
			if($this->core_web_accounting->cycleIsCloseByDate($dataSession["user"]->companyID,$objCycle->startOn))
			throw new Exception("EL DOCUMENTO NO PUEDE INGRESAR, EL CICLO CONTABLE ESTA CERRADO");
			
			
			//Obtener transaccion
			$transactionID 							= $this->core_web_transaction->getTransactionID($dataSession["user"]->companyID,"tb_transaction_master_rrhh_adelantos",0);
			$companyID 								= $dataSession["user"]->companyID;
			$objT 									= $this->Transaction_Model->getByCompanyAndTransaction($dataSession["user"]->companyID,$transactionID);
			
			$objTM["companyID"] 					= $dataSession["user"]->companyID;
			$objTM["transactionID"] 				= $transactionID;			
			$objTM["branchID"]						= $dataSession["user"]->branchID;
			$objTM["transactionNumber"]				= $this->core_web_counter->goNextNumber($dataSession["user"]->companyID,$dataSession["user"]->branchID,"tb_transaction_master_rrhh_adelantos",0);
			$objTM["transactionCausalID"] 			= $this->core_web_transaction->getDefaultCausalID($objTM["companyID"],$objTM["transactionID"]);
			$objTM["transactionOn"]					= date("Y-m-d H:m:s");
			$objTM["statusIDChangeOn"]				= date("Y-m-d H:m:s");
			$objTM["componentID"] 					= $objComponent->componentID;
			$objTM["note"] 							= $this->input->post("txtNote",'');
			$objTM["sign"] 							= $objT->signInventory;
			$objTM["currencyID"]					= $this->input->post("txtCurrencyID",'');
			$objTM["currencyID2"]					= $this->core_web_currency->getTarget($companyID,$objTM["currencyID"]);
			$objTM["exchangeRate"]					= $this->core_web_currency->getRatio($dataSession["user"]->companyID,date("Y-m-d"),1,$objTM["currencyID"],$objTM["currencyID2"]);
			$objTM["reference1"] 					= $this->input->post("txtTypeID");
			$objTM["reference2"] 					= $this->input->post("txtCycleID");
			$objTM["reference3"] 					= "";
			$objTM["reference4"] 					= "";
			$objTM["descriptionReference"] 			= "reference1:Tipo de Adelanto,reference2: Ciclo contable del adelanto";
			$objTM["statusID"] 						= $this->input->post("txtStatusID");
			$objTM["amount"] 						= 0;
			$objTM["isApplied"] 					= 0;
			$objTM["journalEntryID"] 				= 0;
			$objTM["entityID"] 						= 0;
			$objTM["classID"] 						= NULL;
			$objTM["areaID"] 						= NULL;
			$objTM["sourceWarehouseID"]				= NULL;
			$objTM["targetWarehouseID"]				= NULL;
			$objTM["isActive"]						= 1;
			$this->core_web_auditoria->setAuditCreated($objTM,$dataSession);			
			
			
			$this->db->trans_begin();
			$transactionMasterID = $this->Transaction_Master_Model->insert($objTM);
			
			//Crear la Carpeta para almacenar los Archivos del Documento
			mkdir(PATH_FILE_OF_APP."/company_".$objTM["companyID"]."/component_".$objComponent->componentID."/component_item_".$transactionMasterID, 0700);
			
			//Recorrer la lista del detalle del documento
			$arrayListTransactionMasterDetailID	= $this->input->post("txtTransactionMasterDetailID");
			$arrayListEmployeeID				= $this->input->post("txtEmployeeID");			
			$arrayListNeto						= $this->input->post("txtNeto");
			$Total								= 0;
			$EntityID							= 0;
			if(!empty($arrayListTransactionMasterDetailID)){
				foreach($arrayListTransactionMasterDetailID as $key => $value){
					$transactionMasterDetailID	= $value;
					$EmployeeID					= $arrayListEmployeeID[$key];
					$Neto 						= $arrayListNeto[$key];
					
					$objTMD["companyID"] 					= $objTM["companyID"];
					$objTMD["transactionID"] 				= $objTM["transactionID"];
					$objTMD["transactionMasterID"] 			= $transactionMasterID;
					$objTMD["componentID"]					= $objComponentEmployee->componentID;
					$objTMD["componentItemID"] 				= $EmployeeID;
					$objTMD["quantity"] 					= 1;
					$objTMD["unitaryCost"]					= 1;
					$objTMD["cost"] 						= 1;
					
					$objTMD["unitaryAmount"]				= 0;
					$objTMD["amount"] 						= $Neto;									
					$objTMD["discount"]						= 0;
					$objTMD["unitaryPrice"]					= 0;
					$objTMD["promotionID"] 					= 0;
					
					$objTMD["reference1"]					= '';
					$objTMD["reference2"]					= '';
					$objTMD["reference3"]					= '';
					$objTMD["catalogStatusID"]				= 0;
					$objTMD["inventoryStatusID"]			= 0;
					$objTMD["isActive"]						= 1;
					$objTMD["quantityStock"]				= 0;
					$objTMD["quantiryStockInTraffic"]		= 0;
					$objTMD["quantityStockUnaswared"]		= 0;
					$objTMD["remaingStock"]					= 0;
					$objTMD["expirationDate"]				= NULL;
					$objTMD["inventoryWarehouseSourceID"]	= NULL;
					$objTMD["inventoryWarehouseTargetID"]	= NULL;
					$Total 									= $Total + $objTMD["amount"];
					$EntityID								= $EmployeeID;
					$this->Transaction_Master_Detail_Model->insert($objTMD);
				}
			}
			
			//Actualizar Transaccion
			$objTM["amount"] 	= $Total;
			$objTM["entityID"] 	= $EntityID;
			$this->Transaction_Master_Model->update($companyID,$transactionID,$transactionMasterID,$objTM);
			
			
			if($this->db->trans_status() !== false){
				$this->db->trans_commit();						
				$this->core_web_notification->set_message(false,SUCCESS);
				redirect('app_planilla_adelantos/edit/companyID/'.$companyID.'/transactionID/'.$transactionID.'/transactionMasterID/'.$transactionMasterID);
			}
			else{
				$this->db->trans_rollback();						
				$this->core_web_notification->set_message(true,$this->db->_error_message());
				redirect('app_planilla_adelantos/add');	
			}
			
			
		}
		catch(Exception $ex){
			show_error($ex->getLine()." ".$ex->getMessage() ,500 );
		}	
	}
	function save($mode){
		 try{ 
			//AUTENTICADO
			if(!$this->core_web_authentication->isAuthenticated())
			throw new Exception(USER_NOT_AUTENTICATED);
			$dataSession		= $this->session->all_userdata();
			
			//Validar Formulario						
			$this->form_validation->set_rules("txtCycleID","Ciclo","required");
			
			 //Validar Formulario
			if(!$this->form_validation->run()){
				$stringValidation = $this->core_web_tools->formatMessageError(validation_errors());
				$this->core_web_notification->set_message(true,$stringValidation);
				redirect('app_planilla_adelantos/add');
				exit;
			} 
			
			//Guardar o Editar Registro						
			if($mode == "new"){
				$this->insertElement($dataSession);
			}
			else if ($mode == "edit"){
				$this->updateElement($dataSession);
			}
			else{
				$stringValidation = "El modo de operacion no es correcto (new|edit)";
				$this->core_web_notification->set_message(true,$stringValidation);
				redirect('app_planilla_adelantos/add');
				exit;
			}
			
			
		}
		catch(Exception $ex){
			show_error($ex->getLine()." ".$ex->getMessage() ,500 );
		}		
			
	}
	
	function add(){ 
	
		try{ 
			//AUTENTICADO
			if(!$this->core_web_authentication->isAuthenticated())
			throw new Exception(USER_NOT_AUTENTICATED);
			$dataSession		= $this->session->all_userdata();
			
			//PERMISO SOBRE LA FUNCTION
			if(APP_NEED_AUTHENTICATION == true){
						$permited = false;
						$permited = $this->core_web_permission->urlPermited($this->router->class,"index",$this->config->item('url_suffix'),$dataSession["menuTop"],$dataSession["menuLeft"],$dataSession["menuBodyReport"],$dataSession["menuBodyTop"],$dataSession["menuHiddenPopup"]);
						
						if(!$permited)
						throw new Exception(NOT_ACCESS_CONTROL);
						
						$resultPermission		= $this->core_web_permission->urlPermissionCmd($this->router->class,"add",$this->config->item('url_suffix'),$dataSession,$dataSession["menuTop"],$dataSession["menuLeft"],$dataSession["menuBodyReport"],$dataSession["menuBodyTop"],$dataSession["menuHiddenPopup"]);
						if ($resultPermission 	== PERMISSION_NONE)
						throw new Exception(NOT_ALL_INSERT);			
			
			}				 
			
			//Cargar Librerias
			$this->load->model("Company_Currency_Model");
			$this->load->model("Component_Cycle_Model");
			
			//Obtener el componente de Item
			$objComponentCalendarPay	= $this->core_web_tools->getComponentIDBy_ComponentName("tb_transaction_master_rrhh_adelantos");
			if(!$objComponentCalendarPay)
			throw new Exception("EL COMPONENTE 'tb_transaction_master_rrhh_adelantos' NO EXISTE...");
		
			$objComponentEmployee	= $this->core_web_tools->getComponentIDBy_ComponentName("tb_employee");
			if(!$objComponentEmployee)
			throw new Exception("EL COMPONENTE 'tb_employee' NO EXISTE...");
			
			$dataView["companyID"]				= $dataSession["user"]->companyID;
			$dataView["userID"]					= $dataSession["user"]->userID;
			$dataView["userName"]				= $dataSession["user"]->nickname;
			$dataView["roleID"]					= $dataSession["role"]->roleID;
			$dataView["roleName"]				= $dataSession["role"]->name;
			$dataView["branchID"]				= $dataSession["branch"]->branchID;
			$dataView["branchName"]				= $dataSession["branch"]->name;
			
			
			$dataView["objComponentCalendarPay"]= $objComponentCalendarPay;
			$dataView["objComponentEmployee"]	= $objComponentEmployee;
			//$dataView["objListCycle"]			= $this->Component_Cycle_Model->get_rowByCompanyIDFecha($dataView["companyID"],date("Y-m-d"));
			//Obtener el Parametro Estado Cerrado de los Ciclos Contables.
			$objCompanyParameter 				= $this->core_web_parameter->getParameter("ACCOUNTING_CYCLE_WORKFLOWSTAGECLOSED",$dataSession["user"]->companyID);
			
			$dataView["objListCycle"]			= $this->Component_Cycle_Model->get_rowByCompanyID_TopCycleOpenAscAndOpen($dataView["companyID"],100,$objCompanyParameter->value);
			
			$dataView["objListType"]			= $this->core_web_catalog->getCatalogAllItem("tb_transaction_master_rrhh_adelantos","typeID",$dataView["companyID"]);
			$dataView["objListCurrency"]		= $this->Company_Currency_Model->getByCompany($dataView["companyID"]);
			$dataView["objListWorkflowStage"]	= $this->core_web_workflow->getWorkflowInitStage("tb_transaction_master_rrhh_adelantos","statusID",$dataView["companyID"],$dataView["branchID"],$dataView["roleID"]);
			
			
			//Renderizar Resultado 
			$dataSession["notification"]	= $this->core_web_error->get_error($dataSession["user"]->userID);
			$dataSession["message"]			= $this->core_web_notification->get_message();
			$dataSession["head"]			= $this->load->view('app_planilla_adelantos/news_head',$dataView,true);
			$dataSession["body"]			= $this->load->view('app_planilla_adelantos/news_body',$dataView,true);
			$dataSession["script"]			= $this->load->view('app_planilla_adelantos/news_script',$dataView,true);  
			$dataSession["footer"]			= "";
			$this->load->view("core_masterpage/default_masterpage",$dataSession);	
			
		}
		catch(Exception $ex){
			show_error($ex->getLine()." ".$ex->getMessage() ,500 );
		}	
			
    }
	function index($dataViewID = null){	
		try{ 
		
			//Librerias
			$this->load->library('user_agent');
			
			
			//AUTENTICADO
			if(!$this->core_web_authentication->isAuthenticated())
			throw new Exception(USER_NOT_AUTENTICATED);
			$dataSession		= $this->session->all_userdata();
		
			//PERMISO SOBRE LA FUNCTION
			if(APP_NEED_AUTHENTICATION == true){				
				
						$permited = false;
						$permited = $this->core_web_permission->urlPermited($this->router->class,"index",$this->config->item('url_suffix'),$dataSession["menuTop"],$dataSession["menuLeft"],$dataSession["menuBodyReport"],$dataSession["menuBodyTop"],$dataSession["menuHiddenPopup"]);
						
						if(!$permited)
						throw new Exception(NOT_ACCESS_CONTROL);
						
						$resultPermission		= $this->core_web_permission->urlPermissionCmd($this->router->class,"index",$this->config->item('url_suffix'),$dataSession,$dataSession["menuTop"],$dataSession["menuLeft"],$dataSession["menuBodyReport"],$dataSession["menuBodyTop"],$dataSession["menuHiddenPopup"]);
						if ($resultPermission 	== PERMISSION_NONE)
						throw new Exception(NOT_ACCESS_FUNCTION);			
			
			}	
			//Obtener el componente Para mostrar la lista de AccountType
			$objComponent		= $this->core_web_tools->getComponentIDBy_ComponentName("tb_transaction_master_rrhh_adelantos");
			if(!$objComponent)
			throw new Exception("00409 EL COMPONENTE 'tb_transaction_master_rrhh_adelantos' NO EXISTE...");
			
			
			//Vista por defecto PC
			if($dataViewID == null and  !$this->agent->is_mobile() ){				
				$targetComponentID			= 0;	
				$parameter["{companyID}"]	= $this->session->userdata('user')->companyID;
				$dataViewData				= $this->core_web_view->getViewDefault($this->session->userdata('user'),$objComponent->componentID,CALLERID_LIST,$targetComponentID,$resultPermission,$parameter);			
				$dataViewRender				= $this->core_web_view->renderGreed($dataViewData,'ListView',"fnTableSelectedRow");
			}		
			//Vista por defecto MOBILE
			else if( $this->agent->is_mobile() ){
				$parameter["{companyID}"]	= $this->session->userdata('user')->companyID;
				$dataViewData				= $this->core_web_view->getViewByName($this->session->userdata('user'),$objComponent->componentID,"DEFAULT_MOBILE_LISTA_ABONOS",CALLERID_LIST,$resultPermission,$parameter); 			
				$dataViewRender				= $this->core_web_view->renderGreed($dataViewData,'ListView',"fnTableSelectedRow");
			} 
			//Vista Por Id
			else 
			{
				$parameter["{companyID}"]	= $this->session->userdata('user')->companyID;
				$dataViewData				= $this->core_web_view->getViewBy_DataViewID($this->session->userdata('user'),$objComponent->componentID,$dataViewID,CALLERID_LIST,$resultPermission,$parameter); 			
				$dataViewRender				= $this->core_web_view->renderGreed($dataViewData,'ListView',"fnTableSelectedRow");
			}
			 
			//Renderizar Resultado
			$dataSession["notification"]	= $this->core_web_error->get_error($dataSession["user"]->userID);
			$dataSession["message"]			= $this->core_web_notification->get_message();
			$dataSession["head"]			= $this->load->view('app_planilla_adelantos/list_head','',true);
			$dataSession["footer"]			= $this->load->view('app_planilla_adelantos/list_footer','',true);
			$dataSession["body"]			= $dataViewRender; 
			$dataSession["script"]			= $this->load->view('app_planilla_adelantos/list_script','',true).$this->core_web_javascript->createVar("componentID",$objComponent->componentID);  
			$this->load->view("core_masterpage/default_masterpage",$dataSession);		
		}
		catch(Exception $ex){
			show_error($ex->getLine()." ".$ex->getMessage() ,500 );
		}
	}	
	
}
?>
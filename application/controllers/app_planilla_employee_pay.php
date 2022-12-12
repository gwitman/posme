<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class App_Planilla_Employee_Pay extends CI_Controller {
	
    public function __construct() {
       parent::__construct();
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
			
			//Obtener el componente de Item
			$objComponentCalendarPay	= $this->core_web_tools->getComponentIDBy_ComponentName("tb_employee_calendar_pay");
			if(!$objComponentCalendarPay)
			throw new Exception("EL COMPONENTE 'tb_employee_calendar_pay' NO EXISTE...");
		
			$objComponentEmployee	= $this->core_web_tools->getComponentIDBy_ComponentName("tb_employee");
			if(!$objComponentEmployee)
			throw new Exception("EL COMPONENTE 'tb_employee' NO EXISTE...");
			
			
			//Redireccionar datos
			$uri					= $this->uri->uri_to_assoc(3);
			$calendarID				= $uri["calendarID"];
			$companyID 				= $dataSession["user"]->companyID;
			$branchID 				= $dataSession["user"]->branchID;
			$roleID 				= $dataSession["role"]->roleID;			
			$userID					= $dataSession["user"]->userID;
			
			if((!$calendarID))
			{ 
				redirect('app_planilla_employee_pay/add');	
			} 		
			
			$dataView["companyID"]				= $dataSession["user"]->companyID;
			$dataView["userID"]					= $dataSession["user"]->userID;
			$dataView["userName"]				= $dataSession["user"]->nickname;
			$dataView["roleID"]					= $dataSession["role"]->roleID;
			$dataView["roleName"]				= $dataSession["role"]->name;
			$dataView["branchID"]				= $dataSession["branch"]->branchID;
			$dataView["branchName"]				= $dataSession["branch"]->name;
			
			
			$dataView["objComponentCalendarPay"]= $objComponentCalendarPay;
			$dataView["objComponentEmployee"]	= $objComponentEmployee;
			$dataView["objCalendarPay"]			= $this->Employee_Calendar_Pay_Model->get_rowByPK($calendarID);
			$dataView["objCalendarPayDetail"]	= $this->Employee_Calendar_Pay_Detail_Model->get_rowByCalendarID($calendarID);
			$dataView["objListCycle"]			= $this->Component_Cycle_Model->get_rowByCycleID($dataView["objCalendarPay"]->accountingCycleID);
			$dataView["objListType"]			= $this->core_web_catalog->getCatalogAllItem("tb_employee_calendar_pay","typeID",$dataView["companyID"]);
			$dataView["objListCurrency"]		= $this->Company_Currency_Model->getByCompany($dataView["companyID"]);
			$dataView["objListWorkflowStage"]	= $this->core_web_workflow->getWorkflowStageByStageInit("tb_employee_calendar_pay","statusID",$dataView["objCalendarPay"]->statusID,$companyID,$branchID,$roleID);
			
			
			//Renderizar Resultado 
			$dataSession["notification"]	= $this->core_web_error->get_error($dataSession["user"]->userID);
			$dataSession["message"]			= $this->core_web_notification->get_message();
			$dataSession["head"]			= $this->load->view('app_planilla_employee_pay/edit_head',$dataView,true);
			$dataSession["body"]			= $this->load->view('app_planilla_employee_pay/edit_body',$dataView,true);
			$dataSession["script"]			= $this->load->view('app_planilla_employee_pay/edit_script',$dataView,true);
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
			if( $this->core_web_workflow->validateWorkflowStage("tb_transaction_master_share","statusID",$objTM->statusID,COMMAND_ELIMINABLE,$dataSession["user"]->companyID,$dataSession["user"]->branchID,$dataSession["role"]->roleID))
			throw new Exception(NOT_WORKFLOW_DELETE);
			
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
			
			//Obtener el Componente
			$objComponentCalendarPay			= $this->core_web_tools->getComponentIDBy_ComponentName("tb_employee_calendar_pay");
			if(!$objComponentCalendarPay)
			throw new Exception("EL COMPONENTE 'tb_employee_calendar_pay' NO EXISTE...");
			
			//Obtener el Ciclo
			$objCycle 							= $this->Component_Cycle_Model->get_rowByCycleID($this->input->post("txtCycleID"));
			
			if($this->core_web_accounting->cycleIsCloseByDate($dataSession["user"]->companyID,$objCycle->startOn))
			throw new Exception("EL DOCUMENTO NO PUEDE INGRESAR, EL CICLO CONTABLE ESTA CERRADO");
			
			$branchID 								= $dataSession["user"]->branchID;
			$roleID 								= $dataSession["role"]->roleID;
			$companyID 								= $dataSession["user"]->companyID;
			$userID 								= $dataSession["user"]->userID;
			
			//Obtener transaccion
			$calendarID								= $this->input->post("txtCalendarPayID");
			$objCalendarPay							= $this->Employee_Calendar_Pay_Model->get_rowByPK($calendarID);
			$objEC["companyID"] 					= $dataSession["user"]->companyID;
			$objEC["accountingCycleID"] 			= $this->input->post("txtCycleID");	
			$objEC["name"] 							= $this->input->post("txtNombre");
			$objEC["typeID"] 						= $this->input->post("txtTypeID");
			$objEC["currencyID"]					= $this->input->post("txtCurrencyID");
			$objEC["statusID"]						= $this->input->post("txtStatusID");
			$objEC["description"] 					= $this->input->post("txtNote");
			
			//Obtener el Ciclo
			$objCycle 								= $this->Component_Cycle_Model->get_rowByCycleID($this->input->post("txtCycleID"));			
			
			//Validar Edicion por el Usuario
			if ($resultPermission 	== PERMISSION_ME && ($objCalendarPay->createdBy != $userID))
			throw new Exception(NOT_EDIT);
			
			//Validar si el estado permite editar
			if(!$this->core_web_workflow->validateWorkflowStage("tb_employee_calendar_pay","statusID",$objCalendarPay->statusID,COMMAND_EDITABLE_TOTAL,$dataSession["user"]->companyID,$dataSession["user"]->branchID,$dataSession["role"]->roleID))
			throw new Exception(NOT_WORKFLOW_EDIT);					
			
			if($this->core_web_accounting->cycleIsCloseByDate($companyID,$objCycle->startOn))
			throw new Exception("EL DOCUMENTO NO PUEDE ACTUALIZARCE, EL CICLO CONTABLE ESTA CERRADO");
			
			
			$this->db->trans_begin();			
			//El Estado solo permite editar el workflow
			if($this->core_web_workflow->validateWorkflowStage("tb_employee_calendar_pay","statusID",$objCalendarPay->statusID,COMMAND_EDITABLE,$dataSession["user"]->companyID,$dataSession["user"]->branchID,$dataSession["role"]->roleID)){
				$objEC								= array();
				$objEC["description"] 					= $this->input->post("txtNote");
				$objEC["statusID"] 					= $this->input->post("txtStatusID");						
				$this->Employee_Calendar_Pay_Model->update($calendarID,$objEC);
			}
			else{
				$this->Employee_Calendar_Pay_Model->update($calendarID,$objEC);
			}
			
			
			//Recorrer la lista del detalle del documento
			$arrayListCalendarDetailID	= $this->input->post("txtCalendarDetailID");
			$arrayListEmployeeID		= $this->input->post("txtEmployeeID");
			$arrayListSalario			= $this->input->post("txtSalario");
			$arrayListComision			= $this->input->post("txtComision");
			$arrayListAdelantos	 		= $this->input->post("txtAdelantos");			
			$arrayListNeto				= $this->input->post("txtNeto");
			
			//Eliminar Para Crear Nuevamente.
			$this->Employee_Calendar_Pay_Detail_Model->deleteWhereIDNotIn($calendarID,$arrayListCalendarDetailID);
			
			if(!empty($arrayListCalendarDetailID)){
				foreach($arrayListCalendarDetailID as $key => $value){
					$calendarPayDetailID		= $value;
					$EmployeeID					= $arrayListEmployeeID[$key];
					$Salario					= $arrayListSalario[$key];
					$Comision					= $arrayListComision[$key];
					$Adelantos					= $arrayListAdelantos[$key];
					$Neto 						= $arrayListNeto[$key];
					
					//Nuevo Detalle
					if($calendarPayDetailID == 0){	
						$objECD 								= NULL;
						$objECD["calendarID"] 					= $calendarID;
						$objECD["employeeID"] 					= $EmployeeID;
						$objECD["salary"] 						= $Salario;
						$objECD["commission"]					= $Comision;
						$objECD["adelantos"] 					= $Adelantos;
						$objECD["neto"] 						= $Neto;
						$objECD["isActive"]						= 1;
						
						$this->Employee_Calendar_Pay_Detail_Model->insert($objECD);
					}					
					//Editar Detalle
					else{						
						$objECD 								= NULL;
						$objECD["calendarID"] 					= $calendarID;
						$objECD["employeeID"] 					= $EmployeeID;
						$objECD["salary"] 						= $Salario;
						$objECD["commission"]					= $Comision;
						$objECD["adelantos"] 					= $Adelantos;
						$objECD["neto"] 						= $Neto;
						$objECD["isActive"]						= 1;						
						$this->Employee_Calendar_Pay_Detail_Model->update($calendarPayDetailID,$objECD);					
					}
					
					
				}
			}			
			
			//Aplicar el Documento?
			if( $this->core_web_workflow->validateWorkflowStage("tb_employee_calendar_pay","statusID",$objEC["statusID"],COMMAND_APLICABLE,$dataSession["user"]->companyID,$dataSession["user"]->branchID,$dataSession["role"]->roleID) &&  $objCalendarPay->statusID != $objEC["statusID"] ){
				
				//Crear Transaccion Nueva
				$query			= "CALL pr_planilla_create_transaction (".$companyID.",".$calendarID.");";	
				$resultQuery	= $this->Bd_Model->executeRender($query);										
				
			}
			
			
			
			if($this->db->trans_status() !== false){
				$this->db->trans_commit();						
				$this->core_web_notification->set_message(false,SUCCESS);
				redirect('app_planilla_employee_pay/edit/calendarID/'.$calendarID);
			}
			else{
				$this->db->trans_rollback();						
				$this->core_web_notification->set_message(true,$this->db->_error_message());
				redirect('app_planilla_employee_pay/add');	
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
			
			$this->core_web_permission->getValueLicense($dataSession["user"]->companyID,$this->router->class."/"."index");
			//Obtener el Componente
			$objComponentCalendarPay			= $this->core_web_tools->getComponentIDBy_ComponentName("tb_employee_calendar_pay");
			if(!$objComponentCalendarPay)
			throw new Exception("EL COMPONENTE 'tb_employee_calendar_pay' NO EXISTE...");
			
			//Obtener el Ciclo
			$objCycle 							= $this->Component_Cycle_Model->get_rowByCycleID($this->input->post("txtCycleID"));
			
			if($this->core_web_accounting->cycleIsCloseByDate($dataSession["user"]->companyID,$objCycle->startOn))
			throw new Exception("EL DOCUMENTO NO PUEDE INGRESAR, EL CICLO CONTABLE ESTA CERRADO");
			
			//Obtener transaccion
			$objEC["companyID"] 					= $dataSession["user"]->companyID;
			$objEC["accountingCycleID"] 			= $this->input->post("txtCycleID");			
			$objEC["number"]						= $this->core_web_counter->goNextNumber($dataSession["user"]->companyID,$dataSession["user"]->branchID,"tb_employee_calendar_pay",0);
			$objEC["name"] 							= $this->input->post("txtNombre");
			$objEC["typeID"] 						= $this->input->post("txtTypeID");
			$objEC["currencyID"]					= $this->input->post("txtCurrencyID");
			$objEC["statusID"]						= $this->input->post("txtStatusID");
			$objEC["description"] 					= $this->input->post("txtNote");
			$objEC["isActive"] 						= 1;
			$this->core_web_auditoria->setAuditCreated($objEC,$dataSession);			
			
			
			$this->db->trans_begin();
			$calendarID = $this->Employee_Calendar_Pay_Model->insert($objEC);
			
			//Crear la Carpeta para almacenar los Archivos del Documento
			mkdir(PATH_FILE_OF_APP."/company_".$objEC["companyID"]."/component_".$objComponentCalendarPay->componentID."/component_item_".$calendarID, 0700);
			
			//Recorrer la lista del detalle del documento
			$arrayListCalendarDetailID	= $this->input->post("txtCalendarDetailID");
			$arrayListEmployeeID		= $this->input->post("txtEmployeeID");
			$arrayListSalario			= $this->input->post("txtSalario");
			$arrayListComision			= $this->input->post("txtComision");
			$arrayListAdelantos	 		= $this->input->post("txtAdelantos");			
			$arrayListNeto				= $this->input->post("txtNeto");
			
			
			if(!empty($arrayListCalendarDetailID)){
				foreach($arrayListCalendarDetailID as $key => $value){
					$calendarPayDetailID		= $value;
					$EmployeeID					= $arrayListEmployeeID[$key];
					$Salario					= $arrayListSalario[$key];
					$Comision					= $arrayListComision[$key];
					$Adelantos					= $arrayListAdelantos[$key];
					$Neto 						= $arrayListNeto[$key];
					
					$objECD 								= NULL;
					$objECD["calendarID"] 					= $calendarID;
					$objECD["employeeID"] 					= $EmployeeID;
					$objECD["salary"] 						= $Salario;
					$objECD["commission"]					= $Comision;
					$objECD["adelantos"] 					= $Adelantos;
					$objECD["neto"] 						= $Neto;
					$objECD["isActive"]						= 1;
					
					$this->Employee_Calendar_Pay_Detail_Model->insert($objECD);
				}
			}
			
			if($this->db->trans_status() !== false){
				$this->db->trans_commit();						
				$this->core_web_notification->set_message(false,SUCCESS);
				redirect('app_planilla_employee_pay/edit/calendarID/'.$calendarID);
			}
			else{
				$this->db->trans_rollback();						
				$this->core_web_notification->set_message(true,$this->db->_error_message());
				redirect('app_planilla_employee_pay/add');	
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
				redirect('app_planilla_employee_pay/add');
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
				redirect('app_planilla_employee_pay/add');
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
			$objComponentCalendarPay	= $this->core_web_tools->getComponentIDBy_ComponentName("tb_employee_calendar_pay");
			if(!$objComponentCalendarPay)
			throw new Exception("EL COMPONENTE 'tb_employee_calendar_pay' NO EXISTE...");
		
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
			$dataView["objListType"]			= $this->core_web_catalog->getCatalogAllItem("tb_employee_calendar_pay","typeID",$dataView["companyID"]);
			$dataView["objListCurrency"]		= $this->Company_Currency_Model->getByCompany($dataView["companyID"]);
			$dataView["objListWorkflowStage"]	= $this->core_web_workflow->getWorkflowInitStage("tb_employee_calendar_pay","statusID",$dataView["companyID"],$dataView["branchID"],$dataView["roleID"]);
			
			
			//Renderizar Resultado 
			$dataSession["notification"]	= $this->core_web_error->get_error($dataSession["user"]->userID);
			$dataSession["message"]			= $this->core_web_notification->get_message();
			$dataSession["head"]			= $this->load->view('app_planilla_employee_pay/news_head',$dataView,true);
			$dataSession["body"]			= $this->load->view('app_planilla_employee_pay/news_body',$dataView,true);
			$dataSession["script"]			= $this->load->view('app_planilla_employee_pay/news_script',$dataView,true);  
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
			$objComponent		= $this->core_web_tools->getComponentIDBy_ComponentName("tb_employee_calendar_pay");
			if(!$objComponent)
			throw new Exception("00409 EL COMPONENTE 'tb_employee_calendar_pay' NO EXISTE...");
			
			
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
			$dataSession["head"]			= $this->load->view('app_planilla_employee_pay/list_head','',true);
			$dataSession["footer"]			= $this->load->view('app_planilla_employee_pay/list_footer','',true);
			$dataSession["body"]			= $dataViewRender; 
			$dataSession["script"]			= $this->load->view('app_planilla_employee_pay/list_script','',true).$this->core_web_javascript->createVar("componentID",$objComponent->componentID);  
			$this->load->view("core_masterpage/default_masterpage",$dataSession);		
		}
		catch(Exception $ex){
			show_error($ex->getLine()." ".$ex->getMessage() ,500 );
		}
	}	
	
}
?>
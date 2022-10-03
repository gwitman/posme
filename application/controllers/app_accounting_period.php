<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class App_Accounting_Period extends CI_Controller {
	
    public function __construct() {
       parent::__construct();
    }	
    function edit(){  
		 try{ 
			//AUTENTICADO
			if(!$this->core_web_authentication->isAuthenticated())
			throw new Exception(USER_NOT_AUTENTICATED);
			$dataSession		= $this->session->all_userdata();
			
			//PERMISO SOBRE LA FUNCION
			if(APP_NEED_AUTHENTICATION == true){
				$permited = false;
				$permited = $this->core_web_permission->urlPermited($this->router->class,"index",$this->config->item('url_suffix'),$dataSession["menuTop"],$dataSession["menuLeft"],$dataSession["menuBodyReport"],$dataSession["menuBodyTop"],$dataSession["menuHiddenPopup"]);
				
				if(!$permited)
				throw new Exception(NOT_ACCESS_CONTROL);
				
				$resultPermission		= $this->core_web_permission->urlPermissionCmd($this->router->class,"edit",$this->config->item('url_suffix'),$dataSession,$dataSession["menuTop"],$dataSession["menuLeft"],$dataSession["menuBodyReport"],$dataSession["menuBodyTop"],$dataSession["menuHiddenPopup"]);
				if ($resultPermission 	== PERMISSION_NONE)
				throw new Exception(NOT_ALL_EDIT);			
			}
			
			//Set Datos			
			//
			////////////////////////////////////////
			////////////////////////////////////////
			////////////////////////////////////////
			$this->load->model("Component_Period_Model");	
			$this->load->model("Component_Cycle_Model");
			$this->load->model("core/User_Permission_Model");
			$this->load->model("core/Role_Autorization_Model");
			
			
			//Redireccionar datos
			$uri					= $this->uri->uri_to_assoc(3);
						
			$companyID				= $uri["companyID"];
			$componentID			= $uri["componentID"];	
			$componentPeriodID		= $uri["componentPeriodID"];	
			$branchID 				= $dataSession["user"]->branchID;
			$roleID 				= $dataSession["role"]->roleID;			
			if((!$companyID || !$componentID || !$componentPeriodID))
			{ 
				redirect('app_accounting_period/add');	
			} 		
			
			
			//Obtener el Registro			
			$datView["objComponentPeriod"]	 		 = $this->Component_Period_Model->get_rowByPK($componentPeriodID);
			$datView["objComponentPeriod"]->startOn  = date_format(date_create($datView["objComponentPeriod"]->startOn),"Y-m-d");
			$datView["objComponentPeriod"]->endOn 	 = date_format(date_create($datView["objComponentPeriod"]->endOn),"Y-m-d");						
			$datView["objListComponentPeriodStatus"] = $this->core_web_workflow->getWorkflowStageByStageInit("tb_accounting_period","statusID",$datView["objComponentPeriod"]->statusID,$companyID,$branchID,$roleID);			
			$datView["objListComponentCycle"] 		 = $this->Component_Cycle_Model->getByComponentPeriodID($datView["objComponentPeriod"]->componentPeriodID);
			if($datView["objListComponentCycle"])
			foreach($datView["objListComponentCycle"] as &$i){
				$i->startOn = date_format(date_create($i->startOn),"Y-m-d");
				$i->endOn 	= date_format(date_create($i->endOn),"Y-m-d");
			}						
			$datView["objListComponentCycleStatus"] = $this->core_web_workflow->getWorkflowAllStage("tb_accounting_cycle","statusID",$companyID,$branchID,$roleID);
			
			
			//Obtener los Permisos Core
			$datView["objUserPermission"]			= $this->User_Permission_Model->get_rowByCompanyIDyBranchIDyRoleID($companyID,$branchID,$roleID);
			//Obtener las Autorization Core
			$datView["listComponentAutoriation"]	= $this->Role_Autorization_Model->get_rowByRoleAutorization($companyID,$branchID,$roleID);
			
			
			//Renderizar Resultado
			$dataSession["notification"]	= $this->core_web_error->get_error($dataSession["user"]->userID);
			$dataSession["message"]			=  $this->core_web_notification->get_message();
			$dataSession["head"]			= $this->load->view('app_account_period/edit_head',$datView,true);
			$dataSession["body"]			= $this->load->view('app_account_period/edit_body',$datView,true);
			$dataSession["script"]			= $this->load->view('app_account_period/edit_script',$datView,true);  
			$dataSession["footer"]			= "";				
			$this->load->view("core_masterpage/default_masterpage",$dataSession);	
			
		}
		catch(Exception $ex){
			show_error($ex->getLine()." ".$ex->getMessage() ,500 );
		}	
	}
	
	
	function delete(){
		try{ 
			//AUTENTICACION
			if(!$this->core_web_authentication->isAuthenticated())
			throw new Exception(USER_NOT_AUTENTICATED);
			$dataSession		= $this->session->all_userdata();
			
			//PERMISO SOBRE LA FUNCION
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
			$this->load->model("Component_Period_Model");  
			$this->load->model("Component_Cycle_Model");  
			
			//Nuevo Registro
			$companyID 				= $this->input->post("companyID");
			$componentID 			= $this->input->post("componentID");				
			$componentPeriodID		= $this->input->post("componentPeriodID");				
			
			if((!$companyID && !$componentID && !$componentPeriodID)){
					throw new Exception(NOT_PARAMETER);								 
			} 
			
			$obj 					= $this->Component_Period_Model->get_rowByPK($componentPeriodID);	
			$objListCycle			= $this->Component_Cycle_Model->getByComponentPeriodID($componentPeriodID);	
			
			//PERMISO SOBRE EL REGISTRO
			if ($resultPermission 	== PERMISSION_ME && ($obj->createdBy != $dataSession["user"]->userID))
			throw new Exception(NOT_DELETE);
			
			//PERMISO PUEDE ELIMINAR EL REGISTRO SEGUN EL WORKFLOW
			if(!$this->core_web_workflow->validateWorkflowStage("tb_accounting_period","statusID",$obj->statusID,COMMAND_ELIMINABLE,$dataSession["user"]->companyID,$dataSession["user"]->branchID,$dataSession["role"]->roleID))
			throw new Exception(NOT_WORKFLOW_DELETE);

			//PERIODO CONTIENE COMPROBANTES
			$bo = $this->core_web_accounting->periodIsEmptyByID($companyID,$componentPeriodID);
			if(!$bo)
			throw new Exception("El PERIODO CONTABLE CONTIENE COMPROBANTES VALIDOS");
			
			
			//Eliminar el Registro
			$this->db->trans_begin();
			$this->Component_Period_Model->delete($companyID,$componentID,$componentPeriodID);
			foreach($objListCycle as $itemCycle){				
				$this->Component_Cycle_Model->delete($itemCycle->componentCycleID);
			}
			
			if($this->db->trans_status() !== false){
				$this->db->trans_commit();									
			}
			else{
				$this->db->trans_rollback();										
			}
			
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
	
	function save($method = NULL){
		 try{ 
			//AUTENTICACION
			if(!$this->core_web_authentication->isAuthenticated())
			throw new Exception(USER_NOT_AUTENTICATED);
			$dataSession		= $this->session->all_userdata();
			
			
			//Load Modelos			
			//
			////////////////////////////////////////
			////////////////////////////////////////
			////////////////////////////////////////
			$this->load->model("Component_Period_Model"); 			  
			$this->load->model("Component_Cycle_Model"); 
					
			
			//Validar Formulario						
			$this->form_validation->set_rules("txtName","Name","required");    
			$this->form_validation->set_rules("txtStatusID","Status","required");
			$this->form_validation->set_rules("txtStartOn","startOn","required");
			$this->form_validation->set_rules("txtEndOn","endOn","required");
			
			 
			//Nuevo Registro			
			$continue				= true;
			$objComponentAccounting = $this->core_web_tools->getComponentIDBy_ComponentName("0-CONTABILIDAD");
			
			if( $method == "new" && $this->form_validation->run() == true ){
					
					//PERMISO SOBRE LA FUNCION
					if(APP_NEED_AUTHENTICATION == true){
						$permited = false;
						$permited = $this->core_web_permission->urlPermited($this->router->class,"index",$this->config->item('url_suffix'),$dataSession["menuTop"],$dataSession["menuLeft"],$dataSession["menuBodyReport"],$dataSession["menuBodyTop"],$dataSession["menuHiddenPopup"]);
						
						if(!$permited)
						throw new Exception(NOT_ACCESS_CONTROL);
						
						$resultPermission		= $this->core_web_permission->urlPermissionCmd($this->router->class,"add",$this->config->item('url_suffix'),$dataSession,$dataSession["menuTop"],$dataSession["menuLeft"],$dataSession["menuBodyReport"],$dataSession["menuBodyTop"],$dataSession["menuHiddenPopup"]);
						if ($resultPermission 	== PERMISSION_NONE)
						throw new Exception(NOT_ALL_INSERT);			
					
					}					
					
					//Ingresar Periodo
					if($continue){
						$this->db->trans_begin();
						$obj["companyID"]		= $dataSession["user"]->companyID;
						$obj["componentID"]		= $objComponentAccounting->componentID;
						$obj["number"]			= date("YmdHis");
						$obj["name"]			= $this->input->post("txtName");
						$obj["description"]		= $this->input->post("txtDescription");
						$obj["startOn"]			= $this->input->post("txtStartOn");
						$obj["endOn"]			= $this->input->post("txtEndOn");
						$obj["statusID"]		= $this->input->post("txtStatusID");
						$obj["isActive"]		= 1;
						$this->core_web_auditoria->setAuditCreated($obj,$dataSession);
						
						//Validar Periodo
						$result					= $this->Component_Period_Model->validateTime($obj["companyID"],$obj["componentID"],$obj["startOn"],$obj["endOn"]);
						if($result){
							throw new Exception("000243 EL PERIODO NO PUEDE SER CREADO, POR QUE ESTARIA SOLAPADO CON OTRO PERIODO CONTABLE...");
						}
						//Ingresar Periodo
						$componentPeriodID 		= $this->Component_Period_Model->insert($obj);
						
						
						//Ingresar los Ciclos
						$objListCycleStartOn 	= $this->input->post("txtCycleStartOn");
						$objListCycleEndOn 		= $this->input->post("txtCycleEndOn");
						$objListCycleStatusID 	= $this->input->post("txtCycleStatusID");
						$objListCycleNumber 	= $this->input->post("txtCycleNumber");
						if($objListCycleStartOn)
						foreach($objListCycleStartOn as $key => $value){
							$objCycle["componentPeriodID"]		= $componentPeriodID;
							$objCycle["companyID"]				= $obj["companyID"];
							$objCycle["componentID"] 			= $obj["componentID"];
							$objCycle["number"] 				= $obj["number"];
							$objCycle["name"]					= $obj["number"];
							$objCycle["description"] 			= $obj["description"];
							$objCycle["startOn"] 				= $objListCycleStartOn[$key];
							$objCycle["endOn"]					= $objListCycleEndOn[$key];
							$objCycle["statusID"]				= $objListCycleStatusID[$key];
							$objCycle["isActive"] 				= true;
							$this->core_web_auditoria->setAuditCreated($objCycle,$dataSession);
							
							if(!$objCycle["statusID"])
							throw new Exception("000243 TODOS LOS CICLOS DEBEN DE TENER UN ESTADO ESTABLECIDO");
							
							if(!$objCycle["startOn"])
							throw new Exception("000243 TODOS LOS CICLOS DEBEN DE TENER FECHA INICIAL ESTABLECIDA");
							
							if(!$objCycle["endOn"])
							throw new Exception("000243 TODOS LOS CICLOS DEBEN DE TENER FECHA FINAL ESTABLECIDA");
							
							$componentCycleID 					= $this->Component_Cycle_Model->insert($objCycle);
						}
						
						if($this->db->trans_status() !== false){
							$this->db->trans_commit();						
							$this->core_web_notification->set_message(false,SUCCESS);
							redirect('app_accounting_period/edit/companyID/'.$obj["companyID"]."/componentID/".$obj["componentID"]."/componentPeriodID/".$componentPeriodID);
						}
						else{
							$this->db->trans_rollback();						
							$this->core_web_notification->set_message(true,$this->db->_error_message());
							redirect('app_accounting_period/add');	
						}
					}
					else{
						redirect('app_accounting_period/add');	
					}
					
					 
			} 
			//Editar Registro
			else if( $this->form_validation->run() == true) {
					//PERMISO SOBRE LA FUNCION
					if(APP_NEED_AUTHENTICATION == true){
						$permited = false;
						$permited = $this->core_web_permission->urlPermited($this->router->class,"index",$this->config->item('url_suffix'),$dataSession["menuTop"],$dataSession["menuLeft"],$dataSession["menuBodyReport"],$dataSession["menuBodyTop"],$dataSession["menuHiddenPopup"]);
						
						if(!$permited)
						throw new Exception(NOT_ACCESS_CONTROL);
						
						$resultPermission		= $this->core_web_permission->urlPermissionCmd($this->router->class,"edit",$this->config->item('url_suffix'),$dataSession,$dataSession["menuTop"],$dataSession["menuLeft"],$dataSession["menuBodyReport"],$dataSession["menuBodyTop"],$dataSession["menuHiddenPopup"]);
						if ($resultPermission 	== PERMISSION_NONE)
						throw new Exception(NOT_ALL_EDIT);			

					
					}	
					
								
					//PERMISO SOBRE EL REGISTRO
					$messageTmp					= '';
					$companyID					= $dataSession["user"]->companyID;
					$componentID				= $objComponentAccounting->componentID;
					$componentPeriodID 			= $this->input->post("txtComponentPeriodID");
					$objOld = $this->Component_Period_Model->get_rowByPK($componentPeriodID);
					if ($resultPermission 	== PERMISSION_ME && ($objOld->createdBy != $dataSession["user"]->userID))
					throw new Exception(NOT_EDIT);
			
					//PERMISO PUEDE EDITAR EL REGISTRO
					if(!$this->core_web_workflow->validateWorkflowStage("tb_accounting_period","statusID",$objOld->statusID,COMMAND_EDITABLE_TOTAL,$dataSession["user"]->companyID,$dataSession["user"]->branchID,$dataSession["role"]->roleID))
					throw new Exception(NOT_WORKFLOW_EDIT);

					
					if($continue){
						$this->db->trans_begin();					
						
						if(!$this->core_web_workflow->validateWorkflowStage("tb_accounting_period","statusID",$objOld->statusID,COMMAND_EDITABLE,$dataSession["user"]->companyID,$dataSession["user"]->branchID,$dataSession["role"]->roleID))
						{
							$companyID					= $dataSession["user"]->companyID;
							$componentID				= $objComponentAccounting->componentID;
							$componentPeriodID 			= $this->input->post("txtComponentPeriodID");
							$obj["number"]				= $this->input->post("txtNumber");
							$obj["name"]				= $this->input->post("txtName");
							$obj["description"]			= $this->input->post("txtDescription");
							$obj["startOn"]				= $this->input->post("txtStartOn");
							$obj["endOn"]				= $this->input->post("txtEndOn");
							$obj["statusID"]			= $this->input->post("txtStatusID");
							$obj["isActive"]			= 1;
							//Validar Periodo
							$result					= $this->Component_Period_Model->validateTime($companyID,$componentID,$obj["startOn"],$obj["endOn"]);
							
							if($result && $result[0]->componentPeriodID != $componentPeriodID ){
								throw new Exception("000243 EL PERIODO NO PUEDE SER EDITADO, POR QUE ESTARIA SOLAPADO CON OTRO PERIODO CONTABLE...");
							}						
							
							//Actualizar Periodo
							$result 					= $this->Component_Period_Model->update($companyID,$componentID,$componentPeriodID,$obj);
							
													
							//Ingresar los Ciclos
							$objListCycleID 		= $this->input->post("txtComponentCycleID"); 
							$objListCycleStartOn 	= $this->input->post("txtCycleStartOn");
							$objListCycleEndOn 		= $this->input->post("txtCycleEndOn");
							$objListCycleStatusID 	= $this->input->post("txtCycleStatusID");
							$objListCycleNumber 	= $this->input->post("txtCycleNumber");
							
							//Validar si puede eliminar  los ciclos que previamente fueron eliminados por el usuario
							$objListCycleToDelete 	= $this->Component_Cycle_Model->get_rowByCycleNotIn($companyID,$componentID,$componentPeriodID,$objListCycleID);
							if($objListCycleToDelete)
							foreach($objListCycleToDelete as $ik){
								$bo = $this->core_web_accounting->cycleIsEmptyByID($companyID,$ik->componentCycleID);
								if(!$bo)
								throw new Exception("El CICLO CONTABLE CONTIENE COMPROBANTES VALIDOS ".$ik->startOn);
								
								$bo = $this->core_web_accounting->cycleIsCloseByID($companyID,$ik->componentCycleID);
								if($bo)
								throw new Exception("El CICLO CONTABLE ESTA CERRADO ".$ik->startOn);
								
							}
							
							//Eliminar los Ciclos que fueron eliminados por el usuario
							$this->Component_Cycle_Model->deleteNotInArray($companyID,$componentID,$componentPeriodID,$objListCycleID);
							
							if($objListCycleStartOn)
							foreach($objListCycleStartOn as $key => $value){							
								$componentCycleID 					= $objListCycleID[$key];								
								$objCycle["startOn"] 				= $objListCycleStartOn[$key];
								$objCycle["endOn"]					= $objListCycleEndOn[$key];
								$objCycle["statusID"]				= $objListCycleStatusID[$key];							
								
								if(!$objCycle["statusID"])
								throw new Exception("000243 TODOS LOS CICLOS DEBEN DE TENER UN ESTADO ESTABLECIDO");
								
								if(!$objCycle["startOn"])
								throw new Exception("000243 TODOS LOS CICLOS DEBEN DE TENER FECHA INICIAL ESTABLECIDA");
								
								if(!$objCycle["endOn"])
								throw new Exception("000243 TODOS LOS CICLOS DEBEN DE TENER FECHA FINAL ESTABLECIDA");
								
								if($componentCycleID){
									$objCycleTmp 		= $this->Component_Cycle_Model->get_rowByCycleID($componentCycleID);
									$objCycleTmpStartOn = date_format(date_create($objCycleTmp->startOn),"Y-m-d");
									$objCycleTmpEndOn 	= date_format(date_create($objCycleTmp->endOn),"Y-m-d");									

									//Validar si puede editar las fechas del ciclo
									if(($objCycleTmpStartOn != $objCycle["startOn"]) || ($objCycleTmpEndOn != $objCycle["endOn"])){
										$bo = $this->Component_Cycle_Model->get_rowByCompanyIDFecha($companyID,$objCycle["startOn"]);
										if($bo)
										throw new Exception("NO PUEDE CAMBIAR LAS FECHA DE ESTE CICLO POR QUE SE SOLAPA CON OTRA ".$objCycleTmpStartOn);
										
										$bo = $this->Component_Cycle_Model->get_rowByCompanyIDFecha($companyID,$objCycle["endOn"]);
										if($bo)
										throw new Exception("NO PUEDE CAMBIAR LAS FECHA DE ESTE CICLO POR QUE SE SOLAPA CON OTRA ".$objCycleTmpStartOn);
										
										$bo = $this->core_web_accounting->cycleIsCloseByDate($companyID,$objCycleTmpStartOn);
										if($bo)
										throw new Exception("NO PUEDE CAMBIAR LAS FECHA DE ESTE CICLO EL CICLO ESTA CERRADO ".$objCycleTmpStartOn);
										
										$bo = $this->core_web_accounting->cycleIsCloseByDate($companyID,$objCycleTmpEndOn);
										if($bo)
										throw new Exception("NO PUEDE CAMBIAR LAS FECHA DE ESTE CICLO EL CICLO ESTA CERRADO ".$objCycleTmpStartOn);
										
										
									}
									
									//Editar Ciclo
									$componentCycleID 					= $this->Component_Cycle_Model->update($componentCycleID,$objCycle);
								}
								else{
									//Nuevo Ciclo
									$objCycle["componentPeriodID"]		= $componentPeriodID;
									$objCycle["companyID"]				= $companyID;
									$objCycle["componentID"] 			= $componentID;
									$objCycle["number"] 				= $obj["number"];
									$objCycle["name"]					= $obj["number"];
									$objCycle["description"] 			= $obj["description"];							
									$objCycle["isActive"] 				= true;
									$this->core_web_auditoria->setAuditCreated($objCycle,$dataSession);							
									$componentCycleID 					= $this->Component_Cycle_Model->insert($objCycle);
								}
							}
						}
						else{
							$obj["statusID"]			= $this->input->post("txtStatusID");		
							$companyID					= $dataSession["user"]->companyID;
							$componentID				= $objComponentAccounting->componentID;
							$componentPeriodID 			= $this->input->post("txtComponentPeriodID");							
							$result 					= $this->Component_Period_Model->update($companyID,$componentID,$componentPeriodID,$obj);							
							$messageTmp					= "EL REGISTRO FUE EDITADO PARCIALMENTE, POR LA CONFIGURACION DE SU ESTADO ACTUAL";
						}
						
						if($this->db->trans_status() !== false){
							$this->db->trans_commit();
							$this->core_web_notification->set_message(false,SUCCESS." ".$messageTmp);
						}
						else{
							$this->db->trans_rollback();						
							$this->core_web_notification->set_message(true,$this->db->_error_message());
						}
						redirect('app_accounting_period/edit/companyID/'.$companyID."/componentID/".$componentID."/componentPeriodID/".$componentPeriodID);
						
					}					
					else{
						redirect('app_accounting_period/add');	
					}
			}  
			else{
				$stringValidation = str_replace("\n","",validation_errors());								
				$this->core_web_notification->set_message(true,$stringValidation);
				redirect('app_accounting_period/add');	
			} 
			
		}
		catch(Exception $ex){
			show_error($ex->getLine()." ".$ex->getMessage() ,500 );
		}		
			
	}
	
	function add(){ 
	
		try{ 
			//AUTENTICACION
			if(!$this->core_web_authentication->isAuthenticated())
			throw new Exception(USER_NOT_AUTENTICATED);
			$dataSession		= $this->session->all_userdata();
			
			//PERMISO SOBRE LA FUNCION
			if(APP_NEED_AUTHENTICATION == true){
						$permited = false;
						$permited = $this->core_web_permission->urlPermited($this->router->class,"index",$this->config->item('url_suffix'),$dataSession["menuTop"],$dataSession["menuLeft"],$dataSession["menuBodyReport"],$dataSession["menuBodyTop"],$dataSession["menuHiddenPopup"]);
						
						if(!$permited)
						throw new Exception(NOT_ACCESS_CONTROL);
						
						$resultPermission		= $this->core_web_permission->urlPermissionCmd($this->router->class,"add",$this->config->item('url_suffix'),$dataSession,$dataSession["menuTop"],$dataSession["menuLeft"],$dataSession["menuBodyReport"],$dataSession["menuBodyTop"],$dataSession["menuHiddenPopup"]);
						if ($resultPermission 	== PERMISSION_NONE)
						throw new Exception(NOT_ALL_INSERT);			

			
			}	
			
			//Obtener la lista de Workflow
			$companyID 	= $dataSession["user"]->companyID;
			$branchID 	= $dataSession["user"]->branchID;
			$roleID 	= $dataSession["role"]->roleID;
			$data["objListComponentPeriodStatus"]  	= $this->core_web_workflow->getWorkflowInitStage("tb_accounting_period","statusID",$companyID,$branchID,$roleID);
			$data["objListComponentCycleStatus"]  	= $this->core_web_workflow->getWorkflowInitStage("tb_accounting_cycle","statusID",$companyID,$branchID,$roleID);
			
			//Renderizar Resultado 
			$dataSession["notification"]	= $this->core_web_error->get_error($dataSession["user"]->userID);
			$dataSession["message"]			= $this->core_web_notification->get_message();
			$dataSession["head"]			= $this->load->view('app_account_period/news_head',$data,true);
			$dataSession["body"]			= $this->load->view('app_account_period/news_body',$data,true);
			$dataSession["script"]			= $this->load->view('app_account_period/news_script',$data,true);  
			$dataSession["footer"]			= "";
			$this->load->view("core_masterpage/default_masterpage",$dataSession);	
			
		}
		catch(Exception $ex){
			show_error($ex->getLine()." ".$ex->getMessage() ,500 );
		}	
			
    }
	function index($dataViewID = null){	
	try{ 
		
			
			//AUTENTICACION
			if(!$this->core_web_authentication->isAuthenticated())
			throw new Exception(USER_NOT_AUTENTICATED);
			$dataSession		= $this->session->all_userdata();
		
			//PERMISO SOBRE LA FUNCION
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
			$objComponent		= $this->core_web_tools->getComponentIDBy_ComponentName("0-CONTABILIDAD");
			if(!$objComponent)
			throw new Exception("00409 EL COMPONENTE '0-CONTABILIDAD' NO EXISTE...");
			
			
			//Vista por defecto 
			if($dataViewID == null){				
				$targetComponentID			= 0;	
				$parameter["{companyID}"]	= $this->session->userdata('user')->companyID;
				$parameter["{componentID}"]	= $objComponent->componentID;
				$dataViewData				= $this->core_web_view->getViewDefault($this->session->userdata('user'),$objComponent->componentID,CALLERID_LIST,$targetComponentID,$resultPermission,$parameter);			
				$dataViewRender				= $this->core_web_view->renderGreed($dataViewData,'ListView',"fnTableSelectedRow");
			}
			//Otra vista
			else{									
				$parameter["{companyID}"]	= $this->session->userdata('user')->companyID;
				$parameter["{componentID}"]	= $objComponent->componentID;
				$dataViewData				= $this->core_web_view->getViewBy_DataViewID($this->session->userdata('user'),$objComponent->componentID,$dataViewID,CALLERID_LIST,$resultPermission,$parameter); 			
				$dataViewRender				= $this->core_web_view->renderGreed($dataViewData,'ListView',"fnTableSelectedRow");
			} 
			 
			//Renderizar Resultado
			$dataSession["notification"]	= $this->core_web_error->get_error($dataSession["user"]->userID);
			$dataSession["message"]			= $this->core_web_notification->get_message();
			$dataSession["head"]			= $this->load->view('app_account_period/list_head','',true);
			$dataSession["footer"]			= $this->load->view('app_account_period/list_footer','',true);
			$dataSession["body"]			= $dataViewRender; 
			$dataSession["script"]			= $this->load->view('app_account_period/list_script','',true).$this->core_web_javascript->createVar("componentID",$objComponent->componentID);  
			$this->load->view("core_masterpage/default_masterpage",$dataSession);		
		}
		catch(Exception $ex){
			show_error($ex->getLine()." ".$ex->getMessage() ,500 );
		}
	}
}
?>
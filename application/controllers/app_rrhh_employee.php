<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');



class App_Rrhh_Employee extends CI_Controller {

	

    public function __construct() {

       parent::__construct();

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

			

			$this->load->model("Employee_Model");	

			$this->load->model("Entity_Model");

			$this->load->model("Natural_Model");

			$this->load->model("Entity_Phone_Model");	

			$this->load->model("Entity_Email_Model");

			

			//Obtener el Componente de Transacciones Other Input to Inventory

			$objComponent							= $this->core_web_tools->getComponentIDBy_ComponentName("tb_employee");

			if(!$objComponent)

			throw new Exception("EL COMPONENTE 'tb_employee' NO EXISTE...");

			

			

			$branchID 								= $dataSession["user"]->branchID;

			$roleID 								= $dataSession["role"]->roleID;

			$companyID 								= $dataSession["user"]->companyID;

			

			$companyID_ 							= $this->input->post("txtCompanyID");

			$branchID_								= $this->input->post("txtBranchID");

			$entityID_								= $this->input->post("txtEntityID");

			

			$objEmployee							= $this->Employee_Model->get_rowByPK($companyID_,$branchID_,$entityID_);

			$oldStatusID 							= $objEmployee->statusID;

			

			//Validar Edicion por el Usuario

			if ($resultPermission 	== PERMISSION_ME && ($objEmployee->createdBy != $dataSession["user"]->userID))

			throw new Exception(NOT_EDIT);

			

			//Validar si el estado permite editar

			if(!$this->core_web_workflow->validateWorkflowStage("tb_employee","statusID",$objEmployee->statusID,COMMAND_EDITABLE_TOTAL,$dataSession["user"]->companyID,$dataSession["user"]->branchID,$dataSession["role"]->roleID))

			throw new Exception(NOT_WORKFLOW_EDIT);					

			

			

			

			$this->db->trans_begin();			

			//El Estado solo permite editar el workflow

			if($this->core_web_workflow->validateWorkflowStage("tb_employee","statusID",$oldStatusID,COMMAND_EDITABLE,$dataSession["user"]->companyID,$dataSession["user"]->branchID,$dataSession["role"]->roleID)){				

				$objEmployee["statusID"] 		= $this->input->post('txtStatusID','');
				$this->Employee_Model->update($companyID_,$branchID_,$entityID_,$objEmployee);

			}

			else{

				$objNatural["isActive"]		= true;

				$objNatural["firstName"]	= $this->input->post("txtFirstName",'');

				$objNatural["lastName"]		= $this->input->post("txtLastName",'');

				$objNatural["address"]		= $this->input->post("txtAddress",'');

				$this->Natural_Model->update($companyID_,$branchID_,$entityID_,$objNatural);



				$objEmployee 							= NULL;				

				$objEmployee["numberIdentification"]	= $this->input->post('txtIdentification','');

				$objEmployee["identificationTypeID"]	= $this->input->post('txtIdentificationTypeID','');

				$objEmployee["typeEmployeeID"]			= $this->input->post('txtTypeEmployeeID','');

				$objEmployee["categoryID"]				= $this->input->post('txtCategoryID','');

				$objEmployee["clasificationID"]			= $this->input->post("txtClasificationID",'');

				$objEmployee["reference1"]				= $this->input->post("txtReference1",'');

				$objEmployee["reference2"]				= $this->input->post("txtReference2",'');

				$objEmployee["socialSecurityNumber"]	= $this->input->post("txtSocialSecurityNumber",'');

				$objEmployee["hourCost"]				= $this->input->post('txtHourCost','');

				$objEmployee["countryID"]				= $this->input->post('txtCountryID','');

				$objEmployee["stateID"]					= $this->input->post('txtStateID','');

				$objEmployee["cityID"]					= $this->input->post("txtCityID",'');

				$objEmployee["address"]					= $this->input->post("txtAddress",'');			

				$objEmployee["statusID"]				= $this->input->post('txtStatusID','');

				$objEmployee["departamentID"]			= $this->input->post('txtDepartamentID','');

				$objEmployee["areaID"]					= $this->input->post('txtAreaID','');

				$objEmployee["parentEmployeeID"]		= $this->input->post("txtParentEmployeeID",'');

				$objEmployee["startOn"]					= $this->input->post("txtStartOn",'');

				$objEmployee["endOn"]					= $this->input->post("txtEndOn",'');			

				$objEmployee["isActive"]				= true;

				$this->Employee_Model->update($companyID_,$branchID_,$entityID_,$objEmployee);

			

			}

			

			

			//Email

			$this->Entity_Email_Model->deleteByEntity($companyID_,$branchID_,$entityID_);

			$arrayListEntityEmail 				= $this->input->post("txtEntityEmail");

			$arrayListEntityEmailIsPrimary		= $this->input->post("txtEmailIsPrimary");			

			if(!empty($arrayListEntityEmail))

			foreach($arrayListEntityEmail as $key => $value){

				$objEntityEmail["companyID"]	= $companyID_;

				$objEntityEmail["branchID"]		= $branchID_;

				$objEntityEmail["entityID"]		= $entityID_;

				$objEntityEmail["email"]		= $value;

				$objEntityEmail["isPrimary"]	= $arrayListEntityEmailIsPrimary[$key];

				$this->Entity_Email_Model->insert($objEntityEmail);

			}

			

			//Phone

			$this->Entity_Phone_Model->deleteByEntity($companyID_,$branchID_,$entityID_);

			$arrayListEntityPhoneTypeID			= $this->input->post("txtEntityPhoneTypeID");

			$arrayListEntityPhoneNumber 		= $this->input->post("txtEntityPhoneNumber");

			$arrayListEntityPhoneIsPrimary 		= $this->input->post("txtEntityPhoneIsPrimary");			

			if(!empty($arrayListEntityPhoneTypeID))

			foreach($arrayListEntityPhoneTypeID as $key => $value){

				$objEntityPhone["companyID"]	= $companyID_;

				$objEntityPhone["branchID"]		= $branchID_;

				$objEntityPhone["entityID"]		= $entityID_;

				$objEntityPhone["typeID"]		= $value;

				$objEntityPhone["number"]		= $arrayListEntityPhoneNumber[$key];

				$objEntityPhone["isPrimary"]	= $arrayListEntityPhoneIsPrimary[$key];

				$this->Entity_Phone_Model->insert($objEntityPhone);

			}	

			

			//Confirmar Entidad

			if($this->db->trans_status() !== false){

				$this->db->trans_commit();						

				$this->core_web_notification->set_message(false,SUCCESS);

				redirect('app_rrhh_employee/edit/companyID/'.$companyID_."/branchID/".$branchID_."/entityID/".$entityID_);

			}

			else{

				$this->db->trans_rollback();						

				$this->core_web_notification->set_message(true,$this->db->_error_message());

				redirect('app_rrhh_employee/add');	

			}

		}

		catch(Exception $ex){

			show_error($ex->getLine()." ".$ex->getMessage() ,500 );

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

			

			//Librerias		

			//

			////////////////////////////////////////

			$this->load->model("Entity_Model"); 			

			$this->load->model("Natural_Model");

			$this->load->model("Employee_Model");			

			$this->load->model("Entity_Phone_Model");

			$this->load->model("Entity_Email_Model");

			

			

			//Redireccionar datos

			$uri			= $this->uri->uri_to_assoc(3);

						

			$companyID		= $uri["companyID"];

			$branchID		= $uri["branchID"];	

			$entityID		= $uri["entityID"];	

			$branchIDUser	= $dataSession["user"]->branchID;

			$roleID 		= $dataSession["role"]->roleID;			

			if((!$companyID || !$branchID || !$entityID))

			{ 

				redirect('app_rrhh_employee/add');	

			} 		

			

			

			//Obtener el Registro			

			$datView["objEntity"]	 			= $this->Entity_Model->get_rowByPK($companyID,$branchID,$entityID);

			$datView["objNatural"]	 			= $this->Natural_Model->get_rowByPK($companyID,$branchID,$entityID);

			$datView["objEmployee"]	 			= $this->Employee_Model->get_rowByPK($companyID,$branchID,$entityID);

			$datView["objParentEmployee"] 		= $this->Employee_Model->get_rowByEntityID($companyID,$datView["objEmployee"]->parentEmployeeID); 

			$datView["objParentNatural"]		= $datView["objParentEmployee"] == null ? $datView["objParentEmployee"] : $this->Natural_Model->get_rowByPK($companyID,$datView["objParentEmployee"]->branchID,$datView["objParentEmployee"]->entityID);

			$datView["objEntityListEmail"]		= $this->Entity_Email_Model->get_rowByEntity($companyID,$branchID,$entityID);

			$datView["objEntityListPhone"]		= $this->Entity_Phone_Model->get_rowByEntity($companyID,$branchID,$entityID);

			

			

			$objComponent						= $this->core_web_tools->getComponentIDBy_ComponentName("tb_employee");

			if(!$objComponent)

			throw new Exception("00409 EL COMPONENTE 'tb_employee' NO EXISTE...");

			

			

			//Obtener Informacion

			$datView["objListWorkflowStage"]			= $this->core_web_workflow->getWorkflowStageByStageInit("tb_employee","statusID",$datView["objEmployee"]->statusID,$companyID,$branchIDUser,$roleID);

			$datView["objComponent"] 					= $objComponent;

			$datView["objListCountry"]					= $this->core_web_catalog->getCatalogAllItem("tb_employee","countryID",$companyID);

			$datView["objListState"]					= $this->core_web_catalog->getCatalogAllItem_Parent("tb_employee","stateID",$companyID,$datView["objEmployee"]->countryID);

			$datView["objListCity"]						= $this->core_web_catalog->getCatalogAllItem_Parent("tb_employee","cityID",$companyID,$datView["objEmployee"]->stateID);			

			$datView["objListIdentificationType"]		= $this->core_web_catalog->getCatalogAllItem("tb_employee","identificationTypeID",$companyID);			

			$datView["objListDepartamentID"]			= $this->core_web_catalog->getCatalogAllItem("tb_employee","departamentID",$companyID);					

			$datView["objListAreaID"]					= $this->core_web_catalog->getCatalogAllItem("tb_employee","areaID",$companyID);			

			$datView["objListClasificationID"]			= $this->core_web_catalog->getCatalogAllItem("tb_employee","clasificationID",$companyID);			

			$datView["objListCategoryID"]				= $this->core_web_catalog->getCatalogAllItem("tb_employee","categoryID",$companyID);

			$datView["objListTypeEmployeeID"]			= $this->core_web_catalog->getCatalogAllItem("tb_employee","typeEmployeeID",$companyID);

			

			

			//Renderizar Resultado

			$dataSession["notification"]	= $this->core_web_error->get_error($dataSession["user"]->userID);

			$dataSession["message"]			= $this->core_web_notification->get_message();

			$dataSession["head"]			= $this->load->view('app_rrhh_employee/edit_head',$datView,true);

			$dataSession["body"]			= $this->load->view('app_rrhh_employee/edit_body',$datView,true);

			$dataSession["script"]			= $this->load->view('app_rrhh_employee/edit_script',$datView,true);  

			$dataSession["footer"]			= "";				

			$this->load->view("core_masterpage/default_masterpage",$dataSession);	

			

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

			

			$this->load->model("Employee_Model");	

			$this->load->model("Entity_Model");

			$this->load->model("Natural_Model");

			$this->load->model("Entity_Phone_Model");	

			$this->load->model("Entity_Email_Model");

			

			

			$this->core_web_permission->getValueLicense($dataSession["user"]->companyID,$this->router->class."/"."index");
			//Obtener el Componente de Transacciones Other Input to Inventory

			$objComponent							= $this->core_web_tools->getComponentIDBy_ComponentName("tb_employee");

			if(!$objComponent)

			throw new Exception("EL COMPONENTE 'tb_employee' NO EXISTE...");

			

			

			//Obtener transaccion

			$companyID 								= $dataSession["user"]->companyID;			

			$objEntity["companyID"] 				= $dataSession["user"]->companyID;			

			$objEntity["branchID"]					= $dataSession["user"]->branchID;			

			$this->core_web_auditoria->setAuditCreated($objEntity,$dataSession);

			

			$this->db->trans_begin();

			$entityID = $this->Entity_Model->insert($objEntity);

			

			$objNatural["companyID"]	= $objEntity["companyID"];

			$objNatural["branchID"] 	= $objEntity["branchID"];

			$objNatural["entityID"]		= $entityID;

			$objNatural["isActive"]		= true;

			$objNatural["firstName"]	= $this->input->post("txtFirstName",'');

			$objNatural["lastName"]		= $this->input->post("txtLastName",'');

			$objNatural["address"]		= $this->input->post("txtAddress",'');

			$result 					= $this->Natural_Model->insert($objNatural);

			

			$objEmployee["companyID"]				= $objEntity["companyID"];

			$objEmployee["branchID"]				= $objEntity["branchID"];

			$objEmployee["entityID"]				= $entityID;

			$objEmployee["employeNumber"]			= $this->core_web_counter->goNextNumber($dataSession["user"]->companyID,$dataSession["user"]->branchID,"tb_employee",0);

			$objEmployee["numberIdentification"]	= $this->input->post('txtIdentification','');

			$objEmployee["identificationTypeID"]	= $this->input->post('txtIdentificationTypeID','');

			$objEmployee["typeEmployeeID"]			= $this->input->post('txtTypeEmployeeID','');

			$objEmployee["categoryID"]				= $this->input->post('txtCategoryID','');

			$objEmployee["clasificationID"]			= $this->input->post("txtClasificationID",'');

			$objEmployee["reference1"]				= $this->input->post("txtReference1",'');

			$objEmployee["reference2"]				= $this->input->post("txtReference2",'');

			$objEmployee["socialSecurityNumber"]	= $this->input->post("txtSocialSecurityNumber",'');

			$objEmployee["hourCost"]				= $this->input->post('txtHourCost','');

			$objEmployee["countryID"]				= $this->input->post('txtCountryID','');

			$objEmployee["stateID"]					= $this->input->post('txtStateID','');

			$objEmployee["cityID"]					= $this->input->post("txtCityID",'');

			$objEmployee["address"]					= $this->input->post("txtAddress",'');			

			$objEmployee["statusID"]				= $this->input->post('txtStatusID','');

			$objEmployee["departamentID"]			= $this->input->post('txtDepartamentID','');

			$objEmployee["areaID"]					= $this->input->post('txtAreaID','');

			$objEmployee["parentEmployeeID"]		= $this->input->post("txtParentEmployeeID",'');

			$objEmployee["startOn"]					= $this->input->post("txtStartOn",'');

			$objEmployee["endOn"]					= $this->input->post("txtEndOn",'');			

			$objEmployee["isActive"]				= true;

			$this->core_web_auditoria->setAuditCreated($objEmployee,$dataSession);

			$result 							= $this->Employee_Model->insert($objEmployee);

			

			//Email

			$arrayListEntityEmail 				= $this->input->post("txtEntityEmail");

			$arrayListEntityEmailIsPrimary		= $this->input->post("txtEmailIsPrimary");			

			if(!empty($arrayListEntityEmail))

			foreach($arrayListEntityEmail as $key => $value){

				$objEntityEmail["companyID"]	= $objEntity["companyID"];

				$objEntityEmail["branchID"]		= $objEntity["branchID"];

				$objEntityEmail["entityID"]		= $entityID;

				$objEntityEmail["email"]		= $value;

				$objEntityEmail["isPrimary"]	= $arrayListEntityEmailIsPrimary[$key];

				$this->Entity_Email_Model->insert($objEntityEmail);

			}

			

			//Phone

			$arrayListEntityPhoneTypeID			= $this->input->post("txtEntityPhoneTypeID");

			$arrayListEntityPhoneNumber 		= $this->input->post("txtEntityPhoneNumber");

			$arrayListEntityPhoneIsPrimary 		= $this->input->post("txtEntityPhoneIsPrimary");			

			if(!empty($arrayListEntityPhoneTypeID))

			foreach($arrayListEntityPhoneTypeID as $key => $value){

				$objEntityPhone["companyID"]	= $objEntity["companyID"];

				$objEntityPhone["branchID"]		= $objEntity["branchID"];

				$objEntityPhone["entityID"]		= $entityID;

				$objEntityPhone["typeID"]		= $value;

				$objEntityPhone["number"]		= $arrayListEntityPhoneNumber[$key];

				$objEntityPhone["isPrimary"]	= $arrayListEntityPhoneIsPrimary[$key];

				$this->Entity_Phone_Model->insert($objEntityPhone);

			}

			

			

			//Crear la Carpeta para almacenar los Archivos del Cliente

			mkdir(PATH_FILE_OF_APP."/company_".$companyID."/component_".$objComponent->componentID."/component_item_".$entityID, 0700);

			

			if($this->db->trans_status() !== false){

				$this->db->trans_commit();						

				$this->core_web_notification->set_message(false,SUCCESS);

				redirect('app_rrhh_employee/edit/companyID/'.$companyID."/branchID/".$objEntity["branchID"]."/entityID/".$entityID);

			}

			else{

				$this->db->trans_rollback();						

				$this->core_web_notification->set_message(true,$this->db->_error_message());

				redirect('app_rrhh_employee/add');	

			}

			

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

			$this->load->model("Natural_Model");  

			$this->load->model("Employee_Model");  

			

			//Nuevo Registro

			$companyID 			= $this->input->post("companyID");

			$branchID 			= $this->input->post("branchID");				

			$entityID 			= $this->input->post("entityID");				

			

			if((!$companyID && !$branchID && !$entityID)){

					throw new Exception(NOT_PARAMETER);			

					 

			} 

			

			//OBTENER EL EMPLEADO

			$objEmployee 		= $this->Employee_Model->get_rowByPK($companyID,$branchID,$entityID);	

			

			

			//PERMISO SOBRE EL REGISTRO

			if ($resultPermission 	== PERMISSION_ME && ($objEmployee->createdBy != $dataSession["user"]->userID))

			throw new Exception(NOT_DELETE);

			

			

			//PERMISO PUEDE ELIMINAR EL REGISTRO SEGUN EL WORKFLOW

			if(!$this->core_web_workflow->validateWorkflowStage("tb_employee","statusID",$objEmployee->statusID,COMMAND_ELIMINABLE,$dataSession["user"]->companyID,$dataSession["user"]->branchID,$dataSession["role"]->roleID))

			throw new Exception(NOT_WORKFLOW_DELETE);

			

			//Eliminar el Registro

			$this->Employee_Model->delete($companyID,$branchID,$entityID);

					

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

	

	function save($mode){

			//AUTENTICADO

			if(!$this->core_web_authentication->isAuthenticated())

			throw new Exception(USER_NOT_AUTENTICATED);

			$dataSession		= $this->session->all_userdata();

			

			//Validar Formulario						

			$this->form_validation->set_rules("txtCountryID","Pais","required");

			$this->form_validation->set_rules("txtStateID","Departamento","required");

			$this->form_validation->set_rules("txtCityID","Municipio","required");

			$this->form_validation->set_rules("txtIdentification","Identificacion","required");

				

				

			//Validar Formulario

			if(!$this->form_validation->run()){

				$stringValidation = $this->core_web_tools->formatMessageError(validation_errors());

				$this->core_web_notification->set_message(true,$stringValidation);

				redirect('app_rrhh_employee/add');

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

				redirect('app_rrhh_employee/add');

				exit;

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

			 

			 

			

			$dataView							= null;			

			$companyID 							= $dataSession["user"]->companyID;

			$branchID 							= $dataSession["user"]->branchID;

			$roleID 							= $dataSession["role"]->roleID;			

			$objComponent						= $this->core_web_tools->getComponentIDBy_ComponentName("tb_employee");

			

			if(!$objComponent)

			throw new Exception("EL COMPONENTE 'tb_employee' NO EXISTE...");

			

			

			$dataView["componentEmployeeID"] 			= $objComponent->componentID;			

			$dataView["objListWorkflowStage"]			= $this->core_web_workflow->getWorkflowInitStage("tb_employee","statusID",$companyID,$branchID,$roleID);

			$dataView["objListIdentificationType"]		= $this->core_web_catalog->getCatalogAllItem("tb_employee","identificationTypeID",$companyID);			

			$dataView["objListCountry"]					= $this->core_web_catalog->getCatalogAllItem("tb_employee","countryID",$companyID);						

			$dataView["objListDepartamentID"]			= $this->core_web_catalog->getCatalogAllItem("tb_employee","departamentID",$companyID);					

			$dataView["objListAreaID"]					= $this->core_web_catalog->getCatalogAllItem("tb_employee","areaID",$companyID);			

			$dataView["objListClasificationID"]			= $this->core_web_catalog->getCatalogAllItem("tb_employee","clasificationID",$companyID);			

			$dataView["objListCategoryID"]				= $this->core_web_catalog->getCatalogAllItem("tb_employee","categoryID",$companyID);

			$dataView["objListTypeEmployeeID"]			= $this->core_web_catalog->getCatalogAllItem("tb_employee","typeEmployeeID",$companyID);

			

			//Renderizar Resultado 

			$dataSession["notification"]	= $this->core_web_error->get_error($dataSession["user"]->userID);

			$dataSession["message"]			= $this->core_web_notification->get_message();

			$dataSession["head"]			= $this->load->view('app_rrhh_employee/news_head',$dataView,true);

			$dataSession["body"]			= $this->load->view('app_rrhh_employee/news_body',$dataView,true);

			$dataSession["script"]			= $this->load->view('app_rrhh_employee/news_script',$dataView,true);  

			$dataSession["footer"]			= "";

			$this->load->view("core_masterpage/default_masterpage",$dataSession);	

			

		}

		catch(Exception $ex){

			show_error($ex->getLine()." ".$ex->getMessage() ,500 );

		}	

			

    }

	function index($dataViewID = null){	

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

			//Obtener el componente Para mostrar la lista de AccountType

			$objComponent		= $this->core_web_tools->getComponentIDBy_ComponentName("tb_employee");

			if(!$objComponent)

			throw new Exception("00409 EL COMPONENTE 'tb_employee' NO EXISTE...");

			

			

			//Vista por defecto 

			if($dataViewID == null){				

				$targetComponentID			= 0;	

				$parameter["{companyID}"]	= $this->session->userdata('user')->companyID;

				$dataViewData				= $this->core_web_view->getViewDefault($this->session->userdata('user'),$objComponent->componentID,CALLERID_LIST,$targetComponentID,$resultPermission,$parameter);			

				$dataViewRender				= $this->core_web_view->renderGreed($dataViewData,'ListView',"fnTableSelectedRow");

			}

			//Otra vista

			else{									

				$parameter["{companyID}"]	= $this->session->userdata('user')->companyID;

				$dataViewData				= $this->core_web_view->getViewBy_DataViewID($this->session->userdata('user'),$objComponent->componentID,$dataViewID,CALLERID_LIST,$resultPermission,$parameter); 			

				$dataViewRender				= $this->core_web_view->renderGreed($dataViewData,'ListView',"fnTableSelectedRow");

			} 

			 

			//Renderizar Resultado

			$dataSession["notification"]	= $this->core_web_error->get_error($dataSession["user"]->userID);

			$dataSession["message"]			= $this->core_web_notification->get_message();

			$dataSession["head"]			= $this->load->view('app_rrhh_employee/list_head','',true);

			$dataSession["footer"]			= $this->load->view('app_rrhh_employee/list_footer','',true);

			$dataSession["body"]			= $dataViewRender; 

			$dataSession["script"]			= $this->load->view('app_rrhh_employee/list_script','',true).$this->core_web_javascript->createVar("componentID",$objComponent->componentID);  

			$this->load->view("core_masterpage/default_masterpage",$dataSession);		

		}

		catch(Exception $ex){

			show_error($ex->getLine()." ".$ex->getMessage() ,500 );

		}

	}

	function add_email(){

			

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

			

			

			//Renderizar Resultado

			$dataSession["message"]		= "";

			$dataSession["head"]		= $this->load->view('app_rrhh_employee/popup_addemail_head','',true);

			$dataSession["body"]		= $this->load->view('app_rrhh_employee/popup_addemail_body','',true);

			$dataSession["script"]		= $this->load->view('app_rrhh_employee/popup_addemail_script','',true);  

			$this->load->view("core_masterpage/default_popup",$dataSession);

	}

	function add_phone(){

			

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

			

			$companyID 						= $dataSession["user"]->companyID;

			$data["objListPhoneTypeID"]		= $this->core_web_catalog->getCatalogAllItem("tb_entity_phone","typeID",$companyID);

			

			//Renderizar Resultado

			$dataSession["message"]		= "";

			$dataSession["head"]		= $this->load->view('app_rrhh_employee/popup_addphone_head','',true);

			$dataSession["body"]		= $this->load->view('app_rrhh_employee/popup_addphone_body',$data,true);

			$dataSession["script"]		= $this->load->view('app_rrhh_employee/popup_addphone_script','',true);  

			$this->load->view("core_masterpage/default_popup",$dataSession);

	}

	

}

?>
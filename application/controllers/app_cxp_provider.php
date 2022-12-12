<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');



class App_Cxp_Provider extends CI_Controller {

	

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

			

			$this->load->model("Provider_Model");	

			$this->load->model("Entity_Model");

			$this->load->model('Legal_Model');

			$this->load->model("Natural_Model");

			$this->load->model("Entity_Phone_Model");	

			$this->load->model("Entity_Email_Model");

			

			//Obtener el Componente de Transacciones Other Input to Inventory

			$objComponent							= $this->core_web_tools->getComponentIDBy_ComponentName("tb_provider");

			if(!$objComponent)

			throw new Exception("EL COMPONENTE 'tb_provider' NO EXISTE...");

			

			

			$branchID 								= $dataSession["user"]->branchID;

			$roleID 								= $dataSession["role"]->roleID;

			$companyID 								= $dataSession["user"]->companyID;

			

			$companyID_ 							= $this->input->post("txtCompanyID");

			$branchID_								= $this->input->post("txtBranchID");

			$entityID_								= $this->input->post("txtEntityID");

			

			$objProvider							= $this->Provider_Model->get_rowByPK($companyID_,$branchID_,$entityID_);

			$oldStatusID 							= $objProvider->statusID;

			

			//Validar Edicion por el Usuario

			if ($resultPermission 	== PERMISSION_ME && ($objProvider->createdBy != $dataSession["user"]->userID))

			throw new Exception(NOT_EDIT);

			

			//Validar si el estado permite editar

			if(!$this->core_web_workflow->validateWorkflowStage("tb_provider","statusID",$objProvider->statusID,COMMAND_EDITABLE_TOTAL,$dataSession["user"]->companyID,$dataSession["user"]->branchID,$dataSession["role"]->roleID))

			throw new Exception(NOT_WORKFLOW_EDIT);					

			

			

			

			$this->db->trans_begin();			

			//El Estado solo permite editar el workflow

			if($this->core_web_workflow->validateWorkflowStage("tb_provider","statusID",$oldStatusID,COMMAND_EDITABLE,$dataSession["user"]->companyID,$dataSession["user"]->branchID,$dataSession["role"]->roleID)){				

				$objProvider["statusID"] 		= $this->input->post('txtStatusID','');

				$this->Provider_Model->update($companyID_,$branchID_,$entityID_,$objProvider);

			}

			else{

				$objNatural["isActive"]		= true;

				$objNatural["firstName"]	= $this->input->post("txtFirstName",'');

				$objNatural["lastName"]		= $this->input->post("txtLastName",'');

				$objNatural["address"]		= $this->input->post("txtAddress",'');

				$this->Natural_Model->update($companyID_,$branchID_,$entityID_,$objNatural);



				$objLegal["isActive"]		= true;

				$objLegal["comercialName"]	= $this->input->post("txtCommercialName",'');

				$objLegal["legalName"]		= $this->input->post("txtLegalName",'');

				$objLegal["address"]		= $this->input->post("txtAddress",'');

				$this->Legal_Model->update($companyID_,$branchID_,$entityID_,$objLegal);

				

				$objProvider 							= NULL;

				$objProvider["numberIdentification"]	= $this->input->post('txtIdentification','');

				$objProvider["identificationTypeID"]	= $this->input->post('txtIdentificationTypeID','');

				$objProvider["providerType"]			= $this->input->post('txtProviderTypeID','');

				$objProvider["providerCategoryID"]		= $this->input->post('txtCategoryID','');

				$objProvider["providerClasificationID"]	= $this->input->post("txtClasificationID",'');

				$objProvider["reference1"]				= $this->input->post("txtReference1",'');

				$objProvider["reference2"]				= $this->input->post("txtReference2",'');

				$objProvider["payConditionID"]			= $this->input->post("txtTypePayID",'');

				$objProvider["isLocal"]					= $this->input->post('txtIsLocal','');

				$objProvider["countryID"]				= $this->input->post('txtCountryID','');

				$objProvider["stateID"]					= $this->input->post('txtStateID','');

				$objProvider["cityID"]					= $this->input->post("txtCityID",'');

				$objProvider["address"]					= $this->input->post("txtAddress",'');

				$objProvider["currencyID"]				= $this->input->post('txtCurrencyID','');

				$objProvider["statusID"]				= $this->input->post('txtStatusID','');

				$objProvider["deleveryDay"]				= $this->input->post('txtDayDelevery','');

				$objProvider["deleveryDayReal"]			= $this->input->post('txtDayDeleveryReal','');

				$objProvider["distancia"]				= $this->input->post("txtDistancia",'');

				$objProvider["isActive"]				= true;

				$this->Provider_Model->update($companyID_,$branchID_,$entityID_,$objProvider);

			

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

				$objEntityEmail["isPrimary"]	= $arrayListEntityEmailIsPrimary[$key] == 1 ? true : false; 

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

				redirect('app_cxp_provider/edit/companyID/'.$companyID_."/branchID/".$branchID_."/entityID/".$entityID_);

			}

			else{

				$this->db->trans_rollback();						

				$this->core_web_notification->set_message(true,$this->db->_error_message());

				redirect('app_cxp_provider/add');	

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

			

			$this->load->model("Provider_Model");	

			$this->load->model("Entity_Model");

			$this->load->model('Legal_Model');

			$this->load->model("Natural_Model");

			$this->load->model("Entity_Phone_Model");	

			$this->load->model("Entity_Email_Model");

			

			

			//Obtener el Componente de Transacciones Other Input to Inventory

			$objComponent							= $this->core_web_tools->getComponentIDBy_ComponentName("tb_provider");

			if(!$objComponent)

			throw new Exception("EL COMPONENTE 'tb_provider' NO EXISTE...");

			

			
			$this->core_web_permission->getValueLicense($dataSession["user"]->companyID,$this->router->class."/"."index");
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

			

			$objLegal["companyID"]		= $objEntity["companyID"];

			$objLegal["branchID"]		= $objEntity["branchID"];

			$objLegal["entityID"]		= $entityID;

			$objLegal["isActive"]		= true;

			$objLegal["comercialName"]	= $this->input->post("txtCommercialName",'');

			$objLegal["legalName"]		= $this->input->post("txtLegalName",'');

			$objLegal["address"]		= $this->input->post("txtAddress",'');

			$result 					= $this->Legal_Model->insert($objLegal);

			

			$objProvider["companyID"]				= $objEntity["companyID"];

			$objProvider["branchID"]				= $objEntity["branchID"];

			$objProvider["entityID"]				= $entityID;

			$objProvider["providerNumber"]			= $this->core_web_counter->goNextNumber($dataSession["user"]->companyID,$dataSession["user"]->branchID,"tb_provider",0);

			$objProvider["numberIdentification"]	= $this->input->post('txtIdentification','');

			$objProvider["identificationTypeID"]	= $this->input->post('txtIdentificationTypeID','');

			$objProvider["providerType"]			= $this->input->post('txtProviderTypeID','');

			$objProvider["providerCategoryID"]		= $this->input->post('txtCategoryID','');

			$objProvider["providerClasificationID"]	= $this->input->post("txtClasificationID",'');

			$objProvider["reference1"]				= $this->input->post("txtReference1",'');

			$objProvider["reference2"]				= $this->input->post("txtReference2",'');

			$objProvider["payConditionID"]			= $this->input->post("txtTypePayID",'');

			$objProvider["isLocal"]					= $this->input->post('txtIsLocal','');

			$objProvider["countryID"]				= $this->input->post('txtCountryID','');

			$objProvider["stateID"]					= $this->input->post('txtStateID','');

			$objProvider["cityID"]					= $this->input->post("txtCityID",'');

			$objProvider["address"]					= $this->input->post("txtAddress",'');

			$objProvider["currencyID"]				= $this->input->post('txtCurrencyID','');

			$objProvider["statusID"]				= $this->input->post('txtStatusID','');

			$objProvider["deleveryDay"]				= $this->input->post('txtDayDelevery','');

			$objProvider["deleveryDayReal"]			= $this->input->post('txtDayDeleveryReal','');

			$objProvider["distancia"]				= $this->input->post("txtDistancia",'');

			$objProvider["isActive"]				= true;

			$this->core_web_auditoria->setAuditCreated($objProvider,$dataSession);

			$result 							= $this->Provider_Model->insert($objProvider);

			

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

				redirect('app_cxp_provider/edit/companyID/'.$companyID."/branchID/".$objEntity["branchID"]."/entityID/".$entityID);

			}

			else{

				$this->db->trans_rollback();						

				$this->core_web_notification->set_message(true,$this->db->_error_message());

				redirect('app_cxp_provider/add');	

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

			$this->load->model("Legal_Model");  

			$this->load->model("Natural_Model");  

			$this->load->model("Provider_Model");  

			

			//Nuevo Registro

			$companyID 			= $this->input->post("companyID");

			$branchID 			= $this->input->post("branchID");				

			$entityID 			= $this->input->post("entityID");				

			

			if((!$companyID && !$branchID && !$entityID)){

					throw new Exception(NOT_PARAMETER);			

					 

			} 

			

			//OBTENER EL PROVEEDOR

			$objProvider 		= $this->Provider_Model->get_rowByPK($companyID,$branchID,$entityID);	

			

			

			//PERMISO SOBRE EL REGISTRO

			if ($resultPermission 	== PERMISSION_ME && ($objProvider->createdBy != $dataSession["user"]->userID))

			throw new Exception(NOT_DELETE);

			

			

			//PERMISO PUEDE ELIMINAR EL REGISTRO SEGUN EL WORKFLOW

			if(!$this->core_web_workflow->validateWorkflowStage("tb_provider","statusID",$objProvider->statusID,COMMAND_ELIMINABLE,$dataSession["user"]->companyID,$dataSession["user"]->branchID,$dataSession["role"]->roleID))

			throw new Exception(NOT_WORKFLOW_DELETE);

			

			//Eliminar el Registro

			$this->Provider_Model->delete($companyID,$branchID,$entityID);

					

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

				redirect('app_cxp_provider/add');

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

				redirect('app_cxp_provider/add');

				exit;

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

			$this->load->model("Company_Currency_Model"); 

			$this->load->model("Entity_Model"); 			

			$this->load->model("Natural_Model");

			$this->load->model("Legal_Model");

			$this->load->model("Provider_Model");			

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

				redirect('app_cxp_provider/add');	

			} 		

			

			

			//Obtener el Registro			

			$datView["objEntity"]	 			= $this->Entity_Model->get_rowByPK($companyID,$branchID,$entityID);

			$datView["objNatural"]	 			= $this->Natural_Model->get_rowByPK($companyID,$branchID,$entityID);

			$datView["objLegal"]	 			= $this->Legal_Model->get_rowByPK($companyID,$branchID,$entityID);

			$datView["objProvider"]	 			= $this->Provider_Model->get_rowByPK($companyID,$branchID,$entityID);

			$datView["objEntityListEmail"]		= $this->Entity_Email_Model->get_rowByEntity($companyID,$branchID,$entityID);

			$datView["objEntityListPhone"]		= $this->Entity_Phone_Model->get_rowByEntity($companyID,$branchID,$entityID);

			

			

			$objComponent						= $this->core_web_tools->getComponentIDBy_ComponentName("tb_provider");

			if(!$objComponent)

			throw new Exception("00409 EL COMPONENTE 'tb_provider' NO EXISTE...");

			

			

			//Obtener Informacion

			$datView["objListWorkflowStage"]			= $this->core_web_workflow->getWorkflowStageByStageInit("tb_provider","statusID",$datView["objProvider"]->statusID,$companyID,$branchIDUser,$roleID);

			$datView["objListCurrency"]					= $this->Company_Currency_Model->getByCompany($companyID);			

			$datView["objComponent"] 					= $objComponent;

			$datView["objCurrency"]						= $this->core_web_currency->getCurrencyDefault($companyID);			

			$datView["objListCountry"]					= $this->core_web_catalog->getCatalogAllItem("tb_provider","countryID",$companyID);

			$datView["objListState"]					= $this->core_web_catalog->getCatalogAllItem_Parent("tb_provider","stateID",$companyID,$datView["objProvider"]->countryID);

			$datView["objListCity"]						= $this->core_web_catalog->getCatalogAllItem_Parent("tb_provider","cityID",$companyID,$datView["objProvider"]->stateID);			

			$datView["objListIdentificationType"]		= $this->core_web_catalog->getCatalogAllItem("tb_provider","identificationTypeID",$companyID);						

			$datView["objListProviderTypeID"]			= $this->core_web_catalog->getCatalogAllItem("tb_provider","providerType",$companyID);

			$datView["objListCategoryID"]				= $this->core_web_catalog->getCatalogAllItem("tb_provider","providerCategoryID",$companyID);

			$datView["objListClasificationID"]			= $this->core_web_catalog->getCatalogAllItem("tb_provider","providerClasificationID",$companyID);

			$datView["objListPayConditionID"]			= $this->core_web_catalog->getCatalogAllItem("tb_provider","payConditionID",$companyID);

			

			//Renderizar Resultado

			$dataSession["notification"]	= $this->core_web_error->get_error($dataSession["user"]->userID);

			$dataSession["message"]			=  $this->core_web_notification->get_message();

			$dataSession["head"]			= $this->load->view('app_cxp_provider/edit_head',$datView,true);

			$dataSession["body"]			= $this->load->view('app_cxp_provider/edit_body',$datView,true);

			$dataSession["script"]			= $this->load->view('app_cxp_provider/edit_script',$datView,true);  

			$dataSession["footer"]			= "";				

			$this->load->view("core_masterpage/default_masterpage",$dataSession);	

			

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

			 

			$this->load->model("Company_Currency_Model");			

			$dataView							= null;

			

			//Obtener Tasa de Cambio			

			$companyID 							= $dataSession["user"]->companyID;

			$branchID 							= $dataSession["user"]->branchID;

			$roleID 							= $dataSession["role"]->roleID;

			

			$dataView["objListWorkflowStage"]			= $this->core_web_workflow->getWorkflowInitStage("tb_provider","statusID",$companyID,$branchID,$roleID);

			$dataView["objListIdentificationType"]		= $this->core_web_catalog->getCatalogAllItem("tb_provider","identificationTypeID",$companyID);

			$dataView["objListCountry"]					= $this->core_web_catalog->getCatalogAllItem("tb_provider","countryID",$companyID);

			

			$dataView["objListProviderTypeID"]			= $this->core_web_catalog->getCatalogAllItem("tb_provider","providerType",$companyID);			

			$dataView["objListCategoryID"]				= $this->core_web_catalog->getCatalogAllItem("tb_provider","providerCategoryID",$companyID);			

			$dataView["objListClasificationID"]			= $this->core_web_catalog->getCatalogAllItem("tb_provider","providerClasificationID",$companyID);			

			$dataView["objListPayConditionID"]			= $this->core_web_catalog->getCatalogAllItem("tb_provider","payConditionID",$companyID);

			

			$dataView["objListCurrency"]				= $this->Company_Currency_Model->getByCompany($companyID);

			$objCurrency								= $this->core_web_currency->getCurrencyDefault($companyID);			

			$dataView["objCurrency"]					= $objCurrency;

			

			//Renderizar Resultado 

			$dataSession["notification"]	= $this->core_web_error->get_error($dataSession["user"]->userID);

			$dataSession["message"]			= $this->core_web_notification->get_message();

			$dataSession["head"]			= $this->load->view('app_cxp_provider/news_head',$dataView,true);

			$dataSession["body"]			= $this->load->view('app_cxp_provider/news_body',$dataView,true);

			$dataSession["script"]			= $this->load->view('app_cxp_provider/news_script',$dataView,true);  

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

			$objComponent		= $this->core_web_tools->getComponentIDBy_ComponentName("tb_provider");

			if(!$objComponent)

			throw new Exception("00409 EL COMPONENTE 'tb_provider' NO EXISTE...");

			

			

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

			$dataSession["head"]			= $this->load->view('app_cxp_provider/list_head','',true);

			$dataSession["footer"]			= $this->load->view('app_cxp_provider/list_footer','',true);

			$dataSession["body"]			= $dataViewRender; 

			$dataSession["script"]			= $this->load->view('app_cxp_provider/list_script','',true).$this->core_web_javascript->createVar("componentID",$objComponent->componentID);  

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

			$dataSession["head"]		= $this->load->view('app_cxp_provider/popup_addemail_head','',true);

			$dataSession["body"]		= $this->load->view('app_cxp_provider/popup_addemail_body','',true);

			$dataSession["script"]		= $this->load->view('app_cxp_provider/popup_addemail_script','',true);  

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

			$dataSession["head"]		= $this->load->view('app_cxp_provider/popup_addphone_head','',true);

			$dataSession["body"]		= $this->load->view('app_cxp_provider/popup_addphone_body',$data,true);

			$dataSession["script"]		= $this->load->view('app_cxp_provider/popup_addphone_script','',true);  

			$this->load->view("core_masterpage/default_popup",$dataSession);

	}

	

}

?>
<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class App_Cxc_Customer extends CI_Controller {
	
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
			
			//Librerias		
			//
			////////////////////////////////////////
			$this->load->model("Company_Currency_Model"); 
			$this->load->model("Entity_Model"); 			
			$this->load->model("Natural_Model");
			$this->load->model("Legal_Model");
			$this->load->model("Customer_Model");			
			$this->load->model("Entity_Phone_Model");
			$this->load->model("Entity_Email_Model");
			$this->load->model("Customer_Credit_Model");
			$this->load->model("Customer_Credit_Line_Model");
			$this->load->model("Entity_Account_Model");
			$this->load->model("Account_Model");
			$this->load->model("Customer_Consultas_Sin_Riesgo_Model"); 
			
			//Redireccionar datos
			$uri			= $this->uri->uri_to_assoc(3);
						
			$companyID		= $uri["companyID"];
			$branchID		= $uri["branchID"];	
			$entityID		= $uri["entityID"];	
			$branchIDUser	= $dataSession["user"]->branchID;
			$roleID 		= $dataSession["role"]->roleID;			
			if((!$companyID || !$branchID || !$entityID))
			{ 
				redirect('app_cxc_customer/add');	
			} 		
			
			
			//Obtener el Registro			
			$datView["objEntity"]	 			= $this->Entity_Model->get_rowByPK($companyID,$branchID,$entityID);
			$datView["objNatural"]	 			= $this->Natural_Model->get_rowByPK($companyID,$branchID,$entityID);
			$datView["objLegal"]	 			= $this->Legal_Model->get_rowByPK($companyID,$branchID,$entityID);
			$datView["objCustomer"]	 			= $this->Customer_Model->get_rowByPK($companyID,$branchID,$entityID);
			$datView["objEntityListEmail"]		= $this->Entity_Email_Model->get_rowByEntity($companyID,$branchID,$entityID);
			$datView["objEntityListPhone"]		= $this->Entity_Phone_Model->get_rowByEntity($companyID,$branchID,$entityID);
			$datView["objCustomerCredit"]		= $this->Customer_Credit_Model->get_rowByPK($companyID,$branchID,$entityID);
			$datView["objCustomerCreditLine"]	= $this->Customer_Credit_Line_Model->get_rowByEntity($companyID,$branchID,$entityID);
			$datView["objCustomerSinRiesgo"]	= $this->Customer_Consultas_Sin_Riesgo_Model->get_rowByCedula_FileName($companyID,str_replace("-","",$datView["objCustomer"]->identification));
			
			$objComponent						= $this->core_web_tools->getComponentIDBy_ComponentName("tb_customer");
			if(!$objComponent)
			throw new Exception("00409 EL COMPONENTE 'tb_customer' NO EXISTE...");
			
			$objComponentAccount				= $this->core_web_tools->getComponentIDBy_ComponentName("tb_account");
			if(!$objComponentAccount)
			throw new Exception("EL COMPONENTE 'tb_account' NO EXISTE...");
			
			
			$objListEntityAccount 						= $this->Entity_Account_Model->get_rowByEntity($companyID,$objComponent->componentID,$entityID);
			$objFirstEntityAccount						= $objListEntityAccount[0];
			$objAccount 								= $this->Account_Model->get_rowByPK($companyID,$objFirstEntityAccount->accountID);
			
			//Obtener Informacion
			$datView["objListWorkflowStage"]			= $this->core_web_workflow->getWorkflowStageByStageInit("tb_customer","statusID",$datView["objCustomer"]->statusID,$companyID,$branchIDUser,$roleID);
			$datView["objListCurrency"]					= $this->Company_Currency_Model->getByCompany($companyID);			
			$datView["objComponent"] 					= $objComponent;
			$datView["objCurrency"]						= $this->core_web_currency->getCurrencyDefault($companyID);
			$datView["objListIdentificationType"]		= $this->core_web_catalog->getCatalogAllItem("tb_customer","identificationType",$companyID);
			$datView["objListCountry"]					= $this->core_web_catalog->getCatalogAllItem("tb_customer","countryID",$companyID);
			$datView["objListState"]					= $this->core_web_catalog->getCatalogAllItem_Parent("tb_customer","stateID",$companyID,$datView["objCustomer"]->countryID);
			$datView["objListCity"]						= $this->core_web_catalog->getCatalogAllItem_Parent("tb_customer","cityID",$companyID,$datView["objCustomer"]->stateID);
			$datView["objListClasificationID"]			= $this->core_web_catalog->getCatalogAllItem("tb_customer","clasificationID",$companyID);
			$datView["objListCustomerTypeID"]			= $this->core_web_catalog->getCatalogAllItem("tb_customer","customerTypeID",$companyID);
			$datView["objListCategoryID"]				= $this->core_web_catalog->getCatalogAllItem("tb_customer","categoryID",$companyID);
			$datView["objListSubCategoryID"]			= $this->core_web_catalog->getCatalogAllItem("tb_customer","subCategoryID",$companyID);
			$datView["objListTypePay"]					= $this->core_web_catalog->getCatalogAllItem("tb_customer","typePay",$companyID);
			$datView["objListPayConditionID"]			= $this->core_web_catalog->getCatalogAllItem("tb_customer","payConditionID",$companyID);
			$datView["objListSexoID"]					= $this->core_web_catalog->getCatalogAllItem("tb_customer","sexoID",$companyID);						$datView["objListEstadoCivilID"]			= $this->core_web_catalog->getCatalogAllItem("tb_naturales","statusID",$companyID);						$datView["objListProfesionID"] 				= $this->core_web_catalog->getCatalogAllItem("tb_naturales","profesionID",$companyID);						$datView["objListTypeFirmID"] 				= $this->core_web_catalog->getCatalogAllItem("tb_customer","typeFirm",$companyID);
			$datView["objComponentAccount"] 			= $objComponentAccount;
			$datView["objEntityAccount"] 				= $objFirstEntityAccount;
			$datView["objAccount"] 						= $objAccount;
			
			
			
			//Renderizar Resultado
			$dataSession["notification"]	= $this->core_web_error->get_error($dataSession["user"]->userID);
			$dataSession["message"]			=  $this->core_web_notification->get_message();
			$dataSession["head"]			= $this->load->view('app_cxc_customer/edit_head',$datView,true);
			$dataSession["body"]			= $this->load->view('app_cxc_customer/edit_body',$datView,true);
			$dataSession["script"]			= $this->load->view('app_cxc_customer/edit_script',$datView,true);  
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
			$this->load->model("Legal_Model");  
			$this->load->model("Natural_Model");  
			$this->load->model("Customer_Model");  
			
			//Nuevo Registro
			$companyID 			= $this->input->post("companyID");
			$branchID 			= $this->input->post("branchID");				
			$entityID 			= $this->input->post("entityID");				
			
			if((!$companyID && !$branchID && !$entityID)){
					throw new Exception(NOT_PARAMETER);			
					 
			} 
			
			//OBTENER EL CLIENTE
			$objCustomer 		= $this->Customer_Model->get_rowByPK($companyID,$branchID,$entityID);	
			
			
			//PERMISO SOBRE EL REGISTRO
			if ($resultPermission 	== PERMISSION_ME && ($objCustomer->createdBy != $dataSession["user"]->userID))
			throw new Exception(NOT_DELETE);
			
			
			//PERMISO PUEDE ELIMINAR EL REGISTRO SEGUN EL WORKFLOW
			if(!$this->core_web_workflow->validateWorkflowStage("tb_customer","statusID",$objCustomer->statusID,COMMAND_ELIMINABLE,$dataSession["user"]->companyID,$dataSession["user"]->branchID,$dataSession["role"]->roleID))
			throw new Exception(NOT_WORKFLOW_DELETE);
			
			//Eliminar el Registro
			$this->Customer_Model->delete($companyID,$branchID,$entityID);
					
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
			
			$this->load->model("Customer_Model");	
			$this->load->model("Entity_Model");
			$this->load->model('Legal_Model');
			$this->load->model("Natural_Model");
			$this->load->model("Entity_Phone_Model");	
			$this->load->model("Entity_Email_Model");
			$this->load->model("Customer_Credit_Line_Model");
			$this->load->model("Customer_Credit_Model");
			$this->load->model("Company_Currency_Model");
			$this->load->model("Entity_Account_Model");
			$this->load->model("Account_Model");
			
			//Obtener el Componente de Transacciones Other Input to Inventory
			$objComponent							= $this->core_web_tools->getComponentIDBy_ComponentName("tb_customer");
			if(!$objComponent)
			throw new Exception("EL COMPONENTE 'tb_customer' NO EXISTE...");
			
			
			$branchID 								= $dataSession["user"]->branchID;
			$roleID 								= $dataSession["role"]->roleID;
			$companyID 								= $dataSession["user"]->companyID;
			
			//Moneda Dolares
			date_default_timezone_set(APP_TIMEZONE); 
			$objCurrencyDolares						= $this->core_web_currency->getCurrencyReport($companyID);
			$dateOn 								= date("Y-m-d");
			$dateOn 								= date_format(date_create($dateOn),"Y-m-d");
			$exchangeRate 							= 0;
			$exchangeRateTotal 						= 0;
			$exchangeRateAmount 					= 0;
			
			$companyID_ 							= $this->input->post("txtCompanyID");
			$branchID_								= $this->input->post("txtBranchID");
			$entityID_								= $this->input->post("txtEntityID");
			
			$objCustomer							= $this->Customer_Model->get_rowByPK($companyID_,$branchID_,$entityID_);
			$oldStatusID 							= $objCustomer->statusID;
			
			//Validar Edicion por el Usuario
			if ($resultPermission 	== PERMISSION_ME && ($objCustomer->createdBy != $dataSession["user"]->userID))
			throw new Exception(NOT_EDIT);
			
			//Validar si el estado permite editar
			if(!$this->core_web_workflow->validateWorkflowStage("tb_customer","statusID",$objCustomer->statusID,COMMAND_EDITABLE_TOTAL,$dataSession["user"]->companyID,$dataSession["user"]->branchID,$dataSession["role"]->roleID))
			throw new Exception(NOT_WORKFLOW_EDIT);					
			
			
			
			$this->db->trans_begin();			
			//El Estado solo permite editar el workflow
			if($this->core_web_workflow->validateWorkflowStage("tb_customer","statusID",$oldStatusID,COMMAND_EDITABLE,$dataSession["user"]->companyID,$dataSession["user"]->branchID,$dataSession["role"]->roleID)){				
				$objCustomer["statusID"] 		= $this->input->post('txtStatusID','');
				$this->Customer_Model->update($companyID_,$branchID_,$entityID_,$objCustomer);
			}
			else{
				$objNatural["isActive"]		= true;
				$objNatural["firstName"]	= $this->input->post("txtFirstName",'');
				$objNatural["lastName"]		= $this->input->post("txtLastName",'');
				$objNatural["address"]		= $this->input->post("txtAddress",'');								$objNatural["statusID"]		= $this->input->post("txtCivilStatusID",'');								$objNatural["profesionID"]	= $this->input->post("txtProfesionID",'');
				$this->Natural_Model->update($companyID_,$branchID_,$entityID_,$objNatural);

				$objLegal["isActive"]		= true;
				$objLegal["comercialName"]	= $this->input->post("txtCommercialName",'');
				$objLegal["legalName"]		= $this->input->post("txtLegalName",'');
				$objLegal["address"]		= $this->input->post("txtAddress",'');
				$this->Legal_Model->update($companyID_,$branchID_,$entityID_,$objLegal);
				
				$objCustomer 						= NULL;
				$objCustomer["identificationType"]	= $this->input->post('txtIdentificationTypeID','');
				$objCustomer["identification"]		= $this->input->post('txtIdentification','');
				$objCustomer["countryID"]			= $this->input->post('txtCountryID','');
				$objCustomer["stateID"]				= $this->input->post('txtStateID','');
				$objCustomer["cityID"]				= $this->input->post("txtCityID",'');
				$objCustomer["location"]			= $this->input->post("txtLocation",'');
				$objCustomer["address"]				= $this->input->post("txtAddress",'');
				$objCustomer["currencyID"]			= $this->input->post("txtCurrencyID",'');
				$objCustomer["clasificationID"]		= $this->input->post('txtClasificationID','');
				$objCustomer["categoryID"]			= $this->input->post('txtCategoryID','');
				$objCustomer["subCategoryID"]		= $this->input->post('txtSubCategoryID','');
				$objCustomer["customerTypeID"]		= $this->input->post("txtCustomerTypeID",'');
				$objCustomer["birthDate"]			= $this->input->post("txtBirthDate",'');
				$objCustomer["statusID"]			= $this->input->post('txtStatusID','');
				$objCustomer["typePay"]				= $this->input->post('txtTypePayID','');
				$objCustomer["payConditionID"]		= $this->input->post('txtPayConditionID','');
				$objCustomer["sexoID"]				= $this->input->post('txtSexoID','');
				$objCustomer["reference1"]			= $this->input->post("txtReference1",'');
				$objCustomer["reference2"]			= $this->input->post("txtReference2",'');								$objCustomer["typeFirm"]			= $this->input->post("txtTypeFirmID",'');
				$objCustomer["isActive"]			= true;
				$this->Customer_Model->update($companyID_,$branchID_,$entityID_,$objCustomer);
				
				//Actualizar Customer Credit
				$objCustomerCredit 							= $this->Customer_Credit_Model->get_rowByPK($companyID_,$branchID_,$entityID_);
				$objCustomerCreditNew["limitCreditDol"] 	= helper_StringToNumber($this->input->post("txtLimitCreditDol",'0'));
				$objCustomerCreditNew["balanceDol"] 		= $objCustomerCreditNew["limitCreditDol"] - ($objCustomerCredit->limitCreditDol - $objCustomerCredit->balanceDol);
				$objCustomerCreditNew["incomeDol"] 			= helper_StringToNumber($this->input->post("txtIncomeDol",'0'));
				$this->Customer_Credit_Model->update($companyID_,$branchID_,$entityID_,$objCustomerCreditNew);
				
				//actualizar cuenta
				$objListEntityAccount 					= $this->Entity_Account_Model->get_rowByEntity($companyID_,$objComponent->componentID,$entityID_);
				$objFirstEntityAccount					= $objListEntityAccount[0];				
				$objEntityAccount["accountID"]			= empty($this->input->post("txtAccountID",'0')) ? 0 : $this->input->post("txtAccountID",'0');
				$this->Entity_Account_Model->update($objFirstEntityAccount->entityAccountID,$objEntityAccount);
			
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
			
			//Lineas de Creditos
			$arrayListCustomerCreditLineID	= $this->input->post("txtCustomerCreditLineID");
			$arrayListCreditLineID			= $this->input->post("txtCreditLineID");
			$arrayListCreditCurrencyID		= $this->input->post("txtCreditCurrencyID");
			$arrayListCreditStatusID		= $this->input->post("txtCreditStatusID");
			$arrayListCreditInterestYear	= $this->input->post("txtCreditInterestYear");
			$arrayListCreditInterestPay		= $this->input->post("txtCreditInterestPay");
			$arrayListCreditTotalPay		= $this->input->post("txtCreditTotalPay");
			$arrayListCreditTotalDefeated	= $this->input->post("txtCreditTotalDefeated");
			$arrayListCreditDateOpen		= $this->input->post("txtCreditDateOpen");
			$arrayListCreditPeriodPay		= $this->input->post("txtCreditPeriodPay");
			$arrayListCreditDateLastPay		= $this->input->post("txtCreditDateLastPay");
			$arrayListCreditTerm			= $this->input->post("txtCreditTerm");
			$arrayListCreditNote			= $this->input->post("txtCreditNote");
			$arrayListCreditLine			= $this->input->post("txtLine");
			$arrayListCreditNumber			= $this->input->post("txtLineNumber");
			$arrayListCreditLimit			= $this->input->post("txtLineLimit");
			$arrayListCreditBalance			= $this->input->post("txtLineBalance");
			$arrayListCreditStatus			= $this->input->post("txtLineStatus");
			$arrayListTypeAmortization		= $this->input->post("txtTypeAmortization");
			$limitCreditLine 				= 0;
			//Limpiar Lineas de Creditos
			$this->Customer_Credit_Line_Model->deleteWhereIDNotIn($companyID_,$branchID_,$entityID_,$arrayListCustomerCreditLineID);
			
			if(!empty($arrayListCustomerCreditLineID))
			foreach($arrayListCustomerCreditLineID as $key => $value){
			
				$customerCreditLineID 						= $value;
				if($customerCreditLineID == 0 ){
					$objCustomerCreditLine					= NULL;
					$objCustomerCreditLine["companyID"]		= $companyID_;
					$objCustomerCreditLine["branchID"]		= $branchID_;
					$objCustomerCreditLine["entityID"]		= $entityID_;
					$objCustomerCreditLine["creditLineID"]	= $arrayListCreditLineID[$key];
					$objCustomerCreditLine["accountNumber"]	= $this->core_web_counter->goNextNumber($dataSession["user"]->companyID,$dataSession["user"]->branchID,"tb_customer_credit_line",0);
					$objCustomerCreditLine["currencyID"]	= $arrayListCreditCurrencyID[$key];
					$objCustomerCreditLine["limitCredit"]	= helper_StringToNumber($arrayListCreditLimit[$key]);
					$objCustomerCreditLine["balance"]		= helper_StringToNumber($arrayListCreditLimit[$key]);
					$objCustomerCreditLine["interestYear"]	= helper_StringToNumber($arrayListCreditInterestYear[$key]);
					$objCustomerCreditLine["interestPay"]	= $arrayListCreditInterestPay[$key];
					$objCustomerCreditLine["totalPay"]		= $arrayListCreditTotalPay[$key];
					$objCustomerCreditLine["totalDefeated"]	= $arrayListCreditTotalDefeated[$key];
					$objCustomerCreditLine["dateOpen"]		= date("Y-m-d");
					$objCustomerCreditLine["periodPay"]		= $arrayListCreditPeriodPay[$key];
					$objCustomerCreditLine["dateLastPay"]	= date("Y-m-d");
					$objCustomerCreditLine["term"]			= helper_StringToNumber($arrayListCreditTerm[$key]);
					$objCustomerCreditLine["note"]			= $arrayListCreditNote[$key];
					$objCustomerCreditLine["statusID"]		= $arrayListCreditStatusID[$key];
					$objCustomerCreditLine["isActive"]		= 1;
					$objCustomerCreditLine["typeAmortization"]		= $arrayListTypeAmortization[$key];
					$limitCreditLine 								= $limitCreditLine + $objCustomerCreditLine["limitCredit"];
					$exchangeRate 									= $this->core_web_currency->getRatio($companyID,$dateOn,1,$objCurrencyDolares->currencyID,$objCustomerCreditLine["currencyID"]);
					$exchangeRateAmount								= $objCustomerCreditLine["limitCredit"];
					$this->Customer_Credit_Line_Model->insert($objCustomerCreditLine);
					
					if($objCustomerCreditLine["balance"] > $objCustomerCreditLine["limitCredit"])
					throw new Exception("BALANCE NO PUEDE SER MAYOR QUE EL LIMITE EN LA LINEA");
				}
				else{					
					$objCustomerCreditLine 							= $this->Customer_Credit_Line_Model->get_rowByPK($customerCreditLineID);
					$objCustomerCreditLineNew						= NULL;
					$objCustomerCreditLineNew["creditLineID"]		= $arrayListCreditLineID[$key];
					$objCustomerCreditLineNew["limitCredit"]		= helper_StringToNumber($arrayListCreditLimit[$key]);
					$objCustomerCreditLineNew["interestYear"]		= helper_StringToNumber($arrayListCreditInterestYear[$key]);
					$objCustomerCreditLineNew["balance"] 			= $objCustomerCreditLineNew["limitCredit"] - ($objCustomerCreditLine->limitCredit - $objCustomerCreditLine->balance);
					$objCustomerCreditLineNew["periodPay"]			= $arrayListCreditPeriodPay[$key];
					$objCustomerCreditLineNew["term"]				= helper_StringToNumber($arrayListCreditTerm[$key]);
					$objCustomerCreditLineNew["note"]				= $arrayListCreditNote[$key];
					$objCustomerCreditLineNew["statusID"]			= $arrayListCreditStatusID[$key];
					$objCustomerCreditLineNew["typeAmortization"]		= $arrayListTypeAmortization[$key];
					$limitCreditLine 									= $limitCreditLine + $objCustomerCreditLineNew["limitCredit"];
					$exchangeRate 										= $this->core_web_currency->getRatio($companyID,$dateOn,1,$objCurrencyDolares->currencyID,$objCustomerCreditLine->currencyID);
					$exchangeRateAmount									= $objCustomerCreditLineNew["limitCredit"];
					
					//Si el balance es mayor que el limite igual el balance al limite
					if($objCustomerCreditLineNew["balance"] > $objCustomerCreditLineNew["limitCredit"])
					$objCustomerCreditLineNew["balance"] = $objCustomerCreditLineNew["limitCredit"];
					
					//actualizar
					$this->Customer_Credit_Line_Model->update($customerCreditLineID,$objCustomerCreditLineNew);
					
					
			
				}
				
				//sumar los limites en dolares
				if($exchangeRate == 1)
					$exchangeRateTotal = $exchangeRateTotal + $exchangeRateAmount;
				//sumar los limite en cordoba
				else
					$exchangeRateTotal = $exchangeRateTotal + ($exchangeRateAmount / $exchangeRate);
					
				
			}
			
			//Validar Limite de Credito
			if($exchangeRateTotal > $objCustomerCreditNew["limitCreditDol"])
			throw new Exception("LINEAS DE CREDITOS MAL CONFIGURADAS LÍMITE EXCEDIDO");
			
			//Actualizar Balance
			if($objCustomerCreditNew["balanceDol"] > $objCustomerCreditNew["limitCreditDol"]){
				$objCustomerCreditNew["balanceDol"] = $objCustomerCreditNew["limitCreditDol"];
				$this->Customer_Credit_Model->update($companyID_,$branchID_,$entityID_,$objCustomerCreditNew);
			}
			
			//Confirmar Entidad
			if($this->db->trans_status() !== false){
				$this->db->trans_commit();						
				$this->core_web_notification->set_message(false,SUCCESS);
				redirect('app_cxc_customer/edit/companyID/'.$companyID_."/branchID/".$branchID_."/entityID/".$entityID_);
			}
			else{
				$this->db->trans_rollback();						
				$this->core_web_notification->set_message(true,$this->db->_error_message());
				redirect('app_cxc_customer/add');	
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
			
			$this->load->model("Customer_Model");	
			$this->load->model("Entity_Model");
			$this->load->model('Legal_Model');
			$this->load->model("Natural_Model");
			$this->load->model("Entity_Phone_Model");	
			$this->load->model("Entity_Email_Model");
			$this->load->model("Customer_Credit_Line_Model");
			$this->load->model("Customer_Credit_Model");
			$this->load->model("Company_Currency_Model");
			$this->load->model("Entity_Account_Model");
			$this->load->model("Account_Model");
			
			
						
			
			//Obtener el Componente de Transacciones Other Input to Inventory
			$objComponent							= $this->core_web_tools->getComponentIDBy_ComponentName("tb_customer");
			if(!$objComponent)
			throw new Exception("EL COMPONENTE 'tb_customer' NO EXISTE...");
			
			//Obtener transaccion
			$companyID 								= $dataSession["user"]->companyID;			
			$objEntity["companyID"] 				= $dataSession["user"]->companyID;			
			$objEntity["branchID"]					= $dataSession["user"]->branchID;			
			$this->core_web_auditoria->setAuditCreated($objEntity,$dataSession);
			
			//Moneda Dolares
			date_default_timezone_set(APP_TIMEZONE); 
			$objCurrencyDolares						= $this->core_web_currency->getCurrencyReport($companyID);
			$dateOn 								= date("Y-m-d");
			$dateOn 								= date_format(date_create($dateOn),"Y-m-d");
			$exchangeRate 							= 0;
			$exchangeRateTotal 						= 0;
			$exchangeRateAmount 					= 0;
			
			
			$this->db->trans_begin();
			$entityID = $this->Entity_Model->insert($objEntity);
			
			$objNatural["companyID"]	= $objEntity["companyID"];
			$objNatural["branchID"] 	= $objEntity["branchID"];
			$objNatural["entityID"]		= $entityID;
			$objNatural["isActive"]		= true;
			$objNatural["firstName"]	= $this->input->post("txtFirstName",'');
			$objNatural["lastName"]		= $this->input->post("txtLastName",'');
			$objNatural["address"]		= $this->input->post("txtAddress",'');			$objNatural["statusID"]		= $this->input->post("txtCivilStatusID",'');			$objNatural["profesionID"]	= $this->input->post("txtProfesionID",'');
			$result 					= $this->Natural_Model->insert($objNatural);
			
			$objLegal["companyID"]		= $objEntity["companyID"];
			$objLegal["branchID"]		= $objEntity["branchID"];
			$objLegal["entityID"]		= $entityID;
			$objLegal["isActive"]		= true;
			$objLegal["comercialName"]	= $this->input->post("txtCommercialName",'');
			$objLegal["legalName"]		= $this->input->post("txtLegalName",'');
			$objLegal["address"]		= $this->input->post("txtAddress",'');
			$result 					= $this->Legal_Model->insert($objLegal);
			
			$objCustomer["companyID"]			= $objEntity["companyID"];
			$objCustomer["branchID"]			= $objEntity["branchID"];
			$objCustomer["entityID"]			= $entityID;
			$objCustomer["customerNumber"]		= $this->core_web_counter->goNextNumber($dataSession["user"]->companyID,$dataSession["user"]->branchID,"tb_customer",0);
			$objCustomer["identificationType"]	= $this->input->post('txtIdentificationTypeID','');
			$objCustomer["identification"]		= $this->input->post('txtIdentification','');
			$objCustomer["countryID"]			= $this->input->post('txtCountryID','');
			$objCustomer["stateID"]				= $this->input->post('txtStateID','');
			$objCustomer["cityID"]				= $this->input->post("txtCityID",'');
			$objCustomer["location"]			= $this->input->post("txtLocation",'');
			$objCustomer["address"]				= $this->input->post("txtAddress",'');
			$objCustomer["currencyID"]			= $this->input->post("txtCurrencyID",'');
			$objCustomer["clasificationID"]		= $this->input->post('txtClasificationID','');
			$objCustomer["categoryID"]			= $this->input->post('txtCategoryID','');
			$objCustomer["subCategoryID"]		= $this->input->post('txtSubCategoryID','');
			$objCustomer["customerTypeID"]		= $this->input->post("txtCustomerTypeID",'');
			$objCustomer["birthDate"]			= $this->input->post("txtBirthDate",'');
			$objCustomer["statusID"]			= $this->input->post('txtStatusID','');
			$objCustomer["typePay"]				= $this->input->post('txtTypePayID','');
			$objCustomer["payConditionID"]		= $this->input->post('txtPayConditionID','');
			$objCustomer["sexoID"]				= $this->input->post('txtSexoID','');
			$objCustomer["reference1"]			= $this->input->post("txtReference1",'');
			$objCustomer["reference2"]			= $this->input->post("txtReference2",'');						$objCustomer["typeFirm"]			= $this->input->post("txtTypeFirmID",'');
			$objCustomer["isActive"]			= true;
			$this->core_web_auditoria->setAuditCreated($objCustomer,$dataSession);
			$result 							= $this->Customer_Model->insert($objCustomer);
			
			//Ingresar Cuenta
			$objEntityAccount["companyID"]			= $objEntity["companyID"];
			$objEntityAccount["componentID"]		= $objComponent->componentID;
			$objEntityAccount["componentItemID"]	= $entityID;
			$objEntityAccount["name"]				= "";
			$objEntityAccount["description"]		= "";
			$objEntityAccount["accountTypeID"]		= "0";
			$objEntityAccount["currencyID"]			= "0";
			$objEntityAccount["classID"]			= "0";
			$objEntityAccount["balance"]			= "0";
			$objEntityAccount["creditLimit"]		= "0";
			$objEntityAccount["maxCredit"]			= "0";
			$objEntityAccount["debitLimit"]			= "0";
			$objEntityAccount["maxDebit"]			= "0";
			$objEntityAccount["statusID"]			= "0";			
			$objEntityAccount["accountID"]			= empty($this->input->post("txtAccountID",'0')) ? '0': $this->input->post("txtAccountID",'0');
			$objEntityAccount["statusID"]			= "0";
			$objEntityAccount["isActive"]			= 1;
			$this->core_web_auditoria->setAuditCreated($objEntityAccount,$dataSession);
			$this->Entity_Account_Model->insert($objEntityAccount);
			
			//Ingresar Customer Credit
			$objCustomerCredit["companyID"] 		= $objEntity["companyID"];
			$objCustomerCredit["branchID"] 			= $objEntity["branchID"];
			$objCustomerCredit["entityID"] 			= $entityID;
			$objCustomerCredit["limitCreditDol"] 	= helper_StringToNumber($this->input->post("txtLimitCreditDol",'0'));
			$objCustomerCredit["balanceDol"] 		= $objCustomerCredit["limitCreditDol"];
			$objCustomerCredit["incomeDol"] 		= helper_StringToNumber($this->input->post("txtIncomeDol",'0'));
			$this->Customer_Credit_Model->insert($objCustomerCredit);
			
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
			
			//Lineas de Creditos
			$arrayListCustomerCreditLineID	= $this->input->post("txtCustomerCreditLineID");
			$arrayListCreditLineID			= $this->input->post("txtCreditLineID");
			$arrayListCreditCurrencyID		= $this->input->post("txtCreditCurrencyID");
			$arrayListCreditStatusID		= $this->input->post("txtCreditStatusID");
			$arrayListCreditInterestYear	= $this->input->post("txtCreditInterestYear");
			$arrayListCreditInterestPay		= $this->input->post("txtCreditInterestPay");
			$arrayListCreditTotalPay		= $this->input->post("txtCreditTotalPay");
			$arrayListCreditTotalDefeated	= $this->input->post("txtCreditTotalDefeated");
			$arrayListCreditDateOpen		= $this->input->post("txtCreditDateOpen");
			$arrayListCreditPeriodPay		= $this->input->post("txtCreditPeriodPay");
			$arrayListCreditDateLastPay		= $this->input->post("txtCreditDateLastPay");
			$arrayListCreditTerm			= $this->input->post("txtCreditTerm");
			$arrayListCreditNote			= $this->input->post("txtCreditNote");
			$arrayListCreditLine			= $this->input->post("txtLine");
			$arrayListCreditNumber			= $this->input->post("txtLineNumber");
			$arrayListCreditLimit			= $this->input->post("txtLineLimit");
			$arrayListCreditBalance			= $this->input->post("txtLineBalance");
			$arrayListCreditStatus			= $this->input->post("txtLineStatus");
			$arrayListTypeAmortization		= $this->input->post("txtTypeAmortization");
			
			$limitCreditLine 				= 0;
			if(!empty($arrayListCustomerCreditLineID))
			foreach($arrayListCustomerCreditLineID as $key => $value){
				$objCustomerCreditLine["companyID"]		= $objEntity["companyID"];
				$objCustomerCreditLine["branchID"]		= $objEntity["branchID"];
				$objCustomerCreditLine["entityID"]		= $entityID;
				$objCustomerCreditLine["creditLineID"]	= $arrayListCreditLineID[$key];
				$objCustomerCreditLine["accountNumber"]	= $this->core_web_counter->goNextNumber($dataSession["user"]->companyID,$dataSession["user"]->branchID,"tb_customer_credit_line",0);
				$objCustomerCreditLine["currencyID"]	= $arrayListCreditCurrencyID[$key];
				$objCustomerCreditLine["limitCredit"]	= helper_StringToNumber($arrayListCreditLimit[$key]);
				$objCustomerCreditLine["balance"]		= helper_StringToNumber($arrayListCreditLimit[$key]);
				$objCustomerCreditLine["interestYear"]	= helper_StringToNumber($arrayListCreditInterestYear[$key]);
				$objCustomerCreditLine["interestPay"]	= $arrayListCreditInterestPay[$key];
				$objCustomerCreditLine["totalPay"]		= $arrayListCreditTotalPay[$key];
				$objCustomerCreditLine["totalDefeated"]	= $arrayListCreditTotalDefeated[$key];
				$objCustomerCreditLine["dateOpen"]		= date("Y-m-d");
				$objCustomerCreditLine["periodPay"]		= $arrayListCreditPeriodPay[$key];
				$objCustomerCreditLine["dateLastPay"]	= date("Y-m-d");
				$objCustomerCreditLine["term"]			= helper_StringToNumber($arrayListCreditTerm[$key]);
				$objCustomerCreditLine["note"]			= $arrayListCreditNote[$key];
				$objCustomerCreditLine["statusID"]		= $arrayListCreditStatusID[$key];
				$objCustomerCreditLine["isActive"]		= 1;
				$objCustomerCreditLine["typeAmortization"]	= $arrayListTypeAmortization[$key];
				$limitCreditLine 							= $limitCreditLine + $objCustomerCreditLine["limitCredit"];
				$exchangeRate 								= $this->core_web_currency->getRatio($companyID,$dateOn,1,$objCurrencyDolares->currencyID,$objCustomerCreditLine["currencyID"]);
				$exchangeRateAmount							= $objCustomerCreditLine["limitCredit"];
				$this->Customer_Credit_Line_Model->insert($objCustomerCreditLine);
				
				//sumar los limites en dolares
				if($exchangeRate == 1)
					$exchangeRateTotal = $exchangeRateTotal + $exchangeRateAmount;
				//sumar los limite en cordoba
				else
					$exchangeRateTotal = $exchangeRateTotal + ($exchangeRateAmount / $exchangeRate);
				
				
			}
			
			//Validar Limite de Credito
			if($exchangeRateTotal > $objCustomerCredit["limitCreditDol"])
			throw new Exception("LINEAS DE CREDITOS MAL CONFIGURADAS LÍMITE EXCEDIDO");
			
			//Crear la Carpeta para almacenar los Archivos del Cliente
			mkdir(PATH_FILE_OF_APP."/company_".$companyID."/component_".$objComponent->componentID."/component_item_".$entityID, 0700);
			
			if($this->db->trans_status() !== false){
				$this->db->trans_commit();						
				$this->core_web_notification->set_message(false,SUCCESS);
				redirect('app_cxc_customer/edit/companyID/'.$companyID."/branchID/".$objEntity["branchID"]."/entityID/".$entityID);
			}
			else{
				$this->db->trans_rollback();						
				$this->core_web_notification->set_message(true,$this->db->_error_message());
				redirect('app_cxc_customer/add');	
			}
			
		}
		catch(Exception $ex){
			show_error($ex->getLine()." ".$ex->getMessage() ,500 );
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
				redirect('app_cxc_customer/add');
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
				redirect('app_cxc_customer/add');
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
			 
			$this->load->model("Company_Currency_Model");			
			$dataView							= null;
			
			//Obtener Tasa de Cambio			
			$companyID 							= $dataSession["user"]->companyID;
			$branchID 							= $dataSession["user"]->branchID;
			$roleID 							= $dataSession["role"]->roleID;
			
			$objComponentAccount				= $this->core_web_tools->getComponentIDBy_ComponentName("tb_account");
			if(!$objComponentAccount)
			throw new Exception("EL COMPONENTE 'tb_account' NO EXISTE...");
						$objParameterPais	= $this->core_web_parameter->getParameter("CXC_PAIS_DEFAULT",$companyID);						$objParameterPais 	= $objParameterPais->value;			$dataView["objParameterPais"] = $objParameterPais;						$objParameterDepartamento	= $this->core_web_parameter->getParameter("CXC_DEPARTAMENTO_DEFAULT",$companyID);						$objParameterDepartamento 	= $objParameterDepartamento->value;			$dataView["objParameterDepartamento"] = $objParameterDepartamento;						$objParameterMunicipio	= $this->core_web_parameter->getParameter("CXC_MUNICIPIO_DEFAULT",$companyID);						$objParameterMunicipio 	= $objParameterMunicipio->value;			$dataView["objParameterMunicipio"] = $objParameterMunicipio;		
			
			$dataView["objListWorkflowStage"]			= $this->core_web_workflow->getWorkflowInitStage("tb_customer","statusID",$companyID,$branchID,$roleID);
			$dataView["objListIdentificationType"]		= $this->core_web_catalog->getCatalogAllItem("tb_customer","identificationType",$companyID);
			$dataView["objListCountry"]					= $this->core_web_catalog->getCatalogAllItem("tb_customer","countryID",$companyID);
			$dataView["objListClasificationID"]			= $this->core_web_catalog->getCatalogAllItem("tb_customer","clasificationID",$companyID);
			$dataView["objListCustomerTypeID"]			= $this->core_web_catalog->getCatalogAllItem("tb_customer","customerTypeID",$companyID);
			$dataView["objListCategoryID"]				= $this->core_web_catalog->getCatalogAllItem("tb_customer","categoryID",$companyID);
			$dataView["objListSubCategoryID"]			= $this->core_web_catalog->getCatalogAllItem("tb_customer","subCategoryID",$companyID);
			$dataView["objListTypePay"]					= $this->core_web_catalog->getCatalogAllItem("tb_customer","typePay",$companyID);
			$dataView["objListPayConditionID"]			= $this->core_web_catalog->getCatalogAllItem("tb_customer","payConditionID",$companyID);
			$dataView["objListSexoID"]					= $this->core_web_catalog->getCatalogAllItem("tb_customer","sexoID",$companyID);						$dataView["objListEstadoCivilID"]			= $this->core_web_catalog->getCatalogAllItem("tb_naturales","statusID",$companyID);						$dataView["objListProfesionID"] 			= $this->core_web_catalog->getCatalogAllItem("tb_naturales","profesionID",$companyID);						$dataView["objListTypeFirmID"] 				= $this->core_web_catalog->getCatalogAllItem("tb_customer","typeFirm",$companyID);
			$dataView["objListCurrency"]				= $this->Company_Currency_Model->getByCompany($companyID);
			$objCurrency								= $this->core_web_currency->getCurrencyDefault($companyID);			
			$dataView["objCurrency"]					= $objCurrency;
			$dataView["objComponentAccount"]			= $objComponentAccount;
			
			
			
			//Renderizar Resultado
			$dataSession["notification"]	= $this->core_web_error->get_error($dataSession["user"]->userID);			
			$dataSession["message"]			= $this->core_web_notification->get_message();
			$dataSession["head"]			= $this->load->view('app_cxc_customer/news_head',$dataView,true);
			$dataSession["body"]			= $this->load->view('app_cxc_customer/news_body',$dataView,true);
			$dataSession["script"]			= $this->load->view('app_cxc_customer/news_script',$dataView,true);  
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
			$objComponent		= $this->core_web_tools->getComponentIDBy_ComponentName("tb_customer");
			if(!$objComponent)
			throw new Exception("00409 EL COMPONENTE 'tb_customer' NO EXISTE...");
			
			
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
			$dataSession["head"]			= $this->load->view('app_cxc_customer/list_head','',true);
			$dataSession["footer"]			= $this->load->view('app_cxc_customer/list_footer','',true);
			$dataSession["body"]			= $dataViewRender; 
			$dataSession["script"]			= $this->load->view('app_cxc_customer/list_script','',true).$this->core_web_javascript->createVar("componentID",$objComponent->componentID);  
			$this->load->view("core_masterpage/default_masterpage",$dataSession);		
		}
		catch(Exception $ex){
			show_error($ex->getLine()." ".$ex->getMessage() ,500 );
		}
	}
	function edit_credit_line(){
			
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
			
			$this->load->model("Credit_Line_Model");
			$this->load->model("Company_Currency_Model");
			$this->load->model("Customer_Credit_Line_Model");
			
			
			$uri								= $this->uri->uri_to_assoc(3);
			$customerCreditLineID				= $uri["customerCreditLineID"];	
			$companyID 							= $dataSession["user"]->companyID;
			$branchID 							= $dataSession["user"]->branchID;
			$roleID 							= $dataSession["role"]->roleID;
			
			
			$dataView["objListLine"]			= $this->Credit_Line_Model->get_rowByCompany($companyID);
			$dataView["objCurrencyList"]		= $this->Company_Currency_Model->getByCompany($companyID);
			$dataView["objListWorkflowStage"]	= $this->core_web_workflow->getWorkflowInitStage("tb_customer_credit_line","statusID",$companyID,$branchID,$roleID);
			$dataView["objListPay"]				= $this->core_web_catalog->getCatalogAllItem("tb_customer_credit_line","periodPay",$companyID);
			$dataView["objCustomerCreditLine"] 	= $this->Customer_Credit_Line_Model->get_rowByPK($customerCreditLineID);
			$dataView["objListTypeAmortization"]= $this->core_web_catalog->getCatalogAllItem("tb_customer_credit_line","typeAmortization",$companyID);
			
			//Renderizar Resultado
			$dataSession["message"]		= "";
			$dataSession["head"]		= $this->load->view('app_cxc_customer/popup_editcreditline_head',$dataView,true);
			$dataSession["body"]		= $this->load->view('app_cxc_customer/popup_editcreditline_body',$dataView,true);
			$dataSession["script"]		= $this->load->view('app_cxc_customer/popup_editcreditline_script',$dataView,true);  
			$this->load->view("core_masterpage/default_popup",$dataSession);
	}
	function add_credit_line(){
			
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
			
			$this->load->model("Credit_Line_Model");
			$this->load->model("Company_Currency_Model");
			
			$companyID 								= $dataSession["user"]->companyID;
			$branchID 								= $dataSession["user"]->branchID;
			$roleID 								= $dataSession["role"]->roleID;
			$dataView["objListLine"]				= $this->Credit_Line_Model->get_rowByCompany($companyID);
			$dataView["objCurrencyList"]			= $this->Company_Currency_Model->getByCompany($companyID);
			$dataView["objListWorkflowStage"]		= $this->core_web_workflow->getWorkflowInitStage("tb_customer_credit_line","statusID",$companyID,$branchID,$roleID);
			$dataView["objListPay"]					= $this->core_web_catalog->getCatalogAllItem("tb_customer_credit_line","periodPay",$companyID);
			$dataView["objListTypeAmortization"]	= $this->core_web_catalog->getCatalogAllItem("tb_customer_credit_line","typeAmortization",$companyID);
						$objParameterCurrenyDefault	= $this->core_web_parameter->getParameter("ACCOUNTING_CURRENCY_NAME_FUNCTION",$companyID);						$objParameterCurrenyDefault 	= $objParameterCurrenyDefault->value;			$dataView["objParameterCurrenyDefault"] = $objParameterCurrenyDefault;									$objParameterAmortizationDefault	= $this->core_web_parameter->getParameter("CXC_TYPE_AMORTIZATION",$companyID);						$objParameterAmortizationDefault 	= $objParameterAmortizationDefault->value;			$dataView["objParameterAmortizationDefault"] = $objParameterAmortizationDefault;									$objParameterPayDefault	= $this->core_web_parameter->getParameter("CXC_FRECUENCIA_PAY_DEFAULT",$companyID);						$objParameterPayDefault 	= $objParameterPayDefault->value;			$dataView["objParameterPayDefault"] = $objParameterPayDefault;
			
			//Renderizar Resultado
			$dataSession["message"]		= "";
			$dataSession["head"]		= $this->load->view('app_cxc_customer/popup_addcreditline_head',$dataView,true);
			$dataSession["body"]		= $this->load->view('app_cxc_customer/popup_addcreditline_body',$dataView,true);
			$dataSession["script"]		= $this->load->view('app_cxc_customer/popup_addcreditline_script',$dataView,true);  
			$this->load->view("core_masterpage/default_popup",$dataSession);
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
			$dataSession["head"]		= $this->load->view('app_cxc_customer/popup_addemail_head','',true);
			$dataSession["body"]		= $this->load->view('app_cxc_customer/popup_addemail_body','',true);
			$dataSession["script"]		= $this->load->view('app_cxc_customer/popup_addemail_script','',true);  
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
			$dataSession["head"]		= $this->load->view('app_cxc_customer/popup_addphone_head','',true);
			$dataSession["body"]		= $this->load->view('app_cxc_customer/popup_addphone_body',$data,true);
			$dataSession["script"]		= $this->load->view('app_cxc_customer/popup_addphone_script','',true);  
			$this->load->view("core_masterpage/default_popup",$dataSession);
	}
	
}
?>
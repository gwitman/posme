<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class App_Accounting_Parameters extends CI_Controller {
	
    public function __construct() {
       parent::__construct();
    }	
	
	function save(){
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
			$this->load->model('core/Company_Parameter_Model');
			
			$companyID 						= $dataSession["user"]->companyID;
			$accountNumberUtility 			= $this->input->post("accountUtility");//ACCOUNTING_NUMBER_UTILITY_PERIOD	
			$accountNumberUtilityAcumulate 	= $this->input->post("accountUtilityAcumulate");//ACCOUNTING_NUMBER_UTILITY_ACUMULATE		
			$currencyDefaultName 			= $this->input->post("currencyDefault");//ACCOUNTING_CURRENCY_NAME_FUNCTION		
			$formulateUtility 				= $this->input->post("formulateUtility");//ACCOUNTING_FORMULATE_OF_UTILITY
			$currencyReportName				= $this->input->post("currencyReport");//ACCOUNTING_CURRENCY_NAME_REPORT
			$accountNumberCostos			= $this->input->post("accountCostos");//ACCOUNTING_NUMBER_COSTOS
			$accountNumberGastos			= $this->input->post("accountGastos");//ACCOUNTING_NUMBER_GASTOS
			$accountNumberIngreso			= $this->input->post("accountIngreso");//ACCOUNTING_NUMBER_INGRESO
			$accountNumberActivo			= $this->input->post("accountActivo");//ACCOUNTING_NUMBER_ACTIVO
			$accountNumberPasivo			= $this->input->post("accountPasivo");//ACCOUNTING_NUMBER_PASIVO
			$accountNumberCapital			= $this->input->post("accountCapital");//ACCOUNTING_NUMBER_CAPITAL
			$accountResult					= $this->input->post("accountResult");//ACCOUNTING_ACCOUNTTYPE_RESULT
			$exchangePurchase				= $this->input->post("exchangePurchase");//ACCOUNTING_EXCHANGE_PURCHASE
			$exchangeSales					= $this->input->post("exchangeSales");//ACCOUNTING_EXCHANGE_SALE			
			$razon001						= $this->input->post("razon001");//ACCOUNTING_RF_RAZON_CIRCULANTE
			$razon002						= $this->input->post("razon002");//ACCOUNTING_RF_ENDEUDAMIENTO
			$razon003						= $this->input->post("razon003");//ACCOUNTING_RF_UTILIDAD_MENSUAL
			$razon004						= $this->input->post("razon004");//ACCOUNTING_RF_UTILIDAD_ANUAL
			$razon005						= $this->input->post("razon005");//ACCOUNTING_RF_RENTABILIDAD_ANUAL
			$razon006						= $this->input->post("razon006");//ACCOUNTING_RF_RENTABILIDAD_MENSUAL
			
			$objAccountResult 					= $this->core_web_parameter->getParameter("ACCOUNTING_ACCOUNTTYPE_RESULT",$companyID );
			$objAccountNumberCostos 			= $this->core_web_parameter->getParameter("ACCOUNTING_NUMBER_COSTOS",$companyID );
			$objAccountNumberGastos 			= $this->core_web_parameter->getParameter("ACCOUNTING_NUMBER_GASTOS",$companyID );
			$objAccountNumberIngreso 			= $this->core_web_parameter->getParameter("ACCOUNTING_NUMBER_INGRESO",$companyID );
			$objAccountNumberActivo 			= $this->core_web_parameter->getParameter("ACCOUNTING_NUMBER_ACTIVO",$companyID );
			$objAccountNumberPasivo 			= $this->core_web_parameter->getParameter("ACCOUNTING_NUMBER_PASIVO",$companyID );
			$objAccountNumberCapital 			= $this->core_web_parameter->getParameter("ACCOUNTING_NUMBER_CAPITAL",$companyID );			
			$objAccountNumberUtility 			= $this->core_web_parameter->getParameter("ACCOUNTING_NUMBER_UTILITY_PERIOD",$companyID );
			$objAccountNumberUtilityAcumulate 	= $this->core_web_parameter->getParameter("ACCOUNTING_NUMBER_UTILITY_ACUMULATE",$companyID );
			$objCurrencyDefaultName 			= $this->core_web_parameter->getParameter("ACCOUNTING_CURRENCY_NAME_FUNCTION",$companyID );
			$objFormulateUtility 				= $this->core_web_parameter->getParameter("ACCOUNTING_FORMULATE_OF_UTILITY",$companyID );
			$objCurrencyReportName 				= $this->core_web_parameter->getParameter("ACCOUNTING_CURRENCY_NAME_REPORT",$companyID );
			$objExchangePurchase 				= $this->core_web_parameter->getParameter("ACCOUNTING_EXCHANGE_PURCHASE",$companyID );
			$objExchangeSales 					= $this->core_web_parameter->getParameter("ACCOUNTING_EXCHANGE_SALE",$companyID );			
			$objRazon001 						= $this->core_web_parameter->getParameter("ACCOUNTING_RF_RAZON_CIRCULANTE",$companyID );
			$objRazon002 						= $this->core_web_parameter->getParameter("ACCOUNTING_RF_ENDEUDAMIENTO",$companyID );
			$objRazon003 						= $this->core_web_parameter->getParameter("ACCOUNTING_RF_UTILIDAD_MENSUAL",$companyID );
			$objRazon004 						= $this->core_web_parameter->getParameter("ACCOUNTING_RF_UTILIDAD_ANUAL",$companyID );
			$objRazon005 						= $this->core_web_parameter->getParameter("ACCOUNTING_RF_RENTABILIDAD_ANUAL",$companyID );
			$objRazon006 						= $this->core_web_parameter->getParameter("ACCOUNTING_RF_RENTABILIDAD_MENSUAL",$companyID );
			
			$data["value"] = $exchangeSales;			
			$this->Company_Parameter_Model->update($companyID,$objExchangeSales->parameterID,$data);
			$data["value"] = $exchangePurchase;			
			$this->Company_Parameter_Model->update($companyID,$objExchangePurchase->parameterID,$data);
			$data["value"] = $accountResult;			
			$this->Company_Parameter_Model->update($companyID,$objAccountResult->parameterID,$data);
			$data["value"] = $accountNumberCostos;			
			$this->Company_Parameter_Model->update($companyID,$objAccountNumberCostos->parameterID,$data);
			$data["value"] = $accountNumberGastos;			
			$this->Company_Parameter_Model->update($companyID,$objAccountNumberGastos->parameterID,$data);
			$data["value"] = $accountNumberIngreso;			
			$this->Company_Parameter_Model->update($companyID,$objAccountNumberIngreso->parameterID,$data);
			$data["value"] = $accountNumberActivo;			
			$this->Company_Parameter_Model->update($companyID,$objAccountNumberActivo->parameterID,$data);
			$data["value"] = $accountNumberPasivo;			
			$this->Company_Parameter_Model->update($companyID,$objAccountNumberPasivo->parameterID,$data);
			$data["value"] = $accountNumberCapital;			
			$this->Company_Parameter_Model->update($companyID,$objAccountNumberCapital->parameterID,$data);			
			$data["value"] = $accountNumberUtility;			
			$this->Company_Parameter_Model->update($companyID,$objAccountNumberUtility->parameterID,$data);
			$data["value"] = $accountNumberUtilityAcumulate;
			$this->Company_Parameter_Model->update($companyID,$objAccountNumberUtilityAcumulate->parameterID,$data);
			$data["value"] = $currencyDefaultName;
			$this->Company_Parameter_Model->update($companyID,$objCurrencyDefaultName->parameterID,$data);
			$data["value"] = $formulateUtility;
			$this->Company_Parameter_Model->update($companyID,$objFormulateUtility->parameterID,$data);
			$data["value"] = $currencyReportName;
			$this->Company_Parameter_Model->update($companyID,$objCurrencyReportName->parameterID,$data);
			$data["value"] = $razon001;			
			$this->Company_Parameter_Model->update($companyID,$objRazon001->parameterID,$data);
			$data["value"] = $razon002;			
			$this->Company_Parameter_Model->update($companyID,$objRazon002->parameterID,$data);
			$data["value"] = $razon003;			
			$this->Company_Parameter_Model->update($companyID,$objRazon003->parameterID,$data);
			$data["value"] = $razon004;			
			$this->Company_Parameter_Model->update($companyID,$objRazon004->parameterID,$data);
			$data["value"] = $razon005;			
			$this->Company_Parameter_Model->update($companyID,$objRazon005->parameterID,$data);
			$data["value"] = $razon006;			
			$this->Company_Parameter_Model->update($companyID,$objRazon006->parameterID,$data);
			
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
		}		
			
	}	
	function index($dataViewID = null){	
	try{ 
		
			//AUTENTICADO
			if(!$this->core_web_authentication->isAuthenticated())
			throw new Exception(USER_NOT_AUTENTICATED);
			$dataSession		= $this->session->all_userdata();
		
			//PERMISOS SOBRE LA FUNCTION
			if(APP_NEED_AUTHENTICATION == true){				
				
						$permited = false;
						$permited = $this->core_web_permission->urlPermited($this->router->class,"index",$this->config->item('url_suffix'),$dataSession["menuTop"],$dataSession["menuLeft"],$dataSession["menuBodyReport"],$dataSession["menuBodyTop"],$dataSession["menuHiddenPopup"]);
						
						if(!$permited)
						throw new Exception(NOT_ACCESS_CONTROL);
						
						$resultPermission		= $this->core_web_permission->urlPermissionCmd($this->router->class,"index",$this->config->item('url_suffix'),$dataSession,$dataSession["menuTop"],$dataSession["menuLeft"],$dataSession["menuBodyReport"],$dataSession["menuBodyTop"],$dataSession["menuHiddenPopup"]);
						if ($resultPermission 	== PERMISSION_NONE)
						throw new Exception(NOT_ACCESS_FUNCTION);			
		
			}	
			
			//Obtener Parametros Contables
			$objAccountCostos 				= $this->core_web_parameter->getParameter("ACCOUNTING_NUMBER_COSTOS",$dataSession["user"]->companyID);
			$objAccountGastos 				= $this->core_web_parameter->getParameter("ACCOUNTING_NUMBER_GASTOS",$dataSession["user"]->companyID);
			$objAccountIngreso 				= $this->core_web_parameter->getParameter("ACCOUNTING_NUMBER_INGRESO",$dataSession["user"]->companyID);
			$objAccountActivo 				= $this->core_web_parameter->getParameter("ACCOUNTING_NUMBER_ACTIVO",$dataSession["user"]->companyID);
			$objAccountPasivo 				= $this->core_web_parameter->getParameter("ACCOUNTING_NUMBER_PASIVO",$dataSession["user"]->companyID);
			$objAccountCapital 				= $this->core_web_parameter->getParameter("ACCOUNTING_NUMBER_CAPITAL",$dataSession["user"]->companyID);			
			$objAccountUtilityPeriod 		= $this->core_web_parameter->getParameter("ACCOUNTING_NUMBER_UTILITY_PERIOD",$dataSession["user"]->companyID);
			$objAccountUtilityAcumulate 	= $this->core_web_parameter->getParameter("ACCOUNTING_NUMBER_UTILITY_ACUMULATE",$dataSession["user"]->companyID);
			$objCurrencyDefault 			= $this->core_web_parameter->getParameter("ACCOUNTING_CURRENCY_NAME_FUNCTION",$dataSession["user"]->companyID);
			$objFormulateUtility 			= $this->core_web_parameter->getParameter("ACCOUNTING_FORMULATE_OF_UTILITY",$dataSession["user"]->companyID);
			$objCurrencyReport 				= $this->core_web_parameter->getParameter("ACCOUNTING_CURRENCY_NAME_REPORT",$dataSession["user"]->companyID);
			$objAccountResult 				= $this->core_web_parameter->getParameter("ACCOUNTING_ACCOUNTTYPE_RESULT",$dataSession["user"]->companyID);
			$objExchangePurchase 			= $this->core_web_parameter->getParameter("ACCOUNTING_EXCHANGE_PURCHASE",$dataSession["user"]->companyID);
			$objExchangeSales 				= $this->core_web_parameter->getParameter("ACCOUNTING_EXCHANGE_SALE",$dataSession["user"]->companyID);
			
			$objRazon001 					= $this->core_web_parameter->getParameter("ACCOUNTING_RF_RAZON_CIRCULANTE",$dataSession["user"]->companyID);
			$objRazon002 					= $this->core_web_parameter->getParameter("ACCOUNTING_RF_ENDEUDAMIENTO",$dataSession["user"]->companyID);
			$objRazon003 					= $this->core_web_parameter->getParameter("ACCOUNTING_RF_UTILIDAD_MENSUAL",$dataSession["user"]->companyID);
			$objRazon004 					= $this->core_web_parameter->getParameter("ACCOUNTING_RF_UTILIDAD_ANUAL",$dataSession["user"]->companyID);
			$objRazon005 					= $this->core_web_parameter->getParameter("ACCOUNTING_RF_RENTABILIDAD_ANUAL",$dataSession["user"]->companyID);
			$objRazon006 					= $this->core_web_parameter->getParameter("ACCOUNTING_RF_RENTABILIDAD_MENSUAL",$dataSession["user"]->companyID);
			
			$dataV["accountCostos"] 			= $objAccountCostos->value;
			$dataV["accountGastos"] 			= $objAccountGastos->value;
			$dataV["accountIngreso"] 			= $objAccountIngreso->value;
			$dataV["accountActivo"] 			= $objAccountActivo->value;
			$dataV["accountPasivo"] 			= $objAccountPasivo->value;
			$dataV["accountCapital"] 			= $objAccountCapital->value;			
			$dataV["accountUtilityPeriod"] 		= $objAccountUtilityPeriod->value;
			$dataV["accountUtilityAcumulate"] 	= $objAccountUtilityAcumulate->value;
			$dataV["currencyDefault"]			= $objCurrencyDefault->value;
			$dataV["formulateUtility"] 			= $objFormulateUtility->value;
			$dataV["currencyReport"] 			= $objCurrencyReport->value;
			$dataV["accountResult"] 			= $objAccountResult->value;
			$dataV["exchangePurchase"] 			= $objExchangePurchase->value;
			$dataV["exchangeSales"] 			= $objExchangeSales->value;
			$dataV["objRazon001"] 			= $objRazon001->value;
			$dataV["objRazon002"] 			= $objRazon002->value;
			$dataV["objRazon003"] 			= $objRazon003->value;
			$dataV["objRazon004"] 			= $objRazon004->value;
			$dataV["objRazon005"] 			= $objRazon005->value;
			$dataV["objRazon006"] 			= $objRazon006->value;
			
			//Renderizar Resultado 
			$dataSession["notification"]	= $this->core_web_error->get_error($dataSession["user"]->userID);
			$dataSession["message"]			= $this->core_web_notification->get_message();
			$dataSession["head"]			= $this->load->view('app_account_parameters/view_head',$dataV,true);
			$dataSession["body"]			= $this->load->view('app_account_parameters/view_body',$dataV,true);
			$dataSession["script"]			= $this->load->view('app_account_parameters/view_script',$dataV,true);  
			$dataSession["footer"]			= "";			
			$this->load->view("core_masterpage/default_masterpage",$dataSession);		
		}
		catch(Exception $ex){
			show_error($ex->getLine()." ".$ex->getMessage() ,500 );
		}
	}	
	
}
?>
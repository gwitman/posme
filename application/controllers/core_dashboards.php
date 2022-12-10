<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class core_dashboards extends CI_Controller {

	//Constructor ...
    public function __construct() {
       parent::__construct();
    }
    
	//INDEX
	////////////////////////////
	function index(){ 
		try{ 
		
			//AUTENTICACION
			if(!$this->core_web_authentication->isAuthenticated())
			throw new Exception(USER_NOT_AUTENTICATED);
			$dataSession		= $this->session->all_userdata();
			log_message("ERROR","session");
			log_message("ERROR",print_r($dataSession["company"]->name,true));
			
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
								
			//Validar Parametro de maximo de usuario.
			$companyID 									= $dataSession["company"]->companyID;
			$objParameterMAX_USER 						= $this->core_web_parameter->getParameter("CORE_CUST_PRICE_MAX_USER",$companyID);
			$objParameterMAX_USER 						= $objParameterMAX_USER->value;
			$dataSession["objParameterMAX_USER"] 		= $objParameterMAX_USER;

			$parameterFechaExpiration 					= $this->core_web_parameter->getParameter("CORE_CUST_PRICE_LICENCES_EXPIRED",$companyID);
			$parameterFechaExpiration 					= $parameterFechaExpiration->value;
			$parameterFechaExpiration 					= DateTime::createFromFormat('Y-m-d',$parameterFechaExpiration)->format("Y-m-d");			
			$dataSession["parameterFechaExpiration"] 	= $parameterFechaExpiration;

			$objParameterISleep							= $this->core_web_parameter->getParameter("CORE_CUST_PRICE_SLEEP",$companyID);
			$objParameterISleep							= $objParameterISleep->value;
			$dataSession["objParameterISleep"] 			= $objParameterISleep;

			$objParameterTipoPlan						= $this->core_web_parameter->getParameter("CORE_CUST_PRICE_TIPO_PLAN",$companyID);
			$objParameterTipoPlan						= $objParameterTipoPlan->value;
			$dataSession["objParameterTipoPlan"] 		= $objParameterTipoPlan;

			$objParameterExpiredLicense					= $this->core_web_parameter->getParameter("CORE_CUST_PRICE_LICENCES_EXPIRED",$companyID);
			$objParameterExpiredLicense					= $objParameterExpiredLicense->value;
			$objParameterExpiredLicense 				= DateTime::createFromFormat('Y-m-d',$objParameterExpiredLicense)->format("Y-m-d");	
			$dataSession["objParameterExpiredLicense"] 	= $objParameterExpiredLicense;

			$objParameterCreditos						= $this->core_web_parameter->getParameter("CORE_CUST_PRICE_BALANCE",$companyID);
			$objParameterCreditosID						= $objParameterCreditos->parameterID;
			$objParameterCreditos						= $objParameterCreditos->value;
			$dataSession["objParameterCreditos"] 		= $objParameterCreditos;

			$objParameterPriceByInvoice					= $this->core_web_parameter->getParameter("CORE_CUST_PRICE_BY_INVOICE",$companyID);
			$objParameterPriceByInvoice					= $objParameterPriceByInvoice->value;
			$dataSession["objParameterPriceByInvoice"] 	= $objParameterPriceByInvoice;

			
			
			//Renderizar Resultado
			$dataSession["notification"]	= $this->core_web_error->get_error($dataSession["user"]->userID);
			$dataSession["message"]			= "";
			$dataSession["head"]			= "";
			$dataSession["body"]			= $this->load->view('core_dasboard/dashboards_default',$dataSession,true);
			$dataSession["script"]			= ""; 
			$dataSession["footer"]			= ""; 			
			$this->load->view("core_masterpage/default_masterpage",$dataSession);		
		}
		catch(Exception $ex){
			show_error($ex->getMessage() ,500 );
		}
	}
}
?>
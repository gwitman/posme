<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class App_Cxc_Process extends CI_Controller {
	
    public function __construct() {
       parent::__construct();
    }	
		
	function uploadDataSinRiesgo(){
		try{ 
		
			
			//AUTENTICADO 
			if(!$this->core_web_authentication->isAuthenticated())
			throw new Exception(USER_NOT_AUTENTICATED);
			$dataSession		= $this->session->all_userdata();
			
			//PERMISO SOBRE FUNCIONES
			if(APP_NEED_AUTHENTICATION == true){
				$permited = false;
				$permited = $this->core_web_permission->urlPermited($this->router->class,"index",$this->config->item('url_suffix'),$dataSession["menuTop"],$dataSession["menuLeft"],$dataSession["menuBodyReport"],$dataSession["menuBodyTop"],$dataSession["menuHiddenPopup"]);
				
				if(!$permited)
				throw new Exception(NOT_ACCESS_CONTROL);
				
			}	
			
			
			$this->load->model('Customer_Consultas_Sin_Riesgo_Model');
			$this->load->model('core/Bd_Model');
			$companyID			= $dataSession["user"]->companyID;
			$branchID 			= $dataSession["user"]->branchID;
			$loginID			= $dataSession["user"]->userID;
			$tocken				= "";
			
			//Obtener La Data, de la vista.
			$query			    = "CALL pr_cxc_get_report_upload_buro('".$loginID."','".$tocken."','".$companyID."');";
			//$resultados1x 	= $this->Customer_Consultas_Sin_Riesgo_Model->get_rowByCompany($companyID);
			log_message("ERROR",print_r($query,true));
			$resultados1	= $this->Bd_Model->executeProcedureMultiQuery($query);
			$resultados1	= $resultados1[0];
			
			$resultados2	= array();
			foreach($resultados1 as $key => $value){
				$resultados2[$key]	= array_values($value);
			}
			 
			
			//https://www.sinriesgos.com.ni/ServiceFacade/servicios.asmx?wsdl
			//https://www.sinriesgos.com.ni/ServiceFacade/servicios.asmx?wsdl
			$objParameter 			= $this->core_web_parameter->getParameter("CORE_CXC_WSDL_SIN_RIESGO_UPLOAD",$companyID);//""			
			$objParameterCodigo		= $this->core_web_parameter->getParameter("CORE_CXC_WSDL_SIN_RIESGO_UPLOAD_CODIGO",$companyID);//"b77a5c561934e089"	
			$objParameterPassword 	= $this->core_web_parameter->getParameter("CORE_CXC_WSDL_SIN_RIESGO_PASSWORD",$companyID);//flc-wgonzalez
			$objParameterUsuario 	= $this->core_web_parameter->getParameter("CORE_CXC_WSDL_SIN_RIESGO_USUARIO",$companyID);//180389Gonzalez.
			$logDB["code"]			= 0;
			
			log_message("ERROR","*********************log de envio a sin riesgo*************************");
			log_message("ERROR",print_r($objParameter,true));
			log_message("ERROR",print_r($objParameterCodigo,true));
			log_message("ERROR",print_r($objParameterPassword,true));
			log_message("ERROR",print_r($objParameterUsuario,true));
			log_message("ERROR",print_r($objParameterUsuario,true));
			log_message("ERROR",print_r($resultados1,true));
			log_message("ERROR",print_r($resultados2,true));
			log_message("ERROR","*********************log de envio a sin riesgo*************************");
			$client 				= new SoapClient($objParameter->value);
			$params 				= array(
				"Codigo"					=> $objParameterCodigo->value,
				"Usuario" 					=> $objParameterUsuario->value,
				"Contraseña" 				=> $objParameterPassword->value,
				"lstCreditos"				=> $resultados2 
			);			
			
			$resultado = $client->ActualizarCreditosOL( $params );
			$this->output->set_content_type('application/json');
			$this->output->set_output(json_encode(array(
				'error'   => false,
				'message' => $resultado->ActualizarCreditosOLResult,
				'result'  => 0
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
		
			//PERMISOS SOBRE LA FUNCIONES
			if(APP_NEED_AUTHENTICATION == true){				
				
				$permited = false;
				$permited = $this->core_web_permission->urlPermited($this->router->class,$this->router->method,$this->config->item('url_suffix'),$dataSession["menuTop"],$dataSession["menuLeft"],$dataSession["menuBodyReport"],$dataSession["menuBodyTop"],$dataSession["menuHiddenPopup"]);
				
				if(!$permited)
				throw new Exception(NOT_ACCESS_CONTROL);
			
			}	
			
			$this->load->model('Component_Period_Model');
			$this->load->model('Transaction_Model');
			
			//Renderizar Resultado 
			$dataSession["notification"]	= $this->core_web_error->get_error($dataSession["user"]->userID);
			$dataSession["message"]			= $this->core_web_notification->get_message();
			$dataSession["head"]			= $this->load->view('app_cxc_process/view_head',NULL,true);
			$dataSession["body"]			= $this->load->view('app_cxc_process/view_body',NULL,true);
			$dataSession["script"]			= $this->load->view('app_cxc_process/view_script',NULL,true);  
			$dataSession["footer"]			= "";			
			$this->load->view("core_masterpage/default_masterpage",$dataSession);		
		}
		catch(Exception $ex){
			show_error($ex->getLine()." ".$ex->getMessage() ,500 );
		}
	}	
	
}
?>
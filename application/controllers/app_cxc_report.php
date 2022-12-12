<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class App_Cxc_Report extends CI_Controller {
	
    public function __construct() {
       parent::__construct();
    }	
	function index($dataViewID = null){	
		try{ 
		
			//AUTENTICADO
			if(!$this->core_web_authentication->isAuthenticated())
			throw new Exception(USER_NOT_AUTENTICATED);
			$dataSession		= $this->session->all_userdata();
		
			//PERMISOS SOBRE LAS FUNCIONES
			if(APP_NEED_AUTHENTICATION == true){				
				
				$permited = false;
				$permited = $this->core_web_permission->urlPermited($this->router->class,$this->router->method,$this->config->item('url_suffix'),$dataSession["menuTop"],$dataSession["menuLeft"],$dataSession["menuBodyReport"],$dataSession["menuBodyTop"],$dataSession["menuHiddenPopup"]);
				
				if(!$permited)
				throw new Exception(NOT_ACCESS_CONTROL);
				
				$resultPermission		= $this->core_web_permission->urlPermissionCmd($this->router->class,$this->router->method,$this->config->item('url_suffix'),$dataSession,$dataSession["menuTop"],$dataSession["menuLeft"],$dataSession["menuBodyReport"],$dataSession["menuBodyTop"],$dataSession["menuHiddenPopup"]);
				if ($resultPermission 	== PERMISSION_NONE)
				throw new Exception(NOT_ACCESS_FUNCTION);			
			
				$parentMenuElementID 	= $this->core_web_permission->getElementID($this->router->class,$this->router->method,$this->config->item('url_suffix'),$dataSession["menuTop"],$dataSession["menuLeft"],$dataSession["menuBodyReport"],$dataSession["menuBodyTop"],$dataSession["menuHiddenPopup"]);
			}	
			
			//Obtener la Lista de Reportes
			$dataMenu["menuRenderBodyReport"] 	
									= $this->core_web_menu->render_menu_body_report($dataSession["menuBodyReport"],$parentMenuElementID);
									
			//Renderizar Resultado 
			$dataSession["notification"]	= $this->core_web_error->get_error($dataSession["user"]->userID);
			$dataSession["message"]			= $this->core_web_notification->get_message();
			$dataSession["head"]			= $this->load->view('app_cxc_report/view_head','',true);
			$dataSession["body"]			= $this->load->view('app_cxc_report/view_body',$dataMenu,true);
			$dataSession["script"]			= $this->load->view('app_cxc_report/view_script','',true);  
			$dataSession["footer"]			= "";			
			$this->load->view("core_masterpage/default_masterpage",$dataSession);		
		}
		catch(Exception $ex){
			show_error($ex->getLine()." ".$ex->getMessage() ,500 );
		}
	}
	function document_contract(){
		try{ 
		
			//AUTENTICADO
			if(!$this->core_web_authentication->isAuthenticated())
			throw new Exception(USER_NOT_AUTENTICATED);
			$dataSession		= $this->session->all_userdata();
		
			//PERMISOS SOBRE LAS FUNCIONES
			if(APP_NEED_AUTHENTICATION == true){				
				
				$permited = false;
				$permited = $this->core_web_permission->urlPermited($this->router->class,"index",$this->config->item('url_suffix'),$dataSession["menuTop"],$dataSession["menuLeft"],$dataSession["menuBodyReport"],$dataSession["menuBodyTop"],$dataSession["menuHiddenPopup"]);
				
				if(!$permited)
				throw new Exception(NOT_ACCESS_CONTROL);
				
				$resultPermission		= $this->core_web_permission->urlPermissionCmd($this->router->class,"index",$this->config->item('url_suffix'),$dataSession,$dataSession["menuTop"],$dataSession["menuLeft"],$dataSession["menuBodyReport"],$dataSession["menuBodyTop"],$dataSession["menuHiddenPopup"]);
				if ($resultPermission 	== PERMISSION_NONE)
				throw new Exception(NOT_ACCESS_FUNCTION);			
			}	
			
			$uri				= $this->uri->uri_to_assoc(3);						
			$viewReport			= false;
			$documentNumber		= false;
			$companyID			= $dataSession["user"]->companyID;
			$branchID			= $dataSession["user"]->branchID;
			$userID				= $dataSession["user"]->userID;
			$tocken				= '';
			if($uri != false){
				
				$viewReport			= $uri["viewReport"];	
				$documentNumber		= $uri["documentNumber"];			
				$view_name			= array_key_exists("viewname",$uri) ? $uri["viewname"] : false;	
			} 
			
			//Cargar Libreria
			$this->load->model('core/Company_Model');	
			$this->load->model('core/Bd_Model');
				
			if(!($viewReport && $documentNumber)){
				//Renderizar Resultado 
				$dataSession["message"]		= $this->core_web_notification->get_message();
				$dataSession["head"]		= $this->load->view('app_cxc_report/document_contract/view_head','',true);
				$dataSession["body"]		= $this->load->view('app_cxc_report/document_contract/view_body','',true);
				$dataSession["script"]		= $this->load->view('app_cxc_report/document_contract/view_script','',true);  
				$dataSession["footer"]		= "";			
				$this->load->view("core_masterpage/default_report",$dataSession);		
			}
			else{				
				
				//Obtener el tipo de Comprobante
				$companyID 		= $dataSession["user"]->companyID;
				//Get Component
				$objComponent	= $this->core_web_tools->getComponentIDBy_ComponentName("tb_company");
				//Get Logo
				$objParameter			= $this->core_web_parameter->getParameter("CORE_COMPANY_LOGO",$companyID);
				$objPropietaryID		= $this->core_web_parameter->getParameter("CORE_PROPIETARY_ID",$companyID);
				$objPropietaryName		= $this->core_web_parameter->getParameter("CORE_PROPIETARY_NAME",$companyID);
				$objPropietaryAddress	= $this->core_web_parameter->getParameter("CORE_PROPIETARY_ADDRESS",$companyID);
				$objPropietaryTitle		= $this->core_web_parameter->getParameter("CORE_PROPIETARY_TITLE",$companyID);
				//Get Company
				$objCompany 	= $this->Company_Model->get_rowByPK($companyID);
				//Get Datos
				$query			= "CALL pr_cxc_get_report_document_contract('".$companyID."','".$tocken."','".$userID."','".$documentNumber."');";
				$objData		= $this->Bd_Model->executeProcedureMultiQuery($query);			
				
				if(isset($objData[0])){
					$objDataResult["objFirstDetail"]			= $objData[0][0];
				}
				else{
					$objDataResult["objFirstDetail"]			= NULL;
				}
				$objDataResult["objCompany"] 				= $objCompany;
				$objDataResult["objLogo"] 					= $objParameter;
				$objDataResult["objPropietaryID"]			= $objPropietaryID;
				$objDataResult["objPropietaryName"]			= $objPropietaryName;
				$objDataResult["objPropietaryAddress"]		= $objPropietaryAddress;
				$objDataResult["objPropietaryTitle"] 		= $objPropietaryTitle;
				$objDataResult["objFirma"] 					= "{companyID:" . $dataSession["user"]->companyID . ",branchID:" . $dataSession["user"]->branchID . ",userID:" . $dataSession["user"]->userID . ",fechaID:" . date('Y-m-d H:i:s') . ",reportID:" . "pr_cxc_get_report_document_credit" . ",ip:". $dataSession["ip_address"] . ",sessionID:" . $dataSession["session_id"] .",agenteID:". $dataSession["user_agent"] .",lastActivity:".  $dataSession["last_activity"] . "}"  ;
				$objDataResult["objFirmaEncription"] 		= md5 ($objDataResult["objFirma"]);
				log_message("INFO",print_r($objDataResult,true));
				
				
				if($objDataResult["objFirstDetail"] != NULL){
					if($view_name == "view_a_disemp_provider")
						$this->load->view("app_cxc_report/document_contract/view_a_disemp_provider",$objDataResult);
					else if($view_name == "view_a_disemp_protocolo")
						$this->load->view("app_cxc_report/document_contract/view_a_disemp_protocolo",$objDataResult);
					else if($view_name == "view_a_disemp_testimonio")
						$this->load->view("app_cxc_report/document_contract/view_a_disemp_testimonio",$objDataResult);
					else if($view_name == "view_a_disemp_producto")
						$this->load->view("app_cxc_report/document_contract/view_a_disemp_producto",$objDataResult);
					else
						$this->load->view("app_cxc_report/document_contract/view_a_disemp",$objDataResult);
				}
				else{
					show_error("NO ACCESO..." ,500 ); 
				}
				
			}
		}
		catch(Exception $ex){
			show_error($ex->getLine()." ".$ex->getMessage() ,500 );
		}
	}
	function document_credit(){
		try{ 
		
			//AUTENTICADO
			if(!$this->core_web_authentication->isAuthenticated())
			throw new Exception(USER_NOT_AUTENTICATED);
			$dataSession		= $this->session->all_userdata();
		
			//PERMISOS SOBRE LAS FUNCIONES
			if(APP_NEED_AUTHENTICATION == true){				
				
				$permited = false;
				$permited = $this->core_web_permission->urlPermited($this->router->class,"index",$this->config->item('url_suffix'),$dataSession["menuTop"],$dataSession["menuLeft"],$dataSession["menuBodyReport"],$dataSession["menuBodyTop"],$dataSession["menuHiddenPopup"]);
				
				if(!$permited)
				throw new Exception(NOT_ACCESS_CONTROL);
				
				$resultPermission		= $this->core_web_permission->urlPermissionCmd($this->router->class,"index",$this->config->item('url_suffix'),$dataSession,$dataSession["menuTop"],$dataSession["menuLeft"],$dataSession["menuBodyReport"],$dataSession["menuBodyTop"],$dataSession["menuHiddenPopup"]);
				if ($resultPermission 	== PERMISSION_NONE)
				throw new Exception(NOT_ACCESS_FUNCTION);			
			}	
			
			$uri				= $this->uri->uri_to_assoc(3);						
			$viewReport			= false;
			$documentNumber		= false;
			$companyID			= $dataSession["user"]->companyID;
			$branchID			= $dataSession["user"]->branchID;
			$userID				= $dataSession["user"]->userID;
			$tocken				= '';
			if($uri != false){
				$viewReport			= $uri["viewReport"];	
				$documentNumber		= $uri["documentNumber"];		
				$view_name			= array_key_exists("viewname",$uri) ? true : false;	
			} 
			
			//Cargar Libreria
			$this->load->model('core/Company_Model');	
			$this->load->model('core/Bd_Model');
				
			if(!($viewReport && $documentNumber)){
				//Renderizar Resultado 
				$dataSession["message"]		= $this->core_web_notification->get_message();
				$dataSession["head"]		= $this->load->view('app_cxc_report/document_credit/view_head','',true);
				$dataSession["body"]		= $this->load->view('app_cxc_report/document_credit/view_body','',true);
				$dataSession["script"]		= $this->load->view('app_cxc_report/document_credit/view_script','',true);  
				$dataSession["footer"]		= "";			
				$this->load->view("core_masterpage/default_report",$dataSession);		
			}
			else{				
				
				//Obtener el tipo de Comprobante
				$companyID 		= $dataSession["user"]->companyID;
				//Get Component
				$objComponent	= $this->core_web_tools->getComponentIDBy_ComponentName("tb_company");
				//Get Logo
				$objParameter			= $this->core_web_parameter->getParameter("CORE_COMPANY_LOGO",$companyID);
				$objPropietaryName		= $this->core_web_parameter->getParameter("CORE_PROPIETARY_NAME",$companyID);
				//Get Company
				$objCompany 	= $this->Company_Model->get_rowByPK($companyID);
				//Get Datos
				$query			= "CALL pr_cxc_get_report_document_credit('".$companyID."','".$tocken."','".$userID."','".$documentNumber."');";
				log_message('ERROR',"punto de interrupcion al momento de generar el reporte documento_credito");
				log_message('ERROR',$query);
				$objData		= $this->Bd_Model->executeProcedureMultiQuery($query);			
				
				if(isset($objData[0])){
					$objDataResult["objDetail"]					= $objData[0];
					$objDataResult["objFirstDetail"]			= $objData[0][0];
					$objDataResult["objFirstDetail"]["TotalPrincipal"]			= array_sum(array_column($objDataResult["objDetail"], 'capital'));   
					$objDataResult["objFirstDetail"]["TotalInteres"]			= array_sum(array_column($objDataResult["objDetail"], 'interest'));   
					$objDataResult["objFirstDetail"]["Total"]					= $objDataResult["objFirstDetail"]["TotalPrincipal"] + $objDataResult["objFirstDetail"]["TotalInteres"];
				}
				else{
					$objDataResult["objDetail"]					= NULL;
					$objDataResult["objFirstDetail"]			= NULL;
				}
				$objDataResult["objCompany"] 				= $objCompany;
				$objDataResult["objLogo"] 					= $objParameter;
				$objDataResult["objPropietaryName"] 		= $objPropietaryName;
				$objDataResult["objFirma"] 					= "{companyID:" . $dataSession["user"]->companyID . ",branchID:" . $dataSession["user"]->branchID . ",userID:" . $dataSession["user"]->userID . ",fechaID:" . date('Y-m-d H:i:s') . ",reportID:" . "pr_cxc_get_report_document_credit" . ",ip:". $dataSession["ip_address"] . ",sessionID:" . $dataSession["session_id"] .",agenteID:". $dataSession["user_agent"] .",lastActivity:".  $dataSession["last_activity"] . "}"  ;
				$objDataResult["objFirmaEncription"] 		= md5 ($objDataResult["objFirma"]);
				log_message("INFO",print_r($objDataResult,true));
				
				if($view_name)
				$this->load->view("app_cxc_report/document_credit/view_a_disemp_fidlocal",$objDataResult);
				else
				$this->load->view("app_cxc_report/document_credit/view_a_disemp",$objDataResult);
				
			}
		}
		catch(Exception $ex){
			show_error($ex->getLine()." ".$ex->getMessage() ,500 );
		}
	}
	function customer_status(){
		try{ 
		
			//AUTENTICADO
			if(!$this->core_web_authentication->isAuthenticated())
			throw new Exception(USER_NOT_AUTENTICATED);
			$dataSession		= $this->session->all_userdata();
		
			//PERMISOS SOBRE LAS FUNCIONES
			if(APP_NEED_AUTHENTICATION == true){				
				
				$permited = false;
				$permited = $this->core_web_permission->urlPermited($this->router->class,"index",$this->config->item('url_suffix'),$dataSession["menuTop"],$dataSession["menuLeft"],$dataSession["menuBodyReport"],$dataSession["menuBodyTop"],$dataSession["menuHiddenPopup"]);
				
				if(!$permited)
				throw new Exception(NOT_ACCESS_CONTROL);
				
				$resultPermission		= $this->core_web_permission->urlPermissionCmd($this->router->class,"index",$this->config->item('url_suffix'),$dataSession,$dataSession["menuTop"],$dataSession["menuLeft"],$dataSession["menuBodyReport"],$dataSession["menuBodyTop"],$dataSession["menuHiddenPopup"]);
				if ($resultPermission 	== PERMISSION_NONE)
				throw new Exception(NOT_ACCESS_FUNCTION);			
			}	
			
			$uri				= $this->uri->uri_to_assoc(3);						
			$viewReport			= false;
			$customerNumber		= false;
			$companyID			= $dataSession["user"]->companyID;
			$branchID			= $dataSession["user"]->branchID;
			$userID				= $dataSession["user"]->userID;
			$tocken				= '';
			if($uri != false){
				$viewReport			= $uri["viewReport"];	
				$customerNumber		= $uri["customerNumber"];				
			} 
			
			//Cargar Libreria
			$this->load->model('core/Company_Model');	
			$this->load->model('core/Bd_Model');
			$this->load->model("Customer_Model");	
				
				
			if(!($viewReport && $customerNumber )){
				
				//Renderizar Resultado 
				$data["objListCustomer"]	= $this->Customer_Model->get_rowByCompany($companyID);
				$dataSession["message"]		= $this->core_web_notification->get_message();
				$dataSession["head"]		= $this->load->view('app_cxc_report/customer_status/view_head','',true);
				$dataSession["body"]		= $this->load->view('app_cxc_report/customer_status/view_body',$data,true);
				$dataSession["script"]		= $this->load->view('app_cxc_report/customer_status/view_script','',true);  
				$dataSession["footer"]		= "";			
				$this->load->view("core_masterpage/default_report",$dataSession);		
			}
			else{				
				
				//Obtener el tipo de Comprobante
				$companyID 		= $dataSession["user"]->companyID;
				//Get Component
				$objComponent	= $this->core_web_tools->getComponentIDBy_ComponentName("tb_company");
				//Get Logo
				$objParameter	= $this->core_web_parameter->getParameter("CORE_COMPANY_LOGO",$companyID);
				//Get Company
				$objCompany 	= $this->Company_Model->get_rowByPK($companyID);
				//Get Datos
				$query			= "CALL pr_cxc_get_report_customer_status('".$companyID."','".$tocken."','".$userID."','".$customerNumber."');";
				$objData		= $this->Bd_Model->executeProcedureMultiQuery($query);			
				
				
				if(isset($objData[0])){
					$objDataResult["objClient"]				= $objData[0][0];
					$objDataResult["objLine"]				= $objData[1];
					$objDataResult["objDocument"]			= $objData[2];
					$objDataResult["objAmortization"]		= $objData[3];
				}
				else{
					$objDataResult["objClient"]				= NULL;
					$objDataResult["objLine"]				= NULL;
					$objDataResult["objDocument"]			= NULL;
					$objDataResult["objAmortization"]		= NULL;
				}
				$objDataResult["objCompany"] 				= $objCompany;
				$objDataResult["objLogo"] 					= $objParameter;
				$objDataResult["objFirma"] 					= "{companyID:" . $dataSession["user"]->companyID . ",branchID:" . $dataSession["user"]->branchID . ",userID:" . $dataSession["user"]->userID . ",fechaID:" . date('Y-m-d H:i:s') . ",reportID:" . "pr_cxc_get_report_customer_status" . ",ip:". $dataSession["ip_address"] . ",sessionID:" . $dataSession["session_id"] .",agenteID:". $dataSession["user_agent"] .",lastActivity:".  $dataSession["last_activity"] . "}"  ;
				$objDataResult["objFirmaEncription"] 		= md5 ($objDataResult["objFirma"]);
				log_message("INFO",print_r($objDataResult,true));
				$this->load->view("app_cxc_report/customer_status/view_a_disemp",$objDataResult);
				
			}
		}
		catch(Exception $ex){
			show_error($ex->getLine()." ".$ex->getMessage() ,500 );
		}
	}
	function customer_credit(){
		try{ 
		
			//AUTENTICADO
			if(!$this->core_web_authentication->isAuthenticated())
			throw new Exception(USER_NOT_AUTENTICATED);
			$dataSession		= $this->session->all_userdata();
		
			//PERMISOS SOBRE LAS FUNCIONES
			if(APP_NEED_AUTHENTICATION == true){				
				
				$permited = false;
				$permited = $this->core_web_permission->urlPermited($this->router->class,"index",$this->config->item('url_suffix'),$dataSession["menuTop"],$dataSession["menuLeft"],$dataSession["menuBodyReport"],$dataSession["menuBodyTop"],$dataSession["menuHiddenPopup"]);
				
				if(!$permited)
				throw new Exception(NOT_ACCESS_CONTROL);
				
				$resultPermission		= $this->core_web_permission->urlPermissionCmd($this->router->class,"index",$this->config->item('url_suffix'),$dataSession,$dataSession["menuTop"],$dataSession["menuLeft"],$dataSession["menuBodyReport"],$dataSession["menuBodyTop"],$dataSession["menuHiddenPopup"]);
				if ($resultPermission 	== PERMISSION_NONE)
				throw new Exception(NOT_ACCESS_FUNCTION);			
			}	
			
			$uri				= $this->uri->uri_to_assoc(3);						
			$viewReport			= false;
			$companyID			= $dataSession["user"]->companyID;
			$branchID			= $dataSession["user"]->branchID;
			$userID				= $dataSession["user"]->userID;
			$tocken				= '';
			 
			
			//Cargar Libreria
			$this->load->model('core/Company_Model');	
			$this->load->model('core/Bd_Model');
				
				
			//Obtener el tipo de Comprobante
			$companyID 		= $dataSession["user"]->companyID;
			//Get Component
			$objComponent	= $this->core_web_tools->getComponentIDBy_ComponentName("tb_company");
			//Get Logo
			$objParameter	= $this->core_web_parameter->getParameter("CORE_COMPANY_LOGO",$companyID);
			//Get Company
			$objCompany 	= $this->Company_Model->get_rowByPK($companyID);
			//Get Datos
			$query			= "CALL pr_cxc_get_report_customer_credit('".$userID."','".$tocken."','".$companyID."');";
			$objData		= $this->Bd_Model->executeProcedureMultiQuery($query);			
			
			if(isset($objData[0]))
			$objDataResult["objDetail"]					= $objData[0];
			else
			$objDataResult["objDetail"]					= NULL;
			$objDataResult["objCompany"] 				= $objCompany;
			$objDataResult["objLogo"] 					= $objParameter;
			$objDataResult["objFirma"] 					= "{companyID:" . $dataSession["user"]->companyID . ",branchID:" . $dataSession["user"]->branchID . ",userID:" . $dataSession["user"]->userID . ",fechaID:" . date('Y-m-d H:i:s') . ",reportID:" . "pr_cxc_get_report_customer_credit" . ",ip:". $dataSession["ip_address"] . ",sessionID:" . $dataSession["session_id"] .",agenteID:". $dataSession["user_agent"] .",lastActivity:".  $dataSession["last_activity"] . "}"  ;
			$objDataResult["objFirmaEncription"] 		= md5 ($objDataResult["objFirma"]);
			log_message("INFO",print_r($objDataResult,true));
			$this->load->view("app_cxc_report/customer_credit/view_a_disemp",$objDataResult);
			
		}
		catch(Exception $ex){
			show_error($ex->getLine()." ".$ex->getMessage() ,500 );
		}
	}
	function consulta_sin_riesgo_list(){
	    try{ 
		
		    
			//AUTENTICADO
			if(!$this->core_web_authentication->isAuthenticated())
			throw new Exception(USER_NOT_AUTENTICATED);
			$dataSession		= $this->session->all_userdata();
		
		    
			//PERMISOS SOBRE LAS FUNCIONES
			if(APP_NEED_AUTHENTICATION == true){				
				
				$permited = false;
				$permited = $this->core_web_permission->urlPermited($this->router->class,"index",$this->config->item('url_suffix'),$dataSession["menuTop"],$dataSession["menuLeft"],$dataSession["menuBodyReport"],$dataSession["menuBodyTop"],$dataSession["menuHiddenPopup"]);
				
				if(!$permited)
				throw new Exception(NOT_ACCESS_CONTROL);
				
				$resultPermission		= $this->core_web_permission->urlPermissionCmd($this->router->class,"index",$this->config->item('url_suffix'),$dataSession,$dataSession["menuTop"],$dataSession["menuLeft"],$dataSession["menuBodyReport"],$dataSession["menuBodyTop"],$dataSession["menuHiddenPopup"]);
				if ($resultPermission 	== PERMISSION_NONE)
				throw new Exception(NOT_ACCESS_FUNCTION);			
			}	
			
			
				
			$uri				= $this->uri->uri_to_assoc(3);						
			$viewReport			= false;
			$startOn			= false;
			$endOn				= false;
			$companyID			= $dataSession["user"]->companyID;
			$branchID			= $dataSession["user"]->branchID;
			$userID				= $dataSession["user"]->userID;
			$tocken				= '';
			if($uri != false){
				$viewReport			= $uri["viewReport"];	
				$startOn			= $uri["startOn"];
				$endOn				= $uri["endOn"];	
			}
			 
			
			
			//Cargar Libreria
			$this->load->model('core/Company_Model');	
			$this->load->model('core/Bd_Model');
				
			
			if(!($viewReport && $startOn && $endOn )){
				
				//Renderizar Resultado 
				$dataSession["message"]		= $this->core_web_notification->get_message();
				$dataSession["head"]		= $this->load->view('app_cxc_report/consulta_sin_riesgo_list/view_head','',true);
				$dataSession["body"]		= $this->load->view('app_cxc_report/consulta_sin_riesgo_list/view_body','',true);
				$dataSession["script"]		= $this->load->view('app_cxc_report/consulta_sin_riesgo_list/view_script','',true);  
				$dataSession["footer"]		= "";			
				$this->load->view("core_masterpage/default_report",$dataSession);		
			}
			else{						
				//Obtener el tipo de Comprobante
				$companyID 		= $dataSession["user"]->companyID;
				//Get Component
				$objComponent	= $this->core_web_tools->getComponentIDBy_ComponentName("tb_company");
				//Get Logo
				$objParameter	= $this->core_web_parameter->getParameter("CORE_COMPANY_LOGO",$companyID);
				//Get Company
				$objCompany 	= $this->Company_Model->get_rowByPK($companyID);
				//Get Datos
				$query			= "CALL pr_cxc_get_report_customer_sr_list('".$userID."','".$tocken."','".$companyID."','".$startOn."','".$endOn."');";
				$objData		= $this->Bd_Model->executeProcedureMultiQuery($query);			
				
				if(isset($objData[0]))
				$objDataResult["objDetail"]					= $objData[0];
				else
				$objDataResult["objDetail"]					= NULL;
				$objDataResult["objCompany"] 				= $objCompany;
				$objDataResult["objLogo"] 					= $objParameter;
				$objDataResult["startOn"] 					= $startOn;
				$objDataResult["endOn"] 					= $endOn;
				$objDataResult["objFirma"] 					= "{companyID:" . $dataSession["user"]->companyID . ",branchID:" . $dataSession["user"]->branchID . ",userID:" . $dataSession["user"]->userID . ",fechaID:" . date('Y-m-d H:i:s') . ",reportID:" . "pr_cxc_get_report_customer_credit" . ",ip:". $dataSession["ip_address"] . ",sessionID:" . $dataSession["session_id"] .",agenteID:". $dataSession["user_agent"] .",lastActivity:".  $dataSession["last_activity"] . "}"  ;
				$objDataResult["objFirmaEncription"] 		= md5 ($objDataResult["objFirma"]);
				log_message("INFO",print_r($objDataResult,true));
				$this->load->view("app_cxc_report/consulta_sin_riesgo_list/view_a_disemp",$objDataResult);
			}
			
			
		}
		catch(Exception $ex){
			show_error($ex->getLine()." ".$ex->getMessage() ,500 );
		}
	}
	function LeerDatos($file){
		$fp 	= fopen($file, "rb");
		$datos 	= fread($fp, filesize($file));
		fclose($fp);
		return $datos;
	}
	function FillDatos($resultado){
		$datView		= null;
		$resultado 		= json_decode($resultado);
		$resultado		= $resultado->ObtenerRecordCrediticioResult;
		
		//Obtener Datos Generales
		$datView["Persona"] = $resultado->Persona;
		/*
		$resultado->Persona->NumeroDocumentoIdentidad;
		$resultado->Persona->NombreRazonSocial;
		*/
		
		if (!empty((array)$resultado->DatosContacto)) {
			$empty = "";
			
			
			//Obtener Datos de Direcciones
			if(!empty((array)$resultado->DatosContacto->DireccionesContacto))
			if(!empty((array)$resultado->DatosContacto->DireccionesContacto->DireccionContacto))
			{
			    $datView["Direcciones"]	= $resultado->DatosContacto->DireccionesContacto->DireccionContacto;
			    $datView["Direcciones"] = is_array($datView["Direcciones"]) ? $datView["Direcciones"] : array($datView["Direcciones"]);
			}
			
			/*
			$resultado->DatosContacto->DireccionesContacto->DireccionContacto[0]->Direccion;
			$resultado->DatosContacto->DireccionesContacto->DireccionContacto[0]->Departamento;
			$resultado->DatosContacto->DireccionesContacto->DireccionContacto[0]->Municipio;
			$resultado->DatosContacto->DireccionesContacto->DireccionContacto[0]->Referencia;
			*/
			
			//Obtener Datos de Telefonos
			if(!empty((array)$resultado->DatosContacto->TelefonosContacto))
			if(!empty((array)$resultado->DatosContacto->TelefonosContacto->TelefonoContacto)){
			    $datView["Telefonos"]	= $resultado->DatosContacto->TelefonosContacto->TelefonoContacto;
			    $datView["Telefonos"] = is_array($datView["Telefonos"]) ? $datView["Telefonos"] : array($datView["Telefonos"]);
			}
			/*
			$resultado->DatosContacto->TelefonosContacto->TelefonoContacto[0]->Telefono;
			$resultado->DatosContacto->TelefonosContacto->TelefonoContacto[0]->Referencia;
			*/
		}
		
		//Datos de Creditos Vigente
		if(isset($resultado->CreditosVigentes))
		if (!empty((array)$resultado->CreditosVigentes)) 
		if (!empty((array)$resultado->CreditosVigentes->OperacionDeCredito)) {
		    $datView["CreditosVigentes"]	= $resultado->CreditosVigentes->OperacionDeCredito;		
		    $datView["CreditosVigentes"]    = is_array($datView["CreditosVigentes"]) ? $datView["CreditosVigentes"] : array($datView["CreditosVigentes"]);
		}
		/*
		$resultado->CreditosVigentes->OperacionDeCredito[0]->NumeroCredito;
		$resultado->CreditosVigentes->OperacionDeCredito[0]->Cuota;
		$resultado->CreditosVigentes->OperacionDeCredito[0]->FechaReporte;
		$resultado->CreditosVigentes->OperacionDeCredito[0]->Departamento;
		$resultado->CreditosVigentes->OperacionDeCredito[0]->TipoCredito;
		$resultado->CreditosVigentes->OperacionDeCredito[0]->FechaDesembolso;
		$resultado->CreditosVigentes->OperacionDeCredito[0]->MontoAutorizado;
		$resultado->CreditosVigentes->OperacionDeCredito[0]->PlazoMeses;
		$resultado->CreditosVigentes->OperacionDeCredito[0]->FormaDePago;
		$resultado->CreditosVigentes->OperacionDeCredito[0]->SaldoDeuda;
		$resultado->CreditosVigentes->OperacionDeCredito[0]->EstadoOP;
		$resultado->CreditosVigentes->OperacionDeCredito[0]->TipoObligacion;
		$resultado->CreditosVigentes->OperacionDeCredito[0]->MontoVencido;
		$resultado->CreditosVigentes->OperacionDeCredito[0]->AntiguedadMoraEnDias;
		$resultado->CreditosVigentes->OperacionDeCredito[0]->TipoGarantia;
		$resultado->CreditosVigentes->OperacionDeCredito[0]->FormaRecuperacion;
		$resultado->CreditosVigentes->OperacionDeCredito[0]->Entidad;
		$resultado->CreditosVigentes->OperacionDeCredito[0]->HistorialMora->HistoriaMora[0]->FechaReporte;
		$resultado->CreditosVigentes->OperacionDeCredito[0]->HistorialMora->HistoriaMora[0]->AntiguedadMoraEnDias;
		*/
		
		//Datos de Creditos Cancelados	
		if(isset($resultado->CreditosCancelados))
		if (!empty((array)$resultado->CreditosCancelados)) 
		if (!empty((array)$resultado->CreditosCancelados->OperacionDeCredito)) {
		    $datView["CreditosCancelados"]	= $resultado->CreditosCancelados->OperacionDeCredito;
		    $datView["CreditosCancelados"]  = is_array($datView["CreditosCancelados"]) ? $datView["CreditosCancelados"] : array($datView["CreditosCancelados"]);
		}
		/*
		$resultado->CreditosCancelados->OperacionDeCredito[0]->NumeroCredito;
		$resultado->CreditosCancelados->OperacionDeCredito[0]->Cuota;
		$resultado->CreditosCancelados->OperacionDeCredito[0]->FechaReporte;
		$resultado->CreditosCancelados->OperacionDeCredito[0]->Departamento;
		$resultado->CreditosCancelados->OperacionDeCredito[0]->TipoCredito;
		$resultado->CreditosCancelados->OperacionDeCredito[0]->FechaDesembolso;
		$resultado->CreditosCancelados->OperacionDeCredito[0]->MontoAutorizado;
		$resultado->CreditosCancelados->OperacionDeCredito[0]->PlazoMeses;
		$resultado->CreditosCancelados->OperacionDeCredito[0]->FormaDePago; 
		$resultado->CreditosCancelados->OperacionDeCredito[0]->SaldoDeuda;
		$resultado->CreditosCancelados->OperacionDeCredito[0]->EstadoOP;
		$resultado->CreditosCancelados->OperacionDeCredito[0]->TipoObligacion;
		$resultado->CreditosCancelados->OperacionDeCredito[0]->MontoVencido;
		$resultado->CreditosCancelados->OperacionDeCredito[0]->AntiguedadMoraEnDias;
		$resultado->CreditosCancelados->OperacionDeCredito[0]->TipoGarantia;
		$resultado->CreditosCancelados->OperacionDeCredito[0]->FormaRecuperacion;
		$resultado->CreditosCancelados->OperacionDeCredito[0]->Entidad;
		$resultado->CreditosCancelados->OperacionDeCredito[0]->HistorialMora->HistoriaMora[0]->FechaReporte;
		$resultado->CreditosCancelados->OperacionDeCredito[0]->HistorialMora->HistoriaMora[0]->AntiguedadMoraEnDias;
		*/
		
		//Tarjetas de Credito
		if(isset($resultado->TarjetasDeCredito))
		if(isset($resultado->TarjetasDeCredito))
		if (!empty((array)$resultado->TarjetasDeCredito->TarjetaDeCredito)) {
		    $datView["TarjetasDeCredito"]	= $resultado->TarjetasDeCredito->TarjetaDeCredito;
		    $datView["TarjetasDeCredito"]  = is_array($datView["TarjetasDeCredito"]) ? $datView["TarjetasDeCredito"] : array($datView["TarjetasDeCredito"]);
		}
		/*
		$resultado->TarjetasDeCredito->TarjetaDeCredito[0]->NumeroTarjeta;
		$resultado->TarjetasDeCredito->TarjetaDeCredito[0]->Entidad;
		$resultado->TarjetasDeCredito->TarjetaDeCredito[0]->FechaReporte;
		$resultado->TarjetasDeCredito->TarjetaDeCredito[0]->FechaEmision;
		$resultado->TarjetasDeCredito->TarjetaDeCredito[0]->LimiteCredito;
		$resultado->TarjetasDeCredito->TarjetaDeCredito[0]->SaldoDeuda;
		$resultado->TarjetasDeCredito->TarjetaDeCredito[0]->CreditoDisponible;
		$resultado->TarjetasDeCredito->TarjetaDeCredito[0]->TipoTarjeta;
		$resultado->TarjetasDeCredito->TarjetaDeCredito[0]->TipoObligacion;
		$resultado->TarjetasDeCredito->TarjetaDeCredito[0]->MontoVencido;
		$resultado->TarjetasDeCredito->TarjetaDeCredito[0]->MoraEnDias;
		$resultado->TarjetasDeCredito->TarjetaDeCredito[0]->FormaRecuperacion;
		$resultado->TarjetasDeCredito->TarjetaDeCredito[0]->FechaDesembolso;
		$resultado->TarjetasDeCredito->TarjetaDeCredito[0]->MontoAutorizado;
		$resultado->TarjetasDeCredito->TarjetaDeCredito[0]->PlazoMeses;
		$resultado->TarjetasDeCredito->TarjetaDeCredito[0]->SaldoDeudaExtraFinanciamiento;
		*/
		
		//Historia de Consulta
		if (!empty((array)$resultado->Consultas)) 
		if (!empty((array)$resultado->Consultas->HistoriaConsulta)){ 
		    $datView["Consultas"]	= $resultado->Consultas->HistoriaConsulta;
		    $datView["Consultas"]   = is_array($datView["Consultas"]) ? $datView["Consultas"] : array($datView["Consultas"]);
		}
	
	
		/*
		$resultado->Consultas->HistoriaConsulta[0]->Entidad;
		$resultado->Consultas->HistoriaConsulta[0]->FechaConsulta;
		$resultado->Consultas->HistoriaConsulta[0]->Cantidad;
		*/	
		return $datView;
	}
	function consulta_sin_riesgo(){
	    try{ 
		
		    
			//AUTENTICADO
			if(!$this->core_web_authentication->isAuthenticated())
			throw new Exception(USER_NOT_AUTENTICATED);
			$dataSession		= $this->session->all_userdata();
		
		    
			//PERMISOS SOBRE LAS FUNCIONES
			if(APP_NEED_AUTHENTICATION == true){				
				
				$permited = false;
				$permited = $this->core_web_permission->urlPermited($this->router->class,"index",$this->config->item('url_suffix'),$dataSession["menuTop"],$dataSession["menuLeft"],$dataSession["menuBodyReport"],$dataSession["menuBodyTop"],$dataSession["menuHiddenPopup"]);
				
				if(!$permited)
				throw new Exception(NOT_ACCESS_CONTROL);
				
				$resultPermission		= $this->core_web_permission->urlPermissionCmd($this->router->class,"index",$this->config->item('url_suffix'),$dataSession,$dataSession["menuTop"],$dataSession["menuLeft"],$dataSession["menuBodyReport"],$dataSession["menuBodyTop"],$dataSession["menuHiddenPopup"]);
				if ($resultPermission 	== PERMISSION_NONE)
				throw new Exception(NOT_ACCESS_FUNCTION);			
			}	
			
			
				
			$uri				= $this->uri->uri_to_assoc(3);						
			$viewReport			= false;
			$fileName			= false;
			$companyID			= $dataSession["user"]->companyID;
			$branchID			= $dataSession["user"]->branchID;
			$userID				= $dataSession["user"]->userID;
			$tocken				= '';
			if($uri != false){
				$viewReport			= $uri["viewReport"];	
				$fileName			= $uri["fileName"];
			}
			 
			$objComponentConsulta	= $this->core_web_tools->getComponentIDBy_ComponentName("tb_customer_consultas_sin_riesgo");
			if(!$objComponentConsulta)
			throw new Exception("00409 EL COMPONENTE 'tb_customer_consultas_sin_riesgo' NO EXISTE...");
			
			//Cargar Libreria
			$this->load->model('core/Company_Model');	
			$this->load->model('core/Bd_Model');
				
			
			if(!($viewReport && $fileName )){
				
				//Renderizar Resultado 
				$dataSession["message"]		= $this->core_web_notification->get_message();
				$dataSession["head"]		= $this->load->view('app_cxc_report/consulta_sin_riesgo/view_head','',true);
				$dataSession["body"]		= $this->load->view('app_cxc_report/consulta_sin_riesgo/view_body','',true);
				$dataSession["script"]		= $this->load->view('app_cxc_report/consulta_sin_riesgo/view_script','',true);  
				$dataSession["footer"]		= "";			
				$this->load->view("core_masterpage/default_report",$dataSession);		
			}
			else{						
				//Obtener el tipo de Comprobante
				$companyID 		= $dataSession["user"]->companyID;
				//Get Component
				$objComponent	= $this->core_web_tools->getComponentIDBy_ComponentName("tb_company");
				//Get Logo
				$objParameter	= $this->core_web_parameter->getParameter("CORE_COMPANY_LOGO",$companyID);
				//Get Company
				$objCompany 	= $this->Company_Model->get_rowByPK($companyID);
				//Leer Datos del Archivo
				$fileName                                       = PATH_FILE_OF_APP."/company_".$companyID."/component_".$objComponentConsulta->componentID."/".$fileName;
				$datos 											= $this->LeerDatos($fileName);	
				$datView										= $this->FillDatos($datos);
				$objDataResult["objDetail"]					    = $datView;
				$objDataResult["objCompany"] 				    = $objCompany;
				$objDataResult["objLogo"] 					    = $objParameter;
				$objDataResult["fileName"] 					    = $fileName;
				$objDataResult["objFirma"] 					    = "{companyID:" . $dataSession["user"]->companyID . ",branchID:" . $dataSession["user"]->branchID . ",userID:" . $dataSession["user"]->userID . ",fechaID:" . date('Y-m-d H:i:s') . ",reportID:" . "pr_cxc_get_report_customer_credit" . ",ip:". $dataSession["ip_address"] . ",sessionID:" . $dataSession["session_id"] .",agenteID:". $dataSession["user_agent"] .",lastActivity:".  $dataSession["last_activity"] . "}"  ;
				$objDataResult["objFirmaEncription"] 		    = md5 ($objDataResult["objFirma"]);
				log_message("INFO",print_r($objDataResult,true));
				$this->load->view("app_cxc_report/consulta_sin_riesgo/view_a_disemp",$objDataResult);
			}
			
			
		}
		catch(Exception $ex){
			show_error($ex->getLine()." ".$ex->getMessage() ,500 );
		}
	}
	function customer_list(){
		try{ 
		
			//AUTENTICADO
			if(!$this->core_web_authentication->isAuthenticated())
			throw new Exception(USER_NOT_AUTENTICATED);
			$dataSession		= $this->session->all_userdata();
		
			//PERMISOS SOBRE LAS FUNCIONES
			if(APP_NEED_AUTHENTICATION == true){				
				
				$permited = false;
				$permited = $this->core_web_permission->urlPermited($this->router->class,"index",$this->config->item('url_suffix'),$dataSession["menuTop"],$dataSession["menuLeft"],$dataSession["menuBodyReport"],$dataSession["menuBodyTop"],$dataSession["menuHiddenPopup"]);
				
				if(!$permited)
				throw new Exception(NOT_ACCESS_CONTROL);
				
				$resultPermission		= $this->core_web_permission->urlPermissionCmd($this->router->class,"index",$this->config->item('url_suffix'),$dataSession,$dataSession["menuTop"],$dataSession["menuLeft"],$dataSession["menuBodyReport"],$dataSession["menuBodyTop"],$dataSession["menuHiddenPopup"]);
				if ($resultPermission 	== PERMISSION_NONE)
				throw new Exception(NOT_ACCESS_FUNCTION);			
			}	
			
			$uri				= $this->uri->uri_to_assoc(3);						
			$viewReport			= false;
			$companyID			= $dataSession["user"]->companyID;
			$branchID			= $dataSession["user"]->branchID;
			$userID				= $dataSession["user"]->userID;
			$tocken				= '';
			 
			
			//Cargar Libreria
			$this->load->model('core/Company_Model');	
			$this->load->model('core/Bd_Model');
				
				
			//Obtener el tipo de Comprobante
			$companyID 		= $dataSession["user"]->companyID;
			//Get Component
			$objComponent	= $this->core_web_tools->getComponentIDBy_ComponentName("tb_company");
			//Get Logo
			$objParameter	= $this->core_web_parameter->getParameter("CORE_COMPANY_LOGO",$companyID);
			//Get Company
			$objCompany 	= $this->Company_Model->get_rowByPK($companyID);
			//Get Datos
			$query			= "CALL pr_cxc_get_report_customer_list('".$userID."','".$tocken."','".$companyID."');";
			log_message("ERROR",print_r($query,true));
			
			$objData		= $this->Bd_Model->executeProcedureMultiQuery($query);			
			
			if(isset($objData[0]))
			$objDataResult["objDetail"]					= $objData[0];
			else
			$objDataResult["objDetail"]					= NULL;
			$objDataResult["objCompany"] 				= $objCompany;
			$objDataResult["objLogo"] 					= $objParameter;
			$objDataResult["objFirma"] 					= "{companyID:" . $dataSession["user"]->companyID . ",branchID:" . $dataSession["user"]->branchID . ",userID:" . $dataSession["user"]->userID . ",fechaID:" . date('Y-m-d H:i:s') . ",reportID:" . "pr_cxc_get_report_customer_credit" . ",ip:". $dataSession["ip_address"] . ",sessionID:" . $dataSession["session_id"] .",agenteID:". $dataSession["user_agent"] .",lastActivity:".  $dataSession["last_activity"] . "}"  ;
			$objDataResult["objFirmaEncription"] 		= md5 ($objDataResult["objFirma"]);
			log_message("INFO",print_r($objDataResult,true));
			$this->load->view("app_cxc_report/customer_list/view_a_disemp",$objDataResult);
			
		}
		catch(Exception $ex){
			show_error($ex->getLine()." ".$ex->getMessage() ,500 );
		}
	}
	
	function interes_periodo(){
		try{ 
		
			//AUTENTICADO
			if(!$this->core_web_authentication->isAuthenticated())
			throw new Exception(USER_NOT_AUTENTICATED);
			$dataSession		= $this->session->all_userdata();
		
			//PERMISOS SOBRE LAS FUNCIONES
			if(APP_NEED_AUTHENTICATION == true){				
				
				$permited = false;
				$permited = $this->core_web_permission->urlPermited($this->router->class,"index",$this->config->item('url_suffix'),$dataSession["menuTop"],$dataSession["menuLeft"],$dataSession["menuBodyReport"],$dataSession["menuBodyTop"],$dataSession["menuHiddenPopup"]);
				
				if(!$permited)
				throw new Exception(NOT_ACCESS_CONTROL);
				
				$resultPermission		= $this->core_web_permission->urlPermissionCmd($this->router->class,"index",$this->config->item('url_suffix'),$dataSession,$dataSession["menuTop"],$dataSession["menuLeft"],$dataSession["menuBodyReport"],$dataSession["menuBodyTop"],$dataSession["menuHiddenPopup"]);
				if ($resultPermission 	== PERMISSION_NONE)
				throw new Exception(NOT_ACCESS_FUNCTION);			
			}	
			
				
			$uri				= $this->uri->uri_to_assoc(3);						
			$viewReport			= false;
			$startOn			= false;
			$endOn				= false;
			$companyID			= $dataSession["user"]->companyID;
			$branchID			= $dataSession["user"]->branchID;
			$userID				= $dataSession["user"]->userID;
			$tocken				= '';
			if($uri != false){
				$viewReport			= $uri["viewReport"];	
				$startOn			= $uri["startOn"];
				$endOn				= $uri["endOn"];	
			} 
			
			 
			
			//Cargar Libreria
			$this->load->model('core/Company_Model');	
			$this->load->model('core/Bd_Model');
				
			
			if(!($viewReport && $startOn && $endOn )){
				
				//Renderizar Resultado 
				$dataSession["message"]		= $this->core_web_notification->get_message();
				$dataSession["head"]		= $this->load->view('app_cxc_report/interes_periodo/view_head','',true);
				$dataSession["body"]		= $this->load->view('app_cxc_report/interes_periodo/view_body','',true);
				$dataSession["script"]		= $this->load->view('app_cxc_report/interes_periodo/view_script','',true);  
				$dataSession["footer"]		= "";			
				$this->load->view("core_masterpage/default_report",$dataSession);		
			}
			else{						
				//Obtener el tipo de Comprobante
				$companyID 		= $dataSession["user"]->companyID;
				//Get Component
				$objComponent	= $this->core_web_tools->getComponentIDBy_ComponentName("tb_company");
				//Get Logo
				$objParameter	= $this->core_web_parameter->getParameter("CORE_COMPANY_LOGO",$companyID);
				//Get Company
				$objCompany 	= $this->Company_Model->get_rowByPK($companyID);
				//Get Datos
				$query			= "CALL pr_cxc_get_report_interes_periodo('".$userID."','".$tocken."','".$companyID."','".$startOn."','".$endOn."');";
				$objData		= $this->Bd_Model->executeProcedureMultiQuery($query);			
				
				if(isset($objData[0]))
				$objDataResult["objDetail"]					= $objData[0];
				else
				$objDataResult["objDetail"]					= NULL;
				$objDataResult["objCompany"] 				= $objCompany;
				$objDataResult["objLogo"] 					= $objParameter;
				$objDataResult["startOn"] 					= $startOn;
				$objDataResult["endOn"] 					= $endOn;
				$objDataResult["objFirma"] 					= "{companyID:" . $dataSession["user"]->companyID . ",branchID:" . $dataSession["user"]->branchID . ",userID:" . $dataSession["user"]->userID . ",fechaID:" . date('Y-m-d H:i:s') . ",reportID:" . "pr_cxc_get_report_customer_credit" . ",ip:". $dataSession["ip_address"] . ",sessionID:" . $dataSession["session_id"] .",agenteID:". $dataSession["user_agent"] .",lastActivity:".  $dataSession["last_activity"] . "}"  ;
				$objDataResult["objFirmaEncription"] 		= md5 ($objDataResult["objFirma"]);
				log_message("INFO",print_r($objDataResult,true));
				$this->load->view("app_cxc_report/interes_periodo/view_a_disemp",$objDataResult);
			}
		}
		catch(Exception $ex){
			show_error($ex->getLine()." ".$ex->getMessage() ,500 );
		}
	}
	function proyection(){
		try{ 
		
			//AUTENTICADO
			if(!$this->core_web_authentication->isAuthenticated())
			throw new Exception(USER_NOT_AUTENTICATED);
			$dataSession		= $this->session->all_userdata();
		
			//PERMISOS SOBRE LAS FUNCIONES
			if(APP_NEED_AUTHENTICATION == true){				
				
				$permited = false;
				$permited = $this->core_web_permission->urlPermited($this->router->class,"index",$this->config->item('url_suffix'),$dataSession["menuTop"],$dataSession["menuLeft"],$dataSession["menuBodyReport"],$dataSession["menuBodyTop"],$dataSession["menuHiddenPopup"]);
				
				if(!$permited)
				throw new Exception(NOT_ACCESS_CONTROL);
				
				$resultPermission		= $this->core_web_permission->urlPermissionCmd($this->router->class,"index",$this->config->item('url_suffix'),$dataSession,$dataSession["menuTop"],$dataSession["menuLeft"],$dataSession["menuBodyReport"],$dataSession["menuBodyTop"],$dataSession["menuHiddenPopup"]);
				if ($resultPermission 	== PERMISSION_NONE)
				throw new Exception(NOT_ACCESS_FUNCTION);			
			}	
			
				
			$uri				= $this->uri->uri_to_assoc(3);						
			$viewReport			= false;
			$startOn			= false;
			$endOn				= false;
			$companyID			= $dataSession["user"]->companyID;
			$branchID			= $dataSession["user"]->branchID;
			$userID				= $dataSession["user"]->userID;
			$tocken				= '';
			if($uri != false){
				$viewReport			= $uri["viewReport"];
			} 
			
			 
			
			//Cargar Libreria
			$this->load->model('core/Company_Model');	
			$this->load->model('core/Bd_Model');
				
									
			//Obtener el tipo de Comprobante
			$companyID 		= $dataSession["user"]->companyID;
			//Get Component
			$objComponent	= $this->core_web_tools->getComponentIDBy_ComponentName("tb_company");
			//Get Logo
			$objParameter	= $this->core_web_parameter->getParameter("CORE_COMPANY_LOGO",$companyID);
			//Get Company
			$objCompany 	= $this->Company_Model->get_rowByPK($companyID);
			//Get Datos
			$query			= "CALL pr_cxc_get_report_info_proyect('".$userID."','".$tocken."','".$companyID."');";
			
			$objData		= $this->Bd_Model->executeProcedureMultiQuery($query);			
			
			if(isset($objData[0]))
			$objDataResult["objDetail"]					= $objData[0];
			else
			$objDataResult["objDetail"]					= NULL;
			$objDataResult["objCompany"] 				= $objCompany;
			$objDataResult["objLogo"] 					= $objParameter;
			$objDataResult["objFirma"] 					= "{companyID:" . $dataSession["user"]->companyID . ",branchID:" . $dataSession["user"]->branchID . ",userID:" . $dataSession["user"]->userID . ",fechaID:" . date('Y-m-d H:i:s') . ",reportID:" . "pr_cxc_get_report_customer_credit" . ",ip:". $dataSession["ip_address"] . ",sessionID:" . $dataSession["session_id"] .",agenteID:". $dataSession["user_agent"] .",lastActivity:".  $dataSession["last_activity"] . "}"  ;
			$objDataResult["objFirmaEncription"] 		= md5 ($objDataResult["objFirma"]);
			log_message("INFO",print_r($objDataResult,true));
			$this->load->view("app_cxc_report/proyection/view_a_disemp",$objDataResult);
		
		}
		catch(Exception $ex){
			show_error($ex->getLine()." ".$ex->getMessage() ,500 );
		}
	}	
	function pay(){
		try{ 
		
			//AUTENTICADO
			if(!$this->core_web_authentication->isAuthenticated())
			throw new Exception(USER_NOT_AUTENTICATED);
			$dataSession		= $this->session->all_userdata();
		
			//PERMISOS SOBRE LAS FUNCIONES
			if(APP_NEED_AUTHENTICATION == true){				
				
				$permited = false;
				$permited = $this->core_web_permission->urlPermited($this->router->class,"index",$this->config->item('url_suffix'),$dataSession["menuTop"],$dataSession["menuLeft"],$dataSession["menuBodyReport"],$dataSession["menuBodyTop"],$dataSession["menuHiddenPopup"]);
				
				if(!$permited)
				throw new Exception(NOT_ACCESS_CONTROL);
				
				$resultPermission		= $this->core_web_permission->urlPermissionCmd($this->router->class,"index",$this->config->item('url_suffix'),$dataSession,$dataSession["menuTop"],$dataSession["menuLeft"],$dataSession["menuBodyReport"],$dataSession["menuBodyTop"],$dataSession["menuHiddenPopup"]);
				if ($resultPermission 	== PERMISSION_NONE)
				throw new Exception(NOT_ACCESS_FUNCTION);			
			}	
			
			$uri				= $this->uri->uri_to_assoc(3);						
			$viewReport			= false;
			$customerNumber		= false;
			$companyID			= $dataSession["user"]->companyID;
			$branchID			= $dataSession["user"]->branchID;
			$userID				= $dataSession["user"]->userID;
			$tocken				= '';
			if($uri != false){
				$viewReport			= $uri["viewReport"];	
				$customerNumber		= $uri["customerNumber"];				
			} 
			
			//Cargar Libreria
			$this->load->model('core/Company_Model');	
			$this->load->model('core/Bd_Model');
				
				
			if(!($viewReport && $customerNumber )){
				
				//Renderizar Resultado 
				$dataSession["message"]		= $this->core_web_notification->get_message();
				$dataSession["head"]		= $this->load->view('app_cxc_report/pay/view_head','',true);
				$dataSession["body"]		= $this->load->view('app_cxc_report/pay/view_body','',true);
				$dataSession["script"]		= $this->load->view('app_cxc_report/pay/view_script','',true);  
				$dataSession["footer"]		= "";			
				$this->load->view("core_masterpage/default_report",$dataSession);		
			}
			else{				
				
				//Obtener el tipo de Comprobante
				$companyID 		= $dataSession["user"]->companyID;
				//Get Component
				$objComponent	= $this->core_web_tools->getComponentIDBy_ComponentName("tb_company");
				//Get Logo
				$objParameter	= $this->core_web_parameter->getParameter("CORE_COMPANY_LOGO",$companyID);
				//Get Company
				$objCompany 	= $this->Company_Model->get_rowByPK($companyID);
				//Get Datos
				$query			= "CALL pr_cxc_get_report_customer_status('".$companyID."','".$tocken."','".$userID."','".$customerNumber."');";
				$objData01		= $this->Bd_Model->executeProcedureMultiQuery($query);			
				$query			= "CALL pr_cxc_get_report_customer_pay('".$companyID."','".$tocken."','".$userID."','".$customerNumber."');";
				$objData02		= $this->Bd_Model->executeProcedureMultiQuery($query);			
				
				if(isset($objData01[0])){
					$objDataResult["objClient"]				= $objData01[0][0];
					$objDataResult["objLine"]				= $objData01[1];
					$objDataResult["objDocument"]			= $objData01[2];
					$objDataResult["objAmortization"]		= $objData01[3];
					
					if(isset($objData02[0])){
					$objDataResult["objPayList"]			= $objData02[0];
					}
					else
					$objDataResult["objPayList"]			= NULL;
				}
				else{
					$objDataResult["objClient"]				= NULL;
					$objDataResult["objLine"]				= NULL;
					$objDataResult["objDocument"]			= NULL;
					$objDataResult["objAmortization"]		= NULL;
					$objDataResult["objPayList"]			= NULL;
				}
				$objDataResult["objCompany"] 				= $objCompany;
				$objDataResult["objLogo"] 					= $objParameter;
				$objDataResult["objFirma"] 					= "{companyID:" . $dataSession["user"]->companyID . ",branchID:" . $dataSession["user"]->branchID . ",userID:" . $dataSession["user"]->userID . ",fechaID:" . date('Y-m-d H:i:s') . ",reportID:" . "pr_cxc_get_report_customer_status" . ",ip:". $dataSession["ip_address"] . ",sessionID:" . $dataSession["session_id"] .",agenteID:". $dataSession["user_agent"] .",lastActivity:".  $dataSession["last_activity"] . "}"  ;
				$objDataResult["objFirmaEncription"] 		= md5 ($objDataResult["objFirma"]);
				log_message("INFO",print_r($objDataResult,true));
				$this->load->view("app_cxc_report/pay/view_a_disemp",$objDataResult);
				
			}
		}
		catch(Exception $ex){
			show_error($ex->getLine()." ".$ex->getMessage() ,500 );
		}
	}
	function collection_manager(){
		try{ 
		
			//AUTENTICADO
			if(!$this->core_web_authentication->isAuthenticated())
			throw new Exception(USER_NOT_AUTENTICATED);
			$dataSession		= $this->session->all_userdata();
		
			//PERMISOS SOBRE LAS FUNCIONES
			if(APP_NEED_AUTHENTICATION == true){				
				
				$permited = false;
				$permited = $this->core_web_permission->urlPermited($this->router->class,"index",$this->config->item('url_suffix'),$dataSession["menuTop"],$dataSession["menuLeft"],$dataSession["menuBodyReport"],$dataSession["menuBodyTop"],$dataSession["menuHiddenPopup"]);
				
				if(!$permited)
				throw new Exception(NOT_ACCESS_CONTROL);
				
				$resultPermission		= $this->core_web_permission->urlPermissionCmd($this->router->class,"index",$this->config->item('url_suffix'),$dataSession,$dataSession["menuTop"],$dataSession["menuLeft"],$dataSession["menuBodyReport"],$dataSession["menuBodyTop"],$dataSession["menuHiddenPopup"]);
				if ($resultPermission 	== PERMISSION_NONE)
				throw new Exception(NOT_ACCESS_FUNCTION);			
			}	
			
			$uri				= $this->uri->uri_to_assoc(3);						
			$viewReport			= false;
			$employeeNumber		= false;
			$companyID			= $dataSession["user"]->companyID;
			$branchID			= $dataSession["user"]->branchID;
			$userID				= $dataSession["user"]->userID;
			$tocken				= '';
			if($uri != false){
				$viewReport			= $uri["viewReport"];	
				$employeeNumber		= $this->core_web_counter->getFillNumber($companyID,$branchID,"tb_employee",0,$uri["employeeNumber"]);
			} 
			
			//Cargar Libreria
			$this->load->model('core/Company_Model');	
			$this->load->model('core/Bd_Model');
				
			if(!($viewReport && $employeeNumber)){
				//Renderizar Resultado 
				$dataSession["message"]		= $this->core_web_notification->get_message();
				$dataSession["head"]		= $this->load->view('app_cxc_report/collection_manager/view_head','',true);
				$dataSession["body"]		= $this->load->view('app_cxc_report/collection_manager/view_body','',true);
				$dataSession["script"]		= $this->load->view('app_cxc_report/collection_manager/view_script','',true);  
				$dataSession["footer"]		= "";			
				$this->load->view("core_masterpage/default_report",$dataSession);		
			}
			else{				
				
				//Obtener el tipo de Comprobante
				$companyID 		= $dataSession["user"]->companyID;
				//Get Component
				$objComponent	= $this->core_web_tools->getComponentIDBy_ComponentName("tb_company");
				//Get Logo
				$objParameter			= $this->core_web_parameter->getParameter("CORE_COMPANY_LOGO",$companyID);
				$objPropietaryName		= $this->core_web_parameter->getParameter("CORE_PROPIETARY_NAME",$companyID);
				//Get Company
				$objCompany 	= $this->Company_Model->get_rowByPK($companyID);
				//Get Datos
				$query			= "CALL pr_cxc_get_report_collection_manager('".$userID."','".$tocken."','".$companyID."','".$employeeNumber."');";
				log_message("INFO",print_r($query,true));
				$objData		= $this->Bd_Model->executeProcedureMultiQuery($query);			
				
				if(isset($objData[0])){
					$objDataResult["objDetail"]					= $objData[0];
					$objDataResult["objFirstDetail"]			= $objData[0][0];
				}
				else{
					$objDataResult["objDetail"]					= NULL;
					$objDataResult["objFirstDetail"]			= NULL;
				}
				$objDataResult["objCompany"] 				= $objCompany;
				$objDataResult["objLogo"] 					= $objParameter;
				$objDataResult["objPropietaryName"] 		= $objPropietaryName;
				$objDataResult["objFirma"] 					= "{companyID:" . $dataSession["user"]->companyID . ",branchID:" . $dataSession["user"]->branchID . ",userID:" . $dataSession["user"]->userID . ",fechaID:" . date('Y-m-d H:i:s') . ",reportID:" . "pr_cxc_get_report_document_credit" . ",ip:". $dataSession["ip_address"] . ",sessionID:" . $dataSession["session_id"] .",agenteID:". $dataSession["user_agent"] .",lastActivity:".  $dataSession["last_activity"] . "}"  ;
				$objDataResult["objFirmaEncription"] 		= md5 ($objDataResult["objFirma"]);
				log_message("INFO",print_r($objDataResult,true));
				$this->load->view("app_cxc_report/collection_manager/view_a_disemp",$objDataResult);
				
			}
		}
		catch(Exception $ex){
			show_error($ex->getLine()." ".$ex->getMessage() ,500 );
		}
	}
	function exchange_rate(){
		try{ 
		
			//AUTENTICADO
			if(!$this->core_web_authentication->isAuthenticated())
			throw new Exception(USER_NOT_AUTENTICATED);
			$dataSession		= $this->session->all_userdata();
		
			//PERMISOS SOBRE LAS FUNCIONES
			if(APP_NEED_AUTHENTICATION == true){				
				
				$permited = false;
				$permited = $this->core_web_permission->urlPermited($this->router->class,"index",$this->config->item('url_suffix'),$dataSession["menuTop"],$dataSession["menuLeft"],$dataSession["menuBodyReport"],$dataSession["menuBodyTop"],$dataSession["menuHiddenPopup"]);
				
				if(!$permited)
				throw new Exception(NOT_ACCESS_CONTROL);
				
				$resultPermission		= $this->core_web_permission->urlPermissionCmd($this->router->class,"index",$this->config->item('url_suffix'),$dataSession,$dataSession["menuTop"],$dataSession["menuLeft"],$dataSession["menuBodyReport"],$dataSession["menuBodyTop"],$dataSession["menuHiddenPopup"]);
				if ($resultPermission 	== PERMISSION_NONE)
				throw new Exception(NOT_ACCESS_FUNCTION);			
			}	
			
			$uri				= $this->uri->uri_to_assoc(3);						
			$viewReport			= false;
			$companyID			= $dataSession["user"]->companyID;
			$branchID			= $dataSession["user"]->branchID;
			$userID				= $dataSession["user"]->userID;
			$tocken				= '';
			 
			
			//Cargar Libreria
			$this->load->model('core/Company_Model');	
			$this->load->model('core/Bd_Model');
				
				
			//Obtener el tipo de Comprobante
			$companyID 		= $dataSession["user"]->companyID;
			//Get Component
			$objComponent	= $this->core_web_tools->getComponentIDBy_ComponentName("tb_company");
			//Get Logo
			$objParameter	= $this->core_web_parameter->getParameter("CORE_COMPANY_LOGO",$companyID);
			//Get Company
			$objCompany 	= $this->Company_Model->get_rowByPK($companyID);
			//Get Datos
			$query			= "CALL pr_cxc_get_report_exchange_rate('".$userID."','".$tocken."','".$companyID."');";
			$objData		= $this->Bd_Model->executeProcedureMultiQuery($query);			
			
			if(isset($objData[0]))
			$objDataResult["objDetail"]					= $objData[0];
			else
			$objDataResult["objDetail"]					= NULL;
			$objDataResult["objCompany"] 				= $objCompany;
			$objDataResult["objLogo"] 					= $objParameter;
			$objDataResult["objFirma"] 					= "{companyID:" . $dataSession["user"]->companyID . ",branchID:" . $dataSession["user"]->branchID . ",userID:" . $dataSession["user"]->userID . ",fechaID:" . date('Y-m-d H:i:s') . ",reportID:" . "pr_cxc_get_report_customer_credit" . ",ip:". $dataSession["ip_address"] . ",sessionID:" . $dataSession["session_id"] .",agenteID:". $dataSession["user_agent"] .",lastActivity:".  $dataSession["last_activity"] . "}"  ;
			$objDataResult["objFirmaEncription"] 		= md5 ($objDataResult["objFirma"]);
			log_message("INFO",print_r($objDataResult,true));
			$this->load->view("app_cxc_report/exchange_rate/view_a_disemp",$objDataResult);
			
		}
		catch(Exception $ex){
			show_error($ex->getLine()." ".$ex->getMessage() ,500 );
		}
	}
	function summary_credit(){
		try{ 
		
			//AUTENTICADO
			if(!$this->core_web_authentication->isAuthenticated())
			throw new Exception(USER_NOT_AUTENTICATED);
			$dataSession		= $this->session->all_userdata();
		
			//PERMISOS SOBRE LAS FUNCIONES
			if(APP_NEED_AUTHENTICATION == true){				
				
				$permited = false;
				$permited = $this->core_web_permission->urlPermited($this->router->class,"index",$this->config->item('url_suffix'),$dataSession["menuTop"],$dataSession["menuLeft"],$dataSession["menuBodyReport"],$dataSession["menuBodyTop"],$dataSession["menuHiddenPopup"]);
				
				if(!$permited)
				throw new Exception(NOT_ACCESS_CONTROL);
				
				$resultPermission		= $this->core_web_permission->urlPermissionCmd($this->router->class,"index",$this->config->item('url_suffix'),$dataSession,$dataSession["menuTop"],$dataSession["menuLeft"],$dataSession["menuBodyReport"],$dataSession["menuBodyTop"],$dataSession["menuHiddenPopup"]);
				if ($resultPermission 	== PERMISSION_NONE)
				throw new Exception(NOT_ACCESS_FUNCTION);			
			}	
			
			$uri				= $this->uri->uri_to_assoc(3);						
			$viewReport			= false;
			$companyID			= $dataSession["user"]->companyID;
			$branchID			= $dataSession["user"]->branchID;
			$userID				= $dataSession["user"]->userID;
			$tocken				= '';
			 
			
			//Cargar Libreria
			$this->load->model('core/Company_Model');	
			$this->load->model('core/Bd_Model');
				
				
			//Obtener el tipo de Comprobante
			$companyID 		= $dataSession["user"]->companyID;
			//Get Component
			$objComponent	= $this->core_web_tools->getComponentIDBy_ComponentName("tb_company");
			//Get Logo
			$objParameter	= $this->core_web_parameter->getParameter("CORE_COMPANY_LOGO",$companyID);
			//Get Company
			$objCompany 	= $this->Company_Model->get_rowByPK($companyID);
			//Get Datos
			$query			= "CALL pr_cxc_get_report_summary_credit('".$userID."','".$tocken."','".$companyID."');";
			$objData		= $this->Bd_Model->executeProcedureMultiQuery($query);			
			
			if(isset($objData[0]))
			$objDataResult["objDetail"]					= $objData[0];
			else
			$objDataResult["objDetail"]					= NULL;
			$objDataResult["objCompany"] 				= $objCompany;
			$objDataResult["objLogo"] 					= $objParameter;
			$objDataResult["objFirma"] 					= "{companyID:" . $dataSession["user"]->companyID . ",branchID:" . $dataSession["user"]->branchID . ",userID:" . $dataSession["user"]->userID . ",fechaID:" . date('Y-m-d H:i:s') . ",reportID:" . "pr_cxc_get_report_customer_credit" . ",ip:". $dataSession["ip_address"] . ",sessionID:" . $dataSession["session_id"] .",agenteID:". $dataSession["user_agent"] .",lastActivity:".  $dataSession["last_activity"] . "}"  ;
			$objDataResult["objFirmaEncription"] 		= md5 ($objDataResult["objFirma"]);
			log_message("INFO",print_r($objDataResult,true));
			$this->load->view("app_cxc_report/summary_credit/view_a_disemp",$objDataResult);
			
		}
		catch(Exception $ex){
			show_error($ex->getLine()." ".$ex->getMessage() ,500 );
		}
	}
}
?>
<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class App_Collection_Report extends CI_Controller {
	
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
			$dataSession["head"]			= $this->load->view('app_collection_report/view_head','',true);
			$dataSession["body"]			= $this->load->view('app_collection_report/view_body',$dataMenu,true);
			$dataSession["script"]			= $this->load->view('app_collection_report/view_script','',true);  
			$dataSession["footer"]			= "";			
			$this->load->view("core_masterpage/default_masterpage",$dataSession);		
		}
		catch(Exception $ex){
			show_error($ex->getLine()." ".$ex->getMessage() ,500 );
		}
	}	function commission_provider(){		try{ 					//AUTENTICADO			if(!$this->core_web_authentication->isAuthenticated())			throw new Exception(USER_NOT_AUTENTICATED);			$dataSession		= $this->session->all_userdata();					//PERMISOS SOBRE LAS FUNCIONES			if(APP_NEED_AUTHENTICATION == true){												$permited = false;				$permited = $this->core_web_permission->urlPermited($this->router->class,"index",$this->config->item('url_suffix'),$dataSession["menuTop"],$dataSession["menuLeft"],$dataSession["menuBodyReport"],$dataSession["menuBodyTop"],$dataSession["menuHiddenPopup"]);								if(!$permited)				throw new Exception(NOT_ACCESS_CONTROL);								$resultPermission		= $this->core_web_permission->urlPermissionCmd($this->router->class,"index",$this->config->item('url_suffix'),$dataSession,$dataSession["menuTop"],$dataSession["menuLeft"],$dataSession["menuBodyReport"],$dataSession["menuBodyTop"],$dataSession["menuHiddenPopup"]);				if ($resultPermission 	== PERMISSION_NONE)				throw new Exception(NOT_ACCESS_FUNCTION);					}							$authorization		= $resultPermission;			$uri				= $this->uri->uri_to_assoc(3);									$viewReport			= false;			$startOn			= false;			$endOn				= false;						$providerID			= false;			$companyID			= $dataSession["user"]->companyID;			$branchID			= $dataSession["user"]->branchID;			$userID				= $dataSession["user"]->userID;			$tocken				= '';			if($uri != false){				$viewReport			= $uri["viewReport"];					$startOn			= $uri["startOn"];				$endOn				= $uri["endOn"];									$providerID			= $uri["providerID"];				} 						 						//Cargar Libreria			$this->load->model('core/Company_Model');				$this->load->model('core/Bd_Model');			$this->load->model('Provider_Model');						if(!($viewReport && $startOn && $endOn   )){								$objFiltros						= NULL;				$objFiltros["objListProvider"]	= $this->Provider_Model->get_rowByCompany($companyID);				//Renderizar Resultado 				$dataSession["message"]		= $this->core_web_notification->get_message();				$dataSession["head"]		= $this->load->view('app_collection_report/commission_provider/view_head','',true);				$dataSession["body"]		= $this->load->view('app_collection_report/commission_provider/view_body',$objFiltros,true);				$dataSession["script"]		= $this->load->view('app_collection_report/commission_provider/view_script','',true);  				$dataSession["footer"]		= "";							$this->load->view("core_masterpage/default_report",$dataSession);					}			else{										//Obtener el tipo de Comprobante				$companyID 		= $dataSession["user"]->companyID;				//Get Component				$objComponent	= $this->core_web_tools->getComponentIDBy_ComponentName("tb_company");				//Get Logo				$objParameter	= $this->core_web_parameter->getParameter("CORE_COMPANY_LOGO",$companyID);				//Get Company				$objCompany 	= $this->Company_Model->get_rowByPK($companyID);				//Get Datos				$query			= "CALL pr_collection_get_report_commision_provider('".$userID."','".$tocken."','".$companyID."','".$startOn."','".$endOn."','".$providerID."');";				$objData		= $this->Bd_Model->executeProcedureMultiQuery($query);											if(isset($objData[0]))				$objDataResult["objDetail"]					= $objData[0];				else				$objDataResult["objDetail"]					= NULL;				$objDataResult["objCompany"] 				= $objCompany;				$objDataResult["objLogo"] 					= $objParameter;				$objDataResult["startOn"] 					= $startOn;				$objDataResult["endOn"] 					= $endOn;				$objDataResult["objFirma"] 					= "{companyID:" . $dataSession["user"]->companyID . ",branchID:" . $dataSession["user"]->branchID . ",userID:" . $dataSession["user"]->userID . ",fechaID:" . date('Y-m-d H:i:s') . ",reportID:" . "pr_cxc_get_report_customer_credit" . ",ip:". $dataSession["ip_address"] . ",sessionID:" . $dataSession["session_id"] .",agenteID:". $dataSession["user_agent"] .",lastActivity:".  $dataSession["last_activity"] . "}"  ;				$objDataResult["objFirmaEncription"] 		= md5 ($objDataResult["objFirma"]);				log_message("INFO",print_r($objDataResult,true));				$this->load->view("app_collection_report/commission_provider/view_a_disemp",$objDataResult);			}		}		catch(Exception $ex){			show_error($ex->getLine()." ".$ex->getMessage() ,500 );		}	}
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
			} 
			
			//Cargar Libreria
			$this->load->model('core/Company_Model');	
			$this->load->model('core/Bd_Model');
				
			if(!($viewReport && $documentNumber)){
				//Renderizar Resultado 
				$dataSession["message"]		= $this->core_web_notification->get_message();
				$dataSession["head"]		= $this->load->view('app_collection_report/document_credit/view_head','',true);
				$dataSession["body"]		= $this->load->view('app_collection_report/document_credit/view_body','',true);
				$dataSession["script"]		= $this->load->view('app_collection_report/document_credit/view_script','',true);  
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
				$query			= "CALL pr_collection_get_report_document_credit('".$companyID."','".$tocken."','".$userID."','".$documentNumber."');";
				$objData		= $this->Bd_Model->executeProcedureMultiQuery($query);			
				
				if(isset($objData[0])){
					$objDataResult["objDetail"]					= $objData[0];
				}
				else{
					$objDataResult["objDetail"]					= NULL;
				}
				$objDataResult["objUser"] 					= $dataSession["user"];
				$objDataResult["objCompany"] 				= $objCompany;
				$objDataResult["objLogo"] 					= $objParameter;
				$objDataResult["objPropietaryName"] 		= $objPropietaryName;
				$objDataResult["objFirma"] 					= "{companyID:" . $dataSession["user"]->companyID . ",branchID:" . $dataSession["user"]->branchID . ",userID:" . $dataSession["user"]->userID . ",fechaID:" . date('Y-m-d H:i:s') . ",reportID:" . "pr_cxc_get_report_document_credit" . ",ip:". $dataSession["ip_address"] . ",sessionID:" . $dataSession["session_id"] .",agenteID:". $dataSession["user_agent"] .",lastActivity:".  $dataSession["last_activity"] . "}"  ;
				$objDataResult["objFirmaEncription"] 		= md5 ($objDataResult["objFirma"]);
				log_message("INFO",print_r($objDataResult,true));
				$this->load->view("app_collection_report/document_credit/view_a_disemp",$objDataResult);
				
			}
		}
		catch(Exception $ex){
			show_error($ex->getLine()." ".$ex->getMessage() ,500 );
		}
	}
	function customer(){
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
			$query			= "CALL pr_collection_get_report_customer('".$userID."','".$tocken."','".$companyID."');";
			$objData		= $this->Bd_Model->executeProcedureMultiQuery($query);			
			
			if(isset($objData[0]))
			$objDataResult["objDetail"]					= $objData[0];
			else
			$objDataResult["objDetail"]					= NULL;
		
			$objDataResult["objUser"]					= $dataSession["user"];
			$objDataResult["objCompany"] 				= $objCompany;
			$objDataResult["objLogo"] 					= $objParameter;
			$objDataResult["objFirma"] 					= "{companyID:" . $dataSession["user"]->companyID . ",branchID:" . $dataSession["user"]->branchID . ",userID:" . $dataSession["user"]->userID . ",fechaID:" . date('Y-m-d H:i:s') . ",reportID:" . "pr_cxc_get_report_customer_credit" . ",ip:". $dataSession["ip_address"] . ",sessionID:" . $dataSession["session_id"] .",agenteID:". $dataSession["user_agent"] .",lastActivity:".  $dataSession["last_activity"] . "}"  ;
			$objDataResult["objFirmaEncription"] 		= md5 ($objDataResult["objFirma"]);
			log_message("INFO",print_r($objDataResult,true));
			$this->load->view("app_collection_report/customer/view_a_disemp",$objDataResult);
			
		}
		catch(Exception $ex){
			show_error($ex->getLine()." ".$ex->getMessage() ,500 );
		}
	}
	function commission(){
		try{ 
		
			//CARGAR HELPER
			$this->load->helper('language');
			
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
			
			$uri			= $this->uri->uri_to_assoc(3);						
			$viewReport		= false;
			$periodID		= false;
			$cycleID		= false;
			$companyID		= $dataSession["user"]->companyID;
			$branchID		= $dataSession["user"]->branchID;
			$userID			= $dataSession["user"]->userID;
			$tocken			= '';
			if($uri != false){
				$viewReport		= $uri["viewReport"];	
				$periodID		= $uri["periodID"];
				$cycleID		= $uri["cycleID"];	
			} 
			
			if(!($viewReport && $periodID && $cycleID)){
				$this->load->model('Component_Period_Model');
				$data["objListAccountingPeriod"] 
										= $this->Component_Period_Model->get_rowByCompany($dataSession["user"]->companyID);
				
				//Renderizar Resultado 
				$dataSession["message"]	= $this->core_web_notification->get_message();
				$dataSession["head"]	= $this->load->view('app_collection_report/commission/view_head','',true);
				$dataSession["body"]	= $this->load->view('app_collection_report/commission/view_body',$data,true);
				$dataSession["script"]	= $this->load->view('app_collection_report/commission/view_script','',true);  
				$dataSession["footer"]	= "";			
				$this->load->view("core_masterpage/default_report",$dataSession);		
			}
			else{				
				//Cargar Libreria
				$this->load->model('core/Company_Model');	
				$this->load->model('core/Bd_Model');
				$this->load->model('Component_Cycle_Model');
				$this->load->model('Component_Period_Model');
				
				$companyID 		= $dataSession["user"]->companyID;
				//Get Component
				$objComponent	= $this->core_web_tools->getComponentIDBy_ComponentName("tb_company");
				//Get Logo
				$objParameter	= $this->core_web_parameter->getParameter("CORE_COMPANY_LOGO",$companyID);
				//Get Company
				$objCompany 	= $this->Company_Model->get_rowByPK($companyID);			
				$objCycle		= $this->Component_Cycle_Model->get_rowByPK($periodID,$cycleID);
				$objPeriod		= $this->Component_Period_Model->get_rowByPK($periodID);
				//Get Datos
				$query			= "CALL pr_collection_get_report_detalle_transaction('".$companyID."','".$branchID."','".$userID."','".$tocken."','".$periodID."','".$cycleID."');";				
				$objData		= $this->Bd_Model->executeProcedureMultiQuery($query);	

				$objDataResult["objDetail"]			= $objData[0];
				$objDataResult["objCompany"] 		= $objCompany;
				$objDataResult["objLogo"] 			= $objParameter;
				$objDataResult["objPeriod"]			= $objPeriod;
				$objDataResult["objCycle"]			= $objCycle;
				$objDataResult["objFirma"] 					= "{companyID:" . $dataSession["user"]->companyID . ",branchID:" . $dataSession["user"]->branchID . ",userID:" . $dataSession["user"]->userID . ",fechaID:" . date('Y-m-d H:i:s') . ",reportID:" . "balance_general" . ",ip:". $dataSession["ip_address"] . ",sessionID:" . $dataSession["session_id"] .",agenteID:". $dataSession["user_agent"] .",lastActivity:".  $dataSession["last_activity"] . "}"  ;
				$objDataResult["objFirmaEncription"] 		= md5 ($objDataResult["objFirma"]);
				log_message("INFO",print_r($objDataResult,true));
				$this->load->view("app_collection_report/commission/view_a_disemp",$objDataResult);
				
			}
		}
		catch(Exception $ex){
			show_error($ex->getLine()." ".$ex->getMessage() ,500 );
		}
	}
}
?>
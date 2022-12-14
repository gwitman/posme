<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class App_Box_Report extends CI_Controller {
	
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
			$dataSession["head"]			= $this->load->view('app_box_report/view_head','',true);
			$dataSession["body"]			= $this->load->view('app_box_report/view_body',$dataMenu,true);
			$dataSession["script"]			= $this->load->view('app_box_report/view_script','',true);  
			$dataSession["footer"]			= "";			
			$this->load->view("core_masterpage/default_masterpage",$dataSession);		
		}
		catch(Exception $ex){
			show_error($ex->getLine()." ".$ex->getMessage() ,500 );
		}
	}
	function share(){
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
			
			$authorization		= $resultPermission;
			$uri				= $this->uri->uri_to_assoc(3);						
			$viewReport			= false;
			$startOn			= false;
			$endOn				= false;
			$companyID			= $dataSession["user"]->companyID;
			$branchID			= $dataSession["user"]->branchID;
			$userID				= $dataSession["user"]->userID;
			$tocken				= '';
			//log_message("ERROR",print_r($dataSession["role"],true)) ;
			//obtener permiso de fecha de reporte				
			$filterArray 		= array_filter($dataSession["menuHiddenPopup"], function($val){  return (strpos($val->display, 'ES_PERMITIDO_MOSTRAR_INFO_DE_') !== false) && (strpos($val->display, '_DAY_IN_app_box_report_share') !== false) ;});
			$filteredArray 		= [];
			$filterIndex 		= 0;			
			foreach($filterArray as $key => $value ){ $filteredArray[$filterIndex] = $value;  $filterIndex++; }
			if(count($filteredArray) > 0 && $dataSession["role"]->isAdmin == 0 ){
				$filteredArray = str_replace("ES_PERMITIDO_MOSTRAR_INFO_DE_","",$filteredArray[0]->display);
				$filteredArray = str_replace("_DAY_IN_app_box_report_share","",$filteredArray);
				$filteredArray = intval($filteredArray);
			}
			else{
				$filteredArray = -1;
			}
			//log_message("ERROR","cantidad de dias") ;
			if($uri != false){
				$viewReport			= $uri["viewReport"];	
				$startOn			= $uri["startOn"];
				$endOn				= $uri["endOn"]." 23:59:59";	
				//calcular las fechas iniciales del reporte
				$startOn_ 	= DateTime::createFromFormat('Y-m-d',$startOn);		
				$endOn_ 	= DateTime::createFromFormat('Y-m-d H:i:s',$endOn);		
				if($filteredArray != -1){
					$startOn_Temporal = $endOn_;
					date_sub($startOn_Temporal, date_interval_create_from_date_string($filteredArray.' days'));
					
					if($startOn_ <  $startOn_Temporal){
						$startOn = $startOn_Temporal->format('Y-m-d');
					}
				}

				//log_message("ERROR",print_r($startOn,true)) ;
				//log_message("ERROR",print_r($endOn,true)) ;
			} 
			
			
			
			
			//Cargar Libreria
			$this->load->model('core/Company_Model');	
			$this->load->model('core/Bd_Model');
				
			
			if(!($viewReport && $startOn && $endOn )){
				
				//Renderizar Resultado 
				$dataSession["message"]		= $this->core_web_notification->get_message();
				$dataSession["head"]		= $this->load->view('app_box_report/share/view_head','',true);
				$dataSession["body"]		= $this->load->view('app_box_report/share/view_body','',true);
				$dataSession["script"]		= $this->load->view('app_box_report/share/view_script','',true);  
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
				$query			= "CALL pr_box_get_report_abonos('".$userID."','".$tocken."','".$companyID."','".$authorization."','".$startOn."','".$endOn."');";
				$objData		= $this->Bd_Model->executeProcedureMultiQuery($query);			
				
				
				//Get Datos de Facturacion				
				$query			= "CALL pr_sales_get_report_sales_summary('".$companyID."','".$tocken."','".$userID."','".$startOn."','".$endOn."');";
				$objDataSales	= $this->Bd_Model->executeProcedureMultiQuery($query);			
				
				//Get Datos de Entrada de Efectivo y Salida				
				$query			= "CALL pr_box_get_report_input_outpu_cash('".$userID."','".$tocken."','".$companyID."','".$authorization."','".$startOn."','".$endOn."');";
				$objDataCash	= $this->Bd_Model->executeProcedureMultiQuery($query);			
				
				if(isset($objData[0]))
				$objDataResult["objDetail"]					= $objData[0];
				else
				$objDataResult["objDetail"]					= NULL;
			
			
				if(isset($objDataSales[0]))
				$objDataResult["objSales"]					= $objDataSales[0];
				else
				$objDataResult["objSales"]					= NULL;
			
				if(isset($objDataCash[0]))				
				$objDataResult["objCash"]					= $objDataCash[0];
				else
				$objDataResult["objCash"]					= NULL;
			
				//log_message("ERROR",print_r($objDataCash,true));
				//log_message("ERROR",print_r($objDataSales,true));
				
				
				
				$objDataResult["objCompany"] 				= $objCompany;
				$objDataResult["objLogo"] 					= $objParameter;
				$objDataResult["startOn"] 					= $startOn;
				$objDataResult["endOn"] 					= $endOn;
				$objDataResult["objFirma"] 					= "{companyID:" . $dataSession["user"]->companyID . ",branchID:" . $dataSession["user"]->branchID . ",userID:" . $dataSession["user"]->userID . ",fechaID:" . date('Y-m-d H:i:s') . ",reportID:" . "pr_cxc_get_report_customer_credit" . ",ip:". $dataSession["ip_address"] . ",sessionID:" . $dataSession["session_id"] .",agenteID:". $dataSession["user_agent"] .",lastActivity:".  $dataSession["last_activity"] . "}"  ;
				$objDataResult["objFirmaEncription"] 		= md5 ($objDataResult["objFirma"]);
				//log_message("INFO",print_r($objDataResult,true));
				$this->load->view("app_box_report/share/view_a_disemp",$objDataResult);
			}
		}
		catch(Exception $ex){
			show_error($ex->getLine()." ".$ex->getMessage() ,500 );
		}
	}
	
}
?>
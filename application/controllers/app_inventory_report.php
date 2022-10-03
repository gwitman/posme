<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class App_Inventory_Report extends CI_Controller {
	
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
			$dataSession["head"]			= $this->load->view('app_inventory_report/view_head','',true);
			$dataSession["body"]			= $this->load->view('app_inventory_report/view_body',$dataMenu,true);
			$dataSession["script"]			= $this->load->view('app_inventory_report/view_script','',true);  
			$dataSession["footer"]			= "";			
			$this->load->view("core_masterpage/default_masterpage",$dataSession);		
		}
		catch(Exception $ex){
			show_error($ex->getLine()." ".$ex->getMessage() ,500 );
		}
	}
	function movement_by_warehouse(){
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
			$warehouseID		= false;			
			$itemID 			= false;
			$companyID			= $dataSession["user"]->companyID;
			$branchID			= $dataSession["user"]->branchID;
			$userID				= $dataSession["user"]->userID;
			$tocken				= '';
			if($uri != false){
				$viewReport			= $uri["viewReport"];	
				$startOn			= $uri["startOn"];
				$endOn				= $uri["endOn"];	
				$warehouseID		= $uri["warehouseID"];						
				$itemID				= $uri["itemID"];						
			} 
			
			//Cargar Libreria
			$this->load->model('core/Company_Model');	
			$this->load->model('core/Bd_Model');
			$this->load->model('Warehouse_Model');
			$this->load->model("UserWarehouse_Model");
			$this->load->model("Item_Model");
				
				
			if(!($viewReport && $startOn && $endOn && $warehouseID && $itemID )){
				
				$data["objListWarehouse"]	= $this->UserWarehouse_Model->getRowByUserID($companyID,$userID);				
				$data["objListItem"]		= $this->Item_Model->get_rowByCompany($companyID);
				//Renderizar Resultado 
				$dataSession["message"]		= $this->core_web_notification->get_message();
				$dataSession["head"]		= $this->load->view('app_inventory_report/movement_by_warehouse/view_head','',true);
				$dataSession["body"]		= $this->load->view('app_inventory_report/movement_by_warehouse/view_body',$data,true);
				$dataSession["script"]		= $this->load->view('app_inventory_report/movement_by_warehouse/view_script','',true);  
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
				$query			= "CALL pr_inventory_get_report_auxiliar_mov_by_warehouse('".$companyID."','".$warehouseID."','".$startOn."','".$endOn."','".$itemID."');";
				$objData		= $this->Bd_Model->executeProcedureMultiQuery($query);			
				
				if(isset($objData[0]))
				$objDataResult["objDetail"]					= $objData[0];
				else
				$objDataResult["objDetail"]					= NULL;
				$objDataResult["objCompany"] 				= $objCompany;
				$objDataResult["objLogo"] 					= $objParameter;
				$objDataResult["startOn"]					= $startOn;
				$objDataResult["endOn"]						= $endOn;
				$objDataResult["objItem"] 					= $this->Item_Model->get_rowByPK($companyID,$itemID);
				$objDataResult["objWarehouse"]				= $this->Warehouse_Model->get_rowByPK($companyID,$warehouseID);
				$objDataResult["objFirma"] 					= "{companyID:" . $dataSession["user"]->companyID . ",branchID:" . $dataSession["user"]->branchID . ",userID:" . $dataSession["user"]->userID . ",fechaID:" . date('Y-m-d H:i:s') . ",reportID:" . "auxiliar_mov_x_tipo_de_comprobantes" . ",ip:". $dataSession["ip_address"] . ",sessionID:" . $dataSession["session_id"] .",agenteID:". $dataSession["user_agent"] .",lastActivity:".  $dataSession["last_activity"] . "}"  ;
				$objDataResult["objFirmaEncription"] 		= md5 ($objDataResult["objFirma"]);
				log_message("INFO",print_r($objDataResult,true));
				$this->load->view("app_inventory_report/movement_by_warehouse/view_a_disemp",$objDataResult);
				
			}
		}
		catch(Exception $ex){
			show_error($ex->getLine()." ".$ex->getMessage() ,500 );
		}
	}
	function movement(){
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
			$itemID 			= false;
			$companyID			= $dataSession["user"]->companyID;
			$branchID			= $dataSession["user"]->branchID;
			$userID				= $dataSession["user"]->userID;
			$tocken				= '';
			if($uri != false){
				$viewReport			= $uri["viewReport"];	
				$startOn			= $uri["startOn"];
				$endOn				= $uri["endOn"];	
				$itemID				= $uri["itemID"];						
			} 
			
			//Cargar Libreria
			$this->load->model('core/Company_Model');	
			$this->load->model('core/Bd_Model');
			$this->load->model("Item_Model");
				
				
			if(!($viewReport && $startOn && $endOn && $itemID )){
				
				$data["objListItem"]		= $this->Item_Model->get_rowByCompany($companyID);
				//Renderizar Resultado 
				$dataSession["message"]		= $this->core_web_notification->get_message();
				$dataSession["head"]		= $this->load->view('app_inventory_report/movement/view_head','',true);
				$dataSession["body"]		= $this->load->view('app_inventory_report/movement/view_body',$data,true);
				$dataSession["script"]		= $this->load->view('app_inventory_report/movement/view_script','',true);  
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
				$query			= "CALL pr_inventory_get_report_auxiliar_mov_by_allwarehouse('".$companyID."','".$startOn."','".$endOn."','".$itemID."');";
				$objData		= $this->Bd_Model->executeProcedureMultiQuery($query);			
				
				
				if(isset($objData[0]))
				$objDataResult["objDetail"]					= $objData[0];
				else
				$objDataResult["objDetail"]					= NULL;
				$objDataResult["objCompany"] 				= $objCompany;
				$objDataResult["objLogo"] 					= $objParameter;
				$objDataResult["startOn"]					= $startOn;
				$objDataResult["endOn"]						= $endOn;
				$objDataResult["objItem"] 					= $this->Item_Model->get_rowByPK($companyID,$itemID);				
				$objDataResult["objFirma"] 					= "{companyID:" . $dataSession["user"]->companyID . ",branchID:" . $dataSession["user"]->branchID . ",userID:" . $dataSession["user"]->userID . ",fechaID:" . date('Y-m-d H:i:s') . ",reportID:" . "auxiliar_mov_x_tipo_de_comprobantes" . ",ip:". $dataSession["ip_address"] . ",sessionID:" . $dataSession["session_id"] .",agenteID:". $dataSession["user_agent"] .",lastActivity:".  $dataSession["last_activity"] . "}"  ;
				$objDataResult["objFirmaEncription"] 		= md5 ($objDataResult["objFirma"]);
				log_message("INFO",print_r($objDataResult,true));
				$this->load->view("app_inventory_report/movement/view_a_disemp",$objDataResult);
				
			}
		}
		catch(Exception $ex){
			show_error($ex->getLine()." ".$ex->getMessage() ,500 );
		}
	}
	function master_kardex(){
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
			$warehouseID		= false;
			$companyID			= $dataSession["user"]->companyID;
			$branchID			= $dataSession["user"]->branchID;
			$userID				= $dataSession["user"]->userID;
			$tocken				= '';
			if($uri != false){
				$viewReport			= $uri["viewReport"];	
				$startOn			= $uri["startOn"];
				$endOn				= $uri["endOn"];	
				$warehouseID		= $uri["warehouseID"];	
			} 
			
			//Cargar Libreria
			$this->load->model('core/Company_Model');	
			$this->load->model('core/Bd_Model');
			$this->load->model('Warehouse_Model');
			$this->load->model("UserWarehouse_Model");
			$this->load->model("Item_Model");
				
				
			if(!($viewReport && $startOn && $endOn  )){
				$data["objListWarehouse"]	= $this->UserWarehouse_Model->getRowByUserID($companyID,$userID);
				//Renderizar Resultado 
				$dataSession["message"]		= $this->core_web_notification->get_message();
				$dataSession["head"]		= $this->load->view('app_inventory_report/master_kardex/view_head','',true);
				$dataSession["body"]		= $this->load->view('app_inventory_report/master_kardex/view_body',$data,true);
				$dataSession["script"]		= $this->load->view('app_inventory_report/master_kardex/view_script','',true);  
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
				$query			= "CALL pr_inventory_get_report_master_kardex('".$userID."','".$tocken."','".$companyID."','".$warehouseID."','".$startOn."','".$endOn."');";
				$objData		= $this->Bd_Model->executeProcedureMultiQuery($query);			
				
				if(isset($objData[0]))
				$objDataResult["objDetail"]					= $objData[0];
				else
				$objDataResult["objDetail"]					= NULL;
				$objDataResult["objCompany"] 				= $objCompany;
				$objDataResult["objLogo"] 					= $objParameter;
				$objDataResult["startOn"]					= $startOn;
				$objDataResult["endOn"]						= $endOn;
				$objDataResult["objWarehouse"]				= $this->Warehouse_Model->get_rowByPK($companyID,$warehouseID);
				$objDataResult["objFirma"] 					= "{companyID:" . $dataSession["user"]->companyID . ",branchID:" . $dataSession["user"]->branchID . ",userID:" . $dataSession["user"]->userID . ",fechaID:" . date('Y-m-d H:i:s') . ",reportID:" . "auxiliar_mov_x_tipo_de_comprobantes" . ",ip:". $dataSession["ip_address"] . ",sessionID:" . $dataSession["session_id"] .",agenteID:". $dataSession["user_agent"] .",lastActivity:".  $dataSession["last_activity"] . "}"  ;
				$objDataResult["objFirmaEncription"] 		= md5 ($objDataResult["objFirma"]);
				log_message("INFO",print_r($objDataResult,true));
				$this->load->view("app_inventory_report/master_kardex/view_a_disemp",$objDataResult);
				
			}
		}
		catch(Exception $ex){
			show_error($ex->getLine()." ".$ex->getMessage() ,500 );
		}
	}
	function list_item(){
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
			$query			= "CALL pr_inventory_get_report_list_item('".$userID."','".$tocken."','".$companyID."');";
			$objData		= $this->Bd_Model->executeProcedureMultiQuery($query);			
			
			if(isset($objData[0]))
			$objDataResult["objDetail"]					= $objData[0];
			else
			$objDataResult["objDetail"]					= NULL;
			$objDataResult["objCompany"] 				= $objCompany;
			$objDataResult["objLogo"] 					= $objParameter;
			$objDataResult["objFirma"] 					= "{companyID:" . $dataSession["user"]->companyID . ",branchID:" . $dataSession["user"]->branchID . ",userID:" . $dataSession["user"]->userID . ",fechaID:" . date('Y-m-d H:i:s') . ",reportID:" . "pr_inventory_get_report_list_item" . ",ip:". $dataSession["ip_address"] . ",sessionID:" . $dataSession["session_id"] .",agenteID:". $dataSession["user_agent"] .",lastActivity:".  $dataSession["last_activity"] . "}"  ;
			$objDataResult["objFirmaEncription"] 		= md5 ($objDataResult["objFirma"]);
			log_message("INFO",print_r($objDataResult,true));
			$this->load->view("app_inventory_report/list_item/view_a_disemp",$objDataResult);
			
		}
		catch(Exception $ex){
			show_error($ex->getLine()." ".$ex->getMessage() ,500 );
		}
	}
}
?>
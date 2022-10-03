<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class App_Accounting_Report extends CI_Controller {
	
    public function __construct() {
       parent::__construct();
    }	
	function auxiliar_mov_x_tipo_de_comprobantes(){
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
			$journalTypeID		= false;				$excludeSystem		= 0;			$stringContainer	= "";
			$companyID			= $dataSession["user"]->companyID;
			$branchID			= $dataSession["user"]->branchID;
			$userID				= $dataSession["user"]->userID;
			$tocken				= '';
			if($uri != false){
				$viewReport			= $uri["viewReport"];	
				$startOn			= $uri["startOn"];
				$endOn				= $uri["endOn"];
				$journalTypeID		= $uri["journalTypeID"];										$excludeSystem		= $uri["excludeSystem"];					$stringContainer	= $uri["stringContainer"];				$classID			= $uri["classID"];
			} 
			
			if(!($viewReport && $startOn && $endOn && $journalTypeID )){
								$this->load->model('Center_Cost_Model');
				$data["objListJournalType"]	= $this->core_web_catalog->getCatalogAllItem("tb_journal_entry","journalTypeID",$companyID);				$data["objListCentroCosto"]	= $this->Center_Cost_Model->getByCompany($companyID);				
				//Renderizar Resultado 
				$dataSession["message"]		= $this->core_web_notification->get_message();
				$dataSession["head"]		= $this->load->view('app_account_report/auxiliar_mov_x_tipo_de_comprobantes/view_head','',true);
				$dataSession["body"]		= $this->load->view('app_account_report/auxiliar_mov_x_tipo_de_comprobantes/view_body',$data,true);
				$dataSession["script"]		= $this->load->view('app_account_report/auxiliar_mov_x_tipo_de_comprobantes/view_script','',true);  
				$dataSession["footer"]		= "";			
				$this->load->view("core_masterpage/default_report",$dataSession);		
			}
			else{				
				//Cargar Libreria
				$this->load->model('core/Company_Model');	
				$this->load->model('core/Bd_Model');
				$this->load->model('core/Catalog_Item_Model');				$this->load->model('Center_Cost_Model');
				
				//Obtener el tipo de Comprobante
				$objCatalogItem = $this->Catalog_Item_Model->get_rowByCatalogItemID($journalTypeID);
				$companyID 		= $dataSession["user"]->companyID;
				//Get Component
				$objComponent	= $this->core_web_tools->getComponentIDBy_ComponentName("tb_company");
				//Get Logo
				$objParameter	= $this->core_web_parameter->getParameter("CORE_COMPANY_LOGO",$companyID);
				//Get Company
				$objCompany 	= $this->Company_Model->get_rowByPK($companyID);
				//Get Datos
				$query			= "CALL pr_accounting_get_report_auxiliar_mov_tipo_comprobantes('".$companyID."','".$journalTypeID."','".$startOn."','".$endOn."','".$excludeSystem."','".$stringContainer."','".$classID."');";
				$objData		= $this->Bd_Model->executeProcedureMultiQuery($query);			
				
				if(isset($objData[0]))
				$objDataResult["objDetail"]			= $objData[0];
				else
				$objDataResult["objDetail"]			= $objData;
				
				$objDataResult["objCompany"] 		= $objCompany;
				$objDataResult["objLogo"] 			= $objParameter;
				$objDataResult["startOn"]			= $startOn;
				$objDataResult["endOn"]				= $endOn;
				$objDataResult["objTipo"]			= $objCatalogItem <> NULL ? $objCatalogItem->name : 'TODOS';
				$objDataResult["objFirma"] 					= "{companyID:" . $dataSession["user"]->companyID . ",branchID:" . $dataSession["user"]->branchID . ",userID:" . $dataSession["user"]->userID . ",fechaID:" . date('Y-m-d H:i:s') . ",reportID:" . "auxiliar_mov_x_tipo_de_comprobantes" . ",ip:". $dataSession["ip_address"] . ",sessionID:" . $dataSession["session_id"] .",agenteID:". $dataSession["user_agent"] .",lastActivity:".  $dataSession["last_activity"] . "}"  ;
				$objDataResult["objFirmaEncription"] 		= md5 ($objDataResult["objFirma"]);
				log_message("INFO",print_r($objDataResult,true));
				$this->load->view("app_account_report/auxiliar_mov_x_tipo_de_comprobantes/view_a_disemp",$objDataResult);
				
			}
		}
		catch(Exception $ex){
			show_error($ex->getLine()." ".$ex->getMessage() ,500 );
		}
	}
	function catalogo_de_cuentas(){
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
			
			$companyID		= $dataSession["user"]->companyID;
			$branchID		= $dataSession["user"]->branchID;
			$userID			= $dataSession["user"]->userID;			
			
			//Cargar Libreria
			$this->load->model('core/Company_Model');	
			$this->load->model('core/Bd_Model');
			$this->load->library('encrypt');
			
			$companyID 		= $dataSession["user"]->companyID;
			//Get Component
			$objComponent	= $this->core_web_tools->getComponentIDBy_ComponentName("tb_company");
			//Get Logo
			$objParameter	= $this->core_web_parameter->getParameter("CORE_COMPANY_LOGO",$companyID);
			//Get Company
			$objCompany 	= $this->Company_Model->get_rowByPK($companyID);							
			//Get Datos
			$query			= "CALL pr_accounting_get_report_catalogo_de_cuenta('".$companyID."');";
			$objData		= $this->Bd_Model->executeProcedureMultiQuery($query);	
			
			$objDataResult["objDetail"] 				= $objData[0];
			$objDataResult["objCompany"] 				= $objCompany;
			$objDataResult["objLogo"] 					= $objParameter;
			$objDataResult["objFirma"] 					= "{companyID:" . $dataSession["user"]->companyID . ",branchID:" . $dataSession["user"]->branchID . ",userID:" . $dataSession["user"]->userID . ",fechaID:" . date('Y-m-d H:i:s') . ",reportID:" . "catalogo_de_cuentas" . ",ip:". $dataSession["ip_address"] . ",sessionID:" . $dataSession["session_id"] .",agenteID:". $dataSession["user_agent"] .",lastActivity:".  $dataSession["last_activity"] . "}"  ;
			$objDataResult["objFirmaEncription"] 		= md5 ($objDataResult["objFirma"]);
			log_message("INFO",print_r($objDataResult,true));
			$this->load->view("app_account_report/catalogo_de_cuenta/view_a_disemp",$objDataResult);			
			
		}
		catch(Exception $ex){
			show_error($ex->getLine()." ".$ex->getMessage() ,500 );
		}
	}
	function auxiliar_de_cuenta(){
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
			
			$uri			= $this->uri->uri_to_assoc(3);						
			$viewReport		= false;
			$periodID		= false;
			$cycleStartID	= false;
			$cycleEndID		= false;
			$accountID		= false;
			$companyID		= $dataSession["user"]->companyID;
			$branchID		= $dataSession["user"]->branchID;
			$userID			= $dataSession["user"]->userID;
			$tocken			= '';
			if($uri != false){
				$viewReport		= $uri["viewReport"];	
				$periodID		= $uri["periodID"];
				$cycleStartID	= $uri["cycleStartID"];	
				$cycleEndID		= $uri["cycleEndID"];	
				$accountID		= $uri["accountID"];	
			} 
			
			if(!($viewReport && $periodID && $cycleStartID && $cycleEndID && $accountID)){
				$this->load->model('Component_Period_Model');
				$this->load->model('Account_Model');
				$data["objListAccountingPeriod"] 
										= $this->Component_Period_Model->get_rowByCompany($dataSession["user"]->companyID);
				$data["objListAccount"] 
										= $this->Account_Model->getByCompanyOperative($dataSession["user"]->companyID);
				
				//Renderizar Resultado 
				$dataSession["message"]	= $this->core_web_notification->get_message();
				$dataSession["head"]	= $this->load->view('app_account_report/auxiliar_de_cuenta/view_head','',true);
				$dataSession["body"]	= $this->load->view('app_account_report/auxiliar_de_cuenta/view_body',$data,true);
				$dataSession["script"]	= $this->load->view('app_account_report/auxiliar_de_cuenta/view_script','',true);  
				$dataSession["footer"]	= "";			
				$this->load->view("core_masterpage/default_report",$dataSession);		
			}
			else{				
				//Cargar Libreria
				$this->load->model('core/Company_Model');	
				$this->load->model('core/Bd_Model');
				$this->load->model('Component_Cycle_Model');
				
				//Obtener Ciclo Inicial y final
				$objCycleStart 	= $this->Component_Cycle_Model->get_rowByPK($periodID,$cycleStartID);
				$objCycleEnd 	= $this->Component_Cycle_Model->get_rowByPK($periodID,$cycleEndID);
				
				
				$companyID 		= $dataSession["user"]->companyID;
				//Get Component
				$objComponent	= $this->core_web_tools->getComponentIDBy_ComponentName("tb_company");
				//Get Logo
				$objParameter	= $this->core_web_parameter->getParameter("CORE_COMPANY_LOGO",$companyID);
				//Get Company
				$objCompany 	= $this->Company_Model->get_rowByPK($companyID);	
				//Get Datos
				$query			= "CALL pr_accounting_get_report_auxiliar_account('".$companyID."','".$periodID."','".$cycleStartID."','".$cycleEndID."','".$accountID."');";
				$objData		= $this->Bd_Model->executeProcedureMultiQuery($query);				

				
				if(isset($objData[0])){
					$objDataResult["name"] 				= $objData[0][0]["name"];
					$objDataResult["accountNumber"] 	= $objData[0][0]["accountNumber"];
					$objDataResult["naturaleza"] 		= $objData[0][0]["naturaleza"];
					$objDataResult["money"] 			= $objData[0][0]["money"];
					$objDataResult["description"] 		= $objData[0][0]["description"];
				}
				if(isset($objData[1])){
					$objDataResult["objBalanceStart"] 	= $objData[1][0]["balanceStart"];
				}
				else
					$objDataResult["objBalanceStart"] 	= $objData;
					
				if(isset($objData[2])){
					$objDataResult["objMovement"] 		= $objData[2];
				}
				else 
					$objDataResult["objMovement"] 		= NULL;
					
				if(isset($objData[3])){
					$objDataResult["objBalanceEnd"] 	= $objData[3][0]["balanceEnd"];
				}
				else 
					$objDataResult["objBalanceEnd"] 	= $objData;
				
				$objDataResult["objCompany"] 		= $objCompany;
				$objDataResult["objLogo"] 			= $objParameter;
				$objDataResult["objCycleStart"]		= $objCycleStart;
				$objDataResult["objCycleEnd"]		= $objCycleEnd;
				$objDataResult["objFirma"] 					= "{companyID:" . $dataSession["user"]->companyID . ",branchID:" . $dataSession["user"]->branchID . ",userID:" . $dataSession["user"]->userID . ",fechaID:" . date('Y-m-d H:i:s') . ",reportID:" . "auxiliar_de_cuenta" . ",ip:". $dataSession["ip_address"] . ",sessionID:" . $dataSession["session_id"] .",agenteID:". $dataSession["user_agent"] .",lastActivity:".  $dataSession["last_activity"] . "}"  ;
				$objDataResult["objFirmaEncription"] 		= md5 ($objDataResult["objFirma"]);
				log_message("INFO",print_r($objDataResult,true));
				$this->load->view("app_account_report/auxiliar_de_cuenta/view_a_disemp",$objDataResult);
				
			}
		}
		catch(Exception $ex){
			show_error($ex->getLine()." ".$ex->getMessage() ,500 );
		}
	}
	function balance_general(){
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
				$dataSession["head"]	= $this->load->view('app_account_report/balance_general/view_head','',true);
				$dataSession["body"]	= $this->load->view('app_account_report/balance_general/view_body',$data,true);
				$dataSession["script"]	= $this->load->view('app_account_report/balance_general/view_script','',true);  
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
				$query			= "CALL pr_accounting_get_report_balance_general('".$companyID."','".$branchID."','".$userID."','".$tocken."','".$periodID."','".$cycleID."');";				
				$objData		= $this->Bd_Model->executeProcedureMultiQuery($query);	

				$objDataResult["objDetailActivo"]			= $objData[0];
				$objDataResult["objDetailPasivo"]			= $objData[1];
				$objDataResult["objDetailCapital"]			= $objData[2];
				$objDataResult["objTotalActivo"]			= $objData[3];
				$objDataResult["objTotalPasivoCapital"]		= $objData[4];
				$objDataResult["objCompany"] 		= $objCompany;
				$objDataResult["objLogo"] 			= $objParameter;
				$objDataResult["objPeriod"]			= $objPeriod;
				$objDataResult["objCycle"]			= $objCycle;
				$objDataResult["objFirma"] 					= "{companyID:" . $dataSession["user"]->companyID . ",branchID:" . $dataSession["user"]->branchID . ",userID:" . $dataSession["user"]->userID . ",fechaID:" . date('Y-m-d H:i:s') . ",reportID:" . "balance_general" . ",ip:". $dataSession["ip_address"] . ",sessionID:" . $dataSession["session_id"] .",agenteID:". $dataSession["user_agent"] .",lastActivity:".  $dataSession["last_activity"] . "}"  ;
				$objDataResult["objFirmaEncription"] 		= md5 ($objDataResult["objFirma"]);
				log_message("INFO",print_r($objDataResult,true));
				$this->load->view("app_account_report/balance_general/view_a_disemp",$objDataResult);
				
			}
		}
		catch(Exception $ex){
			show_error($ex->getLine()." ".$ex->getMessage() ,500 );
		}
	}
	function razon_financial(){
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
				$dataSession["head"]	= $this->load->view('app_account_report/razon_financial/view_head','',true);
				$dataSession["body"]	= $this->load->view('app_account_report/razon_financial/view_body',$data,true);
				$dataSession["script"]	= $this->load->view('app_account_report/razon_financial/view_script','',true);  
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
				$query			= "CALL pr_accounting_get_report_razon_financial('".$companyID."','".$branchID."','".$userID."','".$tocken."','".$periodID."','".$cycleID."');";				
				$objData		= $this->Bd_Model->executeProcedureMultiQuery($query);	

				
				$objDataResult["objCycle"]					= $objCycle;			
				$objDataResult["objDetailRazon"]			= $objData[0];			
				$objDataResult["objDetailIndicadores"]		= $objData[1];
				$objDataResult["objCompany"] 				= $objCompany;
				$objDataResult["objLogo"] 					= $objParameter;
				$objDataResult["objFirma"] 					= "{companyID:" . $dataSession["user"]->companyID . ",branchID:" . $dataSession["user"]->branchID . ",userID:" . $dataSession["user"]->userID . ",fechaID:" . date('Y-m-d H:i:s') . ",reportID:" . "razon_financiera" . ",ip:". $dataSession["ip_address"] . ",sessionID:" . $dataSession["session_id"] .",agenteID:". $dataSession["user_agent"] .",lastActivity:".  $dataSession["last_activity"] . "}"  ;
				$objDataResult["objFirmaEncription"] 		= md5 ($objDataResult["objFirma"]);
				log_message("INFO",print_r($objDataResult,true));
				$this->load->view("app_account_report/razon_financial/view_a_disemp",$objDataResult);
				
			}
		
		}
		catch(Exception $ex){
			show_error($ex->getLine()." ".$ex->getMessage() ,500 );
		}
	}
	function estado_de_resultado(){
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
				$cycleID		= $uri["cycleID"];									$classID		= $uri["classID"];
			} 
			
			if(!($viewReport && $periodID && $cycleID)){
				$this->load->model('Component_Period_Model');				$this->load->model('Center_Cost_Model');
				$data["objListAccountingPeriod"] 	= $this->Component_Period_Model->get_rowByCompany($dataSession["user"]->companyID);				$data["objListCentroCosto"]			= $this->Center_Cost_Model->getByCompany($companyID);
				
				//Renderizar Resultado 
				$dataSession["message"]	= $this->core_web_notification->get_message();
				$dataSession["head"]	= $this->load->view('app_account_report/estado_de_resultado/view_head','',true);
				$dataSession["body"]	= $this->load->view('app_account_report/estado_de_resultado/view_body',$data,true);
				$dataSession["script"]	= $this->load->view('app_account_report/estado_de_resultado/view_script','',true);  
				$dataSession["footer"]	= "";			
				$this->load->view("core_masterpage/default_report",$dataSession);		
			}
			else{		
				//Cargar Libreria
				$this->load->model('core/Company_Model');	
				$this->load->model('core/Bd_Model');
				$this->load->model('Component_Cycle_Model');
				$this->load->model('Component_Period_Model');								$this->load->model('Center_Cost_Model');
				
				$companyID 		= $dataSession["user"]->companyID;
				//Get Component
				$objComponent	= $this->core_web_tools->getComponentIDBy_ComponentName("tb_company");
				//Get Logo
				$objParameter	= $this->core_web_parameter->getParameter("CORE_COMPANY_LOGO",$companyID);
				//Get Company
				$objCompany 	= $this->Company_Model->get_rowByPK($companyID);			
				$objCycle		= $this->Component_Cycle_Model->get_rowByPK($periodID,$cycleID);
				$objPeriod		= $this->Component_Period_Model->get_rowByPK($periodID);								$objCenterCost	= $this->Center_Cost_Model->get_rowByPK($companyID,$classID);
				//Get Datos
				$query			= "CALL pr_accounting_get_report_estado_resultado('".$companyID."','".$branchID."','".$userID."','".$tocken."','".$periodID."','".$cycleID."','".$classID."');";
				$objData		= $this->Bd_Model->executeProcedureMultiQuery($query);	

				$objDataResult["objDetailIngresos"]			= isset($objData[0]) ? $objData[0] : NULL;
				$objDataResult["objDetailCostos"]			= isset($objData[1]) ? $objData[1] : NULL;
				$objDataResult["objDetailGastos"]			= isset($objData[2]) ? $objData[2] : NULL;;
				$objDataResult["objUtilityNeta"]			= $objData[3][0]["valor"];
				$objDataResult["objUtilityMensual"]			= $objData[4][0]["valor"];
				$objDataResult["objCompany"] 		= $objCompany;
				$objDataResult["objLogo"] 			= $objParameter;
				$objDataResult["objPeriod"]			= $objPeriod;
				$objDataResult["objCycle"]			= $objCycle;								$objDataResult["objCenterCost"]		= $objCenterCost;
				$objDataResult["objFirma"] 					= "{companyID:" . $dataSession["user"]->companyID . ",branchID:" . $dataSession["user"]->branchID . ",userID:" . $dataSession["user"]->userID . ",fechaID:" . date('Y-m-d H:i:s') . ",reportID:" . "estado_de_resultado" . ",ip:". $dataSession["ip_address"] . ",sessionID:" . $dataSession["session_id"] .",agenteID:". $dataSession["user_agent"] .",lastActivity:".  $dataSession["last_activity"] . "}"  ;
				$objDataResult["objFirmaEncription"] 		= md5 ($objDataResult["objFirma"]);
				log_message("INFO",print_r($objDataResult,true));
				$this->load->view("app_account_report/estado_de_resultado/view_a_disemp",$objDataResult);
				
			}
		}
		catch(Exception $ex){
			show_error($ex->getLine()." ".$ex->getMessage() ,500 );
		}
	}
	function cash_flow(){
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
				$dataSession["head"]	= $this->load->view('app_account_report/cash_flow/view_head','',true);
				$dataSession["body"]	= $this->load->view('app_account_report/cash_flow/view_body',$data,true);
				$dataSession["script"]	= $this->load->view('app_account_report/cash_flow/view_script','',true);  
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
				$query			= "CALL pr_accounting_get_report_cash_flow('".$companyID."','".$tocken."','".$userID."','".$cycleID."','".$periodID."');";				log_message("ERROR",$query);
				$objData		= $this->Bd_Model->executeProcedureMultiQuery($query);	

				$objDataResult["objDetail"]			= $objData[0];				
				$objDataResult["objCompany"] 		= $objCompany;
				$objDataResult["objLogo"] 			= $objParameter;
				$objDataResult["objPeriod"]			= $objPeriod;
				$objDataResult["objCycle"]			= $objCycle;
				$objDataResult["objFirma"] 					= "{companyID:" . $dataSession["user"]->companyID . ",branchID:" . $dataSession["user"]->branchID . ",userID:" . $dataSession["user"]->userID . ",fechaID:" . date('Y-m-d H:i:s') . ",reportID:" . "estado_de_resultado" . ",ip:". $dataSession["ip_address"] . ",sessionID:" . $dataSession["session_id"] .",agenteID:". $dataSession["user_agent"] .",lastActivity:".  $dataSession["last_activity"] . "}"  ;
				$objDataResult["objFirmaEncription"] 		= md5 ($objDataResult["objFirma"]);
				log_message("INFO",print_r($objDataResult,true));
				$this->load->view("app_account_report/cash_flow/view_a_disemp",$objDataResult);
				
			}
		}
		catch(Exception $ex){
			show_error($ex->getLine()." ".$ex->getMessage() ,500 );
		}
	}
	function presupuestory(){
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
			
			$uri			= $this->uri->uri_to_assoc(3);						
			$viewReport		= false;
			$periodID		= false;
			$cycleID		= false;
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
				$dataSession["head"]	= $this->load->view('app_account_report/presupuestory/view_head','',true);
				$dataSession["body"]	= $this->load->view('app_account_report/presupuestory/view_body',$data,true);
				$dataSession["script"]	= $this->load->view('app_account_report/presupuestory/view_script','',true);  
				$dataSession["footer"]	= "";			
				$this->load->view("core_masterpage/default_report",$dataSession);		
			}
			else{
				//Cargar Libreria
				///////////////////////////
				///////////////////////////
				$this->load->model('core/Company_Model');	
				$this->load->model('core/Bd_Model');
				$this->load->model('Component_Cycle_Model');
				$this->load->model('Component_Period_Model');
				
						
				//Obtener datos
				////////////////////////////
				////////////////////////////
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
				$query			= "CALL pr_accounting_get_report_presupuestory('".$companyID."','".$periodID."','".$cycleID."');";
				$objData		= $this->Bd_Model->executeProcedureMultiQuery($query);					
				$objDataResult["objDetail"] 	= $objData[0];
				$objDataResult["objPeriod"] 	= $objPeriod;
				$objDataResult["objCycle"] 		= $objCycle;
				$objDataResult["objCompany"] 	= $objCompany;
				$objDataResult["objLogo"] 		= $objParameter;
				$objDataResult["objFirma"] 					= "{companyID:" . $dataSession["user"]->companyID . ",branchID:" . $dataSession["user"]->branchID . ",userID:" . $dataSession["user"]->userID . ",fechaID:" . date('Y-m-d H:i:s') . ",reportID:" . "balanza_comprobacion" . ",ip:". $dataSession["ip_address"] . ",sessionID:" . $dataSession["session_id"] .",agenteID:". $dataSession["user_agent"] .",lastActivity:".  $dataSession["last_activity"] . "}"  ;
				$objDataResult["objFirmaEncription"] 		= md5 ($objDataResult["objFirma"]);
				log_message("INFO",print_r($objDataResult,true));
				$this->load->view("app_account_report/presupuestory/view_a_disemp",$objDataResult);
			}
		}
		catch(Exception $ex){
			show_error($ex->getLine()." ".$ex->getMessage() ,500 );
		}
	}
	function balanza_comprobacion(){
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
			
			$uri			= $this->uri->uri_to_assoc(3);						
			$viewReport		= false;
			$periodID		= false;
			$cycleID		= false;
			if($uri != false){
				$viewReport		= $uri["viewReport"];	
				$periodID		= $uri["periodID"];
				$cycleID		= $uri["cycleID"];									$classID		= $uri["classID"];
			} 
			
			if(!($viewReport && $periodID && $cycleID)){
				$this->load->model('Component_Period_Model');				$this->load->model('Center_Cost_Model');
				$data["objListAccountingPeriod"] 	= $this->Component_Period_Model->get_rowByCompany($dataSession["user"]->companyID);				$data["objListCentroCosto"]			= $this->Center_Cost_Model->getByCompany($dataSession["user"]->companyID);
				
				//Renderizar Resultado 
				$dataSession["message"]	= $this->core_web_notification->get_message();
				$dataSession["head"]	= $this->load->view('app_account_report/balanza_de_comprobacion/view_head','',true);
				$dataSession["body"]	= $this->load->view('app_account_report/balanza_de_comprobacion/view_body',$data,true);
				$dataSession["script"]	= $this->load->view('app_account_report/balanza_de_comprobacion/view_script','',true);  
				$dataSession["footer"]	= "";			
				$this->load->view("core_masterpage/default_report",$dataSession);		
			}
			else{
				//Cargar Libreria
				///////////////////////////
				///////////////////////////
				$this->load->model('core/Company_Model');	
				$this->load->model('core/Bd_Model');
				$this->load->model('Component_Cycle_Model');
				$this->load->model('Component_Period_Model');
				
						
				//Obtener datos
				////////////////////////////
				////////////////////////////
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
				$query			= "CALL pr_accounting_get_report_balanza_de_comprobacion('".$companyID."','".$periodID."','".$cycleID."','".$classID."');";
				$objData		= $this->Bd_Model->executeProcedureMultiQuery($query);	
				$objDataResult["objSumary"] 	= isset($objData[1][0]) ? $objData[1][0] : array();
				$objDataResult["objDetail"] 	= isset($objData[0]) ? $objData[0] : array();
				$objDataResult["objPeriod"] 	= $objPeriod;
				$objDataResult["objCycle"] 		= $objCycle;
				$objDataResult["objCompany"] 	= $objCompany;
				$objDataResult["objLogo"] 		= $objParameter;
				$objDataResult["objFirma"] 					= "{companyID:" . $dataSession["user"]->companyID . ",branchID:" . $dataSession["user"]->branchID . ",userID:" . $dataSession["user"]->userID . ",fechaID:" . date('Y-m-d H:i:s') . ",reportID:" . "balanza_comprobacion" . ",ip:". $dataSession["ip_address"] . ",sessionID:" . $dataSession["session_id"] .",agenteID:". $dataSession["user_agent"] .",lastActivity:".  $dataSession["last_activity"] . "}"  ;
				$objDataResult["objFirmaEncription"] 		= md5 ($objDataResult["objFirma"]);
				log_message("INFO",print_r($objDataResult,true));
				$this->load->view("app_account_report/balanza_de_comprobacion/view_a_disemp",$objDataResult);
			}
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
			$dataSession["head"]			= $this->load->view('app_account_report/view_head','',true);
			$dataSession["body"]			= $this->load->view('app_account_report/view_body',$dataMenu,true);
			$dataSession["script"]			= $this->load->view('app_account_report/view_script','',true);  
			$dataSession["footer"]			= "";			
			$this->load->view("core_masterpage/default_masterpage",$dataSession);		
		}
		catch(Exception $ex){
			show_error($ex->getLine()." ".$ex->getMessage() ,500 );
		}
	}	
	
}
?>
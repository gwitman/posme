<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class App_Inventory_Process extends CI_Controller {

    public function __construct() {
       parent::__construct();
    }	
	
	
	
	
	function index($dataViewID = null){	
	try{ 
			$dataSession		= $this->session->all_userdata();
			log_message("ERROR",print_r($dataSession,true));
			
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
			
			$objCompanyParameter 				= $this->core_web_parameter->getParameter("ACCOUNTING_PERIOD_WORKFLOWSTAGECLOSED",$dataSession["user"]->companyID);
			$dataV["objListAccountingPeriod"]	= $this->Component_Period_Model->get_rowByNotClosed($dataSession["user"]->companyID,$objCompanyParameter->value);
			
			
			//Renderizar Resultado 
			$dataSession["notification"]	= $this->core_web_error->get_error($dataSession["user"]->userID);
			$dataSession["message"]			= $this->core_web_notification->get_message();
			$dataSession["head"]			= $this->load->view('app_inventory_process/view_head',$dataV,true);
			$dataSession["body"]			= $this->load->view('app_inventory_process/view_body',$dataV,true);
			$dataSession["script"]			= $this->load->view('app_inventory_process/view_script',$dataV,true);  
			$dataSession["footer"]			= "";			
			$this->load->view("core_masterpage/default_masterpage",$dataSession);		
		}
		catch(Exception $ex){
			show_error($ex->getLine()." ".$ex->getMessage() ,500 );
		}
	}	
	
}
?>
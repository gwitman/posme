<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class App_Accounting_Api extends CI_Controller {
	
    public function __construct() {
       parent::__construct();
    } 
	function getCycle(){
		try{ 
			//AUTENTICACION
			if(!$this->core_web_authentication->isAuthenticated())
			throw new Exception(USER_NOT_AUTENTICATED);
			$dataSession		= $this->session->all_userdata();
			
			//PERMISO SOBRE LA FUNCION
			if(APP_NEED_AUTHENTICATION == true){
				$permited = false;
				$permited = $this->core_web_permission->urlPermited($this->router->class,"index",$this->config->item('url_suffix'),$dataSession["menuTop"],$dataSession["menuLeft"],$dataSession["menuBodyReport"],$dataSession["menuBodyTop"],$dataSession["menuHiddenPopup"]);
				
				if(!$permited)
				throw new Exception(NOT_ACCESS_CONTROL." ".$this->router->class);		
			}			
			
			//Obtener Parametros
			$companyID 				= $dataSession["user"]->companyID;
			$componentPeriodID 		= $this->input->post("componentPeriodID");		
			if((!$companyID) || (!$componentPeriodID)){
					throw new Exception(NOT_PARAMETER);	
			} 
			
			
			$this->load->model('Component_Cycle_Model');			
			$cycles					= $this->Component_Cycle_Model->getByComponentPeriodID($componentPeriodID);
			if($cycles)
			foreach($cycles as $key => $value){
				$value->startOnFormat 	= helper_DateToSpanish($value->startOnFormat,"Y-F");
				$value->endOnFormat 	= helper_DateToSpanish($value->endOnFormat,"Y-F");
				$cycles[$key] = $value;
			}
			
			$this->output->set_content_type('application/json');
			$this->output->set_output(json_encode(array(
				'error'   => false,
				'message' => SUCCESS,
				'cycles'  => $cycles
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
	function getCycleNotClosed(){
		try{ 			log_message("ERROR","obteniendo ciclos contables");			$dataSession = $this->session->all_userdata();			log_message("ERROR",print_r($dataSession,true));						
			//AUTENTICACION
			if(!$this->core_web_authentication->isAuthenticated()){				log_message("ERROR","obteniendo ciclos contables 001.001");
				throw new Exception(USER_NOT_AUTENTICATED);			}			
			$dataSession		= $this->session->all_userdata();			log_message("ERROR","obteniendo ciclos contables 001");
			
			//PERMISO SOBRE LA FUNCION
			if(APP_NEED_AUTHENTICATION == true){
				$permited = false;
				$permited = $this->core_web_permission->urlPermited($this->router->class,"index",$this->config->item('url_suffix'),$dataSession["menuTop"],$dataSession["menuLeft"],$dataSession["menuBodyReport"],$dataSession["menuBodyTop"],$dataSession["menuHiddenPopup"]);
				
				if(!$permited)
				throw new Exception(NOT_ACCESS_CONTROL." ".$this->router->class);		
			}			
			
			//Obtener Parametros
			$companyID 				= $dataSession["user"]->companyID;
			$componentPeriodID 		= $this->input->post("componentPeriodID");		
			if((!$companyID) || (!$componentPeriodID)){
					throw new Exception(NOT_PARAMETER);	
			} 			
			
			
			$this->load->model('Component_Cycle_Model');
			$objCompanyParameter 	= $this->core_web_parameter->getParameter("ACCOUNTING_CYCLE_WORKFLOWSTAGECLOSED",$dataSession["user"]->companyID);
			$cycles					= $this->Component_Cycle_Model->get_rowByNotClosed($dataSession["user"]->companyID,$componentPeriodID,$objCompanyParameter->value);
			if($cycles)
			foreach($cycles as $key => $value){
				$value->startOnFormat 	= helper_DateToSpanish($value->startOnFormat,"Y-F");
				$value->endOnFormat 	= helper_DateToSpanish($value->endOnFormat,"Y-F");
				$cycles[$key] = $value;
			}
			
			
			$this->output->set_content_type('application/json');
			$this->output->set_output(json_encode(array(
				'error'   => false,
				'message' => SUCCESS,
				'cycles'  => $cycles
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
	function getInforDashBoards(){
		try{ 
			//AUTENTICACION
			if(!$this->core_web_authentication->isAuthenticated())
			throw new Exception(USER_NOT_AUTENTICATED);
			$dataSession		= $this->session->all_userdata();
			
			
			//PERMISO SOBRE LA FUNCION
			if(APP_NEED_AUTHENTICATION == true){
				$permited = false;
				$permited = $this->core_web_permission->urlPermited($this->router->class,"index",$this->config->item('url_suffix'),$dataSession["menuTop"],$dataSession["menuLeft"],$dataSession["menuBodyReport"],$dataSession["menuBodyTop"],$dataSession["menuHiddenPopup"]);
				
				if(!$permited)
				throw new Exception(NOT_ACCESS_CONTROL." ".$this->router->class);		
			}			
			
			
			//Obtener Parametros
			$companyID 		= $dataSession["user"]->companyID;
			if((!$companyID)){
					throw new Exception(NOT_PARAMETER);			
					 
			} 
			
			$this->load->model('core/Bd_Model');
			$this->load->model('core/User_Model');
			$this->load->model('Account_Model');
			$this->load->model('Component_Period_Model');
						
			
			$numberUser		= $this->User_Model->getCount($companyID);					
			$numberPeriod	= $this->Component_Period_Model->get_countPeriod($companyID);
			$numberAccount	= $this->Account_Model->get_countAccount($companyID);
			
			$this->output->set_content_type('application/json');
			$this->output->set_output(json_encode(array(
				'error'   => false,
				'message' => SUCCESS,			
				'numberuser'	  	=> $numberUser,
				'numberaccount'	  	=> $numberAccount,
				'numberperiod'	  	=> $numberPeriod
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
	function getHistoryBalanceByAccount(){
		try{ 
			//AUTENTICACION
			if(!$this->core_web_authentication->isAuthenticated())
			throw new Exception(USER_NOT_AUTENTICATED);
			$dataSession		= $this->session->all_userdata();
			
			//PERMISO SOBRE LA FUNCION
			if(APP_NEED_AUTHENTICATION == true){
				$permited = false;
				$permited = $this->core_web_permission->urlPermited($this->router->class,"index",$this->config->item('url_suffix'),$dataSession["menuTop"],$dataSession["menuLeft"],$dataSession["menuBodyReport"],$dataSession["menuBodyTop"],$dataSession["menuHiddenPopup"]);
				
				if(!$permited)
				throw new Exception(NOT_ACCESS_CONTROL." ".$this->router->class);		
			}			
			
			//Obtener Parametros
			$companyID 		= $this->input->post("companyID");
			$accountID 		= $this->input->post("accountID");				
			
			if((!$companyID && !$accountID)){
					throw new Exception(NOT_PARAMETER);			
					 
			} 
			
			$this->load->model('core/Bd_Model');
			$query			= "CALL pr_accounting_get_history_balance_by_account ('".$companyID."','".$accountID."');";			
			$resultQuery	= $this->Bd_Model->executeRender($query);		
			
			$this->output->set_content_type('application/json');
			$this->output->set_output(json_encode(array(
				'error'   => false,
				'message' => SUCCESS,
				'data'	  => $resultQuery
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
	
}
?>
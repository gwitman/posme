<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class App_Inventory_Api extends CI_Controller {
	
    public function __construct() {
       parent::__construct();
    } 

	function generatedTransactionOutputByFormulate(){
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
			$this->load->model('core/Bd_Model');	
			$this->load->model('core/Log_Model');
			
			
			$companyID			= $dataSession["user"]->companyID;
			$branchID 			= $dataSession["user"]->branchID;
			$loginID			= $dataSession["user"]->userID;
			$componentPeriodID	= $this->input->post("componentPeriodID");
			$componentCycleID	= $this->input->post("componentCycleID");
			
			$query				= "
			SET @resultMayorization 	= '0';
			CALL pr_inventory_create_transaction_output_by_formulated('".$companyID."','".$branchID."','".$loginID."','".$componentPeriodID."','".$componentCycleID."',@resultMayorization);
			SELECT @resultMayorization as codigo;
			";
			log_message("ERROR","ejecucion de query");
			log_message("ERROR",$query);
			
			$resultMayorizate						= $this->Bd_Model->executeProcedureMultiQuery($query);	
			log_message("ERROR","ejecucion de query 001");
			log_message("ERROR",print_r($resultMayorizate,true));
			$resultMayorizate						= $this->Log_Model->get_rowByPK($companyID,$branchID,$loginID,'');
			$resultMayorizateTransactionID			= $this->Log_Model->get_rowByNameParameterOutput($companyID,$branchID,$loginID,'','pr_inventory_create_transaction_output_by_formulated_transactionID');
			$resultMayorizateTransactionMasterIDID	= $this->Log_Model->get_rowByNameParameterOutput($companyID,$branchID,$loginID,'','pr_inventory_create_transaction_output_by_formulated_transactionMasterID');
			$resultMayorizateTransactionID 			=  $resultMayorizateTransactionID->description;
			$resultMayorizateTransactionMasterIDID	= $resultMayorizateTransactionMasterIDID->description;
			log_message("ERROR","ejecucion de query 002");
			log_message("ERROR",print_r($resultMayorizate,true));
			log_message("ERROR","ejecucion de query 003");
			log_message("ERROR",print_r($resultMayorizateTransactionID,true));
			log_message("ERROR","ejecucion de query 004");
			log_message("ERROR",print_r($resultMayorizateTransactionMasterIDID,true));
			
			//Ingresar en Kardex.
			$this->core_web_inventory->calculateKardexNewOutput($companyID,$resultMayorizateTransactionID,$resultMayorizateTransactionMasterIDID);			
			
			//Crear Conceptos.
			$this->core_web_concept->otheroutput($companyID,$resultMayorizateTransactionID,$resultMayorizateTransactionMasterIDID);
			
			$this->output->set_content_type('application/json');
			$this->output->set_output(json_encode(array(
				'error'   => false,
				'message' => "SUCCESS",
				'result'  => $resultMayorizate
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
			
			$this->load->model('core/Transaction_Model');
			$this->load->model('Item_Model');
						
			
			$numberItem		= $this->Item_Model->getCount($companyID);					
			$numberInput	= $this->Transaction_Model->getCountInput($companyID);
			$numberOutput	= $this->Transaction_Model->getCountOutput($companyID);
			
			$this->output->set_content_type('application/json');
			$this->output->set_output(json_encode(array(
				'error'   => false,
				'message' => SUCCESS,			
				'numberitem'	  	=> $numberItem,
				'numberinput'	  	=> $numberInput,
				'numberoutput'	  	=> $numberOutput
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
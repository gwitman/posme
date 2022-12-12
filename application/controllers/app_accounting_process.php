<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class App_Accounting_Process extends CI_Controller {

    public function __construct() {
       parent::__construct();
    }	
	function downloadTipoCambio(){
		
		try{ 
		
			/* 
			//AUTENTICADO 
			*/
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
			
			
			
			$this->load->model('Component_Period_Model');
			$this->load->model('Component_Cycle_Model');
			$this->load->model('Company_Currency_Model');			
			$this->load->model('core/ExchangeRate_Model');						
			$this->load->model('core/Currency_Model'); 
			
			
			$companyID			= $dataSession["user"]->companyID;
			$branchID 			= $dataSession["user"]->branchID;
			$loginID			= $dataSession["user"]->userID;			
			$componentPeriodID	= $this->input->post("componentPeriodID");
			$componentCycleID	= $this->input->post("componentCycleID");			
			
			//Obtener el Periodo
			$Cycle			= $this->Component_Cycle_Model->get_rowByPK($componentPeriodID,$componentCycleID);
			$objParameter 	= $this->core_web_parameter->getParameter("CORE_ACCOUNTING_WSDL_DER",$companyID);
			
			//"https://servicios.bcn.gob.ni/Tc_Servicio/ServicioTC.asmx?WSDL"			
			//log_message("INFO",$objParameter->value);			
			//$xml = file_get_contents('https://servicios.bcn.gob.ni/Tc_Servicio/ServicioTC.asmx?WSDL');
			//log_message("INFO",$xml);
			

			$dateInicial 	= new DateTime($Cycle->startOn);
			$dateFinal 		= new DateTime($Cycle->endOn);			
			$client 		= new SoapClient($objParameter->value,[
				"stream_context" => stream_context_create(
				   array( 
					  'ssl' => array(
						   'verify_peer'       => false,
						   'verify_peer_name'  => false,
					  )
				   )
				)
			]);
			
			
			log_message("ERROR","punto de interrupcion fecha incial y final del tipo de cambio");
			log_message("ERROR",print_r($dateInicial,true));
			log_message("ERROR",print_r($dateFinal,true));
			
			$objCurrencySource	= $this->core_web_currency->getCurrencyDefault($companyID);
			$objCurrencyTarget	= $this->core_web_currency->getCurrencyExternal($companyID);		
			$currencyIDSource	= $objCurrencySource->currencyID;
			$currencyIDTarget	= $objCurrencyTarget->currencyID;
			log_message("ERROR",print_r($objCurrencySource,true));
			log_message("ERROR",print_r($objCurrencyTarget,true));
				
			while ($dateInicial <= $dateFinal){
				log_message("ERROR","punto de interrupcion while del download");
				log_message("ERROR",print_r($dateInicial,true));
				$params 				= array(
					"Ano"					=> $dateInicial->format("Y"),
					"Mes" 					=> $dateInicial->format("m"),
					"Dia" 					=> $dateInicial->format("d")
				);
				
				
				//Tipo de Cambio del Dia
				$resultado 		= $client->RecuperaTC_Dia( $params );			
				$excangeRate 	= $resultado->RecuperaTC_DiaResult;
				log_message("ERROR","punto de interrupcion tipo de cambio");
				log_message("ERROR",print_r($excangeRate,true));
				
				
				$objExchangeRate 	= $this->ExchangeRate_Model->get_rowByPK($companyID,$dateInicial->format("Y-m-d"),$currencyIDSource,$currencyIDTarget);								
				if($objExchangeRate){										
					log_message("ERROR","punto de interrupcion actualizar tasa de cambio");
					$data			= NULL;
					$data["ratio"] 	= $excangeRate;
					$this->ExchangeRate_Model->update($companyID,$dateInicial->format("Y-m-d"),$currencyIDSource,$currencyIDTarget,$data);
					
				}
				//Insertar
				else{					
					log_message("ERROR","punto de interrupcion insertar tasa de cambio");
					$data						= NULL;
					$data["companyID"] 			= $companyID;
					$data["date"] 				= $dateInicial->format("Y-m-d");
					$data["currencyID"] 		= $currencyIDSource;
					$data["targetCurrencyID"] 	= $currencyIDTarget;
					$data["ratio"] 				= $excangeRate;					
					$this->ExchangeRate_Model->insert($data);
				}
				
				
				//Siguiente Fecha
				$dateInicial = date_add($dateInicial, date_interval_create_from_date_string('1 days'));
			}
			
			$logDB["code"] = 0;
			$this->output->set_content_type('application/json');
			$this->output->set_output(json_encode(array(
				'error'   => false,
				'message' => "SUCCESS",
				'result'  => $logDB
			)));
			
			
		}
		catch(Exception $ex){
			log_message("ERROR",$ex->getMessage());
			$this->output->set_content_type('application/json');
			$this->output->set_output(json_encode(array(
				'error'   => true,
				'message' => $ex->getLine()." ".$ex->getMessage()
			)));
		}	
			
	}
	function contabilizateDocument(){
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
			$this->load->model('transaction_model');
			
			$companyID				= $dataSession["user"]->companyID;
			$branchID 				= $dataSession["user"]->branchID;
			$loginID				= $dataSession["user"]->userID;
			$transactionID			= $this->input->post("transactionID");			
			$objListTransaction		= $this->transaction_model->getTransactionContabilizable($companyID);
			$app					= "CONTABILIZATE";
			$logDB					= NULL;
			
		
			if($transactionID == 0){
				foreach( $objListTransaction as $item)
				{
					$transactionID			= $item->transactionID;
					$query					= "SET @resultTransaction 	= '0';CALL pr_accounting_transaction_to_journal('".$companyID."','".$branchID."','".$loginID."','".$transactionID."','".$app."',@resultTransaction);SELECT @resultTransaction as codigo;";
					$resultTransaction		= $this->Bd_Model->executeProcedureMultiQuery($query);							
					$logDB					= $this->Log_Model->get_rowByPK($companyID,$branchID,$loginID,$app);
				}
			}					
			else {
				$query					= "SET @resultTransaction 	= '0';CALL pr_accounting_transaction_to_journal('".$companyID."','".$branchID."','".$loginID."','".$transactionID."','".$app."',@resultTransaction);SELECT @resultTransaction as codigo;";
				$resultTransaction		= $this->Bd_Model->executeProcedureMultiQuery($query);							
				$logDB					= $this->Log_Model->get_rowByPK($companyID,$branchID,$loginID,$app);
			}
			
			$this->output->set_content_type('application/json');
			$this->output->set_output(json_encode(array(
				'error'   => false,
				'message' => "SUCCESS",
				'result'  => $logDB
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
	function execNotification(){
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
			$this->load->model('transaction_model');
			
			$companyID				= $dataSession["user"]->companyID;
			$branchID 				= $dataSession["user"]->branchID;
			$loginID				= $dataSession["user"]->userID;
			$notificationName		= $this->input->post("notificationName");			
			$logDB["code"]			= 0;
			
			
			/*TODAS*/			
			if ($notificationName == "currentNotification" || $notificationName == "TODAS")
			{
					$ex = curl_init();
					curl_setopt($ex, CURLOPT_URL, base_url()."app_notification/currentNotification");
					curl_setopt($ex, CURLOPT_HEADER, 0);
					$er = curl_exec($ex);
					$er = curl_close($ex);
			}
			if ($notificationName == "sendEmail" || $notificationName == "TODAS")
			{
				$ex = curl_init();
				curl_setopt($ex, CURLOPT_URL,  base_url()."app_notification/sendEmail");
				curl_setopt($ex, CURLOPT_HEADER, 0);
				$er = curl_exec($ex);
				$er = curl_close($ex);
			}
			if ($notificationName == "fillTipoCambio" || $notificationName == "TODAS")
			{
				$ex = curl_init();
				curl_setopt($ex, CURLOPT_URL,  base_url()."app_notification/fillTipoCambio");
				curl_setopt($ex, CURLOPT_HEADER, 0);
				$er = curl_exec($ex);
				$er = curl_close($ex);
			}
			if ($notificationName == "fillInventarioMinimo" || $notificationName == "TODAS")
			{
				$ex = curl_init();
				curl_setopt($ex, CURLOPT_URL,  base_url()."app_notification/fillInventarioMinimo");
				curl_setopt($ex, CURLOPT_HEADER, 0);
				$er = curl_exec($ex);
				$er = curl_close($ex);
			}
			if ($notificationName == "fillCumpleayo" || $notificationName == "TODAS")
			{
				$ex = curl_init();
				curl_setopt($ex, CURLOPT_URL,  base_url()."app_notification/fillCumpleayo");
				curl_setopt($ex, CURLOPT_HEADER, 0);
				$er = curl_exec($ex);
				$er = curl_close($ex);
			}
			if ($notificationName == "fillCuotaAtrasada" || $notificationName == "TODAS")
			{
				$ex = curl_init();
				curl_setopt($ex, CURLOPT_URL,  base_url()."app_notification/fillCuotaAtrasada");
				curl_setopt($ex, CURLOPT_HEADER, 0);
				$er = curl_exec($ex);
				$er = curl_close($ex);
			}
			
															
			$this->output->set_content_type('application/json');
			$this->output->set_output(json_encode(array(
				'error'   => false,
				'message' => "SUCCESS",
				'result'  => $logDB
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
	function clearNotification(){
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
			$this->load->model('Notification_Model');
			$this->load->model('Error_Model');
			
			$companyID				= $dataSession["user"]->companyID;
			$branchID 				= $dataSession["user"]->branchID;
			$loginID				= $dataSession["user"]->userID;
			$tagID					= $this->input->post("tagID");			
			$logDB["code"]			= 0;
			$data 					= null;
			$data["isRead"]			= 1;
			$data["readOn"]			= date("Y-m-d");

			if($tagID == -1){
				$resultDB 				= $this->Error_Model->updateTagID(1,$companyID,$data);
				$resultDB 				= $this->Error_Model->updateTagID(2,$companyID,$data);
				$resultDB 				= $this->Error_Model->updateTagID(5,$companyID,$data);
				$resultDB 				= $this->Error_Model->deleteByTagID(5,$companyID);
				$resultDB 				= $this->Error_Model->updateTagID(6,$companyID,$data);
				$resultDB 				= $this->Error_Model->updateTagID(7,$companyID,$data);
			}
			else if($tagID <> 5 )
				$resultDB 				= $this->Error_Model->updateTagID($tagID,$companyID,$data);
			else
			{
				$resultDB 				= $this->Error_Model->updateTagID(5,$companyID,$data);
				$resultDB 				= $this->Error_Model->deleteByTagID(5,$companyID);
			} 

															
			$this->output->set_content_type('application/json');
			$this->output->set_output(json_encode(array(
				'error'   => false,
				'message' => "SUCCESS",
				'result'  => $logDB
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
	function mayorizateCycle(){
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
			//$this->load->library("core_web_accounting");
			
			$companyID			= $dataSession["user"]->companyID;
			$branchID 			= $dataSession["user"]->branchID;
			$loginID			= $dataSession["user"]->userID;
			$componentPeriodID	= $this->input->post("componentPeriodID");
			$componentCycleID	= $this->input->post("componentCycleID");
			
			$query				= "
			SET @resultMayorization = '0';
			CALL pr_accounting_mayorizate_cycle('".$companyID."','".$branchID."','".$loginID."','".$componentPeriodID."','".$componentCycleID."',@resultMayorization);
			SELECT @resultMayorization as codigo;
			";
			
			$resultMayorizate	= $this->Bd_Model->executeProcedureMultiQuery($query);	
			$resultMayorizate	= $this->Log_Model->get_rowByPK($companyID,$branchID,$loginID,'');
			//$resultMayorizate	= $this->core_web_accounting->mayorizateCycle($companyID,$branchID,$loginID,$componentPeriodID,$componentCycleID);
			
			
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
	function closedCycle(){
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
			$createdIn			= $dataSession["ip_address"];
			$tocken				= ''; 
			$componentPeriodID	= $this->input->post("componentPeriodID");
			$componentCycleID	= $this->input->post("componentCycleID");
			$query				= "
			SET @resultMessage 	= '';
			SET @resultCode 	= '0';
			CALL pr_accounting_closed_cycle('".$companyID."','".$branchID."','".$loginID."','".$createdIn."','".$tocken."','".$componentPeriodID."','".$componentCycleID."',@resultCode,@resultMessage);
			SELECT @resultMessage as message,@resultCode as codigo;
			";			
			$resultClosed		= $this->Bd_Model->executeProcedureMultiQuery($query);	
			$resultClosed		= $this->Log_Model->get_rowByPK($companyID,$branchID,$loginID,$tocken);
			
			
			$this->output->set_content_type('application/json');
			$this->output->set_output(json_encode(array(
				'error'   => false,
				'message' => "SUCCESS",
				'result'  => $resultClosed
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
			$dataV["objListTransaction"]		= $this->Transaction_Model->getTransactionContabilizable($dataSession["user"]->companyID);
			
			//Renderizar Resultado 
			$dataSession["notification"]	= $this->core_web_error->get_error($dataSession["user"]->userID);
			$dataSession["message"]			= $this->core_web_notification->get_message();
			$dataSession["head"]			= $this->load->view('app_account_process/view_head',$dataV,true);
			$dataSession["body"]			= $this->load->view('app_account_process/view_body',$dataV,true);
			$dataSession["script"]			= $this->load->view('app_account_process/view_script',$dataV,true);  
			$dataSession["footer"]			= "";			
			$this->load->view("core_masterpage/default_masterpage",$dataSession);		
		}
		catch(Exception $ex){
			show_error($ex->getLine()." ".$ex->getMessage() ,500 );
		}
	}	
	
}
?>
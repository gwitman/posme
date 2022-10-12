<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class App_Invoice_Api extends CI_Controller {
	
    public function __construct() {
       parent::__construct();
    }
		function getViewApi($componentid,$fnCallback,$viewname,$filter){		try{  			//Validar Authentication			if(!$this->core_web_authentication->isAuthenticated())			throw new Exception(USER_NOT_AUTENTICATED);			$dataSession		= $this->session->all_userdata(); 								$parameter["{companyID}"]	= $this->session->userdata('user')->companyID;			$viewname 					= urldecode($viewname);						$filter 					= urldecode($filter);			$result 					= $this->core_web_tools->formatParameter($filter);						//log_message('ERROR',"componenete:");			//log_message('ERROR',$componentid);			//log_message('ERROR',$viewname);			//log_message('ERROR',$filter);			if($result)			$parameter 					= array_merge($parameter,$result);			//log_message('ERROR',"componenete 002:");					$dataViewData				= $this->core_web_view->getViewByName($this->session->userdata('user'),$componentid,$viewname,CALLERID_SEARCH,null,$parameter); 									log_message('ERROR',"componenete 003:");			log_message('ERROR',print_r($dataViewData["view_data"],true));			//log_message('ERROR',"componenete 004:");						//Obtener Resultados.			$this->output->set_content_type('application/json');			$this->output->set_output(json_encode(array(				'error'   => false,				'message' => SUCCESS,							'objGridView'	 => $dataViewData["view_data"]			)));					}		catch(Exception $ex){			show_error($ex->getMessage() ,500 );		}	}	
	function getLineByCustomer(){
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
			
			//Cargar Librerias
			$this->load->model("Customer_Model");
			$this->load->model("Customer_Credit_Line_Model");
			
			//Obtener Parametros
			$entityID 		= $this->input->post("entityID","0");
			$companyID 		= $dataSession["user"]->companyID;
			if(!$companyID && !$entityID){
					throw new Exception(NOT_PARAMETER);		
			} 
			
			//Obtener tasa de cambio
			date_default_timezone_set(APP_TIMEZONE); 
			$objCurrencyDolares						= $this->core_web_currency->getCurrencyExternal($companyID);
			$objCurrencyCordoba						= $this->core_web_currency->getCurrencyDefault($companyID);
			$dateOn 								= date("Y-m-d");
			$dateOn 								= date_format(date_create($dateOn),"Y-m-d");
			$exchangeRate 							= $this->core_web_currency->getRatio($companyID,$dateOn,1,$objCurrencyDolares->currencyID,$objCurrencyCordoba->currencyID);
			$parameterCausalTypeCredit 				= $this->core_web_parameter->getParameter("INVOICE_BILLING_CREDIT",$companyID);
			
			//Obtener Cliente
			$objCustomer 					= $this->Customer_Model->get_rowByEntity($companyID,$entityID);
			$branchID 						= ($objCustomer != null ? $objCustomer->branchID : 0);
			//Obtener Lineas de Credito
			$objListCustomerCreditLine2 	= $this->Customer_Credit_Line_Model->get_rowByEntity($companyID,$branchID,$entityID);
			$objListCustomerCreditLine 		= null;
			$counter 						= 0;
			
			if($objListCustomerCreditLine2)
			{
				foreach($objListCustomerCreditLine2 as $key => $value){
					if($value->balance > 0)
					{
						$objListCustomerCreditLine[$counter] = $value;
						$counter++;
					}
				}
			}
			
			//Obtener Resultados.
			$this->output->set_content_type('application/json');
			$this->output->set_output(json_encode(array(
				'error'   => false,
				'message' => SUCCESS,			
				'objCustomer'	  				=> $objCustomer,
				'objListCustomerCreditLine'	  	=> $objListCustomerCreditLine,
				'objExchangeRate'				=> $exchangeRate,
				'objCausalTypeCredit'			=> $parameterCausalTypeCredit,
				'objCurrencyDolares' 			=> $objCurrencyDolares,
				'objCurrencyCordoba' 			=> $objCurrencyCordoba
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
			
			$countInvoice		= $this->core_web_transaction->getCountTransactionBillingAnuladas($companyID);
			$countInvoiceCancel	= $this->core_web_transaction->getCountTransactionBillingCancel($companyID);
			
			
			$this->output->set_content_type('application/json');
			$this->output->set_output(json_encode(array(
				'error'   => false,
				'message' => SUCCESS,			
				'countinvoice'	  			=> $countInvoice,
				'countinvoicecancel'	  	=> $countInvoiceCancel
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
<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class App_Cxc_Api extends CI_Controller {
	
    public function __construct() {
       parent::__construct();
    } 
	
	function getCustomerBalance(){
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
			
			//Redireccionar datos
			$this->load->model("Customer_Credit_Document_Model");
			$customerID		= $this->input->post("customerID");			
			$data 			= $this->Customer_Credit_Document_Model->get_rowByEntityApplied($companyID,$customerID);
			$this->output->set_content_type('application/json');
			$this->output->set_output(json_encode(array(
				'error'   	=> false,
				'message' 	=> SUCCESS,			
				'array'	  	=> $data
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
	function getSimulateAmortization(){
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
			
			//Redireccionar datos
			$plantID					= $this->input->post("plantID");			
			$frecuencyID				= $this->input->post("frecuencyID");			
			$numberPay					= helper_StringToNumber($this->input->post("numberPay",0));
			$interestYear				= helper_StringToNumber($this->input->post("interestYear",0));	
			$amount						= helper_StringToNumber($this->input->post("amount",0));
			$branchID 					= $dataSession["user"]->branchID;
			$roleID 					= $dataSession["role"]->roleID;	
			$companyID					= $dataSession["user"]->companyID;
			$creditMultiplicador		= $this->core_web_parameter->getParameter("CREDIT_INTERES_MULTIPLO",$companyID)->value;
			$interestYear				= $interestYear * $creditMultiplicador;
			
			
			//Cargar Libreria
			$this->load->library("financial/financial_amort");
			$this->load->model("Customer_Credit_Document_Model");
			$this->load->model("Customer_Credit_Amortization_Model");
			$this->load->model("Customer_Credit_Line_Model");
			$this->load->model("Customer_Credit_Model");
			$this->load->model("core/Catalog_Item_Model");
			
			//Obtener catalogo
			$periodPay 				= $this->Catalog_Item_Model->get_rowByCatalogItemID($frecuencyID);
			
			//Crear tabla de amortizacion
			$this->financial_amort->amort(
				$amount, 									/*monto*/
				$interestYear,								/*interes anual*/
				$numberPay,									/*numero de pagos*/	
				$periodPay->sequence,						/*frecuencia de pago en dia*/
				date("Y-m-d"),								/*fecha del credito*/	
				$plantID 									/*tipo de amortizacion*/
			);			
			
			$tableAmortization = $this->financial_amort->getTable();
			$this->output->set_content_type('application/json');
			$this->output->set_output(json_encode(array(
				'error'   	=> false,
				'message' 	=> SUCCESS,			
				'array'	  	=> $tableAmortization
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
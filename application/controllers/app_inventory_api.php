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
			
			$resultMayorizate	= $this->Bd_Model->executeProcedureMultiQuery($query);	
			$resultMayorizate	= $this->Log_Model->get_rowByPK($companyID,$branchID,$loginID,'');
			
			
			
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
<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class core_concept_api extends CI_Controller {

	
    public function __construct() {
       parent::__construct();
    }
	
	
	function index($dataViewID = null){ 
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
				throw new Exception(NOT_ACCESS_CONTROL);
				
				$resultPermission		= $this->core_web_permission->urlPermissionCmd($this->router->class,"index",$this->config->item('url_suffix'),$dataSession,$dataSession["menuTop"],$dataSession["menuLeft"],$dataSession["menuBodyReport"],$dataSession["menuBodyTop"],$dataSession["menuHiddenPopup"]);
				if ($resultPermission 	== PERMISSION_NONE)
				throw new Exception(NOT_ACCESS_FUNCTION);
						
			}				
			
			$this->load->model("Company_Component_Concept_Model");
			$companyID 				= $dataSession["user"]->companyID;
			$componentID 			= $this->input->post("componentID");		
			$componentItemID		= $this->input->post("componentItemID");	
			$name					= $this->input->post("name");	
			
			if((!$companyID) || (!$componentID) || (!$componentItemID)){
					throw new Exception(NOT_PARAMETER);	
			} 
			
			$data = $this->Company_Component_Concept_Model->get_rowByPK($companyID,$componentID,$componentItemID,$name);
			$this->output->set_content_type('application/json');
			$this->output->set_output(json_encode(array(
				'error'   	=> false,
				'message' 	=> SUCCESS,
				'data'  	=> $data
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
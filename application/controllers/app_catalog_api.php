<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class App_Catalog_Api extends CI_Controller {
	
    public function __construct() {
       parent::__construct();
    } 
	function getCatalogItemByState(){
		try{ 
			//AUTENTICACION
			if(!$this->core_web_authentication->isAuthenticated())
			throw new Exception(USER_NOT_AUTENTICATED);
			$dataSession		= $this->session->all_userdata();
			
			
			//Obtener Parametros
			$companyID 				= $dataSession["user"]->companyID;
			$catalogItemID 			= $this->input->post("catalogItemID");		
			if((!$companyID) || (!$catalogItemID)){
					throw new Exception(NOT_PARAMETER);	
			} 
			
			//Obtener todos los departamentos del pais
			$catalogItems = $this->core_web_catalog->getCatalogAllItem_Parent("tb_customer","stateID",$companyID,$catalogItemID);
			
			$this->output->set_content_type('application/json');
			$this->output->set_output(json_encode(array(
				'error'   		=> false,
				'message' 		=> SUCCESS,
				'catalogItems'  => $catalogItems
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
	function getCatalogItemByCity(){
		try{ 
			//AUTENTICACION
			if(!$this->core_web_authentication->isAuthenticated())
			throw new Exception(USER_NOT_AUTENTICATED);
			$dataSession		= $this->session->all_userdata();
			
			
			//Obtener Parametros
			$companyID 				= $dataSession["user"]->companyID;
			$catalogItemID 			= $this->input->post("catalogItemID");		
			if((!$companyID) || (!$catalogItemID)){
					throw new Exception(NOT_PARAMETER);	
			} 
			
			//Obtener todos los departamentos del pais
			$catalogItems = $this->core_web_catalog->getCatalogAllItem_Parent("tb_customer","cityID",$companyID,$catalogItemID);
			
			$this->output->set_content_type('application/json');
			$this->output->set_output(json_encode(array(
				'error'   		=> false,
				'message' 		=> SUCCESS,
				'catalogItems'  => $catalogItems
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
<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class core_testunitary extends CI_Controller {

	//Constructor ...
    public function __construct() {
       parent::__construct();
    }
    
	//INDEX
	////////////////////////////
	function index(){ 
		try{
			
			//Validar Authentication
			if(!$this->core_web_authentication->isAuthenticated())
			throw new Exception('00018 EL USUARIO NO SE HA AUTENTICADO...');
			$dataSession		= $this->session->all_userdata();
			
			
		}
		catch(Exception $ex){
			show_error($ex->getLine()." ".$ex->getMessage() ,500 );
		}	
		
	}
}
?>
<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Http_request_logger {

    public function log_all() {  
        $CI = & get_instance();
		
		$session 	= $CI->session->all_userdata();
		
		$session	= empty($session["user"]) ? "": "companyID:".$session["user"]->companyID.",userID:-->".$session["user"]->userID.",nickName:-->".$session["user"]->nickname."-->";
		log_message('info','*******************************************************************************************************');
		log_message('info','*******************************************************************************************************');
		log_message('info','*******************************************************************************************************');
		log_message('info', 'METHOD 	    --> ' .var_export($session, true).print_r('execute method************************************'.$CI->router->fetch_class().'/'.$CI->router->fetch_method(),true));
        log_message('info', 'GET 		--> ' .var_export($session, true).var_export($CI->input->get(null), true));
        log_message('info', 'POST 		--> ' .var_export($session, true).var_export($CI->input->post(null), true));                
        log_message('info', '$_SERVER 	--> ' .var_export($session, true).var_export($_SERVER, true));
		
    }

}
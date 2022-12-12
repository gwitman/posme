<?php if ( ! defined('BASEPATH')) exit('No se permite el acceso directo al script');
class core_web_notification {
   
   /**********************Variables Estaticas********************/
   /*************************************************************/
   /*************************************************************/
   /*************************************************************/
	private $CI; 
	
	
   /**********************Funciones******************************/
   /*************************************************************/
   /*************************************************************/
   /*************************************************************/
   public function __construct(){		
        $this->CI = & get_instance();
   }
   function set_message($code,$message){ 
	$this->CI->session->set_userdata("errorMessage",$code);
	log_message("error",print_r("punto de interrupcion  set_message**************1",true));
	$this->CI->session->set_userdata("txtMessage"  , $message);
	$this->CI->session->set_userdata("showMessage" ,true);
   }
   function get_message(){
		$messageFill 		= "";
		$data["txtMessage"] = $this->CI->session->userdata("txtMessage");								
		 
		if(!$this->CI->session->userdata("showMessage"))
			$messageFill = "";
		else if(!$this->CI->session->userdata("errorMessage"))
			$messageFill = $this->CI->load->view("core_template/message_app_success",$data,true);					
		else
			$messageFill = $this->CI->load->view("core_template/message_app_error",$data,true);
		
		$this->CI->session->set_userdata("showMessage",false);
		return $messageFill;
   }
}
?>
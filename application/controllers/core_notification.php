<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class core_notification extends CI_Controller {

	
    public function __construct() {
       parent::__construct();
    }
	function save($errorID = null){
		 try{ 
			//AUTENTICACION
			if(!$this->core_web_authentication->isAuthenticated())
			throw new Exception(USER_NOT_AUTENTICATED);
			$dataSession		= $this->session->all_userdata();
			
			//Load Modelos			
			//
			////////////////////////////////////////
			////////////////////////////////////////
			////////////////////////////////////////
			$this->load->model("Error_Model");  
			
			
			$errorID	= $this->input->post("errorID");
			if(!$errorID ){					
				throw new Exception("NO ES POSIBLE MARCAR COMO LEIDO");	 
				return;
			} 
			
			$objError				= $this->Error_Model->get_rowByPK($errorID);
			$data["isRead"]			= 1;
			$data["readOn"]			= date_format(date_create(),"Y-m-d H:i:s");
			$this->Error_Model->update($errorID,$data);
			
			$this->output->set_content_type('application/json');
			$this->output->set_output(json_encode(array(
				'error'   => false,
				'message' => "success"
			)));
		}
		catch(Exception $ex){
			show_error($ex->getLine()." ".$ex->getMessage() ,500 );
		}		
			
	}
	
	function index(){ 
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
			
			$this->load->model("Error_Model");
			$this->load->model('Tag_Model');
		
			//Renderizar Resultado
			$data["objListErrorOblicaciones"]				= $this->Error_Model->get_rowByUserAllAndTagID($dataSession["user"]->userID,$this->Tag_Model->get_rowByName("NOTIFICAR OBLIGACION")->tagID);
			$data["objListErrorPagos"]						= $this->Error_Model->get_rowByUserAllAndTagID($dataSession["user"]->userID,$this->Tag_Model->get_rowByName("NOTIFICAR CUOTA VENCIDA")->tagID);
			$data["objListErrorCumple"]						= $this->Error_Model->get_rowByUserAllAndTagID($dataSession["user"]->userID,$this->Tag_Model->get_rowByName("FELIZ CUMPLE")->tagID);
			$data["objListErrorInventarioMinimo"]			= $this->Error_Model->get_rowByUserAllAndTagID($dataSession["user"]->userID,$this->Tag_Model->get_rowByName("NOTIFICAR INVENTARIO MINIMO")->tagID);
			$data["objListErrorTC"]							= $this->Error_Model->get_rowByUserAllAndTagID($dataSession["user"]->userID,$this->Tag_Model->get_rowByName("NOTIFICAR TIPO DE CAMBIO")->tagID);
			$dataViewRender									= $this->load->view('core_notification/list_body',$data,true);
			
			$dataSession["notification"]	= $this->core_web_error->get_error($dataSession["user"]->userID);
			$dataSession["message"]			= $this->core_web_notification->get_message();
			$dataSession["head"]			= $this->load->view('core_notification/list_head','',true);
			$dataSession["footer"]			= $this->load->view('core_notification/list_footer','',true);
			$dataSession["body"]			= $dataViewRender; 
			$dataSession["script"]			= $this->load->view('core_notification/list_script','',true);
			$this->load->view("core_masterpage/default_masterpage",$dataSession);		
		}
		catch(Exception $ex){
			show_error($ex->getLine()." ".$ex->getMessage() ,500 );
		}
	}
	
	
}
?>
<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class App_Config_Noti extends CI_Controller {
	
    public function __construct() {
       parent::__construct();
    }	
	function edit(){ 
		 try{ 
			//AUTENTICADO		
			if(!$this->core_web_authentication->isAuthenticated())
			throw new Exception(USER_NOT_AUTENTICATED);
			$dataSession		= $this->session->all_userdata();
			
			//PERMISO SOBRE LA FUNCITON
			if(APP_NEED_AUTHENTICATION == true){
						$permited = false;
						$permited = $this->core_web_permission->urlPermited($this->router->class,"index",$this->config->item('url_suffix'),$dataSession["menuTop"],$dataSession["menuLeft"],$dataSession["menuBodyReport"],$dataSession["menuBodyTop"],$dataSession["menuHiddenPopup"]);
						
						if(!$permited)
						throw new Exception(NOT_ACCESS_CONTROL);
						
						$resultPermission		= $this->core_web_permission->urlPermissionCmd($this->router->class,"edit",$this->config->item('url_suffix'),$dataSession,$dataSession["menuTop"],$dataSession["menuLeft"],$dataSession["menuBodyReport"],$dataSession["menuBodyTop"],$dataSession["menuHiddenPopup"]);
						if ($resultPermission 	== PERMISSION_NONE)
						throw new Exception(NOT_ALL_EDIT);			
			
			}	 
			
			//Set Datos			
			//
			////////////////////////////////////////
			////////////////////////////////////////
			////////////////////////////////////////
			$this->load->model("Remember_Model");
			
			
			//Redireccionar datos
			$uri			= $this->uri->uri_to_assoc(3);
			$rememberID		= $uri["rememberID"];	
			$companyID		= $dataSession["user"]->companyID;
			$branchID 		= $dataSession["user"]->branchID;
			$roleID 		= $dataSession["role"]->roleID;			
			if((!$companyID || !$rememberID))
			{ 
				redirect('app_config_noti/add');	
			} 		
			
			
			//Obtener el Registro			
			$datView["objRemember"]					= $this->Remember_Model->get_rowByPK($rememberID);			
			$datView["objListPeriod"]				= $this->core_web_catalog->getCatalogAllItem("tb_remember","period",$companyID);
			$datView["objListWorkflowStage"]		= $this->core_web_workflow->getWorkflowStageByStageInit("tb_remember","statusID",$datView["objRemember"]->statusID,$companyID,$branchID,$roleID);
			
			
			//Renderizar Resultado
			$dataSession["notification"]	= $this->core_web_error->get_error($dataSession["user"]->userID);
			$dataSession["message"]			=  $this->core_web_notification->get_message();
			$dataSession["head"]			= $this->load->view('app_config_noti/edit_head',$datView,true);
			$dataSession["body"]			= $this->load->view('app_config_noti/edit_body',$datView,true);
			$dataSession["script"]			= $this->load->view('app_config_noti/edit_script',$datView,true);  
			$dataSession["footer"]			= "";				
			$this->load->view("core_masterpage/default_masterpage",$dataSession);	
			
		}
		catch(Exception $ex){
			show_error($ex->getLine()." ".$ex->getMessage() ,500 );
		}	
	}
	
	function delete(){
		try{ 
			//AUTENTICADO
			if(!$this->core_web_authentication->isAuthenticated())
			throw new Exception(USER_NOT_AUTENTICATED);
			$dataSession		= $this->session->all_userdata();
			
			//PERMISO SOBRE LA FUNCTION
			if(APP_NEED_AUTHENTICATION == true){
						$permited = false;
						$permited = $this->core_web_permission->urlPermited($this->router->class,"index",$this->config->item('url_suffix'),$dataSession["menuTop"],$dataSession["menuLeft"],$dataSession["menuBodyReport"],$dataSession["menuBodyTop"],$dataSession["menuHiddenPopup"]);
						
						if(!$permited)
						throw new Exception(NOT_ACCESS_CONTROL);
						
						$resultPermission		= $this->core_web_permission->urlPermissionCmd($this->router->class,"delete",$this->config->item('url_suffix'),$dataSession,$dataSession["menuTop"],$dataSession["menuLeft"],$dataSession["menuBodyReport"],$dataSession["menuBodyTop"],$dataSession["menuHiddenPopup"]);
						if ($resultPermission 	== PERMISSION_NONE)
						throw new Exception(NOT_ALL_DELETE);			
			
			}	
			//Load Modelos
			//
			////////////////////////////////////////
			////////////////////////////////////////
			////////////////////////////////////////
			$this->load->model("Remember_Model");
			
			//Nuevo Registro
			$rememberID 		= $this->input->post("rememberID");				
			
			if((!$rememberID)){
					throw new Exception(NOT_PARAMETER);			
					 
			} 			
		
			//Eliminar el Registro
			$this->Remember_Model->delete($rememberID);
					
			$this->output->set_content_type('application/json');
			$this->output->set_output(json_encode(array(
				'error'   => false,
				'message' => SUCCESS
			)));
			
			
		}
		catch(Exception $ex){
			$this->output->set_content_type('application/json');
			$this->output->set_output(json_encode(array(
				'error'   => true,
				'message' => $ex->getLine()." ".$ex->getMessage()
			)));
			$this->core_web_notification->set_message(true,$ex->getLine()." ".$ex->getMessage());
		}		
			
	}
	
	function save($method = NULL){
		 try{ 
			//AUTENTICADO
			if(!$this->core_web_authentication->isAuthenticated())
			throw new Exception(USER_NOT_AUTENTICATED);
			$dataSession		= $this->session->all_userdata();
			
			//Load Modelos			
			//
			////////////////////////////////////////
			////////////////////////////////////////
			////////////////////////////////////////	
			$this->load->model("Remember_Model");  			
			//Validar Formulario						
			$this->form_validation->set_rules("txtTitulo","title","required");    
			
			 
			//Nuevo Registro			
			$continue	= true;
			if( $method == "new" && $this->form_validation->run() == true ){
					
					//PERMISO SOBRE LA FUNCTION
					if(APP_NEED_AUTHENTICATION == true){
						$permited = false;
						$permited = $this->core_web_permission->urlPermited($this->router->class,"index",$this->config->item('url_suffix'),$dataSession["menuTop"],$dataSession["menuLeft"],$dataSession["menuBodyReport"],$dataSession["menuBodyTop"],$dataSession["menuHiddenPopup"]);
						
						if(!$permited)
						throw new Exception(NOT_ACCESS_CONTROL);
						
						$resultPermission		= $this->core_web_permission->urlPermissionCmd($this->router->class,"add",$this->config->item('url_suffix'),$dataSession,$dataSession["menuTop"],$dataSession["menuLeft"],$dataSession["menuBodyReport"],$dataSession["menuBodyTop"],$dataSession["menuHiddenPopup"]);
						if ($resultPermission 	== PERMISSION_NONE)
						throw new Exception(NOT_ALL_INSERT);			
					
					}					
					
					//Ingresar Currency
					if($continue){
						$this->db->trans_begin();
						//Crear Cuenta
						$obj["companyID"]					= $dataSession["user"]->companyID;
						$obj["title"] 						= $this->input->post("txtTitulo");
						$obj["period"] 						= $this->input->post("txtPeriodID");
						$obj["day"] 						= $this->input->post("txtDias");
						$obj["statusID"] 					= $this->input->post("txtStatusID");
						$obj["isTemporal"] 					= $this->input->post("txtIsTemporal");
						$obj["description"] 				= $this->input->post("txtDescripcion");
						$obj["lastNotificationOn"]			= date('Y-m-d');
						$obj["isActive"]					= 1;
						$this->core_web_auditoria->setAuditCreated($obj,$dataSession);
						$rememberID							= $this->Remember_Model->insert($obj);
						
						if($this->db->trans_status() !== false){
							$this->db->trans_commit();
							$this->core_web_notification->set_message(false,SUCCESS);
							redirect('app_config_noti/edit/rememberID/'.$rememberID);						
						}
						else{
							$this->db->trans_rollback();						
							$this->core_web_notification->set_message(true,$this->db->_error_message());
							redirect('app_config_noti/add');	
						}
					}
					else{
						redirect('app_config_noti/add');	
					}
					
					 
			} 
			//Editar Registro
			else if( $this->form_validation->run() == true) {
					//PERMISO SOBRE LA FUNCTION
					if(APP_NEED_AUTHENTICATION == true){
						$permited = false;
						$permited = $this->core_web_permission->urlPermited($this->router->class,"index",$this->config->item('url_suffix'),$dataSession["menuTop"],$dataSession["menuLeft"],$dataSession["menuBodyReport"],$dataSession["menuBodyTop"],$dataSession["menuHiddenPopup"]);
						
						if(!$permited)
						throw new Exception(NOT_ACCESS_CONTROL);
						
						$resultPermission		= $this->core_web_permission->urlPermissionCmd($this->router->class,"edit",$this->config->item('url_suffix'),$dataSession,$dataSession["menuTop"],$dataSession["menuLeft"],$dataSession["menuBodyReport"],$dataSession["menuBodyTop"],$dataSession["menuHiddenPopup"]);
						if ($resultPermission 	== PERMISSION_NONE)
						throw new Exception(NOT_ALL_EDIT);			
					
					}	 
					 
					
					
					if($continue){
						$this->db->trans_begin();
						
						//Actualizar Rol
						$rememberID							= $this->input->post("txtRememberID");
						$obj["title"] 						= $this->input->post("txtTitulo");
						$obj["period"] 						= $this->input->post("txtPeriodID");
						$obj["day"] 						= $this->input->post("txtDias");
						$obj["statusID"] 					= $this->input->post("txtStatusID");
						$obj["isTemporal"] 					= $this->input->post("txtIsTemporal");
						$obj["description"] 				= $this->input->post("txtDescripcion");
						$result 			= $this->Remember_Model->update($rememberID,$obj);						
						
						if($this->db->trans_status() !== false){
							$this->db->trans_commit();
							$this->core_web_notification->set_message(false,SUCCESS);
						}
						else{
							$this->db->trans_rollback();						
							$this->core_web_notification->set_message(true,$this->db->_error_message());
						}
						redirect('app_config_noti/edit/rememberID/'.$rememberID);
					}					
					else{
						redirect('app_config_noti/add');	
					}
			}  
			else{
				$stringValidation = str_replace("\n","",validation_errors());								
				$this->core_web_notification->set_message(true,$stringValidation);
				redirect('app_config_noti/add');	
			} 
			
		}
		catch(Exception $ex){
			show_error($ex->getLine()." ".$ex->getMessage() ,500 );
		}		
			
	}
	
	function add(){ 
	
		try{ 
			//AUTENTICADO
			if(!$this->core_web_authentication->isAuthenticated())
			throw new Exception(USER_NOT_AUTENTICATED);
			$dataSession		= $this->session->all_userdata();
			
			//PERMISO SOBRE LA FUNCTION
			if(APP_NEED_AUTHENTICATION == true){
						$permited = false;
						$permited = $this->core_web_permission->urlPermited($this->router->class,"index",$this->config->item('url_suffix'),$dataSession["menuTop"],$dataSession["menuLeft"],$dataSession["menuBodyReport"],$dataSession["menuBodyTop"],$dataSession["menuHiddenPopup"]);
						
						if(!$permited)
						throw new Exception(NOT_ACCESS_CONTROL);
						
						$resultPermission		= $this->core_web_permission->urlPermissionCmd($this->router->class,"add",$this->config->item('url_suffix'),$dataSession,$dataSession["menuTop"],$dataSession["menuLeft"],$dataSession["menuBodyReport"],$dataSession["menuBodyTop"],$dataSession["menuHiddenPopup"]);
						if ($resultPermission 	== PERMISSION_NONE)
						throw new Exception(NOT_ALL_INSERT);			
			 
			}	  			
			
			//Obtener el componente Para mostrar la lista de CompanyCurrency
			$objComponent					= $this->core_web_tools->getComponentIDBy_ComponentName("tb_remember");
			if(!$objComponent)
			throw new Exception("00409 EL COMPONENTE 'tb_remember' NO EXISTE...");
			$dataView["component"]			= $objComponent;
			
			//Renderizar Resultado 
			$companyID 							= $dataSession["user"]->companyID;
			$branchID 							= $dataSession["user"]->branchID;
			$roleID 							= $dataSession["role"]->roleID;
			
			$dataView["objListPeriod"]			= $this->core_web_catalog->getCatalogAllItem("tb_remember","period",$companyID);
			$dataView["objListWorkflowStage"]	= $this->core_web_workflow->getWorkflowInitStage("tb_remember","statusID",$companyID,$branchID,$roleID);
			
			$dataSession["notification"]		= $this->core_web_error->get_error($dataSession["user"]->userID);
			$dataSession["message"]				= $this->core_web_notification->get_message();
			$dataSession["head"]				= $this->load->view('app_config_noti/news_head',$dataView,true);
			$dataSession["body"]				= $this->load->view('app_config_noti/news_body',$dataView,true);
			$dataSession["script"]				= $this->load->view('app_config_noti/news_script',$dataView,true);  
			$dataSession["footer"]				= "";
			$this->load->view("core_masterpage/default_masterpage",$dataSession);	
			
		}
		catch(Exception $ex){
			show_error($ex->getLine()." ".$ex->getMessage() ,500 );
		}	
			
    }
	
	function index($dataViewID = null){	
	try{ 
		
			//AUTENTICADO
			if(!$this->core_web_authentication->isAuthenticated())
			throw new Exception(USER_NOT_AUTENTICATED);
			$dataSession		= $this->session->all_userdata();
		
			//PERMISO SOBRE LA FUNCTION
			if(APP_NEED_AUTHENTICATION == true){				
				
						$permited = false;
						$permited = $this->core_web_permission->urlPermited($this->router->class,"index",$this->config->item('url_suffix'),$dataSession["menuTop"],$dataSession["menuLeft"],$dataSession["menuBodyReport"],$dataSession["menuBodyTop"],$dataSession["menuHiddenPopup"]);
						
						if(!$permited)
						throw new Exception(NOT_ACCESS_CONTROL);
						
						$resultPermission		= $this->core_web_permission->urlPermissionCmd($this->router->class,"index",$this->config->item('url_suffix'),$dataSession,$dataSession["menuTop"],$dataSession["menuLeft"],$dataSession["menuBodyReport"],$dataSession["menuBodyTop"],$dataSession["menuHiddenPopup"]);
						if ($resultPermission 	== PERMISSION_NONE)
						throw new Exception(NOT_ACCESS_FUNCTION);			

			}				
			
			
			//Obtener el componente Para mostrar la lista de CompanyCurrency
			$objComponent		= $this->core_web_tools->getComponentIDBy_ComponentName("tb_remember");
			if(!$objComponent)
			throw new Exception("00409 EL COMPONENTE 'tb_remember' NO EXISTE...");
			
			
			//Vista por defecto 
			if($dataViewID == null){				
				$targetComponentID			= 0;	
				$parameter["{companyID}"]	= $this->session->userdata('user')->companyID;
				$dataViewData				= $this->core_web_view->getViewDefault($this->session->userdata('user'),$objComponent->componentID,CALLERID_LIST,$targetComponentID,$resultPermission,$parameter);			
				$dataViewRender				= $this->core_web_view->renderGreed($dataViewData,'ListView',"fnTableSelectedRow");
			}
			//Otra vista
			else{									
				$parameter["{companyID}"]	= $this->session->userdata('user')->companyID;
				$dataViewData				= $this->core_web_view->getViewBy_DataViewID($this->session->userdata('user'),$objComponent->componentID,$dataViewID,CALLERID_LIST,$resultPermission,$parameter); 			
				$dataViewRender				= $this->core_web_view->renderGreed($dataViewData,'ListView',"fnTableSelectedRow");
			}			 
			
			
			//Renderizar Resultado
			$dataSession["notification"]	= $this->core_web_error->get_error($dataSession["user"]->userID);
			$dataView["componentID"]		= $objComponent->componentID;
			$dataSession["message"]			= $this->core_web_notification->get_message();
			$dataSession["head"]			= $this->load->view('app_config_noti/list_head',$dataView,true);
			$dataSession["footer"]			= $this->load->view('app_config_noti/list_footer','',true);
			$dataSession["body"]			= $dataViewRender; 
			$dataSession["script"]			= $this->load->view('app_config_noti/list_script','',true).$this->core_web_javascript->createVar("componentID",$objComponent->componentID);  
			$this->load->view("core_masterpage/default_masterpage",$dataSession);		
		}
		catch(Exception $ex){
			show_error($ex->getLine()." ".$ex->getMessage() ,500 );
		}
	}	
	
}
?>
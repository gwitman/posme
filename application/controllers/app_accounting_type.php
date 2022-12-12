<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class App_Accounting_Type extends CI_Controller {
	
    public function __construct() {
       parent::__construct();
    }
    public function field_naturaleza_check($str){
    	if (!($str == 'D' || $str == "C"))
		{
			$this->form_validation->set_message('field_naturaleza_check', 'The %s Son permitidos los Valores C|D');
			return FALSE;
		}
    } 
	function edit(){ 
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
				
				$resultPermission		= $this->core_web_permission->urlPermissionCmd($this->router->class,"edit",$this->config->item('url_suffix'),$dataSession,$dataSession["menuTop"],$dataSession["menuLeft"],$dataSession["menuBodyReport"],$dataSession["menuBodyTop"],$dataSession["menuHiddenPopup"]);
				if ($resultPermission 	== PERMISSION_NONE)
				throw new Exception(NOT_ALL_EDIT);			
			}	
			
			//Set Datos			
			//
			////////////////////////////////////////
			////////////////////////////////////////
			////////////////////////////////////////
			$this->load->model("Account_Type_Model"); 			
			$this->load->model("core/User_Permission_Model");
			$this->load->model("core/Role_Autorization_Model");
			
			
			//Redireccionar datos
			$uri			= $this->uri->uri_to_assoc(3);
						
			$companyID		= $uri["companyID"];
			$accountTypeID	= $uri["accountTypeID"];	
			$branchID 		= $dataSession["user"]->branchID;
			$roleID 		= $dataSession["role"]->roleID;			
			if((!$companyID || !$accountTypeID))
			{ 
				redirect('app_accounting_type/add');	
			} 		
			
			
			//Obtener el Registro			
			$datView["objAccountType"]	 			= $this->Account_Type_Model->get_rowByPK($companyID,$accountTypeID);
			//Obtener los Permisos Core
			$datView["objUserPermission"]			= $this->User_Permission_Model->get_rowByCompanyIDyBranchIDyRoleID($companyID,$branchID,$roleID);
			//Obtener las Autorization Core
			$datView["listComponentAutoriation"]	= $this->Role_Autorization_Model->get_rowByRoleAutorization($companyID,$branchID,$roleID);
			
			
			//Renderizar Resultado
			$dataSession["notification"]	= $this->core_web_error->get_error($dataSession["user"]->userID);
			$dataSession["message"]			= $this->core_web_notification->get_message();
			$dataSession["head"]			= $this->load->view('app_account_type/edit_head',$datView,true);
			$dataSession["body"]			= $this->load->view('app_account_type/edit_body',$datView,true);
			$dataSession["script"]			= $this->load->view('app_account_type/edit_script',$datView,true);  
			$dataSession["footer"]			= "";				
			$this->load->view("core_masterpage/default_masterpage",$dataSession);	
			
		}
		catch(Exception $ex){
			show_error($ex->getLine()." ".$ex->getMessage() ,500 );
		}	
	}
	function delete(){
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
				
				$resultPermission		= $this->core_web_permission->urlPermissionCmd($this->router->class,"delete",$this->config->item('url_suffix'),$dataSession,$dataSession["menuTop"],$dataSession["menuLeft"],$dataSession["menuBodyReport"],$dataSession["menuBodyTop"],$dataSession["menuHiddenPopup"]);
				if ($resultPermission 	== PERMISSION_NONE)
				throw new Exception(NOT_ALL_DELETE);			
			}	
			
			//Load Modelos
			//
			////////////////////////////////////////
			////////////////////////////////////////
			////////////////////////////////////////
			$this->load->model("Account_Type_Model");  
			
			//Nuevo Registro
			$companyID 		= $this->input->post("companyID");
			$accountTypeID 	= $this->input->post("accountTypeID");				
			
			if((!$companyID && !$accountTypeID)){
					throw new Exception(NOT_PARAMETER);			
					 
			} 
			
			$obj 			= $this->Account_Type_Model->get_rowByPK($companyID,$accountTypeID);	
			//PERMISO SOBRE EL REGISTRO
			if ($resultPermission 	== PERMISSION_ME && ($obj->createdBy != $dataSession["user"]->userID))
			throw new Exception(NOT_DELETE);
			
			//VALIDAR SI EL REGISTRO NO ESTA SIENDO USADO EN UNA CUENTA DE LA EMPREA
			$resultTemp = $this->Account_Type_Model->get_countInAccount($companyID,$accountTypeID);
			if($resultTemp > 0 ){
			throw new Exception("EL REGISTRO ESTA EN USO, NO PUEDE SER ELIMINADO");
			}
			
			//Eliminar el Registro
			$this->Account_Type_Model->delete($companyID,$accountTypeID);
					
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
			//AUTENTICACION
			if(!$this->core_web_authentication->isAuthenticated())
			throw new Exception(USER_NOT_AUTENTICATED);
			$dataSession		= $this->session->all_userdata();
			
			
			//Load Modelos			
			//
			////////////////////////////////////////
			////////////////////////////////////////
			////////////////////////////////////////
			$this->load->model("Account_Type_Model"); 			  
					
			
			//Validar Formulario						
			$this->form_validation->set_rules("txtName","Nombre","required");    
			$this->form_validation->set_rules("txtNaturaleza","Naturaleza","callback_field_naturaleza_check");
			
			 
			//Nuevo Registro			
			$continue	= true;
			if( $method == "new" && $this->form_validation->run() == true ){
					
					//PERMISO SOBRE LA FUNCION
					if(APP_NEED_AUTHENTICATION == true){
						$permited = false;
						$permited = $this->core_web_permission->urlPermited($this->router->class,"index",$this->config->item('url_suffix'),$dataSession["menuTop"],$dataSession["menuLeft"],$dataSession["menuBodyReport"],$dataSession["menuBodyTop"],$dataSession["menuHiddenPopup"]);
						
						if(!$permited)
						throw new Exception(NOT_ACCESS_CONTROL);
						
						$resultPermission		= $this->core_web_permission->urlPermissionCmd($this->router->class,"add",$this->config->item('url_suffix'),$dataSession,$dataSession["menuTop"],$dataSession["menuLeft"],$dataSession["menuBodyReport"],$dataSession["menuBodyTop"],$dataSession["menuHiddenPopup"]);
						if ($resultPermission 	== PERMISSION_NONE)
						throw new Exception(NOT_ALL_INSERT);			
					
					}					
					
					//Ingresar Cuenta
					if($continue){
						$this->db->trans_begin();
						//Crear Cuenta
						$obj["companyID"]			= $dataSession["user"]->companyID;
						$obj["name"] 				= $this->input->post("txtName");
						$obj["naturaleza"] 			= $this->input->post("txtNaturaleza");
						$obj["description"] 		= $this->input->post("txtDescription");				 
						$obj["isActive"] 			= true;
						$this->core_web_auditoria->setAuditCreated($obj,$dataSession);
						
						$accountTypeID				= $this->Account_Type_Model->insert($obj);
						$companyID 					= $obj["companyID"];
						
						if($this->db->trans_status() !== false){
							$this->db->trans_commit();						
							$this->core_web_notification->set_message(false,SUCCESS);
							redirect('app_accounting_type/edit/companyID/'.$companyID."/accountTypeID/".$accountTypeID);						
						}
						else{
							$this->db->trans_rollback();						
							$this->core_web_notification->set_message(true,$this->db->_error_message());
							redirect('app_accounting_type/add');	
						}
					}
					else{
						redirect('app_accounting_type/add');	
					}
					
					 
			} 
			//Editar Registro
			else if( $this->form_validation->run() == true) {
					//PERMISO SOBRE LA FUNCION
					if(APP_NEED_AUTHENTICATION == true){
						$permited = false;
						$permited = $this->core_web_permission->urlPermited($this->router->class,"index",$this->config->item('url_suffix'),$dataSession["menuTop"],$dataSession["menuLeft"],$dataSession["menuBodyReport"],$dataSession["menuBodyTop"],$dataSession["menuHiddenPopup"]);
						
						if(!$permited)
						throw new Exception(NOT_ACCESS_CONTROL);
						
						$resultPermission		= $this->core_web_permission->urlPermissionCmd($this->router->class,"edit",$this->config->item('url_suffix'),$dataSession,$dataSession["menuTop"],$dataSession["menuLeft"],$dataSession["menuBodyReport"],$dataSession["menuBodyTop"],$dataSession["menuHiddenPopup"]);
						if ($resultPermission 	== PERMISSION_NONE)
						throw new Exception(NOT_ALL_EDIT);			
					}	 
					
					//PERMISO SOBRE EL REGISTRO
					$companyID 			= $this->input->post("txtCompanyID");
					$accountTypeID 		= $this->input->post("txtAccountTypeID");
					$objOld = $this->Account_Type_Model->get_rowByPK($companyID,$accountTypeID);
					if ($resultPermission 	== PERMISSION_ME && ($objOld->createdBy != $dataSession["user"]->userID))
					throw new Exception(NOT_EDIT);
			
			
					if($continue){
						$this->db->trans_begin();
						
						//Actualizar Rol
						$companyID 			= $this->input->post("txtCompanyID");
						$accountTypeID 		= $this->input->post("txtAccountTypeID");
						$obj["name"] 		= $this->input->post("txtName");
						$obj["description"] = $this->input->post("txtDescription");
						$obj["naturaleza"] 	= $this->input->post("txtNaturaleza");
						$result 			= $this->Account_Type_Model->update($companyID,$accountTypeID,$obj);
					
						if($this->db->trans_status() !== false){
							$this->db->trans_commit();
							$this->core_web_notification->set_message(false,SUCCESS);
						}
						else{
							$this->db->trans_rollback();						
							$this->core_web_notification->set_message(true,$this->db->_error_message());
						}
						redirect('app_accounting_type/edit/companyID/'.$companyID."/accountTypeID/".$accountTypeID);
					}					
					else{
						redirect('app_accounting_type/add');	
					}
			}  
			else{
				$stringValidation = str_replace("\n","",validation_errors());								
				$this->core_web_notification->set_message(true,$stringValidation);
				redirect('app_accounting_type/add');	
			} 
			
		}
		catch(Exception $ex){
			show_error($ex->getLine()." ".$ex->getMessage() ,500 );
		}		
			
	}
	function add(){ 
	
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
				
				$resultPermission		= $this->core_web_permission->urlPermissionCmd($this->router->class,"add",$this->config->item('url_suffix'),$dataSession,$dataSession["menuTop"],$dataSession["menuLeft"],$dataSession["menuBodyReport"],$dataSession["menuBodyTop"],$dataSession["menuHiddenPopup"]);
				if ($resultPermission 	== PERMISSION_NONE)
				throw new Exception(NOT_ALL_INSERT);			
			
			}	
			
			//Renderizar Resultado
			$dataSession["notification"]	= $this->core_web_error->get_error($dataSession["user"]->userID);
			$dataSession["message"]			= $this->core_web_notification->get_message();
			$dataSession["head"]			= $this->load->view('app_account_type/news_head','',true);
			$dataSession["body"]			= $this->load->view('app_account_type/news_body','',true);
			$dataSession["script"]			= $this->load->view('app_account_type/news_script','',true);  
			$dataSession["footer"]			= "";
			$this->load->view("core_masterpage/default_masterpage",$dataSession);	
			
		}
		catch(Exception $ex){
			show_error($ex->getLine()." ".$ex->getMessage() ,500 );
		}	
			
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
			
			//Obtener el componente Para mostrar la lista de AccountType
			$objComponent		= $this->core_web_tools->getComponentIDBy_ComponentName("tb_account_type");
			if(!$objComponent)
			throw new Exception("00409 EL COMPONENTE 'AccountType' NO EXISTE...");
			
			
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
			$dataSession["message"]			= $this->core_web_notification->get_message();
			$dataSession["head"]			= $this->load->view('app_account_type/list_head','',true);
			$dataSession["footer"]			= $this->load->view('app_account_type/list_footer','',true);
			$dataSession["body"]			= $dataViewRender; 
			$dataSession["script"]			= $this->load->view('app_account_type/list_script','',true).$this->core_web_javascript->createVar("componentID",$objComponent->componentID);  
			$this->load->view("core_masterpage/default_masterpage",$dataSession);		
		}
		catch(Exception $ex){
			show_error($ex->getLine()." ".$ex->getMessage() ,500 );
		}
	}	
	
}
?>
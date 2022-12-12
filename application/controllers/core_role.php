<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class core_role extends CI_Controller {

	
    public function __construct() {
       parent::__construct();
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
			$this->load->model("core/Role_Model");  
			
			//Nuevo Registro
			$companyID 	= $this->input->post("companyID");
			$branchID 	= $this->input->post("branchID");
			$roleID		= $this->input->post("roleID");	
			
			if((!$companyID && !$branchID   && !$roleID)){
					throw new Exception(NOT_PARAMETER);			
					 
			} 
			
			$obj 			= $this->Role_Model->get_rowByPK($companyID,$branchID,$roleID);			
			$obj->isActive	= false;
			
			//PERMISO SOBRE EL REGISTRO
			if ($resultPermission 	== PERMISSION_ME && ($obj->createdBy != $dataSession["user"]->userID))
			throw new Exception(NOT_DELETE);
			
			
			$obj			= (array)$obj;			
			$result 		= $this->Role_Model->update($companyID,$branchID,$roleID,$obj);
					
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
	function save(){
		 try{ 
			//AUTENTICACION			
			if(!$this->core_web_authentication->isAuthenticated())
			throw new Exception(USER_NOT_AUTENTICATED);
			$dataSession		= $this->session->all_userdata();
			
			
			
			//Set Datos			
			//
			////////////////////////////////////////
			////////////////////////////////////////
			////////////////////////////////////////
			$this->load->model("core/Role_Model");  
			$this->load->model("core/User_Permission_Model");  
			$this->load->model("core/Role_Autorization_Model");
					
			
			//Validar Formulario			
			//Datos Requerido para que el Usuario pueda ser Seleccionado
			$this->form_validation->set_rules("txtName","Nombre ","required");    
			$this->form_validation->set_rules("txtUrlDefault","Url Default ","required");    
			$this->form_validation->set_rules("txtDescription","Descripcion ","required");    			
						
			
			 
			//Nuevo Registro
			$companyID 	= $this->input->post("companyID");
			$branchID 	= $this->input->post("branchID");
			$roleID		= $this->input->post("roleID");		
			if((!$companyID && !$branchID   && !$roleID) && $this->form_validation->run() == true ){

					
					//PERMISO SOBRE LA FUNCION
					if(APP_NEED_AUTHENTICATION == true){
							$permited = false;
							$permited = $this->core_web_permission->urlPermited($this->router->class,"index",$this->config->item('url_suffix'),$dataSession["menuTop"],$dataSession["menuLeft"],$dataSession["menuBodyReport"],$dataSession["menuBodyTop"],$dataSession["menuHiddenPopup"]);
							
							if(!$permited)
							throw new Exception(NOT_ACCESS_CONTROL);
							
							$resultPermission		= $this->core_web_permission->urlPermissionCmd($this->router->class,"add",$this->config->item('url_suffix'),$dataSession,$dataSession["menuTop"],$dataSession["menuLeft"],$dataSession["menuBodyReport"],$dataSession["menuBodyTop"],$dataSession["menuHiddenPopup"]);
							if ($resultPermission 	== PERMISSION_NONE)
							throw new Exception(NOT_ACCESS_FUNCTION);			
						
					}	 					
					
					$this->db->trans_begin();
					//Crear Rol
					$obj["companyID"]		= $dataSession["user"]->companyID;					
					$obj["branchID"] 		= $dataSession["user"]->branchID;					
					$obj["name"] 			= $this->input->post("txtName");
					$obj["description"] 	= $this->input->post("txtDescription");
					$obj["urlDefault"] 		= $this->input->post("txtUrlDefault");
					$obj["isAdmin"] 		= false;
					$obj["isActive"] 		= true;		
					$obj["createdBy"] 		= $dataSession["user"]->userID;
					$roleID 				= $this->Role_Model->insert($obj);
					$companyID 				= $obj["companyID"];
					$branchID 				= $obj["branchID"];
					 
					//Eliminar Elementos
					$this->User_Permission_Model->delete_ByRole($companyID,$branchID,$roleID);
					
					//Insert Elementos 
					$elementIDArray			= $this->input->post("txtElementID");
					$txtSelectedIDArray		= $this->input->post("txtSelectedID");
					$txtInsertedIDArray		= $this->input->post("txtInsertedID");
					$txtDeletedIDArray		= $this->input->post("txtDeletedID");
					$txtEditedIDArray		= $this->input->post("txtEditedID");
					if($elementIDArray)
					foreach($elementIDArray as $key => $value){
							$objUserPermission["companyID"]		= $companyID;
							$objUserPermission["branchID"]		= $branchID;
							$objUserPermission["roleID"]		= $roleID;
							$objUserPermission["elementID"]		= $value;							
							$objUserPermission["selected"]		= $txtSelectedIDArray[$value];
							$objUserPermission["inserted"]		= $txtInsertedIDArray[$value];
							$objUserPermission["deleted"]		= $txtDeletedIDArray[$value];
							$objUserPermission["edited"]		= $txtEditedIDArray[$value];
							$this->User_Permission_Model->insert($objUserPermission);
					} 
					
					//Insertar Autorizaciones
					$listsComponentAutorizationID = $this->input->post("txtComponentAutorizationID");
					if($listsComponentAutorizationID)
					foreach($listsComponentAutorizationID as $key => $value){
						$data["componentAutorizationID"] 	= $value;
						$data["companyID"] 					= $companyID;
						$data["roleID"] 					= $roleID;
						$data["branchID"] 					= $branchID;
						$this->Role_Autorization_Model->insert($data);
					} 
					 
					if($this->db->trans_status() !== false){
						$this->db->trans_commit();						
						$this->core_web_notification->set_message(false,SUCCESS);
						redirect('core_role/edit/companyID/'.$companyID."/branchID/".$branchID."/roleID/".$roleID);						
					}
					else{
						$this->db->trans_rollback();						
						$this->core_web_notification->set_message(true,$this->db->_error_message());
						redirect('core_role/add');	
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
					$objOld = $this->Role_Model->get_rowByPK($companyID,$branchID,$roleID);
					if ($resultPermission 	== PERMISSION_ME && ($objOld->createdBy != $dataSession["user"]->userID))
					throw new Exception(NOT_EDIT);
			
			
					$this->db->trans_begin();
					//Actualizar Rol
					$obj["name"] 			= $this->input->post("txtName");
					$obj["description"] 	= $this->input->post("txtDescription");
					$obj["urlDefault"] 		= $this->input->post("txtUrlDefault");
					$obj["isAdmin"] 		= false;
					$obj["isActive"] 		= true;
					$result 				= $this->Role_Model->update($companyID,$branchID,$roleID,$obj);
					
					//Eliminar Elementos
					$this->User_Permission_Model->delete_ByRole($companyID,$branchID,$roleID);
					
					//Insert Elementos  
					$elementIDArray			= $this->input->post("txtElementID");
					$txtSelectedIDArray		= $this->input->post("txtSelectedID");
					$txtInsertedIDArray		= $this->input->post("txtInsertedID");
					$txtDeletedIDArray		= $this->input->post("txtDeletedID");
					$txtEditedIDArray		= $this->input->post("txtEditedID");
					if($elementIDArray)
					foreach($elementIDArray as $key => $value){
							$objUserPermission["companyID"]		= $companyID;
							$objUserPermission["branchID"]		= $branchID;
							$objUserPermission["roleID"]		= $roleID;
							$objUserPermission["elementID"]		= $value;							
							$objUserPermission["selected"]		= $txtSelectedIDArray[$value];
							$objUserPermission["inserted"]		= $txtInsertedIDArray[$value];
							$objUserPermission["deleted"]		= $txtDeletedIDArray[$value];
							$objUserPermission["edited"]		= $txtEditedIDArray[$value];
							$this->User_Permission_Model->insert($objUserPermission);
					}
					
					//Limpiar tablas
					$this->Role_Autorization_Model->deleteByRole($companyID,$branchID,$roleID);
					
					
					//Insertar Autorizaciones
					$listsComponentAutorizationID = $this->input->post("txtComponentAutorizationID");
					if($listsComponentAutorizationID)
					foreach($listsComponentAutorizationID as $key => $value){
						$data["componentAutorizationID"] 	= $value;
						$data["companyID"] 					= $companyID;
						$data["roleID"] 					= $roleID;
						$data["branchID"] 					= $branchID;
						$this->Role_Autorization_Model->insert($data);
					} 
					
					
					if($this->db->trans_status() !== false){
						$this->db->trans_commit();
						$this->core_web_notification->set_message(false,SUCCESS);
					}
					else{
						$this->db->trans_rollback();						
						$this->core_web_notification->set_message(true,$this->db->_error_message());
					}
					redirect('core_role/edit/companyID/'.$companyID."/branchID/".$branchID."/roleID/".$roleID);
			}  
			else{
				$this->core_web_notification->set_message(true,validation_errors());
				redirect('core_role/add');	
			} 
			
		}
		catch(Exception $ex){
			show_error($ex->getLine()." ".$ex->getMessage() ,500 );
		}		
			
	}
	function edit(){ 
		 try{ 
			//AUTENTICADO			
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
			$this->load->model("core/Role_Model"); 			
			$this->load->model("core/User_Permission_Model");
			$this->load->model("core/Role_Autorization_Model");
			
			//Redireccionar datos
			$uri		= $this->uri->uri_to_assoc(3);
			$companyID	= $uri["companyID"];
			$branchID	= $uri["branchID"];
			$roleID		= $uri["roleID"];
			if((!$companyID || !$branchID ||  !$roleID))
			{ 
				redirect('core_role/add');	
			} 		
			
			
			//Obtener el Registro			
			$datView["objRole"]	 					= $this->Role_Model->get_rowByPK($companyID,$branchID,$roleID);
			//Obtener los Permisos
			$datView["objUserPermission"]			= $this->User_Permission_Model->get_rowByCompanyIDyBranchIDyRoleID($companyID,$branchID,$roleID);
			//Obtener las Autorization
			$datView["listComponentAutoriation"]	= $this->Role_Autorization_Model->get_rowByRoleAutorization($companyID,$branchID,$roleID);
			
			
			//Renderizar Resultado
			$dataSession["notification"]	= $this->core_web_error->get_error($dataSession["user"]->userID);
			$dataSession["message"]			=  $this->core_web_notification->get_message();
			$dataSession["head"]			= $this->load->view('core_role/edit_head',$datView,true);
			$dataSession["body"]			= $this->load->view('core_role/edit_body',$datView,true);
			$dataSession["script"]			= $this->load->view('core_role/edit_script',$datView,true);  
			$dataSession["footer"]			= "";				
			$this->load->view("core_masterpage/default_masterpage",$dataSession);	
			
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
			
			//PERMISO SOBRE LAS FUNCION
			if(APP_NEED_AUTHENTICATION == true){
				$permited = false;
				$permited = $this->core_web_permission->urlPermited($this->router->class,"index",$this->config->item('url_suffix'),$dataSession["menuTop"],$dataSession["menuLeft"],$dataSession["menuBodyReport"],$dataSession["menuBodyTop"],$dataSession["menuHiddenPopup"]);
				
				if(!$permited)
				throw new Exception(NOT_ACCESS_CONTROL);
				
				$resultPermission		= $this->core_web_permission->urlPermissionCmd($this->router->class,"add",$this->config->item('url_suffix'),$dataSession,$dataSession["menuTop"],$dataSession["menuLeft"],$dataSession["menuBodyReport"],$dataSession["menuBodyTop"],$dataSession["menuHiddenPopup"]);
				if ($resultPermission 	== PERMISSION_NONE)
				throw new Exception(NOT_ACCESS_FUNCTION);			
			 
			}	  
			 
			//Renderizar Resultado 
			$dataSession["notification"]	= $this->core_web_error->get_error($dataSession["user"]->userID);
			$dataSession["message"]			= $this->core_web_notification->get_message();
			$dataSession["head"]			= $this->load->view('core_role/news_head','',true);
			$dataSession["body"]			= $this->load->view('core_role/news_body','',true);
			$dataSession["script"]			= $this->load->view('core_role/news_script','',true);  
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
			
			
			//Obtener el componente Para mostrar la lista de Roles
			$objComponent		= $this->core_web_tools->getComponentIDBy_ComponentName("tb_role");
			if(!$objComponent)
			throw new Exception("00384 EL COMPONENTE 'tb_role' NO EXISTE ...");
			
			
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
			$dataSession["head"]			= $this->load->view('core_role/list_head','',true);
			$dataSession["footer"]			= $this->load->view('core_role/list_footer','',true);
			$dataSession["body"]			= $dataViewRender; 
			$dataSession["script"]			= $this->load->view('core_role/list_script','',true).$this->core_web_javascript->createVar("componentID",$objComponent->componentID);  
			$this->load->view("core_masterpage/default_masterpage",$dataSession);		
		}
		catch(Exception $ex){
			show_error($ex->getLine()." ".$ex->getMessage() ,500 );
		}
	}

	//POPUP
	function add_subelement_autorization(){
			//Cargar Libreria
			$this->load->model("core/Component_Autorization_Model");
			
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
			
			//Obtener la lista de elementos			
			$listComponentAutorization			= $this->Component_Autorization_Model->get_rowByCompanyID($dataSession["user"]->companyID);
			$data["listComponentAutorization"] 	= $listComponentAutorization;
			
			//Renderizar Resultado
			$dataSession["message"]		= "";
			$dataSession["head"]		= $this->load->view('core_role/popup_add_autorization_head','',true);
			$dataSession["body"]		= $this->load->view('core_role/popup_add_autorization_body',$data,true);
			$dataSession["script"]		= $this->load->view('core_role/popup_add_autorization_script','',true);  
			$this->load->view("core_masterpage/default_popup",$dataSession);
	}
	function add_subelement(){
			//Cargar Libreria
			$this->load->model("core/Menu_Element_Model");
			
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
			
			//Obtener la lista de elementos			
			$listMenuElement			= $this->Menu_Element_Model->get_rowByCompanyID($dataSession["user"]->companyID);
			$data["listMenuElement"] 	= $listMenuElement;
			
			//Renderizar Resultado
			$dataSession["message"]		= "";
			$dataSession["head"]		= $this->load->view('core_role/popup_add_head','',true);
			$dataSession["body"]		= $this->load->view('core_role/popup_add_body',$data,true);
			$dataSession["script"]		= $this->load->view('core_role/popup_add_script','',true);  
			$this->load->view("core_masterpage/default_popup",$dataSession);				
			
	}
	
} 
?>
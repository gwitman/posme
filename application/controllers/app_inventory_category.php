<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class App_Inventory_Category extends CI_Controller {
	
    public function __construct() {
       parent::__construct();
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
			$this->load->model("ItemCategory_Model"); 	
			
			
			//Redireccionar datos
			$uri			= $this->uri->uri_to_assoc(3);
			$companyID		= $uri["companyID"];
			$itemCategoryID	= $uri["itemCategoryID"];	
			
			if((!$companyID || !$itemCategoryID))
			{ 
				redirect('app_inventory_category/add');	
			} 		
			
			
			//Obtener el Registro			
			$datView["objItemCategory"]			= $this->ItemCategory_Model->getByPK($companyID,$itemCategoryID);
			
			//Renderizar Resultado
			$dataSession["notification"]	= $this->core_web_error->get_error($dataSession["user"]->userID);
			$dataSession["message"]			=  $this->core_web_notification->get_message();
			$dataSession["head"]			= $this->load->view('app_inventory_category/edit_head',$datView,true);
			$dataSession["body"]			= $this->load->view('app_inventory_category/edit_body',$datView,true);
			$dataSession["script"]			= $this->load->view('app_inventory_category/edit_script',$datView,true);  
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
			$this->load->model("ItemCategory_Model");  			
			
			//Nuevo Registro
			$companyID 			= $this->input->post("companyID");
			$itemCategoryID		= $this->input->post("itemCategoryID");				
			
			if((!$companyID && !$itemCategoryID)){
					throw new Exception(NOT_PARAMETER);			
					 
			} 
			
			//Eliminar el Registro
			$this->ItemCategory_Model->delete($companyID,$itemCategoryID);
			
			//Resultado
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
			
			//Validar Formulario						
			$this->form_validation->set_rules("txtName","Nombre","required");    
			$this->load->model("ItemCategory_Model"); 	
			
			//Validar Formulario
			if($this->form_validation->run() != true){
				$stringValidation = str_replace("\n","",validation_errors());								
				$this->core_web_notification->set_message(true,$stringValidation);
				redirect('app_inventory_category/add');	
				exit;
			}
			
			//Nuevo Registro
			if( $method == "new"){
					
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
					
					$this->db->trans_begin();
					//Crear Categoria
					$obj["companyID"]			= $dataSession["user"]->companyID;
					$obj["branchID"] 			= $dataSession["user"]->branchID;
					$obj["name"] 				= $this->input->post("txtName");				 
					$obj["description"] 		= $this->input->post("txtDescription");				 
					$obj["isActive"] 			= true;
					$this->core_web_auditoria->setAuditCreated($obj,$dataSession);
					
					//Ingresar
					$itemCategoryID				= $this->ItemCategory_Model->insert($obj);
					$companyID 					= $obj["companyID"];
					
					//Completar Transaccion
					if($this->db->trans_status() !== false){
						$this->db->trans_commit();						
						$this->core_web_notification->set_message(false,SUCCESS);
						redirect('app_inventory_category/edit/companyID/'.$companyID."/itemCategoryID/".$itemCategoryID);						
					}
					else{
						$this->db->trans_rollback();						
						$this->core_web_notification->set_message(true,$this->db->_error_message());
						redirect('app_inventory_category/add');	
					}
					 
			} 
			//Editar Registro
			else {
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
					
					//Actualizar Bodega
					$this->db->trans_begin();					
					$companyID 			= $dataSession["user"]->companyID;
					$itemCategoryID 	= $this->input->post("txtItemCategoryID");
					$obj["name"] 		= $this->input->post("txtName");
					$obj["description"] = $this->input->post("txtDescription");
					
					
					//Actualizar Bodega
					$result 			= $this->ItemCategory_Model->update($companyID,$itemCategoryID,$obj);
				
					if($this->db->trans_status() !== false){
						$this->db->trans_commit();
						$this->core_web_notification->set_message(false,SUCCESS);
					}
					else{
						$this->db->trans_rollback();						
						$this->core_web_notification->set_message(true,$this->db->_error_message());
					}
					redirect('app_inventory_category/edit/companyID/'.$companyID."/itemCategoryID/".$itemCategoryID);
					
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
			$dataSession["head"]			= $this->load->view('app_inventory_category/news_head','',true);
			$dataSession["body"]			= $this->load->view('app_inventory_category/news_body','',true);
			$dataSession["script"]			= $this->load->view('app_inventory_category/news_script','',true);  
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
			
			//Obtener el componente Para mostrar la lista de Bodegas
			$objComponent		= $this->core_web_tools->getComponentIDBy_ComponentName("tb_item_category");
			if(!$objComponent)
			throw new Exception("00409 EL COMPONENTE 'tb_item_category' NO EXISTE...");
			
			
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
			$dataSession["head"]			= $this->load->view('app_inventory_category/list_head','',true);
			$dataSession["footer"]			= $this->load->view('app_inventory_category/list_footer','',true);
			$dataSession["body"]			= $dataViewRender; 
			$dataSession["script"]			= $this->load->view('app_inventory_category/list_script','',true).$this->core_web_javascript->createVar("componentID",$objComponent->componentID);  
			$this->load->view("core_masterpage/default_masterpage",$dataSession);		
		}
		catch(Exception $ex){
			show_error($ex->getLine()." ".$ex->getMessage() ,500 );
		}
	}	
}
?>
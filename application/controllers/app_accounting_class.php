<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class App_Accounting_Class extends CI_Controller {
	
    public function __construct() {
       parent::__construct();
    }
	
	public function isValidAccountNumber($accountNumber,$companyID,$accountLevelID){
		//false numero incorrecto
		//true 	numero correcto
		$this->load->model("Account_Level_Model"); 
		$objAccountLevel = $this->Account_Level_Model->get_rowByPK($companyID,$accountLevelID);
		
		//Validar Longitud Total
		if($objAccountLevel->lengthTotal != strlen($accountNumber) )
		return false;		
			
		//Validar Longitud de Grupo
		if($objAccountLevel->split){
			$partNumber = explode($objAccountLevel->split,$accountNumber);
			$count 		= count($partNumber) -1;
			if($objAccountLevel->lengthGroup != strlen($partNumber[$count]))
			return false;
		}
				
		return true;
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
			$this->load->model("Account_Level_Model"); 						
			$this->load->model("Center_Cost_Model"); 				
			$this->load->model("core/User_Permission_Model");
			$this->load->model("core/Role_Autorization_Model");
			
			
			//Redireccionar datos
			$uri			= $this->uri->uri_to_assoc(3);						
			$companyID		= $uri["companyID"];
			$classID		= $uri["classID"];	
			$branchID 		= $dataSession["user"]->branchID;
			$roleID 		= $dataSession["role"]->roleID;			
			if((!$companyID || !$classID))
			{ 
				redirect('app_accounting_class/add');	
			} 		
			
			
			//Obtener el Registro			
			$datView["objClass"]	 				= $this->Center_Cost_Model->get_rowByPK($companyID,$classID);
			$datView["objParentClass"]				= $this->Center_Cost_Model->get_rowByPK($companyID,$datView["objClass"]->parentClassID);
			$datView["objListAccountLevel"]	 		= $this->Account_Level_Model->getByCompany($companyID);
			
			
			//Obtener los Permisos Core
			$datView["objUserPermission"]			= $this->User_Permission_Model->get_rowByCompanyIDyBranchIDyRoleID($companyID,$branchID,$roleID);
			//Obtener las Autorization Core
			$datView["listComponentAutoriation"]	= $this->Role_Autorization_Model->get_rowByRoleAutorization($companyID,$branchID,$roleID);
			
			
			//Renderizar Resultado
			$dataSession["notification"]	= $this->core_web_error->get_error($dataSession["user"]->userID);
			$dataSession["message"]			=  $this->core_web_notification->get_message();
			$dataSession["head"]			= $this->load->view('app_account_class/edit_head',$datView,true);
			$dataSession["body"]			= $this->load->view('app_account_class/edit_body',$datView,true);
			$dataSession["script"]			= $this->load->view('app_account_class/edit_script',$datView,true);  
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
			$this->load->model("Center_Cost_Model");  
			
			//Nuevo Registro
			$companyID 			= $this->input->post("companyID");
			$classID 			= $this->input->post("classID");				
			
			if((!$companyID && !$classID)){
					throw new Exception(NOT_PARAMETER);			
					 
			} 
			
			$obj 			= $this->Center_Cost_Model->get_rowByPK($companyID,$classID);	
			//PERMISO SOBRE EL REGISTRO
			if ($resultPermission 	== PERMISSION_ME && ($obj->createdBy != $dataSession["user"]->userID))
			throw new Exception(NOT_DELETE);
			
			//Eliminar el Registro
			$this->Center_Cost_Model->delete($companyID,$classID);
					
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
			$this->load->model("Account_Level_Model"); 
			$this->load->model("Center_Cost_Model"); 
					
			
			//Validar Formulario
			$this->form_validation->set_rules("txtNumber","Number","required");
			$this->form_validation->set_rules("txtAccountLevelID","Class","required");
			$this->form_validation->set_rules("txtDescription","Name","required");
			
			 
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
						//Buscar Padre
						$objParentClass = NULL;
						if($this->input->post("txtParentClassNumber")){
							$objParentClass		= $this->Center_Cost_Model->getByClassNumber($this->input->post("txtParentClassNumber"),$dataSession["user"]->companyID);
						}
						
						//Buscar si Existe Cuenta
						$objClass					= $this->Center_Cost_Model->getByClassNumber($this->input->post("txtNumber"),$dataSession["user"]->companyID);
						if($objClass){
							$continue 				= false;
							throw new Exception("EL CODIGO DEL CENTRO DE COSTO YA ESTA RESERVADO...");
						}
						//Validar Codigo	
						if(!$this->isValidAccountNumber($this->input->post("txtNumber"),$dataSession["user"]->companyID,$this->input->post("txtAccountLevelID"))){
							$continue 				= false;
							throw new Exception("EL CODIGO DEL CENTRO DE COSTO TIENE UN FORMATO INCORRECTO");
						}
						
						//Crear Centro de Costo
						$obj["companyID"]			= $dataSession["user"]->companyID;						
						$obj["accountLevelID"] 		= $this->input->post("txtAccountLevelID");
						$obj["parentClassID"] 		= $objParentClass ? $objParentClass->classID : NULL;
						$obj["number"] 				= $this->input->post("txtNumber");							
						$obj["description"] 		= $this->input->post("txtDescription");												
						$obj["isActive"] 			= true;
						$this->core_web_auditoria->setAuditCreated($obj,$dataSession);
						
						$classID				= $this->Center_Cost_Model->insert($obj);
						$companyID 				= $obj["companyID"];
						
						if($this->db->trans_status() !== false){
							$this->db->trans_commit();						
							$this->core_web_notification->set_message(false,SUCCESS);
							redirect('app_accounting_class/edit/companyID/'.$companyID."/classID/".$classID);						
						}
						else{
							$this->db->trans_rollback();						
							$this->core_web_notification->set_message(true,$this->db->_error_message());
							redirect('app_accounting_class/add');	
						}
					}
					else{
						redirect('app_accounting_class/add');	
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
					$companyID			= $dataSession["user"]->companyID;
					$classID 			= $this->input->post("txtClassID");
					$objOld 			= $this->Center_Cost_Model->get_rowByPK($companyID,$classID);
					if ($resultPermission 	== PERMISSION_ME && ($objOld->createdBy != $dataSession["user"]->userID))
					throw new Exception(NOT_EDIT);
			
			
					if($continue){
						$this->db->trans_begin();					
						
						//Buscar Padre
						$objParentClass 	= NULL;
						$classID 			= $this->input->post("txtClassID");
						if($this->input->post("txtParentClassNumber")){
							$objParentClass		= $this->Center_Cost_Model->getByClassNumber($this->input->post("txtParentClassNumber"),$dataSession["user"]->companyID);
						}
						
						//Buscar si Existe Cuenta
						$objClass					= $this->Center_Cost_Model->getByClassNumber($this->input->post("txtNumber"),$dataSession["user"]->companyID);
						if($objClass){
							if($objClass->classID != $classID){
								$continue 				= false;
								throw new Exception("EL CODIGO DEL CENTRO DE COSTO YA ESTA RESERVADO...");
							}
						}
						//Validar Codigo	
						if(!$this->isValidAccountNumber($this->input->post("txtNumber"),$dataSession["user"]->companyID,$this->input->post("txtAccountLevelID"))){
							$continue 				= false;
							throw new Exception("EL CODIGO DEL CENTRO DE COSTO TIENE UN FORMATO INCORRECTO");
						}						
						
						//Crear Cuenta
						$companyID					= $dataSession["user"]->companyID;
						$classID 					= $this->input->post("txtClassID");						
						$obj["accountLevelID"] 		= $this->input->post("txtAccountLevelID");
						$obj["parentAccountID"] 	= $objParentClass ? $objParentClass->classID : NULL;						
						$obj["number"] 				= $this->input->post("txtNumber");					 
						$obj["description"] 		= $this->input->post("txtDescription");
						$result 					= $this->Center_Cost_Model->update($companyID,$classID,$obj);						
						
						if($this->db->trans_status() !== false){
							$this->db->trans_commit();
							$this->core_web_notification->set_message(false,SUCCESS);
						}
						else{
							$this->db->trans_rollback();						
							$this->core_web_notification->set_message(true,$this->db->_error_message());
						}
						redirect('app_accounting_class/edit/companyID/'.$companyID."/classID/".$classID);
						
					}					
					else{
						redirect('app_accounting_class/add');	
					}
			}  
			else{
				$stringValidation = str_replace("\n","",validation_errors());								
				$this->core_web_notification->set_message(true,$stringValidation);
				redirect('app_accounting_class/add');	
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
			 
			$this->load->model("Account_Level_Model");  			
			$data["objListAccountLevel"] 	= $this->Account_Level_Model->getByCompany($dataSession["user"]->companyID);
			
			
			//Renderizar Resultado 
			$dataSession["notification"]	= $this->core_web_error->get_error($dataSession["user"]->userID);
			$dataSession["message"]			= $this->core_web_notification->get_message();
			$dataSession["head"]			= $this->load->view('app_account_class/news_head',$data,true);
			$dataSession["body"]			= $this->load->view('app_account_class/news_body',$data,true);
			$dataSession["script"]			= $this->load->view('app_account_class/news_script',$data,true);  
			$dataSession["footer"]			= "";
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
			
			//Obtener el componente Para mostrar la lista de CenterCost
			$objComponent		= $this->core_web_tools->getComponentIDBy_ComponentName("tb_center_cost");
			if(!$objComponent)
			throw new Exception("00409 EL COMPONENTE 'tb_center_cost' NO EXISTE...");
			
			
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
			$dataSession["head"]			= $this->load->view('app_account_class/list_head','',true);
			$dataSession["footer"]			= $this->load->view('app_account_class/list_footer','',true);
			$dataSession["body"]			= $dataViewRender; 
			$dataSession["script"]			= $this->load->view('app_account_class/list_script','',true).$this->core_web_javascript->createVar("componentID",$objComponent->componentID);  
			$this->load->view("core_masterpage/default_masterpage",$dataSession);		
		}
		catch(Exception $ex){
			show_error($ex->getLine()." ".$ex->getMessage() ,500 );
		}
	}	
	
}
?>
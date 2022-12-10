<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');





class core_user extends CI_Controller {



	

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

			$this->load->model("core/User_Model");

			$companyID 	= $this->input->post("companyID");

			$branchID 	= $this->input->post("branchID");

			$userID		= $this->input->post("userID");	

			

			if((!$companyID && !$branchID   && !$userID)){

					throw new Exception(NOT_PARAMETER);

					 

			} 

			

			$obj 			= $this->User_Model->get_rowByPK($companyID,$branchID,$userID);			

			$obj->isActive	= false;			

			

			//PERMISO SOBRE EL REGISTRO

			if ($resultPermission 	== PERMISSION_ME && ($obj->createdBy != $dataSession["user"]->userID))

			throw new Exception(NOT_DELETE);

			

			$obj			= (array)$obj;	

			$result 		= $this->User_Model->update($companyID,$branchID,$userID,$obj);

					

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

			

			

			

			//Load Modelos			

			//

			////////////////////////////////////////

			////////////////////////////////////////

			////////////////////////////////////////

			$this->load->model("core/User_Model");  

			$this->load->model("core/Membership_Model");  

			$this->load->model("UserWarehouse_Model");  

			$this->load->model("User_Tag_Model");

			

			//Validar Formulario						

			$this->form_validation->set_rules("txtNickname","Nickname ","required");    

			$this->form_validation->set_rules("txtPassword","Password ","required");    

			$this->form_validation->set_rules("txtEmail","Email","required");

			$this->form_validation->set_rules("txtRoleID","Rol","required");

						

			

			 

			//Nuevo Registro

			$companyID 	= $this->input->post("companyID");

			$branchID 	= $this->input->post("branchID");

			$userID		= $this->input->post("userID");	

			$continue	= true;

			if((!$companyID && !$branchID   && !$userID) && $this->form_validation->run() == true ){

					

					//Validar si tiene permiso para ver la Pagina WEB					

					if(APP_NEED_AUTHENTICATION == true){

							$permited = false;

							$permited = $this->core_web_permission->urlPermited($this->router->class,"index",$this->config->item('url_suffix'),$dataSession["menuTop"],$dataSession["menuLeft"],$dataSession["menuBodyReport"],$dataSession["menuBodyTop"],$dataSession["menuHiddenPopup"]);

							

							if(!$permited)

							throw new Exception(NOT_ACCESS_CONTROL);

							

							$resultPermission		= $this->core_web_permission->urlPermissionCmd($this->router->class,"add",$this->config->item('url_suffix'),$dataSession,$dataSession["menuTop"],$dataSession["menuLeft"],$dataSession["menuBodyReport"],$dataSession["menuBodyTop"],$dataSession["menuHiddenPopup"]);

							if ($resultPermission 	== PERMISSION_NONE)

							throw new Exception(NOT_ACCESS_FUNCTION);

					}	 

					

					

					//validar nickname

					$objUserTmp = $this->User_Model->get_rowByExistNickname($this->input->post("txtNickname"));

					if($objUserTmp && $continue )

					{						

						$continue = false;

						$this->core_web_notification->set_message(true,NICKNAME_DUPLI);

					}   

					

					//validar email

					$objUserTmp = $this->User_Model->get_rowByEmail($this->input->post("txtEmail"));

					if($objUserTmp && $continue )

					{ 	

						$continue = false;

						$this->core_web_notification->set_message(true,EMAIL_DUPLI);				

					} 					
					

					$this->core_web_permission->getValueLicense($companyID,$this->router->class."/"."index");
					$continue = true;

					//Ingresar usuario
					if($continue){

						$this->db->trans_begin();

						//Crear Usuario

						$obj["companyID"]		= $dataSession["user"]->companyID;					

						$obj["branchID"] 		= $dataSession["user"]->branchID;					

						

						$obj["nickname"] 			= $this->input->post("txtNickname");

						$obj["password"] 			= $this->input->post("txtPassword");

						$obj["email"] 				= $this->input->post("txtEmail");					

						$obj["createdOn"]			= date("Y-m-d H:i:s");					

						$obj["createdBy"]			= $dataSession["user"]->userID;

						$obj["isActive"] 			= true;		

						$obj["employeeID"] 			= $this->input->post("txtEmployeeID");

						$userID		 				= $this->User_Model->insert($obj);					

						$companyID 					= $obj["companyID"];

						$branchID 					= $obj["branchID"];			 

						$roleID 					= $this->input->post("txtRoleID");

						

						//Eliminar Membership

						$result 					= $this->Membership_Model->delete($companyID,$branchID,$userID);

						 

						//Nuevo Membership

						$objMembership["companyID"] = $companyID;

						$objMembership["branchID"] 	= $branchID;

						$objMembership["userID"] 	= $userID;

						$objMembership["roleID"] 	= $roleID;

						$result 					= $this->Membership_Model->insert($objMembership);

						

						//Agrebar Bodegas

						$objListWarehouse 			= $this->input->post("txtDetailWarehouseID");

						$this->UserWarehouse_Model->deleteByUser($companyID,$userID);

						if($objListWarehouse)

						foreach($objListWarehouse as $key => $value){

							$objWarehouse["companyID"] 		= $companyID;

							$objWarehouse["branchID"] 		= $branchID;

							$objWarehouse["userID"] 		= $userID;

							$objWarehouse["warehouseID"] 	= $value;

							$this->UserWarehouse_Model->insert($objWarehouse);

						}

						

						//Agregar Notificaciones

						$objListTag					= $this->input->post("txtDetailTagID");

						$this->User_Tag_Model->deleteByUser($userID);

						if($objListTag)

						foreach($objListTag as $key => $value){

							$objTag["companyID"] 		= $companyID;

							$objTag["branchID"] 		= $branchID;

							$objTag["userID"] 			= $userID;

							$objTag["tagID"] 			= $value;

							$this->User_Tag_Model->insert($objTag);

						}

						

						

						if($this->db->trans_status() !== false){

							$this->db->trans_commit();						

							$this->core_web_notification->set_message(false,SUCCESS);

							redirect('core_user/edit/companyID/'.$companyID."/branchID/".$branchID."/userID/".$userID);						

						}

						else{

							$this->db->trans_rollback();						

							$this->core_web_notification->set_message(true,$this->db->_error_message());

							redirect('core_user/add');	

						}

					}

					else{

						redirect('core_user/add');	

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

					$objOld = $this->User_Model->get_rowByPK($companyID,$branchID,$userID);

					if ($resultPermission 	== PERMISSION_ME && ($objOld->createdBy != $dataSession["user"]->userID))

					throw new Exception(NOT_EDIT);

			

			

					//validar nickname

					$objUserTmp = $this->User_Model->get_rowByExistNickname($this->input->post("txtNickname"));

					if($objUserTmp && ($objUserTmp->userID != $userID)  && $continue )

					{						

						$continue = false;

						$this->core_web_notification->set_message(true,NICKNAME_DUPLI);								

					}   

					

					//validar email

					$objUserTmp = $this->User_Model->get_rowByEmail($this->input->post("txtEmail"));

					if($objUserTmp && ($objUserTmp->userID != $userID)  && $continue )

					{ 	

						$continue = false;

						$this->core_web_notification->set_message(true,EMAIL_DUPLI);				

					} 

						

					if($continue){

						$this->db->trans_begin();

						

						//Actualizar Rol

						$obj["nickname"] 			= $this->input->post("txtNickname");

						$obj["password"] 			= $this->input->post("txtPassword");

						$obj["email"] 				= $this->input->post("txtEmail");

						$obj["employeeID"] 			= $this->input->post("txtEmployeeID");

						$result 					= $this->User_Model->update($companyID,$branchID,$userID,$obj);

						$roleID 					= $this->input->post("txtRoleID");

						

						//Eliminar Membership

						$result 					= $this->Membership_Model->delete($companyID,$branchID,$userID);

						 

						//Nuevo Membership

						$objMembership["companyID"] = $companyID;

						$objMembership["branchID"] 	= $branchID;

						$objMembership["userID"] 	= $userID;

						$objMembership["roleID"] 	= $roleID;

						$result 					= $this->Membership_Model->insert($objMembership);

						

						

						//Agrebar Bodegas

						$objListWarehouse 			= $this->input->post("txtDetailWarehouseID");

						$this->UserWarehouse_Model->deleteByUser($companyID,$userID);

						if($objListWarehouse)

						foreach($objListWarehouse as $key => $value){

							$objWarehouse["companyID"] 		= $companyID;

							$objWarehouse["branchID"] 		= $branchID;

							$objWarehouse["userID"] 		= $userID;

							$objWarehouse["warehouseID"] 	= $value;

							$this->UserWarehouse_Model->insert($objWarehouse);

						}

						

						//Agregar Notificaciones

						$objListTag					= $this->input->post("txtDetailTagID");

						$this->User_Tag_Model->deleteByUser($userID);

						if($objListTag)

						foreach($objListTag as $key => $value){

							$objTag["companyID"] 		= $companyID;

							$objTag["branchID"] 		= $branchID;

							$objTag["userID"] 			= $userID;

							$objTag["tagID"] 			= $value;

							$this->User_Tag_Model->insert($objTag);

						}

						

						if($this->db->trans_status() !== false){

							$this->db->trans_commit();

							$this->core_web_notification->set_message(false,SUCCESS);

						}

						else{

							$this->db->trans_rollback();						

							$this->core_web_notification->set_message(true,$this->db->_error_message());

						}

						redirect('core_user/edit/companyID/'.$companyID."/branchID/".$branchID."/userID/".$userID);

					}					

					else{

						redirect('core_user/add');	

					}

			}  

			else{				

				$stringValidation = str_replace("\n","",validation_errors());				

				$this->core_web_notification->set_message(true,$stringValidation);

				redirect('core_user/add');	

			} 

			

		}

		catch(Exception $ex){

			show_error($ex->getLine()." ".$ex->getMessage() ,500 );

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

			

			//Load Model

			//

			////////////////////////////////////////

			////////////////////////////////////////

			////////////////////////////////////////

			$this->load->model("core/Role_Model"); 			

			$this->load->model("core/User_Model");			

			$this->load->model("core/Membership_Model");

			$this->load->model("UserWarehouse_Model");

			$this->load->model("Entity_Model");

			$this->load->model("Natural_Model");

			$this->load->model("Employee_Model");

			$this->load->model("Customer_Model");	

			$this->load->model("Provider_Model");

			$this->load->model("User_Tag_Model");

			

			//Redireccionar datos

			$uri		= $this->uri->uri_to_assoc(3);

			$companyID	= $uri["companyID"];

			$branchID	= $uri["branchID"];

			$userID		= $uri["userID"];

			if((!$companyID || !$branchID ||  !$userID))

			{ 

				

				redirect('core_user/add');	

			} 		

			

			$objComponent						= $this->core_web_tools->getComponentIDBy_ComponentName("tb_employee");			

			if(!$objComponent)

			throw new Exception("EL COMPONENTE 'tb_employee' NO EXISTE...");

		

			$objComponentEntity					= $this->core_web_tools->getComponentIDBy_ComponentName("tb_entity");			

			if(!$objComponentEntity)

			throw new Exception("EL COMPONENTE 'tb_entity' NO EXISTE...");

		

			

			//Obtener el Registro			

			$datView["objUser"]	 				= $this->User_Model->get_rowByPK($companyID,$branchID,$userID);

			//Obtener los Roles

			$datView["objListRoles"]			= $this->Role_Model->get_rowByCompanyIDyBranchID($companyID,$branchID);

			//Obtener el Membership

			$datView["objMembership"]	 		= $this->Membership_Model->get_rowByCompanyIDBranchIDUserID($companyID,$branchID,$userID);

			//Obtener la lista de Bodegas

			$datView["objListWarehouse"] 		= $this->UserWarehouse_Model->getRowByUserID($companyID,$userID);

			//Obtener la lista de Notificaciones

			$datView["objListNotification"]		= $this->User_Tag_Model->get_rowByUser($userID);

			//Obtener el componente

			$datView["objComponentEmployee"]	= $objComponent;

			$datView["objComponentEntity"]		= $objComponentEntity;

			//Obtener Entidad

			$datView["objEntity"]				= $this->Entity_Model->get_rowByEntity($companyID,$datView["objUser"]->employeeID);

			//Obtener Empleado

			$datView["objCustomer"]				= $datView["objEntity"] == null ? null : $this->Customer_Model->get_rowByPK($datView["objEntity"]->companyID,$datView["objEntity"]->branchID,$datView["objEntity"]->entityID);

			$datView["objEmployee"]				= $datView["objEntity"] == null ? null : $this->Employee_Model->get_rowByPK($datView["objEntity"]->companyID,$datView["objEntity"]->branchID,$datView["objEntity"]->entityID);			
			
			$datView["objProvider"]				= $datView["objEntity"] == null ? null : $this->Provider_Model->get_rowByPK($datView["objEntity"]->companyID,$datView["objEntity"]->branchID,$datView["objEntity"]->entityID);			

			$datView["entityNumber"]			= $datView["objCustomer"] != null ? $datView["objCustomer"]->customerNumber :
												  (
													$datView["objEmployee"] != null ? $datView["objEmployee"]->employeNumber : 
														(
															$datView["objProvider"] != null ? $datView["objProvider"]->providerNumber : NULL
														)
												  );

			

			//Obtener El Natural

			$datView["objNatural"]				= $datView["objEntity"] == null ? null : $this->Natural_Model->get_rowByPK($datView["objEntity"]->companyID,$datView["objEntity"]->branchID,$datView["objEntity"]->entityID);

			

			//Renderizar Resultado

			$dataSession["notification"]	= $this->core_web_error->get_error($dataSession["user"]->userID);

			$dataSession["message"]			= $this->core_web_notification->get_message();

			$dataSession["head"]			= $this->load->view('core_user/edit_head',$datView,true);

			$dataSession["body"]			= $this->load->view('core_user/edit_body',$datView,true);

			$dataSession["script"]			= $this->load->view('core_user/edit_script',$datView,true);  

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

			

			//Load Modelos

			$this->load->model("core/Role_Model"); 

			

			$objComponent						= $this->core_web_tools->getComponentIDBy_ComponentName("tb_employee");			

			if(!$objComponent)

			throw new Exception("EL COMPONENTE 'tb_employee' NO EXISTE...");

		

			$objComponentEntity					= $this->core_web_tools->getComponentIDBy_ComponentName("tb_entity");			

			if(!$objComponentEntity)

			throw new Exception("EL COMPONENTE 'tb_entity' NO EXISTE...");

		

		

			

			

			//Obtener los Roles

			$datView["objListRoles"] = $this->Role_Model->get_rowByCompanyIDyBranchID($this->session->userdata('user')->companyID,$this->session->userdata('user')->branchID);

			$datView["objEmployee"]  = $objComponent;

			$datView["objEntity"]  	 = $objComponentEntity;

			

			

			//Renderizar Resultado 

			$dataSession["notification"]	= $this->core_web_error->get_error($dataSession["user"]->userID);

			$dataSession["message"]			= $this->core_web_notification->get_message();

			$dataSession["head"]			= $this->load->view('core_user/news_head','',true);

			$dataSession["body"]			= $this->load->view('core_user/news_body',$datView,true);

			$dataSession["script"]			= $this->load->view('core_user/news_script','',true);  

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

			$objComponent		= $this->core_web_tools->getComponentIDBy_ComponentName("tb_user");

			if(!$objComponent)

			throw new Exception("00409 EL COMPONENTE 'tb_user' NO EXISTE...");

			

			

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

			$dataSession["head"]			= $this->load->view('core_user/list_head','',true);

			$dataSession["footer"]			= $this->load->view('core_user/list_footer','',true);

			$dataSession["body"]			= $dataViewRender; 

			$dataSession["script"]			= $this->load->view('core_user/list_script','',true).$this->core_web_javascript->createVar("componentID",$objComponent->componentID);  

			$this->load->view("core_masterpage/default_masterpage",$dataSession);		

		}

		catch(Exception $ex){

			show_error($ex->getLine()." ".$ex->getMessage() ,500 );

		}

	}

	function add_warehouse(){

			//Cargar Libreria

			$this->load->model("Warehouse_Model");

			

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

			$objListWarehouse			= $this->Warehouse_Model->getByCompany($dataSession["user"]->companyID);

			$data["objListWarehouse"] 	= $objListWarehouse;

			

			//Renderizar Resultado

			$dataSession["message"]		= "";

			$dataSession["head"]		= $this->load->view('core_user/popup_add_head','',true);

			$dataSession["body"]		= $this->load->view('core_user/popup_add_body',$data,true);

			$dataSession["script"]		= $this->load->view('core_user/popup_add_script','',true);  

			$this->load->view("core_masterpage/default_popup",$dataSession);				

			

	}

	function add_tag(){

			//Cargar Libreria

			$this->load->model("Tag_Model");

			

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

			$objListTag					= $this->Tag_Model->get_rows();

			$data["objListTag"] 		= $objListTag;

			

			//Renderizar Resultado

			$dataSession["message"]		= "";

			$dataSession["head"]		= $this->load->view('core_user/popup_tag_add_head','',true);

			$dataSession["body"]		= $this->load->view('core_user/popup_tag_add_body',$data,true);

			$dataSession["script"]		= $this->load->view('core_user/popup_tag_add_script','',true);  

			$this->load->view("core_masterpage/default_popup",$dataSession);				

			

	}

	

}

?>
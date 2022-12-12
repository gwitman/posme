<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');



class App_Inventory_Warehouse extends CI_Controller {

	

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

			$this->load->model("Warehouse_Model"); 			

			$this->load->model("core/Branch_Model"); 	

			

			

			//Redireccionar datos

			$uri			= $this->uri->uri_to_assoc(3);

			$companyID		= $uri["companyID"];

			$warehouseID	= $uri["warehouseID"];	

			$branchID 		= $dataSession["user"]->branchID;

			$roleID 		= $dataSession["role"]->roleID;			

			if((!$companyID || !$warehouseID))

			{ 

				redirect('app_inventory_warehouse/add');	

			} 		

			

			

			//Obtener el Registro			

			$datView["objWarehouse"]			= $this->Warehouse_Model->get_rowByPK($companyID,$warehouseID);

			$datView["objListBranch"]			= $this->Branch_Model->getByCompany($companyID);

			$datView["objListWorkflowStage"]	= $this->core_web_workflow->getWorkflowStageByStageInit("tb_warehouse","statusID",$datView["objWarehouse"]->statusID,$companyID,$branchID,$roleID);

			

			//Renderizar Resultado

			$dataSession["notification"]	= $this->core_web_error->get_error($dataSession["user"]->userID);

			$dataSession["message"]			= $this->core_web_notification->get_message();

			$dataSession["head"]			= $this->load->view('app_inventory_warehouse/edit_head',$datView,true);

			$dataSession["body"]			= $this->load->view('app_inventory_warehouse/edit_body',$datView,true);

			$dataSession["script"]			= $this->load->view('app_inventory_warehouse/edit_script',$datView,true);  

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

			$this->load->model("Warehouse_Model");  

			$this->load->model("ItemWarehouse_Model");  

			

			//Nuevo Registro

			$companyID 			= $this->input->post("companyID");

			$warehouseID 		= $this->input->post("warehouseID");				

			

			if((!$companyID && !$warehouseID)){

					throw new Exception(NOT_PARAMETER);			

					 

			} 

			

			//OBTENER EL COMPROBANTE

			$obj 			= $this->Warehouse_Model->get_rowByPK($companyID,$warehouseID);	

			//PERMISO SOBRE EL REGISTRO

			if ($resultPermission 	== PERMISSION_ME && ($obj->createdBy != $dataSession["user"]->userID))

			throw new Exception(NOT_DELETE);

			

			//VALIDAR PRODUCTOS EN LAS BODEGAS

			$count 				= $this->ItemWarehouse_Model->warehouseIsEmpty($companyID,$warehouseID);

			if($count > 0)

			throw new Exception("la bodega no puede ser eliminada, hay productos con existencias mayor que 0");	

			

			//Eliminar el Registro

			$this->Warehouse_Model->delete($companyID,$warehouseID);

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

			$this->form_validation->set_rules("txtNumber","Codigo","required|min_length[5]|max_length[5]");    

			$this->form_validation->set_rules("txtName","Nombre","required");

			$this->form_validation->set_rules("txtBranchID","Sucursal","required");

			$this->load->model("Warehouse_Model"); 	

			

			//Validar Formulario

			if($this->form_validation->run() != true){

				$stringValidation = str_replace("\n","",validation_errors());								

				$this->core_web_notification->set_message(true,$stringValidation);

				redirect('app_inventory_warehouse/add');	

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

					

					$this->core_web_permission->getValueLicense($dataSession["user"]->companyID,$this->router->class."/"."index");
					$this->db->trans_begin();

					//Crear Cuenta

					$obj["companyID"]			= $dataSession["user"]->companyID;

					$obj["branchID"] 			= $this->input->post("txtBranchID");

					$obj["number"] 				= $this->input->post("txtNumber");

					$obj["name"] 				= $this->input->post("txtName");				 

					$obj["address"] 			= $this->input->post("txtAddress");				 

					$obj["isActive"] 			= true;

					$obj["statusID"] 			= $this->input->post("txtStatusID");

					$this->core_web_auditoria->setAuditCreated($obj,$dataSession);

					

					//Validar Codigo de Bodega

					$objWarehouse				= $this->Warehouse_Model->getByCode($obj["companyID"],$obj["number"]);

					if($objWarehouse)

					{

						$this->core_web_notification->set_message(true,"Ya hay una bodega existente con ese codigo");

						redirect('app_inventory_warehouse/add');	

						exit;

					}

					

					//Ingresar

					$warehouseID				= $this->Warehouse_Model->insert($obj);

					$companyID 					= $obj["companyID"];

					

					//Completar Transaccion

					if($this->db->trans_status() !== false){

						$this->db->trans_commit();						

						$this->core_web_notification->set_message(false,SUCCESS);

						redirect('app_inventory_warehouse/edit/companyID/'.$companyID."/warehouseID/".$warehouseID);						

					}

					else{

						$this->db->trans_rollback();						

						$this->core_web_notification->set_message(true,$this->db->_error_message());

						redirect('app_inventory_warehouse/add');	

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

					

					//PERMISO SOBRE EL REGISTRO

					$companyID 			= $dataSession["user"]->companyID;

					$warehouseID 		= $this->input->post("txtWarehouseID");

					$branchID 			= $this->input->post("txtBranchID");

					$oldWarehouse 		= $this->Warehouse_Model->get_rowByPK($companyID,$warehouseID);

					if ($resultPermission 	== PERMISSION_ME && ($oldWarehouse->createdBy != $dataSession["user"]->userID))

					throw new Exception(NOT_EDIT);

			

					//Actualizar Bodega

					$this->db->trans_begin();					

					$obj["number"] 		= $this->input->post("txtNumber");

					$obj["address"] 	= $this->input->post("txtAddress");

					$obj["name"] 		= $this->input->post("txtName");

					$obj["statusID"] 	= $this->input->post("txtStatusID");

					

					//Validar Codigo de Bodega

					$objWarehouse		= $this->Warehouse_Model->getByCode($companyID,$obj["number"]);

					if($objWarehouse)

					{

						if($objWarehouse->warehouseID != $oldWarehouse->warehouseID){

							$this->core_web_notification->set_message(true,"Ya hay una bodega existente con ese codigo");

							redirect('app_inventory_warehouse/edit/companyID/'.$companyID."/warehouseID/".$warehouseID);

							exit;

						}

					}

					

					//Actualizar Bodega

					$result 			= $this->Warehouse_Model->update($companyID,$branchID,$warehouseID,$obj);

				

					if($this->db->trans_status() !== false){

						$this->db->trans_commit();

						$this->core_web_notification->set_message(false,SUCCESS);

					}

					else{

						$this->db->trans_rollback();						

						$this->core_web_notification->set_message(true,$this->db->_error_message());

					}

					redirect('app_inventory_warehouse/edit/companyID/'.$companyID."/warehouseID/".$warehouseID);

					

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

			

			//Cargar Libreria

			$this->load->model("core/Branch_Model"); 

			

			$companyID 							= $dataSession["user"]->companyID;

			$branchID 							= $dataSession["user"]->branchID;

			$roleID 							= $dataSession["role"]->roleID;

			$objData["objListBranch"] 			= $this->Branch_Model->getByCompany($companyID);

			$objData["objListWorkflowStage"]	= $this->core_web_workflow->getWorkflowInitStage("tb_warehouse","statusID",$companyID,$branchID,$roleID);

			

			//Renderizar Resultado 

			$dataSession["notification"]	= $this->core_web_error->get_error($dataSession["user"]->userID);

			$dataSession["message"]			= $this->core_web_notification->get_message();

			$dataSession["head"]			= $this->load->view('app_inventory_warehouse/news_head','',true);

			$dataSession["body"]			= $this->load->view('app_inventory_warehouse/news_body',$objData,true);

			$dataSession["script"]			= $this->load->view('app_inventory_warehouse/news_script','',true);  

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

			$objComponent		= $this->core_web_tools->getComponentIDBy_ComponentName("tb_warehouse");

			if(!$objComponent)

			throw new Exception("00409 EL COMPONENTE 'tb_warehouse' NO EXISTE...");

			

			

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

			$dataSession["head"]			= $this->load->view('app_inventory_warehouse/list_head','',true);

			$dataSession["footer"]			= $this->load->view('app_inventory_warehouse/list_footer','',true);

			$dataSession["body"]			= $dataViewRender; 

			$dataSession["script"]			= $this->load->view('app_inventory_warehouse/list_script','',true).$this->core_web_javascript->createVar("componentID",$objComponent->componentID);  

			$this->load->view("core_masterpage/default_masterpage",$dataSession);		

		}

		catch(Exception $ex){

			show_error($ex->getLine()." ".$ex->getMessage() ,500 );

		}

	}	

}

?>
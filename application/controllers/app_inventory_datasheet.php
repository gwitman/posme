<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');



class App_Inventory_Datasheet extends CI_Controller {

	

    public function __construct() {

       parent::__construct();

    }   

	function edit(){ 

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

						

						$resultPermission		= $this->core_web_permission->urlPermissionCmd($this->router->class,"edit",$this->config->item('url_suffix'),$dataSession,$dataSession["menuTop"],$dataSession["menuLeft"],$dataSession["menuBodyReport"],$dataSession["menuBodyTop"],$dataSession["menuHiddenPopup"]);

						if ($resultPermission 	== PERMISSION_NONE)

						throw new Exception(NOT_ALL_EDIT);			



			

			}	

			

			//Librerias		

			//

			////////////////////////////////////////

			////////////////////////////////////////

			////////////////////////////////////////

			$this->load->model("Item_Model");

			$this->load->model("Company_Component_Concept_Model");

			$this->load->model("Item_Data_Sheet_Model");

			$this->load->model("Item_Data_Sheet_Detail_Model");

			

			

			//Redireccionar datos

			$uri				= $this->uri->uri_to_assoc(3);

			$companyID			= $uri["companyID"];

			$itemDataSheetID	= $uri["itemDataSheetID"];	

			$branchID 			= $dataSession["user"]->branchID;

			$roleID 			= $dataSession["role"]->roleID;			

			if((!$companyID || !$itemDataSheetID))

			{ 

				redirect('app_inventory_datasheet/add');	

			} 		

			

			//Obtener el componente de Item

			$objComponent		= $this->core_web_tools->getComponentIDBy_ComponentName("tb_item");

			if(!$objComponent)

			throw new Exception("EL COMPONENTE 'tb_item' NO EXISTE...");

			

			//Obtener Informacion

			$dataView["componentItemID"] 			= $objComponent->componentID;

			$dataView["objItemDataSheet"]	 		= $this->Item_Data_Sheet_Model->get_rowByPK($itemDataSheetID);

			$dataView["objItemDataSheetDetail"]	 	= $this->Item_Data_Sheet_Detail_Model->get_rowByItemDataSheet($itemDataSheetID);

			$dataView["objItem"]					= $this->Item_Model->get_rowByPK($companyID,$dataView["objItemDataSheet"]->itemID);

			$dataView["objListWorkflowStage"]		= $this->core_web_workflow->getWorkflowStageByStageInit("tb_item_data_sheet","statusID",$dataView["objItemDataSheet"]->statusID,$companyID,$branchID,$roleID);			

			

			//Renderizar Resultado

			$dataSession["notification"]	= $this->core_web_error->get_error($dataSession["user"]->userID);

			$dataSession["message"]			= $this->core_web_notification->get_message();

			$dataSession["head"]			= $this->load->view('app_inventory_datasheet/edit_head',$dataView,true);

			$dataSession["body"]			= $this->load->view('app_inventory_datasheet/edit_body',$dataView,true);

			$dataSession["script"]			= $this->load->view('app_inventory_datasheet/edit_script',$dataView,true);  

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

			$this->load->model("Item_Model");  			

			

			//Nuevo Registro

			$companyID 			= $this->input->post("companyID");

			$itemID 			= $this->input->post("itemID");				

			

			if((!$companyID && !$itemID)){

					throw new Exception(NOT_PARAMETER);			

					 

			} 

			

			//OBTENER EL ITEM

			$obj 			= $this->Item_Model->get_rowByPK($companyID,$itemID);	

			//PERMISO SOBRE EL REGISTRO

			if ($resultPermission 	== PERMISSION_ME && ($obj->createdBy != $dataSession["user"]->userID))

			throw new Exception(NOT_DELETE);

			//PERMISO PUEDE ELIMINAR EL REGISTRO SEGUN EL WORKFLOW

			if(!$this->core_web_workflow->validateWorkflowStage("tb_item","statusID",$obj->statusID,COMMAND_ELIMINABLE,$dataSession["user"]->companyID,$dataSession["user"]->branchID,$dataSession["role"]->roleID))

			throw new Exception(NOT_WORKFLOW_DELETE);

			//VALIDAR CANTIDAD

			if($obj->quantity > 0)

			throw new Exception("EL REGISTRO NO PUEDE SER ELIMINADO, SU CANTIDAD ES MAYOR QUE  0");			

			//Eliminar el Registro

			$this->Item_Model->delete($companyID,$itemID);

					

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

	function searchItem(){

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

			

			//Load Modelos

			//

			////////////////////////////////////////

			////////////////////////////////////////

			////////////////////////////////////////

			$this->load->model("Item_Model");  

			

			//Nuevo Registro

			$itemNumber 			= $this->input->post("itemNumber");

			

			

			if(!$itemNumber){

					throw new Exception(NOT_PARAMETER);			

			} 			

			$obj 	= $this->Item_Model->get_rowByCode($dataSession["user"]->companyID,$itemNumber);	

			

			if(!$obj)

			throw new Exception("NO SE ENCONTRO EL REGISTRO");	

			

			

			$this->output->set_content_type('application/json');

			$this->output->set_output(json_encode(array(

				'error'   			=> false,

				'message' 			=> SUCCESS,

				'companyID' 		=> $obj->companyID,

				'itemID'			=> $obj->itemID

			)));

			

		}

		catch(Exception $ex){

			$this->output->set_content_type('application/json');

			$this->output->set_output(json_encode(array(

				'error'   => true,

				'message' => $ex->getLine()." ".$ex->getMessage()

			)));

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

			$this->load->model("Item_Model");

			$this->load->model("ItemWarehouse_Model");

			$this->load->model("ProviderItem_Model");

			$this->load->model("Company_Component_Concept_Model");

			$this->load->model("Item_Data_Sheet_Model");

			$this->load->model("Item_Data_Sheet_Detail_Model");



			$objComponent							= $this->core_web_tools->getComponentIDBy_ComponentName("tb_item");

			if(!$objComponent)

			throw new Exception("EL COMPONENTE 'tb_item' NO EXISTE...");

			

			$objComponentItemDataSheet				= $this->core_web_tools->getComponentIDBy_ComponentName("tb_item_data_sheet");

			if(!$objComponentItemDataSheet)

			throw new Exception("EL COMPONENTE 'tb_item_data_sheet' NO EXISTE...");



			$companyID 	= $dataSession["user"]->companyID;

			//Nuevo Registro	

			if( $method == "new"  ){

					

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

					

					$this->core_web_permission->getValueLicense($dataSession["user"]->companyID,$this->router->class."/"."index");
					//Ingresar Cuenta					

					$this->db->trans_begin();					

					$objItemDataSeet["itemID"]				= $this->input->post("txtItemID",0);

					$objItemDataSeet["version"]				= $this->input->post("txtVersion",0);

					$objItemDataSeet["statusID"]			= $this->input->post("txtStatusID",0);

					$objItemDataSeet["name"]				= $this->input->post("txtName","N/D");

					$objItemDataSeet["description"]			= $this->input->post("txtDescription","...");

					$objItemDataSeet["isActive"]			= 1;

					$this->core_web_auditoria->setAuditCreated($objItemDataSeet,$dataSession);

					$itemDataSheetID						= $this->Item_Data_Sheet_Model->insert($objItemDataSeet);

					



					//Crear la Carpeta para almacenar los Archivos del Item

					mkdir(PATH_FILE_OF_APP."/company_".$companyID."/component_".$objComponentItemDataSheet->componentID."/component_item_".$itemDataSheetID, 0700);

					

					if($this->db->trans_status() !== false){

						$this->db->trans_commit();						

						$this->core_web_notification->set_message(false,SUCCESS);

						redirect('app_inventory_datasheet/edit/companyID/'.$companyID."/itemDataSheetID/".$itemDataSheetID);						

					}

					else{

						$this->db->trans_rollback();						

						$this->core_web_notification->set_message(true,$this->db->_error_message());

						redirect('app_inventory_datasheet/add');	

					}

					 

			} 

			//Editar Registro

			else {

					

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

					 

					//PERMISO SOBRE EL REGISTRO

					$itemDataSheetID		= $this->input->post("txtItemDataSheetID",0);

					$objOldItemDataSheet	= $this->Item_Data_Sheet_Model->get_rowByPK($itemDataSheetID);

					if ($resultPermission 	== PERMISSION_ME && ($objOldItemDataSheet->createdBy != $dataSession["user"]->userID))

					throw new Exception(NOT_EDIT);

			

					//PERMISO PUEDE EDITAR EL REGISTRO

					if(!$this->core_web_workflow->validateWorkflowStage("tb_item_data_sheet","statusID",$objOldItemDataSheet->statusID,COMMAND_EDITABLE_TOTAL,$dataSession["user"]->companyID,$dataSession["user"]->branchID,$dataSession["role"]->roleID))

					throw new Exception(NOT_WORKFLOW_EDIT);					

					

					$this->db->trans_begin();						

					if(!$this->core_web_workflow->validateWorkflowStage("tb_item_data_sheet","statusID",$objOldItemDataSheet->statusID,COMMAND_EDITABLE,$dataSession["user"]->companyID,$dataSession["user"]->branchID,$dataSession["role"]->roleID))

					{

						//Actualizar Cuenta						
						$objNewItemDataSeet["itemID"]				= $this->input->post("txtItemID",0);
						$objNewItemDataSeet["version"]				= $this->input->post("txtVersion",0);
						$objNewItemDataSeet["statusID"]				= $this->input->post("txtStatusID",0);
						$objNewItemDataSeet["name"]					= $this->input->post("txtName","N/D");
						$objNewItemDataSeet["description"]			= $this->input->post("txtDescription","...");
						$objNewItemDataSeet["isActive"]				= 1;

						

						//Actualizar Objeto
						$row_affected 	= $this->Item_Data_Sheet_Model->update($itemDataSheetID,$objNewItemDataSeet);
						$messageTmp						= "";


						//Actualizar Detalle
						$arrayItemID				= $this->input->post("txtDetailItemID");
						$arrayItemDataSheetDetailID	= $this->input->post("txtDetailItemDataSheetDetailID");
						$arrayQuantity				= $this->input->post("txtDetailQuantity");

						log_message("ERROR","Revisar detalle de formula");
						log_message("ERROR",print_r($arrayItemID,true));
						log_message("ERROR",print_r($arrayItemDataSheetDetailID,true));
						log_message("ERROR",print_r($arrayQuantity,true));

						//Eliminar los registros que no estan
						$this->Item_Data_Sheet_Detail_Model->deleteWhereIDNotIn($itemDataSheetID,$arrayItemDataSheetDetailID);

						if (!empty($arrayItemID)) {
							foreach ($arrayItemID as $key => $value) {
								$itemID 								= $value;
								$dataSheetDetailID						= $arrayItemDataSheetDetailID[$key];
								$quantity								= $arrayQuantity[$key];

								
								if ($dataSheetDetailID == 0) {
									$dataNewItemDataSheetDetail = [];
									$dataNewItemDataSheetDetail["itemDataSheetID"] 	= $itemDataSheetID;
									$dataNewItemDataSheetDetail["itemID"] 			= $itemID;
									$dataNewItemDataSheetDetail["quantity"] 		= $quantity;
									$dataNewItemDataSheetDetail["relatedItemID"] 	= 0;
									$dataNewItemDataSheetDetail["isActive"] 		= true;
									
									$reId = $this->Item_Data_Sheet_Detail_Model->insert($dataNewItemDataSheetDetail);
								}
								else{
									$dataNewItemDataSheetDetail = [];
									$dataNewItemDataSheetDetail["itemID"] 	= $itemID;
									$dataNewItemDataSheetDetail["quantity"] = $quantity;
									$dataNewItemDataSheetDetail["isActive"] = true;
									$reId = $this->Item_Data_Sheet_Detail_Model->update($dataSheetDetailID,$dataNewItemDataSheetDetail);
								}

							}
						}

					}

					else{

						$objNewItemDataSheet["statusID"] 	= $this->input->post("txtStatusID");							
						$row_affected 						= $this->Item_Data_Sheet_Model->update($itemDataSheetID,$objNewItemDataSheet);
						$messageTmp							= "EL REGISTRO FUE EDITADO PARCIALMENTE, POR LA CONFIGURACION DE SU ESTADO ACTUAL";

					}

					

					

					if($this->db->trans_status() !== false){

						$this->db->trans_commit();

						$this->core_web_notification->set_message(false,SUCCESS." ".$messageTmp);

					}

					else{

						$this->db->trans_rollback();						

						$this->core_web_notification->set_message(true,$this->db->_error_message());

					}

					redirect('app_inventory_datasheet/edit/companyID/'.$companyID."/itemDataSheetID/".$itemDataSheetID);

					

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

			

			$companyID 							= $dataSession["user"]->companyID;

			$branchID 							= $dataSession["user"]->branchID;

			$roleID 							= $dataSession["role"]->roleID;

						

			//Obtener el componente de Item

			$objComponent		= $this->core_web_tools->getComponentIDBy_ComponentName("tb_item");

			if(!$objComponent)

			throw new Exception("EL COMPONENTE 'tb_item' NO EXISTE...");

			

			$dataView["objListWorkflowStage"]	= $this->core_web_workflow->getWorkflowInitStage("tb_item_data_sheet","statusID",$companyID,$branchID,$roleID);

			$dataView["componentItemID"] 		= $objComponent->componentID;

			

			//Renderizar Resultado 

			$dataSession["notification"]	= $this->core_web_error->get_error($dataSession["user"]->userID);

			$dataSession["message"]			= $this->core_web_notification->get_message();

			$dataSession["head"]			= $this->load->view('app_inventory_datasheet/news_head',$dataView,true);

			$dataSession["body"]			= $this->load->view('app_inventory_datasheet/news_body',$dataView,true);

			$dataSession["script"]			= $this->load->view('app_inventory_datasheet/news_script',$dataView,true);  

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

			//Obtener el componente Para mostrar la lista de AccountType

			$objComponent		= $this->core_web_tools->getComponentIDBy_ComponentName("tb_item_data_sheet");

			if(!$objComponent)

			throw new Exception("EL COMPONENTE 'tb_item_data_sheet' NO EXISTE...");

			

			

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

			$dataSession["head"]			= $this->load->view('app_inventory_datasheet/list_head','',true);

			$dataSession["footer"]			= $this->load->view('app_inventory_datasheet/list_footer','',true);

			$dataSession["body"]			= $dataViewRender; 

			$dataSession["script"]			= $this->load->view('app_inventory_datasheet/list_script','',true).$this->core_web_javascript->createVar("componentID",$objComponent->componentID);  

			$this->load->view("core_masterpage/default_masterpage",$dataSession);		

		}

		catch(Exception $ex){

			show_error($ex->getLine()." ".$ex->getMessage() ,500 );

		}

	}	

	

}

?>
<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class App_Inventory_Item extends CI_Controller {
	
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
			$this->load->model("ItemWarehouse_Model"); 			
			$this->load->model("ProviderItem_Model"); 			
			$this->load->model("Warehouse_Model");
			$this->load->model("ItemCategory_Model");
			$this->load->model("Company_Component_Concept_Model");
			
			
			
			//Redireccionar datos
			$uri			= $this->uri->uri_to_assoc(3);						
			$companyID		= $uri["companyID"];
			$itemID			= $uri["itemID"];				
			$callback		= array_key_exists("callback",$uri) ? $uri["callback"]: "false";
			$branchID 		= $dataSession["user"]->branchID;
			$roleID 		= $dataSession["role"]->roleID;			
			if((!$companyID || !$itemID))
			{ 
				redirect('app_inventory_item/add');	
			} 		
			
			//Obtener el componente Para mostrar la lista de AccountType
			$objComponent							= $this->core_web_tools->getComponentIDBy_ComponentName("tb_item");
			if(!$objComponent)
			throw new Exception("EL COMPONENTE 'tb_item' NO EXISTE...");
			
			//Obtener el componente Para mostrar la lista de AccountType
			$objComponentProvider							= $this->core_web_tools->getComponentIDBy_ComponentName("tb_provider");
			if(!$objComponentProvider)
			throw new Exception("EL COMPONENTE 'tb_provider' NO EXISTE...");
			
			
			//Obtener Informacion
			$dataView["objComponent"] 				= $objComponent;
			$dataView["componentProviderID"]		= $objComponentProvider->componentID;
			$dataView["objListConcept"]				= $this->Company_Component_Concept_Model->get_rowByComponentItemID($companyID,$objComponent->componentID,$itemID);
			$dataView["objItem"]	 				= $this->Item_Model->get_rowByPK($companyID,$itemID);
			$dataView["objItemWarehouse"]			= $this->ItemWarehouse_Model->get_rowByItemID($companyID,$itemID);			
			$dataView["objListWarehouse"]			= $this->Warehouse_Model->getByCompany($companyID);
			$dataView["objListProvider"]			= $this->ProviderItem_Model->get_rowByItemID($companyID,$itemID);
			$dataView["objListInventoryCategory"]	= $this->ItemCategory_Model->getByCompany($companyID);
			$dataView["objListWorkflowStage"]		= $this->core_web_workflow->getWorkflowStageByStageInit("tb_item","statusID",$dataView["objItem"]->statusID,$companyID,$branchID,$roleID);			
			$dataView["objListFamily"]				= $this->core_web_catalog->getCatalogAllItem("tb_item","familyID",$companyID);
			$dataView["objListUnitMeasure"]			= $this->core_web_catalog->getCatalogAllItem("tb_item","unitMeasureID",$companyID);
			$dataView["objListDisplay"]				= $this->core_web_catalog->getCatalogAllItem("tb_item","displayID",$companyID);
			$dataView["objListDisplayUnitMeasure"]	= $this->core_web_catalog->getCatalogAllItem("tb_item","displayUnitMeasureID",$companyID);
			$dataView["callback"]					= $callback;
			
					
			//Renderizar Resultado
			$dataSession["notification"]	= $this->core_web_error->get_error($dataSession["user"]->userID);
			$dataSession["message"]			= $this->core_web_notification->get_message();
			$dataSession["head"]			= $this->load->view('app_inventory_item/edit_head',$dataView,true);
			$dataSession["body"]			= $this->load->view('app_inventory_item/edit_body',$dataView,true);
			$dataSession["script"]			= $this->load->view('app_inventory_item/edit_script',$dataView,true);  
			$dataSession["footer"]			= "";				

			if($callback == "false")
				$this->load->view("core_masterpage/default_masterpage",$dataSession);	
			else
				$this->load->view("core_masterpage/default_popup",$dataSession);	

			
			
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
			$this->load->library('core_web_barcode/barcode.php');
			$this->load->model("Item_Model");
			$this->load->model("ItemWarehouse_Model");
			$this->load->model("ProviderItem_Model");
			$this->load->model("Provider_Model");
			$this->load->model("Company_Component_Concept_Model");
			
			$this->load->model("List_Price_Model");	
			$this->load->model("Price_Model");	
			$objComponent							= $this->core_web_tools->getComponentIDBy_ComponentName("tb_item");
			if(!$objComponent)
			throw new Exception("EL COMPONENTE 'tb_item' NO EXISTE...");
			
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
					
					//Ingresar Cuenta					
					$this->db->trans_begin();					
					$callback  								= $this->input->post("txtCallback"); 
					$objItem["companyID"]					= $dataSession["user"]->companyID;
					$objItem["branchID"] 					= $dataSession["user"]->branchID;					
					$objItem["inventoryCategoryID"] 		= $this->input->post("txtInventoryCategoryID");
					$objItem["familyID"] 					= $this->input->post("txtFamilyID");
					$objItem["itemNumber"] 					= $this->core_web_counter->goNextNumber($dataSession["user"]->companyID,$dataSession["user"]->branchID,"tb_item",0);
					$objItem["barCode"] 					= $this->input->post("txtBarCode") == "" ? "B".$objItem["itemNumber"].""  : $this->input->post("txtBarCode");
					$objItem["name"] 						= $this->input->post("txtName");
					$objItem["description"] 				= $this->input->post("txtDescription");
					$objItem["unitMeasureID"] 				= $this->input->post("txtUnitMeasureID");
					$objItem["displayID"] 					= $this->input->post("txtDisplayID");
					$objItem["capacity"] 					= $this->input->post("txtCapacity");
					$objItem["displayUnitMeasureID"] 		= $this->input->post("txtDisplayUnitMeasureID");
					$objItem["defaultWarehouseID"] 			= $this->input->post("txtDefaultWarehouseID");
					$objItem["quantity"] 					= 0;
					$objItem["quantityMax"] 				= $this->input->post("txtQuantityMax");
					$objItem["quantityMin"] 				= $this->input->post("txtQuantityMin");
					$objItem["cost"] 						= 0;
					$objItem["reference1"] 					= $this->input->post("txtReference1");
					$objItem["reference2"] 					= $this->input->post("txtReference2");
					$objItem["statusID"] 					= $this->input->post("txtStatusID");
					$objItem["isPerishable"] 				= $this->input->post("txtIsPerishable");
					$objItem["isInvoiceQuantityZero"] 		= $this->input->post("txtIsInvoiceQuantityZero");
					$objItem["factorBox"] 					= $this->input->post("txtFactorBox");
					$objItem["factorProgram"] 				= $this->input->post("txtFactorProgram");
					$objItem["isActive"] 					= 1;
					$this->core_web_auditoria->setAuditCreated($objItem,$dataSession);
					
					$itemID								= $this->Item_Model->insert($objItem);
					$companyID 							= $objItem["companyID"];
					//Crear la Carpeta para almacenar los Archivos del Item
					mkdir(PATH_FILE_OF_APP."/company_".$companyID."/component_".$objComponent->componentID."/component_item_".$itemID, 0700);
					
					
					//Guardar el Detalle de las Bodegas
					$objListWarehouseID					= $this->input->post("txtDetailWarehouseID");
					$objListWarehouseQuantityMax		= $this->input->post("txtDetailQuantityMax");
					$objListWarehouseQuantityMain		= $this->input->post("txtDetailQuantityMin");
					
				
					if($objListWarehouseID)
					foreach($objListWarehouseID as $key => $value){
						$objItemWarehouse["companyID"] 			= $companyID;
						$objItemWarehouse["branchID"] 			= $objItem["branchID"];
						$objItemWarehouse["warehouseID"] 		= $value;
						$objItemWarehouse["itemID"] 			= $itemID;
						$objItemWarehouse["quantity"] 			= 0;
						$objItemWarehouse["quantityMax"] 		= $objListWarehouseQuantityMax[$key];
						$objItemWarehouse["quantityMin"] 		= $objListWarehouseQuantityMain[$key];
						$this->ItemWarehouse_Model->insert($objItemWarehouse);
					}
					
					//Guardar proveedor por defecto
					$objParameterProviderDefault	= $this->core_web_parameter->getParameter("INVENTORY_ITEM_PROVIDER_DEFAULT",$companyID);
					$objParameterProviderDefault 	= $objParameterProviderDefault->value;
					$objTmpProvider					= [];
					$objTmpProvider["companyID"]	= $companyID;
					$objTmpProvider["branchID"]		= $objItem["branchID"];
					$objTmpProvider["itemID"]		= $itemID;
					$objTmpProvider["entityID"]		= $objParameterProviderDefault;
					$this->ProviderItem_Model->insert($objTmpProvider);
						
						
					//Ingresar la configuracion de precios
					//por defecto con 0% de utilidad
					$objParameterPriceDefault	= $this->core_web_parameter->getParameter("INVOICE_DEFAULT_PRICELIST",$companyID);
					$listPriceID 	= $objParameterPriceDefault->value;
					$objTipePrice 	= $this->core_web_catalog->getCatalogAllItem("tb_price","typePriceID",$companyID);
						
					foreach($objTipePrice as $price)
					{				
					
							log_message("ERROR","Lista de Precio");
							log_message("ERROR",print_r($objItem["itemNumber"],true));
							
							$typePriceID			= 0;							
							$objItemTmp				= $this->Item_Model->get_rowByCode($companyID,
								$objItem["itemNumber"]);
								
							log_message("ERROR",print_r($objItemTmp->itemID,true));
								
							$typePriceID			= $price->catalogItemID;
							
							//Insert register to price
							$dataPrice["companyID"] 	= $companyID;
							$dataPrice["listPriceID"] 	= $listPriceID;
							$dataPrice["itemID"] 		= $objItemTmp->itemID;
							$dataPrice["typePriceID"] 	= $typePriceID;							
							$dataPrice["price"] 		= 0;
							$dataPrice["percentage"] 	= 0;
									
							$this->Price_Model->insert($dataPrice);
					}
					
					//Generar la Imagen del Codigo de Barra
					$pathFileCodeBarra = PATH_FILE_OF_APP."/company_".$companyID.
					"/component_".$objComponent->componentID."/component_item_".$itemID."/barcode.jpg";
					
					
					$objBarCode 	= new barcode();
					$objBarCode->generate( $pathFileCodeBarra, $objItem["barCode"], "80", "horizontal", "code128", false, 1 );
					
					
					//Fin				
					if($this->db->trans_status() !== false){
						$this->db->trans_commit();						
						$this->core_web_notification->set_message(false,SUCCESS);
						redirect('app_inventory_item/edit/companyID/'.$companyID."/itemID/".$itemID."/callback/".$callback);						
					}
					else{
						$this->db->trans_rollback();						
						$this->core_web_notification->set_message(true,$this->db->_error_message());
						redirect('app_inventory_item/add');	
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
					$companyID 			= $dataSession["user"]->companyID;
					$itemID				= $this->input->post("txtItemID");
					$objOldItem 		= $this->Item_Model->get_rowByPK($companyID,$itemID);
					if ($resultPermission 	== PERMISSION_ME && ($objOldItem->createdBy != $dataSession["user"]->userID))
					throw new Exception(NOT_EDIT);
			
					//PERMISO PUEDE EDITAR EL REGISTRO
					if(!$this->core_web_workflow->validateWorkflowStage("tb_item","statusID",$objOldItem->statusID,COMMAND_EDITABLE_TOTAL,$dataSession["user"]->companyID,$dataSession["user"]->branchID,$dataSession["role"]->roleID))
					throw new Exception(NOT_WORKFLOW_EDIT);					
					
					
					
					//Crear la Carpeta para almacenar los Archivos del Item
					$directoryItem = PATH_FILE_OF_APP."/company_".$companyID."/component_".$objComponent->componentID."/component_item_".$itemID;
					if(!file_exists($directoryItem))
					mkdir( $directoryItem,0700);
					
					$this->db->trans_begin();	
					$callback  	= $this->input->post("txtCallback"); 									
					if(!$this->core_web_workflow->validateWorkflowStage("tb_item","statusID",$objOldItem->statusID,COMMAND_EDITABLE,$dataSession["user"]->companyID,$dataSession["user"]->branchID,$dataSession["role"]->roleID))
					{
						//Actualizar Cuenta								
						$objNewItem["inventoryCategoryID"] 			= $this->input->post("txtInventoryCategoryID");
						$objNewItem["familyID"] 					= $this->input->post("txtFamilyID");												
						$objNewItem["barCode"] 						= $this->input->post("txtBarCode") == "" ? "B".$objOldItem->itemNumber  : $this->input->post("txtBarCode");
						$objNewItem["name"] 						= $this->input->post("txtName");
						$objNewItem["description"] 					= $this->input->post("txtDescription");
						$objNewItem["unitMeasureID"] 				= $this->input->post("txtUnitMeasureID");
						$objNewItem["displayID"] 					= $this->input->post("txtDisplayID");
						$objNewItem["capacity"] 					= $this->input->post("txtCapacity");
						$objNewItem["displayUnitMeasureID"] 		= $this->input->post("txtDisplayUnitMeasureID");
						$objNewItem["defaultWarehouseID"] 			= $this->input->post("txtDefaultWarehouseID");						
						$objNewItem["quantityMax"] 					= $this->input->post("txtQuantityMax");
						$objNewItem["quantityMin"] 					= $this->input->post("txtQuantityMin");						
						$objNewItem["reference1"] 					= $this->input->post("txtReference1");
						$objNewItem["reference2"] 					= $this->input->post("txtReference2");
						$objNewItem["statusID"] 					= $this->input->post("txtStatusID");
						$objNewItem["isPerishable"] 				= $this->input->post("txtIsPerishable");
						$objNewItem["isInvoiceQuantityZero"] 		= $this->input->post("txtIsInvoiceQuantityZero");
						$objNewItem["factorBox"] 					= $this->input->post("txtFactorBox");
						$objNewItem["factorProgram"] 				= $this->input->post("txtFactorProgram");
						//Actualizar Objeto
						$row_affected 	= $this->Item_Model->update($companyID,$itemID,$objNewItem);
			
						//Guardar el detalle de Conceptos
						$this->Company_Component_Concept_Model->deleteWhereComponentItemID($companyID,$objComponent->componentID,$itemID);
						$objListConcept						= $this->input->post("txtDetailConceptName");
						if($objListConcept)
						foreach($objListConcept as $key => $value){
							$objTmpConcept						= [];
							$objTmpConcept["companyID"]			= $companyID;
							$objTmpConcept["componentID"]		= $objComponent->componentID;
							$objTmpConcept["componentItemID"]	= $itemID;
							$objTmpConcept["name"]				= $value;
							$objTmpConcept["valueIn"]			= $this->input->post("txtDetailConceptValueIn")[$key];
							$objTmpConcept["valueOut"]			= $this->input->post("txtDetailConceptValueOut")[$key];
							$this->Company_Component_Concept_Model->insert($objTmpConcept);
						}
						
						//Guardar el detalle de Proveedores
						$this->ProviderItem_Model->deleteWhereItemID($companyID,$itemID);
						$objListProviderID					= $this->input->post("txtProviderEntityID");
						if($objListProviderID)
						foreach($objListProviderID as $key => $value){
							$objTmpProvider					= [];
							$objTmpProvider["companyID"]	= $objOldItem->companyID;
							$objTmpProvider["branchID"]		= $objOldItem->branchID;
							$objTmpProvider["itemID"]		= $itemID;
							$objTmpProvider["entityID"]		= $value;
							$this->ProviderItem_Model->insert($objTmpProvider);
						}
						
						//Guardar el Detalle las Bodegas
						$objListDetailWarehouseID			= $this->input->post("txtDetailWarehouseID");
						$objListDetailWarehouseQuantityMax	= $this->input->post("txtDetailQuantityMax");
						$objListDetailWarehouseQuantityMin	= $this->input->post("txtDetailQuantityMin");
					
						//Eliminar las Bodegas que no estan
						$this->ItemWarehouse_Model->deleteWhereIDNotIn($companyID,$itemID,$objListDetailWarehouseID);
						
						if($objListDetailWarehouseID)
						foreach($objListDetailWarehouseID as $key => $value){
							$objWarehouseDetail["quantityMax"] 			= $objListDetailWarehouseQuantityMax[$key];
							$objWarehouseDetail["quantityMin"] 			= $objListDetailWarehouseQuantityMin[$key];
							$warehouseID 								= $objListDetailWarehouseID[$key];
							$objOldItemWarehouse 						= $this->ItemWarehouse_Model->getByPK($companyID,$itemID,$warehouseID);
							if($objOldItemWarehouse){
								$this->ItemWarehouse_Model->update($companyID,$itemID,$warehouseID,$objWarehouseDetail);
							}
							else{								
								$objWarehouseDetail["companyID"] 	= $companyID;
								$objWarehouseDetail["warehouseID"] 	= $warehouseID;
								$objWarehouseDetail["itemID"] 		= $itemID;
								$objWarehouseDetail["quantity"] 	= 0;
								$objWarehouseDetail["branchID"] 	= $dataSession["user"]->branchID;
								$this->ItemWarehouse_Model->insert($objWarehouseDetail);
							}
						}
						
						
						//Ingresar la configuracion de precios
						//por defecto con 0% de utilidad
						$objParameterPriceDefault	= $this->core_web_parameter->getParameter("INVOICE_DEFAULT_PRICELIST",$companyID);
						$listPriceID 	= $objParameterPriceDefault->value;
						$objTipePrice 	= 
						$this->core_web_catalog->getCatalogAllItem("tb_price","typePriceID",
							$companyID);
							
						foreach($objTipePrice as $price)
						{				
								
								$typePriceID				= 0;	
								$typePriceID				= $price->catalogItemID;
								
								
								//Insert register to price
								$dataPrice["companyID"] 	= $companyID;
								$dataPrice["listPriceID"] 	= $listPriceID;
								$dataPrice["itemID"] 		= $itemID;
								$dataPrice["typePriceID"] 	= $typePriceID;							
								$dataPrice["price"] 		= 0;
								$dataPrice["percentage"] 	= 0;
										
								$objPrice = $this->Price_Model->get_rowByPK($companyID,$listPriceID,$itemID,$typePriceID);
								
								if($objPrice == null )
								{
									$this->Price_Model->insert($dataPrice);
								}
						}
						
						$messageTmp						= "";
					}
					else{
						$objNewItem["statusID"] 		= $this->input->post("txtStatusID");							
						$row_affected 					= $this->Item_Model->update($companyID,$itemID,$objNewItem);
						$messageTmp						= "EL REGISTRO FUE EDITADO PARCIALMENTE, POR LA CONFIGURACION DE SU ESTADO ACTUAL";
					}
					
					
					//Generar la Imagen del Codigo de Barra
					$pathFileCodeBarra = PATH_FILE_OF_APP."/company_".$companyID.
					"/component_".$objComponent->componentID."/component_item_".$itemID."/barcode.jpg";
					
					
					$objBarCode 	= new barcode();
					$objBarCode->generate( $pathFileCodeBarra, $objNewItem["barCode"], "40", "horizontal", "code128", false, 3 );
					
					
					
					if($this->db->trans_status() !== false){
						$this->db->trans_commit();
						$this->core_web_notification->set_message(false,SUCCESS." ".$messageTmp);
					}
					else{
						$this->db->trans_rollback();						
						$this->core_web_notification->set_message(true,$this->db->_error_message());
					}
					redirect('app_inventory_item/edit/companyID/'.$companyID."/itemID/".$itemID."/callback/".$callback);
					
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
			 
			$this->load->model("Item_Model");
			$this->load->model("ItemWarehouse_Model");
			$this->load->model("Warehouse_Model");
			$this->load->model("ItemCategory_Model");
			
			$uri								= $this->uri->uri_to_assoc(3);
			$companyID 							= $dataSession["user"]->companyID;
			$branchID 							= $dataSession["user"]->branchID;
			$roleID 							= $dataSession["role"]->roleID;
			$callback							= array_key_exists("callback",$uri) ? $uri["callback"]: "false";
			
			$objParameterWarehouseDefault	= $this->core_web_parameter->getParameter("INVENTORY_ITEM_WAREHOUSE_DEFAULT",$companyID);
			$warehouseDefault 				= $objParameterWarehouseDefault->value;
			
			
			$dataView["objListWarehouse"]			= $this->Warehouse_Model->getByCompany($companyID);
			$dataView["objListInventoryCategory"]	= $this->ItemCategory_Model->getByCompany($companyID);
			$dataView["objListWorkflowStage"]		= $this->core_web_workflow->getWorkflowInitStage("tb_item","statusID",$companyID,$branchID,$roleID);
			$dataView["objListFamily"]				= $this->core_web_catalog->getCatalogAllItem("tb_item","familyID",$companyID);
			$dataView["objListUnitMeasure"]			= $this->core_web_catalog->getCatalogAllItem("tb_item","unitMeasureID",$companyID);
			$dataView["objListDisplay"]				= $this->core_web_catalog->getCatalogAllItem("tb_item","displayID",$companyID);
			$dataView["objListDisplayUnitMeasure"]	= $this->core_web_catalog->getCatalogAllItem("tb_item","displayUnitMeasureID",$companyID);
			$dataView["warehouseDefault"]			= $warehouseDefault;
			$dataView["callback"]					= $callback;
			
			//Renderizar Resultado 
			$dataSession["notification"]	= $this->core_web_error->get_error($dataSession["user"]->userID);
			$dataSession["message"]			= $this->core_web_notification->get_message();
			$dataSession["head"]			= $this->load->view('app_inventory_item/news_head',$dataView,true);
			$dataSession["body"]			= $this->load->view('app_inventory_item/news_body',$dataView,true);
			$dataSession["script"]			= $this->load->view('app_inventory_item/news_script',$dataView,true);  
			$dataSession["footer"]			= "";

			if($callback == "false")
				$this->load->view("core_masterpage/default_masterpage",$dataSession);	
			else
				$this->load->view("core_masterpage/default_popup",$dataSession);	
			
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
			log_message("ERROR",print_r($dataSession,true));
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
			$objComponent		= $this->core_web_tools->getComponentIDBy_ComponentName("tb_item");
			if(!$objComponent)
			throw new Exception("EL COMPONENTE 'tb_item' NO EXISTE...");
			
			
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
			$dataSession["head"]			= $this->load->view('app_inventory_item/list_head','',true);
			$dataSession["footer"]			= $this->load->view('app_inventory_item/list_footer','',true);
			$dataSession["body"]			= $dataViewRender; 
			$dataSession["script"]			= $this->load->view('app_inventory_item/list_script','',true).$this->core_web_javascript->createVar("componentID",$objComponent->componentID);  
			
			$this->load->view("core_masterpage/default_masterpage",$dataSession);		
		}
		catch(Exception $ex){
			show_error($ex->getLine()." ".$ex->getMessage() ,500 );
		}
	}	
	function popup_add_concept(){
			
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
		
			//Renderizar Resultado
			$dataSession["message"]		= "";
			$dataSession["head"]		= $this->load->view('app_inventory_item/popup_addconcept_head','',true);
			$dataSession["body"]		= $this->load->view('app_inventory_item/popup_addconcept_body','',true);
			$dataSession["script"]		= $this->load->view('app_inventory_item/popup_addconcept_script','',true);  
			$this->load->view("core_masterpage/default_popup",$dataSession);				
			
	}
	
	
	function popup_add_renderimg($companyID,$componentID,$itemID){
		//Extraer el codigo de barra			
		$pathFileCodeBarra = PATH_FILE_OF_APP."/company_".$companyID."/component_".$componentID."/component_item_".$itemID."/barcode.jpg";
		
		
		$type = 'image/jpg';
		header('Content-Type:'.$type);
		header('Content-Length: ' . filesize($pathFileCodeBarra));
		readfile($pathFileCodeBarra);

		exit;
	}
	
}
?>
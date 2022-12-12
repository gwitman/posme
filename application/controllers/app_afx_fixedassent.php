<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class App_Afx_Fixedassent extends CI_Controller {
	
    public function __construct() {
       parent::__construct();
    }
	function updateElement($dataSession){
		try{
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
			
			$this->load->model("Fixed_Assent_Model");	
			
			//Obtener el Componente de Transacciones Other Input to Inventory
			$objComponent							= $this->core_web_tools->getComponentIDBy_ComponentName("tb_fixed_assent");
			if(!$objComponent)
			throw new Exception("EL COMPONENTE 'tb_fixed_assent' NO EXISTE...");
			
			
			$branchID 								= $dataSession["user"]->branchID;
			$roleID 								= $dataSession["role"]->roleID;
			$companyID 								= $dataSession["user"]->companyID;
			
			$companyID_ 							= $this->input->post("txtCompanyID");
			$branchID_								= $this->input->post("txtBranchID");
			$fixedAssentID_							= $this->input->post("txtFixedAssentID");
			
			$objFA									= $this->Fixed_Assent_Model->get_rowByPK($companyID_,$branchID_,$fixedAssentID_);
			$oldStatusID 							= $objFA->statusID;
			
			//Validar Edicion por el Usuario
			if ($resultPermission 	== PERMISSION_ME && ($objFA->createdBy != $dataSession["user"]->userID))
			throw new Exception(NOT_EDIT);
			
			//Validar si el estado permite editar
			if(!$this->core_web_workflow->validateWorkflowStage("tb_fixed_assent","statusID",$objFA->statusID,COMMAND_EDITABLE_TOTAL,$dataSession["user"]->companyID,$dataSession["user"]->branchID,$dataSession["role"]->roleID))
			throw new Exception(NOT_WORKFLOW_EDIT);					
			
			
			
			
			$this->db->trans_begin();			
			//El Estado solo permite editar el workflow
			if($this->core_web_workflow->validateWorkflowStage("tb_fixed_assent","statusID",$oldStatusID,COMMAND_EDITABLE,$dataSession["user"]->companyID,$dataSession["user"]->branchID,$dataSession["role"]->roleID)){				
				
				$objFA 					= NULL;		
				$objFA["statusID"] 		= $this->input->post('txtStatusID','');
				$this->Fixed_Assent_Model->update($companyID_,$branchID_,$fixedAssentID_,$objFA);
			}
			else{
				
				$objFA 								= NULL;										
				$objFA["colorID"]					= $this->input->post('txtColorID','');
				$objFA["typeID"]					= $this->input->post('txtTypeID','');
				$objFA["categoryID"]				= $this->input->post('txtCategoryID','');
				$objFA["typeDepresiationID"]		= $this->input->post('txtTypeDepresiationID','');
				$objFA["statusID"]					= $this->input->post('txtStatusID','');
				$objFA["isForaneo"]					= $this->input->post('txtIsForaneo','');
				$objFA["name"]						= $this->input->post('txtName','');
				$objFA["description"]				= $this->input->post('txtDescription','');
				$objFA["modelNumber"]				= $this->input->post('txtModelNumber','');
				$objFA["marca"]						= $this->input->post('txtMarca','');
				$objFA["chasisNumber"]				= $this->input->post('txtChasisNumber','');
				$objFA["reference1"]				= $this->input->post('txtReference1','');
				$objFA["reference2"]				= $this->input->post('txtReference2','');
				$objFA["year"]						= $this->input->post('txtYear','');
				$objFA["asignedEmployeeID"]			= $this->input->post('txtAsignedEmployeeID','');
				$objFA["yearOfUtility"]				= $this->input->post('txtYearUtility','');
				$objFA["priceStart"]				= $this->input->post('txtPriceStart','');
			
				$this->Fixed_Assent_Model->update($companyID_,$branchID_,$fixedAssentID_,$objFA);
			
			}
			
			//Confirmar Entidad
			if($this->db->trans_status() !== false){
				$this->db->trans_commit();						
				$this->core_web_notification->set_message(false,SUCCESS);
				redirect('app_afx_fixedassent/edit/companyID/'.$companyID_."/branchID/".$branchID_."/fixedAssentID/".$fixedAssentID_);
			}
			else{
				$this->db->trans_rollback();						
				$this->core_web_notification->set_message(true,$this->db->_error_message());
				redirect('app_afx_fixedassent/add');	
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
			$this->load->model("Fixed_Assent_Model");
			$this->load->model("Employee_Model");
			$this->load->model("Natural_Model");
			$this->load->model("Entity_Model");
			
			//Redireccionar datos
			$uri			= $this->uri->uri_to_assoc(3);
						
			$companyID		= $uri["companyID"];
			$branchID		= $uri["branchID"];	
			$fixedAssentID	= $uri["fixedAssentID"];	
			$branchIDUser	= $dataSession["user"]->branchID;
			$roleID 		= $dataSession["role"]->roleID;			
			if((!$companyID || !$branchID || !$fixedAssentID))
			{ 
				redirect('app_afx_fixedassent/add');	
			} 		
			
			
			//Obtener el Registro			
			$datView["objFA"]	 				= $this->Fixed_Assent_Model->get_rowByPK($companyID,$branchID,$fixedAssentID);			
			$datView["objAsignedEmployee"] 		= $this->Employee_Model->get_rowByEntityID($companyID,$datView["objFA"]->asignedEmployeeID); 
			$datView["objAsignedNatural"]		= $datView["objAsignedEmployee"] == null ? $datView["objAsignedEmployee"] : $this->Natural_Model->get_rowByPK($companyID,$datView["objAsignedEmployee"]->branchID,$datView["objAsignedEmployee"]->entityID);
			
			
			
			$objComponent						= $this->core_web_tools->getComponentIDBy_ComponentName("tb_employee");
			if(!$objComponent)
			throw new Exception("00409 EL COMPONENTE 'tb_employee' NO EXISTE...");
			
			$objComponentFA						= $this->core_web_tools->getComponentIDBy_ComponentName("tb_fixed_assent");
			if(!$objComponentFA)
			throw new Exception("00409 EL COMPONENTE 'tb_fixed_assent' NO EXISTE...");
			
			//Obtener Informacion
			$datView["objListWorkflowStage"]		= $this->core_web_workflow->getWorkflowStageByStageInit("tb_fixed_assent","statusID",$datView["objFA"]->statusID,$companyID,$branchIDUser,$roleID);
			$datView["componentEmployeeID"] 		= $objComponent->componentID;
			$datView["objComponentFA"] 				= $objComponentFA;
			$datView["objListColor"]				= $this->core_web_catalog->getCatalogAllItem("tb_fixed_assent","colorID",$companyID);			
			$datView["objListCategory"]				= $this->core_web_catalog->getCatalogAllItem("tb_fixed_assent","categoryID",$companyID);						
			$datView["objListType"]					= $this->core_web_catalog->getCatalogAllItem("tb_fixed_assent","typeID",$companyID);					
			$datView["objListTypeDepresiation"]		= $this->core_web_catalog->getCatalogAllItem("tb_fixed_assent","typeDepresiationID",$companyID);
			
			
			
			//Renderizar Resultado
			$dataSession["notification"]	= $this->core_web_error->get_error($dataSession["user"]->userID);
			$dataSession["message"]			=  $this->core_web_notification->get_message();
			$dataSession["head"]			= $this->load->view('app_afx_fixedassent/edit_head',$datView,true);
			$dataSession["body"]			= $this->load->view('app_afx_fixedassent/edit_body',$datView,true);
			$dataSession["script"]			= $this->load->view('app_afx_fixedassent/edit_script',$datView,true);  
			$dataSession["footer"]			= "";				
			$this->load->view("core_masterpage/default_masterpage",$dataSession);	
			
		}
		catch(Exception $ex){
			show_error($ex->getLine()." ".$ex->getMessage() ,500 );
		}	
	}
	
	function insertElement($dataSession){
	
		try{
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
			
			$this->load->model("Fixed_Assent_Model");	;
			
			
			//Obtener el Componente de Transacciones Other Input to Inventory
			$objComponent							= $this->core_web_tools->getComponentIDBy_ComponentName("tb_fixed_assent");
			if(!$objComponent)
			throw new Exception("EL COMPONENTE 'tb_fixed_assent' NO EXISTE...");
			
			
			//Obtener transaccion
			$companyID 							= $dataSession["user"]->companyID;			
			$objFA["companyID"] 				= $dataSession["user"]->companyID;			
			$objFA["branchID"]					= $dataSession["user"]->branchID;
			$objFA["isActive"]					= true;
			$objFA["fixedAssentCode"]			= $this->core_web_counter->goNextNumber($dataSession["user"]->companyID,$dataSession["user"]->branchID,"tb_fixed_assent",0);
			$objFA["colorID"]					= $this->input->post('txtColorID','');
			$objFA["typeID"]					= $this->input->post('txtTypeID','');
			$objFA["categoryID"]				= $this->input->post('txtCategoryID','');
			$objFA["typeDepresiationID"]		= $this->input->post('txtTypeDepresiationID','');
			$objFA["statusID"]					= $this->input->post('txtStatusID','');
			$objFA["isForaneo"]					= $this->input->post('txtIsForaneo','');			
			$objFA["name"]						= $this->input->post('txtName','');
			$objFA["description"]				= $this->input->post('txtDescription','');
			$objFA["modelNumber"]				= $this->input->post('txtModelNumber','');
			$objFA["marca"]						= $this->input->post('txtMarca','');
			$objFA["chasisNumber"]				= $this->input->post('txtChasisNumber','');
			$objFA["reference1"]				= $this->input->post('txtReference1','');
			$objFA["reference2"]				= $this->input->post('txtReference2','');
			$objFA["year"]						= $this->input->post('txtYear','');
			$objFA["asignedEmployeeID"]			= $this->input->post('txtAsignedEmployeeID','');
			$objFA["yearOfUtility"]				= $this->input->post('txtYearUtility','');
			$objFA["priceStart"]				= $this->input->post('txtPriceStart','');
			
			
			$this->core_web_auditoria->setAuditCreated($objFA,$dataSession);
			
			$this->db->trans_begin();
			$fixedAssentID = $this->Fixed_Assent_Model->insert($objFA);
			
			
			//Crear la Carpeta para almacenar los Archivos del Cliente
			mkdir(PATH_FILE_OF_APP."/company_".$companyID."/component_".$objComponent->componentID."/component_item_".$fixedAssentID, 0700);
			
			if($this->db->trans_status() !== false){
				$this->db->trans_commit();						
				$this->core_web_notification->set_message(false,SUCCESS);
				redirect('app_afx_fixedassent/edit/companyID/'.$companyID."/branchID/".$objFA["branchID"]."/fixedAssentID/".$fixedAssentID);
			}
			else{
				$this->db->trans_rollback();						
				$this->core_web_notification->set_message(true,$this->db->_error_message());
				redirect('app_afx_fixedassent/add');	
			}
			
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
			$this->load->model("Fixed_Assent_Model");  
			
			//Nuevo Registro
			$companyID 				= $this->input->post("companyID");
			$branchID 				= $this->input->post("branchID");				
			$fixedAssentID 			= $this->input->post("fixedAssentID");				
			
			if((!$companyID && !$branchID && !$fixedAssentID)){
					throw new Exception(NOT_PARAMETER);			
					 
			} 
			
			//OBTENER EL EMPLEADO
			$objFixedAssent 		= $this->Fixed_Assent_Model->get_rowByPK($companyID,$branchID,$fixedAssentID);	
			
			
			//PERMISO SOBRE EL REGISTRO
			if ($resultPermission 	== PERMISSION_ME && ($objFixedAssent->createdBy != $dataSession["user"]->userID))
			throw new Exception(NOT_DELETE);
			
			
			//PERMISO PUEDE ELIMINAR EL REGISTRO SEGUN EL WORKFLOW
			if(!$this->core_web_workflow->validateWorkflowStage("tb_fixed_assent","statusID",$objFixedAssent->statusID,COMMAND_ELIMINABLE,$dataSession["user"]->companyID,$dataSession["user"]->branchID,$dataSession["role"]->roleID))
			throw new Exception(NOT_WORKFLOW_DELETE);
			
			//Eliminar el Registro
			$this->Fixed_Assent_Model->delete($companyID,$branchID,$fixedAssentID);
					
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
	
	function save($mode){
			//AUTENTICADO
			if(!$this->core_web_authentication->isAuthenticated())
			throw new Exception(USER_NOT_AUTENTICATED);
			$dataSession		= $this->session->all_userdata();
			
			//Validar Formulario						
			$this->form_validation->set_rules("txtName","Nombre","required");
			$this->form_validation->set_rules("txtCategoryID","Categoria","required");
			$this->form_validation->set_rules("txtTypeID","Tipo","required");
				
				
			//Validar Formulario
			if(!$this->form_validation->run()){
				$stringValidation = $this->core_web_tools->formatMessageError(validation_errors());
				$this->core_web_notification->set_message(true,$stringValidation);
				redirect('app_afx_fixedassent/add');
				exit;
			} 
			
			//Guardar o Editar Registro						
			if($mode == "new"){
				$this->insertElement($dataSession);
			}
			else if ($mode == "edit"){
				$this->updateElement($dataSession);
			}
			else{
				$stringValidation = "El modo de operacion no es correcto (new|edit)";
				$this->core_web_notification->set_message(true,$stringValidation);
				redirect('app_afx_fixedassent/add');
				exit;
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
			
			$dataView							= null;			
			$companyID 							= $dataSession["user"]->companyID;
			$branchID 							= $dataSession["user"]->branchID;
			$roleID 							= $dataSession["role"]->roleID;			
			$objComponent						= $this->core_web_tools->getComponentIDBy_ComponentName("tb_employee");
			
			if(!$objComponent)
			throw new Exception("EL COMPONENTE 'tb_employee' NO EXISTE...");
			
			
			$dataView["componentEmployeeID"] 			= $objComponent->componentID;			
			$dataView["objListWorkflowStage"]			= $this->core_web_workflow->getWorkflowInitStage("tb_fixed_assent","statusID",$companyID,$branchID,$roleID);
			$dataView["objListColor"]					= $this->core_web_catalog->getCatalogAllItem("tb_fixed_assent","colorID",$companyID);			
			$dataView["objListCategory"]				= $this->core_web_catalog->getCatalogAllItem("tb_fixed_assent","categoryID",$companyID);						
			$dataView["objListType"]					= $this->core_web_catalog->getCatalogAllItem("tb_fixed_assent","typeID",$companyID);					
			$dataView["objListTypeDepresiation"]		= $this->core_web_catalog->getCatalogAllItem("tb_fixed_assent","typeDepresiationID",$companyID);
			
			//Renderizar Resultado 
			$dataSession["notification"]	= $this->core_web_error->get_error($dataSession["user"]->userID);
			$dataSession["message"]			= $this->core_web_notification->get_message();
			$dataSession["head"]			= $this->load->view('app_afx_fixedassent/news_head',$dataView,true);
			$dataSession["body"]			= $this->load->view('app_afx_fixedassent/news_body',$dataView,true);
			$dataSession["script"]			= $this->load->view('app_afx_fixedassent/news_script',$dataView,true);  
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
			$objComponent		= $this->core_web_tools->getComponentIDBy_ComponentName("tb_fixed_assent");
			if(!$objComponent)
			throw new Exception("00409 EL COMPONENTE 'tb_fixed_assent' NO EXISTE...");
			
			
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
			$dataSession["head"]			= $this->load->view('app_afx_fixedassent/list_head','',true);
			$dataSession["footer"]			= $this->load->view('app_afx_fixedassent/list_footer','',true);
			$dataSession["body"]			= $dataViewRender; 
			$dataSession["script"]			= $this->load->view('app_afx_fixedassent/list_script','',true).$this->core_web_javascript->createVar("componentID",$objComponent->componentID);  
			$this->load->view("core_masterpage/default_masterpage",$dataSession);		
		}
		catch(Exception $ex){
			show_error($ex->getLine()." ".$ex->getMessage() ,500 );
		}
	}
	
}
?>
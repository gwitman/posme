<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class App_Transaction_Config extends CI_Controller {
	
    public function __construct() {
       parent::__construct();
    }
	function apiDeleteProfileDetail(){
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
				throw new Exception(NOT_ACCESS_CONTROL." ".$this->router->class);		
			}			
			
			//Obtener Parametros
			$companyID 				= $dataSession["user"]->companyID;
			$transactionID 			= $this->input->post("transactionID");		
			$transactionCausalID 	= $this->input->post("transactionCausalID");
			$profileDetailID 		= $this->input->post("profileDetailID");
			
			if((!$companyID) || (!$transactionID) || (!$transactionCausalID) || (!$profileDetailID)) {
					throw new Exception(NOT_PARAMETER);	
			} 
			
			//Load Library			
			$this->load->model("Transaction_Profile_Detail_Model");	
			$row				= $this->Transaction_Profile_Detail_Model->delete($companyID,$transactionID,$transactionCausalID,$profileDetailID);
			
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
		}
	}
	function apiInsertProfileDetail(){
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
				throw new Exception(NOT_ACCESS_CONTROL." ".$this->router->class);		
			}			
			
			//Obtener Parametros
			$companyID 				= $dataSession["user"]->companyID;
			$transactionID 			= $this->input->post("transactionID");		
			$transactionCausalID 	= $this->input->post("transactionCausalID");
			$centerCostID 			= $this->input->post("centerCostID");
			$centerCostDescription 	= $this->input->post("centerCostDescription");
			$accountID 				= $this->input->post("accountID");
			$accountDescription 	= $this->input->post("accountDescription");
			$sign 					= $this->input->post("sign");
			$conceptID 				= $this->input->post("conceptID");
			$conceptDescription 	= $this->input->post("conceptDescription");
			if((!$companyID) || (!$transactionID) || (!$transactionCausalID)){
					throw new Exception(NOT_PARAMETER);	
			} 
			
			//Load Library			
			$this->load->model("Transaction_Profile_Detail_Model");	
			$objTPDNew["companyID"] 			= $companyID;
			$objTPDNew["transactionID"] 		= $transactionID;
			$objTPDNew["transactionCausalID"] 	= $transactionCausalID;
			$objTPDNew["conceptID"] 			= $conceptID;
			$objTPDNew["accountID"] 			= $accountID;
			$objTPDNew["classID"] 				= $centerCostID;
			$objTPDNew["sign"] 					= $sign;
			$profileDetailID					= $this->Transaction_Profile_Detail_Model->insert($objTPDNew);
			$objTPD 							= $objTPDNew;
			$objTPD["profileDetailID"]			= $profileDetailID;
			$objTPD["centerCostDescription"] 	= $this->input->post("centerCostDescription");
			$objTPD["accountDescription"] 		= $this->input->post("accountDescription");
			$objTPD["conceptDescription"] 		= $this->input->post("conceptDescription");
			
			$this->output->set_content_type('application/json');
			$this->output->set_output(json_encode(array(
				'error'   => false,
				'message' => SUCCESS,
				'objProfileDetail'   => $objTPD
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
	function apiGetInforCausal(){
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
				throw new Exception(NOT_ACCESS_CONTROL." ".$this->router->class);		
			}			
			
			//Obtener Parametros
			$companyID 				= $dataSession["user"]->companyID;
			$transactionID 			= $this->input->post("transactionID");		
			$transactionCausalID 	= $this->input->post("transactionCausalID");		
			if((!$companyID) || (!$transactionID) || (!$transactionCausalID)){
					throw new Exception(NOT_PARAMETER);	
			} 
			
			//Load Library
			$this->load->model("Transaction_Causal_Model");
			$this->load->model("Transaction_Profile_Detail_Model");
			
			$objTC 		= $this->Transaction_Causal_Model->getByCompanyAndTransactionAndCausal($companyID,$transactionID,$transactionCausalID);
			$objTPD		= $this->Transaction_Profile_Detail_Model->getByCompanyAndTransactionAndCausal($companyID,$transactionID,$transactionCausalID);
			
			$this->output->set_content_type('application/json');
			$this->output->set_output(json_encode(array(
				'error'   => false,
				'message' => SUCCESS,
				'objListTransactionProfileDetail'   => $objTPD,
				'objTransactionCausal'  			=> $objTC
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
	function save(){
		try{ 
			//AUTENTICACION
			if(!$this->core_web_authentication->isAuthenticated())
			throw new Exception(USER_NOT_AUTENTICATED);
			$dataSession		= $this->session->all_userdata();			
			
			//Validar Formulario						
			$this->form_validation->set_rules("txtName","Nombre","required");    
			$this->form_validation->set_rules("txtCompanyID","Compañia ID","required");    
			$this->form_validation->set_rules("txtTransactionID","Transacion ID","required");    
			$companyID 		= $this->input->post("txtCompanyID");
			$transactionID 	= $this->input->post("txtTransactionID");

			//Error Formulario no valido
			if($this->form_validation->run() != true ){
				$strTemp = str_replace("\n","",validation_errors());								
				$this->core_web_notification->set_message(true,$strTemp);
				redirect('app_transaction_config/edit/companyID/'.$companyID.'/transactionID/'.$transactionID);	
				exit;
			}
			
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
			//Load Library
			$this->load->model("Transaction_Model"); 			
			$this->load->model("Transaction_Causal_Model");
			
			//Iniciar Transacciones
			$this->db->trans_start();
			$objTransaction["journalTypeID"] 	= $this->input->post("txtJournalTypeID");
			$objTransaction["reference1"] 		= $this->input->post("txtReference1");
			$objTransaction["reference2"] 		= $this->input->post("txtReference2");
			$objTransaction["reference3"] 		= $this->input->post("txtReference3");
			$objTransaction["isCountable"] 		= $this->input->post("txtIsContabilize");
			$objTransaction["description"] 		= $this->input->post("txtDescription");
			$objListCausalID					= $this->input->post("txtCausalID");
			$result 							= $this->Transaction_Model->update($companyID,$transactionID,$objTransaction);
					
			//Eliminar todas los Causales que no estan en el detalle del Cliente
			$result								= $this->Transaction_Causal_Model->delete($companyID,$transactionID,$objListCausalID);

			//Insert o Update Causal
			if($objListCausalID)
			foreach($objListCausalID as $idex => $value){
				$causalID 											= $value;							
				$objNewTransactionCausal["branchID"]				= $this->input->post("txtCausalBranchID")[$idex];
				$objNewTransactionCausal["name"]					= $this->input->post("txtCausalName")[$idex];
				$objNewTransactionCausal["isDefault"]				= $this->input->post("txtCausalIsDefault")[$idex] == "true" ? 1 : 0 ;
				$objNewTransactionCausal["warehouseSourceID"]		= $this->input->post("txtCausalWarehouseSourceID")[$idex] == "" ? "NULL" : $this->input->post("txtCausalWarehouseSourceID")[$idex] ;
				$objNewTransactionCausal["warehouseTargetID"]		= $this->input->post("txtCausalWarehouseTargetID")[$idex] == "" ? "NULL" : $this->input->post("txtCausalWarehouseTargetID")[$idex];
				$objOldTransactionCausal 	= $this->Transaction_Causal_Model->getByCompanyAndTransactionAndCausal($companyID,$transactionID,$causalID);
				
				//Insert Causal
				if(!$objOldTransactionCausal){
					$objNewTransactionCausal["companyID"] 		= $companyID;
					$objNewTransactionCausal["transactionID"] 	= $transactionID;
					$objNewTransactionCausal["isActive"] 		= 1;					
					$result = $this->Transaction_Causal_Model->insert($objNewTransactionCausal);
				}
				//Update Causal
				else{
					$result = $this->Transaction_Causal_Model->update($companyID,$transactionID,$causalID,$objNewTransactionCausal);
				}
			}
			//Validar que hay un causal por defecto
			$countCausalDefault = $this->Transaction_Causal_Model->countCausalDefault($companyID,$transactionID);
			if($countCausalDefault == 0)
			throw new Exception("Siempre el documento tiene que tener un Causal Principal.");
			
			//Confirmar Transaccion
			$this->db->trans_complete();
						
			//Redireccionar
			if ($this->db->trans_status() === FALSE)
			$this->core_web_notification->set_message(true,$this->db->_error_message());
			else
			$this->core_web_notification->set_message(false,SUCCESS);
			
			redirect('app_transaction_config/edit/companyID/'.$companyID.'/transactionID/'.$transactionID);	
		}
		catch(Exception $ex){
			show_error($ex->getLine()." ".$ex->getMessage() ,500 );
		}
	}
	function add_causal(){
			
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
			
			//Cargar Librerias
			$this->load->model("Warehouse_Model"); 	
			$this->load->model("core/Branch_Model"); 	
			$D["objListWarehouse"]		= $this->Warehouse_Model->getByCompany($dataSession["user"]->companyID);
			$D["objListBranch"]			= $this->Branch_Model->getByCompany($dataSession["user"]->companyID);
			
			//Renderizar Resultado
			$dataSession["message"]		= "";
			$dataSession["head"]		= $this->load->view('app_transaction_config/popup_addcausal_head','',true);
			$dataSession["body"]		= $this->load->view('app_transaction_config/popup_addcausal_body',$D,true);
			$dataSession["script"]		= $this->load->view('app_transaction_config/popup_addcausal_script','',true);  
			$this->load->view("core_masterpage/default_popup",$dataSession);				
			
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
			
			//Redireccionar datos
			$uri			= $this->uri->uri_to_assoc(3);						
			$companyID		= $uri["companyID"];
			$transactionID	= $uri["transactionID"];	
			$branchID 		= $dataSession["user"]->branchID;
			$roleID 		= $dataSession["role"]->roleID;
			if((!$companyID || !$transactionID))
			{ 
				redirect('app_transaction_config/index');	
			} 	
			
			
			$this->load->model("Transaction_Model"); 	
			$this->load->model("Transaction_Concept_Model"); 	
			$this->load->model("Transaction_Causal_Model"); 	
			$this->load->model("Account_Model"); 	
			$this->load->model("Center_Cost_model"); 	
			$this->load->model("core/Workflow_Model"); 	
			
			
			$D["objListTransactionConcept"]		= $this->Transaction_Concept_Model->getByCompanyAndTransaction($dataSession["user"]->companyID,$transactionID);
			$D["objListAccount"]				= $this->Account_Model->getByCompanyOperative($dataSession["user"]->companyID);
			$D["objListCenterCost"]				= $this->Center_Cost_model->getByCompany($dataSession["user"]->companyID);
			$D["objListJournalType"]			= $this->core_web_catalog->getCatalogAllItem("tb_transaction","journalTypeID",$dataSession["user"]->companyID);
			$D["objTransaction"]				= $this->Transaction_Model->getByCompanyAndTransaction($dataSession["user"]->companyID,$transactionID);
			$D["objWorkflow"]					= $this->Workflow_Model->get_rowByWorkflowID($D["objTransaction"]->workflowID);
			$D["objListTransactionCausal"]		= $this->Transaction_Causal_Model->getByCompanyAndTransaction($dataSession["user"]->companyID,$transactionID);
			
			//Renderizar Resultado
			$dataSession["notification"]	= $this->core_web_error->get_error($dataSession["user"]->userID);
			$dataSession["message"]			= $this->core_web_notification->get_message();
			$dataSession["head"]			= $this->load->view('app_transaction_config/edit_head',$D,true);
			$dataSession["body"]			= $this->load->view('app_transaction_config/edit_body',$D,true);
			$dataSession["script"]			= $this->load->view('app_transaction_config/edit_script',$D,true);  
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
			$objComponent		= $this->core_web_tools->getComponentIDBy_ComponentName("tb_transaction");
			if(!$objComponent)
			throw new Exception("00409 EL COMPONENTE 'tb_transaction' NO EXISTE...");
			
			
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
			$dataSession["head"]			= $this->load->view('app_transaction_config/list_head','',true);
			$dataSession["footer"]			= $this->load->view('app_transaction_config/list_footer','',true);
			$dataSession["body"]			= $dataViewRender; 
			$dataSession["script"]			= $this->load->view('app_transaction_config/list_script','',true).$this->core_web_javascript->createVar("componentID",$objComponent->componentID);  
			$this->load->view("core_masterpage/default_masterpage",$dataSession);		
		}
		catch(Exception $ex){
			show_error($ex->getLine()." ".$ex->getMessage() ,500 );
		}
	}	
	
}
?>
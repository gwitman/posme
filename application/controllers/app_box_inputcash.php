<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class App_Box_Inputcash extends CI_Controller {
	
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
			$this->load->model("UserWarehouse_Model");
			$this->load->model("Customer_Model");
			$this->load->model("Natural_Model");
			$this->load->model("Employee_Model");
			$this->load->model("Legal_Model");
			$this->load->model("Transaction_Causal_Model");
			$this->load->model("Transaction_Master_Model");
			$this->load->model("Transaction_Master_Info_Model");
			$this->load->model("Transaction_Master_Detail_Model");
			$this->load->model("Transaction_Master_Concept_Model");
			
			//Redireccionar datos
			$uri					= $this->uri->uri_to_assoc(3);						
			$companyID				= $uri["companyID"];
			$transactionID			= $uri["transactionID"];	
			$transactionMasterID	= $uri["transactionMasterID"];	
			$branchID 				= $dataSession["user"]->branchID;
			$roleID 				= $dataSession["role"]->roleID;			
			$userID					= $dataSession["user"]->userID;
			
			if((!$companyID || !$transactionID  || !$transactionMasterID))
			{ 
				redirect('app_box_inputcash/add');	
			} 		
			
			
		
		
		
			
			//Componente de facturacion
			$objComponentTransactionShare	= $this->core_web_tools->getComponentIDBy_ComponentName("tb_transaction_master_inputcash");
			if(!$objComponentTransactionShare)
			throw new Exception("EL COMPONENTE 'tb_transaction_master_inputcash' NO EXISTE...");
		
			
			$objCurrency						= $this->core_web_currency->getCurrencyDefault($companyID);
			$targetCurrency						= $this->core_web_currency->getCurrencyExternal($companyID);			
			
			//Tipo de Factura			
			$dataView["objTransactionMaster"]					= $this->Transaction_Master_Model->get_rowByPK($companyID,$transactionID,$transactionMasterID);			
			$dataView["objTransactionMasterDetail"]				= $this->Transaction_Master_Detail_Model->get_rowByTransactionToShare($companyID,$transactionID,$transactionMasterID);
			$dataView["objTransactionMaster"]->transactionOn 	= date_format(date_create($dataView["objTransactionMaster"]->transactionOn),"Y-m-d");
			log_message("ERROR",print_r($dataView["objTransactionMasterDetail"],true));
			
			$dataView["companyID"]				= $dataSession["user"]->companyID;
			$dataView["userID"]					= $dataSession["user"]->userID;
			$dataView["userName"]				= $dataSession["user"]->nickname;
			$dataView["roleID"]					= $dataSession["role"]->roleID;
			$dataView["roleName"]				= $dataSession["role"]->name;
			$dataView["branchID"]				= $dataSession["branch"]->branchID;
			$dataView["branchName"]				= $dataSession["branch"]->name;
			$dataView["exchangeRate"]			= $this->core_web_currency->getRatio($companyID,date("Y-m-d"),1,$targetCurrency->currencyID,$objCurrency->currencyID);			
			$dataView["objComponentShare"]		= $objComponentTransactionShare;					
			$dataView["objListWorkflowStage"]	= $this->core_web_workflow->getWorkflowStageByStageInit("tb_transaction_master_inputcash","statusID",$dataView["objTransactionMaster"]->statusID,$companyID,$branchID,$roleID);
			
			//Renderizar Resultado 
			$dataSession["notification"]	= $this->core_web_error->get_error($dataSession["user"]->userID);
			$dataSession["message"]			= $this->core_web_notification->get_message();
			$dataSession["head"]			= $this->load->view('app_box_inputcash/edit_head',$dataView,true);
			$dataSession["body"]			= $this->load->view('app_box_inputcash/edit_body',$dataView,true);
			$dataSession["script"]			= $this->load->view('app_box_inputcash/edit_script',$dataView,true);
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
			$this->load->model("Transaction_Master_Model");
			$this->load->model("Transaction_Master_Detail_Model");	
			
			//Nuevo Registro
			$companyID 				= $this->input->post("companyID");
			$transactionID 			= $this->input->post("transactionID");				
			$transactionMasterID 	= $this->input->post("transactionMasterID");				
			
			
			if((!$companyID && !$transactionID && !$transactionMasterID)){
					throw new Exception(NOT_PARAMETER);								 
			} 
			
			$objTM	 				= $this->Transaction_Master_Model->get_rowByPK($companyID,$transactionID,$transactionMasterID);			
			if ($resultPermission 	== PERMISSION_ME && ($objTM->createdBy != $dataSession["user"]->userID))
			throw new Exception(NOT_DELETE);
			
			if($this->core_web_accounting->cycleIsCloseByDate($companyID,$objTM->transactionOn))
			throw new Exception("EL DOCUMENTO NO PUEDE ELIMINARSE, EL CICLO CONTABLE ESTA CERRADO");
				
				
			//Si el documento esta aplicado crear el contra documento
			if( $this->core_web_workflow->validateWorkflowStage("tb_transaction_master_inputcash","statusID",$objTM->statusID,COMMAND_ELIMINABLE,$dataSession["user"]->companyID,$dataSession["user"]->branchID,$dataSession["role"]->roleID))
			throw new Exception(NOT_WORKFLOW_DELETE);
			
			//Eliminar el Registro			
			$this->Transaction_Master_Model->delete($companyID,$transactionID,$transactionMasterID);
			$this->Transaction_Master_Detail_Model->deleteWhereTM($companyID,$transactionID,$transactionMasterID);			
			
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
			
			$this->load->model("Transaction_Model");	
			$this->load->model("Company_Currency_Model");
			$this->load->model('core/Bd_Model');
			$this->load->model("Transaction_Master_Model");
			$this->load->model("Transaction_Master_Info_Model");
			$this->load->model("Transaction_Master_Detail_Model");	
			$this->load->model("Transaction_Master_Detail_Credit_Model");	
			$this->load->model("Transaction_Master_Concept_Model");	
			$this->load->model("Transaction_Causal_Model");
			$this->load->model("Company_Component_Concept_Model");
			$this->load->model("Entity_Account_Model");
			$this->load->model("Customer_Credit_Document_Model");
			$this->load->model("Customer_Credit_Amortization_Model");
			$this->load->model("Customer_Credit_Line_Model");
			$this->load->model("Customer_Credit_Model");
			$this->load->model("Customer_Model");
			$this->load->model("core/Catalog_Item_Model");
			$this->load->library("core_web_amortization");
			$this->load->library("financial/financial_amort");
			
			
			
			//Obtener el Componente de Transacciones Facturacion
			$objComponentShare			= $this->core_web_tools->getComponentIDBy_ComponentName("tb_transaction_master_inputcash");
			if(!$objComponentShare)
			throw new Exception("EL COMPONENTE 'tb_transaction_master_inputcash' NO EXISTE...");
			
			
			
			
			$branchID 								= $dataSession["user"]->branchID;
			$roleID 								= $dataSession["role"]->roleID;
			$companyID 								= $dataSession["user"]->companyID;
			$userID 								= $dataSession["user"]->userID;			
			$transactionID 							= $this->input->post("txtTransactionID");
			$transactionMasterID					= $this->input->post("txtTransactionMasterID");
			$objTM	 								= $this->Transaction_Master_Model->get_rowByPK($companyID,$transactionID,$transactionMasterID);
			$oldStatusID 							= $objTM->statusID;
			
			
			//Valores de tasa de cambio
			date_default_timezone_set(APP_TIMEZONE); 
			$objCurrencyDolares						= $this->core_web_currency->getCurrencyExternal($companyID);
			$objCurrencyCordoba						= $this->core_web_currency->getCurrencyDefault($companyID);
			$dateOn 								= date("Y-m-d");
			$dateOn 								= date_format(date_create($dateOn),"Y-m-d");
			$exchangeRate 							= $this->core_web_currency->getRatio($companyID,$dateOn,1,$objCurrencyDolares->currencyID,$objCurrencyCordoba->currencyID);
			
			
			//Validar Edicion por el Usuario
			if ($resultPermission 	== PERMISSION_ME && ($objTM->createdBy != $userID))
			throw new Exception(NOT_EDIT);
			
			//Validar si el estado permite editar
			if(!$this->core_web_workflow->validateWorkflowStage("tb_transaction_master_inputcash","statusID",$objTM->statusID,COMMAND_EDITABLE_TOTAL,$dataSession["user"]->companyID,$dataSession["user"]->branchID,$dataSession["role"]->roleID))
			throw new Exception(NOT_WORKFLOW_EDIT);					
			
			if($this->core_web_accounting->cycleIsCloseByDate($companyID,$objTM->transactionOn))
			throw new Exception("EL DOCUMENTO NO PUEDE ACTUALIZARCE, EL CICLO CONTABLE ESTA CERRADO");
			
			//Actualizar Maestro
			//$objTMNew["entityID"] 						= $this->input->post("txtCustomerID");
			$objTMNew["transactionOn"]					= $this->input->post("txtDate");
			$objTMNew["statusIDChangeOn"]				= date("Y-m-d H:m:s");
			$objTMNew["note"] 							= $this->input->post("txtNote",'');
			$objTMNew["exchangeRate"]					= $this->core_web_currency->getRatio($dataSession["user"]->companyID,date("Y-m-d"),1,$objTM->currencyID2,$objTM->currencyID);
			//$objTMNew["reference1"] 					= $this->input->post("txtReference1");
			//$objTMNew["reference2"] 					= $this->input->post("txtReference2");
			//$objTMNew["reference3"] 					= $this->input->post("txtEmployeeID","0");
			//$objTMNew["reference4"] 					= $this->input->post("txtCustomerCreditLineID","0");
			//$objTMNew["descriptionReference"] 		= "reference1:input,reference2:input,reference3:Gestor de Cobro,reference4:Linea de credito del Cliente";
			$objTMNew["statusID"] 						= $this->input->post("txtStatusID");
			$objTMNew["amount"] 						= 0;
			
			$this->db->trans_begin();
			
			//El Estado solo permite editar el workflow
			if($this->core_web_workflow->validateWorkflowStage("tb_transaction_master_inputcash","statusID",$objTM->statusID,COMMAND_EDITABLE,$dataSession["user"]->companyID,$dataSession["user"]->branchID,$dataSession["role"]->roleID)){
				$objTMNew								= array();
				$objTMNew["statusID"] 					= $this->input->post("txtStatusID");						
				$this->Transaction_Master_Model->update($companyID,$transactionID,$transactionMasterID,$objTMNew);
			}
			else{
				$this->Transaction_Master_Model->update($companyID,$transactionID,$transactionMasterID,$objTMNew);				
			}
			
			
			//Actualizar Detalle
			$arrayListTransactionDetailID				= $this->input->post("txtDetailTransactionDetailID");			
			$arrayListShare	 							= $this->input->post("txtDetailAmount");
			$amount 									= 0;
			$this->Transaction_Master_Detail_Model->deleteWhereIDNotIn($companyID,$transactionID,$transactionMasterID,$arrayListTransactionDetailID); 
			
			//phpinfo();			
			if(!empty($arrayListTransactionDetailID)){				
				
				log_message("error",print_r("punto de interrupcion**************1",true));
				log_message("error",print_r($arrayListTransactionDetailID,true));	
				log_message("error",print_r("punto de interrupcion**************2",true));
				$amount									= helper_StringToNumber($arrayListShare);
				log_message("error",print_r("punto de interrupcion**************3",true));
				$transactionDetailID					= $arrayListTransactionDetailID;
				log_message("error",print_r("punto de interrupcion**************4",true));				
				log_message("error",print_r("punto de interrupcion**************5",true));				
				log_message("error",print_r("punto de interrupcion**************6",true));					
				log_message("error",print_r("punto de interrupcion**************7",true));
				
				//Nuevo Detalle
				if($transactionDetailID == 0){	
					log_message("error",print_r("punto de interrupcion**************8",true));
					$objTMD 								= NULL;
					$objTMD["companyID"] 					= $objTM["companyID"];
					$objTMD["transactionID"] 				= $objTM["transactionID"];
					$objTMD["transactionMasterID"] 			= $transactionMasterID;
					$objTMD["componentID"]					= $objComponentShare->componentID;
					$objTMD["componentItemID"] 				= 0;
					$objTMD["quantity"] 					= 0;
					$objTMD["unitaryCost"]					= 0;
					$objTMD["cost"] 						= 0;
					
					$objTMD["unitaryPrice"]					= 0;
					$objTMD["unitaryAmount"]				= 0;
					$objTMD["amount"] 						= $amount;
					$objTMD["discount"]						= 0;					
					$objTMD["promotionID"] 					= 0;						
					$objTMD["reference1"]					= '';						
					$objTMD["reference2"]					= '';
					$objTMD["reference3"]					= '';
					$objTMD["catalogStatusID"]				= 0;
					$objTMD["inventoryStatusID"]			= 0;
					$objTMD["isActive"]						= 1;
					$objTMD["quantityStock"]				= 0;
					$objTMD["quantiryStockInTraffic"]		= 0;
					$objTMD["quantityStockUnaswared"]		= 0;
					$objTMD["remaingStock"]					= 0;
					$objTMD["expirationDate"]				= NULL;
					$objTMD["inventoryWarehouseSourceID"]	= NULL;
					$objTMD["inventoryWarehouseTargetID"]	= NULL;
					$amount 								= $amount + $objTMD["amount"];
					
					$this->Transaction_Master_Detail_Model->insert($objTMD);
				}					
				//Editar Detalle
				else{	
					log_message("error",print_r("punto de interrupcion**************9",true));
					$objTMD 								= $this->Transaction_Master_Detail_Model->get_rowByPK($companyID,$transactionID,$transactionMasterID,$transactionDetailID,$objComponentShare->componentID);						
					$objTMDNew 								= null;						
					$objTMDNew["amount"] 					= $amount;
					$objTMDNew["reference1"]				= '';
					$objTMDNew["reference2"]				= '';
					$objTMDNew["reference3"]				= '';
					$objTMDNew["exchangeRateReference"]		= '0';
					$objTMDNew["descriptionReference"]		= '';
					$amount 								= $amount + $objTMDNew["amount"];
					log_message("error",print_r("punto de interrupcion**************10",true));
					$this->Transaction_Master_Detail_Model->update($companyID,$transactionID,$transactionMasterID,$transactionDetailID,$objTMDNew);
				}
					
					
				
			}			
			
			
			
			log_message("error",print_r("punto de interrupcion**************8",true));
			//Actualizar Transaccion			
			$objTMNew["amount"] = $amount;			
			$this->Transaction_Master_Model->update($companyID,$transactionID,$transactionMasterID,$objTMNew);
						
			log_message("error",print_r("punto de interrupcion**************9",true));			
			log_message("error",print_r("punto de interrupcion**************10",true));
			
			if($this->db->trans_status() !== false){
				log_message("error",print_r("punto de interrupcion**************11",true));
				$this->db->trans_commit();						
				log_message("error",print_r("punto de interrupcion**************12",true));
				$this->core_web_notification->set_message(false,SUCCESS);
				log_message("error",print_r("punto de interrupcion**************13",true));
				redirect('app_box_inputcash/edit/companyID/'.$companyID."/transactionID/".$transactionID."/transactionMasterID/".$transactionMasterID);
				log_message("error",print_r("punto de interrupcion**************14",true));
			}
			else{
				$this->db->trans_rollback();	
				$this->core_web_notification->set_message(true,$this->db->_error_message());
				redirect('app_box_inputcash/add');	
			}
			
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
			
			$this->load->model("Transaction_Model");	
			$this->load->model("Company_Currency_Model");
			$this->load->model('core/Bd_Model');
			$this->load->model("Transaction_Master_Model");
			$this->load->model("Transaction_Master_Info_Model");
			$this->load->model("Transaction_Master_Detail_Model");	
			$this->load->model("Transaction_Master_Concept_Model");	
			$this->load->model("Transaction_Causal_Model");
			$this->load->model("Company_Component_Concept_Model");
			
			
			$this->core_web_permission->getValueLicense($dataSession["user"]->companyID,$this->router->class."/"."index");
			
			//Obtener el Componente de Transacciones Facturacion
			$objComponentShare			= $this->core_web_tools->getComponentIDBy_ComponentName("tb_transaction_master_inputcash");
			if(!$objComponentShare)
			throw new Exception("EL COMPONENTE 'tb_transaction_master_inputcash' NO EXISTE...");
			
			log_message("ERROR",print_r($objComponentShare,true));
			
			if($this->core_web_accounting->cycleIsCloseByDate($dataSession["user"]->companyID,$this->input->post("txtDate")))
			throw new Exception("EL DOCUMENTO NO PUEDE INGRESAR, EL CICLO CONTABLE ESTA CERRADO");
			
			//Obtener transaccion
			$transactionID 							= $this->core_web_transaction->getTransactionID($dataSession["user"]->companyID,"tb_transaction_master_inputcash",0);
			$companyID 								= $dataSession["user"]->companyID;
			$objT 									= $this->Transaction_Model->getByCompanyAndTransaction($dataSession["user"]->companyID,$transactionID);
			
			$objTM["companyID"] 					= $dataSession["user"]->companyID;
			$objTM["transactionID"] 				= $transactionID;			
			$objTM["branchID"]						= $dataSession["user"]->branchID;
			$objTM["transactionNumber"]				= $this->core_web_counter->goNextNumber($dataSession["user"]->companyID,$dataSession["user"]->branchID,"tb_transaction_master_inputcash",0);
			$objTM["transactionCausalID"] 			= $this->core_web_transaction->getDefaultCausalID($dataSession["user"]->companyID,$transactionID);
			//$objTM["entityID"] 						= $this->input->post("txtCustomerID");
			$objTM["transactionOn"]					= $this->input->post("txtDate");
			$objTM["statusIDChangeOn"]				= date("Y-m-d H:m:s");
			$objTM["componentID"] 					= $objComponentShare->componentID;
			$objTM["note"] 							= $this->input->post("txtNote",'');
			$objTM["sign"] 							= $objT->signInventory;
			$objTM["currencyID"]					= $this->core_web_currency->getCurrencyDefault($dataSession["user"]->companyID)->currencyID;
			$objTM["currencyID2"]					= $this->core_web_currency->getCurrencyExternal($dataSession["user"]->companyID)->currencyID;
			$objTM["exchangeRate"]					= $this->core_web_currency->getRatio($dataSession["user"]->companyID,date("Y-m-d"),1,$objTM["currencyID2"],$objTM["currencyID"]);
			//$objTM["reference1"] 					= $this->input->post("txtReference1");
			//$objTM["reference2"] 					= $this->input->post("txtReference2");
			$objTM["reference3"] 					= '';
			$objTM["reference4"] 					= '';
			$objTM["statusID"] 						= $this->input->post("txtStatusID");
			$objTM["amount"] 						= helper_StringToNumber($this->input->post('txtTotal','0'));
			$objTM["isApplied"] 					= 0;
			$objTM["journalEntryID"] 				= 0;
			$objTM["classID"] 						= NULL;
			$objTM["areaID"] 						= NULL;
			$objTM["sourceWarehouseID"]				= NULL;
			$objTM["targetWarehouseID"]				= NULL;
			$objTM["isActive"]						= 1;
			$this->core_web_auditoria->setAuditCreated($objTM,$dataSession);			
			log_message("ERROR","creando transaction master");
			
			$this->db->trans_begin();
			$transactionMasterID = $this->Transaction_Master_Model->insert($objTM);
			
			//Crear la Carpeta para almacenar los Archivos del Documento
			mkdir(PATH_FILE_OF_APP."/company_".$companyID."/component_".$objComponentShare->componentID."/component_item_".$transactionMasterID, 0700);
			
			log_message("ERROR","creando transaction master 02");
			//Recorrer la lista del detalle del documento			
			$arrayListShare	 							= $this->input->post("txtDetailAmount");			
			$arrayListTransactionDetailID				= $this->input->post("txtDetailTransactionDetailID");			
			$amount 									= 0;
			log_message("ERROR","creando transaction master 3");
			log_message("ERROR",print_r($arrayListShare,true));
			log_message("ERROR",print_r($arrayListTransactionDetailID,true));
			log_message("ERROR","creando transaction master 3");
			if(!empty($arrayListShare)){				
				log_message("ERROR","creando transaction master 4");
				$amount									= helper_StringToNumber($arrayListShare);
				$transactionDetailID					= $arrayListTransactionDetailID;
			
				
				$objTMD 								= NULL;
				$objTMD["companyID"] 					= $objTM["companyID"];
				$objTMD["transactionID"] 				= $objTM["transactionID"];
				$objTMD["transactionMasterID"] 			= $transactionMasterID;
				$objTMD["componentID"]					= 0;
				$objTMD["componentItemID"] 				= 0;
				$objTMD["quantity"] 					= 0;
				$objTMD["unitaryCost"]					= 0;
				$objTMD["cost"] 						= 0;
				
				$objTMD["unitaryPrice"]					= 0;
				$objTMD["unitaryAmount"]				= 0;
				$objTMD["amount"] 						= $amount;
				$objTMD["discount"]						= 0;					
				$objTMD["promotionID"] 					= 0;
				
				$objTMD["reference1"]					= 0;
				$objTMD["reference2"]					= 0;
				$objTMD["reference3"]					= 0;
				$objTMD["catalogStatusID"]				= 0;
				$objTMD["inventoryStatusID"]			= 0;
				$objTMD["isActive"]						= 1;
				$objTMD["quantityStock"]				= 0;
				$objTMD["quantiryStockInTraffic"]		= 0;
				$objTMD["quantityStockUnaswared"]		= 0;
				$objTMD["remaingStock"]					= 0;
				$objTMD["expirationDate"]				= NULL;
				$objTMD["inventoryWarehouseSourceID"]	= NULL;
				$objTMD["inventoryWarehouseTargetID"]	= NULL;				
				$amount 								= $amount + $objTMD["amount"];
				log_message("ERROR","creando transaction master 5");
				$this->Transaction_Master_Detail_Model->insert($objTMD);
				
			}
			
			//Actualizar Transaccion
			$objTM["amount"] = $amount;
			log_message("ERROR","creando transaction master 6");
			$this->Transaction_Master_Model->update($companyID,$transactionID,$transactionMasterID,$objTM);
			
			if($this->db->trans_status() !== false){
				$this->db->trans_commit();						
				$this->core_web_notification->set_message(false,SUCCESS);
				redirect('app_box_inputcash/edit/companyID/'.$companyID."/transactionID/".$objTM["transactionID"]."/transactionMasterID/".$transactionMasterID);
			}
			else{
				$this->db->trans_rollback();						
				$this->core_web_notification->set_message(true,$this->db->_error_message());
				redirect('app_box_inputcash/add');	
			}
			
			
		}
		catch(Exception $ex){
			show_error($ex->getLine()." ".$ex->getMessage() ,500 );
		}	
	}
	function save($mode){
		 try{ 
			//AUTENTICADO
			if(!$this->core_web_authentication->isAuthenticated())
			throw new Exception(USER_NOT_AUTENTICATED);
			$dataSession		= $this->session->all_userdata();
			
			//Validar Formulario						
			$this->form_validation->set_rules("txtStatusID","Estado","required");
			$this->form_validation->set_rules("txtDate","Fecha","required");
			
			 //Validar Formulario
			if(!$this->form_validation->run()){
				$stringValidation = $this->core_web_tools->formatMessageError(validation_errors());
				$this->core_web_notification->set_message(true,$stringValidation);
				redirect('app_box_inputcash/add');
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
				redirect('app_box_inputcash/add');
				exit;
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
			 
			
			$this->load->model("Customer_Model");
			$this->load->model("Natural_Model");
			$this->load->model("Legal_Model");
			$this->load->model("Transaction_Causal_Model");
			
			
			$companyID 							= $dataSession["user"]->companyID;
			$branchID 							= $dataSession["user"]->branchID;
			$roleID 							= $dataSession["role"]->roleID;
			$userID								= $dataSession["user"]->userID;
			
			
			//Obtener Tasa de Cambio			
			$companyID 							= $dataSession["user"]->companyID;
			$branchID 							= $dataSession["user"]->branchID;
			$roleID 							= $dataSession["role"]->roleID;
			$transactionID 						= $this->core_web_transaction->getTransactionID($dataSession["user"]->companyID,"tb_transaction_master_inputcash",0);
			$objCurrency						= $this->core_web_currency->getCurrencyDefault($companyID);
			$targetCurrency						= $this->core_web_currency->getCurrencyExternal($companyID);			
			
			//Tipo de Factura
			$dataView["companyID"]				= $dataSession["user"]->companyID;
			$dataView["userID"]					= $dataSession["user"]->userID;
			$dataView["userName"]				= $dataSession["user"]->nickname;
			$dataView["roleID"]					= $dataSession["role"]->roleID;
			$dataView["roleName"]				= $dataSession["role"]->name;
			$dataView["branchID"]				= $dataSession["branch"]->branchID;
			$dataView["branchName"]				= $dataSession["branch"]->name;
			$dataView["exchangeRate"]			= $this->core_web_currency->getRatio($companyID,date("Y-m-d"),1,$targetCurrency->currencyID,$objCurrency->currencyID);			
			
			$objParameterExchangePurchase		= $this->core_web_parameter->getParameter("ACCOUNTING_EXCHANGE_PURCHASE",$companyID);
			$dataView["exchangeRatePurchase"]	= $this->core_web_currency->getRatio($companyID,date("Y-m-d"),1,$targetCurrency->currencyID,$objCurrency->currencyID) - $objParameterExchangePurchase->value;			
			$objParameterExchangeSales			= $this->core_web_parameter->getParameter("ACCOUNTING_EXCHANGE_SALE",$companyID);
			$dataView["exchangeRateSale"]		= $this->core_web_currency->getRatio($companyID,date("Y-m-d"),1,$targetCurrency->currencyID,$objCurrency->currencyID) + $objParameterExchangeSales->value;		
			
		
			$dataView["objCaudal"]				= $this->Transaction_Causal_Model->getCausalByBranch($companyID,$transactionID,$branchID);			
			$dataView["objListWorkflowStage"]	= $this->core_web_workflow->getWorkflowInitStage("tb_transaction_master_inputcash","statusID",$companyID,$branchID,$roleID);
			
			
			//Renderizar Resultado 
			$dataSession["notification"]	= $this->core_web_error->get_error($dataSession["user"]->userID);
			$dataSession["message"]			= $this->core_web_notification->get_message();
			$dataSession["head"]			= $this->load->view('app_box_inputcash/news_head',$dataView,true);
			$dataSession["body"]			= $this->load->view('app_box_inputcash/news_body',$dataView,true);
			$dataSession["script"]			= $this->load->view('app_box_inputcash/news_script',$dataView,true);  
			$dataSession["footer"]			= "";
			$this->load->view("core_masterpage/default_masterpage",$dataSession);	
			
		}
		catch(Exception $ex){
			show_error($ex->getLine()." ".$ex->getMessage() ,500 );
		}	
			
    }
	function index($dataViewID = null){	
		try{ 
		
			//Librerias
			$this->load->library('user_agent');
			
			
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
			$objComponent		= $this->core_web_tools->getComponentIDBy_ComponentName("tb_transaction_master_inputcash");
			if(!$objComponent)
			throw new Exception("00409 EL COMPONENTE 'tb_transaction_master_inputcash' NO EXISTE...");
			
			
			//Vista por defecto PC
			if($dataViewID == null and  !$this->agent->is_mobile() ){				
				$targetComponentID			= 0;	
				$parameter["{companyID}"]	= $this->session->userdata('user')->companyID;
				$dataViewData				= $this->core_web_view->getViewDefault($this->session->userdata('user'),$objComponent->componentID,CALLERID_LIST,$targetComponentID,$resultPermission,$parameter);			
				$dataViewRender				= $this->core_web_view->renderGreed($dataViewData,'ListView',"fnTableSelectedRow");
			}		
			//Vista por defecto MOBILE
			else if( $this->agent->is_mobile() ){
				$parameter["{companyID}"]	= $this->session->userdata('user')->companyID;
				$dataViewData				= $this->core_web_view->getViewByName($this->session->userdata('user'),$objComponent->componentID,"DEFAULT_MOBILE_LISTA_INGRESO_A_CAJA",CALLERID_LIST,$resultPermission,$parameter); 			
				$dataViewRender				= $this->core_web_view->renderGreed($dataViewData,'ListView',"fnTableSelectedRow");
			} 
			//Vista Por Id
			else 
			{
				$parameter["{companyID}"]	= $this->session->userdata('user')->companyID;
				$dataViewData				= $this->core_web_view->getViewBy_DataViewID($this->session->userdata('user'),$objComponent->componentID,$dataViewID,CALLERID_LIST,$resultPermission,$parameter); 			
				$dataViewRender				= $this->core_web_view->renderGreed($dataViewData,'ListView',"fnTableSelectedRow");
			}
			
			//Renderizar Resultado
			$dataSession["notification"]	= $this->core_web_error->get_error($dataSession["user"]->userID);
			$dataSession["message"]			= $this->core_web_notification->get_message();
			$dataSession["head"]			= $this->load->view('app_box_inputcash/list_head','',true);
			$dataSession["footer"]			= $this->load->view('app_box_inputcash/list_footer','',true);
			$dataSession["body"]			= $dataViewRender; 
			$dataSession["script"]			= $this->load->view('app_box_inputcash/list_script','',true).$this->core_web_javascript->createVar("componentID",$objComponent->componentID);  
			$this->load->view("core_masterpage/default_masterpage",$dataSession);		
		}
		catch(Exception $ex){
			show_error($ex->getLine()." ".$ex->getMessage() ,500 );
		}
	}	
	function searchTransactionMaster(){
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
			$this->load->model("Transaction_Master_Model");  
			
			//Nuevo Registro
			$transactionNumber 	= $this->input->post("transactionNumber");
			
			
			if(!$transactionNumber){
					throw new Exception(NOT_PARAMETER);			
			} 			
			$objTM 	= $this->Transaction_Master_Model->get_rowByTransactionNumber($dataSession["user"]->companyID,$transactionNumber);	
			
			if(!$objTM)
			throw new Exception("NO SE ENCONTRO EL DOCUMENTO");	
			
			
			$this->output->set_content_type('application/json');
			$this->output->set_output(json_encode(array(
				'error'   				=> false,
				'message' 				=> SUCCESS,
				'companyID' 			=> $objTM->companyID,
				'transactionID'			=> $objTM->transactionID,
				'transactionMasterID'	=> $objTM->transactionMasterID
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
	
}
?>
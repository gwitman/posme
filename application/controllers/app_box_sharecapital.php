<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class App_Box_Sharecapital extends CI_Controller {
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
				redirect('app_box_sharecapital/add');	
			} 		
			
			
			//Obtener el componente de Item
			$objComponentCustomer	= $this->core_web_tools->getComponentIDBy_ComponentName("tb_customer");
			if(!$objComponentCustomer)
			throw new Exception("EL COMPONENTE 'tb_customer' NO EXISTE...");
			
			//Componente de facturacion
			$objComponentTransactionShareCapital	= $this->core_web_tools->getComponentIDBy_ComponentName("tb_transaction_master_share_capital");
			if(!$objComponentTransactionShareCapital)
			throw new Exception("EL COMPONENTE 'tb_transaction_master_share_capital' NO EXISTE...");
		
			//Componente de facturacion
			$objComponentCustomerCreditDocument	= $this->core_web_tools->getComponentIDBy_ComponentName("tb_customer_credit_document");
			if(!$objComponentCustomerCreditDocument)
			throw new Exception("EL COMPONENTE 'tb_customer_credit_document' NO EXISTE...");
			
			$objCurrency						= $this->core_web_currency->getCurrencyDefault($companyID);
			$targetCurrency						= $this->core_web_currency->getCurrencyExternal($companyID);			
			
			$urlPrinterDocument					= $this->core_web_parameter->getParameter("BOX_SHARECAPITAL_URL_PRINTER",$companyID);
			//Tipo de Factura
			$dataView["urlPrinterDocument"]						= $urlPrinterDocument->value;
			$dataView["objTransactionMaster"]					= $this->Transaction_Master_Model->get_rowByPK($companyID,$transactionID,$transactionMasterID);
			$dataView["objTransactionMasterInfo"]				= $this->Transaction_Master_Info_Model->get_rowByPK($companyID,$transactionID,$transactionMasterID);
			$dataView["objTransactionMasterDetail"]				= $this->Transaction_Master_Detail_Model->get_rowByTransactionToShare($companyID,$transactionID,$transactionMasterID);
			$dataView["objTransactionMaster"]->transactionOn 	= date_format(date_create($dataView["objTransactionMaster"]->transactionOn),"Y-m-d");
			$dataView["objComponentCustomerCreditDocument"]		= $objComponentCustomerCreditDocument;
			$dataView["objComponentTransactionShareCapital"]	= $objComponentTransactionShareCapital;
			$dataView["objComponentCustomer"]					= $objComponentCustomer;
			
			$dataView["companyID"]				= $dataSession["user"]->companyID;
			$dataView["userID"]					= $dataSession["user"]->userID;
			$dataView["userName"]				= $dataSession["user"]->nickname;
			$dataView["roleID"]					= $dataSession["role"]->roleID;
			$dataView["roleName"]				= $dataSession["role"]->name;
			$dataView["branchID"]				= $dataSession["branch"]->branchID;
			$dataView["branchName"]				= $dataSession["branch"]->name;
			$dataView["exchangeRate"]			= $this->core_web_currency->getRatio($companyID,date("Y-m-d"),1,$targetCurrency->currencyID,$objCurrency->currencyID);			
			$dataView["objListWorkflowStage"]	= $this->core_web_workflow->getWorkflowStageByStageInit("tb_transaction_master_share_capital","statusID",$dataView["objTransactionMaster"]->statusID,$companyID,$branchID,$roleID);
			$dataView["objCustomerDefault"]		= $this->Customer_Model->get_rowByEntity($companyID,$dataView["objTransactionMaster"]->entityID);
			$dataView["objNaturalDefault"]		= $this->Natural_Model->get_rowByPK($companyID,$dataView["objCustomerDefault"]->branchID,$dataView["objCustomerDefault"]->entityID);
			$dataView["objLegalDefault"]		= $this->Legal_Model->get_rowByPK($companyID,$dataView["objCustomerDefault"]->branchID,$dataView["objCustomerDefault"]->entityID);
			
			//Renderizar Resultado 
			$dataSession["notification"]	= $this->core_web_error->get_error($dataSession["user"]->userID);
			$dataSession["message"]			= $this->core_web_notification->get_message();
			$dataSession["head"]			= $this->load->view('app_box_sharecapital/edit_head',$dataView,true);
			$dataSession["body"]			= $this->load->view('app_box_sharecapital/edit_body',$dataView,true);
			$dataSession["script"]			= $this->load->view('app_box_sharecapital/edit_script',$dataView,true);
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
			if( $this->core_web_workflow->validateWorkflowStage("tb_transaction_master_share_capital","statusID",$objTM->statusID,COMMAND_ELIMINABLE,$dataSession["user"]->companyID,$dataSession["user"]->branchID,$dataSession["role"]->roleID))
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
			$objComponentShareCapital			= $this->core_web_tools->getComponentIDBy_ComponentName("tb_transaction_master_share_capital");
			if(!$objComponentShareCapital)
			throw new Exception("EL COMPONENTE 'tb_transaction_master_share_capital' NO EXISTE...");
			
			
			//Obtener el Componente de Transacciones Facturacion
			$objComponentCreditDocument			= $this->core_web_tools->getComponentIDBy_ComponentName("tb_customer_credit_document");
			if(!$objComponentCreditDocument)
			throw new Exception("EL COMPONENTE 'tb_customer_credit_document' NO EXISTE...");
			
			
			$objComponentCustomer				= $this->core_web_tools->getComponentIDBy_ComponentName("tb_customer");
			if(!$objComponentCustomer)
			throw new Exception("EL COMPONENTE 'tb_customer' NO EXISTE...");
			
			
			
			$branchID 								= $dataSession["user"]->branchID;
			$roleID 								= $dataSession["role"]->roleID;
			$companyID 								= $dataSession["user"]->companyID;
			$userID 								= $dataSession["user"]->userID;
			$typeAmortizationAmericanoID			= $this->core_web_parameter->getParameter("CXC_AMERICANO",$companyID)->value;
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
			if(!$this->core_web_workflow->validateWorkflowStage("tb_transaction_master_share_capital","statusID",$objTM->statusID,COMMAND_EDITABLE_TOTAL,$dataSession["user"]->companyID,$dataSession["user"]->branchID,$dataSession["role"]->roleID))
			throw new Exception(NOT_WORKFLOW_EDIT);					
			
			if($this->core_web_accounting->cycleIsCloseByDate($companyID,$objTM->transactionOn))
			throw new Exception("EL DOCUMENTO NO PUEDE ACTUALIZARCE, EL CICLO CONTABLE ESTA CERRADO");
			
			//Actualizar Maestro
			$objTMNew["entityID"] 						= $this->input->post("txtCustomerID");
			$objTMNew["transactionOn"]					= $this->input->post("txtDate");
			$objTMNew["statusIDChangeOn"]				= date("Y-m-d H:m:s");
			$objTMNew["note"] 							= $this->input->post("txtNote",'');
			$objTMNew["exchangeRate"]					= $this->core_web_currency->getRatio($dataSession["user"]->companyID,date("Y-m-d"),1,$objTM->currencyID2,$objTM->currencyID);
			$objTMNew["reference1"] 					= $this->input->post("txtReference1");
			$objTMNew["reference2"] 					= $this->input->post("txtReference2");
			$objTMNew["reference3"] 					= $this->input->post("txtReference3");
			$objTMNew["reference4"] 					= $this->input->post("txtCustomerCreditLineID","0");
			$objTMNew["statusID"] 						= $this->input->post("txtStatusID");
			$objTMNew["amount"] 						= 0;
			
			//Ingresar Informacion Adicional
			$objTMInfoNew["companyID"]					= $objTM->companyID;
			$objTMInfoNew["transactionID"]				= $objTM->transactionID;
			$objTMInfoNew["transactionMasterID"]		= $transactionMasterID;
			$objTMInfoNew["zoneID"]						= 0;
			$objTMInfoNew["routeID"]					= 0;
			$objTMInfoNew["referenceClientName"]		= $this->input->post("txtReferenceClientName");
			$objTMInfoNew["referenceClientIdentifier"]	= $this->input->post("txtReferenceClientIdentifier");
			$objTMInfoNew["receiptAmount"]				= helper_StringToNumber($this->input->post("txtReceiptAmount",0));
			
			$this->db->trans_begin();
			
			//El Estado solo permite editar el workflow
			if($this->core_web_workflow->validateWorkflowStage("tb_transaction_master_share_capital","statusID",$objTM->statusID,COMMAND_EDITABLE,$dataSession["user"]->companyID,$dataSession["user"]->branchID,$dataSession["role"]->roleID)){
				$objTMNew								= array();
				$objTMNew["statusID"] 					= $this->input->post("txtStatusID");						
				$this->Transaction_Master_Model->update($companyID,$transactionID,$transactionMasterID,$objTMNew);
			}
			else{
				$this->Transaction_Master_Model->update($companyID,$transactionID,$transactionMasterID,$objTMNew);
				$this->Transaction_Master_Info_Model->update($companyID,$transactionID,$transactionMasterID,$objTMInfoNew);
			}
			
			
			//Actualizar Detalle
			$arrayListCustomerCreditDocumentID			= $this->input->post("txtDetailCustomerCreditDocumentID");
			$arrayListShare	 							= $this->input->post("txtDetailShare");
			$arrayListTransactionDetailID				= $this->input->post("txtDetailTransactionDetailID");
			$amount 									= 0;
			$this->Transaction_Master_Detail_Model->deleteWhereIDNotIn($companyID,$transactionID,$transactionMasterID,$arrayListTransactionDetailID);
			
			if(!empty($arrayListTransactionDetailID)){
				foreach($arrayListTransactionDetailID as $key => $value){			
					$customerCreditDocumentID				= $arrayListCustomerCreditDocumentID[$key];
					$share									= helper_StringToNumber($arrayListShare[$key]);
					$transactionDetailID					= $arrayListTransactionDetailID[$key];
					$objCustomerCreditDocument				= $this->Customer_Credit_Document_Model->get_rowByPK($customerCreditDocumentID);
					$objCustomerCreditLine					= $this->Customer_Credit_Line_Model->get_rowByPK($objCustomerCreditDocument->customerCreditLineID); /*customerCreditLineID*/
					
					//Nuevo Detalle
					if($transactionDetailID == 0){						
						$objTMD 								= NULL;
						$objTMD["companyID"] 					= $objTM->companyID;
						$objTMD["transactionID"] 				= $objTM->transactionID;
						$objTMD["transactionMasterID"] 			= $transactionMasterID;
						$objTMD["componentID"]					= $objComponentCreditDocument->componentID;
						$objTMD["componentItemID"] 				= $customerCreditDocumentID;
						$objTMD["quantity"] 					= 0;
						$objTMD["unitaryCost"]					= 0;
						$objTMD["cost"] 						= 0;
						
						$objTMD["unitaryPrice"]					= 0;
						$objTMD["unitaryAmount"]				= 0;
						$objTMD["amount"] 						= $share;
						$objTMD["discount"]						= 0;					
						$objTMD["promotionID"] 					= 0;
						
						$objTMD["reference1"]					= $objCustomerCreditDocument->documentNumber;
						
						//Obtener balance anterior
						if($typeAmortizationAmericanoID == $objCustomerCreditLine->typeAmortization)
						$objTMD["reference2"]					= $objCustomerCreditDocument->balance;
						else
						$objTMD["reference2"]					= $objCustomerCreditDocument->balanceNew;					
					
					
						$objTMD["reference3"]					= NULL;
						$objTMD["catalogStatusID"]				= 0;
						$objTMD["inventoryStatusID"]			= 0;
						$objTMD["isActive"]						= 1;
						$objTMD["quantityStock"]				= 0;
						$objTMD["quantiryStockInTraffic"]		= 0;
						$objTMD["quantityStockUnaswared"]		= 0;
						$objTMD["remaingStock"]					= 0;
						$objTMD["expirationDate"]				= NULL;
						$objTMD["inventoryWarehouseSourceID"]	= $objTM->sourceWarehouseID;
						$objTMD["inventoryWarehouseTargetID"]	= $objTM->targetWarehouseID;						
						$amount 								= $amount + $objTMD["amount"];						
						$this->Transaction_Master_Detail_Model->insert($objTMD);
					}					
					//Editar Detalle
					else{
						
						$objTMD 								= $this->Transaction_Master_Detail_Model->get_rowByPK($companyID,$transactionID,$transactionMasterID,$transactionDetailID);						
						$objTMDNew 								= null;
					
						$objTMDNew["amount"] 					= $share;
						$objTMDNew["reference1"]				= $objCustomerCreditDocument->documentNumber;
						
						//Obtener balance anterior
						if($typeAmortizationAmericanoID == $objCustomerCreditLine->typeAmortization)
						$objTMDNew["reference2"]					= $objCustomerCreditDocument->balance;
						else
						$objTMDNew["reference2"]					= $objCustomerCreditDocument->balanceNew;					
										
						$objTMDNew["reference3"]				= NULL;
						$objTMDNew["exchangeRateReference"]		= $objCustomerCreditDocument->exchangeRate;
						$objTMDNew["descriptionReference"]		= '{componentID:"Componente de los documentos de credito",componentItemID:"Id del documento de credito",reference1:"Numero del desembolso",refernece2:"balance anterior",reference4:"balance nuevo",exchangeRateReference:"Tasa de cambio del desembolso"}';
						$amount 								= $amount + $objTMDNew["amount"];
						$this->Transaction_Master_Detail_Model->update($companyID,$transactionID,$transactionMasterID,$transactionDetailID,$objTMDNew);
					}
					
					
				}
			}			
			
			//Actualizar Transaccion			
			$objTMNew["amount"] = $amount;			
			$this->Transaction_Master_Model->update($companyID,$transactionID,$transactionMasterID,$objTMNew);
						
			//Aplicar el Documento?
			if( $this->core_web_workflow->validateWorkflowStage("tb_transaction_master_share_capital","statusID",$objTMNew["statusID"],COMMAND_APLICABLE,$dataSession["user"]->companyID,$dataSession["user"]->branchID,$dataSession["role"]->roleID) &&  $oldStatusID != $objTMNew["statusID"] ){
				
				
				//Recorrer Facturas
				$objListTMD = $this->Transaction_Master_Detail_Model->get_rowByTransactionToShare($companyID,$transactionID,$transactionMasterID);
				if($objListTMD)
				foreach($objListTMD as $key => $objTMD){
					
					//documento inicial
					$objCustomerCreditDocumentInicial			= $this->Customer_Credit_Document_Model->get_rowByPK($objTMD->componentItemID);					
					
					//aplicar
					$this->core_web_amortization->shareCapital($companyID,$objTMD->componentItemID,$objTMD->amount);
					
					//documento final
					$objCustomerCreditDocument					= $this->Customer_Credit_Document_Model->get_rowByPK($objTMD->componentItemID);					
					
					//Actualizar detlle de credito
					$objTMDC["transactionMasterID"]				= $objTMD->transactionMasterID;
					$objTMDC["transactionMasterDetailID"]		= $objTMD->transactionMasterDetailID;
					$objTMDC["capital"]							= ($objCustomerCreditDocumentInicial->balance - $objCustomerCreditDocument->balance);
					$objTMDC["interest"]						= $objTMD->amount - $objTMDC["capital"];
					$objTMDC["dayDalay"]						= 0;
					$objTMDC["interestMora"]					= 0;
					$objTMDC["currencyID"]						= $objTM->currencyID;
					$objTMDC["exchangeRate"]					= $objTMNew["exchangeRate"];
					$objTMDC["reference1"]						= NULL;
					$objTMDC["reference2"]						= NULL;
					$objTMDC["reference3"]						= NULL;
					$objTMDC["reference4"]						= NULL;
					$this->Transaction_Master_Detail_Credit_Model->insert($objTMDC);
					
					
					$objCustomer								= $this->Customer_Model->get_rowByEntity($companyID,$objTMNew["entityID"]);					
					$objTMFactura 								= $this->Transaction_Master_Model->get_rowByTransactionNumber($companyID,$objCustomerCreditDocument->documentNumber);/*invoiceNumber*/
					$objCustomerCreditLine 						= $this->Customer_Credit_Line_Model->get_rowByPK($objCustomerCreditDocument->customerCreditLineID); /*customerCreditLineID*/
					$objCustomerCredit							= $this->Customer_Credit_Model->get_rowByPK($companyID,$objCustomer->branchID,$objCustomer->entityID);
					$montoAbono									= $objTMDC["capital"];
					$montoAbonoDolares							= $objTMFactura->exchangeRate > 1 ? /*cordoba a dolares*/ ($objTMDC["capital"] * round(1/round($objTMFactura->exchangeRate,4),4)) : $objTMDC["capital"];
					$montoAbonoCordobas							= $objTMFactura->exchangeRate < 1 ? /*dolares a cordoba*/ ($objTMDC["capital"] / round($objTMFactura->exchangeRate,4)) : $objTMDC["capital"];
					
					
					//actualizar saldo general del cliente
					$objCustomerCreditNew["balanceDol"]			= $objCustomerCredit->balanceDol + $montoAbonoDolares;
					$this->Customer_Credit_Model->update($companyID,$objCustomer->branchID,$objCustomer->entityID,$objCustomerCreditNew);
					
						
					//actualizar saldo de la linea
					//linea dolares y factura dolares					
					//linea cordoba y factura cordoba
					if($objCustomerCreditLine->currencyID == $objTMFactura->currencyID)
					$objCustomerCreditLineNew["balance"]	= $objCustomerCreditLine->balance + $montoAbono;
					
					//linea en dolares factura en cordoba
					if($objCustomerCreditLine->currencyID == $objCurrencyDolares->currencyID && $objTMFactura->currencyID != $objCurrencyDolares->currencyID)
					$objCustomerCreditLineNew["balance"]	= $objCustomerCreditLine->balance + $montoAbonoDolares;
						
					//linea en cordoba factura en dolares
					if($objCustomerCreditLine->currencyID != $objCurrencyDolares->currencyID && $objTMFactura->currencyID == $objCurrencyDolares->currencyID)
					$objCustomerCreditLineNew["balance"]	= $objCustomerCreditLine->balance + $montoAbonoCordobas;
					
					
					//actualizar linea
					$this->Customer_Credit_Line_Model->update($objCustomerCreditLine->customerCreditLineID,$objCustomerCreditLineNew);
					
					
					//actualizar saldo del recibo
					$objTMDNew									= NULL;
					if($typeAmortizationAmericanoID == $objCustomerCreditLine->typeAmortization)
					$objTMDNew["reference4"]					= $objCustomerCreditDocument->balance;
					else
					$objTMDNew["reference4"]					= $objCustomerCreditDocument->balanceNew;
				
					//actualizar saldo del recibo
					$this->Transaction_Master_Detail_Model->update($objTMD->companyID,$objTMD->transactionID,$objTMD->transactionMasterID,$objTMD->transactionMasterDetailID,$objTMDNew);
					
					
				}
				
				//Crear Conceptos.
				$this->core_web_concept->shareCapital($companyID,$transactionID,$transactionMasterID);
				
			}
			
			
			if($this->db->trans_status() !== false){
				$this->db->trans_commit();						
				$this->core_web_notification->set_message(false,SUCCESS);
				redirect('app_box_sharecapital/edit/companyID/'.$companyID."/transactionID/".$transactionID."/transactionMasterID/".$transactionMasterID);
			}
			else{
				$this->db->trans_rollback();						
				$this->core_web_notification->set_message(true,$this->db->_error_message());
				redirect('app_box_sharecapital/add');	
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
			$this->load->model("Customer_Credit_Document_Model");
			
			
			$this->core_web_permission->getValueLicense($dataSession["user"]->companyID,$this->router->class."/"."index");
			//Obtener el Componente de Transacciones Facturacion
			$objComponentShareCapital			= $this->core_web_tools->getComponentIDBy_ComponentName("tb_transaction_master_share_capital");
			if(!$objComponentShareCapital)
			throw new Exception("EL COMPONENTE 'tb_transaction_master_share_capital' NO EXISTE...");
			
			//Obtener el Componente de Transacciones Facturacion
			$objComponentCreditDocument			= $this->core_web_tools->getComponentIDBy_ComponentName("tb_customer_credit_document");
			if(!$objComponentCreditDocument)
			throw new Exception("EL COMPONENTE 'tb_customer_credit_document' NO EXISTE...");
			
			
			$objComponentCustomer				= $this->core_web_tools->getComponentIDBy_ComponentName("tb_customer");
			if(!$objComponentCustomer)
			throw new Exception("EL COMPONENTE 'tb_customer' NO EXISTE...");
			
			
			if($this->core_web_accounting->cycleIsCloseByDate($dataSession["user"]->companyID,$this->input->post("txtDate")))
			throw new Exception("EL DOCUMENTO NO PUEDE INGRESAR, EL CICLO CONTABLE ESTA CERRADO");
			
			//Obtener transaccion
			$transactionID 							= $this->core_web_transaction->getTransactionID($dataSession["user"]->companyID,"tb_transaction_master_share_capital",0);
			$companyID 								= $dataSession["user"]->companyID;
			$objT 									= $this->Transaction_Model->getByCompanyAndTransaction($dataSession["user"]->companyID,$transactionID);
			
			$objTM["companyID"] 					= $dataSession["user"]->companyID;
			$objTM["transactionID"] 				= $transactionID;			
			$objTM["branchID"]						= $dataSession["user"]->branchID;
			$objTM["transactionNumber"]				= $this->core_web_counter->goNextNumber($dataSession["user"]->companyID,$dataSession["user"]->branchID,"tb_transaction_master_share_capital",0);
			$objTM["transactionCausalID"] 			= $this->core_web_transaction->getDefaultCausalID($dataSession["user"]->companyID,$transactionID);
			$objTM["entityID"] 						= $this->input->post("txtCustomerID");
			$objTM["transactionOn"]					= $this->input->post("txtDate");
			$objTM["statusIDChangeOn"]				= date("Y-m-d H:m:s");
			$objTM["componentID"] 					= $objComponentShareCapital->componentID;
			$objTM["note"] 							= $this->input->post("txtNote",'');
			$objTM["sign"] 							= $objT->signInventory;
			$objTM["currencyID"]					= $this->core_web_currency->getCurrencyDefault($dataSession["user"]->companyID)->currencyID;
			$objTM["currencyID2"]					= $this->core_web_currency->getCurrencyExternal($dataSession["user"]->companyID)->currencyID;
			$objTM["exchangeRate"]					= $this->core_web_currency->getRatio($dataSession["user"]->companyID,date("Y-m-d"),1,$objTM["currencyID2"],$objTM["currencyID"]);
			$objTM["reference1"] 					= $this->input->post("txtReference1");
			$objTM["reference2"] 					= $this->input->post("txtReference2");
			$objTM["reference3"] 					= $this->input->post("txtReference3");
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
			
			
			$this->db->trans_begin();
			$transactionMasterID = $this->Transaction_Master_Model->insert($objTM);
			
			//Crear la Carpeta para almacenar los Archivos del Documento
			mkdir(PATH_FILE_OF_APP."/company_".$companyID."/component_".$objComponentShareCapital->componentID."/component_item_".$transactionMasterID, 0700);
			//Ingresar Informacion Adicional
			$objTMInfo["companyID"]					= $objTM["companyID"];
			$objTMInfo["transactionID"]				= $objTM["transactionID"];
			$objTMInfo["transactionMasterID"]		= $transactionMasterID;
			$objTMInfo["zoneID"]					= 0;
			$objTMInfo["routeID"]					= 0;
			$objTMInfo["referenceClientName"]		= $this->input->post("txtReferenceClientName");
			$objTMInfo["referenceClientIdentifier"]	= $this->input->post("txtReferenceClientIdentifier");
			$objTMInfo["receiptAmount"]				= helper_StringToNumber($this->input->post("txtReceiptAmount",0));
			$this->Transaction_Master_Info_Model->insert($objTMInfo);
			
			//Recorrer la lista del detalle del documento
			$arrayListCustomerCreditDocumentID			= $this->input->post("txtDetailCustomerCreditDocumentID");
			$arrayListShare	 							= $this->input->post("txtDetailShare");			
			$arrayListTransactionDetailID				= $this->input->post("txtDetailTransactionDetailID");
			$amount 									= 0;
			
			if(!empty($arrayListCustomerCreditDocumentID)){
				foreach($arrayListCustomerCreditDocumentID as $key => $value){
					$customerCreditDocumentID				= $value;
					$share									= helper_StringToNumber($arrayListShare[$key]);
					$transactionDetailID					= $arrayListTransactionDetailID[$key];
					$objCustomerCreditDocument				= $this->Customer_Credit_Document_Model->get_rowByPK($customerCreditDocumentID);
					
					$objTMD 								= NULL;
					$objTMD["companyID"] 					= $objTM["companyID"];
					$objTMD["transactionID"] 				= $objTM["transactionID"];
					$objTMD["transactionMasterID"] 			= $transactionMasterID;
					$objTMD["componentID"]					= $objComponentCreditDocument->componentID;
					$objTMD["componentItemID"] 				= $customerCreditDocumentID;
					$objTMD["quantity"] 					= 0;
					$objTMD["unitaryCost"]					= 0;
					$objTMD["cost"] 						= 0;
					
					$objTMD["unitaryPrice"]					= 0;
					$objTMD["unitaryAmount"]				= 0;
					$objTMD["amount"] 						= $share;
					$objTMD["discount"]						= 0;					
					$objTMD["promotionID"] 					= 0;
					
					$objTMD["reference1"]					= $objCustomerCreditDocument->documentNumber;
					$objTMD["reference2"]					= NULL;
					$objTMD["reference3"]					= NULL;
					$objTMD["catalogStatusID"]				= 0;
					$objTMD["inventoryStatusID"]			= 0;
					$objTMD["isActive"]						= 1;
					$objTMD["quantityStock"]				= 0;
					$objTMD["quantiryStockInTraffic"]		= 0;
					$objTMD["quantityStockUnaswared"]		= 0;
					$objTMD["remaingStock"]					= 0;
					$objTMD["expirationDate"]				= NULL;
					$objTMD["inventoryWarehouseSourceID"]	= $objTM["sourceWarehouseID"];
					$objTMD["inventoryWarehouseTargetID"]	= $objTM["targetWarehouseID"];
					$amount 								= $amount + $objTMD["amount"];
					
					$this->Transaction_Master_Detail_Model->insert($objTMD);
				}
			}
			
			//Actualizar Transaccion
			$objTM["amount"] = $amount;
			$this->Transaction_Master_Model->update($companyID,$transactionID,$transactionMasterID,$objTM);
			
			if($this->db->trans_status() !== false){
				$this->db->trans_commit();						
				$this->core_web_notification->set_message(false,SUCCESS);
				redirect('app_box_sharecapital/edit/companyID/'.$companyID."/transactionID/".$objTM["transactionID"]."/transactionMasterID/".$transactionMasterID);
			}
			else{
				$this->db->trans_rollback();						
				$this->core_web_notification->set_message(true,$this->db->_error_message());
				redirect('app_box_sharecapital/add');	
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
				redirect('app_box_sharecapital/add');
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
				redirect('app_box_sharecapital/add');
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
			
			//Obtener el componente de Item
			$objComponentCustomer	= $this->core_web_tools->getComponentIDBy_ComponentName("tb_customer");
			if(!$objComponentCustomer)
			throw new Exception("EL COMPONENTE 'tb_customer' NO EXISTE...");
			
			//Obtener el componente de Item
			$objComponentCustomerCreditDocument	= $this->core_web_tools->getComponentIDBy_ComponentName("tb_customer_credit_document");
			if(!$objComponentCustomerCreditDocument)
			throw new Exception("EL COMPONENTE 'tb_customer_credit_document' NO EXISTE...");
			
			//Obtener Tasa de Cambio			
			$companyID 							= $dataSession["user"]->companyID;
			$branchID 							= $dataSession["user"]->branchID;
			$roleID 							= $dataSession["role"]->roleID;
			$transactionID 						= $this->core_web_transaction->getTransactionID($dataSession["user"]->companyID,"tb_transaction_master_share_capital",0);
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
			
			$dataView["objComponentCustomer"]				= $objComponentCustomer;
			$dataView["objComponentCustomerCreditDocument"]	= $objComponentCustomerCreditDocument;
			$dataView["objCaudal"]				= $this->Transaction_Causal_Model->getCausalByBranch($companyID,$transactionID,$branchID);			
			$dataView["objListWorkflowStage"]	= $this->core_web_workflow->getWorkflowInitStage("tb_transaction_master_share_capital","statusID",$companyID,$branchID,$roleID);
			
			
			//Renderizar Resultado 
			$dataSession["notification"]	= $this->core_web_error->get_error($dataSession["user"]->userID);
			$dataSession["message"]			= $this->core_web_notification->get_message();
			$dataSession["head"]			= $this->load->view('app_box_sharecapital/news_head',$dataView,true);
			$dataSession["body"]			= $this->load->view('app_box_sharecapital/news_body',$dataView,true);
			$dataSession["script"]			= $this->load->view('app_box_sharecapital/news_script',$dataView,true);  
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
			$objComponent		= $this->core_web_tools->getComponentIDBy_ComponentName("tb_transaction_master_share_capital");
			if(!$objComponent)
			throw new Exception("00409 EL COMPONENTE 'tb_transaction_master_share_capital' NO EXISTE...");
			
			
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
			$dataSession["head"]			= $this->load->view('app_box_sharecapital/list_head','',true);
			$dataSession["footer"]			= $this->load->view('app_box_sharecapital/list_footer','',true);
			$dataSession["body"]			= $dataViewRender; 
			$dataSession["script"]			= $this->load->view('app_box_sharecapital/list_script','',true).$this->core_web_javascript->createVar("componentID",$objComponent->componentID);  
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
	
	function viewRegisterFormatoPaginaNormal(){
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
			
			$uri						= $this->uri->uri_to_assoc(3);						
			$transactionID				= $uri["transactionID"];			
			$transactionMasterID		= $uri["transactionMasterID"];				
			$companyID 					= $dataSession["user"]->companyID;		
			$branchID 					= $dataSession["user"]->branchID;		
			$roleID 					= $dataSession["role"]->roleID;		
			
			
			//Cargar Libreria
			$this->load->library('core_web_pdf/src/EXTCezpdf.php');			
			$this->load->model("Transaction_Master_Model");
			$this->load->model("Transaction_Master_Detail_Model");	
			$this->load->model("core/Company_Model"); 
			$this->load->model("core/User_Model");
			$this->load->model("Customer_Credit_Document_Model");
		
			$this->load->model("Provider_Model");
			$this->load->model("Legal_Model");
			$this->load->model("Natural_Model");
			$this->load->model("core/Branch_Model");
			$this->load->model("Customer_Model");
				
			
			//Crear Objetos
			$pdf 	= new EXTCezpdf(PAGE_SIZE,'portrait','none',array());
			$pdf->selectFont('./fonts/Courier.afm');
			$pdf->ezSetCmMargins(TOP_MARGIN,BOTTOM_MARGIN,LEFT_MARGIN,RIGHT_MARGIN);
			$width 	= $pdf->EXTGetWidth();
									
			
			//Get Component
			$objComponent	= $this->core_web_tools->getComponentIDBy_ComponentName("tb_company");
			//Get Logo
			$objParameter	= $this->core_web_parameter->getParameter("CORE_COMPANY_LOGO",$companyID);
			//Get Company
			$objCompany 	= $this->Company_Model->get_rowByPK($companyID);			
			//Get Documento				
			
			$datView["objTM"]	 					= $this->Transaction_Master_Model->get_rowByPK($companyID,$transactionID,$transactionMasterID);
			$datView["objTMD"]						= $this->Transaction_Master_Detail_Model->get_rowByTransactionToShare($companyID,$transactionID,$transactionMasterID);
			$datView["objTM"]->transactionOn 		= date_format(date_create($datView["objTM"]->transactionOn),"Y-m-d");
			$datView["objUser"] 					= $this->User_Model->get_rowByPK($datView["objTM"]->companyID,$datView["objTM"]->createdAt,$datView["objTM"]->createdBy);
			$datView["objBranch"]					= $this->Branch_Model->get_rowByPK($datView["objTM"]->companyID,$datView["objTM"]->branchID);
			$datView["objCustumer"]					= $this->Customer_Model->get_rowByEntity($companyID,$datView["objTM"]->entityID);
			$datView["objNatural"]					= $this->Natural_Model->get_rowByPK($companyID,$datView["objCustumer"]->branchID,$datView["objCustumer"]->entityID);
			$datView["tipoCambio"]					= round($datView["objTM"]->exchangeRate + $this->core_web_parameter->getParameter("ACCOUNTING_EXCHANGE_SALE",$companyID)->value,2);
			
			//Set Nombre del Reporte
			$reportName		= "DOC_ENTRADA_CANCELACION_FACTURA";
			//Set Informacion File
			$pdf->addInfo(array('Title'=>$reportName,'Author'=>APP_NAME,'CreationDate'=>date('Y-m-d H:i:s')));
			//Set Titulo			
			$pdf->EXTCreateHeader("::".$objCompany->name." ABONO AL CAPITAL"."::",$objComponent->componentID,$objParameter->value,$dataSession);
			//Set Encambezado del comprobante
			$pdf->ezText("<b>DOCUMENTO NO:".$datView["objTM"]->transactionNumber."</b>\n\n\n\n",FONT_SIZE,array('justification'=>'center'));
			
			
			$data = array( 
				array(
					'field1'=>'<b>Fecha</b>',
					'field2'=>$datView["objTM"]->transactionOn,
					'field3'=>'<b>Referencia1</b>',
					'field4'=>$datView["objTM"]->reference1
				),
				array(
					'field1'=>'<b>Cliente</b>',
					'field2'=>$datView["objCustumer"]->customerNumber." ".$datView["objNatural"]->firstName ." ". $datView["objNatural"]->lastName,
					'field3'=>'<b>Referencia2</b>',
					'field4'=>$datView["objTM"]->reference2
				),
				array(
					'field1'=>'<b>Estado</b>',
					'field2'=>$datView["objTM"]->workflowStageName,
					'field3'=>'<b>Referencia3</b>',
					'field4'=>$datView["objTM"]->reference3
				),
				array(
					'field1'=>'<b>Tipo Cambio</b>',
					'field2'=>$datView["tipoCambio"],
					'field3'=>'<b>Cajero</b>',
					'field4'=>$datView["objUser"]->nickname
				)
			);		
			$pdf->ezTable(
				$data,
				array('field1'=>'','field2'=>'','field3' => '','field4'=>'' ),
				'',
				array(
					'showHeadings'=>0,'showLines' => 0 ,
					'shaded'=>0,'xPos'=>'left','xOrientation'=>'right',
					'width'=>$width,
					'fontSize' => FONT_SIZE,	
					'colGap' => 0,
					'rowGap' => 0.5,
					'cols'=>array(
						'field1'=>array('justification'=>'left','width'=>66),
						'field2'=>array('justification'=>'left'),
						'field3'=>array('justification'=>'left','width'=>66),
						'field4'=>array('justification'=>'left'),
						'field5'=>array('justification'=>'left','width'=>35),
						'field6'=>array('justification'=>'left')
					) 
				)
			);
			
			//Set Comentario del Comprobante
			$pdf->ezText("\n<b>Comentario</b>",FONT_SIZE);
			$data	= array(
				array('field1'=> $datView["objTM"]->note."...")
			);
			$pdf->ezTable(
				$data,
				array('field1'=>''),
				'',
				array(
					'showHeadings'=>0,'shaded'=>0,'xPos'=>'left',
					'xOrientation'=>'right','width'=>$width,
					'showLines'=>0, 	
					'fontSize' => FONT_SIZE,	
					'colGap' => 0
				)
			);
			
			//Set Detalle del Comprobante
			$pdf->ezText("\n\n<b>Detalle</b>",FONT_SIZE);			
			$data		= array();
			if($datView["objTMD"])
			foreach($datView["objTMD"] as $row){
				$objCustomerCreditDocument 	= $this->Customer_Credit_Document_Model->get_rowByPK($row->componentItemID);
				$data[] 					= array('field1'=>$row->reference1,'field2'=>round($row->reference2,2),'field3'=>$row->amount,'field4'=>round($row->reference4,2),'field5'=>$objCustomerCreditDocument->currencyName);
			}
			
			$pdf->ezTable(
				$data,
				array('field1'=>'Documento','field2'=>'Saldo Anterior','field3'=>'Monto','field4'=>'Saldo Nuevo','field5'=>'Moneda'),
				'',				
				array(
					'showHeadings'=>1,'showLines'=>4,'shaded'=>0,
					'xPos'=>'left','xOrientation'=>'right',
					'width'=>$width,
					'fontSize' => FONT_SIZE,	
					'colGap' => 0
				)
			);
			
			
			//Set Firma del Comprobante
			$pdf->ezText("\n\n<b>Firma</b>\n");
			$pdf->ezText("<b>Dir:</b>".$datView["objCustumer"]->reference1."");			
			$pdf->ezText("<b>C$ BAC: 361-727-506</b>  <b>C$ BANPRO: 100-2000-0118-404</b> fecha:<b>".$datView["objTM"]->createdOn."</b>");
			$margin_left_pint = (LEFT_MARGIN / 2.54)*72;
			$y				  = $pdf->y;
			$pdf->setColor(255,255,255);
			$pdf->setStrokeColor(0,0,0);
			$pdf->setLineStyle(1);
			//$pdf->line($margin_left_pint ,$y+10,($width + $margin_left_pint)/2,$y+10);
					
			//Set Pie		
			$pdf->EXTCreateFooter();
			//OutPut
			$pdf->ezStream(array('Content-Disposition' => $reportName)); 
			
		}
		catch(Exception $ex){
			show_error($ex->getLine()." ".$ex->getMessage() ,500 );
		}
	}
	function viewRegisterFormatoPaginaTicket(){
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
			
			$uri						= $this->uri->uri_to_assoc(3);						
			$transactionID				= $uri["transactionID"];			
			$transactionMasterID		= $uri["transactionMasterID"];				
			$companyID 					= $dataSession["user"]->companyID;		
			$branchID 					= $dataSession["user"]->branchID;		
			$roleID 					= $dataSession["role"]->roleID;		
			
			
			//Cargar Libreria
			$this->load->library('core_web_pdf/src/EXTCezpdf.php');			
			$this->load->model("Transaction_Master_Model");
			$this->load->model("Transaction_Master_Detail_Model");	
			$this->load->model("core/Company_Model"); 
			$this->load->model("core/User_Model");
			$this->load->model("Customer_Credit_Document_Model");
		
			$this->load->model("Provider_Model");
			$this->load->model("Legal_Model");
			$this->load->model("Natural_Model");
			$this->load->model("core/Branch_Model");
			$this->load->model("Customer_Model");
				
			
			//Crear Objetos
			$pdf 	= new EXTCezpdf(PAGE_INVOICE,'portrait','none',array());
			//$pdf->selectFont('./fonts/Courier.afm');
			$pdf->ezSetCmMargins(TOP_MARGIN_INVOICE,BOTTOM_MARGIN_INVOICE,LEFT_MARGIN_INVOICE,RIGHT_MARGIN_INVOICE);
			$width 	= $pdf->EXTGetWidth();
									
			
			//Get Component
			$objComponent	= $this->core_web_tools->getComponentIDBy_ComponentName("tb_company");
			//Get Logo
			$objParameter	= $this->core_web_parameter->getParameter("CORE_COMPANY_LOGO",$companyID);
			//Get Company
			$objCompany 	= $this->Company_Model->get_rowByPK($companyID);			
			//Get Documento				
			
			$datView["objTM"]	 					= $this->Transaction_Master_Model->get_rowByPK($companyID,$transactionID,$transactionMasterID);
			$datView["objTMD"]						= $this->Transaction_Master_Detail_Model->get_rowByTransactionToShare($companyID,$transactionID,$transactionMasterID);
			$datView["objTM"]->transactionOn 		= date_format(date_create($datView["objTM"]->transactionOn),"Y-m-d");
			$datView["objUser"] 					= $this->User_Model->get_rowByPK($datView["objTM"]->companyID,$datView["objTM"]->createdAt,$datView["objTM"]->createdBy);
			$datView["objBranch"]					= $this->Branch_Model->get_rowByPK($datView["objTM"]->companyID,$datView["objTM"]->branchID);
			$datView["objCustumer"]					= $this->Customer_Model->get_rowByEntity($companyID,$datView["objTM"]->entityID);
			$datView["objNatural"]					= $this->Natural_Model->get_rowByPK($companyID,$datView["objCustumer"]->branchID,$datView["objCustumer"]->entityID);
			$datView["tipoCambio"]					= round($datView["objTM"]->exchangeRate + $this->core_web_parameter->getParameter("ACCOUNTING_EXCHANGE_SALE",$companyID)->value,2);
			
			//Set Nombre del Reporte
			$reportName		= "DOC_ENTRADA_CANCELACION_FACTURA";
			//Set Informacion File
			$pdf->addInfo(array('Title'=>$reportName,'Author'=>APP_NAME,'CreationDate'=>date('Y-m-d H:i:s')));
			//Set Titulo			
			$pdf->EXTCreateHeaderPrinterTicketAndTermica80cm(""./*$objCompany->name.*/""."",$objComponent->componentID,$objParameter->value,$dataSession);
			//Set Encambezado del comprobante
			//$pdf->ezText("VARIEDADES"."",FONT_SIZE,array('justification'=>'center'));
			//$pdf->ezText("CARLOS LUIS"."\n",FONT_SIZE,array('justification'=>'center'));
			$pdf->ezText("::".strtoupper($objCompany->name)."::",FONT_SIZE,array('justification'=>'center' ));
			$pdf->ezText("ABONO AL CAPITAL:".$datView["objTM"]->transactionNumber."\n",FONT_SIZE,array('justification'=>'center'));
			$spacing 			= 0.5;
			
			$data = array( 
				array(
					'field1'=>'Fecha',
					'field2'=>$datView["objTM"]->transactionOn					
				),
				array(
					'field1'=>'Vendedor',
					'field2'=>$datView["objUser"]->nickname				
				),
				array(
					'field1'=>'Cliente',
					'field2'=>$datView["objCustumer"]->customerNumber			
				),				
				array(
					'field1'=>'Estado',
					'field2'=>$datView["objTM"]->workflowStageName					
				)
			);		
			$pdf->ezTable(
				$data,
				array('field1'=>'','field2'=>''),
				'',
				array(
					'showHeadings'	=> 0,
					'showLines' 	=> 0,
					'shaded'		=> 0,
					'xPos'			=>'left',
					'xOrientation'	=>'right',
					'width'			=>$width,					
					'fontSize' 		=>FONT_SIZE_BODY_INVICE,	
					'colGap' 		=>0,
					'rowGap' 		=>0,
					'cols'			=>array(
						'field1'=>array('justification'=>'left','width'=>75,'spacing' => $spacing),
						'field2'=>array('justification'=>'left','heigth' => 40,'spacing' => $spacing)						
					) 
				)
			);
			
			
			//Set Comentario del Comprobante
			$pdf->ezText("\nCLIENTE",FONT_SIZE_BODY_INVICE);
			$data	= array(
				//array('field1'=> $datView["objNatural"]->firstName."/".$datView["objTM"]->note."...")
				array('field1'=> $datView["objNatural"]->firstName)
			);
			$pdf->ezTable(
				$data,
				array('field1'=>''),
				'',
				array(
					'showHeadings'	=> 0,
					'showLines' 	=> 0,
					'shaded'		=> 0,
					'xPos'			=>'left',
					'xOrientation'	=>'right',
					'width'			=>$width,					
					'fontSize' 		=>FONT_SIZE_BODY_INVICE,	
					'colGap' 		=>0,
					'rowGap' 		=>0,
					'cols'			=>array(
						'field1'=>array('justification'=>'left','width'=>200,'spacing' => $spacing)
					) 
				)
			);
			
			//Set Detalle del Comprobante
			$pdf->ezText("\nDETALLE",FONT_SIZE_BODY_INVICE);			
			$data		= array();
			if($datView["objTMD"])
			foreach($datView["objTMD"] as $row){
				$objCustomerCreditDocument 	= $this->Customer_Credit_Document_Model->get_rowByPK($row->componentItemID);
				
				
				$data = array( 
					array(
						'field1'=>'Factura',
						'field2'=>$row->reference1				
					),
					array(
						'field1'=>'Saldo Anterior',
						'field2'=>round($row->reference2,0)
					),				
					array(
						'field1'=>'Abono',
						'field2'=>round($row->amount,2)
					),
					array(
						'field1'=>'Nuevo Saldo',
						'field2'=>round($row->reference4,0)
					),
					array(
						'field1'=>'Moneda',
						'field2'=>$objCustomerCreditDocument->currencyName
					)
				);		
				$pdf->ezTable(
					$data,
					array('field1'=>'','field2'=>''),
					'',
					array(
						'showHeadings'	=> 0,
						'showLines' 	=> 0,
						'shaded'		=> 0,
						'xPos'			=>'left',
						'xOrientation'	=>'right',
						'width'			=>$width,					
						'fontSize' 		=>FONT_SIZE_BODY_INVICE,	
						'colGap' 		=>0,
						'rowGap' 		=>0,
						'cols'			=>array(
							'field1'=>array('justification'=>'left','width'=>75,'spacing' => $spacing),
							'field2'=>array('justification'=>'left','heigth' => 40,'spacing' => $spacing)						
						) 
					)
				);
			
			}
			
		
			
			
			
			
					
			//Set Pie		
			$pdf->EXTCreateFooter();
			//OutPut
			$pdf->ezStream(array('Content-Disposition' => $reportName)); 
			
		}
		catch(Exception $ex){
			show_error($ex->getLine()." ".$ex->getMessage() ,500 );
		}
	}
	function viewRegisterFidLocal(){
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
			
			$uri						= $this->uri->uri_to_assoc(3);						
			$transactionID				= $uri["transactionID"];			
			$transactionMasterID		= $uri["transactionMasterID"];				
			$companyID 					= $dataSession["user"]->companyID;		
			$branchID 					= $dataSession["user"]->branchID;		
			$roleID 					= $dataSession["role"]->roleID;		
			
			
			//Cargar Libreria
			$this->load->library('core_web_pdf/src/EXTCezpdf.php');			
			$this->load->model("Transaction_Master_Model");
			$this->load->model("Transaction_Master_Detail_Model");	
			$this->load->model("core/Company_Model"); 
			$this->load->model("core/User_Model");
			$this->load->model("Customer_Credit_Document_Model");
		
			$this->load->model("Provider_Model");
			$this->load->model("Legal_Model");
			$this->load->model("Natural_Model");
			$this->load->model("core/Branch_Model");
			$this->load->model("Customer_Model");
				
			
			//Crear Objetos
			$pdf 	= new EXTCezpdf(PAGE_SIZE,'portrait','none',array());
			$pdf->selectFont('./fonts/Courier.afm');
			$pdf->ezSetCmMargins(TOP_MARGIN,BOTTOM_MARGIN,LEFT_MARGIN,RIGHT_MARGIN);
			$width 	= $pdf->EXTGetWidth();
									
			
			//Get Component
			$objComponent	= $this->core_web_tools->getComponentIDBy_ComponentName("tb_company");
			//Get Logo
			$objParameter	= $this->core_web_parameter->getParameter("CORE_COMPANY_LOGO",$companyID);
			//Get Company
			$objCompany 	= $this->Company_Model->get_rowByPK($companyID);			
			//Get Documento				
			
			$datView["objTM"]	 					= $this->Transaction_Master_Model->get_rowByPK($companyID,$transactionID,$transactionMasterID);
			$datView["objTMD"]						= $this->Transaction_Master_Detail_Model->get_rowByTransactionToShare($companyID,$transactionID,$transactionMasterID);
			$datView["objTM"]->transactionOn 		= date_format(date_create($datView["objTM"]->transactionOn),"Y-m-d");
			$datView["objUser"] 					= $this->User_Model->get_rowByPK($datView["objTM"]->companyID,$datView["objTM"]->createdAt,$datView["objTM"]->createdBy);
			$datView["objBranch"]					= $this->Branch_Model->get_rowByPK($datView["objTM"]->companyID,$datView["objTM"]->branchID);
			$datView["objCustumer"]					= $this->Customer_Model->get_rowByEntity($companyID,$datView["objTM"]->entityID);
			$datView["objNatural"]					= $this->Natural_Model->get_rowByPK($companyID,$datView["objCustumer"]->branchID,$datView["objCustumer"]->entityID);
			$datView["tipoCambio"]					= round($datView["objTM"]->exchangeRate + $this->core_web_parameter->getParameter("ACCOUNTING_EXCHANGE_SALE",$companyID)->value,2);
			
			//Set Nombre del Reporte
			$reportName		= "DOC_ENTRADA_CANCELACION_FACTURA";
			//Set Informacion File
			$pdf->addInfo(array('Title'=>$reportName,'Author'=>APP_NAME,'CreationDate'=>date('Y-m-d H:i:s')));
			//Set Titulo			
			$pdf->EXTCreateHeader("::".$objCompany->name." ABONO AL CAPITAL"."::",$objComponent->componentID,$objParameter->value,$dataSession);
			//Set Encambezado del comprobante
			$pdf->ezText("<b>DOCUMENTO NO:".$datView["objTM"]->transactionNumber."</b>\n\n\n\n",FONT_SIZE,array('justification'=>'center'));
			
			
			$data = array( 
				array(
					'field1'=>'<b>Fecha</b>',
					'field2'=>$datView["objTM"]->transactionOn,
					'field3'=>'<b>Referencia1</b>',
					'field4'=>$datView["objTM"]->reference1
				),
				array(
					'field1'=>'<b>Cliente</b>',
					'field2'=>$datView["objCustumer"]->customerNumber." ".$datView["objNatural"]->firstName ." ". $datView["objNatural"]->lastName,
					'field3'=>'<b>Referencia2</b>',
					'field4'=>$datView["objTM"]->reference2
				),
				array(
					'field1'=>'<b>Estado</b>',
					'field2'=>$datView["objTM"]->workflowStageName,
					'field3'=>'<b>Referencia3</b>',
					'field4'=>$datView["objTM"]->reference3
				),
				array(
					'field1'=>'<b>Tipo Cambio</b>',
					'field2'=>$datView["tipoCambio"],
					'field3'=>'<b>Cajero</b>',
					'field4'=>$datView["objUser"]->nickname
				)
			);		
			$pdf->ezTable(
				$data,
				array('field1'=>'','field2'=>'','field3' => '','field4'=>'' ),
				'',
				array(
					'showHeadings'=>0,'showLines' => 0 ,
					'shaded'=>0,'xPos'=>'left','xOrientation'=>'right',
					'width'=>$width,
					'fontSize' => FONT_SIZE,	
					'colGap' => 0,
					'rowGap' => 0.5,
					'cols'=>array(
						'field1'=>array('justification'=>'left','width'=>66),
						'field2'=>array('justification'=>'left'),
						'field3'=>array('justification'=>'left','width'=>66),
						'field4'=>array('justification'=>'left'),
						'field5'=>array('justification'=>'left','width'=>35),
						'field6'=>array('justification'=>'left')
					) 
				)
			);
			
			//Set Comentario del Comprobante
			$pdf->ezText("\n<b>Comentario</b>",FONT_SIZE);
			$data	= array(
				array('field1'=> $datView["objTM"]->note."...")
			);
			$pdf->ezTable(
				$data,
				array('field1'=>''),
				'',
				array(
					'showHeadings'=>0,'shaded'=>0,'xPos'=>'left',
					'xOrientation'=>'right','width'=>$width,
					'showLines'=>0, 	
					'fontSize' => FONT_SIZE,	
					'colGap' => 0
				)
			);
			
			//Set Detalle del Comprobante
			$pdf->ezText("\n\n<b>Detalle</b>",FONT_SIZE);			
			$data		= array();
			if($datView["objTMD"])
			foreach($datView["objTMD"] as $row){
				$objCustomerCreditDocument 	= $this->Customer_Credit_Document_Model->get_rowByPK($row->componentItemID);
				$data[] 					= array('field1'=>$row->reference1,'field2'=>round($row->reference2,2),'field3'=>$row->amount,'field4'=>round($row->reference4,2),'field5'=>$objCustomerCreditDocument->currencyName);
			}
			
			$pdf->ezTable(
				$data,
				array('field1'=>'Documento','field2'=>'Saldo Anterior','field3'=>'Monto','field4'=>'Saldo Nuevo','field5'=>'Moneda'),
				'',				
				array(
					'showHeadings'=>1,'showLines'=>4,'shaded'=>0,
					'xPos'=>'left','xOrientation'=>'right',
					'width'=>$width,
					'fontSize' => FONT_SIZE,	
					'colGap' => 0
				)
			);
			
			
			//Set Firma del Comprobante
			$pdf->ezText("\n\n<b>Firma</b>\n");
			$pdf->ezText("<b>Dir:</b>".$datView["objCustumer"]->reference1."");			
			$pdf->ezText("<b>C$ BAC: 361-727-506</b>  <b>C$ BANPRO: 100-2000-0118-404</b> fecha:<b>".$datView["objTM"]->createdOn."</b>");
			$margin_left_pint = (LEFT_MARGIN / 2.54)*72;
			$y				  = $pdf->y;
			$pdf->setColor(255,255,255);
			$pdf->setStrokeColor(0,0,0);
			$pdf->setLineStyle(1);
			//$pdf->line($margin_left_pint ,$y+10,($width + $margin_left_pint)/2,$y+10);
					
			//Set Pie		
			$pdf->EXTCreateFooter();
			//OutPut
			$pdf->ezStream(array('Content-Disposition' => $reportName)); 
			
		}
		catch(Exception $ex){
			show_error($ex->getLine()." ".$ex->getMessage() ,500 );
		}
	}	
	function viewRegisterVariedadesCarlosLuis(){
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
			
			$uri						= $this->uri->uri_to_assoc(3);						
			$transactionID				= $uri["transactionID"];			
			$transactionMasterID		= $uri["transactionMasterID"];				
			$companyID 					= $dataSession["user"]->companyID;		
			$branchID 					= $dataSession["user"]->branchID;		
			$roleID 					= $dataSession["role"]->roleID;		
			
			
			//Cargar Libreria
			$this->load->library('core_web_pdf/src/EXTCezpdf.php');			
			$this->load->model("Transaction_Master_Model");
			$this->load->model("Transaction_Master_Detail_Model");	
			$this->load->model("core/Company_Model"); 
			$this->load->model("core/User_Model");
			$this->load->model("Customer_Credit_Document_Model");
		
			$this->load->model("Provider_Model");
			$this->load->model("Legal_Model");
			$this->load->model("Natural_Model");
			$this->load->model("core/Branch_Model");
			$this->load->model("Customer_Model");
				
			
			//Crear Objetos
			$pdf 	= new EXTCezpdf(PAGE_INVOICE,'portrait','none',array());
			//$pdf->selectFont('./fonts/Courier.afm');
			$pdf->ezSetCmMargins(TOP_MARGIN_INVOICE,BOTTOM_MARGIN_INVOICE,LEFT_MARGIN_INVOICE,RIGHT_MARGIN_INVOICE);
			$width 	= $pdf->EXTGetWidth();
									
			
			//Get Component
			$objComponent	= $this->core_web_tools->getComponentIDBy_ComponentName("tb_company");
			//Get Logo
			$objParameter	= $this->core_web_parameter->getParameter("CORE_COMPANY_LOGO",$companyID);
			//Get Company
			$objCompany 	= $this->Company_Model->get_rowByPK($companyID);			
			//Get Documento				
			
			$datView["objTM"]	 					= $this->Transaction_Master_Model->get_rowByPK($companyID,$transactionID,$transactionMasterID);
			$datView["objTMD"]						= $this->Transaction_Master_Detail_Model->get_rowByTransactionToShare($companyID,$transactionID,$transactionMasterID);
			$datView["objTM"]->transactionOn 		= date_format(date_create($datView["objTM"]->transactionOn),"Y-m-d");
			$datView["objUser"] 					= $this->User_Model->get_rowByPK($datView["objTM"]->companyID,$datView["objTM"]->createdAt,$datView["objTM"]->createdBy);
			$datView["objBranch"]					= $this->Branch_Model->get_rowByPK($datView["objTM"]->companyID,$datView["objTM"]->branchID);
			$datView["objCustumer"]					= $this->Customer_Model->get_rowByEntity($companyID,$datView["objTM"]->entityID);
			$datView["objNatural"]					= $this->Natural_Model->get_rowByPK($companyID,$datView["objCustumer"]->branchID,$datView["objCustumer"]->entityID);
			$datView["tipoCambio"]					= round($datView["objTM"]->exchangeRate + $this->core_web_parameter->getParameter("ACCOUNTING_EXCHANGE_SALE",$companyID)->value,2);
			
			//Set Nombre del Reporte
			$reportName		= "DOC_ENTRADA_CANCELACION_FACTURA";
			//Set Informacion File
			$pdf->addInfo(array('Title'=>$reportName,'Author'=>APP_NAME,'CreationDate'=>date('Y-m-d H:i:s')));
			//Set Titulo			
			$pdf->EXTCreateHeaderPrinterTicketAndTermica80cm(""./*$objCompany->name.*/""."",$objComponent->componentID,$objParameter->value,$dataSession);
			//Set Encambezado del comprobante
			//$pdf->ezText("VARIEDADES"."",FONT_SIZE,array('justification'=>'center'));
			//$pdf->ezText("CARLOS LUIS"."\n",FONT_SIZE,array('justification'=>'center'));
			$pdf->ezText("::".strtoupper($objCompany->name)."::",FONT_SIZE,array('justification'=>'center' ));
			$pdf->ezText("ABONO AL CAPITAL:".$datView["objTM"]->transactionNumber."\n",FONT_SIZE,array('justification'=>'center'));
			$spacing 			= 0.5;
			
			$data = array( 
				array(
					'field1'=>'Fecha',
					'field2'=>$datView["objTM"]->transactionOn					
				),
				array(
					'field1'=>'Vendedor',
					'field2'=>$datView["objUser"]->nickname				
				),
				array(
					'field1'=>'Cliente',
					'field2'=>$datView["objCustumer"]->customerNumber			
				),				
				array(
					'field1'=>'Estado',
					'field2'=>$datView["objTM"]->workflowStageName					
				)
			);		
			$pdf->ezTable(
				$data,
				array('field1'=>'','field2'=>''),
				'',
				array(
					'showHeadings'	=> 0,
					'showLines' 	=> 0,
					'shaded'		=> 0,
					'xPos'			=>'left',
					'xOrientation'	=>'right',
					'width'			=>$width,					
					'fontSize' 		=>FONT_SIZE_BODY_INVICE,	
					'colGap' 		=>0,
					'rowGap' 		=>0,
					'cols'			=>array(
						'field1'=>array('justification'=>'left','width'=>75,'spacing' => $spacing),
						'field2'=>array('justification'=>'left','heigth' => 40,'spacing' => $spacing)						
					) 
				)
			);
			
			
			//Set Comentario del Comprobante
			$pdf->ezText("\nCLIENTE",FONT_SIZE_BODY_INVICE);
			$data	= array(
				//array('field1'=> $datView["objNatural"]->firstName."/".$datView["objTM"]->note."...")
				array('field1'=> $datView["objNatural"]->firstName)
			);
			$pdf->ezTable(
				$data,
				array('field1'=>''),
				'',
				array(
					'showHeadings'	=> 0,
					'showLines' 	=> 0,
					'shaded'		=> 0,
					'xPos'			=>'left',
					'xOrientation'	=>'right',
					'width'			=>$width,					
					'fontSize' 		=>FONT_SIZE_BODY_INVICE,	
					'colGap' 		=>0,
					'rowGap' 		=>0,
					'cols'			=>array(
						'field1'=>array('justification'=>'left','width'=>200,'spacing' => $spacing)
					) 
				)
			);
			
			//Set Detalle del Comprobante
			$pdf->ezText("\nDETALLE",FONT_SIZE_BODY_INVICE);			
			$data		= array();
			if($datView["objTMD"])
			foreach($datView["objTMD"] as $row){
				$objCustomerCreditDocument 	= $this->Customer_Credit_Document_Model->get_rowByPK($row->componentItemID);
				
				
				$data = array( 
					array(
						'field1'=>'Factura',
						'field2'=>$row->reference1				
					),
					array(
						'field1'=>'Saldo Anterior',
						'field2'=>round($row->reference2,0)
					),				
					array(
						'field1'=>'Abono',
						'field2'=>round($row->amount,2)
					),
					array(
						'field1'=>'Nuevo Saldo',
						'field2'=>round($row->reference4,0)
					),
					array(
						'field1'=>'Moneda',
						'field2'=>$objCustomerCreditDocument->currencyName
					)
				);		
				$pdf->ezTable(
					$data,
					array('field1'=>'','field2'=>''),
					'',
					array(
						'showHeadings'	=> 0,
						'showLines' 	=> 0,
						'shaded'		=> 0,
						'xPos'			=>'left',
						'xOrientation'	=>'right',
						'width'			=>$width,					
						'fontSize' 		=>FONT_SIZE_BODY_INVICE,	
						'colGap' 		=>0,
						'rowGap' 		=>0,
						'cols'			=>array(
							'field1'=>array('justification'=>'left','width'=>75,'spacing' => $spacing),
							'field2'=>array('justification'=>'left','heigth' => 40,'spacing' => $spacing)						
						) 
					)
				);
			
			}
			
		
			
			
			
			
					
			//Set Pie		
			$pdf->EXTCreateFooter();
			//OutPut
			$pdf->ezStream(array('Content-Disposition' => $reportName)); 
			
		}
		catch(Exception $ex){
			show_error($ex->getLine()." ".$ex->getMessage() ,500 );
		}
	}
	function viewRegisterInstitutoLaVid(){
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
			
			$uri						= $this->uri->uri_to_assoc(3);						
			$transactionID				= $uri["transactionID"];			
			$transactionMasterID		= $uri["transactionMasterID"];				
			$companyID 					= $dataSession["user"]->companyID;		
			$branchID 					= $dataSession["user"]->branchID;		
			$roleID 					= $dataSession["role"]->roleID;		
			
			
			//Cargar Libreria
			$this->load->library('core_web_pdf/src/EXTCezpdf.php');			
			$this->load->model("Transaction_Master_Model");
			$this->load->model("Transaction_Master_Detail_Model");	
			$this->load->model("core/Company_Model"); 
			$this->load->model("core/User_Model");
			$this->load->model("Customer_Credit_Document_Model");
		
			$this->load->model("Provider_Model");
			$this->load->model("Legal_Model");
			$this->load->model("Natural_Model");
			$this->load->model("core/Branch_Model");
			$this->load->model("Customer_Model");
				
			
			//Crear Objetos
			$pdf 	= new EXTCezpdf(PAGE_INVOICE,'portrait','none',array());
			//$pdf->selectFont('./fonts/Courier.afm');
			$pdf->ezSetCmMargins(TOP_MARGIN_INVOICE,BOTTOM_MARGIN_INVOICE,LEFT_MARGIN_INVOICE,RIGHT_MARGIN_INVOICE);
			$width 	= $pdf->EXTGetWidth();
									
			
			//Get Component
			$objComponent	= $this->core_web_tools->getComponentIDBy_ComponentName("tb_company");
			//Get Logo
			$objParameter	= $this->core_web_parameter->getParameter("CORE_COMPANY_LOGO",$companyID);
			//Get Company
			$objCompany 	= $this->Company_Model->get_rowByPK($companyID);			
			//Get Documento				
			
			$datView["objTM"]	 					= $this->Transaction_Master_Model->get_rowByPK($companyID,$transactionID,$transactionMasterID);
			$datView["objTMD"]						= $this->Transaction_Master_Detail_Model->get_rowByTransactionToShare($companyID,$transactionID,$transactionMasterID);
			$datView["objTM"]->transactionOn 		= date_format(date_create($datView["objTM"]->transactionOn),"Y-m-d");
			$datView["objUser"] 					= $this->User_Model->get_rowByPK($datView["objTM"]->companyID,$datView["objTM"]->createdAt,$datView["objTM"]->createdBy);
			$datView["objBranch"]					= $this->Branch_Model->get_rowByPK($datView["objTM"]->companyID,$datView["objTM"]->branchID);
			$datView["objCustumer"]					= $this->Customer_Model->get_rowByEntity($companyID,$datView["objTM"]->entityID);
			$datView["objNatural"]					= $this->Natural_Model->get_rowByPK($companyID,$datView["objCustumer"]->branchID,$datView["objCustumer"]->entityID);
			$datView["tipoCambio"]					= round($datView["objTM"]->exchangeRate + $this->core_web_parameter->getParameter("ACCOUNTING_EXCHANGE_SALE",$companyID)->value,2);
			
			//Set Nombre del Reporte
			$reportName		= "DOC_ENTRADA_CANCELACION_FACTURA";
			//Set Informacion File
			$pdf->addInfo(array('Title'=>$reportName,'Author'=>APP_NAME,'CreationDate'=>date('Y-m-d H:i:s')));
			//Set Titulo			
			$pdf->EXTCreateHeaderPrinterTicketAndTermica80cm(""./*$objCompany->name.*/""."",$objComponent->componentID,$objParameter->value,$dataSession);
			//Set Encambezado del comprobante
			//$pdf->ezText("VARIEDADES"."",FONT_SIZE,array('justification'=>'center'));
			//$pdf->ezText("CARLOS LUIS"."\n",FONT_SIZE,array('justification'=>'center'));
			$pdf->ezText("::".strtoupper($objCompany->name)."::",FONT_SIZE,array('justification'=>'center' ));
			$pdf->ezText("ABONO AL CAPITAL:".$datView["objTM"]->transactionNumber."\n",FONT_SIZE,array('justification'=>'center'));
			$spacing 			= 0.5;
			
			$data = array( 
				array(
					'field1'=>'Fecha',
					'field2'=>$datView["objTM"]->transactionOn					
				),
				array(
					'field1'=>'Vendedor',
					'field2'=>$datView["objUser"]->nickname				
				),
				array(
					'field1'=>'Cliente',
					'field2'=>$datView["objCustumer"]->customerNumber			
				),				
				array(
					'field1'=>'Estado',
					'field2'=>$datView["objTM"]->workflowStageName					
				)
			);		
			$pdf->ezTable(
				$data,
				array('field1'=>'','field2'=>''),
				'',
				array(
					'showHeadings'	=> 0,
					'showLines' 	=> 0,
					'shaded'		=> 0,
					'xPos'			=>'left',
					'xOrientation'	=>'right',
					'width'			=>$width,					
					'fontSize' 		=>FONT_SIZE_BODY_INVICE,	
					'colGap' 		=>0,
					'rowGap' 		=>0,
					'cols'			=>array(
						'field1'=>array('justification'=>'left','width'=>75,'spacing' => $spacing),
						'field2'=>array('justification'=>'left','heigth' => 40,'spacing' => $spacing)						
					) 
				)
			);
			
			
			//Set Comentario del Comprobante
			$pdf->ezText("\nCLIENTE",FONT_SIZE_BODY_INVICE);
			$data	= array(
				//array('field1'=> $datView["objNatural"]->firstName."/".$datView["objTM"]->note."...")
				array('field1'=> $datView["objNatural"]->firstName)
			);
			$pdf->ezTable(
				$data,
				array('field1'=>''),
				'',
				array(
					'showHeadings'	=> 0,
					'showLines' 	=> 0,
					'shaded'		=> 0,
					'xPos'			=>'left',
					'xOrientation'	=>'right',
					'width'			=>$width,					
					'fontSize' 		=>FONT_SIZE_BODY_INVICE,	
					'colGap' 		=>0,
					'rowGap' 		=>0,
					'cols'			=>array(
						'field1'=>array('justification'=>'left','width'=>200,'spacing' => $spacing)
					) 
				)
			);
			
			//Set Detalle del Comprobante
			$pdf->ezText("\nDETALLE",FONT_SIZE_BODY_INVICE);			
			$data		= array();
			if($datView["objTMD"])
			foreach($datView["objTMD"] as $row){
				$objCustomerCreditDocument 	= $this->Customer_Credit_Document_Model->get_rowByPK($row->componentItemID);
				
				
				$data = array( 
					array(
						'field1'=>'Factura',
						'field2'=>$row->reference1				
					),
					array(
						'field1'=>'Saldo Anterior',
						'field2'=>round($row->reference2,0)
					),				
					array(
						'field1'=>'Abono',
						'field2'=>round($row->amount,2)
					),
					array(
						'field1'=>'Nuevo Saldo',
						'field2'=>round($row->reference4,0)
					),
					array(
						'field1'=>'Moneda',
						'field2'=>$objCustomerCreditDocument->currencyName
					)
				);		
				$pdf->ezTable(
					$data,
					array('field1'=>'','field2'=>''),
					'',
					array(
						'showHeadings'	=> 0,
						'showLines' 	=> 0,
						'shaded'		=> 0,
						'xPos'			=>'left',
						'xOrientation'	=>'right',
						'width'			=>$width,					
						'fontSize' 		=>FONT_SIZE_BODY_INVICE,	
						'colGap' 		=>0,
						'rowGap' 		=>0,
						'cols'			=>array(
							'field1'=>array('justification'=>'left','width'=>75,'spacing' => $spacing),
							'field2'=>array('justification'=>'left','heigth' => 40,'spacing' => $spacing)						
						) 
					)
				);
			
			}
			
		
			
			
			
			
					
			//Set Pie		
			$pdf->EXTCreateFooter();
			//OutPut
			$pdf->ezStream(array('Content-Disposition' => $reportName)); 
			
		}
		catch(Exception $ex){
			show_error($ex->getLine()." ".$ex->getMessage() ,500 );
		}
	}
	
	
}
?>
<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class App_Invoice_Billing extends CI_Controller {
	
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
			$this->load->model("List_Price_Model");
			$this->load->model("Transaction_Master_Model");
			$this->load->model("Transaction_Master_Info_Model");
			$this->load->model("Transaction_Master_Detail_Model");
			
			$this->load->model("Transaction_Master_Detail_Credit_Model");	
			$this->load->model("Transaction_Master_Concept_Model");
			$this->load->model("Company_Currency_Model");
			
			$this->load->model("Provider_Model");
			
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
				redirect('app_invoice_billing/add');	
			} 		
			
			//Obtener el componente de Item
			$objComponentCustomer	= $this->core_web_tools->getComponentIDBy_ComponentName("tb_customer");
			if(!$objComponentCustomer)
			throw new Exception("EL COMPONENTE 'tb_customer' NO EXISTE...");
			
			//Componente de facturacion
			$objComponentTransactionBilling	= $this->core_web_tools->getComponentIDBy_ComponentName("tb_transaction_master_billing");
			if(!$objComponentTransactionBilling)
			throw new Exception("EL COMPONENTE 'tb_transaction_master_billing' NO EXISTE...");
		
			//Obtener el componente de Item
			$objComponentItem	= $this->core_web_tools->getComponentIDBy_ComponentName("tb_item");
			if(!$objComponentItem)
			throw new Exception("EL COMPONENTE 'tb_item' NO EXISTE...");
			
			$objCurrency						= $this->core_web_currency->getCurrencyDefault($companyID);
			$targetCurrency						= $this->core_web_currency->getCurrencyExternal($companyID);			
			$customerDefault					= $this->core_web_parameter->getParameter("INVOICE_BILLING_CLIENTDEFAULT",$companyID);
			$objListPrice 						= $this->List_Price_Model->getListPriceToApply($companyID);
			$objListCurrency					= $this->Company_Currency_Model->getByCompany($companyID);
			$urlPrinterDocument					= $this->core_web_parameter->getParameter("INVOICE_URL_PRINTER",$companyID);
			
			if(!$objListPrice)
			throw new Exception("NO EXISTE UNA LISTA DE PRECIO PARA SER APLICADA");
		
			//Obtener parametros para mostrar botones de impresion
			//$parameterValue = $this->core_web_parameter->getParameter("INVOICE_BUTTOM_PRINTER_BAUCHER_GENERAL",$companyID);
			//$dataView["objParameterInvoiceButtomPrinterBoucherGeneral"] = $parameterValue->value;
			
			//$parameterValue = $this->core_web_parameter->getParameter("INVOICE_BUTTOM_PRINTER_PREPRINTER",$companyID);
			//$dataView["objParameterInvoiceButtomPrinterPrePrinter"] = $parameterValue->value;
			//$parameterValue = $this->core_web_parameter->getParameter("INVOICE_BUTTOM_PRINTER_FIDLOCAL_PAYMENT",$companyID);
			//$dataView["objParameterInvoiceButtomPrinterFidLocalPayment"] = $parameterValue->value;
			$parameterValue = $this->core_web_parameter->getParameter("INVOICE_BUTTOM_PRINTER_FIDLOCAL_PAYMENT_AND_AMORTIZACION",$companyID);
			$dataView["objParameterInvoiceButtomPrinterFidLocalPaymentAndAmortization"] = $parameterValue->value;
			
			
			$objParameterInvoiceBillingQuantityZero					= $this->core_web_parameter->getParameter("INVOICE_BILLING_QUANTITY_ZERO",$companyID);
			$dataView["objParameterInvoiceBillingQuantityZero"]		= $objParameterInvoiceBillingQuantityZero->value;
			$objParameterInvoiceBillingPrinterDirect				= $this->core_web_parameter->getParameter("INVOICE_BILLING_PRINTER_DIRECT",$companyID);
			$dataView["objParameterInvoiceBillingPrinterDirect"]	= $objParameterInvoiceBillingPrinterDirect->value;
			$objParameterInvoiceBillingPrinterDirectUrl					= $this->core_web_parameter->getParameter("INVOICE_BILLING_PRINTER_DIRECT_URL",$companyID);
			$dataView["objParameterInvoiceBillingPrinterDirectUrl"]		= $objParameterInvoiceBillingPrinterDirectUrl->value;
			//Tipo de Factura
			$dataView["urlPrinterDocument"]						= $urlPrinterDocument->value;
			$dataView["objTransactionMaster"]					= $this->Transaction_Master_Model->get_rowByPK($companyID,$transactionID,$transactionMasterID);
			$dataView["objTransactionMasterInfo"]				= $this->Transaction_Master_Info_Model->get_rowByPK($companyID,$transactionID,$transactionMasterID);
			$dataView["objTransactionMasterDetail"]				= $this->Transaction_Master_Detail_Model->get_rowByTransaction($companyID,$transactionID,$transactionMasterID);
			$dataView["objTransactionMasterDetailWarehouse"]	= $this->Transaction_Master_Detail_Model->get_rowByTransactionAndWarehouse($companyID,$transactionID,$transactionMasterID);
			$dataView["objTransactionMasterDetailConcept"]		= $this->Transaction_Master_Concept_Model->get_rowByTransactionMasterConcept($companyID,$transactionID,$transactionMasterID,$objComponentItem->componentID);
			$dataView["objTransactionMaster"]->transactionOn 	= date_format(date_create($dataView["objTransactionMaster"]->transactionOn),"Y-m-d");
			$dataView["objTransactionMaster"]->transactionOn2 	= date_format(date_create($dataView["objTransactionMaster"]->transactionOn2),"Y-m-d");
			$dataView["objTransactionMasterDetailCredit"]		= null;	
			$dataView["companyID"]				= $dataSession["user"]->companyID;
			$dataView["userID"]					= $dataSession["user"]->userID;
			$dataView["userName"]				= $dataSession["user"]->nickname;
			$dataView["roleID"]					= $dataSession["role"]->roleID;
			$dataView["roleName"]				= $dataSession["role"]->name;
			$dataView["branchID"]				= $dataSession["branch"]->branchID;
			$dataView["branchName"]				= $dataSession["branch"]->name;
			$dataView["exchangeRate"]			= $this->core_web_currency->getRatio($companyID,date("Y-m-d"),1,$targetCurrency->currencyID,$objCurrency->currencyID);			
						
			$dataView["objListPrice"]			= $objListPrice;
			$dataView["objComponentBilling"]	= $objComponentTransactionBilling;
			$dataView["objComponentItem"]		= $objComponentItem;
			$dataView["objComponentCustomer"]	= $objComponentCustomer;
			$dataView["objCaudal"]				= $this->Transaction_Causal_Model->getCausalByBranch($companyID,$transactionID,$branchID);			
			$dataView["warehouseID"]			= $dataView["objCaudal"][0]->warehouseSourceID;
			$dataView["objListWarehouse"]		= $this->UserWarehouse_Model->getRowByUserID($companyID,$userID);
			$dataView["objListWorkflowStage"]	= $this->core_web_workflow->getWorkflowStageByStageInit("tb_transaction_master_billing","statusID",$dataView["objTransactionMaster"]->statusID,$companyID,$branchID,$roleID);
			$dataView["objCustomerDefault"]		= $this->Customer_Model->get_rowByEntity($companyID,$dataView["objTransactionMaster"]->entityID);
			$dataView["objListTypePrice"]		= $this->core_web_catalog->getCatalogAllItem("tb_price","typePriceID",$companyID);
			$dataView["objListZone"]			= $this->core_web_catalog->getCatalogAllItem("tb_transaction_master_info_billing","zoneID",$companyID);
			$dataView["listCurrency"]			= $objListCurrency;
			$dataView["listProvider"]			= $this->Provider_Model->get_rowByCompany($companyID);
			$dataView["objListaPermisos"]		= $dataSession["menuHiddenPopup"];
			
			
			if(!$dataView["objCustomerDefault"])
			throw new Exception("NO EXISTE EL CLIENTE POR DEFECTO");
			
			$dataView["objNaturalDefault"]		= $this->Natural_Model->get_rowByPK($companyID,$dataView["objCustomerDefault"]->branchID,$dataView["objCustomerDefault"]->entityID);
			$dataView["objLegalDefault"]		= $this->Legal_Model->get_rowByPK($companyID,$dataView["objCustomerDefault"]->branchID,$dataView["objCustomerDefault"]->entityID);
			
			//Procesar Datos
			if($dataView["objTransactionMasterDetail"])
			foreach($dataView["objTransactionMasterDetail"] as $key => $value)
			{
				$dataView["objTransactionMasterDetail"][$key]->itemName = htmlentities($value->itemName,ENT_QUOTES);
				$dataView["objTransactionMasterDetailCredit"]			= $this->Transaction_Master_Detail_Credit_Model->get_rowByPK($value->transactionMasterDetailID);
			}
			
			//Renderizar Resultado 
			$dataSession["notification"]	= $this->core_web_error->get_error($dataSession["user"]->userID);
			$dataSession["message"]			= $this->core_web_notification->get_message();
			$dataSession["head"]			= $this->load->view('app_invoice_billing/edit_head',$dataView,true);
			$dataSession["body"]			= $this->load->view('app_invoice_billing/edit_body',$dataView,true);
			$dataSession["script"]			= $this->load->view('app_invoice_billing/edit_script',$dataView,true);  
			$dataSession["footer"]			= "";

			//$this->load->view("core_masterpage/default_masterpage",$dataSession);	
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
			$this->load->model("Transaction_Master_Model");
			$this->load->model("Transaction_Master_Detail_Model");	
			$this->load->model("Customer_Credit_Model");	
			$this->load->model("Customer_Credit_Line_Model");	
			$this->load->model("Customer_Credit_Document_Model");	
			$this->load->model("Customer_Model");	
			
			
			
			
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
				
			//Validar si el estado permite editar
			if(!$this->core_web_workflow->validateWorkflowStage("tb_transaction_master_billing","statusID",$objTM->statusID,COMMAND_ELIMINABLE,$dataSession["user"]->companyID,$dataSession["user"]->branchID,$dataSession["role"]->roleID))
			throw new Exception(NOT_WORKFLOW_DELETE); 					
				
			//Si el documento esta aplicado crear el contra documento
			if( $this->core_web_workflow->validateWorkflowStage("tb_transaction_master_billing","statusID",$objTM->statusID,COMMAND_APLICABLE,$dataSession["user"]->companyID,$dataSession["user"]->branchID,$dataSession["role"]->roleID))
			{
				$transactionIDRevert = $this->core_web_parameter->getParameter("INVOICE_TRANSACTION_REVERSION_TO_BILLING",$companyID);
				$transactionIDRevert = $transactionIDRevert->value;
				$result = $this->core_web_transaction->createInverseDocumentByTransaccion($companyID,$transactionID,$transactionMasterID,$transactionIDRevert,0);
				
				//Si la factura es de credito
				$parameterCausalTypeCredit 				= $this->core_web_parameter->getParameter("INVOICE_BILLING_CREDIT",$companyID);
				$causalIDTypeCredit 					= explode(",", $parameterCausalTypeCredit->value);
				$exisCausalInCredit						= null;
				$exisCausalInCredit						= array_search($objTM->transactionCausalID ,$causalIDTypeCredit);
				
				if($exisCausalInCredit || $exisCausalInCredit === 0){
				
					//Valores de tasa de cambio
					date_default_timezone_set(APP_TIMEZONE); 
					$objCurrencyDolares						= $this->core_web_currency->getCurrencyExternal($companyID);
					$objCurrencyCordoba						= $this->core_web_currency->getCurrencyDefault($companyID);
					$dateOn 								= date("Y-m-d");
					$dateOn 								= date_format(date_create($dateOn),"Y-m-d");
					$exchangeRate 							= $this->core_web_currency->getRatio($companyID,$dateOn,1,$objCurrencyDolares->currencyID,$objCurrencyCordoba->currencyID);
						
					//cancelar el documento de credito
					$objCustomerCredotDocument					= $this->Customer_Credit_Document_Model->get_rowByDocument($objTM->companyID,$objTM->entityID,$objTM->transactionNumber);
					$objCustomerCredotDocumentNew["statusID"]	= $this->core_web_parameter->getParameter("SHARE_DOCUMENT_ANULADO",$companyID)->value;
					$this->Customer_Credit_Document_Model->update($objCustomerCredotDocument->customerCreditDocumentID,$objCustomerCredotDocumentNew);
					
					$amountDol									= $objCustomerCredotDocument->amount / $exchangeRate;
					$amountCor									= $objCustomerCredotDocument->amount;
					
					//aumentar el blance de la linea
					$objCustomerCreditLine						= $this->Customer_Credit_Line_Model->get_rowByPK($objCustomerCredotDocument->customerCreditLineID);
					$objCustomerCreditLineNew["balance"]		= $objCustomerCreditLine->balance + ($objCustomerCreditLine->currencyID == $objCurrencyDolares->currencyID ? $amountDol : $amountCor);
					$this->Customer_Credit_Line_Model->update($objCustomerCredotDocument->customerCreditLineID,$objCustomerCreditLineNew);
					
					//aumentar el balance de credito
					$objCustomer								= $this->Customer_Model->get_rowByEntity($objTM->companyID,$objTM->entityID);
					$objCustomerCredit							= $this->Customer_Credit_Model->get_rowByPK($objTM->companyID,$objCustomer->branchID,$objTM->entityID);
					$objCustomerCreditNew["balanceDol"]			= $objCustomerCredit->balanceDol + $amountDol;
					$this->Customer_Credit_Model->update($objTM->companyID,$objCustomer->branchID,$objTM->entityID,$objCustomerCreditNew);
										
				}
				
				
			}
			else 
			{	
				//Eliminar el Registro			
				$this->Transaction_Master_Model->delete($companyID,$transactionID,$transactionMasterID);
				$this->Transaction_Master_Detail_Model->deleteWhereTM($companyID,$transactionID,$transactionMasterID);			
			}
			
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
			$this->load->model("Transaction_Master_Concept_Model");	
			
			$this->load->model("Transaction_Master_Detail_Credit_Model");
			$this->load->model("Item_Model");
			$this->load->model("Transaction_Causal_Model");
			$this->load->model("ItemWarehouse_Model");
			$this->load->model("List_Price_Model");
			$this->load->model("Price_Model");
			$this->load->model("Company_Component_Concept_Model");
			$this->load->model("Entity_Account_Model");
			$this->load->model("Customer_Credit_Document_Model");
			$this->load->model("Customer_Credit_Amortization_Model");
			$this->load->model("Customer_Credit_Line_Model");
			$this->load->model("Customer_Credit_Document_Endity_Related_Model");
			$this->load->model("Customer_Credit_Model");		
			$this->load->model("core/Catalog_Item_Model");							
			$this->load->library("financial/financial_amort");
			$this->load->library('core_web_csv/csvreader'); 
			
			
			//Obtener el Componente de Transacciones Facturacion
			$objComponentBilling			= $this->core_web_tools->getComponentIDBy_ComponentName("tb_transaction_master_billing");
			if(!$objComponentBilling)
			throw new Exception("EL COMPONENTE 'tb_transaction_master_billing' NO EXISTE...");
			
			
			$objComponentItem				= $this->core_web_tools->getComponentIDBy_ComponentName("tb_item");
			if(!$objComponentItem)
			throw new Exception("EL COMPONENTE 'tb_item' NO EXISTE...");
			
			$branchID 								= $dataSession["user"]->branchID;
			$roleID 								= $dataSession["role"]->roleID;
			$companyID 								= $dataSession["user"]->companyID;
			$userID 								= $dataSession["user"]->userID;
			$transactionID 							= $this->input->post("txtTransactionID");
			$transactionMasterID					= $this->input->post("txtTransactionMasterID");
			$objTM	 								= $this->Transaction_Master_Model->get_rowByPK($companyID,$transactionID,$transactionMasterID);
			$oldStatusID 							= $objTM->statusID;
			$parameterCausalTypeCredit 				= $this->core_web_parameter->getParameter("INVOICE_BILLING_CREDIT",$companyID);
			
			
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
			if(!$this->core_web_workflow->validateWorkflowStage("tb_transaction_master_billing","statusID",$objTM->statusID,COMMAND_EDITABLE_TOTAL,$dataSession["user"]->companyID,$dataSession["user"]->branchID,$dataSession["role"]->roleID))
			throw new Exception(NOT_WORKFLOW_EDIT);					
			
			if($this->core_web_accounting->cycleIsCloseByDate($companyID,$objTM->transactionOn))
			throw new Exception("EL DOCUMENTO NO PUEDE ACTUALIZARCE, EL CICLO CONTABLE ESTA CERRADO");
			
			$objParameterInvoiceBillingQuantityZero		= $this->core_web_parameter->getParameter("INVOICE_BILLING_QUANTITY_ZERO",$companyID);
			$objParameterInvoiceBillingQuantityZero		= $objParameterInvoiceBillingQuantityZero->value;
			//Actualizar Maestro
			$typePriceID 								= $this->input->post("txtTypePriceID");
			$objListPrice 								= $this->List_Price_Model->getListPriceToApply($companyID);
			$objTMNew["transactionCausalID"] 			= $this->input->post("txtCausalID");
			$objTMNew["entityID"] 						= $this->input->post("txtCustomerID");
			$objTMNew["transactionOn"]					= $this->input->post("txtDate");
			$objTMNew["transactionOn2"]					= $this->input->post("txtDateFirst");//Fecha del Primer Pago, de las facturas al credito
			$objTMNew["statusIDChangeOn"]				= date("Y-m-d H:m:s");
			$objTMNew["note"] 							= $this->input->post("txtNote",'');			
			$objTMNew["reference1"] 					= $this->input->post("txtReference1");
			$objTMNew["descriptionReference"] 			= "reference1:entityID del proveedor de credito para las facturas al credito,reference4: customerCreditLineID linea de credito del cliente";
			$objTMNew["reference2"] 					= $this->input->post("txtReference2");
			$objTMNew["reference3"] 					= $this->input->post("txtReference3");
			$objTMNew["reference4"] 					= $this->input->post("txtCustomerCreditLineID","0");
			$objTMNew["statusID"] 						= $this->input->post("txtStatusID");
			$objTMNew["amount"] 						= 0;
			$objTMNew["currencyID"]						= $this->input->post("txtCurrencyID"); 
			$objTMNew["currencyID2"]					= $this->core_web_currency->getTarget($companyID,$objTMNew["currencyID"]);
			$objTMNew["exchangeRate"]					= $this->core_web_currency->getRatio($dataSession["user"]->companyID,date("Y-m-d"),1,$objTMNew["currencyID2"],$objTMNew["currencyID"]);
			$objTMNew["sourceWarehouseID"]				= $this->input->post("txtWarehouseID");
			
			
			//Ingresar Informacion Adicional
			$objTMInfoNew["companyID"]					= $objTM->companyID;
			$objTMInfoNew["transactionID"]				= $objTM->transactionID;
			$objTMInfoNew["transactionMasterID"]		= $transactionMasterID;
			$objTMInfoNew["zoneID"]						= $this->input->post("txtZoneID");
			$objTMInfoNew["routeID"]					= 0;
			$objTMInfoNew["referenceClientName"]		= $this->input->post("txtReferenceClientName");
			$objTMInfoNew["referenceClientIdentifier"]	= $this->input->post("txtReferenceClientIdentifier");
			$objTMInfoNew["receiptAmount"]				= helper_StringToNumber($this->input->post("txtReceiptAmount",0));
			$objTMInfoNew["receiptAmountDol"]			= helper_StringToNumber($this->input->post("txtReceiptAmountDol",0));
			
			$this->db->trans_begin();
			
			//El Estado solo permite editar el workflow
			if($this->core_web_workflow->validateWorkflowStage("tb_transaction_master_billing","statusID",$objTM->statusID,COMMAND_EDITABLE,$dataSession["user"]->companyID,$dataSession["user"]->branchID,$dataSession["role"]->roleID)){
				$objTMNew								= array();
				$objTMNew["statusID"] 					= $this->input->post("txtStatusID");						
				$this->Transaction_Master_Model->update($companyID,$transactionID,$transactionMasterID,$objTMNew);
			}
			else{
				$this->Transaction_Master_Model->update($companyID,$transactionID,$transactionMasterID,$objTMNew);
				$this->Transaction_Master_Info_Model->update($companyID,$transactionID,$transactionMasterID,$objTMInfoNew);
			}
			
			
			
			//Leer archivo

			$path 	= PATH_FILE_OF_APP."/company_".$companyID."/component_".$objComponentBilling->componentID."/component_item_".$transactionMasterID;			
			$path 	= $path.'/procesar.csv';
			$pathNew 	= PATH_FILE_OF_APP."/company_".$companyID."/component_".$objComponentBilling->componentID."/component_item_".$transactionMasterID;			
			$pathNew 	= $pathNew.'/procesado.csv';
			log_message("ERROR","archivo de proceso");		
			log_message("ERROR",print_r($pathNew,true));
			
			if (file_exists($path))
			{
				//Actualizar Detalle
				$listTransactionDetalID 					= array();
				$arrayListItemID 							= array();
				$arrayListQuantity	 						= array();
				$arrayListPrice		 						= array();
				$arrayListSubTotal	 						= array();
				$arrayListIva		 						= array();
				$arrayListLote	 							= array();
				$arrayListVencimiento						= array();
				
				$objParameterDeliminterCsv	= $this->core_web_parameter->getParameter("CORE_CSV_SPLIT",$companyID);
				$characterSplie = $objParameterDeliminterCsv->value;
				
				//Obtener los registro del archivo
				$this->csvreader->separator = $characterSplie;
				$table 			= $this->csvreader->parse_file($path); 
				
				
				rename($path,$pathNew);
				$fila 			= 0;
				if($table)
				foreach ($table as $row) 
				{	
					$fila++;
					$codigo 		= $row["Codigo"];
					$description 	= $row["Nombre"];
					$cantidad 		= $row["Cantidad"];
					$precio 		= $row["Precio"];											
					$objItem		= $this->Item_Model->get_rowByCode($companyID,$codigo);
					
					array_push($listTransactionDetalID, 0);
					array_push($arrayListItemID, $objItem->itemID);
					array_push($arrayListQuantity, $cantidad);
					array_push($arrayListPrice, $precio);
					//$arrayListSubTotal		= SUB TOTAL ES UN SOLO NUMERO
					//$arrayListIva		 		= IVA ES UN SOLO NUMERO POR QUE ES EL TOTAL
					array_push($arrayListLote, '');
					array_push($arrayListVencimiento, '');
					
				}
			}
			else{
				//Actualizar Detalle
				$listTransactionDetalID 					= $this->input->post("txtTransactionMasterDetailID");
				$arrayListItemID 							= $this->input->post("txtItemID");
				$arrayListQuantity	 						= $this->input->post("txtQuantity");
				$arrayListPrice		 						= $this->input->post("txtPrice");
				$arrayListSubTotal	 						= $this->input->post("txtSubTotal");
				$arrayListIva		 						= $this->input->post("txtIva");
				$arrayListLote	 							= $this->input->post("txtDetailLote");			
				$arrayListVencimiento						= $this->input->post("txtDetailVencimiento");	
				
			}
					
				
			log_message("ERROR","Revisar variable");		
			log_message("ERROR",print_r($listTransactionDetalID,true));
			log_message("ERROR","Revisar variable 1");	
			log_message("ERROR",print_r($arrayListItemID,true));
			log_message("ERROR","Revisar variable 2");	
			log_message("ERROR",print_r($arrayListQuantity,true));
			log_message("ERROR","Revisar variable 3");	
			log_message("ERROR",print_r($arrayListPrice,true));
			log_message("ERROR","Revisar variable 4");	
			log_message("ERROR",print_r($arrayListSubTotal,true));
			log_message("ERROR","Revisar variable 5");	
			log_message("ERROR",print_r($arrayListIva,true));
			log_message("ERROR","Revisar variable 6");				
			log_message("ERROR",print_r($arrayListLote,true));
			log_message("ERROR","Revisar variable 7");	
			log_message("ERROR",print_r($arrayListVencimiento,true));
			//Ingresar la configuracion de precios			
			$objParameterPriceDefault	= $this->core_web_parameter->getParameter("INVOICE_DEFAULT_PRICELIST",$companyID);
			$listPriceID 	= $objParameterPriceDefault->value;
			$objTipePrice 	= $this->core_web_catalog->getCatalogAllItem("tb_price","typePriceID",$companyID);
			
			
			$objParameterUpdatePrice	= $this->core_web_parameter->getParameter("INVOICE_UPDATEPRICE_ONLINE",$companyID);
			$objUpdatePrice 			= $objParameterUpdatePrice->value;
			
							
							
			$amountTotal 									= 0;
			$tax1Total 										= 0;
			$subAmountTotal									= 0;			$this->Transaction_Master_Detail_Model->deleteWhereIDNotIn($companyID,$transactionID,$transactionMasterID,$listTransactionDetalID);
			$this->Transaction_Master_Detail_Credit_Model->deleteWhereIDNotIn($transactionMasterID,$listTransactionDetalID);
			if(!empty($arrayListItemID)){
				foreach($arrayListItemID as $key => $value){			
					$itemID 								= $value;
					$lote 									= $arrayListLote[$key];
					$vencimiento							= $arrayListVencimiento[$key];
					$warehouseID 							= $objTMNew["sourceWarehouseID"];
					$objItem 								= $this->Item_Model->get_rowByPK($companyID,$itemID);
					$objItemWarehouse 						= $this->ItemWarehouse_Model->getByPK($companyID,$itemID,$warehouseID);					
					$quantity 								= helper_StringToNumber($arrayListQuantity[$key]);
					
					$objPrice 								= $this->Price_Model->get_rowByPK($companyID,$objListPrice->listPriceID,$itemID,$typePriceID);
					$objCompanyComponentConcept 			= $this->Company_Component_Concept_Model->get_rowByPK($companyID,$objComponentItem->componentID,$itemID,"IVA");
					
					//$price 								= $objItem->cost * ( 1 + ($objPrice->percentage/100));
					$price 									= $arrayListPrice[$key];					
					$ivaPercentage							= ($objCompanyComponentConcept != null ? $objCompanyComponentConcept->valueOut : 0 );					
					$unitaryAmount 							= $price * (1 + $ivaPercentage);					
					$tax1 									= $price * $ivaPercentage;
					$transactionMasterDetailID				= $listTransactionDetalID[$key];
					
					//Validar Cantidades
					$messageException = "La cantidad de '".$objItem->itemNumber. " " .$objItem->name."' es mayor que la disponible en bodega";
					$messageException = $messageException.", en bodega existen ".$objItemWarehouse->quantity." y esta solicitando : ".$quantity;
					if(
						$objItemWarehouse->quantity < $quantity  
						&& 
						$objItem->isInvoiceQuantityZero == 0
						//&&
						//$objParameterInvoiceBillingQuantityZero == "false"
					)					
					throw new Exception($messageException);
										
					//Nuevo Detalle
					if($transactionMasterDetailID == 0){	
						
						$objTMD 								= NULL;
						$objTMD["companyID"] 					= $objTM->companyID;
						$objTMD["transactionID"] 				= $objTM->transactionID;
						$objTMD["transactionMasterID"] 			= $transactionMasterID;
						$objTMD["componentID"]					= $objComponentItem->componentID;
						$objTMD["componentItemID"] 				= $itemID;
						$objTMD["quantity"] 					= $quantity;						//cantidad
						$objTMD["unitaryCost"]					= $objItem->cost;					//costo
						$objTMD["cost"] 						= $quantity * $objItem->cost;		//costo por unidad
						
						$objTMD["unitaryPrice"]					= $price;							//precio de lista
						$objTMD["unitaryAmount"]				= $unitaryAmount;					//precio de lista con inpuesto
						$objTMD["tax1"]							= $tax1;							//impuesto de lista
						$objTMD["amount"] 						= $quantity * $unitaryAmount;		//precio de lista con inpuesto por cantidad
						$objTMD["discount"]						= 0;					
						$objTMD["promotionID"] 					= 0;
						
						$objTMD["reference1"]					= $lote;
						$objTMD["reference2"]					= $vencimiento;
						$objTMD["reference3"]					= '0';
						
						
						$objTMD["catalogStatusID"]				= 0;
						$objTMD["inventoryStatusID"]			= 0;
						$objTMD["isActive"]						= 1;
						$objTMD["quantityStock"]				= 0;
						$objTMD["quantiryStockInTraffic"]		= 0;
						$objTMD["quantityStockUnaswared"]		= 0;
						$objTMD["remaingStock"]					= 0;
						$objTMD["expirationDate"]				= NULL;
						$objTMD["inventoryWarehouseSourceID"]	= $objTMNew["sourceWarehouseID"];
						$objTMD["inventoryWarehouseTargetID"]	= $objTM->targetWarehouseID;
						
						$tax1Total								= $tax1Total + $tax1;
						$subAmountTotal							= $subAmountTotal + ($quantity * $price);
						$amountTotal							= $amountTotal + $objTMD["amount"];
						$transactionMasterDetailID_				= $this->Transaction_Master_Detail_Model->insert($objTMD);
						$objTMDC								= NULL;
						$objTMDC["transactionMasterID"]			= $transactionMasterID;
						$objTMDC["transactionMasterDetailID"]	= $transactionMasterDetailID_;
						$objTMDC["reference1"]					= $this->input->post("txtFixedExpenses");
						$objTMDC["reference2"]					= $this->input->post("txtCheckReportSinRiesgo");
						$objTMDC["reference3"]					= $this->input->post("txtLayFirstLineProtocolo");
						$objTMDC["reference4"]					= "";
						$objTMDC["reference5"]					= "";
						$objTMDC["reference9"]					= "reference1: Porcentaje de Gastos fijos para las facturas de credito,reference2: Escritura Publica,reference3: Primer Linea del Protocolo";						
						$this->Transaction_Master_Detail_Credit_Model->insert($objTMDC);
						
						//Actualizar el Precio
						if($objUpdatePrice)
						{							
							$typePriceID					= $typePriceID;
							$dataUpdatePrice["price"] 		= $price;
							$dataUpdatePrice["percentage"] 	= 
															$objItem->cost == 0 ? 
																($price / 100) : 
																(((100 * $price) / $objItem->cost) - 100);																		
							
							$objPrice = $this->Price_Model->update($companyID,$listPriceID,$itemID,$typePriceID,$dataUpdatePrice);									
							
						}
						
						
					}					
					//Editar Detalle
					else{
						
						$objTMDC  								= $this->Transaction_Master_Detail_Credit_Model->get_rowByPK($transactionMasterDetailID);
						$objTMDC								= NULL;
						
						$objTMDNew 								= null;
						$objTMDNew["quantity"] 					= $quantity;					//cantidad
						$objTMDNew["unitaryCost"]				= $objItem->cost;				//costo
						$objTMDNew["cost"] 						= $quantity * $objItem->cost;	//costo por cantidad
						
						$objTMDNew["unitaryPrice"]				= $price;						//precio de lista
						$objTMDNew["unitaryAmount"]				= $unitaryAmount;				//precio de lista con inpuesto
						$objTMDNew["tax1"]						= $tax1;						//impuesto de lista
						$objTMDNew["amount"] 					= $quantity * $unitaryAmount;	//precio de lista con inpuesto por cantidad
						$objTMDNew["reference1"]				= $lote;
						$objTMDNew["reference2"]				= $vencimiento;
						$objTMDNew["reference3"]				= '0';
						$objTMDNew["inventoryWarehouseSourceID"]= $objTMNew["sourceWarehouseID"];
						
						
						$tax1Total								= $tax1Total + $tax1;
						$subAmountTotal							= $subAmountTotal + ($quantity * $price);
						$amountTotal							= $amountTotal + $objTMDNew["amount"];						
						$this->Transaction_Master_Detail_Model->update($companyID,$transactionID,$transactionMasterID,$transactionMasterDetailID,$objTMDNew);	
						
						$objTMDC["reference1"]					= $this->input->post("txtFixedExpenses");
						$objTMDC["reference2"]					= $this->input->post("txtCheckReportSinRiesgo");
						$objTMDC["reference3"]					= $this->input->post("txtLayFirstLineProtocolo");
						$objTMDC["reference4"]					= "";
						$objTMDC["reference5"]					= "";
						$objTMDC["reference9"]					= "reference1: Porcentaje de Gastos Fijos para las Facturas de Credito,reference2: Escritura Publica,reference3: Primer Linea del Protocolo";
						$this->Transaction_Master_Detail_Credit_Model->update($transactionMasterDetailID,$objTMDC);
						
						//Actualizar el Precio
						if($objUpdatePrice)
						{
							
							$typePriceID					= $typePriceID;
							$dataUpdatePrice["price"] 		= $price;
							$dataUpdatePrice["percentage"] 	= 
															$objItem->cost == 0 ? 
																($price / 100) : 
																(((100 * $price) / $objItem->cost) - 100);
							
							$objPrice = $this->Price_Model->update($companyID,$listPriceID,$itemID,$typePriceID,$dataUpdatePrice);									
							
						}
						
					}
					
					
				}
			}			
			
			//Actualizar Transaccion			
			$objTMNew["amount"] 	= $amountTotal;
			$objTMNew["tax1"] 		= $tax1Total;
			$objTMNew["subAmount"] 	= $subAmountTotal;
			$this->Transaction_Master_Model->update($companyID,$transactionID,$transactionMasterID,$objTMNew);
			
			
			//Aplicar el Documento?
			if( $this->core_web_workflow->validateWorkflowStage("tb_transaction_master_billing","statusID",$objTMNew["statusID"],COMMAND_APLICABLE,$dataSession["user"]->companyID,$dataSession["user"]->branchID,$dataSession["role"]->roleID) &&  $oldStatusID != $objTMNew["statusID"] ){
				
				//Ingresar en Kardex.
				$this->core_web_inventory->calculateKardexNewOutput($companyID,$transactionID,$transactionMasterID);			
			
				//Crear Conceptos.
				$this->core_web_concept->billing($companyID,$transactionID,$transactionMasterID);
				
				//Si es al credito crear tabla de amortizacion
				$causalIDTypeCredit 	= explode(",", $parameterCausalTypeCredit->value);
				$exisCausalInCredit		= null;
				$exisCausalInCredit		= array_search($objTMNew["transactionCausalID"] ,$causalIDTypeCredit);
				
				//si la factura es de credito
				if($exisCausalInCredit || $exisCausalInCredit === 0){
					
					
					//Crear documento del modulo
					$objCustomerCreditLine 								= $this->Customer_Credit_Line_Model->get_rowByPK($objTMNew["reference4"]);
					$objCustomerCreditDocument["companyID"] 			= $companyID;
					$objCustomerCreditDocument["entityID"] 				= $objCustomerCreditLine->entityID;
					$objCustomerCreditDocument["customerCreditLineID"] 	= $objCustomerCreditLine->customerCreditLineID;
					$objCustomerCreditDocument["documentNumber"] 		= $objTM->transactionNumber;
					$objCustomerCreditDocument["dateOn"] 				= $objTMNew["transactionOn"];
					$objCustomerCreditDocument["exchangeRate"] 			= $objTMNew["exchangeRate"];
					$objCustomerCreditDocument["term"] 					= $objCustomerCreditLine->term;
					$objCustomerCreditDocument["interes"] 				= $objCustomerCreditLine->interestYear;
					$objCustomerCreditDocument["amount"] 				= $amountTotal;
					$objCustomerCreditDocument["currencyID"] 			= $objTMNew["currencyID"];					
					$objCustomerCreditDocument["statusID"] 				= $this->core_web_workflow->getWorkflowInitStage("tb_customer_credit_document","statusID",$companyID,$branchID,$roleID)[0]->workflowStageID;
					$objCustomerCreditDocument["reference1"] 			= $objTMNew["note"];
					$objCustomerCreditDocument["reference2"] 			= "";
					$objCustomerCreditDocument["reference3"] 			= "";
					$objCustomerCreditDocument["isActive"] 				= 1;
					
					$objCustomerCreditDocument["providerIDCredit"] 		= $objTMNew["reference1"];
					$objCustomerCreditDocument["periodPay"]				= $objCustomerCreditLine->periodPay;
					$objCustomerCreditDocument["typeAmortization"] 		= $objCustomerCreditLine->typeAmortization;
					$objCustomerCreditDocument["balance"] 				= $amountTotal;
					$objCustomerCreditDocument["reportSinRiesgo"] 	 	= $this->input->post("txtCheckReportSinRiesgo");
					$customerCreditDocumentID 							= $this->Customer_Credit_Document_Model->insert($objCustomerCreditDocument);
					$periodPay 											= $this->Catalog_Item_Model->get_rowByCatalogItemID($objCustomerCreditLine->periodPay);
					
					//Crear tabla de amortizacion
					$this->financial_amort->amort(
						$objCustomerCreditDocument["amount"], 		/*monto*/
						$objCustomerCreditDocument["interes"],		/*interes anual*/
						$objCustomerCreditDocument["term"],			/*numero de pagos*/	
						$periodPay->sequence,						/*frecuencia de pago en dia*/
						$objTMNew["transactionOn2"], 				/*fecha del credito*/	
						$objCustomerCreditLine->typeAmortization 	/*tipo de amortizacion*/
					);
					
					$tableAmortization = $this->financial_amort->getTable();
					if($tableAmortization["detail"])
					foreach($tableAmortization["detail"] as $key => $itemAmortization){
						$objCustomerAmoritizacion["customerCreditDocumentID"]	= $customerCreditDocumentID;
						$objCustomerAmoritizacion["balanceStart"]				= $itemAmortization["saldoInicial"];
						$objCustomerAmoritizacion["dateApply"]					= $itemAmortization["date"];
						$objCustomerAmoritizacion["interest"]					= $itemAmortization["interes"];
						$objCustomerAmoritizacion["capital"]					= $itemAmortization["principal"];
						$objCustomerAmoritizacion["share"]						= $itemAmortization["cuota"];
						$objCustomerAmoritizacion["balanceEnd"]					= $itemAmortization["saldo"];
						$objCustomerAmoritizacion["remaining"]					= $itemAmortization["cuota"];
						$objCustomerAmoritizacion["dayDelay"]					= 0;
						$objCustomerAmoritizacion["note"]						= '';
						$objCustomerAmoritizacion["statusID"]					= $this->core_web_workflow->getWorkflowInitStage("tb_customer_credit_amoritization","statusID",$companyID,$branchID,$roleID)[0]->workflowStageID;
						$objCustomerAmoritizacion["isActive"]					= 1;
						$objCustomerAmortizationID 								= $this->Customer_Credit_Amortization_Model->insert($objCustomerAmoritizacion);
					}
					
					//Crear las personas relacionadas a la factura
					$objEntityRelated								= array();
					$objEntityRelated["customerCreditDocumentID"]	= $customerCreditDocumentID;
					$objEntityRelated["entityID"]					= $objCustomerCreditLine->entityID;
					$objEntityRelated["type"]						= $this->core_web_parameter->getParameter("CXC_PROPIETARIO_DEL_CREDITO",$companyID)->value;
					$objEntityRelated["typeCredit"]					= 401; /*comercial*/
					$objEntityRelated["statusCredit"]				= 429; /*activo*/
					$objEntityRelated["typeGarantia"]				= 444; /*pagare*/
					$objEntityRelated["typeRecuperation"]			= 450; /*recuperacion normal */
					$objEntityRelated["ratioDesembolso"]			= 1;
					$objEntityRelated["ratioBalance"]				= 1;
					$objEntityRelated["ratioBalanceExpired"]		= 1;
					$objEntityRelated["ratioShare"]					= 1;
					$objEntityRelated["isActive"]					= 1;
					$this->core_web_auditoria->setAuditCreated($objEntityRelated,$dataSession);			
					$ccEntityID 		= $this->Customer_Credit_Document_Endity_Related_Model->insert($objEntityRelated);
					

					//Calculo del Total en Dolares
					$amountTotalDolares	= $objTMNew["exchangeRate"] > 1 ? 
								/*factura en cordoba*/ ($amountTotal * round($objTMNew["exchangeRate"],4)) : 
								/*factura en dolares*/ ($amountTotal * 1 );
					
					
					//disminuir el balance de general					
					$objCustomerCredit 					= $this->Customer_Credit_Model->get_rowByPK($objCustomerCreditLine->companyID,$objCustomerCreditLine->branchID,$objCustomerCreditLine->entityID);
					$objCustomerCreditNew["balanceDol"]	= $objCustomerCredit->balanceDol - $amountTotalDolares;
					$this->Customer_Credit_Model->update($objCustomerCreditLine->companyID,$objCustomerCreditLine->branchID,$objCustomerCreditLine->entityID,$objCustomerCreditNew);
					
					//disminuir el balance de linea
					if($objCustomerCreditLine->currencyID == $objCurrencyCordoba->currencyID)
						$objCustomerCreditLineNew["balance"]	= $objCustomerCreditLine->balance - $amountTotal;
					else
						$objCustomerCreditLineNew["balance"]	= $objCustomerCreditLine->balance - $amountTotalDolares;
						
					
					$this->Customer_Credit_Line_Model->update($objCustomerCreditLine->customerCreditLineID,$objCustomerCreditLineNew);
					

				}
				
			}
			
			
			if($this->db->trans_status() !== false){
				$this->db->trans_commit();						
				$this->core_web_notification->set_message(false,SUCCESS);
				redirect('app_invoice_billing/edit/companyID/'.$companyID."/transactionID/".$transactionID."/transactionMasterID/".$transactionMasterID);
			}
			else{
				$this->db->trans_rollback();						
				$this->core_web_notification->set_message(true,$this->db->_error_message());
				redirect('app_invoice_billing/add');	
			}
			
		}
		catch(Exception $ex){		
			log_message("ERROR",print_r("error exception",true));			
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
			$this->load->model("Transaction_Master_Detail_Credit_Model");	
			$this->load->model("Item_Model");
			$this->load->model("Transaction_Causal_Model");
			$this->load->model("ItemWarehouse_Model");
			$this->load->model("List_Price_Model");
			$this->load->model("Price_Model");
			$this->load->model("Company_Component_Concept_Model");
			
			$this->load->model("Entity_Account_Model");
			$this->load->model("Customer_Credit_Document_Model");
			$this->load->model("Customer_Credit_Amortization_Model");
			$this->load->model("Customer_Credit_Line_Model");
			$this->load->model("Customer_Credit_Document_Endity_Related_Model");
			$this->load->model("Customer_Credit_Model");		
			$this->load->model("core/Catalog_Item_Model");							
			$this->load->library("financial/financial_amort");
			
			//Obtener el Componente de Transacciones Facturacion
			$objComponentBilling			= $this->core_web_tools->getComponentIDBy_ComponentName("tb_transaction_master_billing");
			if(!$objComponentBilling)
			throw new Exception("EL COMPONENTE 'tb_transaction_master_billing' NO EXISTE...");
			
			
			$objComponentItem				= $this->core_web_tools->getComponentIDBy_ComponentName("tb_item");
			if(!$objComponentItem)
			throw new Exception("EL COMPONENTE 'tb_item' NO EXISTE...");
			
			
			$companyID 							= $dataSession["user"]->companyID;
			$branchID 							= $dataSession["user"]->branchID;
			$roleID 							= $dataSession["role"]->roleID;
			$userID								= $dataSession["user"]->userID;
			
			//Obtener transaccion
			$transactionID 							= $this->core_web_transaction->getTransactionID($dataSession["user"]->companyID,"tb_transaction_master_billing",0);
			$companyID 								= $dataSession["user"]->companyID;
			$objT 									= $this->Transaction_Model->getByCompanyAndTransaction($dataSession["user"]->companyID,$transactionID);
			$objTransactionCausal 					= $this->Transaction_Causal_Model->getByCompanyAndTransactionAndCausal($companyID,$transactionID,$this->input->post("txtCausalID"));
			
			
			//Valores de tasa de cambio
			date_default_timezone_set(APP_TIMEZONE); 
			$objCurrencyDolares						= $this->core_web_currency->getCurrencyExternal($companyID);
			$objCurrencyCordoba						= $this->core_web_currency->getCurrencyDefault($companyID);
			$dateOn 								= date("Y-m-d");
			$dateOn 								= date_format(date_create($dateOn),"Y-m-d");
			$exchangeRate 							= $this->core_web_currency->getRatio($companyID,$dateOn,1,$objCurrencyDolares->currencyID,$objCurrencyCordoba->currencyID);
			
 
			if($this->core_web_accounting->cycleIsCloseByDate($dataSession["user"]->companyID,$this->input->post("txtDate")))
			throw new Exception("EL DOCUMENTO NO PUEDE INGRESAR, EL CICLO CONTABLE ESTA CERRADO");
			
			$this->core_web_permission->getValueLicense($dataSession["user"]->companyID,$this->router->class."/"."index");
			$objParameterInvoiceBillingQuantityZero		= $this->core_web_parameter->getParameter("INVOICE_BILLING_QUANTITY_ZERO",$companyID);
			$objParameterInvoiceBillingQuantityZero		= $objParameterInvoiceBillingQuantityZero->value;
			
			//obtener el primer estado  de la factura o el estado inicial.
			$objListWorkflowStage					= $this->core_web_workflow->getWorkflowInitStage("tb_transaction_master_billing","statusID",$companyID,$branchID,$roleID);

			//Saber si se va autoaplicar
			$objParameterInvoiceAutoApply			= $this->core_web_parameter->getParameter("INVOICE_AUTOAPPLY_CASH",$companyID);
			$objParameterInvoiceAutoApply			= $objParameterInvoiceAutoApply->value;
			//Saber si es al credito
			$parameterCausalTypeCredit 				= $this->core_web_parameter->getParameter("INVOICE_BILLING_CREDIT",$companyID);			
			$causalIDTypeCredit 					= explode(",", $parameterCausalTypeCredit->value);
			$exisCausalInCredit						= null;
			$exisCausalInCredit						= array_search($this->input->post("txtCausalID"),$causalIDTypeCredit);
			if($exisCausalInCredit || $exisCausalInCredit === 0){
				$exisCausalInCredit = "true";
			}
			//Si esta configurado como auto aplicado
			//y es al credito. cambiar el estado por el estado inicial, que es registrada
			$statusID = "";
			if($objParameterInvoiceAutoApply == "true" && $exisCausalInCredit == "true" ){				
				$statusID = $objListWorkflowStage[0]->workflowStageID;
			}
			//De lo contrario respetar el estado que venga en pantalla
			else {
				$statusID = $this->input->post("txtStatusID");
			}
			
			
			$objTM["companyID"] 					= $dataSession["user"]->companyID;
			$objTM["transactionID"] 				= $transactionID;			
			$objTM["branchID"]						= $dataSession["user"]->branchID;
			$objTM["transactionNumber"]				= $this->core_web_counter->goNextNumber($dataSession["user"]->companyID,$dataSession["user"]->branchID,"tb_transaction_master_billing",0);
			$objTM["transactionCausalID"] 			= $this->input->post("txtCausalID");
			$objTM["entityID"] 						= $this->input->post("txtCustomerID");
			$objTM["transactionOn"]					= $this->input->post("txtDate");
			$objTM["transactionOn2"]				= $this->input->post("txtDateFirst");//Fecha del Primer Pago, de las facturas al credito
			$objTM["statusIDChangeOn"]				= date("Y-m-d H:m:s");
			$objTM["componentID"] 					= $objComponentBilling->componentID;
			$objTM["note"] 							= $this->input->post("txtNote",'');
			$objTM["sign"] 							= $objT->signInventory;
			$objTM["currencyID"]					= $this->input->post("txtCurrencyID"); 
			$objTM["currencyID2"]					= $this->core_web_currency->getTarget($companyID,$objTM["currencyID"]);
			$objTM["exchangeRate"]					= $this->core_web_currency->getRatio($dataSession["user"]->companyID,date("Y-m-d"),1,$objTM["currencyID2"],$objTM["currencyID"]);
			$objTM["reference1"] 					= $this->input->post("txtReference1");
			$objTM["descriptionReference"] 			= "reference1:entityID del proveedor de credito para las facturas al credito,reference4: customerCreditLineID linea de credito del cliente";
			$objTM["reference2"] 					= $this->input->post("txtReference2");
			$objTM["reference3"] 					= $this->input->post("txtReference3");
			$objTM["reference4"] 					= $this->input->post("txtCustomerCreditLineID","0");
			$objTM["statusID"] 						= $statusID;
			$objTM["amount"] 						= 0;
			$objTM["isApplied"] 					= 0;
			$objTM["journalEntryID"] 				= 0;
			$objTM["classID"] 						= NULL;
			$objTM["areaID"] 						= NULL;
			$objTM["sourceWarehouseID"]				= $this->input->post("txtWarehouseID");
			$objTM["targetWarehouseID"]				= NULL;
			$objTM["isActive"]						= 1;
			$this->core_web_auditoria->setAuditCreated($objTM,$dataSession);			
			
			
			$this->db->trans_begin();
			$transactionMasterID = $this->Transaction_Master_Model->insert($objTM);
			
			//Crear la Carpeta para almacenar los Archivos del Documento
			mkdir(PATH_FILE_OF_APP."/company_".$companyID."/component_".$objComponentBilling->componentID."/component_item_".$transactionMasterID, 0700);
			//Ingresar Informacion Adicional
			$objTMInfo["companyID"]					= $objTM["companyID"];
			$objTMInfo["transactionID"]				= $objTM["transactionID"];
			$objTMInfo["transactionMasterID"]		= $transactionMasterID;
			$objTMInfo["zoneID"]					= $this->input->post("txtZoneID");
			$objTMInfo["routeID"]					= 0;
			$objTMInfo["referenceClientName"]		= $this->input->post("txtReferenceClientName");
			$objTMInfo["referenceClientIdentifier"]	= $this->input->post("txtReferenceClientIdentifier");
			$objTMInfo["receiptAmount"]				= helper_StringToNumber($this->input->post("txtReceiptAmount",0));
			$objTMInfo["receiptAmountDol"]			= helper_StringToNumber($this->input->post("txtReceiptAmountDol",0));
			$this->Transaction_Master_Info_Model->insert($objTMInfo);
			

			//Recorrer la lista del detalle del documento
			$arrayListItemID 							= $this->input->post("txtItemID");
			$arrayListQuantity	 						= $this->input->post("txtQuantity");	
			$arrayListPrice		 						= $this->input->post("txtPrice");
			$arrayListSubTotal	 						= $this->input->post("txtSubTotal");
			$arrayListIva		 						= $this->input->post("txtIva");
			$arrayListLote	 							= $this->input->post("txtDetailLote");			
			$arrayListVencimiento						= $this->input->post("txtDetailVencimiento");			
			
			//Ingresar la configuracion de precios		
			$amountTotal 									= 0;
			$tax1Total 										= 0;
			$subAmountTotal									= 0;
			
			//Tipo de precio seleccionado por el usuario,
			//Actualmente no se esta usando
			$typePriceID 							= $this->input->post("txtTypePriceID");
			$objListPrice 							= $this->List_Price_Model->getListPriceToApply($companyID);
			//obtener la lista de precio por defecto
			$objParameterPriceDefault	= $this->core_web_parameter->getParameter("INVOICE_DEFAULT_PRICELIST",$companyID);
			$listPriceID 	= $objParameterPriceDefault->value;
			//obener los tipos de precio de la lista de precio por defecto
			$objTipePrice 	= $this->core_web_catalog->getCatalogAllItem("tb_price","typePriceID",$companyID);
			
			//Parametro para validar si se cambian los precios en la facturacion
			$objParameterUpdatePrice	= $this->core_web_parameter->getParameter("INVOICE_UPDATEPRICE_ONLINE",$companyID);
			$objUpdatePrice 			= $objParameterUpdatePrice->value;
			
			
			if(!empty($arrayListItemID)){
				foreach($arrayListItemID as $key => $value){
					$itemID 								= $value;
					$lote 									= $arrayListLote[$key];
					$vencimiento							= $arrayListVencimiento[$key];
					$warehouseID 							= $objTM["sourceWarehouseID"];
					$objItem 								= $this->Item_Model->get_rowByPK($companyID,$itemID);					
					$objItemWarehouse 						= $this->ItemWarehouse_Model->getByPK($companyID,$itemID,$warehouseID);
					$quantity 								= helper_StringToNumber($arrayListQuantity[$key]);
					$objPrice 								= $this->Price_Model->get_rowByPK($companyID,$objListPrice->listPriceID,$itemID,$typePriceID);
					$objCompanyComponentConcept 			= $this->Company_Component_Concept_Model->get_rowByPK($companyID,$objComponentItem->componentID,$itemID,"IVA");
					
					//$price								= $objItem->cost * ( 1 + ($objPrice->percentage/100));
					$price 									= $arrayListPrice[$key];
					$ivaPercentage							= ($objCompanyComponentConcept != null ? $objCompanyComponentConcept->valueOut : 0 );
					$unitaryAmount 							= $price * (1 + $ivaPercentage);
					$tax1 									= $price * $ivaPercentage;
					
					
					if(
						$objItemWarehouse->quantity < $quantity 
						&& 
						$objItem->isInvoiceQuantityZero == 0
						&&						
						$objParameterInvoiceBillingQuantityZero == "false"
					)
					throw new Exception("La cantidad de '"+$objItem->itemNumber+ " " +$objItem->name+"' es mayor que la disponible en bodega");
					
					$objTMD 								= NULL;
					$objTMD["companyID"] 					= $objTM["companyID"];
					$objTMD["transactionID"] 				= $objTM["transactionID"];
					$objTMD["transactionMasterID"] 			= $transactionMasterID;
					$objTMD["componentID"]					= $objComponentItem->componentID;
					$objTMD["componentItemID"] 				= $itemID;
					$objTMD["quantity"] 					= $quantity;						//cantidad
					$objTMD["unitaryCost"]					= $objItem->cost;					//costo
					$objTMD["cost"] 						= $quantity * $objItem->cost;		//cantidad por costo
					
					$objTMD["unitaryPrice"]					= $price;							//precio de lista
					$objTMD["unitaryAmount"]				= $unitaryAmount;					//precio de lista con inpuesto
					$objTMD["tax1"]							= $tax1;							//impuesto de lista
					$objTMD["amount"] 						= $quantity * $unitaryAmount;		//precio de lista con inpuesto por cantidad
					$objTMD["discount"]						= 0;					
					$objTMD["promotionID"] 					= 0;
					
					$objTMD["reference1"]					= $lote;
					$objTMD["reference2"]					= $vencimiento;
					$objTMD["reference3"]					= '0';
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
					
					$tax1Total								= $tax1Total + $tax1;
					$subAmountTotal							= $subAmountTotal + ($quantity * $price);
					$amountTotal							= $amountTotal + $objTMD["amount"];
					
					$transactionMasterDetailID_				= $this->Transaction_Master_Detail_Model->insert($objTMD);
					
					$objTMDC								= NULL;
					$objTMDC["transactionMasterID"]			= $transactionMasterID;
					$objTMDC["transactionMasterDetailID"]	= $transactionMasterDetailID_;
					$objTMDC["reference1"]					= $this->input->post("txtFixedExpenses");
					$objTMDC["reference2"]					= $this->input->post("txtCheckReportSinRiesgo");
					$objTMDC["reference3"]					= $this->input->post("txtLayFirstLineProtocolo");
					$objTMDC["reference4"]					= "";
					$objTMDC["reference5"]					= "";
					$objTMDC["reference9"]					= "reference1: Porcentaje de Gastos Fijo para las facturas de credito,reference2: Escritura Publica,reference3: Primer Linea del Protocolo";
					$this->Transaction_Master_Detail_Credit_Model->insert($objTMDC);
					
					//Actualizar tipo de precio
					if($objUpdatePrice)
					{ 
						
						$typePriceID					= $typePriceID;																				
						$dataUpdatePrice["price"] 		= $price;
						$dataUpdatePrice["percentage"] 	= 
														$objItem->cost == 0 ? 
															($price / 100) : 
															(((100 * $price) / $objItem->cost) - 100);
															
						
						$objPrice = $this->Price_Model->update($companyID,$listPriceID,$itemID,$typePriceID,$dataUpdatePrice);
								
						
					}
					
					
				}
			}
			
			//Actualizar Transaccion
			$objTM["amount"] 	= $amountTotal;
			$objTM["tax1"] 		= $tax1Total;
			$objTM["subAmount"] = $subAmountTotal;			
			$this->Transaction_Master_Model->update($companyID,$transactionID,$transactionMasterID,$objTM);
			
			//Aplicar el Documento?
			//Las factuas de credito no se auto aplican auque este el parametro, por que hay que crer el documento
			//y esto debe ser revisado cuidadosamente
			if( $this->core_web_workflow->validateWorkflowStage("tb_transaction_master_billing","statusID",$objTM["statusID"],COMMAND_APLICABLE,$dataSession["user"]->companyID,$dataSession["user"]->branchID,$dataSession["role"]->roleID)){
				
				//Ingresar en Kardex.
				$this->core_web_inventory->calculateKardexNewOutput($companyID,$transactionID,$transactionMasterID);			
			
				//Crear Conceptos.
				$this->core_web_concept->billing($companyID,$transactionID,$transactionMasterID);
				
			}
			
			if($this->db->trans_status() !== false){
				$this->db->trans_commit();						
				$this->core_web_notification->set_message(false,SUCCESS);
				redirect('app_invoice_billing/edit/companyID/'.$companyID."/transactionID/".$objTM["transactionID"]."/transactionMasterID/".$transactionMasterID);
			}
			else{
				$this->db->trans_rollback();						
				$this->core_web_notification->set_message(true,$this->db->_error_message());
				redirect('app_invoice_billing/add');	
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
				redirect('app_invoice_billing/add');
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
				redirect('app_invoice_billing/add');
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
			 
			$this->load->model("UserWarehouse_Model");
			$this->load->model("Customer_Model");
			$this->load->model("Natural_Model");
			$this->load->model("Legal_Model");
			$this->load->model("Transaction_Causal_Model");
			$this->load->model("List_Price_Model");
			$this->load->model("Company_Currency_Model");
			
			$this->load->model("Provider_Model");
			
			$companyID 							= $dataSession["user"]->companyID;
			$branchID 							= $dataSession["user"]->branchID;
			$roleID 							= $dataSession["role"]->roleID;
			$userID								= $dataSession["user"]->userID;
			
			//Obtener el componente de Item
			$objComponentCustomer	= $this->core_web_tools->getComponentIDBy_ComponentName("tb_customer");
			if(!$objComponentCustomer)
			throw new Exception("EL COMPONENTE 'tb_customer' NO EXISTE...");
			
			//Obtener el componente de Item
			$objComponentItem	= $this->core_web_tools->getComponentIDBy_ComponentName("tb_item");
			if(!$objComponentItem)
			throw new Exception("EL COMPONENTE 'tb_item' NO EXISTE...");
			
			//Obtener Tasa de Cambio			
			$companyID 							= $dataSession["user"]->companyID;
			$branchID 							= $dataSession["user"]->branchID;
			$roleID 							= $dataSession["role"]->roleID;
			$transactionID 						= $this->core_web_transaction->getTransactionID($dataSession["user"]->companyID,"tb_transaction_master_billing",0);
			$objCurrency						= $this->core_web_currency->getCurrencyDefault($companyID);
			$targetCurrency						= $this->core_web_currency->getCurrencyExternal($companyID);			
			$customerDefault					= $this->core_web_parameter->getParameter("INVOICE_BILLING_CLIENTDEFAULT",$companyID);
			$objListPrice 						= $this->List_Price_Model->getListPriceToApply($companyID);
			$objListCurrency					= $this->Company_Currency_Model->getByCompany($companyID);
			
			
			if(!$objListPrice)
			throw new Exception("NO EXISTE UNA LISTA DE PRECIO PARA SER APLICADA");
		
			$objParameterInvoiceAutoApply			= $this->core_web_parameter->getParameter("INVOICE_AUTOAPPLY_CASH",$companyID);
			$objParameterInvoiceAutoApply			= $objParameterInvoiceAutoApply->value;
			$objParameterTypePreiceDefault			= $this->core_web_parameter->getParameter("INVOICE_DEFAULT_TYPE_PRICE",$companyID);
			$objParameterTypePreiceDefault			= $objParameterTypePreiceDefault->value;
			$objParameterTipoWarehouseDespacho		= $this->core_web_parameter->getParameter("INVOICE_TYPE_WAREHOUSE_DESPACHO",$companyID);
			$objParameterTipoWarehouseDespacho		= $objParameterTipoWarehouseDespacho->value;
			
			//Obtener la lista de estados
			if($objParameterInvoiceAutoApply == "true"){
				$dataView["objListWorkflowStage"]	= $this->core_web_workflow->getWorkflowStageApplyFirst("tb_transaction_master_billing","statusID",$companyID,$branchID,$roleID);
			}
			else{
				$dataView["objListWorkflowStage"]	= $this->core_web_workflow->getWorkflowInitStage("tb_transaction_master_billing","statusID",$companyID,$branchID,$roleID);
			}
			
			
			
			//Tipo de Factura
			$dataView["companyID"]							= $dataSession["user"]->companyID;
			$dataView["userID"]								= $dataSession["user"]->userID;
			$dataView["userName"]							= $dataSession["user"]->nickname;
			$dataView["roleID"]								= $dataSession["role"]->roleID;
			$dataView["roleName"]							= $dataSession["role"]->name;
			$dataView["branchID"]							= $dataSession["branch"]->branchID;
			$dataView["branchName"]							= $dataSession["branch"]->name;
			$dataView["exchangeRate"]						= $this->core_web_currency->getRatio($companyID,date("Y-m-d"),1,$targetCurrency->currencyID,$objCurrency->currencyID);			
			$dataView["listCurrency"]						= $objListCurrency;
			$dataView["objListPrice"]						= $objListPrice;
			$dataView["objComponentItem"]					= $objComponentItem;
			$dataView["objComponentCustomer"]				= $objComponentCustomer;
			$dataView["objCaudal"]							= $this->Transaction_Causal_Model->getCausalByBranch($companyID,$transactionID,$branchID);			
			$dataView["warehouseID"]						= $dataView["objCaudal"][0]->warehouseSourceID;
			$dataView["objListWarehouse"]					= $this->UserWarehouse_Model->getRowByUserID($companyID,$userID);			
			$dataView["objCustomerDefault"]					= $this->Customer_Model->get_rowByCode($companyID,$customerDefault->value);
			$dataView["objListTypePrice"]					= $this->core_web_catalog->getCatalogAllItem("tb_price","typePriceID",$companyID);
			$dataView["objListZone"]						= $this->core_web_catalog->getCatalogAllItem("tb_transaction_master_info_billing","zoneID",$companyID);			
			$dataView["listProvider"]						= $this->Provider_Model->get_rowByCompany($companyID);
			$dataView["objListaPermisos"]					= $dataSession["menuHiddenPopup"];
			$dataView["objParameterTypePreiceDefault"] 		= $objParameterTypePreiceDefault;
			$dataView["objParameterTipoWarehouseDespacho"] 	= $objParameterTipoWarehouseDespacho;
			
			
			$objParameterInvoiceBillingQuantityZero					= $this->core_web_parameter->getParameter("INVOICE_BILLING_QUANTITY_ZERO",$companyID);
			$dataView["objParameterInvoiceBillingQuantityZero"]		= $objParameterInvoiceBillingQuantityZero->value;
			
						
			if(!$dataView["objCustomerDefault"])
			throw new Exception("NO EXISTE EL CLIENTE POR DEFECTO");
			
			$dataView["objNaturalDefault"]		= $this->Natural_Model->get_rowByPK($companyID,$dataView["objCustomerDefault"]->branchID,$dataView["objCustomerDefault"]->entityID);
			$dataView["objLegalDefault"]		= $this->Legal_Model->get_rowByPK($companyID,$dataView["objCustomerDefault"]->branchID,$dataView["objCustomerDefault"]->entityID);
			
			//Renderizar Resultado 
			$dataSession["notification"]	= $this->core_web_error->get_error($dataSession["user"]->userID);
			$dataSession["message"]			= $this->core_web_notification->get_message();
			$dataSession["head"]			= $this->load->view('app_invoice_billing/news_head',$dataView,true);
			$dataSession["body"]			= $this->load->view('app_invoice_billing/news_body',$dataView,true);
			$dataSession["script"]			= $this->load->view('app_invoice_billing/news_script',$dataView,true);  
			$dataSession["footer"]			= "";
			//$this->load->view("core_masterpage/default_masterpage",$dataSession);	
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
			$objComponent		= $this->core_web_tools->getComponentIDBy_ComponentName("tb_transaction_master_billing");
			if(!$objComponent)
			throw new Exception("00409 EL COMPONENTE 'tb_transaction_master_billing' NO EXISTE...");
			
			
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
			$dataSession["head"]			= $this->load->view('app_invoice_billing/list_head','',true);
			$dataSession["footer"]			= $this->load->view('app_invoice_billing/list_footer','',true);
			$dataSession["body"]			= $dataViewRender; 
			$dataSession["script"]			= $this->load->view('app_invoice_billing/list_script','',true).$this->core_web_javascript->createVar("componentID",$objComponent->componentID);  
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
	//facturacino imprimir directamente en impresora, formato de ticket
	function viewPrinterDirect(){
		try{
			
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
			$this->load->model("List_Price_Model");
			$this->load->model("Transaction_Master_Model");
			$this->load->model("Transaction_Master_Info_Model");
			$this->load->model("Transaction_Master_Detail_Model");
			
			
			$this->load->model("Transaction_Master_Detail_Credit_Model");	
			$this->load->model("Transaction_Master_Concept_Model");
			$this->load->model("Company_Currency_Model");
			$this->load->model("Provider_Model");			
			$this->load->model("core/Company_Model");			
			$this->load->model("core/Currency_Model"); 
			$this->load->model("core/User_Model");
			$this->load->model("core/Branch_Model");
			$this->load->model("core/Workflow_Stage_Model");
			log_message("ERROR","imprmir imporesora directo inicio");	
			
			
			
			//Obtener el componente de Item
			$objComponentItem	= $this->core_web_tools->getComponentIDBy_ComponentName("tb_item");
			if(!$objComponentItem)
			throw new Exception("EL COMPONENTE 'tb_item' NO EXISTE...");
			
			$uri					= $this->uri->uri_to_assoc(3);						
			$companyID				= $uri["companyID"];
			$transactionID			= $uri["transactionID"];	
			$transactionMasterID	= $uri["transactionMasterID"];	
			
			$dataView["objTransactionMaster"]					= $this->Transaction_Master_Model->get_rowByPK($companyID,$transactionID,$transactionMasterID);
			$dataView["objTransactionMasterInfo"]				= $this->Transaction_Master_Info_Model->get_rowByPK($companyID,$transactionID,$transactionMasterID);
			$dataView["objTransactionMasterDetail"]				= $this->Transaction_Master_Detail_Model->get_rowByTransaction($companyID,$transactionID,$transactionMasterID);
			$dataView["objTransactionMasterDetailWarehouse"]	= $this->Transaction_Master_Detail_Model->get_rowByTransactionAndWarehouse($companyID,$transactionID,$transactionMasterID);
			$dataView["objTransactionMasterDetailConcept"]		= $this->Transaction_Master_Concept_Model->get_rowByTransactionMasterConcept($companyID,$transactionID,$transactionMasterID,$objComponentItem->componentID);
			
			
			$dataView["objComponentCompany"]			= $this->core_web_tools->getComponentIDBy_ComponentName("tb_company");
			$dataView["objParameterLogo"]				= $this->core_web_parameter->getParameter("CORE_COMPANY_LOGO",$companyID);
			$dataView["objParameterPhoneProperty"]		= $this->core_web_parameter->getParameter("CORE_PROPIETARY_PHONE",$companyID);
			$dataView["objCompany"] 					= $this->Company_Model->get_rowByPK($companyID);			
			$dataView["objUser"] 						= $this->User_Model->get_rowByPK($companyID,$dataView["objTransactionMaster"]->createdAt,$dataView["objTransactionMaster"]->createdBy);
			$dataView["Identifier"]						= $this->core_web_parameter->getParameter("CORE_COMPANY_IDENTIFIER",$companyID);
			$dataView["objBranch"]						= $this->Branch_Model->get_rowByPK($companyID,$dataView["objTransactionMaster"]->branchID);
			$dataView["objTipo"]						= $this->Transaction_Causal_Model->getByCompanyAndTransactionAndCausal($companyID,$dataView["objTransactionMaster"]->transactionID,$dataView["objTransactionMaster"]->transactionCausalID);
			$dataView["objCustumer"]					= $this->Customer_Model->get_rowByEntity($companyID,$dataView["objTransactionMaster"]->entityID);
			$dataView["objCurrency"]					= $this->Currency_Model->get_rowByPK($dataView["objTransactionMaster"]->currencyID);
			$dataView["prefixCurrency"]					= $dataView["objCurrency"]->simbol." ";
			$dataView["cedulaCliente"] 					= $dataView["objTransactionMasterInfo"]->referenceClientIdentifier == "" ? $dataView["objCustumer"]->customerNumber :  $dataView["objTransactionMasterInfo"]->referenceClientIdentifier;
			$dataView["nombreCliente"] 					= $dataView["objTransactionMasterInfo"]->referenceClientName  == "" ? $dataView["objCustumer"]->firstName : $dataView["objTransactionMasterInfo"]->referenceClientName ;
			$dataView["objStage"]						= $this->Workflow_Stage_Model->get_rowByWorkflowStageIDOnly($dataView["objTransactionMaster"]->statusID);
			//obtener nombre de impresora por defecto
			$objParameterPrinterName = $this->core_web_parameter->getParameter("INVOICE_BILLING_PRINTER_DIRECT_NAME_DEFAULT",$companyID);
			$objParameterPrinterName = $objParameterPrinterName->value;
			log_message("ERROR","imprmir imporesora directo fin");
			
			
			$this->load->library('core_web_printer_direct/library.php');						
			$objPrinter = new Library();
			$objPrinter->configurationPrinter($objParameterPrinterName);
			$objPrinter->executePrinter($dataView);
		}
		catch(Exception $ex){
			show_error($ex->getLine()." ".$ex->getMessage() ,500 );
		}	
	}
	//facturacion e imprimir en una factura memebretada
	function viewRegisterPrePrinter(){
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
			$this->load->model("Transaction_Causal_Model");
			$this->load->model("core/Company_Model"); 
			$this->load->model("core/User_Model");
			$this->load->model("Warehouse_Model"); 
			$this->load->model("core/Branch_Model");
			$this->load->model("Customer_Model");
			$this->load->model("Natural_Model");
			$this->load->model("Legal_Model");
			
			
			//Crear Objetos
			$pdf 	= new EXTCezpdf('LEGAL','portrait','none',array());
			$pdf->selectFont('./fonts/Courier.afm');
			$pdf->ezSetMargins(22 /*top margin*/,17 /*bottom margin*/,22 /*left mergin*/,22 /*rigth margin*/);
			$width 	= $pdf->EXTGetWidth();							
			
			//Get Component
			$objComponent							= $this->core_web_tools->getComponentIDBy_ComponentName("tb_company");
			$objParameter							= $this->core_web_parameter->getParameter("CORE_COMPANY_LOGO",$companyID);
			$objPhone								= $this->core_web_parameter->getParameter("CORE_PHONE",$companyID);
			$objCompany 							= $this->Company_Model->get_rowByPK($companyID);			
			$parameterCausalTypeCredit 				= $this->core_web_parameter->getParameter("INVOICE_BILLING_CREDIT",$companyID);
			
			//Get Documento					
			$datView["objTM"]	 					= $this->Transaction_Master_Model->get_rowByPK($companyID,$transactionID,$transactionMasterID);
			$datView["objTMD"]						= $this->Transaction_Master_Detail_Model->get_rowByTransaction($companyID,$transactionID,$transactionMasterID);
			$datView["objTM"]->transactionOn 		= date_format(date_create($datView["objTM"]->transactionOn),"Y-m-d");
			$datView["objTM_Day"] 					= date_format(date_create($datView["objTM"]->transactionOn),"d");
			$datView["objTM_Month"] 				= date_format(date_create($datView["objTM"]->transactionOn),"m");
			$datView["objTM_Year"] 					= date_format(date_create($datView["objTM"]->transactionOn),"Y");
			$datView["objUser"] 					= $this->User_Model->get_rowByPK($datView["objTM"]->companyID,$datView["objTM"]->createdAt,$datView["objTM"]->createdBy);
			$datView["Identifier"]					= $this->core_web_parameter->getParameter("CORE_COMPANY_IDENTIFIER",$companyID);
			$datView["objBranch"]					= $this->Branch_Model->get_rowByPK($datView["objTM"]->companyID,$datView["objTM"]->branchID);
			$datView["objStage"]					= $this->core_web_workflow->getWorkflowStage("tb_transaction_master_billing","statusID",$datView["objTM"]->statusID,$companyID,$branchID,$roleID);
			$datView["objTipo"]						= $this->Transaction_Causal_Model->getByCompanyAndTransactionAndCausal($companyID,$datView["objTM"]->transactionID,$datView["objTM"]->transactionCausalID);
			$datView["objCustumer"]					= $this->Customer_Model->get_rowByEntity($companyID,$datView["objTM"]->entityID);
			$datView["objLegal"]					= $this->Legal_Model->get_rowByPK($companyID,$datView["objCustumer"]->branchID,$datView["objCustumer"]->entityID);
			$datView["objNatural"]					= $this->Natural_Model->get_rowByPK($companyID,$datView["objCustumer"]->branchID,$datView["objCustumer"]->entityID);
			
			//Si es al credito crear tabla de amortizacion
			$causalIDTypeCredit 	= explode(",", $parameterCausalTypeCredit->value);
			$exisCausalInCredit		= null;
			$exisCausalInCredit		= array_search($datView["objTM"]->transactionCausalID ,$causalIDTypeCredit);
			
			if($exisCausalInCredit || $exisCausalInCredit === 0)
				$datView["objTM_IsCredit"]				= true;				
			else
				$datView["objTM_IsCredit"]				= false;
			
			//Renderizar Resultado
			$this->load->view("app_invoice_billing/print",$datView);	
			
			
		}
		catch(Exception $ex){
			show_error($ex->getLine()." ".$ex->getMessage() ,500 );
		}	
	}
	//facturacion estandar, horizontal tamaña a4
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
			$this->load->model("Transaction_Master_Info_Model");	
			$this->load->model("Transaction_Causal_Model");
			$this->load->model("core/Company_Model"); 
			$this->load->model("core/User_Model");
			$this->load->model("Warehouse_Model"); 
			$this->load->model("core/Branch_Model");
			$this->load->model("Customer_Model");
			$this->load->model("core/Currency_Model"); 
			
			
			//Crear Objetos
			$pdf 	= new EXTCezpdf(PAGE_SIZE,'portrait','none',array());
			$pdf->selectFont('./fonts/Courier.afm');
			$pdf->ezSetCmMargins(TOP_MARGIN,BOTTOM_MARGIN,LEFT_MARGIN,RIGHT_MARGIN);
			$width 	= $pdf->EXTGetWidth();							
			
			//Get Component
			$objComponent	= $this->core_web_tools->getComponentIDBy_ComponentName("tb_company");
			$objParameter	= $this->core_web_parameter->getParameter("CORE_COMPANY_LOGO",$companyID);
			$objCompany 	= $this->Company_Model->get_rowByPK($companyID);			
			$spacing 		= 0.5;
			
			//Get Documento					
			$datView["objTM"]	 					= $this->Transaction_Master_Model->get_rowByPK($companyID,$transactionID,$transactionMasterID);
			$datView["objTMI"]						= $this->Transaction_Master_Info_Model->get_rowByPK($companyID,$transactionID,$transactionMasterID);
			$datView["objTMD"]						= $this->Transaction_Master_Detail_Model->get_rowByTransaction($companyID,$transactionID,$transactionMasterID);
			$datView["objTM"]->transactionOn 		= date_format(date_create($datView["objTM"]->transactionOn),"Y-m-d");
			$datView["objUser"] 					= $this->User_Model->get_rowByPK($datView["objTM"]->companyID,$datView["objTM"]->createdAt,$datView["objTM"]->createdBy);
			$datView["Identifier"]					= $this->core_web_parameter->getParameter("CORE_COMPANY_IDENTIFIER",$companyID);
			$datView["objBranch"]					= $this->Branch_Model->get_rowByPK($datView["objTM"]->companyID,$datView["objTM"]->branchID);
			$datView["objStage"]					= $this->core_web_workflow->getWorkflowStage("tb_transaction_master_billing","statusID",$datView["objTM"]->statusID,$companyID,$branchID,$roleID);
			$datView["objTipo"]						= $this->Transaction_Causal_Model->getByCompanyAndTransactionAndCausal($companyID,$datView["objTM"]->transactionID,$datView["objTM"]->transactionCausalID);
			$datView["objCustumer"]					= $this->Customer_Model->get_rowByEntity($companyID,$datView["objTM"]->entityID);
			$datView["objCurrency"]					= $this->Currency_Model->get_rowByPK($datView["objTM"]->currencyID);
			$prefixCurrency 						= $datView["objCurrency"]->simbol." "; 
			//Set Nombre del Reporte
			$reportName		= "DOC_INVOICE";
			$pdf->addInfo(array('Title'=>$reportName,'Author'=>APP_NAME,'CreationDate'=>date('Y-m-d H:i:s')));			
			//Set Titulo			
			$pdf->EXTCreateHeader("::"./*.$objCompany->name.*/"FACTURA"."::",$objComponent->componentID,$objParameter->value,$dataSession);
			$pdf->ezText("<b>DOCUMENTO NO:".$datView["objTM"]->transactionNumber."</b>",FONT_SIZE,array('justification'=>'center'));
			$pdf->ezText("<b>RUC NO:".$datView["Identifier"]->value."</b>\n\n\n\n",FONT_SIZE,array('justification'=>'center'));
			
			
			$data = array( 
				array('field1'=>'<b>Fecha</b>'			,'field2'=>$datView["objTM"]->transactionOn	 		) ,
				array('field1'=>'<b>Estado</b>'			,'field2'=>$datView["objStage"][0]->display	 		) ,
				array('field1'=>'<b>Cajero</b>'			,'field2'=>$datView["objUser"]->nickname     		) ,
				array('field1'=>'<b>Tienda</b>'			,'field2'=>$datView["objBranch"]->name   	 		) ,
				array('field1'=>'<b>Tipo</b>'			,'field2'=>$datView["objTipo"]->name   	 			) ,
				array('field1'=>'<b>Moneda</b>'			,'field2'=>$prefixCurrency   	 			) ,
				array('field1'=>'<b>Tipo Cambio</b>'	,'field2'=>$datView["objTM"]->exchangeRate 			) ,
				array('field1'=>'<b>Cliente</b>'		,'field2'=>$datView["objCustumer"]->customerNumber	) 
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
					'heigth'		=> 20,
					'fontSize' 		=>FONT_SIZE,	
					'colGap' 		=>0,
					'rowGap' 		=>$spacing,
					'cols'			=>array(
						'field1'=>array('justification'=>'left','width'=>80,'heigth' => 40,'spacing' => $spacing),
						'field2'=>array('justification'=>'left','heigth' => 40,'spacing' => $spacing)						
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
			$subtotal 	= 0;
			$iva 		= 0;
			$total 		= 0;
			$cambio		= 0;
			
			if($datView["objTMD"])
			foreach($datView["objTMD"] as $row){
				$data[] = array(
					'field1'=>substr($row->itemNumber,-5),
					'field2'=>strtolower(substr($row->itemName,0,14)),
					'field3'=>number_format(round($row->quantity,2),2,'.',','),
					'field4'=>number_format(round($row->unitaryPrice,2),2,'.',','),
					'field5'=>number_format(round($row->amount,2),2,'.',',')
				);
				
				$iva		= $iva + ($row->tax1 * $row->quantity);
				$total		= $total + $row->amount;
				$subtotal	= $total - $iva;
			}
			$iva 		= number_format(round($iva,2),2,'.',',');
			$total 		= number_format(round($total,2),2,'.',',');
			$subtotal 	= number_format(round($subtotal,2),2,'.',',');
			$cambio		= ($datView["objTMI"]->receiptAmount - $datView["objTM"]->amount);
			$cambio 	= number_format(round($cambio,2),2,'.',',');
			
			$pdf->ezTable(
				$data,
				array('field1'=>'<b>Codigo</b>','field2'=>'<b>Nombre</b>','field3'=>'<b>Cantidad</b>','field4'=>'<b>Precio</b>','field5'=>'<b>Total</b>'),
				'',				
				array(
					'showHeadings'	=>1,
					'showLines'		=>4,
					'shaded'		=>0,
					'xPos'			=>'left',
					'xOrientation'	=>'right',
					'width'			=>$width,
					'fontSize' 		=>FONT_SIZE,	
					'colGap' 		=>0,
					'cols'			=>array(
						'field1'=>array('justification'=>'left','width'=>60,'heigth' => 50,'spacing' => $spacing),
						'field2'=>array('justification'=>'left','width'=>340,'heigth' => 50,'spacing' => $spacing),
						'field3'=>array('justification'=>'right','width'=>60,'heigth' => 50,'spacing' => $spacing),
						'field4'=>array('justification'=>'right','width'=>60,'heigth' => 50,'spacing' => $spacing),
						'field5'=>array('justification'=>'right','width'=>60,'heigth' => 50,'spacing' => $spacing)
					)
				)
			);
			
			
			$pdf->ezText("\n",FONT_SIZE,array('justification'=>'center','spacing' => $spacing ));
			
			
			//Resumen
			$data = array( 
				array('field1'=>'<b>Sub Total:</b>'	,'field2'=>$subtotal 	) ,
				array('field1'=>'<b>Iva:</b>'		,'field2'=>$iva 		) ,
				array('field1'=>'<b>Total:</b>'		,'field2'=>$total		) ,
				array('field1'=>'<b>Cambio:</b>'	,'field2'=>$cambio		) 
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
					'heigth'		=>20,
					'fontSize' 		=>FONT_SIZE,	
					'colGap' 		=>0,
					'rowGap' 		=>$spacing,
					'cols'			=>array(
						'field1'=>array('justification'=>'right','width'=>530,'heigth' => 50,'spacing' => $spacing),
						'field2'=>array('justification'=>'right','width'=>50,'heigth' => 50,'spacing' => $spacing)						
					) 
				)
			);
			
			
			//Set Firma del Comprobante						
			$pdf->ezText("\n\n\n<b>gracias por preferirnos\n\n***************************</b>\n\n",FONT_SIZE,array('justification'=>'center','spacing' => $spacing ));
			$pdf->ezText("<b>".$objCompany->address."</b>\n\n",FONT_SIZE,array('justification'=>'center','spacing' => $spacing ));
			
			//Set Pie		
			$pdf->EXTCreateFooter();
			//OutPut
			$pdf->ezStream(array('Content-Disposition' => $reportName)); 
			
		}
		catch(Exception $ex){
			show_error($ex->getLine()." ".$ex->getMessage() ,500 );
		}
	}
	//factura en imppresora de ticket de 80mm
	function viewRegisterFormatoPaginaTicket(){
		//Factura en Impresora Termica 
		//O impresora de ticket, con ancho de 3.2 pulgadas
		//O equivalente a 8 centimetro
		//Formato de papel rollo.
		
		
		try{ 
		
			log_message("ERROR","preuba de impresora");
			
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
			$this->load->model("Transaction_Master_Info_Model");	
			$this->load->model("Transaction_Causal_Model");
			$this->load->model("core/Company_Model"); 
			$this->load->model("core/Currency_Model"); 
			$this->load->model("core/User_Model");
			$this->load->model("Warehouse_Model"); 
			$this->load->model("core/Branch_Model");
			$this->load->model("Customer_Model");
			
			
			
			//Crear Objetos
			$pdf 	= new EXTCezpdf(
				PAGE_INVOICE,'portrait','none',array()
			);
			
			//Estilo de letra
			//$pdf->selectFont('./fonts/Courier.afm');
			$pdf->ezSetCmMargins(
				TOP_MARGIN_INVOICE,BOTTOM_MARGIN_INVOICE,LEFT_MARGIN_INVOICE,
				RIGHT_MARGIN_INVOICE
			);
			$width 	= $pdf->EXTGetWidth();
			
			
			log_message("ERROR","preuba de impresora 003");
			
			//Get Component
			$objComponent		= $this->core_web_tools->getComponentIDBy_ComponentName("tb_company");
			$objParameter		= $this->core_web_parameter->getParameter("CORE_COMPANY_LOGO",$companyID);
			$objParameterPhone	= $this->core_web_parameter->getParameter("CORE_PROPIETARY_PHONE",$companyID);
			$objCompany 		= $this->Company_Model->get_rowByPK($companyID);			
			$spacing 			= 0.5;
			
			//Get Documento					
			$datView["objTM"]	 					= $this->Transaction_Master_Model->get_rowByPK($companyID,$transactionID,$transactionMasterID);
			$datView["objTMI"]						= $this->Transaction_Master_Info_Model->get_rowByPK($companyID,$transactionID,$transactionMasterID);
			$datView["objTMD"]						= $this->Transaction_Master_Detail_Model->get_rowByTransaction($companyID,$transactionID,$transactionMasterID);
			$datView["objTM"]->transactionOn 		= date_format(date_create($datView["objTM"]->transactionOn),"Y-m-d");
			$datView["objUser"] 					= $this->User_Model->get_rowByPK($datView["objTM"]->companyID,$datView["objTM"]->createdAt,$datView["objTM"]->createdBy);
			$datView["Identifier"]					= $this->core_web_parameter->getParameter("CORE_COMPANY_IDENTIFIER",$companyID);
			$datView["objBranch"]					= $this->Branch_Model->get_rowByPK($datView["objTM"]->companyID,$datView["objTM"]->branchID);
			$datView["objStage"]					= $this->core_web_workflow->getWorkflowStage("tb_transaction_master_billing","statusID",$datView["objTM"]->statusID,$companyID,$branchID,$roleID);
			$datView["objTipo"]						= $this->Transaction_Causal_Model->getByCompanyAndTransactionAndCausal($companyID,$datView["objTM"]->transactionID,$datView["objTM"]->transactionCausalID);
			$datView["objCustumer"]					= $this->Customer_Model->get_rowByEntity($companyID,$datView["objTM"]->entityID);
			$datView["objCurrency"]					= $this->Currency_Model->get_rowByPK($datView["objTM"]->currencyID);
			$prefixCurrency 						= $datView["objCurrency"]->simbol." ";
			
			log_message("ERROR","preuba de impresora 004");
			
			//Set Nombre del Reporte
			$reportName		= "DOC_INVOICE";
			$pdf->addInfo(array('Title'=>$reportName,'Author'=>APP_NAME,'CreationDate'=>date('Y-m-d H:i:s')));			
			$pdf->EXTCreateHeaderPrinterTicketAndTermica80cm(""./*.$objCompany->name.*/""."",$objComponent->componentID,$objParameter->value,$dataSession);
			
			log_message("ERROR","preuba de impresora 005");
			$pdf->ezText("::".strtoupper($objCompany->name)."::\n",FONT_SIZE_TITLE_INVICE,array('justification'=>'center','spacing' => $spacing ));
			//$pdf->ezText("VARIEDADES"."\n",FONT_SIZE_TITLE_INVICE,array('justification'=>'center','spacing' => $spacing ));
			//$pdf->ezText("VARIEDADES CARLOS LUIS"."\n",FONT_SIZE_TITLE_INVICE,array('justification'=>'center','spacing' => $spacing ));
			$pdf->ezText("#".$datView["objTM"]->transactionNumber."\n",FONT_SIZE_TITLE_INVICE,array('justification'=>'center','spacing' => $spacing ));
			$pdf->ezText("RUC ".$datView["Identifier"]->value."\n\n",FONT_SIZE_TITLE_INVICE,array('justification'=>'center','spacing' => $spacing ));
			log_message("ERROR","preuba de impresora 006");
			
			$cedulaCliente = $datView["objTMI"]->referenceClientIdentifier == "" ? $datView["objCustumer"]->customerNumber :  $datView["objTMI"]->referenceClientIdentifier;
			$nombreCliente = $datView["objTMI"]->referenceClientName  == "" ? $datView["objCustumer"]->firstName : $datView["objTMI"]->referenceClientName ;
			
			$facturaTipo   = $datView["objTipo"]->name;
			$facturaEstado = $datView["objStage"][0]->display;
			$facturaEstado = $facturaTipo == "CREDITO" ? "CREDITO": $facturaEstado;
			
			$data = array( 
				array('field1'=>'Fecha'			,'field2'=>$datView["objTM"]->createdOn	 		) ,
				array('field1'=>'Estado'		,'field2'=>$facturaEstado) ,
				array('field1'=>'Vendedor'		,'field2'=>substr($datView["objUser"]->nickname."",0,15)) ,
				//array('field1'=>'Tienda'		,'field2'=>$datView["objBranch"]->name   	 		) ,
				array('field1'=>'Tipo'			,'field2'=>$facturaTipo) ,
				//array('field1'=>'Tipo Cambio'	,'field2'=>$prefixCurrency.$datView["objTM"]->exchangeRate) ,
				array('field1'=>'Cliente'		,'field2'=>$cedulaCliente   ) ,
				array('field1'=>'Nombre'		,'field2'=>$nombreCliente	) 
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
			
			$pdf->ezText("\n",FONT_SIZE_BODY_INVICE,array('justification'=>'center','spacing' => $spacing ));
			
			//Primer Encabezdo del detalle
			//$data		= array();
			//$pdf->ezTable(
			//	$data,
			//	array('field1'=>'Codigo','field2'=>'Nombre'),
			//	'',				
			//	array(
			//		'showHeadings'	=>1,
			//		'showLines'		=>0,					
			//		'xPos'			=>'left',
			//		'xOrientation'	=>'right',					
			//		'fontSize' 		=>FONT_SIZE_BODY_INVICE,	
			//		'rowGap'		=>0,					
			//		'colGap' 		=>0,
			//		'cols'			=>array(
			//			'field1'=>array('justification'=>'left','width'=>60,'spacing' => $spacing),
			//			'field2'=>array('justification'=>'left','width' => 140,'spacing' => $spacing)			
			//		) 
			//	)
			//);
			
			
			//Segundo Encabezado del detalle			
			$data		= array();
			$pdf->ezTable(
				$data,
				array('field1'=>'Cantidad','field2'=>'Precio','field3'=>'Total'),
				'',				
				array(
					'showHeadings'	=>1,
					'showLines'		=>0,					
					'xPos'			=>'left',
					'xOrientation'	=>'right',					
					'fontSize' 		=>FONT_SIZE_BODY_INVICE,	
					'showBgCol'		=>1,
					'rowGap'		=>0,					
					'colGap' 		=>0,
					'cols'			=>array(
						'field1'=>array('justification'=>'left','width'=>60,'spacing' => $spacing,'bgcolor'=>array(0.5,0.5,0.5) ),
						'field2'=>array('justification'=>'left','width' => 70,'spacing' => $spacing),		
						'field3'=>array('justification'=>'left','width' => 70,'spacing' => $spacing)				
					) 
				)
			);
			
			
			
			$data1		= array();			
			$subtotal 	= 0;
			$iva 		= 0;
			$total 		= 0;
			$cambio		= 0;
			
			if($datView["objTMD"])
			foreach($datView["objTMD"] as $row){								
			
				//Primer linea del detalle
				$data		= array();
				$pdf->ezTable(
					$data,
					array('field1'=>substr($row->itemNumber,4,7),'field2'=>strtolower(substr($row->itemName,0,15))),
					'',				
					array(
						'showHeadings'	=>1,
						'showLines'		=>0,						
						'xPos'			=>'left',
						'xOrientation'	=>'right',						
						'fontSize' 		=>FONT_SIZE_BODY_INVICE,	
						'rowGap'		=>0,
						'colGap' 		=>0,
						'cols'			=>array(
							'field1'=>array('justification'=>'left','width'=>60,'spacing' => $spacing),
							'field2'=>array('justification'=>'left','width' => 140,'spacing' => $spacing)			
						) 
					)
				);
				
				
				//Segundo linea del detalle			
				$data		= array();
				$pdf->ezTable(
					$data,
					array(
						'field1'=>number_format(round($row->quantity,2),2,'.',','),
						'field2'=>$prefixCurrency.number_format(round($row->unitaryPrice,2),2,'.',','),
						'field3'=>$prefixCurrency.number_format(round($row->amount,2),2,'.',',')
					),
					'',				
					array(
						'showHeadings'	=>1,
						'showLines'		=>0,						
						'xPos'			=>'left',
						'xOrientation'	=>'right',						
						'fontSize' 		=>FONT_SIZE_BODY_INVICE,	
						'rowGap'		=>0,
						'colGap' 		=>0,
						'cols'			=>array(
							'field1'=>array('justification'=>'left','width'=>60,'spacing' => $spacing),
							'field2'=>array('justification'=>'right','width' => 70,'spacing' => $spacing),		
							'field3'=>array('justification'=>'right','width' => 70,'spacing' => $spacing)				
						) 
					)
				);
				
			
				////////////////////////////////////
				$iva		= $iva + ($row->tax1 * $row->quantity);
				$total		= $total + $row->amount;
				$subtotal	= $total - $iva;
			}
			
			
			$iva 		= number_format(round($iva,2),2,'.',',');
			$total 		= number_format(round($total,2),2,'.',',');
			$subtotal 	= number_format(round($subtotal,2),2,'.',',');
			$cambio		= ($datView["objTMI"]->receiptAmount - $datView["objTM"]->amount);
			$cambio 	= number_format(round($cambio,2),2,'.',',');
			
			$pdf->ezText("\n",FONT_SIZE_BODY_INVICE,array('justification'=>'center','spacing' => $spacing ));
			log_message("ERROR","preuba de impresora 010");
			
			//Resumen
			$data = array( 
				//array('field1'=>'Sub Total'	,'field2'=>$prefixCurrency.$subtotal 	) ,
				//array('field1'=>'Iva'		,'field2'=>$prefixCurrency.$iva 			) ,
				array('field1'=>'Total'		,'field2'=>$prefixCurrency.$total		) ,
				array('field1'=>'Cambio'	,'field2'=>$prefixCurrency.$cambio		) 
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
						'field1'=>array('justification'=>'right','width'=>120,'spacing' => $spacing),
						'field2'=>array('justification'=>'right','heigth' => 80,'spacing' => $spacing)						
					) 
				)
			);
			
			
			//Set Firma del Comprobante						
			$pdf->ezText("\n\n\ngracias por su compra",						
			FONT_SIZE_BODY_INVICE,array('justification'=>'center','spacing' => $spacing ));
			
			//$pdf->ezText("\ncontamos con servicios a ",									
			//FONT_SIZE_BODY_INVICE,array('justification'=>'center','spacing' => $spacing ));
			
			//$pdf->ezText("\ndomicilio en el municipio de malpaisillo.",									
			//FONT_SIZE_BODY_INVICE,array('justification'=>'center','spacing' => $spacing ));
			
			$pdf->ezText("\n***************************",						
			FONT_SIZE_BODY_INVICE,array('justification'=>'center','spacing' => $spacing ));
			
			$pdf->ezText("\nTelefono de tienda:",						
			FONT_SIZE_BODY_INVICE,array('justification'=>'center','spacing' => $spacing ));
			
			$pdf->ezText("\n".$objParameterPhone->value,						
			FONT_SIZE_BODY_INVICE,array('justification'=>'center','spacing' => $spacing ));
			
			$pdf->ezText("\n".$objCompany->address,
			FONT_SIZE_BODY_INVICE,array('justification'=>'center','spacing' => $spacing ));
			
			$pdf->ezText("\nsistema:+(505) 8712-5827",						
			FONT_SIZE_BODY_INVICE,array('justification'=>'center','spacing' => $spacing ));
			
			//Set Pie		
			$pdf->EXTCreateFooter();
			//OutPut
			$pdf->ezStream(array('Content-Disposition' => $reportName)); 
			
			log_message("ERROR","preuba de impresora 015");
			
			
		}
		catch(Exception $ex){
			show_error($ex->getLine()." ".$ex->getMessage() ,500 );
		}
	}
	//facturacion de fid local
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
			$this->load->model("Transaction_Master_Info_Model");	
			$this->load->model("Transaction_Causal_Model");
			$this->load->model("core/Company_Model"); 
			$this->load->model("core/User_Model");
			$this->load->model("Warehouse_Model"); 
			$this->load->model("core/Branch_Model");
			$this->load->model("Customer_Model");
			$this->load->model("core/Currency_Model"); 
			
			
			//Crear Objetos
			$pdf 	= new EXTCezpdf(PAGE_SIZE,'portrait','none',array());
			$pdf->selectFont('./fonts/Courier.afm');
			$pdf->ezSetCmMargins(TOP_MARGIN,BOTTOM_MARGIN,LEFT_MARGIN,RIGHT_MARGIN);
			$width 	= $pdf->EXTGetWidth();							
			
			//Get Component
			$objComponent	= $this->core_web_tools->getComponentIDBy_ComponentName("tb_company");
			$objParameter	= $this->core_web_parameter->getParameter("CORE_COMPANY_LOGO",$companyID);
			$objCompany 	= $this->Company_Model->get_rowByPK($companyID);			
			$spacing 		= 0.5;
			
			//Get Documento					
			$datView["objTM"]	 					= $this->Transaction_Master_Model->get_rowByPK($companyID,$transactionID,$transactionMasterID);
			$datView["objTMI"]						= $this->Transaction_Master_Info_Model->get_rowByPK($companyID,$transactionID,$transactionMasterID);
			$datView["objTMD"]						= $this->Transaction_Master_Detail_Model->get_rowByTransaction($companyID,$transactionID,$transactionMasterID);
			$datView["objTM"]->transactionOn 		= date_format(date_create($datView["objTM"]->transactionOn),"Y-m-d");
			$datView["objUser"] 					= $this->User_Model->get_rowByPK($datView["objTM"]->companyID,$datView["objTM"]->createdAt,$datView["objTM"]->createdBy);
			$datView["Identifier"]					= $this->core_web_parameter->getParameter("CORE_COMPANY_IDENTIFIER",$companyID);
			$datView["objBranch"]					= $this->Branch_Model->get_rowByPK($datView["objTM"]->companyID,$datView["objTM"]->branchID);
			$datView["objStage"]					= $this->core_web_workflow->getWorkflowStage("tb_transaction_master_billing","statusID",$datView["objTM"]->statusID,$companyID,$branchID,$roleID);
			$datView["objTipo"]						= $this->Transaction_Causal_Model->getByCompanyAndTransactionAndCausal($companyID,$datView["objTM"]->transactionID,$datView["objTM"]->transactionCausalID);
			$datView["objCustumer"]					= $this->Customer_Model->get_rowByEntity($companyID,$datView["objTM"]->entityID);
			$datView["objCurrency"]					= $this->Currency_Model->get_rowByPK($datView["objTM"]->currencyID);
			$prefixCurrency 						= $datView["objCurrency"]->simbol." "; 
			//Set Nombre del Reporte
			$reportName		= "DOC_INVOICE";
			$pdf->addInfo(array('Title'=>$reportName,'Author'=>APP_NAME,'CreationDate'=>date('Y-m-d H:i:s')));			
			//Set Titulo			
			$pdf->EXTCreateHeader("::"./*.$objCompany->name.*/"FACTURA"."::",$objComponent->componentID,$objParameter->value,$dataSession);
			$pdf->ezText("<b>DOCUMENTO NO:".$datView["objTM"]->transactionNumber."</b>",FONT_SIZE,array('justification'=>'center'));
			$pdf->ezText("<b>RUC NO:".$datView["Identifier"]->value."</b>\n\n\n\n",FONT_SIZE,array('justification'=>'center'));
			
			
			$data = array( 
				array('field1'=>'<b>Fecha</b>'			,'field2'=>$datView["objTM"]->transactionOn	 		) ,
				array('field1'=>'<b>Estado</b>'			,'field2'=>$datView["objStage"][0]->display	 		) ,
				array('field1'=>'<b>Cajero</b>'			,'field2'=>$datView["objUser"]->nickname     		) ,
				array('field1'=>'<b>Tienda</b>'			,'field2'=>$datView["objBranch"]->name   	 		) ,
				array('field1'=>'<b>Tipo</b>'			,'field2'=>$datView["objTipo"]->name   	 			) ,
				array('field1'=>'<b>Moneda</b>'			,'field2'=>$prefixCurrency   	 			) ,
				array('field1'=>'<b>Tipo Cambio</b>'	,'field2'=>$datView["objTM"]->exchangeRate 			) ,
				array('field1'=>'<b>Cliente</b>'		,'field2'=>$datView["objCustumer"]->customerNumber	) 
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
					'heigth'		=> 20,
					'fontSize' 		=>FONT_SIZE,	
					'colGap' 		=>0,
					'rowGap' 		=>$spacing,
					'cols'			=>array(
						'field1'=>array('justification'=>'left','width'=>80,'heigth' => 40,'spacing' => $spacing),
						'field2'=>array('justification'=>'left','heigth' => 40,'spacing' => $spacing)						
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
			$subtotal 	= 0;
			$iva 		= 0;
			$total 		= 0;
			$cambio		= 0;
			
			if($datView["objTMD"])
			foreach($datView["objTMD"] as $row){
				$data[] = array(
					'field1'=>substr($row->itemNumber,-5),
					'field2'=>strtolower(substr($row->itemName,0,14)),
					'field3'=>number_format(round($row->quantity,2),2,'.',','),
					'field4'=>number_format(round($row->unitaryPrice,2),2,'.',','),
					'field5'=>number_format(round($row->amount,2),2,'.',',')
				);
				
				$iva		= $iva + ($row->tax1 * $row->quantity);
				$total		= $total + $row->amount;
				$subtotal	= $total - $iva;
			}
			$iva 		= number_format(round($iva,2),2,'.',',');
			$total 		= number_format(round($total,2),2,'.',',');
			$subtotal 	= number_format(round($subtotal,2),2,'.',',');
			$cambio		= ($datView["objTMI"]->receiptAmount - $datView["objTM"]->amount);
			$cambio 	= number_format(round($cambio,2),2,'.',',');
			
			$pdf->ezTable(
				$data,
				array('field1'=>'<b>Codigo</b>','field2'=>'<b>Nombre</b>','field3'=>'<b>Cantidad</b>','field4'=>'<b>Precio</b>','field5'=>'<b>Total</b>'),
				'',				
				array(
					'showHeadings'	=>1,
					'showLines'		=>4,
					'shaded'		=>0,
					'xPos'			=>'left',
					'xOrientation'	=>'right',
					'width'			=>$width,
					'fontSize' 		=>FONT_SIZE,	
					'colGap' 		=>0,
					'cols'			=>array(
						'field1'=>array('justification'=>'left','width'=>60,'heigth' => 50,'spacing' => $spacing),
						'field2'=>array('justification'=>'left','width'=>340,'heigth' => 50,'spacing' => $spacing),
						'field3'=>array('justification'=>'right','width'=>60,'heigth' => 50,'spacing' => $spacing),
						'field4'=>array('justification'=>'right','width'=>60,'heigth' => 50,'spacing' => $spacing),
						'field5'=>array('justification'=>'right','width'=>60,'heigth' => 50,'spacing' => $spacing)
					)
				)
			);
			
			
			$pdf->ezText("\n",FONT_SIZE,array('justification'=>'center','spacing' => $spacing ));
			
			
			//Resumen
			$data = array( 
				array('field1'=>'<b>Sub Total:</b>'	,'field2'=>$subtotal 	) ,
				array('field1'=>'<b>Iva:</b>'		,'field2'=>$iva 		) ,
				array('field1'=>'<b>Total:</b>'		,'field2'=>$total		) ,
				array('field1'=>'<b>Cambio:</b>'	,'field2'=>$cambio		) 
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
					'heigth'		=>20,
					'fontSize' 		=>FONT_SIZE,	
					'colGap' 		=>0,
					'rowGap' 		=>$spacing,
					'cols'			=>array(
						'field1'=>array('justification'=>'right','width'=>530,'heigth' => 50,'spacing' => $spacing),
						'field2'=>array('justification'=>'right','width'=>50,'heigth' => 50,'spacing' => $spacing)						
					) 
				)
			);
			
			
			//Set Firma del Comprobante						
			$pdf->ezText("\n\n\n<b>gracias por preferirnos\n\n***************************</b>\n\n",FONT_SIZE,array('justification'=>'center','spacing' => $spacing ));
			$pdf->ezText("<b>".$objCompany->address."</b>\n\n",FONT_SIZE,array('justification'=>'center','spacing' => $spacing ));
			
			//Set Pie		
			$pdf->EXTCreateFooter();
			//OutPut
			$pdf->ezStream(array('Content-Disposition' => $reportName)); 
			
		}
		catch(Exception $ex){
			show_error($ex->getLine()." ".$ex->getMessage() ,500 );
		}
	}
	//factura general de carlos luis y demas comercios
	function viewRegisterVariedadesCarlosLuis(){
		//Factura en Impresora Termica 
		//O impresora de ticket, con ancho de 3.2 pulgadas
		//O equivalente a 8 centimetro
		//Formato de papel rollo.
		
		
		try{ 
		
			log_message("ERROR","preuba de impresora");
			
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
			$this->load->model("Transaction_Master_Info_Model");	
			$this->load->model("Transaction_Causal_Model");
			$this->load->model("core/Company_Model"); 
			$this->load->model("core/Currency_Model"); 
			$this->load->model("core/User_Model");
			$this->load->model("Warehouse_Model"); 
			$this->load->model("core/Branch_Model");
			$this->load->model("Customer_Model");
			
			
			
			//Crear Objetos
			$pdf 	= new EXTCezpdf(
				PAGE_INVOICE,'portrait','none',array()
			);
			
			//Estilo de letra
			//$pdf->selectFont('./fonts/Courier.afm');
			$pdf->ezSetCmMargins(
				TOP_MARGIN_INVOICE,BOTTOM_MARGIN_INVOICE,LEFT_MARGIN_INVOICE,
				RIGHT_MARGIN_INVOICE
			);
			$width 	= $pdf->EXTGetWidth();
			
			
			log_message("ERROR","preuba de impresora 003");
			
			//Get Component
			$objComponent		= $this->core_web_tools->getComponentIDBy_ComponentName("tb_company");
			$objParameter		= $this->core_web_parameter->getParameter("CORE_COMPANY_LOGO",$companyID);
			$objParameterPhone	= $this->core_web_parameter->getParameter("CORE_PROPIETARY_PHONE",$companyID);
			$objCompany 		= $this->Company_Model->get_rowByPK($companyID);			
			$spacing 			= 0.5;
			
			//Get Documento					
			$datView["objTM"]	 					= $this->Transaction_Master_Model->get_rowByPK($companyID,$transactionID,$transactionMasterID);
			$datView["objTMI"]						= $this->Transaction_Master_Info_Model->get_rowByPK($companyID,$transactionID,$transactionMasterID);
			$datView["objTMD"]						= $this->Transaction_Master_Detail_Model->get_rowByTransaction($companyID,$transactionID,$transactionMasterID);
			$datView["objTM"]->transactionOn 		= date_format(date_create($datView["objTM"]->transactionOn),"Y-m-d");
			$datView["objUser"] 					= $this->User_Model->get_rowByPK($datView["objTM"]->companyID,$datView["objTM"]->createdAt,$datView["objTM"]->createdBy);
			$datView["Identifier"]					= $this->core_web_parameter->getParameter("CORE_COMPANY_IDENTIFIER",$companyID);
			$datView["objBranch"]					= $this->Branch_Model->get_rowByPK($datView["objTM"]->companyID,$datView["objTM"]->branchID);
			$datView["objStage"]					= $this->core_web_workflow->getWorkflowStage("tb_transaction_master_billing","statusID",$datView["objTM"]->statusID,$companyID,$branchID,$roleID);
			$datView["objTipo"]						= $this->Transaction_Causal_Model->getByCompanyAndTransactionAndCausal($companyID,$datView["objTM"]->transactionID,$datView["objTM"]->transactionCausalID);
			$datView["objCustumer"]					= $this->Customer_Model->get_rowByEntity($companyID,$datView["objTM"]->entityID);
			$datView["objCurrency"]					= $this->Currency_Model->get_rowByPK($datView["objTM"]->currencyID);
			$prefixCurrency 						= $datView["objCurrency"]->simbol." ";
			
			log_message("ERROR","preuba de impresora 004");
			
			//Set Nombre del Reporte
			$reportName		= "DOC_INVOICE";
			$pdf->addInfo(array('Title'=>$reportName,'Author'=>APP_NAME,'CreationDate'=>date('Y-m-d H:i:s')));			
			$pdf->EXTCreateHeaderPrinterTicketAndTermica80cm(""./*.$objCompany->name.*/""."",$objComponent->componentID,$objParameter->value,$dataSession);
			
			log_message("ERROR","preuba de impresora 005");
			$pdf->ezText("::".strtoupper($objCompany->name)."::\n",FONT_SIZE_TITLE_INVICE,array('justification'=>'center','spacing' => $spacing ));
			//$pdf->ezText("VARIEDADES"."\n",FONT_SIZE_TITLE_INVICE,array('justification'=>'center','spacing' => $spacing ));
			//$pdf->ezText("VARIEDADES CARLOS LUIS"."\n",FONT_SIZE_TITLE_INVICE,array('justification'=>'center','spacing' => $spacing ));
			$pdf->ezText("#".$datView["objTM"]->transactionNumber."\n",FONT_SIZE_TITLE_INVICE,array('justification'=>'center','spacing' => $spacing ));
			$pdf->ezText("RUC ".$datView["Identifier"]->value."\n\n",FONT_SIZE_TITLE_INVICE,array('justification'=>'center','spacing' => $spacing ));
			log_message("ERROR","preuba de impresora 006");
			
			$cedulaCliente = $datView["objTMI"]->referenceClientIdentifier == "" ? $datView["objCustumer"]->customerNumber :  $datView["objTMI"]->referenceClientIdentifier;
			$nombreCliente = $datView["objTMI"]->referenceClientName  == "" ? $datView["objCustumer"]->firstName : $datView["objTMI"]->referenceClientName ;
			
			$data = array( 
				array('field1'=>'Fecha'			,'field2'=>$datView["objTM"]->createdOn	 		) ,
				array('field1'=>'Estado'		,'field2'=>$datView["objStage"][0]->display	 		) ,
				array('field1'=>'Vendedor'		,'field2'=>$datView["objUser"]->nickname     		) ,
				//array('field1'=>'Tienda'		,'field2'=>$datView["objBranch"]->name   	 		) ,
				array('field1'=>'Tipo'			,'field2'=>$datView["objTipo"]->name   	 			) ,
				//array('field1'=>'Tipo Cambio'	,'field2'=>$prefixCurrency.$datView["objTM"]->exchangeRate) ,
				array('field1'=>'Cliente'		,'field2'=>$cedulaCliente   ) ,
				array('field1'=>'Nombre'		,'field2'=>$nombreCliente	) 
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
			
			$pdf->ezText("\n",FONT_SIZE_BODY_INVICE,array('justification'=>'center','spacing' => $spacing ));
			
			//Primer Encabezdo del detalle
			//$data		= array();
			//$pdf->ezTable(
			//	$data,
			//	array('field1'=>'Codigo','field2'=>'Nombre'),
			//	'',				
			//	array(
			//		'showHeadings'	=>1,
			//		'showLines'		=>0,					
			//		'xPos'			=>'left',
			//		'xOrientation'	=>'right',					
			//		'fontSize' 		=>FONT_SIZE_BODY_INVICE,	
			//		'rowGap'		=>0,					
			//		'colGap' 		=>0,
			//		'cols'			=>array(
			//			'field1'=>array('justification'=>'left','width'=>60,'spacing' => $spacing),
			//			'field2'=>array('justification'=>'left','width' => 140,'spacing' => $spacing)			
			//		) 
			//	)
			//);
			
			
			//Segundo Encabezado del detalle			
			$data		= array();
			$pdf->ezTable(
				$data,
				array('field1'=>'Cantidad','field2'=>'Precio','field3'=>'Total'),
				'',				
				array(
					'showHeadings'	=>1,
					'showLines'		=>0,					
					'xPos'			=>'left',
					'xOrientation'	=>'right',					
					'fontSize' 		=>FONT_SIZE_BODY_INVICE,	
					'showBgCol'		=>1,
					'rowGap'		=>0,					
					'colGap' 		=>0,
					'cols'			=>array(
						'field1'=>array('justification'=>'left','width'=>60,'spacing' => $spacing,'bgcolor'=>array(0.5,0.5,0.5) ),
						'field2'=>array('justification'=>'left','width' => 70,'spacing' => $spacing),		
						'field3'=>array('justification'=>'left','width' => 70,'spacing' => $spacing)				
					) 
				)
			);
			
			
			
			$data1		= array();			
			$subtotal 	= 0;
			$iva 		= 0;
			$total 		= 0;
			$cambio		= 0;
			
			if($datView["objTMD"])
			foreach($datView["objTMD"] as $row){								
			
				//Primer linea del detalle
				$data		= array();
				$pdf->ezTable(
					$data,
					array('field1'=>substr($row->itemNumber,4,7),'field2'=>strtolower(substr($row->itemName,0,15))),
					'',				
					array(
						'showHeadings'	=>1,
						'showLines'		=>0,						
						'xPos'			=>'left',
						'xOrientation'	=>'right',						
						'fontSize' 		=>FONT_SIZE_BODY_INVICE,	
						'rowGap'		=>0,
						'colGap' 		=>0,
						'cols'			=>array(
							'field1'=>array('justification'=>'left','width'=>60,'spacing' => $spacing),
							'field2'=>array('justification'=>'left','width' => 140,'spacing' => $spacing)			
						) 
					)
				);
				
				
				//Segundo linea del detalle			
				$data		= array();
				$pdf->ezTable(
					$data,
					array(
						'field1'=>number_format(round($row->quantity,2),2,'.',','),
						'field2'=>$prefixCurrency.number_format(round($row->unitaryPrice,2),2,'.',','),
						'field3'=>$prefixCurrency.number_format(round($row->amount,2),2,'.',',')
					),
					'',				
					array(
						'showHeadings'	=>1,
						'showLines'		=>0,						
						'xPos'			=>'left',
						'xOrientation'	=>'right',						
						'fontSize' 		=>FONT_SIZE_BODY_INVICE,	
						'rowGap'		=>0,
						'colGap' 		=>0,
						'cols'			=>array(
							'field1'=>array('justification'=>'left','width'=>60,'spacing' => $spacing),
							'field2'=>array('justification'=>'right','width' => 70,'spacing' => $spacing),		
							'field3'=>array('justification'=>'right','width' => 70,'spacing' => $spacing)				
						) 
					)
				);
				
			
				////////////////////////////////////
				$iva		= $iva + ($row->tax1 * $row->quantity);
				$total		= $total + $row->amount;
				$subtotal	= $total - $iva;
			}
			
			
			$iva 		= number_format(round($iva,2),2,'.',',');
			$total 		= number_format(round($total,2),2,'.',',');
			$subtotal 	= number_format(round($subtotal,2),2,'.',',');
			$cambio		= ($datView["objTMI"]->receiptAmount - $datView["objTM"]->amount);
			$cambio 	= number_format(round($cambio,2),2,'.',',');
			
			$pdf->ezText("\n",FONT_SIZE_BODY_INVICE,array('justification'=>'center','spacing' => $spacing ));
			log_message("ERROR","preuba de impresora 010");
			
			//Resumen
			$data = array( 
				//array('field1'=>'Sub Total'	,'field2'=>$prefixCurrency.$subtotal 	) ,
				//array('field1'=>'Iva'		,'field2'=>$prefixCurrency.$iva 			) ,
				array('field1'=>'Total'		,'field2'=>$prefixCurrency.$total		) ,
				array('field1'=>'Cambio'	,'field2'=>$prefixCurrency.$cambio		) 
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
						'field1'=>array('justification'=>'right','width'=>120,'spacing' => $spacing),
						'field2'=>array('justification'=>'right','heigth' => 80,'spacing' => $spacing)						
					) 
				)
			);
			
			
			//Set Firma del Comprobante						
			$pdf->ezText("\n\n\ngracias por su compra",						
			FONT_SIZE_BODY_INVICE,array('justification'=>'center','spacing' => $spacing ));
			
			$pdf->ezText("\ncontamos con servicios a ",									
			FONT_SIZE_BODY_INVICE,array('justification'=>'center','spacing' => $spacing ));
			
			$pdf->ezText("\ndomicilio en el municipio de malpaisillo.",									
			FONT_SIZE_BODY_INVICE,array('justification'=>'center','spacing' => $spacing ));
			
			$pdf->ezText("\n***************************",						
			FONT_SIZE_BODY_INVICE,array('justification'=>'center','spacing' => $spacing ));
			
			$pdf->ezText("\nTelefono de tienda:",						
			FONT_SIZE_BODY_INVICE,array('justification'=>'center','spacing' => $spacing ));
			
			$pdf->ezText("\n".$objParameterPhone->value,						
			FONT_SIZE_BODY_INVICE,array('justification'=>'center','spacing' => $spacing ));
			
			$pdf->ezText("\n".$objCompany->address,
			FONT_SIZE_BODY_INVICE,array('justification'=>'center','spacing' => $spacing ));
			
			$pdf->ezText("\nsistema:+(505) 8712-5827",						
			FONT_SIZE_BODY_INVICE,array('justification'=>'center','spacing' => $spacing ));
			
			//Set Pie		
			$pdf->EXTCreateFooter();
			//OutPut
			$pdf->ezStream(array('Content-Disposition' => $reportName)); 
			
			log_message("ERROR","preuba de impresora 015");
			
			
		}
		catch(Exception $ex){
			show_error($ex->getLine()." ".$ex->getMessage() ,500 );
		}
	}
	function viewRegisterInstitutoLaVid(){
		//Factura en Impresora Termica 
		//O impresora de ticket, con ancho de 3.2 pulgadas
		//O equivalente a 8 centimetro
		//Formato de papel rollo.
		
		
		try{ 
		
			log_message("ERROR","preuba de impresora");
			
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
			$this->load->model("Transaction_Master_Info_Model");	
			$this->load->model("Transaction_Causal_Model");
			$this->load->model("core/Company_Model"); 
			$this->load->model("core/Currency_Model"); 
			$this->load->model("core/User_Model");
			$this->load->model("Warehouse_Model"); 
			$this->load->model("core/Branch_Model");
			$this->load->model("Customer_Model");
			
			
			
			//Crear Objetos
			$pdf 	= new EXTCezpdf(
				PAGE_INVOICE,'portrait','none',array()
			);
			
			//Estilo de letra
			//$pdf->selectFont('./fonts/Courier.afm');
			$pdf->ezSetCmMargins(
				TOP_MARGIN_INVOICE,BOTTOM_MARGIN_INVOICE,LEFT_MARGIN_INVOICE,
				RIGHT_MARGIN_INVOICE
			);
			$width 	= $pdf->EXTGetWidth();
			
			
			log_message("ERROR","preuba de impresora 003");
			
			//Get Component
			$objComponent		= $this->core_web_tools->getComponentIDBy_ComponentName("tb_company");
			$objParameter		= $this->core_web_parameter->getParameter("CORE_COMPANY_LOGO",$companyID);
			$objParameterPhone	= $this->core_web_parameter->getParameter("CORE_PROPIETARY_PHONE",$companyID);
			$objCompany 		= $this->Company_Model->get_rowByPK($companyID);			
			$spacing 			= 0.5;
			
			//Get Documento					
			$datView["objTM"]	 					= $this->Transaction_Master_Model->get_rowByPK($companyID,$transactionID,$transactionMasterID);
			$datView["objTMI"]						= $this->Transaction_Master_Info_Model->get_rowByPK($companyID,$transactionID,$transactionMasterID);
			$datView["objTMD"]						= $this->Transaction_Master_Detail_Model->get_rowByTransaction($companyID,$transactionID,$transactionMasterID);
			$datView["objTM"]->transactionOn 		= date_format(date_create($datView["objTM"]->transactionOn),"Y-m-d");
			$datView["objUser"] 					= $this->User_Model->get_rowByPK($datView["objTM"]->companyID,$datView["objTM"]->createdAt,$datView["objTM"]->createdBy);
			$datView["Identifier"]					= $this->core_web_parameter->getParameter("CORE_COMPANY_IDENTIFIER",$companyID);
			$datView["objBranch"]					= $this->Branch_Model->get_rowByPK($datView["objTM"]->companyID,$datView["objTM"]->branchID);
			$datView["objStage"]					= $this->core_web_workflow->getWorkflowStage("tb_transaction_master_billing","statusID",$datView["objTM"]->statusID,$companyID,$branchID,$roleID);
			$datView["objTipo"]						= $this->Transaction_Causal_Model->getByCompanyAndTransactionAndCausal($companyID,$datView["objTM"]->transactionID,$datView["objTM"]->transactionCausalID);
			$datView["objCustumer"]					= $this->Customer_Model->get_rowByEntity($companyID,$datView["objTM"]->entityID);
			$datView["objCurrency"]					= $this->Currency_Model->get_rowByPK($datView["objTM"]->currencyID);
			$prefixCurrency 						= $datView["objCurrency"]->simbol." ";
			
			log_message("ERROR","preuba de impresora 004");
			
			//Set Nombre del Reporte
			$reportName		= "DOC_INVOICE";
			$pdf->addInfo(array('Title'=>$reportName,'Author'=>APP_NAME,'CreationDate'=>date('Y-m-d H:i:s')));			
			$pdf->EXTCreateHeaderPrinterTicketAndTermica80cmCuadrado(""./*.$objCompany->name.*/""."",$objComponent->componentID,$objParameter->value,$dataSession);
			
			log_message("ERROR","preuba de impresora 005");
			$pdf->ezText("\n\n\n::".strtoupper($objCompany->name)."::\n",FONT_SIZE_TITLE_INVICE,array('justification'=>'center','spacing' => $spacing ));
			//$pdf->ezText("VARIEDADES"."\n",FONT_SIZE_TITLE_INVICE,array('justification'=>'center','spacing' => $spacing ));
			//$pdf->ezText("VARIEDADES CARLOS LUIS"."\n",FONT_SIZE_TITLE_INVICE,array('justification'=>'center','spacing' => $spacing ));
			$pdf->ezText("#".$datView["objTM"]->transactionNumber."\n",FONT_SIZE_TITLE_INVICE,array('justification'=>'center','spacing' => $spacing ));
			$pdf->ezText("RUC ".$datView["Identifier"]->value."\n\n",FONT_SIZE_TITLE_INVICE,array('justification'=>'center','spacing' => $spacing ));
			log_message("ERROR","preuba de impresora 006");
			
			$cedulaCliente = $datView["objTMI"]->referenceClientIdentifier == "" ? $datView["objCustumer"]->customerNumber :  $datView["objTMI"]->referenceClientIdentifier;
			$nombreCliente = $datView["objTMI"]->referenceClientName  == "" ? $datView["objCustumer"]->firstName : $datView["objTMI"]->referenceClientName ;
			
			$data = array( 
				array('field1'=>'Fecha'			,'field2'=>$datView["objTM"]->createdOn	 		) ,
				array('field1'=>'Estado'		,'field2'=>$datView["objStage"][0]->display	 		) ,
				array('field1'=>'Vendedor'		,'field2'=>$datView["objUser"]->nickname     		) ,
				//array('field1'=>'Tienda'		,'field2'=>$datView["objBranch"]->name   	 		) ,
				array('field1'=>'Tipo'			,'field2'=>$datView["objTipo"]->name   	 			) ,
				//array('field1'=>'Tipo Cambio'	,'field2'=>$prefixCurrency.$datView["objTM"]->exchangeRate) ,
				array('field1'=>'Cliente'		,'field2'=>$cedulaCliente   ) ,
				array('field1'=>'Nombre'		,'field2'=>$nombreCliente	) 
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
			
			$pdf->ezText("\n",FONT_SIZE_BODY_INVICE,array('justification'=>'center','spacing' => $spacing ));
			
			//Primer Encabezdo del detalle
			//$data		= array();
			//$pdf->ezTable(
			//	$data,
			//	array('field1'=>'Codigo','field2'=>'Nombre'),
			//	'',				
			//	array(
			//		'showHeadings'	=>1,
			//		'showLines'		=>0,					
			//		'xPos'			=>'left',
			//		'xOrientation'	=>'right',					
			//		'fontSize' 		=>FONT_SIZE_BODY_INVICE,	
			//		'rowGap'		=>0,					
			//		'colGap' 		=>0,
			//		'cols'			=>array(
			//			'field1'=>array('justification'=>'left','width'=>60,'spacing' => $spacing),
			//			'field2'=>array('justification'=>'left','width' => 140,'spacing' => $spacing)			
			//		) 
			//	)
			//);
			
			
			//Segundo Encabezado del detalle			
			$data		= array();
			$pdf->ezTable(
				$data,
				array('field1'=>'Cantidad','field2'=>'Precio','field3'=>'Total'),
				'',				
				array(
					'showHeadings'	=>1,
					'showLines'		=>0,					
					'xPos'			=>'left',
					'xOrientation'	=>'right',					
					'fontSize' 		=>FONT_SIZE_BODY_INVICE,	
					'showBgCol'		=>1,
					'rowGap'		=>0,					
					'colGap' 		=>0,
					'cols'			=>array(
						'field1'=>array('justification'=>'left','width'=>60,'spacing' => $spacing,'bgcolor'=>array(0.5,0.5,0.5) ),
						'field2'=>array('justification'=>'left','width' => 70,'spacing' => $spacing),		
						'field3'=>array('justification'=>'left','width' => 70,'spacing' => $spacing)				
					) 
				)
			);
			
			
			
			$data1		= array();			
			$subtotal 	= 0;
			$iva 		= 0;
			$total 		= 0;
			$cambio		= 0;
			
			if($datView["objTMD"])
			foreach($datView["objTMD"] as $row){								
			
				//Primer linea del detalle
				$data		= array();
				$pdf->ezTable(
					$data,
					array('field1'=>substr($row->itemNumber,4,7),'field2'=>strtolower(substr($row->itemName,0,15))),
					'',				
					array(
						'showHeadings'	=>1,
						'showLines'		=>0,						
						'xPos'			=>'left',
						'xOrientation'	=>'right',						
						'fontSize' 		=>FONT_SIZE_BODY_INVICE,	
						'rowGap'		=>0,
						'colGap' 		=>0,
						'cols'			=>array(
							'field1'=>array('justification'=>'left','width'=>60,'spacing' => $spacing),
							'field2'=>array('justification'=>'left','width' => 140,'spacing' => $spacing)			
						) 
					)
				);
				
				
				//Segundo linea del detalle			
				$data		= array();
				$pdf->ezTable(
					$data,
					array(
						'field1'=>number_format(round($row->quantity,2),2,'.',','),
						'field2'=>$prefixCurrency.number_format(round($row->unitaryPrice,2),2,'.',','),
						'field3'=>$prefixCurrency.number_format(round($row->amount,2),2,'.',',')
					),
					'',				
					array(
						'showHeadings'	=>1,
						'showLines'		=>0,						
						'xPos'			=>'left',
						'xOrientation'	=>'right',						
						'fontSize' 		=>FONT_SIZE_BODY_INVICE,	
						'rowGap'		=>0,
						'colGap' 		=>0,
						'cols'			=>array(
							'field1'=>array('justification'=>'left','width'=>60,'spacing' => $spacing),
							'field2'=>array('justification'=>'right','width' => 70,'spacing' => $spacing),		
							'field3'=>array('justification'=>'right','width' => 70,'spacing' => $spacing)				
						) 
					)
				);
				
			
				////////////////////////////////////
				$iva		= $iva + ($row->tax1 * $row->quantity);
				$total		= $total + $row->amount;
				$subtotal	= $total - $iva;
			}
			
			
			$iva 		= number_format(round($iva,2),2,'.',',');
			$total 		= number_format(round($total,2),2,'.',',');
			$subtotal 	= number_format(round($subtotal,2),2,'.',',');
			$cambio		= ($datView["objTMI"]->receiptAmount - $datView["objTM"]->amount);
			$cambio 	= number_format(round($cambio,2),2,'.',',');
			
			$pdf->ezText("\n",FONT_SIZE_BODY_INVICE,array('justification'=>'center','spacing' => $spacing ));
			log_message("ERROR","preuba de impresora 010");
			
			//Resumen
			$data = array( 
				//array('field1'=>'Sub Total'	,'field2'=>$prefixCurrency.$subtotal 	) ,
				//array('field1'=>'Iva'		,'field2'=>$prefixCurrency.$iva 			) ,
				array('field1'=>'Total'		,'field2'=>$prefixCurrency.$total		) ,
				array('field1'=>'Cambio'	,'field2'=>$prefixCurrency.$cambio		) 
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
						'field1'=>array('justification'=>'right','width'=>120,'spacing' => $spacing),
						'field2'=>array('justification'=>'right','heigth' => 80,'spacing' => $spacing)						
					) 
				)
			);
			
			
			//Set Firma del Comprobante						
			$pdf->ezText("\n\n\ngracias por su compra",						
			FONT_SIZE_BODY_INVICE,array('justification'=>'center','spacing' => $spacing ));
			
			//$pdf->ezText("\ncontamos con servicios a ",									
			//FONT_SIZE_BODY_INVICE,array('justification'=>'center','spacing' => $spacing ));
			
			//$pdf->ezText("\ndomicilio en el municipio de malpaisillo.",									
			//FONT_SIZE_BODY_INVICE,array('justification'=>'center','spacing' => $spacing ));
			
			$pdf->ezText("\n***************************",						
			FONT_SIZE_BODY_INVICE,array('justification'=>'center','spacing' => $spacing ));
			
			$pdf->ezText("\nTelefono de tienda:",						
			FONT_SIZE_BODY_INVICE,array('justification'=>'center','spacing' => $spacing ));
			
			$pdf->ezText("\n".$objParameterPhone->value,						
			FONT_SIZE_BODY_INVICE,array('justification'=>'center','spacing' => $spacing ));
			
			$pdf->ezText("\n".$objCompany->address,
			FONT_SIZE_BODY_INVICE,array('justification'=>'center','spacing' => $spacing ));
			
			$pdf->ezText("\nsistema:+(505) 8712-5827",						
			FONT_SIZE_BODY_INVICE,array('justification'=>'center','spacing' => $spacing ));
			
			//Set Pie		
			$pdf->EXTCreateFooter();
			//OutPut
			$pdf->ezStream(array('Content-Disposition' => $reportName)); 
			
			log_message("ERROR","preuba de impresora 015");
			
			
		}
		catch(Exception $ex){
			show_error($ex->getLine()." ".$ex->getMessage() ,500 );
		}
	}
}
?>
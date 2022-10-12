<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class App_Inventory_Inputunpost extends CI_Controller {
	
    public function __construct() {
       parent::__construct();
    }
	function viewRegister(){
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
			$this->load->model("Warehouse_Model"); 
			$this->load->model("Provider_Model");
			$this->load->model("Legal_Model");
			$this->load->model("Natural_Model");
			
			
			
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
			$datView["objTMD"]						= $this->Transaction_Master_Detail_Model->get_rowByTransaction($companyID,$transactionID,$transactionMasterID);
			$datView["objTM"]->transactionOn 		= date_format(date_create($datView["objTM"]->transactionOn),"Y-m-d");
			$datView["objProvider"]					= $this->Provider_Model->get_rowByEntity($companyID,$datView["objTM"]->entityID);
			$datView["objLegal"]					= $datView["objProvider"] != NULL ? $this->Legal_Model->get_rowByPK($companyID,$datView["objProvider"]->branchID,$datView["objProvider"]->entityID) : NULL;
			$datView["objWarehouse"]				= $this->Warehouse_Model->get_rowByPK($companyID,$datView["objTM"]->targetWarehouseID);
			
			//Set Nombre del Reporte
			$reportName		= "DOC_ENTRADA_SINPOSTEAR";
			//Set Informacion File
			$pdf->addInfo(array('Title'=>$reportName,'Author'=>APP_NAME,'CreationDate'=>date('Y-m-d H:i:s')));
			//Set Titulo			
			$pdf->EXTCreateHeader("::".$objCompany->name." ENTRADA SIN POSTEAR"."::",$objComponent->componentID,$objParameter->value,$dataSession);
			//Set Encambezado del comprobante
			$pdf->ezText("<b>DOCUMENTO NO:".$datView["objTM"]->transactionNumber."</b>\n\n\n\n",FONT_SIZE,array('justification'=>'center'));
			
			
			$data = array( 
				array('field1'=>'<b>Fecha</b>'			,'field2'=>$datView["objTM"]->transactionOn					,'field3'=>'<b>Referencia1</b>'		,'field4'=>$datView["objTM"]->reference1	) ,
				array('field1'=>'<b>Proveedor</b>'		,'field2'=>$datView["objLegal"]->comercialName 				,'field3'=>'<b>Referencia2</b>'		,'field4'=>$datView["objTM"]->reference2	) ,
				array('field1'=>'<b>Ord. Compra</b>'	,'field2'=>$datView["objTM"]->reference4					,'field3'=>'<b>Referencia3</b>'		,'field4'=>$datView["objTM"]->reference3	) ,
				array('field1'=>'<b>Estado</b>'			,'field2'=>$datView["objTM"]->workflowStageName				,'field3'=>'<b>Bodega</b>'			,'field4'=>$datView["objWarehouse"]->name	) 
				
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
			$sumCredit	= 0;
			$sumDebit	= 0;
			if($datView["objTMD"])
			foreach($datView["objTMD"] as $row){
				$data[] 	= array('field1'=>$row->itemNumber,'field2'=>$row->itemName,'field3'=>$row->unitMeasureName,'field4'=>$row->quantity, 'field5' => $row->unitaryCost, 'field6' => $row->reference1 , 'field7' => $row->reference2);
			}
			
			$pdf->ezTable(
				$data,
				array('field1'=>'Codigo','field2'=>'Nombre','field3'=>'U/M','field4'=>'Cantidad','field5'=>'Costo','field6'=>'Lote', 'field7' => 'Vencimiento'),
				'',				
				array(
					'showHeadings'=>1,'showLines'=>4,'shaded'=>0,
					'xPos'=>'left','xOrientation'=>'right',
					'width'=>$width,
					'fontSize' => FONT_SIZE,	
					'colGap' => 0
				)
			);
			
			$pdf->ezText("\n<b>Resumen</b>",FONT_SIZE);
			$data = array( 
				array('field1'=>'SUB TOTAL'	,'field2'=> (floor($datView["objTM"]->subAmount * 100) / 100)) ,
				array('field1'=>'DESCUENTO'	,'field2'=> (floor($datView["objTM"]->discount * 100) / 100 )) ,
				array('field1'=>'IMPUESTO'	,'field2'=> (floor($datView["objTM"]->tax1 * 100) / 100 )) ,
				array('field1'=>'TOTAL'		,'field2'=> (floor($datView["objTM"]->amount * 100) / 100 )) 
				
			);		
			$pdf->ezTable(
				$data,
				array('field1'=>'','field2'=>'' ),
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
			
			//Set Firma del Comprobante
			$pdf->ezText("\n\n<b>Firma</b>\n");
			$margin_left_pint = (LEFT_MARGIN / 2.54)*72;
			$y				  = $pdf->y;
			$pdf->setColor(255,255,255);
			$pdf->setStrokeColor(0,0,0);
			$pdf->setLineStyle(1);
			$pdf->line($margin_left_pint ,$y,($width + $margin_left_pint)/2,$y);
					
			//Set Pie		
			$pdf->EXTCreateFooter();
			//OutPut
			$pdf->ezStream(array('Content-Disposition' => $reportName)); 
			
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
			
			//Obtener Parametros
			$companyID 				= $this->input->post("companyID");
			$transactionID 			= $this->input->post("transactionID");				
			$transactionMasterID 	= $this->input->post("transactionMasterID");				
			
			if((!$companyID && !$transactionID && !$transactionMasterID)){
					throw new Exception(NOT_PARAMETER);								 
			} 
			
			
			$objTM	 				= $this->Transaction_Master_Model->get_rowByPK($companyID,$transactionID,$transactionMasterID);			
			if ($resultPermission 	== PERMISSION_ME && ($objTM->createdBy != $dataSession["user"]->userID))
			throw new Exception(NOT_DELETE);
			
			
			if(!$this->core_web_workflow->validateWorkflowStage("tb_transaction_master_inputunpost","statusID",$objTM->statusID,COMMAND_ELIMINABLE,$dataSession["user"]->companyID,$dataSession["user"]->branchID,$dataSession["role"]->roleID))
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
			$this->load->model("Transaction_Master_Model");
			$this->load->model("Transaction_Master_Detail_Model");	
			$this->load->model("Item_Model");
			$this->load->model("ItemWarehouse_Model");
			$this->load->library("core_web_csv/csvreader");
			
			
			//Obtener el Componente de Transacciones Other Input to Inventory
			$objComponent							= $this->core_web_tools->getComponentIDBy_ComponentName("tb_transaction_master_inputunpost");
			if(!$objComponent)
			throw new Exception("EL COMPONENTE 'tb_transaction_master_inputunpost' NO EXISTE...");
			
			$objComponentItem						= $this->core_web_tools->getComponentIDBy_ComponentName("tb_item");
			if(!$objComponentItem)
			throw new Exception("EL COMPONENTE 'tb_item' NO EXISTE...");
			
			//Obtener transaccion
			$transactionID 							= $this->core_web_transaction->getTransactionID($dataSession["user"]->companyID,"tb_transaction_master_inputunpost",0);
			$companyID 								= $dataSession["user"]->companyID;
			$objT 									= $this->Transaction_Model->getByCompanyAndTransaction($dataSession["user"]->companyID,$transactionID);
			
			$objTM["companyID"] 					= $dataSession["user"]->companyID;
			$objTM["transactionID"] 				= $transactionID;			
			$objTM["branchID"]						= $dataSession["user"]->branchID;			
			$objTM["transactionNumber"]				= $this->core_web_counter->goNextNumber($dataSession["user"]->companyID,$dataSession["user"]->branchID,"tb_transaction_master_inputunpost",0);
			$objTM["transactionCausalID"] 			= $this->core_web_transaction->getDefaultCausalID($objTM["companyID"],$objTM["transactionID"]);
			$objTM["entityID"]						= $this->input->post("txtProviderID");
			$objTM["transactionOn"]					= $this->input->post("txtTransactionOn");
			$objTM["statusIDChangeOn"]				= date("Y-m-d H:m:s");
			$objTM["componentID"] 					= $objComponent->componentID;
			$objTM["note"] 							= $this->input->post("txtDescription",'');
			$objTM["sign"] 							= $objT->signInventory;
			$objTM["currencyID"]					= $this->core_web_currency->getCurrencyDefault($dataSession["user"]->companyID)->currencyID;
			$objTM["currencyID2"]					= $objTM["currencyID"];//$this->core_web_currency->getCurrencyExternal($dataSession["user"]->companyID)->currencyID;
			$objTM["exchangeRate"]					= 1;//$this->core_web_currency->getRatio($dataSession["user"]->companyID,date("Y-m-d"),1,$objTM["currencyID"],$objTM["currencyID2"]);
			$objTM["reference1"] 					= $this->input->post("txtReference1",'');
			$objTM["reference2"] 					= $this->input->post("txtReference2",'');
			$objTM["reference3"] 					= $this->input->post("txtReference3",'');
			$objTM["reference4"] 					= $this->input->post("txtTransactionMasterIDOrdenCompra",'');
			$objTM["statusID"] 						= $this->input->post("txtStatusID");
			$objTM["amount"] 						= 0;
			$objTM["isApplied"] 					= 0;
			$objTM["journalEntryID"] 				= 0;
			$objTM["classID"] 						= NULL;
			$objTM["areaID"] 						= NULL;
			$objTM["sourceWarehouseID"]				= NULL;
			$objTM["targetWarehouseID"]				= $this->input->post("txtWarehouseID",'');
			$objTM["tax1"]							= helper_StringToNumber($this->input->post("txtIva",'0'));
			$objTM["tax2"]							= 0;
			$objTM["tax3"]							= 0;
			$objTM["tax4"]							= 0;
			$objTM["subAmount"]						= helper_StringToNumber($this->input->post("txtSubTotal",'0'));
			$objTM["discount"]						= helper_StringToNumber($this->input->post("txtDiscount",'0'));
			$objTM["amount"]						= helper_StringToNumber($this->input->post("txtTotal",'0'));
			$objTM["isActive"]						= 1;
			$this->core_web_auditoria->setAuditCreated($objTM,$dataSession);			
			
			
			
			$this->db->trans_begin();
			$transactionMasterID = $this->Transaction_Master_Model->insert($objTM);
			
			//Crear la Carpeta para almacenar los Archivos del Documento
			$path_ = PATH_FILE_OF_APP."/company_".$companyID."/component_".$objComponent->componentID."/component_item_".$transactionMasterID;
			mkdir($path_, 0700);
			
			//Recorrer la lista del detalle del documento
			$arrayListItemID 							= $this->input->post("txtDetailItemID");
			$arrayListQuantity	 						= $this->input->post("txtDetailQuantity");	
			$arrayListCost	 							= $this->input->post("txtDetailCost");			
			$arrayListLote	 							= $this->input->post("txtDetailLote");			
			$arrayListVencimiento						= $this->input->post("txtDetailVencimiento");			
			
			if(!empty($arrayListItemID)){
				foreach($arrayListItemID as $key => $value){	
					$itemID 								= $value;
					$objItem 								= $this->Item_Model->get_rowByPK($objTM["companyID"],$value);
					$quantity 								= helper_StringToNumber($arrayListQuantity[$key]);
					$cost 									= helper_StringToNumber($arrayListCost[$key]);
					$lote 									= $arrayListLote[$key];
					$vencimiento							= $arrayListVencimiento[$key];
					
					$objTMD["companyID"] 					= $objTM["companyID"];
					$objTMD["transactionID"] 				= $objTM["transactionID"];
					$objTMD["transactionMasterID"] 			= $transactionMasterID;
					$objTMD["componentID"]					= $objComponentItem->componentID;
					$objTMD["componentItemID"] 				= $itemID;
					$objTMD["quantity"] 					= $quantity;
					$objTMD["unitaryCost"]					= $cost;
					$objTMD["cost"] 						= $objTMD["quantity"] * $objTMD["unitaryCost"];
					
					$objTMD["unitaryAmount"]				= 0;
					$objTMD["amount"] 						= 0;										
					$objTMD["discount"]						= 0;
					$objTMD["unitaryPrice"]					= 0;
					$objTMD["promotionID"] 					= 0;
					
					$objTMD["reference1"]					= $lote;
					$objTMD["reference2"]					= $vencimiento;
					$objTMD["reference3"]					= '';
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
					
					$this->Transaction_Master_Detail_Model->insert($objTMD);
				}
			}
			
			
			if($this->db->trans_status() !== false){
				$this->db->trans_commit();						
				$this->core_web_notification->set_message(false,SUCCESS);
				redirect('app_inventory_inputunpost/edit/companyID/'.$companyID."/transactionID/".$objTM["transactionID"]."/transactionMasterID/".$transactionMasterID);
			}
			else{
				$this->db->trans_rollback();						
				$this->core_web_notification->set_message(true,$this->db->_error_message());
				redirect('app_inventory_inputunpost/add');	
			}
			
		}
		catch(Exception $ex){
			show_error($ex->getLine()." ".$ex->getMessage() ,500 );
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
			$this->load->model("Transaction_Master_Model");
			$this->load->model("Transaction_Master_Detail_Model");				$this->load->model("Item_Model");			
			$this->load->library('core_web_csv/csvreader'); 		
			
			//Obtener el Componente de Transacciones de Solicitud General
			$objComponent							= $this->core_web_tools->getComponentIDBy_ComponentName("tb_transaction_master_inputunpost");
			if(!$objComponent)
			throw new Exception("EL COMPONENTE 'tb_transaction_master_inputunpost' NO EXISTE...");
			
			$objComponentItem						= $this->core_web_tools->getComponentIDBy_ComponentName("tb_item");
			if(!$objComponentItem)
			throw new Exception("EL COMPONENTE 'tb_item' NO EXISTE...");
			
			$branchID 								= $dataSession["user"]->branchID;
			$roleID 								= $dataSession["role"]->roleID;
			$companyID 								= $dataSession["user"]->companyID;
			$transactionID 							= $this->input->post("txtTransactionID");
			$transactionMasterID					= $this->input->post("txtTransactionMasterID");
			$objTM	 								= $this->Transaction_Master_Model->get_rowByPK($companyID,$transactionID,$transactionMasterID);
			$oldStatusID 							= $objTM->statusID;
			
			//Validar Edicion por el Usuario
			if ($resultPermission 	== PERMISSION_ME && ($objTM->createdBy != $dataSession["user"]->userID))
			throw new Exception(NOT_EDIT);
			
			//Validar si el estado permite editar
			if(!$this->core_web_workflow->validateWorkflowStage("tb_transaction_master_inputunpost","statusID",$objTM->statusID,COMMAND_EDITABLE_TOTAL,$dataSession["user"]->companyID,$dataSession["user"]->branchID,$dataSession["role"]->roleID))
			throw new Exception(NOT_WORKFLOW_EDIT);					
			
			if($this->core_web_accounting->cycleIsCloseByDate($companyID,$objTM->transactionOn))
			throw new Exception("EL DOCUMENTO NO PUEDE ACTUALIZARCE, EL CICLO CONTABLE ESTA CERRADO");
			
			//Actualizar Maestro
			$objTMNew["transactionOn"]				= $this->input->post("txtTransactionOn");
			$objTMNew["statusIDChangeOn"]			= date("Y-m-d H:m:s");			
			$objTMNew["note"] 						= $this->input->post("txtDescription",'');
			$objTMNew["exchangeRate"]				= 1;//$this->core_web_currency->getRatio($companyID,date("Y-m-d"),1,$objTM->currencyID,$objTM->currencyID2);
			$objTMNew["entityID"]					= $this->input->post("txtProviderID");
			$objTMNew["statusID"] 					= $this->input->post("txtStatusID");						
			$objTMNew["reference1"] 				= $this->input->post("txtReference1",'');
			$objTMNew["reference2"] 				= $this->input->post("txtReference2",'');
			$objTMNew["reference3"] 				= $this->input->post("txtReference3",'');
			$objTMNew["reference4"] 				= $this->input->post("txtTransactionMasterIDOrdenCompra",'');
			$objTMNew["sourceWarehouseID"]			= NULL;
			$objTMNew["targetWarehouseID"]			= $this->input->post("txtWarehouseID",'');
			$objTMNew["tax1"]						= helper_StringToNumber($this->input->post("txtIva",'0'));
			$objTMNew["tax2"]						= 0;
			$objTMNew["tax3"]						= 0;
			$objTMNew["tax4"]						= 0;
			$objTMNew["subAmount"]					= helper_StringToNumber($this->input->post("txtSubTotal",'0'));
			$objTMNew["discount"]					= helper_StringToNumber($this->input->post("txtDiscount",'0'));
			$objTMNew["amount"]						= helper_StringToNumber($this->input->post("txtTotal",'0'));
			
			$this->db->trans_begin();
			//El Estado solo permite editar el workflow
			if($this->core_web_workflow->validateWorkflowStage("tb_transaction_master_inputunpost","statusID",$objTM->statusID,COMMAND_EDITABLE,$dataSession["user"]->companyID,$dataSession["user"]->branchID,$dataSession["role"]->roleID)){
				$objTMNew								= array();
				$objTMNew["statusID"] 					= $this->input->post("txtStatusID");						
				$this->Transaction_Master_Model->update($companyID,$transactionID,$transactionMasterID,$objTMNew);
			}
			else{
				$this->Transaction_Master_Model->update($companyID,$transactionID,$transactionMasterID,$objTMNew);
			}
			
			//Actualizar Detalle
			$listTMD_ID 								= $this->input->post("txtDetailTransactionDetailID");
			$arrayListItemID 							= $this->input->post("txtDetailItemID");
			$arrayListQuantity	 						= $this->input->post("txtDetailQuantity");			
			$arrayListCost	 							= $this->input->post("txtDetailCost");			
			$arrayListLote	 							= $this->input->post("txtDetailLote");			
			$arrayListVencimiento						= $this->input->post("txtDetailVencimiento");						$archivoCSV 								= $this->input->post("txtFileImport");						
			
						if($archivoCSV != ".csv"){				$this->Transaction_Master_Detail_Model->deleteWhereTM($companyID,$transactionID,$transactionMasterID);								//Leer archivo				$path 	= PATH_FILE_OF_APP."/company_".$companyID."/component_".$objComponent->componentID."/component_item_".$transactionMasterID;							$path 	= $path.'/'.$archivoCSV;								if (!file_exists($path))				throw new Exception("NO EXISTE EL ARCHIVO PARA IMPORTAR LOS PRECIOS");								$objParameter	= $this->core_web_parameter->getParameter("CORE_CSV_SPLIT",$companyID);				$characterSplie = $objParameter->value;								$this->csvreader->separator = $characterSplie;				$table 			= $this->csvreader->parse_file($path); 				$fila 			= 0;				if($table)				foreach ($table as $row) 				{						$fila++;					$codigo 		= $row["Codigo"];					$description 	= $row["Nombre"];					$cantidad 		= $row["Cantidad"];					$costo 			= $row["Costo"];								$objItem		= $this->Item_Model->get_rowByCode($companyID,$codigo);		


					$transactionMasterDetailID				= 0;					
					$itemID 								= $objItem->itemID;
					$quantity 								= helper_StringToNumber($cantidad);
					$cost 									= helper_StringToNumber($costo);
					$lote 									= '';
					$vencimiento							= '';
					//Nuevo Detalle
					if($transactionMasterDetailID == 0){						
						$objTMD 								= array();
						$objTMD["companyID"] 					= $companyID;
						$objTMD["transactionID"] 				= $transactionID;
						$objTMD["transactionMasterID"] 			= $transactionMasterID;
						$objTMD["componentID"]					= $objComponentItem->componentID;
						$objTMD["componentItemID"] 				= $itemID;//itemID
						$objTMD["quantity"] 					= $quantity;
						$objTMD["unitaryCost"]					= $cost;
						$objTMD["cost"] 						= $objTMD["quantity"] * $objTMD["unitaryCost"];
						$objTMD["unitaryAmount"]				= 0;
						$objTMD["amount"] 						= 0;
						$objTMD["discount"]						= 0;
						$objTMD["unitaryPrice"]					= 0;
						$objTMD["promotionID"] 					= 0;
						$objTMD["reference1"]					= $lote;
						$objTMD["reference2"]					= $vencimiento;
						$objTMD["reference3"]					= '';
						$objTMD["catalogStatusID"]				= 0;
						$objTMD["inventoryStatusID"]			= 0;
						$objTMD["isActive"]						= 1;
						$objTMD["quantityStock"]				= 0;
						$objTMD["quantiryStockInTraffic"]		= 0;
						$objTMD["quantityStockUnaswared"]		= 0;
						$objTMD["remaingStock"]					= 0;
						$objTMD["expirationDate"]				= NULL;
						$objTMD["inventoryWarehouseSourceID"]	= $objTMNew["sourceWarehouseID"];
						$objTMD["inventoryWarehouseTargetID"]	= $objTMNew["targetWarehouseID"];
						$this->Transaction_Master_Detail_Model->insert($objTMD);
						

					}				}												}			else{								$this->Transaction_Master_Detail_Model->deleteWhereIDNotIn($companyID,$transactionID,$transactionMasterID,$listTMD_ID);
				if(!empty($arrayListItemID)){
					foreach($arrayListItemID as $key => $value){
						$transactionMasterDetailID				= $listTMD_ID[$key];	
						$objItem 								= $this->Item_Model->get_rowByPK($objTM->companyID,$value);
						$itemID 								= $value;
						$quantity 								= helper_StringToNumber($arrayListQuantity[$key]);
						$cost 									= helper_StringToNumber($arrayListCost[$key]);
						$lote 									= $arrayListLote[$key];
						$vencimiento							= $arrayListVencimiento[$key];
						
						//Nuevo Detalle
						if($transactionMasterDetailID == 0){						
							$objTMD 								= array();
							$objTMD["companyID"] 					= $companyID;
							$objTMD["transactionID"] 				= $transactionID;
							$objTMD["transactionMasterID"] 			= $transactionMasterID;
							$objTMD["componentID"]					= $objComponentItem->componentID;
							$objTMD["componentItemID"] 				= $itemID;//itemID
							$objTMD["quantity"] 					= $quantity;
							$objTMD["unitaryCost"]					= $cost;
							$objTMD["cost"] 						= $objTMD["quantity"] * $objTMD["unitaryCost"];
							
							$objTMD["unitaryAmount"]				= 0;
							$objTMD["amount"] 						= 0;										
							$objTMD["discount"]						= 0;
							$objTMD["unitaryPrice"]					= 0;
							$objTMD["promotionID"] 					= 0;
							
							$objTMD["reference1"]					= $lote;
							$objTMD["reference2"]					= $vencimiento;
							$objTMD["reference3"]					= '';
							$objTMD["catalogStatusID"]				= 0;
							$objTMD["inventoryStatusID"]			= 0;
							$objTMD["isActive"]						= 1;
							$objTMD["quantityStock"]				= 0;
							$objTMD["quantiryStockInTraffic"]		= 0;
							$objTMD["quantityStockUnaswared"]		= 0;
							$objTMD["remaingStock"]					= 0;
							$objTMD["expirationDate"]				= NULL;
							$objTMD["inventoryWarehouseSourceID"]	= $objTMNew["sourceWarehouseID"];
							$objTMD["inventoryWarehouseTargetID"]	= $objTMNew["targetWarehouseID"];;						
							$this->Transaction_Master_Detail_Model->insert($objTMD);
							
						}
						//Editar Detalle
						else{
							$objTMD 									= $this->Transaction_Master_Detail_Model->get_rowByPK($companyID,$transactionID,$transactionMasterID,$transactionMasterDetailID);						
							$objTMDNew["quantity"] 						= $quantity;
							$objTMDNew["unitaryCost"]					= $cost;
							$objTMDNew["cost"] 							= $objTMDNew["quantity"] * $objTMDNew["unitaryCost"];
							$objTMDNew["reference1"]					= $lote;
							$objTMDNew["reference2"]					= $vencimiento;
							$objTMDNew["inventoryWarehouseSourceID"]	= $objTMNew["sourceWarehouseID"];
							$objTMDNew["inventoryWarehouseTargetID"]	= $objTMNew["targetWarehouseID"];
							$this->Transaction_Master_Detail_Model->update($companyID,$transactionID,$transactionMasterID,$transactionMasterDetailID,$objTMDNew);						
						}
						
						
					}
				}
			}
			
			//Aplicar el Documento?
			if( $this->core_web_workflow->validateWorkflowStage("tb_transaction_master_inputunpost","statusID",$objTMNew["statusID"],COMMAND_APLICABLE,$dataSession["user"]->companyID,$dataSession["user"]->branchID,$dataSession["role"]->roleID) &&  $oldStatusID != $objTMNew["statusID"] ){
				//Ingresar en Kardex.
				$this->core_web_inventory->calculateKardexNewInput($companyID,$transactionID,$transactionMasterID);			
			
				//Crear Conceptos.
				$this->core_web_concept->inputunpost($companyID,$transactionID,$transactionMasterID);
			}
			
			
			if($this->db->trans_status() !== false){
				$this->db->trans_commit();						
				$this->core_web_notification->set_message(false,SUCCESS);
				redirect('app_inventory_inputunpost/edit/companyID/'.$companyID."/transactionID/".$transactionID."/transactionMasterID/".$transactionMasterID);
			}
			else{
				$this->db->trans_rollback();						
				$this->core_web_notification->set_message(true,$this->db->_error_message());
				redirect('app_inventory_inputunpost/add');	
			}
		}
		catch(Exception $ex){
			show_error($ex->getLine()." ".$ex->getMessage() ,500 );
		}			
	}
	function save($mode){
	
			//AUTENTICADO
			if(!$this->core_web_authentication->isAuthenticated())
			throw new Exception(USER_NOT_AUTENTICATED);
			$dataSession		= $this->session->all_userdata();
			
			//Validar Formulario						
			$this->form_validation->set_rules("txtProviderID","Proveedor","required");
			$this->form_validation->set_rules("txtStatusID","Estado","required");
			$this->form_validation->set_rules("txtTransactionOn","Fecha","required");
				
			//Validar Formulario
			if(!$this->form_validation->run()){
				$stringValidation = $this->core_web_tools->formatMessageError(validation_errors());
				$this->core_web_notification->set_message(true,$stringValidation);
				redirect('app_inventory_inputunpost/add');
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
				redirect('app_inventory_inputunpost/add');
				exit;
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
			////////////////////////////////////////
			////////////////////////////////////////
			$this->load->model("UserWarehouse_Model"); 
			$this->load->model("Transaction_Master_Model"); 			
			$this->load->model("Transaction_Master_Detail_Model");
			$this->load->model("Warehouse_Model");
			$this->load->model("Provider_Model");
			$this->load->model("Natural_Model");
			$this->load->model("core/User_Model");
			$this->load->model("Natural_Model");
			$this->load->model("Legal_Model");
			
			
			//Obtener parametros
			$uri					= $this->uri->uri_to_assoc(3);						
			$companyID				= $uri["companyID"];
			$transactionID			= $uri["transactionID"];
			$transactionMasterID	= $uri["transactionMasterID"];
			$branchID 				= $dataSession["user"]->branchID;
			$roleID 				= $dataSession["role"]->roleID;		
			$userID 				= $dataSession["user"]->userID;
			if((!$transactionID || !$transactionMasterID))
			{ 
				redirect('app_inventory_inputunpost/add');	
			} 		
			
			
			//Obtener el componente de Item
			$objComponentItem		= $this->core_web_tools->getComponentIDBy_ComponentName("tb_item");
			if(!$objComponentItem)
			throw new Exception("EL COMPONENTE 'tb_item' NO EXISTE...");
			
			//Obtener el componente de Proveedor
			$objComponentProvider		= $this->core_web_tools->getComponentIDBy_ComponentName("tb_provider");
			if(!$objComponentProvider)
			throw new Exception("EL COMPONENTE 'tb_provider' NO EXISTE...");
			
			//Obtener el componente de Entrada sin postear
			$objComponentInputSinPost		= $this->core_web_tools->getComponentIDBy_ComponentName("tb_transaction_master_inputunpost");
			if(!$objComponentInputSinPost)
			throw new Exception("EL COMPONENTE 'tb_transaction_master_inputunpost' NO EXISTE...");
			
			//Obtener el componente de Orden de Compra
			$objComponentOrdenCompra		= $this->core_web_tools->getComponentIDBy_ComponentName("tb_transaction_master_purchaseorden");
			if(!$objComponentOrdenCompra)
			throw new Exception("EL COMPONENTE 'tb_transaction_master_purchaseorden' NO EXISTE...");
			
			
			//Obtener el Registro	
			$datView["objComponentItem"]	 		= $objComponentItem;
			$datView["objComponentProvider"]	 	= $objComponentProvider;
			$datView["objComponentInputSinPost"]	= $objComponentInputSinPost;
			$datView["objComponentOrdenCompra"]		= $objComponentOrdenCompra;
			$datView["objListWarehouse"]		= $this->UserWarehouse_Model->getRowByUserID($companyID,$userID);
			$datView["objTM"]	 				= $this->Transaction_Master_Model->get_rowByPK($companyID,$transactionID,$transactionMasterID);
			$datView["objTMD"]					= $this->Transaction_Master_Detail_Model->get_rowByTransaction($companyID,$transactionID,$transactionMasterID);
			$datView["objListWorkflowStage"]	= $this->core_web_workflow->getWorkflowStageByStageInit("tb_transaction_master_inputunpost","statusID",$datView["objTM"]->statusID,$companyID,$branchID,$roleID);
			$datView["objTM"]->transactionOn 	= date_format(date_create($datView["objTM"]->transactionOn),"Y-m-d");
			$datView["objTMOrdenCompra"]		= $this->Transaction_Master_Model->get_rowByTransactionMasterID($companyID,$datView["objTM"]->reference4);
			
			$datView["objProvider"]				= $this->Provider_Model->get_rowByEntity($companyID,$datView["objTM"]->entityID);			
			$datView["objNaturalDefault"]		= $this->Natural_Model->get_rowByPK($companyID,$datView["objProvider"]->branchID,$datView["objProvider"]->entityID);
			$datView["objLegalDefault"]			= $this->Legal_Model->get_rowByPK($companyID,$datView["objProvider"]->branchID,$datView["objProvider"]->entityID);
			
			//Renderizar Resultado
			$dataSession["notification"]	= $this->core_web_error->get_error($dataSession["user"]->userID);
			$dataSession["message"]			=  $this->core_web_notification->get_message();
			$dataSession["head"]			= $this->load->view('app_inventory_inputunpost/edit_head',$datView,true);
			$dataSession["body"]			= $this->load->view('app_inventory_inputunpost/edit_body',$datView,true);
			$dataSession["script"]			= $this->load->view('app_inventory_inputunpost/edit_script',$datView,true);  
			$dataSession["footer"]			= "";				
			$this->load->view("core_masterpage/default_masterpage",$dataSession);	
			
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
			
			//Library
			$this->load->model("UserWarehouse_Model");
			$this->load->model("Warehouse_Model");
			$this->load->model("Employee_Model");
			$this->load->model("Natural_Model");
			
			$companyID 							= $dataSession["user"]->companyID;
			$branchID 							= $dataSession["user"]->branchID;
			$roleID 							= $dataSession["role"]->roleID;
			$userID								= $dataSession["user"]->userID;
			$dataView["objListWorkflowStage"]	= $this->core_web_workflow->getWorkflowInitStage("tb_transaction_master_inputunpost","statusID",$companyID,$branchID,$roleID);
			$dataView["objListWarehouse"]		= $this->UserWarehouse_Model->getRowByUserID($companyID,$userID);
			
			//Obtener el componente de Item
			$objComponentItem		= $this->core_web_tools->getComponentIDBy_ComponentName("tb_item");
			if(!$objComponentItem)
			throw new Exception("EL COMPONENTE 'tb_item' NO EXISTE...");
			
			//Obtener el componente de Proveedor
			$objComponentProvider		= $this->core_web_tools->getComponentIDBy_ComponentName("tb_provider");
			if(!$objComponentProvider)
			throw new Exception("EL COMPONENTE 'tb_provider' NO EXISTE...");
			
			//Obtener el componente de Entrada sin postear
			$objComponentInputSinPost		= $this->core_web_tools->getComponentIDBy_ComponentName("tb_transaction_master_inputunpost");
			if(!$objComponentInputSinPost)
			throw new Exception("EL COMPONENTE 'tb_transaction_master_inputunpost' NO EXISTE...");
			
			//Obtener el componente de Orden de Compra
			$objComponentOrdenCompra		= $this->core_web_tools->getComponentIDBy_ComponentName("tb_transaction_master_purchaseorden");
			if(!$objComponentOrdenCompra)
			throw new Exception("EL COMPONENTE 'tb_transaction_master_purchaseorden' NO EXISTE...");
			
			
			$dataView["objComponentItem"] 			= $objComponentItem;
			$dataView["objComponentProvider"] 		= $objComponentProvider;
			$dataView["objComponentInputSinPost"]	= $objComponentInputSinPost;
			$dataView["objComponentOrdenCompra"]	= $objComponentOrdenCompra;
			
			//Renderizar Resultado 
			$dataSession["notification"]		= $this->core_web_error->get_error($dataSession["user"]->userID);
			$dataSession["message"]				= $this->core_web_notification->get_message();
			$dataSession["head"]				= $this->load->view('app_inventory_inputunpost/news_head',$dataView,true);
			$dataSession["body"]				= $this->load->view('app_inventory_inputunpost/news_body',$dataView,true);
			$dataSession["script"]				= $this->load->view('app_inventory_inputunpost/news_script',$dataView,true);  
			$dataSession["footer"]				= "";
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
			//Obtener el componente Para mostrar la lista
			$objComponent		= $this->core_web_tools->getComponentIDBy_ComponentName("tb_transaction_master_inputunpost");
			if(!$objComponent)
			throw new Exception("EL COMPONENTE 'tb_transaction_master_inputunpost' NO EXISTE...");
			
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
			$dataSession["head"]			= $this->load->view('app_inventory_inputunpost/list_head','',true);
			$dataSession["footer"]			= $this->load->view('app_inventory_inputunpost/list_footer','',true);
			$dataSession["body"]			= $dataViewRender; 
			$dataSession["script"]			= $this->load->view('app_inventory_inputunpost/list_script','',true).$this->core_web_javascript->createVar("componentID",$objComponent->componentID);  
			$this->load->view("core_masterpage/default_masterpage",$dataSession);		
		}
		catch(Exception $ex){
			show_error($ex->getLine()." ".$ex->getMessage() ,500 );
		}
	}	
	
	
}
?>
<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class App_Invoice_Price extends CI_Controller {
	
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
			$listPriceID				= $uri["listPriceID"];			
			$companyID 					= $dataSession["user"]->companyID;		
			$branchID 					= $dataSession["user"]->branchID;		
			$roleID 					= $dataSession["role"]->roleID;		
			
			
			//Cargar Libreria
			$this->load->library('core_web_pdf/src/EXTCezpdf.php');
			$this->load->model("core/Company_Model"); 
			$this->load->model("List_Price_Model"); 
			$this->load->model("Price_Model"); 
			
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
			$datView["objListPrice"]				= $this->List_Price_Model->get_rowByPK($companyID,$listPriceID);
			$datView["objListPriceDetail"]			= $this->Price_Model->get_rowByAll($companyID,$listPriceID);
			
			//Set Nombre del Reporte
			$reportName		= "DOC_LISTA_PRECIO";
			//Set Informacion File
			$pdf->addInfo(array('Title'=>$reportName,'Author'=>APP_NAME,'CreationDate'=>date('Y-m-d H:i:s')));
			//Set Titulo			
			$pdf->EXTCreateHeader("::".$objCompany->name." LISTA DE PRECIO"."::",$objComponent->componentID,$objParameter->value,$dataSession);
			//Set Encambezado del comprobante
			$pdf->ezText("<b>DOCUMENTO NO:".$datView["objListPrice"]->listPriceID."</b>\n\n\n\n",FONT_SIZE,array('justification'=>'center'));
			
			
			$data = array( 
				array('field1'=>'<b>Fecha Inicial:</b>'		,'field2'=>$datView["objListPrice"]->startOn					,'field3'=>'<b>Fecha Final:</b>'	,'field4'=>$datView["objListPrice"]->endOn	) ,
				array('field1'=>'<b>Nombre:</b>'			,'field2'=>$datView["objListPrice"]->name 						,'field3'=>'<b>Estado:</b>'			,'field4'=>$datView["objListPrice"]->statusName	) 
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
						'field1'=>array('justification'=>'left','width'=>70),
						'field2'=>array('justification'=>'left'),
						'field3'=>array('justification'=>'left','width'=>66),
						'field4'=>array('justification'=>'left'),
						'field5'=>array('justification'=>'left','width'=>35),
						'field6'=>array('justification'=>'left')
					) 
				)
			);
			
			//Set Detalle del Comprobante
			$data		= array();
			$sumCredit	= 0;
			$sumDebit	= 0;
			if($datView["objListPriceDetail"])
			foreach($datView["objListPriceDetail"] as $row){
				$data[] 	= array(
					'field1'=>$row->itemNumber,
					'field2'=>$row->itemName,
					'field3'=>$row->tipoPrice,
					'field4'=>$row->percentage,
					'field5'=>$row->cost,
					'field6'=>$row->price
				);
			}
			$pdf->ezText("\n\n",FONT_SIZE);
			$pdf->ezTable(
				$data,
				array('field1'=>'Codigo','field2'=>'Nombre','field3'=>'Tipo','field4'=>'%','field5'=>'Costo','field6'=>'Precio'),
				'',				
				array(
					'showHeadings'=>1,'showLines'=>4,'shaded'=>0,
					'xPos'=>'left','xOrientation'=>'right',
					'width'=>$width,
					'fontSize' => FONT_SIZE,	
					'colGap' => 0
				)
			);
			
			//Set Pie		
			$pdf->EXTCreateFooter();
			//OutPut
			$pdf->ezStream(array('Content-Disposition' => $reportName)); 
			
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
			$this->load->model("List_Price_Model"); 
			
			//Redireccionar datos
			$uri			= $this->uri->uri_to_assoc(3);
						
			$companyID		= $uri["companyID"];
			$listPriceID	= $uri["listPriceID"];				
			$branchID		= $dataSession["user"]->branchID;
			$roleID 		= $dataSession["role"]->roleID;			
			
			if((!$companyID || !$branchID || !$listPriceID))
			{ 
				redirect('app_invoice_price/add');	
			} 		
			
			
			//Obtener el Registro			
			$datView["objListPrice"]	 		= $this->List_Price_Model->get_rowByPK($companyID,$listPriceID);						
			$objComponent						= $this->core_web_tools->getComponentIDBy_ComponentName("tb_list_price");
			if(!$objComponent)
			throw new Exception("00409 EL COMPONENTE 'tb_list_price' NO EXISTE...");
			
			//Obtener Informacion
			$datView["objListWorkflowStage"]			= $this->core_web_workflow->getWorkflowStageByStageInit("tb_list_price","statusID",$datView["objListPrice"]->statusID,$companyID,$branchID,$roleID);			
			$datView["objComponent"] 					= $objComponent;			
			
			//Renderizar Resultado
			$dataSession["notification"]	= $this->core_web_error->get_error($dataSession["user"]->userID);
			$dataSession["message"]			=  $this->core_web_notification->get_message();
			$dataSession["head"]			= $this->load->view('app_invoice_price/edit_head',$datView,true);
			$dataSession["body"]			= $this->load->view('app_invoice_price/edit_body',$datView,true);
			$dataSession["script"]			= $this->load->view('app_invoice_price/edit_script',$datView,true);  
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
			$this->load->model("List_Price_Model");  
			
			//Nuevo Registro
			$companyID 			= $this->input->post("companyID");
			$listPriceID		= $this->input->post("listPriceID");				
			
			
			if((!$companyID && !$listPriceID )){
					throw new Exception(NOT_PARAMETER);			
					 
			} 
			
			//OBTENER EL lista de precio
			$objListPrice	= $this->List_Price_Model->get_rowByPK($companyID,$listPriceID);	
			
			
			//PERMISO SOBRE EL REGISTRO
			if ($resultPermission 	== PERMISSION_ME && ($objListPrice->createdBy != $dataSession["user"]->userID))
			throw new Exception(NOT_DELETE);
			
			
			//PERMISO PUEDE ELIMINAR EL REGISTRO SEGUN EL WORKFLOW
			if(!$this->core_web_workflow->validateWorkflowStage("tb_list_price","statusID",$objListPrice->statusID,COMMAND_ELIMINABLE,$dataSession["user"]->companyID,$dataSession["user"]->branchID,$dataSession["role"]->roleID))
			throw new Exception(NOT_WORKFLOW_DELETE);
			
			//Eliminar el Registro
			$this->List_Price_Model->delete($companyID,$listPriceID);
					
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
			
			$this->load->model("List_Price_Model");	
			$this->load->model("Price_Model");	
			$this->load->model("Item_Model");
			$this->load->library('core_web_csv/csvreader'); 			
			
			//Obtener el Componente de la lista de precio
			$objComponent							= $this->core_web_tools->getComponentIDBy_ComponentName("tb_list_price");
			if(!$objComponent)
			throw new Exception("EL COMPONENTE 'tb_list_price' NO EXISTE...");
			
			
			$branchID 								= $dataSession["user"]->branchID;
			$roleID 								= $dataSession["role"]->roleID;
			$companyID 								= $dataSession["user"]->companyID;			
			$listPriceID 							= $this->input->post("txtListPriceID");
			$filePrice 								= $this->input->post("txtFilePrice",''); 
			
			$objListPrice							= $this->List_Price_Model->get_rowByPK($companyID,$listPriceID);
			$oldStatusID 							= $objListPrice->statusID;
			
			//Validar Edicion por el Usuario
			if ($resultPermission 	== PERMISSION_ME && ($objListPrice->createdBy != $dataSession["user"]->userID))
			throw new Exception(NOT_EDIT);
			
			//Validar si el estado permite editar
			if(!$this->core_web_workflow->validateWorkflowStage("tb_list_price","statusID",$objListPrice->statusID,COMMAND_EDITABLE_TOTAL,$dataSession["user"]->companyID,$dataSession["user"]->branchID,$dataSession["role"]->roleID))
			throw new Exception(NOT_WORKFLOW_EDIT);					
			
			
			$this->db->trans_begin();			
			//El Estado solo permite editar el workflow
			if($this->core_web_workflow->validateWorkflowStage("tb_list_price","statusID",$oldStatusID,COMMAND_EDITABLE,$dataSession["user"]->companyID,$dataSession["user"]->branchID,$dataSession["role"]->roleID)){				
				$objListPrice["statusID"] 		= $this->input->post('txtStatusID','');
				$this->List_Price_Model->update($companyID,$listPriceID);
			}
			else{
				
				$objListPrice 							= NULL;
				$objListPrice["startOn"]				= $this->input->post("txtStartOn",'');
				$objListPrice["endOn"]					= $this->input->post("txtEndOn",'');
				$objListPrice["name"]					= $this->input->post("txtName",'');
				$objListPrice["description"]			= $this->input->post("txtDescription",'');
				$objListPrice["statusID"]				= $this->input->post("txtStatusID",'');
				$objListPrice["isActive"]				= 1;			
				$this->List_Price_Model->update($companyID,$listPriceID,$objListPrice);
				
				
				if($filePrice != ""){
					
					$path 	= PATH_FILE_OF_APP."/company_".$companyID."/component_".$objComponent->componentID."/component_item_".$listPriceID;			
					$path 	= $path.'/'.$filePrice;
					
					if (!file_exists($path))
					throw new Exception("NO EXISTE EL ARCHIVO PARA IMPORTAR LOS PRECIOS");
				
									$objParameter	= $this->core_web_parameter->getParameter("CORE_CSV_SPLIT",$companyID);					$characterSplie = $objParameter->value;					
					$this->Price_Model->delete($companyID,$listPriceID);
					$objTipePrice 	= $this->core_web_catalog->getCatalogAllItem("tb_price","typePriceID",$companyID);					
					$fila 			= 0;					$this->csvreader->separator = $characterSplie;
					$table 			= $this->csvreader->parse_file($path); 
					if($table)
					foreach ($table as $row) 
					{												
						$fila++;
						$codigo 		= $row["Codigo"];
						$description 	= $row["Descripcion"];
						$typePriceID	= 0;
						$priceID 		= 0;						
						foreach($objTipePrice as $price){									
							$typePriceID			= 0;
							$priceID 				= 0;
							$objItem				= $this->Item_Model->get_rowByCode($companyID,$codigo);
							$typePriceID			= $price->catalogItemID;							
							$precieValueAbs			= $row["".$price->catalogItemID."-".strtoupper($price->display)."-abs"];							if($objItem->itemID == 596 ){								log_message("ERROR",print_r($objItem));								log_message("ERROR",print_r($row));							}							
							//Insert register to price
							$dataPrice["companyID"] 	= $companyID;
							$dataPrice["listPriceID"] 	= $listPriceID;
							$dataPrice["itemID"] 		= $objItem->itemID;
							$dataPrice["typePriceID"] 	= $typePriceID;							
							$dataPrice["price"] 		= $precieValueAbs;
							$dataPrice["percentage"] 	= $objItem->cost == 0 ? 									($precieValueAbs / 100) : 									(((100 * $precieValueAbs) / $objItem->cost) - 100);									
							$this->Price_Model->insert($dataPrice);
						}
					}
				}
				
				//Generar Archivo
				$generateFile = $this->input->post("txtGenerateFile",'0');
				if($generateFile){
					date_default_timezone_set(APP_TIMEZONE); 
					$date 	= date("Y_m_d_H_i_s");
					//Crear la Carpeta para almacenar los Archivos del Cliente
					$path 	= PATH_FILE_OF_APP."/company_".$companyID."/component_".$objComponent->componentID."/component_item_".$listPriceID;
					$path 	= $path.'/list_price_default_'.$date.'.csv';
					$fp 	= fopen($path, 'w');
					
					//Crear el archivo de precios
					$objListItem 	= $this->Item_Model->get_rowByCompany($companyID);
					$objTipePrice 	= $this->core_web_catalog->getCatalogAllItem("tb_price","typePriceID",$companyID);
					$objParameter	= $this->core_web_parameter->getParameter("CORE_CSV_SPLIT",$companyID);					$characterSplie = $objParameter->value;
					$field 		 	= ["Codigo","Descripcion","Costo"];
					foreach($objTipePrice as $price){						
						array_push($field,"".$price->catalogItemID."-".strtoupper($price->display)."-abs");
					}
					
					fputcsv($fp, $field,$characterSplie);
					foreach($objListItem as $item){
						$rowfield = [];
						array_push($rowfield,$item->itemNumber);
						array_push($rowfield,$item->name);
						array_push($rowfield,$item->cost);
					
						foreach($objTipePrice as $price){
							//obtener el precio
							$objPrice 	=	$this->Price_Model->get_rowByPK($companyID,$listPriceID,$item->itemID,$price->catalogItemID);
							$percentage	= 	($objPrice != null ? $objPrice->percentage : 0);
							$price		= 	($objPrice != null ? $objPrice->price : 0);							
							array_push($rowfield,$price);
						}
						fputcsv($fp, $rowfield,$characterSplie);
					}			
					fclose($fp);
				}			
			}
			
			//Confirmar Entidad
			if($this->db->trans_status() !== false){
				$this->db->trans_commit();						
				$this->core_web_notification->set_message(false,SUCCESS);
				redirect('app_invoice_price/edit/companyID/'.$companyID."/listPriceID/".$listPriceID);
			}
			else{
				$this->db->trans_rollback();						
				$this->core_web_notification->set_message(true,$this->db->_error_message());
				redirect('app_invoice_price/add');	
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
			
			$this->load->model("List_Price_Model");		
			$this->load->model("Item_Model");
			
			//Obtener el Componente de Lista de Precio
			$objComponent							= $this->core_web_tools->getComponentIDBy_ComponentName("tb_list_price");
			if(!$objComponent)
			throw new Exception("EL COMPONENTE 'tb_list_price' NO EXISTE...");
			
			
			//Obtener transaccion
			$companyID 								= $dataSession["user"]->companyID;						
			$objListPrice["companyID"]				= $companyID;
			$objListPrice["startOn"]				= $this->input->post("txtStartOn",'');
			$objListPrice["endOn"]					= $this->input->post("txtEndOn",'');
			$objListPrice["name"]					= $this->input->post("txtName",'');
			$objListPrice["description"]			= $this->input->post("txtDescription",'');
			$objListPrice["statusID"]				= $this->input->post("txtStatusID",'');
			$objListPrice["isActive"]				= 1;
			$this->core_web_auditoria->setAuditCreated($objListPrice,$dataSession);
			
			$this->db->trans_begin();
			$listPriceID = $this->List_Price_Model->insert($objListPrice);
			
			//Crear la Carpeta para almacenar los Archivos del Cliente
			$path 	= PATH_FILE_OF_APP."/company_".$companyID."/component_".$objComponent->componentID."/component_item_".$listPriceID;
			mkdir($path, 0700);
			$path 	= $path.'/list_price_default.csv';
			$fp 	= fopen($path, 'w');
			
			//Crear el archivo de precios
			$objListItem 	= $this->Item_Model->get_rowByCompany($companyID);
			$objTipePrice 	= $this->core_web_catalog->getCatalogAllItem("tb_price","typePriceID",$companyID);
			
			$field 		 	= ["Codigo","Descripcion","Costo"];
			foreach($objTipePrice as $price){
				array_push($field,"".$price->catalogItemID."-".strtoupper($price->display)."-%");
				array_push($field,"".$price->catalogItemID."-".strtoupper($price->display)."-abs");
			}
			
			fputcsv($fp, $field);
			foreach($objListItem as $item){
				$rowfield = [];
				array_push($rowfield,$item->itemNumber);
				array_push($rowfield,$item->name);
				array_push($rowfield,$item->cost);
			
				foreach($objTipePrice as $price){
					array_push($rowfield,0);
					array_push($rowfield,0);
				}
				
				fputcsv($fp, $rowfield);
			}			
			fclose($fp);
			
			
			//Commit Operaciones
			if($this->db->trans_status() !== false){
				$this->db->trans_commit();						
				$this->core_web_notification->set_message(false,SUCCESS);
				redirect('app_invoice_price/edit/companyID/'.$companyID."/listPriceID/".$listPriceID);
			}
			else{
				$this->db->trans_rollback();						
				$this->core_web_notification->set_message(true,$this->db->_error_message());
				redirect('app_invoice_price/add');	
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
				redirect('app_invoice_price/add');
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
			$dataView["objListWorkflowStage"]	= $this->core_web_workflow->getWorkflowInitStage("tb_list_price","statusID",$companyID,$branchID,$roleID);
			
			//Renderizar Resultado 
			$dataSession["notification"]	= $this->core_web_error->get_error($dataSession["user"]->userID);
			$dataSession["message"]			= $this->core_web_notification->get_message();
			$dataSession["head"]			= $this->load->view('app_invoice_price/news_head',$dataView,true);
			$dataSession["body"]			= $this->load->view('app_invoice_price/news_body',$dataView,true);
			$dataSession["script"]			= $this->load->view('app_invoice_price/news_script',$dataView,true);  
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
			//Obtener el componente Para mostrar la lista de Precios
			$objComponent		= $this->core_web_tools->getComponentIDBy_ComponentName("tb_list_price");
			if(!$objComponent)
			throw new Exception("00409 EL COMPONENTE 'tb_list_price' NO EXISTE...");
			
			
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
			$dataSession["head"]			= $this->load->view('app_invoice_price/list_head','',true);
			$dataSession["footer"]			= $this->load->view('app_invoice_price/list_footer','',true);
			$dataSession["body"]			= $dataViewRender; 
			$dataSession["script"]			= $this->load->view('app_invoice_price/list_script','',true).$this->core_web_javascript->createVar("componentID",$objComponent->componentID);  
			$this->load->view("core_masterpage/default_masterpage",$dataSession);		
		}
		catch(Exception $ex){
			show_error($ex->getLine()." ".$ex->getMessage() ,500 );
		}
	}
	
}
?>
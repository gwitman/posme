<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');



class App_Accounting_Currency extends CI_Controller {

	

    public function __construct() {

       parent::__construct();

    }	
  
	function process_view_report(){

		try{ 

			//AUTENTICADO

			if(!$this->core_web_authentication->isAuthenticated())

			throw new Exception(USER_NOT_AUTENTICATED);

			$dataSession		= $this->session->all_userdata();

			

			//PERMISO SOBRE LA FUNCION

			if(APP_NEED_AUTHENTICATION == true){

						$permited = false;

						$permited = $this->core_web_permission->urlPermited($this->router->class,"currency_core_function",$this->config->item('url_suffix'),$dataSession["menuTop"],$dataSession["menuLeft"],$dataSession["menuBodyReport"],$dataSession["menuBodyTop"],$dataSession["menuHiddenPopup"]);

						

						if(!$permited)

						throw new Exception(NOT_ACCESS_CONTROL);



			}	 

			

			$uri			= $this->uri->uri_to_assoc(3);						

			$startOn		= $uri["startOn"];

			$endOn			= $uri["endOn"];	

			$companyID 		= $dataSession["user"]->companyID;			

			

			//Cargar Libreria

			$this->load->library('core_web_pdf/src/EXTCezpdf.php');	

			$this->load->model('core/Company_Model');	

			$this->load->model('core/ExchangeRate_Model');						

			

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

			//Get Datos

			$objData		= $this->ExchangeRate_Model->getByCompanyAndDate($companyID,$startOn,$endOn);								

			//Set Columnas

			$objColumn 		= array('date'=>'Fecha','nameSource' => 'Moneda Local','ratio'=>'Equivale A','nameTarget' => 'Moneda Extranjera');

			//Set Nombre del Reporte

			$reportName		= "TASA DE CAMBIO";

			//Set Info File

			$pdf->addInfo(array('Title'=>$reportName,'Author'=>APP_NAME,'CreationDate'=>date('Y-m-d H:i:s')));

			//Set Titulo			

			$pdf->EXTCreateHeader($objCompany->name,$objComponent->componentID,$objParameter->value,$dataSession);

			$pdf->ezText("<b>".$reportName."</b>\n",FONT_SIZE,array('justification'=>'center'));

			$pdf->ezText("\n\n<b>DEL ".$startOn." AL ".$endOn."</b>\n",FONT_SIZE,array('justification'=>'center'));

			//Set Body

			$pdf->ezTable($objData, $objColumn,'', array('showHeadings'=>1,'shaded'=>0,'showLines'=>1, 'width'=>$width,'fontSize'=>FONT_SIZE));

			//Set Pie		

			$pdf->EXTCreateFooter();

			//OutPut

			$pdf->ezStream(array('Content-Disposition' => $reportName)); 

			

		}

		catch(Exception $ex){

			show_error($ex->getLine()." ".$ex->getMessage() ,500 );

		}

	}

	

	function process_file(){

		try{ 

			//AUTENTICADO

			if(!$this->core_web_authentication->isAuthenticated())
			throw new Exception(USER_NOT_AUTENTICATED);
			
			$dataSession		= $this->session->all_userdata();

			

			//PERMISO SOBRE LA FUNCION

			if(APP_NEED_AUTHENTICATION == true){

						$permited = false;

						$permited = $this->core_web_permission->urlPermited($this->router->class,"currency_core_function",$this->config->item('url_suffix'),$dataSession["menuTop"],$dataSession["menuLeft"],$dataSession["menuBodyReport"],$dataSession["menuBodyTop"],$dataSession["menuHiddenPopup"]);

						

						if(!$permited)
						throw new Exception(NOT_ACCESS_CONTROL);								

			

			}	

			

			$this->load->model('Company_Currency_Model');			

			$this->load->model('core/ExchangeRate_Model');						

			$this->load->model('core/Currency_Model');

			$this->load->library('core_web_csv/csvreader'); 			

		

			$companyID 		= $dataSession["user"]->companyID;	

			$objComponent	= $this->core_web_tools->getComponentIDBy_ComponentName("tb_company_currency");

			$filePath 		= PATH_FILE_OF_APP."/company_".$companyID."/component_".$objComponent->componentID."/component_item_0/";

			$fileName 		= $filePath.$this->input->post("fileName");

			date_default_timezone_set(APP_TIMEZONE); 

	

			//Existe el Archivo

			$fila = 0;

			if (!file_exists($fileName))
			throw new Exception("NO EXISTE EL ARCHIVO MENCIONADO");

			

			//Leer el Archivo					

			$csvTable 		= $this->csvreader->parse_file($fileName); 

			$lineNumber		= 0;
			log_message("ERROR","punto de interrupcion lineNumber");
			foreach ($csvTable as $csvRow) 
			{	

				$lineNumber++;

				if(

					!isset($csvRow["FECHA"])  || empty($csvRow["FECHA"]) || is_null($csvRow["FECHA"]) || 

					!isset($csvRow["MONEDA_LOCAL"])  || empty($csvRow["MONEDA_LOCAL"]) || is_null($csvRow["MONEDA_LOCAL"]) || 

					!isset($csvRow["TASA_DE_CAMBIO"])   || empty($csvRow["TASA_DE_CAMBIO"]) || is_null($csvRow["TASA_DE_CAMBIO"]) || 

					!isset($csvRow["MONEDA_EXTRANJERA"])   || empty($csvRow["MONEDA_EXTRANJERA"]) || is_null($csvRow["MONEDA_EXTRANJERA"]) || 

					!isset($csvRow["TIPO"])  || empty($csvRow["TIPO"]) || is_null($csvRow["TIPO"]) 

				)
				throw new Exception("REVISAR LINEA ".$lineNumber);

				
				log_message("ERROR","punto de interrupcion 231");
				$date 				= date($csvRow["FECHA"]);

				$nameSource 		= $csvRow["MONEDA_LOCAL"];

				$objCurrencySource 	= $this->Currency_Model->get_rowName($nameSource);

				$value 				= $csvRow["TASA_DE_CAMBIO"];

				$nameTarget 		= $csvRow["MONEDA_EXTRANJERA"];

				$objCurrencyTarget 	= $this->Currency_Model->get_rowName($nameTarget);

				$type 				= $csvRow["TIPO"];

				

				
				log_message("ERROR","punto de interrupcion 250");
				//Validar Fecha					

				try{

					$date = date_format(date_create($date),"Y-m-d");					

				}

				catch(Exception $e){

					throw new Exception("FECHA TIENE FORMATO (YYYY-MM-DD) INCORRECTO REVISAR LINEA ".$lineNumber);

				}

				
				log_message("ERROR","punto de interrupcion 265");
				if($date == "0000-00-00")
				throw new Exception("FECHA TIENE FORMATO (YYYY-MM-DD) INCORRECTO REVISAR LINEA ".$lineNumber);

				 

				//Validar Mas Campos

				if(empty($value) || $value == 0)
				throw new Exception("TASA DE CAMBIO NO PUEDE SER 0 REVISAR LINEA ".$lineNumber);						

				if(!$objCurrencySource)
				throw new Exception("MONEDA LOCAL NO EXISTE REVISAR LINEA ".$lineNumber);

				if(!$objCurrencyTarget)
				throw new Exception("MONEDA EXTRANJERA NO EXISTE REVISAR LINEA  ".$lineNumber);						

				
				log_message("ERROR","punto de interrupcion 283");
				$ratio				= $value;

				$currencyIDSource	= $objCurrencySource->currencyID;

				$currencyIDTarget	= $objCurrencyTarget->currencyID;

				

				

				//Buscar si existe La Tasa de Cambio
				log_message("ERROR","punto de interrupcion buscar si existe la tasa de cambio 001");
				$objExchangeRate = $this->ExchangeRate_Model->get_rowByPK($companyID,$date,$currencyIDSource,$currencyIDTarget);
				log_message("ERROR","punto de interrupcion fecha:".$date);
				log_message("ERROR","punto de interrupcion currencyOrigen:".$currencyIDSource);
				log_message("ERROR","punto de interrupcion currencyTarget:".$currencyIDTarget);
				log_message("ERROR","punto de interrupcion currencyTarget:".print_r($objExchangeRate,true));

				//Actualizar

				if($objExchangeRate){					

					log_message("ERROR","punto de interrupcion actualizar tasa de cambio");
					$data["ratio"] = $ratio;
					$this->ExchangeRate_Model->update($companyID,$date,$currencyIDSource,$currencyIDTarget,$data);

					

				}
				//Insertar
				else{
					log_message("ERROR","punto de interrupcion insertar tasa de cambio");
					$data["companyID"] 			= $companyID;

					$data["date"] 				= $date;

					$data["currencyID"] 		= $currencyIDSource;

					$data["targetCurrencyID"] 	= $currencyIDTarget;

					$data["ratio"] 				= $ratio;					

					$this->ExchangeRate_Model->insert($data);

				}

				

			}

			

			

			$this->output->set_content_type('application/json');

			$this->output->set_output(json_encode(array(

				'error'   => false,

				'message' => SUCCESS

			)));

			$this->core_web_notification->set_message(false,SUCCESS);

		

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

	

	function process(){

		try{ 

			//AUTENTICADO		

			if(!$this->core_web_authentication->isAuthenticated())

			throw new Exception(USER_NOT_AUTENTICATED);

			$dataSession		= $this->session->all_userdata();

			

			//PERMISO SOBRE LA FUNCTION

			if(APP_NEED_AUTHENTICATION == true){

						$permited = false;

						$permited = $this->core_web_permission->urlPermited($this->router->class,"currency_core_function",$this->config->item('url_suffix'),$dataSession["menuTop"],$dataSession["menuLeft"],$dataSession["menuBodyReport"],$dataSession["menuBodyTop"],$dataSession["menuHiddenPopup"]);

						

					

						if(!$permited)

						throw new Exception(NOT_ACCESS_CONTROL);

			

			}	

			

			//Renderizar Resultado 

			$dataSession["notification"]	= $this->core_web_error->get_error($dataSession["user"]->userID);

			$dataSession["message"]			= $this->core_web_notification->get_message();

			$dataSession["head"]			= $this->load->view('app_account_currency/process_head','',true);

			$dataSession["body"]			= $this->load->view('app_account_currency/process_body','',true);

			$dataSession["script"]			= $this->load->view('app_account_currency/process_script','',true);  

			$dataSession["footer"]			= "";

			$this->load->view("core_masterpage/default_masterpage",$dataSession);	

			

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

			

			//PERMISO SOBRE LA FUNCITON

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

			$this->load->model("Company_Currency_Model"); 			

			$this->load->model("core/Currency_Model");

			$this->load->model("core/User_Permission_Model");

			$this->load->model("core/Role_Autorization_Model");

			

			

			//Redireccionar datos

			$uri			= $this->uri->uri_to_assoc(3);						

			$companyID		= $uri["companyID"];

			$currencyID		= $uri["currencyID"];	

			$branchID 		= $dataSession["user"]->branchID;

			$roleID 		= $dataSession["role"]->roleID;			

			if((!$companyID || !$currencyID))

			{ 

				redirect('app_accounting_currency/add');	

			} 		

			

			

			//Obtener el Registro			

			$datView["objCompanyCurrency"]	= $this->Company_Currency_Model->get_rowByPK($companyID,$currencyID);			

			$datView["objListCurrency"] 	= $this->Currency_Model->getList();

			

			

			//Obtener los Permisos Core

			$datView["objUserPermission"]			= $this->User_Permission_Model->get_rowByCompanyIDyBranchIDyRoleID($companyID,$branchID,$roleID);

			//Obtener las Autorization Core

			$datView["listComponentAutoriation"]	= $this->Role_Autorization_Model->get_rowByRoleAutorization($companyID,$branchID,$roleID);

			

			

			//Renderizar Resultado

			$dataSession["notification"]	= $this->core_web_error->get_error($dataSession["user"]->userID);

			$dataSession["message"]			=  $this->core_web_notification->get_message();

			$dataSession["head"]			= $this->load->view('app_account_currency/edit_head',$datView,true);

			$dataSession["body"]			= $this->load->view('app_account_currency/edit_body',$datView,true);

			$dataSession["script"]			= $this->load->view('app_account_currency/edit_script',$datView,true);  

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

			$this->load->model("Company_Currency_Model");  

			

			//Nuevo Registro

			$companyID 		= $this->input->post("companyID");

			$currencyID 	= $this->input->post("currencyID");				

			

			if((!$companyID && !$currencyID)){

					throw new Exception(NOT_PARAMETER);			

					 

			} 			

		

			//Eliminar el Registro

			$this->Company_Currency_Model->delete($companyID,$currencyID);

					

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

			//AUTENTICADO

			if(!$this->core_web_authentication->isAuthenticated())

			throw new Exception(USER_NOT_AUTENTICATED);

			$dataSession		= $this->session->all_userdata();

			

			//Load Modelos			

			//

			////////////////////////////////////////

			////////////////////////////////////////

			////////////////////////////////////////						

			$this->load->model("Company_Currency_Model");

			//Validar Formulario						

			$this->form_validation->set_rules("txtCurrencyID","Currency","required");    

			$this->form_validation->set_rules("txtSimb","Simb","required|max_length[5]|min_length[1]");

			

			 

			//Nuevo Registro			

			$continue	= true;

			if( $method == "new" && $this->form_validation->run() == true ){

					

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

					

					//Ingresar Currency

					if($continue){

						$this->db->trans_begin();

						//Crear Cuenta

						$obj["companyID"]			= $dataSession["user"]->companyID;

						$obj["currencyID"] 			= $this->input->post("txtCurrencyID");

						$obj["simb"] 				= $this->input->post("txtSimb");

						

						

						$result						= $this->Company_Currency_Model->insert($obj);

						$companyID 					= $obj["companyID"];

						$currencyID 				= $obj["currencyID"];

						

						if($this->db->trans_status() !== false){

							$this->db->trans_commit();						

							$this->core_web_notification->set_message(false,SUCCESS);

							redirect('app_accounting_currency/edit/companyID/'.$companyID."/currencyID/".$currencyID);						

						}

						else{

							$this->db->trans_rollback();						

							$this->core_web_notification->set_message(true,$this->db->_error_message());

							redirect('app_accounting_currency/add');	

						}

					}

					else{

						redirect('app_accounting_currency/add');	

					}

					

					 

			} 

			//Editar Registro

			else if( $this->form_validation->run() == true) {

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

					 

					

					

					if($continue){

						$this->db->trans_begin();

						

						//Actualizar Rol

						$companyID 			= $this->input->post("txtCompanyID");

						$currencyID 		= $this->input->post("txtCurrencyID");

						$obj["simb"] 		= $this->input->post("txtSimb");					

						$result 			= $this->Company_Currency_Model->update($companyID,$currencyID,$obj);						

						

						if($this->db->trans_status() !== false){

							$this->db->trans_commit();

							$this->core_web_notification->set_message(false,SUCCESS);

						}

						else{

							$this->db->trans_rollback();						

							$this->core_web_notification->set_message(true,$this->db->_error_message());

						}

						redirect('app_accounting_currency/edit/companyID/'.$companyID."/currencyID/".$currencyID);

					}					

					else{

						redirect('app_accounting_currency/add');	

					}

			}  

			else{

				$stringValidation = str_replace("\n","",validation_errors());								

				$this->core_web_notification->set_message(true,$stringValidation);

				redirect('app_accounting_currency/add');	

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

			

			$this->load->model("core/Currency_Model");  

			$dataView["objListCurrency"] = $this->Currency_Model->getList();

			

			//Renderizar Resultado 

			$dataSession["notification"]	= $this->core_web_error->get_error($dataSession["user"]->userID);

			$dataSession["message"]			= $this->core_web_notification->get_message();

			$dataSession["head"]			= $this->load->view('app_account_currency/news_head',$dataView,true);

			$dataSession["body"]			= $this->load->view('app_account_currency/news_body',$dataView,true);

			$dataSession["script"]			= $this->load->view('app_account_currency/news_script',$dataView,true);  

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

			

			

			//Obtener el componente Para mostrar la lista de CompanyCurrency

			$objComponent		= $this->core_web_tools->getComponentIDBy_ComponentName("tb_company_currency");

			if(!$objComponent)

			throw new Exception("00409 EL COMPONENTE 'CompanyCurrency' NO EXISTE...");

			

			

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

			$dataView["componentID"]		= $objComponent->componentID;

			$dataSession["message"]			= $this->core_web_notification->get_message();

			$dataSession["head"]			= $this->load->view('app_account_currency/list_head',$dataView,true);

			$dataSession["footer"]			= $this->load->view('app_account_currency/list_footer','',true);

			$dataSession["body"]			= $dataViewRender; 

			$dataSession["script"]			= $this->load->view('app_account_currency/list_script','',true).$this->core_web_javascript->createVar("componentID",$objComponent->componentID);  

			$this->load->view("core_masterpage/default_masterpage",$dataSession);		

		}

		catch(Exception $ex){

			show_error($ex->getLine()." ".$ex->getMessage() ,500 );

		}

	}	

	

}

?>
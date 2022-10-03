<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class App_Accounting_Journal extends CI_Controller {
	
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
			$this->load->model("Journal_Entry_Model"); 
			$this->load->model("Journal_Entry_Detail_Model"); 			
			$this->load->model("Company_Currency_Model");
			$this->load->model("Account_Model");
			$this->load->model("Center_Cost_Model");			
			$this->load->model("core/User_Permission_Model");
			$this->load->model("core/Role_Autorization_Model");
			
			
			//Redireccionar datos
			$uri			= $this->uri->uri_to_assoc(3);
						
			$companyID		= $uri["companyID"];
			$journalEntryID	= $uri["journalEntryID"];	
			$branchID 		= $dataSession["user"]->branchID;
			$roleID 		= $dataSession["role"]->roleID;			
			if((!$companyID || !$journalEntryID))
			{ 
				redirect('app_accounting_journal/add');	
			} 		
			
			
			//Obtener el Registro			
			$datView["objJournalEntry"]	 			= $this->Journal_Entry_Model->get_rowByPK($companyID,$journalEntryID);
			$datView["objJournalEntryDetail"]		= $this->Journal_Entry_Detail_Model->get_rowByJournalEntryID($companyID,$journalEntryID);
			$datView["objNextJournal"]			= $this->Journal_Entry_Model->get_rowByPK_Next($companyID,$journalEntryID);						$datView["objBackJournal"]			= $this->Journal_Entry_Model->get_rowByPK_Back($companyID,$journalEntryID);
			//Obtener los Permisos Core
			$datView["objUserPermission"]			= $this->User_Permission_Model->get_rowByCompanyIDyBranchIDyRoleID($companyID,$branchID,$roleID);
			//Obtener las Autorization Core
			$datView["listComponentAutoriation"]	= $this->Role_Autorization_Model->get_rowByRoleAutorization($companyID,$branchID,$roleID);
			
			//Obtener el componente Para mostrar la lista de AccountType
			$objComponent							= $this->core_web_tools->getComponentIDBy_ComponentName("tb_journal_entry");
			if(!$objComponent)
			throw new Exception("00409 EL COMPONENTE 'tb_journal_entry' NO EXISTE...");
			
			//Obtener el componente de Item
			$objComponentAccount					= $this->core_web_tools->getComponentIDBy_ComponentName("tb_account");
			if(!$objComponentAccount)
			throw new Exception("EL COMPONENTE 'tb_account' NO EXISTE...");
			
			//Obtener Informacion
			$objCurrency						= $this->core_web_currency->getCurrencyDefault($companyID);
			$targetCurrency						= $this->core_web_currency->getCurrencyReport($companyID);			
			$datView["objExchangeRate"]			= $this->core_web_currency->getRatio($companyID,$datView["objJournalEntry"]->journalDate,1,$targetCurrency->currencyID,$objCurrency->currencyID);
			$datView["objCurrency"]				= $objCurrency;
			$datView["objListWorkflowStage"]	= $this->core_web_workflow->getWorkflowStageByStageInit("tb_journal_entry","statusID",$datView["objJournalEntry"]->statusID,$companyID,$branchID,$roleID);
			$datView["objListJournalType"]		= $this->core_web_catalog->getCatalogAllItem("tb_journal_entry","journalTypeID",$companyID);
			$datView["objListCurrency"]			= $this->Company_Currency_Model->getByCompany($companyID);
			$datView["objListAccount"]			= $this->Account_Model->getByCompanyOperative($companyID);
			$datView["objListClass"]			= $this->Center_Cost_Model->getByCompany($companyID);			
			$datView["objComponent"] 			= $objComponent;
			$datView["componentAccountID"] 		= $objComponentAccount->componentID;						
			
			
			//Formato
			$datView["objJournalEntry"]->journalDate 		= date_format(date_create($datView["objJournalEntry"]->journalDate),"Y-m-d");
			
			//Renderizar Resultado
			$dataSession["notification"]	= $this->core_web_error->get_error($dataSession["user"]->userID);
			$dataSession["message"]			=  $this->core_web_notification->get_message();
			$dataSession["head"]			= $this->load->view('app_account_journal/edit_head',$datView,true);
			$dataSession["body"]			= $this->load->view('app_account_journal/edit_body',$datView,true);
			$dataSession["script"]			= $this->load->view('app_account_journal/edit_script',$datView,true);  
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
			$this->load->model("Journal_Entry_Model");  
			$this->load->model("Component_Cycle_Model");  
			$this->load->model("Transaction_Master_Model");
			
			//Nuevo Registro
			$companyID 			= $this->input->post("companyID");
			$journalEntryID 	= $this->input->post("journalEntryID");				
			
			if((!$companyID && !$journalEntryID)){
					throw new Exception(NOT_PARAMETER);			
					 
			} 
			
			//OBTENER EL COMPROBANTE
			$obj 			= $this->Journal_Entry_Model->get_rowByPK($companyID,$journalEntryID);	
			//PERMISO SOBRE EL REGISTRO
			if ($resultPermission 	== PERMISSION_ME && ($obj->createdBy != $dataSession["user"]->userID))
			throw new Exception(NOT_DELETE);
			//PERMISO PUEDE ELIMINAR EL REGISTRO SEGUN EL WORKFLOW
			if(!$this->core_web_workflow->validateWorkflowStage("tb_journal_entry","statusID",$obj->statusID,COMMAND_ELIMINABLE,$dataSession["user"]->companyID,$dataSession["user"]->branchID,$dataSession["role"]->roleID))
			throw new Exception(NOT_WORKFLOW_DELETE);
			//SI EL CICLO YA ESTA CERRADO EL COMPROBANTE NO PUEDE SER ELIMINADO			
			$objx = $this->core_web_accounting->cycleIsCloseByID($companyID,$obj->accountingCycleID);
			if($objx)
			throw new Exception("EL CICLO ESTA CERRADO, EL COMPROBANTE NO PUEDE SER EDITADO");

			//inicio de transaccion
			$this->db->trans_begin();
			if($obj->isModule == 1)
			{
				$objTM						= $this->Transaction_Master_Model->get_rowByPK($obj->companyID,$obj->transactionID,$obj->transactionMasterID);
				$objTM["journalEntryID"]	= 0;
				$this->Transaction_Master_Model->update($obj->companyID,$obj->transactionID,$obj->transactionMasterID,$objTM);
			}
			//Eliminar el Registro
			$this->Journal_Entry_Model->delete($companyID,$journalEntryID);			
			
			//fin de transaccion
			if($this->db->trans_status() !== false){
				$this->db->trans_commit();						
			}
			else{
				$this->db->trans_rollback();
				throw new Exception("Error al intentar anular el comprobante.");
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
	
	function save($method = NULL){
		 try{ 
			//AUTENTICADO
			if(!$this->core_web_authentication->isAuthenticated())
			throw new Exception(USER_NOT_AUTENTICATED);
			$dataSession		= $this->session->all_userdata();
			 
			
		
			//Validar Formulario						
			$this->form_validation->set_rules("txtDate","Date","required");
			$this->form_validation->set_rules("txtEntryName","EntryName","required");
			$this->form_validation->set_rules("txtStatusID","Status","required");
			$this->form_validation->set_rules("txtJournalType","JournalType","required");
			$this->form_validation->set_rules("txtCurrencyID","Currency","required");
			
			 	
			//Load Modelos			
			//
			////////////////////////////////////////
			////////////////////////////////////////
			////////////////////////////////////////
			$this->load->model("Journal_Entry_Model");
			$this->load->model("Journal_Entry_Detail_Model");	
			$this->load->model("Component_Cycle_Model");	
			$this->load->model("Company_Currency_Model");						$this->load->model('core/Bd_Model');			$this->load->model('core/Log_Model');

			//Obtener el componente Para mostrar la lista de AccountType
			$objComponent							= $this->core_web_tools->getComponentIDBy_ComponentName("tb_journal_entry");
			if(!$objComponent)
			throw new Exception("00409 EL COMPONENTE 'tb_journal_entry' NO EXISTE...");
			
			$objCurrency							= $this->core_web_currency->getCurrencyDefault($dataSession["user"]->companyID);
			$targetCurrency							= $this->core_web_currency->getCurrencyReport($dataSession["user"]->companyID);
			
			
			//Nuevo Registro			
			$continue	= true;			if($method == "new" && $this->input->post("txtTemplatedNumber") != "" )			{				$journalEntryIDTemplated 	= $this->input->post("txtTemplatedNumber");				$journalEntryID				= 0;				$companyID					= $dataSession["user"]->companyID;				$branchID					= $dataSession["user"]->branchID;				$loginID					= $dataSession["user"]->userID;				$app						= "PR_SELECTED_TEMPLATED";								log_message("ERROR","Crear comprobante a partir de una plantilla");				//ejecutar procedimiento.				$query					= "SET @resultTransaction 	= '0';CALL pr_accounting_templated_to_journal('".$companyID."','".$branchID."','".$loginID."','".$app."','".$journalEntryIDTemplated."',@resultTransaction);SELECT @resultTransaction as codigo;";				$resultTransaction		= $this->Bd_Model->executeProcedureMultiQuery($query);				$journalEntryID			= $resultTransaction[2][0]["codigo"];				$logDB					= $this->Log_Model->get_rowByPK($companyID,$branchID,$loginID,$app);				log_message("ERROR","Resultado del proceso.");				log_message("ERROR",print_r($journalEntryID,true));								//redireccionar a editar.				$this->core_web_notification->set_message(false,SUCCESS);				redirect('app_accounting_journal/edit/companyID/'.$companyID."/journalEntryID/".$journalEntryID);													}
			else if( $method == "new" && $this->form_validation->run() == true ){
					
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
					if($continue){
						$this->db->trans_begin();
						//Obtener Ciclo
						$objCycle											= $this->Component_Cycle_Model->get_rowByCompanyIDFecha($dataSession["user"]->companyID,$this->input->post("txtDate"));
						if(!$objCycle)
						throw new Exception("TODO COMPROBANTE DEBE DE PERTENECER A UN CICLO");
						
						//Obtener la tasa de Cambio
						$objCurrency						= $this->core_web_currency->getCurrencyDefault($dataSession["user"]->companyID);
						$targetCurrency						= $this->core_web_currency->getCurrencyReport($dataSession["user"]->companyID);			
						$exchangeRate						= $this->core_web_currency->getRatio($dataSession["user"]->companyID,$this->input->post("txtDate"),1,$targetCurrency->currencyID,$this->input->post("txtCurrencyID"));			
						
						if(!$exchangeRate)
							throw new Exception("NO EXISTE LA TASA DE CAMBIO PARA:".$this->input->post("txtDate"));
							
							
						//No puede agregar comprobantes en un ciclo cerrado						
						$objx = $this->core_web_accounting->cycleIsCloseByID($dataSession["user"]->companyID,$objCycle->componentCycleID);
						if($objx)
						throw new Exception("EL CICLO ESTA CERRADO, EL COMPROBANTE NO PUEDE SER AGREGADO");
						
			
						//Crear Cuenta
						$objJournalEntry["companyID"]						= $dataSession["user"]->companyID;
						$objJournalEntry["journalNumber"] 					= $this->core_web_counter->goNextNumber($dataSession["user"]->companyID,$dataSession["user"]->branchID,"tb_journal_entry",$this->input->post("txtJournalType"));
						$objJournalEntry["entryName"] 						= $this->input->post("txtEntryName");
						$objJournalEntry["journalDate"] 					= $this->input->post("txtDate");
						$objJournalEntry["tb_exchange_rate"] 				= $exchangeRate;
						$objJournalEntry["isActive"] 						= 1;
						$objJournalEntry["isApplied"] 						= 0;
						$objJournalEntry["statusID"] 						= $this->input->post("txtStatusID");
						$objJournalEntry["note"] 							= $this->input->post("txtNote");
						$objJournalEntry["reference1"] 						= $this->input->post("txtReference1");
						$objJournalEntry["reference2"] 						= $this->input->post("txtReference2");
						$objJournalEntry["reference3"] 						= $this->input->post("txtReference3");
						$objJournalEntry["journalTypeID"] 					= $this->input->post("txtJournalType");
						$objJournalEntry["currencyID"] 						= $this->input->post("txtCurrencyID");
						$objJournalEntry["accountingCycleID"] 				= $objCycle->componentCycleID;
						$this->core_web_auditoria->setAuditCreated($objJournalEntry,$dataSession);
						
						$journalEntryID						= $this->Journal_Entry_Model->insert($objJournalEntry);
						$companyID 							= $objJournalEntry["companyID"];

						//Crear la Carpeta para almacenar los Archivos del Comprobante
						mkdir(PATH_FILE_OF_APP."/company_".$companyID."/component_".$objComponent->componentID."/component_item_".$journalEntryID, 0700);
						
						
						//Guardar el Detalle
						$objListJournalEntryDetailAccount	= $this->input->post("txtAccountID");
						$objListJournalEntryDetailClass		= $this->input->post("txtClassID");
						$objListJournalEntryDetailDebit		= $this->input->post("txtDebit");
						$objListJournalEntryDetailCredit	= $this->input->post("txtCredit");
					
						if($objListJournalEntryDetailAccount)
						foreach($objListJournalEntryDetailAccount as $key => $value){
							$objJournalEntryDetailID["companyID"] 			= $companyID;
							$objJournalEntryDetailID["journalEntryID"] 		= $journalEntryID;
							$objJournalEntryDetailID["accountID"] 			= $value;
							$objJournalEntryDetailID["isActive"] 			= 1;
							$objJournalEntryDetailID["classID"] 			= $objListJournalEntryDetailClass[$key];
							$objJournalEntryDetailID["debit"] 				= helper_StringToNumber($objListJournalEntryDetailDebit[$key]);
							$objJournalEntryDetailID["credit"] 				= helper_StringToNumber($objListJournalEntryDetailCredit[$key]);
							$objJournalEntryDetailID["note"] 				= "";
							$objJournalEntryDetailID["isApplied"] 			= 0;
							$objJournalEntryDetailID["branchID"] 			= $dataSession["user"]->branchID;
							$objJournalEntryDetailID["tb_exchange_rate"] 	= $exchangeRate;
							
							$this->Journal_Entry_Detail_Model->insert($objJournalEntryDetailID);
						}
						
						if($this->db->trans_status() !== false){
							$this->db->trans_commit();						
							$this->core_web_notification->set_message(false,SUCCESS);
							redirect('app_accounting_journal/edit/companyID/'.$companyID."/journalEntryID/".$journalEntryID);						
						}
						else{
							$this->db->trans_rollback();						
							$this->core_web_notification->set_message(true,$this->db->_error_message());
							redirect('app_accounting_journal/add');	
						}
					}
					else{
						redirect('app_accounting_journal/add');	
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
					 
					//PERMISO SOBRE EL REGISTRO
					$messageTmp			= '';
					$companyID 			= $dataSession["user"]->companyID;
					$journalEntryID		= $this->input->post("txtJournalEntryID");
					$objOld = $this->Journal_Entry_Model->get_rowByPK($companyID,$journalEntryID);
					if ($resultPermission 	== PERMISSION_ME && ($objOld->createdBy != $dataSession["user"]->userID))
					throw new Exception(NOT_EDIT);
			
					//PERMISO PUEDE EDITAR EL REGISTRO
					if(!$this->core_web_workflow->validateWorkflowStage("tb_journal_entry","statusID",$objOld->statusID,COMMAND_EDITABLE_TOTAL,$dataSession["user"]->companyID,$dataSession["user"]->branchID,$dataSession["role"]->roleID))
					throw new Exception(NOT_WORKFLOW_EDIT);					
					
					//NO PUEDE EDITAR UN COMPROBANTE QUE PERTENECE A UN CICLO CERRADO
					$objx = $this->core_web_accounting->cycleIsCloseByID($companyID,$objOld->accountingCycleID);
					if($objx)
					throw new Exception("EL CICLO ESTA CERRADO, EL COMPROBANTE NO PUEDE SER EDITADO");
					
					
					if($continue){
						$this->db->trans_begin();
						
						if(!$this->core_web_workflow->validateWorkflowStage("tb_journal_entry","statusID",$objOld->statusID,COMMAND_EDITABLE,$dataSession["user"]->companyID,$dataSession["user"]->branchID,$dataSession["role"]->roleID))
						{
							$companyID 			= $dataSession["user"]->companyID;
							$journalEntryID		= $this->input->post("txtJournalEntryID");
							$objJournalEntryOld	= $this->Journal_Entry_Model->get_rowByPK($companyID,$journalEntryID);
							
							
							//Obtener Ciclo
							$objCycle												= $this->Component_Cycle_Model->get_rowByCompanyIDFecha($dataSession["user"]->companyID,$this->input->post("txtDate"));
							if(!$objCycle)
							throw new Exception("TODO COMPROBANTE DEBE DE PERTENECER A UN CICLO");
							
							//Obtener la tasa de Cambio
							$objCurrency						= $this->core_web_currency->getCurrencyDefault($dataSession["user"]->companyID);
							$targetCurrency						= $this->core_web_currency->getCurrencyReport($dataSession["user"]->companyID);									
							$exchangeRate						= $this->core_web_currency->getRatio($companyID,$this->input->post("txtDate"),1,$targetCurrency->currencyID,$this->input->post("txtCurrencyID"));			
							
							if(!$exchangeRate)
							throw new Exception("NO EXISTE LA TASA DE CAMBIO PARA:".$this->input->post("txtDate"));
							
							//validar si puede guardar el comprobante en la fecha celeccionado 
							$objx = $this->core_web_accounting->cycleIsCloseByID($companyID,$objCycle->componentCycleID);
							if($objx)
							throw new Exception("EL CICLO DESTINO ESTA CERRADO, EL COMPROBANTE NO PUEDE SER EDITADO");
			
							//Actualizar Cuenta
							$objJournalEntryNew["entryName"] 						= $this->input->post("txtEntryName");
							$objJournalEntryNew["journalDate"] 						= $this->input->post("txtDate");
							$objJournalEntryNew["tb_exchange_rate"]					= $exchangeRate;
							$objJournalEntryNew["isActive"] 						= 1;
							$objJournalEntryNew["isApplied"] 						= 0;
							$objJournalEntryNew["statusID"] 						= $this->input->post("txtStatusID");
							$objJournalEntryNew["note"] 							= $this->input->post("txtNote");
							$objJournalEntryNew["reference1"] 						= $this->input->post("txtReference1");
							$objJournalEntryNew["reference2"] 						= $this->input->post("txtReference2");
							$objJournalEntryNew["reference3"] 						= $this->input->post("txtReference3");
							$objJournalEntryNew["journalTypeID"] 					= $this->input->post("txtJournalType");
							$objJournalEntryNew["currencyID"] 						= $this->input->post("txtCurrencyID");														$objJournalEntryNew["isTemplated"] 						= $this->input->post("txtIsTemplated");														$objJournalEntryNew["titleTemplated"] 					= $this->input->post("txtTitleTemplated");
							$objJournalEntryNew["accountingCycleID"] 				= $objCycle->componentCycleID;
							//Actualizar Objeto
							$row_affected 	= $this->Journal_Entry_Model->update($companyID,$journalEntryID,$objJournalEntryNew);
							
							
							
							//Guardar el Detalle
							$objListJournalEntryDetailID		= $this->input->post("txtJournalEntryDetailID");
							$objListJournalEntryDetailAccount	= $this->input->post("txtAccountID");
							$objListJournalEntryDetailClass		= $this->input->post("txtClassID");
							$objListJournalEntryDetailDebit		= $this->input->post("txtDebit");
							$objListJournalEntryDetailCredit	= $this->input->post("txtCredit");
						
							//Eliminar Los detalle que no estan
							$this->Journal_Entry_Detail_Model->deleteWhereIDNotIn($companyID,$journalEntryID,$objListJournalEntryDetailID);
							
							$debitTotal 	= 0;
							$creditTotal 	= 0;
							if($objListJournalEntryDetailAccount)
							foreach($objListJournalEntryDetailAccount as $key => $value){
								
								$objJournalEntryDetailID["accountID"] 			= $value;
								$objJournalEntryDetailID["isActive"] 			= 1;
								$objJournalEntryDetailID["classID"] 			= $objListJournalEntryDetailClass[$key];
								$objJournalEntryDetailID["debit"] 				= helper_StringToNumber($objListJournalEntryDetailDebit[$key]);
								$objJournalEntryDetailID["credit"] 				= helper_StringToNumber($objListJournalEntryDetailCredit[$key]);
								$objJournalEntryDetailID["note"] 				= "";
								$objJournalEntryDetailID["isApplied"] 			= 0;
								$objJournalEntryDetailID["branchID"] 			= $dataSession["user"]->branchID;
								$objJournalEntryDetailID["tb_exchange_rate"] 	= $exchangeRate;
								$journalEntryDetailID 							= $objListJournalEntryDetailID[$key];
								$debitTotal 									= $debitTotal + $objJournalEntryDetailID["debit"];
								$creditTotal 									= $creditTotal + $objJournalEntryDetailID["credit"];
								
								if($journalEntryDetailID)
									$this->Journal_Entry_Detail_Model->update($companyID,$journalEntryID,$journalEntryDetailID,$objJournalEntryDetailID);
								else{
									$objJournalEntryDetailID["companyID"] 			= $companyID;
									$objJournalEntryDetailID["journalEntryID"] 		= $journalEntryID;
									$this->Journal_Entry_Detail_Model->insert($objJournalEntryDetailID);
								}
							}
							
							//Actualizar Maestro 
							$objJournalEntryNew["debit"] 	= $debitTotal;
							$objJournalEntryNew["credit"] 	= $creditTotal;
							$row_affected 	= $this->Journal_Entry_Model->update($companyID,$journalEntryID,$objJournalEntryNew);
						}
						else{
							$companyID 						= $dataSession["user"]->companyID;
							$journalEntryID					= $this->input->post("txtJournalEntryID");
							$objJournalEntryNew["statusID"] = $this->input->post("txtStatusID");							
							$row_affected 					= $this->Journal_Entry_Model->update($companyID,$journalEntryID,$objJournalEntryNew);
							$messageTmp						= "EL REGISTRO FUE EDITADO PARCIALMENTE, POR LA CONFIGURACION DE SU ESTADO ACTUAL";
						}
						
						//AUDITORIA
						$objNew = $this->Journal_Entry_Model->get_rowByPK($companyID,$journalEntryID);
						$this->core_web_auditoria->setAudit("tb_journal_entry",$objOld,$objNew,$dataSession);
						
						//CREAR LA CARPETA
						$pathFile = PATH_FILE_OF_APP."/company_".$companyID."/component_".$objComponent->componentID."/component_item_".$journalEntryID;
						if(!file_exists($pathFile))
						mkdir($pathFile, 0700);
						
						if($this->db->trans_status() !== false){
							$this->db->trans_commit();
							$this->core_web_notification->set_message(false,SUCCESS." ".$messageTmp);
						}
						else{
							$this->db->trans_rollback();						
							$this->core_web_notification->set_message(true,$this->db->_error_message());
						}
						redirect('app_accounting_journal/edit/companyID/'.$companyID."/journalEntryID/".$journalEntryID);
					}					
					else{
						redirect('app_accounting_journal/add');	
					}
			}  
			else{
				$stringValidation = str_replace("\n","",validation_errors());								
				$this->core_web_notification->set_message(true,$stringValidation);
				redirect('app_accounting_journal/add');	
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
			 
			$this->load->model("Company_Currency_Model");
			$this->load->model("Account_Model");
			$this->load->model("Center_Cost_Model");
			$dataView							= null;
			
			//Obtener el componente de Item
			$objComponent		= $this->core_web_tools->getComponentIDBy_ComponentName("tb_account");
			if(!$objComponent)
			throw new Exception("EL COMPONENTE 'tb_account' NO EXISTE...");
			
			//Obtener Tasa de Cambio			
			$companyID 							= $dataSession["user"]->companyID;
			$branchID 							= $dataSession["user"]->branchID;
			$roleID 							= $dataSession["role"]->roleID;
			$objCurrency						= $this->core_web_currency->getCurrencyDefault($companyID);
			$targetCurrency						= $this->core_web_currency->getCurrencyReport($companyID);			
			$dataView["componentAccountID"] 	= $objComponent->componentID;
			$dataView["exchangeRate"]			= $this->core_web_currency->getRatio($companyID,date("Y-m-d"),1,$targetCurrency->currencyID,$objCurrency->currencyID);
			$dataView["objCurrency"]			= $objCurrency;
			$dataView["objListWorkflowStage"]	= $this->core_web_workflow->getWorkflowInitStage("tb_journal_entry","statusID",$companyID,$branchID,$roleID);
			$dataView["objListJournalType"]		= $this->core_web_catalog->getCatalogAllItem("tb_journal_entry","journalTypeID",$companyID);
			$dataView["objListCurrency"]		= $this->Company_Currency_Model->getByCompany($companyID);
			$dataView["objListAccount"]			= $this->Account_Model->getByCompanyOperative($companyID);
			$dataView["objListClass"]			= $this->Center_Cost_Model->getByCompany($companyID);
			
			//Renderizar Resultado 
			$dataSession["notification"]	= $this->core_web_error->get_error($dataSession["user"]->userID);
			$dataSession["message"]			= $this->core_web_notification->get_message();
			$dataSession["head"]			= $this->load->view('app_account_journal/news_head',$dataView,true);
			$dataSession["body"]			= $this->load->view('app_account_journal/news_body',$dataView,true);
			$dataSession["script"]			= $this->load->view('app_account_journal/news_script',$dataView,true);  
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
			$objComponent		= $this->core_web_tools->getComponentIDBy_ComponentName("tb_journal_entry");
			if(!$objComponent)
			throw new Exception("00409 EL COMPONENTE 'tb_journal_entry' NO EXISTE...");
			
			
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
			$dataSession["head"]			= $this->load->view('app_account_journal/list_head','',true);
			$dataSession["footer"]			= $this->load->view('app_account_journal/list_footer','',true);
			$dataSession["body"]			= $dataViewRender; 
			$dataSession["script"]			= $this->load->view('app_account_journal/list_script','',true).$this->core_web_javascript->createVar("componentID",$objComponent->componentID);  
			$this->load->view("core_masterpage/default_masterpage",$dataSession);		
		}
		catch(Exception $ex){
			show_error($ex->getLine()." ".$ex->getMessage() ,500 );
		}
	}	
	function searchJournal(){
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
			$this->load->model("Journal_Entry_Model");  
			
			//Nuevo Registro
			$journalNumber 			= $this->input->post("journalNumber");
			
			
			if(!$journalNumber){
					throw new Exception(NOT_PARAMETER);			
			} 			
			$obj 	= $this->Journal_Entry_Model->get_rowByCode($dataSession["user"]->companyID,$journalNumber);	
			
			if(!$obj)
			throw new Exception("NO SE ENCONTRO EL COMPROBANTE");	
			
			
			$this->output->set_content_type('application/json');
			$this->output->set_output(json_encode(array(
				'error'   			=> false,
				'message' 			=> SUCCESS,
				'companyID' 		=> $obj->companyID,
				'journalEntryID'	=> $obj->journalEntryID
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
	function viewAudit(){
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
			
			$uri				= $this->uri->uri_to_assoc(3);						
			$journalEntryID		= $uri["journalEntryID"];			
			$companyID 			= $dataSession["user"]->companyID;		
			$branchID 			= $dataSession["user"]->branchID;		
			$roleID 			= $dataSession["role"]->roleID;		
				
			
			//Cargar Libreria
			$this->load->library('core_web_pdf/src/EXTCezpdf.php');	 
			$this->load->model("Journal_Entry_Model"); 			
			$this->load->model("core/Company_Model"); 
			
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
			//Get Journal
			$datView["objJournalEntry"]	 					= $this->Journal_Entry_Model->get_rowByPK($companyID,$journalEntryID);
			$dataView["objDataAudit"]						= $this->core_web_auditoria->getAuditDetail($companyID,$journalEntryID,"tb_journal_entry");
			
			//Set Nombre del Reporte
			$reportName		= "COMPROBANTE";
			//Set Informacion File
			$pdf->addInfo(array('Title'=>$reportName,'Author'=>APP_NAME,'CreationDate'=>date('Y-m-d H:i:s')));
			//Set Titulo			
			$pdf->EXTCreateHeader("::".$objCompany->name." COMPROBANTE NO:".$datView["objJournalEntry"]->journalNumber."::",$objComponent->componentID,$objParameter->value,$dataSession);									
			$pdf->ezText("<b>IT REPORTE DE AUDITORIA</b>\n\n\n\n",FONT_SIZE,array('justification'=>'center'));
			//Set Detalle
			$objData = array();
			if($dataView["objDataAudit"])
			foreach($dataView["objDataAudit"] as $row){
				$objData[] = array('date'=>$row->modifiedOn,'user' => $row->nickname,'field'=>$row->name,'oldValue' => $row->oldValue,'newValue'=>$row->newValue);
			}			
			$objColumn 		= array('date'=>'Fecha','user' => 'Usuario','field'=>'Atributo','oldValue' => 'Valor Anterior','newValue'=>'Valor Nuevo');
			$pdf->ezTable($objData, $objColumn,'', array('showHeadings'=>1,'shaded'=>0,'showLines'=>1, 'width'=>$width,'fontSize' => FONT_SIZE));					
			//Set Pie		
			$pdf->EXTCreateFooter();
			//OutPut
			$pdf->ezStream(array('Content-Disposition' => $reportName)); 
			
		}
		catch(Exception $ex){
			show_error($ex->getLine()." ".$ex->getMessage() ,500 );
		}
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
			
			$uri				= $this->uri->uri_to_assoc(3);						
			$journalEntryID		= $uri["journalEntryID"];			
			$companyID 			= $dataSession["user"]->companyID;		
			$branchID 			= $dataSession["user"]->branchID;		
			$roleID 			= $dataSession["role"]->roleID;		
				
			
			//Cargar Libreria
			$this->load->library('core_web_pdf/src/EXTCezpdf.php');	 			
			$this->load->model("Journal_Entry_Model"); 
			$this->load->model("Journal_Entry_Detail_Model"); 			
			$this->load->model("Company_Currency_Model");
			$this->load->model("Account_Model");
			$this->load->model("Center_Cost_Model");			
			$this->load->model("core/Company_Model"); 
			$this->load->model("core/User_Permission_Model");
			$this->load->model("core/Role_Autorization_Model");

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
			//Get Journal
			$datView["objJournalEntry"]	 					= $this->Journal_Entry_Model->get_rowByPK($companyID,$journalEntryID);
			$datView["objJournalEntryDetail"]				= $this->Journal_Entry_Detail_Model->get_rowByJournalEntryID($companyID,$journalEntryID);								
			$datView["objJournalEntry"]->journalDate 		= date_format(date_create($datView["objJournalEntry"]->journalDate),"Y-m-d");
						
			//Set Nombre del Reporte
			$reportName		= "COMPROBANTE";
			//Set Informacion File
			$pdf->addInfo(array('Title'=>$reportName,'Author'=>APP_NAME,'CreationDate'=>date('Y-m-d H:i:s')));
			//Set Titulo			
			$pdf->EXTCreateHeader("::".$objCompany->name." COMPROBANTE NO:".$datView["objJournalEntry"]->journalNumber."::",$objComponent->componentID,$objParameter->value,$dataSession);						
			//Set Encambezado del comprobante
			$pdf->ezText("\n\n\n");
			$data = array( 
				array('field1'=>'<b>Fecha</b>'		,'field2'=>$datView["objJournalEntry"]->journalDate					,'field3'=>'<b>Estado</b>'		,'field4'=>$datView["objJournalEntry"]->workflowStageName	,'field5'=>'<b>Ref1</b>'		,'field6'=>$datView["objJournalEntry"]->reference1) ,
				array('field1'=>'<b>Aplicado</b>'	,'field2'=>$datView["objJournalEntry"]->isApplied ? 'Si' : 'No'		,'field3'=>'<b>Tipo</b>'		,'field4'=>$datView["objJournalEntry"]->journalTypeName		,'field5'=>'<b>Ref2</b>'		,'field6'=>$datView["objJournalEntry"]->reference2) ,
				array('field1'=>'<b>Cambio</b>'		,'field2'=>$datView["objJournalEntry"]->tb_exchange_rate			,'field3'=>'<b>Moneda</b>'		,'field4'=>$datView["objJournalEntry"]->currencyName		,'field5'=>'<b>Ref3</b>'		,'field6'=>$datView["objJournalEntry"]->reference3) 				
			);		
			$pdf->ezTable(
				$data,
				array('field1'=>'','field2'=>'','field3' => '','field4'=>'','field5'=>'','field6' => ''),
				'',
				array(
					'showHeadings'=>0,'showLines' => 0 ,'shaded'=>0,'xPos'=>'left',
					'xOrientation'=>'right',
					'width'=>$width,
					'fontSize' => FONT_SIZE,	
					'colGap' => 0,
					'cols'=>array(
						'field1'=>array('justification'=>'left','width'=>66),
						'field2'=>array('justification'=>'left'),
						'field3'=>array('justification'=>'left','width'=>50),
						'field4'=>array('justification'=>'left'),
						'field5'=>array('justification'=>'left','width'=>35),
						'field6'=>array('justification'=>'left')
					) 
				)
			);
			//Set Beneficiario
			$pdf->ezText("\n<b>Beneficiario</b>",FONT_SIZE);
			$data	= array(
				array('field1'=> $datView["objJournalEntry"]->entryName."...")
			);
			$pdf->ezTable(
				$data,
				array('field1'=>''),
				'',
				array(
					'showHeadings'=>0, 			/*mostrar titulo*/
					'showLines'=>0, 			/*mostrar linea*/
					'xPos'=>'left', 			/*de donde inicia a dibujar*/
					'xOrientation'=>'right', 	/*para donde dibuja*/
					'width'=>$width, 			/*ancho*/
					'fontSize' => FONT_SIZE,	/*tama;o de letra*/
					'colGap' => 0				/*margen de la columna*/
				)
			);
			//Set Comentario del Comprobante
			$pdf->ezText("\n<b>Comentario</b>",FONT_SIZE);
			$data	= array(
				array('field1'=> $datView["objJournalEntry"]->note."...")
			);
			$pdf->ezTable(
				$data,
				array('field1'=>''),
				'',
				array(
					'showHeadings'=>0,'shaded'=>0,'xPos'=>'left','xOrientation'=>'right',
					'showLines'=>0, 
					'width'=>$width,
					'fontSize' => FONT_SIZE,
					'colGap' => 0			
				)
			);
			//Set Detalle del Comprobante
			$pdf->ezText("\n<b>Detalle</b>",FONT_SIZE);
			$data		= array();
			$sumCredit	= 0;
			$sumDebit	= 0;
			if($datView["objJournalEntryDetail"])
			foreach($datView["objJournalEntryDetail"] as $row){
				$data[] 	= array('field1'=>$row->accountNumber." ".$row->accountName,'field2'=>$row->classNumber,'field3'=>number_format($row->debit,2),'field4'=>number_format($row->credit,2));
				$sumCredit 	= $sumCredit + $row->credit;
				$sumDebit 	= $sumDebit + $row->debit;
			}
			$pdf->ezTable(
				$data,
				array('field1'=>'Cuenta','field2'=>'CC','field3'=>'Debito','field4'=>'Credito'),
				'',				
				array(
					'showHeadings'=>1,
					'showLines'=>4,
					'shaded'=>0,
					'xPos'=>'left',
					'xOrientation'=>'right',
					'width'=>$width,
					'colGap' => 0	,
					'fontSize' => FONT_SIZE,
					'cols' => 
						array (
							'field3' => array('justification'=>'right'),
							'field4' => array('justification'=>'right')
						)
					)
			);
			//Set Resultado del Comprobante
			$pdf->ezText("\n<b>Resumen</b>",FONT_SIZE);
			$data	= array(
				array('field1'=>'Credito:','field2' => $sumCredit),
				array('field1'=>'Debito:','field2' => $sumDebit)
			);
			$pdf->ezTable(
				$data,
				array('field1'=>'','field2'=>''),
				'',
				array(
					'showHeadings'=>0,
					'shaded'=>0,
					'xPos'=>'left',
					'showLines'=>0, 
					'colGap' => 0	,
					'fontSize' => FONT_SIZE,
					'xOrientation'=>'right',
					'width'=>$width/2,
					'cols'=>array(
						'field2'=>array('justification'=>'left','width'=>100),
						'field1'=>array('width'=>60)
					)          
				)
			);
			//Set Firma del Comprobante
			$pdf->ezText("\n\n\n\n<b>Firma</b>\n");
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
}
?>
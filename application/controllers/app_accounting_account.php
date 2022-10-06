<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class App_Accounting_Account extends CI_Controller {

	

    public function __construct() {

       parent::__construct();

    } 

	public function isValidAccountNumber($accountNumber,$companyID,$accountLevelID){

		//false numero incorrecto

		//true numero correcto

		$this->load->model("Account_Level_Model"); 

		$objAccountLevel = $this->Account_Level_Model->get_rowByPK($companyID,$accountLevelID);

				

		//Validar Longitud Total

		if($objAccountLevel->lengthTotal != strlen($accountNumber) )

		return false;		

			

		//Validar Longitud de Grupo

		if($objAccountLevel->split){

			$partNumber = explode($objAccountLevel->split,$accountNumber);

			$count 		= count($partNumber) -1;

			if($objAccountLevel->lengthGroup != strlen($partNumber[$count]))

			return false;

		}

		

		return true;

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

			

			//Set Datos			

			//

			////////////////////////////////////////

			////////////////////////////////////////

			////////////////////////////////////////

			$this->load->model("Account_Level_Model"); 			

			$this->load->model("Account_Type_Model"); 			

			$this->load->model("Account_Model"); 			

			$this->load->model("Company_Currency_Model"); 			

			$this->load->model("core/User_Permission_Model");

			$this->load->model("core/Role_Autorization_Model");
			
			$this->load->model("Center_Cost_Model");

			

			

			//Redireccionar datos

			$uri			= $this->uri->uri_to_assoc(3);

						

			$companyID		= $uri["companyID"];

			$accountID		= $uri["accountID"];	

			$branchID 		= $dataSession["user"]->branchID;

			$roleID 		= $dataSession["role"]->roleID;			

			if((!$companyID || !$accountID))

			{ 

				redirect('app_accounting_account/add');	

			} 		

			

			

			//Obtener el Registro			

			$datView["objAccount"]	 				= $this->Account_Model->get_rowByPK($companyID,$accountID);

			$datView["objParentAccount"]			= $this->Account_Model->get_rowByPK($companyID,$datView["objAccount"]->parentAccountID);

			$datView["objListAccountLevel"]	 		= $this->Account_Level_Model->getByCompany($companyID);

			$datView["objListAccountType"]	 		= $this->Account_Type_Model->getByCompany($companyID);

			$datView["objListCompanyCurrency"]	 	= $this->Company_Currency_Model->getByCompany($companyID);
			
			$datView["objListCenterCost"]			= $this->Center_Cost_Model->getByCompany($dataSession["user"]->companyID);

			//Obtener los Permisos Core

			$datView["objUserPermission"]			= $this->User_Permission_Model->get_rowByCompanyIDyBranchIDyRoleID($companyID,$branchID,$roleID);

			//Obtener las Autorization Core

			$datView["listComponentAutoriation"]	= $this->Role_Autorization_Model->get_rowByRoleAutorization($companyID,$branchID,$roleID);

			

			//Renderizar Resultado

			$dataSession["notification"]	= $this->core_web_error->get_error($dataSession["user"]->userID);

			$dataSession["message"]			=  $this->core_web_notification->get_message();

			$dataSession["head"]			= $this->load->view('app_account/edit_head',$datView,true);

			$dataSession["body"]			= $this->load->view('app_account/edit_body',$datView,true);

			$dataSession["script"]			= $this->load->view('app_account/edit_script',$datView,true);  

			$dataSession["footer"]			= "";				

			$this->load->view("core_masterpage/default_masterpage",$dataSession);	

			

		}

		catch(Exception $ex){

			show_error($ex->getLine()." ".$ex->getMessage() ,500 );

		}	

	}

	function delete(){

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

						

						$resultPermission		= $this->core_web_permission->urlPermissionCmd($this->router->class,"delete",$this->config->item('url_suffix'),$dataSession,$dataSession["menuTop"],$dataSession["menuLeft"],$dataSession["menuBodyReport"],$dataSession["menuBodyTop"],$dataSession["menuHiddenPopup"]);

						if ($resultPermission 	== PERMISSION_NONE)

						throw new Exception(NOT_ALL_DELETE);			

			

			}	 

			

			//Load Modelos

			//

			////////////////////////////////////////

			////////////////////////////////////////

			////////////////////////////////////////

			$this->load->model("Account_Model");  

			$this->load->model('core/Bd_Model');

			

			//Nuevo Registro

			$companyID 			= $this->input->post("companyID");

			$accountID 			= $this->input->post("accountID");				

			$branchID			= $dataSession["user"]->branchID;

			$loginID			= $dataSession["user"]->userID;

			

			if((!$companyID && !$accountID)){

					throw new Exception(NOT_PARAMETER);		

			} 

			

			$obj = $this->Account_Model->get_rowByPK($companyID,$accountID);	

			

			//Validar permiso de usuario

			if ($resultPermission 	== PERMISSION_ME && ($obj->createdBy != $dataSession["user"]->userID))

			throw new Exception(NOT_DELETE);

			

			//Validar si la cuenta es hoja

			$resultTemp = $this->Account_Model->get_isParent($companyID,$accountID);

			if($resultTemp > 0 ){

			throw new Exception("ELIMINAR PRIMERAMENTE LAS CUENTAS DE MAS BAJO NIVEL");

			}

			

			//Validar Saldo

			$app					 = "DELETE_ACCOUNT";

			$query					 = "SET @resultProcessMessage 	= '';";

			$query					.= "SET @resultProcessCode 		= 0;";

			$query					.= "CALL pr_accounting_checkaccount_to_delete('".$companyID."','".$branchID."','".$loginID."','".$accountID."','".$app."',@resultProcessMessage,@resultProcessCode);";

			$query					.= "SELECT @resultProcessMessage as message,@resultProcessCode as codigo;";

			$resultProcess			 = $this->Bd_Model->executeProcedureMultiQuery($query);				

			$resultProcess			 = $resultProcess[3][0];

			if($resultProcess["codigo"] == 0)

			throw new Exception($resultProcess["message"]);

			

			//Eliminar el Registro

			$this->Account_Model->delete($companyID,$accountID);

					

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

			

			//AUTENTICACION

			if(!$this->core_web_authentication->isAuthenticated())

			throw new Exception(USER_NOT_AUTENTICATED);

			$dataSession		= $this->session->all_userdata();

			

			//Load Modelos			

			//

			////////////////////////////////////////

			////////////////////////////////////////

			////////////////////////////////////////

			$this->load->model("Account_Level_Model"); 			  

			$this->load->model("Account_Type_Model"); 

			$this->load->model("Account_Model"); 

			$this->load->model("Company_Currency_Model"); 
			
			$this->load->model("Center_Cost_Model");

					

			

			//Validar Formulario						

			$this->form_validation->set_rules("txtAccountNumber","Codigo","required");    

			$this->form_validation->set_rules("txtAccountLevelID","Clase","required");

			$this->form_validation->set_rules("txtAccountTypeID","Tipo","required");

			$this->form_validation->set_rules("txtName","Nombre","required");

			$this->form_validation->set_rules("txtCurrencyID","Moneda","required");

			

			 

			//Nuevo Registro			

			$continue	= true;

			if( $method == "new" && $this->form_validation->run() == true ){

					

					//PERMISO SOBRE LA FUNCION

					if(APP_NEED_AUTHENTICATION == true){

						$permited = false;

						$permited = $this->core_web_permission->urlPermited($this->router->class,"index",$this->config->item('url_suffix'),$dataSession["menuTop"],$dataSession["menuLeft"],$dataSession["menuBodyReport"],$dataSession["menuBodyTop"],$dataSession["menuHiddenPopup"]);

						

						if(!$permited)

						throw new Exception(NOT_ACCESS_CONTROL);

						

						$resultPermission		= $this->core_web_permission->urlPermissionCmd($this->router->class,"add",$this->config->item('url_suffix'),$dataSession,$dataSession["menuTop"],$dataSession["menuLeft"],$dataSession["menuBodyReport"],$dataSession["menuBodyTop"],$dataSession["menuHiddenPopup"]);

						if ($resultPermission 	== PERMISSION_NONE)

						throw new Exception(NOT_ACCESS_FUNCTION);			

					

					}	

					

					//Ingresar Cuenta

					if($continue){

						$this->db->trans_begin();

						//Buscar Padre

						$objParentAccount = NULL;

						if($this->input->post("txtAccountNumberParent")){

							$objParentAccount		= $this->Account_Model->getByAccountNumber($this->input->post("txtAccountNumberParent"),$dataSession["user"]->companyID);

						}

						

						//Buscar si Existe Cuenta

						$objAccount					= $this->Account_Model->getByAccountNumber($this->input->post("txtAccountNumber"),$dataSession["user"]->companyID);

						if($objAccount){

							$continue 				= false;

							throw new Exception("EL CODIGO DE LA CUENTA YA ESTA RESERVADO...");

						}

						//Validar Codigo	

						if(!$this->isValidAccountNumber($this->input->post("txtAccountNumber"),$dataSession["user"]->companyID,$this->input->post("txtAccountLevelID"))){

							$continue 				= false;

							throw new Exception("EL CODIGO DE LA CUENTA TIENE UN FORMATO INCORRECTO");

						}



						//Validar si la cuenta puede ser operativa

						$objAccountLevel 			= $this->Account_Level_Model->get_rowByPK($dataSession["user"]->companyID,$this->input->post("txtAccountLevelID"));

						if($this->input->post("txtIsOperative") !=  $objAccountLevel->isOperative){

							$continue 				= false;

							throw new Exception("OPERATIVIDAD DE LA CUENTA NO ES VALIDA");

						}

						

						

						//Crear Cuenta

						$obj["companyID"]			= $dataSession["user"]->companyID;

						$obj["accountTypeID"] 		= $this->input->post("txtAccountTypeID");

						$obj["accountLevelID"] 		= $this->input->post("txtAccountLevelID");

						$obj["parentAccountID"] 	= $objParentAccount ? $objParentAccount->accountID : NULL;
						
						$obj["classID"] 			= $this->input->post("txtClassID") ? $this->input->post("txtClassID") : NULL;

						$obj["accountNumber"] 		= $this->input->post("txtAccountNumber");	

						$obj["name"] 				= $this->input->post("txtName");					 

						$obj["description"] 		= $this->input->post("txtDescription");

						$obj["isOperative"] 		= $this->input->post("txtIsOperative");

						$obj["statusID"] 			= 0;

						$obj["currencyID"] 			= $this->input->post("txtCurrencyID");

						$obj["isActive"] 			= true;

						$this->core_web_auditoria->setAuditCreated($obj,$dataSession);

						

						$accountID				= $this->Account_Model->insert($obj);

						$companyID 				= $obj["companyID"];

						

						if($this->db->trans_status() !== false){

							$this->db->trans_commit();						

							$this->core_web_notification->set_message(false,SUCCESS);

							redirect('app_accounting_account/edit/companyID/'.$companyID."/accountID/".$accountID);						

						}

						else{

							$this->db->trans_rollback();						

							$this->core_web_notification->set_message(true,$this->db->_error_message());

							redirect('app_accounting_account/add');	

						}

					}

					else{

						redirect('app_accounting_account/add');	

					}

					

					 

			} 

			//Editar Registro

			else if( $this->form_validation->run() == true) {

					

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

					

					//PERMISO SOBRE EL REGISTRO

					$companyID					= $dataSession["user"]->companyID;

					$accountID 					= $this->input->post("txtAccountID");

					$objOld = $this->Account_Model->get_rowByPK($companyID,$accountID);

					if ($resultPermission 	== PERMISSION_ME && ($objOld->createdBy != $dataSession["user"]->userID))

					throw new Exception(NOT_EDIT);

					

			

					if($continue){

						$this->db->trans_begin();					

						

						//Buscar Padre

						$objParentAccount 	= NULL;

						$accountID 			= $this->input->post("txtAccountID");

						if($this->input->post("txtAccountNumberParent")){

							$objParentAccount		= $this->Account_Model->getByAccountNumber($this->input->post("txtAccountNumberParent"),$dataSession["user"]->companyID);

						}

						

						//Buscar si Existe Cuenta

						$objAccount					= $this->Account_Model->getByAccountNumber($this->input->post("txtAccountNumber"),$dataSession["user"]->companyID);

						if($objAccount){

							if($objAccount->accountID != $accountID){

								$continue 				= false;

								throw new Exception("EL CODIGO DE LA CUENTA YA ESTA RESERVADO...");

							}

						}

						//Validar Codigo	

						if(!$this->isValidAccountNumber($this->input->post("txtAccountNumber"),$dataSession["user"]->companyID,$this->input->post("txtAccountLevelID"))){

							$continue 				= false;

							throw new Exception("EL CODIGO DE LA CUENTA TIENE UN FORMATO INCORRECTO");

						}						

				

						//Validar si la cuenta puede ser operativa

						$objAccountLevel 			= $this->Account_Level_Model->get_rowByPK($dataSession["user"]->companyID,$this->input->post("txtAccountLevelID"));

						if($this->input->post("txtIsOperative") !=  $objAccountLevel->isOperative){

							$continue 				= false;

							throw new Exception("OPERATIVIDAD DE LA CUENTA NO ES VALIDA");

						}

						

						

						//Crear Cuenta

						$companyID					= $dataSession["user"]->companyID;

						$accountID 					= $this->input->post("txtAccountID");

						$obj["accountTypeID"] 		= $this->input->post("txtAccountTypeID");

						$obj["accountLevelID"] 		= $this->input->post("txtAccountLevelID");

						$obj["parentAccountID"] 	= $objParentAccount ? $objParentAccount->accountID : NULL;
						
						$obj["classID"] 			= $this->input->post("txtClassID") ? $this->input->post("txtClassID") : NULL;

						$obj["accountNumber"] 		= $this->input->post("txtAccountNumber");	

						$obj["name"] 				= $this->input->post("txtName");					 

						$obj["description"] 		= $this->input->post("txtDescription");

						$obj["isOperative"] 		= $this->input->post("txtIsOperative");						

						$obj["currencyID"] 			= $this->input->post("txtCurrencyID");

						$obj["isActive"] 			= true;					

						$result 					= $this->Account_Model->update($companyID,$accountID,$obj);

						

						if($this->db->trans_status() !== false){

							$this->db->trans_commit();

							$this->core_web_notification->set_message(false,SUCCESS);

						}

						else{

							$this->db->trans_rollback();						

							$this->core_web_notification->set_message(true,$this->db->_error_message());

						}

						redirect('app_accounting_account/edit/companyID/'.$companyID."/accountID/".$accountID);

						

					}					

					else{

						redirect('app_accounting_account/add');	

					}

			}  

			else{

				$stringValidation = str_replace("\n","",validation_errors());								

				$this->core_web_notification->set_message(true,$stringValidation);

				redirect('app_accounting_account/add');	

			} 

			

		}

		catch(Exception $ex){

			show_error($ex->getLine()." ".$ex->getMessage() ,500 );

		}		

			

	}

	function add(){ 

	

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

						

						$resultPermission		= $this->core_web_permission->urlPermissionCmd($this->router->class,"add",$this->config->item('url_suffix'),$dataSession,$dataSession["menuTop"],$dataSession["menuLeft"],$dataSession["menuBodyReport"],$dataSession["menuBodyTop"],$dataSession["menuHiddenPopup"]);

						if ($resultPermission 	== PERMISSION_NONE)

						throw new Exception(NOT_ALL_INSERT);			

			}	

			 

			$this->load->model("Account_Level_Model"); 

			$this->load->model("Account_Type_Model"); 

			$this->load->model("Company_Currency_Model"); 
			
			$this->load->model("Center_Cost_Model");

			

			$data["objListAccountLevel"] 	= $this->Account_Level_Model->getByCompany($dataSession["user"]->companyID);

			$data["objListAccountType"] 	= $this->Account_Type_Model->getByCompany($dataSession["user"]->companyID);

			$data["objListCompanyCurrency"] = $this->Company_Currency_Model->getByCompany($dataSession["user"]->companyID);
			
			$data["objListCenterCost"]		= $this->Center_Cost_Model->getByCompany($dataSession["user"]->companyID);

			

			//Renderizar Resultado 

			$dataSession["notification"]	= $this->core_web_error->get_error($dataSession["user"]->userID);

			$dataSession["message"]			= $this->core_web_notification->get_message();

			$dataSession["head"]			= $this->load->view('app_account/news_head',$data,true);

			$dataSession["body"]			= $this->load->view('app_account/news_body',$data,true);

			$dataSession["script"]			= $this->load->view('app_account/news_script',$data,true);  

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

			$objComponent		= $this->core_web_tools->getComponentIDBy_ComponentName("tb_account");

			if(!$objComponent)

			throw new Exception("00409 EL COMPONENTE 'tb_account' NO EXISTE...");

			

			

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

			$dataSession["head"]			= $this->load->view('app_account/list_head','',true);

			$dataSession["footer"]			= $this->load->view('app_account/list_footer','',true);

			$dataSession["body"]			= $dataViewRender; 

			$dataSession["script"]			= $this->load->view('app_account/list_script','',true).$this->core_web_javascript->createVar("componentID",$objComponent->componentID);  

			$this->load->view("core_masterpage/default_masterpage",$dataSession);		

		}

		catch(Exception $ex){

			show_error($ex->getLine()." ".$ex->getMessage() ,500 );

		}

	}	

	

}

?>
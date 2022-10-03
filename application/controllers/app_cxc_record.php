<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class App_Cxc_Record extends CI_Controller {
	
    public function __construct() {
       parent::__construct();
    }   
	function index(){ 
		
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

		
			//Obtener el componente Para mostrar la lista de AccountType
			$objComponent	= $this->core_web_tools->getComponentIDBy_ComponentName("tb_customer_consultas_sin_riesgo");
			if(!$objComponent)
			throw new Exception("00409 EL COMPONENTE 'tb_customer_consultas_sin_riesgo' NO EXISTE...");
		
					
			$entityID		= $dataSession["user"]->userID;
			$companyID		= $dataSession["user"]->companyID;
			$branchIDUser	= $dataSession["user"]->branchID;
			$roleID 		= $dataSession["role"]->roleID;					

			
			//Leer Parametros
			$this->load->model("Customer_Consultas_Sin_Riesgo_Model"); 
			date_default_timezone_set(APP_TIMEZONE); 
			$uri			= $this->uri->uri_to_assoc(3);	
			$identificacion = $this->input->get("identificacion",'');			
			$file_exists 	= $this->input->get("file_exists",'');
			$identificacion	= !$file_exists ? str_replace("-","",$identificacion) : substr($file_exists,0,14);
			$path_ 			= PATH_FILE_OF_APP."/company_".$companyID."/component_".$objComponent->componentID."/";
			$file			= $identificacion;
			$prefiex 		= date("_Y_m_d");
			$extencion		= ".txt";
			$archivo1 		= $path_.$file.$prefiex.$extencion;
			$archivo2 		= $path_.$file_exists;
		
			
			$objParameter 			= $this->core_web_parameter->getParameter("CORE_CXC_WSDL_SIN_RIESGO",$companyID);//"https://www.sinriesgos.com.ni/WS/WebService.asmx?WSDL"			
			$objParameterPassword 	= $this->core_web_parameter->getParameter("CORE_CXC_WSDL_SIN_RIESGO_PASSWORD",$companyID);//flc-wgonzalez
			$objParameterUsuario 	= $this->core_web_parameter->getParameter("CORE_CXC_WSDL_SIN_RIESGO_USUARIO",$companyID);//180389Gonzalez.
			$client 				= new SoapClient($objParameter->value);
			$params 				= array(
				"Usuario" 					=> $objParameterUsuario->value,
				"Password" 					=> $objParameterPassword->value,
				"NumeroIdentificacion" 		=> $identificacion,
				"TipoConsulta" 				=> 1, 
				"Score"						=> false,
				"TipoPersona"				=> "F"
			);
			
			
		
			
			$resultado 		= "";
			$datView		= "";
			if(
				($identificacion && !$file_exists) ||  /*si viene la variable identificacion y no viene la variable file_exists: consultar */
				(!file_exists($archivo2) && ($file_exists)) /*si viene la varieble file_exists y no existe el archivo: consultar */ 
			){				
				
				$objUltimoRegistro 				= $this->Customer_Consultas_Sin_Riesgo_Model->get_rowByCedulaLast($companyID,$identificacion);
				$requestID						= $objUltimoRegistro == null ? 0 : $objUltimoRegistro->requestID;
				$objUltimoRegistroMas6Dias 		= $this->Customer_Consultas_Sin_Riesgo_Model->get_rowValidOld($requestID,6);
				$objFileDist					= $this->Customer_Consultas_Sin_Riesgo_Model->get_rowByCedula_FileName($companyID,$identificacion);
				$archivo_exists					= $objUltimoRegistroMas6Dias != null ? !file_exists($objUltimoRegistroMas6Dias->file): false;
				$resultado 						= $client->ObtenerRecordCrediticio($params);	
				
				//Guardar Archivo si, viene la variable file_exists, pero no existe el archivo
				if(!file_exists($archivo2) && ($file_exists)){ 
					$this->GuardarArchivo($archivo2,$resultado);
				}
				//Guardar Archivo si, viene la varieble identificacion y no viene la varieble file_exists
				if($identificacion && !$file_exists){					
					$this->GuardarArchivo($archivo1,$resultado);
					$archivo2 = $archivo1;
				}


				//Guardar la informacion del cliente
				if(
					($objUltimoRegistroMas6Dias  != NULL) || /*si la ultima consulta tiene mas de 6 dias */
					($objUltimoRegistro == NULL) /*si no existe registro en la tabla*/
				)
				{	
					$objCustomerConsultaSinRiesgo["companyID"] 				= $dataSession["user"]->companyID;			
					$objCustomerConsultaSinRiesgo["name"] 					= $resultado->ObtenerRecordCrediticioResult->Persona->NombreRazonSocial;			
					$objCustomerConsultaSinRiesgo["id"] 					= $resultado->ObtenerRecordCrediticioResult->Persona->NumeroDocumentoIdentidad;
					$objCustomerConsultaSinRiesgo["userID"] 				= $dataSession["user"]->userID;
					$objCustomerConsultaSinRiesgo["file"] 					= $archivo2;
					$this->core_web_auditoria->setAuditCreated($objCustomerConsultaSinRiesgo,$dataSession);
					$requestID 	= $this->Customer_Consultas_Sin_Riesgo_Model->insert($objCustomerConsultaSinRiesgo);					
				
				} 
				
				$requestID												= 0;
				$objCustomerConsultaSinRiesgo							= NULL;
				$objCustomerConsultaSinRiesgo["modifiedOn"] 			= date("Y-m-d");
				$objCustomerConsultaSinRiesgo["userID"] 				= $dataSession["user"]->userID;
				$objCustomerConsultaSinRiesgo["file"] 					= substr($archivo2,-29);
				//error_reporting(E_ALL);
				//ini_set('display_errors', 1);
				$this->Customer_Consultas_Sin_Riesgo_Model->updateByCedula($companyID,$identificacion,$objCustomerConsultaSinRiesgo);
			}
			
		
			if(   !(!$identificacion && !$file_exists)   ){
				$identificacion									= $identificacion ? str_replace("-","",$identificacion) : (substr($file_exists, 0, 14));				
				$objUltimoRegistro 								= $this->Customer_Consultas_Sin_Riesgo_Model->get_rowByCedulaLast($companyID,$identificacion);
				$datos 											= $this->LeerDatos($archivo2);				
				$datView										= $this->FillDatos($datos);
				$datView["ObjConsulta"]							= $objUltimoRegistro;
				$datView["Persona"]->NumeroDocumentoIdentidad 	= substr_replace($datView["Persona"]->NumeroDocumentoIdentidad, "-", 3, 0);
				$datView["Persona"]->NumeroDocumentoIdentidad 	= substr_replace($datView["Persona"]->NumeroDocumentoIdentidad, "-", 10, 0);
			}
		
			
			//Renderizar Resultado
			$dataSession["notification"]	= $this->core_web_error->get_error($dataSession["user"]->userID);
			$dataSession["message"]			= $this->core_web_notification->get_message();
			$dataSession["head"]			= $this->load->view('app_cxc_record/edit_head',$datView,true);
			$dataSession["body"]			= $this->load->view('app_cxc_record/edit_body',$datView,true);
			$dataSession["script"]			= $this->load->view('app_cxc_record/edit_script',$datView,true);  
			$dataSession["footer"]			= "";				
			$this->load->view("core_masterpage/default_masterpage",$dataSession);	
			
		}
		catch(Exception $ex){
			show_error($ex->getLine()." ".$ex->getMessage() ,500 );
		}	
	}
	function GuardarArchivo($file,$data){
		//Serializar		
		$json_reaultado = json_encode($data);
		if(!file_exists($file))
		{
			//Crear Archivo
			$fp = fopen($file, 'w+');
			fclose($fp);

			//Guardar Datos.
			$current 	= file_get_contents($file);
			$current 	.= $json_reaultado;			
			file_put_contents($file, $current);
		}
	}
	function LeerDatos($file){
		$fp 	= fopen($file, "rb");
		$datos 	= fread($fp, filesize($file));
		fclose($fp);
		return $datos;
	}
	function FillDatos($resultado){
		$datView		= null;
		$resultado 		= json_decode($resultado);
		$resultado		= $resultado->ObtenerRecordCrediticioResult;
		
		//Obtener Datos Generales
		$datView["Persona"] = $resultado->Persona;
		/*
		$resultado->Persona->NumeroDocumentoIdentidad;
		$resultado->Persona->NombreRazonSocial;
		*/
		
		if (!empty((array)$resultado->DatosContacto)) {
			$empty = "";
			
			
			//Obtener Datos de Direcciones
			if(!empty((array)$resultado->DatosContacto->DireccionesContacto))
			if(!empty((array)$resultado->DatosContacto->DireccionesContacto->DireccionContacto))
			{
			    $datView["Direcciones"]	= $resultado->DatosContacto->DireccionesContacto->DireccionContacto;
			    $datView["Direcciones"] = is_array($datView["Direcciones"]) ? $datView["Direcciones"] : array($datView["Direcciones"]);
			}
			
			/*
			$resultado->DatosContacto->DireccionesContacto->DireccionContacto[0]->Direccion;
			$resultado->DatosContacto->DireccionesContacto->DireccionContacto[0]->Departamento;
			$resultado->DatosContacto->DireccionesContacto->DireccionContacto[0]->Municipio;
			$resultado->DatosContacto->DireccionesContacto->DireccionContacto[0]->Referencia;
			*/
			
			//Obtener Datos de Telefonos
			if(!empty((array)$resultado->DatosContacto->TelefonosContacto))
			if(!empty((array)$resultado->DatosContacto->TelefonosContacto->TelefonoContacto)){
			    $datView["Telefonos"]	= $resultado->DatosContacto->TelefonosContacto->TelefonoContacto;
			    $datView["Telefonos"] = is_array($datView["Telefonos"]) ? $datView["Telefonos"] : array($datView["Telefonos"]);
			}
			/*
			$resultado->DatosContacto->TelefonosContacto->TelefonoContacto[0]->Telefono;
			$resultado->DatosContacto->TelefonosContacto->TelefonoContacto[0]->Referencia;
			*/
		}
		
		//Datos de Creditos Vigente
		if(isset($resultado->CreditosVigentes))
		if (!empty((array)$resultado->CreditosVigentes)) 
		if (!empty((array)$resultado->CreditosVigentes->OperacionDeCredito)) {
		    $datView["CreditosVigentes"]	= $resultado->CreditosVigentes->OperacionDeCredito;		
		    $datView["CreditosVigentes"]    = is_array($datView["CreditosVigentes"]) ? $datView["CreditosVigentes"] : array($datView["CreditosVigentes"]);
		}
		/*
		$resultado->CreditosVigentes->OperacionDeCredito[0]->NumeroCredito;
		$resultado->CreditosVigentes->OperacionDeCredito[0]->Cuota;
		$resultado->CreditosVigentes->OperacionDeCredito[0]->FechaReporte;
		$resultado->CreditosVigentes->OperacionDeCredito[0]->Departamento;
		$resultado->CreditosVigentes->OperacionDeCredito[0]->TipoCredito;
		$resultado->CreditosVigentes->OperacionDeCredito[0]->FechaDesembolso;
		$resultado->CreditosVigentes->OperacionDeCredito[0]->MontoAutorizado;
		$resultado->CreditosVigentes->OperacionDeCredito[0]->PlazoMeses;
		$resultado->CreditosVigentes->OperacionDeCredito[0]->FormaDePago;
		$resultado->CreditosVigentes->OperacionDeCredito[0]->SaldoDeuda;
		$resultado->CreditosVigentes->OperacionDeCredito[0]->EstadoOP;
		$resultado->CreditosVigentes->OperacionDeCredito[0]->TipoObligacion;
		$resultado->CreditosVigentes->OperacionDeCredito[0]->MontoVencido;
		$resultado->CreditosVigentes->OperacionDeCredito[0]->AntiguedadMoraEnDias;
		$resultado->CreditosVigentes->OperacionDeCredito[0]->TipoGarantia;
		$resultado->CreditosVigentes->OperacionDeCredito[0]->FormaRecuperacion;
		$resultado->CreditosVigentes->OperacionDeCredito[0]->Entidad;
		$resultado->CreditosVigentes->OperacionDeCredito[0]->HistorialMora->HistoriaMora[0]->FechaReporte;
		$resultado->CreditosVigentes->OperacionDeCredito[0]->HistorialMora->HistoriaMora[0]->AntiguedadMoraEnDias;
		*/
		
		//Datos de Creditos Cancelados	
		if(isset($resultado->CreditosCancelados))
		if (!empty((array)$resultado->CreditosCancelados)) 
		if (!empty((array)$resultado->CreditosCancelados->OperacionDeCredito)) {
		    $datView["CreditosCancelados"]	= $resultado->CreditosCancelados->OperacionDeCredito;
		    $datView["CreditosCancelados"]  = is_array($datView["CreditosCancelados"]) ? $datView["CreditosCancelados"] : array($datView["CreditosCancelados"]);
		}
		/*
		$resultado->CreditosCancelados->OperacionDeCredito[0]->NumeroCredito;
		$resultado->CreditosCancelados->OperacionDeCredito[0]->Cuota;
		$resultado->CreditosCancelados->OperacionDeCredito[0]->FechaReporte;
		$resultado->CreditosCancelados->OperacionDeCredito[0]->Departamento;
		$resultado->CreditosCancelados->OperacionDeCredito[0]->TipoCredito;
		$resultado->CreditosCancelados->OperacionDeCredito[0]->FechaDesembolso;
		$resultado->CreditosCancelados->OperacionDeCredito[0]->MontoAutorizado;
		$resultado->CreditosCancelados->OperacionDeCredito[0]->PlazoMeses;
		$resultado->CreditosCancelados->OperacionDeCredito[0]->FormaDePago; 
		$resultado->CreditosCancelados->OperacionDeCredito[0]->SaldoDeuda;
		$resultado->CreditosCancelados->OperacionDeCredito[0]->EstadoOP;
		$resultado->CreditosCancelados->OperacionDeCredito[0]->TipoObligacion;
		$resultado->CreditosCancelados->OperacionDeCredito[0]->MontoVencido;
		$resultado->CreditosCancelados->OperacionDeCredito[0]->AntiguedadMoraEnDias;
		$resultado->CreditosCancelados->OperacionDeCredito[0]->TipoGarantia;
		$resultado->CreditosCancelados->OperacionDeCredito[0]->FormaRecuperacion;
		$resultado->CreditosCancelados->OperacionDeCredito[0]->Entidad;
		$resultado->CreditosCancelados->OperacionDeCredito[0]->HistorialMora->HistoriaMora[0]->FechaReporte;
		$resultado->CreditosCancelados->OperacionDeCredito[0]->HistorialMora->HistoriaMora[0]->AntiguedadMoraEnDias;
		*/
		
		//Tarjetas de Credito
		if(isset($resultado->TarjetasDeCredito))
		if(isset($resultado->TarjetasDeCredito))
		if (!empty((array)$resultado->TarjetasDeCredito->TarjetaDeCredito)) {
		    $datView["TarjetasDeCredito"]	= $resultado->TarjetasDeCredito->TarjetaDeCredito;
		    $datView["TarjetasDeCredito"]  = is_array($datView["TarjetasDeCredito"]) ? $datView["TarjetasDeCredito"] : array($datView["TarjetasDeCredito"]);
		}
		/*
		$resultado->TarjetasDeCredito->TarjetaDeCredito[0]->NumeroTarjeta;
		$resultado->TarjetasDeCredito->TarjetaDeCredito[0]->Entidad;
		$resultado->TarjetasDeCredito->TarjetaDeCredito[0]->FechaReporte;
		$resultado->TarjetasDeCredito->TarjetaDeCredito[0]->FechaEmision;
		$resultado->TarjetasDeCredito->TarjetaDeCredito[0]->LimiteCredito;
		$resultado->TarjetasDeCredito->TarjetaDeCredito[0]->SaldoDeuda;
		$resultado->TarjetasDeCredito->TarjetaDeCredito[0]->CreditoDisponible;
		$resultado->TarjetasDeCredito->TarjetaDeCredito[0]->TipoTarjeta;
		$resultado->TarjetasDeCredito->TarjetaDeCredito[0]->TipoObligacion;
		$resultado->TarjetasDeCredito->TarjetaDeCredito[0]->MontoVencido;
		$resultado->TarjetasDeCredito->TarjetaDeCredito[0]->MoraEnDias;
		$resultado->TarjetasDeCredito->TarjetaDeCredito[0]->FormaRecuperacion;
		$resultado->TarjetasDeCredito->TarjetaDeCredito[0]->FechaDesembolso;
		$resultado->TarjetasDeCredito->TarjetaDeCredito[0]->MontoAutorizado;
		$resultado->TarjetasDeCredito->TarjetaDeCredito[0]->PlazoMeses;
		$resultado->TarjetasDeCredito->TarjetaDeCredito[0]->SaldoDeudaExtraFinanciamiento;
		*/
		
		//Historia de Consulta
		if (!empty((array)$resultado->Consultas)) 
		if (!empty((array)$resultado->Consultas->HistoriaConsulta)){ 
		    $datView["Consultas"]	= $resultado->Consultas->HistoriaConsulta;
		    $datView["Consultas"]   = is_array($datView["Consultas"]) ? $datView["Consultas"] : array($datView["Consultas"]);
		}
	
	
		/*
		$resultado->Consultas->HistoriaConsulta[0]->Entidad;
		$resultado->Consultas->HistoriaConsulta[0]->FechaConsulta;
		$resultado->Consultas->HistoriaConsulta[0]->Cantidad;
		*/	
		return $datView;
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
			$this->load->model("Legal_Model");  
			$this->load->model("Natural_Model");  
			$this->load->model("Customer_Model");  
			
			//Nuevo Registro
			$companyID 			= $this->input->post("companyID");
			$branchID 			= $this->input->post("branchID");				
			$entityID 			= $this->input->post("entityID");				
			
			if((!$companyID && !$branchID && !$entityID)){
					throw new Exception(NOT_PARAMETER);			
					 
			} 
			
			//OBTENER EL CLIENTE
			$objCustomer 		= $this->Customer_Model->get_rowByPK($companyID,$branchID,$entityID);	
			
			
			//PERMISO SOBRE EL REGISTRO
			if ($resultPermission 	== PERMISSION_ME && ($objCustomer->createdBy != $dataSession["user"]->userID))
			throw new Exception(NOT_DELETE);
			
			
			//PERMISO PUEDE ELIMINAR EL REGISTRO SEGUN EL WORKFLOW
			if(!$this->core_web_workflow->validateWorkflowStage("tb_customer","statusID",$objCustomer->statusID,COMMAND_ELIMINABLE,$dataSession["user"]->companyID,$dataSession["user"]->branchID,$dataSession["role"]->roleID))
			throw new Exception(NOT_WORKFLOW_DELETE);
			
			//Eliminar el Registro
			$this->Customer_Model->delete($companyID,$branchID,$entityID);
					
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
			
			$this->load->model("Customer_Model");	
			$this->load->model("Entity_Model");
			$this->load->model('Legal_Model');
			$this->load->model("Natural_Model");
			$this->load->model("Entity_Phone_Model");	
			$this->load->model("Entity_Email_Model");
			$this->load->model("Customer_Credit_Line_Model");
			$this->load->model("Customer_Credit_Model");
			$this->load->model("Company_Currency_Model");
			$this->load->model("Entity_Account_Model");
			$this->load->model("Account_Model");
			
			//Obtener el Componente de Transacciones Other Input to Inventory
			$objComponent							= $this->core_web_tools->getComponentIDBy_ComponentName("tb_customer");
			if(!$objComponent)
			throw new Exception("EL COMPONENTE 'tb_customer' NO EXISTE...");
			
			
			$branchID 								= $dataSession["user"]->branchID;
			$roleID 								= $dataSession["role"]->roleID;
			$companyID 								= $dataSession["user"]->companyID;
			
			//Moneda Dolares
			date_default_timezone_set(APP_TIMEZONE); 
			$objCurrencyDolares						= $this->core_web_currency->getCurrencyReport($companyID);
			$dateOn 								= date("Y-m-d");
			$dateOn 								= date_format(date_create($dateOn),"Y-m-d");
			$exchangeRate 							= 0;
			$exchangeRateTotal 						= 0;
			$exchangeRateAmount 					= 0;
			
			$companyID_ 							= $this->input->post("txtCompanyID");
			$branchID_								= $this->input->post("txtBranchID");
			$entityID_								= $this->input->post("txtEntityID");
			
			$objCustomer							= $this->Customer_Model->get_rowByPK($companyID_,$branchID_,$entityID_);
			$oldStatusID 							= $objCustomer->statusID;
			
			//Validar Edicion por el Usuario
			if ($resultPermission 	== PERMISSION_ME && ($objCustomer->createdBy != $dataSession["user"]->userID))
			throw new Exception(NOT_EDIT);
			
			//Validar si el estado permite editar
			if(!$this->core_web_workflow->validateWorkflowStage("tb_customer","statusID",$objCustomer->statusID,COMMAND_EDITABLE_TOTAL,$dataSession["user"]->companyID,$dataSession["user"]->branchID,$dataSession["role"]->roleID))
			throw new Exception(NOT_WORKFLOW_EDIT);					
			
			
			
			$this->db->trans_begin();			
			//El Estado solo permite editar el workflow
			if($this->core_web_workflow->validateWorkflowStage("tb_customer","statusID",$oldStatusID,COMMAND_EDITABLE,$dataSession["user"]->companyID,$dataSession["user"]->branchID,$dataSession["role"]->roleID)){				
				$objCustomer["statusID"] 		= $this->input->post('txtStatusID','');
				$this->Customer_Model->update($companyID_,$branchID_,$entityID_,$objCustomer);
			}
			else{
				$objNatural["isActive"]		= true;
				$objNatural["firstName"]	= $this->input->post("txtFirstName",'');
				$objNatural["lastName"]		= $this->input->post("txtLastName",'');
				$objNatural["address"]		= $this->input->post("txtAddress",'');
				$this->Natural_Model->update($companyID_,$branchID_,$entityID_,$objNatural);

				$objLegal["isActive"]		= true;
				$objLegal["comercialName"]	= $this->input->post("txtCommercialName",'');
				$objLegal["legalName"]		= $this->input->post("txtLegalName",'');
				$objLegal["address"]		= $this->input->post("txtAddress",'');
				$this->Legal_Model->update($companyID_,$branchID_,$entityID_,$objLegal);
				
				$objCustomer 						= NULL;
				$objCustomer["identificationType"]	= $this->input->post('txtIdentificationTypeID','');
				$objCustomer["identification"]		= $this->input->post('txtIdentification','');
				$objCustomer["countryID"]			= $this->input->post('txtCountryID','');
				$objCustomer["stateID"]				= $this->input->post('txtStateID','');
				$objCustomer["cityID"]				= $this->input->post("txtCityID",'');
				$objCustomer["location"]			= $this->input->post("txtLocation",'');
				$objCustomer["address"]				= $this->input->post("txtAddress",'');
				$objCustomer["currencyID"]			= $this->input->post("txtCurrencyID",'');
				$objCustomer["clasificationID"]		= $this->input->post('txtClasificationID','');
				$objCustomer["categoryID"]			= $this->input->post('txtCategoryID','');
				$objCustomer["subCategoryID"]		= $this->input->post('txtSubCategoryID','');
				$objCustomer["customerTypeID"]		= $this->input->post("txtCustomerTypeID",'');
				$objCustomer["birthDate"]			= $this->input->post("txtBirthDate",'');
				$objCustomer["statusID"]			= $this->input->post('txtStatusID','');
				$objCustomer["typePay"]				= $this->input->post('txtTypePayID','');
				$objCustomer["payConditionID"]		= $this->input->post('txtPayConditionID','');
				$objCustomer["sexoID"]				= $this->input->post('txtSexoID','');
				$objCustomer["reference1"]			= $this->input->post("txtReference1",'');
				$objCustomer["reference2"]			= $this->input->post("txtReference2",'');
				$objCustomer["isActive"]			= true;
				$this->Customer_Model->update($companyID_,$branchID_,$entityID_,$objCustomer);
				
				//Actualizar Customer Credit
				$objCustomerCredit 							= $this->Customer_Credit_Model->get_rowByPK($companyID_,$branchID_,$entityID_);
				$objCustomerCreditNew["limitCreditDol"] 	= helper_StringToNumber($this->input->post("txtLimitCreditDol",'0'));
				$objCustomerCreditNew["balanceDol"] 		= $objCustomerCreditNew["limitCreditDol"] - ($objCustomerCredit->limitCreditDol - $objCustomerCredit->balanceDol);
				$objCustomerCreditNew["incomeDol"] 			= helper_StringToNumber($this->input->post("txtIncomeDol",'0'));
				$this->Customer_Credit_Model->update($companyID_,$branchID_,$entityID_,$objCustomerCreditNew);
				
				//actualizar cuenta
				$objListEntityAccount 					= $this->Entity_Account_Model->get_rowByEntity($companyID_,$objComponent->componentID,$entityID_);
				$objFirstEntityAccount					= $objListEntityAccount[0];
				$objEntityAccount["accountID"]			= $this->input->post("txtAccountID",'');
				$this->Entity_Account_Model->update($objFirstEntityAccount->entityAccountID,$objEntityAccount);
			
			}
			
			
			//Email
			$this->Entity_Email_Model->deleteByEntity($companyID_,$branchID_,$entityID_);
			$arrayListEntityEmail 				= $this->input->post("txtEntityEmail");
			$arrayListEntityEmailIsPrimary		= $this->input->post("txtEmailIsPrimary");			
			if(!empty($arrayListEntityEmail))
			foreach($arrayListEntityEmail as $key => $value){
				$objEntityEmail["companyID"]	= $companyID_;
				$objEntityEmail["branchID"]		= $branchID_;
				$objEntityEmail["entityID"]		= $entityID_;
				$objEntityEmail["email"]		= $value;
				$objEntityEmail["isPrimary"]	= $arrayListEntityEmailIsPrimary[$key] == 1 ? true : false;
				$this->Entity_Email_Model->insert($objEntityEmail);
			}
			
			//Phone
			$this->Entity_Phone_Model->deleteByEntity($companyID_,$branchID_,$entityID_);
			$arrayListEntityPhoneTypeID			= $this->input->post("txtEntityPhoneTypeID");
			$arrayListEntityPhoneNumber 		= $this->input->post("txtEntityPhoneNumber");
			$arrayListEntityPhoneIsPrimary 		= $this->input->post("txtEntityPhoneIsPrimary");			
			if(!empty($arrayListEntityPhoneTypeID))
			foreach($arrayListEntityPhoneTypeID as $key => $value){
				$objEntityPhone["companyID"]	= $companyID_;
				$objEntityPhone["branchID"]		= $branchID_;
				$objEntityPhone["entityID"]		= $entityID_;
				$objEntityPhone["typeID"]		= $value;
				$objEntityPhone["number"]		= $arrayListEntityPhoneNumber[$key];
				$objEntityPhone["isPrimary"]	= $arrayListEntityPhoneIsPrimary[$key];
				$this->Entity_Phone_Model->insert($objEntityPhone);
			}	
			
			//Lineas de Creditos
			$arrayListCustomerCreditLineID	= $this->input->post("txtCustomerCreditLineID");
			$arrayListCreditLineID			= $this->input->post("txtCreditLineID");
			$arrayListCreditCurrencyID		= $this->input->post("txtCreditCurrencyID");
			$arrayListCreditStatusID		= $this->input->post("txtCreditStatusID");
			$arrayListCreditInterestYear	= $this->input->post("txtCreditInterestYear");
			$arrayListCreditInterestPay		= $this->input->post("txtCreditInterestPay");
			$arrayListCreditTotalPay		= $this->input->post("txtCreditTotalPay");
			$arrayListCreditTotalDefeated	= $this->input->post("txtCreditTotalDefeated");
			$arrayListCreditDateOpen		= $this->input->post("txtCreditDateOpen");
			$arrayListCreditPeriodPay		= $this->input->post("txtCreditPeriodPay");
			$arrayListCreditDateLastPay		= $this->input->post("txtCreditDateLastPay");
			$arrayListCreditTerm			= $this->input->post("txtCreditTerm");
			$arrayListCreditNote			= $this->input->post("txtCreditNote");
			$arrayListCreditLine			= $this->input->post("txtLine");
			$arrayListCreditNumber			= $this->input->post("txtLineNumber");
			$arrayListCreditLimit			= $this->input->post("txtLineLimit");
			$arrayListCreditBalance			= $this->input->post("txtLineBalance");
			$arrayListCreditStatus			= $this->input->post("txtLineStatus");
			$arrayListTypeAmortization		= $this->input->post("txtTypeAmortization");
			$limitCreditLine 				= 0;
			//Limpiar Lineas de Creditos
			$this->Customer_Credit_Line_Model->deleteWhereIDNotIn($companyID_,$branchID_,$entityID_,$arrayListCustomerCreditLineID);
			
			if(!empty($arrayListCustomerCreditLineID))
			foreach($arrayListCustomerCreditLineID as $key => $value){
			
				$customerCreditLineID 						= $value;
				if($customerCreditLineID == 0 ){
					$objCustomerCreditLine					= NULL;
					$objCustomerCreditLine["companyID"]		= $companyID_;
					$objCustomerCreditLine["branchID"]		= $branchID_;
					$objCustomerCreditLine["entityID"]		= $entityID_;
					$objCustomerCreditLine["creditLineID"]	= $arrayListCreditLineID[$key];
					$objCustomerCreditLine["accountNumber"]	= $this->core_web_counter->goNextNumber($dataSession["user"]->companyID,$dataSession["user"]->branchID,"tb_customer_credit_line",0);
					$objCustomerCreditLine["currencyID"]	= $arrayListCreditCurrencyID[$key];
					$objCustomerCreditLine["limitCredit"]	= helper_StringToNumber($arrayListCreditLimit[$key]);
					$objCustomerCreditLine["balance"]		= helper_StringToNumber($arrayListCreditLimit[$key]);
					$objCustomerCreditLine["interestYear"]	= helper_StringToNumber($arrayListCreditInterestYear[$key]);
					$objCustomerCreditLine["interestPay"]	= $arrayListCreditInterestPay[$key];
					$objCustomerCreditLine["totalPay"]		= $arrayListCreditTotalPay[$key];
					$objCustomerCreditLine["totalDefeated"]	= $arrayListCreditTotalDefeated[$key];
					$objCustomerCreditLine["dateOpen"]		= date("Y-m-d");
					$objCustomerCreditLine["periodPay"]		= $arrayListCreditPeriodPay[$key];
					$objCustomerCreditLine["dateLastPay"]	= NULL;
					$objCustomerCreditLine["term"]			= helper_StringToNumber($arrayListCreditTerm[$key]);
					$objCustomerCreditLine["note"]			= $arrayListCreditNote[$key];
					$objCustomerCreditLine["statusID"]		= $arrayListCreditStatusID[$key];
					$objCustomerCreditLine["isActive"]		= 1;
					$objCustomerCreditLine["typeAmortization"]		= $arrayListTypeAmortization[$key];
					$limitCreditLine 								= $limitCreditLine + $objCustomerCreditLine["limitCredit"];
					$exchangeRate 									= $this->core_web_currency->getRatio($companyID,$dateOn,1,$objCurrencyDolares->currencyID,$objCustomerCreditLine["currencyID"]);
					$exchangeRateAmount								= $objCustomerCreditLine["limitCredit"];
					$this->Customer_Credit_Line_Model->insert($objCustomerCreditLine);
					
					if($objCustomerCreditLine["balance"] > $objCustomerCreditLine["limitCredit"])
					throw new Exception("BALANCE NO PUEDE SER MAYOR QUE EL LIMITE EN LA LINEA");
				}
				else{					
					$objCustomerCreditLine 							= $this->Customer_Credit_Line_Model->get_rowByPK($customerCreditLineID);
					$objCustomerCreditLineNew						= NULL;
					$objCustomerCreditLineNew["creditLineID"]		= $arrayListCreditLineID[$key];
					$objCustomerCreditLineNew["limitCredit"]		= helper_StringToNumber($arrayListCreditLimit[$key]);
					$objCustomerCreditLineNew["interestYear"]		= helper_StringToNumber($arrayListCreditInterestYear[$key]);
					$objCustomerCreditLineNew["balance"] 			= $objCustomerCreditLineNew["limitCredit"] - ($objCustomerCreditLine->limitCredit - $objCustomerCreditLine->balance);
					$objCustomerCreditLineNew["periodPay"]			= $arrayListCreditPeriodPay[$key];
					$objCustomerCreditLineNew["term"]				= helper_StringToNumber($arrayListCreditTerm[$key]);
					$objCustomerCreditLineNew["note"]				= $arrayListCreditNote[$key];
					$objCustomerCreditLineNew["statusID"]			= $arrayListCreditStatusID[$key];
					$objCustomerCreditLineNew["typeAmortization"]		= $arrayListTypeAmortization[$key];
					$limitCreditLine 									= $limitCreditLine + $objCustomerCreditLineNew["limitCredit"];
					$exchangeRate 										= $this->core_web_currency->getRatio($companyID,$dateOn,1,$objCurrencyDolares->currencyID,$objCustomerCreditLine->currencyID);
					$exchangeRateAmount									= $objCustomerCreditLineNew["limitCredit"];
					
					//Si el balance es mayor que el limite igual el balance al limite
					if($objCustomerCreditLineNew["balance"] > $objCustomerCreditLineNew["limitCredit"])
					$objCustomerCreditLineNew["balance"] = $objCustomerCreditLineNew["limitCredit"];
					
					//actualizar
					$this->Customer_Credit_Line_Model->update($customerCreditLineID,$objCustomerCreditLineNew);
					
					
			
				}
				
				//sumar los limites en dolares
				if($exchangeRate == 1)
					$exchangeRateTotal = $exchangeRateTotal + $exchangeRateAmount;
				//sumar los limite en cordoba
				else
					$exchangeRateTotal = $exchangeRateTotal + ($exchangeRateAmount / $exchangeRate);
					
				
			}
			
			//Validar Limite de Credito
			if($exchangeRateTotal > $objCustomerCreditNew["limitCreditDol"])
			throw new Exception("LINEAS DE CREDITOS MAL CONFIGURADAS LÍMITE EXCEDIDO");
			
			//Actualizar Balance
			if($objCustomerCreditNew["balanceDol"] > $objCustomerCreditNew["limitCreditDol"]){
				$objCustomerCreditNew["balanceDol"] = $objCustomerCreditNew["limitCreditDol"];
				$this->Customer_Credit_Model->update($companyID_,$branchID_,$entityID_,$objCustomerCreditNew);
			}
			
			//Confirmar Entidad
			if($this->db->trans_status() !== false){
				$this->db->trans_commit();						
				$this->core_web_notification->set_message(false,SUCCESS);
				redirect('app_cxc_customer/edit/companyID/'.$companyID_."/branchID/".$branchID_."/entityID/".$entityID_);
			}
			else{
				$this->db->trans_rollback();						
				$this->core_web_notification->set_message(true,$this->db->_error_message());
				redirect('app_cxc_customer/add');	
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
			
			$this->load->model("Customer_Model");	
			$this->load->model("Entity_Model");
			$this->load->model('Legal_Model');
			$this->load->model("Natural_Model");
			$this->load->model("Entity_Phone_Model");	
			$this->load->model("Entity_Email_Model");
			$this->load->model("Customer_Credit_Line_Model");
			$this->load->model("Customer_Credit_Model");
			$this->load->model("Company_Currency_Model");
			$this->load->model("Entity_Account_Model");
			$this->load->model("Account_Model");
			
			
						
			
			//Obtener el Componente de Transacciones Other Input to Inventory
			$objComponent							= $this->core_web_tools->getComponentIDBy_ComponentName("tb_customer");
			if(!$objComponent)
			throw new Exception("EL COMPONENTE 'tb_customer' NO EXISTE...");
			
			//Obtener transaccion
			$companyID 								= $dataSession["user"]->companyID;			
			$objEntity["companyID"] 				= $dataSession["user"]->companyID;			
			$objEntity["branchID"]					= $dataSession["user"]->branchID;			
			$this->core_web_auditoria->setAuditCreated($objEntity,$dataSession);
			
			//Moneda Dolares
			date_default_timezone_set(APP_TIMEZONE); 
			$objCurrencyDolares						= $this->core_web_currency->getCurrencyReport($companyID);
			$dateOn 								= date("Y-m-d");
			$dateOn 								= date_format(date_create($dateOn),"Y-m-d");
			$exchangeRate 							= 0;
			$exchangeRateTotal 						= 0;
			$exchangeRateAmount 					= 0;
			
			
			$this->db->trans_begin();
			$entityID = $this->Entity_Model->insert($objEntity);
			
			$objNatural["companyID"]	= $objEntity["companyID"];
			$objNatural["branchID"] 	= $objEntity["branchID"];
			$objNatural["entityID"]		= $entityID;
			$objNatural["isActive"]		= true;
			$objNatural["firstName"]	= $this->input->post("txtFirstName",'');
			$objNatural["lastName"]		= $this->input->post("txtLastName",'');
			$objNatural["address"]		= $this->input->post("txtAddress",'');
			$result 					= $this->Natural_Model->insert($objNatural);
			
			$objLegal["companyID"]		= $objEntity["companyID"];
			$objLegal["branchID"]		= $objEntity["branchID"];
			$objLegal["entityID"]		= $entityID;
			$objLegal["isActive"]		= true;
			$objLegal["comercialName"]	= $this->input->post("txtCommercialName",'');
			$objLegal["legalName"]		= $this->input->post("txtLegalName",'');
			$objLegal["address"]		= $this->input->post("txtAddress",'');
			$result 					= $this->Legal_Model->insert($objLegal);
			
			$objCustomer["companyID"]			= $objEntity["companyID"];
			$objCustomer["branchID"]			= $objEntity["branchID"];
			$objCustomer["entityID"]			= $entityID;
			$objCustomer["customerNumber"]		= $this->core_web_counter->goNextNumber($dataSession["user"]->companyID,$dataSession["user"]->branchID,"tb_customer",0);
			$objCustomer["identificationType"]	= $this->input->post('txtIdentificationTypeID','');
			$objCustomer["identification"]		= $this->input->post('txtIdentification','');
			$objCustomer["countryID"]			= $this->input->post('txtCountryID','');
			$objCustomer["stateID"]				= $this->input->post('txtStateID','');
			$objCustomer["cityID"]				= $this->input->post("txtCityID",'');
			$objCustomer["location"]			= $this->input->post("txtLocation",'');
			$objCustomer["address"]				= $this->input->post("txtAddress",'');
			$objCustomer["currencyID"]			= $this->input->post("txtCurrencyID",'');
			$objCustomer["clasificationID"]		= $this->input->post('txtClasificationID','');
			$objCustomer["categoryID"]			= $this->input->post('txtCategoryID','');
			$objCustomer["subCategoryID"]		= $this->input->post('txtSubCategoryID','');
			$objCustomer["customerTypeID"]		= $this->input->post("txtCustomerTypeID",'');
			$objCustomer["birthDate"]			= $this->input->post("txtBirthDate",'');
			$objCustomer["statusID"]			= $this->input->post('txtStatusID','');
			$objCustomer["typePay"]				= $this->input->post('txtTypePayID','');
			$objCustomer["payConditionID"]		= $this->input->post('txtPayConditionID','');
			$objCustomer["sexoID"]				= $this->input->post('txtSexoID','');
			$objCustomer["reference1"]			= $this->input->post("txtReference1",'');
			$objCustomer["reference2"]			= $this->input->post("txtReference2",'');
			$objCustomer["isActive"]			= true;
			$this->core_web_auditoria->setAuditCreated($objCustomer,$dataSession);
			$result 							= $this->Customer_Model->insert($objCustomer);
			
			//Ingresar Cuenta
			$objEntityAccount["companyID"]			= $objEntity["companyID"];
			$objEntityAccount["componentID"]		= $objComponent->componentID;
			$objEntityAccount["componentItemID"]	= $entityID;
			$objEntityAccount["name"]				= "";
			$objEntityAccount["description"]		= "";
			$objEntityAccount["accountTypeID"]		= "0";
			$objEntityAccount["currencyID"]			= "0";
			$objEntityAccount["classID"]			= "0";
			$objEntityAccount["balance"]			= "0";
			$objEntityAccount["creditLimit"]		= "0";
			$objEntityAccount["maxCredit"]			= "0";
			$objEntityAccount["debitLimit"]			= "0";
			$objEntityAccount["maxDebit"]			= "0";
			$objEntityAccount["statusID"]			= "0";
			$objEntityAccount["accountID"]			= $this->input->post("txtAccountID",'');
			$objEntityAccount["statusID"]			= "0";
			$objEntityAccount["isActive"]			= "1";
			$this->core_web_auditoria->setAuditCreated($objEntityAccount,$dataSession);
			$this->Entity_Account_Model->insert($objEntityAccount);
			
			//Ingresar Customer Credit
			$objCustomerCredit["companyID"] 		= $objEntity["companyID"];
			$objCustomerCredit["branchID"] 			= $objEntity["branchID"];
			$objCustomerCredit["entityID"] 			= $entityID;
			$objCustomerCredit["limitCreditDol"] 	= helper_StringToNumber($this->input->post("txtLimitCreditDol",'0'));
			$objCustomerCredit["balanceDol"] 		= $objCustomerCredit["limitCreditDol"];
			$objCustomerCredit["incomeDol"] 		= helper_StringToNumber($this->input->post("txtIncomeDol",'0'));
			$this->Customer_Credit_Model->insert($objCustomerCredit);
			
			//Email
			$arrayListEntityEmail 				= $this->input->post("txtEntityEmail");
			$arrayListEntityEmailIsPrimary		= $this->input->post("txtEmailIsPrimary");			
			if(!empty($arrayListEntityEmail))
			foreach($arrayListEntityEmail as $key => $value){
				$objEntityEmail["companyID"]	= $objEntity["companyID"];
				$objEntityEmail["branchID"]		= $objEntity["branchID"];
				$objEntityEmail["entityID"]		= $entityID;
				$objEntityEmail["email"]		= $value;
				$objEntityEmail["isPrimary"]	= $arrayListEntityEmailIsPrimary[$key];
				$this->Entity_Email_Model->insert($objEntityEmail);
			}
			
			//Phone
			$arrayListEntityPhoneTypeID			= $this->input->post("txtEntityPhoneTypeID");
			$arrayListEntityPhoneNumber 		= $this->input->post("txtEntityPhoneNumber");
			$arrayListEntityPhoneIsPrimary 		= $this->input->post("txtEntityPhoneIsPrimary");			
			if(!empty($arrayListEntityPhoneTypeID))
			foreach($arrayListEntityPhoneTypeID as $key => $value){
				$objEntityPhone["companyID"]	= $objEntity["companyID"];
				$objEntityPhone["branchID"]		= $objEntity["branchID"];
				$objEntityPhone["entityID"]		= $entityID;
				$objEntityPhone["typeID"]		= $value;
				$objEntityPhone["number"]		= $arrayListEntityPhoneNumber[$key];
				$objEntityPhone["isPrimary"]	= $arrayListEntityPhoneIsPrimary[$key];
				$this->Entity_Phone_Model->insert($objEntityPhone);
			}
			
			//Lineas de Creditos
			$arrayListCustomerCreditLineID	= $this->input->post("txtCustomerCreditLineID");
			$arrayListCreditLineID			= $this->input->post("txtCreditLineID");
			$arrayListCreditCurrencyID		= $this->input->post("txtCreditCurrencyID");
			$arrayListCreditStatusID		= $this->input->post("txtCreditStatusID");
			$arrayListCreditInterestYear	= $this->input->post("txtCreditInterestYear");
			$arrayListCreditInterestPay		= $this->input->post("txtCreditInterestPay");
			$arrayListCreditTotalPay		= $this->input->post("txtCreditTotalPay");
			$arrayListCreditTotalDefeated	= $this->input->post("txtCreditTotalDefeated");
			$arrayListCreditDateOpen		= $this->input->post("txtCreditDateOpen");
			$arrayListCreditPeriodPay		= $this->input->post("txtCreditPeriodPay");
			$arrayListCreditDateLastPay		= $this->input->post("txtCreditDateLastPay");
			$arrayListCreditTerm			= $this->input->post("txtCreditTerm");
			$arrayListCreditNote			= $this->input->post("txtCreditNote");
			$arrayListCreditLine			= $this->input->post("txtLine");
			$arrayListCreditNumber			= $this->input->post("txtLineNumber");
			$arrayListCreditLimit			= $this->input->post("txtLineLimit");
			$arrayListCreditBalance			= $this->input->post("txtLineBalance");
			$arrayListCreditStatus			= $this->input->post("txtLineStatus");
			$arrayListTypeAmortization		= $this->input->post("txtTypeAmortization");
			
			$limitCreditLine 				= 0;
			if(!empty($arrayListCustomerCreditLineID))
			foreach($arrayListCustomerCreditLineID as $key => $value){
				$objCustomerCreditLine["companyID"]		= $objEntity["companyID"];
				$objCustomerCreditLine["branchID"]		= $objEntity["branchID"];
				$objCustomerCreditLine["entityID"]		= $entityID;
				$objCustomerCreditLine["creditLineID"]	= $arrayListCreditLineID[$key];
				$objCustomerCreditLine["accountNumber"]	= $this->core_web_counter->goNextNumber($dataSession["user"]->companyID,$dataSession["user"]->branchID,"tb_customer_credit_line",0);
				$objCustomerCreditLine["currencyID"]	= $arrayListCreditCurrencyID[$key];
				$objCustomerCreditLine["limitCredit"]	= helper_StringToNumber($arrayListCreditLimit[$key]);
				$objCustomerCreditLine["balance"]		= helper_StringToNumber($arrayListCreditLimit[$key]);
				$objCustomerCreditLine["interestYear"]	= helper_StringToNumber($arrayListCreditInterestYear[$key]);
				$objCustomerCreditLine["interestPay"]	= $arrayListCreditInterestPay[$key];
				$objCustomerCreditLine["totalPay"]		= $arrayListCreditTotalPay[$key];
				$objCustomerCreditLine["totalDefeated"]	= $arrayListCreditTotalDefeated[$key];
				$objCustomerCreditLine["dateOpen"]		= date("Y-m-d");
				$objCustomerCreditLine["periodPay"]		= $arrayListCreditPeriodPay[$key];
				$objCustomerCreditLine["dateLastPay"]	= NULL;
				$objCustomerCreditLine["term"]			= helper_StringToNumber($arrayListCreditTerm[$key]);
				$objCustomerCreditLine["note"]			= $arrayListCreditNote[$key];
				$objCustomerCreditLine["statusID"]		= $arrayListCreditStatusID[$key];
				$objCustomerCreditLine["isActive"]		= 1;
				$objCustomerCreditLine["typeAmortization"]	= $arrayListTypeAmortization[$key];
				$limitCreditLine 							= $limitCreditLine + $objCustomerCreditLine["limitCredit"];
				$exchangeRate 								= $this->core_web_currency->getRatio($companyID,$dateOn,1,$objCurrencyDolares->currencyID,$objCustomerCreditLine["currencyID"]);
				$exchangeRateAmount							= $objCustomerCreditLine["limitCredit"];
				$this->Customer_Credit_Line_Model->insert($objCustomerCreditLine);
				
				//sumar los limites en dolares
				if($exchangeRate == 1)
					$exchangeRateTotal = $exchangeRateTotal + $exchangeRateAmount;
				//sumar los limite en cordoba
				else
					$exchangeRateTotal = $exchangeRateTotal + ($exchangeRateAmount / $exchangeRate);
				
				
			}
			
			//Validar Limite de Credito
			if($exchangeRateTotal > $objCustomerCredit["limitCreditDol"])
			throw new Exception("LINEAS DE CREDITOS MAL CONFIGURADAS LÍMITE EXCEDIDO");
			
			//Crear la Carpeta para almacenar los Archivos del Cliente
			mkdir(PATH_FILE_OF_APP."/company_".$companyID."/component_".$objComponent->componentID."/component_item_".$entityID, 0700);
			
			if($this->db->trans_status() !== false){
				$this->db->trans_commit();						
				$this->core_web_notification->set_message(false,SUCCESS);
				redirect('app_cxc_customer/edit/companyID/'.$companyID."/branchID/".$objEntity["branchID"]."/entityID/".$entityID);
			}
			else{
				$this->db->trans_rollback();						
				$this->core_web_notification->set_message(true,$this->db->_error_message());
				redirect('app_cxc_customer/add');	
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
			$this->form_validation->set_rules("txtCountryID","Pais","required");
			$this->form_validation->set_rules("txtStateID","Departamento","required");
			$this->form_validation->set_rules("txtCityID","Municipio","required");
			$this->form_validation->set_rules("txtIdentification","Identificacion","required");
				
				
			//Validar Formulario
			if(!$this->form_validation->run()){
				$stringValidation = $this->core_web_tools->formatMessageError(validation_errors());
				$this->core_web_notification->set_message(true,$stringValidation);
				redirect('app_cxc_customer/add');
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
				redirect('app_cxc_customer/add');
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
			 
			$this->load->model("Company_Currency_Model");			
			$dataView							= null;
			
			//Obtener Tasa de Cambio			
			$companyID 							= $dataSession["user"]->companyID;
			$branchID 							= $dataSession["user"]->branchID;
			$roleID 							= $dataSession["role"]->roleID;
			
			$objComponentAccount				= $this->core_web_tools->getComponentIDBy_ComponentName("tb_account");
			if(!$objComponentAccount)
			throw new Exception("EL COMPONENTE 'tb_account' NO EXISTE...");
			
			
			$dataView["objListWorkflowStage"]			= $this->core_web_workflow->getWorkflowInitStage("tb_customer","statusID",$companyID,$branchID,$roleID);
			$dataView["objListIdentificationType"]		= $this->core_web_catalog->getCatalogAllItem("tb_customer","identificationType",$companyID);
			$dataView["objListCountry"]					= $this->core_web_catalog->getCatalogAllItem("tb_customer","countryID",$companyID);
			$dataView["objListClasificationID"]			= $this->core_web_catalog->getCatalogAllItem("tb_customer","clasificationID",$companyID);
			$dataView["objListCustomerTypeID"]			= $this->core_web_catalog->getCatalogAllItem("tb_customer","customerTypeID",$companyID);
			$dataView["objListCategoryID"]				= $this->core_web_catalog->getCatalogAllItem("tb_customer","categoryID",$companyID);
			$dataView["objListSubCategoryID"]			= $this->core_web_catalog->getCatalogAllItem("tb_customer","subCategoryID",$companyID);
			$dataView["objListTypePay"]					= $this->core_web_catalog->getCatalogAllItem("tb_customer","typePay",$companyID);
			$dataView["objListPayConditionID"]			= $this->core_web_catalog->getCatalogAllItem("tb_customer","payConditionID",$companyID);
			$dataView["objListSexoID"]					= $this->core_web_catalog->getCatalogAllItem("tb_customer","sexoID",$companyID);
			$dataView["objListCurrency"]				= $this->Company_Currency_Model->getByCompany($companyID);
			$objCurrency								= $this->core_web_currency->getCurrencyDefault($companyID);			
			$dataView["objCurrency"]					= $objCurrency;
			$dataView["objComponentAccount"]			= $objComponentAccount;
			
			
			
			//Renderizar Resultado
			$dataSession["notification"]	= $this->core_web_error->get_error($dataSession["user"]->userID);			
			$dataSession["message"]			= $this->core_web_notification->get_message();
			$dataSession["head"]			= $this->load->view('app_cxc_customer/news_head',$dataView,true);
			$dataSession["body"]			= $this->load->view('app_cxc_customer/news_body',$dataView,true);
			$dataSession["script"]			= $this->load->view('app_cxc_customer/news_script',$dataView,true);  
			$dataSession["footer"]			= "";
			$this->load->view("core_masterpage/default_masterpage",$dataSession);	
			
		}
		catch(Exception $ex){
			show_error($ex->getLine()." ".$ex->getMessage() ,500 );
		}	
			
    }
	function edit_credit_line(){
			
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
			
			$this->load->model("Credit_Line_Model");
			$this->load->model("Company_Currency_Model");
			$this->load->model("Customer_Credit_Line_Model");
			
			
			$uri								= $this->uri->uri_to_assoc(3);
			$customerCreditLineID				= $uri["customerCreditLineID"];	
			$companyID 							= $dataSession["user"]->companyID;
			$branchID 							= $dataSession["user"]->branchID;
			$roleID 							= $dataSession["role"]->roleID;
			
			
			$dataView["objListLine"]			= $this->Credit_Line_Model->get_rowByCompany($companyID);
			$dataView["objCurrencyList"]		= $this->Company_Currency_Model->getByCompany($companyID);
			$dataView["objListWorkflowStage"]	= $this->core_web_workflow->getWorkflowInitStage("tb_customer_credit_line","statusID",$companyID,$branchID,$roleID);
			$dataView["objListPay"]				= $this->core_web_catalog->getCatalogAllItem("tb_customer_credit_line","periodPay",$companyID);
			$dataView["objCustomerCreditLine"] 	= $this->Customer_Credit_Line_Model->get_rowByPK($customerCreditLineID);
			$dataView["objListTypeAmortization"]= $this->core_web_catalog->getCatalogAllItem("tb_customer_credit_line","typeAmortization",$companyID);
			
			//Renderizar Resultado
			$dataSession["message"]		= "";
			$dataSession["head"]		= $this->load->view('app_cxc_customer/popup_editcreditline_head',$dataView,true);
			$dataSession["body"]		= $this->load->view('app_cxc_customer/popup_editcreditline_body',$dataView,true);
			$dataSession["script"]		= $this->load->view('app_cxc_customer/popup_editcreditline_script',$dataView,true);  
			$this->load->view("core_masterpage/default_popup",$dataSession);
	}
	function add_credit_line(){
			
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
			
			$this->load->model("Credit_Line_Model");
			$this->load->model("Company_Currency_Model");
			
			$companyID 								= $dataSession["user"]->companyID;
			$branchID 								= $dataSession["user"]->branchID;
			$roleID 								= $dataSession["role"]->roleID;
			$dataView["objListLine"]				= $this->Credit_Line_Model->get_rowByCompany($companyID);
			$dataView["objCurrencyList"]			= $this->Company_Currency_Model->getByCompany($companyID);
			$dataView["objListWorkflowStage"]		= $this->core_web_workflow->getWorkflowInitStage("tb_customer_credit_line","statusID",$companyID,$branchID,$roleID);
			$dataView["objListPay"]					= $this->core_web_catalog->getCatalogAllItem("tb_customer_credit_line","periodPay",$companyID);
			$dataView["objListTypeAmortization"]	= $this->core_web_catalog->getCatalogAllItem("tb_customer_credit_line","typeAmortization",$companyID);
			
			
			//Renderizar Resultado
			$dataSession["message"]		= "";
			$dataSession["head"]		= $this->load->view('app_cxc_customer/popup_addcreditline_head',$dataView,true);
			$dataSession["body"]		= $this->load->view('app_cxc_customer/popup_addcreditline_body',$dataView,true);
			$dataSession["script"]		= $this->load->view('app_cxc_customer/popup_addcreditline_script',$dataView,true);  
			$this->load->view("core_masterpage/default_popup",$dataSession);
	}
	function add_email(){
			
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
			
			
			//Renderizar Resultado
			$dataSession["message"]		= "";
			$dataSession["head"]		= $this->load->view('app_cxc_customer/popup_addemail_head','',true);
			$dataSession["body"]		= $this->load->view('app_cxc_customer/popup_addemail_body','',true);
			$dataSession["script"]		= $this->load->view('app_cxc_customer/popup_addemail_script','',true);  
			$this->load->view("core_masterpage/default_popup",$dataSession);
	}
	function add_phone(){
			
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
			
			$companyID 						= $dataSession["user"]->companyID;
			$data["objListPhoneTypeID"]		= $this->core_web_catalog->getCatalogAllItem("tb_entity_phone","typeID",$companyID);
			
			//Renderizar Resultado
			$dataSession["message"]		= "";
			$dataSession["head"]		= $this->load->view('app_cxc_customer/popup_addphone_head','',true);
			$dataSession["body"]		= $this->load->view('app_cxc_customer/popup_addphone_body',$data,true);
			$dataSession["script"]		= $this->load->view('app_cxc_customer/popup_addphone_script','',true);  
			$this->load->view("core_masterpage/default_popup",$dataSession);
	}
	
}
?>
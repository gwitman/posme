<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Core_acount extends CI_Controller {
	//Vista de Login
	function index(){
	
		//Renderizar		
		$data["message"]	= "";
		$this->load->view('core_acount/login',$data);
		
	
			
	}		
	function logout(){
		try{																
			$this->load->library('core_web_logs');			
			$dataSession	= $this->session->all_userdata(); 			
			
			/*
			//trigger_error("User error via trigger.", E_USER_ERROR);			
			//trigger_error("Warning error via trigger.", E_USER_WARNING);			
			*/						
		
			log_message("ERROR","punto de interrupcion logout 001");			
			trigger_error("Logout ----> ".print_r($dataSession["user"],true), E_USER_NOTICE);			
			log_message("ERROR","punto de interrupcion logout 002");
			$this->core_web_authentication->destroyLogin();			
			log_message("ERROR","punto de interrupcion logout 003");
			redirect(base_url());			
			log_message("ERROR","punto de interrupcion logout 004");			
		}
		catch(Exception $e){				
			log_message("ERROR","punto de interrupcion logout 005");
			show_error($e->getMessage() ,500 );
		}
	}
	function login(){
		$this->load->helper("cookie");		
		
		try{ 
			if(!isset($_POST["txtNickname"]) || !isset($_POST["txtPassword"]))
			throw new Exception(NOT_VALID_USER);
			$this->load->library('core_web_logs');
			$nickname 	= $_POST["txtNickname"];
			$password	= $_POST["txtPassword"];
			$objUser	= $this->core_web_authentication->get_UserBy_PasswordAndNickname($nickname,$password);			
			$data 		= $this->core_web_authentication->createLogin($objUser);
			$dataSession	= $this->session->all_userdata();	
			
			trigger_error("Login ----> ".print_r($dataSession["user"],true), E_USER_NOTICE);
			
			log_message('ERROR',"componenete:");
			$dataSession		= $this->session->all_userdata(); 
			
			
			//Obtener Datos 			
			log_message('ERROR',print_r($objUser["user"]->companyID,true));
			$parameterFechaExpiration = $this->core_web_parameter->getParameter("CORE_LICENSE_EXPIRED",$objUser["user"]->companyID);
			
			log_message('ERROR',print_r($parameterFechaExpiration,true));
			$parameterFechaExpiration = $parameterFechaExpiration->value;
			log_message('ERROR',print_r($parameterFechaExpiration,true));
			
			$parameterFechaExpiration = DateTime::createFromFormat('Y-m-d',$parameterFechaExpiration);
			log_message('ERROR',print_r($parameterFechaExpiration,true));
			
			//Validar Fecha de Expiracion
			$fechaNow  = DateTime::createFromFormat('Y-m-d',date("Y-m-d"));  
			
			log_message('ERROR',"validar fechas..");			
			log_message('ERROR',print_r($fechaNow,true));
			log_message('ERROR',print_r($parameterFechaExpiration,true));
			
			if( $fechaNow >  $parameterFechaExpiration ){
				throw new Exception('
				<p>La licencia a expirado.</p>
				<p>realizar el pago de la licencia onLine aquí o </p>
				<p>realizar la transferencia a la siguiente cuenta BAC Dolares: 366-577-484 </p>
				<p>telefono de contacto: 8712-5827 </p>
				');
			}
			
			//Establecer cooke de identificaion de usuario
			//log_message("ERROR",print_r($dataSession[user]->userID),true);
			//log_message("ERROR",print_r($dataSession[user]->nickname),true);
			//log_message("ERROR",print_r($dataSession[user]->email),true);
			
			$this->input->set_cookie("userID",$dataSession[user]->userID,43200,"localhost");
			$this->input->set_cookie("nickname",$dataSession[user]->nickname,43200,"localhost");
			$this->input->set_cookie("email",$dataSession[user]->email,43200,"localhost");			
			log_message('ERROR',print_r($this->session->userdata('user'),true));
			
			
			$this->email->set_mailtype('html');
			$this->email->from(EMAIL_APP, HELLOW);
			$this->email->to(EMAIL_APP_NOTIFICACION);
			$this->email->subject("LOGIN:NUEVO");
			$params_["message"]	= "Usuario Login: ".$nickname;
			$this->email->message($this->load->view('core_template/email_notificacion',$params_,true)); 
			$this->email->send();
			
			redirect($objUser["role"]->urlDefault);
		}
		catch(Exception $e){
			$data_message["txtMessage"]	= $e->getMessage();
			$data_login["message"]		= $data_message["txtMessage"];
			$this->load->view('core_acount/login',$data_login);
		}
	}
	function rememberpassword(){
		
		try{ 
			if(!isset($_POST["txtEmail"]))
			throw new Exception(NOT_VALID_EMAIL);
			
			$email 						= $_POST["txtEmail"];
			$objUser					=$this->core_web_authentication->get_UserBy_Email($email);
			
			/* 
			Enviar Email						
			Configurar system/library/email.php
			Configurar application/config/constant.php 
			*/
			
			/*			
			$this->email->set_mailtype('html');
			$this->email->from("nssystem@fidlocal.com", "Witman Jose Gonzalez Rostran");
			$this->email->to("gwitman@yahoo.com");
			$this->email->subject("Esto es una prueba");
			$this->email->message("Esto es una prueba...");
			$this->email->send();		 
			*/
		
			log_message("ERROR","punto de interrupcion 001");
			log_message("ERROR","punto de interrupcion 001 user: ".print_r($objUser["user"],true));
			
			$this->email->set_mailtype('html');
			$this->email->from(EMAIL_APP, HELLOW);
			$this->email->to($objUser["user"]->email);
			$this->email->subject(REMEMBER_PASSWORD);
			$this->email->message($this->load->view('core_template/email_remember_password',$objUser["user"],true)); 
			$this->email->send();
			
			//Notificar
			$data_message["txtMessage"]	= MESSAGE_EMAL;
			$data_login["message"]		= "";
			$data_login["status"]		= "PASSWORD REENVIADA";
			$this->load->view('core_acount/login',$data_login);
		}
		catch(Exception $e){
			$data_message["txtMessage"]	= $e->getMessage();
			$data_login["message"]		= $data_message["txtMessage"];
			$this->load->view('core_acount/login',$data_login);
		}
	}
	function edit(){
	 
		try{
			
			//Cargar Modelo
			$this->load->model("core/User_Model"); 			
			$this->load->model("Entity_Model");
			$this->load->model("Natural_Model");
			$this->load->model("Employee_Model");
			
			//Validar Authentication
			if(!$this->core_web_authentication->isAuthenticated())
			throw new Exception(USER_NOT_AUTENTICATED);
			$dataSession		= $this->session->all_userdata(); 
			 
			//Obtener Datos 
			$objUser["branchID"] 				= $dataSession["user"]->branchID;
			$objUser["companyID"] 				= $dataSession["user"]->companyID;
			$objUser["userID"] 					= $dataSession["user"]->userID;
			$objUser["nickname"] 				= $dataSession["user"]->nickname;
			$objUser["email"] 					= $dataSession["user"]->email;
			$objUser["password"] 				= $dataSession["user"]->password;						
			$objUser["employeeID"] 				= $dataSession["user"]->employeeID;			
			$continue							= true;  	    
			
			//Datos Requerido para que el Usuario pueda ser Seleccionado
			$this->form_validation->set_rules("txtEmail","Email Required","required");    
			$this->form_validation->set_rules("txtPassword","Password Required","required");    
			$this->form_validation->set_rules("txtNickname","Nickname Required","required");    
			 
			//Actualizar  
			if ($this->form_validation->run() == true && $continue) { 
				$objUser["email"] 					= $this->input->post('txtEmail');
				$objUser["password"] 				= $this->input->post('txtPassword');
				$objUser["nickname"] 				= $this->input->post('txtNickname');
					 
				//validar nickname
				$objUserTmp = $this->User_Model->get_rowByExistNickname($objUser["nickname"]);
				if($objUserTmp &&  $objUserTmp->userID != $dataSession["user"]->userID && $continue )
				{
					$continue	= false;
					$this->core_web_notification->set_message(true,NICKNAME_DUPLI);
				}   
				
				//validar email
				$objUserTmp = $this->User_Model->get_rowByEmail($objUser["email"]);
				if($objUserTmp &&  $objUserTmp->userID != $dataSession["user"]->userID && $continue)
				{ 
					$continue	= false;
					$this->core_web_notification->set_message(true,EMAIL_DUPLI);				
				} 
			 
				//actualizar
				$isSuccess 						= $this->User_Model->update($objUser["companyID"],$objUser["branchID"],$objUser["userID"],$objUser);				 				
				if($isSuccess){					
					$this->core_web_notification->set_message(false,SUCCESS);
				}
				else{ 
					$this->core_web_notification->set_message(true,ERROR);
				}  
				
			}
			
			//Obtener Entidad
			$objUser["objEntity"]				= $this->Entity_Model->get_rowByEntity($objUser["companyID"],$objUser["employeeID"]);
			//Obtener Empleado
			$objUser["objEmployee"]				= $objUser["objEntity"] == null ? null : $this->Employee_Model->get_rowByPK($objUser["objEntity"]->companyID,$objUser["objEntity"]->branchID,$objUser["objEntity"]->entityID);
			//Obtener El Natural
			$objUser["objEmployeeNatural"]		= $objUser["objEntity"] == null ? null : $this->Natural_Model->get_rowByPK($objUser["objEntity"]->companyID,$objUser["objEntity"]->branchID,$objUser["objEntity"]->entityID);
			
			
			//Renderizar Resultado 
			$dataSession["notification"]	= $this->core_web_error->get_error($dataSession["user"]->userID);
			$dataSession["message"]			= $this->core_web_notification->get_message();
			$dataSession["head"]			= $this->load->view('core_acount/edit_head','',true);			
			$dataSession["body"]			= $this->load->view('core_acount/edit_body',$objUser,true);
			$dataSession["script"]			= $this->load->view('core_acount/edit_script','',true); 
			$dataSession["footer"]			= "";   
			$this->load->view("core_masterpage/default_masterpage",$dataSession);		
			
		}
		catch(Exception $ex){
				show_error($ex->getMessage() ,500 );
		}
			 
	}
	
	
	function payment(){
		
		$this->load->library('core_web_pagadito/Pagadito');
		
		//produccion
		//$uidt = "7be685eb39ed6cac49de29bd36f4665e";
		//$wskt = "12ef04e7197c001b66920797fac63c46";
		
		//desarrollo
		$uidt = "35f6110eb79c3640a9bc35f876fe05f6";
		$wskt = "56720c930f874d4011ff7f3e2a86eddb";

		$urlProducto = "www.google.com";
		$cantidad = 1;
		$precio  = 25;
		$nombre = "licenemiento";
		$numberFactura = "FAC001";
		$sendBox = true;
		log_message("ERROR","cobrando 001");
		
		
		
		if (($cantidad * $precio) > 0) 
		{
			/*
			 * Lo primero es crear el objeto nusoap_client, al que se le pasa como
			 * parámetro la URL de Conexión definida en la constante WSPG
			 */
			 
			//$Pagadito = new Pagadito($uidt, $wskt);			
			$Pagadito = new Pagadito();
			$Pagadito->Init($uidt,$wskt);	
			
			/*
			 * Si se está realizando pruebas, necesita conectarse con Pagadito SandBox. Para ello llamamos
			 * a la función mode_sandbox_on(). De lo contrario omitir la siguiente linea.
			 */
			if ($sendBox) {
				$Pagadito->mode_sandbox_on();
				log_message("ERROR","cobrando 002");
			}
			
			log_message("ERROR","cobrando 003");
			/*
			 * Validamos la conexión llamando a la función connect(). Retorna
			 * true si la conexión es exitosa. De lo contrario retorna false
			 */
			if ($Pagadito->connect()) {
				log_message("ERROR","cobrando 004");
				/*
				 * Luego pasamos a agregar los detalles
				 */
				if ($cantidad > 0) {
					$Pagadito->add_detail($cantidad,$nombre, $precio,$urlProducto);
				}
				
				//Agregando campos personalizados de la transacción
				$Pagadito->set_custom_param("param1", "Valor de param1");
				$Pagadito->set_custom_param("param2", "Valor de param2");
				$Pagadito->set_custom_param("param3", "Valor de param3");
				$Pagadito->set_custom_param("param4", "Valor de param4");
				$Pagadito->set_custom_param("param5", "Valor de param5");
		
				//Habilita la recepción de pagos preautorizados para la orden de cobro.
				$Pagadito->enable_pending_payments();
		
				/*
				 * Lo siguiente es ejecutar la transacción, enviandole el ern.
				 *
				 * A manera de ejemplo el ern es generado como un número
				 * aleatorio entre 1000 y 2000. Lo ideal es que sea una
				 * referencia almacenada por el Pagadito Comercio.
				 */
				//$ern = rand(1000, 2000);
				$ern = $numberFactura;
				if (!$Pagadito->exec_trans($ern)) {
					/*
					 * En caso de fallar la transacción, verificamos el error devuelto.
					 * Debido a que la API nos puede devolver diversos mensajes de
					 * respuesta, validamos el tipo de mensaje que nos devuelve.
					 */
					switch($Pagadito->get_rs_code())
					{
						case "PG2001":
							/*Incomplete data*/
						case "PG3002":
							/*Error*/
						case "PG3003":
							/*Unregistered transaction*/
						case "PG3004":
							/*Match error*/
						case "PG3005":
							/*Disabled connection*/
						default:
							//echo "
							//	<SCRIPT>
							//		alert(\"".$Pagadito->get_rs_code().": ".$Pagadito->get_rs_message()."\");
							//		location.href = 'index.php';
							//	</SCRIPT>
							//";
							echo $Pagadito->get_rs_code().": ".$Pagadito->get_rs_message();
							break;
					}
				}
			}
			else {
				log_message("ERROR","cobrando 005");
				
				/*
				 * En caso de fallar la conexión, verificamos el error devuelto.
				 * Debido a que la API nos puede devolver diversos mensajes de
				 * respuesta, validamos el tipo de mensaje que nos devuelve.
				 */
				switch($Pagadito->get_rs_code())
				{
					case "PG2001":
						/*Incomplete data*/
					case "PG3001":
						/*Problem connection*/
					case "PG3002":
						/*Error*/
					case "PG3003":
						/*Unregistered transaction*/
					case "PG3005":
						/*Disabled connection*/
					case "PG3006":
						/*Exceeded*/
					default:
						//echo "
						//	<SCRIPT>
						//		alert(\"".$Pagadito->get_rs_code().": ".$Pagadito->get_rs_message()."\");
						//		location.href = 'index.php';
						//	</SCRIPT>
						//";
						echo $Pagadito->get_rs_code().": ".$Pagadito->get_rs_message();
						break;
				}
			}
		
		} 
		
	}
	function paymentBack(){
		$this->load->library('core_web_pagadito/Pagadito.php');
		
		$uidt = "7be685eb39ed6cac49de29bd36f4665e";
		$wskt = "12ef04e7197c001b66920797fac63c46";
		$sendBox = true;
		
		if (isset($_GET["token"]) && $_GET["token"] != "") 
		{
			/*
			 * Lo primero es crear el objeto Pagadito, al que se le pasa como
			 * parámetros el UID y el WSK definidos en config.php
			 */
			$Pagadito = new Pagadito($uidt, $wskt);
			/*
			 * Si se está realizando pruebas, necesita conectarse con Pagadito SandBox. Para ello llamamos
			 * a la función mode_sandbox_on(). De lo contrario omitir la siguiente linea.
			 */
			if ($sendBox) {
				$Pagadito->mode_sandbox_on();
			}
			/*
			 * Validamos la conexión llamando a la función connect(). Retorna
			 * true si la conexión es exitosa. De lo contrario retorna false
			 */
			if ($Pagadito->connect()) {
				/*
				 * Solicitamos el estado de la transacción llamando a la función
				 * get_status(). Le pasamos como parámetro el token recibido vía
				 * GET en nuestra URL de retorno.
				 */
				if ($Pagadito->get_status($_GET["token"])) {
					/*
					 * Luego validamos el estado de la transacción, consultando el
					 * estado devuelto por la API.
					 */
					switch($Pagadito->get_rs_status())
					{
						case "COMPLETED":
							/*
							 * Tratamiento para una transacción exitosa.
							 */ ///////////////////////////////////////////////////////////////////////////////////////////////////////
							$msgPrincipal = "Su compra fue exitosa";
							$msgSecundario = '
							Gracias por comprar con Pagadito.<br />
							NAP(N&uacute;mero de Aprobaci&oacute;n Pagadito): <label class="respuesta">' . $Pagadito->get_rs_reference() . '</label><br />
							Fecha Respuesta: <label class="respuesta">' . $Pagadito->get_rs_date_trans() . '</label><br /><br />';
							break;
						
						case "REGISTERED":
							
							/*
							 * Tratamiento para una transacción aún en
							 * proceso.
							 */ ///////////////////////////////////////////////////////////////////////////////////////////////////////
							$msgPrincipal = "Atenci&oacute;n";
							$msgSecundario = "La transacci&oacute;n fue cancelada.<br /><br />";
							break;
						
						case "VERIFYING":
							
							/*
							 * La transacción ha sido procesada en Pagadito, pero ha quedado en verificación.
							 * En este punto el cobro xha quedado en validación administrativa.
							 * Posteriormente, la transacción puede marcarse como válida o denegada;
							 * por lo que se debe monitorear mediante esta función hasta que su estado cambie a COMPLETED o REVOKED.
							 */ ///////////////////////////////////////////////////////////////////////////////////////////////////////
							$msgPrincipal = "Atenci&oacute;n";
							$msgSecundario = '
							Su pago est&aacute; en validaci&oacute;n.<br />
							NAP(N&uacute;mero de Aprobaci&oacute;n Pagadito): <label class="respuesta">' . $Pagadito->get_rs_reference() . '</label><br />
							Fecha Respuesta: <label class="respuesta">' . $Pagadito->get_rs_date_trans() . '</label><br /><br />';
							break;
						
						case "REVOKED":
							
							/*
							 * La transacción en estado VERIFYING ha sido denegada por Pagadito.
							 * En este punto el cobro ya ha sido cancelado.
							 */ ///////////////////////////////////////////////////////////////////////////////////////////////////////
							$msgPrincipal = "Atenci&oacute;n";
							$msgSecundario = "La transacci&oacute;n fue denegada.<br /><br />";
							break;
						
						case "FAILED":
							/*
							 * Tratamiento para una transacción fallida.
							 */
						default:
							
							/*
							 * Por ser un ejemplo, se muestra un mensaje
							 * de error fijo.
							 */ ///////////////////////////////////////////////////////////////////////////////////////////////////////
							$msgPrincipal = "Atenci&oacute;n";
							$msgSecundario = "La transacci&oacute;n no fue realizada.<br /><br />";
							break;
					}
				} else {
					/*
					 * En caso de fallar la petición, verificamos el error devuelto.
					 * Debido a que la API nos puede devolver diversos mensajes de
					 * respuesta, validamos el tipo de mensaje que nos devuelve.
					 */
					switch($Pagadito->get_rs_code())
					{
						case "PG2001":
							/*Incomplete data*/
						case "PG3002":
							/*Error*/
						case "PG3003":
							/*Unregistered transaction*/
						default:
							/*
							 * Por ser un ejemplo, se muestra un mensaje
							 * de error fijo.
							 */ ///////////////////////////////////////////////////////////////////////////////////////////////////////
							$msgPrincipal = "Error en la transacci&oacute;n";
							$msgSecundario = "La transacci&oacute;n no fue completada.<br /><br />";
							break;
					}
				}
			} else {
				/*
				 * En caso de fallar la conexión, verificamos el error devuelto.
				 * Debido a que la API nos puede devolver diversos mensajes de
				 * respuesta, validamos el tipo de mensaje que nos devuelve.
				 */
				switch($Pagadito->get_rs_code())
				{
					case "PG2001":
						/*Incomplete data*/
					case "PG3001":
						/*Problem connection*/
					case "PG3002":
						/*Error*/
					case "PG3003":
						/*Unregistered transaction*/
					case "PG3005":
						/*Disabled connection*/
					case "PG3006":
						/*Exceeded*/
					default:
						/*
						 * Aqui se muestra el código y mensaje de la respuesta del WSPG
						 */
						$msgPrincipal = "Respuesta de Pagadito API";
						$msgSecundario = "
								COD: " . $Pagadito->get_rs_code() . "<br />
								MSG: " . $Pagadito->get_rs_message() . "<br /><br />";
						break;
				}
			}
		} 
		else {
			/*
			 * Aqui se muestra el mensaje de error al no haber recibido el token por medio de la URL.
			 */
			$msgPrincipal = "Atenci&oacute;n";
			$msgSecundario = "No se recibieron los datos correctamente.<br /> La transacci&oacute;n no fue completada.<br /><br />";
		}

		
	}
}
?>
<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class App_Notification extends CI_Controller {
	
    public function __construct() {
       parent::__construct();
    }
	function currentNotification(){
		
		$this->load->model("core/Company_Model");
		$this->load->model('core/Log_Model');
		$this->load->model('Notification_Model');
		$this->load->model('Tag_Model');
		$this->load->model('Error_Model');
		$this->load->model('User_Tag_Model');
		$this->load->model('Remember_Model');
		
		$tagName		= "NOTIFICAR OBLIGACION";
		$objListCompany = $this->Company_Model->get_rows();
		$objTag			= $this->Tag_Model->get_rowByName($tagName);
		
		//Recorrer Empresas
		if($objListCompany)
		foreach($objListCompany as $i){
			$objListItem		= $this->Remember_Model->getNotificationCompany($i->companyID);			
			//Recorrer las Notificaciones
			if($objListItem){				
				foreach($objListItem as $noti){
					$hoy_			= date_format(date_create(),"Y-m-d");
					$lastNoti 		= date_format(date_create($noti->lastNotificationOn),"Y-m-d");
					log_message("INFO",print_r($noti,true));					//Recorrer desde la ultima notificacion, hasta la fecha de hoy
					while ($lastNoti <= $hoy_){						
						//Validar si Ya esta procesado el Dia.						log_message("INFO",print_r($lastNoti,true));
						$objListItemDetail		= $this->Remember_Model->getProcessNotification($noti->rememberID,$lastNoti);							log_message("INFO",print_r($objListItemDetail,true));						
						if($objListItemDetail)
						if($objListItemDetail->diaProcesado == $noti->day)
						{	
							//echo $noti;
							//echo $objListItemDetail;							log_message("INFO",print_r($objListItemDetail->diaProcesado,true));
							$item 					= $objListItemDetail;
							$mensaje				= "";
							$mensaje				.= "<span class='badge badge-important'>OBLIGACION</span>".$item->title;
							$mensaje				.= " => ".$item->description." => ".$item->Fecha." => <span class='badge badge-important'>ATRAZO</span>";							log_message("INFO",print_r($mensaje,true));
							//Ver si el mensaje ya existe para el administrador
							$objError			= $this->Error_Model->get_rowByMessageUser(0,$mensaje);
							$data				= null;
							$errorID 			= 0;
							//tag con notificacion							if($objTag->sendNotificationApp){								$objListUsuario				= $this->User_Tag_Model->get_rowByPK($objTag->tagID);														//Lista de Usuarios								if ($objListUsuario)								foreach($objListUsuario as $usuario){																		$objErrorUser			= $this->Error_Model->get_rowByMessageUser($usuario->userID,$mensaje);									if(!$objErrorUser){										$data					= null;										$data["tagID"]			= $objTag->tagID;										$data["notificated"]	= "notificar obligacion";										$data["message"]		= $mensaje;										$data["isActive"]		= 1;										$data["userID"]			= $usuario->userID;										$data["createdOn"]		= date_format(date_create(),"Y-m-d H:i:s");										$this->Error_Model->insert($data);									}								}							}							
							if(!$objError){
								$data				= null;
								$data["notificated"]= "notificar obligacion";
								$data["tagID"]		= $objTag->tagID;
								$data["message"]	= $mensaje;
								$data["isActive"]	= 1;
								$data["createdOn"]	= date_format(date_create(),"Y-m-d H:i:s");
								$errorID			= $this->Error_Model->insert($data);
							}							else 
								$errorID 			= $objError->errorID;
							
							//tag con correo
							if($objTag->sendEmail){								
								$objListUsuario				= $this->User_Tag_Model->get_rowByPK($objTag->tagID);						
								if ($objListUsuario)
								foreach($objListUsuario as $usuario){
																		$objNotificationUser		= $this->Notification_Model->get_rowsByToMessage($usuario->email,$mensaje);
									if(!$objNotificationUser){
										$data						= null;
										$data["errorID"]			= $errorID;
										$data["from"]				= EMAIL_APP;
										$data["to"]					= $usuario->email;
										$data["subject"]			= "notificar obligacion";
										$data["message"]			= $mensaje;
										$data["summary"]			= "notificar obligacion";
										$data["title"]				= "notificar obligacion";
										$data["tagID"]				= $objTag->tagID;
										$data["createdOn"]			= date_format(date_create(),"Y-m-d H:i:s");
										$data["isActive"]			= 1;
										$this->Notification_Model->insert($data);
									}
								}
							}
						}
						//Actualizar Base de Datos
						$dataRemember						= NULL;
						$dataRemember["lastNotificationOn"]	= $lastNoti;
						$this->Remember_Model->update($noti->rememberID,$dataRemember);	
						//Siguiente Fecha
						$lastNoti = date_format(date_add(date_create($lastNoti),date_interval_create_from_date_string("1 days")),"Y-m-d");
					}
				}
			}
		}
		
	}
	function sendEmail(){
		//Cargar Libreria
		$this->load->model('Notification_Model');
		
		
		//Obtener lista de email
		$objListNotification = $this->Notification_Model->get_rows(20);
		if($objListNotification)
		foreach($objListNotification as $i){
			
			//Enviar Email
			$this->email->set_mailtype('html');
			$this->email->from(EMAIL_APP, HELLOW);
			$this->email->to($i->to);
			$this->email->subject($i->subject);
			$this->email->message($i->message);
			$this->email->send();
			
			$data["sendOn"]	= date_format(date_create(),"Y-m-d H:i:s");
			$this->Notification_Model->update($i->notificationID,$data);
		}
		
	}
	function fillTipoCambio(){
		$this->load->model("core/Company_Model");
		$this->load->model('core/Log_Model');
		$this->load->model('Notification_Model');
		$this->load->model('Tag_Model');
		$this->load->model('Error_Model');
		$this->load->model('User_Tag_Model');
		
		
		$tagName		= "NOTIFICAR TIPO DE CAMBIO";
		$date_			= date_format(date_create(),"Y-m-d");
		$objListCompany = $this->Company_Model->get_rows();
		$objTag			= $this->Tag_Model->get_rowByName($tagName);
		if($objListCompany)
		foreach($objListCompany as $i){
				$defaultCurrencyID	= $this->core_web_currency->getCurrencyDefault($i->companyID)->currencyID;
				$reportCurrencyID	= $this->core_web_currency->getCurrencyReport($i->companyID)->currencyID;
				
				try {
					$exchangeRate		= $this->core_web_currency->getRatio($i->companyID,$date_,1,$reportCurrencyID,$defaultCurrencyID);					
				} catch (Exception $e) {
					$mensaje			= $e->getMessage();	
					
					$objError			= $this->Error_Model->get_rowByMessageUser(0,$mensaje);
					$data				= null;
					$errorID 			= 0;
					if(!$objError){
						$data["notificated"]= "tipo de cambio...";
						$data["tagID"]		= $objTag->tagID;
						$data["message"]	= $mensaje;
						$data["isActive"]	= 1;
						$data["createdOn"]	= date_format(date_create(),"Y-m-d H:i:s");
						$errorID			= $this->Error_Model->insert($data);
					}
					else 
						$errorID 			= $objError->errorID;
					
					//tag con correo
					if($objTag->sendEmail){
						$objListUsuario				= $this->User_Tag_Model->get_rowByPK($objTag->tagID);						
						if ($objListUsuario)
						foreach($objListUsuario as $usuario){
							$objNotificationUser		= $this->Notification_Model->get_rowsByToMessage($usuario->email,$mensaje);
							if(!$objNotificationUser){
								$data						= null;
								$data["errorID"]			= $errorID;
								$data["from"]				= EMAIL_APP;
								$data["to"]					= $usuario->email;
								$data["subject"]			= "TIPO DE CAMBIO";
								$data["message"]			= $mensaje;
								$data["summary"]			= "TIPO DE CAMBIO NO INGRESADO";
								$data["title"]				= "TIPO DE CAMBIO";
								$data["tagID"]				= $objTag->tagID;
								$data["createdOn"]			= date_format(date_create(),"Y-m-d H:i:s");
								$data["isActive"]			= 1;
								$this->Notification_Model->insert($data);
							}
						}
					}
					
					//tag con notificacion
					if($objTag->sendNotificationApp){
						$objListUsuario				= $this->User_Tag_Model->get_rowByPK($objTag->tagID);						
						if ($objListUsuario)
						foreach($objListUsuario as $usuario){
							$objErrorUser			= $this->Error_Model->get_rowByMessageUser($usuario->userID,$mensaje);
							if(!$objErrorUser){
								$data					= null;
								$data["tagID"]			= $objTag->tagID;
								$data["notificated"]	= "tasa de cambio";
								$data["message"]		= $mensaje;
								$data["isActive"]		= 1;
								$data["userID"]			= $usuario->userID;
								$data["createdOn"]		= date_format(date_create(),"Y-m-d H:i:s");
								$this->Error_Model->insert($data);
							}
						}
					}
					
				}
		}
		
	}
	function fillInventarioMinimo(){
		$this->load->model("core/Company_Model");
		$this->load->model('core/Log_Model');
		$this->load->model('Notification_Model');
		$this->load->model('Tag_Model');
		$this->load->model('Error_Model');
		$this->load->model('User_Tag_Model');
		$this->load->model('ItemWarehouse_Model');
		
		
		$tagName		= "NOTIFICAR INVENTARIO MINIMO";
		$objListCompany = $this->Company_Model->get_rows();
		$objTag			= $this->Tag_Model->get_rowByName($tagName);
		if($objListCompany)
		foreach($objListCompany as $i){
			$objListItem		= $this->ItemWarehouse_Model->get_rowLowMinimus($i->companyID);
			if($objListItem){
				foreach($objListItem as $item){
					$mensaje			 = "";
					$mensaje			.= "<span class='badge badge-warning'>PRODUCTO</span>:".$item->itemNumber." ".$item->itemName."<br/>";
					$mensaje			.= "<span class='badge badge-warning'>BODEGA</span>:".$item->warehouseNumber." ".$item->warehouseName."<br/>";
					$mensaje			.= "<span class='badge badge-warning'>CANTIDAD</span>:".$item->quantity.",<span class='badge badge-warning'>CANTIDAD MINIMA</span>:".$item->quantityMin;
					
					$objError			= $this->Error_Model->get_rowByMessageUser(0,$mensaje);
					$data				= null;
					$errorID 			= 0;
					if(!$objError){
						$data["notificated"]= "inventario minimo";
						$data["tagID"]		= $objTag->tagID;
						$data["message"]	= $mensaje;
						$data["isActive"]	= 1;
						$data["createdOn"]	= date_format(date_create(),"Y-m-d H:i:s");
						$errorID			= $this->Error_Model->insert($data);
					}
					else 
						$errorID 			= $objError->errorID;
					
					//tag con correo
					if($objTag->sendEmail){
						$objListUsuario				= $this->User_Tag_Model->get_rowByPK($objTag->tagID);						
						if ($objListUsuario)
						foreach($objListUsuario as $usuario){
							$objNotificationUser		= $this->Notification_Model->get_rowsByToMessage($usuario->email,$mensaje);
							if(!$objNotificationUser){
								$data						= null;
								$data["errorID"]			= $errorID;
								$data["from"]				= EMAIL_APP;
								$data["to"]					= $usuario->email;
								$data["subject"]			= "INVENTARIO MINIMO";
								$data["message"]			= $mensaje;
								$data["summary"]			= "INVENTARIO MINIMO";
								$data["title"]				= "INVENTARIO MINIMO";
								$data["tagID"]				= $objTag->tagID;
								$data["createdOn"]			= date_format(date_create(),"Y-m-d H:i:s");
								$data["isActive"]			= 1;
								$this->Notification_Model->insert($data);
							}
						}
					}
					
					//tag con notificacion
					if($objTag->sendNotificationApp){
						$objListUsuario				= $this->User_Tag_Model->get_rowByPK($objTag->tagID);						
						if ($objListUsuario)
						foreach($objListUsuario as $usuario){
							$objErrorUser			= $this->Error_Model->get_rowByMessageUser($usuario->userID,$mensaje);
							if(!$objErrorUser){
								$data					= null;
								$data["tagID"]			= $objTag->tagID;
								$data["notificated"]	= "inventario minimo";
								$data["message"]		= $mensaje;
								$data["isActive"]		= 1;
								$data["userID"]			= $usuario->userID;
								$data["createdOn"]		= date_format(date_create(),"Y-m-d H:i:s");
								$this->Error_Model->insert($data);
							}
						}
					}
					
				}
			}
		}
	}
	function fillCumpleayo(){
		$this->load->model("core/Company_Model");
		$this->load->model('core/Log_Model');
		$this->load->model('Notification_Model');
		$this->load->model('Tag_Model');
		$this->load->model('Error_Model');
		$this->load->model('User_Tag_Model');
		$this->load->model('Customer_Model');		
		
		$tagName		= "FELIZ CUMPLE";		
		$objTag			= $this->Tag_Model->get_rowByName($tagName);
		
		//Para cada empresa
		$objListCompany = $this->Company_Model->get_rows();		
		if($objListCompany)
		foreach($objListCompany as $i){
			
			//Obtener los cumple de la empresa
			$mensaje			= null;
			$objListItem		= $this->Customer_Model->get_happyBirthDay($i->companyID);
			
			
			if($objListItem)
			foreach($objListItem as $usuario){
				$mensaje					= "<span class='badge badge-info'>FELIZ CUMPLE</span>:".$usuario->firstName." : =>".$usuario->birthDate." AVISO DEL PERIODO = ".date_format(date_create(),"Y");
				
				//Enviar Mensaje por Correo
				/*
				$objNotificationUser		= $this->Notification_Model->get_rowsByToMessage($usuario->email,$mensaje);
				if(!$objNotificationUser){					
					$data["errorID"]			= NULL;
					$data["from"]				= EMAIL_APP;
					$data["to"]					= $usuario->email;
					$data["subject"]			= "FELIZ CUMPLE";
					$data["message"]			= $mensaje;
					$data["summary"]			= "FELIZ CUMPLE";
					$data["title"]				= "FELIZ CUMPLE";
					$data["tagID"]				= NULL;
					$data["createdOn"]			= date_format(date_create(),"Y-m-d H:i:s");
					$data["isActive"]			= 1;
					$this->Notification_Model->insert($data);
				}
				*/
				
				//Notificaciones al administrador
				$objError			= $this->Error_Model->get_rowByMessageUser(0,$mensaje);
				$data				= null;
				$errorID 			= 0;
					
				if(!$objError){
					$data				= null;
					$data["notificated"]= "cumple";
					$data["tagID"]		= $objTag->tagID;
					$data["message"]	= $mensaje;
					$data["isActive"]	= 1;
					$data["createdOn"]	= date_format(date_create(),"Y-m-d H:i:s");
					$errorID			= $this->Error_Model->insert($data);
				}
				else 
					$errorID 			= $objError->errorID;
					
				
				//Notificacioin a los usuarios
				if($objTag->sendNotificationApp){
					$objListUsuario				= $this->User_Tag_Model->get_rowByPK($objTag->tagID);						
					//Lista de Usuarios
					if ($objListUsuario)
					foreach($objListUsuario as $usuario){
						$objErrorUser			= $this->Error_Model->get_rowByMessageUser($usuario->userID,$mensaje);
						if(!$objErrorUser){
							$data					= null;
							$data["tagID"]			= $objTag->tagID;
							$data["notificated"]	= "FELIZ CUMPLE";
							$data["message"]		= $mensaje;
							$data["isActive"]		= 1;
							$data["userID"]			= $usuario->userID;
							$data["createdOn"]		= date_format(date_create(),"Y-m-d H:i:s");
							$this->Error_Model->insert($data);
						}
					}
				}
				
				
					
				
			}
			
		}
	}
	function fillCuotaAtrasada(){	
		$this->load->model("core/Company_Model");
		$this->load->model('core/Log_Model');
		$this->load->model('Notification_Model');
		$this->load->model('Tag_Model');
		$this->load->model('Error_Model');
		$this->load->model('User_Tag_Model');
		$this->load->model('Customer_Credit_Amortization_Model');
		$this->load->model('core/Currency_Model');
		
		
		$tagName		= "NOTIFICAR CUOTA VENCIDA";
		$objListCompany = $this->Company_Model->get_rows();
		$objTag			= $this->Tag_Model->get_rowByName($tagName);
		
		//Lista de empresa
		if($objListCompany)
		foreach($objListCompany as $i){
			$objListItem		= $this->Customer_Credit_Amortization_Model->get_rowShareLate($i->companyID);
			//Lista de Avisos
			if($objListItem){
				foreach($objListItem as $item){
					$objCurrency		= $this->Currency_Model->get_rowByPK($item->currencyID);
					$mensaje			= "";
					$mensaje			.= "<span class='badge badge-success'>CLIENTE</span>:".$item->customerNumber."-".$item->firstName." ".$item->lastName." => ";
					$mensaje			.= "".$item->documentNumber." => ".$item->dateApply." => <span class='badge badge-success'>ATRAZO</span>: ".$objCurrency->simbol." ".sprintf("%01.2f",$item->remaining);
					  
					//Ver si el mensaje ya existe para el administrador
					$objError			= $this->Error_Model->get_rowByMessageUser(0,$mensaje);
					$data				= null;
					$errorID 			= 0;
					
					if(!$objError){
						$data				= null;
						$data["notificated"]= "cuota atrasada";
						$data["tagID"]		= $objTag->tagID;
						$data["message"]	= $mensaje;
						$data["isActive"]	= 1;
						$data["createdOn"]	= date_format(date_create(),"Y-m-d H:i:s");
						$errorID			= $this->Error_Model->insert($data);
					}
					else 
						$errorID 			= $objError->errorID;
					
					
					//tag con correo
					if($objTag->sendEmail){
						$objListUsuario				= $this->User_Tag_Model->get_rowByPK($objTag->tagID);						
						if ($objListUsuario)
						foreach($objListUsuario as $usuario){
							$objNotificationUser		= $this->Notification_Model->get_rowsByToMessage($usuario->email,$mensaje);
							if(!$objNotificationUser){
								$data						= null;
								$data["errorID"]			= $errorID;
								$data["from"]				= EMAIL_APP;
								$data["to"]					= $usuario->email;
								$data["subject"]			= "CUOTA ATRASADA";
								$data["message"]			= $mensaje;
								$data["summary"]			= "CUOTA ATRASADA";
								$data["title"]				= "CUOTA ATRASADA";
								$data["tagID"]				= $objTag->tagID;
								$data["createdOn"]			= date_format(date_create(),"Y-m-d H:i:s");
								$data["isActive"]			= 1;
								$this->Notification_Model->insert($data);
							}
						}
					}
					
					
					//tag con notificacion
					if($objTag->sendNotificationApp){
						$objListUsuario				= $this->User_Tag_Model->get_rowByPK($objTag->tagID);						
						//Lista de Usuarios
						if ($objListUsuario)
						foreach($objListUsuario as $usuario){
							$objErrorUser			= $this->Error_Model->get_rowByMessageUser($usuario->userID,$mensaje);
							if(!$objErrorUser){
								$data					= null;
								$data["tagID"]			= $objTag->tagID;
								$data["notificated"]	= "cuota atrasada";
								$data["message"]		= $mensaje;
								$data["isActive"]		= 1;
								$data["userID"]			= $usuario->userID;
								$data["createdOn"]		= date_format(date_create(),"Y-m-d H:i:s");
								$this->Error_Model->insert($data);
							}
						}
					}
					
				
				}
			}
		}
	}
}
?>
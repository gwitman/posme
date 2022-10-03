<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
|--------------------------------------------------------------------------
| File and Directory Modes
|--------------------------------------------------------------------------
|
| These prefs are used when checking and setting modes when working
| with the file system.  The defaults are fine on servers with proper
| security, but you may wish (or even need) to change the values in
| certain environments (Apache running a separate process for each
| user, PHP under CGI with Apache suEXEC, etc.).  Octal values should
| always be used to set the mode correctly.
|
*/
define('FILE_READ_MODE', 0644);
define('FILE_WRITE_MODE', 0666);
define('DIR_READ_MODE', 0755);
define('DIR_WRITE_MODE', 0777);

/*
|--------------------------------------------------------------------------
| File Stream Modes
|--------------------------------------------------------------------------
|
| These modes are used when working with fopen()/popen()
|
*/

define('FOPEN_READ',							'rb');
define('FOPEN_READ_WRITE',						'r+b');
define('FOPEN_WRITE_CREATE_DESTRUCTIVE',		'wb'); // truncates existing file data, use with care
define('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE',	'w+b'); // truncates existing file data, use with care
define('FOPEN_WRITE_CREATE',					'ab');
define('FOPEN_READ_WRITE_CREATE',				'a+b');
define('FOPEN_WRITE_CREATE_STRICT',				'xb');
define('FOPEN_READ_WRITE_CREATE_STRICT',		'x+b');


/* End of file constants.php */
/* Location: ./application/config/constants.php */



/* CONSTANTES DE LA APLICACION*/
//
//
/////////////////////////////////////////////////////
/////////////////////////////////////////////////////
/////////////////////////////////////////////////////
//APP
define('APP_NEED_AUTHENTICATION',true);
define('EMAIL_APP',"nssystem@fidlocal.com");
define('EMAIL_APP_NOTIFICACION',"gwitman@yahoo.com");
define('PATH_FILE_OF_APP','C:/xampp/teamds2/nsSystem/fidlocal-produccion-app/file_company');
define('PATH_FILE_OF_APP_ROOT','C:/xampp/teamds2/nsSystem/fidlocal-produccion-app');
define('PATH_FILE_OF_XAMPP_TMP','C:/xampp/tmp');
define('APP_NAME','WSYSTEM');
define('APP_TIMEZONE','America/Managua');/*yyyy-m-d*/

//Tipos de Menu
define("MENU_TOP",4);
define("MENU_LEFT",5);
define("MENU_BODY",6);
define("MENU_TOP_BODY",7);
define("MENU_HIDDEN_POPUP",8);

//Tipos de Vistas
define('ELEMENT_TYPE_PAGE','1');
define('ELEMENT_TYPE_TABLE','2');
define('CALLERID_LIST','1');
define('CALLERID_SEARCH','2');

//Permisos sobre los registros en las vistas
define('PERMISSION_NONE','-1');
define('PERMISSION_ALL','0');
define('PERMISSION_BRANCH','1');
define('PERMISSION_ME','2');

//Permisos sobre los registros en los workflow
define('COMMAND_VINCULATE','1');
define('COMMAND_EDITABLE','2');
define('COMMAND_EDITABLE_TOTAL','3');
define('COMMAND_ELIMINABLE','4'); 
define('COMMAND_APLICABLE','5');

//Configuracion de Reportes 
define("PAGE_SIZE","LETTER");
define("LEFT_MARGIN","0.5");//cm
define("RIGHT_MARGIN","0.5");//cm
define("BOTTOM_MARGIN","0.5");//cm
define("TOP_MARGIN","0.5");//cm
define("FONT_SIZE_SMALL","7");
define("FONT_SIZE","8");
define("FONT_SIZE_BOLD","9");
define("FONT_SIZE_TITLE","10");
define("LOGO_SIZE_WIDTH","105");
define("LOGO_SIZE_HEIGTH","60"); 

//Configuracion de Factura
define("PAGE_INVOICE","INVOICE_PRINTER_TERMICA_001");
define("LEFT_MARGIN_INVOICE","0");//cm
define("RIGHT_MARGIN_INVOICE","0.9");//cm
define("BOTTOM_MARGIN_INVOICE","0");//cm
define("TOP_MARGIN_INVOICE","0");//cm
define("FONT_SIZE_TITLE_INVICE","12");//cm
define("FONT_SIZE_BODY_INVICE","10");//cm

//Mensajes
define("USER_NOT_AUTENTICATED","TIEMPO DE ESPERA AGOTADO:   <a href='http://localhost/posme/'>**INGRESAR**</a> ");
define("NOT_ALL_INSERT","NO PUEDE INGRESAR UN REGISTRO");
define("NOT_ALL_EDIT","NO PUEDE EDITAR NINGUN REGISTRO");
define("NOT_EDIT","NO PUEDE EDITAR UN REGISTRO QUE NO FUE CREADO POR USTED");
define("NOT_WORKFLOW_EDIT","EL REGISTRO NO PUEDE SER EDITADO POR SU ESTADO ACTUAL");
define("NOT_ALL_DELETE","NO PUEDE ELIMINAR NINGUN REGISTRO");
define("NOT_DELETE","NO PUEDE ELIMINAR UN REGISTRO QUE NO FUE CREADO POR USTED");
define("NOT_WORKFLOW_DELETE","EL REGISTRO NO PUEDE SER ELIMINADO POR SU ESTADO ACTUAL");
define("NOT_ACCESS_CONTROL","NO TIENE ACCESO AL CONTROLADOR");
define("NOT_ACCESS_FUNCTION","NO TIENE ACCESO A LA FUNCION");
define("NOT_PARAMETER","PARAMETROS INCORRECTOS");
define("SUCCESS","SUCCESS");
define("ERROR","ERROR");

define("NOT_VALID_USER","USUARIO O PASSWORD INCORRECTO");
define("NOT_VALID_EMAIL","EMAIL INCORRECTA");
define("HELLOW","NSSYSTEM NOTICIAS AUTOMATICAS");
define("NICKNAME_DUPLI","NICKNAME EXISTE");
define("EMAIL_DUPLI","EMAIL EXISTE");
define("MESSAGE_EMAL","DATOS ENVIADOS A SU CUENTA DE CORREO");
define("REMEMBER_PASSWORD","PASSWORD ENVIADO");
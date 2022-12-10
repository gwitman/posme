<?php if ( ! defined('BASEPATH')) exit('No se permite el acceso directo al script');

class core_web_permission {
   
   /**********************Variables Estaticas********************/
   /*************************************************************/
   /*************************************************************/
   /*************************************************************/
	private $CI; 
	
	
   /**********************Funciones******************************/
   /*************************************************************/
   /*************************************************************/
   /*************************************************************/
   public function __construct(){		
        $this->CI = & get_instance();
   }
   
   function getElementID($controler,$method,$suffix,$dataMenuTop,$dataMenuLeft,$dataMenuBodyReport,$dataMenuBodyTop,$dataMenuHiddenPopup){
		$url  = $controler."/".$method.$suffix;	
		
		
		//dataMenuTop
		//dataMenuLeft
		//dataMenuBodyReport
		//dataMenuBodyTop
		//dataMenuHiddenPopup
		

		if(is_array($dataMenuHiddenPopup))
		foreach($dataMenuHiddenPopup AS $url_){	
			if(strtoupper ($url_->address) == strtoupper ($url)){
				return $url_->menuElementID;
			}
		}
		
		if(is_array($dataMenuBodyTop))
		foreach($dataMenuBodyTop AS $url_){	
			if(strtoupper ($url_->address) == strtoupper ($url)){
				return $url_->menuElementID;
			}
		}
		
		if(is_array($dataMenuBodyReport))
		foreach($dataMenuBodyReport AS $url_){	
			if(strtoupper ($url_->address) == strtoupper ($url)){
				return $url_->menuElementID;
			}
		}
		
		if(is_array($dataMenuTop))
		foreach($dataMenuTop AS $url_){	
			if(strtoupper ($url_->address) == strtoupper ($url)){
				return $url_->menuElementID;
			}
		}
		
		if(is_array($dataMenuLeft))
		foreach($dataMenuLeft AS $url_){	
			if(strtoupper ($url_->address) == strtoupper ($url)){
				return $url_->menuElementID;
			}
		}
		
		return false;
   }
   function urlPermited($controler,$method,$suffix,$dataMenuTop,$dataMenuLeft,$dataMenuBodyReport,$dataMenuBodyTop,$dataMenuHiddenPopup){
		$url  = $controler."/".$method.$suffix;			
		
		//dataMenuTop
		//dataMenuLeft
		//dataMenuBodyReport
		//dataMenuBodyTop
		//dataMenuHiddenPopup	
		
		

		if(is_array($dataMenuHiddenPopup))
		foreach($dataMenuHiddenPopup AS $url_){	
			if(strtoupper ($url_->address) == strtoupper ($url)){
				return true;
			}
		}
		
		if(is_array($dataMenuBodyTop))
		foreach($dataMenuBodyTop AS $url_){	
			if(strtoupper ($url_->address) == strtoupper ($url)){
				return true;
			}
		}
		
		if(is_array($dataMenuBodyReport))
		foreach($dataMenuBodyReport AS $url_){	
			if(strtoupper ($url_->address) == strtoupper ($url)){
				return true;
			}
		}
		
		if(is_array($dataMenuLeft))
		foreach($dataMenuLeft AS $url_){	
			if(strtoupper ($url_->address) == strtoupper ($url)){
				return true;
			}
		}
		
	
		if(is_array($dataMenuTop))
		foreach($dataMenuTop AS $url_){	
			if(strtoupper ($url_->address) == strtoupper ($url)){
				return true;
			}
		}
		return false;
   }
   function urlPermissionCmd($controler,$method,$suffix,$session_,$dataMenuTop,$dataMenuLeft,$dataMenuBodyReport,$dataMenuBodyTop,$dataMenuHiddenPopup){
		$this->CI->load->model("core/User_Permission_Model");
		$url  	= $controler."/".$method.$suffix;
		$url2  	= $controler."/"."index".$suffix;
		
		//dataMenuTop
		//dataMenuLeft
		//dataMenuBodyReport
		//dataMenuBodyTop
		//dataMenuHiddenPopup	
		
		
		//Craer Variables
		$elementID	= 0; 	
		if($session_["role"]->isAdmin)
		return PERMISSION_ALL;			
		
		//Obtener el elementoID
		if(is_array($dataMenuTop))
		foreach($dataMenuTop AS $url_){				
			if(strtoupper ($url_->address) == strtoupper ($url)){
				$elementID = $url_->elementID;
				break;
			}
			else if(strtoupper ($url_->address) == strtoupper ($url2)){
				$elementID = $url_->elementID;
				break;
			}
		}
		
		if(is_array($dataMenuLeft))
		foreach($dataMenuLeft AS $url_){				
			if(strtoupper ($url_->address) == strtoupper ($url)){
				$elementID = $url_->elementID;
				break;
			}			
			else if(strtoupper ($url_->address) == strtoupper ($url2)){
				$elementID = $url_->elementID;
				break;
			}
		}
		
		if(is_array($dataMenuBodyReport))
		foreach($dataMenuBodyReport AS $url_){	
			
			if(strtoupper ($url_->address) == strtoupper ($url)){				
				$elementID = $url_->elementID;
				break;
			}			
			else if(strtoupper ($url_->address) == strtoupper ($url2)){
				$elementID = $url_->elementID;
				break;
			}
		}
		
		if(is_array($dataMenuBodyTop))
		foreach($dataMenuBodyTop AS $url_){				
			if(strtoupper ($url_->address) == strtoupper ($url)){
				$elementID = $url_->elementID;
				break;
			}
			else if(strtoupper ($url_->address) == strtoupper ($url2)){
				$elementID = $url_->elementID;
				break;
			}
		}
		
		if(is_array($dataMenuHiddenPopup))
		foreach($dataMenuHiddenPopup AS $url_){				
			if(strtoupper ($url_->address) == strtoupper ($url)){
				$elementID = $url_->elementID;
				break;
			}
			else if(strtoupper ($url_->address) == strtoupper ($url2)){
				$elementID = $url_->elementID;
				break;
			}
		}
		
		
		if($elementID == 0)
		return PERMISSION_NONE;
		
		
		//Obtener resultado...		
		$rowRolePermission				= $this->CI->User_Permission_Model->get_rowByPK($session_["user"]->companyID,$session_["user"]->branchID,$session_["role"]->roleID,$elementID);
		if(!$rowRolePermission)
		return PERMISSION_NONE;
		
		
		if($method 				== "index"){
			return $rowRolePermission->selected;
		}	
		else if($method 		== "edit"){
			return $rowRolePermission->edited;
		}
		else if($method 		== "delete"){
			return $rowRolePermission->deleted;
		}
		else if($method 		== "add"){
			return $rowRolePermission->inserted;
		}
		
   }

   function getValueLicense($companyID,$url)
   {

		
		log_message("ERROR","validar licencia ".$url);
		$this->CI->load->model("core/User_Permission_Model");
		$this->CI->load->model("core/User_Model");

		//Validar Parametro de maximo de usuario.
		$objParameterMAX_USER 		= $this->CI->core_web_parameter->getParameter("CORE_CUST_PRICE_MAX_USER",$companyID);

		$parameterFechaExpiration 	= $this->CI->core_web_parameter->getParameter("CORE_CUST_PRICE_LICENCES_EXPIRED",$companyID);
		$parameterFechaExpiration 	= $parameterFechaExpiration->value;
		$parameterFechaExpiration 	= DateTime::createFromFormat('Y-m-d',$parameterFechaExpiration);			

		$objParameterISleep			= $this->CI->core_web_parameter->getParameter("CORE_CUST_PRICE_SLEEP",$companyID);
		$objParameterISleep			= $objParameterISleep->value;

		$objParameterTipoPlan		= $this->CI->core_web_parameter->getParameter("CORE_CUST_PRICE_TIPO_PLAN",$companyID);
		$objParameterTipoPlan		= $objParameterTipoPlan->value;

		$objParameterExpiredLicense	= $this->CI->core_web_parameter->getParameter("CORE_CUST_PRICE_LICENCES_EXPIRED",$companyID);
		$objParameterExpiredLicense	= $objParameterExpiredLicense->value;
		$objParameterExpiredLicense = DateTime::createFromFormat('Y-m-d',$objParameterExpiredLicense);		

		

		
		//Validar cantidad maxima de usuario
		if($objParameterMAX_USER->value > 0 ){			
			$count = $this->CI->User_Model->getCount($companyID);		
			if(($count + 1) > $objParameterMAX_USER->value ){
				throw new Exception('
				<p>A superado el numero maximo de usuario.</p>

				<p>telefono de contacto: 8712-5827 para activar licencia</p>
				<p>realizar el pago de la licencia  aqui &ograve; </p>
				<p>realizar la transferencia a la siguiente cuenta BAC Dolares: 366-577-484 </p>
				
				');
			}
		}



		//Validar Fecha de expiracion de la licencia
		$fechaNow  = DateTime::createFromFormat('Y-m-d',date("Y-m-d"));  						
		if( $fechaNow >  $parameterFechaExpiration ){
			throw new Exception('
			<p>La licencia a expirado.</p>

			<p>telefono de contacto: 8712-5827 para activar licencia</p>
			<p>realizar el pago de la licencia  aqui &ograve; </p>
			<p>realizar la transferencia a la siguiente cuenta BAC Dolares: 366-577-484 </p>
			');
		}
		

		//Dormir el sistema cuando el tipo de Licencia es PERMANENTE y la fecha actual es mayor a la fecha limite de licencia
		//En tal caso, dormir el sistema	
		$fechaNow  = DateTime::createFromFormat('Y-m-d',date("Y-m-d"));  	
		if( $fechaNow >  $objParameterExpiredLicense && $objParameterTipoPlan != "MENSUALIDAD" ){		
			$diff = $objParameterExpiredLicense->diff($fechaNow);
			$days = abs($diff->days);
			$days = $days + $objParameterISleep ;						
			
			if($days > 60)
			$days = 60;

			sleep($days);
		}		

	
	}
  
}

?>
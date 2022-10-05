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
  
}

?>
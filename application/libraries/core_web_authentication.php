<?php if ( ! defined('BASEPATH')) exit('No se permite el acceso directo al script');

class core_web_authentication {
   
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
   function get_UserBy_PasswordAndNickname($nickname,$password){
		$this->CI->load->model("core/User_Model");
		$this->CI->load->model("core/Role_Model");
		$this->CI->load->model("core/Membership_Model");
		$this->CI->load->model("core/Company_Model");
		$this->CI->load->model("core/Branch_Model");
		$this->CI->load->library("core_web_menu");
		
		$objUser	= $this->CI->User_Model->get_rowByNiknamePassword($nickname,$password);
		if(!$objUser)
		throw new Exception('PASSWORD O NICKNAME INCORRECTO ...');
		
		$objCompany		= $this->CI->Company_Model->get_rowByPK($objUser->companyID);
		$objBranch		= $this->CI->Branch_Model->get_rowByPK($objUser->companyID,$objUser->branchID);
		$objMembership	= $this->CI->Membership_Model->get_rowByCompanyIDBranchIDUserID($objUser->companyID,$objUser->branchID,$objUser->userID);
		
		if(!$objMembership)
		throw new Exception('EL USUARIO NO TIENE ASIGNADO UN ROL...');
		
		$objRole					= $this->CI->Role_Model->get_rowByPK($objUser->companyID,$objUser->branchID,$objMembership->roleID);
		$objElementAuthorized		= $this->CI->core_web_menu->get_menu_top($objMembership->companyID,$objMembership->branchID,$objMembership->roleID,$objMembership->userID);
		$objElementNotAuthorized	= $this->CI->core_web_menu->get_menu_left($objMembership->companyID,$objMembership->branchID,$objMembership->roleID,$objMembership->userID);
		$menuBodyReport				= $this->CI->core_web_menu->get_menu_body_report($objMembership->companyID,$objMembership->branchID,$objMembership->roleID,$objMembership->userID);
		$menuHiddenPopup 			= $this->CI->core_web_menu->get_menu_hidden_popup($objMembership->companyID,$objMembership->branchID,$objMembership->roleID,$objMembership->userID);
		
		if(!$objCompany)
		throw new Exception('LA EMPREA NO FUE ENCONTRADA ...');
		
		if(!$objBranch)
		throw new Exception('LA SUCURSAL NO FUE ENCONTRADA ...');
		
		if(!$objRole)
		throw new Exception('EL ROL DEL USUARIO NO FUE ENCONTRADO...');
		
		$data["company"]			= $objCompany;
		$data["branch"]				= $objBranch;
		$data["role"]				= $objRole;
		$data["user"]				= $objUser;
		$data["menuTop"]			= $objElementAuthorized;
		$data["menuLeft"]			= $objElementNotAuthorized;
		$data["menuBodyTop"]		= null;
		$data["menuBodyReport"]		= $menuBodyReport;
		$data["menuHiddenPopup"]	= $menuHiddenPopup;
		
		
		return $data;
   }
   function get_UserBy_Email($email){
		$this->CI->load->model("core/User_Model");
		$this->CI->load->model("core/Role_Model");
		$this->CI->load->model("core/Membership_Model");
		$this->CI->load->model("core/Company_Model");
		$this->CI->load->model("core/Branch_Model");
		$this->CI->load->library("core_web_menu");
		
		$objUser	= $this->CI->User_Model->get_rowByEmail($email);
		if(!$objUser)
		throw new Exception('EMAIL INCORRECTO ...');
		
		$objCompany		= $this->CI->Company_Model->get_rowByPK($objUser->companyID);
		$objBranch		= $this->CI->Branch_Model->get_rowByPK($objUser->companyID,$objUser->branchID);
		$objMembership	= $this->CI->Membership_Model->get_rowByCompanyIDBranchIDUserID($objUser->companyID,$objUser->branchID,$objUser->userID);
		
		if(!$objMembership)
		throw new Exception('EL USUARIO NO TIENE ASIGNADO UN ROL...');
		
		$objRole					= $this->CI->Role_Model->get_rowByPK($objUser->companyID,$objUser->branchID,$objMembership->roleID);
		$objElementAuthorized		= $this->CI->core_web_menu->get_menu_top($objMembership->companyID,$objMembership->branchID,$objMembership->roleID,$objMembership->userID);
		$objElementNotAuthorized	= $this->CI->core_web_menu->get_menu_left($objMembership->companyID,$objMembership->branchID,$objMembership->roleID,$objMembership->userID);
		$objElementBodyReport 		= $this->CI->core_web_menu->get_menu_body_report($objMembership->companyID,$objMembership->branchID,$objMembership->roleID,$objMembership->userID);
		$menuHiddenPopup 			= $this->CI->core_web_menu->get_menu_hidden_popup($objMembership->companyID,$objMembership->branchID,$objMembership->roleID,$objMembership->userID);
		
		if(!$objCompany)
		throw new Exception('LA EMPREA NO FUE ENCONTRADA ...');
		
		if(!$objBranch)
		throw new Exception('LA SUCURSAL NO FUE ENCONTRADA ...');
		
		if(!$objRole)
		throw new Exception('EL ROL DEL USUARIO NO FUE ENCONTRADO...');
		
		$data["company"]			= $objCompany;
		$data["branch"]				= $objBranch;
		$data["role"]				= $objRole;
		$data["user"]				= $objUser;
		$data["menuTop"]			= $objElementAuthorized;
		$data["menuLeft"]			= $objElementNotAuthorized;
		$data["menuBodyTop"]		= null;
		$data["menuBodyReport"] 	= $objElementBodyReport;
		$data["menuHiddenPopup"]	= $menuHiddenPopup;
		return $data;
		
   }
   function createLogin($data){
		$this->CI->load->library("session");
		$this->CI->load->library("core_web_menu");		
		
		$this->CI->session->set_userdata($data);		
		$data			 				= $this->CI->session->all_userdata();
		$userdata["menuRenderTop"] 		= $this->CI->core_web_menu->render_menu_top($data["menuTop"]);			
		$userdata["menuRenderLeft"] 	= $this->CI->core_web_menu->render_menu_left($data["menuLeft"]);
		$this->CI->session->set_userdata($userdata);
		
		
				
   } 
   function destroyLogin(){
		$this->CI->load->library("session");
		$this->CI->session->sess_destroy();   
   }
   function isAuthenticated(){
		
		$this->CI->load->library("session");
		log_message("ERROR",APP_NEED_AUTHENTICATION." 001.001");
			
		if(!APP_NEED_AUTHENTICATION)
		return true;
				
		log_message("ERROR","obteniendo userdata 001.001");
		log_message("ERROR",print_r($this->CI->session->userdata('user'),true));
		
		if($this->CI->session->userdata('user'))
		return true;
		
		return false;			
   }
}

?>
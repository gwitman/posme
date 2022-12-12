<?php if ( ! defined('BASEPATH')) exit('No se permite el acceso directo al script');
class core_web_menu {
   
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
   function get_menu_top($companyID=null,$branchID=null,$roleID=null,$userID=null){
		
		//Cargar Modelos		
		$this->CI->load->model("core/Component_Model");
		$this->CI->load->model("core/Company_Component_Model");
		$this->CI->load->model("core/Element_Model");
		$this->CI->load->model("core/Menu_Element_Model");
		$this->CI->load->model("core/User_Permission_Model");
		$this->CI->load->model("core/Role_Model");
		
		
		//Obtener el rol del usuario
		$objRole 	= $this->CI->Role_Model->get_rowByPK($companyID,$branchID,$roleID);
		if(!$objRole)		
		throw new Exception('NO EXISTE EL ROL DEL USUARIO');
		
		//Obtener la lista de elementos tipo pagina, que pertenescan al componente de seguridad
		$listElementSeguridad =	$this->CI->Element_Model->get_rowByTypeAndLayout(ELEMENT_TYPE_PAGE,MENU_TOP);
		if(!$listElementSeguridad)
		return null;
			
		
		//Obtener la lista del elementos de tipo pagina a la cual el usuario tiene permiso , segun el rol del usuario
		$listElementPermitido = $this->CI->User_Permission_Model->get_rowByCompanyIDyBranchIDyRoleID($companyID,$branchID,$roleID);
		
		//Obtener los id de los Elementos
		$listElementIDSeguridad;
		$listElementIDPermitied;		
		foreach($listElementSeguridad AS $i){ 
			$listElementIDSeguridad[] = $i->elementID; 
		}
		
		if($listElementPermitido){
			$tmp;
			foreach($listElementPermitido AS $i){$tmp[] = $i->elementID;}
			$listElementIDPermitied = array_intersect($listElementIDSeguridad, $tmp);
		}
		
		//Obtener la lista de menu_element del componente de  seguridad...
		if($objRole->isAdmin && $listElementIDSeguridad)		
		$listMenuElement = $this->CI->Menu_Element_Model->get_rowByCompanyIDyElementID($companyID,$listElementIDSeguridad);
		else if ($listElementIDPermitied)
		$listMenuElement = $this->CI->Menu_Element_Model->get_rowByCompanyIDyElementID($companyID,$listElementIDPermitied);
		
		if(!$listMenuElement)
		return null; 	
	
	   
		
		//Resultado 
		return $listMenuElement;
		
    }
   function get_menu_left($companyID=null,$branchID=null,$roleID=null,$userID=null){
		
		//Cargar Modelos		
		$this->CI->load->model("core/Component_Model");
		$this->CI->load->model("core/Company_Component_Model");
		$this->CI->load->model("core/Element_Model");
		$this->CI->load->model("core/Menu_Element_Model");
		$this->CI->load->model("core/User_Permission_Model");
		$this->CI->load->model("core/Role_Model");
		
		
		//Obtener el rol del usuario
		$objRole 	= $this->CI->Role_Model->get_rowByPK($companyID,$branchID,$roleID);
		if(!$objRole)		
		throw new Exception('NO EXISTE EL ROL DEL USUARIO');
		
		//Obtener la lista de elementos tipo pagina, que pertenescan al componente de seguridad
		$listElementNotSeguridad =	$this->CI->Element_Model->get_rowByTypeAndLayout(ELEMENT_TYPE_PAGE,MENU_LEFT);
		if(!$listElementNotSeguridad)
		return null;
			
		//Obtener la lista del elementos de tipo pagina a la cual el usuario tiene permiso , segun el rol del usuario
		$listElementPermitido = $this->CI->User_Permission_Model->get_rowByCompanyIDyBranchIDyRoleID($companyID,$branchID,$roleID);
		
		//Obtener los id de los Elementos
		$listElementIDNotSeguridad;
		$listElementIDPermitied;		
		foreach($listElementNotSeguridad AS $i){ 
			$listElementIDNotSeguridad[] = $i->elementID; 
		}
		
		if($listElementPermitido){
			$tmp;
			foreach($listElementPermitido AS $i){$tmp[] = $i->elementID;}
			$listElementIDPermitied = array_intersect($listElementIDNotSeguridad, $tmp);
		}
		
		
		//Obtener la lista de menu_element del componente de  seguridad...
		if($objRole->isAdmin && $listElementIDNotSeguridad)		
		$listMenuElement = $this->CI->Menu_Element_Model->get_rowByCompanyIDyElementID($companyID,$listElementIDNotSeguridad);
		else if ($listElementIDPermitied)
		$listMenuElement = $this->CI->Menu_Element_Model->get_rowByCompanyIDyElementID($companyID,$listElementIDPermitied);
		
		if(!$listMenuElement)
		return null; 	
	
	
		//Resultado  
		return $listMenuElement;
		
    }
    function get_menu_body_report($companyID=null,$branchID=null,$roleID=null,$userID=null){
		//Cargar Modelos		
		$this->CI->load->model("core/Component_Model");
		$this->CI->load->model("core/Company_Component_Model");
		$this->CI->load->model("core/Element_Model");
		$this->CI->load->model("core/Menu_Element_Model");
		$this->CI->load->model("core/User_Permission_Model");
		$this->CI->load->model("core/Role_Model");
		
		
		//Obtener el rol del usuario
		$objRole 	= $this->CI->Role_Model->get_rowByPK($companyID,$branchID,$roleID);
		if(!$objRole)		
		throw new Exception('NO EXISTE EL ROL DEL USUARIO');
		
		//Obtener la lista de elementos tipo pagina, que pertenescan al componente de seguridad
		$listElementNotSeguridad =	$this->CI->Element_Model->get_rowByTypeAndLayout(ELEMENT_TYPE_PAGE,MENU_BODY);
		if(!$listElementNotSeguridad)
		return null;
			
		//Obtener la lista del elementos de tipo pagina a la cual el usuario tiene permiso , segun el rol del usuario
		$listElementPermitido = $this->CI->User_Permission_Model->get_rowByCompanyIDyBranchIDyRoleID($companyID,$branchID,$roleID);
		
		//Obtener los id de los Elementos
		$listElementIDNotSeguridad;
		$listElementIDPermitied;		
		foreach($listElementNotSeguridad AS $i){ 
			$listElementIDNotSeguridad[] = $i->elementID; 
		}
		
		if($listElementPermitido){
			$tmp;
			foreach($listElementPermitido AS $i){$tmp[] = $i->elementID;}
			$listElementIDPermitied = array_intersect($listElementIDNotSeguridad, $tmp);
		}
		
		$listMenuElement	= null;
		//Obtener la lista de menu_element del componente de  seguridad...
		if($objRole->isAdmin && $listElementIDNotSeguridad)		
		$listMenuElement = $this->CI->Menu_Element_Model->get_rowByCompanyIDyElementID($companyID,$listElementIDNotSeguridad);
		else if ($listElementIDPermitied)
		$listMenuElement = $this->CI->Menu_Element_Model->get_rowByCompanyIDyElementID($companyID,$listElementIDPermitied);
		
		if(!$listMenuElement)
		return null; 	
	
	
		//Resultado  
		return $listMenuElement;
		
	}
	function get_menu_hidden_popup($companyID=null,$branchID=null,$roleID=null,$userID=null){
		//Cargar Modelos		
		$this->CI->load->model("core/Component_Model");
		$this->CI->load->model("core/Company_Component_Model");
		$this->CI->load->model("core/Element_Model");
		$this->CI->load->model("core/Menu_Element_Model");
		$this->CI->load->model("core/User_Permission_Model");
		$this->CI->load->model("core/Role_Model");
		
		
		//Obtener el rol del usuario
		$objRole 	= $this->CI->Role_Model->get_rowByPK($companyID,$branchID,$roleID);
		if(!$objRole)		
		throw new Exception('NO EXISTE EL ROL DEL USUARIO');
		
		//Obtener la lista de elementos tipo pagina, que pertenescan al componente de seguridad
		$listElementNotSeguridad =	$this->CI->Element_Model->get_rowByTypeAndLayout(ELEMENT_TYPE_PAGE,MENU_HIDDEN_POPUP);
		if(!$listElementNotSeguridad)
		return null;
			
		//Obtener la lista del elementos de tipo pagina a la cual el usuario tiene permiso , segun el rol del usuario
		$listElementPermitido = $this->CI->User_Permission_Model->get_rowByCompanyIDyBranchIDyRoleID($companyID,$branchID,$roleID);
		
		//Obtener los id de los Elementos
		$listElementIDNotSeguridad;
		$listElementIDPermitied;		
		foreach($listElementNotSeguridad AS $i){ 
			$listElementIDNotSeguridad[] = $i->elementID; 
		}
		
		if($listElementPermitido){
			$tmp;
			foreach($listElementPermitido AS $i){$tmp[] = $i->elementID;}
			$listElementIDPermitied = array_intersect($listElementIDNotSeguridad, $tmp);
		}
		
		
		//Obtener la lista de menu_element del componente de  seguridad...
		$listMenuElement = NULL;
		if($objRole->isAdmin && $listElementIDNotSeguridad)		
		$listMenuElement = $this->CI->Menu_Element_Model->get_rowByCompanyIDyElementID($companyID,$listElementIDNotSeguridad);
		else if ($listElementIDPermitied)
		$listMenuElement = $this->CI->Menu_Element_Model->get_rowByCompanyIDyElementID($companyID,$listElementIDPermitied);
		
		if(!$listMenuElement)
		return null; 	
	
	
		//Resultado  
		return $listMenuElement;
		
	}
	function render_menu_top($data)
    {
		$html		= "";
		$html		= self::render_item_top($data,null);
		return $html;
    }
    function render_item_top($data,$parent){
		$html	= "";	
		$x		= "";	
		foreach($data AS $obj){
			if($obj->parentMenuElementID == $parent){				
				$x 					= self::render_item_top($data,$obj->menuElementID);		
				$data_["icon"]		= $obj->icon;
				$data_["address"]	= base_url().$obj->address;
				$data_["display"]	= $obj->display;
				$data_["submenu"]	= $x;								
				$template			= $this->CI->load->view("core_template/".$obj->template,$data_,true);								
				$html				= $html . $template;
			}		
		}		 
		return $html;  
    }
    function render_menu_left($data)
    {
		$html		= "";		
		$html		= self::render_item_left($data,null);
		return $html;
    }
    function render_item_left($data,$parent){
		$html	= "";	
		$x		= "";	
		foreach($data AS $obj){
			if($obj->parentMenuElementID == $parent){				
				$x 					= self::render_item_left($data,$obj->menuElementID);		
				$data_["icon"]		= $obj->icon;
				$data_["address"]	= base_url().$obj->address;
				$data_["display"]	= $obj->display;
				$data_["submenu"]	= $x;								
				$template			= $this->CI->load->view("core_template/".$obj->template,$data_,true);								
				$html				= $html . $template;
			}		
		}		 
		return $html;  
    }
	function render_menu_body_report($data,$elementID){
		$html		= "";		
		$html		= self::render_item_body_report($data,$elementID);
		return $html;
	}
	function render_item_body_report($data,$parent){
		$html	= "";	
		$x		= "";	
		if(!$data)
		return;
		
		foreach($data AS $obj){
			if($obj->parentMenuElementID == $parent){				
				$x 					= self::render_item_body_report($data,$obj->menuElementID);		
				$data_["icon"]		= $obj->icon;
				$data_["address"]	= base_url().$obj->address;
				$data_["display"]	= $obj->display;
				$data_["submenu"]	= $x;								
				$template			= $this->CI->load->view("core_template/".$obj->template,$data_,true);								
				$html				= $html . $template;
			}		
		}		 
		return $html;  
    }
}
?>
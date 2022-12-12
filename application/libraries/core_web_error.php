<?php if ( ! defined('BASEPATH')) exit('No se permite el acceso directo al script');
class core_web_error {
   
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
   function get_error($userID){
		$this->CI->load->model("Error_Model");
		$objListError		= $this->CI->Error_Model->get_rowByUser($userID);
		$objListErrorCount	= $this->CI->Error_Model->get_rowByUserCount($userID);
		
		$html				= "";
		$item				= "";
		$counter			= 0;
		
		if($objListError)
		foreach($objListError as $i){
			$counter++;
			$item	.= '<li role="presentation"><a href="'.site_url().'core_notification/index.aspx" class=""><i class="icon16 i-bell-2"></i> '.$i->notificated.' </a></li>';
		}
		
		if($objListErrorCount > $counter )
		$item	.= '<li role="presentation"><a href="'.site_url().'core_notification/index.aspx" class=""><i class="icon16 i-bell-2"></i> ......... </a></li>';
	
		
		$html				=  "<li class='divider-vertical'></li>";
		$html				.= '<li class="dropdown">';
		$html				.= '	<a href="#" class="dropdown-toggle" data-toggle="dropdown">';
		$html				.= '		<i class="icon24 i-bell-2"></i>';
		$html				.= '		<span class="notification red">'.$objListErrorCount.'</span>';
		$html				.= '	</a>';
		$html				.= '	<ul class="dropdown-menu" role="menu">';		
		$html				.= 			$item;
		$html				.= '	</ul>';
		$html				.= '</li>';
		
		return $html;
   }
}
?>
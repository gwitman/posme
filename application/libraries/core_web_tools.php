<?php if ( ! defined('BASEPATH')) exit('No se permite el acceso directo al script');

class core_web_tools {
   
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
   function formatMessageError($message){
			$resultMessage = str_replace("\n","",$message);								
			return $resultMessage;
   }
   function formatParameter($filter){			
			$filter 					= str_replace("|",":",$filter);			
			$filter 					= str_replace("{}",",",$filter);
			$filter 					= json_decode($filter);
			
			//Salir
			if(!$filter)
			return;
			
			//Obtener Variables
			$filter 					= (array)$filter;
			$filterKey 					= array_keys($filter);
			$result;
			foreach($filterKey as $key){
				$result["{".$key."}"] 	= $filter[$key];
			}
			return $result;
			
   }
   function getComponentIDBy_ComponentName($componentName){		
		//Cargar Libreria
		$this->CI->load->model('core/Component_Model');
		
		//Obtener
		$component = $this->CI->Component_Model->get_rowByName($componentName);
		
		//Resultado...
		return $component;
   }
}

?>
<?php if ( ! defined('BASEPATH')) exit('No se permite el acceso directo al script');
 
class core_web_javascript {
   
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
	function createVar($key,$value){
			$script_	= "";
			$script_	= "<script> var  ".$key." = '".$value."'; </script>";
			return  $script_;
    }
    function setVar($key,$value){
			$script_	= "";
			$script_	= "<script>  ".$key." = '".$value."'; </script>";
			return  $script_;
			
    }
    function insertScript($value){
			$script_	= ""; 
			$script_	= "<script>  ".$value." </script>";
			return  $script_;		
    }
}
?>
<?php if ( ! defined('BASEPATH')) exit('No se permite el acceso directo al script');
class core_web_inventory {
   
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
   //Calcular el Kardex al registrar una entrada
   function calculateKardexNewInput($companyID,$transactionID,$transactionMasterID){
			$this->CI->load->model('core/Bd_Model');
			$queryResult = $this->CI->Bd_Model->executeRender("CALL pr_inventory_calculate_kardex_new_input (".$companyID.",".$transactionID.",".$transactionMasterID."); ");
			return $queryResult;
   }
    //Calcular el Kardex al registrar una salida
   function calculateKardexNewOutput($companyID,$transactionID,$transactionMasterID){
			$this->CI->load->model('core/Bd_Model');
			$queryResult = $this->CI->Bd_Model->executeRender("CALL pr_inventory_calculate_kardex_new_output (".$companyID.",".$transactionID.",".$transactionMasterID."); ");			
			log_message("ERROR",print_r("Mensaje Retornado por la salida de inventario",true));
			log_message("ERROR",print_r($queryResult,true));
			return $queryResult;
   }
}
?>
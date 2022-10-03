<?php if ( ! defined('BASEPATH')) exit('No se permite el acceso directo al script');

class core_web_concept {
   
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
   //Crear los Conceptos para la Transaccion de (Otras Entradas a Inventario)
   function otherinput($companyID,$transactionID,$transactionMasterID){
			$this->CI->load->model('core/Bd_Model');
			$queryResult = $this->CI->Bd_Model->executeRender("CALL pr_concept_helper_other_input(".$companyID.",".$transactionID.",".$transactionMasterID."); ");
			return $queryResult;
   }
   //Entrada sin postear
   function inputunpost($companyID,$transactionID,$transactionMasterID){
			$this->CI->load->model('core/Bd_Model');
			$queryResult = $this->CI->Bd_Model->executeRender("CALL pr_concept_helper_input_unpost(".$companyID.",".$transactionID.",".$transactionMasterID."); ");
			return $queryResult;
   }
   //Crear los Conceptos para la Transaccion de (Otras Salidas de Inventario)
   function otheroutput($companyID,$transactionID,$transactionMasterID){
			$this->CI->load->model('core/Bd_Model');
			$queryResult = $this->CI->Bd_Model->executeRender("CALL pr_concept_helper_other_output(".$companyID.",".$transactionID.",".$transactionMasterID."); ");
			return $queryResult;
   }
    //Crear los Conceptos para la Transaccion de (Devolucion de Compra)
   function returnsprovider($companyID,$transactionID,$transactionMasterID){
			$this->CI->load->model('core/Bd_Model');
			$queryResult = $this->CI->Bd_Model->executeRender("CALL pr_concept_helper_returns_provider(".$companyID.",".$transactionID.",".$transactionMasterID."); ");
			return $queryResult;
   }
   //Crear los Conceptos para la Transaccion de (Facturacion)
   function billing($companyID,$transactionID,$transactionMasterID){
			$this->CI->load->model('core/Bd_Model');
			$queryResult = $this->CI->Bd_Model->executeRender("CALL pr_concept_helper_billing(".$companyID.",".$transactionID.",".$transactionMasterID."); ");
			return $queryResult;
   }
    //Crear los Conceptos para la Transaccion de (Abono)
   function share($companyID,$transactionID,$transactionMasterID){
			$this->CI->load->model('core/Bd_Model');
			$queryResult = $this->CI->Bd_Model->executeRender("CALL pr_concept_helper_share(".$companyID.",".$transactionID.",".$transactionMasterID."); ");
			return $queryResult;
   }
   //Crear los Conceptos para la Transaccion de (Provisiones de Cuentas Incobrables)
   function provider($companyID,$transactionID,$transactionMasterID){
			$this->CI->load->model('core/Bd_Model');
			$queryResult = $this->CI->Bd_Model->executeRender("CALL pr_concept_helper_provider(".$companyID.",".$transactionID.",".$transactionMasterID."); ");
			return $queryResult;
   }
    //Crear los Conceptos para la Transaccion de (Cancelar documento de Credito)
   function cancelinvoice($companyID,$transactionID,$transactionMasterID){
			$this->CI->load->model('core/Bd_Model');
			$queryResult = $this->CI->Bd_Model->executeRender("CALL pr_concept_helper_cancelinvoice(".$companyID.",".$transactionID.",".$transactionMasterID."); ");
			return $queryResult;
   }
   //Crear los Conceptos para la Transaccion de (Abono al Capital)
   function shareCapital($companyID,$transactionID,$transactionMasterID){
			$this->CI->load->model('core/Bd_Model');
			$queryResult = $this->CI->Bd_Model->executeRender("CALL pr_concept_helper_sharecapital(".$companyID.",".$transactionID.",".$transactionMasterID."); ");
			return $queryResult;
   }
   //Crear los Conceptos para la Transaccion de (Pago de Planilla)
   function CalendarPay($companyID,$transactionID,$transactionMasterID){
			$this->CI->load->model('core/Bd_Model');
			$queryResult = $this->CI->Bd_Model->executeRender("CALL pr_concept_helper_calendarpay(".$companyID.",".$transactionID.",".$transactionMasterID."); ");
			return $queryResult;
   }
   //Crear los Conceptos para la Transaccion de (Adelanto de Salario)
   function salaryAdvance($companyID,$transactionID,$transactionMasterID){
    $this->CI->load->model('core/Bd_Model');
    $queryResult = $this->CI->Bd_Model->executeRender("CALL pr_concept_helper_salaryadvance(".$companyID.",".$transactionID.",".$transactionMasterID."); ");
    return $queryResult;
}
}
?>
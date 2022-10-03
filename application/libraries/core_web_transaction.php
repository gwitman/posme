<?php if ( ! defined('BASEPATH')) exit('No se permite el acceso directo al script');

class core_web_transaction {
   
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
   function getCountTransactionBillingAnuladas($companyID){
		$this->CI->load->model('core/Transaction_Model');		
		
		$invoiceAnuladasStatus 	= $this->CI->core_web_parameter->getParameter("INVOICE_BILLING_ANULADAS",$companyID);		
		$objComponent			= $this->CI->core_web_tools->getComponentIDBy_ComponentName("tb_transaction_master_billing");
		if(!$objComponent)
		throw new Exception("EL COMPONENTE 'tb_transaction_master_billing' NO EXISTE...");		
		
		$transactionID 	= $this->getTransactionID($companyID,"tb_transaction_master_billing",0);
		if(!$transactionID)
		throw new Exception("LA TRANSACCION  'tb_transaction_master_billing' NO EXISTE...");
		
		$result = $this->CI->Transaction_Model->getCounterTransactionMaster($companyID,$transactionID,$invoiceAnuladasStatus->value);
		return $result;
		
   }
   function getCountTransactionBillingCancel($companyID){
		$this->CI->load->model('core/Transaction_Model');		
		
		$invoiceCancelStatus 	= $this->CI->core_web_parameter->getParameter("INVOICE_BILLING_CANCEL",$companyID);
		$objComponent			= $this->CI->core_web_tools->getComponentIDBy_ComponentName("tb_transaction_master_billing");
		if(!$objComponent)
		throw new Exception("EL COMPONENTE 'tb_transaction_master_billing' NO EXISTE...");
		
		$transactionID 	= $this->getTransactionID($companyID,"tb_transaction_master_billing",0);
		if(!$transactionID)
		throw new Exception("LA TRANSACCION  'tb_transaction_master_billing' NO EXISTE...");
		
		$result = $this->CI->Transaction_Model->getCounterTransactionMaster($companyID,$transactionID,$invoiceCancelStatus->value);
		return $result;
   }
   function getDefaultCausalID($companyID,$transactionID){
		$this->CI->load->model("Transaction_Causal_Model");	
		
		$objCausal = $this->CI->Transaction_Causal_Model->getCausalDefaultID($companyID,$transactionID);
		if(!$objCausal)
		throw new Exception("NO HAY UN CAUSAL PORDEFECTO PARA LA TRANSACCION");		

		return $objCausal->transactionCausalID;
   }
   function createInverseDocumentByTransaccion($companyIDOriginal,$transactionIDOriginal,$transactionMasterIDOriginal,$transactionIDRevert,$transactionMasterIDRevert){
			$this->CI->load->model('core/Bd_Model');
			$queryResult = $this->CI->Bd_Model->executeRender("CALL pr_transaction_revert (".$companyIDOriginal.",".$transactionIDOriginal.",".$transactionMasterIDOriginal.",".$transactionIDRevert.",".$transactionMasterIDRevert."); ");
			return $queryResult;
   }
   function getTransactionID($companyID,$componentName,$componentItemID) {
		$this->CI->load->model('core/Company_Component_Flavor_Model');
		$this->CI->load->model('core/Component_Model');
		
		//obtener el Componente
		$objComponent = $this->CI->Component_Model->get_rowByName($componentName);
		if(!$objComponent)
		throw new Exception("NO EXISTE EL COMPONENTE '$componentName' DENTROS DE LOS REGISTROS DE 'Component' ");
		
		
		//obtener el flavor
		$objCompanyComponentFlavor = $this->CI->Company_Component_Flavor_Model->get_rowByCompanyAndComponentAndComponentItemID($companyID,$objComponent->componentID,$componentItemID);
		if(!$objCompanyComponentFlavor)
		throw new Exception("NO EXISTE EL FLAVOR PARA EL COMPONENTE DE CATALOGO ");
		
		//retornar transactionID
		return $objCompanyComponentFlavor->flavorID;
   }
   function getTransaction($companyID,$name){
		$this->CI->load->model('core/Transaction_Model');
		return $this->CI->Transaction_Model->get_rowByPK($companyID,$name);
		
   }
   function getConcept($companyID,$transactionName,$conceptName){
   	    $this->CI->load->model('core/Transaction_Model');
   	    $this->CI->load->model('core/Transaction_Concept_Model');
   	    
   	    $objT =  $this->CI->Transaction_Model->get_rowByPK($companyID,$transactionName);
   	    if(!$objT)
   	    throw new Exception("NO EXISTE LA TRANSACCION ".$transactionName);
   	    
   	    return $this->CI->Transaction_Concept_Model->get_rowByPK($companyID,$objT->transactionID,$conceptName);
   	    
   	    
   } 
   
}
?>
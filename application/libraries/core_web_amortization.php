<?php if ( ! defined('BASEPATH')) exit('No se permite el acceso directo al script');

class core_web_amortization {
   
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
   function cancelDocument($companyID,$customerCreditDocumentID,$amount){
		$this->CI->load->model("Customer_Credit_Document_Model");
		$this->CI->load->model("Customer_Credit_Amortization_Model");
		date_default_timezone_set(APP_TIMEZONE);
		 
		$documentCancel 							= $this->CI->core_web_parameter->getParameter("SHARE_DOCUMENT_CANCEL",$companyID)->value;
		$amortizationCancel 						= $this->CI->core_web_parameter->getParameter("SHARE_CANCEL",$companyID)->value;
		$objCustomerCreditDocument					= $this->CI->Customer_Credit_Document_Model->get_rowByPK($customerCreditDocumentID);
		
		
		//Cancel Document
		if($amount >= $objCustomerCreditDocument->balance){
			$objCustomerCreditDocumentNew["balance"]	= 0;
			$objCustomerCreditDocumentNew["statusID"]	= $documentCancel;
			$this->CI->Customer_Credit_Document_Model->update($objCustomerCreditDocument->customerCreditDocumentID,$objCustomerCreditDocumentNew);
		}
		else
			throw new Exception("EL IMPORTE NO ES SUFICIENTE PARA CANCELAR EL DOCUMENTO");
		
		//Cancelar Amortizacion
		$objListCustomerCreditDocumentAmortization 	= $this->CI->Customer_Credit_Amortization_Model->get_rowByDocumentAndVinculable($customerCreditDocumentID);
		if($objListCustomerCreditDocumentAmortization)
		foreach($objListCustomerCreditDocumentAmortization as $key => $itemAmortization){
			$itemAmortizationNew["remaining"]				= 0;
			$this->CI->Customer_Credit_Amortization_Model->update($itemAmortization->creditAmortizationID,$itemAmortizationNew);		
		}
		
		
   }
   function shareCapital($companyID,$customerCreditDocumentID,$amount){
		$this->CI->load->model("Customer_Credit_Document_Model");
		$this->CI->load->model("Customer_Credit_Amortization_Model");
		$this->CI->load->model("Customer_Credit_Line_Model");		
		date_default_timezone_set(APP_TIMEZONE);
		
		
		$objCustomerCreditDocument					= $this->CI->Customer_Credit_Document_Model->get_rowByPK($customerCreditDocumentID);
		$objListCustomerCreditDocumentAmortization 	= $this->CI->Customer_Credit_Amortization_Model->get_rowByDocumentAndVinculable($customerCreditDocumentID);
		$objCustomerCreditLine						= $this->CI->Customer_Credit_Line_Model->get_rowByPK($objCustomerCreditDocument->customerCreditLineID);
		$periodPay 									= $this->CI->Catalog_Item_Model->get_rowByCatalogItemID($objCustomerCreditLine->periodPay);

		$numCuotas									= count($objListCustomerCreditDocumentAmortization);
		$totalCapital								= $objCustomerCreditDocument->balance - $amount;
		//obtener el primer registro
		//$creditAmortizationIDMin					= array_reduce($objListCustomerCreditDocumentAmortization,function($v,$w){if (!$v)$v = $w->creditAmortizationID;if ($v > $w->creditAmortizationID){ $v = $w->creditAmortizationID;} return $v;});
		$dateApplyFirst 							= $objListCustomerCreditDocumentAmortization[0]->dateApply;
		
		$this->CI->financial_amort->amort(
						$totalCapital, 											/*monto*/
						$objCustomerCreditDocument->interes,					/*interes anual*/
						$numCuotas,												/*numero de pagos*/	
						$periodPay->sequence,									/*frecuencia de pago en dia*/
						$objCustomerCreditDocument->dateOn,						/*fecha del credito*/	
						$objCustomerCreditLine->typeAmortization 				/*tipo de amortizacion*/
					);
		
		//Recalcular Tabla de Amortizacion		
		$tableAmortization 	= $this->CI->financial_amort->getTable();		
		if($objListCustomerCreditDocumentAmortization)
		foreach($objListCustomerCreditDocumentAmortization as $key => $itemAmortization){
		
			$itemAmortizationNew	= null;
			//si es el primer registro , registrar que realizo un abono al capital
			if($dateApplyFirst == $itemAmortization->dateApply){
					$itemAmortizationNew["shareCapital"]	= $amount;
			}
			
			$itemAmortizationNew["balanceStart"]			= $tableAmortization["detail"][$key+1]["saldoInicial"];
			$itemAmortizationNew["balanceEnd"]				= $tableAmortization["detail"][$key+1]["saldo"];
			$itemAmortizationNew["capital"]					= $tableAmortization["detail"][$key+1]["principal"];
			$itemAmortizationNew["interest"]				= $tableAmortization["detail"][$key+1]["interes"];
			$itemAmortizationNew["share"]					= $itemAmortizationNew["interest"] + $itemAmortizationNew["capital"];
			$itemAmortizationNew["remaining"]				= $itemAmortizationNew["share"];			
			$this->CI->Customer_Credit_Amortization_Model->update($itemAmortization->creditAmortizationID,$itemAmortizationNew);		
		}
		
		//Actualizar Balance del Documento
		$objCustomerCreditDocumentNew				= null;
		$objCustomerCreditDocumentNew["balance"]	= $totalCapital;
		$this->CI->Customer_Credit_Document_Model->update($objCustomerCreditDocument->customerCreditDocumentID,$objCustomerCreditDocumentNew);
		
   }
   function changeStatus($companyID,$customerCreditDocumentID){
	   $this->CI->load->model("Customer_Credit_Document_Model");
		$this->CI->load->model("Customer_Credit_Amortization_Model");
		date_default_timezone_set(APP_TIMEZONE);
		 
		$documentProvisioned						= $this->CI->core_web_parameter->getParameter("CREDIT_DOCUMENT_PROVISIONED",$companyID)->value;
		$amortizationProvisioned					= $this->CI->core_web_parameter->getParameter("CREDIT_AMORTIZATION_PROVISIONED",$companyID)->value;
		$objCustomerCreditDocument					= $this->CI->Customer_Credit_Document_Model->get_rowByPK($customerCreditDocumentID);
		$objListCustomerCreditDocumentAmortization 	= $this->CI->Customer_Credit_Amortization_Model->get_rowByDocumentAndVinculable($customerCreditDocumentID);
		
		//Provisionar Documento
		if($objCustomerCreditDocument->balanceProvicioned >=  $objCustomerCreditDocument->balance){
			$objCustomerCreditDocumentNew				= null;
			$objCustomerCreditDocumentNew["statusID"]	= $documentProvisioned;
			$this->CI->Customer_Credit_Document_Model->update($objCustomerCreditDocument->customerCreditDocumentID,$objCustomerCreditDocumentNew);
		}
	   
   }
   function applyCuote($companyID,$customerCreditDocumentID,$amount,$amoritizationID){
		$this->CI->load->model("Customer_Credit_Document_Model");
		$this->CI->load->model("Customer_Credit_Amortization_Model");
		date_default_timezone_set(APP_TIMEZONE);
		 
		$documentCancel 							= $this->CI->core_web_parameter->getParameter("SHARE_DOCUMENT_CANCEL",$companyID)->value;
		$amortizationCancel 						= $this->CI->core_web_parameter->getParameter("SHARE_CANCEL",$companyID)->value;
		$objCustomerCreditDocument					= $this->CI->Customer_Credit_Document_Model->get_rowByPK($customerCreditDocumentID);
		$objListCustomerCreditDocumentAmortization 	= $this->CI->Customer_Credit_Amortization_Model->get_rowByDocumentAndVinculable($customerCreditDocumentID);
		$objConceptos 				= [];
		$objConceptos["capital"] 	= 0;
		$objConceptos["interes"] 	= 0;
		
		
		//Cancel Cuota
		if($objListCustomerCreditDocumentAmortization)
		foreach($objListCustomerCreditDocumentAmortization as $key => $itemAmortization){	
			$interval	= date_diff(date_create($itemAmortization->dateApply),date_create());			
			
			if ($amount >= $itemAmortization->remaining && $amount <> 0){
				$amount									= $amount - $itemAmortization->remaining;				
				$dif									= $itemAmortization->remaining - $amount;
				$itemAmortizationNew					= NULL;
				$itemAmortizationNew["statusID"]		= $amortizationCancel;
				$itemAmortizationNew["remaining"]		= 0; 
				$itemAmortizationNew["dayDelay"]		= $interval->format('%r%a');	
				
				//Abonar a la cuota completa
				if($itemAmortization->remaining == $itemAmortization->share )
				{
					$objConceptos["capital"] 	= $objConceptos["capital"] + $itemAmortization->capital;
					$objConceptos["interes"] 	= $objConceptos["interes"] + $itemAmortization->interest;
				}
				else if ($itemAmortization->remaining > $itemAmortization->interest){
					$objConceptos["capital"] 	= $objConceptos["capital"] + ($itemAmortization->remaining - $itemAmortization->interest);
					$objConceptos["interes"] 	= $objConceptos["interes"] + $itemAmortization->interest;
				}
				else if ($itemAmortization->remaining < $itemAmortization->interest){
					$objConceptos["capital"] 	= $objConceptos["capital"] + 0;
					$objConceptos["interes"] 	= $objConceptos["interes"] + $itemAmortization->remaining;
				}
				else if ($itemAmortization->remaining = $itemAmortization->interest){
					$objConceptos["capital"] 	= $objConceptos["capital"] + 0;
					$objConceptos["interes"] 	= $objConceptos["interes"] + $itemAmortization->interest;
				}
			
				$this->CI->Customer_Credit_Amortization_Model->update($itemAmortization->creditAmortizationID,$itemAmortizationNew);				
			}
			else if ($amount <> 0){	
				$itemAmortizationNew					= NULL;
				$itemAmortizationNew["remaining"]		= $itemAmortization->remaining - $amount;
				$itemAmortizationNew["dayDelay"]		= $interval->format('%r%a');
				$dif									= $itemAmortization->remaining - $amount;
				
				if ($dif > $itemAmortization->interest){
					$objConceptos["capital"] 	= $objConceptos["capital"] + $amount;
					$objConceptos["interes"] 	= $objConceptos["interes"] + 0;
				}
				else if ($dif == $itemAmortization->interest){
					$objConceptos["capital"] 	= $objConceptos["capital"] + $amount;
					$objConceptos["interes"] 	= $objConceptos["interes"] + 0;
				}
				else if ($dif < $itemAmortization->interest and $itemAmortization->remaining <= $itemAmortization->interest){
					$objConceptos["capital"] 	= $objConceptos["capital"] + 0;
					$objConceptos["interes"] 	= $objConceptos["interes"] + $amount;
				}
				else if ($dif < $itemAmortization->interest and $itemAmortization->remaining > $itemAmortization->interest){
					$capital001 				= $itemAmortization->remaining - $itemAmortization->interest;
					$interes001 				= $amount - $capital001;					
					$objConceptos["capital"] 	= $objConceptos["capital"] + $capital001;
					$objConceptos["interes"] 	= $objConceptos["interes"] + $interes001;
				}
				
				
				$this->CI->Customer_Credit_Amortization_Model->update($itemAmortization->creditAmortizationID,$itemAmortizationNew);				
				$amount 								= 0;					
			}
		}
		
		//Actualizar Balance del Documento
		$objCustomerCreditDocumentNew				= null;
		$objCustomerCreditDocumentNew["balance"]	= $objCustomerCreditDocument->balance - $objConceptos["capital"];
		$this->CI->Customer_Credit_Document_Model->update($objCustomerCreditDocument->customerCreditDocumentID,$objCustomerCreditDocumentNew);
		
		//Cancel Document
		$objListCustomerCreditDocumentAmortization 	= $this->CI->Customer_Credit_Amortization_Model->get_rowByDocumentAndVinculable($customerCreditDocumentID);			
		if(!$objListCustomerCreditDocumentAmortization){
			$objCustomerCreditDocumentNew				= null;
			$objCustomerCreditDocumentNew["statusID"]	= $documentCancel;
			$this->CI->Customer_Credit_Document_Model->update($objCustomerCreditDocument->customerCreditDocumentID,$objCustomerCreditDocumentNew);
		}
		
		
   }
  
}
?>

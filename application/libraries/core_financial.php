<?php if ( ! defined('BASEPATH')) exit('No se permite el acceso directo al script');

class core_financial {
   
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
   
   //Amount: 		Monto
   //Term: 			Plazo en meses
   //Interes: 		Interes anual en %
   //Share:  		Cantidad de Pagos
   //$dateFirstPay:	Primer pag   
   function getAmoritizationSimple($amount,$term,$interes,$share,$dateFirstPay){
		$tableAmortization;
		$year 					= 365;
		$amountInteres 			= $amount  * ($interes / 100) * $term;
		$dateLastPay			= $dateFirstPay;
		$next 					= 
								(
									(
										$dateFirstPay 
										+ 
										(	
											$term 
											* 
											$year
										)
									) 
									- 
									$dateFirstPay
								) 
								/ 
								$share;
		
		for($i =0; $i < $share ){
			$dateLastPay = $dateLastPay + $next;
			
			$tableAmortization[$i] = array (
				'share' 	= $i,
				'date'		= $dateLastPay,
				'amount' 	= $amount / $share,
				'interes'	= $amountInteres / $share,
				'total'		= ($amount / $share) + ($amountInteres / $share)
			);
			
			$i++;
		}	
   }
}
?>
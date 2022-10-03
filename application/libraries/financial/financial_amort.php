<?php if ( ! defined('BASEPATH')) exit('No se permite el acceso directo al script');

class financial_amort{
	var $amount;       			//amount of the loan
	var $rate;         			//percentage rate of the loan
	var $numberPay;    			//number of years of the loan
	var $npmts;        			//number of payments of the loan
	var $mrate;        			//monthly interest rate
	var $tpmnt;        			//total amount paid on the loan
	var $tint;         			//total interest paid on the loan
	var $pmnt;         			//monthly payment of the loan
	var $firstDate;				//yyyy-mm-dd	 
	var $typeAmortization;		//tipo de amortization 193-constante,194-frances,195-aleman,196-americano
	var $periodPay; 
	 
	//*******************************
	//193 Amortizacion Constante
	// Es un sistema de amortizacion que se caracteriza por cuotas
	// e interes, decrecientes y los valores de amortizacion del principal
	// constante.
	
	//*******************************
	//194 Amortizacion Frances
	// Es un sistema de amortizacion que se caracteriza por cuotas
	// iguales, valores de amortizacion del principal e interes crecientes
	
	//*******************************
	//195 Amortizacion Aleman
	// Es un sistema de amortizacion que se caracteriza por el interes pagado por adelantado
	// pagos iguales, a excepcion de la primera parcela lo que corresponde a lo intereses
	// la amortizacion del capital es creciente y los intereses decrecientes.
	
	//*******************************
	//196 Amortizacion Americano
	// Es un sistema de amortizacion que se caracteriza por el pago de cuotas iguales
	// al interes, excepto el ultimo, cuando el valor total del principal se añade.
	
	//*******************************
	//463 Amortizacion Simple
	// Es un sistema de amortizacion que se caracteriza por el pago de cuotas iguales
	// y el interese se multiplica por el mismo numero de meses, como que si no se disminyllera el principal
	
	function amort($amount=0,$rate=0,$numberPay=0,$periodPay = 0,$firstDate,$typeAmortization)
	{
		 date_default_timezone_set(APP_TIMEZONE);
		 $this->amount				=	$amount;  					//monto
		 $this->rate				=	$rate;   					//interes anual 
		 $this->numberPay			=	$numberPay;   				//numero de pagos
		 $this->periodPay 			= 	$periodPay;					//periodo de pago
		 $this->typeAmortization	=	$typeAmortization;			//tipo de amortizacion
		 $this->firstDate			=   date_create($firstDate);	//fecha del credito
		 
	}
	function getPmtValueAleman($pv,$n,$i){ 
		$pmt = (($pv* $i) / (1-( pow((1 - $i),$n))));
		return $pmt;
	}
	
	function getBaseRatio($periodPay){
		if ($periodPay == 7)
			return 52;
		else if ($periodPay == 15)
			return 24;
		else if ($periodPay == 30)
			return 12;
		else if ($periodPay == 45)
			return 8;
		else
			return 0;			
	}
	function getNextDate($date,$periodPay){
				
		$day				= date_format($date, 'd');
		$firstDateMonth		= date_format($date, 'Y-m');
		$firstDateMonth		= $firstDateMonth."-01";
		$firstDateMonth		= date_create($firstDateMonth);
		 
		$lastDateMonth		= date_create(date_format($firstDateMonth,"Y-m-d"));
		$lastDateMonth		= date_add($lastDateMonth, date_interval_create_from_date_string('1 months'));
		$lastDateMonth		= date_sub($lastDateMonth, date_interval_create_from_date_string('1 days'));
		
		/*semanal*/
		if ($periodPay == 7)
			return date_add($date,date_interval_create_from_date_string('7 days'));
		 /*quincenal*/ 
		else if ($periodPay == 15)
			return date_add($date,date_interval_create_from_date_string('15 days'));			
		/*mensual*/
		else if ($periodPay == 30)
			return date_add($date,date_interval_create_from_date_string('1 months'));
		/*45 dias*/ 
		else if ($periodPay == 45) 
		{
			return 
				date_add(
					date_add($date,date_interval_create_from_date_string('1 months')),
					date_interval_create_from_date_string("15 days")
				);
		}
		else
			return $date;
	}
	
	function getPmtValueFrances($pv,$n,$i){ 
		$pmt = ($pv * $i*( pow( 1 + $i ,$n )) / ( pow( 1 + $i , $n ) - 1));
		return $pmt;
	}
	function getPmtValueSimple($pv,$n,$i){		
		$pmt = ($pv * $i*( pow( 1 + $i ,$n )) / ( pow( 1 + $i , $n ) - 1));
		$pmt = round(($pv + ($pv * ($i) * $n)) / $n,2,PHP_ROUND_HALF_UP); 
		return $pmt;
	}
	function getTable(){
		$result["summary"]					= null;
		$result["summary"]["totalPay"] 		= 0;
		$result["summary"]["totalIntest"] 	= 0;
		$result["summary"]["totalCuotas"] 	= 0;
		$result["summary"]["pagoMensual"] 	= 0;
		$result["detail"] 					= null;
		
		if ($this->typeAmortization == 194)
			$result = $this->getTableFrances();
		else if ($this->typeAmortization == 195)
			$result = $this->getTableAleman();
		else if ($this->typeAmortization == 196)
			$result = $this->getTableAmericano();
		else if ($this->typeAmortization == 463)
			$result = $this->getTableSimple();
		else 
			$result = $this->getTableConstante();
			
		return $result;
	
	}
	function getTableSimple(){
		$pv 	= $this->amount;
		$n   	= $this->numberPay; 
		$i  	= ($this->rate / $this->getBaseRatio($this->periodPay)) / 100;		
		$pmt 	= $this->getPmtValueSimple($pv,$n,$i);
		log_message("ERROR","punto de interrupcion  ******INICIO TABLA DE AMORTIZACION");
		log_message("ERROR","punto de interrupcion  pv:".$pv);
		log_message("ERROR","punto de interrupcion  n:".$n);
		log_message("ERROR","punto de interrupcion  i:".$i); 
		log_message("ERROR","punto de interrupcion  pmt:".$pmt);
		
		$result["summary"]					= null;
		$result["summary"]["totalPay"] 		= ($pmt);
		$result["summary"]["totalIntest"] 	= ($pmt * $n) - $pv;
		$result["summary"]["totalCuotas"] 	= ($pmt * $n);
		$result["summary"]["pagoMensual"] 	= 0;
		$result["detail"] 					= null;
		log_message("ERROR","punto de interrupcion  smmary:".print_r($result["summary"],true));
		
		
		$amount		=$this->amount;
		$numpay		=$this->numberPay; 
		$rate		=($this->rate / $this->getBaseRatio($this->periodPay));
		$rate		=$rate/100;
		$monthly	=$rate;
		$payment	=$pmt;
		$total		=$payment*$numpay;
		$interest	=$total-$amount;
	
		$balance	=$amount;
		$i 			=1;
		$nextDate 	=$this->firstDate;
		log_message("ERROR","punto de interrupcion  amount:".$amount);
		log_message("ERROR","punto de interrupcion  balance:".$balance);		
		log_message("ERROR","punto de interrupcion  numberPay:".$numpay);
		log_message("ERROR","punto de interrupcion  rate:".$rate);
		log_message("ERROR","punto de interrupcion  monthly:".$monthly);
		log_message("ERROR","punto de interrupcion  payment:".$payment);
		log_message("ERROR","punto de interrupcion  total:".$total);
		log_message("ERROR","punto de interrupcion  interest:".$interest);
		
		while ($i <= $numpay) {
			$newInterest	= $monthly*$amount;
			$amort			= $payment-$newInterest;
			$balance		= $balance-$amort;
			$balanceInicial	= $balance+$amort;
		
			log_message("ERROR","punto de interrupcion  ******detalle*******");
			log_message("ERROR","punto de interrupcion  newInterest:".$newInterest);
			log_message("ERROR","punto de interrupcion  amort:".$amort);
			log_message("ERROR","punto de interrupcion  balanceInicial:".$balanceInicial);
			log_message("ERROR","punto de interrupcion  balance:".$balance);
			
		
			$result["detail"][$i]	  				= null;
			$result["detail"][$i]["pnum"] 			= $i;									
			$result["detail"][$i]["date"] 			= date_format($nextDate,"Y-m-d");		
			$result["detail"][$i]["principal"] 		= sprintf("%01.2f",$amort) ;				
			$result["detail"][$i]["interes"] 		= sprintf("%01.2f",$newInterest) ;			
			$result["detail"][$i]["cuota"] 			= sprintf("%01.2f",$payment);				
			$result["detail"][$i]["saldo"] 			= sprintf("%01.2f",$balance) ;				
			$result["detail"][$i]["saldoInicial"] 	= sprintf("%01.2f",$balanceInicial) ;
			$result["detail"][$i]["cpmnt"] 			= 0;
			$nextDate								= $this->getNextDate($nextDate,$this->periodPay);			
			log_message("ERROR","punto de interrupcion  detail:".print_r($result["detail"][$i],true));
			$i++;
		}
		log_message("ERROR","punto de interrupcion  ******FIN TABLA DE AMORTIZACION");
		return $result;
	}
	function getTableFrances(){
	
		$pv 	= $this->amount;
		$n   	= $this->numberPay; 
		$i  	= ($this->rate / $this->getBaseRatio($this->periodPay)) / 100;					
		$pmt 	= $this->getPmtValueFrances($pv,$n,$i);
		
		log_message("ERROR","punto de interrupcion  ******INICIO TABLA DE AMORTIZACION");
		log_message("ERROR","punto de interrupcion  pv:".$pv);
		log_message("ERROR","punto de interrupcion  n:".$n);
		log_message("ERROR","punto de interrupcion  i:".$i); 
		log_message("ERROR","punto de interrupcion  pmt:".$pmt);
		
		$result["summary"]					= null;
		$result["summary"]["totalPay"] 		= ($pmt);
		$result["summary"]["totalIntest"] 	= ($pmt * $n) - $pv;
		$result["summary"]["totalCuotas"] 	= ($pmt * $n);
		$result["summary"]["pagoMensual"] 	= 0;
		$result["detail"] 					= null;
		log_message("ERROR","punto de interrupcion  smmary:".print_r($result["summary"],true));
		
		$amount		=$this->amount;
		$numpay		=$this->numberPay; 
		$rate		=($this->rate / $this->getBaseRatio($this->periodPay));
		$rate		=$rate/100;
		$monthly	=$rate;
		$payment	=(($amount*$monthly)/(1-pow((1+$monthly),-$numpay)));
		$total		=$payment*$numpay;
		$interest	=$total-$amount;
	
		$balance	=$amount;
		$i 			=1;
		$nextDate 	=$this->firstDate;
			
		while ($i <= $numpay) {
			$newInterest	= $monthly*$balance;
			$amort			= $payment-$newInterest;
			$balance		= $balance-$amort;
			$balanceInicial	= $balance+$amort;
			
			$result["detail"][$i]	  				= null;
			$result["detail"][$i]["pnum"] 			= $i;									
			$result["detail"][$i]["date"] 			= date_format($nextDate,"Y-m-d");		
			$result["detail"][$i]["principal"] 		= sprintf("%01.2f",$amort) ;				
			$result["detail"][$i]["interes"] 		= sprintf("%01.2f",$newInterest) ;			
			$result["detail"][$i]["cuota"] 			= sprintf("%01.2f",$payment);				
			$result["detail"][$i]["saldo"] 			= sprintf("%01.2f",$balance) ;				
			$result["detail"][$i]["saldoInicial"] 	= sprintf("%01.2f",$balanceInicial) ;
			$result["detail"][$i]["cpmnt"] 			= 0;
			$nextDate								= $this->getNextDate($nextDate,$this->periodPay);			
			$i++;
		}
		
		return $result;
	}
	function getTableAleman(){
		
		$pv 		= $this->amount;
		$n   		= $this->numberPay; 
		$i  		= ($this->rate / $this->getBaseRatio($this->periodPay)) / 100;
		$pmt 		= $this->getPmtValueAleman($pv,$n,$i);
		$interest 	= $pv*$i;
		
		$result["summary"]					= null;
		$result["summary"]["totalPay"] 		= ($pmt);
		$result["summary"]["totalIntest"] 	= (($pmt * $n) + $interest) - $pv;
		$result["summary"]["totalCuotas"] 	= (($pmt * $n) + $interest);
		$result["summary"]["pagoMensual"] 	= $pv * $i;
		$result["detail"] 					= null;
		
		$amount		=$this->amount;
		$numpay		=$this->numberPay; 
		$rate		=($this->rate / $this->getBaseRatio($this->periodPay));
		$rate		=$rate/100;
		$monthly	=$rate;
		
		
		
		$Init_interest 	=$monthly*$amount;
		$Init_parcela 	=$Init_interest;
		$s 				=(1-$monthly);
		$payment		=($amount* $monthly) / (1-( pow($s,$numpay)));
		$amort 			=0;
		$principal 		=$payment;
		$saldo 			=$amount;
		$n 				=$numpay;
		$total			=$payment*$numpay;
		$interest		=$total-$amount;
		$nextDate 		=$this->firstDate;
		$saldo			=$amount;
		$i 				=1;
		
		
		
		$result["detail"][$i-1]	  					= null;
		$result["detail"][$i-1]["pnum"] 			= $i;									
		$result["detail"][$i-1]["date"] 			= date_format($nextDate,"Y-m-d");		
		$result["detail"][$i-1]["interes"] 			= sprintf("%01.2f",$Init_interest) ;
		$result["detail"][$i-1]["cuota"] 			= sprintf("%01.2f",$Init_parcela) ;
		$result["detail"][$i-1]["principal"] 		= 0;
		$result["detail"][$i-1]["saldoInicial"] 	= sprintf("%01.2f",$amount) ;
		$result["detail"][$i-1]["saldo"] 			= sprintf("%01.2f",$amount) ;
		$result["detail"][$i-1]["cpmnt"] 			= 0;
		
		
		while ($i <= $numpay) {
			$amort			=$payment*(pow($s,($n-$i)));
			$saldo			=$saldo-$amort;
			$newInterest	=$monthly*$saldo;
			$newpayment 	=$amort;
			$newInterest	=$monthly*$saldo;
			$saldoInicial	=$saldo + $amort;
			
			if($i==$numpay){
				$newInterest	= 0;
				$parcela 		= $payment;
			}
		
			$nextDate								= $this->getNextDate($nextDate,$this->periodPay);
			$result["detail"][$i]	  				= null;
			$result["detail"][$i]["pnum"] 			= $i;									
			$result["detail"][$i]["date"] 			= date_format($nextDate,"Y-m-d");		
			$result["detail"][$i]["principal"]		= sprintf("%01.2f",$amort) ;				
			$result["detail"][$i]["interes"] 		= sprintf("%01.2f",$newInterest) ;			
			$result["detail"][$i]["cuota"] 			= sprintf("%01.2f",$payment);				
			$result["detail"][$i]["saldo"] 			= sprintf("%01.2f",$saldo) ;
			$result["detail"][$i]["saldoInicial"] 	= sprintf("%01.2f",$saldoInicial) ;
			$result["detail"][$i]["cpmnt"] 			= 0;
			
			$i++;
		}
		
		return $result;
	}
	function getTableAmericano(){
		$pv 		= $this->amount;
		$n   		= $this->numberPay; 
		$i  		= ($this->rate / $this->getBaseRatio($this->periodPay)) / 100;
		$interest 	= $n * $pv * $i;		
		
		$result["summary"]					= null;
		$result["summary"]["totalPay"] 		= 0;
		$result["summary"]["totalIntest"] 	= $interest;
		$result["summary"]["totalCuotas"] 	= $pv + $interest;
		$result["summary"]["pagoMensual"] 	= 0;
		$result["detail"] 					= null;
		
		$amount		=$this->amount;
		$numpay		=$this->numberPay; 
		$rate		=($this->rate / $this->getBaseRatio($this->periodPay));
		$rate		=$rate/100;
		$monthly	=$rate;
		$payment	=0;
		$amort 		="";
		$saldo 		=$amount;
		$n 			=$numpay;
		$total		=$payment*$numpay;
		$interest	=$total-$amount;
		$saldo		=$amount;
		$i 			=1;
		$nextDate 	=$this->firstDate;
		
		
		while ($i <= $numpay) {
			$newInterest	=$monthly*$amount;
			$payment 		=$newInterest;
			
			if($i==$numpay){
				$saldo 		= 0;
				$amort 		= $amount;
				$payment 	= $amount+($monthly*$amount);
			}
			
			
			$saldoInicial							= $saldo + $amort;
			$result["detail"][$i]	  				= null;
			$result["detail"][$i]["pnum"] 			= $i;									
			$result["detail"][$i]["date"] 			= date_format($nextDate,"Y-m-d");		
			$result["detail"][$i]["principal"]		= sprintf("%01.2f",$amort) ;				
			$result["detail"][$i]["interes"] 		= sprintf("%01.2f",$newInterest) ;			
			$result["detail"][$i]["cuota"] 			= sprintf("%01.2f",$payment);				
			$result["detail"][$i]["saldo"] 			= sprintf("%01.2f",$saldo) ;
			$result["detail"][$i]["saldoInicial"] 	= sprintf("%01.2f",$saldoInicial) ;
			$result["detail"][$i]["cpmnt"] 			= 0;
			$nextDate								= $this->getNextDate($nextDate,$this->periodPay);
			$i++;
		}

		return $result;
	
	}
	function getTableConstante(){
	
		$pv 		= $this->amount;
		$n   		= $this->numberPay; 
		$i  		= ($this->rate / $this->getBaseRatio($this->periodPay)) / 100;
		
		$p 				= $pv/$n;
		$saldo 			= $pv+$p;
		$npv 			= 0;
		$newInterest 	= 0;
		$Totpay 		= 0;
		
		for ($t=1;$t<= $n;$t++ ){
			$npv			= $saldo-$p ;
			$newInterest 	= $newInterest+($i*$npv);
			$saldo 			= $npv;
			$Totpay 		= $Totpay+$npv;
			
		}
		
		
		$Totint 							=  $newInterest;//total de intereses
		$Totpay 							=  $pv+$Totint;//total de pago
		
			
		$result["summary"]					= null;
		$result["summary"]["totalPay"] 		= 0;
		$result["summary"]["totalIntest"] 	= $Totint;
		$result["summary"]["totalCuotas"] 	= $Totpay;
		$result["summary"]["pagoMensual"] 	= 0;
		$result["detail"] 					= null;
		
			
		$amount		=$this->amount;
		$numpay		=$this->numberPay; 
		$rate		=($this->rate / $this->getBaseRatio($this->periodPay));
		$rate		=$rate/100;
		$monthly	=$rate;
		$base		=(1-pow((1+$monthly),-$numpay));		
		$payment	=$base == 0 ? ($amount / $numpay) : (($amount*$monthly) / $base);
		$total		=$payment*$numpay;
		$interest	=$total-$amount;
		$saldo		=$amount;
		$Totint 	=0;
		$i 			=1;
		$nextDate 	=$this->firstDate;		
		
		while ($i <= $numpay) {
			$newInterest	=$monthly*$saldo;
			$principal 		=$amount/$numpay;
			$parcela		=$principal+$newInterest;
			$saldo			=$saldo-$principal ;
			$saldoInicial	=$saldo+$principal ;
			
			
			$result["detail"][$i]	  				= null;
			$result["detail"][$i]["pnum"] 			= $i;									
			$result["detail"][$i]["date"] 			= date_format($nextDate,"Y-m-d");		
			$result["detail"][$i]["principal"] 		= sprintf("%01.2f",$principal) ;				
			$result["detail"][$i]["interes"] 		= sprintf("%01.2f",$newInterest) ;			
			$result["detail"][$i]["cuota"] 			= sprintf("%01.2f",$parcela);				
			$result["detail"][$i]["saldo"] 			= sprintf("%01.2f",$saldo) ;				
			$result["detail"][$i]["saldoInicial"] 	= sprintf("%01.2f",$saldoInicial);
			$result["detail"][$i]["cpmnt"] 			= 0;
			$nextDate								= $this->getNextDate($nextDate,$this->periodPay);
			$i++;
		}
		
		return $result;
		
	}
}

?>
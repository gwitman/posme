<?php if ( ! defined('BASEPATH')) exit('No se permite el acceso directo al script');
class core_web_currency {
   
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
   function getCurrencyDefault($companyID){
   		$this->CI->load->model('core/Currency_Model');
	    $moneyFuncionalName = $this->CI->core_web_parameter->getParameter("ACCOUNTING_CURRENCY_NAME_FUNCTION",$companyID);
	    //Obtener Moneda
	    return $this->CI->Currency_Model->get_rowName($moneyFuncionalName->value);
   }
   function getCurrencyReport($companyID){
   		$this->CI->load->model('core/Currency_Model');
	    $moneyFuncionalName = $this->CI->core_web_parameter->getParameter("ACCOUNTING_CURRENCY_NAME_REPORT",$companyID);
	    //Obtener Moneda
	    return $this->CI->Currency_Model->get_rowName($moneyFuncionalName->value);
   }
   function getCurrencyExternal($companyID){
		$this->CI->load->model('core/Currency_Model');
		$moneyFuncionalName = $this->CI->core_web_parameter->getParameter("ACCOUNTING_CURRENCY_NAME_EXTERNAL",$companyID);
		//Obtener Moneda
		return $this->CI->Currency_Model->get_rowName($moneyFuncionalName->value);
	}
   function getCurrencyName($name){
   		$this->CI->load->model('core/Currency_Model');
   		
	    //Obtener Moneda
	    return $this->CI->Currency_Model->get_rowName($name);
   }
   function getTarget($companyID,$currencySourceID){
	   $default = $this->getCurrencyDefault($companyID)->currencyID;
	   $report  = $this->getCurrencyExternal($companyID)->currencyID;
	   
	   
	   $result  = ($currencySourceID == $default) ? $report : $default;
	   return $result;
   }
   //Convertir tasa de cambio
   //Cuantos $targetCurrencyID(DOLARES) son en $quantity $currencyID (CORDOBA)
   function getRatio($companyID,$dateRatio,$quantity,$currencyID,$targetCurrencyID){   		
   		$this->CI->load->model('core/ExchangeRate_Model');
		
		//Obtener monedas por defecto
		$exhangeRate			= 1;
		$exhangeRate2			= 1;
   		$objConvertionDefault  	= $this->getCurrencyDefault($companyID);
		if(!$objConvertionDefault)
		throw new Exception("NO EXISTE LA MONEDA POR DEFECTO");
		
		//Cuentos CORDOBAS son en tantos CORDOBAS
		$objConvertionSource = $this->CI->ExchangeRate_Model->get_rowByPK($companyID,$dateRatio,$objConvertionDefault->currencyID,$currencyID);
		if($currencyID != $objConvertionDefault->currencyID){
			if(!$objConvertionSource)
				throw new Exception("NO EXISTE LA TASA DE CAMBIO [012555]:  ".$dateRatio);
			else 
				$exhangeRate			= (float)$objConvertionSource->ratio;
		}
		
		//Cuantos DOLARES son en tantos CORDOBAS
		$objConvertionTarget = $this->CI->ExchangeRate_Model->get_rowByPK($companyID,$dateRatio,$objConvertionDefault->currencyID,$targetCurrencyID);
		if($targetCurrencyID != $objConvertionDefault->currencyID){
			if(!$objConvertionTarget)
				throw new Exception("NO EXISTE LA TASA DE CAMBIO [012556]:  ".$dateRatio);
			else 
				$exhangeRate2			= (float)$objConvertionTarget->ratio;
		}
		
		if($targetCurrencyID == $currencyID)
			return $quantity;
		else 
		{
			return ($quantity * $exhangeRate) / $exhangeRate2;
		}
		
   }
   
}
?>
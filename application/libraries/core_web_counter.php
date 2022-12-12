<?php if ( ! defined('BASEPATH')) exit('No se permite el acceso directo al script');
class core_web_counter {
   
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
   //Se le pasa un numero y retorna en formato del contador
   //Ejemplo: 1 --> EMP00000001
   function getFillNumber($companyID,$branchID,$componentName,$componentItemID,$number){
		$this->CI->load->model('core/Counter_Model');
		$this->CI->load->model('core/Component_Model');
		
		//obtener componente 
		$objComponente 	= $this->CI->Component_Model->get_rowByName($componentName);
		if(!$objComponente)
		throw new Exception("NO EXISTE EL COMPONENTE '".$componentName."' ");
		
		//obtener el countador		
		$objCounter 	= $this->CI->Counter_Model->get_rowByPK($companyID,$branchID,$objComponente->componentID,$componentItemID); 
		if(!$objCounter)
		throw new Exception("NO EXISTE EL CONTADOR ");
		
		$value = str_pad(intval($number), $objCounter->length, "0", STR_PAD_LEFT);
		$value = $objCounter->serie.$value; 
		
		//retornar valor 
		return $value;
   }
   //Obtiene el contador actual
   function getCurrenctNumber($companyID,$branchID,$componentName,$componentItemID){
		
		$this->CI->load->model('core/Counter_Model');
		$this->CI->load->model('core/Component_Model');
		
		//obtener componente 
		$objComponente 	= $this->CI->Component_Model->get_rowByName($componentName);
		if(!$objComponente)
		throw new Exception("NO EXISTE EL COMPONENTE '".$componentName."' ");
		
		//obtener el countador		
		$objCounter 	= $this->CI->Counter_Model->get_rowByPK($companyID,$branchID,$objComponente->componentID,$componentItemID); 
		if(!$objCounter)
		throw new Exception("NO EXISTE EL CONTADOR ");
		
		$value = str_pad($objCounter->currentValue, $objCounter->length, "0", STR_PAD_LEFT);
		$value = $objCounter->serie.$value; 
		
		//retornar valor 
		return $value;
		
   }
   //Pasa al siguiente contador 
   //y retorna el valor calculado
   function goNextNumber($companyID,$branchID,$componentName,$componentItemID){
		$this->CI->load->model('core/Counter_Model');
		$this->CI->load->model('core/Component_Model');
		
		//obtener componente 
		$objComponente 	= $this->CI->Component_Model->get_rowByName($componentName);
		if(!$objComponente)
		throw new Exception("NO EXISTE EL COMPONENTE '".$componentName."' ");
		
		//obtener el countador		
		$objCounter 	= $this->CI->Counter_Model->get_rowByPK($companyID,$branchID,$objComponente->componentID,$componentItemID); 
		if(!$objCounter)
		throw new Exception("NO EXISTE EL CONTADOR ");
		//actualizar
		$data["currentValue"] = $objCounter->currentValue + $objCounter->seed;
		$this->CI->Counter_Model->update($companyID,$branchID,$objComponente->componentID,$componentItemID,$data);
		
		//obtener valor
		$value = str_pad($objCounter->currentValue, $objCounter->length, "0", STR_PAD_LEFT);
		$value = $objCounter->serie.$value; 
		
		//retornar valor 
		return $value;
		
		
   }
}
?>
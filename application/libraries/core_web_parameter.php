<?php if ( ! defined('BASEPATH')) exit('No se permite el acceso directo al script');
class core_web_parameter {
   
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
   function getParameter($parameterName,$companyID){
		$this->CI->load->model('core/Parameter_Model');
		$this->CI->load->model('core/Company_Parameter_Model');
		
		
		//Obtener el Parametro
		$objParameter = $this->CI->Parameter_Model->get_rowByName($parameterName);
		if(!$objParameter)
		throw new Exception("NO EXISTE EL PARAMETRO ".$parameterName);
		
		//Obtener el CompanyParameter
		$objCompanyParameter =  $this->CI->Company_Parameter_Model->get_rowByParameterID_CompanyID($companyID,$objParameter->parameterID);
		if(!$objCompanyParameter)
		throw new Exception("NO EXISTE EL PARAMETRO ".$parameterName." PARA LA COMPANY ".$companyID);
		
		return $objCompanyParameter;		
		
   }
}
?>
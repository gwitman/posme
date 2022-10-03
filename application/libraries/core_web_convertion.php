<?php if ( ! defined('BASEPATH')) exit('No se permite el acceso directo al script');

class core_web_convertion {
   
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
   function convert($companyID,$quantity,$catalogID,$fromCatalogItemID,$toCatalogItemID){
		$this->CI->load->model('core/Catalog_Item_Convertion_Model');
		
		$objConvertionDefault = $this->CI->Catalog_Item_Convertion_Model->get_default($companyID,$catalogID);
		if(!$objConvertionDefault)
		throw new Exception("NO EXISTE EL CATALOGITEM DEFAULT EN EL CATALOGO");
		
		$objConvertionSource = $this->CI->Catalog_Item_Convertion_Model->get_rowByPK($companyID,$catalogID,$fromCatalogItemID,$objConvertionDefault->catalogItemID);
		if(!$objConvertionSource)
		throw new Exception("NO EXISTE EL CATALOGITEM-SOURCE --> DEFAULT");
		
		$objConvertionTarget = $this->CI->Catalog_Item_Convertion_Model->get_rowByPK($companyID,$catalogID,$toCatalogItemID,$objConvertionDefault->catalogItemID);
		if(!$objConvertionTarget)
		throw new Exception("NO EXISTE EL CATALOGITEM-TARGET --> DEFAULT");
		
		if($objConvertionSource->catalogItemID == $objConvertionTarget->catalogItemID)
		return $quantity;

		$result = 0;
		//De Menor al Default
		if($objConvertionTarget->catalogItemID == $objConvertionDefault->catalogItemID && $objConvertionSource->ratio > 0 ){
			$result = ($quantity/$objConvertionSource->ratio);
		}
		//De Mayor al Default
		else if($objConvertionTarget->catalogItemID == $objConvertionDefault->catalogItemID && $objConvertionSource->ratio < 0){
			$result = ($quantity*$objConvertionSource->ratio);
		}
		//De Menor a mayor		
		else if($objConvertionSource->ratio > $objConvertionTarget->ratio ){
			
			$result = ($quantity * $objConvertionTarget->ratio)/$objConvertionSource->ratio;
		}
		//De Mayor a Menor
		else{
			$result = ($quantity * $objConvertionTarget->ratio)/$objConvertionSource->ratio;
		}
		
		return $result;		

   }
}
?>
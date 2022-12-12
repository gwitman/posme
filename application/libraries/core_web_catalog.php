<?php if ( ! defined('BASEPATH')) exit('No se permite el acceso directo al script');
class core_web_catalog {
   
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
   function getCatalogAllItem($table,$field,$companyID){
		$this->CI->load->model('core/Component_Model');
		$this->CI->load->model('core/Element_Model');
		$this->CI->load->model('core/Sub_Element_Model');
		$this->CI->load->model('core/Company_Component_Flavor_Model');
		$this->CI->load->model('core/Catalog_Model');  
		$this->CI->load->model('core/Catalog_Item_Model');  
		
		//obtener elemento 
		$objElement 	= $this->CI->Element_Model->get_rowByName($table,ELEMENT_TYPE_TABLE);
		if(!$objElement)
		throw new Exception("NO EXISTE LA TABLA '".$table."' DENTRO DE LOS REGISTROS DE ELEMENT ");
		
		//obtener subelement
		$objSubElement 	= $this->CI->Sub_Element_Model->get_rowByNameAndElementID($objElement->elementID,$field); 
		if(!$objSubElement)
		throw new Exception("NO EXISTE EL CAMPO '".$field."' DENTRO DE LOS REGISTROS DE SUBELEMENT PARA EL ELEMENTO '".$table."' ");
		
		//obtener componente catalogo
		$objComponent = $this->CI->Component_Model->get_rowByName("tb_catalog");
		if(!$objComponent)
		throw new Exception("NO EXISTE EL COMPONENTE 'tb_catalog' DENTROS DE LOS REGISTROS DE 'Component' ");
		
		
		//obtener el catalogo
		if(!$objSubElement->catalogID)
		throw new Exception("EN LA TABLA SUBELEMENT PARA '".$field."' NO EXISTE EL CATALOGO CONFIGURADO");
		
		$objCatalog = $this->CI->Catalog_Model->get_rowByCatalogID($objSubElement->catalogID);
		if(!$objCatalog)
		throw new Exception("NO EXISTE EL CATALOGO ");
				
		//obtener flavor
		$objCompanyComponentFlavor = $this->CI->Company_Component_Flavor_Model->get_rowByCompanyAndComponentAndComponentItemID($companyID,$objComponent->componentID,$objCatalog->catalogID);
		if(!$objCompanyComponentFlavor)
		throw new Exception("NO EXISTE EL FLAVOR PARA EL COMPONENTE DE CATALOGO ");
		
		//obtener la lista de catalogItem
		$objCatalogItem = $this->CI->Catalog_Item_Model->get_rowByCatalogIDAndFlavorID($objCatalog->catalogID,$objCompanyComponentFlavor->flavorID);
		return $objCatalogItem;
		
   }
   function getCatalogAllItem_Parent($table,$field,$companyID,$parentCatalogItemID){
		$this->CI->load->model('core/Component_Model');
		$this->CI->load->model('core/Element_Model');
		$this->CI->load->model('core/Sub_Element_Model');
		$this->CI->load->model('core/Company_Component_Flavor_Model');
		$this->CI->load->model('core/Catalog_Model');  
		$this->CI->load->model('core/Catalog_Item_Model');  
		
		//obtener elemento 
		$objElement 	= $this->CI->Element_Model->get_rowByName($table,ELEMENT_TYPE_TABLE);
		if(!$objElement)
		throw new Exception("NO EXISTE LA TABLA '".$table."' DENTRO DE LOS REGISTROS DE ELEMENT ");
		
		//obtener subelement
		$objSubElement 	= $this->CI->Sub_Element_Model->get_rowByNameAndElementID($objElement->elementID,$field); 
		if(!$objSubElement)
		throw new Exception("NO EXISTE EL CAMPO '".$field."' DENTRO DE LOS REGISTROS DE SUBELEMENT PARA EL ELEMENTO '".$table."' ");
		
		//obtener componente catalogo
		$objComponent = $this->CI->Component_Model->get_rowByName("tb_catalog");
		if(!$objComponent)
		throw new Exception("NO EXISTE EL COMPONENTE 'tb_catalog' DENTROS DE LOS REGISTROS DE 'Component' ");
		
		
		//obtener el catalogo
		if(!$objSubElement->catalogID)
		throw new Exception("EN LA TABLA SUBELEMENT PARA '".$field."' NO EXISTE EL CATALOGO CONFIGURADO");
		
		$objCatalog = $this->CI->Catalog_Model->get_rowByCatalogID($objSubElement->catalogID);
		if(!$objCatalog)
		throw new Exception("NO EXISTE EL CATALOGO ");
				
		//obtener flavor
		$objCompanyComponentFlavor = $this->CI->Company_Component_Flavor_Model->get_rowByCompanyAndComponentAndComponentItemID($companyID,$objComponent->componentID,$objCatalog->catalogID);
		if(!$objCompanyComponentFlavor)
		throw new Exception("NO EXISTE EL FLAVOR PARA EL COMPONENTE DE CATALOGO ");
		
		//obtener la lista de catalogItem
		$objCatalogItem = $this->CI->Catalog_Item_Model->get_rowByCatalogIDAndFlavorID_Parent($objCatalog->catalogID,$objCompanyComponentFlavor->flavorID,$parentCatalogItemID);
		return $objCatalogItem;
		
   }
   function getCatalogItem($table,$field,$companyID,$catalogItemID){
		$this->CI->load->model('core/Component_Model');
		$this->CI->load->model('core/Element_Model');
		$this->CI->load->model('core/Sub_Element_Model');
		$this->CI->load->model('core/Company_Component_Flavor_Model');
		$this->CI->load->model('core/Catalog_Model');  
		$this->CI->load->model('core/Catalog_Item_Model');  
		
		//obtener elemento 
		$objElement 	= $this->CI->Element_Model->get_rowByName($table,ELEMENT_TYPE_TABLE);
		if(!$objElement)
		throw new Exception("NO EXISTE LA TABLA '".$table."' DENTRO DE LOS REGISTROS DE ELEMENT ");
		
		//obtener subelement
		$objSubElement 	= $this->CI->Sub_Element_Model->get_rowByNameAndElementID($objElement->elementID,$field); 
		if(!$objSubElement)
		throw new Exception("NO EXISTE EL CAMPO '".$field."' DENTRO DE LOS REGISTROS DE SUBELEMENT PARA EL ELEMENTO '".$table."' ");
		
		//obtener componente catalogo
		$objComponent = $this->CI->Component_Model->get_rowByName("tb_catalog");
		if(!$objComponent)
		throw new Exception("NO EXISTE EL COMPONENTE 'tb_catalog' DENTROS DE LOS REGISTROS DE 'Component' ");
		
		
		//obtener el catalogo
		if(!$objSubElement->catalogID)
		throw new Exception("EN LA TABLA SUBELEMENT PARA '".$field."' NO EXISTE EL CATALOGO CONFIGURADO");
		
		$objCatalog = $this->CI->Catalog_Model->get_rowByCatalogID($objSubElement->catalogID);
		if(!$objCatalog)
		throw new Exception("NO EXISTE EL CATALOGO ");
				
		//obtener flavor
		$objCompanyComponentFlavor = $this->CI->Company_Component_Flavor_Model->get_rowByCompanyAndComponentAndComponentItemID($companyID,$objComponent->componentID,$objCatalog->catalogID);
		if(!$objCompanyComponentFlavor)
		throw new Exception("NO EXISTE EL FLAVOR PARA EL COMPONENTE DE CATALOGO ");
		
		//obtener la lista de catalogItem
		$objCatalogItem = $this->CI->Catalog_Item_Model->get_rowByCatalogItemID($catalogItemID);
		return $objCatalogItem;
		
   }
}
?>
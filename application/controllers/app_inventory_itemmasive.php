<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class App_Inventory_Itemmasive extends CI_Controller {
	
    public function __construct() {
       parent::__construct();
    }   
	
	
	function index($dataViewID = null){	
	try{ 
		
			$dataSession		= $this->session->all_userdata();
			
			
			//AUTENTICADO
			if(!$this->core_web_authentication->isAuthenticated())
			throw new Exception(USER_NOT_AUTENTICATED);
			
			log_message("ERROR",print_r($dataSession,true));
			//PERMISO SOBRE LA FUNCTION
			if(APP_NEED_AUTHENTICATION == true){				
				
						$permited = false;
						$permited = $this->core_web_permission->urlPermited($this->router->class,"index",$this->config->item('url_suffix'),$dataSession["menuTop"],$dataSession["menuLeft"],$dataSession["menuBodyReport"],$dataSession["menuBodyTop"],$dataSession["menuHiddenPopup"]);
						
						if(!$permited)
						throw new Exception(NOT_ACCESS_CONTROL);
						
						$resultPermission		= $this->core_web_permission->urlPermissionCmd($this->router->class,"index",$this->config->item('url_suffix'),$dataSession,$dataSession["menuTop"],$dataSession["menuLeft"],$dataSession["menuBodyReport"],$dataSession["menuBodyTop"],$dataSession["menuHiddenPopup"]);
						if ($resultPermission 	== PERMISSION_NONE)
						throw new Exception(NOT_ACCESS_FUNCTION);			
			
			}	
			
			
			//Obtener el componente Para mostrar la lista de AccountType
			$objComponent		= $this->core_web_tools->getComponentIDBy_ComponentName("tb_item_masive");
			if(!$objComponent)
			throw new Exception("EL COMPONENTE 'tb_item_masive' NO EXISTE...");
			
			
			//Vista por defecto 
			if($dataViewID == null){				
				$targetComponentID			= 0;	
				$parameter["{companyID}"]	= $this->session->userdata('user')->companyID;
				$dataViewData				= $this->core_web_view->getViewDefault($this->session->userdata('user'),$objComponent->componentID,CALLERID_LIST,$targetComponentID,$resultPermission,$parameter);			
				$dataViewRender				= $this->core_web_view->renderGreedWithHtmlInFild($dataViewData,'ListView',"fnTableSelectedRow");
			}
			//Otra vista
			else{									
				$parameter["{companyID}"]	= $this->session->userdata('user')->companyID;
				$dataViewData				= $this->core_web_view->getViewBy_DataViewID($this->session->userdata('user'),$objComponent->componentID,$dataViewID,CALLERID_LIST,$resultPermission,$parameter); 			
				$dataViewRender				= $this->core_web_view->renderGreedWithHtmlInFild($dataViewData,'ListView',"fnTableSelectedRow");
			} 
			 
			//Renderizar Resultado
			$dataSession["notification"]	= $this->core_web_error->get_error($dataSession["user"]->userID);
			$dataSession["message"]			= $this->core_web_notification->get_message();
			$dataSession["head"]			= $this->load->view('app_inventory_itemmasive/list_head','',true);
			$dataSession["footer"]			= $this->load->view('app_inventory_itemmasive/list_footer','',true);
			$dataSession["body"]			= $dataViewRender; 
			$dataSession["script"]			= $this->load->view('app_inventory_itemmasive/list_script','',true).$this->core_web_javascript->createVar("componentID",$objComponent->componentID);  
			
			$this->load->view("core_masterpage/default_masterpage",$dataSession);		
		}
		catch(Exception $ex){
			show_error($ex->getLine()." ".$ex->getMessage() ,500 );
		}
	}	
	
	function popup_add_prinercode($listItem){
		//Papel para codigo de barra, de medida
		//2 pulgada x 1pulgada
		
		
		try{ 
		
			log_message("ERROR","preuba de impresora");
			$this->load->library('core_web_barcode/barcode.php');
			
			//AUTENTICADO
			if(!$this->core_web_authentication->isAuthenticated())
			throw new Exception(USER_NOT_AUTENTICATED);
			$dataSession		= $this->session->all_userdata();
			
			//PERMISO SOBRE LA FUNCION
			if(APP_NEED_AUTHENTICATION == true){
						$permited = false;
						$permited = $this->core_web_permission->urlPermited($this->router->class,"index",$this->config->item('url_suffix'),$dataSession["menuTop"],$dataSession["menuLeft"],$dataSession["menuBodyReport"],$dataSession["menuBodyTop"],$dataSession["menuHiddenPopup"]);
						
						if(!$permited)
						throw new Exception(NOT_ACCESS_CONTROL);
						
							
						$resultPermission		= $this->core_web_permission->urlPermissionCmd($this->router->class,"edit",$this->config->item('url_suffix'),$dataSession,$dataSession["menuTop"],$dataSession["menuLeft"],$dataSession["menuBodyReport"],$dataSession["menuBodyTop"],$dataSession["menuHiddenPopup"]);
						if ($resultPermission 	== PERMISSION_NONE)
						throw new Exception(NOT_ALL_EDIT);		

			}	 
			
			$uri						= $this->uri->uri_to_assoc(3);						
			$listItem					= $uri["listItem"];		
			$listItem 					= urldecode($listItem);			
			$companyID 					= $dataSession["user"]->companyID;		
			$branchID 					= $dataSession["user"]->branchID;		
			$roleID 					= $dataSession["role"]->roleID;		
			
			//Cargar Libreria			
			$this->load->model("core/Company_Model"); 						
			$this->load->model("Item_Model"); 		
			log_message("ERROR",print_r($listItem,true));

			$listItem	= explode("|",$listItem);
			log_message("ERROR",print_r($listItem,true));
			
			
			//Get Component
			$objComponent		= $this->core_web_tools->getComponentIDBy_ComponentName("tb_company");						
			$objCompany 		= $this->Company_Model->get_rowByPK($companyID);						
			
			//Componetne de Item
			$objComponentItem			= $this->core_web_tools->getComponentIDBy_ComponentName("tb_item");
			if(!$objComponentItem)
			throw new Exception("EL COMPONENTE 'tb_item' NO EXISTE...");
			
			log_message("ERROR",print_r("foreach productos",true));	
			
			//Actualizar lso codigos de barra
			$objListaItemPrinter = array();			
			foreach($listItem as $itemWitCantidad)
			{

				$itemWitCantidadTmp	= explode("-",$itemWitCantidad);
				$itemID 	= $itemWitCantidadTmp[0];
				$cantidad 	= $itemWitCantidadTmp[1];			

				//Obtener Lista de Productos		
				log_message("ERROR",print_r("Lista de productos",true));	
				log_message("ERROR",print_r($itemID,true));	

				$objBarCode 		= new barcode();
				$objItem 			= $this->Item_Model->get_rowByPK($companyID,$itemID);
				log_message("ERROR",print_r($objItem,true));	

				if($objItem == null)
				{}		
				else{	
					$objItem->barCode	= $objItem->barCode == "" ? "B".$objItem->itemNumber  : $objItem->barCode;

					$objNewItem["barCode"] = $objItem->barCode;
					$row_affected 	= $this->Item_Model->update($companyID,$objItem->itemID,$objNewItem);
					
					$directory=  PATH_FILE_OF_APP."/company_".$companyID."/component_".$objComponentItem->componentID."/component_item_".$objItem->itemID;
					$pathFileCodeBarra = PATH_FILE_OF_APP."/company_".$companyID."/component_".$objComponentItem->componentID."/component_item_".$objItem->itemID."/barcode.jpg";
					
					if(!file_exists($directory))
					mkdir($directory, 0700);
					
					$objBarCode->generate( $pathFileCodeBarra, $objItem->barCode, "40", "horizontal", "code128", false, 3 );

					for($i = 0; $i < $cantidad ; $i++){
						$objItemTempory = $this->Item_Model->get_rowByPK($companyID,$itemID);
						array_push($objListaItemPrinter,$objItemTempory);
					}

				}
			}
					
					
			log_message("ERROR",print_r($objListaItemPrinter,true));	
			$data["objComponentItem"] = $objComponentItem;
			$data["objComponent"] = $objComponent;
			$data["objListaItem"] = $objListaItemPrinter;
			$this->load->view("app_inventory_itemmasive/printer_barcode",$data);	
			
			
			
		}
		catch(Exception $ex){
			show_error($ex->getLine()." ".$ex->getMessage() ,500 );
		}
	}
	
}
?>
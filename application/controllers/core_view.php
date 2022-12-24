<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class core_view extends CI_Controller {

	//Constructor ...
    public function __construct() {
       parent::__construct();
    }
    //BUSCAR UNA VISTA POR NOMBRE
	function showviewbyname($componentid,$fnCallback,$viewname,$autoclose,$filter){
		try{  
		
			

  

			//Validar Authentication
			if(!$this->core_web_authentication->isAuthenticated())
			throw new Exception(USER_NOT_AUTENTICATED);
			$dataSession		= $this->session->all_userdata(); 
		
			
			$parameter["{companyID}"]	= $this->session->userdata('user')->companyID;
			$viewname 					= urldecode($viewname);
			$filter 					= urldecode($filter);
			$result 					= $this->core_web_tools->formatParameter($filter);	

			log_message('ERROR',$componentid);
			log_message('ERROR',$viewname);
			log_message('ERROR',$filter);
			
			//$this->output->cache(100);
			//$this->load->driver('cache',array('adapter'=>'apc','backup'=> 'file'));
			//$nameCache = "SELECCIONAR_ITEM_BILLING";
			//$objCache  = $this->cache->get($nameCache);
			//log_message('ERROR',print_r($objCache,true));
			
			
			//Guardar la info en cache
			//if($objCache && $nameCache ==  $viewname){
			//	echo $objCache;
			//	exit;
			//}



			if($result)
			$parameter 					= array_merge($parameter,$result);

			log_message('ERROR',"extraer informacion de la base de datos");
			$dataViewData				= $this->core_web_view->getViewByName($this->session->userdata('user'),$componentid,$viewname,CALLERID_SEARCH,null,$parameter); 			

			log_message('ERROR',"crear html");
			$dataViewRender				= $this->core_web_view->renderGreed($dataViewData,'ListView',"fnTableSelectedRow");

			log_message('ERROR',"presentar informacion");
			$dataView["fnCallback"] 	= $fnCallback;
			$dataView["viewname"] 		= $viewname;
			$dataView["autoclose"] 		= $autoclose;
			
			
			//log_message('ERROR',print_r($dataView,true));
			//log_message('ERROR',print_r($dataViewRender,true));

			//Guardar la info en cache
			//if($nameCache ==  $viewname){
			//	$this->cache->save($nameCache,$dataSession,100);
			//}

			//Renderizar Resultado
			$dataSession["message"]	= ""; 
			$dataSession["head"]	= $this->load->view('core_view/choose_view_serch_head','',true); 
			$dataSession["body"]	= $dataViewRender;
			$dataSession["script"]	= $this->load->view('core_view/choose_view_serch_script',$dataView,true);   
			$this->load->view("core_masterpage/default_widgetchoose",$dataSession);  		
			log_message('ERROR',"fin");


			//Guardar la info en cache
			//if(!$objCache && $nameCache ==  $viewname){
			//	$this->cache->save($nameCache,$dataSession,100);
			//}

				
		}
		catch(Exception $ex){
			show_error($ex->getMessage() ,500 );
		}
	}
	
	//INDEX
	////////////////////////////
	function chooseview($componentIDParameter){ 
		try{  
		
			//Validar Authentication
			if(!$this->core_web_authentication->isAuthenticated())
			throw new Exception(USER_NOT_AUTENTICATED);
			$dataSession		= $this->session->all_userdata(); 
		
		
			//Obtener grid 
			//Obtener el Componente de CompanyComponentItemDataView
			$parameter["{componentID}"]	= $componentIDParameter;
			$parameter["{companyID}"]	= $this->session->userdata('user')->companyID;
			$parameter["{callerID}"]	= CALLERID_LIST;
			$componentSearch			= $this->core_web_tools->getComponentIDBy_ComponentName("tb_company_component_item_dataview"); 				 
			$dataViewData				= $this->core_web_view->getView($this->session->userdata('user'),$componentSearch->componentID,CALLERID_LIST,null,$parameter); 			
			$dataViewRender				= $this->core_web_view->renderGreed($dataViewData,'ListView',"fnTableSelectedRow");
			  
			  
			//Renderizar Resultado
			$dataSession["message"]	= ""; 
			$dataSession["head"]	= $this->load->view('core_view/choose_view_head','',true); 
			$dataSession["body"]	= $dataViewRender;
			$dataSession["script"]	= $this->load->view('core_view/choose_view_script','',true);   
			$this->load->view("core_masterpage/default_widgetchoose",$dataSession);  		
				
		}
		catch(Exception $ex){
			show_error($ex->getMessage() ,500 );
		}
	}
}
?>
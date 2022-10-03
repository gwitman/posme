<?php if ( ! defined('BASEPATH')) exit('No se permite el acceso directo al script');

class core_web_view {
   
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
   function getViewByName($user,$componentID,$name,$callerID,$permission=null,$parameter){
		$this->CI->load->model('core/Data_View_Model');
		$this->CI->load->model('core/Company_Data_View_Model');
		$this->CI->load->model('core/Bd_Model');  
		
		//Obtener la vista generica
		$companyDataView			= $this->CI->Data_View_Model->getViewByName($componentID,$name,$callerID);
		if(!$companyDataView)
		return null;
		
		//Obtener la vista de la company		
		$dataviewID 				= $companyDataView->dataViewID;
		$companyDataView			= $this->CI->Company_Data_View_Model->get_rowBy_companyIDDataViewID($user->companyID,$dataviewID,$callerID,$componentID);
		if(!$companyDataView)
		return null;
		
		//EXECUTE 
		$queryFill					= str_replace(array_keys($parameter), array_values ($parameter), $companyDataView->sqlScript);
		
		//Aplicar Filtros y Asignar Variables
		if($permission			== PERMISSION_ALL)	
			$filterPermission	= "";
		else if($permission		== PERMISSION_NONE){
			$filterPermission	= " AND 1 != 1 ";
		}
		else if ($permission	== PERMISSION_BRANCH){
			$filterPermission	= " AND x.createdAt = ".$user->branchID;
		}
		else if ($permission	== PERMISSION_ME){
			$filterPermission	= " AND x.createdBy = ".$user->userID; 
		}
		else{
			$filterPermission	= "";
		}		
		
		$queryFill					= str_replace("{filterPermission}", $filterPermission, $queryFill);
		$dataRecordSet				= $this->CI->Bd_Model->executeRender($queryFill);
		$dataResult["view_config"]	= $companyDataView;
		$dataResult["view_data"]	= $dataRecordSet;
		return $dataResult;
   }
   function getViewBy_DataViewID($user,$componentID,$dataviewID,$callerID,$permission=null,$parameter){
		$this->CI->load->model('core/Data_View_Model');
		$this->CI->load->model('core/Company_Data_View_Model');
		$this->CI->load->model('core/Bd_Model');  
		
		//Obtener la vista por company
		$companyDataView			= $this->CI->Company_Data_View_Model->get_rowBy_companyIDDataViewID($user->companyID,$dataviewID,$callerID,$componentID);
		if(!$companyDataView)
		return null;
		
		//EXECUTE 
		$queryFill					= str_replace(array_keys($parameter), array_values ($parameter), $companyDataView->sqlScript);
		
		//Aplicar Filtros y Asignar Variables
		if($permission			== PERMISSION_ALL)	
			$filterPermission	= "";
		else if($permission		== PERMISSION_NONE){
			$filterPermission	= " AND 1 != 1 ";
		}
		else if ($permission	== PERMISSION_BRANCH){
			$filterPermission	= " AND x.createdAt = ".$user->branchID;
		}
		else if ($permission	== PERMISSION_ME){
			$filterPermission	= " AND x.createdBy = ".$user->userID; 
		}
		else{
			$filterPermission	= "";
		}		
		
		$queryFill					= str_replace("{filterPermission}", $filterPermission, $queryFill);
		$dataRecordSet				= $this->CI->Bd_Model->executeRender($queryFill);
		$dataResult["view_config"]	= $companyDataView;
		$dataResult["view_data"]	= $dataRecordSet;
		return $dataResult;
   }
   function getView($user,$componentID = null,$callerID = null,$permission=null,$parameter){
		$this->CI->load->model('core/Data_View_Model');
		$this->CI->load->model('core/Company_Data_View_Model');
		$this->CI->load->model('core/Bd_Model');  
		
		//Obtener la vista		
		$objListView				= $this->CI->Data_View_Model->getListBy_CompanyComponentCaller($componentID,$callerID);				
		if(!$objListView)
		return null; 
		
		//Obtener la vista por company
		$companyDataView			= $this->CI->Company_Data_View_Model->get_rowBy_companyIDDataViewID($user->companyID,$objListView->dataViewID,$callerID,$componentID);
		if(!$companyDataView)
		return null;
		
		//EXECUTE 
		$queryFill					= str_replace(array_keys($parameter), array_values ($parameter), $companyDataView->sqlScript);
		
		//Aplicar Filtros y Asignar Variables
		if($permission			== PERMISSION_ALL)	
			$filterPermission	= "";
		else if($permission		== PERMISSION_NONE){
			$filterPermission	= " AND 1 != 1 ";
		}
		else if ($permission	== PERMISSION_BRANCH){
			$filterPermission	= " AND x.createdAt = ".$user->branchID;
		}
		else if ($permission	== PERMISSION_ME){
			$filterPermission	= " AND x.createdBy = ".$user->userID; 
		}
		else{
			$filterPermission	= "";
		}		
		
		$queryFill					= str_replace("{filterPermission}", $filterPermission, $queryFill);
		$dataRecordSet				= $this->CI->Bd_Model->executeRender($queryFill);
		$dataResult["view_config"]	= $companyDataView;
		$dataResult["view_data"]	= $dataRecordSet;
		return $dataResult;		 
   }   
   function getViewDefault($user,$componentID = null,$callerID = null,$targetComponentID = null,$permission = null,$parameter){
		$this->CI->load->model('core/Company_Default_Data_View_Model');
		$this->CI->load->model('core/Company_Data_View_Model');
		$this->CI->load->model('core/Bd_Model');		
		
		
		
		//Obtener la vista por defecto
		$objCompanyDefaultDataView	= $this->CI->Company_Default_Data_View_Model->get_rowBy_CCCT($user->companyID,$componentID,$callerID,$targetComponentID);
		if(!$objCompanyDefaultDataView)
		return null;
	
		
		//Obtener la vista por company
		$companyDataView			= $this->CI->Company_Data_View_Model->get_rowBy_companyIDDataViewID($user->companyID,$objCompanyDefaultDataView->dataViewID,$callerID,$componentID);
		if(!$companyDataView)
		return null;
		
		
		//EXECUTE 
		$queryFill					= str_replace(array_keys($parameter), array_values ($parameter), $companyDataView->sqlScript);
		
		 
		//Aplicar Filtros y Asignar Variables
		if($permission			== PERMISSION_ALL)	
			$filterPermission	= "";
		else if($permission		== PERMISSION_NONE){
			$filterPermission	= " AND 1 != 1 ";
		}
		else if ($permission	== PERMISSION_BRANCH){
			$filterPermission	= " AND x.createdAt = ".$user->branchID;
		}
		else if ($permission	== PERMISSION_ME){
			$filterPermission	= " AND x.createdBy = ".$user->userID;
		}
		else{
			$filterPermission	= "";
		}		
		
		//Ejecutar Vista.		
		$queryFill	= str_replace("{filterPermission}", $filterPermission, $queryFill);
		$dataRecordSet				= $this->CI->Bd_Model->executeRender($queryFill);
		$dataResult["view_config"]	= $companyDataView;
		$dataResult["view_data"]	= $dataRecordSet;
		return $dataResult;
		
		
   }
   function renderGreed($data,$idTable = null,$functionSelected = NULL,$displayLength = 350){
		$this->CI->load->library('table');
		$this->CI->load->library('javascript'); 
		 
		//Cambiar el Look
		$tmpl = array (
                    'table_open'          => '<table  id="'.$idTable.'" cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered table-hover" style="display:none" >',

                    'heading_row_start'   => '<tr>',
                    'heading_row_end'     => '</tr>',
                    'heading_cell_start'  => '<th>',
                    'heading_cell_end'    => '</th>',

                    'row_start'           => '<tr class="gradeX">',
                    'row_end'             => '</tr>',
                    'cell_start'          => '<td>',
                    'cell_end'            => '</td>',

                    'row_alt_start'       => '<tr>',
                    'row_alt_end'         => '</tr>',
                    'cell_alt_start'      => '<td>',
                    'cell_alt_end'        => '</td>',

                    'table_close'         => '</table>'
        );

		//Configurar
		$this->CI->table->set_template($tmpl);
		$this->CI->table->function = 'htmlspecialchars';

		
		//Agregar Cabecera		
		if(!$data){
			$cabezera1	= array( 0 => "fieldid");
			$cabezera2	= array( 0 => "descripcion");
			$cabezera3	= array_merge($cabezera1 , $cabezera2);				
			$this->CI->table->set_heading($cabezera3);
			
			//Agregar Registro
			$this->CI->table->add_row(array("0","vacio...")); 
		}
		else{
			$cabezera1	= explode(",",$data["view_config"]->nonVisibleColumns);
			$cabezera2	= explode(",",$data["view_config"]->visibleColumns);
			$cabezera3	= array_merge($cabezera1 , $cabezera2);				
			$this->CI->table->set_heading($cabezera3);
			
			//Agregar Registro
			foreach($data["view_data"] AS $row_){			
				$dara_array 	= array_values((array)$row_);
				$this->CI->table->add_row($dara_array); 
			}
		}
		
		//Render JS
		$js					= "
		<script>
			var objTable".$idTable.";
			var objRowTable".$idTable.";	
					
			$(document).ready(function() {
						$('#".$idTable."').dataTable({
							'Dom'				: \"<'row'<'col-lg-6'l><'col-lg-6'f>r>t<'row'<'col-lg-6'i><'col-lg-6'p>>\",
							'sPaginationType'	: 'bootstrap',
							'bJQueryUI'			: false,
							'bAutoWidth'		: false,							
							'iDisplayLength'	: ".$displayLength.",							
							'oLanguage'	: {
								'sSearch'		: '<span>Filtro:</span> _INPUT_',
								'sLengthMenu'	: '<span>_MENU_ elementos</span>',
								'oPaginate'		: { 'sFirst': 'First', 'sLast': 'Last' }
							}
						});
						
						objTable".$idTable." = $('#".$idTable."').dataTable( ); 
		
						$('.dataTables_length select').uniform();
						$('.dataTables_paginate > ul').addClass('pagination');						
						";
						
						foreach($cabezera1 AS $field ){
							$i		= array_search($field, $cabezera3);
							$temp	= "objTable".$idTable.".fnSetColumnVis(".$i.",false); ";
							$js		= $js.$temp; 
							
						}
						
						$js	= $js."	 					
						$(document).on('click','#".$idTable." tr',function(event){ objRowTable".$idTable." = this; ".$functionSelected."(this,event);});  
						$('#".$idTable."').css('display','table');
			});							
		</script>";
		
		//Resultado
		$resultGreed		=	$this->CI->table->generate();
		$resultGreedMoreJS	=   $resultGreed.$js;
		return $resultGreedMoreJS;
		
   }
   
}
?>
<!-- ./ page heading -->
<script>		
	var objCallback				= '<?php echo $callback; ?>';
	var objRowWarehouse 		= {};
	var objTableDetailProvider 	= {};
	var objTableDetailConcept 	= {};
	
	//este evento es util cuando la pantalla se ejecuta desde la pantalla de facturacion
	if(objCallback != 'false'){
		$(window).unload(function() {
			//do something
			window.opener.<?php echo $callback; ?>(); 
		});
	}
	
	$(document).ready(function(){
		objTableDetailProvider = $("#table_provider").dataTable({
			"bPaginate"		: false,
			"bFilter"		: false,
			"bSort"			: false,
			"bInfo"			: false,
			"bAutoWidth"	: false,
			"aaData": [		
				<?php 
					$listprovider = [];
					if($objListProvider){
						foreach($objListProvider as $i)
						{
						$listprovider[] = "[0,".$i->entityID.",'".$i->providerNumber."','".$i->firstName." ".$i->comercialName."']";
						}
						echo implode(",",$listprovider);
					}
				?>
			],
			"aoColumnDefs": [ 
						{
							"aTargets"	: [ 0 ],//checked
							"mRender"	: function ( data, type, full ) {
								if (data == false)
								return '<input type="checkbox"  class="classCheckedDetail"  value="0" ></span>';
								else
								return '<input type="checkbox"  class="classCheckedDetail" checked="checked" value="0" ></span>';
							}
						},
						{
							"aTargets"		: [ 1 ],//entityID
							"bVisible"		: true,
							"sClass" 		: "hidden",
							"bSearchable"	: false,
							"mRender"		: function ( data, type, full ) {
								return '<input type="hidden" value="'+data+'" name="txtProviderEntityID[]" />';
							}
						}
			]							
		});
		
		
		objTableDetailConcept = $("#table_concept").dataTable({
			"bPaginate"		: false,
			"bFilter"		: false,
			"bSort"			: false,
			"bInfo"			: false,
			"bAutoWidth"	: false,
			"aaData": [		
				<?php 
					$listconcept = [];
					if($objListConcept){
						foreach($objListConcept as $i)
						{
							$listconcept[] = "[0,'".$i->name."',".$i->valueIn.",".$i->valueOut."]";
						}
						echo implode(",",$listconcept);
					}
				?>
			],
			"aoColumnDefs": [ 
						{
							"aTargets"	: [ 0 ],//checked
							"mRender"	: function ( data, type, full ) {
								if (data == false)
								return '<input type="checkbox"  class="classCheckedDetailConcept"  value="0" ></span>';
								else
								return '<input type="checkbox"  class="classCheckedDetailConcept" checked="checked" value="0" ></span>';
							}
						},
						{
							"aTargets"		: [ 1 ],//name
							"mRender"		: function ( data, type, full ) {
								return '<input type="text" class="col-lg-12" value="'+data+'" name="txtDetailConceptName[]" />';
							}
						},
						{
							"aTargets"		: [ 2 ],//valueIn
							"mRender"		: function ( data, type, full ) {
								return '<input type="text" class="col-lg-12" value="'+data+'" name="txtDetailConceptValueIn[]" />';
							}
						},
						{
							"aTargets"		: [ 3 ],//valueOut
							"mRender"		: function ( data, type, full ) {
								return '<input type="text" class="col-lg-12" value="'+data+'" name="txtDetailConceptValueOut[]" />';
							}
						}
			]							
		});
		refreschChecked();
		
		
		//Evento Regresar a la lista
		$(document).on("click","#btnBack",function(){
			fnWaitOpen();
		});
		
		//Guardar
		$(document).on("click","#btnAcept",function(){
				$( "#form-new-account-journal" ).attr("method","POST");
				$( "#form-new-account-journal" ).attr("action","<?php echo site_url(); ?>app_inventory_item/save/edit");
				
				if(validateForm()){
					fnWaitOpen();
					$( "#form-new-account-journal" ).submit();
				}
		});
		$(document).on("click","#btnDelete",function(){							
			fnShowConfirm("Confirmar..","Desea eliminar este Registro...",function(){
				fnWaitOpen();
				$.ajax({									
					cache       : false,
					dataType    : 'json',
					type        : 'POST',
					url  		: "<?php echo site_url(); ?>app_inventory_item/delete",
					data 		: {companyID : <?php echo $objItem->companyID;?>, itemID : <?php echo $objItem->itemID;?>  },
					success:function(data){
						console.info("complete delete success");
						fnWaitClose();
						if(data.error){
							fnShowNotification(data.message,"error");
						}
						else{
							window.location = "<?php echo site_url(); ?>app_inventory_item/index";
						}
					},
					error:function(xhr,data){	
						console.info("complete delete error");									
						fnWaitClose();
						fnShowNotification("Error 505","error");
					}
				});
			});
		});
		//Ir a Archivo
		$(document).on("click","#btnClickArchivo",function(){
			window.open("<?php echo site_url()."core_elfinder/index/componentID/".$objComponent->componentID."/componentItemID/".$objItem->itemID; ?>","blanck");
		});
		
		//Agregar Concepto
		$(document).on("click","#btnNewDetailConcept",function(){	
			var url_request = "<?php echo site_url(); ?>app_inventory_item/popup_add_concept"; 
			window.open(url_request,"MsgWindow","width=900,height=450");
			window.onCompleteConcept = onCompleteConcept; 
		});
		
		//Seleccionar Concepto 
		$(document).on("click",".classCheckedDetailConcept",function(){
			var objrow_ = $(this).parent().parent().parent().parent()[0];
			var objind_ = objTableDetailConcept.fnGetPosition(objrow_);
			var objdat_ = objTableDetailConcept.fnGetData(objind_);								
			objTableDetailConcept.fnUpdate( !objdat_[0], objind_, 0 );
			refreschChecked();
		});
		
		//Eliminar Concepto
		$(document).on("click","#btnDeleteDetailConcept",function(){
			var listRow = objTableDetailConcept.fnGetData();							
			var length 	= listRow.length;
			var i 		= 0;
			var j 		= 0;
			while (i< length ){
				if(listRow[i][0] == true){
				objTableDetailConcept.fnDeleteRow( j,null,true );
				j--;
				}
				i++;
				j++;
			}
		});
		
		//Agregar Proveedor
		$(document).on("click","#btnNewDetailProvider",function(){	
			var url_request = "<?php echo site_url(); ?>core_view/showviewbyname/<?php echo $componentProviderID; ?>/onCompleteProvider/SELECCIONAR_PROVEEDOR/true/empty"; 
			window.open(url_request,"MsgWindow","width=900,height=450");
			window.onCompleteProvider = onCompleteProvider; 
		});
		//Seleccionar Proveedor 
		$(document).on("click",".classCheckedDetail",function(){
			var objrow_ = $(this).parent().parent().parent().parent()[0];
			var objind_ = objTableDetailProvider.fnGetPosition(objrow_);
			var objdat_ = objTableDetailProvider.fnGetData(objind_);								
			objTableDetailProvider.fnUpdate( !objdat_[0], objind_, 0 );
			refreschChecked();
		});
		//Eliminar Proveedor
		$(document).on("click","#btnDeleteDetailProvider",function(){
			var listRow = objTableDetailProvider.fnGetData();							
			var length 	= listRow.length;
			var i 		= 0;
			var j 		= 0;
			while (i< length ){
				if(listRow[i][0] == true){
				objTableDetailProvider.fnDeleteRow( j,null,true );
				j--;
				}
				i++;
				j++;
			}
		});
		
		//Nueva Bodega
		$(document).on("click","#btnNewDetailWarehouse",function(){
				var objData 					= {};
				objData.warehouseID 		 	= $("#txtTempWarehouseID").val();								
				objData.warehouseDescription 	= $("#txtTempWarehouseID option:selected").text();
				objData.quantityMax 			= $("#txtTmpDetailQuantityMax").val();
				objData.quantityMin 			= $("#txtTmpDetailQuantityMin").val();
				var objHtml						= $.tmpl($("#tmpl_row_warehouse").html(),objData);
				
				if($("input[value="+objData.warehouseID+"].txtDetailWarehouseID").length > 0 )
				return;
				
				$("#body_detail_warehouse").append(objHtml);
		});
		//Eliminar Bodega
		$(document).on("click","#btnDeleteDetailWarehouse",function(){
				var quantity = $(objRowWarehouse).find(".txtDetailQuantity").val();
				if(quantity == undefined)
				return;
				
				quantity  = parseFloat(quantity);
				if(quantity > 0)
				return;
				
				objRowWarehouse.remove();
		});
		//Seleccionar Bodega
		$(document).on("click",".row_warehouse",function(event){		
				objRowWarehouse = this;
				fnTableSelectedRow(this,event);
		});
		
	});
	function validateForm(){
		var result 				= true;
		var timerNotification 	= 15000;
		
		//Nombre
		if($("#txtName").val()==""){
			fnShowNotification("El nombre no puede estar vacio","error",timerNotification);
			result = false;
		}
		//Validar Estado
		if($("#txtStatusID").val() == ""){
			fnShowNotification("Establecer Estado","error",timerNotification);
			result = false;
		}
		//Categoria
		if($("#txtInventoryCategoryID").val() == ""){
			fnShowNotification("Seleccione una categoria","error",timerNotification);
			result = false;
		}
		//Bodega por Defecto
		if($("#txtDefaultWarehouseID").val() == ""){
			fnShowNotification("Seleccione una bodega por defecto","error",timerNotification);
			result = false;
		}
		//Unidad de Medida
		if($("#txtUnitMeasureID").val() == ""){
			fnShowNotification("Seleccione la unidad de medida","error",timerNotification);
			result = false;
		}
		//La bodega por defecto debe de estar en las bodegas asociadas
		if($("input[value="+$("#txtDefaultWarehouseID").val()+"].txtDetailWarehouseID").length == 0 ){
			fnShowNotification("La bodega que esta pordefecto debe de estar en el detalle de Bodegas","error",timerNotification);
			result = false;
		}
		
		return result;
	}
	function onCompleteConcept(objResponse){
			console.info("CALL onCompleteConcept");
			var objRow 					= {};
			objRow.checked 				= false;
			objRow.name 				= objResponse.txtNameConcept;
			objRow.valueIn				= objResponse.txtValueIn;
			objRow.valueOut				= objResponse.txtValueOut;
			
			//Berificar que el Item ya esta agregado 
			if(jLinq.from(objTableDetailConcept.fnGetData()).where(function(obj){ return obj[1] == objRow.name;}).select().length > 0 ){
				fnShowNotification("El Concepto ya esta agregado","error");
				return;
			}
			
			objTableDetailConcept.fnAddData([objRow.checked,objRow.name,objRow.valueIn,objRow.valueOut]);
			refreschChecked();
			
	}
	
	function onCompleteProvider(objResponse){
			console.info("CALL onCompleteProvider");
			var objRow 						= {};
			objRow.checked 					= false;
			objRow.providerID 				= objResponse[1];
			objRow.providerNumber			= objResponse[2];
			objRow.providerName				= objResponse[3];
			
			//Berificar que el Item ya esta agregado 
			if(jLinq.from(objTableDetailProvider.fnGetData()).where(function(obj){ return obj[1] == objRow.providerID;}).select().length > 0 ){
				fnShowNotification("El Proveedor ya esta agregado","error");
				return;
			}
			
			objTableDetailProvider.fnAddData([objRow.checked,objRow.providerID,objRow.providerNumber,objRow.providerName]);
			refreschChecked();
			
	}
	function refreschChecked(){
		$("[type='checkbox'], [type='radio'], [type='file'], select").not('.toggle, .select2, .multiselect').uniform();
	}
	
</script>
<script>  (function(g,u,i,d,e,s){g[e]=g[e]||[];var f=u.getElementsByTagName(i)[0];var k=u.createElement(i);k.async=true;k.src='https://static.userguiding.com/media/user-guiding-'+s+'-embedded.js';f.parentNode.insertBefore(k,f);if(g[d])return;var ug=g[d]={q:[]};ug.c=function(n){return function(){ug.q.push([n,arguments])};};var m=['previewGuide','finishPreview','track','identify','triggerNps','hideChecklist','launchChecklist'];for(var j=0;j<m.length;j+=1){ug[m[j]]=ug.c(m[j]);}})(window,document,'script','userGuiding','userGuidingLayer','744100086ID'); </script>
<script>
	//window.userGuiding.identify(userId*, attributes)
	  
	// example with attributes
	window.userGuiding.identify('<?php echo get_cookie("email"); ?>', {
	  email: '<?php echo get_cookie("email"); ?>',
	  name: '<?php echo get_cookie("email"); ?>',
	  created_at: 1644403436643,
	});
	// or just send userId without attributes
	//window.userGuiding.identify('1Ax69i57j0j69i60l4')
</script>
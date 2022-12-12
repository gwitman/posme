<!-- ./ page heading -->
<script>	
	var objCallback				= '<?php echo $callback; ?>';
	var objRowWarehouse 		= {};
	//este evento es util cuando la pantalla se ejecuta desde la pantalla de facturacion
	if(objCallback != 'false'){
		$(window).unload(function() {
			//do something
			window.opener.<?php echo $callback; ?>(); 
		});
	}

	$(document).ready(function(){	
				
		 //Regresar a la lista
		$(document).on("click","#btnBack",function(){
				fnWaitOpen();
		});
		//Evento Agregar el Usuario
		$(document).on("click","#btnAcept",function(){
				$( "#form-new-account-journal" ).attr("method","POST");
				$( "#form-new-account-journal" ).attr("action","<?php echo site_url(); ?>app_inventory_item/save/new");
				
				if(validateForm()){
					fnWaitOpen();
					$( "#form-new-account-journal" ).submit();
				}
				
		});						
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
		$(document).on("click","#btnDeleteDetailWarehouse",function(){
				var quantity = $(objRowWarehouse).find(".txtDetailQuantity").val();
				if(quantity == undefined)
				return;
				
				quantity  = parseFloat(quantity);
				if(quantity > 0)
				return;
				
				objRowWarehouse.remove();
		});
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
</script>
<!--posMeHelp-->
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

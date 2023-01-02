<!-- ./ page heading -->
<script>	
	var numberDecimal				= 2;
	var numberDecimalSummary		= 2;
	var numberDecimalSummaryRound	= false;
	var objListaProductos			= {};
	
	var objTableDetailTransaction 	= {};
	$(document).ready(function(){					
		//Inicializar Controles		
		$('#txtTransactionOn').datepicker({format:"yyyy-mm-dd"});
		$('#txtTransactionOn').val(moment().format("YYYY-MM-DD"));	
		$("#txtTransactionOn").datepicker("update");
		 
		objTableDetailTransaction = $("#tb_transaction_master_detail").dataTable({
			"bPaginate"		: false,
			"bFilter"		: false,
			"bSort"			: false,
			"bInfo"			: false,
			"bAutoWidth"	: false,
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
							"aTargets"		: [ 1 ],//itemID
							"bVisible"		: true,
							"sClass" 		: "hidden",
							"bSearchable"	: false,
							"mRender"		: function ( data, type, full ) {
								return '<input type="hidden" value="'+data+'" name="txtDetailItemID[]" />';
							}
						},
						{
							"aTargets"		: [2],//transactionMasterDetailID
							"bVisible"  	: true,
							"sClass" 		: "hidden",
							"bSearchable"	: false,
							"mRender"		: function ( data, type, full ) {
								return '<input type="hidden" value="'+data+'" name="txtDetailTransactionDetailID[]" />';
							}
						},
						{
							"aTargets"	: [4], //nombre
							"sWidth"	: "40%"
						},
						{
							"aTargets"	: [ 6 ],//cantidad
							"mRender"	: function ( data, type, full ) {
								return '<input type="text" class="col-lg-12 txtDetailQuantity txt-numeric" value="'+data+'" name="txtDetailQuantity[]" />';
							}
						},
						{
							"aTargets"	: [ 7 ],//costo
							"mRender"	: function ( data, type, full ) {
								return '<input type="text" class="col-lg-12 txtDetailCost txt-numeric" value="'+data+'" name="txtDetailCost[]" />';
							}
						},
						{
							"aTargets"	: [ 8 ],//precio
							"mRender"	: function ( data, type, full ) {
								return '<input type="text" class="col-lg-12 txtDetailPrice txt-numeric" value="'+data+'" name="txtDetailPrice[]" />';
							}
						},
						{
							"aTargets"		: [ 9 ],//lote
							"bVisible"		: true,
							"sClass" 		: "hidden",
							"bSearchable"	: false,
							"mRender"	: function ( data, type, full ) {
								return '<input type="text" class="col-lg-12 txtDetailLote txt-numeric"" value="'+data+'" name="txtDetailLote[]" readonly="true" />';
							}
						},
						{
							"aTargets"		: [ 10 ],//fecha expiracion
							"bVisible"		: true,
							"sClass" 		: "hidden",
							"bSearchable"	: false,
							"mRender"	: function ( data, type, full ) {
								return '<input type="text" class="col-lg-12 txtDetailVencimiento txt-numeric"" value="'+data+'" name="txtDetailVencimiento[]" readonly="true" />';
							}
						},
						{
							"aTargets"	: [ 11 ],//mas informacion
							"mRender"	: function ( data, type, full ) {								
								if (data == false){									
									var str = '<a class="btn btn-primary btnMasInformcion" data-itemid="'+full[1]+'" data-transactionmasterdetailid="'+full[2]+'"  href="#" >Mas informacion</a>';
									return str;
								}
								else{									
									var str = '<a class="btn btn-primary btnMasInformcion" data-itemid="'+full[1]+'" data-transactionmasterdetailid="'+full[2]+'"  href="#" >Mas informacion</a>';
									return str;
								}
								
							}
						}
			]							
		});
		
		refreschChecked();
		//Buscar el Proveedor
		$(document).on("click","#btnSearchProvider",function(){
			var url_request = "<?php echo site_url(); ?>core_view/showviewbyname/<?php echo $objComponentProvider->componentID; ?>/onCompleteProvider/SELECCIONAR_PROVEEDOR/true/empty";
			window.open(url_request,"MsgWindow","width=900,height=450");
			window.onCompleteProvider = onCompleteProvider; 
		});		
		//Eliminar Proveedor
		$(document).on("click","#btnClearProvider",function(){
					$("#txtProviderID").val("");
					$("#txtProviderDescription").val("");
		});
		//Buscar el Orden de Compra
		$(document).on("click","#btnSearchOrdenCompra",function(){
			var url_request = "<?php echo site_url(); ?>core_view/showviewbyname/<?php echo $objComponentOrdenCompra->componentID; ?>/onCompleteOrdenCompra/SELECCIONAR_ORDEN_COMPRA/true/empty";
			window.open(url_request,"MsgWindow","width=900,height=450");
			window.onCompleteOrdenCompra = onCompleteOrdenCompra; 
		});		
		//Eliminar Orden de Compra
		$(document).on("click","#btnClearOrdenCompra",function(){
					$("#txtTransactionMasterIDOrdenCompra").val("");
					$("#txtTransactionNumberOrdenCompra").val("");
		});
		
		$(document).on("click","#btnNewItemCatalog",function(){
			var url_request 			= "<?php echo site_url(); ?>app_inventory_item/add/callback/fnObtenerListadoProductos";
			window.open(url_request,"MsgWindow","width=700,height=600");
			window.fnObtenerListadoProductos = fnObtenerListadoProductos; 			
		});
		
		//Ir a Lista
		$(document).on("click","#btnBack",function(){
				fnWaitOpen();
		});
		
		//Guardar Documento
		$(document).on("click","#btnAcept",function(){
				$( "#form-new-transaction" ).attr("method","POST");
				$( "#form-new-transaction" ).attr("action","<?php echo site_url(); ?>app_inventory_inputunpost/save/new");
				
				if(validateForm()){
					fnWaitOpen();
					$( "#form-new-transaction" ).submit();
				}
				
		});	
		
		//Agregar Item al Detalle
		$(document).on("click","#btnNewDetailTransaction",function(){
			var timerNotification 	= 15000;
			
			//Bodega Source
			if($("#txtProviderID").val()==""){
				fnShowNotification("Seleccione un proveedor","error",timerNotification);
				return;
			}
		
			var url_request 		= "<?php echo site_url(); ?>core_view/showviewbyname/<?php echo $objComponentItem->componentID; ?>/onCompleteItem/SELECCIONAR_ITEM_TO_PROVIDER/true/"+encodeURI('{\"providerID\"|\"'+$("#txtProviderID").val()+'\"}');  
			window.open(url_request,"MsgWindow","width=900,height=450");
			window.onCompleteItem 	= onCompleteItem; 
			
		});
		$(document).on("click",".btnMasInformcion",function(){
			
			var itemID 						= $(this).data("itemid");
			var transactionMasterDetailID 	= $(this).data("transactionmasterdetailid");
			var tr 							= $(this).parent().parent()[0];
			var index 						= objTableDetailTransaction.fnGetPosition(tr);
			var objdat_ 					= objTableDetailTransaction.fnGetData(index);		
			var lote 						= objdat_[9];
			var vencimiento 				= objdat_[10];
			vencimiento 					= vencimiento.replace(" 00:00:00","");
					
			var url_request = "<?php echo site_url(); ?>app_inventory_otherinput/add_masinformacion/onCompleteUpdateMasInformacion/"+itemID+"/"+transactionMasterDetailID+"/"+index; 
			url_request = url_request + "/"+lote+"/"+vencimiento;
			
			window.open(url_request,"MsgWindow","width=900,height=500");
			window.onCompleteUpdateMasInformacion = onCompleteUpdateMasInformacion; 
			
			
		});
		
		//Eliminar Item del Detalle
		$(document).on("click","#btnDeleteDetailTransaction",function(){
			debugger;
			var listRow = objTableDetailTransaction.fnGetData();							
			var length 	= listRow.length;
			var i 		= 0;
			var j 		= 0;
			while (i< length ){
				if(listRow[i][0] == true){
				objTableDetailTransaction.fnDeleteRow( j,null,true );
				j--;
				}
				i++;
				j++;
			}
			
			
		});
		//Cambio en la cantidades
		$(document).on("blur",".txtDetailQuantity",function(){
			$(this).val(fnFormatFloat(fnFormatNumber(fnFormatFloat($(this).val()),numberDecimal)));
			var objrow_ = $(this).parent().parent()[0];
			var objind_ = objTableDetailTransaction.fnGetPosition(objrow_);
			var objdat_ = objTableDetailTransaction.fnGetData(objind_);								
			objTableDetailTransaction.fnUpdate( $(this).val(), objind_, 6 );
			fnUpdateDetail();
			refreschChecked();
		})
		//Cambio en el Costo
		$(document).on("blur",".txtDetailCost",function(){
			$(this).val(fnFormatFloat(fnFormatNumber(fnFormatFloat($(this).val()),numberDecimal)));
			var objrow_ = $(this).parent().parent()[0];
			var objind_ = objTableDetailTransaction.fnGetPosition(objrow_);
			var objdat_ = objTableDetailTransaction.fnGetData(objind_);								
			objTableDetailTransaction.fnUpdate(  $(this).val(), objind_, 7 );
			fnUpdateDetail();
			refreschChecked();
		})
		//Cambio en el Descuento
		$(document).on("change","input#txtDiscount",function(){
			fnUpdateDetail();
		});
		//Cambio en el Iva
		$(document).on("change","input#txtIva",function(){
			fnUpdateDetail();
		});
		
		//Seleccionar Checke 
		$(document).on("click",".classCheckedDetail",function(){
			debugger;
			var objrow_ = $(this).parent().parent()[0];
			var objind_ = objTableDetailTransaction.fnGetPosition(objrow_);
			var objdat_ = objTableDetailTransaction.fnGetData(objind_);								
			objTableDetailTransaction.fnUpdate( !objdat_[0], objind_, 0 );
			refreschChecked();
		});
	});
	
	//Funciones
	////////////////////////////
	////////////////////////////
	function fnObtenerListadoProductos(){
		fnWaitClose();
		$.ajax(
		{									
			cache       : false,
			dataType    : 'json',
			type        : 'GET',																	
			url  		: "<?php echo site_url(); ?>app_invoice_api/getViewApi/<?php echo $objComponentItem->componentID; ?>/onCompleteNewItem/SELECCIONAR_ITEM_BILLING/"+encodeURI('{"warehouseID"|"0"{}"listPriceID"|"<?php echo $objListPrice->listPriceID; ?>"{}"typePriceID"|"0"}'),		
			success		: fnFillListaProductos,
			error:function(xhr,data)
			{	
				console.info("complete data error");									
				fnWaitClose();
				fnShowNotification("Error 505","error");
			}
		});
	}
	function fnFillListaProductos(data)
	{
		console.info("complete success data");
		fnWaitClose();		
		objListaProductos = data.objGridView;
		
	}
	
	function validateForm(){
		var result 				= true;
		var timerNotification 	= 15000;
		
		//Bodega Source
		if($("#txtProviderID").val()==""){
			fnShowNotification("Seleccione un proveedor","error",timerNotification);
			result = false;
		}
		
		//Validar Estado
		if($("#txtStatusID").val() == ""){
			fnShowNotification("Establecer Estado","error",timerNotification);
			result = false;
		}
		//Fecha
		if($("#txtTransactionOn").val() == ""){
			fnShowNotification("Escriba la Fecha del Documento","error",timerNotification);
			result = false;
		}
	
		return result;
	}
	function onCompleteUpdateMasInformacion(objResponse){
			var index 		= objResponse.txtPosition;
			var vencimiento = objResponse.txtVencimiento;
			var lote 		= objResponse.txtLote;
		
			
			var objdat_ = objTableDetailTransaction.fnGetData(index);		
			objTableDetailTransaction.fnUpdate( lote, index, 9 );
			objTableDetailTransaction.fnUpdate(  vencimiento, index, 10 );		
			
		
	}
	function onCompleteItem(objResponse){
		console.info("CALL onCompleteItem");
		var objRow 						= {};
		objRow.checked 					= false;
		objRow.itemID 					= objResponse[1];
		objRow.transactionMasterDetail 	= 0;
		objRow.itemNumber 				= objResponse[2];
		objRow.itemName 				= objResponse[3];
		objRow.itemUM 					= objResponse[4];
		objRow.quantity 				= 0;
		objRow.cost 					= 0;		
		objRow.price 					= 0;
		objRow.lote 					= "";
		objRow.vencimiento				= "";
		objRow.masinfor					= "";
		
		//Berificar que el Item ya esta agregado 
		if(jLinq.from(objTableDetailTransaction.fnGetData()).where(function(obj){ return obj[1] == objRow.itemID;}).select().length > 0 ){
			fnShowNotification("El Item ya esta agregado","error");
			return;
		}
		
		objTableDetailTransaction.fnAddData([
		objRow.checked,objRow.itemID,objRow.transactionMasterDetail,
		objRow.itemNumber,objRow.itemName,objRow.itemUM,
		objRow.quantity,objRow.cost,objRow.price,
		objRow.lote,objRow.vencimiento,
		objRow.masinfor
		]);
		refreschChecked();
		
	}
	//Refresh
	function refreschChecked(){
		/*
		$("[type='checkbox'], [type='radio'], [type='file'], select").not('.toggle, .select2, .multiselect').uniform();
		$('.txtDetailQuantity').mask('000,000.00', {reverse: true});
		$('.txtDetailCost').mask('000,000.00', {reverse: true});
		
		$('#txtDiscount').mask('000,000.00', {reverse: true});
		$('#txtIva').mask('000,000.00', {reverse: true});
		*/
	}
	function onCompleteOrdenCompra(objResponse){
		console.info("CALL onCompleteOrdenCompra");
	
		$("#txtTransactionMasterIDOrdenCompra").val(objResponse[1]);
		$("#txtTransactionNumberOrdenCompra").val(objResponse[2]);
	
	}
	function onCompleteProvider(objResponse){
		console.info("CALL onCompleteCustomer");
	
		$("#txtProviderID").val(objResponse[1]);
		$("#txtProviderDescription").val(objResponse[2] + " / " + objResponse[3]);
	
	}	
	function fnUpdateDetail(){
		console.info("fnUpdateDetail");
		var subtotal 	= 0;
		var iva			= $("#txtIva").val();
		var discount	= $("#txtDiscount").val();						
		var total		= 0;
		
		iva				= fnFormatFloat(fnFormatNumber(iva,numberDecimal));
		discount		= fnFormatFloat(fnFormatNumber(discount,numberDecimal));
		$("#txtIva").val(iva);
		$("#txtDiscount").val(discount);
		
		for(var i = 0; i < objTableDetailTransaction.fnGetData().length; i++){
			var row 	= objTableDetailTransaction.fnGetData()[i];
			subtotal 	= subtotal + fnFormatFloat(fnFormatNumber((row[6] * row[7]),numberDecimal));
		}
		
		subtotal		= parseInt(subtotal * 100) / 100;
		subtotal		= fnFormatFloat(fnFormatNumber(subtotal ,numberDecimalSummary));
		total			= parseInt(((subtotal + iva) - discount) * 100) / 100;
		total			= fnFormatFloat(fnFormatNumber(total,numberDecimalSummary));
		$("#txtSubTotal").val(subtotal);
		$("#txtTotal").val(total);
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

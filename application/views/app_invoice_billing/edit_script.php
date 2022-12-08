<!-- ./ page heading -->
<script>	
	var objTableDetail 			= {};		
	var tmpData 				= [];
	var objListaProductos		= {};
	var varPermitirFacturarProductosEnZero	= '<?php echo $objParameterInvoiceBillingQuantityZero; ?>';
	var varUrlPrinter			= '<?php echo $urlPrinterDocument; ?>';
	var varDetail 				= JSON.parse('<?php echo json_encode($objTransactionMasterDetail); ?>');	
	var varDetailWarehouse		= JSON.parse('<?php echo json_encode($objTransactionMasterDetailWarehouse); ?>');	
	var varDetailConcept 		= JSON.parse('<?php echo json_encode($objTransactionMasterDetailConcept); ?>');	
	var varParameterInvoiceBillingPrinterDirect		= <?php echo $objParameterInvoiceBillingPrinterDirect; ?>;	
	var varParameterInvoiceBillingPrinterDirectUrl	= '<?php echo $objParameterInvoiceBillingPrinterDirectUrl; ?>';	
	var varTransactionCausalID	= <?php echo $objTransactionMaster->transactionCausalID; ?>;	
	var varCustomerCrediLineID	= <?php echo $objTransactionMaster->reference4; ?>;	
	var varPermisos				= JSON.parse('<?php echo json_encode($objListaPermisos); ?>');
	var varPermisosEsPermitidoModificarPrecio = 
		jLinq.from(varPermisos).where(function(obj){ return obj.display == "ES_PERMITIDO_MODIFICAR_PRECIO_EN_FACTURACION"}).select().length > 0 ?
		true:
		false;
	var PriceStatus = varPermisosEsPermitidoModificarPrecio == true ? "":"readonly";

	

	
	if(varDetail != null){
		for(var i = 0 ; i < varDetail.length;i++){
			//Obtener Iva
			var tmp_ = jLinq.from(varDetailConcept).where(function(obj){ return obj.componentItemID == varDetail[i].componentItemID && obj.name == "IVA" }).select();
			var iva_ = (tmp_.length <= 0 ? 0 : parseFloat(tmp_[0].valueOut));
			
			//Rellenar Datos
			tmpData.push([
				0,
				varDetail[i].transactionMasterDetailID,
				varDetail[i].componentItemID,
				varDetail[i].itemNumber,
				"'"+varDetail[i].itemName + "'", 
				varDetail[i].unitMeasureName,
				fnFormatNumber(varDetail[i].quantity,2),
				fnFormatNumber(varDetail[i].unitaryPrice,4),/*precio sistema*/
				fnFormatNumber(varDetail[i].unitaryPrice * varDetail[i].quantity,2), /*precio por cantidad*/							
				fnFormatNumber(iva_,2)
			]);
		}
	}	
	//Obtener informacion del cliente	
	fnWaitOpen();	
	$.ajax({									
		cache       : false,
		dataType    : 'json',
		type        : 'POST',
		url  		: "<?php echo site_url(); ?>app_invoice_api/getLineByCustomer",
		data 		: {entityID : <?php echo $objTransactionMaster->entityID; ?>  },
		success		: fnCompleteGetCustomerCreditLine,
		error:function(xhr,data){	
			console.info("complete data error");									
			fnWaitClose();
			fnShowNotification("Error 505","error");
		}
	});		
	
	//obtener informacion de los productos
	function fnObtenerListadoProductos(){
		$.ajax({									
			cache       : false,
			dataType    : 'json',
			type        : 'GET',
			url  		: "<?php echo site_url(); ?>app_invoice_api/getViewApi/<?php echo $objComponentItem->componentID; ?>/onCompleteNewItem/SELECCIONAR_ITEM_BILLING/"+encodeURI('{"warehouseID"|"<?php echo $warehouseID ?>"{}"listPriceID"|"<?php echo $objListPrice->listPriceID; ?>"{}"typePriceID"|"'+$("#txtTypePriceID").val() +'"}'),		
			success		: fnFillListaProductos,
			error:function(xhr,data){	
				console.info("complete data error");									
				fnWaitClose();
				fnShowNotification("Error 505","error");
			}
		});	
	}

	function fnCustomerNewCompleted(){
		console.info("cliente completado");
	}

	
	



	fnObtenerListadoProductos();
	
	//Incializar Focos
	document.getElementById("txtScanerCodigo").focus();
	
	$(document).ready(function(){					
		 $('#txtDate').datepicker({format:"yyyy-mm-dd"});						 
		 $("#txtDate").datepicker("update");
		 
		$('#txtDateFirst').datepicker({format:"yyyy-mm-dd"});						 
		$("#txtDateFirst").datepicker("update");
		var objectParameterButtoms = {};
		
		
		
		
		if(<?php echo $objParameterInvoiceButtomPrinterFidLocalPaymentAndAmortization; ?> == true){	
			objectParameterButtoms.FidLocalTabla=function(){
				fnWaitOpen();
				window.open("<?php echo site_url(); ?>app_cxc_report/document_credit/viewReport/true/documentNumber/<?php echo $objTransactionMaster->transactionNumber;?>", '_blank');
				fnWaitClose();
				$(this).dialog("close");
			};
		}
		
		
		
		objectParameterButtoms.Imprimir=function(){
			fnWaitOpen();
			window.open("<?php echo site_url(); ?>"+varUrlPrinter+"/companyID/<?php echo $objTransactionMaster->companyID;?>/transactionID/<?php echo $objTransactionMaster->transactionID;?>/transactionMasterID/<?php echo $objTransactionMaster->transactionMasterID;?>", '_blank');
			fnWaitClose();
			$(this).dialog("close");
		};		
		
		$("#modalDialogOpenPrimter").dialog({
				autoOpen: false,
				modal: true,
				width:520,
				dialogClass: "dialog",
				buttons: objectParameterButtoms
		});
		
		
		objTableDetail = $("#tb_transaction_master_detail").dataTable({
			"bPaginate"		: false,
			"bFilter"		: false,
			"bSort"			: false,
			"bInfo"			: false,
			"bAutoWidth"	: false,
			"aaData"		: tmpData,
			"aoColumnDefs": [ 
						{
							"aTargets"		: [ 0 ],//checked
							"mRender"		: function ( data, type, full ) {
								if (data == false)
								return '<input type="checkbox"  class="classCheckedDetail"  value="0" ></span>';
								else
								return '<input type="checkbox"  class="classCheckedDetail" checked="checked" value="0" ></span>';
							}
						},
						{
							"aTargets"		: [ 1 ],//transactionMasterDetailID
							"bVisible"  	: true,
							"sClass" 		: "hidden",
							"bSearchable"	: false,
							"mRender"		: function ( data, type, full ) {
								return '<input type="hidden" value="'+data+'" name="txtTransactionMasterDetailID[]" />';
							}
						},
						{
							"aTargets"		: [ 2 ],//itemID
							"bVisible"		: true,
							"sClass" 		: "hidden",
							"bSearchable"	: false,
							"mRender"		: function ( data, type, full ) {
								return '<input type="hidden" value="'+data+'" name="txtItemID[]" />';
							}
						},
						{
							"aTargets"		: [ 4 ],//descripcion
							"sWidth" 		: "40%"
						},
						{
							"aTargets"		: [ 6 ],//Cantidad
							"mRender"		: function ( data, type, full ) {
								return '<input type="text" class="col-lg-12 txtQuantity" id="txtQuantityRow'+full[2]+'"  value="'+data+'" name="txtQuantity[]" style="text-align:right" />';
							}
						},
						{
							"aTargets"		: [ 7 ],//Precio
							"mRender"		: function ( data, type, full ) {
								return '<input type="text" class="col-lg-12 txtPrice" '+PriceStatus+'  id="txtPriceRow'+full[2]+'"    value="'+data+'" name="txtPrice[]" style="text-align:right" />';
							}
						},
						{
							"aTargets"		: [ 8 ],//Total
							"mRender"		: function ( data, type, full ) {
								return '<input type="text" class="col-lg-12 txtSubTotal" readonly value="'+data+'" name="txtSubTotal[]" style="text-align:right" />';
							}
						},
						{
							"aTargets"		: [ 9 ],//Iva
							"bVisible"		: true,
							"sClass"		: "hidden",
							"bSearchable"	: false,
							"mRender"		: function ( data, type, full ) {
								return '<input type="text" class="col-lg-12 txtIva" value="'+data+'" name="txtIva[]" style="text-align:right" />';
							}
						}
			]						
		});						
		refreschChecked();
		fnRecalculateDetail(false);
		
		$("#txtReceiptAmount").val("<?php echo number_format($objTransactionMasterInfo->receiptAmount,2); ?>");
		$("#txtReceiptAmountDol").val("<?php echo number_format($objTransactionMasterInfo->receiptAmountDol,2); ?>");
		
		var ingreso = fnFormatFloat($("#txtReceiptAmount").val());
		var ingresoDol = fnFormatFloat($("#txtReceiptAmountDol").val());
		var tipoCambio = fnFormatFloat($("#txtExchangeRate").val());
		var total 	= fnFormatFloat($("#txtTotal").val());	
		var resultTotal =  (ingreso + (ingresoDol * tipoCambio)) - total;
		var resultTotal = fnFormatNumber(resultTotal,2);
		$("#txtChangeAmount").val(resultTotal);	
		
		
		$(document).on("keypress",'#txtReceiptAmount', function(e) {	
			var code = e.keyCode || e.which;
			 if(code != 13) { 
			   	 return;
			 }		 
			 
			document.getElementById("txtReceiptAmountDol").focus();
			return;
				
		});
		
		$(document).on("keypress",'#txtReceiptAmountDol', function(e) {		
		
			var code = e.keyCode || e.which;
			 if(code != 13) { 
			   	 return;
			 }		 
			 
			fnEnviarFactura();
			return;
			 
		});
		
		
		$(document).on("keypress",'#txtScanerCodigo', function(e) {
			var code = e.keyCode || e.which;
			 if(code != 13) { 
			   	 return;
			 }		 
			 
			 
			 
			var codigoABuscar = $("#txtScanerCodigo").val();
			$("#txtScanerCodigo").val("");
			
			//Mover a ingreso de dinero Cordoba
			if(codigoABuscar == ""){
				document.getElementById("txtReceiptAmount").focus();
				return;
			}
			
			//buscar el producto y agregar
			var filterResult = jLinq.from(objListaProductos).where(function(obj){ return obj["Barra"] == codigoABuscar}).select();
			if(filterResult.length == 0)
			{
				return;
			}
			
			
			filterResult = filterResult[0];
			var filterResultArray = [];
			filterResultArray[5] = filterResult.itemID;
			filterResultArray[17] = filterResult.Codigo;
			filterResultArray[18] = filterResult.Nombre;
			filterResultArray[20] = "N/A"
			filterResultArray[21] = filterResult.Cantidad;
			filterResultArray[22] = filterResult.Precio;
			//Agregar el Item a la Fila
			 onCompleteNewItem(filterResultArray); 
			 
		});
		
				//Buscar el Cliente
		$(document).on("click","#btnSearchCustomer",function(){
			var url_request = "<?php echo site_url(); ?>core_view/showviewbyname/<?php echo $objComponentCustomer->componentID; ?>/onCompleteCustomer/SELECCIONAR_CLIENTES_BILLING/empty";
			window.open(url_request,"MsgWindow","width=900,height=450");
			window.onCompleteCustomer = onCompleteCustomer; 
		});						
	
		//Eliminar Cliente
		$(document).on("click","#btnClearCustomer",function(){
					$("#txtCustomerID").val("");
					$("#txtCustomerDescription").val("");
		});
		
		
		//Imprimir Documento
		$(document).on("click","#btnPrinter",function(){			

			if(varParameterInvoiceBillingPrinterDirect == true){
				
				var url="<?php echo site_url(); ?>"+varParameterInvoiceBillingPrinterDirectUrl;
				url = url+
				"/companyID/"+"<?php echo $objTransactionMaster->companyID; ?>" + 
				"/transactionID/"+"<?php echo $objTransactionMaster->transactionID; ?>"+
				"/transactionMasterID/"+"<?php echo $objTransactionMaster->transactionMasterID; ?>";

				fnWaitOpen();	
				$.ajax({									
					cache       : false,
					dataType    : 'json',
					type        : 'GET',
					url  		: url,
					success		: function(){
						fnWaitClose();						
					},
					error:function(xhr,data){	
						debugger;
						console.info("complete data error");									
						console.info(data);
						console.info(xhr);
						fnWaitClose();
						//fnShowNotification("Error 505","error");
					}
				});	
				return;
			}
			else{
				$("#modalDialogOpenPrimter").dialog("open");
				return
			}
			
		});
		//Cambios
		$(document).on("change","#txtTypePriceID,#txtCausalID,#txtCustomerCreditLineID",function(){
			fnClearData();
		});
		$(document).on("change","input.txtQuantity",function(){
			fnRecalculateDetail(true);
		});
		$(document).on("change","input.txtPrice",function(){
			fnRecalculateDetail(true);
		});
		//Regresar a la lista
		$(document).on("click","#btnBack",function(){
				fnWaitOpen();
		});
		
		//Evento Agregar el Usuario
		$(document).on("click",".btnAcept",function(){
				var valueWorkflow = $(this).data("valueworkflow");
				$("#txtStatusID").val(valueWorkflow);			
				fnEnviarFactura();
			
		});
		
		
		
		//Eliminar Documento
		$(document).on("click","#btnDelete",function(){							
			fnShowConfirm("Confirmar..","Desea eliminar este Registro...",function(){
				fnWaitOpen();
				$.ajax({									
					cache       : false,
					dataType    : 'json',
					type        : 'POST',
					url  		: "<?php echo site_url(); ?>app_invoice_billing/delete",
					data 		: {companyID : <?php echo $objTransactionMaster->companyID;?>, transactionID : <?php echo $objTransactionMaster->transactionID;?>,transactionMasterID : <?php echo $objTransactionMaster->transactionMasterID; ?>  },
					success:function(data){
						console.info("complete delete success");
						fnWaitClose();
						if(data.error){
							fnShowNotification(data.message,"error");
						}
						else{
							window.location = "<?php echo site_url(); ?>app_invoice_billing/index";
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

		//Nuevo Producto
		$(document).on("click","#btnNewItem",function(){
			var url_request 			= "<?php echo site_url(); ?>core_view/showviewbyname/<?php echo $objComponentItem->componentID; ?>/onCompleteNewItem/SELECCIONAR_ITEM_BILLING/"+encodeURI("{\"warehouseID\"|\"<?php echo $warehouseID ?>\"{}\"listPriceID\"|\"<?php echo $objListPrice->listPriceID; ?>\"{}\"typePriceID\"|\""+ $("#txtTypePriceID").val() + "\"}");
			window.open(url_request,"MsgWindow","width=900,height=450");
			window.onCompleteNewItem 	= onCompleteNewItem; 
		});

		$(document).on("click","#btnNewItemCatalog",function(){
			var url_request 				 = "<?php echo site_url(); ?>app_inventory_item/add.aspx";
			window.open(url_request,"MsgWindow","width=700,height=600");			
			window.fnObtenerListadoProductos = fnObtenerListadoProductos; 
		});

		$(document).on("click","#btnSearchCustomerNew",function(){
			var url_request 				 = "<?php echo site_url(); ?>app_cxc_customer/add/callback/fnCustomerNewCompleted";
			window.open(url_request,"MsgWindow","width=700,height=600");
			window.fnCustomerNewCompleted = fnCustomerNewCompleted; 	
		});


		//Eliminar Item
		$(document).on("click","#btnDeleteItem",function(){
				var listRow = objTableDetail.fnGetData();							
				var length 	= listRow.length;
				var i 		= 0;
				var itemid 	= 0;
				while (i< length ){
					if(listRow[i][0] == true){
						itemid = listRow[i][2];
						objTableDetail.fnDeleteRow(i);
					}
					i++;
				}
				fnRecalculateDetail(true);		
				
		});
		//Ir a archivos
		$(document).on("click","#btnClickArchivo",function(){
			debugger;
			window.open("<?php echo site_url()."core_elfinder/index/componentID/".$objComponentBilling->componentID."/componentItemID/".$objTransactionMaster->transactionMasterID; ?>","blanck");
		});
		//Cambio en los recibido
		$(document).on("change","input#txtReceiptAmount",function(){							
			var ingreso 	= fnFormatFloat($("#txtReceiptAmount").val());
			var ingresoDol 	= fnFormatFloat($("#txtReceiptAmountDol").val());
			var tipoCambio 	= fnFormatFloat($("#txtExchangeRate").val()); 			
			var total 		= fnFormatFloat($("#txtTotal").val());				
			
			var resultTotal =  (ingreso + (ingresoDol * tipoCambio)) - total;
			var resultTotal = fnFormatNumber(resultTotal,2);
			$("#txtChangeAmount").val(resultTotal);	
			
		});
		$(document).on("change","input#txtReceiptAmountDol",function(){							
			var ingreso 	= fnFormatFloat($("#txtReceiptAmount").val());
			var ingresoDol 	= fnFormatFloat($("#txtReceiptAmountDol").val());
			var tipoCambio 	= fnFormatFloat($("#txtExchangeRate").val()); 			
			var total 		= fnFormatFloat($("#txtTotal").val());			
			
			var resultTotal =  (ingreso + (ingresoDol * tipoCambio)) - total;
			var resultTotal = fnFormatNumber(resultTotal,2);
			$("#txtChangeAmount").val(resultTotal);	
			
		});
	});
	
	//Seleccionar Checke 
	$(document).on("click",".classCheckedDetail",function(){
		var objrow_ = $(this).parent().parent().parent().parent()[0];
		var objind_ = objTableDetail.fnGetPosition(objrow_);
		var objdat_ = objTableDetail.fnGetData(objind_);								
		objTableDetail.fnUpdate( !objdat_[0], objind_, 0 );
		refreschChecked();
	});
	
	//Cargar Cliente
	function onCompleteCustomer(objResponse){
		console.info("CALL onCompleteCustomer");
	
	
		var entityID = objResponse[1];
		$("#txtCustomerID").val(objResponse[1]);
		$("#txtCustomerDescription").val(objResponse[2] + " " + objResponse[3] + " / " + objResponse[4]);
	
		fnClearData();
		//Obtener Informacion de Credito
		fnWaitOpen();


		$.ajax({									
			cache       : false,
			dataType    : 'json',
			type        : 'POST',
			url  		: "<?php echo site_url(); ?>app_invoice_api/getLineByCustomer",
			data 		: {entityID : entityID  },
			success		: fnCompleteGetCustomerCreditLine,
			error:function(xhr,data){	
				console.info("complete data error");									
				fnWaitClose();
				fnShowNotification("Error 505","error");
			}
		});
				
				
	}
	//Buscar Linea
	function fnFillListaProductos(data){
		console.info("complete success data");
		fnWaitClose();
		objListaProductos = data.objGridView;
		
	}
	function fnCompleteGetCustomerCreditLine (data)
	{

		console.info("complete success data");
		fnWaitClose();
		tmpInfoClient = data;
		console.info(tmpInfoClient);						
		
		//Renderizar Line Credit
		$("#txtCustomerCreditLineID").html("");
		$("#txtCustomerCreditLineID").val("");
		if(tmpInfoClient.objListCustomerCreditLine != null)
		for(var i = 0; i< tmpInfoClient.objListCustomerCreditLine.length;i++){
			if(i==0 && varCustomerCrediLineID == 0){
				$("#txtCustomerCreditLineID").append("<option value='"+tmpInfoClient.objListCustomerCreditLine[i].customerCreditLineID+"' selected>"+ tmpInfoClient.objListCustomerCreditLine[i].accountNumber + " " +tmpInfoClient.objListCustomerCreditLine[i].line  +"</option>");
				$("#txtCustomerCreditLineID").val(tmpInfoClient.objListCustomerCreditLine[i].customerCreditLineID);
			}
			else if( varCustomerCrediLineID == tmpInfoClient.objListCustomerCreditLine[i].customerCreditLineID){
				$("#txtCustomerCreditLineID").append("<option value='"+tmpInfoClient.objListCustomerCreditLine[i].customerCreditLineID+"' selected>"+ tmpInfoClient.objListCustomerCreditLine[i].accountNumber + " " +tmpInfoClient.objListCustomerCreditLine[i].line  + "</option>");
				$("#txtCustomerCreditLineID").val(tmpInfoClient.objListCustomerCreditLine[i].customerCreditLineID);
			}
			else
				$("#txtCustomerCreditLineID").append("<option  value='"+tmpInfoClient.objListCustomerCreditLine[i].customerCreditLineID+"'>"+ tmpInfoClient.objListCustomerCreditLine[i].accountNumber + " " +tmpInfoClient.objListCustomerCreditLine[i].line  +"</option>");
		}
		
		//Habilitar la compra al contado o al credito
		$("#txtCausalID option").removeAttr("disabled");
		$("#txtCausalID").val("");
		
		
		
		var listArrayCausalCredit = tmpInfoClient.objCausalTypeCredit.value.split(",");
		$.each( $("#txtCausalID option"),function(index,obj){
			for(var i=0;i<listArrayCausalCredit.length;i++){
				var causalIDCredit = listArrayCausalCredit[i];
				if( ($(obj).attr("value") == causalIDCredit) && (tmpInfoClient.objListCustomerCreditLine != null))
					$("#txtCausalID option[value="+causalIDCredit+"]").removeAttr("disabled");
				else if( ($(obj).attr("value") == causalIDCredit) && (tmpInfoClient.objListCustomerCreditLine == null))
					$("#txtCausalID option[value="+causalIDCredit+"]").attr("disabled","true");
				else
					$("#txtCausalID option[value="+causalIDCredit+"]").removeAttr("disabled");
			}
		});
		
		$.each( $("#txtCausalID option"),function(index,obj){
			if(varTransactionCausalID == $(obj).attr("value")){
				$(obj).attr("selected");
				$("#txtCausalID").val(varTransactionCausalID);
			}
		});
		
		//Refresh Control
		$("#txtCustomerCreditLineID").select2();
		$("#txtCausalID").select2();
		refreschChecked();
	}
	//Nuevo Producto
	function onCompleteNewItem(objResponse){
		console.info("CALL onCompleteNewItem");
		var objRow 							= {};
		objRow.checked 						= false;						
		objRow.transactionMasterDetailID 	= 0;
		objRow.itemID						= objResponse[5];
		objRow.codigo						= objResponse[17];
		objRow.description					= objResponse[18].toLowerCase();
		objRow.um							= objResponse[20];
		objRow.quantity 					= fnFormatNumber(1,2);
		objRow.bquantity 					= fnFormatNumber(objResponse[21],2);
		objRow.price 						= fnFormatNumber(objResponse[22],2);
		objRow.total 						= fnFormatNumber(objRow.quantity * objRow.price,2);						
		objRow.iva 							= 0;
		objRow.lote 						= "";
		objRow.vencimiento					= "";
		
		//Berificar que el Item ya esta agregado 
		if(jLinq.from(objTableDetail.fnGetData()).where(function(obj){ return obj[2] == objRow.itemID;}).select().length > 0 ){
			fnShowNotification("El Item ya esta agregado","error");
			return;
		}
		
		objTableDetail.fnAddData([
			objRow.checked,
			objRow.transactionMasterDetailID,
			objRow.itemID,
			objRow.codigo,
			objRow.description,
			objRow.um,
			objRow.quantity,
			objRow.price,
			objRow.total,
			objRow.iva
		]);
		
		fnGetConcept(objRow.itemID,"IVA");						
		refreschChecked();		
		document.getElementById("txtScanerCodigo").focus();		
		
	}
	
	function validateForm(){
		var result 				= true;		var timerNotification 	= 15000;
		var switchDesembolso	= !$("#txtLabelIsDesembolsoEfectivo").parent().find(".switch.has-switch").children().hasClass("switch-off");
		
		//Validar Fecha		
		if($("#txtDate").val() == ""){			
			fnShowNotification("Establecer Fecha al Documento","error",timerNotification);			
			result = false;		
		}		
		//Validar Cliente		
		if($("#txtCustomerID").val() == ""){
			fnShowNotification("Seleccionar el Cliente","error",timerNotification);
			result = false;
		}
		
		//Validar Proveedor de Credito
		if($("#txtReference1").val() == "0" && switchDesembolso){
			fnShowNotification("Seleccionar el Proveedor de Credito","error",timerNotification);
			result = false;
		}

		//Validar Zona
		if($("#txtZoneID").val() == "" && switchDesembolso){
			fnShowNotification("Seleccionar la Zona de la Factura","error",timerNotification);
			result = false;
		}
		
		
		
		//Validar Detalle
		//
		///////////////////////////////////////////////
		//Validar Cuentas del Comprobantes
		//if(objTableDetail.fnGetData().length == 0){
		//	fnShowNotification("La factura no tiene productos","error",timerNotification);
		//	result = false;
		//};
		
		var cantidadTotalesEnZero = jLinq.from(objTableDetail.fnGetData()).where(function(obj){ return obj[8] == 0;}).select().length ;
		if(cantidadTotalesEnZero > 0){
			fnShowNotification("No pueden haber totales en 0","error",timerNotification);
			result = false;
		};		
		
		var cantidadTotalesEnZero = jLinq.from(objTableDetail.fnGetData()).where(function(obj){ return obj[6] == 0;}).select().length ;
		if(cantidadTotalesEnZero > 0){
			fnShowNotification("No pueden haber cantidades en 0","error",timerNotification);
			result = false;
		};		
		
		for(var i = 0; i < objTableDetail.fnGetData().length; i++){
			var rowTable = objTableDetail.fnGetData()[i];
			var rowTableItemID 		 = rowTable[2];
			var rowTableItemQuantity = rowTable[6];
			var rowTableItemNombre = rowTable[4];
			var objProducto = jLinq.from(objListaProductos).where(function(obj){ return obj.itemID == rowTableItemID}).select();
			
			if(objProducto.length == 0){
				fnShowNotification("Producto no se encuentra en inventario","error",timerNotification);
				result = false;	
			}
			
			
			objProducto = objProducto[0];
			if(
				parseFloat(objProducto.Cantidad) < parseFloat(rowTableItemQuantity)
				&&
				objProducto.isInvoiceQuantityZero == "0" 
				&& 
				varPermitirFacturarProductosEnZero == "false" 
			){
				fnShowNotification("Producto no hay suficiente en inventario " + rowTableItemNombre,"error",timerNotification);				
				document.getElementById("txtQuantityRow"+rowTableItemID).focus();
				result = false;	
				
			}
			
			
			
		}
		
		
		
		//Si es de credito que la factura no supere la linea de credito
		var causalSelect 				= $("#txtCausalID").val();
		var customerCreditLineID 		= $("#txtCustomerCreditLineID").val();
		var objCustomerCreditLine 		= jLinq.from(tmpInfoClient.objListCustomerCreditLine).where(function(obj){ return obj.customerCreditLineID == customerCreditLineID; }).select();
		var causalCredit 				= tmpInfoClient.objCausalTypeCredit.value.split(",");
		var invoiceTypeCredit 			= false;
		
		//Obtener si la factura es al credito						
		for(var i=0;i<causalCredit.length;i++){
			if(causalCredit[i] == causalSelect){
				invoiceTypeCredit = true;
			}
		}
		
		//Obtener Limite
		if(invoiceTypeCredit){

			//Validar Fecha del Primer Pago si es de Credito
			if($("#txtDateFirst").val() == "" && switchDesembolso){
				fnShowNotification("Seleccionar la Fecha del Primer Pago","error",timerNotification);
				result = false;
			}
			
			
			//Validar Notas
			if($("#txtNote").val() == "" && switchDesembolso){
				fnShowNotification("Asignarle una nota al documento","error",timerNotification);
				result = false;
			}
			
			//Validar Escritura Publica
			if($("#txtFixedExpenses").val() == "" && switchDesembolso){
				fnShowNotification("Ingresar el Porcentaje de Gastos Fijo por Desembolso","error",timerNotification);
				result = false;
			}
			
			var montoTotalInvoice 	= fnFormatFloat(fnFormatNumber($("#txtTotal").val(),"4"));
			var balanceCredit 		= 0;
			
			if(tmpInfoClient.objCurrencyCordoba.currencyID == objCustomerCreditLine[0].currencyID)
				balanceCredit =  fnFormatFloat(fnFormatNumber(objCustomerCreditLine[0].balance,"4"));
			else{
				balanceCredit = (
									fnFormatFloat(fnFormatNumber(objCustomerCreditLine[0].balance,"4")) * 
									fnFormatFloat(fnFormatNumber(objCustomerCreditLine[0].objExchangeRate,"4")) 
								);
			}
			
			//Validar Limite
			if(balanceCredit < montoTotalInvoice){
				fnShowNotification("La factura no puede ser facturada al credito. Balance del cliente: " + balanceCredit,"error",timerNotification);
				result = false;
			}
			
		}
		else{
			//Validar Pago
			if( parseFloat( $("#txtChangeAmount").val() )  < 0 ){
				fnShowNotification("El cambio de la factura no puede ser menor a 0","error",timerNotification);
				result = false;
			}
			
		}
		
		
		return result;
	}
	function fnGetConcept(conceptItemID,nameConcept){
		fnWaitOpen();
		$.ajax({									
			cache       : false,
			dataType    : 'json',
			type        : 'POST',
			url  		: "<?php echo site_url(); ?>core_concept_api/index",
			data 		: {companyID : <?php echo $companyID;?>, componentID : <?php echo $objComponentItem->componentID;?>, componentItemID : conceptItemID, name : nameConcept  },
			success:function(data){
				console.info("complete concept success");
				fnWaitClose();
				if(data.error){
					fnShowNotification(data.message,"error");
					fnRecalculateDetail(true);
					return;
				}								
				
				if(data.data != null){
					var x_		= jLinq.from(objTableDetail.fnGetData()).where(function(obj){ return obj[2] == data.data.componentItemID;}).select();									
					var objind_ = fnGetPosition(x_,objTableDetail.fnGetData());
					objTableDetail.fnUpdate( fnFormatNumber(data.data.valueOut,2), objind_, 9 );
				}
				fnRecalculateDetail(true);
			},
			error:function(xhr,data){	
				console.info("complete concept error");									
				fnWaitClose();
				fnShowNotification("Error 505","error");
				fnRecalculateDetail(true);
			}
		});								
	}
	
	function fnRecalculateDetail(clearRecibo){
		var cantidad 				= 0;
		var iva 					= 0;
		var precio					= 0;		
		var subtotal 				= 0;		
		var total 					= 0;
		
		var cantidadGeneral 				= 0;
		var ivaGeneral 						= 0;
		var precioGeneral					= 0;		
		var subtotalGeneral 				= 0;		
		var totalGeneral 					= 0;
		
		
		var NSSystemDetailInvoice	= objTableDetail.fnGetData();		
		for(var i = 0; i < NSSystemDetailInvoice.length; i++){
			objTableDetail.fnUpdate( $(".txtQuantity")[i].value, i, 6 );
			objTableDetail.fnUpdate( $(".txtPrice")[i].value, i, 7 );
		
			cantidad 	= parseFloat(NSSystemDetailInvoice[i][6]);
			precio 		= parseFloat(NSSystemDetailInvoice[i][7]);
			iva 		= parseFloat(NSSystemDetailInvoice[i][9]);
			
			
			
			subtotal    = precio * cantidad;
			iva 		= (precio * cantidad) * iva;
			total 		= iva + subtotal;
			
			
			cantidadGeneral 	= cantidadGeneral + cantidad;
			precioGeneral 		= precioGeneral + precio;
			ivaGeneral 			= ivaGeneral + iva;			
			subtotalGeneral 	= subtotalGeneral + subtotal;
			totalGeneral 		= totalGeneral + total;
			
			
			objTableDetail.fnUpdate( fnFormatNumber(subtotal,2), i, 8 );
		}						
		$("#txtSubTotal").val(fnFormatNumber(subtotalGeneral,2));
		$("#txtIva").val(fnFormatNumber(ivaGeneral,2));
		$("#txtTotal").val(fnFormatNumber(totalGeneral,2));
		
		$("#txtReceiptAmount").val("0.00");
		$("#txtReceiptAmountDol").val("0.00");
		$("#txtChangeAmount").val("0.00");			
		
	}
	function refreschChecked(){
		$("[type='checkbox'], [type='radio'], [type='file'], select").not('.toggle, .select2, .multiselect').uniform();						
	}
	function fnClearData(){
			console.info("fnClearData");
			objTableDetail.fnClearTable();
			$("#txtReceiptAmount").val("0");
			$("#txtReceiptAmountDol").val("0.00");
			$("#txtChangeAmount").val("0");
			$("#txtSubTotal").val("0");
			$("#txtIva").val("0");
			$("#txtTotal").val("0");
	}
	function fnGetPosition(item,data){
		var i = 0;
		for(i = 0 ; i < data.length; i++){
			var x_		= jLinq.from(data).where(function(obj){ return obj[2] == item[0][2]}).select();
			if(x_.length != 0)
				return i;							
		}
	}
	function fnEnviarFactura(){
				$( "#form-new-invoice" ).attr("method","POST");
				$( "#form-new-invoice" ).attr("action","<?php echo site_url(); ?>app_invoice_billing/save/edit");
				
				if(validateForm()){
					fnWaitOpen();
					$( "#form-new-invoice" ).submit();
				}				
	}		
</script>

<script>  (function(g,u,i,d,e,s){g[e]=g[e]||[];var f=u.getElementsByTagName(i)[0];var k=u.createElement(i);k.async=true;k.src='https://static.userguiding.com/media/user-guiding-'+s+'-embedded.js';f.parentNode.insertBefore(k,f);if(g[d])return;var ug=g[d]={q:[]};ug.c=function(n){return function(){ug.q.push([n,arguments])};};var m=['previewGuide','finishPreview','track','identify','triggerNps','hideChecklist','launchChecklist'];for(var j=0;j<m.length;j+=1){ug[m[j]]=ug.c(m[j]);}})(window,document,'script','userGuiding','userGuidingLayer','744100086ID'); </script>
				<!-- ./ page heading -->
				<script>	
					var objTableDetailTransaction = {};
					$(document).ready(function(){					
						//Inicializar Controles		
						$('#txtTransactionOn').datepicker({format:"yyyy-mm-dd"});
						$('#txtTransactionOn').val(moment().format("YYYY-MM-DD"));						 						
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
												return '<input type="text" class="col-lg-12 txtDetailQuantity txt-numeric " value="'+data+'" name="txtDetailQuantity[]" />';
											}
										}
							]							
						});
						
						
						//Ir a Lista
						$(document).on("click","#btnBack",function(){
								fnWaitOpen();
						});
						
						//Guardar Documento
						$(document).on("click","#btnAcept",function(){
								$( "#form-new-transaction" ).attr("method","POST");
								$( "#form-new-transaction" ).attr("action","<?php echo site_url(); ?>app_purchase_internalpurchaserequest/save/new");
								
								if(validateForm()){
									fnWaitOpen();
									$( "#form-new-transaction" ).submit();
								}
								
						});	
						
						//Agregar Item al Detalle
						$(document).on("click","#btnNewDetailTransaction",function(){
							var timerNotification 	= 15000;
							
							//Bodega Source
							if($("#txtWarehouseSourceID").val()==""){
								fnShowNotification("Seleccione la Bodega Origen","error",timerNotification);
								return;
							}
						
							//var url_request 		= "<?php echo site_url(); ?>core_view/showviewbyname/<?php echo $componentItemID; ?>/onCompleteItem/SELECCIONAR_ITEM_TO_SOLICGENERAL/"+encodeURI('{\"warehouseTargetID\"|\"'+$("#txtWarehouseTargetID").val()+'\"{}\"warehouseSourceID\"|\"'+$("#txtWarehouseSourceID").val()+'\"}');  
							var url_request 		= "<?php echo site_url(); ?>core_view/showviewbyname/<?php echo $componentItemID; ?>/onCompleteItem/SELECCIONAR_ITEM_TO_SOLICGENERAL/"+encodeURI('{\"warehouseSourceID\"|\"'+$("#txtWarehouseSourceID").val()+'\"}');  
							window.open(url_request,"MsgWindow","width=900,height=450");
							window.onCompleteItem 	= onCompleteItem; 
							
						});
						
						//Eliminar Item del Detalle
						$(document).on("click","#btnDeleteDetailTransaction",function(){
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
							var objrow_ = $(this).parent().parent()[0];
							var objind_ = objTableDetailTransaction.fnGetPosition(objrow_);
							var objdat_ = objTableDetailTransaction.fnGetData(objind_);								
							objTableDetailTransaction.fnUpdate( $(this).val(), objind_, 6 );
							refreschChecked();
						})
						//Seleccionar Checke 
						$(document).on("click",".classCheckedDetail",function(){
							var objrow_ = $(this).parent().parent().parent().parent()[0];
							var objind_ = objTableDetailTransaction.fnGetPosition(objrow_);
							var objdat_ = objTableDetailTransaction.fnGetData(objind_);								
							objTableDetailTransaction.fnUpdate( !objdat_[0], objind_, 0 );
							refreschChecked();
						});
					});
					//Cambio de Bodega
					$(document).on("change","#txtWarehouseSourceID",function(s,e){
						console.info("call changeWarehouse");
						//Limpiar la Lista
						objTableDetailTransaction.fnClearTable();
					});
					
					//Funciones
					////////////////////////////
					////////////////////////////
					function validateForm(){
						var result 				= true;
						var timerNotification 	= 15000;
						
						//Bodega Source
						if($("#txtWarehouseSourceID").val()==""){
							fnShowNotification("Seleccione la Bodega Origen","error",timerNotification);
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
						//Detalle
						var lengthRow = objTableDetailTransaction.fnGetData().length;
						if(lengthRow == 0){
							fnShowNotification("Agregar el Detalle del Documento","error",timerNotification);
							result = false;
						}
						//Verificar la Cantidad
						if(jLinq.from(objTableDetailTransaction.fnGetData()).where(function(obj){ return obj[6] == 0;}).select().length > 0 ){
							fnShowNotification("Valores Ceros no Permitidos","error");
							return;
						}
					
						return result;
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
						
						
						//Berificar que el Item ya esta agregado 
						if(jLinq.from(objTableDetailTransaction.fnGetData()).where(function(obj){ return obj[1] == objRow.itemID;}).select().length > 0 ){
							fnShowNotification("El Item ya esta agregado","error");
							return;
						}
						
						objTableDetailTransaction.fnAddData([objRow.checked,objRow.itemID,objRow.transactionMasterDetail,objRow.itemNumber,objRow.itemName,objRow.itemUM,objRow.quantity]);
						refreschChecked();
						
					}
					//Refresh
					function refreschChecked(){
						$("[type='checkbox'], [type='radio'], [type='file'], select").not('.toggle, .select2, .multiselect').uniform();
						$('.txtDetailQuantity').mask('000,000.00', {reverse: true});
					}
				</script>
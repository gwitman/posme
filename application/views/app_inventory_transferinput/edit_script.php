				<!-- ./ page heading -->
				<script>	
					var objTableDetailTransaction = {};
					$(document).ready(function(){					
						//Inicializar Controles		
						$('#txtTransactionOn').datepicker({format:"yyyy-mm-dd"});
						
						objTableDetailTransaction = $("#tb_transaction_master_detail").dataTable({
							"bPaginate"		: false,
							"bFilter"		: false,
							"bSort"			: false,
							"bInfo"			: false,
							"bAutoWidth"	: false,
							"aaData": [		
								<?php 
									$listrow = [];
									foreach($objTMD as $i)
									{
									$listrow[] = "[0,".$i->componentItemID.",".$i->transactionMasterDetailID.",'".$i->itemNumber."','".$i->itemName."','".$i->unitMeasureName."',fnFormatNumber(".$i->quantity.",2),'".$i->lote."','".$i->expirationDate."','mas informacion']";
									}
									echo implode(",",$listrow);
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
											"aTargets"		: [ 7 ],//lote
											"bVisible"		: true,
											"sClass" 		: "hidden",
											"bSearchable"	: false,
											"mRender"	: function ( data, type, full ) {
												return '<input type="text" class="col-lg-12 txtDetailLote txt-numeric"" value="'+data+'" name="txtDetailLote[]" readonly="true" />';
											}
										},
										{
											"aTargets"		: [ 8 ],//fecha expiracion
											"bVisible"		: true,
											"sClass" 		: "hidden",
											"bSearchable"	: false,
											"mRender"	: function ( data, type, full ) {
												return '<input type="text" class="col-lg-12 txtDetailVencimiento txt-numeric"" value="'+data+'" name="txtDetailVencimiento[]" readonly="true" />';
											}
										},
										{
											"aTargets"	: [ 9 ],//mas informacion
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
						
						//Ir a Lista
						$(document).on("click","#btnBack",function(){
								fnWaitOpen();
						});
						$(document).on("click","#btnPrinter",function(){
							fnWaitOpen();							
							window.location = "<?php echo site_url(); ?>app_inventory_transferinput/viewRegister/companyID/<?php echo $objTM->companyID;?>/transactionID/<?php echo $objTM->transactionID;?>/transactionMasterID/<?php echo $objTM->transactionMasterID;?>";
						});
						//Eliminar el Documento
						$(document).on("click","#btnDelete",function(){
							fnShowConfirm("Confirmar..","Desea eliminar este Registro...",function(){
								fnWaitOpen();
								$.ajax({									
									cache       : false,
									dataType    : 'json',
									type        : 'POST',
									url  		: "<?php echo site_url(); ?>app_inventory_transferinput/delete",
									data 		: {companyID : <?php echo $objTM->companyID;?>, transactionID : <?php echo $objTM->transactionID;?> , transactionMasterID : <?php echo $objTM->transactionMasterID;?>  },
									success:function(data){
										console.info("complete delete success");
										fnWaitClose();
										if(data.error){
											fnShowNotification(data.message,"error");
										}
										else{
											window.location = "<?php echo site_url(); ?>app_inventory_transferinput/index";
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
						//Guardar Documento
						$(document).on("click","#btnAcept",function(){
								$( "#form-new-transaction" ).attr("method","POST");
								$( "#form-new-transaction" ).attr("action","<?php echo site_url(); ?>app_inventory_transferinput/save/edit");
								
								if(validateForm()){
									fnWaitOpen();
									$( "#form-new-transaction" ).submit();
								}
								
						});	
						
						//Agregar Item
						$(document).on("click","#btnNewDetailTransaction",function(){							
							if($("#txtWarehouseSourceID").val() == "")
							{
								fnShowNotification("Seleccionar la Bodega Destino","error");
								return;
							}
							
							var url_request	= "<?php echo site_url(); ?>core_view/showviewbyname/<?php echo $componentItemID; ?>/onCompleteItem/SELECCIONAR_ITEM_TO_TRANSFERINPUT/true/"+encodeURI("{\"warehouseSourceID\"|\""+$("#txtWarehouseSourceID").val()+"\"{}\"userID\"|\"<?php echo $userID; ?>\"}");
							window.open(url_request,"MsgWindow","width=900,height=450");
							window.onCompleteItem = onCompleteItem; 
							
						});
						$(document).on("click",".btnMasInformcion",function(){
			
							var itemID 						= $(this).data("itemid");
							var transactionMasterDetailID 	= $(this).data("transactionmasterdetailid");
							var tr 							= $(this).parent().parent()[0];
							var index 						= objTableDetailTransaction.fnGetPosition(tr);
							var objdat_ 					= objTableDetailTransaction.fnGetData(index);		
							var lote 						= objdat_[7];
							var vencimiento 				= objdat_[8];
							vencimiento 					= vencimiento.replace(" 00:00:00","");
							
							var url_request = "<?php echo site_url(); ?>app_inventory_otherinput/add_masinformacion/onCompleteUpdateMasInformacion/"+itemID+"/"+transactionMasterDetailID+"/"+index; 
							url_request = url_request + "/"+lote+"/"+vencimiento;
							
							window.open(url_request,"MsgWindow","width=900,height=500");
							window.onCompleteUpdateMasInformacion = onCompleteUpdateMasInformacion; 
						});
						//Eliminar Item
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
					
					//Funciones
					////////////////////////////
					////////////////////////////
					function validateForm(){
						var result 				= true;
						var timerNotification 	= 15000;
						
						//Bodega
						if($("#txtWarehouseSourceID").val()==""){
							fnShowNotification("Seleccionar la Bodega","error",timerNotification);
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
						
						//Validar Fecha
						if(moment($("#txtTransactionOn").val(),"YYYY-MM-DD").toDate()  > moment().toDate()){
							fnShowNotification("La Fecha no Puede ser Mayor a la Fecha Actual","error",timerNotification);
							result = false;
						}
						
						
						//Detalle
						var lengthRow = objTableDetailTransaction.fnGetData().length;
						if(lengthRow == 0){
							fnShowNotification("Agregar el Detalle del Documento","error",timerNotification);
							result = false;
						}
						return result;
					}
					function onCompleteUpdateMasInformacion(objResponse){
							var index 		= objResponse.txtPosition;
							var vencimiento = objResponse.txtVencimiento;
							var lote 		= objResponse.txtLote;
						
							
							var objdat_ = objTableDetailTransaction.fnGetData(index);		
							objTableDetailTransaction.fnUpdate( lote, index, 7 );
							objTableDetailTransaction.fnUpdate(  vencimiento, index, 8 );			
							
							
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
					function refreschChecked(){
						$("[type='checkbox'], [type='radio'], [type='file'], select").not('.toggle, .select2, .multiselect').uniform();
						$('.txtDetailQuantity').mask('000,000.00', {reverse: true});
						//$('.txtDetailCost').mask('000,000.00', {reverse: true});
					}
					
				</script>
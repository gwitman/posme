				<!-- ./ page heading -->
				<script>	
					var objTableDetailTransaction = {};
					$(document).ready(function(){					
						//Inicializar Controles		
						$('#txtTransactionOn').datepicker({format:"yyyy-mm-dd"});
						$("#txtTransactionOn").datepicker("update");
						$('.txt-numeric').mask('000,000.00', {reverse: true});
						
						
						
						//Buscar el Proveedor
						$(document).on("click","#btnSearchProvider",function(){
							var url_request = "<?php echo site_url(); ?>core_view/showviewbyname/<?php echo $objComponentProvider->componentID; ?>/onCompleteProvider/SELECCIONAR_PROVEEDOR/empty";
							window.open(url_request,"MsgWindow","width=900,height=450");
							window.onCompleteProvider = onCompleteProvider; 
						});		
						//Eliminar Proveedor
						$(document).on("click","#btnClearProvider",function(){
									$("#txtProviderID").val("");
									$("#txtProviderDescription").val("");
						});

						
						//Ir a Lista
						$(document).on("click","#btnBack",function(){
								fnWaitOpen();
						});
						//Printer
						$(document).on("click","#btnPrinter",function(){
							fnWaitOpen();							
							window.location = "<?php echo site_url(); ?>app_purchase_returnsprovider/viewRegister/companyID/<?php echo $objTM->companyID;?>/transactionID/<?php echo $objTM->transactionID;?>/transactionMasterID/<?php echo $objTM->transactionMasterID;?>";
						});
						
						//Archivos
						$(document).on("click","#btnClickArchivo",function(){
							window.open("<?php echo site_url()."core_elfinder/index/componentID/".$objComponentDevolucionCompra->componentID."/componentItemID/".$objTM->transactionMasterID; ?>","blanck");
						});
						
						//Eliminar el Documento
						$(document).on("click","#btnDelete",function(){
							fnShowConfirm("Confirmar..","Desea eliminar este Registro...",function(){
								fnWaitOpen();
								$.ajax({									
									cache       : false,
									dataType    : 'json',
									type        : 'POST',
									url  		: "<?php echo site_url(); ?>app_purchase_returnsprovider/delete",
									data 		: {companyID : <?php echo $objTM->companyID;?>, transactionID : <?php echo $objTM->transactionID;?> , transactionMasterID : <?php echo $objTM->transactionMasterID;?>  },
									success:function(data){
										console.info("complete delete success");
										fnWaitClose();
										if(data.error){
											fnShowNotification(data.message,"error");
										}
										else{
											window.location = "<?php echo site_url(); ?>app_purchase_returnsprovider/index";
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
								$( "#form-new-transaction" ).attr("action","<?php echo site_url(); ?>app_purchase_returnsprovider/save/edit");
								
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
						
							var url_request 		= "<?php echo site_url(); ?>core_view/showviewbyname/<?php echo $objComponentItem->componentID; ?>/onCompleteItem/SELECCIONAR_ITEM_TO_PROVIDER/"+encodeURI('{\"providerID\"|\"'+$("#txtProviderID").val()+'\"}');  
							window.open(url_request,"MsgWindow","width=900,height=450");
							window.onCompleteItem 	= onCompleteItem; 
							
						});
						//Eliminar Item del Detalle
						$(document).on("click","#btnDeleteDetailTransaction",function(){
							$(".txtCheckedIsActive").each(function(i,obj){
									if($(obj).attr("checked") == "checked"){
										$(obj).parents("tr").first().remove();
									}
							});								
						});
						
					});
					
					//Funciones
					////////////////////////////
					////////////////////////////
					function validateForm(){
						var result 				= true;
						var timerNotification 	= 15000;
						
						//Bodega Proveedor
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
						
						$("select.select2statusID").each(function(e,i){
							if($(i).val() == "") {
								fnShowNotification("Seleccionar la razon de la devolucion","error",timerNotification);
								result = false;
							}
						});

						
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
						objRow.lote 					= "";
						objRow.vencimiento 				= "";
						
						
						//Validar si esta el item
						for(var i = 0 ; i < $(".classDetailItem").length; i++){
								var x  = $(($(".classDetailItem")[i])).val(); 
								var y  = objRow.itemID;
								
								if(x == y){
									fnShowNotification("El Item ya esta agregado","error");
									return;
								}
								
						}
						
						
						var tmpl = $($("#tmpl_row_razon").html());								
						var id 	 = tmpl.find("#txtDetailTipoID").attr("id") + moment().format("ms")
						
						tmpl.find("#txtDetailItemID").attr("value",objRow.itemID);
						tmpl.find("#txtDetailTipoID").attr("id",id);
						tmpl.find("#txtCodigo").text(objRow.itemNumber);
						tmpl.find("#txtNombre").text(objRow.itemName);
						tmpl.find("#txtUM").text(objRow.itemUM);
								
						if($("#"+id).length > 0 )
							return;
									
						$("#body_detail_transaction").append(tmpl);
						refreschChecked();
						
					}
					//Refresh
					function refreschChecked(){
						$("[type='checkbox'], [type='radio'], [type='file'], select").not('.toggle, .select2, .multiselect').uniform();
						
						$("select").each(function(e,i){
							$("#"+i.id).select2({placeholder: "seleccionar..."}); 
						});
					
						$('.txt-numeric').mask('000,000.00', {reverse: true});
						
					}
					function onCompleteProvider(objResponse){
						console.info("CALL onCompleteCustomer");
					
						$("#txtProviderID").val(objResponse[1]);
						$("#txtProviderDescription").val(objResponse[2] + " / " + objResponse[3]);
					
					}		
				</script>
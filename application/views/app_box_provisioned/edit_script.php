				<!-- ./ page heading -->
				<script>		
					$(document).ready(function(){					
						 $('#txtDate').datepicker({format:"yyyy-mm-dd"});						 
						 $("#txtDate").datepicker("update");
						 $('.txt-numeric').mask('000,000.00', {reverse: true});
						 
						
						//Regresar a la lista
						$(document).on("click","#btnBack",function(){
								fnWaitOpen();
						});
						
						
						//Evento Agregar el Usuario
						$(document).on("click","#btnAcept",function(){
								
								if($(".txtCheckedIsActive").length  == 0 ){
									fnShowNotification("Seleccionar documento","error");
									return; 
								}
								
								$( "#form-new-invoice" ).attr("method","POST");
								$( "#form-new-invoice" ).attr("action","<?php echo site_url(); ?>app_box_provisioned/save/edit");
								
								if(validateForm()){
									fnWaitOpen();
									$( "#form-new-invoice" ).submit();
								}
								
						});
						
						//Nueva factura
						$(document).on("click","#btnNewShare",function(){
						
							if($(".txtCheckedIsActive").length  >= 1 ){
								fnShowNotification("El Documento no puede tener mas de un item","error");
								return; 
							}
							var url_request 			= "<?php echo site_url(); ?>core_view/showviewbyname/<?php echo $objComponentCustomerCreditDocument->componentID; ?>/onCompleteNewShare/LISTA_DE_CREDITOS_MOROSOS/true/empty";							
							window.open(url_request,"MsgWindow","width=1585,height=795");
							window.onCompleteNewShare 	= onCompleteNewShare; 
						});
						//Eliminar factura
						$(document).on("click","#btnDeleteShare",function(){
							console.info("btnDeleteShare");
							$(".txtCheckedIsActive").each(function(i,obj){
									if($(obj).attr("checked") == "checked"){
										$(obj).parents("tr").first().remove();
									}
							});	
							
						});						
						$(document).on("change","input.txtBalance",function(){
							updateSummary();
							updateCalculateChange();
						});
						$(document).on("change","#txtReceiptAmount",function(){
							updateCalculateChange();
						});
						$(document).on("click","#btnDelete",function(){							
							fnShowConfirm("Confirmar..","Desea eliminar este Registro...",function(){
								fnWaitOpen();
								$.ajax({									
									cache       : false,
									dataType    : 'json',
									type        : 'POST',
									url  		: "<?php echo site_url(); ?>app_box_provisioned/delete",
									data 		: {companyID : <?php echo $objTransactionMaster->companyID;?>, transactionID : <?php echo $objTransactionMaster->transactionID;?>,transactionMasterID : <?php echo $objTransactionMaster->transactionMasterID; ?>  },
									success:function(data){
										console.info("complete delete success");
										fnWaitClose();
										if(data.error){
											fnShowNotification(data.message,"error");
										}
										else{
											window.location = "<?php echo site_url(); ?>app_box_provisioned/index";
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
						
						$(document).on("click","#btnClickArchivo",function(){
							window.open("<?php echo site_url()."core_elfinder/index/componentID/".$objComponentShare->componentID."/componentItemID/".$objTransactionMaster->transactionMasterID; ?>","blanck");
						});
						
					});
					
					function updateCalculateChange(){
						console.info("updateCalculateChange");
						var i = fnFormatFloat($("#txtTotal").val());
						var x = fnFormatFloat($("#txtReceiptAmount").val());
						$("#txtChangeAmount").val(fnFormatNumber((x-i),2));
					}
					function updateSummary(){
						console.info("updateSummary");
						var total = 0;
						$(".txtBalance").each(function(index,obj){
							total = total + fnFormatFloat($(obj).val());
						});
						total = fnFormatNumber(total,2);
						$("#txtTotal").val(total);
					}
					function onCompleteNewShare(objResponse){
						console.info("CALL onCompleteNewShare");	
						
						var objRow 						= {};
						objRow.checked 					= false;
						objRow.transactionMasterDetail 	= 0;						
						objRow.customerCreditDocumentID = objResponse[0];									/*customerCreditDocumentID*/
						objRow.documentNumber		 	= objResponse[1];									/*documentNumber*/
						objRow.nameMoneda			 	= objResponse[3];									/*moneda*/
						objRow.balance					= fnFormatFloat(objResponse[4],2);	/*saldo*/						
						
						
						
						//Validar si esta el item
						for(var i = 0 ; i < $(".classDetailItem").length; i++){
								var x  = $(($(".classDetailItem")[i])).val(); 
								var y  = objRow.customerCreditDocumentID;
								
								if(x == y){
									fnShowNotification("El Documento ya esta agregado","error");
									return;
								}
								
						}
						
						
						var tmpl = $($("#tmpl_row_document").html());
						tmpl.find("#txtDetailCustomerCreditDocumentID").attr("value",objRow.customerCreditDocumentID);						
						tmpl.find("#txtDetailTransactionMaster").attr("value",objRow.transactionMasterDetail);						
						tmpl.find("#txtDocument").text(objRow.documentNumber);
						tmpl.find("#txtNameMoneda").text(objRow.nameMoneda);
						tmpl.find("#txtBalance").attr("value",objRow.balance);
						
						$("#body_tb_transaction_master_detail").append(tmpl);
						refreschChecked();
						
						updateSummary();
						updateCalculateChange();
						
					}
					
					function validateForm(){
						var result 				= true;
						var timerNotification 	= 15000;
						
						//Validar Fecha
						if($("#txtDate").val() == ""){
							fnShowNotification("Establecer Fecha al Documento","error",timerNotification);
							result = false;
						}
						return result;
					}
					
					function refreschChecked(){
						$("[type='checkbox'], [type='radio'], [type='file'], select").not('.toggle, .select2, .multiselect').uniform();
						//$('.txtDebit').mask('000,000.00', {reverse: true});
						//$('.txtCredit').mask('000,000.00', {reverse: true});
						$('.txt-numeric').mask('000,000.00', {reverse: true});
					}
					
				</script>
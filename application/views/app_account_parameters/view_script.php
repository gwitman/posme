				<!-- ./ page heading -->
				<script>					
					
					$(document).ready(function(){					
						
						//Evento Agregar el Usuario
						$(document).on("click","#btnAcept",function(){
								fnShowConfirm("Confirmar..","Desea Actualizar Los Parametros",function(){
								fnWaitOpen();
								$.ajax({									
									cache       : false,
									dataType    : 'json',
									type        : 'POST',
									url  		: "<?php echo site_url(); ?>app_accounting_parameters/save",
									data 		: { 
										accountUtility 			:$("#txtUtility").val() , 
										accountUtilityAcumulate :$("#txtUtilityAcumulate").val() , 
										currencyDefault			:$("#txtCurrency").val(), 
										formulateUtility 		:$("#txtFormulateUtility").val(), 
										currencyReport 			:$("#txtCurrencyReport").val(), 
										accountCapital 			:$("#txtAccountCapital").val(), 
										accountPasivo 			:$("#txtAccountPasivo").val(), 
										accountActivo 			:$("#txtAccountActivo").val(), 
										accountIngreso 			:$("#txtAccountIngreso").val(), 
										accountGastos 			:$("#txtAccountGastos").val(), 
										accountCostos 			:$("#txtAccountCostos").val(),
										accountResult 			:$("#txtAccountResult").val(),
										exchangePurchase		:$("#txtExchangePurchase").val(),
										exchangeSales			:$("#txtExchangeSales").val(),										
										razon001 				:$("#txtRazon001").val(),
										razon002 				:$("#txtRazon002").val(),
										razon003 				:$("#txtRazon003").val(),
										razon004 				:$("#txtRazon004").val(),
										razon005 				:$("#txtRazon005").val(),
										razon006 				:$("#txtRazon006").val()
									},
									success:function(data){
										console.info("complete delete success");
										fnWaitClose();
										if(data.error){
											fnShowNotification(data.message,"error");
										}
										else{
											fnShowNotification("success","success");
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
						
					});
					
				</script>
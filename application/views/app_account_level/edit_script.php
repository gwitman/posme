				<script>	 				
					
					$(document).ready(function(){
						
						//Comando Guardar
						$(document).on("click","#btnAcept",function(){
								fnWaitOpen();
								$( "#form-new-account-level" ).attr("method","POST");
								$( "#form-new-account-level" ).attr("action","<?php echo site_url(); ?>app_accounting_level/save/edit");
								$( "#form-new-account-level" ).submit();
						});
						//Comando para regresar a la lista
						$(document).on("click","#btnBack",function(){
							fnWaitOpen();
						});
						//Comando Eliminar
						$(document).on("click","#btnDelete",function(){							
							fnShowConfirm("Confirmar..","Desea eliminar este Registro...",function(){
								fnWaitOpen();
								$.ajax({									
									cache       : false,
									dataType    : 'json',
									type        : 'POST',
									url  		: "<?php echo site_url(); ?>app_accounting_level/delete",
									data 		: {companyID : <?php echo $objAccountLevel->companyID;?>, accountLevelID : <?php echo $objAccountLevel->accountLevelID;?>  },
									success:function(data){
										fnWaitClose();
										console.info("complete delete success");
										if(data.error){
											fnShowNotification(data.message,"error");
										}
										else{
											window.location = "<?php echo site_url(); ?>app_accounting_level/index";
										}
									},
									error:function(xhr,data){	
										fnWaitClose();
										console.info("complete delete error");									
										fnShowNotification("Error 505","error");
									}
								});
							});
						});
						
						
					});
					
				</script>
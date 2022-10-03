				<script>	 				
					
					$(document).ready(function(){
						//Regresar a la lista
						$(document).on("click","#btnBack",function(){
								fnWaitOpen();
						});
						//Comando Guardar
						$(document).on("click","#btnAcept",function(){
								fnWaitOpen();
								$( "#form-new-account-level" ).attr("method","POST");
								$( "#form-new-account-level" ).attr("action","<?php echo site_url(); ?>app_config_noti/save/edit");
								$( "#form-new-account-level" ).submit();
						});
						//Comando Eliminar
						$(document).on("click","#btnDelete",function(){							
							fnShowConfirm("Confirmar..","Desea eliminar este Registro...",function(){
								fnWaitOpen();
								$.ajax({									
									cache       : false,
									dataType    : 'json',
									type        : 'POST',
									url  		: "<?php echo site_url(); ?>app_config_noti/delete",
									data 		: {rememberID : <?php echo $objRemember->rememberID;?> },
									success:function(data){
										console.info("complete delete success");
										fnWaitClose();
										if(data.error){
											fnShowNotification(data.message,"error");
										}
										else{
											window.location = "<?php echo site_url(); ?>app_config_noti/index";
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
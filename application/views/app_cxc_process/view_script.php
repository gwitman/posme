				<!-- ./ page heading -->
				<script>			
					$(document).ready(function(){					
						$(document).on("click","#btnAceptUploadFile",function(){
							fnShowConfirm("Confirmar..","Desea ejecutar el proceso de carga a SIN Riesgo...",function(){
									fnWaitOpen();
									$.ajax({
										cache       : false,
										dataType    : 'json',
										type        : 'POST',
										url  		: "<?php echo site_url(); ?>app_cxc_process/uploadDataSinRiesgo",
										data 		: { execute : 1 },
										success:function(data){
											fnWaitClose();
											console.info("complete delete success");
											if(data.error){
												fnShowNotification(data.message,"error");
											}
											else{
												fnShowNotification(data.message,"success");
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
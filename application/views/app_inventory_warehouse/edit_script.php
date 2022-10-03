				<script>	 				
					
					$(document).ready(function(){
						//Regresar
						$(document).on("click","#btnBack",function(){
								fnWaitOpen();
						});
						//Comando Guardar
						$(document).on("click","#btnAcept",function(){
								fnWaitOpen();
								$( "#form-new-account-type" ).attr("method","POST");
								$( "#form-new-account-type" ).attr("action","<?php echo site_url(); ?>app_inventory_warehouse/save/edit");
								$( "#form-new-account-type" ).submit();
						});
						//Comando Eliminar
						$(document).on("click","#btnDelete",function(){							
							fnShowConfirm("Confirmar..","Desea eliminar este Registro...",function(){
								fnWaitOpen();
								$.ajax({									
									cache       : false,
									dataType    : 'json',
									type        : 'POST',
									url  		: "<?php echo site_url(); ?>app_inventory_warehouse/delete",
									data 		: {companyID : <?php echo $objWarehouse->companyID;?>, warehouseID : <?php echo $objWarehouse->warehouseID;?>  },
									success:function(data){
										console.info("complete delete success");
										fnWaitClose();
										if(data.error){
											fnShowNotification(data.message,"error");
										}
										else{
											window.location = "<?php echo site_url(); ?>app_inventory_warehouse/index";
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
				<!-- ./ page heading -->
				<script>					
					
					$(document).ready(function(){					
						//Regresar a la lista
						$(document).on("click","#btnBack",function(){
								fnWaitOpen();
						});
						//Evento Agregar el Usuario
						$(document).on("click","#btnAcept",function(){
								fnWaitOpen();
								$( "#form-new-account-type" ).attr("method","POST");
								$( "#form-new-account-type" ).attr("action","<?php echo site_url(); ?>app_collection_manager/save/new");
								$( "#form-new-account-type" ).submit();
						});
						
						//Buscar Colagorador
						$(document).on("click","#btnSearchEmployee",function(){
							var url_request = "<?php echo site_url(); ?>core_view/showviewbyname/<?php echo $componentEmployeeID; ?>/onCompleteEmployee/SELECCIONAR_EMPLOYEE/empty";
							window.open(url_request,"MsgWindow","width=900,height=450");
							window.onCompleteEmployee = onCompleteEmployee; 
						});
						//Eliminar Colaborador
						$(document).on("click","#btnClearEmployee",function(){
									$("#txtEmployeeID").val("");
									$("#txtEmployeeDescription").val("");
						});
						function onCompleteEmployee(objResponse){
							console.info("CALL onCompleteEmployee");
							
							$("#txtEmployeeID").val(objResponse[2]);
							$("#txtEmployeeDescription").val(objResponse[3] + " / " + objResponse[4]);
							
						}
						
						//Buscar Cliente
						$(document).on("click","#btnSearchCustomer",function(){
							var url_request = "<?php echo site_url(); ?>core_view/showviewbyname/<?php echo $componentCustomerID; ?>/onCompleteCustomer/SELECCIONAR_CLIENTES_BILLING/empty";
							window.open(url_request,"MsgWindow","width=900,height=450");
							window.onCompleteCustomer = onCompleteCustomer; 
						});
						//Eliminar Cliente
						$(document).on("click","#btnClearCustomer",function(){
									$("#txtCustomerID").val("");
									$("#txtCustomerDescription").val("");
						});
						function onCompleteCustomer(objResponse){
							console.info("CALL onCompleteCustomer");
							
							$("#txtCustomerID").val(objResponse[1]);
							$("#txtCustomerDescription").val(objResponse[2] + " / " + objResponse[3]); 
							
						}
						
						
						
					});
					
				</script>
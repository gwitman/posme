				<!-- ./ page heading -->
				<script>					
					
					$(document).ready(function(){					
						//Evento regresar a la lista principal
						$(document).on("click","#btnBack",function(){
							fnWaitOpen();
						});
						//Evento Agregar el Usuario
						$(document).on("click","#btnAcept",function(){
								fnWaitOpen();
								$( "#form-new-account" ).attr("method","POST");
								$( "#form-new-account" ).attr("action","<?php echo site_url(); ?>app_accounting_account/save/new");
								$( "#form-new-account" ).submit();
						});
						
					});
					
				</script>
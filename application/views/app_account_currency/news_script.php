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

								$( "#form-new-account-level" ).attr("method","POST");

								$( "#form-new-account-level" ).attr("action","<?php echo site_url(); ?>app_accounting_currency/save/new");

								$( "#form-new-account-level" ).submit();

						});

						

					});

					

				</script>
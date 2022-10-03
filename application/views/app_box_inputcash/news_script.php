				<!-- ./ page heading -->
				<script>
					$(document).ready(function(){						 
						 $('#txtDate').datepicker({format:"yyyy-mm-dd"});
						 $('#txtDate').val(moment().format("YYYY-MM-DD"));	
						 $("#txtDate").datepicker("update");
						 $('.txt-numeric').mask('000,000.00', {reverse: true});
						 
						
						 //Regresar a la lista
						$(document).on("click","#btnBack",function(){
								fnWaitOpen();
						});
						
						//Evento Agregar el Usuario
						$(document).on("click","#btnAcept",function(){
								$( "#form-new-invoice" ).attr("method","POST");
								$( "#form-new-invoice" ).attr("action","<?php echo site_url(); ?>app_box_inputcash/save/new");
								
								if(validateForm()){
									fnWaitOpen();
									$( "#form-new-invoice" ).submit();
								}
								
						});
						
					});
					
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
				<!-- ./ page heading -->
				<script>						
					var site_url 	  = "<?php echo site_url(); ?>";
					
					$(document).ready(function(){	
						//Inicializar DataPciker
						$('#txtStartOn').datepicker({format:"yyyy-mm-dd"});
						$('#txtEndOn').datepicker({format:"yyyy-mm-dd"});
						
						$('#txtStartOn').val(moment().format("YYYY-MM-DD"));						
						$('#txtEndOn').val(moment().format("YYYY-MM-DD"));
						
						$("#txtStartOn").datepicker("update");
						$("#txtEndOn").datepicker("update");
						
						 //Regresar a la lista
						$(document).on("click","#btnBack",function(){
								fnWaitOpen();
						});
						
						//Guardar la lista de precio
						$(document).on("click","#btnAcept",function(){
								$( "#form-new-invoice-price" ).attr("method","POST");
								$( "#form-new-invoice-price" ).attr("action","<?php echo site_url(); ?>app_invoice_price/save/new");
								
								if(validateForm()){
									fnWaitOpen();
									$( "#form-new-invoice-price" ).submit();
								}
								
						});
						
					});
					function validateForm(){
						var result 				= true;
						var timerNotification 	= 15000;
						
						if (  $('#txtStartOn').val() == "" ||  $('#txtEndOn').val() == "" ) {
							fnShowNotification("Las fechas son obligatorias","error",timerNotification);
							result = false;
						}
						return result;
						
					}
				</script>
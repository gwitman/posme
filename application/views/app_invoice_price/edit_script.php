				<!-- ./ page heading -->
				<script>										
					var site_url 	  = "<?php echo site_url(); ?>";
					
					$(document).ready(function(){	
						//Inicializar DataPciker
						$('#txtStartOn').datepicker({format:"yyyy-mm-dd"});
						$('#txtEndOn').datepicker({format:"yyyy-mm-dd"});						
						
						 //Regresar a la lista
						$(document).on("click","#btnBack",function(){
								fnWaitOpen();
						});
						
						//Eliminar el Documento
						$(document).on("click","#btnDelete",function(){
							fnShowConfirm("Confirmar..","Desea eliminar este Registro...",function(){
								fnWaitOpen();
								$.ajax({									
									cache       : false,
									dataType    : 'json',
									type        : 'POST',
									url  		: "<?php echo site_url(); ?>app_invoice_price/delete",
									data 		: {companyID : <?php echo $objListPrice->companyID;?>, listPriceID : <?php echo $objListPrice->listPriceID;?>   },
									success:function(data){
										console.info("complete delete success");
										fnWaitClose();
										if(data.error){
											fnShowNotification(data.message,"error");
										}
										else{
											window.location = "<?php echo site_url(); ?>app_invoice_price/index";
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
						//Imprimer Lista de Precio
						$(document).on("click","#btnPrinter",function(){
							console.info("call btnPrinter");
							fnWaitOpen();							
							window.location = "<?php echo site_url(); ?>app_invoice_price/viewRegister/listPriceID/<?php echo $objListPrice->listPriceID;?>";
						});
						//Evento Agregar la lista de Precio
						$(document).on("click","#btnAcept",function(){
								$( "#form-new-invoice-price" ).attr("method","POST");
								$( "#form-new-invoice-price" ).attr("action","<?php echo site_url(); ?>app_invoice_price/save/edit");
								
								if(validateForm()){
									fnWaitOpen();
									$( "#form-new-invoice-price" ).submit();
								}
								
						});
						$(document).on("click","#btnClickArchivo",function(){
							window.open("<?php echo site_url()."core_elfinder/index/componentID/".$objComponent->componentID."/componentItemID/".$objListPrice->listPriceID; ?>","blanck");
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
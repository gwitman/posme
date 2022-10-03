				<!-- ./ page heading -->
				<script>				
					$(document).ready(function(){
						
						$(document).on("click","#print-btn-report",function(){
							var documentNumber 			=	$("#txtDocumentNumber").val();
							
							if(!(documentNumber == ""  )){
								fnWaitOpen();
								window.location	= "<?php echo site_url(); ?>app_cxc_report/document_contract/viewReport/true/documentNumber/"+documentNumber;
							}
							else{
								fnShowNotification("Completar los Parametros","error");
							}
						});
					});					
				</script>
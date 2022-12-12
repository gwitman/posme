				<!-- ./ page heading -->
				<script>				
					$(document).ready(function(){
						
						$(document).on("click","#print-btn-report",function(){
							var employeeNumber 			=	$("#txtEmployeeNumber").val();
							
							if(!(employeeNumber == ""  )){
								fnWaitOpen();
								window.location	= "<?php echo site_url(); ?>app_cxc_report/collection_manager/viewReport/true/employeeNumber/"+employeeNumber;
							}
							else{
								fnShowNotification("Completar los Parametros","error");
							}
						});
					});					
				</script>
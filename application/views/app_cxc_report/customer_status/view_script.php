				<!-- ./ page heading -->
				<script>				
					$(document).ready(function(){
					
						$(document).on("click","#print-btn-report",function(){
							var txtCustomerNumber	=	$("#txtCustomerNumber").val();	
							if(!( txtCustomerNumber == ""  ) ){
								fnWaitOpen();
								window.location	= "<?php echo site_url(); ?>app_cxc_report/customer_status/viewReport/true/customerNumber/"+txtCustomerNumber;
							}
							else{
								fnShowNotification("Completar los Parametros","error");
							}
						});
					});					
				</script>
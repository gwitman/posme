				<!-- ./ page heading -->
				<script>				
					$(document).ready(function(){
						$('#txtStartOn').datepicker({format:"yyyy-mm-dd"});
						$('#txtStartOn').val(moment().format("YYYY-MM-DD"));						 						
						$("#txtStartOn").datepicker("update");						
						$('#txtEndOn').datepicker({format:"yyyy-mm-dd"});
						$('#txtEndOn').val(moment().format("YYYY-MM-DD"));		
						$("#txtEndOn").datepicker("update");
						
						
						$(document).on("click","#print-btn-report",function(){
							var startOn					=	$("#txtStartOn").val();	
							var endOn					=	$("#txtEndOn").val();	
							if(!( startOn == "" || endOn == ""  ) ){
								fnWaitOpen();
								window.location	= "<?php echo site_url(); ?>app_cxc_report/consulta_sin_riesgo_list/viewReport/true/startOn/"+startOn+"/endOn/"+endOn;
							}
							else{
								fnShowNotification("Completar los Parametros","error");
							}
						});
					});					
				</script>
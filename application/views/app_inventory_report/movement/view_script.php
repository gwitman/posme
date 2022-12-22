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
							var itemID					=	$("#txtItemID").val();	
							if(!( startOn == "" || endOn == "" || itemID == "" ) ){
								fnWaitOpen();
								window.location	= "<?php echo site_url(); ?>app_inventory_report/movement/viewReport/true/startOn/"+startOn+"/endOn/"+endOn+"/itemID/"+itemID;
							}
							else{
								fnShowNotification("Completar los Parametros","error");
							}
						});
					});					
				</script>
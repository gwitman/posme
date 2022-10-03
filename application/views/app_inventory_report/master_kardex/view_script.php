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
							var warehouseID 			=	$("#txtWarehouseID").val();
							
							if(!( startOn == "" || endOn == "" ) ){
								fnWaitOpen();
								window.location	= "<?php echo site_url(); ?>app_inventory_report/master_kardex/viewReport/true/startOn/"+startOn+"/endOn/"+endOn+"/warehouseID/"+warehouseID;
							}
							else{
								fnShowNotification("Completar los Parametros","error");
							}
						});
					});					
				</script>
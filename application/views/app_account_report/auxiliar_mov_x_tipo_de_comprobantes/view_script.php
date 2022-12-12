				<!-- ./ page heading -->
				<script>				
					$(document).ready(function(){
						$('#txtStartOn').datepicker({format:"yyyy-mm-dd"});
						$('#txtEndOn').datepicker({format:"yyyy-mm-dd"});
						
						$(document).on("click","#print-btn-report",function(){
							var txtJournalTypeID 			=	$("#txtJournalTypeID").val();
							var txtStartOn					=	$("#txtStartOn").val();	
							var txtEndOn					=	$("#txtEndOn").val();							
							var excludeSystem				=	$("#excludeSystem").is(':checked') == true? 1 : 0;
							var stringContainer				=	$("#stringContainer").val();
							var classID						=	$("#txtClassID").val();
							if(!(txtJournalTypeID == "" || txtStartOn == "" || txtEndOn == "" ) ){
								fnWaitOpen();
								window.location	= "<?php echo site_url(); ?>app_accounting_report/auxiliar_mov_x_tipo_de_comprobantes/viewReport/true/startOn/"+txtStartOn+"/endOn/"+txtEndOn+"/journalTypeID/"+txtJournalTypeID + "/excludeSystem/"+excludeSystem+"/classID/"+classID+"/stringContainer/"+stringContainer;
							}
							else{
								fnShowNotification("Completar los Parametros","error");
							}
						});						
					});					
				</script>
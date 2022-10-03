				<!-- ./ page heading -->
				<script>				
					$(document).ready(function(){
						$(document).on("click","#print-btn-report",function(){
							var periodID 	=	$("#txtMayorizatePeriod").val();
							var cycleID		=	$("#txtMayorizateCicle").val();	

							if(!(periodID == "" || cycleID == "") ){
								fnWaitOpen();
								window.location	= "<?php echo site_url(); ?>app_accounting_report/presupuestory/viewReport/true/periodID/"+periodID+"/cycleID/"+cycleID;
							}
							else{
								fnShowNotification("Completar los Parametros","error");
							}
						});
						$(document).on("change","#txtMayorizatePeriod",function(){
								fnWaitOpen();
								$.ajax({									
									cache       : false,
									dataType    : 'json',
									type        : 'POST',
									url  		: "<?php echo site_url(); ?>app_accounting_api/getCycle",
									data 		: { componentPeriodID :$("#txtMayorizatePeriod").val() },
									success:function(data){									
										console.info("complete delete success");
										fnWaitClose();
										if(data.error){
											fnShowNotification(data.message,"error");
										}
										else{
											$("#txtMayorizateCicle").html("");
											$.each(data.cycles,function(i,obj){
												$("#txtMayorizateCicle").append("<option value='"+obj.componentCycleID+"'>"+obj.startOnFormat+"</option>")
											});
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
				</script>
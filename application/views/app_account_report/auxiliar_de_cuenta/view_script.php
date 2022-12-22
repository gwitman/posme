				<!-- ./ page heading -->
				<script>				
					$(document).ready(function(){
						$(document).on("click","#print-btn-report",function(){
							var periodID 			=	$("#txtMayorizatePeriod").val();
							var cycleStartID		=	$("#txtMayorizateCicleStart").val();	
							var cycleEndID			=	$("#txtMayorizateCicleEnd").val();	
							var accountID			=	$("#txtAccount").val();	
							if(!(periodID == "" || cycleStartID == "" || cycleEndID == "" || accountID == "") ){
								fnWaitOpen();
								window.location	= "<?php echo site_url(); ?>app_accounting_report/auxiliar_de_cuenta/viewReport/true/periodID/"+periodID+"/cycleStartID/"+cycleStartID+"/cycleEndID/"+cycleEndID+"/accountID/"+accountID;
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
											$("#txtMayorizateCicleStart").html("");
											$("#txtMayorizateCicleEnd").html("");
											$.each(data.cycles,function(i,obj){
												$("#txtMayorizateCicleStart").append("<option value='"+obj.componentCycleID+"'>"+obj.startOnFormat+"</option>")
												$("#txtMayorizateCicleEnd").append("<option value='"+obj.componentCycleID+"'>"+obj.endOnFormat+"</option>")
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
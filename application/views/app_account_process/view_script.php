				<!-- ./ page heading -->
				<script>			
					$(document).ready(function(){					
						/*Proceso Mayorizar Ciclo*/				
						$(document).on("click","#btnAceptMayorizateCycle",function(){
							fnShowConfirm("Confirmar..","Desea Realizar la Mayorizacion de Este Ciclo Contable",function(){
									fnWaitOpen();
									$.ajax({									
										cache       : false,
										dataType    : 'json',
										type        : 'POST',
										url  		: "<?php echo site_url(); ?>app_accounting_process/mayorizateCycle",
										data 		: { componentPeriodID :$("#txtMayorizatePeriod").val() , componentCycleID :$("#txtMayorizateCicle").val()},
										success:function(data){
											fnWaitClose();
											console.info("complete delete success");
											if(data.error){
												fnShowNotification(data.message,"error");
											}
											else{
												console.info(data);
												if(data.result.code == 0)
													fnShowNotification("success","success");
												else
													fnShowNotification("problemas en el proceso de mayorizacion","error");	
											} 
										},
										error:function(xhr,data){	
											fnWaitClose();
											console.info("complete delete error");									
											fnShowNotification("Error 505","error");
										}
									});
							});
						});
						$(document).on("change","#txtMayorizatePeriod",function(){
								fnWaitOpen();
								$.ajax({									
									cache       : false,
									dataType    : 'json',
									type        : 'POST',
									url  		: "<?php echo site_url(); ?>app_accounting_api/getCycleNotClosed",
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
										fnWaitClose();
										console.info("complete delete error");									
										fnShowNotification("Error 505","error");
									}
								});
						});
						/*Proceso Cerrar Ciclo*/
						$(document).on("click","#btnAceptClosedCycle",function(){
							fnShowConfirm("Confirmar..","Desea Realizar el Cierre de Este Ciclo Contable",function(){
									fnWaitOpen();
									$.ajax({									
										cache       : false,
										dataType    : 'json',
										type        : 'POST',
										url  		: "<?php echo site_url(); ?>app_accounting_process/closedCycle",
										data 		: { componentPeriodID :$("#txtClosedPeriod").val() , componentCycleID :$("#txtClosedCicle").val()},
										success:function(data){
											fnWaitClose();
											console.info("complete delete success");
											if(data.error){
												fnShowNotification(data.message,"error");
											}
											else{
												console.info(data);
												if(data.result.code == 0)
													fnShowNotification("success","success");
												else
													fnShowNotification("problemas en el proceso de cierre","error");	
											}
										},
										error:function(xhr,data){	
											fnWaitClose();
											console.info("complete delete error");									
											fnShowNotification("Error 505","error");
										}
									});
							});
						});
						$(document).on("change","#txtClosedPeriod",function(){
								fnWaitOpen();
								$.ajax({									
									cache       : false,
									dataType    : 'json',
									type        : 'POST',
									url  		: "<?php echo site_url(); ?>app_accounting_api/getCycleNotClosed",
									data 		: { componentPeriodID :$("#txtClosedPeriod").val() },
									success:function(data){
										fnWaitClose();
										console.info("complete delete success");
										if(data.error){
											fnShowNotification(data.message,"error");
										}
										else{
											$("#txtClosedCicle").html("");
											$.each(data.cycles,function(i,obj){
												$("#txtClosedCicle").append("<option value='"+obj.componentCycleID+"'>"+obj.startOnFormat+"</option>")
											});
										}
									},
									error:function(xhr,data){	
										fnWaitClose();
										console.info("complete delete error");									
										fnShowNotification("Error 505","error");
									}
								});
						});
						/*Proceso Contabilizar Documento*/
						$(document).on("click","#btnAceptContabilizeDocument",function(){
							fnShowConfirm("Confirmar..","Desea Realizar la Contabilidad de esta Transaccion",function(){
									fnWaitOpen();
									$.ajax({									
										cache       : false,
										dataType    : 'json',
										type        : 'POST',
										url  		: "<?php echo site_url(); ?>app_accounting_process/contabilizateDocument",
										data 		: { transactionID :$("#txtListTransactionID").val() },
										success:function(data){
											fnWaitClose();
											console.info("complete delete success");
											if(data.error){
												fnShowNotification(data.message,"error");
											}
											else{
												if(data.result.code == 0)
													fnShowNotification("success","success");
												else
													fnShowNotification("problemas al contabilizar documentos","error");
											} 
										},
										error:function(xhr,data){	
											fnWaitClose();
											console.info("complete delete error");									
											fnShowNotification("Error 505","error");
										}
									});
							});
						});
						/*Proceso Ejecutar Notificaciones*/
						$(document).on("click","#btnAceptNotificacion",function(){
							fnShowConfirm("Confirmar..","Desea ejecutar las notificaciones...",function(){
									fnWaitOpen();
									$.ajax({									
										cache       : false,
										dataType    : 'json',
										type        : 'POST',
										url  		: "<?php echo site_url(); ?>app_accounting_process/execNotification",
										data 		: { notificationName :$("#txtNotificationProcess").val() },
										success:function(data){
											fnWaitClose();
											console.info("complete delete success");
											if(data.error){
												fnShowNotification(data.message,"error");
											}
											else{
												if(data.result.code == 0)
													fnShowNotification("success","success");
												else
													fnShowNotification("problemas al ejecutar las notificaciones","error");
											} 
										},
										error:function(xhr,data){	
											fnWaitClose();
											console.info("complete delete error");									
											fnShowNotification("Error 505","error");
										}
									});
							});
						});
						/*Proceso Ejecutar Limpiar Notificaciones*/
						$(document).on("click",".clear_notification",function(e,event){
							var tagID_ =	$(this).data("notificationid");
							fnShowConfirm("Confirmar..","Desea ejecutar la limpieza...",function(){
									fnWaitOpen();
									$.ajax({									
										cache       : false,
										dataType    : 'json',
										type        : 'POST',
										url  		: "<?php echo site_url(); ?>app_accounting_process/clearNotification",
										data 		: { tagID : tagID_ },
										success:function(data){
											fnWaitClose();
											console.info("complete clear success");
											if(data.error){
												fnShowNotification(data.message,"error");
											}
											else{
												if(data.result.code == 0)
													fnShowNotification("success","success");
												else
													fnShowNotification("problemas al ejecutar la limpieza","error");
											} 
										},
										error:function(xhr,data){	
											fnWaitClose();
											console.info("complete delete error");									
											fnShowNotification("Error 505","error");
										}
									});
							});
						});
					});
					
					$(document).on("change","#txtTipoCambioPeriod",function(){
								fnWaitOpen();
								$.ajax({									
									cache       : true,
									dataType    : 'json',
									type        : 'POST',
									url  		: "<?php echo site_url(); ?>app_accounting_api/getCycleNotClosed",
									data 		: { componentPeriodID :$("#txtTipoCambioPeriod").val() },
									success:function(data){
										console.info("complete delete success");
										fnWaitClose();
										if(data.error){
											fnShowNotification(data.message,"error");
										}
										else{
											$("#txtTipoCambioCicle").html("");
											$.each(data.cycles,function(i,obj){
												$("#txtTipoCambioCicle").append("<option value='"+obj.componentCycleID+"'>"+obj.startOnFormat+"</option>")
											});
										}
									},
									error:function(xhr,data){	
										fnWaitClose();
										console.info("complete delete error");									
										fnShowNotification("Error 505","error");
									}
								});
					});
					$(document).on("click","#btnDownloadTipoCambio",function(){
							fnShowConfirm("Confirmar..","Desea actualizar el Tipo de Cambio",function(){
									fnWaitOpen();
									$.ajax({									
										cache       : false,
										dataType    : 'json',
										type        : 'POST',
										url  		: "<?php echo site_url(); ?>app_accounting_process/downloadTipoCambio",
										data 		: { componentPeriodID :$("#txtTipoCambioPeriod").val() , componentCycleID :$("#txtTipoCambioCicle").val()},
										success:function(data){
											fnWaitClose();
											console.info("complete delete success");
											if(data.error){
												fnShowNotification(data.message,"error");
											}
											else{
												console.info(data);
												if(data.result.code == 0)
													fnShowNotification("success","success");
												else
													fnShowNotification("problemas en el proceso de tipo de cambio","error");	
											} 
										},
										error:function(xhr,data){	
											fnWaitClose();
											console.info("complete delete error");									
											fnShowNotification("Error 505","error");
										}
									});
							});
					});
				</script>
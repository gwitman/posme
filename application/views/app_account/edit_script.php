				<script>	 				
					$(document).ready(function() {
						//define chart clolors ( you maybe add more colors if you want or flot will add it automatic )
						var chartColours 	= ['#62aeef', '#d8605f', '#72c380', '#6f7a8a', '#f7cb38', '#5a8022', '#2c7282'];
						var tickSize 		= [1, "month"];
						
						if($(".chart-line").length) {
							//graph options
							var options = {
								grid: {
									show				: true,
									aboveData			: true,
									color				: "#3f3f3f" ,
									labelMargin			: 5,
									axisMargin			: 0, 
									borderWidth			: 0,
									borderColor			:null,
									minBorderMargin		: 5 ,
									clickable			: true, 
									hoverable			: true,
									autoHighlight		: true,
									mouseActiveRadius	: 20
								},
								series: {
									lines: {
										show		: true,
										fill		: true,
										lineWidth	: 2,
										steps		: false
										},
									points: {show:false}
								},
								legend: { 
									position	: "ne", 
									margin		: [0,-25], 
									noColumns	: 0,
									labelBoxBorderColor: null,
									labelFormatter: function(label, series) {										
										return label+'&nbsp;&nbsp;';
									},
									width: 40,
									height: 1
								},
								yaxis: {min: 0 },
								xaxis: {
									mode		: "time",	
									minTickSize : tickSize,									
									timeformat	: "%m/%y"
								},
								colors: chartColours,
								shadowSize:1,
								tooltip: true,
								tooltipOpts: {
									content: "%s : %y.0",										
									xDateFormat: "%d/%m",
									shifts: {
										x: -30,
										y: -50
									},
									defaultTheme: false
								}
							};   

							$.ajax({									
								cache       : false,
								dataType    : 'json',
								type        : 'POST',
								url  		: "<?php echo site_url(); ?>app_accounting_api/getHistoryBalanceByAccount",
								data 		: {companyID : <?php echo $objAccount->companyID;?>, accountID : <?php echo $objAccount->accountID;?>  },
								success:function(data){
									
									var d1 		= [];																	
									$.each(data.data,function(i,obj){			
										console.info(obj);
										d1.push([moment(obj.startOnCycle),parseFloat(obj.balanceEnd)]); 										
									});
			
									$.plot($(".chart-line"), [ 
										{
											label	: "Saldo", 
											data	: d1,
											lines	: {fillColor: "#f3faff"}
										}

									], options);
									
								},
								error:function(xhr,data){	
									fnShowNotification("error en la grafica","error");
								}
							});
							
						}
					});	
						
					
					$(document).ready(function(){
						
						//Evento Regresar a la lista
						$(document).on("click","#btnBack",function(){
							fnWaitOpen();
						});
						//Comando Guardar
						$(document).on("click","#btnAcept",function(){
								fnWaitOpen();
								$( "#form-new-account" ).attr("method","POST");
								$( "#form-new-account" ).attr("action","<?php echo site_url(); ?>app_accounting_account/save/edit");
								$( "#form-new-account" ).submit();
						});
						//Comando Eliminar
						$(document).on("click","#btnDelete",function(){							
							fnShowConfirm("Confirmar..","Desea eliminar este Registro...",function(){
								fnWaitOpen();
								$.ajax({									
									cache       : false,
									dataType    : 'json',
									type        : 'POST',
									url  		: "<?php echo site_url(); ?>app_accounting_account/delete",
									data 		: {companyID : <?php echo $objAccount->companyID;?>, accountID : <?php echo $objAccount->accountID;?>  },
									success:function(data){
										fnWaitClose();
										console.info("complete delete success");
										if(data.error){
											fnShowNotification(data.message,"error");
										}
										else{
											window.location = "<?php echo site_url(); ?>app_accounting_account/index";
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
					
				</script>
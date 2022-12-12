					<script>
					var timerNotification = 15000;
					$(document).ready(function(){					
						$('#txtEndOn').datepicker({format:"yyyy-mm-dd"});
						$('#txtStartOn').datepicker({format:"yyyy-mm-dd"});
						$('.txtCycleEndOn').datepicker({format:"yyyy-mm-dd"});
						$('.txtCycleStartOn').datepicker({format:"yyyy-mm-dd"});	
						
						//Regresar a la lista
						$(document).on("click","#btnBack",function(){
							fnWaitOpen();
						});
						//Evento Agregar el Usuario
						$(document).on("click","#btnAcept",function(){						
								if(validation()){
									fnWaitOpen();
									$( "#form-new-account-period" ).attr("method","POST");
									$( "#form-new-account-period" ).attr("action","<?php echo site_url(); ?>app_accounting_period/save/edit");
									$( "#form-new-account-period" ).submit();
								}
						});
						//Comando Eliminar
						$(document).on("click","#btnDelete",function(){							
							fnShowConfirm("Confirmar..","Desea eliminar este Registro...",function(){
								fnWaitOpen();
								$.ajax({									
									cache       : false,
									dataType    : 'json',
									type        : 'POST',
									url  		: "<?php echo site_url(); ?>app_accounting_period/delete",
									data 		: {companyID : <?php echo $objComponentPeriod->companyID;?>, componentID : <?php echo $objComponentPeriod->componentID;?> , componentPeriodID : <?php echo $objComponentPeriod->componentPeriodID;?>  },
									success:function(data){
										console.info("complete delete success");
										fnWaitClose();
										if(data.error){
											fnShowNotification(data.message,"error");
										}
										else{
											window.location = "<?php echo site_url(); ?>app_accounting_period/index";
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
						$(document).on("click","#btnDeleteCycle",function(){
								$(".txtCycleIsActive").each(function(i,obj){
									if($(obj).attr("checked") == "checked"){
										$(obj).parents("tr").first().remove();
									}
								});
						});
						$(document).on("click","#btnNewCycle",function(){
								var tmpl = $($("#tmpl_row_cycle").html());								
								var id 	 = tmpl.find("#txtCycleWorkflowStageID").attr("id") + moment().format("ms")
								tmpl.find("#txtCycleWorkflowStageID").attr("id",id);
								
								if($("#"+id).length > 0 )
									return;
									
								$("#tbody_detail").append(tmpl);
								$('.txtCycleEndOn').datepicker({format:"yyyy-mm-dd"});
								$('.txtCycleEndOn').val(moment().format("YYYY-MM-DD"));	
								
								$('.txtCycleStartOn').datepicker({format:"yyyy-mm-dd"});	
								$('.txtCycleStartOn').val(moment().format("YYYY-MM-DD"));	
							
								var fechaInicial = $("#txtStartOn").val();
								var fechaFinal 	 = $("#txtEndOn").val();
								$("#tbody_detail .txtCycleStartOn").each(function(index,obj){									
									$(obj).val(fechaInicial);
									fechaInicial = moment(fechaInicial,"YYYY-MM-DD").add("months",1,0).format("YYYY-MM-DD");
									fechaFinal = moment(fechaInicial,"YYYY-MM-DD").add("days",-1,0).format("YYYY-MM-DD");
									$($("#tbody_detail .txtCycleEndOn")[index]).val(fechaFinal);
									
								});[
]								
								$("#"+id).select2({placeholder: "seleccionar..."}); 
								$("[type='checkbox'], [type='radio'], [type='file'], select").not('.toggle, .select2, .multiselect').uniform();
								
						});
					});
					function validation(){
						var result = true;
						//Periodo validar Fecha Inicial y Final 
						if(moment($('#txtStartOn').val(),"YYYY-MM-DD") >= moment($('#txtEndOn').val(),"YYYY-MM-DD") )
						{
							fnShowNotification("Fecha inicial del periodo; mayor que la fecha final del periodo","error",timerNotification);
							result = false;
						}
						//Ciclo Validar la Fecha Inicial de los Ciclos
						$('.txtCycleStartOn').each(function(i,obj){
							if(moment($('#txtStartOn').val(),"YYYY-MM-DD") > moment($(obj).val(),"YYYY-MM-DD")){
								fnShowNotification("Fecha inicial del ciclo; menor que fecha inicial del periodo","error",timerNotification);
								result = false;
							}
						});
						
						//Ciclos Validar la Fecha Final de los Ciclos
						$('.txtCycleEndOn').each(function(i,obj){
							if(moment($('#txtEndOn').val(),"YYYY-MM-DD") < moment($(obj).val(),"YYYY-MM-DD")){
								fnShowNotification("Fecha final del ciclo; mayor que fecha final del periodo","error",timerNotification);
								result = false;
							}
						});
						
						//Validar Ciclos Solapados						
						var tmp1;
						var tmp2;
						var tmp3;
						$(".row_cycle").each(function(i,obj){
							 
							 tmp2 = moment($(obj).find(".txtCycleStartOn").first().val(),"YYYY-MM-DD");
							 tmp3 = moment($(obj).find(".txtCycleEndOn").first().val(),"YYYY-MM-DD");
							 
							 if(tmp2 == tmp3){
								fnShowNotification("Fecha inicial; fecha final no pueden ser iguales","error",timerNotification);
								result = false;
							 }
							 else if(tmp1 == undefined && tmp2 < tmp3)
							 {
								
							 }
							 else if((tmp1 != undefined) && !(tmp1 < tmp2 && tmp2 < tmp3)){
								fnShowNotification("Ciclos Solapados","error",timerNotification);
								result = false;
							 }								 
							 tmp1 = tmp3;							 
						});
						//Validar Estado
						$("select.select2statusID").each(function(i,obj){
							if($(obj).val() == ""){
								fnShowNotification("Ciclos Sin Estado","error",timerNotification);
								result = false;							
							}
						});
						//Validar Estado Periodo
						$("select.select2statusIDPeriod").each(function(i,obj){
							if($(obj).val() == ""){
								fnShowNotification("Periodo Sin Estado","error",timerNotification);
								result = false;							
							}
						});
						return result;
											
					}
				</script>
				
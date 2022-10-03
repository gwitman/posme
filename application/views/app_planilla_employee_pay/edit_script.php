				<!-- ./ page heading -->
				<script>
					$(document).ready(function(){						 
						 
						
						
						 //Regresar a la lista
						$(document).on("click","#btnBack",function(){
								fnWaitOpen();
						});
						
						//Evento Agregar el Usuario
						$(document).on("click","#btnAcept",function(){
								$( "#form-new-invoice" ).attr("method","POST");
								$( "#form-new-invoice" ).attr("action","<?php echo site_url(); ?>app_planilla_employee_pay/save/edit");
								
								if(validateForm()){
									fnWaitOpen();
									$( "#form-new-invoice" ).submit();
								}
								
						});
						//Nueva factura
						$(document).on("click","#btnNewShare",function(){
							var url_request 			= "<?php echo site_url(); ?>core_view/showviewbyname/<?php echo $objComponentEmployee->componentID; ?>/onCompleteNewShare/SELECCIONAR_EMPLOYEE/empty";
							window.open(url_request,"MsgWindow","width=1585,height=795");
							window.onCompleteNewShare 	= onCompleteNewShare; 
						});
						//Eliminar factura
						$(document).on("click","#btnDeleteShare",function(){
							console.info("btnDeleteShare");
							$(".txtCheckedIsActive").each(function(i,obj){
									if($(obj).attr("checked") == "checked"){
										$(obj).parents("tr").first().remove();
									}
							});	
							
						});
						$(document).on("change","input.txtDetailShare",function(){
							updateSummary();
						});
					});
					
					function updateSummary(){
						console.info("updateSummary");
						var total = 0;
						
						$("#body_tb_transaction_master_detail tr").each(function(index,obj){
							/*salario*/
							/*comision*/
							/*-adelanto*/
							/*neto*/
							var salario 	= fnFormatFloat($(obj).find("#txtSalario").val());
							var comision 	= fnFormatFloat($(obj).find("#txtComision").val());
							var adelanto 	= fnFormatFloat($(obj).find("#txtAdelantos").val());
							var neto 		= salario + comision - adelanto;
							
							$(obj).find("#txtNeto").val(fnFormatNumber(neto,2));
							total 			= total + neto;
						});
						
						$("#txtTotal").val(fnFormatNumber(total,2));
					}
					function onCompleteNewShare(objResponse){
						console.info("CALL onCompleteNewShare");	

						var objRow 						= {};
						objRow.checked 					= false;
						objRow.txtCalendarDetailID 		= 0;
						objRow.txtEmployeeID			= objResponse[2];	/*companyID*/
						objRow.name 					= objResponse[3] + "/" + objResponse[4];	/*entityID*/
						
						//Validar si esta el item
						for(var i = 0 ; i < $(".classDetailItem").length; i++){
								var x  = $(($(".classDetailItem")[i])).val(); 
								var y  = objRow.txtEmployeeID;
								
								if(x == y){
									fnShowNotification("El Colaborador ya existe en el detalle","error");
									return;
								}
								
						}
						
						
						var tmpl = $($("#tmpl_row_document").html());
						tmpl.find("#txtCalendarDetailID").attr("value",objRow.txtCalendarDetailID);
						tmpl.find("#txtEmployeeID").attr("value",objRow.txtEmployeeID);
						tmpl.find("#txtDocument").text(objRow.name);
						
						$("#body_tb_transaction_master_detail").append(tmpl);
						refreschChecked();						
						updateSummary();
					}
					function fnCompleteGetDocumentInfo(data){
						console.info("fnCompleteGetDocumentInfo");
						console.info(data);
						fnWaitClose();
						
						var row = $(".classDetailItem[value="+data.customerCreditDocumentID+"]").parent().parent();
						
						
						row.find("#txtDetailTransactionDetailCancel").attr("value",data.cancel);
						row.find("#txtDetailTransactionDetailDay").attr("value",data.diario);
						row.find("#txtDetailTransactionDetailShare").attr("value",data.cuota);
						row.find("#txtOfCancel").text(data.cancel);
						row.find("#txtOfDay").text(data.diario);
						row.find("#txtOfShare").text(data.cuota);
					}
					function validateForm(){
						var result 				= true;
						var timerNotification 	= 15000;
						
						//Validar Nombre
						if($("#txtNombre").val() == ""){
							fnShowNotification("Ingrese el Nombre","error",timerNotification);
							result = false;
						}
						return result;
					}
					
					function refreschChecked(){
						$("[type='checkbox'], [type='radio'], [type='file'], select").not('.toggle, .select2, .multiselect').uniform();						
					}
					
				</script>
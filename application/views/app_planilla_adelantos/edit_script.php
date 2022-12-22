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
								$( "#form-new-invoice" ).attr("action","<?php echo site_url(); ?>app_planilla_adelantos/save/edit");
								
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
							var neto 		= fnFormatFloat($(obj).find("#txtNeto").val());
							
							$(obj).find("#txtNeto").val(fnFormatNumber(neto,2));
							total 			= total + neto;
						});
						
						$("#txtTotal").val(fnFormatNumber(total,2));
					}
					function onCompleteNewShare(objResponse){
						console.info("CALL onCompleteNewShare");	
						var objRow 									= {};
						objRow.checked 								= false;
						objRow.txtTransactionMasterDetailID 		= 0;
						objRow.txtEmployeeID						= objResponse[2];	/*companyID*/
						objRow.name 								= objResponse[3] + "/" + objResponse[4];	/*entityID*/
						
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
						tmpl.find("#txtTransactionMasterDetailID").attr("value",objRow.txtTransactionMasterDetailID);
						tmpl.find("#txtEmployeeID").attr("value",objRow.txtEmployeeID);
						tmpl.find("#txtDocument").text(objRow.name);
						
						$("#body_tb_transaction_master_detail").append(tmpl);
						refreschChecked();						
						updateSummary();
					}
					
					function validateForm(){
						var result 				= true;
						var timerNotification 	= 15000;
						
						return result;
					}
					
					function refreschChecked(){
						$("[type='checkbox'], [type='radio'], [type='file'], select").not('.toggle, .select2, .multiselect').uniform();						
					}
					
				</script>
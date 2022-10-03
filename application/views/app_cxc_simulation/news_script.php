				<!-- ./ page heading -->
				<script>
					var objDetail 	= {};
					//$('.txt-numeric').mask('000,000.00', {reverse: true});
					
					//Obtener informacion del cliente
					fnWaitOpen();
					$.ajax({									
						cache       : false,
						dataType    : 'json',
						type        : 'POST',
						url  		: "<?php echo site_url(); ?>app_invoice_api/getLineByCustomer",
						data 		: {entityID : 0  },
						success		: fnCompleteGetCustomerCreditLine,
						error:function(xhr,data){	
							console.info("complete data error");									
							fnWaitClose();
							fnShowNotification("Error 505","error");
						}
					});
					
					$(document).ready(function(){	
						objDetail = $("#tb_transaction_master_detail").dataTable({
							"bPaginate"		: false,
							"bFilter"		: false,
							"bSort"			: false,
							"bInfo"			: false,
							"bAutoWidth"	: false							
						});
						
						
						//Buscar el Cliente
						$(document).on("click","#btnSearchCustomer",function(){
							var url_request = "<?php echo site_url(); ?>core_view/showviewbyname/<?php echo $objComponentCustomer->componentID; ?>/onCompleteCustomer/SELECCIONAR_CLIENTES_BILLING/empty";
							window.open(url_request,"MsgWindow","width=900,height=450");
							window.onCompleteCustomer = onCompleteCustomer; 
						});						
					
						//Eliminar Cliente
						$(document).on("click","#btnClearCustomer",function(){
									$("#txtCustomerID").val("");
									$("#txtCustomerDescription").val("");
						});
						//Cambios
						$(document).on("change","#txtAmount,#txtInterestYear,#txtNumberPay,#txtFrecuencyID,#txtPlanID",function(){
							fnClearData();
						});						
						//Evento Agregar el Usuario
						$(document).on("click","#btnAcept",function(){
								$( "#form-new-invoice" ).attr("method","POST");
								$( "#form-new-invoice" ).attr("action","<?php echo site_url(); ?>app_cxc_simulation/save/edit");
								
								if(validateForm()){
									fnWaitOpen();
									$( "#form-new-invoice" ).submit();
								}
						});		
						
						//Calcular amortization
						$(document).on("click","#btnCalculate",function(){
								var plantID 		= $("#txtPlanID").val(); 		//tipo de plan (frances,aleman,americano,constante)
								var frecuencyID 	= $("#txtFrecuencyID").val(); 	//frecuencia de pago (semanal,quincenal,mensual,semenstral)
								var numberPay		= $("#txtNumberPay").val();
								var interestYear	= $("#txtInterestYear").val();
								var amount			= $("#txtAmount").val();
								fnClearData();
								
								fnWaitOpen();
								$.ajax({									
									cache       : false,
									dataType    : 'json',
									type        : 'POST',
									url  		: "<?php echo site_url(); ?>app_cxc_api/getSimulateAmortization",
									data 		: {plantID :plantID , frecuencyID : frecuencyID,numberPay :numberPay,interestYear : interestYear, amount : amount },
									success		: fnAmortizationComplete,
									error:function(xhr,data){	
										console.info("complete data error");									
										fnWaitClose();
										fnShowNotification("Error 505","error");
									}
								});
					
					
					
						});
						
					});
					function fnAmortizationComplete(data){
						console.info("complete success data");
						fnWaitClose();
						console.info(data);	
						var total_capital 	= 0;
						var total_interest 	= 0;
						
						$.each(data.array.detail,function(i,obj){
								
								var objRow = obj;
								objDetail.fnAddData([
									objRow.pnum,
									objRow.date,
									objRow.saldoInicial,
									objRow.interes,
									objRow.principal,
									objRow.cuota,
									objRow.saldo
								]);
								total_capital	= total_capital + parseFloat(objRow.principal);
								total_interest	= total_interest + parseFloat(objRow.interes);
						});
						
						
						$("#txtCapitalTotal").val(fnFormatNumber(total_capital,2));
						$("#txtInterestTotal").val(fnFormatNumber(total_interest,2));
						$("#txtTotal").val(fnFormatNumber(total_interest + total_capital,2));
					}
					function onCompleteCustomer(objResponse){
						console.info("CALL onCompleteCustomer");
					
						var entityID = objResponse[1];
						$("#txtCustomerID").val(objResponse[1]);
						$("#txtCustomerDescription").val(objResponse[2] + " " + objResponse[3] + " / " + objResponse[4]);
					
						fnClearData();
						//Obtener Informacion de Credito
						fnWaitOpen();
						$.ajax({									
							cache       : false,
							dataType    : 'json',
							type        : 'POST',
							url  		: "<?php echo site_url(); ?>app_invoice_api/getLineByCustomer",
							data 		: {entityID : entityID  },
							success		: fnCompleteGetCustomerCreditLine,
							error:function(xhr,data){	
								console.info("complete data error");									
								fnWaitClose();
								fnShowNotification("Error 505","error");
							}
						});
						
						
					}
					
					
					function validateForm(){
						var result 				= true;
						var timerNotification 	= 15000;
						
						//Validar Cliente
						if($("#txtCustomerID").val() == ""){
							fnShowNotification("Seleccionar el Cliente","error",timerNotification);
							result = false;
						}
						return result;
					}
					
					
					function refreschChecked(){
						$("[type='checkbox'], [type='radio'], [type='file'], select").not('.toggle, .select2, .multiselect').uniform();
						//$('.txtDebit').mask('000,000.00', {reverse: true});
						//$('.txtCredit').mask('000,000.00', {reverse: true});
					}
					function fnClearData(){
							console.info("fnClearData");
							objDetail.fnClearTable();
					}
					
					function fnCompleteGetCustomerCreditLine (data)
					{
						console.info("complete success data");
						fnWaitClose();
						tmpInfoClient = data;
						console.info(tmpInfoClient);
						
						//Renderizar Line Credit
						$("#txtCustomerCreditLineID").html("");
						$("#txtCustomerCreditLineID").val("");
						if(tmpInfoClient.objListCustomerCreditLine != null)
						for(var i = 0; i< tmpInfoClient.objListCustomerCreditLine.length;i++){
							if(i==0){
								$("#txtCustomerCreditLineID").append("<option value='"+tmpInfoClient.objListCustomerCreditLine[i].customerCreditLineID+"' selected>"+ tmpInfoClient.objListCustomerCreditLine[i].accountNumber +"</option>");
								$("#txtCustomerCreditLineID").val(tmpInfoClient.objListCustomerCreditLine[i].customerCreditLineID);
							}
							else
								$("#txtCustomerCreditLineID").append("<option  value='"+tmpInfoClient.objListCustomerCreditLine[i].customerCreditLineID+"'>"+ tmpInfoClient.objListCustomerCreditLine[i].accountNumber +"</option>");
						}
						
						//Refresh Control
						$("#txtCustomerCreditLineID").select2();
						refreschChecked();
					}
				</script>
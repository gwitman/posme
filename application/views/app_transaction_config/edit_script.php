				<script>	
					var ListElementCausal			= {};
					var ListElementProfileDetail	= {};
					
					var objRowListCausal 			= {};
					var objRowListProfileDetal		= {};
					
					var transactionCausalID 		= 0;
					var transactionProfileDetailID  = 0;
					
					$(document).ready(function(){
						ListElementCausal = $("#ListElementCausal").dataTable({
							"bPaginate"		: false,
							"bLengthChange"	: false,
							"bFilter"		: false,
							"bSort"			: true,
							"bInfo"			: false,
							"bAutoWidth"	: true							
						});
						ListElementProfileDetail = $("#ListElementConfig").dataTable({
							"bPaginate"		: false,
							"bLengthChange"	: false,
							"bFilter"		: false,
							"bSort"			: true,
							"bInfo"			: false,
							"bAutoWidth"	: true							
						});
						//Regresar
						$(document).on("click","#btnBack",function(){
								fnWaitOpen();
						});
						//Comando Guardar
						$(document).on("click","#btnAcept",function(){
								fnWaitOpen();
								$( "#form-edit-document" ).attr("method","POST");
								$( "#form-edit-document" ).attr("action","<?php echo site_url(); ?>app_transaction_config/save/edit");
								$( "#form-edit-document" ).submit();
						});						
						//Abrir popup para agregar causal
						$(document).on("click","#btnNewDetailCausal",function(){
								window.open("<?php echo site_url(); ?>app_transaction_config/add_causal","MsgWindow","width=650,height=500");
								window.parentNewCausal = parentNewCausal;
						});
						//Seleccionar un Perfil Detail
						$(document).on("click","#tbody_detail_config tr",function(event){	
								objRowListProfileDetal 			= this;
								transactionProfileDetailID 		= $(objRowListProfileDetal).find("input").first().val();
								fnTableSelectedRow(this,event);														
						});
						//Seleccionar un Causal
						$(document).on("click","#tbody_detail_causal tr",function(event){		
								objRowListCausal 			= this;
								var transactionCausalID_ 	= $(objRowListCausal).find("input").first().val();
								ListElementProfileDetail.fnClearTable();
								fnTableSelectedRow(this,event);
								
								if(transactionCausalID_ == "0" || transactionCausalID_ == undefined )
								return;
								
								transactionCausalID 		= transactionCausalID_;
								fnWaitOpen();
								$.ajax({									
									cache       : false,
									dataType    : 'json',
									type        : 'POST',
									data 		: {transactionID: <?php echo $objTransaction->transactionID; ?>,transactionCausalID : transactionCausalID_},
									url  		: "<?php echo site_url(); ?>app_transaction_config/apiGetInforCausal",							
									success:function(data){										
										fnWaitClose();
										fillProfileDetail(data);
									},
									error:function(xhr,data){									
										fnShowNotification(data.message,"error");
										fnWaitClose();
									}
								});
						});
						//Eliminar un Causal
						$(document).on("click","#btnDeleteDetailCausal",function(){	
							fnShowConfirm("Confirmar..","Desea eliminar el Causal Seleccionado ...",function(){								
								ListElementCausal.fnDeleteRow(objRowListCausal);
							});							
						});
						//Eliminar un Perfil Detail
						$(document).on("click","#btnDeleteDetailConfig",function(){	
							if(transactionProfileDetailID == undefined)
							return;
							
							fnShowConfirm("Confirmar..","Desea eliminar esta configuracion...",function(){								
								fnWaitOpen();
								$.ajax({									
									cache       : false,
									dataType    : 'json',
									type        : 'POST',
									data 		: {transactionID: <?php echo $objTransaction->transactionID; ?>,transactionCausalID : transactionCausalID,profileDetailID : transactionProfileDetailID},
									url  		: "<?php echo site_url(); ?>app_transaction_config/apiDeleteProfileDetail",							
									success:function(data){										
										fnWaitClose();
										ListElementProfileDetail.fnDeleteRow(objRowListProfileDetal);
									},
									error:function(xhr,data){									
										fnShowNotification(data.message,"error");
										fnWaitClose();
									}
								});
							});							
						});
						//Agregar Perfil Detail
						$(document).on("click","#btnNewDetailConfig",function(){
							var objRequest 						= {};
							objRequest.centerCostID 			= $("#txtCC").val();
							objRequest.centerCostDescription	= $("#txtCC option:selected").text();
							objRequest.accountID	 			= $("#txtAccountID").val();
							objRequest.accountDescription 		= $("#txtAccountID option:selected").text();
							objRequest.sign	 					= $("#txtSignProfileDetail").val();
							objRequest.conceptID				= $("#txtConceptID").val();
							objRequest.conceptDescription		= $("#txtConceptID option:selected").text();
							objRequest.transactionCausalID		= transactionCausalID;
							objRequest.transactionID 			= <?php echo $objTransaction->transactionID; ?>;
							
							if(objRequest.conceptID == "" || objRequest.sign == "" || objRequest.accountID == "" || objRequest.transactionCausalID == 0 ||  objRequest.transactionCausalID == undefined )
							return;
							
							fnWaitOpen();
							$.ajax({									
								cache       : false,
								dataType    : 'json',
								type        : 'POST',
								data 		: objRequest,
								url  		: "<?php echo site_url(); ?>app_transaction_config/apiInsertProfileDetail",							
								success:function(data){										
									fnWaitClose();
									fillProfile(data);
								},
								error:function(xhr,data){									
									fnShowNotification(data.message,"error");
									fnWaitClose();
								}
							});
								
						});
						
					});
					function parentNewCausal(data){	
							var tmpl0 = 	$.tmpl(
											'<span>'+
											'${txtBranchDescription}'+	
											'<input type="hidden" name="txtCausalID[]" value="0" />'+
											'<input type="hidden" name="txtCausalBranchID[]" value="${txtBranchID}" />'+
											'<input type="hidden" name="txtCausalName[]" value="${txtName}" />'+
											'<input type="hidden" name="txtCausalIsDefault[]" value="${txtIsDefault}" />'+
											'<input type="hidden" name="txtCausalWarehouseSourceID[]" value="${txtWarehouseSourceID}" />'+
											'<input type="hidden" name="txtCausalWarehouseTargetID[]" value="${txtWarehouseTargetID}" />'+
											'</span>',data).html();	
							var tmpl1 =		$.tmpl('<a href="#" >${txtName}</a>',data).text();
							var tmpl2 =		$.tmpl('<a href="#" >${txtIsDefault}</a>',data).text();
							var tmpl3 =		$.tmpl('<a href="#" >${txtWarehouseSourceDescription}</a>',data).text();
							var tmpl4 =		$.tmpl('<a href="#" >${txtWarehouseTargetDescription}</a>',data).text();
							
							ListElementCausal.fnAddData([tmpl0,tmpl1,tmpl2,tmpl3,tmpl4]);
							
					} 
					//Fill Detalle del Perfil Contable
					function fillProfileDetail(data){
							if(data.error == true)
							return;
							
							$("#txtCausalDescription").val(data.objTransactionCausal.name);
							if (data.objListTransactionProfileDetail == null)
							return;							
							
							$.each(data.objListTransactionProfileDetail , function(i,obj){
								var tmpl0 = 	$.tmpl(
												'<span>'+
												'${conceptDescription}'+												
												'<input type="hidden" value="${profileDetailID}" />'+											
												'</span>',obj).html();													
								var tmpl1 =		(obj.sign == "D") ? $.tmpl('<a href="#" >${accountDescription}</a>',obj).text() : "";
								var tmpl2 =		(obj.sign == "D") ? $.tmpl('<a href="#" >${centerCostDescription}</a>',obj).text() : "";
								var tmpl3 =		(obj.sign == "C") ? $.tmpl('<a href="#" >${accountDescription}</a>',obj).text() : "";
								var tmpl4 =		(obj.sign == "C") ? $.tmpl('<a href="#" >${centerCostDescription}</a>',obj).text() : "";
								ListElementProfileDetail.fnAddData([tmpl0,tmpl1,tmpl2,tmpl3,tmpl4]);
							});
							
					}
					function fillProfile(data){
							if(data.error == true)
							return;
							
							var tmpl0 = 	$.tmpl(
											'<span>'+
											'${conceptDescription}'+												
											'<input type="hidden" value="${profileDetailID}" />'+											
											'</span>',data.objProfileDetail).html();	
							var tmpl1 =		(data.objProfileDetail.sign == "D") ? $.tmpl('<a href="#" >${accountDescription}</a>',data.objProfileDetail).text() : "";
							var tmpl2 =		(data.objProfileDetail.sign == "D") ? $.tmpl('<a href="#" >${centerCostDescription}</a>',data.objProfileDetail).text() : "";
							var tmpl3 =		(data.objProfileDetail.sign == "C") ? $.tmpl('<a href="#" >${accountDescription}</a>',data.objProfileDetail).text() : "";
							var tmpl4 =		(data.objProfileDetail.sign == "C") ? $.tmpl('<a href="#" >${centerCostDescription}</a>',data.objProfileDetail).text() : "";
							
							
							ListElementProfileDetail.fnAddData([tmpl0,tmpl1,tmpl2,tmpl3,tmpl4]);
							
					}
				</script>
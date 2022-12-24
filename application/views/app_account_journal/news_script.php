				<!-- ./ page heading -->
				<script>					
					var objTableDetailJournal = {};
					$(document).ready(function(){					
						 $('#txtDate').datepicker({format:"yyyy-mm-dd"});
						 $('#txtDate').val(moment().format("YYYY-MM-DD"));	
						 $('#txtTotalCredit').mask('000,000.00', {reverse: true});
						 $('#txtTotalDebit').mask('000,000.00', {reverse: true});
						
						
						$(document).on("click","#btnOpenTemplated",function(){
							var url_request 				= "<?php echo site_url(); ?>core_view/showviewbyname/<?php echo $componentAccountID; ?>/onCompleteSelectTemplate/SELECCIONAR_TEMPLATED/true/empty";
							window.open(url_request,"MsgWindow","width=900,height=450");
							window.onCompleteSelectTemplate 	= onCompleteSelectTemplate; 
						});
						
						function onCompleteSelectTemplate(objResponse){
							console.info("CALL onCompleteSelectTemplate");
							var journalEntryIDTemplated 	= objResponse[0];		
							$( "#txtTemplatedNumber").val(journalEntryIDTemplated);
							$( "#form-new-account-journal" ).attr("method","POST");
							$( "#form-new-account-journal" ).attr("action","<?php echo site_url(); ?>app_accounting_journal/save/new");							
							$( "#form-new-account-journal" ).submit();							
						}
					
						
						
					
						objTableDetailJournal = $("#tb_journal_entry_detail").dataTable({
							"bPaginate"		: false,
							"bFilter"		: false,
							"bSort"			: false,
							"bInfo"			: false,
							"bAutoWidth"	: false,
							"aoColumnDefs": [ 
										{
											"aTargets"		: [ 0 ],//checked
											"mRender"		: function ( data, type, full ) {
												if (data == false)
												return '<input type="checkbox"  class="classCheckedDetail"  value="0" ></span>';
												else
												return '<input type="checkbox"  class="classCheckedDetail" checked="checked" value="0" ></span>';
											}
										},
										{
											"aTargets"		: [ 1 ],//journalEntryDetailID
											"bVisible"  	: true,
											"sClass" 		: "hidden",
											"bSearchable"	: false,
											"mRender"		: function ( data, type, full ) {
												return '<input type="hidden" value="'+data+'" name="txtJournalEntryDetailID[]" />';
											}
										},
										{
											"aTargets"		: [ 2 ],//accountID
											"bVisible"		: true,
											"sClass" 		: "hidden",
											"bSearchable"	: false,
											"mRender"		: function ( data, type, full ) {
												return '<input type="hidden" value="'+data+'" name="txtAccountID[]" />';
											}
										},
										{
											"aTargets"		: [ 3 ],//classID
											"bVisible"		: true,
											"sClass" 		: "hidden",
											"bSearchable"	: false,
											"mRender"		: function ( data, type, full ) {
												return '<input type="hidden" value="'+data+'" name="txtClassID[]" />';
											}
										},
										{
											"aTargets"		: [ 6 ],//Debito
											"mRender"		: function ( data, type, full ) {
												return '<input type="text" class="col-lg-12 txtDebit txt-numeric" value="'+data+'" name="txtDebit[]" />';
											}
										},
										{
											"aTargets"		: [ 7 ],//Credito
											"mRender"		: function ( data, type, full ) {
												return '<input type="text" class="col-lg-12 txtCredit txt-numeric" value="'+data+'" name="txtCredit[]" />';
											}
										}
							]							
						});
						
						
						 //Regresar a la lista
						$(document).on("click","#btnBack",function(){
								fnWaitOpen();
						});
						//Evento Agregar el Usuario
						$(document).on("click","#btnAcept",function(){
								$( "#form-new-account-journal" ).attr("method","POST");
								$( "#form-new-account-journal" ).attr("action","<?php echo site_url(); ?>app_accounting_journal/save/new");
								
								if(validateForm()){
									fnWaitOpen();
									$( "#form-new-account-journal" ).submit();
								}
								
						});
						$(document).on("click","#btnNewDetailJournal",function(){
							var url_request 				= "<?php echo site_url(); ?>core_view/showviewbyname/<?php echo $componentAccountID; ?>/onCompleteNewAccount/SELECCIONAR_CUENTA/true/empty";
							window.open(url_request,"MsgWindow","width=900,height=450");
							window.onCompleteNewAccount 	= onCompleteNewAccount; 
						});
						$(document).on("click","#btnDeleteDetailJournal",function(){
									var listRow = objTableDetailJournal.fnGetData();							
									var length 	= listRow.length;
									var i 		= 0;
									var j 		= 0;
									while (i< length ){
										if(listRow[i][0] == true){
										objTableDetailJournal.fnDeleteRow( j,null,true );
										j--;
										}
										i++;
										j++;
									}
							
								handler_changeDebit();
								handler_changeCredit();
						});
						$(document).on("change","input.txtDebit",function(){
								handler_changeDebit();
						});
						$(document).on("change","input.txtCredit",function(){
								handler_changeCredit();
						});
					});
					//Seleccionar Checke 
					$(document).on("click",".classCheckedDetail",function(){
						var objrow_ = $(this).parent().parent().parent().parent()[0];
						var objind_ = objTableDetailJournal.fnGetPosition(objrow_);
						var objdat_ = objTableDetailJournal.fnGetData(objind_);								
						objTableDetailJournal.fnUpdate( !objdat_[0], objind_, 0 );
						refreschChecked();
					});
					function handler_changeDebit(){
							var totalDebit = 0;
							$("input.txtDebit").each(function(i,obj){
								if($(obj).val() != "")
								totalDebit = totalDebit + fnFormatFloat($(obj).val());
							});
							$("#txtTotalDebit").val(fnFormatNumber(totalDebit,2));
					}
					function handler_changeCredit(){
							var totalCredit = 0;
							$("input.txtCredit").each(function(i,obj){
								if($(obj).val() != "")
								totalCredit = totalCredit + fnFormatFloat($(obj).val()) ;
							});
							$("#txtTotalCredit").val(fnFormatNumber(totalCredit,2));
					}
					function onCompleteNewAccount(objResponse){
						console.info("CALL onCompleteNewAccount");
						var objRow 						= {};
						objRow.checked 					= false;						
						objRow.journalEntryDetailID 	= 0;
						objRow.accountID				= objResponse[1];
						objRow.classID					= 0;
						objRow.accountName				= objResponse[2] + " " + objResponse[3];
						objRow.className				= "";
						objRow.debit 					= fnFormatNumber(0,2);
						objRow.credit 					= fnFormatNumber(0,2);
						
						//Berificar que el Item ya esta agregado 
						if(jLinq.from(objTableDetailJournal.fnGetData()).where(function(obj){ return obj[2] == objRow.accountID;}).select().length > 0 ){
							fnShowNotification("El Item ya esta agregado","error");
							return;
						}
						
						objTableDetailJournal.fnAddData([objRow.checked,objRow.journalEntryDetailID,objRow.accountID,objRow.classID,objRow.accountName,objRow.className,objRow.debit,objRow.credit]);
						refreschChecked();
						
					}
					function validateForm(){
						var result 				= true;
						var timerNotification 	= 15000;
						
						//Validar Fecha
						if($("#txtDate").val() == ""){
							fnShowNotification("Establecer Fecha al Documento","error",timerNotification);
							result = false;
						}
						//Validar Beneficiario
						if($("#txtEntryName").val()==""){
							fnShowNotification("Establecer Beneficiario al Documento","error",timerNotification);
							result = false;
						}
						//Validar Estado
						if($("#txtStatusID").val() == ""){
							fnShowNotification("Establecer Estado al Documento","error",timerNotification);
							result = false;
						}
						//Validar Tipo
						if($("#txtJournalType").val() == ""){
							fnShowNotification("Establecer Tipo al Documento","error",timerNotification);
							result = false;
						}
						//Validar Moneda
						if($("#txtCurrencyID").val() == ""){
							fnShowNotification("Establecer Moneda al Documento","error",timerNotification);
							result = false;
						}
						
						//Validar Detalle
						//
						///////////////////////////////////////////////
						//Validar Creditos
						if($("#txtTotalCredit").val() == "" || $("#txtTotalCredit").val() == "0" ){
							fnShowNotification("Establecer Los creditos al Documento","error",timerNotification);
							result = false;
						}
						//Validar Debitos
						if($("#txtTotalDebit").val() == "" || $("#txtTotalDebit").val() == "0"){
							fnShowNotification("Establecer Los debitos al Documento","error",timerNotification);
							result = false;
						} 
						//Validar Cuentas del Comprobantes
						if(objTableDetailJournal.fnGetData().length == 0){
							fnShowNotification("Establecer Cuentas a los Movimientos del Comprobante","error",timerNotification);
							result = false;
						};
						
						//Validar Comprobante
						if(	$("#txtTotalDebit").val() != $("#txtTotalCredit").val() ){
							fnShowNotification("El documento no esta cuadrado","error",timerNotification);
							result = false;
						}
						
						return result;
					}
					function refreschChecked(){
						$("[type='checkbox'], [type='radio'], [type='file'], select").not('.toggle, .select2, .multiselect').uniform();
						$('.txtDebit').mask('000,000.00', {reverse: true});
						$('.txtCredit').mask('000,000.00', {reverse: true});
						
						$('#txtTotalCredit').mask('000,000.00', {reverse: true});
						$('#txtTotalDebit').mask('000,000.00', {reverse: true});
					}
				</script>
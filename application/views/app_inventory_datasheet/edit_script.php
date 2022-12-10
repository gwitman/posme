				<!-- ./ page heading -->
				<script>		
					var objItemDataSheetDetail 	= {};					
					var objDataSource 			= [];
					var objDataSource 			= JSON.parse('<?php echo json_encode($objItemDataSheetDetail); ?>');	
					var objTmpDataSource 		= [];
					if(objDataSource != null){
						for(var i = 0 ; i < objDataSource.length;i++){							
							//Rellenar Datos
							objTmpDataSource.push([
								0,								
								objDataSource[i].itemDataSheetID,
								objDataSource[i].itemID,
								objDataSource[i].itemDataSheetDetailID,
								0 , /*tipo de relacion */	
								objDataSource[i].itemNumber,
								objDataSource[i].name,
								'Unidad',
								fnFormatNumber(objDataSource[i].quantity,2), /*cantidad */
								0,
								0
							]);
						}
					}
					
					$(document).ready(function(){
						//Regresar a la lista
						$(document).on("click","#btnBack",function(){
								fnWaitOpen();
						});
						//Evento Agregar el Usuario
						$(document).on("click","#btnAcept",function(){
								$( "#form-new-account-journal" ).attr("method","POST");
								$( "#form-new-account-journal" ).attr("action","<?php echo site_url(); ?>app_inventory_datasheet/save/edit"); 
								
								if(validateForm()){
									fnWaitOpen();
									$( "#form-new-account-journal" ).submit();
								}
								
						});
						objItemDataSheetDetail = $("#tb_item_data_sheet_detail").dataTable({
							"bPaginate"		: false,
							"bFilter"		: false,
							"bSort"			: false,
							"bInfo"			: false,
							"aaData"		: objTmpDataSource,
							"bAutoWidth"	: false,
							"aoColumnDefs": [ 
										{
											"aTargets"	: [ 0 ],//checked
											"mRender"	: function ( data, type, full ) {
												if (data == false)
												return '<input type="checkbox"  class="classCheckedDetail"  value="0" ></span>';
												else
												return '<input type="checkbox"  class="classCheckedDetail" checked="checked" value="0" ></span>';
											}
										},
										{
											"aTargets"		: [ 1 ],//itemDataSheetID
											"bVisible"		: true,
											"sClass" 		: "hidden",
											"bSearchable"	: false,
											"mRender"		: function ( data, type, full ) {
												return '<input type="hidden" value="'+data+'" name="txtDetailItemDataSheetID[]" />';
											}
										},
										{
											"aTargets"		: [2],//itemID
											"bVisible"  	: true,
											"sClass" 		: "hidden",
											"bSearchable"	: false,
											"mRender"		: function ( data, type, full ) {
												return '<input type="hidden" value="'+data+'" name="txtDetailItemID[]" />';
											}
										},
										{
											"aTargets"		: [3],//itemDataSheetDetailID
											"bVisible"  	: true,
											"sClass" 		: "hidden",
											"bSearchable"	: false,
											"mRender"		: function ( data, type, full ) {
												return '<input type="hidden" value="'+data+'" name="txtDetailItemDataSheetDetailID[]" />';
											}
										},
										{
											"aTargets"		: [4],//itemRelatedID
											"bVisible"  	: true,
											"sClass" 		: "hidden",
											"bSearchable"	: false,
											"mRender"		: function ( data, type, full ) {
												return '<input type="hidden" value="'+data+'" name="txtDetailItemRelatedID[]" />';
											}
										},
										{
											"aTargets"	: [ 8 ],//cantidad
											"mRender"	: function ( data, type, full ) {
												return '<input type="text" class="col-lg-12 txtDetailQuantity txt-numeric" value="'+data+'" name="txtDetailQuantity[]" />';
											}
										}
							]							
						});
						
						//Buscar el Receta
						$(document).on("click","#txtSearchItemID",function(){
							var url_request = "<?php echo site_url(); ?>core_view/showviewbyname/<?php echo $componentItemID; ?>/onCompleteItemReceta/SELECCIONAR_ITEM_TO_RECETA/empty";
							window.open(url_request,"MsgWindow","width=900,height=450");
							window.onCompleteItemReceta = onCompleteItemReceta; 
						});
						//Eliminar Receta
						$(document).on("click","#txtClearItemID",function(){
									$("#txtItemID").val("");
									$("#txtItemIDDescription").val("");
						});
						
						//Agregar Item al Detalle
						$(document).on("click","#btnNewDetail",function(){
							var timerNotification 	= 15000;
							
							//Producto
							if($("#txtItemID").val()==""){
								fnShowNotification("Seleccione el producto","error",timerNotification);
								return;
							}
							
							var url_request 		= "<?php echo site_url(); ?>core_view/showviewbyname/<?php echo $componentItemID; ?>/onCompleteItem/SELECCIONAR_ITEM_TO_RECETA/empty";
							window.open(url_request,"MsgWindow","width=900,height=450");
							window.onCompleteItem 	= onCompleteItem; 
							
						});
						
						//Eliminar Item del Detalle
						$(document).on("click","#btnDeleteDetail",function(){
							var listRow = objItemDataSheetDetail.fnGetData();							
							var length 	= listRow.length;
							var i 		= 0;
							var j 		= 0;
							while (i< length ){
								if(listRow[i][0] == true){
								objItemDataSheetDetail.fnDeleteRow( j,null,true );
								j--;
								}
								i++;
								j++;
							}
						});
						//Cambio en la cantidades
						$(document).on("blur",".txtDetailQuantity",function(){
							var objrow_ = $(this).parent().parent()[0];
							var objind_ = objItemDataSheetDetail.fnGetPosition(objrow_);
							var objdat_ = objItemDataSheetDetail.fnGetData(objind_);								
							objItemDataSheetDetail.fnUpdate( $(this).val(), objind_, 8 );
							refreschChecked();
						})
						//Seleccionar Checke 
						$(document).on("click",".classCheckedDetail",function(){
							var objrow_ = $(this).parent().parent().parent().parent()[0];
							var objind_ = objItemDataSheetDetail.fnGetPosition(objrow_);
							var objdat_ = objItemDataSheetDetail.fnGetData(objind_);								
							objItemDataSheetDetail.fnUpdate( !objdat_[0], objind_, 0 );
							refreschChecked();
						});
						
						refreschChecked();
						
						
					});
					function validateForm(){
						var result 				= true;
						var timerNotification 	= 15000;
						
						//Nombre
						if($("#txtName").val()==""){
							fnShowNotification("El nombre no puede estar vacio","error",timerNotification);
							result = false;
						}
						//Validar Estado
						if($("#txtStatusID").val() == ""){
							fnShowNotification("Establecer Estado","error",timerNotification);
							result = false;
						}						
						return result;
					}
					function onCompleteItem(objResponse){
						console.info("CALL onCompleteItem");
						var objRow 						= {};
						debugger;
						objRow.checked 					= false;						
						objRow.itemDataSheetID			= 0;
						objRow.itemID 					= objResponse[0];
						objRow.itemDataSheetDetailID 	= 0;
						objRow.itemRelatedID 			= 0;
						objRow.itemNumber 				= objResponse[1];
						objRow.itemName 				= objResponse[2];
						objRow.um 						= objResponse[3];
						objRow.quantity 				= 0;
						objRow.cost 					= objResponse[4];
						objRow.cc 						= 0;
						
						
						
						//Berificar que el Item ya esta agregado 
						if(jLinq.from(objItemDataSheetDetail.fnGetData()).where(function(obj){ return obj[2] == objRow.itemID;}).select().length > 0 ){
							fnShowNotification("El Item ya esta agregado","error");
							return;
						}
						
						objItemDataSheetDetail.fnAddData([
							objRow.checked,
							objRow.itemDataSheetID,
							objRow.itemID,
							objRow.itemDataSheetDetailID,
							objRow.itemRelatedID,
							objRow.itemNumber,
							objRow.itemName,
							objRow.um,
							objRow.quantity,
							objRow.cost,
							objRow.cc]);
							
						refreschChecked();
						
					}
					function onCompleteItemReceta(objResponse){
						console.info("CALL onCompleteItemReceta");
						
						$("#txtItemID").val(objResponse[0]);
						$("#txtItemIDDescription").val(objResponse[1] + " / " + objResponse[2] );
									
					}
					//Refresh
					function refreschChecked(){
						$("[type='checkbox'], [type='radio'], [type='file'], select").not('.toggle, .select2, .multiselect').uniform();
						$('.txtDetailQuantity').mask('000,000.00', {reverse: true});
					}
				</script>
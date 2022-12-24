				<!-- ./ page heading -->
				<script>
					var site_url 	  = "<?php echo site_url(); ?>";
					
					$(document).ready(function(){	
						
						 //Regresar a la lista
						$(document).on("click","#btnBack",function(){
								fnWaitOpen();
						});
						
						//Evento Agregar el Usuario
						$(document).on("click","#btnAcept",function(){
								$( "#form-new-afx-fixedassent" ).attr("method","POST");
								$( "#form-new-afx-fixedassent" ).attr("action","<?php echo site_url(); ?>app_afx_fixedassent/save/new");
								
								if(validateForm()){
									fnWaitOpen();
									$( "#form-new-afx-fixedassent" ).submit();
								}
								
						});
						//Buscar el Asignado A
						$(document).on("click","#btnSearchEmployee",function(){
							var url_request = "<?php echo site_url(); ?>core_view/showviewbyname/<?php echo $componentEmployeeID; ?>/onCompleteEmployee/SELECCIONAR_EMPLOYEE/true/empty";
							window.open(url_request,"MsgWindow","width=900,height=450");
							window.onCompleteEmployee = onCompleteEmployee; 
						});
						//Eliminar Asignado A
						$(document).on("click","#btnClearEmployee",function(){
									$("#txtAsignedEmployeeID").val("");
									$("#txtAsignedEmployeeDescripcion").val("");
						});
						
					});
					function onCompleteEmployee(objResponse){
						console.info("CALL onCompleteEmployee");
						
						$("#txtAsignedEmployeeID").val(objResponse[2]);
						$("#txtAsignedEmployeeDescripcion").val(objResponse[3] + " / " + objResponse[4]);
						
					}
					function validateForm(){
						var result 				= true;
						var timerNotification 	= 15000;
						
						//Categoria
						if($("#txtCategoryID").val() == ""){
							fnShowNotification("Seleccionar la Categoria","error",timerNotification);
							result = false;
						}
						//Tipo
						if($("#txtTypeID").val() == ""){
							fnShowNotification("Seleccionar el Tipo","error",timerNotification);
							result = false;
						}
						//Nombre
						if($("#txtName").val() == ""){
							fnShowNotification("Escribir el Nombre","error",timerNotification);
							result = false;
						}
						
						return result;
						
					}
				</script>
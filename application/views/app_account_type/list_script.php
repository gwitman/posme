<script>
	$(document).ready(function(){		
		$(document).on("click","#btnView",function(){
			window.open("<?php echo site_url(); ?>core_view/chooseview/"+componentID,"MsgWindow","width=900,height=450");
			window.fn_aceptCallback = fn_aceptCallback; 
		});		
		$(document).on("click","#btnEdit",function(){
		
			if(objRowTableListView != undefined){
				fnWaitOpen();
				var data 		= objTableListView.fnGetData(objRowTableListView);			
				window.location	= "<?php echo site_url(); ?>app_accounting_type/edit/companyID/"+data[0]+"/accountTypeID/"+data[1];
			}
			else{
				fnShowNotification("Seleccionar el Registro...","error");
			}
			
		});  
		$(document).on("click","#btnEliminar",function(){
		
			if(objRowTableListView != undefined){
				var data 		= objTableListView.fnGetData(objRowTableListView);				
				fnShowConfirm("Confirmar..","Desea eliminar este Registro...",function(){
					fnWaitOpen();
					$.ajax({									
						cache       : false,
						dataType    : 'json',
						type        : 'POST',
						url  		: "<?php echo site_url(); ?>app_accounting_type/delete",
						data 		: {companyID : data[0], accountTypeID :data[1]},
						success:function(data){
							fnWaitClose();
							console.info("complete delete success");
							if(data.error){
								fnShowNotification(data.message,"error");
							}
							else{				
								fnShowNotification("success","success");
								objTableListView.fnDeleteRow(objRowTableListView);
							}
						},
						error:function(xhr,data){	
							fnWaitClose();
							console.info("complete delete error");									
							fnShowNotification("Error 505","error");
						}
					});
				});
			}
			else{
				fnShowNotification("Seleccionar el Registro...","error");
			}
		});
		$(document).on("click","#btnNuevo",function(){
			fnWaitOpen();
			window.location	= "<?php echo site_url(); ?>app_accounting_type/add.aspx";
		});
	});
	
	function fn_aceptCallback(data){
			var dataViewID 	= data[0];
			window.location = "../../app_accounting_type/index/"+dataViewID;   
	}					
</script>
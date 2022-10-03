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
				window.location	= "<?php echo site_url(); ?>core_user/edit/companyID/"+data[0]+"/branchID/"+data[1]+"/userID/"+data[2];
			}
			else{
				fnShowNotification("Seleccionar el Registro...","error");
			}
			
		});  
		$(document).on("click","#btnEliminar",function(){
		
			if(objRowTableListView != undefined){
				var data 		= objTableListView.fnGetData(objRowTableListView);				
				fnShowConfirm("Confirmar..","Desea eliminar este Usuario...",function(){
					fnWaitOpen();
					$.ajax({									
						cache       : false,
						dataType    : 'json',
						type        : 'POST',
						url  		: "<?php echo site_url(); ?>core_user/delete",
						data 		: {companyID : data[0], branchID :data[1], userID : data[2] },
						success:function(data){
							console.info("complete delete success");
							fnWaitClose();
							if(data.error){
								fnShowNotification(data.message,"error");
							}
							else{				
								fnShowNotification("success","success");
								objTableListView.fnDeleteRow(objRowTableListView);
							}
						},
						error:function(xhr,data){	
							console.info("complete delete error");									
							fnWaitClose();
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
			window.location	= "<?php echo site_url(); ?>core_user/add.aspx";
		});
	});
	
	function fn_aceptCallback(data){
			var dataViewID 	= data[0];
			window.location = "../../core_user/index/"+dataViewID;   
	}					
</script>
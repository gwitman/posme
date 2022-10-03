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
				window.location	= "<?php echo site_url(); ?>app_box_inputcash/edit/companyID/"+data[0]+"/transactionID/"+data[1]+"/transactionMasterID/"+data[2];
			}
			else{
				fnShowNotification("Seleccionar el Registro...","error");
			}
			
		}); 
		$(document).on("click","#btnSearchTransaction",function(){
					fnWaitOpen();
					$.ajax({									
						cache       : false,
						dataType    : 'json',
						type        : 'POST',
						url  		: "<?php echo site_url(); ?>app_box_inputcash/searchTransactionMaster",
						data 		: {transactionNumber : $("#txtSearchTransaction").val() },
						success:function(data){
							console.info("complete delete success");
							fnWaitClose();
							if(data.error){
								fnShowNotification(data.message,"error");
							}
							else{		
								window.location = "<?php echo site_url(); ?>app_box_inputcash/edit/companyID/"+data.companyID+"/transactionID/"+data.transactionID+"/transactionMasterID/"+data.transactionMasterID;
							}
						},
						error:function(xhr,data){	
							console.info("complete delete error");									
							fnWaitClose();
							fnShowNotification("Error 505","error");
						}
					});
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
						url  		: "<?php echo site_url(); ?>app_box_inputcash/delete",
						data 		: {companyID : data[0], transactionID :data[1],transactionMasterID : data[2] },
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
			window.location	= "<?php echo site_url(); ?>app_box_inputcash/add.aspx";
		});
	});
	
	function fn_aceptCallback(data){
			var dataViewID 	= data[0];
			window.location = "../../app_box_inputcash/index/"+dataViewID;   
	}					
</script>
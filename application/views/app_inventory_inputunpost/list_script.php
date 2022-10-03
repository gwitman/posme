<script>
	$(document).ready(function(){	
		$(document).on("click","#btnView",function(){
			window.open("<?php echo site_url(); ?>core_view/chooseview/"+componentID,"MsgWindow","width=900,height=450");
			window.fn_aceptCallback = fn_aceptCallback; 
		});		
		$(document).on("click","#btnEdit",function(){
		
			if(objRowTableListView != undefined){
				var data 		= objTableListView.fnGetData(objRowTableListView);			
				if (data == null)
				return;
				
				fnWaitOpen();				
				window.location	= "<?php echo site_url(); ?>app_inventory_inputunpost/edit/companyID/"+data[0]+"/transactionID/"+data[1]+"/transactionMasterID/"+data[2];
			}
			else{
				fnShowNotification("Seleccionar el Registro...","error");
			}
			
		}); 
		$(document).on("click","#btnSearchItem",function(){
					fnWaitOpen();
					$.ajax({									
						cache       : false,
						dataType    : 'json',
						type        : 'POST',
						url  		: "<?php echo site_url(); ?>app_inventory_inputunpost/searchTransactionMaster",
						data 		: {transactionNumber : $("#txtSearchItem").val() },
						success:function(data){
							console.info("complete delete success");
							fnWaitClose();
							if(data.error){
								fnShowNotification(data.message,"error");
							}
							else{		
								window.location = "<?php echo site_url(); ?>app_inventory_inputunpost/edit/companyID/"+data.companyID+"/transactionID/"+data.transactionID+"/transactionMasterID/"+data.transactionMasterID;
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
				if(data == null)
				return;
				
				fnShowConfirm("Confirmar..","Desea eliminar este Registro...",function(){
					fnWaitOpen();
					$.ajax({									
						cache       : false,
						dataType    : 'json',
						type        : 'POST',
						url  		: "<?php echo site_url(); ?>app_inventory_inputunpost/delete",
						data 		: {companyID : data[0], transactionID :data[1],transactionMasterID: data[2]},
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
			window.location	= "<?php echo site_url(); ?>app_inventory_inputunpost/add.aspx";
		});
	});
	
	function fn_aceptCallback(data){
			var dataViewID 	= data[0];
			window.location = "../../app_inventory_inputunpost/index/"+dataViewID;   
	}					
</script>
<script>  (function(g,u,i,d,e,s){g[e]=g[e]||[];var f=u.getElementsByTagName(i)[0];var k=u.createElement(i);k.async=true;k.src='https://static.userguiding.com/media/user-guiding-'+s+'-embedded.js';f.parentNode.insertBefore(k,f);if(g[d])return;var ug=g[d]={q:[]};ug.c=function(n){return function(){ug.q.push([n,arguments])};};var m=['previewGuide','finishPreview','track','identify','triggerNps','hideChecklist','launchChecklist'];for(var j=0;j<m.length;j+=1){ug[m[j]]=ug.c(m[j]);}})(window,document,'script','userGuiding','userGuidingLayer','744100086ID'); </script>

<script>
	//window.userGuiding.identify(userId*, attributes)
	  
	// example with attributes
	window.userGuiding.identify('<?php echo get_cookie("email"); ?>', {
	  email: '<?php echo get_cookie("email"); ?>',
	  name: '<?php echo get_cookie("email"); ?>',
	  created_at: 1644403436643,
	});

	// or just send userId without attributes
	//window.userGuiding.identify('1Ax69i57j0j69i60l4')
</script>

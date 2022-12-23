<script >
	$(document).ready(function(){
			$("#btnPopupCancelar").click(function(){window.close();});
			$("#btnPopupAceptar").click(function(){ 
					var data	= {};
					data		= objTableListView.fnGetData(objRowTableListView);
						
					if(objRowTableListView == undefined)
					window.opener.fn_aceptCallback(undefined); 
					else
					window.opener.fn_aceptCallback(data); 

					
					window.close(); 
			});
	});     
</script>
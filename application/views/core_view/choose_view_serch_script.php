<script >
	$(document).ready(function(){
			$("#btnPopupCancelar").click(function(){window.close();});
			$("#btnPopupAceptar").click(function(){ 
			
					
					var data	= {};
					data		= objTableListView.fnGetData(objRowTableListView);
					
					if(objRowTableListView == undefined)
					window.opener.<?php echo $fnCallback;?>(undefined); 
					else
					window.opener.<?php echo $fnCallback;?>(data); 
					window.close(); 
			});
	});     
</script>
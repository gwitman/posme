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



					var viewName = '<?php echo $viewname;?>';
					//window.close(); 
			});

			$(window).unload(function() {
				window.opener.objSearchProductosOpen = false;
			});
	});     
</script>
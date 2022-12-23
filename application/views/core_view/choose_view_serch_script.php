<script >
	$(document).ready(function(){
			$("#btnPopupCancelar").click(function(){window.close();});
			$("#btnPopupAceptar").click(function(){ 
			
					
					var viewName 	= '<?php echo $viewname;?>';
					var data		= {};					
					data			= objTableListView.fnGetData(objRowTableListView);
					
					if(objRowTableListView == undefined)
					window.opener.<?php echo $fnCallback;?>(undefined); 
					else
					window.opener.<?php echo $fnCallback;?>(data); 
			});

			$(window).unload(function() {				
				window.opener.openedSearchWindow = false;
			});
	});     
</script>
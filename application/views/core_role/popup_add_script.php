<script >
	$(document).ready(function(){
			$("#btnPopupCancelar").click(function(){window.close();});
			$("#btnPopupAceptar").click(function(){ 
					var data	= {};
					data.txtElementID				= $("#txtElementID").val();
					data.txtElementIDDescription	= $("#txtElementID option:selected").text();
					data.txtSelectedID				= $("#txtSelectedID").val();
					data.txtSelectedIDDescription	= $("#txtSelectedID option:selected").text();
					data.txtEditedID				= $("#txtEditedID").val();
					data.txtEditedIDDescription		= $("#txtEditedID option:selected").text();
					data.txtDeletedID				= $("#txtDeletedID").val();
					data.txtDeletedIDDescription	= $("#txtDeletedID option:selected").text();
					data.txtInsertedID				= $("#txtInsertedID").val();
					data.txtInsertedIDDescription	= $("#txtInsertedID option:selected").text();
					window.opener.parentNewElement(data);  
					window.close(); 
			});
	});
</script>
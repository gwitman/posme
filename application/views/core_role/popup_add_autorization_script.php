<script >
	$(document).ready(function(){
			$("#btnPopupCancelar").click(function(){window.close();});
			$("#btnPopupAceptar").click(function(){ 
					var data							= {};
					data.txtComponentAutorizationID		= $("#txtComponentAutorizationID").val();
					data.txtComponentAutorizationName	= $("#txtComponentAutorizationID option:selected").text();
					window.opener.parentNewElementAutorization(data);  
					window.close(); 
			});
	});
</script>
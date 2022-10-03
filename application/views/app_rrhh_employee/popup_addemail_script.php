<script >
	$(document).ready(function(){
			$("#btnPopupCancelar").click(function(){window.close();});
			$("#btnPopupAceptar").click(function(){ 
					var data					= {};
					data.txtEmail				= $("#txtEmail").val();
					data.txtIsPrimary			= $("#txtIsPrimary").is(':checked') == true ? 1: 0;
					window.opener.parentNewEmail(data);  
					window.close(); 
			});
	});
</script>
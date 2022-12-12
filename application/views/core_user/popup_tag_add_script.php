<script >
	$(document).ready(function(){
			$("#btnPopupCancelar").click(function(){window.close();});
			$("#btnPopupAceptar").click(function(){ 
					var data								= {};
					data.txtTagID				= $("#txtTagID").val();
					data.txtTagName				= $("#txtTagID option:selected").text();
					window.opener.parentNewNotification(data);  
					window.close(); 
			});
	});
</script>
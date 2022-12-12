<script >
	$(document).ready(function(){
			$("#btnPopupCancelar").click(function(){window.close();});
			$("#btnPopupAceptar").click(function(){ 
					var data						= {};
					data.txtNameConcept				= $("#txtNameConcept").val();
					data.txtValueIn					= $("#txtValueIn").val();
					data.txtValueOut				= $("#txtValueOut").val();
					window.opener.onCompleteConcept(data);  
					window.close(); 
			});
	});
</script>
<script >
	$(document).ready(function(){
			
			$('#txtVencimiento').datepicker({format:"yyyy-mm-dd"});
			
			$("#btnPopupCancelar").click(function(){window.close();});
			$("#btnPopupAceptar").click(function(){ 
					var data					= {};
					data.txtLote				= $("#txtLote").val();
					data.txtVencimiento			= $("#txtVencimiento").val();
					data.txtPrecio1				= $("#txtPrecio1").val();
					data.txtPrecio2				= $("#txtPrecio2").val();
					data.txtPosition			= <?php  echo $positionID; ?>;
					window.opener.onCompleteUpdateMasInformacion(data);  
					window.close(); 
			});
	});
</script>
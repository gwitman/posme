<script >
	$(document).ready(function(){
			$("#btnPopupCancelar").click(function(){window.close();});
			$("#btnPopupAceptar").click(function(){ 
					var data								= {};
					data.txtDetailWarehouseID				= $("#txtWarehouseID").val();
					data.txtDetailWarehouseName				= $("#txtWarehouseID option:selected").text();
					window.opener.parentNewWarehouse(data);  
					window.close(); 
			});
	});
</script>
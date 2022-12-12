<script >
	$(document).ready(function(){
			$("#btnPopupCancelar").click(function(){window.close();});
			$("#btnPopupAceptar").click(function(){ 
					var data								= {};
					data.txtBranchID						= $("#txtBranchID").val();
					data.txtBranchDescription				= $("#txtBranchID option:selected").text();
					data.txtName							= $("#txtName").val();					
					data.txtWarehouseSourceID				= $("#txtWarehouseSourceID").val();
					data.txtWarehouseSourceDescription		= $("#txtWarehouseSourceID option:selected").text();
					data.txtWarehouseTargetID				= $("#txtWarehouseTargetID").val();
					data.txtWarehouseTargetDescription		= $("#txtWarehouseTargetID option:selected").text();
					data.txtIsDefault						= $("#txtIsDefault").prop('checked');				
					
					if(data.txtBranchID == "" || data.txtName == "")
					return;
					
					window.opener.parentNewCausal(data);  
					window.close(); 
			});
	});
</script>
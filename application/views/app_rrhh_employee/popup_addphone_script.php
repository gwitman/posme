<script >
	$(document).ready(function(){
			$('#txtEntityPhoneNumber').mask('+(000) 0000-0000');
			
			$("#btnPopupCancelar").click(function(){window.close();});
			$("#btnPopupAceptar").click(function(){ 
					var data									= {};					
					data.txtEntityPhoneTypeID					= $("#txtEntityPhoneTypeID").val();
					data.txtEntityPhoneTypeDescription			= $("#txtEntityPhoneTypeID option:selected").text();
					data.txtEntityPhoneNumber					= $("#txtEntityPhoneNumber").val();
					data.txtIsPrimary							= $("#txtIsPrimary").is(':checked') == true ? 1: 0;
					window.opener.parentNewPhone(data);  
					window.close(); 					
			});
	});
</script>
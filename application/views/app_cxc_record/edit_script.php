<!-- ./ page heading -->
<script>
	$(document).ready(function(){	
						//Inicializar DataPciker
						$('#txtBirthDate').datepicker({format:"yyyy-mm-dd"});
						 //Regresar a la lista
						$(document).on("click","#btnSearchCustomer",function(){
								fnWaitOpen();
						});
    });
</script>
<script>
	$(document).ready(function(){
			$(document).on("click","#btnAcept",function(){
				fnWaitOpen();
				$( "#form-edit-acount" ).attr("method","POST");
				$( "#form-edit-acount" ).attr("action","<?php echo site_url(); ?>core_acount/edit.aspx");
				$( "#form-edit-acount" ).submit();
			});	
	}); 
</script>
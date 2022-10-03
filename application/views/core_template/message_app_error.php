		<?php
		if(!empty($txtMessage))
		{
		?>
			<script>
				$(document).ready(function(){
					fnShowNotification("<?php	echo $txtMessage;?>","error",30000);
				});
			</script>
		<?php
		}
		?>
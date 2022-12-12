		<?php
		if(!empty($txtMessage))
		{
		?>
			<script>
				$(document).ready(function(){
					fnShowNotification("<?php	echo $txtMessage;?>","success",30000);
				});
			</script>
		<?php
		}
		?>
<script>	
	$(document).on("click",".btnRead",function(){
		fnWaitOpen();
		$(".checkIt").each(function(index,obj){ 
			if ( $(obj).parent().hasClass("checked")  == true){
				var errorID	= $(obj).data("errorid");
				
				//llamada ajax
				$.ajax({									
					cache       : false,
					dataType    : 'json',
					type        : 'POST',
					url  		: "<?php echo site_url(); ?>core_notification/save",
					data 		: {errorID : errorID  },
					success:function(data){
						console.info("complete success");				
						if(data.error){
							fnShowNotification(data.message,"error");
						}
						else{
							fnShowNotification("marcado como leido","success");
						}
					},
					error:function(xhr,data){	
						console.info("complete error");									
						fnShowNotification("Error 505","error");
					}
			    });
				
			}
		});
		fnWaitClose();
	});
</script>
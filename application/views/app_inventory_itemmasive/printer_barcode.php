<html>
	<head>
		<style type="text/css">
			
			

			@media all{
				
				@page{
					margin:0px 0px 0px 0px;
					padding:0px;
				}	
				@page:first{
					margin:0px 0px 0px 0px;
					padding:0px;
				}				
				
				html{
					margin:0px 0px 0px 0px;
					padding:0px;
				}

				body{
					margin:0px 0px 0px 0px;
					padding:0px;
				}


				
				div{
					margin-top: 0px;
					margin-left: 0px;
					margin-right: 0px;
					margin-bottom: 0px;    	
					width: 5cm;
					height: 3cm;					
					text-align:center;
				}
				img {
					margin:0px 0px 0px 0px;
					padding:0px;
					width: 4cm;
					height: 1.5cm;					
				}

				h4 {
					margin:0px 0px 0px 0px;
					padding:0px;					
				}
				h6 {
					margin:0px 0px 0px 0px;
					padding:0px;					
				}				
				
				p {
					margin:0px 0px 0px 0px;
					padding:0px;					
				}
				
				
			}
			
		</style>
	</head>
	<body>
		<?php
		if($objListaItem)
		foreach($objListaItem as $i){
		?>
			<div >				
				<img  src="<?php echo site_url(); ?>app_inventory_item/popup_add_renderimg/<?php echo $i->companyID; ?>/<?php echo $objComponentItem->componentID; ?>/<?php echo $i->itemID; ?>" />
				<p><?php echo strtolower(substr($i->name,0,27)); ?></p>
			</div>
		<?php 
		}
		?>
	</body>
</html>

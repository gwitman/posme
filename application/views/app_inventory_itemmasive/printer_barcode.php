<html>
	<head>
		<style type="text/css">
			
			

			@media all{
				
				@page{
					margin:0px 0px 0px 0px;
					padding:0px;
					size:5cm 2.5cm
				}							
				
				html{
					margin:0px 0px 0px 0px;
					padding:0px;
					width: 5cm;
					height: 2.5cm;		
				}

				body{
					margin:0px 0px 0px 0px;
					padding:0px;
					width: 5cm;
					height: 2.5cm;		
				}
				
				div{
					margin: auto;    						
					text-align:center;
					page-break-before:always;
				}
				img {
					margin:0.0cm 0cm 0cm 0cm;
					padding:0cm;
					width: 3.5cm;
					height: 1.5cm;					
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

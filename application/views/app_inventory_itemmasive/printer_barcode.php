<html>
	<head>
	<?php
    	$heider_ = 0;
        if ($objListaItem)
	    {
			$count = count($objListaItem);	       
		}
    	$count = 0 + 1;
		?>


		<style type="text/css">
			
			

			@media all{
				
				@page{
					margin:0px 0px 0px 0px;
					padding:0px;
					size:5cm <?php   echo ($heider_*$count);  ?>cm
					border:solid 0px red;
				}							
				
				html{
					margin:0px 0px 0px 0px;
					padding:0px;
					width: 5cm;
					height: <?php   echo ($heider_*$count);  ?>cm;		
					border:solid 1px red;
				}

				body{
					margin:0px 0px 0px 0px;					
					padding:6px;
					width: 5cm;
					height: <?php   echo ($heider_*$count);  ?>cm;		
					border:solid 0px red;
				}	
				.firts{
					margin-top: 0px;
					margin-left: 0px;
					margin-right: 0px;
					margin-bottom: 20px;
					text-align:center;
					width:4cm;					
					border:solid 1px red;
					page-break-after:always;

				}
				.midle{
					margin-top: 10px;
					margin-left: 0px;
					margin-right: 0px;
					margin-bottom: 20px;
					text-align:center;
					width:4cm;					
					border:solid 1px red;
					page-break-after:always;

				}				
				img {
					margin:0.2cm 0cm 0cm 0cm;
					padding:0cm;
					width: 4cm;
					height: 1.5cm;					
				}						
				
				p {
					margin:0px 0px 0px 0px;
					padding:0px;	
					font-size:small;				
				}
				
				
			}
			
		</style>
	</head>
	<body>
		<?php
        if ($objListaItem)
	    {			
	        $index = 0;
	        foreach ($objListaItem as $i) 
			{
		        $index++;
		        if ($index == 1) {
        		?>
					<div class="firts" >				
						<img  src="<?php echo site_url(); ?>app_inventory_item/popup_add_renderimg/<?php echo $i->companyID; ?>/<?php echo $objComponentItem->componentID; ?>/<?php echo $i->itemID; ?>" />
						<p><?php echo strtolower(substr($i->name.$index, 0, 27)); ?></p>
					</div>
				<?php
		        } else {
                ?>
					<div class="midle" >				
						<img  src="<?php echo site_url(); ?>app_inventory_item/popup_add_renderimg/<?php echo $i->companyID; ?>/<?php echo $objComponentItem->componentID; ?>/<?php echo $i->itemID; ?>" />
						<p><?php echo strtolower(substr($i->name.$index, 0, 27)); ?></p>
					</div>
				<?php
		        }
				
	        }

			?>
			<div class="midle" >								
				<img  src="" />
				<p>cant:<?php echo $count; ?> alto: <?php echo $count * $heider_; ?></p>
			</div>
			<?php

		}
		?>
	</body>
</html>

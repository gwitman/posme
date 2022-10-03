<html>
	<head>
	</head>
	<body>
		<img width="200px" height="70px" src="<?php echo site_url(); ?>app_inventory_item/renderImg/<?php echo $objItem->companyID; ?>/<?php echo $objComponentItem->componentID; ?>/<?php echo $objItem->itemID; ?>" />
		<h4><?php echo $objItem->name; ?></h4>
	</body>
</html>

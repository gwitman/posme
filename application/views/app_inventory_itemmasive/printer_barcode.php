<!--
el formato de esta impresion de codigo de barra es el siguiente,
impresora de ticket  o termia, con papel de de calcomania de la ssiguientes dimeiciones.
5.5cm de ancho, colcamania de 5 cm de ancho  (2pulgada)
				colcamania de 2.5 cm de alto (1plugada)
				
separador de calcomania de 0.4 cm
intrucciones de ingreso.
poner al orilla izquierda de la impresora.
con la cara amarilla visible al usuairo.
la ranura de la separacion de la colcamania , alinearala con
el relieve de de la impresora. que esta por fuera.
cantidad de colcamainia  de desperdicio: 4.
cantidad minimas de codigo:		10.
-->
<html>
	<head>
		<style type="text/css">
			
			@media all{
				@page{
					margin:0px;
					padding:0px;
				}
				
				h4 {
					margin:0px;
					padding:0px;
				}
				h6 {
					margin:0px 0px 15px 0px;
					padding:0px;
				}
				
				img {
					margin:0px;
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
			<h6>...........</h6>
			<img width="150px" height="60px" src="<?php echo site_url(); ?>app_inventory_item/popup_add_renderimg/<?php echo $i->companyID; ?>/<?php echo $objComponentItem->componentID; ?>/<?php echo $i->itemID; ?>" />
			<h4><?php echo strtolower(substr($i->name,0,27)); ?></h4>
		<?php 
		}
		?>
	</body>
</html>

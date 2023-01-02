<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<title></title>
		<meta name="viewport" 			content="width=device-width, initial-scale=1.0">
		<meta name="application-name" 	content="dsemp" /> 
		
		<link rel="stylesheet" type="text/css" href="<?php echo site_url(); ?>css/style_table_report_printer.css">
		<link rel="stylesheet" type="text/css" href="<?php echo site_url(); ?>css/style_table_report_printer.css" media="print">
		
	</head>
	<body> 
		<div class="data_grid_encabezado">
			<table>
				<thead>
					<tr>
						<th colspan="18">DETALLE DE VENTAS</th>
					</tr>
					<tr>
						<th colspan="18"><?php echo strtoupper($objCompany->name); ?></th>
					</tr>
					<tr>
						<th colspan="18">VENTAS DEL <?php echo $objStartOn;?> AL <?php echo $objEndOn;?></th>
					</tr>
				</thead>
			</table>
		</div>
		<br/>
		
		<div class="data_grid_body">
			<table style="width: 1500px !important;">
				<thead>
					<tr>
						<th class="cell_left">Codigo V.</th>
						<th class="cell_left">Vendedor</th>
						<th class="cell_left">Factura</th>
						<th class="cell_left">Tipo</th>
						<th class="cell_left">Fecha</th>
						<th class="cell_left">Cod Cliente</th>
						<th class="cell_left">Cliente</th>
						<th class="cell_left">Zona</th>
						<th class="cell_right">Precio</th>
						<th class="cell_right">Cantidad</th>
						<th class="cell_right">Costo</th>
						<th class="cell_right">Costo Total</th>
						<th class="cell_right">Monto Total</th>
						<th class="cell_right">Utilidad</th>						
						<th class="cell_right" style="width: 120px;">Cod. Producto</th>
						<th class="cell_left">Producto</th>					
					</tr>
				</thead>				
				<tbody>
					<?php
					$count 		= 0;
					if($objDetail)
					foreach($objDetail as $i){
						$count++;						
						echo "<tr>";
							echo "<td class='cell_left'>";
								echo ($i["userID"]);
							echo "</td>";
							echo "<td class='cell_left'>";
								echo ($i["nickname"]);
							echo "</td>";
							echo "<td class='cell_left'>";
								echo ($i["transactionNumber"]);
							echo "</td>";
							echo "<td class='cell_left'>";
								echo ($i["tipo"]);
							echo "</td>";
							echo "<td class='cell_left'>";
								echo ($i["transactionOn"]);
							echo "</td>";
							echo "<td class='cell_left'>";
								echo ($i["customerNumber"]);
							echo "</td>";
							echo "<td class='cell_left'>";
								echo ($i["legalName"]);
							echo "</td>";
							echo "<td class='cell_left'>";
								echo ($i["zone"]);
							echo "</td>";
							echo "<td class='cell_right'>";
								echo sprintf("%01.2f",$i["unitaryPrice"]);
							echo "</td>";
							echo "<td class='cell_right'>";
								echo sprintf("%01.2f",$i["quantity"]);
							echo "</td>";
							echo "<td class='cell_right'>";
								echo sprintf("%01.2f",$i["unitaryCost"]);
							echo "</td>";
							echo "<td class='cell_right'>";
								echo ($i["cost"]);
							echo "</td>";
							echo "<td class='cell_right'>";
								echo ($i["amount"]);
							echo "</td>";
							echo "<td class='cell_right'>";
							echo ( sprintf("%01.2f",$i["amount"] -  $i["cost"] ) );
							echo "</td>";
							echo "<td class='cell_right'>";
								echo ($i["itemNumber"]);
							echo "</td>";
							echo "<td class='cell_left'>";
								echo ($i["itemName"]);
							echo "</td>";
						
						echo "</tr>";
					}
					?>
				</tbody>
			</table>
		</div>
		
		
		
		
	</body>	
</html>
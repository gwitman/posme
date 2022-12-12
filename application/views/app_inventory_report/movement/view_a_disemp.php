<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<title><?php echo $objFirmaEncription; ?></title>
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
						<th>AUXILIAR DE MOVIMIENTOS</th>
					</tr>
					<tr>
						<th><?php echo strtoupper($objCompany->name); ?></th>
					</tr>
					<tr>
						<th>MOVIMIENTOS DE <?php echo $startOn; ?> AL <?php echo $endOn; ?></th>
					</tr>
				</thead>
			</table>
		</div>
		<br/>
		
		<div class="data_grid_left">
			<table>
				<tbody>
					<tr>
						<td>Producto</td>
						<td><?php echo $objItem->itemNumber." ".$objItem->name; ?></td>
					</tr>
				</tbody>
			</table>
		</div>
		<br/>
		<div class="data_grid_body">
			<table>
				<thead>
					<tr>
						<th class="cell_left">Fecha</th>
						<th class="cell_left">Documento</th>
						<th class="cell_left">Bodega</th>
						<th class="cell_left">Tipo</th>
						<th class="cell_right">Cantidad Inicial</th>
						<th class="cell_right">Costo Inicial</th>
						<th class="cell_right">Cantidad</th>
						<th class="cell_right">Costo</th>
						<th class="cell_right">Cantidad Final</th>
						<th class="cell_right">Costo Final</th>
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
								echo ($i["movementOn"]);
							echo "</td>";
							echo "<td class='cell_left'>";
								echo ($i["transactionNumber"]);
							echo "</td>";
							echo "<td class='cell_left'>";
								echo ($i["warehouseNumber"]);
							echo "</td>";
							echo "<td class='cell_left'>";
								echo ($i["transactionType"]);
							echo "</td>";
							echo "<td class='cell_right'>";
								echo ($i["oldQuantity"]);
							echo "</td>";
							echo "<td class='cell_right'>";
								echo ($i["oldCost"]);
							echo "</td>";
							echo "<td class='cell_right'>";
								echo ($i["transactionQuantity"]);
							echo "</td>";
							echo "<td class='cell_right'>";
								echo ($i["transactionCost"]);
							echo "</td>";
							echo "<td class='cell_right'>";
								echo ($i["newQuantity"]);
							echo "</td>";
							echo "<td class='cell_right'>";
								echo ($i["newCost"]);
							echo "</td>";
						echo "</tr>";
					}
					?>
				</tbody>
				<tfoot>
					<tr>
						<th class="cell_left">Fecha</th>
						<th class="cell_left">Documento</th>
						<th class="cell_left">Bodega</th>
						<th class="cell_left">Tipo</th>
						<th class="cell_right">Cantidad Inicial</th>
						<th class="cell_right">Costo Inicial</th>
						<th class="cell_right">Cantidad</th>
						<th class="cell_right">Costo</th>
						<th class="cell_right">Cantidad Final</th>
						<th class="cell_right">Costo Final</th>
					</tr>
				</tfoot>
			</table>
		</div>
		
		<br/>		
		<div class="data_grid_firm_system">
			<table>
				<tbody>
					<tr>
						<td><?php echo date("Y-m-d H:i:s");  ?> <?php echo $objFirmaEncription; ?></td>
					</tr>
				</tbody>
			</table>
		</div>
		
	</body>	
</html>
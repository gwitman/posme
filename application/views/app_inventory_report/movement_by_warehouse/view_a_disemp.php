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
						<th>AUXILIAR DE MOVIMIENTOS POR BODEGA</th>
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
						<td>Bodega</td>
						<td><?php echo "'".$objWarehouse->number."'"; ?></td>
					</tr>
					<tr>
						<td>Nombre</td>
						<td><?php echo $objWarehouse->name; ?></td>
					</tr>
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
						<th class="cell_left">Codigo</th>
						<th class="cell_left">Desc.</th>
						<th class="cell_left">U/M</th>
						<th class="cell_left">Tipo</th>
						<th class="cell_right">Cantidad</th>
						<th class="cell_right">Balance</th>
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
								echo ($i["transactionOn"]);
							echo "</td>";
							echo "<td class='cell_left'>";
								echo ($i["transactionNumber"]);
							echo "</td>";
							echo "<td class='cell_left'>";
								echo ($i["itemNumber"]);
							echo "</td>";
							echo "<td class='cell_left'>";
								echo ($i["itemName"]);
							echo "</td>";
							echo "<td class='cell_left'>";
								echo ($i["itemUnitmeasure"]);
							echo "</td>";
							echo "<td class='cell_left'>";
								echo ($i["itemType"]);
							echo "</td>";
							echo "<td class='cell_right'>";
								echo ($i["quantity"]);
							echo "</td>";
							echo "<td class='cell_right'>";
								echo ($i["balance"]);
							echo "</td>";
						echo "</tr>";
					}
					?>
				</tbody>
				<tfoot>
					<tr>
						<th class="cell_left">Fecha</th>
						<th class="cell_left">Documento</th>
						<th class="cell_left">Codigo</th>
						<th class="cell_left">Desc.</th>
						<th class="cell_left">U/M</th>
						<th class="cell_left">Tipo</th>
						<th class="cell_right">Cantidad</th>
						<th class="cell_right">Balance</th>
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
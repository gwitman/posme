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
						<th>LISTA DE PRODUCTO Y VENCIMIENTO</th>
					</tr>
					<tr>
						<th><?php echo strtoupper($objCompany->name); ?></th>
					</tr>
					<tr>
						<th>LISTA DE PRODUCTO Y VENCIMIENTO</th>
					</tr>
				</thead>
			</table>
		</div>
		<br/>
		<div class="data_grid_body">
			<table>
				<thead>
					<tr>
						<th class="cell_left">Codigo</th>
						<th class="cell_left">Nombre</th>
						<th class="cell_left">U/M</th>
						<th class="cell_left">Categoria</th>
						<th class="cell_right">Cantidad General</th>
						<th class="cell_right">Costo</th>
						<th class="cell_right">Precio</th>
						<th class="cell_right">Bodega</th>
						<th class="cell_right">Vencimiento</th>
						<th class="cell_right">Cantidad Vencimiento</th>
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
								echo ($i["itemNumber"]);
							echo "</td>";
							echo "<td class='cell_left'>";
								echo ($i["itemName"]);
							echo "</td>";
							echo "<td class='cell_left'>";
								echo ($i["unitMeasure"]);
							echo "</td>";
							echo "<td class='cell_left'>";
								echo ($i["categoryName"]);
							echo "</td>";
							echo "<td class='cell_right'>";
								echo ($i["quantity"]);
							echo "</td>";
							echo "<td class='cell_right'>";
								echo ($i["cost"]);
							echo "</td>";
							echo "<td class='cell_right'>";
								echo ($i["pricePublico"]);
							echo "</td>";
							echo "<td class='cell_right'>";
								echo ($i["warehouseName"]);								
							echo "</td>";
							echo "<td class='cell_right'>";
								echo ($i["dateExpired"]);								
							echo "</td>";
							echo "<td class='cell_right'>";
								echo ($i["quantityExpired"]);								
							echo "</td>";
							
						echo "</tr>";
					}
					?>
				</tbody>
				<tfoot>
					<tr>
						<th class="cell_left">Codigo</th>
						<th class="cell_left">Nombre</th>
						<th class="cell_left">U/M</th>
						<th class="cell_left">Categoria</th>
						<th class="cell_right">Cantidad General</th>
						<th class="cell_right">Costo</th>
						<th class="cell_right">Precio</th>
						<th class="cell_right">Bodega</th>
						<th class="cell_right">Vencimiento</th>
						<th class="cell_right">Cantidad Vencimiento</th>
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
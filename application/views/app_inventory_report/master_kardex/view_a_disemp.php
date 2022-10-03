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
						<th>MASTER KARDEX</th>
					</tr>
					<tr>
						<th><?php echo strtoupper($objCompany->name); ?></th>
					</tr>
					<tr>
						<th>KARDEX DE <?php echo $startOn; ?> AL <?php echo $endOn; ?> <?php echo ($objWarehouse != null ? "EN ".$objWarehouse->name : ""); ?></th>
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
						<th class="cell_left">Descripcion</th>
						<th class="cell_right">Cant. Inicial</th>
						<th class="cell_right">Costo Inicial</th>
						<th class="cell_right">Cant. Entrada</th>
						<th class="cell_right">Costo Entrada</th>
						<th class="cell_right">Cant. Salida</th>
						<th class="cell_right">Costo Salida</th>
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
							echo "<td class='cell_right'>";
								echo ($i["quantityInicial"]);
							echo "</td>";
							echo "<td class='cell_right'>";
								echo ($i["costInicial"]);
							echo "</td>";
							echo "<td class='cell_right'>";
								echo ($i["quantityInput"]);
							echo "</td>";
							echo "<td class='cell_right'>";
								echo ($i["costInput"]);
							echo "</td>";
							echo "<td class='cell_right'>";
								echo ($i["quantityOutput"]);
							echo "</td>";
							echo "<td class='cell_right'>";
								echo ($i["costOutput"]);
							echo "</td>";
						echo "</tr>";
					}
					?>
				</tbody>
				<tfoot>
					<tr>
						<th class="cell_left">Codigo</th>
						<th class="cell_left">Descripcion</th>
						<th class="cell_right">Cant. Inicial</th>
						<th class="cell_right">Costo Inicial</th>
						<th class="cell_right">Cant. Entrada</th>
						<th class="cell_right">Costo Entrada</th>
						<th class="cell_right">Cant. Salida</th>
						<th class="cell_right">Costo Salida</th>
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
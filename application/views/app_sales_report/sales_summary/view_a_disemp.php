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
						<th  colspan="11">RESUMEN DE VENTAS</th>
					</tr>
					<tr>
						<th  colspan="11"><?php echo strtoupper($objCompany->name); ?></th>
					</tr>
					<tr>
						<th  colspan="11">VENTAS DEL <?php echo $objStartOn;?> AL <?php echo $objEndOn;?></th>
					</tr>
				</thead>
			</table>
		</div>
		<br/>		
		<div class="data_grid_body">
			<table>
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
						<th class="cell_right">Monto</th>
						<th class="cell_right">Iva</th>
						<th class="cell_right">Total</th>
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
								echo sprintf("%01.2f",$i["amountDocument"]);
							echo "</td>";
							echo "<td class='cell_right'>";
								echo sprintf("%01.2f",$i["ivaDocument"]);
							echo "</td>";
							echo "<td class='cell_right'>";
								echo sprintf("%01.2f",$i["totalDocument"]);
							echo "</td>";
						echo "</tr>";
					}
					?>
				</tbody>
			</table>
		</div>
		
		<br/>		
		<div class="data_grid_firm_system">
			<table>
				<tbody>
					<tr>
						<td colspan="11"><?php echo date("Y-m-d H:i:s");  ?> <?php echo $objFirmaEncription; ?></td>
					</tr>
				</tbody>
			</table>
		</div>
		
		
		
	</body>	
</html>
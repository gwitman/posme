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
						<th  colspan="9">PROYECCION</th>
					</tr>
					<tr>
						<th  colspan="9"><?php echo strtoupper($objCompany->name); ?></th>
					</tr>
					<tr>
						<th  colspan="9">LISTA DE CUENTAS</th>
					</tr>
				</thead>
			</table>
		</div>
		
		<br/>
		<div class="data_grid_body">
			<table>
				<thead>
					<tr>
						<th nowrap class="cell_left">Codigo</th>
						<th nowrap class="cell_left">Cliente</th>						
						<th nowrap class="cell_left">Fecha</th>
						<th nowrap class="cell_left">Periodo</th>
						<th nowrap class="cell_left">Moneda</th>
						<th nowrap class="cell_right">Capital</th>
						<th nowrap class="cell_right">Interest</th>
						<th nowrap class="cell_right">Cuota</th>
						<th nowrap class="cell_right">Restante</th>						
					</tr>
				</thead>				
				<tbody>
					<?php
					$count 		= 0;
					if($objDetail)
					foreach($objDetail as $i){
						$count++;						
						echo "<tr>";
							echo "<td nowrap class='cell_left'>";
								echo ($i["customerNumber"]);
							echo "</td>";
							echo "<td nowrap class='cell_left'>";
								echo ($i["legalName"]);
							echo "</td>";						
							echo "<td nowrap class='cell_left'>";
								echo ($i["Fecha"]);
							echo "</td>";
							echo "<td nowrap class='cell_left'>";
								echo ($i["FechaPeriodo"]);
							echo "</td>";
							echo "<td nowrap class='cell_left'>";
								echo ($i["Moneda"]);
							echo "</td>";
							echo "<td nowrap class='cell_right'>";
								echo sprintf("%01.2f",$i["capital"]);
							echo "</td>";
							echo "<td nowrap class='cell_right'>";
								echo sprintf("%01.2f",$i["interest"]);
							echo "</td>";
							echo "<td nowrap class='cell_right'>";
								echo sprintf("%01.2f",$i["cuota"]);
							echo "</td>";
							echo "<td nowrap class='cell_right'>";
								echo sprintf("%01.2f",$i["remaining"]);
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
						<td colspan="9"><?php echo date("Y-m-d H:i:s");  ?> <?php echo $objFirmaEncription; ?></td>
					</tr>
				</tbody>
			</table>
		</div>
		
	</body>	
</html>
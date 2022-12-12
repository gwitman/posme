<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<title>Catalogo de Cuenta ...<?php echo $objFirmaEncription; ?></title>
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
						<th colspan="8">CATALOGO DE CUENTA</th>
					</tr>
					<tr>
						<th colspan="8"><?php echo strtoupper($objCompany->name); ?></th>
					</tr>
					<tr>
						<th colspan="8">ELABORADO EL <?php echo date('Y-m-d H:i:s'); ?></th>
					</tr>
				</thead>
			</table>
		</div>
		<br/>
		<div class="data_grid_body">
			<table>
				<thead>
					<tr>
						<th nowrap class="cell_left">Cuenta</th>
						<th nowrap class="cell_left">Nombre</th>
						<th nowrap class="cell_right">Es Operativa</th>
						<th nowrap class="cell_right">Moneda</th>
						<th nowrap class="cell_right">Nivel</th>
						<th nowrap class="cell_right">Longitud</th>
						<th nowrap class="cell_right">Tipo</th>
						<th nowrap class="cell_right">Naturaleza</th>
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
								echo ("'".$i["accountNumber"]."'");
							echo "</td>";
							echo "<td nowrap class='cell_left'>";
								echo ($i["name"]);
							echo "</td>";
							echo "<td nowrap class='cell_right'>";
								echo ($i["isOperative"]);
							echo "</td>";
							echo "<td nowrap class='cell_right'>";
								echo ($i["money"]);
							echo "</td>";
							echo "<td nowrap class='cell_right'>";
								echo ($i["nivel"]);
							echo "</td>";
							echo "<td nowrap class='cell_right'>";
								echo ($i["lengthTotal"]);
							echo "</td>";
							echo "<td nowrap class='cell_right'>";
								echo ($i["tipo"]);
							echo "</td>";
							echo "<td nowrap class='cell_right'>";
								echo ($i["naturaleza"]);
							echo "</td>";
						echo "</tr>";
					}
					?>
				</tbody>
				<tfoot>
					<tr>
						<th nowrap class="cell_left">Cuenta</th>
						<th nowrap class="cell_left">Nombre</th>
						<th nowrap class="cell_right">Es Operativa</th>
						<th nowrap class="cell_right">Moneda</th>
						<th nowrap class="cell_right">Nivel</th>
						<th nowrap class="cell_right">Longitud</th>
						<th nowrap class="cell_right">Tipo</th>
						<th nowrap class="cell_right">Naturaleza</th>
					</tr>
				</tfoot>
			</table>
		</div>
		
		<br/>
		<div class="data_grid_firm_system">
			<table>
				<tbody>
					<tr>
						<td colspan="8"><?php echo date("Y-m-d H:i:s");  ?> <?php echo $objFirmaEncription; ?></td>
					</tr>
				</tbody>
			</table>
		</div>
		
	</body>	
</html>
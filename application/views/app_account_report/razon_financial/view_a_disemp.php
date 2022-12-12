<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<title>Razones Financieras ...<?php echo $objFirmaEncription; ?></title>
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
						<th colspan="4">RAZONES FINANCIERAS</th>
					</tr>
					<tr>
						<th colspan="4"><?php echo strtoupper($objCompany->name); ?></th>
					</tr>
					<tr>
						<th colspan="4">DEL <?php echo helper_DateToSpanish($objCycle->endOn,"Y-F"); ?></th>
					</tr>
				</thead>
			</table>
		</div>
		<br/>
		<div class="data_grid_body">
			<table>
				<thead>
					<tr>
						<th nowrap class="cell_left">#</th>
						<th nowrap class="cell_left">Nombre</th>
						<th nowrap class="cell_right">Valor</th>
						<th nowrap class="cell_right">Descripcion</th>
					</tr>
				</thead>				
				<tbody>
					<?php
					$count = 0;
					if($objDetailRazon)
					foreach($objDetailRazon as $i){
						$count++;
						echo "<tr class='data_tr'>";	
							echo "<td nowrap class='cell_left'>";
								echo $count;
							echo "</td>";						
							echo "<td nowrap class='cell_left'>";
								echo $i["name"];
							echo "</td>";
							echo "<td nowrap class='cell_right'>";
								echo number_format($i["value"],2)." ".$i["simbol"];
							echo "</td>";
							echo "<td nowrap class='cell_right'>";
								echo $i["description"];
							echo "</td>";
						echo "</tr>";
					}
					?>
				</tbody>
			</table>
		</div>
		<br/>
		<div class="data_grid_encabezado">
			<table>
				<thead>
					<tr>
						<th colspan="4">INDICADORES</th>
					</tr>
					<tr>
						<th colspan="4"><?php echo strtoupper($objCompany->name); ?></th>
					</tr>
					<tr>
						<th colspan="4">DEL <?php echo helper_DateToSpanish($objCycle->endOn,"Y-F"); ?></th>
					</tr>
				</thead>
			</table>
		</div>
		<br/>
		<div class="data_grid_body">
			<table>
				<thead>
					<tr>
						<th nowrap class="cell_left">#</th>
						<th nowrap class="cell_left">Nombre</th>
						<th nowrap class="cell_right">Valor</th>
						<th nowrap class="cell_right">Descripcion</th>
					</tr>
				</thead>				
				<tbody>
					<?php
					$count = 0;
					if($objDetailIndicadores)
					foreach($objDetailIndicadores as $i){
						$count++;
						
						if($i["isGroup"] == 1 )
						{
							echo "<tr class='data_tr'>";	
								echo "<td nowrap class='cell_left'>";
									echo $i["prefix"].$i["code"].$i["posfix"];
								echo "</td>";						
								echo "<td nowrap class='cell_left'  >";
									echo $i["prefix"].$i["name"].$i["posfix"];
								echo "</td>";
								echo "<td nowrap class='cell_right' colspan='2' >";
									echo $i["prefix"].$i["description"].$i["posfix"];
								echo "</td>";
							echo "</tr>";
						}
						else 
						{
							echo "<tr class='data_tr'>";	
								echo "<td nowrap class='cell_left'>";
									echo $i["code"];
								echo "</td>";						
								echo "<td nowrap class='cell_left'>";
									echo $i["name"];
								echo "</td>";
								echo "<td nowrap class='cell_right'>";
									echo $i["prefix"]." ".number_format($i["value"],2)." ".$i["posfix"];
								echo "</td>";
								echo "<td nowrap class='cell_right'>";
									echo $i["description"];
								echo "</td>";
							echo "</tr>";
						}
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
						<td colspan="4"><?php echo date("Y-m-d H:i:s");  ?> <?php echo $objFirmaEncription; ?></td>
					</tr>
				</tbody>
			</table>
		</div>
		
	</body>	
</html>
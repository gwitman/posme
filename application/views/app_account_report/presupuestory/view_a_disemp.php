<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<title>Presupuesto ...<?php echo $objFirmaEncription; ?></title>
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
						<th colspan="5">PRESUPUESTO MENSUAL</th>
					</tr>
					<tr>
						<th colspan="5"><?php echo strtoupper($objCompany->name); ?></th>
					</tr>
					<tr>
						<th colspan="5">DEL <?php echo helper_DateToSpanish($objCycle->startOn,"Y-F"); ?></th>
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
						<th nowrap class="cell_right">Presupuesto de Gasto $</th>
						<th nowrap class="cell_right">Gasto Realizado $</th>
						<th nowrap class="cell_right">Gasto Presupuestado - Gasto Realizado $</th>						
					</tr>
				</thead>				
				<tbody>
					<?php
					$count 		= 0;
					$dif		= 0;
					if($objDetail)
					foreach($objDetail as $i){
						$count++;
						$dif = number_format($i["numberPresupuesto"] + $i["realPresupuesto"],2);
						if ($dif < 0)
						echo "<tr style='color:red;' class='data_tr'>";						
						else 
						echo "<tr class='data_tr'>";						
							echo "<td nowrap class='cell_left'>";
								echo $i["accountNumber"];
							echo "</td>";
							echo "<td nowrap class='cell_left'>";
								echo ($i["accountName"]);
							echo "</td>";
							echo "<td nowrap class='cell_right'>";
								echo "U$ ".number_format($i["numberPresupuesto"],2);
							echo "</td>";
							echo "<td nowrap class='cell_right'>";
								echo "U$ ".number_format(abs($i["realPresupuesto"]),2);
							echo "</td>";
								echo "<td nowrap class='cell_right'>";
								echo "U$ ".number_format($i["numberPresupuesto"] + $i["realPresupuesto"],2);
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
						<td colspan="5"><?php echo date("Y-m-d H:i:s");  ?> <?php echo $objFirmaEncription; ?></td>
					</tr>
				</tbody>
			</table>
		</div>
		
	</body>	
</html>
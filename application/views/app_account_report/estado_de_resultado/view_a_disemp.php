<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<title>Estado de Resultado ...<?php echo $objFirmaEncription; ?></title>
		<meta name="viewport" 			content="width=device-width, initial-scale=1.0">
		<meta name="application-name" 	content="dsemp" /> 
		
		<link rel="stylesheet" type="text/css" href="<?php echo site_url(); ?>css/style_table_report_printer.css">
		<link rel="stylesheet" type="text/css" href="<?php echo site_url(); ?>css/style_table_report_printer.css" media="print">[
]	</head>
	<body> 
		<div class="data_grid_encabezado">
			<table>
				<thead>
					<tr>
						<th colspan="4">ESTADO DE RESULTADO</th>
					</tr>
					<tr>
						<th colspan="4"><?php echo strtoupper($objCompany->name); ?></th>
					</tr>
					<tr>
						<th colspan="4">DEL <?php echo helper_DateToSpanish($objCycle->startOn,"Y-F"); ?> CENTRO DE COSTO: <?php echo  ($objCenterCost == NULL ? "TODOS": $objCenterCost->description); ?></th>
					</tr>
				</thead>
			</table>
		</div>
		<br/>		
		<div class="data_grid_body">
			<table width="100%">
				<thead>
					<tr width="100%">
						<th nowrap class="cell_left" width="20%">Codigo</th>
						<th nowrap class="cell_left" width="40%">Cuenta</th>
						<th nowrap class="cell_right" width="20%">Mensual</th>
						<th nowrap class="cell_right" width="20%">Acumulado</th>
					</tr>
				</thead>				
				<tbody>
					<?php
					$count 		= 0;
					if($objDetailIngresos)
					foreach($objDetailIngresos as $i){
						$count++;
						echo "<tr class='data_tr'>";						
							echo "<td nowrap class='cell_left'>";
								echo ("'".$i["accountNumber"]);
							echo "</td>";
							echo "<td nowrap class='cell_left'>";
								echo ($i["name"]);
							echo "</td>";
							echo "<td nowrap class='cell_right'>";
								echo "C$ ".number_format($i["balanceMensual"],2);
							echo "</td>";
							echo "<td nowrap class='cell_right'>";
								echo "C$ ".number_format($i["balanceEnd"],2);
							echo "</td>";
						echo "</tr>";
					}
					?>
				</tbody>
			</table>
			<table width="100%">
				<thead>
					<tr width="100%">
						<th nowrap class="cell_left" width="20%">Codigo</th>
						<th nowrap class="cell_left" width="40%">Cuenta</th>
						<th nowrap class="cell_right" width="20%">Mensual</th>
						<th nowrap class="cell_right" width="20%">Acumulado</th>
					</tr>
				</thead>				
				<tbody>
					<?php
					$count 		= 0;
					if($objDetailCostos)
					foreach($objDetailCostos as $i){
						$count++;
						echo "<tr class='data_tr'>";						
							echo "<td nowrap class='cell_left'>";
								echo ("'".$i["accountNumber"]);
							echo "</td>";
							echo "<td nowrap class='cell_left'>";
								echo ($i["name"]);
							echo "</td>";
							echo "<td nowrap class='cell_right'>";
								echo "C$ ".number_format($i["balanceMensual"],2);
							echo "</td>";
							echo "<td nowrap class='cell_right'>";
								echo "C$ ".number_format($i["balanceEnd"],2);
							echo "</td>";
						echo "</tr>";
					}
					?>
				</tbody>
			</table>
			<table width="100%">
				<thead>
					<tr width="100%">
						<th nowrap class="cell_left" width="20%">Codigo</th>
						<th nowrap class="cell_left" width="40%">Cuenta</th>
						<th nowrap class="cell_right" width="20%">Mensual</th>
						<th nowrap class="cell_right" width="20%">Acumulado</th>
					</tr>
				</thead>				
				<tbody>
					<?php
					$count 		= 0;
					if($objDetailGastos)
					foreach($objDetailGastos as $i){
						$count++;
						echo "<tr class='data_tr'>";						
							echo "<td nowrap class='cell_left'>";
								echo ("'".$i["accountNumber"]);
							echo "</td>";
							echo "<td nowrap class='cell_left'>";
								echo ($i["name"]);
							echo "</td>";
							echo "<td nowrap class='cell_right'>";
								echo "C$ ".number_format($i["balanceMensual"],2);
							echo "</td>";
							echo "<td nowrap class='cell_right'>";
								echo "C$ ".number_format($i["balanceEnd"],2);
							echo "</td>";
						echo "</tr>";
					}
					?>
				</tbody>
			</table>
		</div>
		<br/>
		<div class="data_grid_right">
			<table width="100%">
				<tbody>
					<tr width="100%">
						<td nowrap colspan="2" class="cell_right" style="background:#ccc">Utilidades:</td>
						<td nowrap class="cell_right"  width="20%" style="background:#ccc"><?php echo "C$ ".number_format($objUtilityMensual,2);?></td>
						<td nowrap class="cell_right"  width="20%" style="background:#ccc"><?php echo "C$ ".number_format($objUtilityNeta,2);?></td>
					</tr>
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
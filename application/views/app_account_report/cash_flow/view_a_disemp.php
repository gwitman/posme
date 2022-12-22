<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<title>Flujo de Caja ...<?php echo $objFirmaEncription; ?></title>
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
						<th colspan="4">FLUJO DE CAJA</th>
					</tr>
					<tr>
						<th colspan="4"><?php echo strtoupper($objCompany->name); ?></th>
					</tr>
					<tr>
						<th colspan="4">DEL <?php echo helper_DateToSpanish($objCycle->startOn,"Y-F"); ?></th>
					</tr>
				</thead>
			</table>
		</div>
		<br/>		
		<div class="data_grid_body">
			<table width="100%">
				<thead>
					<tr width="100%">
						<th nowrap class="cell_left" width="20%">Tipo</th>
						<th nowrap class="cell_left" width="20%">Codigo</th>
						<th nowrap class="cell_left" width="60%">Cuenta</th>
						<th nowrap class="cell_right" width="20%">Saldo Final</th>
					</tr>
				</thead>				
				<tbody>
					<?php
					$count 			= 0;
					$valor			= 0;
					$total			= 0;
					$valorInicial	= 0;
					$valorFinal		= 0;
					
					if($objDetail)
					foreach($objDetail as $i){
						$count++;
						
						if($count == 1){
							$valor 			= $i["saldoInicial"];
							$valorInicial 	= $i["saldoInicial"];
							$valorFinal 	= $i["saldoFinal"];
						}
						else {
							$valor = $i["saldoFinal"];
						}
						$total	+= $valor;
						
						
						echo "<tr>";						
							echo "<td nowrap class='cell_left'>";
								echo ("'".$i["accountType"]);
							echo "</td>";
							echo "<td nowrap class='cell_left'>";
								echo ("'".$i["accountNumber"]);
							echo "</td>";
							echo "<td nowrap class='cell_left'>";
								echo ($i["account"]);
							echo "</td>";
							echo "<td nowrap class='cell_right'>";
								echo "C$ ". number_format($valor,2);
							echo "</td>";
						echo "</tr>";
					}
					//Total
					echo "<tr>";						
						echo "<td nowrap class='cell_left'>";
							echo "";
						echo "</td>";
						echo "<td nowrap class='cell_left'>";
							echo "";
						echo "</td>";
						echo "<td nowrap class='cell_left' style='background-color:#ccc'>";
							echo "Final";
						echo "</td>";
						echo "<td nowrap class='cell_right' style='background-color:#ccc'>";
							echo "C$ ". number_format($total,2);
						echo "</td>";
					echo "</tr>";
					//Diferencia
					echo "<tr>";						
						echo "<td nowrap class='cell_left'>";
							echo "";
						echo "</td>";
						echo "<td nowrap class='cell_left'>";
							echo "";
						echo "</td>";
						echo "<td nowrap class='cell_left' >";
							echo "Diferencia";
						echo "</td>";
						echo "<td nowrap class='cell_right' >";
							echo "C$ ". number_format($valorFinal - $total,2);
						echo "</td>";
					echo "</tr>";
					?>
				</tbody>
			</table>
		</div>
		<br/>
		<div class="data_grid_firm_system">
			<table>
				<tbody>
					<tr>
						<td colspan="4">Nota #1.  Si la diferencia es negativa debe de ser igual a la cuenta provisiones.</td>
					</tr>
					<tr>
						<td colspan="4"><?php echo date("Y-m-d H:i:s");  ?> <?php echo $objFirmaEncription; ?></td>
					</tr>
				</tbody>
			</table>
		</div>
		
	</body>	
</html>
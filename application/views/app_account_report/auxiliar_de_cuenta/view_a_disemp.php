<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<title>Auxiliar de Cuenta ...<?php echo $objFirmaEncription; ?></title>
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
						<th colspan="7">AUXILIAR DE CUENTA</th>
					</tr>
					<tr>
						<th colspan="7"><?php echo strtoupper($objCompany->name); ?></th>
					</tr>
					<tr>
						<th colspan="7">MOVIMIENTOS DEL <?php echo helper_DateToSpanish($objCycleStart->startOn,"Y-F"); ?></th>
					</tr>
				</thead>
			</table>
		</div>
		<br/>
		<div class="data_grid_left">
			<table>
				<tbody>
					<tr>
						<td nowrap>Cuenta</td>
						<td nowrap><?php echo "'".$accountNumber."'"; ?></td>
					</tr>
					<tr>
						<td nowrap>Nombre</td>
						<td nowrap><?php echo $name; ?></td>
					</tr>
					<tr>
						<td nowrap>Naturaleza</td>
						<td nowrap><?php echo $naturaleza; ?></td>
					</tr>
					<tr>
						<td nowrap>Moneda</td>
						<td nowrap><?php echo $money; ?></td>
					</tr>
					<tr>
						<td nowrap>Descripcion</td>
						<td nowrap><?php echo $description; ?></td>
					</tr>
					<tr>
						<td nowrap>Saldo Inicial</td>
						<td nowrap><?php echo "C$ ".number_format($objBalanceStart,2); ?></td>
					</tr>
					<tr>
						<td nowrap>Saldo Final</td>
						<td nowrap><?php echo "C$ ".number_format($objBalanceEnd,2); ?></td>
					</tr>
				</tbody>
			</table>
		</div>
		<br/>
		<div class="data_grid_body">
			<table>
				<thead>
					<tr>
						<th nowrap class="cell_left" style="width:120px;">Comprobante</th>
						<th nowrap class="cell_left" style="width:120px;">Fecha</th>
						<th nowrap class="cell_left" style="width:120px;">Tipo</th>
						<th nowrap class="cell_left">Nota</th>
						<th nowrap class="cell_left" style="width:120px;">Referencia1</th>
						<th nowrap class="cell_right" style="width:120px;">Debito</th>
						<th nowrap class="cell_right" style="width:120px;">Credito</th>
					</tr>
				</thead>				
				<tbody>
					<?php
					$count 		= 0;
					if($objMovement)
					foreach($objMovement as $i){
						$count++;
						echo "<tr class='data_tr'>";
							echo "<td nowrap class='cell_left'>";
								echo ($i["journalNumber"]);
							echo "</td>";
							echo "<td nowrap class='cell_left'>";
								echo ($i["journalDate"]);
							echo "</td>";
							echo "<td nowrap class='cell_left'>";
								echo ($i["journalType"]);
							echo "</td>";
							echo "<td nowrap class='cell_left'>";
								echo ($i["note"]);
							echo "</td>";
							echo "<td nowrap class='cell_left'>";
								echo ($i["reference1"]);
							echo "</td>";							
							echo "<td nowrap class='cell_right'>";
								echo "C$ ".number_format($i["debit"],2);
							echo "</td>";
							echo "<td nowrap class='cell_right'>";
								echo "C$ ".number_format($i["credit"],2);
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
						<td colspan="7"><?php echo date("Y-m-d H:i:s");  ?> <?php echo $objFirmaEncription; ?></td>
					</tr>
				</tbody>
			</table>
		</div>
		
	</body>	
</html>
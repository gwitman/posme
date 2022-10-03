<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<title>Balanza de Comprobacion ...<?php echo $objFirmaEncription; ?></title>
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
						<th colspan="6">BALANZA DE COMPROBACION</th>
					</tr>
					<tr>
						<th colspan="6"><?php echo strtoupper($objCompany->name); ?></th>
					</tr>
					<tr>
						<th colspan="6">DEL <?php echo helper_DateToSpanish($objCycle->startOn,"Y-F"); ?></th>
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
						<th nowrap class="cell_right">Saldo Inicial</th>
						<th nowrap class="cell_right">Debito</th>
						<th nowrap class="cell_right">Credito</th>
						<th nowrap class="cell_right">Saldo Final</th>
					</tr>
				</thead>				
				<tbody>
					<?php
					$count 		= 0;
					if($objDetail)
					foreach($objDetail as $i){
						$count++;												if ($i["isOperative"] == false){							echo "<tr class='data_tr' style='color:deeppink;'>";							//echo "<tr style='color:deeppink;' class='data_tr'>";													}
						else {							echo "<tr class='data_tr'>";												}
							echo "<td nowrap class='cell_left'>";
								echo ("'".$i["accountNumber"]."'");
							echo "</td>";
							echo "<td nowrap class='cell_left'>";
								echo ($i["name"]);
							echo "</td>";
							echo "<td nowrap class='cell_right'>";
								echo "C$ ".number_format($i["balanceStart"],2);
							echo "</td>";
							echo "<td nowrap class='cell_right'>";
								echo "C$ ".number_format($i["debit"],2);
							echo "</td>";
							echo "<td nowrap class='cell_right'>";
								echo "C$ ".number_format($i["credit"],2);
							echo "</td>";
							echo "<td nowrap class='cell_right'>";
								echo "C$ ".number_format($i["balanceEnd"],2);
							echo "</td>";
						echo "</tr>";
					}
					?>
				</tbody>
				<tfoot>
					<tr>
						<th nowrap class="cell_left">Cuenta</th>
						<th nowrap class="cell_left">Nombre</th>
						<th nowrap class="cell_right">Saldo Inicial</th>
						<th nowrap class="cell_right">Debito</th>
						<th nowrap class="cell_right">Credito</th>
						<th nowrap class="cell_right">Saldo Final</th>
					</tr>
				</tfoot>
			</table>
		</div>
		<br/>
		<div class="data_grid_right">
			<table>
				<tbody>
					<tr>
						<td colspan="4"></td>
						<td class="cell_right">Debitos:</td>
						<td class="cell_right"><?php echo "C$ ".number_format($objSumary["debit"],2);?></td>
					</tr>
										<tr>
						<td colspan="4"></td>
						<td class="cell_right">Creditos:</td>
						<td class="cell_right"><?php echo "C$ ".number_format($objSumary["credit"],2);?></td>
					</tr>
				</tbody>
			</table>
		</div>
		<br/>
		<div class="data_grid_firm_system">
			<table>
				<tbody>
					<tr>
						<td colspan="6"><?php echo date("Y-m-d H:i:s");  ?> <?php echo $objFirmaEncription; ?></td>
					</tr>
				</tbody>
			</table>
		</div>
		
	</body>	
</html>
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
						<th colspan='13'>INTERES POR PERIODO</th>
					</tr>
					<tr>
						<th colspan='13'><?php echo strtoupper($objCompany->name); ?></th>
					</tr>
					<tr>
						<th colspan='13'>INTERES DE <?php echo $startOn; ?> AL <?php echo $endOn; ?></th>
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
						<th nowrap class="cell_left">Fecha Docu.</th>
						<th nowrap class="cell_left">Documento</th>
						<th nowrap class="cell_left">Fecha Tran.</th>
						<th nowrap class="cell_left"># Transaccion.</th>
						<th nowrap class="cell_left">Descripcion.</th>
						
						<th nowrap class="cell_right">U$ Balance</th>
						<th nowrap class="cell_right">U$ Capital</th>
						<th nowrap class="cell_right">U$ Interes</th>
						<th nowrap class="cell_right">T/C</th>
						<th nowrap class="cell_right">Venta</th>
						<th nowrap class="cell_right">Compra</th>
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
								echo ("'".$i["documentFecha"]);
							echo "</td>";
							echo "<td nowrap class='cell_left'>";
								echo ($i["documentNumber"]);
							echo "</td>";
							echo "<td nowrap class='cell_left'>";
								echo ("'".$i["transactionFecha"]);
							echo "</td>";
							echo "<td nowrap class='cell_left'>";
								echo ($i["transactionNumber"]);
							echo "</td>";
							echo "<td nowrap class='cell_left'>";
								echo ($i["transactionName"]);
							echo "</td>";
							
							
							echo "<td nowrap class='cell_right'>";
								echo sprintf("%01.2f",$i["balance"]);
							echo "</td>";
							echo "<td nowrap class='cell_right'>";
								echo sprintf("%01.2f",$i["capital"]);
							echo "</td>";
							echo "<td nowrap class='cell_right'>";
								echo sprintf("%01.2f",$i["interest"]);
							echo "</td>";
							echo "<td nowrap class='cell_right'>";
								echo sprintf("%01.2f",$i["exchangeRate"]);
							echo "</td>";
							echo "<td nowrap class='cell_right'>";
								echo sprintf("%01.2f",$i["sale"]);
							echo "</td>";
							echo "<td nowrap class='cell_right'>";
								echo sprintf("%01.2f",$i["purchase"]);
							echo "</td>";
						echo "</tr>";
					}
					?>
				</tbody>
				<footer>					
					<tr>
						<th nowrap class="cell_left">Total</th>
						<th nowrap class="cell_left"></th>
						<th nowrap class="cell_left"></th>
						<th nowrap class="cell_left"></th>
						<th nowrap class="cell_left"></th>
						<th nowrap class="cell_left"></th>
						<th nowrap class="cell_left"></th>
						<th nowrap class="cell_left"></th>
						<th nowrap class="cell_right">=SUMA(I6:I<?php echo $count+5;?>)</th>
						<th nowrap class="cell_right">=SUMA(J6:J<?php echo $count+5;?>)</th>
						<th nowrap class="cell_left"></th>
						<th nowrap class="cell_left"></th>
						<th nowrap class="cell_left"></th>
					</tr>
				</footer>	
			</table>
		</div>
		<br/>
		<div class="data_grid_firm_system">
			<table>
				<tbody>
					<tr>
						<td colspan='13'><?php echo date("Y-m-d H:i:s");  ?> <?php echo $objFirmaEncription; ?></td>
					</tr>
				</tbody>
			</table>
		</div>
		
	</body>	
</html>
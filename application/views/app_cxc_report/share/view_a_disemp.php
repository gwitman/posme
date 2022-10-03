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
						<th colspan='10'>LISTA DE ABONOS</th>
					</tr>
					<tr>
						<th colspan='10'><?php echo strtoupper($objCompany->name); ?></th>
					</tr>
					<tr>
						<th colspan='10'>DEL <?php echo $startOn; ?> AL <?php echo $endOn; ?></th>
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
						<th nowrap class="cell_left">Moneda</th>
						<th nowrap class="cell_left">Fecha</th>						
						<th nowrap class="cell_left">Fac</th>
						<th nowrap class="cell_left">Transaccion</th>
						<th nowrap class="cell_left">Tran. Number</th>
						<th nowrap class="cell_left">Estado</th>
						<th nowrap class="cell_right">Monto</th>
						<th nowrap class="cell_right">Tipo Cambio</th>
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
								echo ($i["firstName"]);
							echo "</td>";
							echo "<td nowrap class='cell_left'>";
								echo ($i["moneda"]);
							echo "</td>";
								echo "<td nowrap class='cell_left'>";
								echo "'".date_format(date_create($i["transactionOn"]),'Y-m-d');
							echo "</td>";
								echo "<td nowrap class='cell_left'>";
								echo ($i["Fac"]);
							echo "</td>";
							echo "<td nowrap class='cell_left'>";
								echo ($i["transactionName"]);
							echo "</td>";
							echo "<td nowrap class='cell_left'>";
								echo ($i["transactionNumber"]);
							echo "</td>";
								echo "<td nowrap  class='cell_left'>";
								echo ($i["estado"]);
							echo "</td>";
							echo "<td nowrap class='cell_right'>";
								echo sprintf("%01.2f",$i["montoFac"]);
							echo "</td>";
							echo "<td nowrap class='cell_right'>";
								echo sprintf("%01.2f",$i["tipoCambio"]);
							echo "</td>";
						echo "</tr>";
					}
					?>
				</tbody>
				<footer>					
					<tr>
						<th nowrap class="cell_left">Codigo</th>
						<th nowrap class="cell_left">Cliente</th>
						<th nowrap class="cell_left">Moneda</th>
						<th nowrap class="cell_left">Fecha</th>						
						<th nowrap class="cell_left">Fac</th>
						<th nowrap class="cell_left">Transaccion</th>
						<th nowrap class="cell_left">Tran. Number</th>
						<th nowrap class="cell_left">Estado</th>
						<th nowrap class="cell_right">=SUMA(I6:I<?php echo $count+5;?>)</th>
						<th nowrap class="cell_right">Tipo Cambio</th>						
					</tr>
				</footer>	
			</table>
		</div>
		<br/>
		<div class="data_grid_firm_system">
			<table>
				<tbody>
					<tr>
						<td colspan='10'><?php echo date("Y-m-d H:i:s");  ?> <?php echo $objFirmaEncription; ?></td>
					</tr>
				</tbody>
			</table>
		</div>
		
	</body>	
</html>
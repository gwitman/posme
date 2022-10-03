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
						<th colspan="9">LISTA DE CLIENTES</th>
					</tr>
					<tr>
						<th colspan="9"><?php echo strtoupper($objCompany->name); ?></th>
					</tr>
					<tr>
						<th colspan="9">LISTA DE CLIENTES</th>
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
						<th nowrap class="cell_left">Codigo</th>
						<th nowrap class="cell_left">Cliente</th>
						<th nowrap class="cell_left">Cedula</th>
						<th nowrap class="cell_left">Telefono</th>
						<th nowrap class="cell_left">Email</th>	
						<th nowrap class="cell_left">Balance</th>	
						<th nowrap class="cell_left">Cartera</th>	
						<th nowrap class="cell_left">Pagos</th>	
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
								echo $count;
							echo "</td>";
							echo "<td nowrap class='cell_left'>";
								echo ($i["customerNumber"]);
							echo "</td>";
							echo "<td nowrap class='cell_left'>";
								echo ($i["customerName"]);
							echo "</td>";
							echo "<td nowrap class='cell_left'>";
								echo ($i["identification"]);
							echo "</td>";
							echo "<td nowrap class='cell_left'>";
								echo ($i["phone"]);
							echo "</td>";
							echo "<td nowrap class='cell_left'>";
								echo ($i["email"]);
							echo "</td>";
							echo "<td nowrap class='cell_right'>";
								echo ($i["balance"]);
							echo "</td>";
							echo "<td nowrap class='cell_left'>";
								echo "<a href='".site_url()."app_cxc_report/customer_status/viewReport/true/customerNumber/".$i["customerNumber"]."' target='_blank'>".$i["customerNumber"]."</a>";
							echo "</td>";
							echo "<td nowrap class='cell_left'>";
								echo "<a href='".site_url()."app_cxc_report/pay/viewReport/true/customerNumber/".$i["customerNumber"]."' target='_blank'>".$i["customerNumber"]."</a>";
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
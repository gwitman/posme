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
	<body style="background-image:url(<?php echo site_url(); ?>img/logos/<?php echo $objLogo->value;?>);background-size:80px 50px;"> 
		<div class="data_grid_encabezado">
			<table>
				<thead>
					<tr>
						<th colspan="6">TIPO DE CAMBIO</th>
					</tr>
					<tr>
						<th colspan="6"><?php echo strtoupper($objCompany->name); ?></th>
					</tr>
				</thead>
			</table>
		</div>
		<br/>
		<br/>
		<br/>
		<div class="data_grid_body">
			<table>
				<thead>
					<tr>
						<th nowrap class="cell_left">Fecha</th>
						<th nowrap class="cell_left">Cordoba</th>
						<th nowrap class="cell_left">Oficial</th>
						<th nowrap class="cell_left">Compra</th>
						<th nowrap class="cell_left">Venta</th>
						<th nowrap class="cell_right">Dolar</th>	
					</tr>
				</thead>				
				<tbody>
					<?php
					$count 		= 0;
					if($objDetail)
					foreach($objDetail as $i){
						$count++;
						if ($count % 2 == 0 )
						echo "<tr style='background:#ddd'>";
						else 
						echo "<tr>";					
							echo "<td nowrap class='cell_left'>";
								echo ($i["Fecha"]);
							echo "</td>";
								echo "<td nowrap class='cell_left'>";
								echo ($i["Cordoba"]);
							echo "</td>";
							echo "<td nowrap class='cell_left'>";
								echo ($i["Oficial"]);
							echo "</td>";
							echo "<td nowrap class='cell_left'>";
								echo ($i["Compra"]);
							echo "</td>";
							echo "<td nowrap class='cell_left'>";
								echo ($i["Venta"]);
							echo "</td>";
							echo "<td nowrap class='cell_right'>";
								echo ($i["Dolar"]);
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
						<td colspan="6"><?php echo date("Y-m-d H:i:s");  ?> <?php echo $objFirmaEncription; ?></td>
					</tr>
				</tbody>
			</table>
		</div>
		
	</body>	
</html>
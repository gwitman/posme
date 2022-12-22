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
						<th  colspan="14">CARTERA DE CREDITO</th>
					</tr>
					<tr>
						<th  colspan="14"><?php echo strtoupper($objCompany->name); ?></th>
					</tr>
					<tr>
						<th  colspan="14">LISTA DE CLIENTES</th>
					</tr>
				</thead>
			</table>
		</div>
		
		<br/>
		<div class="data_grid_body">
			<table style="width:2210px !important;">
				<thead>
					<tr>
						<th nowrap class="cell_left" style='width: 80px;' >Codigo.</th>
						<th nowrap class="cell_left" style='width: 220px;'>Cliente</th>						
						<th nowrap class="cell_right" style='width:100px;'>Mora</th>
						<th nowrap class="cell_right" style='width:160px;' >Fac.</th>
						<th nowrap class="cell_right" style='width:120px;' >Atrasado</th>
						<th nowrap class="cell_right" style='width:120px;' >F. Prox Pago</th>
						<th nowrap class="cell_right" style='width:120px;' >Prox Pago</th>						
						<th nowrap class="cell_right" style='width:150px;'>F. Cance</th>
											
						
						<th nowrap class="cell_right" style='width:150px;' >Identification</th>
						
						<th nowrap class="cell_right" style='width:120px;'>Telefono</th>
						
						
						<th nowrap class="cell_right" style='width:120px;'>Ultima cuota</th>
						
						<th nowrap class="cell_right" style='width:150px;'>F. Ultima cuota</th>
						
						<th nowrap class="cell_right" style='width:150px;'>M. Ultima cuota</th>
						<th nowrap class="cell_right" style='width:450px;'>Direccion</th>
						
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
								echo "<a href='".site_url()."app_cxc_report/customer_status/viewReport/true/customerNumber/".$i["customerNumber"]."' target='_blank'>"."'".$i["customerNumber"]."</a>";
							echo "</td>";
							echo "<td nowrap class='cell_left'>";
								echo ($i["legalName"]);
							echo "</td>";								
							echo "<td nowrap class='cell_right'>";
								echo sprintf("%01.2f",$i["maxDiasMora"])."";
							echo "</td>";
							echo "<td nowrap class='cell_right'>";
								echo ($i["factura"]);
							echo "</td>";
							echo "<td nowrap class='cell_right'>";
								echo "'".$i["moneda"]." ".sprintf("%01.2f",$i["montoAtrazado"]);
							echo "</td>";							
							echo "<td nowrap class='cell_right'>";
								echo "'".($i["proximoPago"]);
							echo "</td>";
							echo "<td nowrap class='cell_right'>";
								echo "'".$i["moneda"]." ".sprintf("%01.2f",$i["montoProximoPago"]);
							echo "</td>";
							echo "<td nowrap class='cell_right'>";
								echo $i["ultimoPagoFecha"];
							echo "</td>";
							
							
							
							echo "<td nowrap class='cell_right'>";
								echo $i["identification"];
							echo "</td>";
							
							echo "<td nowrap class='cell_right'>";
								echo $i["phone"];
							echo "</td>";
							
							echo "<td nowrap class='cell_right'>";
								echo $i["lastShareNumber"];
							echo "</td>";
							
							echo "<td nowrap class='cell_right'>";
								echo $i["dateLastShareNumber"];
							echo "</td>";
							
							echo "<td nowrap class='cell_right'>";
								
								echo "'".$i["moneda"]." ".sprintf("%01.2f",$i["amountLastShareNumber"]);
							echo "</td>";
							echo "<td nowrap class='cell_right'>";
								echo $i["direccion"];
							echo "</td>";
						echo "</tr>";
					}
					?>
				</tbody>
				<footer>
					<tr>
						<th class="cell_left">Totales</th>
						<th></th>												
						<th class="cell_right">=PROMEDIO(C6:C<?php echo $count+5; ?>)</th>
						<th class="cell_right"></th>
						<th class="cell_right">=SUMA(E6:E<?php echo $count+5; ?>)</th>
						<th class="cell_right"></th>
						<th class="cell_right">=SUMA(G6:G<?php echo $count+5; ?>)</th>
						<th class="cell_right"></th>	
						
						<th class="cell_right"></th>	
						
						<th></th>
						
						<th></th>
						
						<th></th>
						
						<th></th>
						
						<th></th>
					</tr>
				</footer>
			</table>
		</div>
		<br/>
		<div class="data_grid_firm_system">
			<table>
				<tbody>
					<tr>
						<td colspan="14"><?php echo date("Y-m-d H:i:s");  ?> <?php echo $objFirmaEncription; ?></td>
					</tr>
				</tbody>
			</table>
		</div>
		
	</body>	
</html>
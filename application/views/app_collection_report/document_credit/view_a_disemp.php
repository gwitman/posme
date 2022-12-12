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
						<th colspan="8">TABLA DE PAGO</th>
					</tr>
					<tr>
						<th colspan="8"><?php echo strtoupper($objCompany->name); ?></th>
					</tr>
					<tr>
						<th colspan="8">TABLA DE PAGO</th>
					</tr>
				</thead>
			</table>
		</div>
		<br/>
		<div class="data_grid_left">
			<table>
				<tbody>
					<tr>
						<td nowrap>Codigo</td>
						<td nowrap><?php echo $objDetail[0]["CodigoCliente"]; ?></td>
					</tr>
					<tr>
						<td nowrap>Nombre</td>
						<td nowrap colspan="7"><?php echo $objDetail[0]["Cliente"]; ?></td>
					</tr>
					<tr>
						<td nowrap>Tipo</td>
						<td nowrap><?php echo $objDetail[0]["TipoTelefono"]; ?></td>
					</tr>
					<tr>
						<td nowrap>Telefono</td>
						<td nowrap><?php echo $objDetail[0]["Telefono"]; ?></td>
					</tr>
					<tr>
						<td nowrap>Moneda</td>
						<td nowrap><?php echo $objDetail[0]["Moneda"]; ?></td>
					</tr>
					<tr>
						<td nowrap>Factura</td>
						<td nowrap><?php echo $objDetail[0]["Factura"]; ?></td>
					</tr>
					<tr>
						<td nowrap>Pagare</td>
						<td nowrap><?php echo "<a href='".site_url()."app_collection_report/customer' >Regresar</a>"; ?> </td>
					</tr>
					
				</tbody>
			</table>
		</div>
		<br/>
		<br/>
		<div class="data_grid_body">
			<table>
				<thead>
					<tr>
						<th nowrap class="cell_left">No</th>
						<th nowrap class="cell_left">Fecha</th>
						<th nowrap class="cell_right">Saldo de Cuota</th>
						<th nowrap class="cell_right">Dias Atrazo</th>
						<th nowrap class="cell_right">Dias de Pago</th>
					</tr>
				</thead>				
				<tbody>
					<?php
					$capital 	= 0;
					$interes 	= 0;
					$count 		= 0;
					if($objDetail)
					foreach($objDetail as $i){
						$count++;
						
						if($i["dias_atrazo"] > 0)
						echo "<tr style='background-color:aqua;'>";
						else
						echo "<tr>";
					
							echo "<td nowrap class='cell_left'>";
								echo $count;
							echo "</td>";
							echo "<td nowrap class='cell_left'>";
								echo "'".(date_format(date_create($i["dateApply"]),"Y-m-d"));
							echo "</td>";
							echo "<td nowrap class='cell_right'>";
								echo number_format($i["remaining"],2);
							echo "</td>";
							echo "<td nowrap class='cell_right'>";
								echo number_format($i["dias_atrazo"],2);
							echo "</td>";
							echo "<td nowrap class='cell_right'>";
								echo number_format($i["dias_proximo_pago"],2);
							echo "</td>";
							
						echo "</tr>";
					}
					?>
				</tbody>
			</table>
		</div>
		<br/>
		<br/>
		<div class="data_grid_firm_system">
			<table>
				<tbody>
					<tr>
						<td colspan="8"><?php echo date("Y-m-d H:i:s");  ?> <?php echo $objFirmaEncription; ?></td>
					</tr>
				</tbody>
			</table>
		</div>
	</body>	
</html>
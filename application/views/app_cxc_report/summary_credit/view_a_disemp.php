<!DOCTYPE html>
<html lang="en" xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40" >
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
						<th colspan="19">RESUMEN DE CLIENTES CXC</th>
					</tr>
					<tr>
						<th colspan="19"><?php echo strtoupper($objCompany->name); ?></th>
					</tr>
					<tr>
						<th colspan="19">RESUMEN ESTADISTICO DE CLIENTES</th>
					</tr>
				</thead>
			</table>
		</div>
		
		<br/>
		<div class="data_grid_body">
			<table>
				<thead>
					<tr>
						<th nowrap class="cell_left" width="123" >Codigo</th>
						<th nowrap class="cell_left">Cliente</th>
						<th nowrap class="cell_left">Factura</th>
						<th nowrap class="cell_left">Tipo Cambio</th>
						<th nowrap class="cell_left">Capital Inicial</th>
						<th nowrap class="cell_left">Capital Actual</th>
						<th nowrap class="cell_left">Provisionado</th>
						<th nowrap class="cell_left">Cuota Promedio</th>	
						<th nowrap class="cell_left">Interes Mensual</th>	
						<th nowrap class="cell_left">Interes Anual</th>	
						<th nowrap class="cell_left"># Cuotas</th>	
						<th nowrap class="cell_left"># Meses</th>	
						<th nowrap class="cell_left"># Frecuencia de Pago</th>
						<th nowrap class="cell_left">Tipo</th>
						<th nowrap class="cell_left">Moneda</th>
						<th nowrap class="cell_left">Ultima Fecha</th>
						<th nowrap class="cell_left">Dias Para Cancelar</th>
						<th nowrap class="cell_left">Meses Para Cancelar</th>
						<th nowrap class="cell_left">Meses Para Cancelar %</th>
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
								echo ($i["codigoCliente"]);
							echo "</td>";
							echo "<td nowrap class='cell_left'>";
								echo ($i["cliente"]);
							echo "</td>";
							echo "<td nowrap class='cell_left'>";
								echo ($i["Factura"]);
							echo "</td>";
							echo "<td nowrap class='cell_left'>";
								echo ($i["TipoCambio"]);
							echo "</td>";
							echo "<td nowrap class='cell_right'>";
								echo (/*$i["simbolo"]." ".*/$i["capitalInicial"]);
							echo "</td>";
							echo "<td nowrap class='cell_right'>";
								echo (/*$i["simbolo"]." ".*/$i["capitalActual"]);
							echo "</td>";
							echo "<td nowrap class='cell_right'>";
								echo (/*$i["simbolo"]." ".*/$i["Provisionado"]);
							echo "</td>";
							echo "<td nowrap class='cell_right'>";
								echo (/*$i["simbolo"]." ".*/$i["cuotaPromedio"]);
							echo "</td>";
							echo "<td nowrap class='cell_right'>";
								echo ($i["interesMensual"]."%");
							echo "</td>";
							echo "<td nowrap class='cell_right'>";
								echo ($i["interesAnual"]."%");
							echo "</td>";
							echo "<td nowrap class='cell_right'>";
								echo ($i["numeroCuotas"]);
							echo "</td>";
							echo "<td nowrap class='cell_right'>";
								echo ($i["numeroDeMeses"]);
							echo "</td>";
							echo "<td nowrap class='cell_right'>";
								echo ($i["frecuenciaPagoEnDia"]);
							echo "</td>";
							echo "<td nowrap class='cell_left'>";
								echo ($i["amortizacion"]);
							echo "</td>";
							echo "<td nowrap class='cell_left'>";
								echo ($i["moneda"]);
							echo "</td>";
							echo "<td nowrap class='cell_left'>";
								echo ("'".$i["ultimaFecha"]);
							echo "</td>";
							echo "<td nowrap class='cell_right'>";
								echo ($i["diasParaCancelar"]); 
							echo "</td>";
							echo "<td nowrap class='cell_right'>";
								echo ($i["mesParaCancelar"]);
							echo "</td>";
							echo "<td nowrap class='cell_right'>";
								echo ($i["mesParaCancelar%"]."%");
							echo "</td>";
						echo "</tr>";
					}
					?>
				</tbody>
			</table>
		</div>
	</body>	
</html>
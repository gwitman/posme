<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<title>Estado Cuenta Cliente ...<?php echo $objFirmaEncription; ?></title>
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
						<th colspan="11">ESTADO DE CUENTA</th>
					</tr>
					<tr>
						<th colspan="11"><?php echo strtoupper($objCompany->name); ?></th>
					</tr>
					<tr>
						<th colspan="11">ESTADO DE CUENTA DEL CLIENTE <?php echo $objClient["customerNumber"]; ?></th>
					</tr>
				</thead>
			</table>
		</div>
		
		<div class="data_grid_encabezado">
			<table>
				<thead>
					<tr>
						<th colspan="11">INFORMACION DEL CLIENTE</th>
					</tr>
				</thead>
			</table>
		</div>
		
		<div class="data_grid_left">
			<table>
				<tbody>
					<tr>
						<td nowrap>Nombre</td>
						<td nowrap><?php echo $objClient["legalName"]; ?></td>
					</tr>
					<tr>
						<td nowrap><?php echo $objClient["identificationType"]; ?></td>
						<td nowrap><?php echo $objClient["identification"]; ?></td>
					</tr>
					<tr>
						<td nowrap>Pais</td>
						<td nowrap><?php echo $objClient["country"]; ?></td>
					</tr>
					<tr>
						<td nowrap>Departamento</td>
						<td nowrap><?php echo $objClient["state"]; ?></td>
					</tr>
					<tr>
						<td nowrap>Ciudad</td>
						<td nowrap><?php echo $objClient["city"]; ?></td>
					</tr>
					<tr>
						<td nowrap>Nacimiento</td>
						<td nowrap><?php echo "'".$objClient["birth"]; ?></td>
					</tr>
					<tr>
						<td nowrap>Estado</td>
						<td nowrap><?php echo $objClient["statusClient"]; ?></td>
					</tr>
					<tr>
						<td nowrap>Limite</td>
						<td nowrap><?php echo number_format($objClient["limitCreditCordoba"],2); ?></td>
					</tr>
					<tr>
						<td nowrap>Balance</td>
						<td nowrap><?php echo number_format($objClient["balanceCordoba"],2); ?></td>
					</tr>
					<tr>
						<td nowrap>Ingresos</td>
						<td nowrap><?php echo number_format($objClient["incomeCordoba"],2); ?></td>
					</tr>
				</tbody>
			</table>
		</div>
		<br/>
		
		<div class="data_grid_encabezado">
			<table>
				<thead>
					<tr>
						<th colspan="11">LINEAS DE CREDITO</th>
					</tr>
				</thead>
			</table>
		</div>
		
		<div class="data_grid_body">
			<table>
				<thead>
					<tr>
						<th nowrap class="cell_left">Linea</th>
						<th nowrap class="cell_left">No</th>
						<th nowrap class="cell_right">Limite</th>
						<th nowrap class="cell_right">Balance</th>
						<th nowrap class="cell_right">Interes</th>
						<th nowrap class="cell_right">Plazo</th>
						<th nowrap class="cell_right">Periodo</th>
						<th nowrap class="cell_right" colspan="4">Estado</th>
					</tr>
				</thead>				
				<tbody>
					<?php
					$count 		= 0;
					if($objLine)
					foreach($objLine as $i){
						$count++;						
						echo "<tr>";
							echo "<td nowrap class='cell_left'>";
								echo ($i["lineName"]);
							echo "</td>";
							echo "<td nowrap class='cell_left'>";
								echo ($i["lineNumber"]);
							echo "</td>";
							echo "<td nowrap class='cell_right'>";
								echo number_format($i["limitCreditCordobaLinea"],2);
							echo "</td>";
							echo "<td nowrap class='cell_right'>";
								echo number_format($i["balanceCordobaLinea"],2);
							echo "</td>";
							echo "<td nowrap class='cell_right'>";
								echo number_format($i["interestYearLine"],2);
							echo "</td>";
							echo "<td nowrap class='cell_right'>";
								echo number_format($i["termLine"],2);
							echo "</td>";
							echo "<td nowrap class='cell_right'>";
								echo ($i["periodPayLine"]);
							echo "</td>";
							echo "<td nowrap class='cell_right' colspan='4'>";
								echo ($i["statusLine"]);
							echo "</td>";
						echo "</tr>";
					}
					?>
				</tbody>
				<tfoot>
					<tr>
						<th nowrap class="cell_left">Linea</th>
						<th nowrap class="cell_left">No</th>
						<th nowrap class="cell_right">Limite</th>
						<th nowrap class="cell_right">Balance</th>
						<th nowrap class="cell_right">Interes</th>
						<th nowrap class="cell_right">Plazo</th>
						<th nowrap class="cell_right">Periodo</th>
						<th nowrap class="cell_right" colspan="4">Estado</th>
					</tr>
				</tfoot>
			</table>
		</div>
		<br/>		
		
		<div class="data_grid_encabezado">
			<table>
				<thead>
					<tr>
						<th colspan="11">DOCUMENTOS DE CREDITO</th>
					</tr>
				</thead>
			</table>
		</div>
		
		<div class="data_grid_body">
			<table>
				<thead>
					<tr>
						<th nowrap class="cell_left" style="width:75px;">Linea</th>
						<th nowrap class="cell_left" style="width:75px;">Documento</th>
						<th nowrap class="cell_left" style="width:75px;">Desembolso</th>
						<th nowrap class="cell_left" style="width:120px;" >Capital Inicial</th>
						<th nowrap class="cell_left" style="width:120px;" >% Interes Anual</th>
						<th nowrap class="cell_left" style="width:120px;" >% Interes Anual/120</th>
						<th nowrap class="cell_right" style="width:120px;"  ># Cuotas</th>
						<th nowrap class="cell_right" style="width:75px;" >Periodo</th>
						<th nowrap class="cell_right" style="width:75px;"  >Estado</th>
						<th nowrap class="cell_right" style="width:75px;" >Moneda</th>
						<th nowrap class="cell_right" style="width:120px;" >Capital Saldo</th>
						<th nowrap class="cell_right" style="width:120px;" >Dias Atrasados</th>
						
						<th nowrap class="cell_right" style="width:180px;" >Monto Atrasado</th>
						
						<th nowrap class="cell_right" style="width:215px;" >Monto Total de Intereses del Credito</th>
						
						<th nowrap class="cell_right" style="width:210px;" >Vencimiento de Ultima Cuota</th>
						
						<th nowrap class="cell_right" style="width:220px;" >Promedio de Pago en Dia</th>
						
						<th nowrap class="cell_right" style="width:250px;" >La ultima cuota fue cancelada a los X Dias</th>
						
						<th nowrap class="cell_right" style="width:220px;" >Nota</th>
					</tr>
				</thead>				
				<tbody>
					<?php
					$count 		= 0;
					if($objDocument)
					foreach($objDocument as $i){
						$count++;	
						if ($i["statusDocument"] == "REGISTRADO") 
						echo "<tr style='background-color:aqua'>";
						else
						echo "<tr>";
							echo "<td nowrap class='cell_left'>";
								echo ($i["lineNumber"]);
							echo "</td>";
							echo "<td nowrap class='cell_left'>";
								echo "<a href='".site_url()."app_cxc_report/document_credit/viewReport/true/documentNumber/".$i["documentNumber"]."' target='_blank'>".$i["documentNumber"]."</a>";
							echo "</td>";
							echo "<td nowrap class='cell_left'>";
								echo ("'".$i["documentOn"]);
							echo "</td>";
							
							echo "<td nowrap class='cell_right'>";
								echo number_format($i["amountDocument"],2);
							echo "</td>";
							echo "<td nowrap class='cell_right'>";
								echo number_format($i["interesDocument"],2);
							echo "</td>";
							echo "<td nowrap class='cell_right'>";
								echo number_format($i["interesDocumentMultiploDe120"],2);
							echo "</td>";
							
							echo "<td nowrap class='cell_right'>";
								echo number_format($i["termDocument"],2);
							echo "</td>";
							echo "<td nowrap class='cell_right'>";
								echo $i["periodPayDocument"];
							echo "</td>";
							echo "<td nowrap class='cell_right'>";
								echo $i["statusDocument"];
							echo "</td>";
							echo "<td nowrap class='cell_right'>";
								echo ($i["moneda"]);
							echo "</td>";
							echo "<td nowrap class='cell_right'>";
								echo ($i["balanceDocument"]);
							echo "</td>";
							echo "<td nowrap class='cell_right'>";
								echo ($i["dayAtrazo"]);
							echo "</td>";
							
							echo "<td nowrap class='cell_right'>";
								echo ($i["amountAtrazo"]);
							echo "</td>";
							
							echo "<td nowrap class='cell_right'>";
								echo ($i["interestTotalMontoDocument"]);
							echo "</td>";
							
							echo "<td nowrap class='cell_right'>";
								echo ($i["vencimientoUltimaCuota"]);
							echo "</td>";
							
							echo "<td nowrap class='cell_right'>";
								echo ($i["promedioDiaPago"]);
							echo "</td>";
							
							echo "<td nowrap class='cell_right'>";
								echo ($i["atrasoCancelacionDia"]);
							echo "</td>";
							
							echo "<td nowrap class='cell_right'>";
								echo ($i["nota"]);
							echo "</td>";
						echo "</tr>";
					}
					?>
				</tbody>
				<tfoot>
					<tr>
						<th nowrap class="cell_left">Linea</th>
						<th nowrap class="cell_left">Documento</th>
						<th nowrap class="cell_left">Desembolso</th>
						<th nowrap class="cell_left">Capital Inicial</th>
						<th nowrap class="cell_left">% Interes Anual</th>
						<th nowrap class="cell_left">% Interes Anual/120</th>
						<th nowrap class="cell_right"># Cuotas</th>
						<th nowrap class="cell_right">Periodo</th>
						<th nowrap class="cell_right">Estado</th>
						<th nowrap class="cell_right">Moneda</th>
						<th nowrap class="cell_right">Capital Saldo</th>
						<th nowrap class="cell_right">Dias Atrasados</th>
						
						<th nowrap class="cell_right">Monto Atrasado</th>
						
						<th nowrap class="cell_right">Monto Total de Intereses del Credito</th>
						
						<th nowrap class="cell_right">Vencimiento de Ultima Cuota</th>
						
						<th nowrap class="cell_right">Promedio de Pago en Dia</th>
						
						<th nowrap class="cell_right">La ultima cuota fue cancelada a los X Dias</th>
						
						<th nowrap class="cell_right">Nota</th>
					</tr>
				</tfoot>
			</table>
		</div>
		<br/>		
		
		
		<div class="data_grid_firm_system">
			<table>
				<tbody>
					<tr>
						<td colspan="11"><?php echo date("Y-m-d H:i:s");  ?> <?php echo $objFirmaEncription; ?></td>
					</tr>
				</tbody>
			</table>
		</div>
		[
]	</body>	
</html>
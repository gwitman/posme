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
						<th colspan="8">LISTADO DE PAGOS</th>
					</tr>
					<tr>
						<th colspan="8"><?php echo strtoupper($objCompany->name); ?></th>
					</tr>
					<tr>
						<th colspan="8">LISTADO DE ABONOS O PAGOS DEL CLIENTE <?php echo $objClient["customerNumber"]; ?></th>
					</tr>
				</thead>
			</table>
		</div>
		
		<div class="data_grid_encabezado">
			<table>
				<thead>
					<tr>
						<th colspan="8">INFORMACION DEL CLIENTE</th>
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
						<td nowrap><?php echo $objClient["birth"]; ?></td>
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
						<th colspan="8">LINEAS DE CREDITO</th>
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
						<th nowrap class="cell_right">Estado</th>
					</tr>
				</thead>				
				<tbody>
					<?php
					$count 		= 0;
					if($objLine)
					foreach($objLine as $i){
						$count++;						
						echo "<tr>";
							echo "<td nowrap  class='cell_left'>";
								echo ($i["lineName"]);
							echo "</td>";
							echo "<td nowrap  class='cell_left'>";
								echo ($i["lineNumber"]);
							echo "</td>";
							echo "<td nowrap class='cell_right'>";
								echo number_format($i["limitCreditCordobaLinea"],2);
							echo "</td>";
							echo "<td nowrap  class='cell_right'>";
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
							echo "<td nowrap class='cell_right'>";
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
						<th nowrap class="cell_right">Estado</th>
					</tr>
				</tfoot>
			</table>
		</div>
		<br/>		
		
		<div class="data_grid_encabezado">
			<table>
				<thead>
					<tr>
						<th colspan="8">DOCUMENTOS DE CREDITO</th>
					</tr>
				</thead>
			</table>
		</div>
		
		<div class="data_grid_body">
			<table>
				<thead>
					<tr>
						<th nowrap class="cell_left">Linea</th>
						<th nowrap class="cell_left">Documento</th>
						<th nowrap class="cell_left">Fecha</th>
						<th nowrap class="cell_right">Monto</th>
						<th nowrap class="cell_right">Interes</th>
						<th nowrap class="cell_right">Plazo</th>
						<th nowrap class="cell_right">Estado</th>
						<th nowrap class="cell_right">Moneda</th>
					</tr>
				</thead>				
				<tbody>
					<?php
					$count 		= 0;
					if($objDocument)
					foreach($objDocument as $i){
						$count++;						
						echo "<tr>";
							echo "<td nowrap  class='cell_left'>";
								echo ($i["lineNumber"]);
							echo "</td>";
							echo "<td nowrap  class='cell_left'>";
								echo ($i["documentNumber"]);
							echo "</td>";
							echo "<td nowrap class='cell_left'>";
								echo ($i["documentOn"]);
							echo "</td>";
							echo "<td nowrap class='cell_right'>";
								echo number_format($i["amountDocument"],2);
							echo "</td>";
							echo "<td nowrap class='cell_right'>";
								echo number_format($i["interesDocument"],2);
							echo "</td>";
							echo "<td nowrap class='cell_right'>";
								echo number_format($i["termDocument"],2);
							echo "</td>";
							echo "<td nowrap  class='cell_right'>";
								echo ($i["statusDocument"]);
							echo "</td>";
							echo "<td nowrap  class='cell_right'>";
								echo ($i["moneda"]);
							echo "</td>";
						echo "</tr>";
					}
					?>
				</tbody>
				<tfoot>
					<tr>
						<th nowrap class="cell_left">Linea</th>
						<th nowrap class="cell_left">Documento</th>
						<th nowrap class="cell_left">Fecha</th>
						<th nowrap class="cell_right">Monto</th>
						<th nowrap class="cell_right">Interes</th>
						<th nowrap class="cell_right">Plazo</th>
						<th nowrap class="cell_right">Estado</th>
						<th nowrap class="cell_right">Moneda</th>
					</tr>
				</tfoot>
			</table>
		</div>
		<br/>
		
		<div class="data_grid_encabezado">
			<table>
				<thead>
					<tr>
						<th colspan="9">PAGOS</th>
					</tr>
				</thead>
			</table>
		</div>		
		<div class="data_grid_body">
			<table>
				<thead>
					<tr>
						<th nowrap class="cell_left">Fecha</th>
						<th nowrap class="cell_left">No Pago</th>
						<th nowrap class="cell_left">Referencia</th>
						<th nowrap class="cell_left">Moneda</th>						
						<th nowrap class="cell_right">Abono</th>
						<th nowrap class="cell_right">Saldo Anterior</th>
						<th nowrap class="cell_right">Saldo Nuevo</th>
						<th nowrap colspan="2" class="cell_right">Nota</th>
					</tr>
				</thead>				
				<tbody>
					<?php
					$count 		= 0;
					if($objPayList)
					foreach($objPayList as $i){
						$count++;						
						echo "<tr>";
							echo "<td nowrap class='cell_left'>";
								echo ($i["createdOn"]);
							echo "</td>";
							echo "<td nowrap class='cell_left'>";
								echo ($i["transactionNumber"]);
							echo "</td>";
							echo "<td nowrap class='cell_left'>";
								echo ($i["reference1"]);
							echo "</td>";
							echo "<td nowrap class='cell_left'>";
								echo ($i["MonedaDesembolso"]);
							echo "</td>";							
							echo "<td nowrap class='cell_right'>";
								echo number_format($i["Pago"],2);
							echo "</td>";
							echo "<td nowrap class='cell_right'>";
								echo number_format($i["SaldoAterior"],2);
							echo "</td>";
							echo "<td nowrap class='cell_right'>";
								echo number_format($i["SaldoNuevo"],2);
							echo "</td>";
							echo "<td nowrap colspan='2' class='cell_right'>";
								echo substr($i["note"],0,60);
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
						<td colspan="8"><?php echo date("Y-m-d H:i:s");  ?> <?php echo $objFirmaEncription; ?></td>
					</tr>
				</tbody>
			</table>
		</div>
		

	</body>	
</html>
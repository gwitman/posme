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
						<th colspan='13'><?php echo strtoupper($objCompany->name); ?></th>
					</tr>
					<tr>
						<th colspan='13'>DEL <?php echo $startOn; ?> AL <?php echo $endOn; ?></th>
					</tr>
					<tr>
						<th colspan='13'>&nbsp;</th>
					</tr>
					<tr>
						<th colspan='13'>&nbsp;</th>
					</tr>
					<tr>
						<th colspan='13'>LISTA DE ABONOS/ABONO AL CAPITAL/CANCELCIN DE FACTURAS</th>
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
						<th nowrap class="cell_right">Monto Total</th>
						<th nowrap class="cell_right">Usuario</th>						
						<th nowrap class="cell_right">Nota</th>
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
							echo "<td nowrap class='cell_right'>";
								echo sprintf("%01.2f",$i["montoCordoba"] );
							echo "</td>";
							
							echo "<td nowrap  class='cell_right'>";
								echo ($i["nickname"]);
							echo "</td>";
							
							echo "<td nowrap  class='cell_right'>";
								echo ($i["note"]);
							echo "</td>";
						echo "</tr>";
					}
					?>
				</tbody>
				<footer>					
					<tr>
						<th nowrap class="cell_left" colspan="8" >SUB TOTAL</th>
						
						<?php
						if($count == 0){
						?>
							<th nowrap class="cell_right">0</th>
						<?php
						}
						else{
						?>
							<th nowrap class="cell_right">=SUMA(I8:I<?php echo $count+7;?>)</th>
						<?php
						}
						?>	
						
						
						<th nowrap class="cell_right"></th>						
						
						<?php
						if($count == 0){
						?>
							<th nowrap class="cell_right">0</th>
						<?php
						}
						else{
						?>
							<th nowrap class="cell_right">=SUMA(K8:K<?php echo $count+7;?>)</th>
						<?php
						}
						?>	
						
						
						
						<th nowrap class="cell_right"></th>	
						
						<th nowrap class="cell_right"></th>	
					</tr>
				</footer>	
			</table>
		</div>
		<br/>
		<div class="data_grid_encabezado">
			<table>
				<thead>					
					<tr>
						<th colspan='13'>VENTA DE CONTADO</th>
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
						<th nowrap class="cell_left">Tipo</th>
						<th nowrap class="cell_left">Tran. Number</th>
						<th nowrap class="cell_left">Estado</th>
						<th nowrap class="cell_right">Monto</th>
						<th nowrap class="cell_right">Tipo Cambio</th>
						<th nowrap class="cell_right">Monto Total</th>
						<th nowrap class="cell_right">Usuario</th>						
						<th nowrap class="cell_right">Nota</th>
					</tr>
				</thead>				
				<tbody>
					<?php
					$count2 		= 0;
					if($objSales)
					foreach($objSales as $i){
						
						if($i["tipo"] == "CREDITO"){
							continue;
						}
						
						$count2++;
						echo "<tr>";
							echo "<td nowrap class='cell_left'>";
								echo ($i["customerNumber"]);
							echo "</td>";
							echo "<td nowrap class='cell_left'>";
								echo ($i["firstName"]);
							echo "</td>";
							echo "<td nowrap class='cell_left'>";
								echo ($i["currencyName"]);
							echo "</td>";
							echo "<td nowrap class='cell_left'>";
								echo "'".date_format(date_create($i["transactionOn"]),'Y-m-d');
							echo "</td>";
							echo "<td nowrap class='cell_left'>";
								echo ($i["transactionNumber"]);
							echo "</td>";
							echo "<td nowrap class='cell_left'>";
								echo ($i["tipo"]);
							echo "</td>";
							echo "<td nowrap class='cell_left'>";
								echo ($i["transactionNumber"]);
							echo "</td>";
							echo "<td nowrap  class='cell_left'>";
								echo ($i["statusName"]);
							echo "</td>";
							echo "<td nowrap class='cell_right'>";
								echo sprintf("%01.2f",$i["totalDocument"]);
							echo "</td>";
							echo "<td nowrap class='cell_right'>";
								echo sprintf("%01.2f",$i["exchangeRate"]);
							echo "</td>";
							echo "<td nowrap class='cell_right'>";
								echo sprintf("%01.2f",$i["totalDocument"] );
							echo "</td>";
							
							echo "<td nowrap  class='cell_right'>";
								echo ($i["nickname"]);
							echo "</td>";
							
							echo "<td nowrap  class='cell_right'>";
								//echo ($i["note"]);
							echo "</td>";
						echo "</tr>";
					}
					?>
				</tbody>
				<footer>					
					<tr>
						<th nowrap class="cell_left" colspan="8" >SUB TOTAL</th>
						
						<?php
						if($count2 == 0){
						?>
							<th nowrap class="cell_right">0</th>
						<?php
						}
						else{
						?>
							<th nowrap class="cell_right">=SUMA(I<?php echo $count+13;?>:I<?php echo $count2+$count+12;?>)</th>
						<?php
						}
						?>	
						
						
						<th nowrap class="cell_right"></th>		
						<?php
						if($count2 == 0){
						?>
							<th nowrap class="cell_right">0</th>
						<?php
						}
						else{
						?>
							<th nowrap class="cell_right">=SUMA(K<?php echo $count+13;?>:K<?php echo $count2+$count+12;?>)</th>
						<?php
						}
						?>	
						
						
						
						<th nowrap class="cell_right"></th>	
						
						<th nowrap class="cell_right"></th>	
					</tr>
				</footer>	
			
			</table>
		</div>
		<br/>
		<div class="data_grid_encabezado">
			<table>
				<thead>					
					<tr>
						<th colspan='13'>INGRESO DE EFECTIVO</th>
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
						<th nowrap class="cell_left">Tipo</th>
						<th nowrap class="cell_left">Tran. Number</th>
						<th nowrap class="cell_left">Estado</th>
						<th nowrap class="cell_right">Monto</th>
						<th nowrap class="cell_right">Tipo Cambio</th>
						<th nowrap class="cell_right">Monto Total</th>
						<th nowrap class="cell_right">Usuario</th>						
						<th nowrap class="cell_right">Nota</th>
					</tr>
				</thead>				
				<tbody>
					<?php
					$count3 		= 0;
					if($objCash)
					foreach($objCash as $i){
						
						if($i["transactionName"] == "EGRESO A CAJA"){
							continue;
						}
						
						
						$count3++;
						echo "<tr>";
							echo "<td nowrap class='cell_left'>";
								//echo ($i["customerNumber"]);
							echo "</td>";
							echo "<td nowrap class='cell_left'>";
								//echo ($i["firstName"]);
							echo "</td>";
							echo "<td nowrap class='cell_left'>";
								echo ($i["moneda"]);
							echo "</td>";
							echo "<td nowrap class='cell_left'>";
								echo "'".date_format(date_create($i["transactionOn"]),'Y-m-d');
							echo "</td>";
							echo "<td nowrap class='cell_left'>";
								echo ($i["transactionNumber"]);
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
								echo sprintf("%01.2f",$i["montoTransaccion"]);
							echo "</td>";
							echo "<td nowrap class='cell_right'>";
								echo sprintf("%01.2f",$i["tipoCambio"]);
							echo "</td>";
							echo "<td nowrap class='cell_right'>";
								echo sprintf("%01.2f",$i["montoTransaccion"] );
							echo "</td>";
							
							echo "<td nowrap  class='cell_right'>";
								echo ($i["nickname"]);
							echo "</td>";
							
							echo "<td nowrap  class='cell_right'>";
								echo ($i["note"]);
							echo "</td>";
						echo "</tr>";
					}
					?>
				</tbody>
				<footer>					
					<tr>
						<th nowrap class="cell_left" colspan="8" >SUB TOTAL</th>
						<?php
						if($count3 == 0){
						?>
							<th nowrap class="cell_right">0</th>
						<?php
						}
						else{
						?>
							<th nowrap class="cell_right">=SUMA(I<?php echo $count2+$count+18;?>:I<?php echo $count3+$count2+$count+17;?>)</th>
						<?php
						}
						?>						
						<th nowrap class="cell_right"></th>						
						
						
						<?php
						if($count3 == 0){
						?>
							<th nowrap class="cell_right">0</th>
						<?php
						}
						else{
						?>
							<th nowrap class="cell_right">=SUMA(K<?php echo $count2+$count+18;?>:K<?php echo $count3+$count2+$count+17;?>)</th>
						<?php
						}
						?>	
						
						
						<th nowrap class="cell_right"></th>	
						
						<th nowrap class="cell_right"></th>	
					</tr>
				</footer>	
				
			</table>
		</div>
		<br/>
		<div class="data_grid_encabezado">
			<table>
				<thead>					
					<tr>
						<th colspan='13'>SALIDA DE EFECTIVO</th>
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
						<th nowrap class="cell_left">Tipo</th>
						<th nowrap class="cell_left">Tran. Number</th>
						<th nowrap class="cell_left">Estado</th>
						<th nowrap class="cell_right">Monto</th>
						<th nowrap class="cell_right">Tipo Cambio</th>
						<th nowrap class="cell_right">Monto Total</th>
						<th nowrap class="cell_right">Usuario</th>						
						<th nowrap class="cell_right">Nota</th>
					</tr>
				</thead>				
				<tbody>
					<?php
					$count4 		= 0;
					if($objCash)
					foreach($objCash as $i){
						
						if($i["transactionName"] == "INGRESO DE CAJA"){
							continue;
						}
						
						$count4++;
						echo "<tr>";
							echo "<td nowrap class='cell_left'>";
								//echo ($i["customerNumber"]);
							echo "</td>";
							echo "<td nowrap class='cell_left'>";
								//echo ($i["firstName"]);
							echo "</td>";
							echo "<td nowrap class='cell_left'>";
								echo ($i["moneda"]);
							echo "</td>";
							echo "<td nowrap class='cell_left'>";
								echo "'".date_format(date_create($i["transactionOn"]),'Y-m-d');
							echo "</td>";
							echo "<td nowrap class='cell_left'>";
								echo ($i["transactionNumber"]);
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
								echo sprintf("%01.2f",$i["montoTransaccion"]);
							echo "</td>";
							echo "<td nowrap class='cell_right'>";
								echo sprintf("%01.2f",$i["tipoCambio"]);
							echo "</td>";
							echo "<td nowrap class='cell_right'>";
								echo sprintf("%01.2f",$i["montoTransaccion"] );
							echo "</td>";
							
							echo "<td nowrap  class='cell_right'>";
								echo ($i["nickname"]);
							echo "</td>";
							
							echo "<td nowrap  class='cell_right'>";
								echo ($i["note"]);
							echo "</td>";
						echo "</tr>";
					}
					?>
				</tbody>
				<footer>					
					<tr>
						<th nowrap class="cell_left" colspan="8" >SUB TOTAL</th>
						
						<?php
						if($count4 == 0){
						?>
							<th nowrap class="cell_right">0</th>
						<?php
						}
						else{
						?>
							<th nowrap class="cell_right">=SUMA(I<?php echo $count3+$count2+$count+23;?>:I<?php echo $count4+$count3+$count2+$count+22;?>)</th>
						<?php
						}
						?>	
						
						
						<th nowrap class="cell_right"></th>	
						<?php
						if($count4 == 0){
						?>
							<th nowrap class="cell_right">0</th>
						<?php
						}
						else{
						?>
							<th nowrap class="cell_right">=SUMA(K<?php echo $count3+$count2+$count+23;?>:K<?php echo $count4+$count3+$count2+$count+22;?>)</th>
						<?php
						}
						?>	
						
						<th nowrap class="cell_right"></th>	
						
						<th nowrap class="cell_right"></th>	
					</tr>
				</footer>	
				<footer>					
					<tr>
						<th nowrap class="cell_left" colspan="8" >TOTAL</th>
						<th nowrap class="cell_right">=I<?php echo $count+8;?>+I<?php echo $count+13+$count2;?>+I<?php echo $count+18+$count2+$count3;?>-I<?php echo $count+23+$count2+$count3+$count4;?></th>
						<th nowrap class="cell_right"></th>						
						<th nowrap class="cell_right">=K<?php echo $count+8;?>+K<?php echo $count+13+$count2;?>+K<?php echo $count+18+$count2+$count3;?>-K<?php echo $count+23+$count2+$count3+$count4;?></th>
						
						<th nowrap class="cell_right"></th>	
						
						<th nowrap class="cell_right"></th>	
					</tr>
				</footer>	
			</table>
		</div>
		</br>
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
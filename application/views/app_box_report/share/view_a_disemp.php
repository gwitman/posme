<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<title><?php echo $objFirmaEncription; ?></title>
		<meta name="viewport" 			content="width=device-width, initial-scale=1.0">
		<meta name="application-name" 	content="dsemp" /> 
		
		<style>
		
			table, td, tr, th {
				border-collapse: collapse;
			}
			
			.border {
				border-color:black;
				border:solid 1px black;						
			}
				
		</style>
		
		
	</head>
	<body style="font-family:monospace;font-size:smaller;margin:0px 0px 0px 0px"> 
		
		
		
		<table style="
			width:100%;border-spacing: 10px;			
		">
			<thead>
				<tr>
					<th colspan="3" rowspan="5" style="text-align:left;width:130px">
						<img width="120" height="110" 						
							style="
								width: 120px;
								height: 110px;
							"
							
							src="<?php echo site_url();  ?>/img/logos/logo-micro-finanza.jpg" 
						/>
					</th>
					<th colspan="10" style="
						text-align:right;background-color:#00628e;color:white;
						width:80%
					"><?php echo strtoupper($objCompany->name); ?></th>
				</tr>
				<tr>
					<th colspan="10" style="
						text-align:right;background-color:#00628e;color:white;
					">DEL <?php echo $startOn; ?> AL <?php echo $endOn; ?></th>
				</tr>
				<tr>
					<th colspan="10"  style="text-align:left">&nbsp;</th>
				</tr>
				<tr>
					<th colspan="10" style="text-align:left">&nbsp;</th>
				</tr>
				<tr>
					<th colspan="10"  style="text-align:left">&nbsp;</th>
				</tr>
				<tr>
					<th colspan="13" style="text-align:left">
						&nbsp;
					</th>
				</tr>
			</thead>
		</table>
		

		<br/>	
		</br>		

		<table style="
			width:100%;order-spacing: 10px;
		" >
			<thead>		
				<tr style="background-color:#00628e;color:white;" class='border'>
					<!--812-->
					<th colspan='13'>LISTA DE ABONOS/ABONO AL CAPITAL/CANCELCIN DE FACTURAS</th>
				</tr>
				<tr style="background-color:#00628e;color:white;">
					<!--812 vertical --> 
					<!--1017.07 horizontal --> 
					<th nowrap style="text-align:left;width:84px;"  class='border'>Codigo</th>
					<th nowrap style="text-align:left;width:180px;" class='border'>Cliente</th>
					<th nowrap style="text-align:left;width:45px;" class='border'>Moneda</th>
					<th nowrap style="text-align:left;width:100px;" class='border'>Fecha</th>						
					<th nowrap style="text-align:left;width:84px;" class='border'>Fac</th>
					<th nowrap style="text-align:left;width:170px;" class='border'>Transaccion</th>
					<th nowrap style="text-align:left;width:84px;" class='border'>Tran. Number</th>
					<th nowrap style="text-align:left;width:50px;" class='border'>Estado</th>
					<th nowrap style="text-align:left;width:84px;"  class='border'>Monto</th>
					<th nowrap style="text-align:left;width:84px;"  class='border'>Tipo Cambio</th>
					<th nowrap style="text-align:left;width:84px;"  class='border'>Monto Total</th>
					<th nowrap style="text-align:left;width:84px;"  class='border'>Usuario</th>						
					<th nowrap style="text-align:left;width:170px;"  class='border'>Nota</th>
				</tr>
			</thead>				
			<tbody>
				<?php
				$count 		= 0;
				$montoTotal = 0;
				$montoGeneral = 0;
				
				if($objDetail)
				foreach($objDetail as $i){
					$count++;
					$montoTotal = $montoTotal + $i["montoCordoba"];
					echo "<tr>";
						echo "<td nowrap style='text-align:left' class='border' >";
							echo ($i["customerNumber"]);
						echo "</td>";
						echo "<td nowrap style='text-align:left' class='border' >";
							echo ($i["firstName"]);
						echo "</td>";
						echo "<td nowrap style='text-align:left' class='border' >";
							echo ($i["moneda"]);
						echo "</td>";
							echo "<td nowrap style='text-align:left' class='border' >";
							echo "'".date_format(date_create($i["transactionOn"]),'Y-m-d');
						echo "</td>";
							echo "<td nowrap style='text-align:left' class='border' >";
							echo ($i["Fac"]);
						echo "</td>";
						echo "<td nowrap style='text-align:left' class='border' >";
							echo ($i["transactionName"]);
						echo "</td>";
						echo "<td nowrap style='text-align:left' class='border' >";
							echo ($i["transactionNumber"]);
						echo "</td>";
						echo "<td nowrap  style='text-align:left' class='border' >";
							echo ($i["estado"]);
						echo "</td>";
						echo "<td nowrap style='text-align:right' class='border' >";							
							echo (number_format($i["montoFac"],2,'.',','));
						echo "</td>";
						echo "<td nowrap style='text-align:right' class='border' >";							
							echo (number_format($i["tipoCambio"],2,'.',','));
						echo "</td>";
						echo "<td nowrap style='text-align:right' class='border' >";							
							echo (number_format($i["montoCordoba"],2,'.',','));
						echo "</td>";
						
						echo "<td nowrap  style='text-align:right' class='border' >";
							echo ($i["nickname"]);
						echo "</td>";
						
						echo "<td nowrap  style='text-align:right' class='border' >";
							echo ($i["note"]);
						echo "</td>";
					echo "</tr>";
				}
				$montoGeneral = $montoGeneral + $montoTotal;
				?>
			</tbody>
			<footer>					
				<tr>
					<th nowrap style="text-align:left"  colspan="8" >SUB TOTAL</th>
					
					<?php
					if($count == 0){
					?>
						<th nowrap style="text-align:right"  >0</th>
					<?php
					}
					else{
					?>
						<th nowrap style="text-align:right"  ></th>
					<?php
					}
					?>	
					
					
					<th nowrap style="text-align:right"  ></th>						
					
					<?php
					if($count == 0){
					?>
						<th nowrap style="text-align:right"  >0</th>
					<?php
					}
					else{
					?>
						<th nowrap style="text-align:right"  ><?php echo number_format($montoTotal,2,'.',',');  ?></th>
					<?php
					}
					?>	
					
					
					
					<th nowrap style="text-align:right"  ></th>	
					
					<th nowrap style="text-align:right"  ></th>	
				</tr>
			</footer>	
		</table>
		
		<br/>
		</br>
		
		<table style="
			width:100%;order-spacing: 10px;
		" >
			<thead>	
				<tr style="background-color:#00628e;color:white;" class='border'>
					<!--812-->
					<th colspan='13'>VENTA DE CONTADO</th>
				</tr>
				<tr style="background-color:#00628e;color:white;">
					<!--812-->
					<th nowrap style="text-align:left;width:84px;"  class='border'>Codigo</th>
					<th nowrap style="text-align:left;width:180px;" class='border'>Cliente</th>
					<th nowrap style="text-align:left;width:45px;" class='border'>Moneda</th>
					<th nowrap style="text-align:left;width:100px;" class='border'>Fecha</th>						
					<th nowrap style="text-align:left;width:84px;" class='border'>Fac</th>					
					<th nowrap style="text-align:left;width:170px;" class='border'>Tipo</th>
					<th nowrap style="text-align:left;width:84px;" class='border'>Tran. Number</th>
					<th nowrap style="text-align:left;width:50px;" class='border'>Estado</th>
					<th nowrap style="text-align:left;width:84px;"  class='border'>Monto</th>					
					<th nowrap style="text-align:left;width:84px;"  class='border'>Tipo Cambio</th>
					<th nowrap style="text-align:left;width:84px;"  class='border'>Monto Total</th>
					<th nowrap style="text-align:left;width:84px;"  class='border'>Usuario</th>						
					<th nowrap style="text-align:left;width:170px;"  class='border'>Nota</th>
					
				</tr>
			</thead>				
			<tbody>
				<?php
				$count2 		= 0;
				$montoTotal 	= 0;
				if($objSales)
				foreach($objSales as $i){
					
					if($i["tipo"] == "CREDITO"){
						continue;
					}
					
					$count2++;
					$montoTotal = $montoTotal + $i["totalDocument"];
					echo "<tr>";
						echo "<td nowrap style='text-align:left' class='border' >";
							echo ($i["customerNumber"]);
						echo "</td>";
						echo "<td nowrap style='text-align:left' class='border' >";
							echo ($i["firstName"]);
						echo "</td>";
						echo "<td nowrap style='text-align:left' class='border' >";
							echo ($i["currencyName"]);
						echo "</td>";
						echo "<td nowrap style='text-align:left' class='border' >";
							echo "'".date_format(date_create($i["transactionOn"]),'Y-m-d');
						echo "</td>";
						echo "<td nowrap style='text-align:left' class='border' >";
							echo ($i["transactionNumber"]);
						echo "</td>";
						echo "<td nowrap style='text-align:left' class='border' >";
							echo ($i["tipo"]);
						echo "</td>";
						echo "<td nowrap style='text-align:left' class='border' >";
							echo ($i["transactionNumber"]);
						echo "</td>";
						echo "<td nowrap  style='text-align:left' class='border' >";
							echo ($i["statusName"]);
						echo "</td>";
						echo "<td nowrap style='text-align:right' class='border' >";							
							echo (number_format($i["totalDocument"],2,'.',','));
						echo "</td>";
						echo "<td nowrap style='text-align:right' class='border' >";							
							echo (number_format($i["exchangeRate"],2,'.',','));
						echo "</td>";
						echo "<td nowrap style='text-align:right' class='border' >";							
							echo (number_format($i["totalDocument"],2,'.',','));
						echo "</td>";
						
						echo "<td nowrap  style='text-align:right' class='border' >";
							echo ($i["nickname"]);
						echo "</td>";
						
						echo "<td nowrap  style='text-align:right' class='border' >";
							//echo ($i["note"]);
						echo "</td>";
					echo "</tr>";
				}
				$montoGeneral = $montoGeneral + $montoTotal;
				?>
			</tbody>
			<footer>					
				<tr>
					<th nowrap style="text-align:left" colspan="8" >SUB TOTAL</th>
					
					<?php
					if($count2 == 0){
					?>
						<th nowrap style="text-align:right"  >0</th>
					<?php
					}
					else{
					?>
						<th nowrap style="text-align:right"  ></th>
					<?php
					}
					?>	
					
					
					<th nowrap style="text-align:right"  ></th>		
					<?php
					if($count2 == 0){
					?>
						<th nowrap style="text-align:right"  >0</th>
					<?php
					}
					else{
					?>
						<th nowrap style="text-align:right"  ><?php echo number_format($montoTotal,2,'.',',');  ?></th>
					<?php
					}
					?>	
					
					
					
					<th nowrap style="text-align:right"  ></th>	
					
					<th nowrap style="text-align:right"  ></th>	
				</tr>
			</footer>	
		
		</table>
		
		<br/>		
		</br>
		
		<table style="
			width:100%;order-spacing: 10px;
		" >
			<thead>		
				<tr style="background-color:#00628e;color:white;" class='border'>
					<!--812-->
					<th colspan='13'>INGRESO DE EFECTIVO</th>
				</tr>
				<tr style="background-color:#00628e;color:white;">
					<!--812-->
					<th nowrap style="text-align:left;width:84px;"  class='border'>Codigo</th>
					<th nowrap style="text-align:left;width:180px;" class='border'>Cliente</th>
					<th nowrap style="text-align:left;width:45px;" class='border'>Moneda</th>
					<th nowrap style="text-align:left;width:100px;" class='border'>Fecha</th>						
					<th nowrap style="text-align:left;width:84px;" class='border'>Fac</th>					
					<th nowrap style="text-align:left;width:170px;" class='border'>Tipo</th>
					<th nowrap style="text-align:left;width:84px;" class='border'>Tran. Number</th>
					<th nowrap style="text-align:left;width:50px;" class='border'>Estado</th>
					<th nowrap style="text-align:left;width:84px;"  class='border'>Monto</th>					
					<th nowrap style="text-align:left;width:84px;"  class='border'>Tipo Cambio</th>
					<th nowrap style="text-align:left;width:84px;"  class='border'>Monto Total</th>
					<th nowrap style="text-align:left;width:84px;"  class='border'>Usuario</th>						
					<th nowrap style="text-align:left;width:170px;"  class='border'>Nota</th>
					
					
				</tr>
			</thead>				
			<tbody>
				<?php
				$count3 		= 0;
				$montoTotal 	= 0;
				if($objCash)
				foreach($objCash as $i){
					
					if($i["transactionName"] == "EGRESO A CAJA"){
						continue;
					}
					
					
					$count3++;
					$montoTotal = $montoTotal + $i["montoTransaccion"];
					echo "<tr>";
						echo "<td nowrap style='text-align:left' class='border' >";
							//echo ($i["customerNumber"]);
						echo "</td>";
						echo "<td nowrap style='text-align:left' class='border' >";
							//echo ($i["firstName"]);
						echo "</td>";
						echo "<td nowrap style='text-align:left' class='border' >";
							echo ($i["moneda"]);
						echo "</td>";
						echo "<td nowrap style='text-align:left' class='border' >";
							echo "'".date_format(date_create($i["transactionOn"]),'Y-m-d');
						echo "</td>";
						echo "<td nowrap style='text-align:left' class='border' >";
							echo ($i["transactionNumber"]);
						echo "</td>";
						echo "<td nowrap style='text-align:left' class='border' >";
							echo ($i["transactionName"]);
						echo "</td>";
						echo "<td nowrap style='text-align:left' class='border' >";
							echo ($i["transactionNumber"]);
						echo "</td>";
						echo "<td nowrap  style='text-align:left' class='border' >";
							echo ($i["estado"]);
						echo "</td>";
						echo "<td nowrap style='text-align:right' class='border' >";							
							echo (number_format($i["montoTransaccion"],2,'.',','));
						echo "</td>";
						echo "<td nowrap style='text-align:right' class='border' >";							
							echo (number_format($i["tipoCambio"],2,'.',','));
						echo "</td>";
						echo "<td nowrap style='text-align:right' class='border' >";							
							echo (number_format($i["montoTransaccion"],2,'.',','));
						echo "</td>";
						
						echo "<td nowrap  style='text-align:right' class='border' >";
							echo ($i["nickname"]);
						echo "</td>";
						
						echo "<td nowrap  style='text-align:right' class='border' >";
							echo ($i["note"]);
						echo "</td>";
					echo "</tr>";
				}
				$montoGeneral = $montoGeneral + $montoTotal;
				?>
			</tbody>
			<footer>					
				<tr>
					<th nowrap style="text-align:left"  colspan="8" >SUB TOTAL</th>
					<?php
					if($count3 == 0){
					?>
						<th nowrap style="text-align:right"  >0</th>
					<?php
					}
					else{
					?>
						<th nowrap style="text-align:right"  ></th>
					<?php
					}
					?>						
					<th nowrap style="text-align:right"  ></th>						
					
					
					<?php
					if($count3 == 0){
					?>
						<th nowrap style="text-align:right"  >0</th>
					<?php
					}
					else{
					?>
						<th nowrap style="text-align:right"  ><?php echo number_format($montoTotal,2,'.',',');  ?></th>
					<?php
					}
					?>	
					
					
					<th nowrap style="text-align:right"  ></th>	
					
					<th nowrap style="text-align:right"  ></th>	
				</tr>
			</footer>	
			
		</table>
		
		<br/>
		</br>
		
		<table style="
			width:100%;order-spacing: 10px;
		" >
			<thead>		
				<tr style="background-color:#00628e;color:white;" class='border'>
					<!--812-->
					<th colspan='13'>SALIDA DE EFECTIVO</th>
				</tr>
				<tr style="background-color:#00628e;color:white;">
					<!--812-->
					<th nowrap style="text-align:left;width:84px;"  class='border'>Codigo</th>
					<th nowrap style="text-align:left;width:180px;" class='border'>Cliente</th>
					<th nowrap style="text-align:left;width:45px;" class='border'>Moneda</th>
					<th nowrap style="text-align:left;width:100px;" class='border'>Fecha</th>						
					<th nowrap style="text-align:left;width:84px;" class='border'>Fac</th>					
					<th nowrap style="text-align:left;width:170px;" class='border'>Tipo</th>
					<th nowrap style="text-align:left;width:84px;" class='border'>Tran. Number</th>
					<th nowrap style="text-align:left;width:50px;" class='border'>Estado</th>
					<th nowrap style="text-align:left;width:84px;"  class='border'>Monto</th>					
					<th nowrap style="text-align:left;width:84px;"  class='border'>Tipo Cambio</th>
					<th nowrap style="text-align:left;width:84px;"  class='border'>Monto Total</th>
					<th nowrap style="text-align:left;width:84px;"  class='border'>Usuario</th>						
					<th nowrap style="text-align:left;width:170px;"  class='border'>Nota</th>
					
					
					
				</tr>
			</thead>				
			<tbody>
				<?php
				$count4 		= 0;
				$montoTotal 	= 0;
				if($objCash)
				foreach($objCash as $i){
					
					if($i["transactionName"] == "INGRESO DE CAJA"){
						continue;
					}
					
					$count4++;
					$montoTotal = $montoTotal + $i["montoTransaccion"];
					echo "<tr>";
						echo "<td nowrap style='text-align:left' class='border' >";
							//echo ($i["customerNumber"]);
						echo "</td>";
						echo "<td nowrap style='text-align:left' class='border' >";
							//echo ($i["firstName"]);
						echo "</td>";
						echo "<td nowrap style='text-align:left' class='border' >";
							echo ($i["moneda"]);
						echo "</td>";
						echo "<td nowrap style='text-align:left' class='border' >";
							echo "'".date_format(date_create($i["transactionOn"]),'Y-m-d');
						echo "</td>";
						echo "<td nowrap style='text-align:left' class='border' >";
							echo ($i["transactionNumber"]);
						echo "</td>";
						echo "<td nowrap style='text-align:left' class='border' >";
							echo ($i["transactionName"]);
						echo "</td>";
						echo "<td nowrap style='text-align:left' class='border' >";
							echo ($i["transactionNumber"]);
						echo "</td>";
						echo "<td nowrap  style='text-align:left' class='border' >";
							echo ($i["estado"]);
						echo "</td>";
						echo "<td nowrap style='text-align:right' class='border' >";							
							echo (number_format($i["montoTransaccion"],2,'.',','));
						echo "</td>";
						echo "<td nowrap style='text-align:right' class='border' >";							
							echo (number_format($i["tipoCambio"],2,'.',','));
						echo "</td>";
						echo "<td nowrap style='text-align:right' class='border' >";							
							echo (number_format($i["montoTransaccion"],2,'.',','));
						echo "</td>";
						
						echo "<td nowrap  style='text-align:right' class='border' >";
							echo ($i["nickname"]);
						echo "</td>";
						
						echo "<td nowrap  style='text-align:right' class='border' >";
							echo ($i["note"]);
						echo "</td>";
					echo "</tr>";
				}
				$montoGeneral = $montoGeneral + $montoTotal;
				?>
			</tbody>
			<footer>					
				<tr>
					<th nowrap style="text-align:left"  colspan="8" >SUB TOTAL</th>
					
					<?php
					if($count4 == 0){
					?>
						<th nowrap style="text-align:right"  >0</th>
					<?php
					}
					else{
					?>
						<th nowrap style="text-align:right" ></th>
					<?php
					}
					?>	
					
					
					<th nowrap style="text-align:right"  ></th>	
					<?php
					if($count4 == 0){
					?>
						<th nowrap style="text-align:right"  >0</th>
					<?php
					}
					else{
					?>
						<th nowrap style="text-align:right"  ><?php echo number_format($montoTotal,2,'.',',');  ?></th>
					<?php
					}
					?>	
					
					<th nowrap style="text-align:right"  ></th>	
					
					<th style="text-align:right"  ></th>	
				</tr>
			</footer>	
			<footer>
				<tr>
					<th nowrap style="text-align:left"   colspan="13" >	&nbsp;</th>				
				</tr>			
				<tr>
					<th nowrap style="text-align:left"   colspan="8" >TOTAL</th>
					<th nowrap style="text-align:right"  ></th>
					<th nowrap style="text-align:right"  ></th>			
					<th nowrap style="text-align:right"  ><?php echo number_format($montoGeneral,2,'.',',');  ?></th>
					
					<th nowrap style="text-align:right"  ></th>	
					
					<th nowrap style="text-align:right"  ></th>	
				</tr>
			</footer>	
		</table>
		
		</br>
		</br>
		
	
		
		<table style="width:100%">
			<thead>
				<tr>
					<th colspan="13" ><?php echo date("Y-m-d H:i:s");  ?> <?php echo $objFirmaEncription; ?> posMe</th>
				</tr>
			</tbody>
		</table>
		
		
	</body>	
</html>
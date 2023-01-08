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
					<th colspan="7" style="
						text-align:right;background-color:#00628e;color:white;
						width:80%
					">AUXILIAR DE MOVIMIENTOS</th>
				</tr>
				<tr>
					<th colspan="7" style="
						text-align:right;background-color:#00628e;color:white;
					"><?php echo strtoupper($objCompany->name); ?></th>
				</tr>
				<tr>
					<th colspan="7" style="
						text-align:right;background-color:#00628e;color:white;
					">MOVIMIENTOS DE <?php echo $startOn; ?> AL <?php echo $endOn; ?></th>
				</tr>
				<tr>
					<th colspan="7" style="
						text-align:right;background-color:#00628e;color:white;
					">PRODUCTO: <?php echo $objItem->itemNumber." ".$objItem->name; ?></th>
				</tr>
				<tr>
					<th colspan="7" style="text-align:left">&nbsp;</th>
				</tr>
				<tr>
					<th colspan="10" style="text-align:left">
						&nbsp;
					</th>
				</tr>
			</thead>
		</table>
		
		
		<br/>
		
		<table style="
			width:100%;order-spacing: 10px;
		" >
			<thead>
				<tr style="background-color:#00628e;color:white;">
					<!--812-->
					<th style="text-align:left;width:80px;" class="border">Fecha</th>
					<th style="text-align:left;width:80px;" class="border">Documento</th>
					<th style="text-align:left;width:80px;" class="border">Bodega</th>
					<th style="text-align:left;width:92px;" class="border">Tipo</th>
					<th style="text-align:left;width:80px;" class="border">Cnt Ini</th>
					<th style="text-align:left;width:80px;" class="border">Cos Ini</th>
					<th style="text-align:left;width:80px;" class="border">Cnt</th>
					<th style="text-align:left;width:80px;" class="border">Cos</th>
					<th style="text-align:left;width:80px;" class="border">Cnt Fin</th>
					<th style="text-align:left;width:80px;" class="border">Cos Fin</th>
				</tr>
			</thead>				
			<tbody>
				<?php
				$count 		= 0;
				$cantidad 		= 0;
				$costo 			= 0;
				if($objDetail)
				foreach($objDetail as $i){
					$count++;						
					$cantidad 		= $cantidad + $i["transactionQuantity"];
					$costo 			= $costo + $i["transactionCost"];
				
					echo "<tr>";
						echo "<td style='text-align:right' class='border'>";
							echo (date_format(date_create($i["movementOn"]),"Y-m-d"));
						echo "</td>";
						echo "<td style='text-align:right' class='border'>";
							echo ($i["transactionNumber"]);
						echo "</td>";
						echo "<td style='text-align:right' class='border'>";
							echo ($i["warehouseNumber"]);
						echo "</td>";
						echo "<td style='text-align:right' class='border'>";
							echo ($i["transactionType"]);
						echo "</td>";
						echo "<td style='text-align:right' class='border'>";
							echo (number_format($i["oldQuantity"],2,'.',','));
						echo "</td>";
						echo "<td style='text-align:right' class='border'>";
							echo (number_format($i["oldCost"],2,'.',','));
						echo "</td>";
						echo "<td style='text-align:right' class='border'>";
							echo (number_format($i["transactionQuantity"],2,'.',','));
						echo "</td>";
						echo "<td style='text-align:right' class='border'>";
							echo (number_format($i["transactionCost"],2,'.',','));
						echo "</td>";
						echo "<td style='text-align:right' class='border'>";
							echo (number_format($i["newQuantity"],2,'.',','));
						echo "</td>";
						echo "<td style='text-align:right' class='border'>";
							echo (number_format($i["newCost"],2,'.',','));
						echo "</td>";
					echo "</tr>";
				}
				?>
			</tbody>
			<tfoot>
				<tr>
					<th style="text-align:right"  class='border'>Fecha</th>
					<th style="text-align:right"  class='border'>Documento</th>
					<th style="text-align:right"  class='border'>Bodega</th>
					<th style="text-align:right"  class='border'>Tipo</th>
					<th style="text-align:right"  class='border'></th>
					<th style="text-align:right"  class='border'></th>
					<th style="text-align:right"  class='border'><?php echo number_format($cantidad,2,'.',',');  ?></th>
					<th style="text-align:right"  class='border'><?php echo number_format($costo,2,'.',',');  ?></th>
					<th style="text-align:right"  class='border'></th>
					<th style="text-align:right"  class='border'></th>
				</tr>
			</tfoot>
		</table>

		
		<br/>		
		
	
		
		<table style="width:100%">
			<thead>
				<tr>
					<th colspan="10" ><?php echo date("Y-m-d H:i:s");  ?> <?php echo $objFirmaEncription; ?> posMe</th>
				</tr>
			</tbody>
		</table>
		
		
		
	</body>	
</html>
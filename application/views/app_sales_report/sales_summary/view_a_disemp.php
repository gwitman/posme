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
					<th colspan="6" style="
						text-align:right;background-color:#00628e;color:white;
						width:80%
					">RESUMEN DE VENTAS</th>
				</tr>
				<tr>
					<th colspan="6" style="
						text-align:right;background-color:#00628e;color:white;
					"><?php echo strtoupper($objCompany->name); ?></th>
				</tr>
				<tr>
					<th colspan="6" style="
						text-align:right;background-color:#00628e;color:white;
					">VENTAS DEL <?php echo $objStartOn;?> AL <?php echo $objEndOn;?></th>
				</tr>
				<tr>
					<th colspan="6">&nbsp;</th>
				</tr>
				<tr>
					<th colspan="6">&nbsp;</th>
				</tr>
				<tr>
					<th colspan="9" style="text-align:left">
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
					<th style="text-align:left;width:80px;"   colspan="2" class="border">Factura</th>
					<th style="text-align:left;width:80px;"   colspan="2" class="border">Tipo</th>
					<th style="text-align:left;width:120px;"   colspan="2" class="border">Fecha</th>
					<th style="text-align:left;width:80px;"   colspan="1" class="border">Cod Cliente</th>
					<th style="text-align:left;width:372px;"   colspan="1" class="border">Cliente</th>					
					<th style="text-align:left;width:80px;"   colspan="1" class="border">Monto</th>
				</tr>
			</thead>				
			<tbody>
				<?php
				$count 		= 0;
				if($objDetail)
				foreach($objDetail as $i){
					$count++;						
					echo "<tr>";					
						echo "<td style='text-align:left'  colspan='2' class='border' >";
							echo ($i["transactionNumber"]);
						echo "</td>";
						echo "<td style='text-align:left'  colspan='2' class='border' >";
							echo ($i["tipo"]);
						echo "</td>";
						echo "<td style='text-align:left'  colspan='2' class='border' >";							
							echo (date_format(date_create($i["transactionOn"]),"Y-m-d"));
						echo "</td>";
						echo "<td style='text-align:left'  colspan='1' class='border' >";
							echo ($i["customerNumber"]);
						echo "</td>";
						echo "<td style='text-align:left'  colspan='1' class='border' >";
							echo ($i["legalName"]);
						echo "</td>";												
						echo "<td style='text-align:right' class='border'>";
							echo (number_format($i["totalDocument"],2,'.',','));
						echo "</td>";
					echo "</tr>";
				}
				?>
			</tbody>
		</table>
		
		
		<br/>		
		
	
		<table style="width:100%">
			<thead>
				<tr>
					<th colspan="9" ><?php echo date("Y-m-d H:i:s");  ?> <?php echo $objFirmaEncription; ?> posMe</th>
				</tr>
			</tbody>
		</table>
				
		
	</body>	
</html>
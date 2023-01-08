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
					">DETALLE DE VENTAS</th>
				</tr>
				<tr>
					<th colspan="10" style="
						text-align:right;background-color:#00628e;color:white;
					"><?php echo strtoupper($objCompany->name); ?></th>
				</tr>
				<tr>
					<th colspan="10" style="
						text-align:right;background-color:#00628e;color:white;
					">VENTAS DEL <?php echo $objStartOn;?> AL <?php echo $objEndOn;?></th>
				</tr>
				<tr>
					<th colspan="10">&nbsp;</th>
				</tr>
				<tr>
					<th colspan="10">&nbsp;</th>
				</tr>
				<tr>
					<th colspan="13" style="text-align:left">
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
					<!--812 vertical --> 
					<!--1017.07 horizontal --> 
					<th style="text-align:left;width:80px;"  >Factura</th>
					<th style="text-align:left;width:80px;"  >Tipo</th>
					<th style="text-align:left;width:90px;"  >Fecha</th>
					<th style="text-align:left;width:80px;"  >Cod C</th>
					<th style="text-align:left;width:220px;"  >Cliente</th>					
					<th style="text-align:left;width:80px;"  >Pre</th>
					<th style="text-align:left;width:80px;"  >Cant</th>
					<th style="text-align:left;width:80px;"  >Cos</th>
					<th style="text-align:left;width:80px;"  >Cos T.</th>
					<th style="text-align:left;width:80px;"  >Mon T.</th>
					<th style="text-align:left;width:80px;"  >Uti</th>						
					<th style="text-align:left;width:80px;"  >Cod.</th>
					<th style="text-align:left;width:220px;"  >Producto</th>					
				</tr>
			</thead>				
			<tbody>
				<?php
				$count 		= 0;
				$costoTotal 		= 0;
				$montoTotal 		= 0;
				$utilidadTotal 		= 0;
				if($objDetail)
				foreach($objDetail as $i){
					$count++;						
					$costoTotal 		= $costoTotal + $i["cost"];
					$montoTotal 		= $montoTotal + $i["amount"];
					$utilidadTotal 		= $utilidadTotal + ($i["amount"] -  $i["cost"]) ;
					
					echo "<tr>";						
						echo "<td style='text-align:left' class='border' >";
							echo ($i["transactionNumber"]);
						echo "</td>";
						echo "<td style='text-align:left' class='border' >";
							echo ($i["tipo"]);
						echo "</td>";
						echo "<td style='text-align:left' class='border' >";							
							echo (date_format(date_create($i["transactionOn"]),"Y-m-d"));
						echo "</td>";
						echo "<td style='text-align:left' class='border' >";
							echo ($i["customerNumber"]);
						echo "</td>";
						echo "<td style='text-align:left' class='border' >";
							echo ($i["legalName"]);
						echo "</td>";						
						echo "<td style='text-align:right' class='border' >";							
							echo (number_format($i["unitaryPrice"],2,'.',','));
						echo "</td>";
						echo "<td style='text-align:right' class='border' >";							
							echo (number_format($i["quantity"],2,'.',','));
						echo "</td>";
						echo "<td style='text-align:right' class='border' >";							
							echo (number_format($i["unitaryCost"],2,'.',','));
						echo "</td>";
						echo "<td style='text-align:right' class='border' >";							
							echo (number_format($i["cost"],2,'.',','));
						echo "</td>";
						echo "<td style='text-align:right' class='border'>";							
							echo (number_format($i["amount"],2,'.',','));
						echo "</td>";
						echo "<td style='text-align:right' class='border'>";							
							echo (number_format(  ($i["amount"] -  $i["cost"])  ,2,'.',','));
						echo "</td>";
						echo "<td style='text-align:left' class='border' >";
							echo ($i["itemNumber"]);
						echo "</td>";
						echo "<td style='text-align:left' class='border' >";
							echo ($i["itemName"]);
						echo "</td>";
					
					echo "</tr>";
				}
				?>
			</tbody>
			<tfoot>
				<tr>
								

					<th style="text-align:right" class='border'       ></th>
					<th style="text-align:right" class='border'       ></th>
					<th style="text-align:right" class='border'       ></th>
					<th style="text-align:right" class='border'       ></th>
					<th style="text-align:right" class='border'       ></th>					
					<th style="text-align:right" class='border'       ></th>
					<th style="text-align:right" class='border'       ></th>
					<th style="text-align:right" class='border'       ></th>
					<th style="text-align:right" class='border'       ><?php echo number_format($costoTotal,2,'.',',');  ?></th>
					<th style="text-align:right" class='border'       ><?php echo number_format($montoTotal,2,'.',',');  ?></th>
					<th style="text-align:right" class='border'       ><?php echo number_format($utilidadTotal,2,'.',',');  ?></th>						
					<th style="text-align:right" class='border'       ></th>
					<th style="text-align:right" class='border'       ></th>	
					
				</tr>
			</tfoot>
		</table>
		
		
		<br/>		
		
	
		
		
		<table style="width:100%">
			<thead>
				<tr>
					<th colspan="13" ><?php echo date("Y-m-d H:i:s");  ?> <?php echo $objFirmaEncription; ?> posMe</th>
				</tr>
			</tbody>
		</table>
		
		
		
		
	</body>	
</html>
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
					">MASTER KARDEX</th>
				</tr>
				<tr>
					<th colspan="6" style="
						text-align:right;background-color:#00628e;color:white;
					"><?php echo strtoupper($objCompany->name); ?></th>
				</tr>
				<tr>
					<th colspan="6" style="
						text-align:right;background-color:#00628e;color:white;
					">KARDEX DE <?php echo $startOn; ?> AL <?php echo $endOn; ?> <?php echo ($objWarehouse != null ? "EN ".$objWarehouse->name : ""); ?></th>
				</tr>
				<tr>
					<th colspan="6" style="text-align:left">&nbsp;</th>
				</tr>
				<tr>
					<th colspan="6" style="text-align:left">&nbsp;</th>
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
					<th style="text-align:left;width:80px;"   colspan="1" class="border">Codigo</th>
					<th style="text-align:left;width:252px;"  colspan="2" class="border">Descripcion</th>
					<th style="text-align:left;width:80px;" class="border">Cant. Inicial</th>
					<th style="text-align:left;width:80px;" class="border">Costo Inicial</th>
					<th style="text-align:left;width:80px;" class="border">Cant. Entrada</th>
					<th style="text-align:left;width:80px;" class="border">Costo Entrada</th>
					<th style="text-align:left;width:80px;" class="border">Cant. Salida</th>
					<th style="text-align:left;width:80px;" class="border">Costo Salida</th>
				</tr>
			</thead>				
			<tbody>
				<?php
				$count 			= 0;
				$costoinicial	= 0; 
				$costoentrada	= 0; 
				$costosalida	= 0; 
				if($objDetail)
				foreach($objDetail as $i){
					$count++;	
					$costoinicial	= $costoinicial +  	$i["costInicial"];
					$costoentrada	= $costoentrada +  	$i["costInput"];
					$costosalida	= $costosalida +  	$i["costOutput"];				
					echo "<tr>";
						echo "<td style='text-align:left'  colspan='1' class='border' >";
							echo ($i["itemNumber"]);
						echo "</td>";
						echo "<td style='text-align:left'  colspan='2' class='border' >";
							echo ($i["itemName"]);
						echo "</td>";
						echo "<td style='text-align:right' class='border'>";
							echo (number_format($i["quantityInicial"],2,'.',','));
						echo "</td>";
						echo "<td style='text-align:right' class='border'>";
							echo (number_format($i["costInicial"],2,'.',','));
						echo "</td>";
						echo "<td style='text-align:right' class='border'>";
							echo (number_format($i["quantityInput"],2,'.',','));
						echo "</td>";
						echo "<td style='text-align:right' class='border'>";
							echo (number_format($i["costInput"],2,'.',','));
						echo "</td>";
						echo "<td style='text-align:right' class='border'>";
							echo (number_format($i["quantityOutput"],2,'.',','));
						echo "</td>";
						echo "<td style='text-align:right' class='border'>";
							echo (number_format($i["costOutput"],2,'.',','));
						echo "</td>";
					echo "</tr>";
				}
				?>
			</tbody>
			<tfoot>
				<tr>
					<th style="text-align:left"  colspan="1" class='border'>Codigo</th>
					<th style="text-align:left"  colspan="2" class='border'>Descripcion</th>
					<th style="text-align:right"  class='border'></th>
					<th style="text-align:right"  class='border'><?php echo number_format($costoinicial,2,'.',',');  ?></th>
					<th style="text-align:right"  class='border'></th>
					<th style="text-align:right"  class='border'><?php echo number_format($costoentrada,2,'.',',');  ?></th>
					<th style="text-align:right"  class='border'></th>
					<th style="text-align:right"  class='border'><?php echo number_format($costosalida,2,'.',',');  ?></th>
				</tr>
			</tfoot>
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
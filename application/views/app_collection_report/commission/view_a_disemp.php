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
						<th colspan="6">COMISIONES X GESTOR</th>
					</tr>
					<tr>
						<th colspan="6"><?php echo strtoupper($objCompany->name); ?></th>
					</tr>
					<tr>
						<th colspan="6">DEL <?php echo helper_DateToSpanish($objCycle->startOn,"Y-F"); ?></th>
					</tr>
				</thead>
			</table>
		</div>
		<br/>		
		<div class="data_grid_body">
			<table>
				<thead>
					<tr>
						<th nowrap class="cell_left">Nombre</th>
						<th nowrap class="cell_left">Mes</th>
						<th nowrap class="cell_right">10%</th>
						<th nowrap class="cell_right">20%</th>
						<th nowrap class="cell_right">30%</th>
						<th nowrap class="cell_right">40%</th>
						<th nowrap class="cell_right">50%</th>
						<th nowrap class="cell_right">100%</th>
					</tr>
				</thead>				
				<tbody>
					<?php
					$count 			= 0;
					$Tcomision10	= 0;
					$Tcomision20	= 0;
					$Tcomision30	= 0;
					$Tcomision40	= 0;
					$Tcomision50	= 0;
					$Tcomision100	= 0;
					if($objDetail)
					foreach($objDetail as $i){
						$count++;
						
						$Tcomision10	= $Tcomision10 +  $i["comision10"];
						$Tcomision20	= $Tcomision20 +  $i["comision20"];
						$Tcomision30	= $Tcomision30 +  $i["comision30"];
						$Tcomision40	= $Tcomision40 +  $i["comision40"];
						$Tcomision50	= $Tcomision50 +  $i["comision50"];
						$Tcomision100	= $Tcomision100 + $i["comision100"];
					
						echo "<tr>";						
							echo "<td nowrap class='cell_left'>";
								echo ("'".$i["firstName"]."'");
							echo "</td>";
							echo "<td nowrap class='cell_left'>";
								echo ($i["mes"]);
							echo "</td>";
							echo "<td nowrap class='cell_right'>";
								echo "C$ ".number_format($i["comision10"],2);
							echo "</td>";
							echo "<td nowrap class='cell_right'>";
								echo "C$ ".number_format($i["comision20"],2);
							echo "</td>";
							echo "<td nowrap class='cell_right'>";
								echo "C$ ".number_format($i["comision30"],2);
							echo "</td>";
							echo "<td nowrap class='cell_right'>";
								echo "C$ ".number_format($i["comision40"],2);
							echo "</td>";
							echo "<td nowrap class='cell_right'>";
								echo "C$ ".number_format($i["comision50"],2);
							echo "</td>";
							echo "<td nowrap class='cell_right'>";
								echo "C$ ".number_format($i["comision100"],2);
							echo "</td>";
						echo "</tr>";
					}
					?>
				</tbody>
				<tfoot>
					<tr>
						<th nowrap class="cell_left"></th>
						<th nowrap class="cell_left"></th>
						<th nowrap class="cell_right"><?php echo "C$ ".number_format($Tcomision10,2); ?></th>
						<th nowrap class="cell_right"><?php echo "C$ ".number_format($Tcomision20,2); ?></th>
						<th nowrap class="cell_right"><?php echo "C$ ".number_format($Tcomision30,2); ?></th>
						<th nowrap class="cell_right"><?php echo "C$ ".number_format($Tcomision40,2); ?></th>
						<th nowrap class="cell_right"><?php echo "C$ ".number_format($Tcomision50,2); ?></th>
						<th nowrap class="cell_right"><?php echo "C$ ".number_format($Tcomision100,2); ?></th>
					</tr>
				</tfoot>
			</table>			
		</div>
		<br/>
		<div class="data_grid_firm_system">
			<table>
				<tbody>
					<tr>
						<td colspan="6" nowrap ><?php echo date("Y-m-d H:i:s");  ?> <?php echo $objFirmaEncription; ?></td>
					</tr>
				</tbody>
			</table>
		</div>
		
	</body>	
</html>
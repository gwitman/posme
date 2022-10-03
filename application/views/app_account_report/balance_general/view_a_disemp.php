<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<title>Balance General ...<?php echo $objFirmaEncription; ?></title>
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
						<th colspan="6">BALANCE GENERAL</th>
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
			<table width="100%">				
				<tr width="100%">
					<td width="50%" style="vertical-align:top">
						<table width="100%">
							<thead>
								<tr>
									<th nowrap class="cell_left"  width="20%" style=";background:#ccc">Codigo</th>
									<th nowrap class="cell_left"  width="60%" style=";background:#ccc">Cuenta</th>									
									<th nowrap class="cell_right" width="20%" style="text-align:right;background:#ccc">Saldo Final</th>
								</tr>
							</thead>				
							<tbody>
								<?php
								$count 			= 0;
								if($objDetailActivo)
								foreach($objDetailActivo as $i){
									$count++;									
									echo "<tr class='data_tr'>";						
										echo "<td nowrap class='cell_left'>";
											echo ("'".$i["accountNumber"]);
										echo "</td>";
										echo "<td nowrap class='cell_left'>";
											echo ($i["name"]);
										echo "</td>";
										echo "<td nowrap class='cell_right' style='text-align:right;'>";
											echo "C$ ".number_format($i["balanceEnd"],2);
										echo "</td>";
									echo "</tr>";
								}
								?>
							</tbody>
						</table>
					</td>
					<td width="50%">
						<table width="100%">
							<thead>
								<tr>
									<th nowrap class="cell_left"  width="20%" style=";background:#ccc">Codigo</th>
									<th nowrap class="cell_left"  width="60%" style=";background:#ccc">Cuenta</th>									
									<th nowrap class="cell_right" width="20%" style="text-align:right;background:#ccc">Saldo Final</th>
								</tr>
							</thead>				
							<tbody>
								<?php
								$count 		= 0;
								if($objDetailPasivo)
								foreach($objDetailPasivo as $i){
									$count++;
									echo "<tr class='data_tr'>";						
										echo "<td nowrap class='cell_left'>";
											echo ("'".$i["accountNumber"]);
										echo "</td>";
										echo "<td nowrap class='cell_left'>";
											echo ($i["name"]);
										echo "</td>";
										echo "<td nowrap class='cell_right' style='text-align:right;'>";
											echo "C$ ".number_format($i["balanceEnd"],2);
										echo "</td>";
									echo "</tr>";
								}
								?>
							</tbody>
						</table>
						<table width="100%">
							<thead>
								<tr>
									<th nowrap class="cell_left"  width="20%" style=";background:#ccc">Codigo</th>
									<th nowrap class="cell_left"  width="60%" style=";background:#ccc">Cuenta</th>
									<th nowrap class="cell_right" width="20%" style="text-align:right;background:#ccc">Saldo Final</th>
								</tr>
							</thead>				
							<tbody>
								<?php
								$count 		= 0;
								if($objDetailCapital)
								foreach($objDetailCapital as $i){
									$count++;
									echo "<tr class='data_tr'>";						
										echo "<td nowrap class='cell_left'>";
											echo ("'".$i["accountNumber"]);
										echo "</td>";
										echo "<td nowrap class='cell_left'>";
											echo ($i["name"]);
										echo "</td>";
										echo "<td nowrap class='cell_right' style='text-align:right;'>";
											echo "C$ ".number_format($i["balanceEnd"],2);
										echo "</td>";
									echo "</tr>";
								}
								?>
							</tbody>
						</table>
					</td>
				</tr>									
			</table>
			<table width="100%">				
				<tr width="100%">
					<td width="50%" style="vertical-align:top">
						<table width="100%">
							<tbody>
								<tr>
									<th class="cell_left" nowrap>Total</th>
									<th class="cell_left"></th>
									<th class="cell_right" nowrap style='text-align:right;background:#ccc' ><?php echo "C$ ".number_format($objTotalActivo[0]["balanceEnd"],2); ?></th>
								</tr>
							</tbody>
						</table>
					</td>
					<td width="50%" style="vertical-align:top">
						<table width="100%">
							<tbody>
								<tr>
									<th class="cell_left" nowrap>Total</th>
									<th class="cell_left"></th>									
									<th class="cell_right" nowrap style='text-align:right;background:#ccc' ><?php echo "C$ ".number_format($objTotalPasivoCapital[0]["balanceEnd"],2); ?></th>
								</tr>
							</tbody>
						</table>
					</td>
				</tr>
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
<?php 
	$widthDiv		= 205;
	$widthBorder	= 0;
?>
<html>
	<head>
		<title>factura</title>
		<style>
			@page {
				margin: 		0mm -1mm 0mm 3mm;
				padding:		0mm;
			}
			@media print {
				  body {
					margin: 		0mm -1mm 0mm 3mm;
					padding: 		0mm;
				  }
			}
		</style>
	</head>
	<body style="margin:0mm -1mm 0mm 3mm;padding:0mm;border:black <?php echo $widthBorder; ?>px solid;">	
		<div style="border:black <?php echo $widthBorder; ?>px solid;width:<?php echo $widthDiv; ?>mm;height:130mm;margin-top:0mm;background-size:contain">
			<table  style="border-collapse:collapse;border:black <?php echo $widthBorder; ?>px solid;margin-top:19mm;padding-left:0mm;width:<?php echo $widthDiv; ?>mm;font-family:monospace;">
				<tr>
					<td style="border:black <?php echo $widthBorder; ?>px solid;width:0.5mm;"></td>
					<td style="border:black <?php echo $widthBorder; ?>px solid;width:9.2mm;"><?php echo $objTM_Day;?></td>
					<td style="border:black <?php echo $widthBorder; ?>px solid;width:9.3mm;"><?php echo $objTM_Month;?></td>
					<td style="border:black <?php echo $widthBorder; ?>px solid;width:12mm;"><?php echo $objTM_Year;?></td>
					<td style="border:black <?php echo $widthBorder; ?>px solid;"></td>
				</tr>
			</table>
			<table  style="border-collapse:collapse;border:black <?php echo $widthBorder; ?>px solid;margin-top:9mm;padding-left:0mm;width:<?php echo $widthDiv; ?>mm;font-family:monospace;">
				<tr>
					<td style="border:black <?php echo $widthBorder; ?>px solid;width:15mm;"></td>
					<td style="border:black <?php echo $widthBorder; ?>px solid;width:160mm;"><?php echo $objCustumer->customerNumber ." " .$objLegal->legalName; ?></td>
					
				</tr>
			</table>
			<table  style="border-collapse:collapse;border:black <?php echo $widthBorder; ?>px solid;margin-top:3.5mm;padding-left:0mm;width:<?php echo $widthDiv; ?>mm;font-family:monospace;">
				<tr>
					<td style="border:black <?php echo $widthBorder; ?>px solid;width:20mm;"></td>
					<td style="border:black <?php echo $widthBorder; ?>px solid;width:160mm;"><?php echo $objCustumer->address."..."; ?></td>
					
				</tr>
			</table>
			<table  style="border-collapse:collapse;border:black <?php echo $widthBorder; ?>px solid;margin-top:2mm;padding-left:0mm;width:<?php echo $widthDiv; ?>mm;font-family:monospace;">
				<tr>
					<td style="border:black <?php echo $widthBorder; ?>px solid;width:25mm;"></td>
					<td style="border:black <?php echo $widthBorder; ?>px solid;width:70mm;"><?php echo $objUser->userID." ".$objUser->nickname; ?></td>
					<td style="border:black <?php echo $widthBorder; ?>px solid;width:18mm;"></td>
					<td style="border:black <?php echo $widthBorder; ?>px solid;width:30mm;">...</td>
					<td style="border:black <?php echo $widthBorder; ?>px solid;width:29mm;"></td>
					<td style="border:black <?php echo $widthBorder; ?>px solid;width:5mm;"><?php echo ($objTM_IsCredit == true ? "." : "x"); ?></td>
					<td style="border:black <?php echo $widthBorder; ?>px solid;width:22mm;"></td>
					<td style="border:black <?php echo $widthBorder; ?>px solid;width:5mm;"><?php echo ($objTM_IsCredit == true ? "x" : "."); ?></td>
				</tr>
			</table>
			<table  style="border-collapse:collapse;border:black <?php echo $widthBorder; ?>px solid;margin-top:6.5mm;padding-left:0mm;width:<?php echo $widthDiv; ?>mm;font-family:monospace;">
				<tbody>
					<?php
					$cantitem	= 0;					
					$iva 		= 0;
					$discount 	= 0;
					$subtotal 	= 0;
					$total 		= 0;				
					
					if($objTMD)
					foreach($objTMD as $key => $value)
					{
						echo "<tr>";
							echo "<td style='border:black ".$widthBorder."px solid;width:0.1mm'></td>";
							echo "<td style='border:black ".$widthBorder."px solid;width:23mm'>".substr($value->itemNumber,-5)."</td>";
							echo "<td style='border:black ".$widthBorder."px solid;width:30mm'>".strtolower($value->reference1)."</td>";
							echo "<td style='border:black ".$widthBorder."px solid;width:31mm'>".strtolower($value->reference2)."</td>";
							echo "<td style='border:black ".$widthBorder."px solid;width:37mm'>".strtolower(substr($value->itemName,0,14))."</td>";
							echo "<td style='border:black ".$widthBorder."px solid;width:30mm;text-align:center'>".number_format(round($value->quantity,2),2,'.',',')."</td>";
							echo "<td style='border:black ".$widthBorder."px solid;width:28mm;text-align:center'>".number_format(round($value->unitaryPrice,2),2,'.',',')."</td>";
							echo "<td style='border:black ".$widthBorder."px solid;width:25mm;text-align:center'>".number_format(round($value->amount,2),2,'.',',')."</td>";
						echo "</tr>";
						
						$cantitem	= $cantitem + 1;
						$iva 		= $iva +  ($value->tax1 * $value->quantity);
						$total		= $total + $value->amount;
						$subtotal 	= $subtotal + ($value->amount - ($value->tax1 * $value->quantity));
						
					}
					$margin_summary	= (32) - (0.5 * $cantitem);
					?>
				</tbody>
			</table>
			<table  style="border-collapse:collapse;border:black <?php echo $widthBorder; ?>px solid;margin-top:<?php echo $margin_summary; ?>mm;padding-left:0mm;width:<?php echo $widthDiv; ?>mm;font-family:monospace;">
				<tbody>
					<tr>
						<td style="border:black <?php echo $widthBorder; ?>px solid;width:178mm"></td>
						<td style='border:black <?php echo $widthBorder; ?>px solid;text-align:center;width:25mm'><?php echo number_format(round($subtotal,2),2,'.',',');?></td>
						
					</tr>
					<tr>
						<td style="border:black <?php echo $widthBorder; ?>px solid;width:178mm"></td>
						<td style='border:black <?php echo $widthBorder; ?>px solid;text-align:center;width:25mm'><?php echo number_format(round($iva,2),2,'.',',');?></td>
						
					</tr>
					<tr>
						<td style="border:black <?php echo $widthBorder; ?>px solid;width:178mm"></td>
						<td style='border:black <?php echo $widthBorder; ?>px solid;text-align:center;width:25mm'><?php echo  number_format(round($total,2),2,'.',',');?></td>
						
					</tr>
				</tbody>
			</table>
		</div>
	</body>
</html>
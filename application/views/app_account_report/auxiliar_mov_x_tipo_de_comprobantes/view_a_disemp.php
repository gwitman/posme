<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<title>Movimiento x Tipo de Comprobante ...<?php echo $objFirmaEncription; ?></title>
		<meta name="viewport" 			content="width=device-width, initial-scale=1.0">
		<meta name="application-name" 	content="dsemp" /> 
		
		<link rel="stylesheet" type="text/css" href="<?php echo site_url(); ?>css/style_table_report_printer.css">
		<link rel="stylesheet" type="text/css" href="<?php echo site_url(); ?>css/style_table_report_printer.css" media="print">		<script src="<?php echo site_url(); ?>theme-genyx/js/jquery-1-9-1.min.js"></script>	
	</head>
	<body> 
		<div class="data_grid_encabezado">
			<table>
				<thead>
					<tr>
						<th colspan="9">AUXILIAR DE MOVI. POR TIPO DE COMPROBANTES</th>
					</tr>
					<tr>
						<th colspan="9"><?php echo strtoupper($objCompany->name); ?></th>
					</tr>
					<tr>
						<th colspan="9">DEL <?php echo $startOn; ?> AL <?php echo $endOn; ?> TIPO <?php echo $objTipo; ?></th>
					</tr>
				</thead>
			</table>
		</div>
		<br/>
		
		<div class="data_grid_body">
			<table>
				<thead>
					<tr>
						<th nowrap class="cell_left">Comprobante</th>
						<th nowrap class="cell_left">Fecha</th>
						<th nowrap class="cell_right">Cambio</th>
						<th nowrap class="cell_right">Tipo</th>
						<th nowrap class="cell_right">Cuenta</th>
						<th nowrap class="cell_right">Debito</th>
						<th nowrap class="cell_right">Credito</th>
						<th nowrap class="cell_right">..</th>
						<th nowrap class="cell_left">Reference1</th>
					</tr>
				</thead>				
				<tbody>
					<?php
					$count 		= 0;					$fac		= "";
					if($objDetail)
					foreach($objDetail as $i){
						$count++;												if($fac != $i["journalNumber"])						{							echo "<tr style='color:red;cursor:pointer;' class='btnJournal' data-journal='".$i["journalNumber"]."' ><td colspan='9'><b>".$i["journalNumber"]."</b></td></tr>";														echo "<tr style='display:block' id='".$i["journalNumber"]."'>";								echo "<td colspan='9'>";									echo "<b style='color:red;cursor:pointer;'>".$i["journalNumber"].":<br/>".nl2br($i["note"])."</b>";								echo "</td>";							echo "</tr>";												}												$fac  = $i["journalNumber"];
						echo "<tr class='data_tr'>";						
							echo "<td nowrap class='cell_left'>";
								echo ($i["journalNumber"]);
							echo "</td>";
							echo "<td nowrap class='cell_left'>";
								echo "'".($i["journalDate"]);
							echo "</td>";
							echo "<td nowrap class='cell_right'>";
								echo "C$ ".number_format($i["tb_exchange_rate"],2);
							echo "</td>";
							echo "<td nowrap class='cell_right'>";
								echo ($i["journalType"]);
							echo "</td>";
							echo "<td nowrap class='cell_right'>";
								echo ("'".$i["accountNumber"]."' ".$i["accountName"]);
							echo "</td>";
							echo "<td nowrap class='cell_right'>";
								echo "C$ ".number_format($i["debit"],2);
							echo "</td>";
							echo "<td nowrap class='cell_right'>";
								echo "C$ ".number_format($i["credit"],2);
							echo "</td>";
							echo "<td nowrap class='cell_right'>";
								echo ""; //(substr($i["note"],0,25));
							echo "</td>";
							echo "<td nowrap class='cell_left'>";
								echo ($i["reference1"]);
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
						<td colspan="9"><?php echo date("Y-m-d H:i:s");  ?> <?php echo $objFirmaEncription; ?></td>
					</tr>
				</tbody>
			</table>
		</div>
		
	</body>		<script type="text/javascript">		$(function() {			$( ".btnJournal" ).click(function() {				if($("#"+$(this).data("journal")).css("display") == "none"){					$("#"+$(this).data("journal")).css("display","block");					$(this).css("background","black");					$(this).css("color","white");				}				else {					$("#"+$(this).data("journal")).css("display","none");					$(this).css("background","white");					$(this).css("color","red");				}			});		});	</script>
</html>
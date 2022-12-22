<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<title>Lista de Cliente y Su Mora ...<?php echo $objFirmaEncription; ?></title>
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
						<th colspan="9">LISTA DE CLIENTES</th>
					</tr>
					<tr>
						<th colspan="9"><?php echo strtoupper($objCompany->name); ?></th>
					</tr>
					<tr>
						<th colspan="9"><?php echo strtoupper($objUser->nickname); ?></th>
					</tr>
				</thead>
			</table>
		</div>
		
		<br/>
		<div class="data_grid_body">
			<table>
				<thead>
					<tr>
						<th nowrap class="cell_left">#</th>
						<th nowrap class="cell_left">Gestor</th>
						<th nowrap class="cell_left">Codigo</th>
						<th nowrap class="cell_left">Cliente</th>
						<th nowrap class="cell_left">Tipo</th>
						<th nowrap class="cell_left">Telefono</th>
						<th nowrap class="cell_left">Factura</th>	
						<th nowrap class="cell_left">Atrazo</th>	
						<th nowrap class="cell_left">Pago</th>
					</tr>
				</thead>				
				<tbody>
					<?php
					$count 		= 0;
					$countDia	= 0;
					if($objDetail)
					foreach($objDetail as $i){
						$count		= 1 + $count;	
						$countDia	= $i["dias_atrazo"] + $countDia;

						if($i["dias_atrazo"] > 0)
						echo "<tr style='background-color:#f7e2e2;' class='data_tr'>";
						else
						echo "<tr class='data_tr'>";
							echo "<td nowrap class='cell_left'>";
								echo $count;
							echo "</td>";
							echo "<td nowrap class='cell_left'>";
								echo ($i["Gestor"]);
							echo "</td>";
							echo "<td nowrap class='cell_left'>";
								echo ($i["CodigoCliente"]);
							echo "</td>";
							echo "<td nowrap class='cell_left'>";
								echo ($i["Cliente"]);
							echo "</td>";
							echo "<td nowrap class='cell_left'>";
								echo ($i["TipoTelefono"]);
							echo "</td>";
							echo "<td nowrap class='cell_left'>";
								echo ($i["Telefono"]);
							echo "</td>";
							echo "<td nowrap class='cell_left'>";
								echo "<a href='".site_url()."app_collection_report/document_credit/viewReport/true/documentNumber/".$i["Factura"]."' >".$i["Factura"]."</a>";
							echo "</td>";
							echo "<td nowrap class='cell_right'>";
								echo ($i["dias_atrazo"]);
							echo "</td>";
							echo "<td nowrap class='cell_right'>";
								echo ($i["dias_proximo_pago"]);
							echo "</td>";
							
							
						echo "</tr>";
					}
					?>
				</tbody>
				<tfoot>
					<tr>
						<td colspan="7"></td>
						<td><?php echo $countDia; ?></td>
						<td></td>
					</tr>					
				</tfoot>
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
		
	</body>	
</html>
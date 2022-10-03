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
						<th colspan='8'>LISTA DE CONSULTAS REALIZADAS AL BURO DE CREDITO</th>
					</tr>
					<tr>
						<th colspan='8'><?php echo strtoupper($objCompany->name); ?></th>
					</tr>
					<tr>
						<th colspan='8'>DEL <?php echo $startOn; ?> AL <?php echo $endOn; ?></th>
					</tr>
				</thead>
			</table>
		</div>
		<br/>		
		<div class="data_grid_body">
			<table>
				<thead>					
					<tr>
						<th nowrap class="cell_left">Consecutivo</th>
						<th nowrap class="cell_left">RequestID</th>
						<th nowrap class="cell_left">Cliente</th>
						<th nowrap class="cell_left">Cedula del Cliente</th>
						<th nowrap class="cell_left">Archivo</th>
						<th nowrap class="cell_left">Fecha de Consulta</th>
						<th nowrap class="cell_left">Usuario</th>
						<th nowrap class="cell_left">Estado</th>
					</tr>
				</thead>				
				<tbody>
					<?php
					$count 		= 0;
					if($objDetail)
					foreach($objDetail as $i){
						$count++;
						echo "<tr>";
							echo "<td nowrap class='cell_left'>";
								echo ($count);
							echo "</td>";
							echo "<td nowrap class='cell_left'>";
								echo ($i["requestID"]);
							echo "</td>";
							echo "<td nowrap class='cell_left'>";
								echo ($i["cliente"]);
							echo "</td>";
							echo "<td nowrap class='cell_left'>";
								echo ("'".$i["cedulaCliente"]);
							echo "</td>";
							echo "<td nowrap class='cell_left'>";
								echo "<a href='".site_url()."app_cxc_record/index.aspx?file_exists=".$i["file_"]."'  target='_blank'    >".$i["file_"]."</a>";
							echo "</td>";
							echo "<td nowrap class='cell_left'>";
								echo ("'".$i["createdOn"]);
							echo "</td>";
							echo "<td nowrap class='cell_left'>";
								echo ($i["Usuario"]);
							echo "</td>";
							echo "<td nowrap class='cell_left'>";
								echo ($i["Estado"]);
							echo "</td>";
						echo "</tr>";
					}
					?>
				</tbody>
				<footer>					
					<tr>
						<th nowrap class="cell_left">Total</th>
						<th nowrap class="cell_left"></th>
						<th nowrap class="cell_left"></th>
						<th nowrap class="cell_left"></th>
						<th nowrap class="cell_left"></th>
						<th nowrap class="cell_left"></th>
						<th nowrap class="cell_left"></th>
						<th nowrap class="cell_left"></th>
					</tr>
				</footer>	
			</table>
		</div>
		<br/>
		<div class="data_grid_firm_system">
			<table>
				<tbody>
					<tr>
						<td colspan='8'><?php echo date("Y-m-d H:i:s");  ?> <?php echo $objFirmaEncription; ?></td>
					</tr>
				</tbody>
			</table>
		</div>
		
	</body>	
</html>
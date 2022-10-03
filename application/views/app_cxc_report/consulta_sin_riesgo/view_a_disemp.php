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
	<body style="background-image:url(<?php echo site_url(); ?>img/logos/<?php echo $objLogo->value;?>);background-size:80px 50px;"> 
		<div class="data_grid_encabezado">
			<table>
				<thead>
					<tr>
						<th colspan='6'>REPORTE DE CREDITO</th>
					</tr>
					<tr>
						<th colspan='6'><?php echo strtoupper($objCompany->name); ?></th>
					</tr>
					<tr>
						<th colspan='6'>CEDULA DE IDENTIDAD. <?php echo (isset($objDetail["Persona"]) ? $objDetail["Persona"]->NumeroDocumentoIdentidad:""); ?> NOMBRE: <?php echo (isset($objDetail["Persona"]) ? $objDetail["Persona"]->NombreRazonSocial:""); ?></th>
					</tr>
				</thead>
			</table>
		</div>
		<br/>	
		<div class="data_grid_body">
		    
			<table style="width:100%;margin:auto;">
				<thead>	
				    <tr>
				        <th colspan="4">DIRECCIONES</th>
				    </tr>
					<tr>
						<th nowrap class="cell_left" style="width:100px;">Referencia</th>
						<th nowrap class="cell_left" style="width:100px;">Departamento</th>
						<th nowrap class="cell_left" style="width:100px;">Municipio</th>
						<th nowrap class="cell_left">Direccion</th>
						
					</tr>
				</thead>				
				<tbody>									
					<?php
					$count 		= 0;
					if(isset($objDetail["Direcciones"]))
                    if($objDetail["Direcciones"])
					foreach($objDetail["Direcciones"] as $i){
						$count++;
						echo "<tr>";
							echo "<td nowrap class='cell_left'>";
								echo ($i->Referencia);
							echo "</td>";
							echo "<td nowrap class='cell_left'>";
								echo ($i->Departamento);
							echo "</td>";
							echo "<td nowrap class='cell_left'>";
								echo ($i->Municipio);
							echo "</td>";
							echo "<td nowrap class='cell_left'>";
								echo ($i->Direccion);
							echo "</td>";
						echo "</tr>";
					}
					?>
				</tbody>	
			</table>
		</div>
		<br/>	
		<br/>
		<div class="data_grid_body">
		    
			<table style="width:30%;margin:auto;">
				<thead>	
				    <tr>
				        <th colspan="2">TELEFONOS</th>
				    </tr>
					<tr>
						<th nowrap class="cell_left" style="width:100px;">Referencia</th>
						<th nowrap class="cell_left" style="width:100px;">Telefono</th>
						
					</tr>
				</thead>				
				<tbody>									
					<?php
					$count 		= 0;
					if(isset($objDetail["Telefonos"]))
                    if($objDetail["Telefonos"])
					foreach($objDetail["Telefonos"] as $i){
						$count++;
						echo "<tr>";
							echo "<td nowrap class='cell_left'>";
								echo ($i->Referencia);
							echo "</td>";
							echo "<td nowrap class='cell_left'>";
								echo ($i->Telefono);
							echo "</td>";
						echo "</tr>";
					}
					?>
				</tbody>	
			</table>
		</div>
		<br/>
		<br/>
		<div class="data_grid_body">
			<table>
				<thead>	
				    <tr>
				        <th colspan="2">Detalle de operaciones de credito</th>
				    </tr>
				</thead>
			</table>
		</div>
		<div class="data_grid_body">
			<table>
				<tbody>	
                		<?php
                		$cantidad = 0;
                		if(isset($objDetail["CreditosVigentes"]))
                		if($objDetail["CreditosVigentes"])
                		foreach($objDetail["CreditosVigentes"] as $ws){
                			$cantidad++;
                			?>
                			    <tr>
                			        <td colspan="6" style="color: blue;font-style: oblique;font-weight: bold;" >Credito Numero <?php echo $cantidad; ?> --<?php echo  $ws->NumeroCredito; ?>: <?php echo $ws->Entidad; ?></td>
                			    </tr>
								<tr>
                			        <td style="font-weight: bold;" >Tipo de Obligación:</td>
                			        <td><?php echo strtolower($ws->TipoObligacion); ?></td>
                			        <td style="font-weight: bold;">Fecha de Reporte:</td>
									<td><?php echo helper_DateToSpanish($ws->FechaReporte,"Y-M-d"); ?></td>
                			        <td style="font-weight: bold;">Monto Autorizado (C$):</td>
                			        <td><?php echo number_format(round($ws->MontoAutorizado,2),2); ?></td>
                			    </tr>
                			     <tr>
                			        <td style="font-weight: bold;">Tipo de Crédito:</td>
                			        <td><?php echo strtolower($ws->TipoCredito); ?></td>
                			        <td style="font-weight: bold;">Fecha de Desembolso:</td>
                			        <td><?php echo helper_DateToSpanish($ws->FechaDesembolso,"Y-M-d"); ?></td>
                			        <td style="font-weight: bold;">Saldo de Deuda (C$):</td>
                			        <td><?php echo number_format(round($ws->SaldoDeuda,2),2); ?></td>
                			    </tr>
                			     <tr>
                			        <td style="font-weight: bold;">Tipo de Garantia:</td>
                			        <td><?php echo strtolower(substr($ws->TipoGarantia,0,26)); ?></td>
                			        <td style="font-weight: bold;">Departamento:</td>
                			        <td><?php echo strtolower($ws->Departamento); ?></td>
                			        <td style="font-weight: bold;">Monto Vencido (C$):</td>
                			        <td><?php echo number_format(round($ws->MontoVencido,2),2); ?></td>
                			    </tr>
								<tr>
                			        <td style="font-weight: bold;">Estado:</td>
                			        <td><span style='color: <?php echo helper_GetColorSinRiesgo($ws->EstadoOP); ?>;'><?php echo strtolower($ws->EstadoOP); ?></span></td>
                			        <td style="font-weight: bold;">Forma de Pago:</td>
                			        <td><?php echo strtolower($ws->FormaDePago); ?></td> 
                			        <td style="font-weight: bold;">Antiguedad Mora:</td>
                			        <td><?php echo $ws->AntiguedadMoraEnDias; ?></td>
                			    </tr>
                			     <tr>
                			        <td style="font-weight: bold;">Forma de Recuperación:</td>
                			        <td><span style='color: <?php echo helper_GetColorSinRiesgo($ws->FormaRecuperacion); ?>;'><?php echo strtolower($ws->FormaRecuperacion); ?></span></td>
                			        <td style="font-weight: bold;">Plazo:</td>
                			        <td><?php echo $ws->PlazoMeses; ?></td>
                			        <td style="font-weight: bold;">Valor Cuota (C$):</td>
                			        <td><?php echo number_format(round($ws->Cuota,2),2); ?></td>
                			    </tr>
                			<?php
                		}
                		?>
    		    </tbody>
    	    </table>
		</div>
    	<br/>
    	<br/>
		<div class="data_grid_body">
    		<table>
    			<thead>	
    			    <tr>
    			        <th colspan="2">Detalle de operaciones de creditos activos grupos solidarios</th>
    			    </tr>
    			</thead>
    		</table>
    	</div>
    	<br/><br/>
    	<div class="data_grid_body">
    		<table>
    			<thead>	
    			    <tr>
    			        <th colspan="2">Detalle de tarjetas de credito</th>
    			    </tr>
    			</thead>
    		</table>
    	</div>
    	<br/>
    	<br/>
    	<div class="data_grid_body">
    		<table>
    			<thead>	
    			    <tr>
    			        <th colspan="2">Detalle de operaciones de creditos saneados</th>
    			    </tr>
    			</thead>
    		</table>
    	</div>
    	<br/>
    	<br/>
    	<div class="data_grid_body">
    		<table>
    			<thead>	
    			    <tr>
    			        <th colspan="2">Detalle de operaciones de creditos saneados grupos solidarios</th>
    			    </tr>
    			</thead>
    		</table>
    	</div>
    	<br/>
    	<br/>
    	<div class="data_grid_body">
    		<table>
    			<thead>	
    			    <tr>
    			        <th colspan="2">Detalle de operaciones de creditos cancelados</th>
    			    </tr>
    			</thead>
    		</table>
    	</div>
    	<div class="data_grid_body">
			<table>
				<tbody>	
            		<?php
            		$cantidad = 0;
            		if(isset($objDetail["CreditosCancelados"]))
            		if($objDetail["CreditosCancelados"])
            		foreach($objDetail["CreditosCancelados"] as $ws){
            			$cantidad++;
            			?>
            			        <tr>
                			        <td colspan="6" style="color: blue;font-style: oblique;font-weight: bold;">Credito Numero <?php echo $cantidad; ?> --<?php echo  $ws->NumeroCredito; ?>: <?php echo $ws->Entidad; ?></td>
                			    </tr>
                			    <tr>
                			        <td style="font-weight: bold;" >Tipo de Obligación:</td>
                			        <td><?php echo strtolower($ws->TipoObligacion); ?></td>
                			        <td style="font-weight: bold;">Fecha de Reporte:</td>
									<td><?php echo helper_DateToSpanish($ws->FechaReporte,"Y-M-d"); ?></td>
                			        <td style="font-weight: bold;">Monto Autorizado (C$):</td>
                			        <td><?php echo number_format(round($ws->MontoAutorizado,2),2); ?></td>
                			    </tr>
                			     <tr>
                			        <td style="font-weight: bold;">Tipo de Crédito:</td>
                			        <td><?php echo strtolower($ws->TipoCredito); ?></td>
                			        <td style="font-weight: bold;">Fecha de Desembolso:</td>
                			        <td><?php echo helper_DateToSpanish($ws->FechaDesembolso,"Y-M-d"); ?></td>
                			        <td style="font-weight: bold;">Saldo de Deuda (C$):</td>
                			        <td><?php echo number_format(round($ws->SaldoDeuda,2),2); ?></td>
                			    </tr>
                			     <tr>
                			        <td style="font-weight: bold;">Tipo de Garantia:</td>
                			        <td><?php echo strtolower(substr($ws->TipoGarantia,0,26)); ?></td>
                			        <td style="font-weight: bold;">Departamento:</td>
                			        <td><?php echo strtolower($ws->Departamento); ?></td>
                			        <td style="font-weight: bold;">Monto Vencido (C$):</td>
                			        <td><?php echo number_format(round($ws->MontoVencido,2),2); ?></td>
                			    </tr>
								<tr>
                			        <td style="font-weight: bold;">Estado:</td>
                			        <td><span style='color: <?php echo helper_GetColorSinRiesgo($ws->EstadoOP); ?>;'><?php echo strtolower($ws->EstadoOP); ?></span></td>
                			        <td style="font-weight: bold;">Forma de Pago:</td>
                			        <td><?php echo strtolower($ws->FormaDePago); ?></td> 
                			        <td style="font-weight: bold;">Antiguedad Mora:</td>
                			        <td><?php echo $ws->AntiguedadMoraEnDias; ?></td>
                			    </tr>
                			     <tr>
                			        <td style="font-weight: bold;">Forma de Recuperación:</td>
                			        <td><span style='color: <?php echo helper_GetColorSinRiesgo($ws->FormaRecuperacion); ?>;'><?php echo strtolower($ws->FormaRecuperacion); ?></span></td>
                			        <td style="font-weight: bold;">Plazo:</td>
                			        <td><?php echo $ws->PlazoMeses; ?></td>
                			        <td style="font-weight: bold;">Valor Cuota (C$):</td>
                			        <td><?php echo number_format(round($ws->Cuota,2),2); ?></td>
                			    </tr>
            			<?php
            		}
            		?>
    		    </tbody>
    	    </table>
		</div>
    	<br/>
    	<br/>
    	<div class="data_grid_body">
    		<table>
    			<thead>	
    			    <tr>
    			        <th colspan="2">Detalle de operaciones de creditos cancelados grupos solidarios</th>
    			    </tr>
    			</thead>
    		</table>
    	</div>
    	<br/>
    	<br/>
    	<div class="data_grid_body">
    		<table>
    			<thead>	
    			    <tr>
    			        <th colspan="2">Detalle de nota de prensas</th>
    			    </tr>
    			</thead>
    		</table>
    	</div>
    	<br/>
    	<br/>
    	<div class="data_grid_body">
    		<table>
    			<thead>	
    			    <tr>
    			        <th colspan="2">Detalle de operaciones de servicios publicos</th>
    			    </tr>
    			</thead>
    		</table>
    	</div>
    	<br/>
    	<br/>
		<div class="data_grid_body">
			<table style="width:50%;margin:auto;">
				<thead>	
				    <tr>
				        <th colspan="3">LISTADO DE CONSULTAS</th>
				    </tr>
					<tr>
						<th nowrap class="cell_left">Numero</th>
						<th nowrap class="cell_left">Fecha</th>
						<th nowrap class="cell_left">Empresa</th>
					</tr>
				</thead>				
				<tbody>
				    	<?php
														if(isset($Consultas))
														if($Consultas)
														foreach($Consultas as $ws){
															echo "<dt>Cantidad. ".$ws->Cantidad."</dt>";
															echo '<dd>Fecha de consulta: <span class="blue">'.$ws->FechaConsulta.'</span>   &nbsp;&nbsp;&nbsp;Entidad:'.$ws->Entidad.' </dd>';
															echo '<dd>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</dd>';
														}
														?>
														
														
					<?php
					$count 		= 0;
					if(isset($objDetail["Consultas"]))
                    if($objDetail["Consultas"])
					foreach($objDetail["Consultas"] as $i){
						$count++;
						echo "<tr>";
							echo "<td nowrap class='cell_left'>";
								echo ($i->Cantidad);
							echo "</td>";
							echo "<td nowrap class='cell_left'>";
								echo ($i->FechaConsulta);
							echo "</td>";
							echo "<td nowrap class='cell_left'>";
								echo ($i->Entidad);
							echo "</td>";
						echo "</tr>";
					}
					?>
				</tbody>	
			</table>
		</div>
		<br/>
		<br/>
		<div class="data_grid_firm_system">
			<table>
				<tbody>
					<tr>
						<td colspan='6'><?php echo date("Y-m-d H:i:s");  ?> <?php echo $objFirmaEncription; ?></td>
					</tr>
				</tbody>
			</table>
		</div>
		
	</body>	
</html>
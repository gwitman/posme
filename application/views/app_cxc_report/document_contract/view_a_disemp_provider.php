<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<title><?php echo $objFirmaEncription; ?></title>
		<meta name="viewport" 			content="width=device-width, initial-scale=1.0">
		<meta name="application-name" 	content="dsemp" /> 
		
		<link href="https://fonts.googleapis.com/css?family=Inconsolata:400,700" rel="stylesheet">
		<link rel="stylesheet" type="text/css" href="<?php echo site_url(); ?>css/style_table_report_printer.css">
		<link rel="stylesheet" type="text/css" href="<?php echo site_url(); ?>css/style_table_report_printer.css" media="print">
	</head>
	<!--<body style="background:url('<?php echo site_url();?>/img/sello_azul.png');background-repeat:no-repeat;background-size:150px;background-position:bottom +60px right +110px"> -->
	<body style="font-size:4px;margin-left:0px;margin-right:0px;">
		<div class="data_grid_encabezado">
			<table>
				<thead>
					<tr>
						<th colspan="8" style="font-size:small;font-family: 'Inconsolata', monospace;">PAGARE</th>
					</tr>
				</thead>
			</table>
		</div>
		<br/>
		<br/>
		<div class="data_grid_body">
			<table>
				<tbody >
					<tr>
						<td style="text-align:justify;font-size:small;font-family: 'Inconsolata', monospace;">	
						Yo <u><b><?php echo $objFirstDetail["legalName"]; ?></b></u> mayor de edad, identificad@ con cedula <u><?php echo $objFirstDetail["identification"]; ?></u> 
						y con domicilio 
						en <u><?php echo $objFirstDetail["address"] == ""? "**NULL**" : $objFirstDetail["address"]; ?></u>, debo y pagaré 
						incondicionalmente en la ciudad de Malpaisillo, o en el lugar que se me 
						solicite, a la orden del Sr(@) <b><?php echo $objFirstDetail["NombreProveedor"];?></b> reconocido con 
						cedula <?php echo $objFirstDetail["CedulaProveedor"];?> y con domicilio en
						<?php echo $objFirstDetail["DireccionProveedor"];?>, la suma de  
						<u><?php echo helper_GetLetras($objFirstDetail["cuotaDolares"],"DOLARES AMERICANOS CON ",""); ?> CENTAVOS </u> 
						&nbsp;de los Estados Unidos de América 
						<u><b>(US$ <?php echo sprintf("%01.2f",$objFirstDetail["cuotaDolares"]); ?>)</b></u> a razón de C$ 
						<?php echo $objFirstDetail["TipoCambio"];?> córdobas por cada dolar cuando sucedió  el desembolso.
						<br/>
						<br/>
						</td>						
					</tr>
					<tr>
						<td style="text-align:justify;font-size:small;font-family: 'Inconsolata', monospace;">
						El dinero será pagado en Dólares de los Estados Unidos de América, este valor se pagará en  <b><?php echo $objFirstDetail["term"]; ?> cuotas </b>
						de <b>US$ <?php echo sprintf("%01.2f",$objFirstDetail["cuotaDolares"] / $objFirstDetail["term"]); ?></b> 
						dólares o su equivalente en córdobas segun el tipo de cambio emitido por el Banco Central de Nicaragua, 
						iguales y sucesivas cada periodo <b><?php echo $objFirstDetail["period"]; ?></b>,
						la fecha en que la deuda deberá ser cancelada en su totalidad será   
						el día <b> 
						<?php echo helper_DateToSpanish($objFirstDetail["fechFinal"],"d"); ?>
						de 
						<?php echo helper_DateToSpanish($objFirstDetail["fechFinal"],"F"); ?>
						Del 
						<?php echo helper_DateToSpanish($objFirstDetail["fechFinal"],"Y"); ?></b>
						iniciando a partir del día <b>
						<?php echo helper_DateToSpanish($objFirstDetail["fechInicial"],"d"); ?>
						de 
						<?php echo helper_DateToSpanish($objFirstDetail["fechInicial"],"F"); ?>
						Del 
						<?php echo helper_DateToSpanish($objFirstDetail["fechInicial"],"Y"); ?></b>
						<br/>
						<br/>
						</td>						
					</tr>
					<tr>
						<td style="text-align:justify;font-size:small;font-family: 'Inconsolata', monospace;">
						Si no fuere pagado íntegramente en los plazos señalados, el Sr(@)  
						<b><?php echo $objFirstDetail["NombreProveedor"];?></b>  tendrá  la facultad  de declarar por este solo
						echo vencida toda obligación y demandar el pago de los saldos insolutos, 
						también  me comprometo a pagar todos los gastos judiciales y extra 
						judiciales incluyendo también  los honorarios 
						legales y ha si como otras pérdidas y daños causados por el incumplimiento. 
						<br/>
						<br/>
						</td>
					<tr>
						<td style="text-align:justify;font-size:small;font-family: 'Inconsolata', monospace;">
						Al fiel cumplimiento de lo estipulado me obligo con todos mis  
						bienes presentes y futuros. Además,	faculto expresamente al Sr(@) 
						<b><?php echo $objFirstDetail["NombreProveedor"];?></b>  para que cuando esta obligación sea exigible 
						pueda disponer de valores y
						documentos a mi favor u orden, en su poder, como pago parcial o total de este 
						pagaré. Yo quedo sometido a los jueces competentes de esta ciudad, 
						o a los que elija el Sr(@) <b><?php echo $objFirstDetail["NombreProveedor"];?></b>. Yo dejo  
						constancia que el plazo de vista corre desde la fecha en que, en señal de conformidad y 
						aceptación, suscribo este documento.
						<br/>
						<br/>
						</td>
					</tr>
					<tr>
						<td style="text-align:justify;font-size:small;font-family: 'Inconsolata', monospace;">
							De igual manera queda como testigo de este compromiso que yo <b><?php echo $objFirstDetail["legalName"]; ?></b> 
							estoy firmando <b>(PAGARE)</b> 3 personas que estuvieron durante el 
							préstamo del dinero. Los testigos son: <br/><br/>
							<b>1)</b> Señora <b>Petrona Argentina Lechado López</b> mayor de edad identificada 
							con cedula de identidad 291-290661-0000G y con domicilio en la ciudad de Malpaisillo de la Policía Nacional 
							3C.E y 1/2C.S <br/><br/>
							<b>2)</b> Señora <b>Castalia Diomara Picado Lechado</b> mayor de edad identificada con cedula de identidad 
							291-120685-0000P y con domicilio en la ciudad de Malpaisillo de la Policía Nacional 3C.E y 1/2C.S <br/><br/>
							<b>3)</b> Señora <b>Martha Ryder Ramírez Rostran</b> mayor de edad identificada con cedula de identidad 
							291-070974-0001J y con domicilio en la ciudad de Malpaisillo del Restaurante el Campestre 2C.N y 10V.O.<br/>
							<br/>
							El dinero me fue entregado en su totalidad en mis manos, en efectivo y en moneda Dólares Americanos, en la casa de habitación 
							del Sr(@) <b><?php echo $objFirstDetail["NombreProveedor"];?></b> ubicada en la siguiente dirección: <?php echo $objFirstDetail["DireccionProveedor"];?> 							
							Yo <b><?php echo $objFirstDetail["legalName"]; ?></b> he recibido copia de este pagare y he leído este pagare 
							completamente, y el Sr@ <b><?php echo $objFirstDetail["NombreProveedor"];?></b> no ha echo nada de forma oculta, 
							y todo los términos están claros.
							<br/>
							<br/>
						</td>
					</tr>
					<tr>
						<td style="text-align:justify;font-size:small;font-family: 'Inconsolata', monospace;">
						Ambas partes señalan y aseguran que en la celebración del mismo no ha mediado
						error, dolo de nulidad o anulabilidad que pudiera invalidar el contenido del
						documento, por lo que proceden a firmar en la ciudad de Malpaisillo, 
						el día <b>
						<?php echo helper_DateToSpanish($objFirstDetail["fechActual"],"d"); ?>
						de 
						<?php echo helper_DateToSpanish($objFirstDetail["fechActual"],"F"); ?>
						Del 
						<?php echo helper_DateToSpanish($objFirstDetail["fechActual"],"Y"); ?>
						</b>
						a las 
						<b>
						<?php echo helper_DateToSpanish($objFirstDetail["createdOn"],"H"); ?> 
						Horas y 
						<?php echo helper_DateToSpanish($objFirstDetail["createdOn"],"i"); ?>  
						Minutos. 
						</b>
						<br/>
						<br/>
						</td>						
					</tr>
				</tbody>
			</table>
		</div>
		<br/>
		<br/>
		<br/>
		<br/>
		<br/>
		<br/>
		<br/>
		<div class="data_grid_body">
			<table>
				<tbody>
					<tr>
						<td style="text-align:center">____________________________________________________________</td>						
					</tr>
					<tr>
						<td style="font-size:small;font-family: 'Inconsolata', monospace;text-align:center"><b>FIRMA: <?php echo strtoupper($objFirstDetail["legalName"]); ?></b></td>
					</tr>
					<tr>
						<td style="font-size:small;font-family: 'Inconsolata', monospace;text-align:center"><b>CEDULA:<?php echo $objFirstDetail["identification"]; ?></b></td>
					</tr>
					<tr>
						<td style="font-size:small;font-family: 'Inconsolata', monospace;text-align:center"><b>DEUDOR</b></td>
					</tr>
				</tbody>
			</table>
		</div>
		<!--
		<br/>
		<br/>
		<div class="data_grid_body">
			<table>
				<tbody>
					<tr>
						<td style="text-align:center">____________________________________________________________</td>						
					</tr>
					<tr>
						<td style="font-size:small;font-family: 'Inconsolata', monospace;text-align:center"><b>FIRMA: <?php echo $objPropietaryName->value;?></b></td>
					</tr>
					<tr>
						<td style="font-size:small;font-family: 'Inconsolata', monospace;text-align:center"><b>CEDULA: <?php echo $objPropietaryID->value;?></b></td>
					</tr>
					<tr>
						<td style="font-size:small;font-family: 'Inconsolata', monospace;text-align:center"><b>ACREDOR</b></td>
					</tr>
				</tbody>
			</table>
			
		</div>
		-->
		<br/>
		<div class="data_grid_body">
			<table>
				<tbody>
					<tr>
						<td style="font-size:small;font-family: 'Inconsolata', monospace;text-align:center"><br/><br/><br/> </td>
					</tr>
					<tr>
						<td style="text-align:justify;font-size:small;font-family: 'Inconsolata', monospace;">
						 Ante mi ____________________________________, Abogado y Notario público de la
						 republica de Nicaragua, con domicilio y residencia en la ciudad de ______________________________, 
						 debidamente autorizada por la excelentísima corte suprema de justicia para ejercer el Notariado durante un quinquenio que finaliza el  
						 día ______________________________________________________. Dando fe que las firmas que anteceden corresponden a los señores.
						 a los 
						 <?php echo helper_DateToSpanish($objFirstDetail["fechActual"],"d"); ?> día  del mes de 
						 <?php echo helper_DateToSpanish($objFirstDetail["fechActual"],"F"); ?>  del año 
						 <?php echo helper_DateToSpanish($objFirstDetail["fechActual"],"Y"); ?>
						</td>						
					</tr>
					<tr>
						<td style="font-size:small;font-family: 'Inconsolata', monospace;text-align:center"><br/><br/>_________________________________</td>
					</tr>
					<tr>
						<td style="font-size:small;font-family: 'Inconsolata', monospace;text-align:center"><br/><br/></td>
					</tr>
					<tr>
						<td style="font-size:small;font-family: 'Inconsolata', monospace;text-align:center"><b>ABOGADO Y NOTARIO PUBLICO</b></td>
					</tr>
					<tr>
						<td style="font-size:small;font-family: 'Inconsolata', monospace;text-align:center"><!--<b>DEUDOR</b>--></td>
					</tr>
				</tbody>
			</table>
		</div>
	</body>	
</html>
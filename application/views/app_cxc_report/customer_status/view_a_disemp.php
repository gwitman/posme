<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<title>Estado Cuenta Cliente ...<?php echo $objFirmaEncription; ?></title>
		<meta name="viewport" 			content="width=device-width, initial-scale=1.0">
		<meta name="application-name" 	content="dsemp" /> 
		
		<?php 
		helper_echoStyleReport();
		?>
		
	</head>
	<body style="font-family:monospace;font-size:smaller;margin:0px 0px 0px 0px"> 
			
		
		<?php
		$configColumn["0"]["Titulo"] 		= "Linea";
		$configColumn["0"]["TituloFoot"]	= "";
		$configColumn["0"]["FiledSouce"]	= "lineNumber";
		$configColumn["0"]["Colspan"] 		= "1";
		$configColumn["0"]["Formato"] 		= "";
		$configColumn["0"]["Total"] 		= False;
		$configColumn["0"]["Alineacion"] 	= "Left";
		$configColumn["0"]["TotalValor"] 	= 0;
		$configColumn["0"]["FiledSoucePrefix"]	= "";
		$configColumn["0"]["Width"]			= "92px";
		
		$configColumn["1"]["Titulo"] 		= "Documento";
		$configColumn["1"]["TituloFoot"]	= "";
		$configColumn["1"]["FiledSouce"]	= "documentNumber";
		$configColumn["1"]["Colspan"] 		= "1";
		$configColumn["1"]["Formato"] 		= "";
		$configColumn["1"]["Total"] 		= False;
		$configColumn["1"]["Alineacion"] 	= "Left";
		$configColumn["1"]["TotalValor"] 	= 0;
		$configColumn["1"]["FiledSoucePrefix"]	= "";
		$configColumn["1"]["Width"]			= "92px";
		
		$configColumn["2"]["Titulo"] 		= "Desembolso";
		$configColumn["2"]["TituloFoot"]	= "";
		$configColumn["2"]["FiledSouce"]	= "documentOn";
		$configColumn["2"]["Colspan"] 		= "1";
		$configColumn["2"]["Formato"] 		= "";
		$configColumn["2"]["Total"] 		= False;
		$configColumn["2"]["Alineacion"] 	= "Left";
		$configColumn["2"]["TotalValor"] 	= 0;
		$configColumn["2"]["FiledSoucePrefix"]	= "";
		$configColumn["2"]["Width"]			= "92px";
		
		$configColumn["3"]["Titulo"] 		= "Capital Inicial";
		$configColumn["3"]["TituloFoot"]	= "";
		$configColumn["3"]["FiledSouce"]	= "amountDocument";
		$configColumn["3"]["Colspan"] 		= "1";
		$configColumn["3"]["Formato"] 		= "Number";
		$configColumn["3"]["Total"] 		= False;
		$configColumn["3"]["Alineacion"] 	= "Right";
		$configColumn["3"]["TotalValor"] 	= 0;
		$configColumn["3"]["FiledSoucePrefix"]	= "";
		$configColumn["3"]["Width"]			= "128px";
		
		$configColumn["4"]["Titulo"] 		= "% Interes Anual";
		$configColumn["4"]["TituloFoot"]	= "";
		$configColumn["4"]["FiledSouce"]	= "interesDocument";
		$configColumn["4"]["Colspan"] 		= "1";
		$configColumn["4"]["Formato"] 		= "Number";
		$configColumn["4"]["Total"] 		= True;
		$configColumn["4"]["Alineacion"] 	= "Right";
		$configColumn["4"]["TotalValor"] 	= 0;
		$configColumn["4"]["FiledSoucePrefix"]	= "";
		$configColumn["4"]["Width"]			= "136px";
			
		$configColumn["5"]["Titulo"] 		= "% Interes Anual / 12";
		$configColumn["5"]["TituloFoot"]	= "";
		$configColumn["5"]["FiledSouce"]	= "interesDocumentMultiploDe120";
		$configColumn["5"]["Colspan"] 		= "1";
		$configColumn["5"]["Formato"] 		= "Number";
		$configColumn["5"]["Total"] 		= False;
		$configColumn["5"]["Alineacion"] 	= "Right";
		$configColumn["5"]["TotalValor"] 	= 0;
		$configColumn["5"]["FiledSoucePrefix"]	= "";
		$configColumn["5"]["Width"]			= "163px";
		
		$configColumn["6"]["Titulo"] 		= "#Cuotas";
		$configColumn["6"]["TituloFoot"]	= "";
		$configColumn["6"]["FiledSouce"]	= "termDocument";
		$configColumn["6"]["Colspan"] 		= "1";
		$configColumn["6"]["Formato"] 		= "Number";
		$configColumn["6"]["Total"] 		= True;
		$configColumn["6"]["Alineacion"] 	= "Right";
		$configColumn["6"]["TotalValor"] 	= 0;
		$configColumn["6"]["FiledSoucePrefix"]	= "";
		$configColumn["6"]["Width"]			= "113px";
		
		$configColumn["7"]["Titulo"] 		= "Periodo";
		$configColumn["7"]["TituloFoot"]	= "";
		$configColumn["7"]["FiledSouce"]	= "periodPayDocument";
		$configColumn["7"]["Colspan"] 		= "1";
		$configColumn["7"]["Formato"] 		= "";
		$configColumn["7"]["Total"] 		= False;
		$configColumn["7"]["Alineacion"] 	= "Left";
		$configColumn["7"]["TotalValor"] 	= 0;
		$configColumn["7"]["FiledSoucePrefix"]	= "";
		$configColumn["7"]["Width"]			= "70px";
		
		$configColumn["8"]["Titulo"] 		= "Estado";
		$configColumn["8"]["TituloFoot"]	= "";
		$configColumn["8"]["FiledSouce"]	= "statusDocument";
		$configColumn["8"]["Colspan"] 		= "1";
		$configColumn["8"]["Formato"] 		= "";
		$configColumn["8"]["Total"] 		= False;
		$configColumn["8"]["Alineacion"] 	= "Left";
		$configColumn["8"]["TotalValor"] 	= 0;
		$configColumn["8"]["FiledSoucePrefix"]	= "";
		$configColumn["8"]["Width"]			= "80px";
			
		$configColumn["9"]["Titulo"] 		= "Moneda";
		$configColumn["9"]["TituloFoot"]	= "";
		$configColumn["9"]["FiledSouce"]	= "moneda";
		$configColumn["9"]["Colspan"] 		= "1";
		$configColumn["9"]["Formato"] 		= "";
		$configColumn["9"]["Total"] 		= False;
		$configColumn["9"]["Alineacion"] 	= "Left";
		$configColumn["9"]["TotalValor"] 	= 0;
		$configColumn["9"]["FiledSoucePrefix"]	= "";
		$configColumn["9"]["Width"]			= "60px";
		
		$configColumn["10"]["Titulo"] 		= "Capital Saldo";
		$configColumn["10"]["TituloFoot"]	= "";
		$configColumn["10"]["FiledSouce"]	= "balanceDocument";
		$configColumn["10"]["Colspan"] 		= "1";
		$configColumn["10"]["Formato"] 		= "Number";
		$configColumn["10"]["Total"] 		= False;
		$configColumn["10"]["Alineacion"] 	= "Right";
		$configColumn["10"]["TotalValor"] 	= 0;
		$configColumn["10"]["FiledSoucePrefix"]	= "";
		$configColumn["10"]["Width"]			= "130px";
		
		$configColumn["11"]["Titulo"] 		= "Dias Atrasados";
		$configColumn["11"]["TituloFoot"]	= "";
		$configColumn["11"]["FiledSouce"]	= "dayAtrazo";
		$configColumn["11"]["Colspan"] 		= "1";
		$configColumn["11"]["Formato"] 		= "Number";
		$configColumn["11"]["Total"] 		= False;
		$configColumn["11"]["Alineacion"] 	= "Right";
		$configColumn["11"]["TotalValor"] 	= 0;
		$configColumn["11"]["FiledSoucePrefix"]	= "";
		$configColumn["11"]["Width"]			= "140px";
		
		$configColumn["12"]["Titulo"] 		= "Monto Atrazado";
		$configColumn["12"]["TituloFoot"]	= "";
		$configColumn["12"]["FiledSouce"]	= "amountAtrazo";
		$configColumn["12"]["Colspan"] 		= "1";
		$configColumn["12"]["Formato"] 		= "Number";
		$configColumn["12"]["Total"] 		= True;
		$configColumn["12"]["Alineacion"] 	= "Right";
		$configColumn["12"]["TotalValor"] 	= 0;
		$configColumn["12"]["FiledSoucePrefix"]	= "";
		$configColumn["12"]["Width"]			= "140px";
		
		$configColumn["13"]["Titulo"] 		= "Monto Total de Interes del Credito";
		$configColumn["13"]["TituloFoot"]	= "";
		$configColumn["13"]["FiledSouce"]	= "interestTotalMontoDocument";
		$configColumn["13"]["Colspan"] 		= "1";
		$configColumn["13"]["Formato"] 		= "Number";
		$configColumn["13"]["Total"] 		= False;
		$configColumn["13"]["Alineacion"] 	= "Right";
		$configColumn["13"]["TotalValor"] 		= 0;
		$configColumn["13"]["FiledSoucePrefix"]	= "";
		$configColumn["13"]["Width"]			= "260px";
		
		$configColumn["14"]["Titulo"] 		= "Vencimiento de Ultima Cuota";
		$configColumn["14"]["TituloFoot"]	= "";
		$configColumn["14"]["FiledSouce"]	= "vencimientoUltimaCuota";
		$configColumn["14"]["Colspan"] 		= "1";
		$configColumn["14"]["Formato"] 		= "";
		$configColumn["14"]["Total"] 		= False;
		$configColumn["14"]["Alineacion"] 	= "Left";
		$configColumn["14"]["TotalValor"] 		= 0;
		$configColumn["14"]["FiledSoucePrefix"]	= "";
		$configColumn["14"]["Width"]			= "260px";
		
		$configColumn["15"]["Titulo"] 		= "Promedio de Pago en Dia";
		$configColumn["15"]["TituloFoot"]	= "";
		$configColumn["15"]["FiledSouce"]	= "promedioDiaPago";
		$configColumn["15"]["Colspan"] 		= "1";
		$configColumn["15"]["Formato"] 		= "Number";
		$configColumn["15"]["Total"] 		= False;
		$configColumn["15"]["Alineacion"] 	= "Right";
		$configColumn["15"]["TotalValor"] 		= 0;
		$configColumn["15"]["FiledSoucePrefix"]	= "";
		$configColumn["15"]["Width"]			= "260px";
		
		$configColumn["16"]["Titulo"] 		= "La ultimoa cuota fue cancelada a los X Dias";
		$configColumn["16"]["TituloFoot"]	= "";
		$configColumn["16"]["FiledSouce"]	= "atrasoCancelacionDia";
		$configColumn["16"]["Colspan"] 		= "1";
		$configColumn["16"]["Formato"] 		= "Number";
		$configColumn["16"]["Total"] 		= False;
		$configColumn["16"]["Alineacion"] 	= "Right";
		$configColumn["16"]["TotalValor"] 		= 0;
		$configColumn["16"]["FiledSoucePrefix"]	= "";
		$configColumn["16"]["Width"]			= "320px";
		
		$configColumn["17"]["Titulo"] 		= "Nota";
		$configColumn["17"]["TituloFoot"]	= "";
		$configColumn["17"]["FiledSouce"]	= "nota";
		$configColumn["17"]["Colspan"] 		= "1";
		$configColumn["17"]["Formato"] 		= "";
		$configColumn["17"]["Total"] 		= False;
		$configColumn["17"]["Alineacion"] 	= "Left";
		$configColumn["17"]["TotalValor"] 		= 0;
		$configColumn["17"]["FiledSoucePrefix"]	= "";
		$configColumn["17"]["Width"]			= "120px";
		
		$resultado = helper_createTableReport($objDocument,$configColumn,'2700px');
		?>
		
		<?php 
		helper_header(
			'ESTADO DE CUENTA',
			$objCompany->name,
			$resultado["columnas"],
			'ESTADO DE CUENTA DEL CLIENTE '.$objClient["customerNumber"],
			"",
			"",
			$resultado["width"]
		);
		?>
		
		<br/>	
		
		
		<?php
		$configColumn1["0"]["Titulo"] 		= "Nombre";
		$configColumn1["0"]["TituloFoot"]	= "";
		$configColumn1["0"]["FiledSouce"]	= "legalName";
		$configColumn1["0"]["Colspan"] 		= "17";
		$configColumn1["0"]["Formato"] 		= "";
		$configColumn1["0"]["Total"] 		= False;
		$configColumn1["0"]["Alineacion"] 	= "Left";
		$configColumn1["0"]["TotalValor"] 	= 0;
		$configColumn1["0"]["FiledSoucePrefix"]	= "";
		$configColumn1["0"]["Width"]			= "195px";
		
		$configColumn1["1"]["Titulo"] 		= "Tipo de Identificacion";
		$configColumn1["1"]["TituloFoot"]	= "";
		$configColumn1["1"]["FiledSouce"]	= "identificationType";
		$configColumn1["1"]["Colspan"] 		= "17";
		$configColumn1["1"]["Formato"] 		= "";
		$configColumn1["1"]["Total"] 		= False;
		$configColumn1["1"]["Alineacion"] 	= "Left";
		$configColumn1["1"]["TotalValor"] 	= 0;
		$configColumn1["1"]["FiledSoucePrefix"]	= "";
		$configColumn1["1"]["Width"]			= "195px";
		
		$configColumn1["2"]["Titulo"] 		= "Pais";
		$configColumn1["2"]["TituloFoot"]	= "";
		$configColumn1["2"]["FiledSouce"]	= "country";
		$configColumn1["2"]["Colspan"] 		= "17";
		$configColumn1["2"]["Formato"] 		= "";
		$configColumn1["2"]["Total"] 		= False;
		$configColumn1["2"]["Alineacion"] 	= "Left";
		$configColumn1["2"]["TotalValor"] 	= 0;
		$configColumn1["2"]["FiledSoucePrefix"]	= "";
		$configColumn1["2"]["Width"]			= "195px";
		
		$configColumn1["3"]["Titulo"] 		= "Estado";
		$configColumn1["3"]["TituloFoot"]	= "";
		$configColumn1["3"]["FiledSouce"]	= "state";
		$configColumn1["3"]["Colspan"] 		= "17";
		$configColumn1["3"]["Formato"] 		= "";
		$configColumn1["3"]["Total"] 		= False;
		$configColumn1["3"]["Alineacion"] 	= "Left";
		$configColumn1["3"]["TotalValor"] 	= 0;
		$configColumn1["3"]["FiledSoucePrefix"]	= "";
		$configColumn1["3"]["Width"]			= "195px";
		
		$configColumn1["4"]["Titulo"] 		= "Ciudad";
		$configColumn1["4"]["TituloFoot"]	= "";
		$configColumn1["4"]["FiledSouce"]	= "city";
		$configColumn1["4"]["Colspan"] 		= "17";
		$configColumn1["4"]["Formato"] 		= "";
		$configColumn1["4"]["Total"] 		= False;
		$configColumn1["4"]["Alineacion"] 	= "Left";
		$configColumn1["4"]["TotalValor"] 	= 0;
		$configColumn1["4"]["FiledSoucePrefix"]	= "";
		$configColumn1["4"]["Width"]			= "195px";
		
		$configColumn1["5"]["Titulo"] 		= "Nacimiento";
		$configColumn1["5"]["TituloFoot"]	= "";
		$configColumn1["5"]["FiledSouce"]	= "birth";
		$configColumn1["5"]["Colspan"] 		= "17";
		$configColumn1["5"]["Formato"] 		= "Date";
		$configColumn1["5"]["Total"] 		= False;
		$configColumn1["5"]["Alineacion"] 	= "Left";
		$configColumn1["5"]["TotalValor"] 	= 0;
		$configColumn1["5"]["FiledSoucePrefix"]	= "";
		$configColumn1["5"]["Width"]			= "195px";
		
		$configColumn1["6"]["Titulo"] 		= "Estado del Cliente";
		$configColumn1["6"]["TituloFoot"]	= "";
		$configColumn1["6"]["FiledSouce"]	= "statusClient";
		$configColumn1["6"]["Colspan"] 		= "17";
		$configColumn1["6"]["Formato"] 		= "";
		$configColumn1["6"]["Total"] 		= False;
		$configColumn1["6"]["Alineacion"] 	= "Left";
		$configColumn1["6"]["TotalValor"] 	= 0;
		$configColumn1["6"]["FiledSoucePrefix"]	= "";
		$configColumn1["6"]["Width"]			= "195px";
		
		$configColumn1["7"]["Titulo"] 		= "Limite";
		$configColumn1["7"]["TituloFoot"]	= "";
		$configColumn1["7"]["FiledSouce"]	= "limitCreditCordoba";
		$configColumn1["7"]["Colspan"] 		= "17";
		$configColumn1["7"]["Formato"] 		= "Number";
		$configColumn1["7"]["Total"] 		= False;
		$configColumn1["7"]["Alineacion"] 	= "Right";
		$configColumn1["7"]["TotalValor"] 	= 0;
		$configColumn1["7"]["FiledSoucePrefix"]	= "";
		$configColumn1["7"]["Width"]			= "195px";
		
		$configColumn1["8"]["Titulo"] 		= "Balance";
		$configColumn1["8"]["TituloFoot"]	= "";
		$configColumn1["8"]["FiledSouce"]	= "balanceCordoba";
		$configColumn1["8"]["Colspan"] 		= "17";
		$configColumn1["8"]["Formato"] 		= "Number";
		$configColumn1["8"]["Total"] 		= False;
		$configColumn1["8"]["Alineacion"] 	= "Right";
		$configColumn1["8"]["TotalValor"] 	= 0;
		$configColumn1["8"]["FiledSoucePrefix"]	= "";
		$configColumn1["8"]["Width"]			= "195px";
		
		$configColumn1["9"]["Titulo"] 		= "Ingreso";
		$configColumn1["9"]["TituloFoot"]	= "";
		$configColumn1["9"]["FiledSouce"]	= "incomeCordoba";
		$configColumn1["9"]["Colspan"] 		= "17";
		$configColumn1["9"]["Formato"] 		= "Number";
		$configColumn1["9"]["Total"] 		= False;
		$configColumn1["9"]["Alineacion"] 	= "Right";
		$configColumn1["9"]["TotalValor"] 	= 0;
		$configColumn1["9"]["FiledSoucePrefix"]	= "";
		$configColumn1["9"]["Width"]			= "195px";
		
		$objClient;
		$resultado2 = helper_createTableReportVertical(
			$objClient,
			$configColumn1,
			$resultado["columnas"],
			$resultado["width"]
		);
		
		echo $resultado2;
		?>
		
		<br/>
		
		<?php 
		echo $resultado["table"];
		?>
		
		<br/>		
		<?php 
		helper_echoFirma(	
			$objFirmaEncription,
			$resultado["columnas"],
			$resultado["width"]
		);
		?>
		
		
	</body>	
</html>
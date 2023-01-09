<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<title><?php echo $objFirmaEncription; ?></title>
		<meta name="viewport" 			content="width=device-width, initial-scale=1.0">
		<meta name="application-name" 	content="dsemp" /> 
		
		<?php 
		echo helper_reporteGeneralCreateStyle();
		?>
		
	</head>
	<body style="font-family:monospace;font-size:smaller;margin:0px 0px 0px 0px"> 
	
		
		<?php
		$configColumn["0"]["Titulo"] 		= "Codigo";		
		$configColumn["0"]["FiledSouce"]	= "customerNumber";
		$configColumn["0"]["Width"]			= "80px";
		
		$configColumn["1"]["Titulo"] 		= "Cliente";		
		$configColumn["1"]["FiledSouce"]	= "legalName";
		$configColumn["1"]["Width"]			= "220px";
		
		$configColumn["2"]["Titulo"] 		= "Fecha Docu.";		
		$configColumn["2"]["FiledSouce"]	= "documentFecha";
		$configColumn["2"]["Width"]			= "80px";
		
		$configColumn["3"]["Titulo"] 		= "Documento";		
		$configColumn["3"]["FiledSouce"]	= "documentNumber";
		$configColumn["3"]["Width"]			= "80px";
		
		$configColumn["4"]["Titulo"] 		= "Fecha Tran.";		
		$configColumn["4"]["FiledSouce"]	= "transactionFecha";
		$configColumn["4"]["Formato"]		= "Date";
		$configColumn["4"]["Width"]			= "80px";
		
		$configColumn["5"]["Titulo"] 		= "# Transaccion";		
		$configColumn["5"]["FiledSouce"]	= "transactionNumber";
		$configColumn["5"]["Width"]			= "90px";
		
		$configColumn["6"]["Titulo"] 		= "Descripcion";		
		$configColumn["6"]["FiledSouce"]	= "transactionName";
		$configColumn["6"]["Width"]			= "170px";
		
		$configColumn["7"]["Titulo"] 		= "U$ Balance ";		
		$configColumn["7"]["FiledSouce"]	= "balance";
		$configColumn["7"]["Width"]			= "90px";
		$configColumn["7"]["Formato"]		= "Number";
		$configColumn["7"]["Total"]			= True;
		
		$configColumn["8"]["Titulo"] 		= "U$ Capital ";		
		$configColumn["8"]["FiledSouce"]	= "capital";
		$configColumn["8"]["Width"]			= "90px";
		$configColumn["8"]["Formato"]		= "Number";
		$configColumn["8"]["Total"]			= True;
		
		$configColumn["9"]["Titulo"] 		= "U$ Interes ";		
		$configColumn["9"]["FiledSouce"]	= "interest";
		$configColumn["9"]["Width"]			= "90px";
		$configColumn["9"]["Formato"]		= "Number";
		$configColumn["9"]["Total"]			= True;
		
		$configColumn["10"]["Titulo"] 		= "T/C ";		
		$configColumn["10"]["FiledSouce"]	= "exchangeRate";
		$configColumn["10"]["Width"]		= "80px";
		$configColumn["10"]["Formato"]		= "Number";
		
		$configColumn["11"]["Titulo"] 		= "Venta ";		
		$configColumn["11"]["FiledSouce"]	= "sale";
		$configColumn["11"]["Width"]		= "80px";
		$configColumn["11"]["Formato"]		= "Number";
		
		$configColumn["12"]["Titulo"] 		= "Compra ";		
		$configColumn["12"]["FiledSouce"]	= "purchase";
		$configColumn["12"]["Width"]		= "90px";
		$configColumn["12"]["Formato"]		= "Number";
		
		$resultado = helper_reporteGeneralCreateTable(
			$objDetail,
			$configColumn,
			'0'
		);		
		?>
	
	
	
		
		<?php 
		echo helper_reporteGeneralCreateEncabezado(
			'INTERES POR PERIODO',
			$objCompany->name,
			$resultado["columnas"],
			'INTERES DE '.$startOn." AL ".$endOn,
			"",
			"",
			$resultado["width"]
		);
		?>
		
		<br/>	
		
		<?php 
		echo $resultado["table"];
		?>
		
		<br/>		
		<?php 
		echo helper_reporteGeneralCreateFirma(	
			$objFirmaEncription,
			$resultado["columnas"],
			$resultado["width"]
		);
		?>
		
		
	</body>	
</html>
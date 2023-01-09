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
		$configColumn["1"]["Titulo"] 		= "Cliente";		
		$configColumn["2"]["Titulo"] 		= "Fecha";		
		$configColumn["3"]["Titulo"] 		= "Periodo";		
		$configColumn["4"]["Titulo"] 		= "Moneda";		
		$configColumn["5"]["Titulo"] 		= "Capital";		
		$configColumn["6"]["Titulo"] 		= "Interes";		
		$configColumn["7"]["Titulo"] 		= "Cuota";		
		$configColumn["8"]["Titulo"] 		= "Restante";
		
		$configColumn["0"]["FiledSouce"] 		= "customerNumber";		
		$configColumn["1"]["FiledSouce"] 		= "legalName";		
		$configColumn["2"]["FiledSouce"] 		= "Fecha";		
		$configColumn["3"]["FiledSouce"] 		= "FechaPeriodo";		
		$configColumn["4"]["FiledSouce"] 		= "Moneda";		
		$configColumn["5"]["FiledSouce"] 		= "capital";		
		$configColumn["6"]["FiledSouce"] 		= "interest";		
		$configColumn["7"]["FiledSouce"] 		= "cuota";		
		$configColumn["8"]["FiledSouce"] 		= "remaining";
		
		$configColumn["0"]["Width"] 		= "80px";		
		$configColumn["1"]["Width"] 		= "220px";		
		$configColumn["2"]["Width"] 		= "80px";		
		$configColumn["3"]["Width"] 		= "80px";		
		$configColumn["4"]["Width"] 		= "80px";		
		$configColumn["5"]["Width"] 		= "80px";		
		$configColumn["6"]["Width"] 		= "80px";		
		$configColumn["7"]["Width"] 		= "80px";		
		$configColumn["8"]["Width"] 		= "80px";
		
		
		$configColumn["2"]["Formato"] 		= "Date";		
		$configColumn["5"]["Formato"] 		= "Number";		
		$configColumn["6"]["Formato"] 		= "Number";		
		$configColumn["7"]["Formato"] 		= "Number";		
		$configColumn["8"]["Formato"] 		= "Number";
		
			
		$configColumn["5"]["Total"] 		= "True";		
		$configColumn["6"]["Total"] 		= "True";		
		$configColumn["7"]["Total"] 		= "True";		
		$configColumn["8"]["Total"] 		= "True";
		
		
		
		
		
		$resultado = helper_reporteGeneralCreateTable($objDetail,$configColumn,'0');
		?>
		
		
		<?php 
		echo helper_reporteGeneralCreateEncabezado(
			'PROYECCION',
			$objCompany->name,
			$resultado["columnas"],
			'LISTA DE CUENTAS',
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
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
		$configColumn["0"]["Titulo"] 		= "Fecha";
		$configColumn["1"]["Titulo"] 		= "Cordoba";
		$configColumn["2"]["Titulo"] 		= "Oficial";
		$configColumn["3"]["Titulo"] 		= "Compra";
		$configColumn["4"]["Titulo"] 		= "Venta";
		
		$configColumn["0"]["FiledSouce"] 		= "Fecha";
		$configColumn["1"]["FiledSouce"] 		= "Cordoba";
		$configColumn["2"]["FiledSouce"] 		= "Oficial";
		$configColumn["3"]["FiledSouce"] 		= "Compra";
		$configColumn["4"]["FiledSouce"] 		= "Venta";
		
		$configColumn["0"]["Width"] 		= "120px";
		$configColumn["1"]["Width"] 		= "80px";
		$configColumn["2"]["Width"] 		= "80px";
		$configColumn["3"]["Width"] 		= "80px";
		$configColumn["4"]["Width"] 		= "80px";
		
		$configColumn["0"]["Formato"] 		= "Date";
		$configColumn["1"]["Formato"] 		= "";
		$configColumn["2"]["Formato"] 		= "Number";
		$configColumn["3"]["Formato"] 		= "Number";
		$configColumn["4"]["Formato"] 		= "Number";
		
		
		$resultado = helper_reporteGeneralCreateTable($objDetail,$configColumn,'0px');
		
		?>
		
		<?php 
		echo helper_reporteGeneralCreateEncabezado(
			'TIPO DE CAMBIO',
			$objCompany->name,
			$resultado["columnas"],
			"",
			"",
			"",
			$resultado["width"]
		);
		?>
		
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
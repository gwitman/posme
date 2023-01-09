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
		$configColumn["0"]["Titulo"]		= "Fecha";
		$configColumn["0"]["FiledSouce"]	= "createdOn";
		$configColumn["0"]["Width"]			= "80px";
		$configColumn["0"]["Formato"]		= "Date";
		
		$configColumn["1"]["Titulo"]		= "No Pago";
		$configColumn["1"]["FiledSouce"]	= "transactionNumber";
		$configColumn["1"]["Width"]			= "80px";
		
		$configColumn["2"]["Titulo"]		= "Referencia";
		$configColumn["2"]["FiledSouce"]	= "reference1";
		$configColumn["2"]["Width"]			= "80px";
		
		$configColumn["3"]["Titulo"]		= "Moneda";
		$configColumn["3"]["FiledSouce"]	= "MonedaDesembolso";
		$configColumn["3"]["Width"]			= "80px";
		
		$configColumn["4"]["Titulo"]		= "Abono";
		$configColumn["4"]["FiledSouce"]	= "Pago";
		$configColumn["4"]["Width"]			= "120px";
		$configColumn["4"]["Formato"]		= "Number";
		$configColumn["4"]["Total"]			= True;
		
		$configColumn["5"]["Titulo"]		= "Saldo Anterior";
		$configColumn["5"]["FiledSouce"]	= "SaldoAterior";
		$configColumn["5"]["Width"]			= "120px";
		$configColumn["5"]["Formato"]		= "Number";
		$configColumn["5"]["Total"]			= True;
		
		$configColumn["6"]["Titulo"]		= "Saldo Nuevo";
		$configColumn["6"]["FiledSouce"]	= "SaldoNuevo";
		$configColumn["6"]["Width"]			= "120px";
		$configColumn["6"]["Formato"]		= "Number";
		$configColumn["6"]["Total"]			= True;
		
		$configColumn["7"]["Titulo"]		= "Nota";		
		$configColumn["7"]["FiledSouce"]	= "note";
		$configColumn["7"]["Width"]			= "210px";
		
		
		$resultado = helper_reporteGeneralCreateTable($objPayList,$configColumn,'0');
		?>
		
		
		<?php 
		echo helper_reporteGeneralCreateEncabezado(
			'LISTADO DE PAGOS',
			$objCompany->name,
			$resultado["columnas"],
			'LISTADO DE ABONOS O PAGOS DEL CLIENTE :'.$objClient["customerNumber"],
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
		$configColumn1["0"]["Colspan"] 		= "7";
		$configColumn1["0"]["Formato"] 		= "";
		$configColumn1["0"]["Total"] 		= False;
		$configColumn1["0"]["Alineacion"] 	= "Left";
		$configColumn1["0"]["TotalValor"] 	= 0;
		$configColumn1["0"]["FiledSoucePrefix"]	= "";
		$configColumn1["0"]["Width"]			= "195px";
		
		$configColumn1["1"]["Titulo"] 		= "Tipo de Identificacion";
		$configColumn1["1"]["TituloFoot"]	= "";
		$configColumn1["1"]["FiledSouce"]	= "identificationType";
		$configColumn1["1"]["Colspan"] 		= "7";
		$configColumn1["1"]["Formato"] 		= "";
		$configColumn1["1"]["Total"] 		= False;
		$configColumn1["1"]["Alineacion"] 	= "Left";
		$configColumn1["1"]["TotalValor"] 	= 0;
		$configColumn1["1"]["FiledSoucePrefix"]	= "";
		$configColumn1["1"]["Width"]			= "195px";
		
		$configColumn1["2"]["Titulo"] 		= "Pais";
		$configColumn1["2"]["TituloFoot"]	= "";
		$configColumn1["2"]["FiledSouce"]	= "country";
		$configColumn1["2"]["Colspan"] 		= "7";
		$configColumn1["2"]["Formato"] 		= "";
		$configColumn1["2"]["Total"] 		= False;
		$configColumn1["2"]["Alineacion"] 	= "Left";
		$configColumn1["2"]["TotalValor"] 	= 0;
		$configColumn1["2"]["FiledSoucePrefix"]	= "";
		$configColumn1["2"]["Width"]			= "195px";
		
		$configColumn1["3"]["Titulo"] 		= "Estado";
		$configColumn1["3"]["TituloFoot"]	= "";
		$configColumn1["3"]["FiledSouce"]	= "state";
		$configColumn1["3"]["Colspan"] 		= "7";
		$configColumn1["3"]["Formato"] 		= "";
		$configColumn1["3"]["Total"] 		= False;
		$configColumn1["3"]["Alineacion"] 	= "Left";
		$configColumn1["3"]["TotalValor"] 	= 0;
		$configColumn1["3"]["FiledSoucePrefix"]	= "";
		$configColumn1["3"]["Width"]			= "195px";
		
		$configColumn1["4"]["Titulo"] 		= "Ciudad";
		$configColumn1["4"]["TituloFoot"]	= "";
		$configColumn1["4"]["FiledSouce"]	= "city";
		$configColumn1["4"]["Colspan"] 		= "7";
		$configColumn1["4"]["Formato"] 		= "";
		$configColumn1["4"]["Total"] 		= False;
		$configColumn1["4"]["Alineacion"] 	= "Left";
		$configColumn1["4"]["TotalValor"] 	= 0;
		$configColumn1["4"]["FiledSoucePrefix"]	= "";
		$configColumn1["4"]["Width"]			= "195px";
		
		$configColumn1["5"]["Titulo"] 		= "Nacimiento";
		$configColumn1["5"]["TituloFoot"]	= "";
		$configColumn1["5"]["FiledSouce"]	= "birth";
		$configColumn1["5"]["Colspan"] 		= "7";
		$configColumn1["5"]["Formato"] 		= "Date";
		$configColumn1["5"]["Total"] 		= False;
		$configColumn1["5"]["Alineacion"] 	= "Left";
		$configColumn1["5"]["TotalValor"] 	= 0;
		$configColumn1["5"]["FiledSoucePrefix"]	= "";
		$configColumn1["5"]["Width"]			= "195px";
		
		$configColumn1["6"]["Titulo"] 		= "Estado del Cliente";
		$configColumn1["6"]["TituloFoot"]	= "";
		$configColumn1["6"]["FiledSouce"]	= "statusClient";
		$configColumn1["6"]["Colspan"] 		= "7";
		$configColumn1["6"]["Formato"] 		= "";
		$configColumn1["6"]["Total"] 		= False;
		$configColumn1["6"]["Alineacion"] 	= "Left";
		$configColumn1["6"]["TotalValor"] 	= 0;
		$configColumn1["6"]["FiledSoucePrefix"]	= "";
		$configColumn1["6"]["Width"]			= "195px";
		
		$configColumn1["7"]["Titulo"] 		= "Limite";
		$configColumn1["7"]["TituloFoot"]	= "";
		$configColumn1["7"]["FiledSouce"]	= "limitCreditCordoba";
		$configColumn1["7"]["Colspan"] 		= "7";
		$configColumn1["7"]["Formato"] 		= "Number";
		$configColumn1["7"]["Total"] 		= False;
		$configColumn1["7"]["Alineacion"] 	= "Right";
		$configColumn1["7"]["TotalValor"] 	= 0;
		$configColumn1["7"]["FiledSoucePrefix"]	= "";
		$configColumn1["7"]["Width"]			= "195px";
		
		$configColumn1["8"]["Titulo"] 		= "Balance";
		$configColumn1["8"]["TituloFoot"]	= "";
		$configColumn1["8"]["FiledSouce"]	= "balanceCordoba";
		$configColumn1["8"]["Colspan"] 		= "7";
		$configColumn1["8"]["Formato"] 		= "Number";
		$configColumn1["8"]["Total"] 		= False;
		$configColumn1["8"]["Alineacion"] 	= "Right";
		$configColumn1["8"]["TotalValor"] 	= 0;
		$configColumn1["8"]["FiledSoucePrefix"]	= "";
		$configColumn1["8"]["Width"]			= "195px";
		
		$configColumn1["9"]["Titulo"] 		= "Ingreso";
		$configColumn1["9"]["TituloFoot"]	= "";
		$configColumn1["9"]["FiledSouce"]	= "incomeCordoba";
		$configColumn1["9"]["Colspan"] 		= "7";
		$configColumn1["9"]["Formato"] 		= "Number";
		$configColumn1["9"]["Total"] 		= False;
		$configColumn1["9"]["Alineacion"] 	= "Right";
		$configColumn1["9"]["TotalValor"] 	= 0;
		$configColumn1["9"]["FiledSoucePrefix"]	= "";
		$configColumn1["9"]["Width"]			= "195px";
		
		$objClient;
		$resultado2 = helper_reporteGeneralCreateTableVertical(
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
		helper_reporteGeneralCreateFirma(	
			$objFirmaEncription,
			$resultado["columnas"],
			$resultado["width"]
		);
		?>
		
		
		
		
		
	</body>	
</html>
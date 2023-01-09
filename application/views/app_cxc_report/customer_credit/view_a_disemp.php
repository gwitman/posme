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
		$configColumn["0"]["Titulo"] 		= "Cliente";
		$configColumn["0"]["TituloFoot"]	= "";
		$configColumn["0"]["FiledSouce"]	= "customerNumber";
		$configColumn["0"]["Colspan"] 		= "1";
		$configColumn["0"]["Formato"] 		= "";
		$configColumn["0"]["Total"] 		= False;
		$configColumn["0"]["Alineacion"] 	= "Left";
		$configColumn["0"]["TotalValor"] 	= 0;
		$configColumn["0"]["FiledSoucePrefix"]	= "";
		$configColumn["0"]["Width"]			= "80px";
		
		$configColumn["1"]["Titulo"] 		= "Nombre";
		$configColumn["1"]["TituloFoot"]	= "";
		$configColumn["1"]["FiledSouce"]	= "legalName";
		$configColumn["1"]["Colspan"] 		= "1";
		$configColumn["1"]["Formato"] 		= "";
		$configColumn["1"]["Total"] 		= False;
		$configColumn["1"]["Alineacion"] 	= "Left";
		$configColumn["1"]["TotalValor"] 	= 0;
		$configColumn["1"]["FiledSoucePrefix"]	= "";
		$configColumn["1"]["Width"]			= "250px";
		
		$configColumn["2"]["Titulo"] 		= "Mora";
		$configColumn["2"]["TituloFoot"]	= "";
		$configColumn["2"]["FiledSouce"]	= "maxDiasMora";
		$configColumn["2"]["Colspan"] 		= "1";
		$configColumn["2"]["Formato"] 		= "Number";
		$configColumn["2"]["Total"] 		= False;
		$configColumn["2"]["Alineacion"] 	= "Right";
		$configColumn["2"]["TotalValor"] 	= 0;
		$configColumn["2"]["FiledSoucePrefix"]	= "";
		$configColumn["2"]["Width"]			= "50px";
		
		$configColumn["3"]["Titulo"] 		= "Factura";
		$configColumn["3"]["TituloFoot"]	= "";
		$configColumn["3"]["FiledSouce"]	= "factura";
		$configColumn["3"]["Colspan"] 		= "1";
		$configColumn["3"]["Formato"] 		= "";
		$configColumn["3"]["Total"] 		= False;
		$configColumn["3"]["Alineacion"] 	= "Left";
		$configColumn["3"]["TotalValor"] 	= 0;
		$configColumn["3"]["FiledSoucePrefix"]	= "";
		$configColumn["3"]["Width"]			= "80px";
		
		$configColumn["4"]["Titulo"] 		= "Atrazado";
		$configColumn["4"]["TituloFoot"]	= "";
		$configColumn["4"]["FiledSouce"]	= "montoAtrazado";
		$configColumn["4"]["Colspan"] 		= "1";
		$configColumn["4"]["Formato"] 		= "Number";
		$configColumn["4"]["Total"] 		= True;
		$configColumn["4"]["Alineacion"] 	= "Right";
		$configColumn["4"]["TotalValor"] 	= 0;
		$configColumn["4"]["FiledSoucePrefix"]	= "moneda";
		$configColumn["4"]["Width"]			= "80px";
			
		$configColumn["5"]["Titulo"] 		= "Proximo Pago";
		$configColumn["5"]["TituloFoot"]	= "";
		$configColumn["5"]["FiledSouce"]	= "proximoPago";
		$configColumn["5"]["Colspan"] 		= "1";
		$configColumn["5"]["Formato"] 		= "Date";
		$configColumn["5"]["Total"] 		= False;
		$configColumn["5"]["Alineacion"] 	= "Left";
		$configColumn["5"]["TotalValor"] 	= 0;
		$configColumn["5"]["FiledSoucePrefix"]	= "";
		$configColumn["5"]["Width"]			= "90px";
		
		$configColumn["6"]["Titulo"] 		= "M.Proximo Pago";
		$configColumn["6"]["TituloFoot"]	= "";
		$configColumn["6"]["FiledSouce"]	= "montoProximoPago";
		$configColumn["6"]["Colspan"] 		= "1";
		$configColumn["6"]["Formato"] 		= "Number";
		$configColumn["6"]["Total"] 		= True;
		$configColumn["6"]["Alineacion"] 	= "Right";
		$configColumn["6"]["TotalValor"] 	= 0;
		$configColumn["6"]["FiledSoucePrefix"]	= "moneda";
		$configColumn["6"]["Width"]			= "112px";
		
		$configColumn["7"]["Titulo"] 		= "Cancelacion";
		$configColumn["7"]["TituloFoot"]	= "";
		$configColumn["7"]["FiledSouce"]	= "ultimoPagoFecha";
		$configColumn["7"]["Colspan"] 		= "1";
		$configColumn["7"]["Formato"] 		= "Date";
		$configColumn["7"]["Total"] 		= False;
		$configColumn["7"]["Alineacion"] 	= "Left";
		$configColumn["7"]["TotalValor"] 	= 0;
		$configColumn["7"]["FiledSoucePrefix"]	= "";
		$configColumn["7"]["Width"]			= "90px";
		
		$configColumn["8"]["Titulo"] 		= "Id";
		$configColumn["8"]["TituloFoot"]	= "";
		$configColumn["8"]["FiledSouce"]	= "identification";
		$configColumn["8"]["Colspan"] 		= "1";
		$configColumn["8"]["Formato"] 		= "";
		$configColumn["8"]["Total"] 		= False;
		$configColumn["8"]["Alineacion"] 	= "Left";
		$configColumn["8"]["TotalValor"] 	= 0;
		$configColumn["8"]["FiledSoucePrefix"]	= "";
		$configColumn["8"]["Width"]			= "130px";
			
		$configColumn["9"]["Titulo"] 		= "Telefono";
		$configColumn["9"]["TituloFoot"]	= "";
		$configColumn["9"]["FiledSouce"]	= "phone";
		$configColumn["9"]["Colspan"] 		= "1";
		$configColumn["9"]["Formato"] 		= "";
		$configColumn["9"]["Total"] 		= False;
		$configColumn["9"]["Alineacion"] 	= "Left";
		$configColumn["9"]["TotalValor"] 	= 0;
		$configColumn["9"]["FiledSoucePrefix"]	= "";
		$configColumn["9"]["Width"]			= "90px";
		
		$configColumn["10"]["Titulo"] 		= "Ultima Cuota";
		$configColumn["10"]["TituloFoot"]	= "";
		$configColumn["10"]["FiledSouce"]	= "lastShareNumber";
		$configColumn["10"]["Colspan"] 		= "1";
		$configColumn["10"]["Formato"] 		= "";
		$configColumn["10"]["Total"] 		= False;
		$configColumn["10"]["Alineacion"] 	= "Left";
		$configColumn["10"]["TotalValor"] 	= 0;
		$configColumn["10"]["FiledSoucePrefix"]	= "";
		$configColumn["10"]["Width"]			= "90px";
		
		$configColumn["11"]["Titulo"] 		= "F.Ultima Cuota";
		$configColumn["11"]["TituloFoot"]	= "";
		$configColumn["11"]["FiledSouce"]	= "dateLastShareNumber";
		$configColumn["11"]["Colspan"] 		= "1";
		$configColumn["11"]["Formato"] 		= "Date";
		$configColumn["11"]["Total"] 		= False;
		$configColumn["11"]["Alineacion"] 	= "Left";
		$configColumn["11"]["TotalValor"] 	= 0;
		$configColumn["11"]["FiledSoucePrefix"]	= "";
		$configColumn["11"]["Width"]			= "125px";
		
		$configColumn["12"]["Titulo"] 		= "M.Ultima Cuota";
		$configColumn["12"]["TituloFoot"]	= "";
		$configColumn["12"]["FiledSouce"]	= "amountLastShareNumber";
		$configColumn["12"]["Colspan"] 		= "1";
		$configColumn["12"]["Formato"] 		= "Number";
		$configColumn["12"]["Total"] 		= True;
		$configColumn["12"]["Alineacion"] 	= "Right";
		$configColumn["12"]["TotalValor"] 	= 0;
		$configColumn["12"]["FiledSoucePrefix"]	= "moneda";
		$configColumn["12"]["Width"]			= "125px";
		
		$configColumn["13"]["Titulo"] 		= "Direccion";
		$configColumn["13"]["TituloFoot"]	= "";
		$configColumn["13"]["FiledSouce"]	= "direccion";
		$configColumn["13"]["Colspan"] 		= "1";
		$configColumn["13"]["Formato"] 		= "";
		$configColumn["13"]["Total"] 		= False;
		$configColumn["13"]["Alineacion"] 	= "Left";
		$configColumn["13"]["TotalValor"] 		= 0;
		$configColumn["13"]["FiledSoucePrefix"]	= "";
		$configColumn["13"]["Width"]			= "200px";
		
		
		$resultado = helper_reporteGeneralCreateTable($objDetail,$configColumn,'0');
		?>
		
		<?php 
		echo helper_reporteGeneralCreateEncabezado(
			'CARTERA DE CREDITO',
			$objCompany->name,
			$resultado["columnas"],
			'',
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
<!DOCTYPE html>
<html lang="en" xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40" >
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
		$configColumn["2"]["Titulo"] 		= "Factura";
		$configColumn["3"]["Titulo"] 		= "Tipo Cambio";
		$configColumn["4"]["Titulo"] 		= "Capital Inicial";
		$configColumn["5"]["Titulo"] 		= "Capital Actual";
		$configColumn["6"]["Titulo"] 		= "Provisionado";
		$configColumn["7"]["Titulo"] 		= "Cuota Promedio";
		$configColumn["8"]["Titulo"] 		= "Interes Mensual";
		$configColumn["9"]["Titulo"] 		= "Interes Anual";
		$configColumn["10"]["Titulo"] 		= "# Cuotas";
		$configColumn["11"]["Titulo"] 		= "# Meses";
		$configColumn["12"]["Titulo"] 		= "Frecuencia de Pago";
		$configColumn["13"]["Titulo"] 		= "Tipo";
		$configColumn["14"]["Titulo"] 		= "Moneda";
		$configColumn["15"]["Titulo"] 		= "Ultima Fecha";
		$configColumn["16"]["Titulo"] 		= "Dias para Cancelar";
		$configColumn["17"]["Titulo"] 		= "Meses para Cancelar";
		$configColumn["18"]["Titulo"] 		= "Meses para Cancelar %";
		
		
		$configColumn["0"]["FiledSouce"] 		= "codigoCliente";
		$configColumn["1"]["FiledSouce"] 		= "cliente";
		$configColumn["2"]["FiledSouce"] 		= "Factura";
		$configColumn["3"]["FiledSouce"] 		= "TipoCambio";
		$configColumn["4"]["FiledSouce"] 		= "capitalInicial";
		$configColumn["5"]["FiledSouce"] 		= "capitalActual";
		$configColumn["6"]["FiledSouce"] 		= "Provisionado";
		$configColumn["7"]["FiledSouce"] 		= "cuotaPromedio";
		$configColumn["8"]["FiledSouce"] 		= "interesMensual";
		$configColumn["9"]["FiledSouce"] 		= "interesAnual";
		$configColumn["10"]["FiledSouce"] 		= "numeroCuotas";
		$configColumn["11"]["FiledSouce"] 		= "numeroDeMeses";
		$configColumn["12"]["FiledSouce"] 		= "frecuenciaPagoEnDia";
		$configColumn["13"]["FiledSouce"] 		= "amortizacion";
		$configColumn["14"]["FiledSouce"] 		= "moneda";
		$configColumn["15"]["FiledSouce"] 		= "ultimaFecha";
		$configColumn["16"]["FiledSouce"] 		= "diasParaCancelar";
		$configColumn["17"]["FiledSouce"] 		= "mesParaCancelar";
		$configColumn["18"]["FiledSouce"] 		= "mesParaCancelar%";
		
		$configColumn["0"]["Width"] 		= "80px";
		$configColumn["1"]["Width"] 		= "220px";
		$configColumn["2"]["Width"] 		= "80px";
		$configColumn["3"]["Width"] 		= "110px";
		$configColumn["4"]["Width"] 		= "120px";
		$configColumn["5"]["Width"] 		= "120px";
		$configColumn["6"]["Width"] 		= "120px";
		$configColumn["7"]["Width"] 		= "120px";
		$configColumn["8"]["Width"] 		= "120px";
		$configColumn["9"]["Width"] 		= "120px";
		$configColumn["10"]["Width"] 		= "120px";
		$configColumn["11"]["Width"] 		= "120px";
		$configColumn["12"]["Width"] 		= "120px";
		$configColumn["13"]["Width"] 		= "120px";
		$configColumn["14"]["Width"] 		= "120px";
		$configColumn["15"]["Width"] 		= "120px";
		$configColumn["16"]["Width"] 		= "140px";
		$configColumn["17"]["Width"] 		= "140px";
		$configColumn["18"]["Width"] 		= "140px";
		
		
		$configColumn["0"]["Formato"] 		= "";
		$configColumn["1"]["Formato"] 		= "";
		$configColumn["2"]["Formato"] 		= "";
		$configColumn["3"]["Formato"] 		= "Number";
		$configColumn["4"]["Formato"] 		= "Number";
		$configColumn["5"]["Formato"] 		= "Number";
		$configColumn["6"]["Formato"] 		= "Number";
		$configColumn["7"]["Formato"] 		= "Number";
		$configColumn["8"]["Formato"] 		= "Number";
		$configColumn["9"]["Formato"] 		= "Number";
		$configColumn["10"]["Formato"] 		= "Number";
		$configColumn["11"]["Formato"] 		= "Number";
		$configColumn["12"]["Formato"] 		= "Number";
		$configColumn["13"]["Formato"] 		= "";
		$configColumn["14"]["Formato"] 		= "";
		$configColumn["15"]["Formato"] 		= "Date";
		$configColumn["16"]["Formato"] 		= "Number";
		$configColumn["17"]["Formato"] 		= "Number";
		$configColumn["18"]["Formato"] 		= "Number";
		
		
		$configColumn["0"]["FiledSoucePrefix"] 			= "";
		$configColumn["1"]["FiledSoucePrefix"] 			= "";
		$configColumn["2"]["FiledSoucePrefix"] 			= "";
		$configColumn["3"]["FiledSoucePrefix"] 			= "simbolo";
		$configColumn["4"]["FiledSoucePrefix"] 			= "simbolo";
		$configColumn["5"]["FiledSoucePrefix"] 			= "simbolo";
		$configColumn["6"]["FiledSoucePrefix"] 			= "simbolo";
		$configColumn["7"]["FiledSoucePrefix"] 			= "simbolo";
		$configColumn["8"]["FiledSoucePrefix"] 			= "simbolo";
		$configColumn["9"]["FiledSoucePrefix"] 			= "simbolo";
		$configColumn["10"]["FiledSoucePrefix"] 		= "";
		$configColumn["11"]["FiledSoucePrefix"] 		= "";
		$configColumn["12"]["FiledSoucePrefix"] 		= "";
		$configColumn["13"]["FiledSoucePrefix"] 		= "";
		$configColumn["14"]["FiledSoucePrefix"] 		= "";
		$configColumn["15"]["FiledSoucePrefix"] 		= "";
		$configColumn["16"]["FiledSoucePrefix"] 		= "";
		$configColumn["17"]["FiledSoucePrefix"] 		= "";
		$configColumn["18"]["FiledSoucePrefix"] 		= "";	
		$configColumn["18"]["FiledSoucePrefixCustom"] 	= "%";
		
		
		$configColumn["4"]["Promedio"] 						= True;
		$configColumn["5"]["Promedio"] 						= True;
		$configColumn["7"]["Promedio"] 						= True;		
		$configColumn["8"]["Promedio"] 							= True;
		$configColumn["9"]["Promedio"] 							= True;
		$configColumn["10"]["Promedio"] 						= True;
		$configColumn["11"]["Promedio"] 						= True;
		$configColumn["12"]["Promedio"] 						= True;		
		$configColumn["16"]["Promedio"] 						= True;
		$configColumn["17"]["Promedio"] 						= True;
		$configColumn["18"]["Promedio"] 						= True;
		
		
		
		$resultado = helper_reporteGeneralCreateTable($objDetail,$configColumn,'0px');
		//log_message("ERROR",print_r($objDetail,true));
		?>
		
		
		<?php 
		echo helper_reporteGeneralCreateEncabezado(
			'RESUMEN DE CLIENTES CXC',
			$objCompany->name,
			$resultado["columnas"],
			'RESUMEN ESTADISTICO DE CLIENTES',
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
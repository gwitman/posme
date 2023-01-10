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
		$configColumn["0"]["Titulo"] 		= "Consecutivo";
		$configColumn["1"]["Titulo"] 		= "RequestID";
		$configColumn["2"]["Titulo"] 		= "Cliente";
		$configColumn["3"]["Titulo"] 		= "Cedula del Cliente";
		$configColumn["4"]["Titulo"] 		= "Archivo";
		$configColumn["5"]["Titulo"] 		= "Fecha de Consulta";
		$configColumn["6"]["Titulo"] 		= "Usuario";
		$configColumn["7"]["Titulo"] 		= "Estado";
		
		$configColumn["0"]["FiledSouce"] 		= "requestID";
		$configColumn["1"]["FiledSouce"] 		= "requestID";
		$configColumn["2"]["FiledSouce"] 		= "cliente";
		$configColumn["3"]["FiledSouce"] 		= "cedulaCliente";
		$configColumn["4"]["FiledSouce"] 		= "file_";
		$configColumn["5"]["FiledSouce"] 		= "createdOn";
		$configColumn["6"]["FiledSouce"] 		= "Usuario";
		$configColumn["7"]["FiledSouce"] 		= "Estado";
		
		$configColumn["0"]["Width"] 		= "110px";
		$configColumn["1"]["Width"] 		= "100px";
		$configColumn["2"]["Width"] 		= "250px";
		$configColumn["3"]["Width"] 		= "130px";
		$configColumn["4"]["Width"] 		= "250px";
		$configColumn["5"]["Width"] 		= "130px";
		$configColumn["6"]["Width"] 		= "120px";
		$configColumn["7"]["Width"] 		= "110px";
		
		$configColumn["0"]["Formato"] 		= "";
		$configColumn["1"]["Formato"] 		= "";
		$configColumn["2"]["Formato"] 		= "";
		$configColumn["3"]["Formato"] 		= "";
		$configColumn["4"]["Formato"] 		= "";
		$configColumn["5"]["Formato"] 		= "Date";
		$configColumn["6"]["Formato"] 		= "";
		$configColumn["7"]["Formato"] 		= "";
		
		$configColumn["0"]["AutoIncrement"]  = True;
		
		
		$configColumn["4"]["IsUrl"]			= True;
		$configColumn["4"]["FiledSouceUrl"]	= "file_";
		$configColumn["4"]["Url"]			= site_url()."app_cxc_record/index.aspx?file_exists=";
		
		
		
		
		
		
		$resultado = helper_reporteGeneralCreateTable($objDetail,$configColumn,'0px');
		//log_message("ERROR",print_r($objDetail,true));
		?>
		
		
		<?php 
		echo helper_reporteGeneralCreateEncabezado(
			'LISTA DE CONSULTAS REALIZADAS AL BURO DE CREDITO',
			$objCompany->name,
			$resultado["columnas"],
			'DEL '.$startOn.' AL '.$endOn,
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
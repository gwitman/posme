<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<title><?php echo $objFirmaEncription; ?></title>
		<meta name="viewport" 			content="width=device-width, initial-scale=1.0">
		<meta name="application-name" 	content="dsemp" /> 
		
			
		<?php 
		helper_echoStyleReport();
		?>
		
	</head>
	<body style="font-family:monospace;font-size:smaller;margin:0px 0px 0px 0px"> 
	
		<?php 
		helper_header(
			'LISTA DE CLIENTES',
			$objCompany->name,9,
			'LISTA DE CLIENTES',
			"",
			"",'930px'
		);
		?>
		
		
		<br/>
		
		<?php
		$configColumn["0"]["Titulo"] 		= "#";
		$configColumn["0"]["TituloFoot"]	= "";
		$configColumn["0"]["FiledSouce"]	= "customerNumber";
		$configColumn["0"]["Colspan"] 		= "1";
		$configColumn["0"]["Formato"] 		= "";
		$configColumn["0"]["Total"] 		= False;
		$configColumn["0"]["Alineacion"] 	= "Left";
		$configColumn["0"]["TotalValor"] 	= 0;
		$configColumn["0"]["FiledSoucePrefix"]	= "";
		$configColumn["0"]["Width"]			= "30px";
		$configColumn["0"]["AutoIncrement"]	= True;
		
		$configColumn["1"]["Titulo"] 		= "Codigo";
		$configColumn["1"]["TituloFoot"]	= "";
		$configColumn["1"]["FiledSouce"]	= "customerNumber";
		$configColumn["1"]["Colspan"] 		= "1";
		$configColumn["1"]["Formato"] 		= "";
		$configColumn["1"]["Total"] 		= False;
		$configColumn["1"]["Alineacion"] 	= "Left";
		$configColumn["1"]["TotalValor"] 	= 0;
		$configColumn["1"]["FiledSoucePrefix"]	= "";
		$configColumn["1"]["Width"]			= "80px";
		$configColumn["1"]["AutoIncrement"]	= False;
		
		$configColumn["2"]["Titulo"] 		= "Cliente";
		$configColumn["2"]["TituloFoot"]	= "";
		$configColumn["2"]["FiledSouce"]	= "customerName";
		$configColumn["2"]["Colspan"] 		= "1";
		$configColumn["2"]["Formato"] 		= "";
		$configColumn["2"]["Total"] 		= False;
		$configColumn["2"]["Alineacion"] 	= "Left";
		$configColumn["2"]["TotalValor"] 	= 0;
		$configColumn["2"]["FiledSoucePrefix"]	= "";
		$configColumn["2"]["Width"]			= "260px";
		$configColumn["2"]["AutoIncrement"]	= False;
		
		$configColumn["3"]["Titulo"] 		= "Cedula";
		$configColumn["3"]["TituloFoot"]	= "";
		$configColumn["3"]["FiledSouce"]	= "identification";
		$configColumn["3"]["Colspan"] 		= "1";
		$configColumn["3"]["Formato"] 		= "";
		$configColumn["3"]["Total"] 		= False;
		$configColumn["3"]["Alineacion"] 	= "Left";
		$configColumn["3"]["TotalValor"] 	= 0;
		$configColumn["3"]["FiledSoucePrefix"]	= "";
		$configColumn["3"]["Width"]			= "123px";
		$configColumn["3"]["AutoIncrement"]	= False;
		
		$configColumn["4"]["Titulo"] 		= "Telefono";
		$configColumn["4"]["TituloFoot"]	= "";
		$configColumn["4"]["FiledSouce"]	= "phone";
		$configColumn["4"]["Colspan"] 		= "1";
		$configColumn["4"]["Formato"] 		= "";
		$configColumn["4"]["Total"] 		= False;
		$configColumn["4"]["Alineacion"] 	= "Left";
		$configColumn["4"]["TotalValor"] 	= 0;
		$configColumn["4"]["FiledSoucePrefix"]	= "";
		$configColumn["4"]["Width"]			= "80px";
		$configColumn["4"]["AutoIncrement"]	= False;
			
		$configColumn["5"]["Titulo"] 		= "Email";
		$configColumn["5"]["TituloFoot"]	= "";
		$configColumn["5"]["FiledSouce"]	= "email";
		$configColumn["5"]["Colspan"] 		= "1";
		$configColumn["5"]["Formato"] 		= "";
		$configColumn["5"]["Total"] 		= False;
		$configColumn["5"]["Alineacion"] 	= "Left";
		$configColumn["5"]["TotalValor"] 	= 0;
		$configColumn["5"]["FiledSoucePrefix"]	= "";
		$configColumn["5"]["Width"]			= "100px";
		$configColumn["5"]["AutoIncrement"]	= False;
		
		$configColumn["6"]["Titulo"] 		= "Balance";
		$configColumn["6"]["TituloFoot"]	= "";
		$configColumn["6"]["FiledSouce"]	= "balance";
		$configColumn["6"]["Colspan"] 		= "1";
		$configColumn["6"]["Formato"] 		= "Number";
		$configColumn["6"]["Total"] 		= True;
		$configColumn["6"]["Alineacion"] 	= "Right";
		$configColumn["6"]["TotalValor"] 	= 0;
		$configColumn["6"]["FiledSoucePrefix"]	= "";
		$configColumn["6"]["Width"]			= "80px";
		$configColumn["6"]["AutoIncrement"]	= False;
		
		$configColumn["7"]["Titulo"] 		= "Cartera";
		$configColumn["7"]["TituloFoot"]	= "";
		$configColumn["7"]["FiledSouce"]	= "customerNumber";
		$configColumn["7"]["Colspan"] 		= "1";
		$configColumn["7"]["Formato"] 		= "";
		$configColumn["7"]["Total"] 		= False;
		$configColumn["7"]["Alineacion"] 	= "Left";
		$configColumn["7"]["TotalValor"] 	= 0;
		$configColumn["7"]["FiledSoucePrefix"]	= "";
		$configColumn["7"]["Width"]			= "80px";
		$configColumn["7"]["AutoIncrement"]	= False;
		$configColumn["7"]["IsUrl"]			= True;
		$configColumn["7"]["FiledSouceUrl"]	= "customerNumber";
		$configColumn["7"]["Url"]			= site_url()."app_cxc_report/customer_status/viewReport/true/customerNumber/";
		
		$configColumn["8"]["Titulo"] 		= "Pagos";
		$configColumn["8"]["TituloFoot"]	= "";
		$configColumn["8"]["FiledSouce"]	= "customerNumber";
		$configColumn["8"]["Colspan"] 		= "1";
		$configColumn["8"]["Formato"] 		= "";
		$configColumn["8"]["Total"] 		= False;
		$configColumn["8"]["Alineacion"] 	= "Left";
		$configColumn["8"]["TotalValor"] 	= 0;
		$configColumn["8"]["FiledSoucePrefix"]	= "";
		$configColumn["8"]["Width"]			= "80px";
		$configColumn["8"]["AutoIncrement"]	= False;
		$configColumn["8"]["IsUrl"]			= True;
		$configColumn["8"]["FiledSouceUrl"]	= "customerNumber";
		$configColumn["8"]["Url"]			= site_url()."app_cxc_report/pay/viewReport/true/customerNumber/";
			
		helper_createTableReport($objDetail,$configColumn,'930px');
		?>
	
		
		
		<br/>		
		<?php 
		helper_echoFirma($objFirmaEncription,9,'930px');
		?>
		
		
	</body>	
</html>
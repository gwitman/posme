<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<title></title>
		<meta name="viewport" 			content="width=device-width, initial-scale=1.0">
		<meta name="application-name" 	content="dsemp" /> 		
	</head>

	<body style="font-family:'Open Sans';">
		<table style="width:500px">
			<caption style="background: #00628e;color: #fff;"><h1>posMe</h1></caption>
			<caption>Notificación</caption>
			<thead style="background:#67c977">
				<th>Estimado señores de: 
				<?php 
					if(isset($objCompany)) echo $objCompany->name;
					else echo "N/D";
				?>
				</th>
			</thead>
			<tbody>
				<tr>
					<td>
						<p style="text-align:justify">
						<b>En sus mano</b> 
						<?php 
							if(isset($mensaje)) echo $mensaje;
							else echo "N/D";
						?>						
						</p>
					</td>					
				</tr>
				<tr>
					<td>
						Fecha: <?php echo DateTime::createFromFormat('Y-m-d',date("Y-m-d"))->format("Y-m-d h:i:s"); ?>
					</td>
				</tr>
			</tbody>
			<tfoot>								
				<th>
					<!--
					<img style="margin:auto" src="https://fv9-1.failiem.lv/thumb.php?i=sdwdvt64u&n=LOGO+PNG%403x.png" />
					-->
				</th>
			</tfoot>
		</table>
	</body>
</html>

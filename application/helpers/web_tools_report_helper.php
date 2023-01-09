<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

function helper_echoStyleReport(){
	
		echo 
		'
		<style>
			table, td, tr, th {
				border-collapse: collapse;
			}
			
			.border {
				border-color:black;
				border:solid 1px black;						
			}
		</style>
		';
}

function helper_echoFirma($firma,$column,$width){
	
		echo 
		'
		<table style="width:'.$width.'">
			<thead>
				<tr>
					<th colspan="'.$column.'" >'.date("Y-m-d H:i:s").' '.$firma.' posMe</th>
				</tr>
				<!--
				<tr>
					<th colspan="'.$column.'" style="text-align:right" >
						<img width="120" height="110" 						
								style="
									width: 120px;
									height: 110px;
									float:right;
								"
								
								src="'.site_url().'/img/logos/logo-micro-finanza.jpg" 
						/>
					</th>
				</tr>
				-->
			</thead>
		</table>
	
		';
}


function helper_header($titulo,$company,$countColumn,$titulo2,$titulo3,$titulo4,$width){
	
		echo 
		'
		<table style="
			width:'.$width.';border-spacing: 10px;			
		">
			<thead>
				<tr>
					<th colspan="3" rowspan="5" style="text-align:left;width:130px">
						<img width="120" height="110" 						
							style="
								width: 120px;
								height: 110px;
							"
							
							src="'.site_url().'/img/logos/logo-micro-finanza.jpg" 
						/>
					</th>
					<th colspan="'.($countColumn-3).'" style="
						text-align:right;background-color:#00628e;color:white;
						width:80%
					">'.$titulo.'</th>
				</tr>
				<tr>
					<th colspan="'.($countColumn-3).'" style="
						text-align:right;background-color:#00628e;color:white;
					">'.strtoupper($company).'</th>
				</tr>
				<tr>';
				
					if ($titulo2 == "")
						echo '<th colspan="'.($countColumn-3).'" style="text-align:left">&nbsp;</th>';
					else 
						echo '<th colspan="'.($countColumn-3).'" style="text-align:right;background-color:#00628e;color:white;">'.$titulo2.'</th>';
					
					echo '
				</tr>
				<tr>';
				
					if ($titulo3 == "")
						echo '<th colspan="'.($countColumn-3).'" style="text-align:left">&nbsp;</th>';
					else 
						echo '<th colspan="'.($countColumn-3).'" style="text-align:right;background-color:#00628e;color:white;">'.$titulo3.'</th>';
					
					
					echo '
				</tr>
				<tr>';
				
					if ($titulo4 == "")
						echo '<th colspan="'.($countColumn-3).'" style="text-align:left">&nbsp;</th>';
					else 
						echo '<th colspan="'.($countColumn-3).'" style="text-align:right;background-color:#00628e;color:white;">'.$titulo4.'</th>';
					
					
					echo '
				</tr>
				<tr>
					<th colspan="'.($countColumn).'" style="text-align:left">
						&nbsp;
					</th>
				</tr>
			</thead>
		</table>
		';
}

function helper_createTableReport($objDetail,$configColumn,$widht){
	$widthTemporal = 0;
	$table =  
	'<table style="
			width:'.$widht.';order-spacing: 10px;
		" >
			<thead>
				<tr style="background-color:#00628e;color:white;">';
					
					foreach($configColumn as $key => $value ){
						$widthTemporal = $widthTemporal + str_replace("px","",$value['Width']);
						
						$table = $table.'<th nowrap style="text-align:left;width:'.$value['Width'].'" colspan="'.$value['Colspan'].'" class="border"  >'.$value['Titulo'].'</th>';
					}
					
		$table =  $table.'
				</tr>
				<!--
				width:'.$widthTemporal.'
				-->
			</thead>				
			<tbody>
			';
			
		$autoIncrement = 0;
		if($objDetail)
		foreach($objDetail as $i){
			$autoIncrement++;
			$table = $table. "<tr>";
			
			foreach($configColumn as $key => $value ){
				
				$table = $table. "<td nowrap style='text-align:".$value["Alineacion"]."' colspan='".$value['Colspan']."'  class='border'>";
					$valueField 			= ($i[$value["FiledSouce"] ] );					
					$tipoData				= array_key_exists("Formato",$value) ? $value["Formato"] : "" ;
					$sumaryzar				= array_key_exists("Total",$value) ? $value["Total"] : False ; 
					$prefix					= array_key_exists("FiledSoucePrefix",$value) ? $value["FiledSoucePrefix"] : "" ;
					$autoIncrement			= array_key_exists("AutoIncrement",$value) ? $value["AutoIncrement"] : False ;
					
					$IsUrl					= array_key_exists("IsUrl",$value) ? $value["IsUrl"] : False ;					
					$Url					= array_key_exists("Url",$value) ? $value["Url"] : "" ;	
					$FiledSouceUrl			= array_key_exists("FiledSouceUrl",$value) ? $value["FiledSouceUrl"] : "" ;	
					
					
					$valueFieldPrefixValue 	= "";
					$valueFieldUrlValue 	= "";
					
					if($prefix != "")
					$valueFieldPrefixValue 	= ($i[$value["FiledSoucePrefix"] ]);
				
					if($FiledSouceUrl != "")
					$valueFieldUrlValue 	= ($i[$value["FiledSouceUrl"] ]);
					
					
					//Formato al valor
					if($tipoData == "Number"){
						$valueField = number_format($valueField,2,'.',',');						
					}
					else if($tipoData == "Date"){
						$valueField = (date_format(date_create($valueField),"Y-m-d"));
						$valueField = str_replace("-0001-11-30","0001-11-30",$valueField);
					}
					
					//Sumaryzar datos
					if($sumaryzar){
						$configColumn[$key]["TotalValor"] = $value["TotalValor"] + $valueField;
					}
					
					//Prefix					
					if($prefix != ""){						
						$valueField 			= $valueFieldPrefixValue." ".$valueField;
					}
					
					if($autoIncrement){
						$valueField = $autoIncrement;
					}
					
					if($IsUrl){
						$valueField = "<a href='".$Url.$valueFieldUrlValue."' >".$valueField."</a>";
					}
					
					
					
					$table		= $table. $valueField;
				$table = $table. "</td>";								
				
				
			}
			$table = $table. "</tr>";			
		}
				
		$table = $table.'
			</tbody>
			<footer>
				<tr>';
				
					foreach($configColumn as $key => $value ){
						$table = $table.'<th nowrap style="text-align:'.$value['Alineacion'].'" colspan="'.$value['Colspan'].'" class="border"  >';
						
							$filedValue = $value['TituloFoot'];
							$sumaryzar	= $value["Total"] ;
							$totalValor	= $value["TotalValor"] ;
							$prefix		= $value["FiledSoucePrefix"] ;
							
							if($filedValue == ""){
								$filedValue = "	&nbsp;";
							}
							
							if($sumaryzar){
								$filedValue = number_format($totalValor,2,'.',',');
							}
							
							if($prefix != ""){
								$valueFieldPrefixValue 	= ( $i[ $value["FiledSoucePrefix"] ] );
								$filedValue = $valueFieldPrefixValue." ".$filedValue;
							}
							
							$table = $table.$filedValue;
						$table = $table.'</th>';
					}
					
		$table = $table.'</tr>
			</footer>
		</table>
		';
		
	echo $table;
}

function helper_createTableReportVertical($objDetail,$configColumn,$maxColmun,$widht){
	
	
	
	
	$table =  
	'<table style="
			width:'.$widht.';order-spacing: 10px;
		" >
			<tbody>
			';
			
		
	
			
			foreach($configColumn as $key => $value ){
				
				$valueField 			= ( $objDetail[ $value["FiledSouce"] ] );					
				$tipoData				= $value["Formato"] ;
				$sumaryzar				= $value["Total"] ;
				$titulo					= $value["Titulo"] ;				
				$prefix					= $value["FiledSoucePrefix"] ;
				$width					= $value["Width"] ;
				
				
				
				//Formato al valor
				if($tipoData == "Number"){
					$valueField = number_format($valueField,2,'.',',');						
				}
				else if($tipoData == "Date"){
					$valueField = (date_format(date_create($valueField),"Y-m-d"));
					$valueField = str_replace("-0001-11-30","0001-11-30",$valueField);
				}
				
				//Sumaryzar datos
				if($sumaryzar){
					$configColumn[$key]["TotalValor"] = $value["TotalValor"] + $valueField;
				}
				
				//Prefix					
				if($prefix != ""){
					$valueFieldPrefixValue 	= ( $i[ $value["FiledSoucePrefix"] ] );
					$valueField 			= $valueFieldPrefixValue." ".$valueField;
				}
				
				
				
				
				$table = $table. "<tr>";
				
					$table = $table. "<td nowrap style='text-align:".";width:180px;background-color:#00628e;color:white;' colspan='2'   >";
						$table		= $table.$titulo;
					$table = $table. "</td>";
					
					
				
					if($tipoData == "Number"){
						$table = $table. "<td nowrap style='text-align:".$value["Alineacion"].";width:180px' >";
							$table		= $table. $valueField;
						$table = $table. "</td>";
						
						$table = $table. "<td nowrap style='text-align:".$value["Alineacion"].";width:100%' colspan='".($value['Colspan']-2)."'  >";
							$table		= $table."	&nbsp;";
						$table = $table. "</td>";
						
					}
					else{
						$table = $table. "<td nowrap style='text-align:".$value["Alineacion"].";width:100%' colspan='".($value['Colspan']-1)."'  >";
							$table		= $table. $valueField;
						$table = $table. "</td>";
					}
					
					
				$table = $table. "</tr>";	
				
			}
			
				
		$table = $table.'
			</tbody>
		</table>
		';
		
	echo $table;
}


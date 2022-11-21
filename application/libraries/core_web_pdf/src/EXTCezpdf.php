<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
//Cargar Libreria
include_once 'Cezpdf.php';
	
class EXTCezpdf extends Cezpdf {
	
	function EXTCezpdf($paper='a4',$orientation='portrait', $type = 'none', $options = array()){
		parent::Cezpdf($paper,$orientation, $type, $options);		
		//En server WIN
		if(strpos(PHP_OS, 'WIN') !== false){
				$this->tempPath = PATH_FILE_OF_XAMPP_TMP;
		}
	}
	function EXTGetWidth(){
			return $this->ez['pageWidth']  - $this->ez['leftMargin'] - $this->ez['rightMargin'];			
	}
	function EXTCreateHeader($companyName,$componentID,$logoName,$sessionData){
	
		if(!$sessionData)
		throw new Exception("NO VARIABLE DE SESSION ACTIVA");		
		$companyID = $sessionData["user"]->companyID;
		$this->addJpegFromFile(
			PATH_FILE_OF_APP_ROOT.'/img/logos/'.$logoName,
			LEFT_MARGIN * 30,
			$this->ez['pageHeight'] - LOGO_SIZE_HEIGTH - (TOP_MARGIN * 25),
			LOGO_SIZE_WIDTH, /*ancho*/
			LOGO_SIZE_HEIGTH /*alto*/
		);
		$this->ezText("<b>".strtoupper($companyName)."</b>",FONT_SIZE_TITLE,array('justification'=>'center'));
		
	}
	function EXTCreateHeaderPrinterTicketAndTermica80cm($companyName,$componentID,$logoName,$sessionData){
	
		if(!$sessionData)
		throw new Exception("NO VARIABLE DE SESSION ACTIVA");		
		$companyID = $sessionData["user"]->companyID;
		$this->addJpegFromFile(
			PATH_FILE_OF_APP_ROOT.'/img/logos/'.$logoName,
			LEFT_MARGIN * 90,
			$this->ez['pageHeight'] - LOGO_SIZE_HEIGTH - (TOP_MARGIN * 1),
			LOGO_SIZE_WIDTH, /*ancho*/
			LOGO_SIZE_HEIGTH /*alto*/
		);
		$this->ezText("<b>".strtoupper($companyName)."</b>\n\n\n",FONT_SIZE_TITLE,array('justification'=>'center'));
		
	}	
	function EXTCreateHeaderPrinterTicketAndTermica80cmCuadrado($companyName,$componentID,$logoName,$sessionData){
	
		if(!$sessionData)
		throw new Exception("NO VARIABLE DE SESSION ACTIVA");		
		$companyID = $sessionData["user"]->companyID;
		$this->addJpegFromFile(
			PATH_FILE_OF_APP_ROOT.'/img/logos/'.$logoName,
			65,
			$this->ez['pageHeight'] - 90 - 0,
			90, /*ancho*/
			90 /*alto*/
		);
		$this->ezText("<b>".strtoupper($companyName)."</b>\n\n\n",FONT_SIZE_TITLE,array('justification'=>'center'));
		
	}	
	function EXTCreateFooter(){
		for($i = 1; $i <= $this->ezPageCount; $i++ ){
			$rightMargin	= $this->ez['rightMargin'];
			$leftMargin 	= $this->ez['leftMargin'];
			$bottomMargin 	= $this->ez['bottomMargin'];
			$topMargin 		= $this->ez['topMargin'];
			$pageWidth 		= $this->ez['pageWidth'];				
			$pageHeight 	= $this->ez['pageHeight'];
			$fontSize		= FONT_SIZE;
			$deplazamiento	= $bottomMargin / 2;
			$this->ezStartPageNumbers($pageWidth - $leftMargin ,$bottomMargin - $deplazamiento ,$fontSize,'left',strtolower(APP_NAME).' -- pagina {PAGENUM} de {TOTALPAGENUM}');				
		}
	}
}
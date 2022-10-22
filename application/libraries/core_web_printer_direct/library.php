<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
//Cargar Libreria

include_once 'src/Mike42/Escpos/CapabilityProfiles/DefaultCapabilityProfile.php';
include_once 'src/Mike42/Escpos/CapabilityProfiles/EposTepCapabilityProfile.php';
include_once 'src/Mike42/Escpos/CapabilityProfiles/P822DCapabilityProfile.php';
include_once 'src/Mike42/Escpos/CapabilityProfiles/SimpleCapabilityProfile.php';
include_once 'src/Mike42/Escpos/CapabilityProfiles/StarCapabilityProfile.php';


include_once 'src/Mike42/Escpos/PrintBuffers/PrintBuffer.php';
include_once 'src/Mike42/Escpos/PrintBuffers/EscposPrintBuffer.php';
include_once 'src/Mike42/Escpos/PrintBuffers/ImagePrintBuffer.php';


include_once 'src/Mike42/Escpos/CapabilityProfile.php';
include_once 'src/Mike42/Escpos/CodePage.php';
include_once 'src/Mike42/Escpos/EscposImage.php';
include_once 'src/Mike42/Escpos/GdEscposImage.php';
include_once 'src/Mike42/Escpos/ImagickEscposImage.php';
include_once 'src/Mike42/Escpos/NativeEscposImage.php';
include_once 'src/Mike42/Escpos/Printer.php';

include_once 'src/Mike42/Escpos/Devices/AuresCustomerDisplay.php';

include_once 'src/Mike42/Escpos/PrintConnectors/PrintConnector.php';
include_once 'src/Mike42/Escpos/PrintConnectors/ApiPrintConnector.php';
include_once 'src/Mike42/Escpos/PrintConnectors/CupsPrintConnector.php';
include_once 'src/Mike42/Escpos/PrintConnectors/DummyPrintConnector.php';
include_once 'src/Mike42/Escpos/PrintConnectors/FilePrintConnector.php';
include_once 'src/Mike42/Escpos/PrintConnectors/NetworkPrintConnector.php';
include_once 'src/Mike42/Escpos/PrintConnectors/UriPrintConnector.php';
include_once 'src/Mike42/Escpos/PrintConnectors/WindowsPrintConnector.php';



use Mike42\Escpos\EscposImage;
use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;
use Mike42\Escpos\Printer;



class Library {
    var $nombre_impresora;
    var $connectorWindowPrinter;
    var $printer;

    function Library(){	
        $this->nombre_impresora           = "POS-80-Series USB";
        $this->connectorWindowPrinter     = new WindowsPrintConnector($this->nombre_impresora);
        $this->printer                    = new Printer($this->connectorWindowPrinter);
        
	}

    function executePrinter(){    
		
		$this->printer->setJustification(Printer::JUSTIFY_CENTER);

		//$logo = EscposImage::load("logo.jpg", false);
		//$this->printer->bitImage($logo);

		/*
		Imprimimos un mensaje. Podemos usar
		el salto de línea o llamar muchas
		veces a $printer->text()
		*/
		$this->printer->setTextSize(2, 2);
		$this->printer->text("Ticket con PHP");

		$this->printer->setTextSize(2, 1);
		$this->printer->feed();
		$this->printer->text("Hola mundo\n\nParzibyte.me\n\nNo olvides suscribirte");
		/*
		Hacemos que el papel salga. Es como
		dejar muchos saltos de línea sin escribir nada
		*/
		$this->printer->feed(15);

		/*
		Cortamos el papel. Si nuestra impresora
		no tiene soporte para ello, no generará
		ningún error
		*/
		$this->printer->cut();

		/*
		Por medio de la impresora mandamos un pulso.
		Esto es útil cuando la tenemos conectada
		por ejemplo a un cajón
		*/
		$this->printer->pulse();

		/*
		Para imprimir realmente, tenemos que "cerrar"
		la conexión con la impresora. Recuerda incluir esto al final de todos los archivos
		*/
		$this->printer->close();
    }



}
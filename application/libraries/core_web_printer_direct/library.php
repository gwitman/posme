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

    function configurationPrinter($printerName){	
		
        $this->nombre_impresora           = $printerName;
        $this->connectorWindowPrinter     = new WindowsPrintConnector($this->nombre_impresora);
        $this->printer                    = new Printer($this->connectorWindowPrinter);
        
	}

	function addSpaces($string = '', $valid_string_length = 0) {
		if (strlen($string) < $valid_string_length) {
			$spaces = $valid_string_length - strlen($string);
			for ($index1 = 1; $index1 <= $spaces; $index1++) {
				$string = $string . ' ';
			}
		}
	
		return $string;
	}

    function executePrinter($dataSetValores){    
		echo print_r($dataSetValores,true);
		$this->printer->setJustification(Printer::JUSTIFY_CENTER);
		$pathImg = PATH_FILE_OF_APP_ROOT.'/img/logos/invoice-direct-'.$dataSetValores["objParameterLogo"]->value;

		$logo = EscposImage::load($pathImg, false);
		$this->printer->bitImage($logo,1);

		//en formato de $this->printer->setTextSize(1, 1);
		//cada linea tiene 48 caracteres

		//en formato de $this->printer->setTextSize(2, 1);
		//cada linea tiene 24 caracteres

		/*
		Imprimimos un mensaje. Podemos usar
		el salto de línea o llamar muchas
		veces a $printer->text()
		*/

		$this->printer->feed();
		$this->printer->setTextSize(2, 2);
		$this->printer->text($dataSetValores["objCompany"]->name);
		$this->printer->text("\n");
		$this->printer->setTextSize(2, 1);		
		$this->printer->text("\nRUC:".$dataSetValores["Identifier"]->value);
		$this->printer->setTextSize(2, 1);
		$this->printer->text("\n#".$dataSetValores["objTransactionMaster"]->transactionNumber);
		$this->printer->setTextSize(1, 1);
		$this->printer->text("\n");

		$this->printer->text("\nFecha: ".$dataSetValores["objTransactionMaster"]->transactionOn);
		$this->printer->setTextSize(1, 1);
		$this->printer->text("\nEstado: ".$dataSetValores["objStage"][0]->display);
		$this->printer->setTextSize(1, 1);
		$this->printer->text("\nUsuario: ".$dataSetValores["objUser"]->nickname);
		$this->printer->setTextSize(1, 1);
		$this->printer->text("\nTipo: ".$dataSetValores["objTipo"]->name);
		$this->printer->setTextSize(1, 1);
		$this->printer->text("\nCliente: ".$dataSetValores["cedulaCliente"]);
		$this->printer->setTextSize(1, 1);
		$this->printer->text("\nNombre: ".$dataSetValores["nombreCliente"]);
		$this->printer->text("\n");
		$this->printer->text("\n");
		//Detalle
		$data1		= array();			
		$subtotal 	= 0;
		$iva 		= 0;
		$total 		= 0;
		$cambio		= 0;
		
		//pone en negrita
		//$this->printer->setEmphasis(true);		
		//$this->printer->text($this->addSpaces('Cantidad', 20) . $this->addSpaces('Precio', 20) . $this->addSpaces('Total', 8) . "\n");
		$this->printer->setPrintLeftMargin(0);
		$this->printer->setJustification(Printer::JUSTIFY_LEFT);
		if($dataSetValores["objTransactionMasterDetail"]){
			foreach($dataSetValores["objTransactionMasterDetail"] as $row)
			{	


				$this->printer->text($this->addSpaces(substr($row->itemNumber,4,7), 10) . $this->addSpaces(strtolower(substr($row->itemName,0,15)), 20));
				$this->printer->text("\n");
				$this->printer->text(
						$this->addSpaces(number_format(round($row->quantity,2),2,'.',','), 10) . 
						$this->addSpaces($dataSetValores["prefixCurrency"].number_format(round($row->unitaryPrice,2),2,'.',','), 25) . 
						$this->addSpaces($dataSetValores["prefixCurrency"].number_format(round($row->amount,2),2,'.',','), 0));

				$iva		= $iva + ($row->tax1 * $row->quantity);
				$total		= $total + $row->amount;
				$subtotal	= $total - $iva;

			}
		}
		$this->printer->text("\n");

		$this->printer->setJustification(Printer::JUSTIFY_CENTER);
		$iva 		= number_format(round($iva,2),2,'.',',');
		$total 		= number_format(round($total,2),2,'.',',');
		$subtotal 	= number_format(round($subtotal,2),2,'.',',');
		$cambio		= ($dataSetValores["objTransactionMasterInfo"]->receiptAmount - $dataSetValores["objTransactionMaster"]->amount);
		$cambio 	= number_format(round($cambio,2),2,'.',',');
		$this->printer->setTextSize(1, 1);
		$this->printer->text("\nTotal: ".$dataSetValores["prefixCurrency"].$total) ;
		$this->printer->setTextSize(1, 1);
		$this->printer->text("\nCambio: ".$dataSetValores["prefixCurrency"].$cambio);
		$this->printer->text("\n");

		$this->printer->setTextSize(1, 1);
		$this->printer->text("\nGracias por su compra.");
		$this->printer->setTextSize(1, 1);
		$this->printer->text("\nContamos con servicio a domicilio.");
		$this->printer->setTextSize(1, 1);
		$this->printer->text("\n****************************.");
		$this->printer->setTextSize(1, 1);
		$this->printer->text("\nTelefono de tienda: ".$dataSetValores["objParameterPhoneProperty"]->value);
		$this->printer->setTextSize(1, 1);
		$this->printer->text("\n".$dataSetValores["objCompany"]->address);
		$this->printer->setTextSize(1, 1);
		$this->printer->text("\nSistema:+(505) 8712-5827");

		$this->printer->setTextSize(2, 1);
		$this->printer->feed(10);
		//$this->printer->text("Hola mundo\n\nParzibyte.me\n\nNo olvides suscribirte");
		/*
		Hacemos que el papel salga. Es como
		dejar muchos saltos de línea sin escribir nada
		*/
		//$this->printer->feed(15);

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
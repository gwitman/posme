<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
* CSVReader Class
*
* $Id: csvreader.php 147 2007-07-09 23:12:45Z Pierre-Jean $
*
* @example		
*/
class csvreader {
    
    var $fields;        		/** columns names retrieved after parsing */
    var $separator 		= ',';    	/** separator used to explode each line */
	var $separatorLine 	= '\n';    	/** separator used to explode each line */
    
  
	function write_file($p_Filepath,$data){
		
		if($data){
			//Obtener Titulo
			$row1 = $data[0];
			$row1_=[];
			foreach ($row1 as $key => $val) {
				array_push($row1_, $key);
			};
			array_push($row1_,"CANTIDAD");
			
			//Crear Archivo
			$fp = fopen($p_Filepath, 'w');
			
			//Escribir el Titulo
			fputcsv($fp,$row1_);	
			
			//Escribir la Informacion
			foreach ($data as $row) {
				//Obtener Fila
				$row1	= $row;
				$row1_	= [];
				foreach ($row1 as $key => $val) {
					array_push($row1_, $val);
				};
				
				//Escribir Fila
				array_push($row1_,0);
				fputcsv($fp,$row1_);
			}
			
			//Cerrar archivo
			fclose($fp);
		}
	}
    /**
     * Parse a file containing CSV formatted data.
     *
     * @access    public
     * @param     string
     * @return    array
     */
    function parse_file($p_Filepath) {       
		$content 		= FALSE;
		//Abrir el Archivo
		if (($gestor = fopen($p_Filepath, "r")) !== FALSE) {
		
			//Recorrer las lineas del Archivo
			while (($datos = fgetcsv($gestor, 1000,$this->separator)) !== FALSE) {
			
				if( !is_array($content) ) { 
                    $this->fields 	= ($datos);
                    $content 		= array();
                } else {
                    $item = array();
                    foreach( $this->fields as $id => $field ) {
                        if( isset($datos[$id]) ) {
                            $item[$field] = $datos[$id];
                        }
                    }
                    $content[] = $item;
                }
			}
		}
		fclose($gestor);
		return $content;
    }
}
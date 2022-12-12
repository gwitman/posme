<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Biblia_Model extends CI_Model  {
   function __construct(){
      parent::__construct();
   } 
 
   
   function get_rowByDay($companyID,$dia){    
		$this->db->select("i.versiculoID,i.orden,i.dia,i.capitulo,i.libro,i.versiculo");
		$this->db->from("tb_biblia i");		
		$this->db->where("dia >= ",($dia - 3));
		$this->db->where("dia <= ",($dia -1));
		
		//Ejecutar Consulta
		$recordSet = $this->db->get();
		
		//Obtener errores
		if($this->db->_error_message())  
		return null; 
		
		if($recordSet->num_rows() == 0)
		return null;
		
		//Resultado
		return $recordSet->result();
   }
  
}
?>
<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Customer_Consultas_Sin_Riesgo_Model extends CI_Model  {
   function __construct(){   
      parent::__construct(); 
   }
   function update($requestID,$data){
		$this->db->where('requestID', $requestID);	
		$this->db->update('tb_customer_consultas_sin_riesgo', $data);
		return $this->db->affected_rows(); 
   }
   function updateByCedula($companyID,$cedula,$data){ 
        $this->db->where('companyID', $companyID);	
        $this->db->where('id', $cedula);	
        $this->db->update('tb_customer_consultas_sin_riesgo', $data);
        return $this->db->affected_rows(); 
   }
   
   function insert($data){
		$result			 		= $this->db->insert('tb_customer_consultas_sin_riesgo', $data);
		$autoIncrement			= $this->db->insert_id(); 		
		return $result ? $autoIncrement : $result; 
   }   
   //Buscar Por Id
   function get_rowByPK($requestID){
		
        $this->db->select("requestID, companyID, name, id, `file`, userID, createdOn, createdBy, createdIn, createdAt, modifiedOn");
        $this->db->from("tb_customer_consultas_sin_riesgo i");		
        $this->db->where("i.requestID",$requestID);
        
        //Ejecutar Consulta
        $recordSet = $this->db->get();
        
        //Obtener errores
        if($this->db->_error_message())  
        return null; 		
        
        if($recordSet->num_rows() == 0)
        return null;
        
        //Resultado
        return  $recordSet->row();
    }
   //Buscar en la lista el Cliente. 
   function get_rowByCedulaLast($companyID,$cedula){		
        $this->db->select("requestID, companyID, name, id, `file`, userID, createdOn, createdBy, createdIn, createdAt, modifiedOn");
        $this->db->from("tb_customer_consultas_sin_riesgo i");		
        $this->db->where("i.id",$cedula);
        $this->db->where("i.companyID",$companyID);
        $this->db->order_by("createdOn", "desc");
        $this->db->limit(1);
        
        //Ejecutar Consulta
        $recordSet = $this->db->get();
        
        //Obtener errores
        if($this->db->_error_message())  
        return null; 		
        
        if($recordSet->num_rows() == 0)
        return null;
        
        //Resultado
        return  $recordSet->row();
    }
    function get_rowValidOld($requestID,$old){		
        $this->db->select("requestID, companyID, name, id, `file`, userID, createdOn, createdBy, createdIn, createdAt, modifiedOn");
        $this->db->from("tb_customer_consultas_sin_riesgo i");		
        $this->db->where("i.requestID",$requestID);        

        //Si el parametro old es mayor que 0 , filtrar por antiguedad
        if($old > 0)
        $this->db->where("DATEDIFF(NOW(), i.createdOn) > ".$old." "); 
        
        //Ejecutar Consulta
        $recordSet = $this->db->get();
        
        //Obtener errores
        if($this->db->_error_message())  
        return null; 		
        
        if($recordSet->num_rows() == 0)
        return null;
        
        //print_r($this->db->last_query());  
        //Resultado
        return  $recordSet->row();
    }
    
    //Buscar en la lista el Cliente. 
   function get_rowByCedula_FileName($companyID,$cedula){		
        $this->db->distinct('i.`file`');
        $this->db->select("`file`");
        $this->db->from("tb_customer_consultas_sin_riesgo i");		
        $this->db->where("i.id",$cedula);
        $this->db->where("i.companyID",$companyID);
        $this->db->order_by("i.`file`", "desc");
        //Ejecutar Consulta
        $recordSet = $this->db->get();
        
        //Obtener errores
        if($this->db->_error_message())  
        return null; 		
        
        if($recordSet->num_rows() == 0)
        return null;
        
        //Resultado
        return  $recordSet->result();
    }
	
   //Obtener Data
   function get_rowByCompany($companyID){		
		$this->db->select("`i`.`TIPO_DE_ENTIDAD`,`i`.`NUMERO_CORRELATIVO`,`i`.`FECHA_DE_REPORTE`,`i`.`DEPARTAMENTO`,`i`.`NUMERO_DE_CEDULA_O_RUC`,`i`.`NOMBRE_DE_PERSONA`,`i`.`TIPO_DE_CREDITO`,`i`.`FECHA_DE_DESEMBOLSO`,`i`.`TIPO_DE_OBLIGACION`,`i`.`MONTO_AUTORIZADO`,`i`.`PLAZO`,`i`.`FRECUENCIA_DE_PAGO`,`i`.`SALDO_DEUDA`,`i`.`ESTADO`,`i`.`MONTO_VENCIDO`,`i`.`ANTIGUEDAD_DE_MORA`,`i`.`TIPO_DE_GARANTIA`,`i`.`FORMA_DE_RECUPERACION`,`i`.`NUMERO_DE_CREDITO`,`i`.`VALOR_DE_LA_CUOTA`");		
        $this->db->from("vw_sin_riesgo_reporte_creditos_to_systema i");		
        $this->db->where("i.companyID",$companyID);             
        //Ejecutar Consulta
        $recordSet = $this->db->get();
        //Obtener errores
        if($this->db->_error_message())  
        return null; 		
        if($recordSet->num_rows() == 0)
        return null;
        
        //Resultado
        return  $recordSet->result_array();
   }
   
}
?>
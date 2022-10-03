<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Bd_Model extends CI_Model  {
   function __construct(){
      parent::__construct();
   }  
   function executeRender($query){ 
		$queryResult 		= $this->db->query($query);		
		$result 			= $queryResult->result(); 		
		return $result;
   }
   function executeProcedureMultiQuery($query){
		$db_mysqli 			= $this->load->database('default_mysqli',TRUE);				
		$k 					= 0;
		$array_result_sets 	= array();		
		if(mysqli_multi_query($db_mysqli->conn_id,$query)){
			do{
				$result = mysqli_store_result($db_mysqli->conn_id);
				if($result){
					$i = 0;
					while($row = $result->fetch_assoc()){
						$array_result_sets[$k][$i] = $row;
						$i++; 
					}
				}
				$k++;
			}while(mysqli_next_result($db_mysqli->conn_id));
			
			return $array_result_sets;
		}
		$db_mysqli->close();
   }
}
?>
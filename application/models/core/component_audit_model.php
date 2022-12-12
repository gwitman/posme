<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Component_Audit_Model extends CI_Model  {
    function __construct(){
      parent::__construct();
    }
    function insert($data){   
		$result			 				= $this->db->insert('tb_component_audit', $data);
		$componentauditid 				= $this->db->insert_id(); 		
		return $result ? $componentauditid : $result; 	
   }
}
?>
<?php

class M_enc_dok extends CI_Model
{

   
    public function get_data_dokumen($id = null)
    {
        if ($id == null) {
    		return $this->db->get('TB_TRANSACTION_DOK')->result_array();
        	
        }

        else {
        	return $this->db->get_where('TB_TRANSACTION_DOK',array('ID_TRAN' => $id))->result_array();
        }

        
    }


    public function kirim_dokumen($data){

    	$this->db->insert('TB_TRANSACTION_DOK',$data);
    	
    	return $this->db->affected_rows();

    }




}
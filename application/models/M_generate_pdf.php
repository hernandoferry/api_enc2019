<?php

class M_generate_pdf extends CI_Model
{

   
    public function get_data_dokumen($id = null)
    {

        if ($id == null) {
            return $this->db->get('SECR_T_TRANSACTION')->result_array();
            
        }

        else {
            return $this->db->get_where('SECR_T_TRANSACTION',array('TRANS_ID' => $id))->result_array();
        }


        
       // $sql="SELECT DOC_NBR,DOC_DESC FROM SECR_T_TRANSACTION WHERE TRANS_ID=$id";
      //  $query = $this->db->query($sql);
      //  return json_encode($query);
        

      // return $this->db->get_where('SECR_T_TRANSACTION',array('TRANS_ID' => $id))->result_array();
        
    }

}
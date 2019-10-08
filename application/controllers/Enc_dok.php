<?php

use Restserver\Libraries\REST_Controller;
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . 'libraries/REST_Controller.php';
require APPPATH . 'libraries/Format.php';



class Enc_dok extends REST_Controller {

	public function __construct(){

		parent::__construct();

		$this->load->model('M_enc_dok');

	}

//public $key = 'QXBpX2Vkb2t1bWVuUHRKaWN0MjAxOV9CUkFWTw=='; //Api_edokumenPtJict2019_BRAVO

private function _enkerip_data($data, $key) {
    $encryption_key = base64_decode($key);// Remove the base64 encoding from our key
    $iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length('aes-256-cbc')); // Generate an initialization vector
    // Encrypt the data using AES 256 encryption in CBC mode using our encryption key and initialization vector.
    $encrypted = openssl_encrypt($data, 'aes-256-cbc', $encryption_key, 0, $iv);
    return base64_encode($encrypted . '::' . $iv);

}


private function _dekrip_data($data, $key) {

    $encryption_key = base64_decode($key);// Remove the base64 encoding from our key
    list($encrypted_data, $iv) = explode('::', base64_decode($data), 2); 
    return openssl_decrypt($encrypted_data, 'aes-256-cbc', $encryption_key, 0, $iv);
}	





public function index_get()
{

	$id = $this->get('id');

	if ($id === null) {

		$data_dokumen = $this->M_enc_dok->get_data_dokumen();

        if ($data_dokumen) {

        $this->response([
            'status'    => true,
            'message'   => 'data ditemukan',
            'data'      => $data_dokumen


        ], REST_Controller::HTTP_OK);
    }

    else {
        $this->response([
            'status' => false,
            'message' => 'data tidak ditemukan'
        ], REST_Controller::HTTP_NOT_FOUND);
    }

	} 

	else {

		$data_dokumen = $this->M_enc_dok->get_data_dokumen($id);

         foreach ($data_dokumen as $value) {
            $enc_id=$value['ID_TRAN'];
            $enc_contn=$value['CONTENT_DOK'];
            $enc_date=$value['CREATED_AT'];
            $Enc_key=$value['PKEY'];
        }

        if ($data_dokumen) {

        $this->response([
            'status'    => true,
            'message'   => 'data ditemukan',
            'id'        => $enc_id, 
            'content'   =>$this->_dekrip_data($enc_contn,$Enc_key),
            'tgl'       => $enc_date


        ], REST_Controller::HTTP_OK);
    }

    else {
            $this->response([
                'status' => false,
                'message' => 'data tidak ditemukan'
            ], REST_Controller::HTTP_NOT_FOUND);
        }

	}





    }//end index_get

    public function index_post(){

        $key = base64_encode(openssl_random_pseudo_bytes(32));
    	$id= $this->input->post('id_tran');
    	$OrderLines=$this->input->post('content_dok');
    	$CustomerName= date("Y/m/d H:i:s");


    	//mulai enkripsi
        $enc_data=$this->_enkerip_data($OrderLines,$key);

    	$data_dok = [
    		'ID_TRAN' => $id,
    		'CONTENT_DOK' => $enc_data,
    		'CREATED_AT' => $CustomerName,
            'PKEY' => $key

    	];


       //return json_encode($data_dok);

    	$kirim = $this->M_enc_dok->kirim_dokumen($data_dok);

    	if ($kirim > 0 ) {

    		$this->response([
    			'status' 	=> true,
    			'message'	=> 'Document was encripted and has been send'
				//'data'		=> $data_dokumen 

    		], REST_Controller::HTTP_CREATED);
    		
    	}

    	else {
    		$this->response([
    			'status' 	=> false,
    			'message'	=> 'Failed to encript Document'
				//'data'		=> $data_dokumen 

    		], REST_Controller::HTTP_BAD_REQUEST);
    	}
    }
}


?>
<?php

/*
  Tanggal : 24 juli 2019 
  Programmer : Ferry Hernando
  for PT Jakarta International Container Terminal

*/


use Restserver\Libraries\REST_Controller;
defined('BASEPATH') OR exit('No direct script access allowed');

// This can be removed if you use __autoload() in config.php OR use Modular Extensions
/** @noinspection PhpIncludeInspection */
//To Solve File REST_Controller not found
require APPPATH . 'libraries/REST_Controller.php';
require APPPATH . 'libraries/Format.php';


class Generate_pdf extends REST_Controller {



    public function __construct(){

        // buat handling masalah cors origin
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Methods: POST,OPTIONS");
        header("Access-Control-Allow-Headers: Content-Type, Content-Length, Accept-Encoding");

        $method = $_SERVER['REQUEST_METHOD'];
        if($method == "OPTIONS") {

                die();

            }

        parent::__construct();
             $this->load->model('M_generate_pdf');
    }

    public function generate_get()
    {
        $id = $this->get('id');

        if ($id === null) {

        $data_dokumen = $this->M_generate_pdf->get_data_dokumen();

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

        $data_dokumen = $this->M_generate_pdf->get_data_dokumen($id);

         foreach ($data_dokumen as $value) {
            $no_dokumen=$value['DOC_NBR'];
            $tobe_enkript=$value['DOC_DESC']->load();
            
        }

        if ($data_dokumen) {

            $RubahFormatNoDokumen=str_replace("/", "_",$no_dokumen);

            require_once('./assets/html2pdf/html2pdf.class.php');
            $direktori="./assets/bank_dokumen/";

            if (!is_dir($direktori)) {
                //echo "membuat direktori $direktori <br />\n";
                 mkdir($direktori, 0777, TRUE);

                    
              }
                    $pdf = new HTML2PDF('P','A4','en');
                    $pdf->WriteHTML($tobe_enkript);
                    $gentopdf=$pdf->Output($direktori.$RubahFormatNoDokumen.'.pdf', 'F');
                    ob_start();

            $this->response([
                'status'    => true,
                'message'   => 'data ditemukan',
            ], REST_Controller::HTTP_OK);
    }

    else {
            $this->response([
                'status' => false,
                'message' => 'data tidak ditemukan'
            ], REST_Controller::HTTP_NOT_FOUND);
        }

    }


 }
}
     
      /*  

       

        }*/


    

   



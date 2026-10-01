<?php
    class Concept_2 extends CI_Controller
    {
        // ANTRIAN, REGISTRASI, & PEMBAYARAN TINDAKAN PASIEN UMUM
        
        public function __construct()
        {
            parent:: __construct();
        }

        public function index()
        {
            $data["title"]       = "DIGITAL INFORMASI RSUD PASAR MINGGU";
            $this->template->load("v_template","v_concept_2",$data);
        }
        
    }
?>
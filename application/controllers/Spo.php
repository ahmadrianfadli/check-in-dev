<?php
    class Spo extends CI_Controller{

        public function __construct()
        {
            parent:: __construct();
        }

        public function index()
        {
            $data["title"]       = "DIGITAL INFORMASI RSUD PASAR MINGGU";
            $this->template->load("v_template","v_spo",$data);
        }
        
    }
?>
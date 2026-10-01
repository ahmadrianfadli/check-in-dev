<?php
    class Concept_4 extends CI_Controller
    {
        // ESTIMASI BILLING IGD & RANAP
        
        public function __construct()
        {
            parent:: __construct();
        }

        public function index()
        {
            $data["title"]       = "DIGITAL INFORMASI RSUD PASAR MINGGU";
            $this->template->load("v_template","v_concept_4",$data);
        }
        
    }
?>
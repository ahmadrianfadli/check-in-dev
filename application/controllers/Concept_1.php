<?php
    class Concept_1 extends CI_Controller
    {
        // BPJS
        
        public function __construct()
        {
            parent:: __construct();
            redirect('index.php/concept_5');
        }

        public function index()
        {
            $data["title"]       = "DIGITAL INFORMASI RSUD PASAR MINGGU";
            $this->template->load("v_template","v_concept_1",$data);
        }
        
    }
?>
<?php
    class Daftarharga extends CI_Controller
    {

        public function __construct()
        {
            parent:: __construct();
            $this->load->model("Modeldaftarharga","md");
        }

        public function index()
        {
            $data["title"]       = "DIGITAL INFORMASI RSUD PASAR MINGGU";
            $this->template->load("v_template","v_daftarharga",$data);
        }
        
    }
?>
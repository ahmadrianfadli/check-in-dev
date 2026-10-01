<?php
    class Layananbpjs extends CI_Controller
    {

        public function __construct()
        {
            parent:: __construct();
            $this->load->model("Modelantriankasir","md");
        }

        public function index()
        {
            $data["title"]       = "ANTRIAN KASIR RAWAT JALAN";
            $this->template->load("v_template","v_layananbpjs",$data);
        }
        
    }
?>
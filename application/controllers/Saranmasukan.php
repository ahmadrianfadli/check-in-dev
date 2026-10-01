<?php
    class Saranmasukan extends CI_Controller
    {
        public function index()
        {
            $data["title"]       = "DIGITAL INFORMASI RSUD PASAR MINGGU";
            $this->template->load("v_template","v_saranmasukan",$data);
        }
        
    }
?>
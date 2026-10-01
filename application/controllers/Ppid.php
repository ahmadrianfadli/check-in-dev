<?php
    class Ppid extends CI_Controller
    {
        public function index()
        {
            $data["title"]       = "DIGITAL INFORMASI RSUD PASAR MINGGU";
            $this->template->load("v_template","v_ppid",$data);
        }
        
    }
?>
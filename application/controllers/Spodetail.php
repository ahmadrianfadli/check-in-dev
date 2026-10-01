<?php
    class Spodetail extends CI_Controller
    {
        public function index()
        {
            $spo = $_GET['spo'];

            if($spo === "all"){
                $data["spo"]      = "STANDART PELAYANAN";
                $data["document"] = "<embed src='" . base_url('dist/pdf/standar-pelayanan.pdf#toolbar=0&navpanes=0') . "' width='100%' height='100%' type='application/pdf' id='view'>";
            }

            if($spo === "igd"){
                $data["spo"]      = "STANDART PELAYANAN IGD";
                $data["document"] = "<embed src='" . base_url('dist/pdf/1.pdf#toolbar=0&navpanes=0') . "' width='100%' height='100%' type='application/pdf' id='view'>";
            }

            if($spo === "poli"){
                $data["spo"]      = "STANDART PELAYANAN POLIKLINIK SPESIALIS";
                $data["document"] = "<embed src='" . base_url('dist/pdf/2.pdf#toolbar=0&navpanes=0') . "' width='100%' height='100%' type='application/pdf' id='view'>";
            }

            if($spo === "ri"){
                $data["spo"]      = "STANDART PELAYANAN RAWAT INAP";
                $data["document"] = "<embed src='" . base_url('dist/pdf/3.pdf#toolbar=0&navpanes=0') . "' width='100%' height='100%' type='application/pdf' id='view'>";
            }

            if($spo === "cc"){
                $data["spo"]      = "STANDART PELAYANAN INSTALASI RAWAT KHUSUS";
                $data["document"] = "<embed src='" . base_url('dist/pdf/4.pdf#toolbar=0&navpanes=0') . "' width='100%' height='100%' type='application/pdf' id='view'>";
            }

            if($spo === "ibs"){
                $data["spo"]      = "STANDART PELAYANAN BEDAH SENTRAL";
                $data["document"] = "<embed src='" . base_url('dist/pdf/5.pdf#toolbar=0&navpanes=0') . "' width='100%' height='100%' type='application/pdf' id='view'>";
            }

            if($spo === "frm"){
                $data["spo"]      = "STANDART PELAYANAN FARMASI";
                $data["document"] = "<embed src='" . base_url('dist/pdf/6.pdf#toolbar=0&navpanes=0') . "' width='100%' height='100%' type='application/pdf' id='view'>";
            }

            if($spo === "lab"){
                $data["spo"]      = "STANDART PELAYANAN LABORATORIUM";
                $data["document"] = "<embed src='" . base_url('dist/pdf/7.pdf#toolbar=0&navpanes=0') . "' width='100%' height='100%' type='application/pdf' id='view'>";
            }

            if($spo === "rad"){
                $data["spo"]      = "STANDART PELAYANAN RADIOLOGI";
                $data["document"] = "<embed src='" . base_url('dist/pdf/8.pdf#toolbar=0&navpanes=0') . "' width='100%' height='100%' type='application/pdf' id='view'>";
            }

            if($spo === "pendrj"){
                $data["spo"]      = "STANDART PELAYANAN PENDAFTARAN RAWAT JALAN";
                $data["document"] = "<embed src='" . base_url('dist/pdf/9.pdf#toolbar=0&navpanes=0') . "' width='100%' height='100%' type='application/pdf' id='view'>";
            }

            if($spo === "pendri"){
                $data["spo"]      = "STANDART PELAYANAN PENDAFTARAN RAWAT INAP";
                $data["document"] = "<embed src='" . base_url('dist/pdf/10.pdf#toolbar=0&navpanes=0') . "' width='100%' height='100%' type='application/pdf' id='view'>";
            }

            if($spo === "kores"){
                $data["spo"]      = "STANDART PELAYANAN KORESPONDENSI";
                $data["document"] = "<embed src='" . base_url('dist/pdf/11.pdf#toolbar=0&navpanes=0') . "' width='100%' height='100%' type='application/pdf' id='view'>";
            }

            if($spo === "gizi"){
                $data["spo"]      = "STANDART PELAYANAN GIZI";
                $data["document"] = "<embed src='" . base_url('dist/pdf/12.pdf#toolbar=0&navpanes=0') . "' width='100%' height='100%' type='application/pdf' id='view'>";
            }

            if($spo === "jzh"){
                $data["spo"]      = "STANDART PELAYANAN PEMULASARAN JENAZAH";
                $data["document"] = "<embed src='" . base_url('dist/pdf/13.pdf#toolbar=0&navpanes=0') . "' width='100%' height='100%' type='application/pdf' id='view'>";
            }

            if($spo === "laundry"){
                $data["spo"]      = "STANDART PELAYANAN LAUNDRY";
                $data["document"] = "<embed src='" . base_url('dist/pdf/14.pdf#toolbar=0&navpanes=0') . "' width='100%' height='100%' type='application/pdf' id='view'>";
            }

            if($spo === "k3"){
                $data["spo"]      = "STANDART PELAYANAN K3";
                $data["document"] = "<embed src='" . base_url('dist/pdf/15.pdf#toolbar=0&navpanes=0') . "' width='100%' height='100%' type='application/pdf' id='view'>";
            }

            if($spo === "cssd"){
                $data["spo"]      = "STANDART PELAYANAN CSSD";
                $data["document"] = "<embed src='" . base_url('dist/pdf/16.pdf#toolbar=0&navpanes=0') . "' width='100%' height='100%' type='application/pdf' id='view'>";
            }

            if($spo === "ksr"){
                $data["spo"]      = "STANDART PELAYANAN KASIR";
                $data["document"] = "<embed src='" . base_url('dist/pdf/17.pdf#toolbar=0&navpanes=0') . "' width='100%' height='100%' type='application/pdf' id='view'>";
            }

            if($spo === "diklat"){
                $data["spo"]      = "STANDART PELAYANAN DIKLAT";
                $data["document"] = "<embed src='" . base_url('dist/pdf/18.pdf#toolbar=0&navpanes=0') . "' width='100%' height='100%' type='application/pdf' id='view'>";
            }

            $data["title"] = "DIGITAL INFORMASI RSUD PASAR MINGGU";
            
            $this->template->load("v_template","v_spodetail",$data);
        }
        
    }
?>
<?php
    class Jadwaldokter extends CI_Controller
    {
        public function __construct()
        {
            parent:: __construct();
            $this->load->model("Modeljadwaldokter","md");
        }

        public function index()
        {
            $data["title"]       = "DIGITAL INFORMASI RSUD PASAR MINGGU";
            $this->template->load("v_template","v_jadwaldokter",$data);
        }

        public function daftarpoli()
        {
            $result      = $this->md->daftarpoli();

            if(!empty($result)){
                $json['responCode']="00";
                $json['responHead']="success";
                $json['responDesc']="Data Di Temukan";
                $json['responResult']=$result;
            }else{
                $json['responCode']="01";
                $json['responHead']="info";
                $json['responDesc']="Data Tidak Di Temukan";
            }
            echo json_encode($json);
        }
        
    }
?>
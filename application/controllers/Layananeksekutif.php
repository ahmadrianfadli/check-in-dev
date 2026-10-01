<?php
    class Layananeksekutif extends CI_Controller
    {

        public function __construct()
        {
            parent:: __construct();
            $this->load->model("Modellayananeksekutif","md");
        }

        public function index()
        {
            $data["title"]       = "SERIOSA SELF SERVICE KIOSK";
            // $this->template->load("v_template","v_kasirrj",$data);
            $this->template->load("v_template","v_layananeksekutif",$data);
        }

        public function masterantrian()
        {
            $head = $this->input->post('head');
            $parent = $this->input->post('parent');
            $result      = $this->md->masterantrian($head,$parent);

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
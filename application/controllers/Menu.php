<?php
    class Menu extends CI_Controller{

        public function __construct(){
            parent:: __construct();
            $this->load->model("Modelmenu","md");
        }

        public function index(){            
            $data["title"]       = "SIMRS RSUD PASAR MINGGU";
            $this->template->load("v_template","v_menu",$data);
        }

        public function listmenu()
        {
            $result      = $this->md->menudisplay();

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
<?php
    class Listharga extends CI_Controller
    {

        public function __construct()
        {
            parent:: __construct();
            $this->load->model("Modeldaftarharga","md");
        }

        public function index()
        {
            $data["title"]       = "DIGITAL INFORMASI RSUD PASAR MINGGU";
            $this->template->load("v_template","v_daftarhargalist",$data);
        }

        public function cariDataLayan()
        {
            $kategoriId = $this->input->post('kategori');
            $carikata   = $this->input->post('carikata');
            $is_exe = $this->input->post('isexe');
            
            $result = $this->md->hargalayan($kategoriId, $carikata, $is_exe);

            if(!empty($result)){
                $json['responCode']="00";
                $json['responHead']="success";
                $json['responDesc']="Data Di Temukan";
                $json['responResult']=$result;
            }else{
                $json['responCode']="01";
                $json['responHead']="error";
                $json['responDesc']="Data Tidak Di Temukan";
            }

            echo json_encode($json);
        }
        
    }
?>
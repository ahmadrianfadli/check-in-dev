<?php
    class Ns extends CI_Controller{

        public function __construct(){
            parent:: __construct();
            $this->load->model("Modeldisplayns","md");
        }

        public function index(){
            $kodedisplay = $_GET['param'];
            $judul       = $this->md->judul($kodedisplay);

            $data["kodedisplay"] = $kodedisplay;
            $data["judul"]       = $judul[0]->DISPLAY;
            $data["title"]       = "SIMRS RSUD PASAR MINGGU";

            $this->load->view("v_ns", $data);
        }

        public function tensiberikutnya(){
            $this->clear_cache();
            $kodedisplay = $this->input->post('kodedisplay');
            $parameter   = "AND A.HADIR='Y'";
            $orderby     = "ORDER BY PANGGIL ASC, SLOTANTRIAN ASC, NOMORANTRIAN ASC, TGL_HADIR ASC";
            
            $result      = $this->md->antriannursestation($kodedisplay,8,$parameter,$orderby);

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

        public function tensipanggil(){
            $this->clear_cache();
            $kodedisplay = $this->input->post('kodedisplay');
            $parameter   = "AND A.HADIR='Y' AND A.PANGGIL='Y'";
            $orderby     = "ORDER BY TGL_PANGGIL DESC";

            $result      = $this->md->antriannursestation($kodedisplay,1,$parameter,$orderby);

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

        public function clear_cache() {
            $this->load->driver('cache');
            $this->cache->clean();
        }
        

    }
?>
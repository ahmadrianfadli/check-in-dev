<?php
class Cetakpenunjang extends CI_Controller
{

    public function __construct()
    {
        parent:: __construct();
        error_reporting(0);
        ini_set('display_errors', 0);

        $this->load->model("Modelcetakpenunjang","md");
    }

    public function index()
    {
        $data["title"]       = "SERIOSA SELF SERVICE KIOSK";
        $this->template->load("v_template","v_cetakpenunjang",$data);
    }

    public function mastercetak()
    {
        $head = $this->input->post('head');
        $parent = $this->input->post('parent');
        $kode = $this->input->post('kode');
        $result = $this->md->mastercetak($head,$parent,$kode);

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
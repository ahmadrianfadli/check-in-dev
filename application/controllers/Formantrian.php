<?php
class Formantrian extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model("Modelformantrian", "md");
        $this->load->model("Modelantriangeneral", "ma");
    }

    public function index()
    {
        $kode = $this->input->get('kode');
        $antrianms = $this->md->antrianms($kode);
        $data["title"]       = "SERIOSA SELF SERVICE KIOSK";
        $data['title2'] = $antrianms->KETERANGAN;
        $data['kode'] = $kode;
        $this->template->load("v_template", "v_formantrian", $data);
    }

    public function simpanantrian()
    {
        $kodebooking = $this->input->post('kodebooking');
        $kode = $this->input->post('kode');


        if ($kodebooking != "") {

            $cekdatapasien   = $this->md->cekdatapasien($kodebooking);

            if (!empty($cekdatapasien)) {
                $jenisantrian   = $this->md->getjenisantrian($kode);
                $jenis_id = '';
                if(count($jenisantrian) > 0){
                    $jenis_id = $jenisantrian[0]['JENIS_ID'];
                }
				
                $pasienId = $cekdatapasien->PASIEN_ID;
                $noRm = $cekdatapasien->INT_PASIEN_ID;
                $namaPas = $cekdatapasien->NAMA;
                $data['PASIEN_ID']  = $pasienId;
                $data['JENIS_ID']   = $jenis_id;
                $data['KODE']       = $kode;
                $data['IP_ADDRESS'] = $_SERVER['REMOTE_ADDR'];

                if($kode == "I"){
                    $cekspri = $this->md->cekspri($pasienId);
                    if(empty($cekspri)){
                        $json['responCode'] = "01";
                        $json['responHead'] = "info";
                        $json['responDesc'] = "TIDAK ADA RENCANA RAWAT INAP";

                        echo json_encode($json);exit;
                    }
                }
				
                if ($this->ma->simpanantrian($data)) {
                    $lastantrian = $this->ma->lastantrian($kode);

                    $last = $this->ma->get_last_antrian($kode);
                    $data['URUT'] = $last->URUT;

                    $data['NAMA_PASIEN'] = $cekdatapasien->NAMAPASIEN;
                    $data['PASIEN_ID'] = $cekdatapasien->INT_PASIEN_ID;
                    $data['INT_PASIEN_ID'] = $cekdatapasien->INT_PASIEN_ID;
                    $this->md->simpanantrian_postgree($data);

                    if (!empty($lastantrian)) {
                        $json['responCode']  = "00";
                        $json['responHead']  = "success";
                        $json['responTitle'] = "<strong>ANTRIAN<br>" . $lastantrian->KETERANGAN . "</strong><br><strong>";
                        $json['responDesc']  = "" . $lastantrian->NAMA . "</strong><br><br>Nomor Antrian Anda <br><strong>" . $lastantrian->KODE . "-" . $lastantrian->URUT . "</strong><br><br><h4 style='color:#234974;'>Silakan Menunggu Di Pendaftaran BPJS</h4>";
                    } else {
                        $json['responCode'] = "01";
                        $json['responHead'] = "info";
                        $json['responDesc'] = "AMBIL ANTRIAN GAGAL SILAKAN ULANGI KEMBALI";
                    }
                } else {
                    $json['responCode'] = "01";
                    $json['responHead'] = "info";
                    $json['responDesc'] = "AMBIL ANTRIAN GAGAL SILAKAN ULANGI KEMBALI";
                }
            } else {
                $json["responCode"] = "01";
                $json["responHead"] = "error";
                $json["responDesc"] = "INFORMASI";
                $json["responHtml"] = "<h4 style='color:#234974;'><b>$kodebooking<br>Data Pasien Tidak Ditemukan</b></h4>" . "<br><br>" . "<h5 style='color:#234974;'>Silakan Masukan Kembali</br><b>No. Rekam Medis atau No BPJS dan atau No KTP</b></h5>";
            }
        } else {
            $json["responCode"] = "01";
            $json["responHead"] = "error";
            $json["responDesc"] = "INFORMASI";
            $json["responHtml"] = "<h5 style='color:#234974;'>Silakan Masukan </br><b>No. Rekam Medis atau No BPJS dan atau No KTP</b><br> Terlebih Dahulu</h5>";
        }
        echo json_encode($json);
    }
}

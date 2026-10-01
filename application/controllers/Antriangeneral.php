<?php
class Antriangeneral extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model("Modelantriangeneral", "md");
    }

    public function simpanantrian()
    {
        $kode = $this->input->post("kode");
        //Masukannya pak: senin-kamis jam 06.00-12.00
        //Jumat- sabtu jam 06.00-10.00
        $hariid = date('w');
        if (in_array($hariid, ['5', '6'])) {
            $paramid = 'ANTRI08';
        } else {
            $paramid = 'ANTRI07';
        }
        $waktuambil = $this->md->waktuambil($paramid);
        $waktuambil = explode('-', $waktuambil->NILAI);
        $waktumulai = $waktuambil[0];
        $waktututup = $waktuambil[1];
        $data['KODE']       = $kode;
        $data['IP_ADDRESS'] = $_SERVER['REMOTE_ADDR'];
        if ($kode == 'A') {
            if (date("H:i:s") <= $waktumulai || date('H:i:s') >= $waktututup) {
                $json['responCode'] = "01";
                $json['responHead'] = "info";
                $json['responTitle'] = "<strong>TIDAK BISA AMBIL ANTRIAN</strong>";
                $json['responDesc'] = "<h2><strong>ANTRIAN DIBUKA PUKUL</h2><h1>{$waktumulai} - {$waktututup}</h1></strong>";
                echo json_encode($json);
                exit();
            }
        }

        if ($this->md->simpanantrian($data)) {
            $lastantrian = $this->md->lastantrian($kode);

            if (!empty($lastantrian)) {
                $json['responCode']  = "00";
                $json['responHead']  = "success";
                $json['responTitle'] = "<strong>ANTRIAN<br>" . $lastantrian->KETERANGAN . "</strong><br><strong>";
                $json['responDesc']  = "Nomor Antrian Anda <br><strong>" . $lastantrian->KODE . "-" . $lastantrian->URUT . "</strong><br><br><h4 style='color:#234974;'>Silakan Menunggu Di Pendaftaran BPJS</h4>";
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

        echo json_encode($json);
    }

    function cetakantrian(){
        $kode = $this->input->post("kode");
        $jenisid = $this->input->post("jenisid");
        //Masukannya pak: senin-kamis jam 06.00-12.00
        //Jumat- sabtu jam 06.00-10.00
        $hariid = date('w');
        if (in_array($hariid, ['5', '6'])) {
            $paramid = 'ANTRI08';
        } else {
            $paramid = 'ANTRI07';
        }
        $waktuambil = $this->md->waktuambil($paramid);
        $waktuambil = explode('-', $waktuambil->NILAI);
        $waktumulai = $waktuambil[0];
        $waktututup = $waktuambil[1];
        $data['JENIS_ID']   = $jenisid;
        $data['KODE']       = $kode;
        $data['IP_ADDRESS'] = $_SERVER['REMOTE_ADDR'];
        if ($kode == 'A' || $kode == 'C') {
            if (date("H:i:s") <= $waktumulai || date('H:i:s') >= $waktututup) {
                $json['responCode'] = "01";
                $json['responHead'] = "info";
                $json['responTitle'] = "<strong>TIDAK BISA AMBIL ANTRIAN</strong>";
                $json['responDesc'] = "<h2><strong>ANTRIAN DIBUKA PUKUL</h2><h1>{$waktumulai} - {$waktututup}</h1></strong>";
                echo json_encode($json);
                exit();
            }
        }

        if($kode == 'NEWA'){
            $kode = 'A';
            $data['KODE']  = 'A';
        }
        if($kode == 'NEWC'){
            $kode = 'C';
            $data['KODE']  = 'C';
        }

        if ($this->md->simpanantrian($data)) {
            $last = $this->md->get_last_antrian($kode);
            $data['URUT'] = $last->URUT;

            $this->md->simpanantrian_postgree($data);
            $lastantrian = $this->md->lastantrian($kode);

            if (!empty($lastantrian)) {
                $pleas_wait = 'Silakan Menunggu Di Pendaftaran BPJS';
                $img = 'ruang_bpjs.jpg';
                if($kode == 'W' || $kode == 'X' || $kode == 'Y' || $kode == 'Z'){
                    $pleas_wait = 'Silakan Menunggu Di Magnolia';
                    $img = 'ruang_magnolia.png';
                }
                if($kode == 'E' || $kode == 'F'){
                    $pleas_wait = 'Silakan Menunggu Di Pendaftaran UMUM';
                    $img = 'ruang_magnolia.png';
                }

                $caresoul = '
                    <style>
                    #carouselAntrian .carousel-item img {
                        height: 570px;
                        object-fit: cover;
                    }
                    </style>
                    
                    <div id="carouselAntrian" class="carousel slide" data-bs-ride="carousel">
                        <div class="carousel-inner">
                    
                            <div class="carousel-item active text-center">
                                <img src="'. base_url('dist/img/'. $img .'') .'" class="d-block w-100 rounded">
                            </div>
                            
                        </div>
                    
                        <button class="carousel-control-prev" type="button"
                            data-bs-target="#carouselAntrian"
                            data-bs-slide="prev">
                            <span class="carousel-control-prev-icon"></span>
                        </button>
                    
                        <button class="carousel-control-next" type="button"
                            data-bs-target="#carouselAntrian"
                            data-bs-slide="next">
                            <span class="carousel-control-next-icon"></span>
                        </button>
                    </div>
                ';

                $json['responCode']  = "00";
                $json['responHead']  = "success";
                $json['responTitle'] = "<strong>ANTRIAN " . $lastantrian->KETERANGAN . "</strong><br><strong>Nomor Antrian Anda <strong>" . $lastantrian->KODE . "-" . $lastantrian->URUT . "</strong><br>";
                $json['responDesc']  = $caresoul.'<br><h1 style="color:#234974;">'. $pleas_wait .'</h1>';
                $json['responData']  = $lastantrian;
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

        echo json_encode($json);
    }

    function printantrian(){
        $tid = $this->input->post("trans_id");
        $antrian = $this->md->getantrian($tid);
        if(!empty($antrian)){
            $data['nomor']      = $antrian->KODE ." - ". $antrian->URUT;
            $data['keterangan'] = $antrian->KETERANGAN;
            $data['tgl_cetak']  = $antrian->TANGGAL_CETAK;

            $this->md->updateantrian($tid);

            $this->load->view('v_printantrian', $data);
        }else{
            $ip = $_SERVER['REMOTE_ADDR'];
            if($ip == '10.33.0.165'){ /* KIOSK 4 DEPAN MAGNO */
                redirect(site_url('index.php/concept_5'));
            }else{
                redirect(site_url('index.php/concept_1'));
            }
        }
    }
}

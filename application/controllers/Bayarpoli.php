<?php

class Bayarpoli extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model("Modelbayarpoli", "md");
    }

    public function index()
    {
        $data["title"]       = "DIGITAL INFORMASI RSUD PASAR MINGGU";
        $this->template->load("v_template", "v_bayarpoli", $data);
    }

    public function checkdatapasien()
    {
        $kode       = $this->input->post('kode');
        $idpasantri = $this->input->post("idpasantri");
        $subhead = $this->input->post('subhead');

        if ($idpasantri) {
            $cekdatapasien = $this->md->cekdatapasien($idpasantri);

            if (!empty($cekdatapasien)) {

                $namapasien = $cekdatapasien->NAMA;
                $idPasien   = $cekdatapasien->PASIEN_ID;

                $json['responCode'] = "00";
                $json["responHead"] = "success";
                $json["responurl"] = base_url('index.php/bayarpoli/registrasipasien?head=concept_5&subhead=' . $subhead . '&pasienid=' . $idPasien);
            } else {
                $json['responCode'] = "02";
                $json["responHead"] = "error";
                $json["responDesc"] = "INFORMATION";
                $json["responHtml"] = "<h5 style='color:#234974;'>Identitas Anda Tidak Di Temukan</h5>";
            }
        } else {
            $json['responCode'] = "01";
            $json["responHead"] = "error";
            $json["responDesc"] = "INFORMATION";
            $json["responHtml"] = "<h5 style='color:#234974;'>Mohon Masukan Identitas Anda Terlebih Dahulu</h5>";
        }

        echo json_encode($json);
    }

    public function showPoli()
    {
        $listpoli = $this->md->listpoli();
        echo json_encode($listpoli);
    }

    public function registrasipasien()
    {
        $data["title"]       = "REGISTRASI PASIEN UMUM";
        $this->template->load("v_template", "v_bayarpoliform", $data);
    }

    public function getdatapasien()
    {
        $idPasien = $this->input->post('pasienid');
        $cekdatapasien = $this->md->cekdatapasien($idPasien);

        echo json_encode($cekdatapasien);
    }

    public function antrianbayarpoli()
    {
        $poliId = $this->input->post('poliid');
        $pasien_id = $this->input->post('pasien_id');
        $datapoli = $this->md->cekdatapoli($poliId);

        $cekdatapasien = $this->md->cekdatapasien($pasien_id);

        if (!empty($cekdatapasien)) {
            if(count($datapoli)>0){
                if($datapoli[0]['IS_EXE'] == 'T'){
                    $jenis = 'A05';
                    $kode = 'E';
                    $pleas_wait = 'Silakan Menunggu Di Pendaftaran UMUM';
                }else{
                    $jenis = 'A28';
                    $kode = 'X';
                    $pleas_wait = 'Silakan Menunggu Di Magnolia';
                }

                $idPasien = $cekdatapasien->PASIEN_ID;

                $data['PASIEN_ID'] = $idPasien;
                $data['IP_ADDRESS'] = $_SERVER['REMOTE_ADDR'];
                $data['JENIS_ID'] = $jenis;
                $data['KODE'] = $kode;
                $data['POLI_ID'] = $datapoli[0]['POLI_ID'];

                if ($this->md->simpanantrian($data)) {
                    $last = $this->md->get_last_antrian($kode);
                    $data['URUT'] = $last->URUT;
 
					$data['NAMA_PASIEN'] = str_replace("'", "''", $cekdatapasien->NAMAPASIEN);
                    $data['INT_PASIEN_ID'] = $cekdatapasien->INT_PASIEN_ID;
					$data['NM_POLI'] = $datapoli[0]['KETERANGAN'];
                    $this->md->simpanantrian_postgree($data);
                    $lastantrian = $this->md->lastantrian($kode);

                    if (!empty($lastantrian)) {
                        $json['responCode']  = "00";
                        $json['responHead']  = "success";
                        $json['responDesc']  = "INFORMATION";
                        $json['responHtml']  = "Nomor Antrian Anda <br><strong>" . $lastantrian->KODE . "-" . $lastantrian->URUT . "</strong><br><br><h4 style='color:#234974;'>". $pleas_wait ."</h4>";
                        $json['responUrl']   = base_url('index.php/pasienlama?parent=A23&head=concept_5&subhead=antrianloket?head=concept_5&parent=A20');
                    } else {
                        $json['responCode'] = "01";
                        $json['responHead'] = "info";
                        $json['responDesc'] = "INFORMATION";
                        $json['responHtml'] = "AMBIL ANTRIAN GAGAL SILAKAN ULANGI KEMBALI";
                    }
                } else {
                    $json['responCode'] = "01";
                    $json['responHead'] = "info";
                    $json['responDesc'] = "INFORMATION";
                    $json['responHtml'] = "AMBIL ANTRIAN GAGAL SILAKAN ULANGI KEMBALI";
                }
            }else{
                $json['responCode'] = "01";
                $json["responHead"] = "error";
                $json["responDesc"] = "INFORMATION";
                $json["responHtml"] = "<h5 style='color:#234974;'>Mohon Masukan Poli Tujuan</h5>";
            }
        }else{
            $json['responCode'] = "02";
            $json["responHead"] = "error";
            $json["responDesc"] = "INFORMATION";
            $json["responHtml"] = "<h5 style='color:#234974;'>Identitas Anda Tidak Di Temukan</h5>";
        }

        echo json_encode($json);
    }
}

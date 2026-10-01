<?php

class Bayartindakan extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model("Modelbayartindakan", "md");
    }

    public function index()
    {
        $data["title"] = "SERIOSA SELF SERVICE KIOSK";
        $this->template->load("v_template", "v_bayartindakan", $data);
    }

    public function checkinantrikasir()
    {
        $idpasantri = $this->input->post("idpasantri");

        if ($idpasantri != "") {
            $cekdatapasien = $this->md->cekdatapasien($idpasantri);

            if (!empty($cekdatapasien)) {

                $namapasien = $cekdatapasien->NAMA;
                $idPasien = $cekdatapasien->PASIEN_ID;

                //pembayaran tindakan
                $transaksi = $this->md->cektransaksipasien($idPasien);

                if ($transaksi) {
                    if ($transaksi->TOTAL > 0) {
                        if($transaksi->IS_EXE == 'T'){
                            $jenis = 'A06';
                            $kode = 'F';
                            $pleas_wait = 'Silakan Menunggu Di Kasir Umum';
                        }else{
                            $jenis = 'A29';
                            $kode = 'Y';
                            $pleas_wait = 'Silakan Menunggu Di Magnolia';
                        }

                        $data['PASIEN_ID'] = $idPasien;
                        $data['KODE'] = $kode;
                        $data['JENIS_ID'] = $jenis;
                        $data['IP_ADDRESS'] = $_SERVER['REMOTE_ADDR'];

                        if ($this->md->simpanantrian($data)) {
                            $last = $this->md->get_last_antrian($kode);
                            $data['URUT'] = $last->URUT;

                            $data['NAMA_PASIEN'] = $cekdatapasien->NAMA;
                            $data['INT_PASIEN_ID'] = $cekdatapasien->INT_PASIEN_ID;
                            $this->md->simpanantrian_postgree($data);
                            $lastantrian = $this->md->lastantrian($kode);

                            if (!empty($lastantrian)) {
                                $json['responCode'] = "00";
                                $json["responHead"] = "success";
                                $json["responDesc"] = "INFORMATION";
                                $json["responHtml"] = "<h1 style='color:#234974;'>Nomor Antrian Anda <br><strong>" . $lastantrian->KODE . "-" . $lastantrian->URUT . "</strong></h1><br><h4 class='text-truncate' style='color:#234974;'>" . $namapasien . "</h4><br>" . "<h5 style='color:#234974;'>". $pleas_wait ."<br>Kami Akan Segera Melayani Anda</h5>";
                                $json['responUrl']   = base_url('index.php/pasienlama?parent=A23&head=concept_5&subhead=antrianloket?head=concept_5&parent=A20');
                            } else {
                                $json['responCode'] = "01";
                                $json["responHead"] = "error";
                                $json["responDesc"] = "INFORMATION";
                                $json["responHtml"] = "<h5 style='color:#234974;'>Mohon Maaf Anda Gagal Melakukan Antrian. Silakan Hubungi Perawat</h5>";
                            }
                        } else {
                            $json['responCode'] = "01";
                            $json["responHead"] = "error";
                            $json["responDesc"] = "INFORMATION";
                            $json["responHtml"] = "<h5 style='color:#234974;'>Mohon Maaf Anda Gagal Melakukan Antrian. Silakan Hubungi Perawat</h5>";
                        }
                    } else {
                        $json['responCode'] = "01";
                        $json["responHead"] = "error";
                        $json["responDesc"] = "INFORMATION";
                        $json["responHtml"] = "<h5 style='color:#234974;'>Mohon Maaf Anda Tidak Mempunyai Data Pembayaran. Silakan Hubungi Perawat</h5>";
                    }
                } else {
                    $json['responCode'] = "01";
                    $json["responHead"] = "error";
                    $json["responDesc"] = "INFORMATION";
                    $json["responHtml"] = "<h5 style='color:#234974;'>Tidak ada transaksi yang ditemukan</h5>";
                }
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
}

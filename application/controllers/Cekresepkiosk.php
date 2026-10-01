<?php
#checkin
class Cekresepkiosk extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model("Modelcekresep", "md");
    }

    public function index()
    {
        $data["title"]       = "SERIOSA SELF SERVICE KIOSK";
        $data["ket_rating"] = [
            [
                'id' => '1',
                'value' => 'Sangat Kurang'
            ],
            [
                'id' => '2',
                'value' => 'Kurang'
            ],
            [
                'id' => '3',
                'value' => 'Cukup'
            ],
            [
                'id' => '4',
                'value' => 'Baik'
            ],
            [
                'id' => '5',
                'value' => 'Sangat Baik'
            ]
        ];
        $this->template->load("v_template", "v_cekresepkiosk", $data);
    }
    public function cekrating()
    {
        $episodeid = $this->input->post('episodeid');
        $cekrating = $this->md->ratingdokter($episodeid);
        if ($cekrating) {
            $json["responCode"] = "00";
        } else {
            $json["responCode"] = "01";
        }
        echo json_encode($json);
    }
    public function cekresep()
    {
        $html        = "";
        $kodebooking = $this->input->post("kodebooking");

        if ($kodebooking != "") {
            $worklistdouble   = $this->md->cektranscodouble($kodebooking);

            if ($worklistdouble) {
                $episode = $worklistdouble->EPISODE_ID;
                $transco = $worklistdouble->TRANS_CO;
                $this->md->updateworklist($episode, $transco);
            }

            $kodevalid = $this->md->cekbookingkode($kodebooking);

            if ($kodevalid) {
                $data = $this->md->cekadaresep($kodebooking);

                if ($data) {
                    //cek udah ada rating apa blm
                    //$cekrating = $this->md->ratingdokter($data->EPISODE_ID);

                    $isselesai = $data->ISSELESAI;
                    $isbayar   = $data->BAYAR;

                    if ($isbayar === "BELUM BAYAR") {
                        $html  = "<div class='p-3' style='border-style: dashed; border-radius:5px; border-color:gray;'>";
                        $html .= "<h3 class='font-weight-bold text-danger lh-sm'>SILAKAN MELAKUKAN PEMBAYARAN</h3>";
                        $html .= "<hr class='my-4' style='border-width: 5px;'>";
                        $html .= "<h4 class='font-weight-bold'>" . $data->NAMA . "</h4>";
                        $html .= "<h4 class='font-weight-bold'>" . $data->POLI . "</h4>";
                        $html .= "<br><br>" . "<h4>Silakan Melakukan Pembayaran di</h4>";
                        $html .= "<h3 class='m-3'><span class='bg-danger font-weight-bold p-2' style='border-radius:10px;'>KASIR LANTAI 2</span></h3><br><br>";
                        $html .= "<hr class='my-4' style='border-width: 5px;'>";
                        $html .= "<div class='bg-warning p-3' style='border-radius:10px;'><strong><h5>Maksimal pengambilan obat</h5><h5>Pukul 18:00 pada hari yang sama</h5></strong></div>";
                        $html .= "</div>";
                        if ($data->OBATWARNING === 0) {
                            $html .= "<br><div><strong><h5>Pilih metode pengambilan obat :</h5></strong></div>";
                        }

                        $json["responCode"] = "00";
                        $json["responHead"] = "warning";
                        $json["responDesc"] = "<h1 class='font-weight-bold text-danger'>Belum Pembayaran</h1>";
                        $json["responHtml"] = $html;
                        $json["responData"] = $data;
                    } else {
                        if ($isselesai == "Y") {
                            $html  = "<div class='p-3' style='border-style: dashed; border-radius:5px; border-color:gray;'>";
                            $html .= "<h1 class='font-weight-bold text-success lh-sm'>RESEP SELESAI</h1>";
                            $html .= "<hr class='my-4' style='border-width: 5px;'>";
                            $html .= "<h4 class='font-weight-bold'>" . $data->NAMA . "</h4>";
                            $html .= "<h4 class='font-weight-bold'>" . $data->POLI . "</h4>";
                            $html .= "<br><br>" . "<h4>" . ($data->JAMSELESAI !== null ? 'Resep Sudah Selesai' : 'Resep sedang diproses farmasi') . "</h4>" . "<h4>" . ($data->JAMSELESAI !== null ? 'Silakan mengambil obat di' : 'Silakan menunggu di') . "</h4>";
                            $html .= "<h3 class='m-3'><span class='bg-primary font-weight-bold p-2' style='border-radius:10px;'>" . $data->LOKASIVALID . "</span></h3>";
                            $html .= "<h4 class='font-weight-bold mb-1 text-primary'>Hello Farah (Hotline Farmasi) :</h4>";
                            $html .= "<h4 class='font-weight-bold mb-3 text-primary'><i class='fab fa-whatsapp'></i> 0813-8789-505</h4><br>";
                            $html .= "<hr class='my-4' style='border-width: 5px;'>";
                            $html .= "<div class='bg-primary p-3' style='border-radius:10px;'><strong><h5>Maksimal pengambilan obat</h5><h5>Pukul 18:00 pada hari yang sama</h5></strong></div>";
                            $html .= "</div>";
                            if ($data->OBATWARNING === 0) {
                                $html .= "<br><div><strong><h5>Pilih metode pengambilan obat :</h5></strong></div>";
                            }

                            $json["responCode"] = "00";
                            $json["responHead"] = "success";
                            $json["responDesc"] = "<h1 class='font-weight-bold' style='color:#234974;'>RESEP SUDAH SELESAI</h1>";
                            $json["responHtml"] = $html;
                            $json["responData"] = $data;

                        } else {
                            $html  = "<div class='p-3' style='border-style: dashed; border-radius:5px; border-color:gray;'>";
                            $html .= "<h1 class='font-weight-bold text-success lh-sm'>ADA RESEP</h1>";
                            $html .= "<hr class='my-4' style='border-width: 5px;'>";
                            $html .= "<h4 class='font-weight-bold'>" . $data->NAMA . "</h4>";
                            $html .= "<h4 class='font-weight-bold'>" . $data->POLI . "</h4>";
                            $html .= "<br><br>" . "<h4>" . ($data->JAMSELESAI !== null ? 'Resep Sudah Selesai' : 'Resep sedang diproses farmasi') . "</h4>" . "<h4>" . ($data->JAMSELESAI !== null ? 'Silakan mengambil obat di' : 'Silakan menunggu di') . "</h4>";
                            $html .= "<h3 class='m-3'><span class='bg-warning font-weight-bold p-2' style='border-radius:10px;'>" . $data->LOKASIVALID . "</span></h3>";
                            $html .= "<h4 class='font-weight-bold mb-1 text-primary'>Hello Farah (Hotline Farmasi) :</h4>";
                            $html .= "<h4 class='font-weight-bold mb-3 text-primary'><i class='fab fa-whatsapp'></i> 0813-8789-505</h4><br>";
                            $html .= "<h1 class='font-weight-bold mb-5'>" . $data->JENISOBAT . "</h1><br>";
                            $html .= "<h4 class='font-weight-bold'>" . $data->ESTIMASITEKS . "<span class='bg-warning p-2' style='border-radius:10px;'>" . $data->ESTIMASIJAM . "</span></h4>";
                            $html .= "<hr class='my-4' style='border-width: 5px;'>";
                            $html .= "<div class='bg-warning p-3' style='border-radius:10px;'><strong><h5>Maksimal pengambilan obat</h5><h5>Pukul 18:00 pada hari yang sama</h5></strong></div>";
                            $html .= "</div>";       
                            if ($data->OBATWARNING === 0) {
                                $html .= "<br><div><strong><h5>Pilih metode pengambilan obat :</h5></strong></div>";
                            }                    

                            $json["responCode"] = "00";
                            $json["responHead"] = "success";
                            $json["responDesc"] = "<h1 class='font-weight-bold text-success lh-sm'>ADA RESEP</h1>";
                            $json["responHtml"] = $html;
                            $json["responData"] = $data;
                        }
                    }
                    /* } else {
                        $json["responCode"] = "99";
                        $json["responData"] = $data;
                    } */
                } else {
                    $json["responCode"] = "01";
                    $json["responHead"] = "error";
                    $json["responDesc"] = "INFORMASI";
                    $json["responHtml"] = "<h4 style='color:#234974;'><b>$kodebooking<br>TIDAK ADA RESEP</b></h4>";
                    $json["responData"] = $data;
                }
            } else {
                $json["responCode"] = "01";
                $json["responHead"] = "error";
                $json["responDesc"] = "INFORMASI";
                $json["responHtml"] = "<h4 style='color:#234974;'><b>$kodebooking<br>Tidak Teregistrasi Rawat Jalan Hari Ini</b></h4>" . "<br><br>" . "<h5 style='color:#234974;'>Silakan Masukan Kembali</br><b>Kode Booking, No BPJS dan atau No KTP</b></h5>";
            }
        } else {
            $json["responCode"] = "01";
            $json["responHead"] = "error";
            $json["responDesc"] = "INFORMASI";
            $json["responHtml"] = "<h5 style='color:#234974;'>Silakan Masukan </br><b>Kode Booking / No Rekam Medis / KTP</b><br> Terlebih Dahulu</h5>";
        }

        echo json_encode($json);
    }
    public function simpanrating()
    {
        $pasienid = $this->input->post('pasienid');
        $episodeid = $this->input->post('epiid');
        $dokterid = $this->input->post('dokterid');
        $rating = $this->input->post('rating');
        $penilaian = $this->input->post('penilaian') ? json_encode($this->input->post('penilaian')) : '';
        $data = [
            'EPISODE_ID' => $episodeid,
            'DOKTER_ID' => $dokterid,
            'PASIEN_ID' => $pasienid,
            'CREATED_BY' => $pasienid,
            'PENILAIAN' => $penilaian,
            'RATING' => $rating
        ];
        $simpanrating = $this->md->simpanrating($data);
        if ($simpanrating) {
            $json["responCode"] = "00";
            $json["responHead"] = "success";
            $json["responDesc"] = "<h3 class='font-weight-bold text-success lh-sm'>Terima Kasih telah memberikan Rating</h3>";
        } else {
            $json["responCode"] = "01";
            $json["responHead"] = "error";
            $json["responDesc"] = "INFORMASI";
            $json["responHtml"] = "<h5 style='color:#234974;'>Silakan Masukan </br><b>Kode Booking / No Rekam Medis / KTP</b><br> Terlebih Dahulu</h5>";
        }
        echo json_encode($json);
    }
    public function statusambil()
    {
        $episodeid      =   $this->input->post('episodeid');
        $statusambil    =   $this->input->post('statusambil');
        
        $beristatus     =   $this->md->beristatus($episodeid, $statusambil);

        if ($beristatus) {
            $json["responCode"] = "00";
            $json["responHead"] = "success";
        } else {
            $json["responCode"] = "01";
            $json["responHead"] = "error";
        }
        echo json_encode($json);
    }
    public function statucheckin()
    {
        $episodeid      =   $this->input->post('episodeid');
        
        $bericheckin    =   $this->md->bericheckin($episodeid);

        if ($bericheckin) {
            $json["responCode"] = "00";
            $json["responHead"] = "success";
        } else {
            $json["responCode"] = "01";
            $json["responHead"] = "error";
        }
        echo json_encode($json);
    }
}


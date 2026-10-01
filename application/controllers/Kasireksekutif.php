<?php

class Kasireksekutif extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model("Modelkasireksekutif", "md");
    }

    public function index()
    {
        $data["title"] = "SERIOSA SELF SERVICE KIOSK";
        $this->template->load("v_template", "v_kasireksekutif", $data);
    }

    public function transaksipasien()
    {
        $data["title"] = "DETAIL TRANSAKSI PASIEN";
        $this->template->load("v_template", "v_transaksipasien", $data);
    }

    public function checkinantrikasir()
    {
        $kode = $this->input->post('kode');
        $idpasantri = $this->input->post("idpasantri");

        if ($idpasantri != "") {
            $cekdatapasien = $this->md->cekdatapasien($idpasantri);

            if (!empty($cekdatapasien)) {

                $namapasien = $cekdatapasien->NAMA;
                $idPasien = $cekdatapasien->PASIEN_ID;

                //if ($kode === "E") {
                // daftar poli

                $data['PASIEN_ID'] = $idPasien;
                $data['KODE'] = $kode;
                $data['IP_ADDRESS'] = $_SERVER['REMOTE_ADDR'];

                if ($kode === 'X') {
                    //daftar poli
                    if ($this->md->simpanantrian($data)) {
                        $lastantrian = $this->md->lastantrian($kode);

                        if (!empty($lastantrian)) {
                            $json['responCode'] = "00";
                            $json["responHead"] = "success";
                            $json["responDesc"] = "INFORMATION";
                            $json["responHtml"] = "<h1 style='color:#234974;'>Nomor Antrian Anda <br><strong>" . $lastantrian->KODE . "-" . $lastantrian->URUT . "</strong></h1><br><h4 class='text-truncate' style='color:#234974;'>" . $namapasien . "</h4><br>" . "<h5 style='color:#234974;'>Silakan Menunggu<br>Kami Akan Segera Melayani Anda</h5>";
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
                    //pembayaran tindakan
                    $transaksi = $this->md->cektransaksipasien($idPasien);

                    if ($transaksi) {
                        if ($transaksi->TOTAL > 0) {
                            if ($this->md->simpanantrian($data)) {
                                $lastantrian = $this->md->lastantrian($kode);

                                if (!empty($lastantrian)) {
                                    $json['responCode'] = "00";
                                    $json["responHead"] = "success";
                                    $json["responDesc"] = "INFORMATION";
                                    $json["responHtml"] = "<h1 style='color:#234974;'>Nomor Antrian Anda <br><strong>" . $lastantrian->KODE . "-" . $lastantrian->URUT . "</strong></h1><br><h4 class='text-truncate' style='color:#234974;'>" . $namapasien . "</h4><br>" . "<h5 style='color:#234974;'>Silakan Menunggu<br>Kami Akan Segera Melayani Anda</h5>";
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

    public function gettransaksi()
    {
        header('Content-type:Application/json');
        $idPasien = $this->input->post('pasienid');
        $transaksi = $this->md->getalltransaksi($idPasien);
        #grup by episode_id

        #grup by poli
        $poli = [];
        foreach ($transaksi as $i => $d) {
            $poli[$d->POLI_ID]['ID'] = $d->POLI_ID;
            $poli[$d->POLI_ID]['NAMA'] = $d->NAMAPOLI;
            $poli[$d->POLI_ID]['EPISODEID'] = $d->EPISODE_ID;
            $poli[$d->POLI_ID]['JENIS'][] = $d;
        }
        $item = [];
        foreach ($poli as $d) {
            $jenis = [];
            foreach ($d['JENIS'] as $j => $e) {
                $jenis[$e->JENIS]['ID'] = $e->JENIS;
                $jenis[$e->JENIS]['GRUP'] = $e->GRUP;
                $jenis[$e->JENIS]['DATAJENIS'][] = $e;
            }

            $itemalkes = [];
            foreach ($jenis as $f) {
                $itemalkes[] = [
                    'ID' => $f['ID'],
                    'GRUP' => $f['GRUP'],
                    'DATA' => $f['DATAJENIS']
                ];
            }
            $item['POLI'][] = [
                'IDPOLI' => $d['ID'],
                'NAMAPOLI' => $d['NAMA'],
                'EPISODEID' => $d['EPISODEID'],
                'JENIS' => $itemalkes
            ];
        }
        echo json_encode($item);
        //die();
    }

    public function viaqris()
    {
        $header = array(
            "Content-Type: application/json"
        );

        $body['nominal'] = $this->input->post('nominal');
        $items = $this->input->post('items');

        $responsecurl = json_decode(curl([
            'url' => base_url('qris'),
            'method' => "POST",
            'header' => $header,
            'body' => json_encode($body),
            'savelog' => false,
            'source' => "BANKDKI"
        ]), true);
        if ($responsecurl['responseCode'] == '2004700') {
            $resQris = $responsecurl['qrContent'];
            $billNumber = $responsecurl['additionalInfo']['bill_number'];

            $epi = [];
            foreach ($items as $i => $d) {
                $epi[$d['epiid']]['epiid'] = $d['epiid'];
                $epi[$d['epiid']]['items'][] = $d;
                $epi[$d['epiid']]['harga'][] = +$d['harga'];
            }
            // die();
            $pasienId = $this->input->post('pasienid');
            $refNom = $this->md->getreferencenumber($pasienId);
            $refNom = $refNom->TRID;

            foreach ($epi as $d) {
                $epiId = $d['epiid'];

                $drId = $d['items'][0]['dokterid'];
                $poliId = $d['items'][0]['poliid'];

                $tothargaepi = array_sum($d['harga']);

                $transid = $this->md->gettransid();
                $trId = $transid->TRID;

                // INSERT HD
                $this->md->insertqrishd($trId, $pasienId, $epiId, $drId, $poliId, $resQris, $refNom, $billNumber, $tothargaepi);

                foreach ($d['items'] as $f) {
                    //INSERT IT
                    $transco = $f['transco'];
                    $rekid = $f['rekid'];
                    $layanid = $f['layanid'];
                    $nmlayan = $f['namalayan'];
                    $qty = $f['qty'];
                    $harga = $f['harga'];
                    $total = $qty * $harga;
                    $cito = $f['iscito'];
                    $jenis = $f['jenis'];

                    $this->md->insertqrisit($trId, $epiId, $pasienId, $transco, $rekid, $poliId, $layanid, $nmlayan, $qty, $harga, $total, $cito, $jenis);
                }
            }

            $json['responCode'] = "00";
            $json['responBody'] = $body;
            $json['responQris'] = $responsecurl['qrContent'];
            $json['responAll'] = $responsecurl;
        } else {
            $json['responCode'] = "01";
            $json['responQris'] = $responsecurl['responseMessage'];
        }

        echo json_encode($json);
    }

    public function cekstatusbayar()
    {
        $pasienId = $this->input->post('pasienid');

        $getdatabayar = $this->md->getdatabayar($pasienId);

        $refnumber = $getdatabayar->KET2;
        $nominal = $getdatabayar->NOMINAL;
        $tagihanid = $getdatabayar->TAGIHAN_ID;
        $tgljam = $getdatabayar->TGLJAM;
        $bayarid = $getdatabayar->BAYAR_ID;
        $header = array(
            "Content-Type: application/json"
        );

        $body['refnumber'] = $refnumber;
        $body['nominal'] = $nominal;
        $body['tagihanid'] = $tagihanid;
        $body['tgljam'] = $tgljam;

        $responsecurl = json_decode(curl([
            'url' => base_url('status_qris'),
            'method' => "POST",
            'header' => $header,
            'body' => json_encode($body),
            'savelog' => false,
            'source' => "BANKDKI"
        ]), true);

        echo json_encode($responsecurl);
    }

    public function buktibayar()
    {
        $bayarid = $this->input->get('bayarid');
        $pasienid = $this->input->get('pasid');
        $sysparam = $this->md->sysparam();
        $datapasien = $this->md->datapasien($bayarid, $pasienid);
        $detillayan = $this->md->detillayan($bayarid, $pasienid);

        $poli = [];
        foreach ($detillayan as $i => $d) {
            $poli[$d->POLI_ID]['ID'] = $d->POLI_ID;
            $poli[$d->POLI_ID]['NMPOLI'] = $d->NMPOLI;
            $poli[$d->POLI_ID]['EPISODEID'] = $d->EPISODE_ID;
            $poli[$d->POLI_ID]['DATA'][] = $d;
        }


        $data['detil'] = $poli;
        $this->load->view('v_buktibayar', $data);
    }
}

<?php

class Bayarqris extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model("Modelbayarqris", "md");
    }

    public function index()
    {
        $data["title"]       = "PEMBAYARAN TINDAKAN - QRIS";
        $this->template->load("v_template", "v_bayarqris", $data);
    }

    public function transaksipasien()
    {
        $data["title"]       = "DETAIL TRANSAKSI PASIEN - QRIS";
        $this->template->load("v_template", "v_transaksipasien", $data);
    }

    public function checkinantrikasir()
    {
        $kode       = $this->input->post('kode');
        $idpasantri = $this->input->post("idpasantri");
        $head = $this->input->post('head');

        //$idpasantri = '225376';
        $jenis = $this->input->post('jenis');

        if ($idpasantri != "") {
            $cekdatapasien = $this->md->cekdatapasien($idpasantri);

            if (!empty($cekdatapasien)) {

                $namapasien = $cekdatapasien->NAMA;
                $idPasien   = $cekdatapasien->PASIEN_ID;

                // $hitungtransaksi = $this->md->hitungTransaksi($idPasien);

                // if ($hitungtransaksi->JUMLAH != 0) {
                //     $cektransaksipasien = $this->md->cektransaksipasien($idPasien);

                //     if (!empty($cektransaksipasien)) {
                //         $html = '<div class="list-group">';

                //         foreach ($cektransaksipasien as $d) {
                //             $html .= '<div class="list-group-item">
                //                         <div class="justify-content-between">
                //                             <h2 class="mb-1">' . $d->JENIS . '</h2>
                //                         </div>
                //                         <h3 class="mb-1 red-text">' . $d->KETERANGAN . '</h3>
                //                      </div>';
                //         }

                //         $html .= '</div>';

                //         // Prima - 02.08.2024 : tambahkan untuk flag pasien yg sudah melakukan cek pembayaran
                //         $getcekobat = $this->md->getcekobat($idPasien);

                //         if (!empty($getcekobat)){
                //             $updatecekobat = $this->md->updateobatcekpasien($idPasien);
                //         }
                //         // end prima

                //         $json['responCode'] = "01";
                //         $json["responHead"] = "error";
                //         $json["responDesc"] = "INFORMATION";
                //         $json["responHtml"] = $html;

                // } else {
                //     $json['responCode'] = "01";
                //     $json["responHead"] = "error";
                //     $json["responDesc"] = "INFORMATION";
                //     $json["responHtml"] = "<h5 style='color:#234974;'>Mohon Maaf, Anda Tidak Mempunyai Data Pembayaran</h5>";
                // }

                if ($jenis === "kasir") {
                    // regist poli mandiri

                    $data['PASIEN_ID']  = $idPasien;
                    $data['KODE']       = $kode;
                    $data['IP_ADDRESS'] = $_SERVER['REMOTE_ADDR'];

                    // if ($this->md->simpanantrian($data)) {
                    //     $lastantrian = $this->md->lastantrian($kode);

                    //     if (!empty($lastantrian)) {
                    //         $json['responCode'] = "00";
                    //         $json["responHead"] = "success";
                    //         $json["responDesc"] = "INFORMATION";
                    //         $json["responHtml"] = "<h1 style='color:#234974;'>Nomor Antrian Anda <br><strong>" . $lastantrian->KODE . "-" . $lastantrian->URUT . "</strong></h1><br><h4 class='text-truncate' style='color:#234974;'>" . $namapasien . "</h4><br>" . "<h5 style='color:#234974;'>Silakan Menunggu<br>Kami Akan Segera Melayani Anda</h5>";
                    //     } else {
                    //         $json['responCode'] = "01";
                    //         $json["responHead"] = "error";
                    //         $json["responDesc"] = "INFORMATION";
                    //         $json["responHtml"] = "<h5 style='color:#234974;'>Mohon Maaf Anda Gagal Melakukan Antrian. Silakan Hubungi Petugas</h5>";
                    //     }
                    // } else {
                    //     $json['responCode'] = "01";
                    //     $json["responHead"] = "error";
                    //     $json["responDesc"] = "INFORMATION";
                    //     $json["responHtml"] = "<h5 style='color:#234974;'>Mohon Maaf Anda Gagal Melakukan Antrian. Silakan Hubungi Petugas</h5>";
                    // }
                } else {
                    // pembayaran transaksi
                    $cektransaksipasien = $this->md->cektransaksipasien($idPasien);

                    if (!empty($cektransaksipasien)) {
                        $html = '<div class="list-group">';

                        foreach ($cektransaksipasien as $d) {
                            $html .= '<div class="list-group-item">
                                        <div class="justify-content-between">
                                            <h2 class="mb-1">' . $d->JENIS . '</h2>
                                        </div>
                                        <h3 class="mb-1 red-text">' . $d->KETERANGAN . '</h3>
                                     </div>';
                        }

                        $html .= '</div>';

                        // Prima - 02.08.2024 : tambahkan untuk flag pasien yg sudah melakukan cek pembayaran
                        $getcekobat = $this->md->getcekobat($idPasien);

                        if (!empty($getcekobat)) {
                            $updatecekobat = $this->md->updateobatcekpasien($idPasien);
                        }
                        // end prima

                        $json['responCode'] = "01";
                        $json["responHead"] = "error";
                        $json["responDesc"] = "INFORMATION";
                        $json["responHtml"] = $html;
                    } else {
                        //tampil transaksi
                        $hitungtransaksi = $this->md->hitungTransaksi($idPasien);

                        // Pastikan bahwa properti JUMLAH ada dan tidak NULL
                        $jumlahTransaksi = isset($hitungtransaksi->JUMLAH) ? $hitungtransaksi->JUMLAH : 0;

                        if ($jumlahTransaksi == 0) {
                            $json['responCode'] = "01";
                            $json["responHead"] = "error";
                            $json["responDesc"] = "INFORMATION";
                            $json["responHtml"] = "<h5 style='color:#234974;'>Mohon Maaf, Anda Tidak Mempunyai Data Pembayaran</h5>";
                        } else {
                            $json['responCode'] = "00";
                            $json["responHead"] = "success";
                            $json["responurl"] = base_url('index.php/bayarqris/transaksipasien?kode=H&head=' . $head . '&subhead=antriankasir?head=' . $head . '&pasienid=' . $idPasien);
                        }
                    }

                    // $cekselesaidokter = $this->md->cekdokterselesai($idPasien);

                    // if (!empty($cekselesaidokter)) {

                    //     $cekobattr = $this->md->cekobat($idPasien);

                    //     if (!empty($cekobattr)) {
                    //         $json['responCode'] = "01";
                    //         $json["responHead"] = "error";
                    //         $json["responDesc"] = "INFORMATION";
                    //         $json["responHtml"] = "<h5 style='color:#234974;'>Mohon Maaf Ada Obat Belum di Validasi. Silakan Tunggu 10 Menit</h5>";
                    //     } else {
                    //         // CEK ADA TRANSAKSI LAB/RAD
                    //         $cektransaksilab = $this->md->ceklab($idPasien);
                    //         $cektransaksirad = $this->md->cekrad($idPasien);

                    //         if (!empty($cektransaksilab) && !empty($cektransaksirad)) {
                    //             $json['responCode'] = "01";
                    //             $json["responHead"] = "error";
                    //             $json["responDesc"] = "INFORMATION";
                    //             $json["responHtml"] = "<h5 style='color:#234974;'>Anda Mempunyai Transaksi Laboratorium dan Radiologi</h5> <br> <h5 class='text-truncate' style='color:#234974;'>Silakan Konfirmasi ke Laboratorium dan Radiologi</h5>";
                    //         } elseif (!empty($cektransaksilab) && empty($cektransaksirad)) {
                    //             $json['responCode'] = "01";
                    //             $json["responHead"] = "error";
                    //             $json["responDesc"] = "INFORMATION";
                    //             $json["responHtml"] = "<h5 style='color:#234974;'>Anda Mempunyai Transaksi Laboratorium</h5> <br> <h5 class='text-truncate' style='color:#234974;'>Silakan Konfirmasi ke Laboratorium</h5>";
                    //         } elseif (!empty($cektransaksirad) && empty($cektransaksilab)) {
                    //             $json['responCode'] = "01";
                    //             $json["responHead"] = "error";
                    //             $json["responDesc"] = "INFORMATION";
                    //             $json["responHtml"] = "<h5 style='color:#234974;'>Anda Mempunyai Transaksi Radiologi</h5> <br> <h5 class='text-truncate' style='color:#234974;'>Silakan Konfirmasi ke Radiologi</h5>";
                    //         } else {
                    //             $gettransaksi = $this->md->getepisode($idPasien);

                    //             if (!empty($gettransaksi)) {
                    //                 $json['responCode'] = "00";
                    //                 $json["responHead"] = "success";
                    //                 $json["responurl"] = base_url('index.php/bayarqris/transaksipasien?kode=H&head='.$head.'&subhead=antriankasir&pasienid=' . $idPasien);
                    //             } else {
                    //                 $json['responCode'] = "01";
                    //                 $json["responHead"] = "error";
                    //                 $json["responDesc"] = "INFORMATION";
                    //                 $json["responHtml"] = "<h5 style='color:#234974;'>Anda Belum Terdaftar Rawat Jalan Silakan Pilih Menu Daftar Poli</h5>";
                    //                 $json["responHtml"] = "<h1 style='color:#234974;'>Nomor Identitas <br><strong>" . $idpasantri . "</strong></h1><br><h4 class='text-truncate' style='color:#234974;'>Belum Terdaftar Rawat Jalan</h4>" . "<h5 style='color:#234974;'>Silakan Pilih Menu Daftar Poli</h5>";
                    //             }
                    //         }
                    //     }
                    // } else {
                    //     $json['responCode'] = "01";
                    //     $json["responHead"] = "error";
                    //     $json["responDesc"] = "INFORMATION";
                    //     $json["responHtml"] = "<h5 style='color:#234974;'>Dokter Belum Menyelesaikan Pemeriksaan Anda</h5> <br> <h5 class='text-truncate' style='color:#234974;'>Silakan Konfirmasi ke bagian Perawat</h5>";
                    // }
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
    }

    public function viaqris()
    {
        $header = array(
            "Content-Type: application/json"
        );

        $body['nominal'] = $this->input->post('nominal');
        //$body['nominal'] = '1000';
        $items = $this->input->post('items');

        $responsecurl = json_decode(curl([
            'url' => base_url('index.php/qris'),
            'method' => "POST",
            'header' => $header,
            'body' => json_encode($body),
            'savelog' => false,
            'source' => "BANKDKI"
        ]), true);
        if ($responsecurl['responseCode'] == '2004700') {
            $resQris = $responsecurl['qrContent'];
            $billNumber = $responsecurl['additionalInfo']['bill_number'];
            $expDateQris = $responsecurl['additionalInfo']['qr_expired'];

            $epi = [];
            foreach ($items as $i => $d) {
                $epi[$d['epiid']]['epiid'] = $d['epiid'];
                $epi[$d['epiid']]['items'][] = $d;
                $epi[$d['epiid']]['harga'][] = +$d['harga'];
                $epi[$d['epiid']]['total'] = +$d['total'];
            }
            // die();
            $pasienId = $this->input->post('pasienid');
            $refNom = $this->md->getreferencenumber($pasienId);
            $refNom = $refNom->TRID;

            foreach ($epi as $d) {
                $epiId = $d['epiid'];

                $drId = $d['items'][0]['dokterid'];
                $poliId = $d['items'][0]['poliid'];

                $tothargaepi = $d['total'];

                $transid = $this->md->gettransid();
                $trId = $transid->TRID;

                // INSERT HD
                $this->md->insertqrishd($trId, $pasienId, $epiId, $drId, $poliId, $resQris, $refNom, $expDateQris, $billNumber, $tothargaepi);

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

    public function cekstatusbayartesting()
    {
        $tagihanid = $this->input->get('tagihanid');

        $getdatabayar = $this->md->getdatabayartesting($tagihanid);

        $refnumber = $getdatabayar->KET2;
        $nominal = $getdatabayar->NOMINAL;
        $tagihanid = $getdatabayar->TAGIHAN_ID;
        $tgljam = $getdatabayar->TGLJAM;

        $header = array(
            "Content-Type: application/json"
        );

        $body['refnumber'] = $refnumber;
        $body['nominal'] = $nominal;
        $body['tagihanid'] = $tagihanid;
        $body['tgljam'] = $tgljam;

        $adaData = $this->md->ceknotifyqris($tagihanid);
        $response['responseCode'] = '201';
        $response['bayarid'] = '';
        if (!empty($adaData->BAYARID)) {
            $response['responseCode'] = '200';
            $response['bayarid'] = $adaData->BAYARID;
        }

        echo json_encode($response);
    }

    public function cekstatusbayar()
    {
        $tagihanid = $this->input->post('tagihanid');

        $getdatabayar = $this->md->getdatabayar($tagihanid);

        $refnumber = $getdatabayar->KET2;
        $nominal = $getdatabayar->NOMINAL;
        $tagihanid = $getdatabayar->TAGIHAN_ID;
        $tgljam = $getdatabayar->TGLJAM;

        $header = array(
            "Content-Type: application/json"
        );

        $body['refnumber'] = $refnumber;
        $body['nominal'] = $nominal;
        $body['tagihanid'] = $tagihanid;
        $body['tgljam'] = $tgljam;

        $adaData = $this->md->ceknotifyqris($tagihanid);
        $response['responseCode'] = '201';
        $response['bayarid'] = '';

        if (!empty($adaData->BAYARID)) {
            $response['responseCode'] = '200';
            $response['bayarid'] = $adaData->BAYARID;
        } else {
            $responsecurl = json_decode(curl([
                'url' => base_url('index.php/status_qris'),
                'method' => "POST",
                'header' => $header,
                'body' => json_encode($body),
                'savelog' => false,
                'source' => "BANKDKI"
            ]), true);

            if ($responsecurl['responseCode'] == '2005300') {
                $ORIGINALREFERENCENO        = $responsecurl['originalReferenceNo'];
                $ORIGINALPARTNERREFERENCENO = $responsecurl['originalPartnerReferenceNo'];
                $LATESTTRANSACTIONSTATUS    = $responsecurl['latestTransactionStatus'];
                $TRANSACTIONSTATUSDESC      = '';
                $BANKCODE                   = '';
                $EXTERNALSTOREID            = '';
                $AMOUNTVALUE                = intval($responsecurl['amount']['value']);
                $AMOUNTCURRENCY             = $responsecurl['amount']['currency'];
                $CUSTOMERPAN                = $responsecurl['additionalInfo']['customerPan'];
                $TRANSACTIONDATETIME        = date('Y.m.d H:i:s', strtotime($responsecurl['additionalInfo']['transactionDateTime']));
                $AMOUNTFEE                  = intval($responsecurl['additionalInfo']['amountFee']);
                $BILLNUMBER                 = $responsecurl['additionalInfo']['billNumber'];
                $CUSTOMERNAME               = $responsecurl['additionalInfo']['customerName'];
                $TERMINALID                 = $responsecurl['additionalInfo']['terminalId'];
                $STAN                       = $responsecurl['additionalInfo']['stan'];
                $MERCHANTNAME               = '';
                $APPROVALCODE               = '';
                $MERCHANTPAN                = $responsecurl['additionalInfo']['merchantPan'];
                $MCC                        = $responsecurl['additionalInfo']['mcc'];
                $MERCHANTCITY               = $responsecurl['additionalInfo']['merchantCity'];
                $MERCHANTCOUNTRY            = $responsecurl['additionalInfo']['merchantCountry'];
                $IPMERCHANT                 = '';
                $URLMERCHANT                = '';
                $ACQUIRERCODE               = '';
                $ACQUIRERNAME               = '';

                $adaData = $this->md->ceknotifyqris($tagihanid);

                if (!empty($adaData)) {
                    // abaikan jika tidak kosong
                    $response['responseCode']    = $responsecurl['responseCode'];
                    $response['responseMessage'] = $responsecurl['responseMessage'];
                } else {
                    // jika ceknotifyqris = null, maka insert notify
                    if (empty($BILLNUMBER)) {
                        $BILLNUMBER = $tagihanid;
                    }

                    $this->md->insertnotify(
                        $ORIGINALREFERENCENO,
                        $ORIGINALPARTNERREFERENCENO,
                        $LATESTTRANSACTIONSTATUS,
                        $TRANSACTIONSTATUSDESC,
                        $BANKCODE,
                        $EXTERNALSTOREID,
                        $AMOUNTVALUE,
                        $AMOUNTCURRENCY,
                        $CUSTOMERPAN,
                        $TRANSACTIONDATETIME,
                        $AMOUNTFEE,
                        $BILLNUMBER,
                        $CUSTOMERNAME,
                        $TERMINALID,
                        $STAN,
                        $MERCHANTNAME,
                        $APPROVALCODE,
                        $MERCHANTPAN,
                        $MCC,
                        $MERCHANTCITY,
                        $MERCHANTCOUNTRY,
                        $IPMERCHANT,
                        $URLMERCHANT,
                        $ACQUIRERCODE,
                        $ACQUIRERNAME
                    );

                    $response['responseCode']    = $responsecurl['responseCode'];
                    $response['responseMessage'] = $responsecurl['responseMessage'];
                }
            } else {
                $response['responseCode']    = $responsecurl['responseCode'];
                $response['responseMessage'] = $responsecurl['responseMessage'];
            }
        }

        echo json_encode($response);
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
        $data['datapasien'] = $datapasien;
        $data['sysparam'] = $sysparam;

        //$this->load->view('v_buktibayar', $data);
        $data["title"]       = "BUKTI BAYAR - QRIS";
        $this->template->load("v_template", "v_buktibayar", $data);
    }
}

<?php

class Bayarpoliqris extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model("Modelbayarpoliqris", "md");
    }

    public function index()
    {
        $data["title"]       = "DIGITAL INFORMASI RSUD PASAR MINGGU";
        $this->template->load("v_template", "v_bayarpoliqris", $data);
    }

    public function registrasipasien()
    {
        $data["title"]       = "REGISTRASI PASIEN UMUM";
        $this->template->load("v_template", "v_bayarpoliqrisform", $data);
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

                $cektransaksisebelumnya = $this->md->cekRegistSebelumnya($idPasien);

                if (!empty($cektransaksisebelumnya)){
                    // muncul qris sebelumnya
                    $tglmasuk = $cektransaksisebelumnya->TGLMASUK;
                    $nmpoli = $cektransaksisebelumnya->NAMAPOLI;
                    $nmdokter = $cektransaksisebelumnya->NAMADR;
                    $resqris = $cektransaksisebelumnya->KET1;
                    $nominal = $cektransaksisebelumnya->BILLING;
                    $tagihanid = $cektransaksisebelumnya->TAGIHAN_ID;

                    $json['responCode'] = "03";
                    $json["responHead"] = "warning";
                    $json["responDesc"] = "Transaksi Sebelumnya Ditemukan";
                    $json["responHtml"] = "<h3 style='color:#234974;'>Anda memiliki transaksi sebelumnya yang belum selesai.</h3>
                                           <h3 style='color:#234974;'>Silakan selesaikan transaksi tersebut terlebih dahulu.</h3>
                                           <h3 style='color:#234974;'>Tgl. Periksa : $tglmasuk</h3>
                                           <h3 style='color:#234974;'>Poli : $nmpoli</h3>
                                           <h3 style='color:#234974;'>Dokter : $nmdokter</h3>";
                    $json["responData"] =  ['qris' => $resqris, 'nominal' => $nominal, 'tagihanid' => $tagihanid];
                } else {
                    $json['responCode'] = "00";
                    $json["responHead"] = "success";
                    $json["responurl"] = base_url('index.php/bayarpoliqris/registrasipasien?kode=G&head=concept_5&parent=A21&subhead=' . $subhead . '&pasienid=' . $idPasien);
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

    public function getdatapasien()
    {
        $idPasien = $this->input->post('pasienid');
        $cekdatapasien = $this->md->cekdatapasien($idPasien);

        echo json_encode($cekdatapasien);
    }

    public function cekharilibur()
    {
        $tgltr = $this->input->post('tanggal');
        $harilibur = $this->md->getHariLibur($tgltr);

        echo json_encode($harilibur);
    }

    public function showPoli()
    {
        $listpoli = $this->md->listpoli();
        echo json_encode($listpoli);
    }

    public function showDokter()
    {
        $poliId = $this->input->post('poliid');
        $listdokter = $this->md->listdokter($poliId);
        echo json_encode($listdokter);
    }

    public function showJadwal()
    {
        $data['poli_id'] = $this->input->post('poliid');
        $data['dokter_id'] = $this->input->post('dokterid');
        $data['tanggal'] = $this->input->post('tgl');

        $listjadwal = $this->md->getJadwalDokterbyTanggal($data);

        echo json_encode($listjadwal);
    }

    public function showJadwalDokterDuaMinggu()
    {
        $poliId = $this->input->post('poliid');
        $dokterId = $this->input->post('dokterid');
        $jenis = $this->input->post('jenis');
        $jenis = '1';

        // $tglnow = date('d.m.Y');

        // if ($tglnow == $tglpilih) {
        //     $data['jenis'] = '2';
        // } else {
        //     $data['jenis'] = '1';
        // }


        $datapoli = $this->md->getDataPoli($poliId);
        $is_exe = $datapoli['IS_EXE'];

        $listjadwaldokter = $this->md->getJawdalDokterDuaMinggu($poliId, $dokterId, $jenis, $is_exe);
        $data['success'] = true;
        $data['data'] = $listjadwaldokter;
        echo json_encode($data);
    }

    public function gettransaksi()
    {
        header('Content-type:Application/json');
        $idPasien = $this->input->post('pasienid');
        $transaksi = $this->md->getalltransaksi($idPasien);

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

        $pasienId = $this->input->post('pasienid');
        $tanggal = $this->input->post('tanggal');
        $dokterId = $this->input->post('dokterId');
        $poliId = $this->input->post('poliId');
        $jadwal = $this->input->post('jadwal');

        $jadwalexplod = explode(';', $jadwal);
        $jamMulai = $jadwalexplod[0];
        $jamSelesai = $jadwalexplod[1];
        $urut = $jadwalexplod[2];
        $idAntrian = $jadwalexplod[4];
        $hariid = $jadwalexplod[5];
        $jenis = '1';

        $datapasien = $this->md->cekdatapasien($pasienId);
        $noHp = $datapasien->NOHP;
        $email = $datapasien->EMAIL;

        $idjadwal = $this->md->getidjadwal($poliId, $dokterId, $hariid, $idAntrian);
        $jadwalId = $idjadwal->JADWAL_ID;

        // $hariid = date('w',strtotime($tanggal)) + 1;
        // echo $hariid;
        // die();

        $datapoli = $this->md->getDataPoli($poliId);
        $is_exe = $datapoli['IS_EXE'];

        if($is_exe == 'Y'){
            $datadokter = $this->md->getDataDokter($dokterId);

            if($datadokter['SPESIALIS'] == 'Y'){
                $queryJasaDokter = "SELECT SR01_REGISTRASI.GETHARGA_LAYAN('001', 'UMUM', '6', (SELECT NILAI FROM SR01_SYS_PARAMETER WHERE PARAM_ID = 'ADMEXE003')) AS HARGA_DOKTER FROM DUAL";
                $resultHargaDokter = $this->db->query($queryJasaDokter)->row_array();
                $hargaDokter = $resultHargaDokter['HARGA_DOKTER'];
            }

            if($datadokter['SUBSPESIALIS'] == 'Y'){
                $queryJasaDokter = "SELECT SR01_REGISTRASI.GETHARGA_LAYAN('001', 'UMUM', '6', (SELECT NILAI FROM SR01_SYS_PARAMETER WHERE PARAM_ID = 'ADMEXE002')) AS HARGA_DOKTER FROM DUAL";
                $resultHargaDokter = $this->db->query($queryJasaDokter)->row_array();
                $hargaDokter = $resultHargaDokter['HARGA_DOKTER'];
            }

            if($datadokter['DRUMUM'] == 'Y'){
                $queryJasaDokter = "SELECT SR01_REGISTRASI.GETHARGA_LAYAN('001', 'UMUM', '6', (SELECT NILAI FROM SR01_SYS_PARAMETER WHERE PARAM_ID = 'ADMEXE004')) AS HARGA_DOKTER FROM DUAL";
                $resultHargaDokter = $this->db->query($queryJasaDokter)->row_array();
                $hargaDokter = $resultHargaDokter['HARGA_DOKTER'];
            }

            $nominal = intval($hargaDokter);
        }else{
            $nom = $this->md->getNominal($poliId, $dokterId, $jamMulai);
            $nominal = $nom->TOTAL;
        }

        // echo json_encode($noHp.''.$email.''.$nominal);
        // die();

        //$body['nominal'] = '1000';
        $body['nominal'] = $nominal;
        // $items = $this->input->post('items');

        $responsecurl = json_decode(curl([
            'url' => base_url('index.php/qris'),
            'method' => "POST",
            'header' => $header,
            'body' => json_encode($body),
            'savelog' => false,
            'source' => "BANKDKI"
        ]), true);
		
		// echo "<pre>";print_r($responsecurl);exit;

        if ($responsecurl['responseCode'] == '2004700') {
            $resQris = $responsecurl['qrContent'];
            $reffNom = $responsecurl['partnerReferenceNo'];
            $billNumber = $responsecurl['additionalInfo']['bill_number'];
            $expDateQris = $responsecurl['additionalInfo']['qr_expired'];

            $idepi = $this->md->buatepisode();
            $epiId = $idepi->EPISODE_ID;

            $insTransaksi = $this->md->simpanRegistrasi(
                $epiId,
                $pasienId,
                $poliId,
                $dokterId,
                $billNumber,
                $resQris,
                $hariid,
                $jadwalId,
                $jamMulai,
                $jamSelesai,
                $urut,
                $noHp,
                $email,
                $idAntrian,
                $tanggal,
                $jenis,
                $expDateQris,
                $reffNom,
                $is_exe
            );

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

    public function viaqrissebelumnya()
    {
        $header = array(
            "Content-Type: application/json"
        );

        $pasienId = $this->input->post('pasienid');
        $tanggal = $this->input->post('tanggal');
        $dokterId = $this->input->post('dokterId');
        $poliId = $this->input->post('poliId');
        $jadwal = $this->input->post('jadwal');
        $jadwalexplod = explode(';', $jadwal);
        $jamMulai = $jadwalexplod[0];
        $jamSelesai = $jadwalexplod[1];
        $urut = $jadwalexplod[2];
        $idAntrian = $jadwalexplod[4];
        $hariid = $jadwalexplod[5];
        $jenis = '1';

        $datapasien = $this->md->cekdatapasien($pasienId);
        $noHp = $datapasien->NOHP;
        $email = $datapasien->EMAIL;

        $idjadwal = $this->md->getidjadwal($poliId, $dokterId, $hariid, $idAntrian);
        $jadwalId = $idjadwal->JADWAL_ID;

        // $hariid = date('w',strtotime($tanggal)) + 1;
        // echo $hariid;
        // die();

        $nom = $this->md->getNominal($poliId, $dokterId, $jamMulai);
        $nominal = $nom->TOTAL;

        // echo json_encode($noHp.''.$email.''.$nominal);
        // die();

        //$body['nominal'] = '1000';
        $body['nominal'] = $nominal;
        // $items = $this->input->post('items');

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
            $reffNom = $responsecurl['partnerReferenceNo'];
            $billNumber = $responsecurl['additionalInfo']['bill_number'];
            $expDateQris = $responsecurl['additionalInfo']['qr_expired'];

            $idepi = $this->md->buatepisode();
            $epiId = $idepi->EPISODE_ID;

            $insTransaksi = $this->md->simpanRegistrasi(
                $epiId,
                $pasienId,
                $poliId,
                $dokterId,
                $billNumber,
                $resQris,
                $hariid,
                $jadwalId,
                $jamMulai,
                $jamSelesai,
                $urut,
                $noHp,
                $email,
                $idAntrian,
                $tanggal,
                $jenis,
                $expDateQris,
                $reffNom
            );

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

    public function cekstatusqris()
    {
        $header = array(
            "Content-Type: application/json"
        );
        $tagihanid = $this->input->post('tagihanid');
        $getdatabayar = $this->md->getdatabayar($tagihanid);

        if ($getdatabayar[0]->STATUS === "3") {
            $response['responseCode'] = '200';
            $response['bayarid'] = $getdatabayar[0]->BAYAR_ID;
            $response['pasienid'] = $getdatabayar[0]->PASIEN_ID;
        } else {
            $refnumber = $getdatabayar[0]->KET2;
            $nominal = $getdatabayar[0]->NOMINAL;
            $tagihanid = $getdatabayar[0]->TAGIHAN_ID;
            $tgljam = $getdatabayar[0]->TGLJAM;
            $body['refnumber'] = $refnumber;
            $body['nominal'] = $nominal;
            $body['tagihanid'] = $tagihanid;
            $body['tgljam'] = $tgljam;
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
                $TRANSACTIONSTATUSDESC      = $responsecurl['transactionStatusDesc'];
                $BANKCODE                   = $responsecurl['bankCode'];
                $EXTERNALSTOREID            = $responsecurl['externalStoreID'];
                $AMOUNTVALUE                = intval($responsecurl['amount']['value']);
                $AMOUNTCURRENCY             = $responsecurl['amount']['currency'];
                $CUSTOMERPAN                = $responsecurl['additionalInfo']['customerPan'];
                $TRANSACTIONDATETIME        = date('Y.m.d H:i:s', strtotime($responsecurl['additionalInfo']['transactionDateTime']));
                $AMOUNTFEE                  = intval($responsecurl['additionalInfo']['amountFee']);
                $BILLNUMBER                 = $responsecurl['additionalInfo']['billNumber'];
                $CUSTOMERNAME               = $responsecurl['additionalInfo']['customerName'];
                $TERMINALID                 = $responsecurl['additionalInfo']['terminalId'];
                $STAN                       = $responsecurl['additionalInfo']['stan'];
                $MERCHANTNAME               = $responsecurl['additionalInfo']['merchantName'];
                $APPROVALCODE               = $responsecurl['additionalInfo']['approvalCode'];
                $MERCHANTPAN                = $responsecurl['additionalInfo']['merchantPan'];
                $MCC                        = $responsecurl['additionalInfo']['mcc'];
                $MERCHANTCITY               = $responsecurl['additionalInfo']['merchantCity'];
                $MERCHANTCOUNTRY            = $responsecurl['additionalInfo']['merchantCountry'];
                $IPMERCHANT                 = $responsecurl['additionalInfo']['ipMerchant'];
                $URLMERCHANT                = $responsecurl['additionalInfo']['urlMerchant'];
                $ACQUIRERCODE               = $responsecurl['additionalInfo']['acquirerCode'];
                $ACQUIRERNAME               = $responsecurl['additionalInfo']['acquirerName'];

                $adaData = $this->md->ceknotifyqris($tagihanid);

                if (!empty($adaData)) {
                    // abaikan jika tidak kosong
                    $response['responseCode'] = '200';
                    $response['bayarid'] = $getdatabayar[0]->BAYAR_ID;
                    $response['pasienid'] = $getdatabayar[0]->PASIEN_ID;
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

                    $response['responseCode'] = '200';
                    $response['bayarid'] = $getdatabayar[0]->BAYAR_ID;
                    $response['pasienid'] = $getdatabayar[0]->PASIEN_ID;
                }
            } else {
                $response['responseCode']    = $responsecurl['responseCode'];
                $response['responseMessage'] = $responsecurl['responseMessage'];
            }
        }
        echo json_encode($response);
    }
    public function cekstatusbayar()
    {
        $tagihanid = $this->input->post('tagihanid');

        $getdatabayar = $this->md->getdatabayar($tagihanid);

        if ($getdatabayar[0]->STATUS === "3") {
            $response['responseCode'] = '200';
            $response['bayarid'] = $getdatabayar[0]->BAYAR_ID;
            $response['pasienid'] = $getdatabayar[0]->PASIEN_ID;
        } else {
            $response['responseCode'] = '201';
            $response['bayarid'] = '';
            /* $header = array(
                "Content-Type: application/json"
            );

            $refnumber = $getdatabayar[0]->KET2;
            $nominal = $getdatabayar[0]->NOMINAL;
            $tagihanid = $getdatabayar[0]->TAGIHAN_ID;
            $tgljam = $getdatabayar[0]->TGLJAM;

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
            } */
            /* else {
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
                    $TRANSACTIONSTATUSDESC      = $responsecurl['transactionStatusDesc'];
                    $BANKCODE                   = $responsecurl['bankCode'];
                    $EXTERNALSTOREID            = $responsecurl['externalStoreID'];
                    $AMOUNTVALUE                = intval($responsecurl['amount']['value']);
                    $AMOUNTCURRENCY             = $responsecurl['amount']['currency'];
                    $CUSTOMERPAN                = $responsecurl['additionalInfo']['customerPan'];
                    $TRANSACTIONDATETIME        = date('Y.m.d H:i:s', strtotime($responsecurl['additionalInfo']['transactionDateTime']));
                    $AMOUNTFEE                  = intval($responsecurl['additionalInfo']['amountFee']);
                    $BILLNUMBER                 = $responsecurl['additionalInfo']['billNumber'];
                    $CUSTOMERNAME               = $responsecurl['additionalInfo']['customerName'];
                    $TERMINALID                 = $responsecurl['additionalInfo']['terminalId'];
                    $STAN                       = $responsecurl['additionalInfo']['stan'];
                    $MERCHANTNAME               = $responsecurl['additionalInfo']['merchantName'];
                    $APPROVALCODE               = $responsecurl['additionalInfo']['approvalCode'];
                    $MERCHANTPAN                = $responsecurl['additionalInfo']['merchantPan'];
                    $MCC                        = $responsecurl['additionalInfo']['mcc'];
                    $MERCHANTCITY               = $responsecurl['additionalInfo']['merchantCity'];
                    $MERCHANTCOUNTRY            = $responsecurl['additionalInfo']['merchantCountry'];
                    $IPMERCHANT                 = $responsecurl['additionalInfo']['ipMerchant'];
                    $URLMERCHANT                = $responsecurl['additionalInfo']['urlMerchant'];
                    $ACQUIRERCODE               = $responsecurl['additionalInfo']['acquirerCode'];
                    $ACQUIRERNAME               = $responsecurl['additionalInfo']['acquirerName'];

                    $adaData = $this->md->ceknotifyqris($tagihanid);

                    if (!empty($adaData)){
                        // abaikan jika tidak kosong
                        $response['responseCode']    = $responsecurl['responseCode'];
                        $response['responseMessage'] = $responsecurl['responseMessage'];
                    } else {
                        // jika ceknotifyqris = null, maka insert notify
                        if (empty($BILLNUMBER)){
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
            } */
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
        $this->template->load("v_template", "v_buktibayarregist", $data);
    }
}

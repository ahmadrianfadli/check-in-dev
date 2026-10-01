<?php
class Suratkontrol extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model("Modelsuratkontrol", "md");
    }

    public function index()
    {
        $data["title"]       = "SERIOSA SELF SERVICE KIOSK";
        $this->template->load("v_template", "v_suratkontrol", $data);
    }
    public function cekkunjungan()
    {
        $norm = $this->input->post('norm');
        $cekdatapasien = $this->md->cekdatapasien($norm);
        if ($cekdatapasien) {
            $pasienid = $cekdatapasien->PASIEN_ID;
            //cek data
            $cekdatakunjungan = $this->md->cekdatakunjungan($pasienid);
            if ($cekdatakunjungan) {
                if ($cekdatakunjungan->SELESAIDOKTER > 0) {
                    $json['responCode'] = "00";
                    $json["responHead"] = "success";
                    $json["responDesc"] = "INFORMATION";
                    $json["responHtml"] = "<h3 style='color:#234974;'></h3>";
                    $json["responData"] = $cekdatakunjungan;
                } else {
                    $json['responCode'] = "01";
                    $json["responHead"] = "error";
                    $json["responDesc"] = "INFORMATION";
                    $json["responHtml"] = "<h3 style='color:#234974;'>Dokter Belum Menyelesaikan Pemeriksaan Anda</h3>";
                }
            } else {
                $json['responCode'] = "01";
                $json["responHead"] = "error";
                $json["responDesc"] = "INFORMATION";
                $json["responHtml"] = "<h3 style='color:#234974;'>Tidak ada Kunjungan Dokter Hari ini</h3>";
            }
        } else {
            $json['responCode'] = "01";
            $json["responHead"] = "error";
            $json["responDesc"] = "INFORMATION";
            $json["responHtml"] = "<h3 style='color:#234974;'>Identitas Anda Tidak Di Temukan</h3>";
        }
        echo json_encode($json);
    }
    public function getpoli()
    {
        $polirujukan = $this->input->post('polirujukan');
        $getpoli = $this->md->getpoli($polirujukan);
        if ($getpoli) {
            $res['code'] = '00';
            $res['data'] = $getpoli;
        } else {
            $res['code'] = '01';
        }
        echo json_encode($res);
    }
    public function getdokter()
    {
        $poli = $this->input->post('poli');
        $getdokter = $this->md->getdokter($poli);
        if ($getdokter) {
            $res['code'] = '00';
            $res['data'] = $getdokter;
        } else {
            $res['code'] = '01';
        }
        echo json_encode($res);
    }
    public function createsuratkontrol()
    {
        $pasienid = $this->input->post('pasienid');
        $nosep = $this->input->post('nosep');
        $poli = $this->input->post('poli');
        $polibpjs = $this->input->post('polibpjs');
        $dokterid = $this->input->post('dokterid');
        $dokteridbpjs = $this->input->post('dokteridbpjs');
        $tglrencana = $this->input->post('tglrencana');
        $noRujukan = $this->input->post('nokunjungan');
        $body = [
            'no_sep' => $nosep,
            'kode_dokter' => $dokteridbpjs,
            'kode_poli' => $polibpjs,
            'tanggal' => $tglrencana,
            'user' => 'KIOSK'
        ];


        $response = execCurlBpjsWithToken([
            'url' => RSUDPM_API_BASE_URL . '/bpjs-v2/insert-surat-kontrol',
            'method' => 'POST',
            'body' => json_encode($body),
            'resultType' => 'json',
            //'showRawResponse' => true,
        ]);
        /*   echo json_encode($response);
        die(); */
        $retVal['rawResponse'] = $response['rawResponse'];

        if ($response['success']) {

            /* $retVal['log'] = isset($response['result']['log']) ? $response['result']['log'] : []; */

            if (intval($response['result']['metadata']['responCode']) == 00) {
                $insertSuratKontrol = $this->md->insertSuratKontrol($response['result']['response']['noSuratKontrol'], $pasienid, '', '', $poli, $dokterid, $noRujukan, $nosep, date('d.m.Y', strtotime($tglrencana)));
                $json['responCode'] = "00";
                $json["responHead"] = "success";
                $json["responDesc"] = "INFORMATION";
                $json["responHtml"] = 'Berhasil Membuat Surat Kontrol BPJS, Silahkan melakukan Pendaftaran Online Melalui Mobile JKN';
                $json["responData"] = $response['result']['response'];
            } else {
                $json['responCode'] = "01";
                $json["responHead"] = "error";
                $json["responDesc"] = "INFORMATION";
                $json["responHtml"] = $response['result']['metadata']['responDesc'];
            }
        } else {
            $json['responCode'] = "01";
            $json["responHead"] = "error";
            $json["responDesc"] = "INFORMATION";
            $json["responHtml"] = $response['result']['metadata']['responDesc'];
            // $retVal['message'] = 'Gagal mendapatkan data dari server. Silakan coba beberapa saat lagi';
            /*    echo '<pre>' . print_r($response['url'], true) . '</pre>';
            echo '<pre>' . print_r($response['rawRequestHeader'], true) . '</pre>';
            echo '<pre>' . print_r($response['rawRequestBody'], true) . '</pre>';
            echo '<pre>' . print_r($response['rawResponse'], true) . '</pre>'; */
        }

        echo json_encode($json);
    }
}

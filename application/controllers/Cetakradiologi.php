<?php

include FCPATH."plugins/phpqrcode/qrlib.php";

class Cetakradiologi extends CI_Controller
{

    public function __construct()
    {
        parent:: __construct();
        error_reporting(0);
        ini_set('display_errors', 0);

        $this->load->model("Modelcetakradiologi","md");
    }

    public function index()
    {
        $data["title"]       = "DIGITAL INFORMASI RSUD PASAR MINGGU";
        $this->template->load("v_template","v_cetakradiologi",$data);
    }

    function cekpasien(){
        $json = array(
            'responCode' => '01',
            'responHead' => 'info',
        );

        $norm = $this->input->post('norm');
        $tgllahir = convertDateTime(str_replace('/','-', $this->input->post('tgllahir')), 'userDate');
        $tglkunjungan = convertDateTime(str_replace('/','-', $this->input->post('tglkunjungan')), 'userDate');

        $ceknorm = $this->md->ceknorm($norm);
        if(!empty($ceknorm)){
            $norm = $ceknorm->INT_PASIEN_ID;
            $cektgllahir = $this->md->cektgllahir($norm, $tgllahir);
            if(!empty($cektgllahir)){
                if($cektgllahir->EMAIL != ''){
                    if (filter_var($cektgllahir->EMAIL, FILTER_VALIDATE_EMAIL)) {
                        $pasienID = $cektgllahir->PASIEN_ID;

                        $detailPasien = $this->md->getPasienRadDetail($pasienID, $tglkunjungan);

                        if (!empty($detailPasien)) {
                            $TANGGAL_RAD = date('Y-m-d', strtotime($detailPasien['TANGGAL']));

                            if($TANGGAL_RAD >= '2024-08-01'){
                                $episodeId = $detailPasien['EPISODE_ID'];
                                $transCO = $detailPasien['TRANS_CO'];

                                $detailRad = $this->md->getDetailBaru($pasienID, $episodeId, $transCO);
                                $detailRis = $this->md->getRisBaru($norm, $episodeId);

                                $data = [
                                    'pasien' => $detailPasien,
                                    'rad' => $detailRad,
                                    'ris' => $detailRis,
                                ];

                                /* create file pdf */
                                $this->load->library('pdf');
                                $html = $this->load->view('v_detailrad', $data, true);
                                $pdf = new Pdf();

                                $pdf->SetCreator(PDF_CREATOR);
                                $pdf->SetAuthor('RSUD Pasar Minggu');
                                $pdf->SetTitle('RSUD Pasar Minggu');
                                $pdf->SetSubject('Laporan Radiologi '.$detailPasien['NAMA_PASIEN']);

                                $pdf->SetMargins(5, 15, 8);
                                $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
                                $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
                                $pdf->SetAutoPageBreak(TRUE, 40);
                                $pdf->AddPage();

                                $pdf->writeHTML($html, true, false, true, false, '');

                                $pdfpath = FCPATH . '../scanrm/FILESCAN/'.implode('/', str_split($detailPasien['PASIEN_ID'], 2)).'/';
                                if (!is_dir($pdfpath)) {
                                    mkdir($pdfpath, 0777, true);
                                }
                                $pdfname = $detailPasien['PASIEN_ID'].'_0000R_'.str_replace('.','',$detailPasien['TANGGAL']).'.pdf';
                                $pdf->Output($pdfpath.$pdfname, 'F');   /* F untuk buat file, I tampil di browser */
                                /* end create file pdf */

                                $cekDokumen = $this->md->getDocument($pasienID, $tglkunjungan);
                                if(empty($cekDokumen)){
                                    /* save ke table document */
                                    $PASIEN_ID = $detailPasien['PASIEN_ID'];
                                    $DIR_FILE = implode('\\', str_split($detailPasien['PASIEN_ID'], 2));
                                    $NM_FILE = $pdfname;
                                    $CREATED_DATE = date('d.m.Y H:i:s');
                                    $CREATED_BY = 'PASIEN';
                                    $TANGGAL = $detailPasien['TANGGAL'];
                                    $this->md->addDocument($PASIEN_ID, $DIR_FILE, $NM_FILE, $CREATED_DATE, $CREATED_BY, $TANGGAL);
                                    /* end ke table document */
                                }else{
                                    $this->md->reUpdateEsign($pasienID, $tglkunjungan);
                                }

                                $email = $cektgllahir->EMAIL;
                                $atPos = strpos($email, '@');
                                if ($atPos > 4) {
                                    $emailMasked = substr_replace($email, '****', $atPos - 4, 4);
                                } else {
                                    $emailMasked = $email;
                                }

                                $json['responCode']="00";
                                $json['responHead']="success";
                                $json['responDesc'] = "Apakah Anda ingin mengirimkan hasil radiologi ke email berikut: ". $emailMasked ."?";
                            }else{
                                $json['responDesc']="Tanggal kunjungan sebelum 01-08-2024 tidak dapat ditampilkan";
                            }
                        } else {
                            $json['responDesc']="Data Tidak Di Temukan, <br>pada tanggal kunjungan ".$tglkunjungan;
                        }
                    } else {
                        $json['responDesc']="Email Tidak Valid, <br>Silahkan perbaiki data ke bagian pendaftaran";
                    }
                }else{
                    $json['responDesc']="Email belum ada, <br>Silahkan lengkapi data ke bagian pendaftaran";
                }
            }else{
                $json['responDesc']="Data Tidak Lengkap, <br>Silahkan lengkapi data ke bagian pendaftaran";
            }
        }else{
            $json['responDesc']="No Rekam Medik atau NIK KTP ". $norm ." Tidak Di Temukan, <br>Silahkan ke bagian pendaftaran";
        }

        echo json_encode($json);
    }

    function reqdoc()
    {
        $norm = $this->input->post('norm');
        $tgllahir = convertDateTime(str_replace('/', '-', $this->input->post('tgllahir')), 'userDate');
        $tglkunjungan = convertDateTime(str_replace('/', '-', $this->input->post('tglkunjungan')), 'userDate');

        $cektgllahir = $this->md->cektgllahir($norm, $tgllahir);
        if (!empty($cektgllahir)) {
            $pasienid = $cektgllahir->PASIEN_ID;
            $cekDokumen = $this->md->getDocument($pasienid, $tglkunjungan);

            $header = array(
                "Content-Type: application/json"
            );
            $body = array(
                'PASIEN_ID' => $pasienid,
                'TANGGAL' => $tglkunjungan,
            );

            if ($cekDokumen[0]['ESIGN'] == 'Y') {
                /* curl kirim email */
                $url = "https://rsudpasarminggu.jakarta.go.id/kirimemail/hasilrad";
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $url);
                curl_setopt($ch, CURLOPT_POST, true);
                curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($body));
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_HTTPHEADER, [
                    "Content-Type: application/x-www-form-urlencoded"
                ]);
                $respon = curl_exec($ch);
                $responsecurlemail = json_decode($respon, true);
                curl_close($ch);

                if ($responsecurlemail['metaData']['code'] == 200) {
                    $json['responCode'] = "00";
                    $json['responHead'] = "success";
                    $json['responDesc'] = "Dokumen dikirim ke email";
                } else {
                    $json['responCode'] = "01";
                    $json['responHead'] = "info";
                    $json['responDesc'] = "email tidak terkirim";
                }
            } else {
                /* curl tte */
                $url = ESIGN_API_BASE_URL . '/radiologi/creatette';
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $url);
                curl_setopt($ch, CURLOPT_POST, true);
                curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($body));
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_HTTPHEADER, [
                    "Content-Type: application/x-www-form-urlencoded"
                ]);
                $respon = curl_exec($ch);
				$responsecurltte = json_decode($respon, true);
				curl_close($ch);

                if ($responsecurltte['metaData']['code'] == 200) {
                    $this->md->updateEsign($pasienid, $tglkunjungan);

                    /* curl kirim email */
                    $url = "https://rsudpasarminggu.jakarta.go.id/kirimemail/hasilrad";
                    $ch = curl_init();
                    curl_setopt($ch, CURLOPT_URL, $url);
                    curl_setopt($ch, CURLOPT_POST, true);
                    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($body));
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                    curl_setopt($ch, CURLOPT_HTTPHEADER, [
                        "Content-Type: application/x-www-form-urlencoded"
                    ]);
                    $respon = curl_exec($ch);
                    $responsecurlemail = json_decode($respon, true);
                    curl_close($ch);

                    if ($responsecurlemail['metaData']['code'] == 200) {
                        $json['responCode'] = "00";
                        $json['responHead'] = "success";
                        $json['responDesc'] = "Dokumen dikirim ke email";
                    } else {
                        $json['responCode'] = "01";
                        $json['responHead'] = "info";
                        $json['responDesc'] = "email tidak terkirim";
                    }
                } else {
                    $json['responCode'] = "01";
                    $json['responHead'] = "info";
                    $json['responDesc'] = "dokumen gagal di tandatangani <br>".$responsecurltte['metaData']['message'];
                }
            }
        } else {
            $json['responCode'] = "01";
            $json['responHead'] = "info";
            $json['responDesc'] = "Data Tidak Lengkap, <br>Silahkan lengkapi data ke bagian pendaftaran";
        }

        echo json_encode($json);
    }

    function readpdf()
    {
//        $norm = '297989';
//        $tgllahir = convertDateTime(str_replace('/','-', '23/08/1969'), 'userDate');
//        $tglkunjungan = convertDateTime(str_replace('/','-', '03/12/2023'), 'userDate');

//        $norm = '356525';
//        $tgllahir = convertDateTime(str_replace('/','-', '15/12/1961'), 'userDate');
//        $tglkunjungan = convertDateTime(str_replace('/','-', '07/07/2024'), 'userDate');

        $norm = '3174041712910009';
        $tgllahir = convertDateTime(str_replace('/','-', '17/12/1991'), 'userDate');
        $tglkunjungan = convertDateTime(str_replace('/','-', '21/10/2024'), 'userDate');

//        $norm = '403956';
//        $tgllahir = convertDateTime(str_replace('/','-', '25/04/1978'), 'userDate');
//        $tglkunjungan = convertDateTime(str_replace('/','-', '11/03/2025'), 'userDate');

        $ceknorm = $this->md->ceknorm($norm);
        if(!empty($ceknorm)){
            $norm = $ceknorm->INT_PASIEN_ID;
            $cektgllahir = $this->md->cektgllahir($norm, $tgllahir);
            if(!empty($cektgllahir)){
//                if($cektgllahir->EMAIL != ''){
//                    if (filter_var($cektgllahir->EMAIL, FILTER_VALIDATE_EMAIL)) {
                        $pasienID = $cektgllahir->PASIEN_ID;
                        $detailPasien = $this->md->getPasienRadDetail($pasienID, $tglkunjungan);

                        if (!empty($detailPasien)) {
                            $episodeId = $detailPasien['EPISODE_ID'];
                            $transID = $detailPasien['TRANS_ID'];
                            $transCO = $detailPasien['TRANS_CO'];
                            $TANGGAL_RAD = date('Y-m-d', strtotime($detailPasien['TANGGAL']));


                            if($TANGGAL_RAD >= '2024-08-01'){
                                $episodeId = $detailPasien['EPISODE_ID'];
                                $transCO = $detailPasien['TRANS_CO'];

                                $detailRad = $this->md->getDetailBaru($pasienID, $episodeId, $transCO);
                                $detailRis = $this->md->getRisBaru($norm, $episodeId);

                                $data = [
                                    'pasien' => $detailPasien,
                                    'rad' => $detailRad,
                                    'ris' => $detailRis,
                                ];

                                /* create file pdf */
                                $this->load->library('pdf');
                                $html = $this->load->view('v_detailrad', $data, true);
                                $pdf = new Pdf();

                                $pdf->SetCreator(PDF_CREATOR);
                                $pdf->SetAuthor('RSUD Pasar Minggu');
                                $pdf->SetTitle('RSUD Pasar Minggu');
                                $pdf->SetSubject('Laporan Radiologi '.$detailPasien['NAMA_PASIEN']);

                                $pdf->SetMargins(5, 15, 8);
                                $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
                                $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
                                $pdf->SetAutoPageBreak(TRUE, 40);
                                $pdf->AddPage();

                                $pdf->writeHTML($html, true, false, true, false, '');

                                $pdfpath = FCPATH . '../scanrm/FILESCAN/'.implode('/', str_split($detailPasien['PASIEN_ID'], 2)).'/';
                                if (!is_dir($pdfpath)) {
                                    mkdir($pdfpath, 0777, true);
                                }
                                $pdfname = $detailPasien['PASIEN_ID'].'_0000R_'.str_replace('.','',$detailPasien['TANGGAL']).'.pdf';
                                $pdf->Output($pdfpath.$pdfname, 'I');   /* F untuk buat file, I tampil di browser */
                                /* end create file pdf */

                                $cekDokumen = $this->md->getDocument($pasienID, $tglkunjungan);
                                if(empty($cekDokumen)){
                                    /* save ke table document */
                                    $PASIEN_ID = $detailPasien['PASIEN_ID'];
                                    $DIR_FILE = implode('\\', str_split($detailPasien['PASIEN_ID'], 2));
                                    $NM_FILE = $pdfname;
                                    $CREATED_DATE = date('d.m.Y H:i:s');
                                    $CREATED_BY = 'PASIEN';
                                    $TANGGAL = $detailPasien['TANGGAL'];
                                    $this->md->addDocument($PASIEN_ID, $DIR_FILE, $NM_FILE, $CREATED_DATE, $CREATED_BY, $TANGGAL);
                                    /* end ke table document */
                                }else{
                                    $this->md->reUpdateEsign($pasienID, $tglkunjungan);
                                }

                                $email = $cektgllahir->EMAIL;
                                $atPos = strpos($email, '@');
                                if ($atPos > 4) {
                                    $emailMasked = substr_replace($email, '****', $atPos - 4, 4);
                                } else {
                                    $emailMasked = $email;
                                }

                                $json['responCode']="00";
                                $json['responHead']="success";
                                $json['responDesc'] = "Apakah Anda ingin mengirimkan hasil radiologi ke email berikut: ". $emailMasked ."?";
                            }else{
                                $json['responDesc']="Tanggal kunjungan sebelum 01-08-2024 tidak dapat ditampilkan";
                            }
                        } else {
                            $json['responDesc']="Data Tidak Di Temukan, <br>pada tanggal kunjungan ".$tglkunjungan;
                        }
//                    } else {
//                        $json['responDesc']="Email Tidak Valid, <br>Silahkan perbaiki data ke bagian pendaftaran";
//                    }
//                }else{
//                    $json['responDesc']="Email belum ada, <br>Silahkan lengkapi data ke bagian pendaftaran";
//                }
            }else{
                $json['responDesc']="Data Tidak Lengkap, <br>Silahkan lengkapi data ke bagian pendaftaran";
            }
        }else{
            $json['responDesc']="No Rekam Medik atau NIK KTP ". $norm ." Tidak Di Temukan, <br>Silahkan ke bagian pendaftaran";
        }

        echo json_encode($json);
    }
}
?>
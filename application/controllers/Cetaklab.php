<?php

include FCPATH."plugins/phpqrcode/qrlib.php";

class Cetaklab extends CI_Controller
{

    public function __construct()
    {
        parent:: __construct();
        error_reporting(0);
        ini_set('display_errors', 0);
		
        $this->load->model("Modelcetaklab","md");
        // error_reporting(E_ALL);
        // ini_set('display_errors', 1);
    }

    public function index()
    {
        $data["title"]       = "DIGITAL INFORMASI RSUD PASAR MINGGU";
        $this->template->load("v_template","v_cetaklab",$data);
    }

    function cekpasien(){
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
                        $pasienid = $cektgllahir->PASIEN_ID;
                        $labResult = $this->md->getAll($pasienid, $tglkunjungan);

                        if(!empty($labResult)){
                            $result = [];
                            for ($i = 0; $i < count($labResult); $i++) {
                                $isCetak = $this->md->isCetak($labResult[$i]['EPISODE_ID'], $labResult[$i]['SAMPEL_ID'], $labResult[$i]['POLI_ID']);
                                if ($isCetak) {
                                    $result = [];
                                } else {
                                    $result[$i] = $labResult[$i];
                                    $detailLab[$i] = $this->md->getDetailLab($norm, $labResult[$i]['SAMPEL_ID']);
                                    $detailPemeriksa[$i] = $this->md->getDetailLabPemeriksa($labResult[$i]['SAMPEL_ID']);
                                    $detailPasien[$i] = $this->md->getPasienLabDetail($pasienid, $labResult[$i]['SAMPEL_ID']);
                                }
                            }

                            if(!empty($result)){
                                if (!empty($detailLab) && !empty($detailPasien) && !empty($detailPemeriksa)) {
                                    $whNoLab = $this->md->getWh("LABFORM01");
                                    $dokterPJ = $this->md->getWhLab("1712")['VALUE'];
                                    $dokterMikro = $this->md->getWhLab("1714")['VALUE'];


                                    $data = [
                                        'pasien' => $detailPasien,
                                        'lab' => $detailLab,
                                        'infoLab' => [
                                            'dokterPJ' => $dokterPJ,
                                            'dokterMikro' => $dokterMikro,
                                            'nomorLab' => $detailPasien[0]['SAMPEL_ID'] . " / " . $whNoLab['VALUE'] . " / " . $detailPasien[0]['TAHUN_AMBIL']
                                        ],
                                        'infoPemeriksa' => $detailPemeriksa,
                                    ];

                                    /* create file pdf */ 
									ini_set('max_execution_time', 300);
									
                                    $this->load->library('pdf');
                                    $html = $this->load->view('v_detaillab', $data, true);
                                    $pdf = new Pdf(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

                                    $value = "https://rsudpasarminggu.jakarta.go.id/barcode/lab/".$detailPasien[0]['PASIEN_ID'].'/'.str_replace(".", "-", $detailPasien[0]['TGL_MASUK']);
                                    $errorCorrectionLevel = "H";
                                    $matrixPointSize = 10;
                                    $tempdir = FCPATH . "dist/qrpasien/";
                                    $filename = $tempdir . 'qrcode.png';
                                    $pngAbsoluteFilePath = $filename;
                                    if (!file_exists($pngAbsoluteFilePath)) {
                                        QRcode::png($value, $filename, $errorCorrectionLevel, $matrixPointSize, 2);
                                    }
                                    $pdf->setFooterImage($filename);

                                    $pdf->SetCreator(PDF_CREATOR);
                                    $pdf->SetAuthor('RSUD Pasar Minggu');
                                    $pdf->SetTitle('RSUD Pasar Minggu');
                                    $pdf->SetSubject('Laporan Lab '.$detailPasien[0]['NAMA_PASIEN']);

                                    $pdf->SetMargins(5, 30, 5);
                                    $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
                                    $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
                                    $pdf->SetAutoPageBreak(TRUE, 60);
                                    $pdf->AddPage();

                                    $pdf->writeHTML($html, true, false, true, false, '');

                                    $pdfpath = FCPATH . '../scanrm/FILESCAN/'.implode('/', str_split($detailPasien[0]['PASIEN_ID'], 2)).'/';
                                    if (!is_dir($pdfpath)) {
                                        mkdir($pdfpath, 0777, true);
                                    }
                                    $pdfname = $detailPasien[0]['PASIEN_ID'].'_0000G_'.str_replace('.','',$detailPasien[0]['TGL_MASUK']).'.pdf';
                                    $pdf->Output($pdfpath.$pdfname, 'F');   /* F untuk buat file, I tampil di browser */
                                    unlink($filename);
                                    /* end create file pdf */

                                    $cekDokumen = $this->md->getDocument($pasienid, $tglkunjungan);
                                    if(empty($cekDokumen)){
                                        /* save ke table document */
                                        $PASIEN_ID = $detailPasien[0]['PASIEN_ID'];
                                        $DIR_FILE = implode('\\', str_split($detailPasien[0]['PASIEN_ID'], 2));
                                        $NM_FILE = $pdfname;
                                        $CREATED_DATE = date('d.m.Y H:i:s');
                                        $CREATED_BY = 'PASIEN';
                                        $TANGGAL = $detailPasien[0]['TGL_MASUK'];
                                        $this->md->addDocument($PASIEN_ID, $DIR_FILE, $NM_FILE, $CREATED_DATE, $CREATED_BY, $TANGGAL);
                                        /* end ke table document */
                                    }else{
                                        $this->md->reUpdateEsign($pasienid, $tglkunjungan);
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
                                    $json['responDesc'] = "Apakah Anda ingin mengirimkan hasil lab ke email berikut: ". $emailMasked ."?";
                                }else{
                                    $json['responCode']="01";
                                    $json['responHead']="info";
                                    $json['responDesc']="Hasil belum keluar, Masih dalam proses laboratorium";
                                }
                            }else{
                                $json['responCode']="01";
                                $json['responHead']="info";
                                $json['responDesc']="Hasil belum keluar, Masih dalam proses laboratorium";
                            }
                        }else{
                            $json['responCode']="01";
                            $json['responHead']="info";
                            $json['responDesc']="Data Tidak Di Temukan, <br>pada tanggal kunjungan ".$tglkunjungan;
                        }
                    } else {
                        $json['responCode']="01";
                        $json['responHead']="info";
                        $json['responDesc']="Email Tidak Valid, <br>Silahkan perbaiki data ke bagian pendaftaran";
                    }
                }else{
                    $json['responCode']="01";
                    $json['responHead']="info";
                    $json['responDesc']="Email belum ada, <br>Silahkan lengkapi data ke bagian pendaftaran";
                }
            }else{
                $json['responCode']="01";
                $json['responHead']="info";
                $json['responDesc']="Data Tidak Lengkap, <br>Silahkan lengkapi data ke bagian pendaftaran";
            }
        }else{
            $json['responCode']="01";
            $json['responHead']="info";
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
                $url = "https://rsudpasarminggu.jakarta.go.id/kirimemail/hasillab";
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
                $url = ESIGN_API_BASE_URL . '/laboratorium/creatette';
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
//                $responsecurltte = array(
//                    'metaData' => array(
//                        'code' => 200,
//                    )
//                );

                if ($responsecurltte['metaData']['code'] == 200) {
                    $this->md->updateEsign($pasienid, $tglkunjungan);

                    /* curl kirim email */
                    $url = "https://rsudpasarminggu.jakarta.go.id/kirimemail/hasillab";
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
                    $json['responDesc'] = "dokumen gagal di tandatangani";
                }
            }
        } else {
            $json['responCode'] = "01";
            $json['responHead'] = "info";
            $json['responDesc'] = "Data Tidak Lengkap, <br>Silahkan lengkapi data ke bagian pendaftaran";
        }

        echo json_encode($json);
    }
}
?>
<?php
class Checkinkiosk extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model("Modelcheckinkiosk", "md");
    }

    public function index()
    {
        $data["title"]       = "SERIOSA SELF SERVICE KIOSK";
        $this->template->load("v_template", "v_checkinkiosk", $data);
    }

    public function getpesertabynoka()
    {
        $noka = $this->input->post('noka');
        $response = execCurlWithToken([
            'url'        => RSUDPM_API_BASE_URL . '/bpjs-v2/cek-peserta',
            'method'     => 'post',
            'resultType' => 'json',
            'body'       => json_encode([
                'no_kartu' => $noka,
            ]),
            'preloadConfig' => [
                'contentType' => 'json'
            ],
            'showRawResponse' => true,
        ]);
        if ($response['success']) {
            if ($response['result']['metadata']['responCode'] == '00') {
                $retVal['success'] = true;
                $retVal['data'] = $response['result']['response'];
            } else {
                $retVal['message'] = $response['result']['metadata']['responDesc'];
                $retVal['rawResponse'] = $response['rawResponse'];
            }
        } else {
            $retVal['message'] = 'Gagal mendapatkan data dari server. Coba beberapa saat lagi.';
            $retVal['rawResponse'] = $response['rawResponse'];
        }
        echo json_encode($retVal);
    }

    public function cekgeneralconsent()
    {
        $kodebooking      = $this->input->post("kodebookingcheckin");
        if ($kodebooking != "") {
            $checkbooking = $this->md->checkbooking($kodebooking);
            if (!empty($checkbooking)) {
                if (!in_array($checkbooking->POLI_ID, ['POLI0000000035'])) {
                    $pasienid = $checkbooking->PASIEN_ID;
                    $datapasien = $this->md->getpasien($pasienid);
                    if ($datapasien->ADACONCENT > 0 && $datapasien->ACC_SATUSEHAT == 'Y' and $datapasien->ACC_SATUSEHAT_FASKES == 'Y') {
                        $json["responCode"] = "00";
                        $json["responHead"] = "success";
                        $json["responDesc"] = "SUDAH ADA CONSENT";
                    } else {
                        $json["responCode"] = "99";
                        $json["responHead"] = "error";
                        $json["responDesc"] = "BELUM ADA CONSENT";
                        $json["responData"] = $datapasien;
                    }
                } else {
                    $json["responCode"] = "00";
                    $json["responHead"] = "success";
                    $json["responDesc"] = "SUDAH ADA CONSENT";
                }
            } else {
                $json["responCode"] = "01";
                $json["responHead"] = "error";
                $json["responDesc"] = "INFORMASI";
                $json["responHtml"] = "<h5 style='color:#234974;'>Kode Booking tidak ditemukan</h5>";
            }
        } else {
            $json["responCode"] = "01";
            $json["responHead"] = "error";
            $json["responDesc"] = "INFORMASI";
            $json["responHtml"] = "<h5 style='color:#234974;'>Silakan Masukan </br><b>Kode Booking atau No RM atau No BPJS atau No Rujukan</b><br> Terlebih Dahulu</h5>";
        }

        echo json_encode($json);
    }
    public function checkin()
    {
        $kodebooking = $this->input->post("kodebookingcheckin");

        if ($kodebooking != "") {
            $checkbooking = $this->md->checkbooking($kodebooking);

            if (!empty($checkbooking)) {
                //CEK CONSENT
                $pasienid = $checkbooking->PASIEN_ID;
                /*  $datapasien = $this->md->getpasien($pasienid);

                    if ($datapasien->ADACONSENT > 0) { */
                if ($checkbooking->AKTIF === "1") {
                    if ($checkbooking->STATUS === "0") {
                        $json["responCode"] = "01";
                        $json["responHead"] = "error";
                        $json["responDesc"] = "INFORMASI";
                        $json["responHtml"] = "<h4 style='color:#234974;'><b>{$checkbooking->NAMAPASIEN}<br>$kodebooking<br>SUDAH TIDAK BERLAKU<br>TANGGAL KUNJUNGAN : $checkbooking->TGLKUNJUNGAN</b></h4>" . "<br><br>" . "<h5 style='color:#234974;'>Silakan Masukan Kembali</br><b>Kode Booking, No BPJS dan atau No Rujukan</b></h5>";
                    } else {
                        if ($checkbooking->STATUS === "1") {
                            $json["responCode"] = "01";
                            $json["responHead"] = "error";
                            $json["responDesc"] = "INFORMASI";
                            $json["responHtml"] = "<h4 style='color:#234974;'><b>{$checkbooking->NAMAPASIEN}<br>$kodebooking<br>BERLAKU PADA TANGGAL :$checkbooking->TGLKUNJUNGAN</b></h4>" . "<br><br>" . "<h5 style='color:#234974;'>Silakan Datang Kembali Pada Tanggal</br><b>$checkbooking->TGLKUNJUNGAN</b></h5>";
                        } else {
                            if ($checkbooking->HADIR === "T") {
                                if ($checkbooking->STATUSCHECKIN === "0") {
                                    $json["responCode"] = "01";
                                    $json["responHead"] = "error";
                                    $json["responDesc"] = "GAGAL VERIFIKASI KEDATANGAN";
                                    $json["responHtml"] = "<h4 style='color:#234974;'><b>{$checkbooking->NAMAPASIEN}<br>$kodebooking<br>$checkbooking->URUT [ $checkbooking->JAM_MULAI - $checkbooking->JAM_SELESAI ]<br>SILAKAN MELAKUKAN CHECK IN<br>15 Menit Sebelum Jam Kedatangan<br>$checkbooking->JAMMINIMUMCHECKIN</b></h4>" . "<br><br>" . "<h5 style='color:#234974;'>Jam Saat Ini</br><b>$checkbooking->JAMSAATINI</b></h5>";
                                } else {
									$isPranap = strpos($checkbooking->CREATED_BY, 'PRANAP') !== false;
									$isResume = $checkbooking->CREATED_BY === "RESUME-KONTROL";
                                    $isPoli   = in_array($checkbooking->POLI_ID, ['POLI1','POLI0000000003','POLI0000000010']);

									if (($isPranap || $isResume) && $isPoli) {
                                        $img = '
                                            <style>
                                            #imgAntrian {
                                                width: 50%;
                                            }
                                            </style>
                                        
                                            <div class="text-center">
                                                <img id="imgAntrian" src="'. base_url('dist/img/logo-mjkn.png') .'" class="rounded">
                                            </div>
                                        ';

                                        $json["responCode"] = "01";
                                        $json["responHead"] = "info";
                                        $json["responDesc"] = "INFORMASI";
                                        $json["responHtml"] = $img."<br><h2 style='color:#234974;'>Silahkan Verifikasi di mobile JKN atau ke bagian pendaftaran BPJS</h2>";
                                    }else{ 
										$data['HADIR'] = "Y";
										if ($this->md->updatekehadiran($checkbooking->BOOKING_ID, $checkbooking->PASIEN_ID, $checkbooking->EPISODE_ID, $data)) {
											$json["responCode"] = "00";
											$json["responHead"] = "success";
											$json["responDesc"] = "INFORMASI";
											$json["responHtml"] = "<h4 style='color:#234974;'><b>{$checkbooking->NAMAPASIEN}<br>$kodebooking<br>PROSES VERIFIKASI BERHASIL</b></h4>" . "<br><br>" . "<h5 style='color:#234974;'>Silakan Menuju Nurse Station</br><b>$checkbooking->POLITUJUAN</b></h5>";
										} else {
											$json["responCode"] = "01";
											$json["responHead"] = "info";
											$json["responDesc"] = "INFORMASI";
											$json["responHtml"] = "<h4 style='color:#234974;'>Mohon Maaf Kami Sedang Mengalami Kendala Teknis Silakan Hubungi Petugas Kami</h4>";
										} 
									}
                                }
                            } else {
								// $isPranap = strpos($checkbooking->CREATED_BY, 'PRANAP') !== false;
								// $isResume = $checkbooking->CREATED_BY === "RESUME-KONTROL";
								// $isPoli   = in_array($checkbooking->POLI_ID, ['POLI1','POLI0000000003','POLI0000000004']);

								// if (($isPranap || $isResume) && $isPoli) {
									// $img = '
										// <style>
										// #imgAntrian {
											// width: 50%;
										// }
										// </style>
									
										// <div class="text-center">
											// <img id="imgAntrian" src="'. base_url('dist/img/logo-mjkn.png') .'" class="rounded">
										// </div>
									// ';

									// $json["responCode"] = "01";
									// $json["responHead"] = "info";
									// $json["responDesc"] = "INFORMASI";
									// $json["responHtml"] = $img."<br><h2 style='color:#234974;'>Silahkan Verifikasi di mobile JKN atau ke bagian pendaftaran BPJS</h2>";
								// }else{ 
									$json["responCode"] = "00";
									$json["responHead"] = "success";
									$json["responDesc"] = "INFORMASI";
									$json["responHtml"] = "<h4 style='color:#234974;'><b>{$checkbooking->NAMAPASIEN}<br>$kodebooking<br>PROSES VERIFIKASI BERHASIL<br>$checkbooking->TGLHADIR</b></h4>" . "<br><br>" . "<h5 style='color:#234974;'>Silakan Menuju Nurse Station</br><b>$checkbooking->POLITUJUAN</b></h5>";
								// }
								
                                // $json["responCode"]="00";
                                // $json["responHead"]="info";
                                // $json["responDesc"]="INFORMASI";
                                // $json["responHtml"]="<h4 style='color:#234974;'><b>$kodebooking<br>ANDA SUDAH VERIFIKASI KEDATANGAN<br>$checkbooking->TGLHADIR</b></h4>"."<br><br>"."<h5 style='color:#234974;'>Silakan Menuju Nurse Station</br><b>$checkbooking->POLITUJUAN</b></h5>";
                            }
                        }
                    }
                } else {
                    $json["responCode"] = "01";
                    $json["responHead"] = "error";
                    $json["responDesc"] = "INFORMASI";
                    $json["responHtml"] = "<h4 style='color:#234974;'><b>{$checkbooking->NAMAPASIEN}<br>$kodebooking<br>SUDAH DI BATALKAN</b></h4>" . "<br><br>" . "<h5 style='color:#234974;'>Silakan Masukan Kembali</br><b>Kode Booking, No BPJS dan atau No Rujukan</b></h5>";
                }
                /*  } else {

                        $json["responCode"] = "99";
                        $json["responData"] = $datapasien;
                    } */
            } else {
                $json["responCode"] = "01";
                $json["responHead"] = "error";
                $json["responDesc"] = "INFORMASI";
                $json["responHtml"] = "<h5 style='color:#234974;'>Kode Booking tidak ditemukan</h5>";
            }
        } else {
            $json["responCode"] = "01";
            $json["responHead"] = "error";
            $json["responDesc"] = "INFORMASI";
            $json["responHtml"] = "<h5 style='color:#234974;'>Silakan Masukan </br><b>Kode Booking atau No RM atau No BPJS atau No Rujukan</b><br> Terlebih Dahulu</h5>";
        }

        echo json_encode($json);
    }
    public function simpanconcent()
    {
        $updatenik = $this->input->post('updatenik');
        $acc_1 = $this->input->post('setuju_1') ? $this->input->post('setuju_1') : 'N';
        $acc_2 = $this->input->post('setuju_2') ? $this->input->post('setuju_2') : 'N';
        $acc_3 = $this->input->post('setuju_3') ? $this->input->post('setuju_3') : 'N';
        if ($updatenik) {
            $updatenik = $this->md->update('SR01_GEN_PASIEN_MS', ['NO_IDENTITAS' => $this->input->post('nik')], ['PASIEN_ID' => $this->input->post('pasienid')]);
        }
        $idconcent = $this->md->kodeoto('SR01_GEN_PASIEN_CONCENT', 'ID_CONCENT', 5, date('Ymd'));
        $hubunganpasien = [
            'keluarga_inti' => $this->input->post('keluarga_inti') ?  $this->input->post('keluarga_inti') : '',
            'penjamin_asuransi' => $this->input->post('penjamin_asuransi') ? $this->input->post('penjamin_asuransi') : '',
            'wali' => $this->input->post('wali') ?  $this->input->post('wali') : ''
        ];
        $nilaikepercayaan = [
            'tdk_makan_daging_sapi' => $this->input->post('tdk_makan_daging_sapi') ?  $this->input->post('tdk_makan_daging_sapi') : '',
            'tdk_menggunakan_obat_unsur_babi' => $this->input->post('tdk_menggunakan_obat_unsur_babi') ? $this->input->post('tdk_menggunakan_obat_unsur_babi') : '',
            'tdk_mau_imunisasi' => $this->input->post('tdk_mau_imunisasi') ? $this->input->post('tdk_mau_imunisasi') : '',
            'perlakuan_khusus_meninggal' => $this->input->post('perlakuan_khusus_meninggal') ? $this->input->post('perlakuan_khusus_meninggal') : ''
        ];
        $signature = $this->input->post('signature');

        $insertconcent = '';
        if ($signature) {
            /*  $base64_image = preg_replace('/^data:image\/(png|jpg|jpeg);base64,/', '', $signature);
                $image_data = base64_decode($base64_image); */
            $encoded_image = $signature;
            $image_data = base64_decode($encoded_image);
            header("Content-length: " . strlen($image_data));
            $filename = $this->input->post('pasienid') . '.png';
            $upload_path = './dist/uploads/ttd/';

            if (file_put_contents($upload_path . $filename, $image_data)) {
                //cek CONCENT
                $cekconcent = $this->md->getconcent($this->input->post('pasienid'));
                if (empty($cekconcent)) {
                    $datainput = [
                        'ID_CONCENT' => $idconcent,
                        'PASIEN_ID' => $this->input->post('pasienid'),
                        'HUBUNGAN_PASIEN' => json_encode($hubunganpasien),
                        'NILAI_KEPERCAYAAN' => json_encode($nilaikepercayaan),
                        'ACC_SATUSEHAT' => $acc_1,
                        'ACC_SATUSEHAT_FASKES' => $acc_2,
                        'TTD' => $filename
                    ];
                    $insertconcent = $this->md->insert('SR01_GEN_PASIEN_CONCENT', $datainput);
                } else {
                    $dataupdate = [
                        'HUBUNGAN_PASIEN' => json_encode($hubunganpasien),
                        'NILAI_KEPERCAYAAN' => json_encode($nilaikepercayaan),
                        'ACC_SATUSEHAT' => $acc_1,
                        'ACC_SATUSEHAT_FASKES' => $acc_2,
                        'TTD' => $filename
                    ];
                    $insertconcent = $this->md->update('SR01_GEN_PASIEN_CONCENT', $dataupdate, ['PASIEN_ID' => $this->input->post('pasienid')]);
                }
            }
        }
        if ($insertconcent) {
            $updateaccsatset = $this->md->update('SR01_GEN_PASIEN_MS', ['ACC_SATUSEHAT' => $acc_1, 'ACC_SATUSEHAT_FASKES' => $acc_2, 'ACC_CONCENT' => $acc_3], ['PASIEN_ID' => $this->input->post('pasienid')]);
            $json["responCode"] = "00";
            $json["responHead"] = "success";
            $json["responDesc"] = "BERHASIL SIMPAN CONCENT";
        } else {
            $json["responCode"] = "01";
            $json["responHead"] = "error";
            $json["responDesc"] = "GAGAL SIMPAN CONCENT";
        }
        echo json_encode($json);
    }
}

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <?php
    include_once("inc/link.php");
    ?>

    <style>
        .custom-shape-divider-bottom-1672728346 {
            position: fixed;
            bottom: 0;
            left: 0;
            width: 100%;
            overflow: hidden;
            line-height: 0;
            transform: rotate(180deg);
        }

        .custom-shape-divider-bottom-1672728346 svg {
            position: relative;
            display: block;
            width: calc(127% + 1.3px);
            height: 255px;
        }

        .custom-shape-divider-bottom-1672728346 .shape-fill {
            /* fill: #FFFFFF; */
            fill: #234974;
        }
    </style>
</head>

<body class="hold-transition layout-top-nav layout-footer-fixed">

    <div class="wrapper">
        <div class="content-wrapper" id="viewapps">
            <section class="content-header">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-6 d-flex align-middle">
                            <div class="row">
                                <div class="col-md-12">
                                    <h1>RSUD PASAR MINGGU</h1>
                                </div>
                                <div class="col-md-12">
                                    <h6>SISTEM INFORMASI MANAJEMEN RUMAH SAKIT</h2>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="d-flex justify-content-end align-items-center">
                                <img class="rounded pl-2 pr-2" src="<?php echo base_url(); ?>dist/img/logo.png" height="55" width="250">
                                <img class="rounded ml-2" src="<?php echo base_url(); ?>dist/img/bsre.png" height="60" width="150">
                                <img class="ml-2" src="<?php echo base_url(); ?>dist/img/pse.png" height="60" width="180">
                                <img class="ml-2" src="<?php echo base_url(); ?>dist/img/bssn.png" height="70" width="70">
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            <div class="modal fade" id="modalconcent" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
                    <div class="modal-content">
                        <div class="modal-header p-0 border-bottom-0">
                            <!--  <h5 class="modal-title" id="exampleModalLabel"></h5> -->
                            <table class="table table-bordered m-0">
                                <thead>
                                    <tr>
                                        <th width="220" class="text-center align-middle">
                                            <img src="<?= base_url('dist/img/favicon.png') ?>" style="width:120px;" />
                                        </th>
                                        <th class="text-center align-middle" width="400">
                                            <h3>FORMULIR GENERAL CONSENT RAWAT JALAN</h3>
                                            <b>(026/FORM/RM/I/2023) Rev. 01</b>
                                        </th>
                                        <th class="align-middle">
                                            <div>Nama Pasien : <span id="namapasien" class="fw-normal"></span></div>
                                            <div>No RM : <span id="norm" class="fw-normal"></span></div>
                                            <div>Tanggal Lahir : <span id="tgllahir" class="fw-normal"></span></div>
                                            <div>NIK : <span id="nik" class="fw-normal"></span></div>
                                        </th>
                                    </tr>

                                </thead>
                            </table>
                            <!--     <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button> -->
                        </div>
                        <div class="modal-body p-0 text-justify">
                            <div class="py-2 px-4 text-right" style="position: sticky;top:0;background:#eeeeee;z-index:1;" id="div-detail-consent">
                                <a href="javascript:void(0);" onclick="detailconsent();" id="detail-consent">Lihat General Consent</a>
                            </div>
                            <table class="table table-bordered" id="tableconsent">
                                <tbody>
                                    <tr>
                                        <th width="220" class="align-middle text-center">Materi Penjelasan</th>
                                        <th colspan="2" class="align-middle text-center">Isi Materi</th>
                                    </tr>
                                    <tr>
                                        <th width="220" class="align-middle text-center">Penerima Penjelasan</th>
                                        <td>
                                            <div class="row">
                                                <div class="form-group row mb-1">
                                                    <label class="col-sm-4 font-bold mb-1">Nama</label>
                                                    <div class="col-sm-8">
                                                        : <span id="namapasien"></span>
                                                    </div>
                                                </div>
                                                <div class="form-group row mb-1">
                                                    <label class="col-sm-4 font-bold mb-1">Tempat / Tgl Lahir</label>
                                                    <div class="col-sm-8">
                                                        : <span id="ttl"></span>
                                                    </div>
                                                </div>
                                                <div class="form-group row mb-1">
                                                    <label class="col-sm-4 font-bold mb-1">NIK</label>
                                                    <div class="col-sm-8">
                                                        : <span id="nik"></span>
                                                    </div>
                                                </div>
                                                <div class="form-group row mb-1">
                                                    <label class="col-sm-4 font-bold mb-1">Alamat</label>
                                                    <div class="col-sm-8">
                                                        : <span id="alamat"></span>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th class="align-middle text-center">Informasi Tentang Hak dan Kewajiban Pasien</th>
                                        <td>Saya telah mendapatkan penjelasan dari petugas mengenai hak dan kewajiban sebagai pasien/ keluarga pasien sebagaimana yang tercantum dalam PERMENKES N0. 4 Tahun 2018 pasal 17 dan pasal 26.</td>
                                    </tr>
                                    <tr>
                                        <th class="align-middle text-center">Persetujuan Pengobatan dan Perawatan</th>
                                        <td>
                                            <p>
                                                Saya mengijinkan dokter, perawat, dan profesional kesehatan lainnya, untuk melakukan prosedur diagnostik dan untuk memberikan pengobatan medis seperti yang diperlukan dalam penilaian profesional mereka. Prosedur diagnostik dan perawatan seperti pemasangan infus, pemasangan selang makan, pemasangan selang kencing , skin test, (kecuali yang membutuhkan persetujuan khusus/ tertulis), dan pengambilan darah untuk pemeriksaan laboratorium atau pemeriksaan patologi yang dibutuhkan untuk pengobatan dan tindakan yang aman.
                                            </p>
                                            <p>
                                                Semua tindakan yang bertujuan untuk menyelamatkan jiwa pasien atau menghindarkan kecacatan pada pasien diruang gawat darurat, seperti: resusitasi jantung paru saat kondisi pasien gagal napas atau dan gagal jantung sebelum menandatangani pernyataan persetujuan tindakan medis, pemasangan endotracheal tube pada saat kondisi pasien gagal napas, gagal jantung, semua tindakan pembebasan jalan napas ( heimlich manuver), penatalaksanaan syock anafilaktik, pemasangan kateter urin.
                                            </p>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th class="align-middle text-center">Pelepasan Informasi/ Kerahasiaan Informasi</th>
                                        <td>
                                            <div>
                                                <ol class="ps-3 mb-0">
                                                    <li>Saya memahami informasi yang ada di diri saya akan di jaga kerahasiannya.</li>
                                                    <li>Saya memberi wewenang kepada rumah sakit untuk memberikan informasi tentang diagnosis, hasil pelayanan, dan pengobatan bila diperlukan untuk memproses klaim asuransi/ perusahaan, dan atau lembaga pemerintah.</li>
                                                    <li>
                                                        <p>Saya memberi wewenang kepada rumah sakit untuk memberikan informasi tentang diagnosis, hasil pelayanan dan pengobatan saya kepada anggota keluarga saya dan kepada :</p>
                                                        <div>Nama, Hubungan dengan pasien, dan No telepon</div>
                                                        <div class="form-check ">
                                                            <input class="form-check-input" type="checkbox" value="Y" id="keluarga_inti">
                                                            <label class="form-check-label" for="keluarga_inti">
                                                                Keluarga Inti (Suami/istri/anak)
                                                            </label>
                                                        </div>
                                                        <div class="form-check ">
                                                            <input class="form-check-input" type="checkbox" value="Y" id="penjamin_asuransi">
                                                            <label class="form-check-label" for="penjamin_asuransi">
                                                                Penjamin / Asuransi
                                                            </label>
                                                        </div>
                                                        <div class="form-check ">
                                                            <input class="form-check-input" type="checkbox" value="Y" id="wali">
                                                            <label class="form-check-label" for="wali">
                                                                Lain - lain (Wali)
                                                            </label>
                                                        </div>
                                                    </li>
                                                </ol>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th class="align-middle text-center">Nilai - nilai dan Kepercayaan</th>
                                        <td>
                                            <p>Saya akan menginformasikan kepada pihak rumah sakit tentang nilai nilai dan kepercayaan khusus seperti: </p>
                                            <div class="form-check ">
                                                <input class="form-check-input" type="checkbox" value="Y" id="tdk_makan_daging_sapi">
                                                <label class="form-check-label" for="tdk_makan_daging_sapi">
                                                    tidak memakan daging sapi
                                                </label>
                                            </div>
                                            <div class="form-check ">
                                                <input class="form-check-input" type="checkbox" value="Y" id="tdk_menggunakan_obat_unsur_babi">
                                                <label class="form-check-label" for="tdk_menggunakan_obat_unsur_babi">
                                                    tidak mau menggunakan obat obatan dengan unsur babi
                                                </label>
                                            </div>
                                            <div class="form-check ">
                                                <input class="form-check-input" type="checkbox" value="Y" id="tdk_mau_imunisasi">
                                                <label class="form-check-label" for="tdk_mau_imunisasi">
                                                    tidak mau imunisasi
                                                </label>
                                            </div>
                                            <!-- <div class="form-check ">
                                            <input class="form-check-input" type="checkbox" value="Y" id="perlakuan_khusus_meninggal">
                                            <label class="form-check-label" for="perlakuan_khusus_meninggal">
                                                perlakuan khusus pasien sakaratul maut/ meninggal.
                                            </label>
                                        </div> -->
                                        </td>
                                    </tr>
                                    <tr>
                                        <th class="align-middle text-center">Pelayanan Kerohanian</th>
                                        <td>
                                            Saya memahami bahwa rumah sakit akan memfasilitasi pelayanan kerohanian sesuai dengan agama dan kepercayaan pasien, dengan cara pemberian bimbingan yang disesuaikan dengan fasilitas RSUD Pasar Minggu sesuai jadwal dan sewaktu waktu (permintaan pasien/ keluarga)
                                        </td>
                                    </tr>
                                    <tr>
                                        <th class="align-middle text-center">Privasi</th>
                                        <td>
                                            Apabila saya memerlukan hal-hal yang menyangkut privasi saya sebagai pasien, saya menyampaikan kepada Staff Rumah Sakit Umum Daerah Pasar Minggu
                                        </td>
                                    </tr>
                                    <tr>
                                        <th class="align-middle text-center">Perlindungan Barang Berharga Milik Pasien</th>
                                        <td>
                                            Saya memahami bahwa rumah sakit memberikan perlindungan barang berharga milik pasien dengan kondisi khusus tertentu (pasien penurunan kesadaran yang tidak ada penanggung jawab atau keluarga) dan rumah sakit tidak bertanggungjawab atas semua kehilangan barang milik saya dan saya pribadi bertanggung jawab atas semua barang yang saya miliki.
                                            <!-- <ol class="ps-3 mb-0">
                                            <li>Saya memahami bahwa rumah sakit memberikan perlindungan barang berharga milik pasien dengan kondisi khusus tertentu (pasien penurunan kesadaran yang tidak ada penanggung jawab atau keluarga)</li>
                                            <li>Saya memahami bahwa rumah sakit tidak mengizinkan membawa barang barang berharga , seperti uang, perhiasan, buku cek, kartu kredit dan rumah sakit tidak bertanggungjawab atas semua kehilangan barang milik saya dan saya pribadi bertanggung jawab atas semua barang yang saya miliki</li>
                                            <li>Saya juga mengerti bahwa saya harus memberitahu/ menitipkan pada rumah sakit jika saya memiliki gigi palsu, kaca mata, lensa kontak, prosthetic atau barang barang lain untuk saya amankan.</li>
                                            <li>Saya memahami bahwa rumah sakit memfasilitasi dalam penyimpanan barang berharga pasien di ruang perawatan pasien, dan menjadi tanggung jawab pasien bukan rumah sakit.</li>
                                        </ol> -->
                                        </td>
                                    </tr>
                                    <tr>
                                        <th class="align-middle text-center">Peserta Didik dan Penelitian</th>
                                        <td>
                                            <p>
                                                Saya memahami bahwa RSUD Pasar Minggu menerima peserta didik dan penyelenggraan penelitian sehingga memungkinkan pasien sebagai subjek dan penelitian.
                                            </p>
                                            <p>
                                                Saya mengijinkan peserta didik dan penelitian untuk mengakses semua informasi tentang pasien yang dibutuhkan untuk kepentingan peserta didik dan penelitian.
                                            </p>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th class="align-middle text-center">Peserta Asuransi Swasta/ Pemerintah</th>
                                        <td>
                                            Saya memberikan kuasa dan meminta rumah sakit memberikan kepada pembayar asuransi swasta/ pemerintah atau apapun yang telah di identifikasi sebagai penanggung jawab atas pembayaran untuk rawat inap atau kunjungan rawat jalan baik diagnosa, hasil pemeriksaan penunjang, pengobatan dan catatan lainnya.
                                        </td>
                                    </tr>
                                    <tr>
                                        <th class="align-middle text-center">Informasi Ketentuan Pembayaran</th>
                                        <td>
                                            Saya telah dijelaskan tentang biaya:
                                            <ul>
                                                <li>untuk peserta BPJS tidak ada Iur Biaya</li>
                                                <li>Untuk Peserta Umum atau Asuransi akan dijelaskan sesuai ketentuan yang berlaku</li>
                                            </ul>

                                        </td>
                                    </tr>
                                    <tr>
                                        <th class="align-middle text-center">Pengajuan Keluhan</th>
                                        <td>
                                            Saya memahami bahwa rumah sakit memfasilitasi peyampaian keluhan terhadap pelayanan yang diberikan kepada saya selama pengobatan.
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                            <ul class="list-group">
                                <li class="list-group-item">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="Y" id="setuju_1">
                                        <label class="form-check-label" for="setuju_1">
                                            Dengan menandatangani dokumen ini , saya mengetahui dan menyetujui bahwa Fasilitas pelayanan kesehatan membuka akses dan mengirim data rekam medis kepada Kementerian Kesehatan melalui Platform SATU SEHAT.
                                        </label>
                                        <!--  <label class="invalid">Wajib Dipilih</label> -->
                                    </div>
                                </li>
                                <li class="list-group-item">
                                    <div class="form-check ">
                                        <input class="form-check-input" type="checkbox" value="Y" id="setuju_2">
                                        <label class="form-check-label" for="setuju_2">
                                            Menyetujui untuk menerima dan membuka data pasien dari Fasilitas Pelayanan Kesehatan lainnya melalui SATUSEHAT untuk kepentingan pelayanan kesehatan dan/atau rujukan.
                                        </label>
                                        <!--     <label class="invalid">Wajib Dipilih</label> -->
                                    </div>
                                </li>
                                <li class="list-group-item">
                                    <div class="form-check ">
                                        <input class="form-check-input" type="checkbox" value="Y" id="setuju_3">
                                        <label class="form-check-label" for="setuju_3">
                                            Dengan ini, saya menyatakan bahwa saya/ penanggung jawab pasien telah menerima informasi sebagaimana diatas , dengan memberi pernyataan di sebelah kanan kolom, serta telah diberi kesempatan untuk bertanya/ diskusi dan telah memahami dan menyetujui.
                                        </label>
                                        <label class="invalid">Wajib Dipilih</label>
                                    </div>
                                </li>
                            </ul>
                            <div class="p-3 text-center">
                                <h4>TTD DISINI</h4>
                                <div id="signature-pad" class="signature-pad">
                                    <div class="signature-pad-body text-center">
                                        <canvas id="signature-pad-canvas" class="bg-white" style="width: 600px; height: 400px; border:5px dashed #dc3545"></canvas>
                                    </div>
                                    <div id="notif-sign-valid" class="fw-bold"></div>

                                    <div class="signature-pad-footer text-center mt-2">
                                        <div class="signature-pad-actions">
                                            <button type="button" class="clear btn btn-danger btn-lg" data-action="clear"> <i class="mdi mdi-eraser"></i> Ulangi Tanda Tangan </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary btn-lg" data-bs-dismiss="modal">Tutup</button>
                            <button type="button" class="btn btn-primary btn-lg" id="btn-lanjut" onclick="simpanconsent()">Lanjut</button>
                        </div>
                    </div>
                </div>
            </div>
            <section class="content">
                <div class="d-flex justify-content-center">
                    <div class="row container-fluid justify-content-center">
                        <div class="row justify-content-center">
                            <h1 class="font-weight-bold text-center" style="color:#234974;">SILAKAN MASUKAN NO KODE BOOKING ANDA ATAU NO BPJS ANDA</h1>
                        </div>
                        <br>
                        <!-- <div class="row col-md-12 d-flex justify-content-center"><h2 class="text-bold" id="info" name="info"></h2></div> -->
                        <br>
                        <br>
                        <br>
                        <div class="row col-md-12">
                            <div class="container">
                                <form action="<?php echo base_url(); ?>index.php/Checkinkiosk/checkin" id="formcheckin" method="post">
                                    <input type="text" class="form-control" id="kodebookingcheckin" name="kodebookingcheckin" style="height: 100px; font-size: 350%; text-align: center; border: 10px solid #234974; border-radius:50px;" placeholder="Kode Booking Atau No BPJS" onchange="this.value = this.value.toUpperCase()" onkeyup="this.value = this.value.toUpperCase()" autocomplete="off" autofocus>
                                </form>
                            </div>
                        </div>
                        <br>
                        <br>
                        <div class="row col-md-12 mt-5">
                            <div class="container d-flex justify-content-around">
                                <div class="row col-md-12">
                                    <div class="col-md-2">
                                        <a class="btn btn-block btn-primary mt-3 mb-3 shadow-lg" style="font-size: 500%;" onclick="addToDisplay('0')">0</a>
                                    </div>
                                    <div class="col-md-2">
                                        <a class="btn btn-block btn-primary mt-3 mb-3 shadow-lg" style="font-size: 500%;" onclick="addToDisplay('1')">1</a>
                                    </div>
                                    <div class="col-md-2">
                                        <a class="btn btn-block btn-primary mt-3 mb-3 shadow-lg" style="font-size: 500%;" onclick="addToDisplay('2')">2</a>
                                    </div>
                                    <div class="col-md-2">
                                        <a class="btn btn-block btn-primary mt-3 mb-3 shadow-lg" style="font-size: 500%;" onclick="addToDisplay('3')">3</a>
                                    </div>
                                    <div class="col-md-2">
                                        <a class="btn btn-block btn-primary mt-3 mb-3 shadow-lg" style="font-size: 500%;" onclick="addToDisplay('4')">4</a>
                                    </div>
                                    <div class="col-md-2">
                                        <a class="btn btn-block btn-primary mt-3 mb-3 shadow-lg" style="font-size: 500%;" onclick="addToDisplay('5')">5</a>
                                    </div>
                                    <div class="col-md-2">
                                        <a class="btn btn-block btn-primary mt-3 mb-3 shadow-lg" style="font-size: 500%;" onclick="addToDisplay('6')">6</a>
                                    </div>
                                    <div class="col-md-2">
                                        <a class="btn btn-block btn-primary mt-3 mb-3 shadow-lg" style="font-size: 500%;" onclick="addToDisplay('7')">7</a>
                                    </div>
                                    <div class="col-md-2">
                                        <a class="btn btn-block btn-primary mt-3 mb-3 shadow-lg" style="font-size: 500%;" onclick="addToDisplay('8')">8</a>
                                    </div>
                                    <div class="col-md-2">
                                        <a class="btn btn-block btn-primary mt-3 mb-3 shadow-lg" style="font-size: 500%;" onclick="addToDisplay('9')">9</a>
                                    </div>
                                    <div class="col-md-4">
                                        <a class="btn btn-block btn-primary mt-3 mb-3 shadow-lg" style="font-size: 500%;" onclick="clearinput()">CLEAR</a>
                                    </div>
                                    <div class="col-md-12">
                                        <a class="btn btn-block btn-success mt-3 mb-3 shadow-lg" style="font-size: 500%;" onclick="$('#formcheckin').submit()">ENTER</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>

        <?php
        include_once("inc/footercontent.php");
        ?>
    </div>
    <!--  <div class="custom-shape-divider-bottom-1672728346">
        <svg data-name="Layer 1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1200 120" preserveAspectRatio="none">
            <path d="M0,0V46.29c47.79,22.2,103.59,32.17,158,28,70.36-5.37,136.33-33.31,206.8-37.5C438.64,32.43,512.34,53.67,583,72.05c69.27,18,138.3,24.88,209.4,13.08,36.15-6,69.85-17.84,104.45-29.34C989.49,25,1113-14.29,1200,52.47V0Z" opacity=".25" class="shape-fill"></path>
            <path d="M0,0V15.81C13,36.92,27.64,56.86,47.69,72.05,99.41,111.27,165,111,224.58,91.58c31.15-10.15,60.09-26.07,89.67-39.8,40.92-19,84.73-46,130.83-49.67,36.26-2.85,70.9,9.42,98.6,31.56,31.77,25.39,62.32,62,103.63,73,40.44,10.79,81.35-6.69,119.13-24.28s75.16-39,116.92-43.05c59.73-5.85,113.28,22.88,168.9,38.84,30.2,8.66,59,6.17,87.09-7.5,22.43-10.89,48-26.93,60.65-49.24V0Z" opacity=".5" class="shape-fill"></path>
            <path d="M0,0V5.63C149.93,59,314.09,71.32,475.83,42.57c43-7.64,84.23-20.12,127.61-26.46,59-8.63,112.48,12.24,165.56,35.4C827.93,77.22,886,95.24,951.2,90c86.53-7,172.46-45.71,248.8-84.81V0Z" class="shape-fill"></path>
        </svg>
    </div> -->
</body>

</html>

<?php
include_once("inc/script.php");
?>
<div class="col-md-12 mt-5">
    <!-- <h1 class="font-weight-bold text-center" style="color:#234974;">REGISTRASI PASIEN UMUM</h1> -->
    <?php
    //include_once("inc/banner.php");
    ?>
    <!-- <h3 class="font-weight-bold text-center" style="color:#234974;">SILAKAN MASUKAN NO REKAM MEDIS ANDA ATAU NO KTP ANDA</h3> -->
    <div class="col-md-12 mt-5">
        <form action="<?php echo base_url(); ?>index.php/Bayarpoliqris/checkregistrasi" id="formregpasien" method="post">
            <div id="info-pasien" class="card border-primary mb-3">
                <!-- <div class="card-header ">Info Pasien</div> -->
                <div class="card-header text-center" style="background:linear-gradient(to right, #4aac90, #234974);color:#ffffff;position:sticky;top:0;z-index:1;"><strong>Info Pasien</strong></div>
                <div class="card-body py-2" id='patientInfo'></div>
            </div>

            <div id="reg-pas" class="card border-primary mb-3 card-step">
                <div class="card-header text-center" style="background:linear-gradient(to right, #4aac90, #234974);color:#ffffff;position:sticky;top:0;z-index:1;"><strong>Pilih Tanggal dan Poli</strong></div>
                <div class="card-body">
                    <!-- <div class="col-12">
                        <div class="alert alert-default-info notif-surat-kontrol" role="alert"></div>
                    </div>
                    <div class="col-12">
                        <div class="alert alert-default-warning notif-tgl-berlaku" role="alert"></div>
                    </div> -->

                    <div class="col-12">
                        <div class="form-group row">
                            <label class="col-sm-4">Tanggal Kunjungan</label>
                            <div class="col-sm-8">
                                <div class="input-group" id="tanggal" data-target-input="nearest">
                                    <input type="text" class="form-control datepicker" data-target="#tanggal" id="tanggalvalue" style="font-size:30px;">
                                    <!-- value="<?= date('d.m.Y') ?>"> -->
                                    <div class="input-group-append" data-target="#tanggal" data-toggle="datepicker" style="font-size:30px;">
                                        <span class="input-group-text"><span class="fa fa-calendar"></span></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-12">
                        <div class="form-group row">
                            <label class="col-sm-4">Pilih Poli</label>
                            <div class="col-sm-8">
                                <select name="poli_id" id="poli_id" class="form-control">
                                    <option value="">:: Pilih Poli ::</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="col-12">
                        <div class="form-group row">
                            <label class="col-sm-4">Pilih Dokter</label>
                            <div class="col-sm-8">
                                <select name="dokter_id" id="dokter_id" class="form-control">
                                    <option value="">:: Pilih Dokter ::</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="form-group row">
                            <label class="col-sm-4">Pilih Jadwal</label>
                            <div class="col-sm-8">
                                <select name="jadwal" id="jadwal" class="form-control">
                                    <option value="">:: Pilih Jadwal ::</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div id="table-jadwal-poli" class="mb-3"></div>
                    <div class="row">
                        <!-- <div class="col-6">
                            <button data-step="3" class="btn btn-warning btn-block btn-prev" style="font-size: 36px;"><i class="fas fa-chevron-left"></i> Sebelumnya</button>
                        </div> -->
                        <div class="col-6">
                            <button id="btnBayar" type="button" data-step="3" onclick="prosesbayarqris();" class="btn btn-primary btn-block btn-next" style="font-size: 36px;" disabled>Selanjutnya <i class="fas fa-chevron-right"></i></button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
    <div class="modal fade" id="modalqr" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
        <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title fw-bold" id="exampleModalLabel">Scan Kode QR</h3>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close" id="closeButton">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body text-center">
                    <h1 class="NamaRS" style="font-size: 40px;">RSUD Pasar Minggu</h1>
                    <!-- <div id="tanda"><h1 class="red-blink">Pastikan Nominal Sudah Benar!</h1></div> -->
                    <div id="data-content" class="BillNumber" style="font-size: 50px;"></div>
                    <div id="qrcontent" style="width:360px;height:360px;" class="m-auto"></div>
                    <div class="mt-3 mb-3"><img src="<?= base_url('dist/img/QRIS_logo.png') ?>" style="width: 150px;" /></div>
                    <div><button type="button" class="btn btn-primary" onclick="cekstatusqris()" style="font-size: 36px;">Cek Pembayaran</button> </div>

                </div>
                <!-- <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary">Save changes</button>
            </div> -->
            </div>
        </div>
    </div>
    <div class="modal fade" id="modalLoading" tabindex="-1" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow">
                <div class="modal-body text-center p-4">
                    <div class="spinner-border text-primary mb-3" style="width:3rem;height:3rem;" role="status"></div>
                    <h5 class="mb-0">Sedang membuat QRIS... <br>Mohon tunggu & tetap dihalaman ini</h5>
                </div>
            </div>
        </div>
    </div>
    <script>
		let
				startDate = '<?= date('d.m.Y') ?>',
				endDate = '<?= (new DateTime())->add(new DateInterval('P14D'))->format('d.m.Y') ?>';
    </script>
    <?php
    //include_once("inc/keyboard.php");
    ?>
</div>
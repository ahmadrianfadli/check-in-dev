<div class="col-md-12">
    <h1 class="font-weight-bold text-center" style="color:#234974;">PEMBUATAN SURAT KONTROL BPJS</h1>
    <?php
    include_once("inc/banner.php");
    ?>
    <h2 class="font-weight-bold text-center mt-3" style="color:#234974;">SILAKAN MASUKAN NO BPJS ATAU NO REKAM MEDIS ANDA</h2>
    <div class="col-md-12 d-flex justify-content-center mt-3">
        <form action="<?php echo base_url(); ?>index.php/Suratkontrol/cekkunjungan" id="formsurat" method="post">
            <!-- <input type="hidden" class="form-control" id="kode" name="kode"> -->
            <input type="text" class="form-control" id="norm" name="norm" style="width:100%; height:100px; border: 10px solid #234974; border-radius:50px; font-size:400%; text-align: center;" placeholder="No. BPJS / No. RM" onchange="this.value = this.value.toUpperCase()" onkeyup="this.value = this.value.toUpperCase()" autocomplete="off" autofocus>
        </form>
    </div>
    <div class="modal fade" id="modalsurkon" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
        <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable" role="document">
            <div class="modal-content">
                <div class="modal-header p-4">
                    <h2 class="modal-title fw-bold" id="exampleModalLabel">PEMBUATAN SURAT KONTROL BPJS</h2>
                    <button type="button" class="btn btn-primary" data-bs-dismiss="modal" aria-label="Close" class="btn btn-primary" style='font-size:34px;background-color:#234974;border-color:#234974;padding:10px 20px;border-radius:50px;'>
                        Tutup
                    </button>
                </div>
                <div class="modal-body p-4">
                    <div id="step-1" class="animate__animated animate__faster">
                        <h2 class="mb-5 mt-3">PILIH POLIKLINIK</h2>
                        <div id="poli-kontrol"></div>
                    </div>
                    <div id="step-2" class="d-none animate__animated animate__faster">
                        <div id="nama-poli" class="border-bottom" style="font-size:34px;color:#234974;font-weight:bold;"></div>
                        <h2 class="mb-5 mt-3">PILIH DOKTER</h2>
                        <div id="dokter-kontrol"></div>
                        <div class="mt-5">
                            <button type="button" data-step="step-1" class="btn btn-primary" style='font-size:34px;background-color:#234974;border-color:#234974;padding:10px 20px;border-radius:50px;'><i class="fa-solid fa-arrow-left"></i> Kembali</button>
                        </div>
                    </div>
                    <div id="step-3" class="d-none animate__animated animate__faster">
                        <div>
                            <div id="nama-poli2" class="border-bottom" style="font-size:34px;color:#234974;font-weight:bold;"></div>
                        </div>
                        <div>
                            <div id="nama-dokter" class="border-bottom" style="font-size:34px;color:#234974;font-weight:bold;"></div>
                        </div>
                        <h2 class="mb-5 mt-3">PILIH TGL. RENCANA KONTROL</h2>
                        <div id="tgl-kontrol"></div>
                        <div class="mt-5">
                            <button type="button" data-step="step-2" class="btn btn-primary" style='font-size:34px;background-color:#234974;border-color:#234974;padding:10px 20px;border-radius:50px;'><i class="fa-solid fa-arrow-left"></i> Kembali</button>
                        </div>
                    </div>
                    <div id="step-4" class="d-none animate__animated animate__faster">
                        <div class="form-group row border-bottom">
                            <h3 class="col-4">POLI KONTROL</h3>
                            <div id="nama-poli3" class="col-8 text-end" style="font-size:34px;color:#234974;font-weight:bold;"></div>
                        </div>
                        <div class="form-group row border-bottom">
                            <h3 class="col-4">DOKTER KONTROL</h3>
                            <div id="nama-dokter2" class="col-8 text-end" style="font-size:34px;color:#234974;font-weight:bold;"></div>
                        </div>
                        <div class="form-group row border-bottom mb-5">
                            <h3 class="col-4">TANGGAL KONTROL</h3>
                            <div id="tgl-rencana" class="col-8 text-end" style="font-size:34px;color:#234974;font-weight:bold;"></div>
                        </div>
                        <div class="alert alert-warning fw-bold text-center p-3" role="alert" style="font-size: 34px;">
                            <h2 class="m-0">PASTIKAN DATA YANG ANDA PILIH SUDAH SESUAI</h2>
                        </div>
                        <div class="mt-5">
                            <button type="button" data-step="step-2" class="btn btn-primary" style='font-size:34px;background-color:#234974;border-color:#234974;padding:10px 20px;border-radius:50px;'><i class="fa-solid fa-arrow-left"></i> Kembali</button>
                            <button type="button" class="btn btn-primary float-end" style='font-size:34px;background-color:#234974;border-color:#234974;padding:10px 20px;border-radius:50px;' onclick="simpansurkon()">Proses <i class="fa-solid fa-arrow-right"></i></button>
                        </div>
                    </div>
                    <!--  <div class="form-group">
                        <h3>POLI</h3>
                        <input type="text" readonly class="form-control" id="poli_kontrol" name="poli_kontrol" style="width:100%; border: 7px solid #234974; border-radius:50px; font-size:300%; text-align: center;">
                    </div>
                    <div class="form-group">
                        <h3>DOKTER</h3>
                        <input type="text" readonly class="form-control" id="dokter_kontrol" name="dokter_kontrol" style="width:100%; border: 7px solid #234974; border-radius:50px; font-size:300%; text-align: center;">
                    </div> -->
                </div>
                <!-- <div class="modal-footer">
                    <button type="button" class="btn btn-primary rounded-pill" style="font-size: 40px;padding:10px 30px;" onclick="buatsurkon()">Proses</button>
                </div> -->
            </div>
        </div>
    </div>
    <?php
    if (is_ipad()) {
        include_once("inc/keyboard_mobile.php");
    } else {
        include_once("inc/keyboard.php");
    }
    ?>
</div>
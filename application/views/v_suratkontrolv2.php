<div class="col-md-12 surkon-v2">
    <h1 class="font-weight-bold text-center" style="color:#234974;">PEMBUATAN SURAT KONTROL BPJS</h1>
    <h3 class="font-weight-bold text-center mt-3" style="color:#234974;">SILAKAN MASUKAN NO BPJS ATAU NO REKAM MEDIS ANDA</h3>
    <div class="row justify-content-center">
        <div class="col-md-6 mt-3">
            <form action="<?php echo base_url(); ?>index.php/Suratkontrol/cekkunjungan" id="formsurat" method="post">
                <!-- <input type="hidden" class="form-control" id="kode" name="kode"> -->
                <input type="text" class="form-control" id="norm" name="norm" style="width:100%; height:100px; border: 10px solid #234974; border-radius:50px; font-size:350%; text-align: center;" placeholder="No. BPJS / No. RM" onchange="this.value = this.value.toUpperCase()" onkeyup="this.value = this.value.toUpperCase()" autocomplete="off" autofocus>
            </form>
        </div>
    </div>
    <div class="modal fade" id="modalsurkon" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
        <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable" role="document" style="max-width: 70%;">
            <div class="modal-content">
                <div class="modal-header py-2">
                    <h3 class="modal-title fw-bold" id="exampleModalLabel">PEMBUATAN SURAT KONTROL BPJS</h3>
                    <!-- <button type="button" class="btn btn-primary" data-bs-dismiss="modal" aria-label="Close" class="btn btn-primary" style='font-size:28px;background-color:#234974;border-color:#234974;padding:10px 20px;border-radius:50px;'>
                        Tutup
                    </button> -->
                </div>
                <div class="modal-body px-4" style="overflow-x: hidden !important;">
                    <div id="step-1" class="animate__animated animate__faster">
                        <h3 class="mb-3 mt-3">PILIH POLIKLINIK</h3>
                        <div id="poli-kontrol"></div>
                    </div>
                    <div id="step-2" class="d-none animate__animated animate__faster">
                        <div id="nama-poli" class="border-bottom" style="font-size:28px;color:#234974;font-weight:bold;"></div>
                        <h3 class="mb-3 mt-3">PILIH DOKTER</h3>
                        <div id="dokter-kontrol"></div>
                        <!-- <div class="mt-5">
                            <button type="button" data-step="step-1" class="btn btn-primary" style='font-size:28px;background-color:#234974;border-color:#234974;padding:10px 20px;border-radius:50px;'><i class="fa-solid fa-arrow-left"></i> Kembali</button>
                        </div> -->
                    </div>
                    <div id="step-3" class="d-none animate__animated animate__faster">
                        <div class="d-flex border-bottom" style="font-size:28px;color:#234974;font-weight:bold;">
                            <div id="nama-poli2"></div>
                            <div class="mx-4">-</div>
                            <div id="nama-dokter"></div>
                        </div>

                        <h3 class="mb-3 mt-3">PILIH TGL. RENCANA KONTROL</h3>
                        <div id="tgl-kontrol"></div>
                        <!--  <div class="mt-5">
                            <button type="button" data-step="step-2" class="btn btn-primary" style='font-size:28px;background-color:#234974;border-color:#234974;padding:10px 20px;border-radius:50px;'><i class="fa-solid fa-arrow-left"></i> Kembali</button>
                        </div> -->
                    </div>
                    <div id="step-4" class="d-none animate__animated animate__faster">
                        <div class="form-group row border-bottom">
                            <h3 class="col-4">POLI KONTROL</h3>
                            <div id="nama-poli3" class="col-8 text-end" style="font-size:28px;color:#234974;font-weight:bold;"></div>
                        </div>
                        <div class="form-group row border-bottom">
                            <h3 class="col-4">DOKTER KONTROL</h3>
                            <div id="nama-dokter2" class="col-8 text-end" style="font-size:28px;color:#234974;font-weight:bold;"></div>
                        </div>
                        <div class="form-group row border-bottom mb-5">
                            <h3 class="col-4">TANGGAL KONTROL</h3>
                            <div id="tgl-rencana" class="col-8 text-end" style="font-size:28px;color:#234974;font-weight:bold;"></div>
                        </div>
                        <div class="alert alert-warning fw-bold text-center p-3" role="alert" style="font-size: 34px;">
                            <h3 class="m-0">PASTIKAN DATA YANG ANDA PILIH SUDAH SESUAI</h3>
                        </div>
                        <!--  <div class="mt-5">
                            <button type="button" data-step="step-2" class="btn btn-primary" style='font-size:28px;background-color:#234974;border-color:#234974;padding:10px 20px;border-radius:50px;'><i class="fa-solid fa-arrow-left"></i> Kembali</button>
                            <button type="button" class="btn btn-primary float-end" style='font-size:28px;background-color:#234974;border-color:#234974;padding:10px 20px;border-radius:50px;' onclick="simpansurkon()">Proses <i class="fa-solid fa-arrow-right"></i></button>
                        </div> -->
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
                <div class="modal-footer d-flex justify-content-between">
                    <div>
                        <button type="button" id="btn-kembali" data-step="" class="btn btn-default" style='font-size:28px;border-color:#234974;padding:10px 20px;border-radius:50px;'><i class="fa-solid fa-arrow-left"></i> Kembali</button>

                    </div>
                    <div>
                        <button type="button" class="btn btn-default" data-bs-dismiss="modal" aria-label="Close" class="btn btn-primary" style='font-size:28px;padding:10px 20px;border-radius:50px;border-color:#234974;'>
                            Batal
                        </button>
                        <button type="button" class="btn btn-primary ml-3" style='font-size:28px;background-color:#234974;border-color:#234974;padding:10px 20px;border-radius:50px;' id="btn-proses" onclick="simpansurkon()">Proses <i class="fa-solid fa-arrow-right"></i></button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php
    include_once("inc/keyboard_new.php");
    ?>
</div>
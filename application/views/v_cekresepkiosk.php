<div class="col-md-12 row justify-content-center align-items-center">
    <h1 class="font-weight-bold text-center" style="color:#234974;">CEK STATUS RESEP</h1>
    <?php
    include_once("inc/banner.php");
    ?>
    <h3 class="font-weight-bold text-center" style="color:#234974;">Silakan Masukan Kode Booking / NO REKAM MEDIS / NO BPJS / NO KTP</h3>
    <div class="row justify-content-center align-items-center mt-3 mb-3">
        <div class="col-md-12 d-flex justify-content-center mb-2">
            <form action="<?php echo base_url(); ?>index.php/Cekresepkiosk/cekresep" id="formcekresep" method="post">
                <input type="text" class="form-control" id="kodebooking" name="kodebooking" style="width:700px; height:100px; border: 10px solid #234974; border-radius:50px; font-size:250%; text-align: center;" placeholder="Kode Booking / No Rekam Medis / BPJS / KTP" onchange="this.value = this.value.toUpperCase()" onkeyup="this.value = this.value.toUpperCase()" autocomplete="off" autofocus>
            </form>
        </div>
        <div class="modal fade" id="modalrating" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
            <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h3 class="modal-title fw-bold" id="exampleModalLabel"></h3>
                        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body text-center">

                        <div id="dokter_img" class="mb-4"></div>
                        <div class="border-bottom mb-4"></div>
                        <h3 class="mb-2 text-muted">Hari ini anda bertemu dengan</h3>
                        <h1 id="dokter_nama" class="mb-4 fw-bold" style="color:#234974;"></h1>
                        <h3 class="mb-4 text-muted">Silahkan Berikan Rating Anda</h3>
                        <div class="star-ratings mb-4 d-inline-block">
                            <a data-star="1"><i class="fa fa-star fa-4x"></i></a>
                            <a data-star="2"><i class="fa fa-star fa-4x"></i></a>
                            <a data-star="3"><i class="fa fa-star fa-4x"></i></a>
                            <a data-star="4"><i class="fa fa-star fa-4x"></i></a>
                            <a data-star="5"><i class="fa fa-star fa-4x"></i></a>
                        </div>
                        <div id="ketrating1" class="mt-3">
                            <?php foreach ($ket_rating as $d) { ?>
                                <button type="button" class="btn fs-3 mr-2 mb-3 fw-bold" id="btn-penilaian" data-id="<?= $d['id'] ?>" style="border:3px solid #234974;color:#234974;"><?= $d['value'] ?></button>
                            <?php } ?>
                            <!-- <input type="checkbox" class="btn-check" id="btn-check-outlined2" autocomplete="off">
                            <label class="btn btn-outline-primary mr-3 mb-3 fs-3" for="btn-check-outlined2">Komunikatif</label>
                            <input type="checkbox" class="btn-check" id="btn-check-outlined3" autocomplete="off">
                            <label class="btn btn-outline-primary mr-3 mb-3 fs-3" for="btn-check-outlined3">Pelayanan cepat dan efisien</label>
                            <input type="checkbox" class="btn-check" id="btn-check-outlined4" autocomplete="off">
                            <label class="btn btn-outline-primary mr-3 mb-3 fs-3" for="btn-check-outlined4">Tepat Waktu</label> -->
                            <!--  <div style="background-color: #fff3cd;padding:10px;margin-bottom:20px;font-size:24px;font-weight:500;">
                                Dapat memberikan penilaian lebih dari satu<br />Penilaian anda sangat berarti bagi kami<br />Terimakasih
                            </div> -->
                            <div style="background-color: #fff3cd;padding:10px;margin-bottom:20px;font-size:24px;font-weight:500;">
                                Penilaian anda sangat berarti bagi kami<br />Terimakasih
                            </div>
                        </div>
                        <!-- <div id="ketrating2" style="display: none;">
                            <label class="btn btn-outline-primary mr-3 mb-3 fs-3" for="btn-check-outlined5"><input type="checkbox" class="btn-check" id="btn-check-outlined5" autocomplete="off"> Kurang Ramah</label>
                            <label class="btn btn-outline-primary mr-3 mb-3 fs-3" for="btn-check-outlined6"><input type="checkbox" class="btn-check" id="btn-check-outlined6" autocomplete="off"> Kurang Komunikatif</label>
                            <label class="btn btn-outline-primary mr-3 mb-3 fs-3" for="btn-check-outlined7"><input type="checkbox" class="btn-check" id="btn-check-outlined7" autocomplete="off"> Pelayanan lambat</label>
                            <label class="btn btn-outline-primary mr-3 mb-3 fs-3" for="btn-check-outlined8"><input type="checkbox" class="btn-check" id="btn-check-outlined8" autocomplete="off"> Kurang Tepat Waktu</label>
                        </div> -->
                        <!--   <div class="border-bottom mt-3 mb-4"></div> -->

                        <h3 class="fw-bold" style="background:#b4e5d7;padding:10px;">Yuk, Lapor keluhan anda melalui</h3>
                        <h1 class="fw-bold mb-0" style="color: #234974;">E-KELUHAN</h1>
                        <h4 class="fw-bold">RSUD Pasar Minggu</h4>
                        <div style="width: 240px;margin:0 auto;margin-bottom:20px;">
                            <div id="qr-review" style="border:7px solid #222222;margin-bottom:7px;"></div>
                            <div style="background-color: #222222;color:#ffffff;font-size: 32px;font-weight:bold;">SCAN ME</div>
                        </div>
                        <h4 class="fw-bold" style="background:#4aac90;padding:10px;color:#ffffff;">https://rsudpasarminggu.jakarta.go.id/crm/index.php/saran</h4>
                        <div class="border-bottom mt-3 mb-4"></div>
                        <div class="mb-4">
                            <button type="button" class="btn btn-primary btn-xl" onclick="simpanrating()">Lanjut</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php
        include_once("inc/keyboard.php");
        ?>
    </div>
</div>
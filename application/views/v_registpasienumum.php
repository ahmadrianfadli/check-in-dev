<div class="col-md-12 mt-2">
    <h1 class="font-weight-bold text-center" style="color:#234974;">REGISTRASI PASIEN UMUM</h1>
    <?php
    include_once("inc/banner.php");
    ?>
    <h3 class="font-weight-bold text-center" style="color:#234974;">SILAKAN MASUKAN NO REKAM MEDIS ANDA ATAU NO KTP ANDA</h3>
    <div class="col-md-12 d-flex justify-content-center mt-2">
        <form action="<?php echo base_url(); ?>index.php/Registpasienumum/checkdatapasien" id="formregpasien" method="post">
            <input type="hidden" class="form-control" id="kode" name="kode">
            <input type="text" class="form-control" id="idpasantri" name="idpasantri" style="width:100%; height:100px; border: 10px solid #234974; border-radius:50px; font-size:400%; text-align: center;" placeholder="Rekam Medis atau KTP" onchange="this.value = this.value.toUpperCase()" onkeyup="this.value = this.value.toUpperCase()" autocomplete="off" autofocus>
        </form>
    </div>

    <!-- Modal untuk validasi tanggal lahir -->

    <div class="modal fade" id="validationModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
        <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title fw-bold" id="exampleModalLabel">Validasi Tanggal Lahir</h3>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="validationForm">
                        <div class="form-group">
                            <input type="text" class="form-control" id="validationTglLahir" name="validationTglLahir" style="border: 10px solid #234974; border-radius:50px; font-size:200%; text-align: center;" placeholder="DD.MM.YYYY" onchange="this.value = this.value.toUpperCase()" onkeyup="this.value = this.value.toUpperCase()" autocomplete="off" autofocus>
                        </div>
                    </form>
                    <?php
                    include("inc/keyboard_2.php");
                    ?>
                </div>

                <div class="modal-footer">
                    <!-- <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button> -->
                    <button type="button" class="btn btn-primary" onclick="validateTglLahir()">Validate</button>
                </div>
            </div>
        </div>
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
                    <div id="alert"></div>
                    <br>
                    <h1 class="NamaRS" style="font-size: 40px;">RSUD Pasar Minggu</h1>
                    
                   
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
        <?php
        if (is_ipad()) {
            include_once("inc/keyboard_mobile.php");
        } else {
            include_once("inc/keyboard.php");
        }
        ?>
</div>
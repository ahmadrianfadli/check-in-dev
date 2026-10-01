<div class="col-md-12 mt-5">
    <!-- <h3 class="font-weight-bold text-center" style="color:#234974;">DAFTAR TRANSAKSI PASIEN</h3> -->
    <?php
    // include_once("inc/banner.php");
    ?>
    <!-- <h3 class="font-weight-bold text-center" style="color:#234974;">SILAKAN MASUKAN NO REKAM MEDIS ANDA ATAU NO KTP ANDA</h3> -->
    <div id="data-transaksi" style="font-size:30px;"></div>
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-sm-6">
                    <h1>TOTAL TRANSAKSI</h1>
                </div>
                <div class="col-sm-6 text-right">
                    <h1>Rp. <span id="total-transaksi" class="number"></span></h1>
                </div>
            </div>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-sm-6 text-center">
            <!-- <button id="btn-kasir" type="button" class="btn btn-primary btn-lg" style="font-size: 36px;" onclick="prosesbayarkasir()">Via Kasir</button> -->
            <button id="btn-qris" type="button" class="btn btn-success btn-lg" style="font-size: 40px;" onclick="prosesbayarqris()">Tampilkan QRIS</button>
        </div>

        <!--     <div class="col-sm-4 text-right">
            <button type="button" class="btn btn-danger" onclick="batalproses()">Batal</button>
        </div> -->
    </div>

</div>

<div class="modal fade" id="modalqr" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title fw-bold" id="exampleModalLabel">Scan Kode QR</h3>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body text-center">
                <h1 class="NamaRS" style="font-size: 40px;">RSUD Pasar Minggu</h1>
                <!-- <div id="tanda"><h1 class="red-blink">Pastikan Nominal Sudah Benar!</h1></div> -->
                <div id="data-content" class="BillNumber" style="font-size: 50px;"></div>
                <div id="qrcontent" style="width:360px;height:360px;" class="m-auto"></div>
                <div class="mt-3 mb-3"><img src="<?= base_url('dist/img/QRIS_logo.png') ?>" style="width: 150px;" /></div>
            </div>
            <!-- <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary">Save changes</button>
            </div> -->
        </div>
    </div>
</div>

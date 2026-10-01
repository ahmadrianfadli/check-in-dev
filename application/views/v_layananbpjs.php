<?php
if (isset($_GET['head'])) {
    $head = $_GET['head'];
}
?>

<div class="col-md-12 row justify-content-center align-items-center">
    <h1 class="font-weight-bold text-center" style="color:#234974;">MENU LAYANAN PASIEN BPJS</h1>
    <?php
    include_once("inc/banner.php");
    ?>
    <h3 class="font-weight-bold text-center" style="color:#234974;">Silakan Klik Menu Dibawah</h3>
    <div class="col-md-4 d-flex justify-content-center mt-5 mb-5 animate__animated animate__bounceIn">
        <a href="./checkinkiosk?head=<?= $head ?>&subhead=layananbpjs?head=<?= $head ?>" class="btn rounded-circle d-flex flex-column justify-content-center  align-items-center" style="width: 230px; height: 230px; border-style: solid; border-width: 10px; border-color: #234974;">
            <i class="fa-solid fa-qrcode fa-4x text-center" style="background-image: linear-gradient(-45deg, #234974, #4aac90); -webkit-background-clip: text; color: transparent;"></i><br>
            <h4 class="text-center" style="color:#234974;">VERIFIKASI KEDATANGAN</h4>
        </a>
    </div>
    <div class="col-md-4 d-flex justify-content-center mt-5 mb-5 animate__animated animate__bounceIn">
        <a href="./suratkontrol?head=<?= $head ?>&subhead=layananbpjs?head=<?= $head ?>" class="btn rounded-circle d-flex flex-column justify-content-center  align-items-center" style="width: 230px; height: 230px; border-style: solid; border-width: 10px; border-color: #234974;">
            <i class="fa-solid fa-qrcode fa-4x text-center" style="background-image: linear-gradient(-45deg, #234974, #4aac90); -webkit-background-clip: text; color: transparent;"></i><br>
            <h4 class="text-center" style="color:#234974;">SURAT KONTROL BPJS</h4>
        </a>
    </div>
    <!-- <div class="col-md-4 d-flex justify-content-center mt-5 mb-5 animate__animated animate__bounceIn">
        <a href="./checkinkiosk?head=concept_2"  class="btn rounded-circle d-flex flex-column justify-content-center  align-items-center" style="width: 230px; height: 230px; border-style: solid; border-width: 10px; border-color: #234974;">
            <i class="fa-solid fa-qrcode fa-4x text-center" style="background-image: linear-gradient(-45deg, #234974, #4aac90); -webkit-background-clip: text; color: transparent;"></i><br>
            <h4 class="text-center" style="color:#234974;">VERIFIKASI KEDATANGAN</h4>
        </a>
    </div> -->
</div>
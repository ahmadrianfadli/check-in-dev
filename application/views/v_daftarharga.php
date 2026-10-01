<?php
    if (isset($_GET['head'])) {
        $head = $_GET['head'];
    }
?>

<div class="col-md-12 row justify-content-center align-items-center">
    <h1 class="font-weight-bold text-center" style="color:#234974;">BERANDA</h1>
    <?php            
        include_once("inc/banner.php");
    ?>
    <h3 class="font-weight-bold text-center" style="color:#234974;">Silakan Klik Menu Dibawah</h3>
    <!--<div class="col-md-4 d-flex justify-content-center mt-5 mb-5 animate__animated animate__bounceIn">
        <a href="./listharga?head=<?php /*= $head */?>&subhead=daftarharga?head=<?php /*= $head */?>&kategori=JKL-UMU" class="btn rounded-circle d-flex flex-column justify-content-center  align-items-center" style="width: 230px; height: 230px; border-style: solid; border-width: 10px; border-color: #234974;">
            <i class="fa-solid fa-u fa-4x text-center" style="background-image: linear-gradient(-45deg, #234974, #4aac90); -webkit-background-clip: text; color: transparent;"></i><br>
            <h4 class="text-center" style="color:#234974;">UMUM</h4>
        </a>
    </div>

    <div class="col-md-4 d-flex justify-content-center mt-5 mb-5 animate__animated animate__bounceIn">
        <a href="./listharga?head=<?php /*= $head */?>&subhead=daftarharga?head=<?php /*= $head */?>&kategori=JKL-LAB" class="btn rounded-circle d-flex flex-column justify-content-center  align-items-center" style="width: 230px; height: 230px; border-style: solid; border-width: 10px; border-color: #234974;">
            <i class="fa-solid fa-l fa-4x text-center" style="background-image: linear-gradient(-45deg, #234974, #4aac90); -webkit-background-clip: text; color: transparent;"></i><br>
            <h4 class="text-center" style="color:#234974;">LABORATORIUM</h4>
        </a>
    </div>

    <div class="col-md-4 d-flex justify-content-center mt-5 mb-5 animate__animated animate__bounceIn">
        <a href="./listharga?head=<?php /*= $head */?>&subhead=daftarharga?head=<?php /*= $head */?>&kategori=JKL-RAD" class="btn rounded-circle d-flex flex-column justify-content-center  align-items-center" style="width: 230px; height: 230px; border-style: solid; border-width: 10px; border-color: #234974;">
            <i class="fa-solid fa-r fa-4x text-center" style="background-image: linear-gradient(-45deg, #234974, #4aac90); -webkit-background-clip: text; color: transparent;"></i><br>
            <h4 class="text-center" style="color:#234974;">RADIOLOGI</h4>
        </a>
    </div>

    <div class="col-md-4 d-flex justify-content-center mt-5 mb-5 animate__animated animate__bounceIn">
        <a href="./listharga?head=<?php /*= $head */?>&subhead=daftarharga?head=<?php /*= $head */?>&kategori=JKL-OPR" class="btn rounded-circle d-flex flex-column justify-content-center  align-items-center" style="width: 230px; height: 230px; border-style: solid; border-width: 10px; border-color: #234974;">
            <i class="fa-solid fa-o fa-4x text-center" style="background-image: linear-gradient(-45deg, #234974, #4aac90); -webkit-background-clip: text; color: transparent;"></i><br>
            <h4 class="text-center" style="color:#234974;">OPERASI</h4>
        </a>
    </div>-->

    <div class="col-md-4 d-flex justify-content-center mt-5 mb-5 animate__animated animate__bounceIn">
        <a href="./listharga?head=<?= $head ?>&subhead=daftarharga?head=<?= $head ?>&isexe=Y" class="btn rounded-circle d-flex flex-column justify-content-center  align-items-center" style="width: 230px; height: 230px; border-style: solid; border-width: 10px; border-color: #234974;">
            <i class="fa-solid fa-e fa-4x text-center" style="background-image: linear-gradient(-45deg, #234974, #4aac90); -webkit-background-clip: text; color: transparent;"></i><br>
            <h4 class="text-center" style="color:#234974;">EKSEKUTIF</h4>
        </a>
    </div>

    <div class="col-md-4 d-flex justify-content-center mt-5 mb-5 animate__animated animate__bounceIn">
        <a href="./listharga?head=<?= $head ?>&subhead=daftarharga?head=<?= $head ?>&isexe=T" class="btn rounded-circle d-flex flex-column justify-content-center  align-items-center" style="width: 230px; height: 230px; border-style: solid; border-width: 10px; border-color: #234974;">
            <i class="fa-solid fa-n fa-4x text-center" style="background-image: linear-gradient(-45deg, #234974, #4aac90); -webkit-background-clip: text; color: transparent;"></i><br>
            <h4 class="text-center" style="color:#234974;">NON EKSEKUTIF</h4>
        </a>
    </div>
</div>


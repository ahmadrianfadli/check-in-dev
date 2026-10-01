<style type="text/css">
    @media screen and (min-width: 768px) and (max-width: 1024px) and (orientation: portrait) {
        h4 {
            font-size: 20px !important;
            text-align: center;
        }
        .btn.rounded-circle i {
            font-size: 4.2rem !important;
        }
        .col-md-4.d-flex.justify-content-center.mt-5.mb-5.animate__animated.animate__bounceIn {
            flex: 0 0 25% !important;
            max-width: 65% !important;
            margin: 32px 0 !important;
        }
        .btn.rounded-circle {
            width: 260px !important;
            height: 250px !important;
            border-width: 6px !important;
        }
    }
    @media screen and (min-width: 720px) and (max-width: 1024px) and (orientation: portrait) {
        .klikmenu{display: none}
        .container-fluid .p-2{
            position: absolute;
            bottom: 10px;
        }
        h4 {
            font-size: 20px !important;
            text-align: center;
        }
        .btn.rounded-circle i {
            font-size: 4.2rem !important;
        }
        .col-md-4.d-flex.justify-content-center.mt-5.mb-5.animate__animated.animate__bounceIn {
            flex: 0 0 25% !important;
            max-width: 65% !important;
            margin: 32px 0 !important;
        }
        .btn.rounded-circle {
            width: 260px !important;
            height: 250px !important;
            border-width: 6px !important;
        }
    }
</style>

<div class="col-md-12 row justify-content-center align-items-center">
    <h1 class="font-weight-bold text-center" style="color:#234974;">MENU LAYANAN RADIOLOGI</h1>
    <?php
    include_once("inc/banner.php");
    ?>
    <div class="row justify-content-center">
        <?php if($this->uri->segment(1) == 'antrianbpjs'){ ?>
            <h3 class="font-weight-bold text-center" style="color:#234974;"><a onclick="cetakantrian($(this));" data-kode="A" data-jenisid="A01">Silakan klik menu dibawah</a></h3>
        <?php }else{ ?>
            <h3 class="font-weight-bold text-center" style="color:#234974;">Silakan klik menu dibawah</h3>
        <?php } ?>
        <div class="col-md-4 d-flex justify-content-center mt-5 mb-5 animate__animated animate__bounceIn">
            <a href="./formantrian?head=antrianrad&kode=R" data-kode="R" data-jenisid="A09" class="btn rounded-circle d-flex flex-column justify-content-center align-items-center" style="width: 230px; height: 230px; border-style: solid; border-width: 10px; border-color: #234974; ">
                <i class="fa-solid fa-lungs fa-5x text-center" style="background-image: linear-gradient(-45deg, #234974, #4aac90); -webkit-background-clip: text; color: transparent;"></i>
                <br><h5 class="text-center" style="color:#234974;">ANTRIAN RADIOLOGI</h5>
            </a>
        </div>
        <div class="col-md-4 d-flex justify-content-center mt-5 mb-5 animate__animated animate__bounceIn">
            <a href="./cetakradiologi?head=antrianrad" class="btn rounded-circle d-flex flex-column justify-content-center align-items-center" style="width: 230px; height: 230px; border-style: solid; border-width: 10px; border-color: #234974; color: #888888;">
                <i class="fa-solid fa-hospital-user fa-4x text-center" style="background-image: linear-gradient(-45deg, #234974, #4aac90); -webkit-background-clip: text; color: transparent;"></i><br>
                <h4 class="text-center" style="color: #234974;">HASIL EXPERTISE RADIOLOGI</h4>
            </a>
        </div>
    </div>
</div>

<iframe id="framePrint" name="framePrint" style="display:none;"></iframe>
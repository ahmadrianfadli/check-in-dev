<style type="text/css">
    @media (min-width:720px) and (max-width:767px) {
        #bannerControls{
            display: none !important;
        }
        h1.font-weight-bold.text-center{
            font-size:16px;
        }
        h3.font-weight-bold.text-center{
            font-size:14px;
        }

        .col-md-4{
            flex:0 0 33.333333% !important;
            max-width:33.333333% !important;
        }
        .col-md-6{
            flex:0 0 50% !important;
            max-width:50% !important;
        }
        .col-md-12{
            flex:0 0 100% !important;
            max-width:100% !important;
        }

        a.btn.mt-2.mb-2.shadow-lg{
            width: 110px !important;
            height: 110px !important;
            font-size: 370% !important;
        }
        .row.justify-content-center.align-items-center.mt-3.mb-3 > div {
            margin-top: 0 !important;
        }
        input#kodebooking{
            width: 600px !important;
            height: 70px !important;
            border: 6px solid #234974 !important;
            font-size: 180% !important;
        }

        .col-md-12.d-flex.justify-content-around.mt-4.mb-4.animate__animated.animate__bounceIn {
            margin-top: 0 !important;
        }
        a.btn.mt-2.mb-2.shadow-lg{
            line-height: 100px !important;
        }
        a.btn.mt-2.mb-2.shadow-lg{
            line-height: 100px !important;
        }
        .col-md-12.d-flex.justify-content-center.mb-2{
            margin-bottom: 0px !important;
        }
        a.btn.rounded-circle.d-flex.flex-column.justify-content-center.align-items-center{
            width: 100px !important;
            height: 100px !important;
            border-width: 6px !important;
        }

        .container-fluid .p-2{
            position: absolute;
            bottom: 10px;
        }
    }
</style>

<div class="col-md-12 justify-content-center align-items-center">
    <h1 class="font-weight-bold text-center" style="color:#234974;"><?= $title2 ?></h1>
    <?php
    include_once("inc/banner.php");
    ?>
    <h3 class="font-weight-bold text-center" style="color:#234974;">NO REKAM MEDIS / NO BPJS / NO KTP</h3>
    <div class="row justify-content-center align-items-center mt-3 mb-3">
        <div class="col-md-12 d-flex justify-content-center mb-2">
            <form action="<?php echo base_url(); ?>index.php/Formantrian/simpanantrian" id="formantrian" method="post">
                <input type="hidden" class="form-control" id="kode" name="kode" value="<?= $kode ?>">
                <input type="text" class="form-control" id="kodebooking" name="kodebooking" style="width:700px; height:100px; border: 10px solid #234974; border-radius:50px; font-size:250%; text-align: center;" placeholder="No Rekam Medis / BPJS / KTP" onchange="this.value = this.value.toUpperCase()" onkeyup="this.value = this.value.toUpperCase()" autocomplete="off" autofocus inputmode="none">
            </form>
        </div>

        <?php
        /*if (preg_match("/(android|ipad|avantgo|blackberry|bolt|boost|cricket|docomo|fone|hiptop|mini|mobi|palm|phone|pie|tablet|up\.browser|up\.link|webos|wos)/i", $_SERVER["HTTP_USER_AGENT"])) {
            include_once("inc/keyboard_mobile.php");
        } else {
            include_once("inc/keyboard.php");
        }*/
        include_once("inc/keyboard_mobile.php");
        ?>
    </div>
</div>
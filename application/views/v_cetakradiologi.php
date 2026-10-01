<style>
.container-fluid .p-2 .d-flex.justify-content-center.align-items-center.p-1{
    /*position: absolute;*/
    bottom: 30px;
}
/*h6{
    font-size: 1.5rem;
}*/
input#norm, input#tgllahir, input#tglkunjungan{
    margin-right:200px;
    font-size: 300% !important;
}

@media screen and (min-width: 768px) and (max-width: 1024px) and (orientation: portrait) {
    .col-md-12.mt-5{
        margin-top:0px !important;
    }
    .col-md-12.d-flex.justify-content-center.mt-5{
        margin-top:0px !important;
    }

    input.form-control{
        width: 100% !important;
        height: 30% !important;
    }
    input#norm, input#tgllahir, input#tglkunjungan{
        margin-right:200px;
        font-size: 180% !important;
        border: 3px solid #234974 !important;
    }
    a.btn.rounded-circle.d-flex.flex-column.justify-content-center.align-items-center{
        width: 100px !important;
        height: 100px !important;
        border-width: 3px !important;
    }
}

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
        width: 100px !important;
        height: 100px !important;
        font-size: 320% !important;
        line-height: 80px !important;
    }
    .row.justify-content-center.align-items-center.mt-3.mb-3 > div {
        margin-top: 0 !important;
    }

    .col-md-12.d-flex.justify-content-around.mt-4.mb-4.animate__animated.animate__bounceIn {
        margin-top: 4px !important;
    }
    .col-md-12.d-flex.justify-content-center.mb-2{
        margin-bottom: 0px !important;
    }

    .container-fluid .p-2{
        position: absolute;
        bottom: 10px;
    }

    .col-md-12.mt-5{
        margin-top: 0px !important;
    }
    input#norm, input#tgllahir, input#tglkunjungan{
        margin: 0 auto;
        width: 500px !important;
        height: 50px !important;
        border: 3px solid #234974 !important;
        font-size: 140% !important;
    }
    h3.font-weight-bold.text-center{
        display: none !important;
    }
    a.btn.rounded-circle.d-flex.flex-column.justify-content-center.align-items-center{
        width: 100px !important;
        height: 100px !important;
        border-width: 3px !important;
        border-color: #234974;
    }
    i.fa-solid.fa-angles-left.fa-4x.text-center{
        font-size:32px !important;
    }
    .row.col-md-12.d-flex.justify-content-center.mt-4{
        margin-top:0px !important;
    }
    #keyboard > div {
        margin-top: 8px !important;
    }
}
</style>

<div class="col-md-12 mt-5">
    <h3 class="font-weight-bold text-center" style="color:#234974;">SILAKAN MASUKAN NO REKAM MEDIS ATAU NIK KTP, <br>TANGGAL LAHIR, DAN TANGGAL KUNJUNGAN ANDA</h3>
    <p class="text-center">Masukan tanggal lahir dan tanggal kunjungan dengan format dd/mm/yyyy (contoh: 15/08/1980)</p>
    <div class="col-md-12 d-flex justify-content-center mt-5">
        <form action="<?php echo base_url();?>index.php/cetakradiologi/cekpasien" id="formcetakradiologi" method="post">
            <input type="text" class="form-control" id="norm" name="norm" inputmode="none" style="width:100%; height:100px; border: 10px solid #234974; border-radius:50px; font-size:400%; text-align: center;" placeholder="No Rekam Medis atau NIK KTP" onchange="this.value = this.value.toUpperCase()" onkeyup="this.value = this.value.toUpperCase()" autocomplete="off">
            <input type="text" class="form-control mt-3" id="tgllahir" name="tgllahir" inputmode="none" style="width:100%; height:100px; border: 10px solid #234974; border-radius:50px; font-size:400%; text-align: center;" placeholder="Tanggal Lahir dd/mm/yyyy" onchange="this.value = this.value.toUpperCase()" onkeyup="this.value = this.value.toUpperCase()" autocomplete="off">
            <input type="text" class="form-control mt-3" id="tglkunjungan" name="tglkunjungan" inputmode="none" style="width:100%; height:100px; border: 10px solid #234974; border-radius:50px; font-size:400%; text-align: center;" placeholder="Tanggal Kunjungan dd/mm/yyyy" onchange="this.value = this.value.toUpperCase()" onkeyup="this.value = this.value.toUpperCase()" autocomplete="off">
        </form>
    </div>
    <div id="keyboard" class="d-none">
    <?php 
        include_once("inc/keyboard_mobile.php"); 
    ?>
    </div>
    <div class="col-md-12 d-flex justify-content-around mt-4 mb-4 animate__animated animate__bounceIn">
        <?php
            $head='';
            if (isset($_GET['head'])) {
                $head = $_GET['head'];
            }
            if($head === 'antrianrad'){
                echo '
                    <a href="antrianrad" class="btn rounded-circle d-flex flex-column justify-content-center align-items-center" style="width: 120px; height: 120px; border-style: solid; border-width: 10px; border-color: #234974;">
                        <i class="fa-solid fa-angles-left fa-4x text-center" style="background-image: linear-gradient(-45deg, #234974, #4aac90); -webkit-background-clip: text; color: transparent;"></i>
                    </a>
                ';
            }else{
                echo '
                    <a href="cetakpenunjang?head=concept_5&parent=A15&kode=O" class="btn rounded-circle d-flex flex-column justify-content-center align-items-center" style="width: 120px; height: 120px; border-style: solid; border-width: 10px; border-color: #234974;">
                        <i class="fa-solid fa-angles-left fa-4x text-center" style="background-image: linear-gradient(-45deg, #234974, #4aac90); -webkit-background-clip: text; color: transparent;"></i>
                    </a>
                ';
            }
        ?>
    </div>
</div>

<script>
    var base_url = '<?php echo base_url();?>index.php/';
</script>
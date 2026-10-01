<div class="col-md-12 mt-5">
    <!-- <h1 class="font-weight-bold text-center" style="color:#234974;">PEMBAYARAN TINDAKAN</h1> -->
    <?php            
        include_once("inc/banner.php");
    ?>
    <h3 class="font-weight-bold text-center" style="color:#234974;">SILAKAN MASUKAN NO REKAM MEDIS ANDA ATAU NO KTP ANDAx</h3>
    <div class="col-md-12 d-flex justify-content-center mt-5">
        <form action="<?php echo base_url();?>index.php/Bayarqris/checkinantrikasir" id="formbayarqris" method="post">
            <input type="hidden" class="form-control" id="kode" name="kode">
            <input type="text" class="form-control" id="idpasantri" name="idpasantri" style="width:100%; height:100px; border: 10px solid #234974; border-radius:50px; font-size:400%; text-align: center;" placeholder="Rekam Medis atau KTP" onchange="this.value = this.value.toUpperCase()" onkeyup="this.value = this.value.toUpperCase()" autocomplete="off" autofocus>
        </form>
    </div>
    <?php
        if (is_ipad()) {
            include_once("inc/keyboard_mobile.php");
        } else {
            include_once("inc/keyboard.php");
        }
    ?>
</div>
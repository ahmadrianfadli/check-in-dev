<style>
.container-fluid .p-2 .d-flex.justify-content-center.align-items-center.p-1{
    position: absolute;
    bottom: 30px;
}
h6{
    font-size: 1.5rem;
}
input#norm, input#tgllahir, input#tglkunjungan{
    margin-right:200px;
    font-size: 300% !important;
}
</style>

<div class="col-md-12 mt-5">
    <h3 class="font-weight-bold text-center" style="color:#234974;">SILAKAN MASUKAN NO REKAM MEDIS ATAU NIK KTP, <br>TANGGAL LAHIR, DAN TANGGAL KUNJUNGAN ANDA</h3>
    <p class="text-center">Masukan tanggal lahir dan tanggal kunjungan dengan format dd/mm/yyyy (contoh: 15/08/1980)</p>
    <div class="col-md-12 d-flex justify-content-center mt-5">
        <form action="<?php echo base_url();?>index.php/cetaklab/cekpasien" id="formcetaklab" method="post">
            <input type="text" class="form-control" id="norm" name="norm" style="width:100%; height:100px; border: 10px solid #234974; border-radius:50px; font-size:400%; text-align: center;" placeholder="No Rekam Medis atau NIK KTP" onchange="this.value = this.value.toUpperCase()" onkeyup="this.value = this.value.toUpperCase()" autocomplete="off">
            <input type="text" class="form-control mt-3" id="tgllahir" name="tgllahir" style="width:100%; height:100px; border: 10px solid #234974; border-radius:50px; font-size:400%; text-align: center;" placeholder="Tanggal Lahir dd/mm/yyyy" onchange="this.value = this.value.toUpperCase()" onkeyup="this.value = this.value.toUpperCase()" autocomplete="off">
            <input type="text" class="form-control mt-3" id="tglkunjungan" name="tglkunjungan" style="width:100%; height:100px; border: 10px solid #234974; border-radius:50px; font-size:400%; text-align: center;" placeholder="Tanggal Kunjungan dd/mm/yyyy" onchange="this.value = this.value.toUpperCase()" onkeyup="this.value = this.value.toUpperCase()" autocomplete="off">
        </form>
    </div> 
    <div id="keyboard" class="d-none">
    <?php
    if (is_ipad()) {
        include_once("inc/keyboard_mobile.php");
    } else {
        include_once("inc/keyboard.php");
    }
    ?>
    </div>
    <div class="col-md-12 d-flex justify-content-around mt-4 mb-4 animate__animated animate__bounceIn">
        <a href="cetakpenunjang?head=concept_5&parent=A15&kode=O" class="btn rounded-circle d-flex flex-column justify-content-center align-items-center" style="width: 120px; height: 120px; border-style: solid; border-width: 10px; border-color: #234974;">
            <i class="fa-solid fa-angles-left fa-4x text-center" style="background-image: linear-gradient(-45deg, #234974, #4aac90); -webkit-background-clip: text; color: transparent;"></i>
        </a>
    </div>
</div>

<script>
    var base_url = '<?php echo base_url();?>index.php/';
</script>
<div class="col-md-12 row justify-content-center align-items-center">
    <h1 class="font-weight-bold text-center" style="color:#234974;">MENU LAYANAN PASIEN UMUM</h1>
    <?php
    include_once("inc/banner.php");
    ?>
    <div class="row justify-content-center">
        <h3 class="font-weight-bold text-center" style="color:#234974;">Silakan klik menu dibawah</h3>
        <div class="col-md-4 d-flex justify-content-center mt-5 mb-5 animate__animated animate__bounceIn">
            <a class="btn rounded-circle d-flex flex-column justify-content-center align-items-center" data-kode="A" data-jenisid="A01" onclick="cetakantrian($(this));" style="width: 230px; height: 230px; border-style: solid; border-width: 10px; border-color: #234974; color: #888888;">
                <i class="fa-solid fa-print fa-4x text-center" style="background-image: linear-gradient(-45deg, #234974, #4aac90); -webkit-background-clip: text; color: transparent;"></i><br>
                <h4 class="text-center" style="color: #234974;">ANTRIAN POLI</h4>
            </a>
        </div>
        <div class="col-md-4 d-flex justify-content-center mt-5 mb-5 animate__animated animate__bounceIn">
            <a class="btn rounded-circle d-flex flex-column justify-content-center align-items-center" data-kode="B" data-jenisid="A02" onclick="cetakantrian($(this));" style="width: 230px; height: 230px; border-style: solid; border-width: 10px; border-color: #234974; color: #888888;">
                <i class="fa-solid fa-print fa-4x text-center" style="background-image: linear-gradient(-45deg, #234974, #4aac90); -webkit-background-clip: text; color: transparent;"></i><br>
                <h4 class="text-center" style="color: #234974;">ANTRIAN LAB</h4>
            </a>
        </div>
    </div>
</div>
<iframe id="framePrint" name="framePrint" style="display:none;"></iframe>
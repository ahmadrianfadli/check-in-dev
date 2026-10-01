<div class="col-md-12 row justify-content-center align-items-center">
    <h1 class="font-weight-bold text-center" style="color:#234974;">VERIFIKASI KEDATANGAN</h1>
    <?php            
        // include_once("inc/banner.php");
    ?>
    <h3 class="font-weight-bold text-center" style="color:#234974;">Silakan Melakukan Verifikasi Kedatangan Poli Rawat Jalan</h3>
    <div class="row justify-content-center align-items-center mt-3 mb-3">
        <div class="col-md-12 d-flex justify-content-center mb-2">
            <form action="<?php echo base_url();?>index.php/Checkinkiosk/checkin" id="formcheckin" method="post">
                <input type="text" class="form-control" id="kodebookingcheckin" name="kodebookingcheckin" style="width:900px; height:100px; border: 10px solid #234974; border-radius:50px; font-size:250%; text-align: center;" placeholder="Kode Booking Atau No BPJS" onchange="this.value = this.value.toUpperCase()" onkeyup="this.value = this.value.toUpperCase()" autocomplete="off" autofocus>
            </form>
        </div>

        <!-- <div class="col-md-6 d-flex justify-content-center">
            <div class="row d-flex justify-content-center" style="border-width:10px; border-style:solid; border-color:#234974; width:100%; border-radius:20px;">
                <h3 class="d-flex justify-content-center" style="color:#ffff; background-color:#234974; height:50px;"><strong>SCAN BARCODE ANDA</strong></h3>
                <div id="readercheckin" style="border-radius:10px;"></div>
            </div>
        </div> -->

        <div style="margin-top:50px;">
            <div class="row col-md-12 d-flex justify-content-center mt-4" style="height:500px;">
                <div class="col-md-2 d-flex justify-content-center">
                    <a class="btn mt-2 mb-2 shadow-lg" style="width: 180px; height: 180px; background-color: #234974; color: #ffff; font-size: 650%; border-radius: 50%;" onclick="addToDisplay('1')">1</a>
                </div>
                <div class="col-md-2 d-flex justify-content-center">
                    <a class="btn mt-2 mb-2 shadow-lg"  style="width:180px; height:180px; background-color:#234974; color:#ffff; font-size: 650%; border-radius:50%;" onclick="addToDisplay('2')">2</a>
                </div>
                <div class="col-md-2 d-flex justify-content-center">
                    <a class="btn mt-2 mb-2 shadow-lg"  style="width:180px; height:180px; background-color:#234974; color:#ffff; font-size: 650%; border-radius:50%;" onclick="addToDisplay('3')">3</a>
                </div>
                <div class="col-md-2 d-flex justify-content-center">
                    <a class="btn mt-2 mb-2 shadow-lg"  style="width:180px; height:180px; background-color:#234974; color:#ffff; font-size: 650%; border-radius:50%;" onclick="addToDisplay('4')">4</a>
                </div>
                <div class="col-md-2 d-flex justify-content-center">
                    <a class="btn mt-2 mb-2 shadow-lg"  style="width:180px; height:180px; background-color:#234974; color:#ffff; font-size: 650%; border-radius:50%;" onclick="addToDisplay('5')">5</a>
                </div>
                <div class="col-md-2 d-flex justify-content-center">
                    <a class="btn mt-2 mb-2 shadow-lg"  style="width:180px; height:180px; background-color:#234974; color:#ffff; font-size: 650%; border-radius:50%;" onclick="addToDisplay('6')">6</a>
                </div>
                <div class="col-md-2 d-flex justify-content-center">
                    <a class="btn mt-2 mb-2 shadow-lg"  style="width:180px; height:180px; background-color:#234974; color:#ffff; font-size: 650%; border-radius:50%;" onclick="addToDisplay('7')">7</a>
                </div>
                <div class="col-md-2 d-flex justify-content-center">
                    <a class="btn mt-2 mb-2 shadow-lg"  style="width:180px; height:180px; background-color:#234974; color:#ffff; font-size: 650%; border-radius:50%;" onclick="addToDisplay('8')">8</a>
                </div>
                <div class="col-md-2 d-flex justify-content-center">
                    <a class="btn mt-2 mb-2 shadow-lg"  style="width:180px; height:180px; background-color:#234974; color:#ffff; font-size: 650%; border-radius:50%;" onclick="addToDisplay('9')">9</a>
                </div>
                <div class="col-md-2 d-flex justify-content-center">
                    <a class="btn mt-2 mb-2 shadow-lg"  style="width:180px; height:180px; background-color:#234974; color:#ffff; font-size: 650%; border-radius:50%;" onclick="addToDisplay('0')">0</a>
                </div>
                <div class="col-md-2 d-flex justify-content-center">
                    <a class="btn mt-2 mb-2 shadow-lg"  style="width:180px; height:180px; background-color:#ed8423; color:#ffff; font-size: 280%; border-radius:50%;" onclick="clearinput()"><i class="fa-solid fa-arrow-left"></i>HAPUS</a>
                </div>
                <div class="col-md-2 d-flex justify-content-center">
                    <a class="btn mt-2 mb-2 shadow-lg"  style="width:180px; height:180px; background-color:#4aac90; color:#ffff; font-size: 280%; border-radius:50%;" onclick="proses()"><i class="fa-solid fa-magnifying-glass"></i>PROSES</a>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="col-md-12 row justify-content-center align-items-center">
    <h1 class="font-weight-bold text-center" style="color:#234974;">CEK STATUS RESEP</h1>
    <!-- <?php            
        include_once("inc/banner.php");
    ?> -->
    <h3 class="font-weight-bold text-center" style="color:#234974;">MASUKKAN KODE BOOKING / NO REKAM MEDIS / NO BPJS / NO KTP</h3>
    <div class="row justify-content-center align-items-center mt-3 mb-3">
        <div class="col-md-12 d-flex justify-content-center mb-2">
            <form action="<?php echo base_url();?>index.php/Cekresep/cekresep" id="formcekresep" method="post">
                <input type="text" class="form-control" id="kodebooking" name="kodebooking" style="width:700px; height:100px; border: 10px solid #234974; border-radius:50px; font-size:250%; text-align: center;" placeholder="Kode Booking/No Rekam Medis/BPJS/KTP" onchange="this.value = this.value.toUpperCase()" onkeyup="this.value = this.value.toUpperCase()" autocomplete="off" autofocus>
            </form>
        </div>

        <div class="row col-lg-5 col-sm-10 d-flex justify-content-center">
            <div class="row col-12 align-items-center">
                <div class="col-12 d-flex justify-content-around align-items-center">
                    <a class="btn mt-2 mb-2 shadow-lg"  style="width:100px; height:100px; background-color:#234974; color:#ffff; font-size: 400%; border-radius:50%;" onclick="addToDisplay('1')">1</a>
                    <a class="btn mt-2 mb-2 shadow-lg"  style="width:100px; height:100px; background-color:#234974; color:#ffff; font-size: 400%; border-radius:50%;" onclick="addToDisplay('2')">2</a>
                    <a class="btn mt-2 mb-2 shadow-lg"  style="width:100px; height:100px; background-color:#234974; color:#ffff; font-size: 400%; border-radius:50%;" onclick="addToDisplay('3')">3</a>
                </div>    
                <div class="col-12 d-flex justify-content-around align-items-center">
                    <a class="btn mt-2 mb-2 shadow-lg"  style="width:100px; height:100px; background-color:#234974; color:#ffff; font-size: 400%; border-radius:50%;" onclick="addToDisplay('4')">4</a>
                    <a class="btn mt-2 mb-2 shadow-lg"  style="width:100px; height:100px; background-color:#234974; color:#ffff; font-size: 400%; border-radius:50%;" onclick="addToDisplay('5')">5</a>
                    <a class="btn mt-2 mb-2 shadow-lg"  style="width:100px; height:100px; background-color:#234974; color:#ffff; font-size: 400%; border-radius:50%;" onclick="addToDisplay('6')">6</a>
                </div>   
                <div class="col-12 d-flex justify-content-around align-items-center">
                    <a class="btn mt-2 mb-2 shadow-lg"  style="width:100px; height:100px; background-color:#234974; color:#ffff; font-size: 400%; border-radius:50%;" onclick="addToDisplay('7')">7</a>
                    <a class="btn mt-2 mb-2 shadow-lg"  style="width:100px; height:100px; background-color:#234974; color:#ffff; font-size: 400%; border-radius:50%;" onclick="addToDisplay('8')">8</a>
                    <a class="btn mt-2 mb-2 shadow-lg"  style="width:100px; height:100px; background-color:#234974; color:#ffff; font-size: 400%; border-radius:50%;" onclick="addToDisplay('9')">9</a>
                </div> 
                <div class="col-12 d-flex justify-content-around align-items-center">
                    <a class="btn mt-2 mb-2 shadow-lg d-flex flex-column justify-content-center align-items-center"
                        style="width:100px; height:100px; background-color:#ed8423; color:#ffff; font-size: 250%; border-radius:50%;"
                        onclick="clearinput()">
                        <i class="fa-solid fa-arrow-left"></i>
                        <span style="font-size:14px; margin-top:4px;">HAPUS</span>
                    </a>
                    <a class="btn mt-2 mb-2 shadow-lg"  style="width:100px; height:100px; background-color:#234974; color:#ffff; font-size: 400%; border-radius:50%;" onclick="addToDisplay('0')">0</a>
                    <a class="btn mt-2 mb-2 shadow-lg d-flex flex-column justify-content-center align-items-center"
                        style="width:100px; height:100px; background-color:#4aac90; color:#ffff; font-size: 250%; border-radius:50%;"
                        onclick="proses()">
                        <i class="fa-solid fa-magnifying-glass"></i>
                        <span style="font-size:14px; margin-top:4px;">PROSES</span>
                    </a>

                </div>        
            </div>
        </div>
    </div>
</div>
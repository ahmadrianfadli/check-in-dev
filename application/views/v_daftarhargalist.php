<div class="col-md-12 row justify-content-center align-items-center">
    <h1 class="font-weight-bold text-center" style="color:#234974;">DAFTAR HARGA <span id="kat"></span></h1>
    <?php
        // include_once("inc/banner.php");
    ?>
    <!-- <h3 class="font-weight-bold text-center" style="color:#234974;">SILAKAN MASUKAN NO REKAM MEDIS ANDA ATAU NO KTP ANDA</h3> -->
    <div class="input-group mb-3 p-2">
        <input type="text" id="textcari" class="form-control" placeholder="Silakan Masukan Pencarian Anda" aria-label="Recipient's username with two button addons">
        <button class="btn" style="background-color:#234974; color:white; font-size: 30px;" type="button" id="cari-layan" onclick="proses()"><i class="fa-solid fa-magnifying-glass"></i> PENCARIAN</button>
    </div>
    <div class="d-none" style="margin-top:50px;" id="panelkeyboard_abc">
        <div class="row col-md-12 d-flex justify-content-center mt-4">
            <div class="col-md-2 d-flex justify-content-end align-items-center">
                <a class="btn mt-2 mb-2 shadow-lg" style="width: 130px; height: 130px; background-color: #234974; color: #ffff; line-height: 0.9; font-size: 6em; border-radius: 50%;" onclick="addToDisplay('a')">a</a>
            </div>
            <div class="col-md-2 d-flex justify-content-center align-items-center">
                <a class="btn mt-2 mb-2 shadow-lg"  style="width:130px; height:130px; background-color:#234974; color:#ffff; line-height: 0.9; font-size: 6em; border-radius:50%;" onclick="addToDisplay('b')">b</a>
            </div>
            <div class="col-md-2 d-flex justify-content-start align-items-center">
                <a class="btn mt-2 mb-2 shadow-lg"  style="width:130px; height:130px; background-color:#234974; color:#ffff; line-height: 0.9; font-size: 6em; border-radius:50%;" onclick="addToDisplay('c')">c</a>
            </div>
            <div class="col-md-2 d-flex justify-content-end align-items-center">
                <a class="btn mt-2 mb-2 shadow-lg"  style="width:130px; height:130px; background-color:#234974; color:#ffff; line-height: 0.9; font-size: 6em; border-radius:50%;" onclick="addToDisplay('d')">d</a>
            </div>
            <div class="col-md-2 d-flex justify-content-center align-items-center">
                <a class="btn mt-2 mb-2 shadow-lg"  style="width:130px; height:130px; background-color:#234974; color:#ffff; line-height: 0.9; font-size: 6em; border-radius:50%;" onclick="addToDisplay('e')">e</a>
            </div>
            <div class="col-md-2 d-flex justify-content-start align-items-center">
                <a class="btn mt-2 mb-2 shadow-lg"  style="width:130px; height:130px; background-color:#234974; color:#ffff; line-height: 0.9; font-size: 6em; border-radius:50%;" onclick="addToDisplay('f')">f</a>
            </div>
            <div class="col-md-2 d-flex justify-content-end align-items-center">
                <a class="btn mt-2 mb-2 shadow-lg"  style="width:130px; height:130px; background-color:#234974; color:#ffff; line-height: 0.9; font-size: 6em; border-radius:50%;" onclick="addToDisplay('g')">g</a>
            </div>
            <div class="col-md-2 d-flex justify-content-center align-items-center">
                <a class="btn mt-2 mb-2 shadow-lg"  style="width:130px; height:130px; background-color:#234974; color:#ffff; line-height: 0.9; font-size: 6em; border-radius:50%;" onclick="addToDisplay('h')">h</a>
            </div>
            <div class="col-md-2 d-flex justify-content-start align-items-center">
                <a class="btn mt-2 mb-2 shadow-lg"  style="width:130px; height:130px; background-color:#234974; color:#ffff; line-height: 0.9; font-size: 6em; border-radius:50%;" onclick="addToDisplay('i')">i</a>
            </div>
            <div class="col-md-2 d-flex justify-content-end align-items-center">
                <a class="btn mt-2 mb-2 shadow-lg"  style="width:130px; height:130px; background-color:#234974; color:#ffff; line-height: 0.9; font-size: 6em; border-radius:50%;" onclick="addToDisplay('j')">j</a>
            </div>
            <div class="col-md-2 d-flex justify-content-center align-items-center">
                <a class="btn mt-2 mb-2 shadow-lg" style="width: 130px; height: 130px; background-color: #234974; color: #ffff; line-height: 0.9; font-size: 6em; border-radius: 50%;" onclick="addToDisplay('k')">k</a>
            </div>
            <div class="col-md-2 d-flex justify-content-start align-items-center">
                <a class="btn mt-2 mb-2 shadow-lg"  style="width:130px; height:130px; background-color:#234974; color:#ffff; line-height: 0.9; font-size: 6em; border-radius:50%;" onclick="addToDisplay('l')">l</a>
            </div>
            <div class="col-md-2 d-flex justify-content-end align-items-center">
                <a class="btn mt-2 mb-2 shadow-lg"  style="width:130px; height:130px; background-color:#234974; color:#ffff; line-height: 0.9; font-size: 6em; border-radius:50%;" onclick="addToDisplay('m')">m</a>
            </div>
            <div class="col-md-2 d-flex justify-content-center align-items-center">
                <a class="btn mt-2 mb-2 shadow-lg"  style="width:130px; height:130px; background-color:#234974; color:#ffff; line-height: 0.9; font-size: 6em; border-radius:50%;" onclick="addToDisplay('n')">n</a>
            </div>
            <div class="col-md-2 d-flex justify-content-start align-items-center">
                <a class="btn mt-2 mb-2 shadow-lg"  style="width:130px; height:130px; background-color:#234974; color:#ffff; line-height: 0.9; font-size: 6em; border-radius:50%;" onclick="addToDisplay('o')">o</a>
            </div>
            <div class="col-md-2 d-flex justify-content-end align-items-center">
                <a class="btn mt-2 mb-2 shadow-lg"  style="width:130px; height:130px; background-color:#234974; color:#ffff; line-height: 0.9; font-size: 6em; border-radius:50%;" onclick="addToDisplay('p')">p</a>
            </div>
            <div class="col-md-2 d-flex justify-content-center align-items-center">
                <a class="btn mt-2 mb-2 shadow-lg" style="width: 130px; height: 130px; background-color: #234974; color: #ffff; line-height: 0.9; font-size: 6em; border-radius: 50%;" onclick="addToDisplay('q')">q</a>
            </div>
            <div class="col-md-2 d-flex justify-content-start align-items-center">
                <a class="btn mt-2 mb-2 shadow-lg"  style="width:130px; height:130px; background-color:#234974; color:#ffff; line-height: 0.9; font-size: 6em; border-radius:50%;" onclick="addToDisplay('r')">r</a>
            </div>
            <div class="col-md-2 d-flex justify-content-end align-items-center">
                <a class="btn mt-2 mb-2 shadow-lg"  style="width:130px; height:130px; background-color:#234974; color:#ffff; line-height: 0.9; font-size: 6em; border-radius:50%;" onclick="addToDisplay('s')">s</a>
            </div>
            <div class="col-md-2 d-flex justify-content-center align-items-center">
                <a class="btn mt-2 mb-2 shadow-lg"  style="width:130px; height:130px; background-color:#234974; color:#ffff; line-height: 0.9; font-size: 6em; border-radius:50%;" onclick="addToDisplay('t')">t</a>
            </div>
            <div class="col-md-2 d-flex justify-content-start align-items-center">
                <a class="btn mt-2 mb-2 shadow-lg"  style="width:130px; height:130px; background-color:#234974; color:#ffff; line-height: 0.9; font-size: 6em; border-radius:50%;" onclick="addToDisplay('u')">u</a>
            </div>
            <div class="col-md-2 d-flex justify-content-end align-items-center">
                <a class="btn mt-2 mb-2 shadow-lg"  style="width:130px; height:130px; background-color:#234974; color:#ffff; line-height: 0.9; font-size: 6em; border-radius:50%;" onclick="addToDisplay('v')">v</a>
            </div>
            <div class="col-md-2 d-flex justify-content-center align-items-center">
                <a class="btn mt-2 mb-2 shadow-lg" style="width: 130px; height: 130px; background-color: #234974; color: #ffff; line-height: 0.9; font-size: 6em; border-radius: 50%;" onclick="addToDisplay('w')">w</a>
            </div>
            <div class="col-md-2 d-flex justify-content-start align-items-center">
                <a class="btn mt-2 mb-2 shadow-lg"  style="width:130px; height:130px; background-color:#234974; color:#ffff; line-height: 0.9; font-size: 6em; border-radius:50%;" onclick="addToDisplay('x')">x</a>
            </div>
            <div class="col-md-2 d-flex justify-content-center align-items-center">
                <a class="btn mt-2 mb-2 shadow-lg" style="width: 130px; height: 130px; background-color: #234974; color: #ffff; line-height: 0.9; font-size: 6em; border-radius: 50%;" onclick="addToDisplay('y')">y</a>
            </div>
            <div class="col-md-2 d-flex justify-content-start align-items-center">
                <a class="btn mt-2 mb-2 shadow-lg"  style="width:130px; height:130px; background-color:#234974; color:#ffff; line-height: 0.9; font-size: 6em; border-radius:50%;" onclick="addToDisplay('z')">z</a>
            </div>

            <?php if (is_ipad()) { ?>
                <div class="col-md-2 d-flex justify-content-end align-items-center">
                    <a class="btn mt-2 mb-2 shadow-lg"  style="width:130px; height:130px; background-color:#ed8423; color:#ffff; font-size: 200%; border-radius:50%;" onclick="clearinput()"><i class="fa-solid fa-arrow-left"></i></a>
                </div>
                <div class="col-md-2 d-flex justify-content-center align-items-center">
                    <a class="btn mt-2 mb-2 shadow-lg"  style="width:130px; height:130px; background-color:#4aac90; color:#ffff; font-size: 210%; border-radius:50%;" onclick="proses()"><i class="fa-solid fa-magnifying-glass"></i></a>
                </div>
            <?php } else { ?>
                <div class="col-md-2 d-flex justify-content-end align-items-center">
                    <a class="btn mt-2 mb-2 shadow-lg"  style="width:130px; height:130px; background-color:#ed8423; color:#ffff; font-size: 200%; border-radius:50%;" onclick="clearinput()"><i class="fa-solid fa-arrow-left"></i>HAPUS</a>
                </div>
                <div class="col-md-2 d-flex justify-content-center align-items-center">
                    <a class="btn mt-2 mb-2 shadow-lg"  style="width:130px; height:130px; background-color:#4aac90; color:#ffff; font-size: 210%; border-radius:50%;" onclick="proses()"><i class="fa-solid fa-magnifying-glass"></i>CARI</a>
                </div>
            <?php } ?>
        </div>
    </div>
    <div class="d-none" id="resultContainer"></div>
</div>
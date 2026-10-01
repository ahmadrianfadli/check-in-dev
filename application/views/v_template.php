<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php
    include_once("inc/link.php");
    ?>

    <style>
        .container-fluid {
            background-image: url('<?php echo base_url(); ?>dist/img/index_footer_bg_graphic_vector-city-jakarta.png');
            background-size: fixed;
            background-repeat: no-repeat;
            background-position: center bottom;
            width: 100%;
            height: 100vh;
        }
    </style>
</head>

<body>
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center">
            <img src="<?php echo base_url(); ?>dist/img/all.png" style="height:70px; width:350px;">
            <img src="<?php echo base_url(); ?>dist/img/pse.png" style="height:60px; width:220px;">
            <!-- <img src="<?php echo base_url(); ?>dist/img/sukses.png" style="height:70px; width:100px;"> -->
        </div>

        <div class="d-flex justify-content-center mt-2 mb-2">
            <div class="align-items-center pt-1 pb-1 pl-3 pr-3" style="display: inline-block; border-radius: 50px; border-width: 10px; background: linear-gradient(to right, #4aac90, #234974); color: #ffff;">
                <h5 class="text-white mt-2 mb-2 ml-2 mr-2"><?php echo $title; ?></h5>
            </div>
        </div>

        <div class="d-flex justify-content-between">
            <?php echo $contents ?>
        </div>

        <div class="p-2">
            <?php
            $segment  = $this->uri->segment(1);
            $segment2 = $this->uri->segment(2);

            if (isset($_GET['head'])) {
                $head = $_GET['head'];
            }
            if (isset($_GET['subhead'])) {
                $subhead = $_GET['subhead'];
            }
            $parent = '';
            $parent2 = '';
            if (isset($_GET['parent'])) {
                $parent = "&parent=" . $_GET['parent'];
                $parent2 = $_GET['parent'];
            }
            if (
                $segment == "suratkontrol" ||
                $segment == "checkinkiosk" ||
                $segment == "cekresepkiosk" ||
                $segment == "jadwaldokter" ||
                $segment == "lainnya" ||
                $segment == "spo" ||
                //$segment == "antriankasir" ||
                $segment == "layananeksekutif" ||
                $segment == "daftarharga" ||
                $segment == "layananbpjs" ||
                $segment == 'formantrian' ||
                $segment == "antrianloket" ||
                $segment == "pembayarandigital" ||
                $segment == 'antrianbpjs'
            ) {
                if ($segment === 'formantrian') {
                    if($head === 'antrianrad'){
                        echo '<div class="col-md-12 d-flex justify-content-around mt-4 mb-4 animate__animated animate__bounceIn">
                                <a href="antrianrad"class="btn rounded-circle d-flex flex-column justify-content-center align-items-center" style="width: 120px; height: 120px; border-style: solid; border-width: 10px; border-color: #234974;">
                                        <i class="fa-solid fa-angles-left fa-4x text-center" style="background-image: linear-gradient(-45deg, #234974, #4aac90); -webkit-background-clip: text; color: transparent;"></i>
                                    </a>
                                </a>
                            </div>';
                    }else{
                        echo '<div class="col-md-12 d-flex justify-content-around mt-4 mb-4 animate__animated animate__bounceIn">
                                <a href="antriankasir?parent=A14&head=concept_5&subhead=antriankasir?head=concept_5&kode=N"class="btn rounded-circle d-flex flex-column justify-content-center align-items-center" style="width: 120px; height: 120px; border-style: solid; border-width: 10px; border-color: #234974;">
                                        <i class="fa-solid fa-angles-left fa-4x text-center" style="background-image: linear-gradient(-45deg, #234974, #4aac90); -webkit-background-clip: text; color: transparent;"></i>
                                    </a>
                                </a>
                            </div>';
                    }
                } else {
                    if ($segment != "checkinkiosk" && $segment != "suratkontrol") {
                        // if ($parent2 != 'A14') {
                            echo '<div class="col-md-12 d-flex justify-content-around mt-4 mb-4 animate__animated animate__bounceIn">
                            <a href="' . $head . '" class="btn rounded-circle d-flex flex-column justify-content-center align-items-center" style="width: 230px; height: 230px; border-style: solid; border-width: 10px; border-color: #234974;">
                                <i class="fa-solid fa-house fa-4x text-center" style="background-image: linear-gradient(-45deg, #234974, #4aac90); -webkit-background-clip: text; color: transparent;"></i><br>
                                <h4 class="text-center" style="color:#234974;">BERANDA</h4>
                            </a>
                        </div>';
                        // }
                    } else {
                        if (isset($_GET['subhead'])) {
                            echo '<div class="col-md-12 d-flex justify-content-around mt-4 mb-4 animate__animated animate__bounceIn">
                                <a href="' . $subhead . $parent . '"class="btn rounded-circle d-flex flex-column justify-content-center align-items-center" style="width: 230px; height: 230px; border-style: solid; border-width: 10px; border-color: #234974;">
                                        <i class="fa-solid fa-angles-left fa-4x text-center" style="background-image: linear-gradient(-45deg, #234974, #4aac90); -webkit-background-clip: text; color: transparent;"></i><br>
                                        <h4 class="text-center" style="color:#234974;">KEMBALI</h4>
                                    </a>
                                <a href="' . $head . $parent . '" class="btn rounded-circle d-flex flex-column justify-content-center align-items-center" style="width: 230px; height: 230px; border-style: solid; border-width: 10px; border-color: #234974;">
                                    <i class="fa-solid fa-house fa-4x text-center" style="background-image: linear-gradient(-45deg, #234974, #4aac90); -webkit-background-clip: text; color: transparent;"></i><br>
                                    <h4 class="text-center" style="color:#234974;">BERANDA</h4>
                                </a>
                            </div>';
                        } else {
                            echo '<div class="col-md-12 d-flex justify-content-around mt-4 mb-4 animate__animated animate__bounceIn">
                                    <a href="' . $head . '" class="btn rounded-circle d-flex flex-column justify-content-center align-items-center" style="width: 230px; height: 230px; border-style: solid; border-width: 10px; border-color: #234974;">
                                        <i class="fa-solid fa-house fa-4x text-center" style="background-image: linear-gradient(-45deg, #234974, #4aac90); -webkit-background-clip: text; color: transparent;"></i><br>
                                        <h4 class="text-center" style="color:#234974;">BERANDA</h4>
                                    </a>
                                </div>';
                        }
                    }
                }
            }

            if (
                $segment == "spodetail" ||
                $segment == "saranmasukan" ||
                $segment == "ppid" ||
                $segment == "website" ||
                $segment == "registrasipasienbaru" ||
                $segment == "listharga" ||
                $segment == "pilihdokter" ||
                $segment == "detaildokter" ||
                $segment == "kasirrj" ||
                $segment == "bayarqris" ||
                $segment == "pasienbaru" ||
                $segment == "pasienlama" ||
                $segment == "bayarpoli" ||
                $segment == "bayartindakan" ||
                $segment == "registpasienumum"

            ) {
                if($segment == "bayarpoli" || $segment == "bayartindakan"){
                    $parent = $parent.'&head=concept_5&subhead=antrianloket?head=concept_5&parent=A20';
                }
                if($segment == "listharga"){
                    $parent = '?head=concept_5';
                }
				
                echo '<div class="col-md-12 d-flex justify-content-around mt-4 mb-4 animate__animated animate__bounceIn">                                
                                <a href="' . base_url() . 'index.php/' . $subhead . $parent . '" 
                                   class="btn d-flex flex-column justify-content-center align-items-center"
                                   style="width: 230px; height: 230px; border-style: solid; border-width: 10px; border-color: #234974; border-radius: 20px;">
                                    
                                    <i class="fa-solid fa-angles-left fa-4x text-center"
                                       style="background-image: linear-gradient(-45deg, #234974, #4aac90); -webkit-background-clip: text; color: transparent;"></i>
                                    <br>
                                    <h4 class="text-center" style="color:#234974;">KEMBALI</h4>
                                </a>
                                <a href="' . base_url() . 'index.php/' . $head . '" class="btn rounded-circle d-flex flex-column justify-content-center align-items-center" style="width: 230px; height: 230px; border-style: solid; border-width: 10px; border-color: #234974;">
                                    <i class="fa-solid fa-house fa-4x text-center" style="background-image: linear-gradient(-45deg, #234974, #4aac90); -webkit-background-clip: text; color: transparent;"></i><br>
                                    <h4 class="text-center" style="color:#234974;">BERANDA</h4>
                                </a>
                            </div>';
            }
            /* 
            if ($segment == "bayarqris" || $segment == 'registpasienumum')  {
                if ($segment2 == 'transaksipasien' || $segment2 == 'registrasipasien') {
                    echo '<div class="col-md-12 d-flex justify-content-around mt-4 mb-4 animate__animated animate__bounceIn">
                        <a href="../' . $segment . '?kode=' . $_GET['kode'] . '&head=concept_2&subhead=' . $_GET['subhead'] . '" class="btn rounded-circle d-flex flex-column justify-content-center align-items-center" style="width: 230px; height: 230px; border-style: solid; border-width: 10px; border-color: #234974;">
                                <i class="fa-solid fa-angles-left fa-4x text-center" style="background-image: linear-gradient(-45deg, #234974, #4aac90); -webkit-background-clip: text; color: transparent;"></i><br>
                                <h4 class="text-center" style="color:#234974;">KEMBALI</h4>
                            </a>
                        <a href=".' . $subhead . '?head=concept_2" class="btn rounded-circle d-flex flex-column justify-content-center align-items-center" style="width: 230px; height: 230px; border-style: solid; border-width: 10px; border-color: #234974;">
                            <i class="fa-solid fa-house fa-4x text-center" style="background-image: linear-gradient(-45deg, #234974, #4aac90); -webkit-background-clip: text; color: transparent;"></i><br>
                            <h4 class="text-center" style="color:#234974;">BERANDA</h4>
                        </a>
                    </div>';
                } else {
                    echo '<div class="col-md-12 d-flex justify-content-around mt-4 mb-4 animate__animated animate__bounceIn">
                                <a href="' . $subhead . '" class="btn rounded-circle d-flex flex-column justify-content-center align-items-center" style="width: 230px; height: 230px; border-style: solid; border-width: 10px; border-color: #234974;">
                                        <i class="fa-solid fa-angles-left fa-4x text-center" style="background-image: linear-gradient(-45deg, #234974, #4aac90); -webkit-background-clip: text; color: transparent;"></i><br>
                                        <h4 class="text-center" style="color:#234974;">KEMBALI</h4>
                                    </a>
                                <a href="' . $head . '?head=concept_2" class="btn rounded-circle d-flex flex-column justify-content-center align-items-center" style="width: 230px; height: 230px; border-style: solid; border-width: 10px; border-color: #234974;">
                                    <i class="fa-solid fa-house fa-4x text-center" style="background-image: linear-gradient(-45deg, #234974, #4aac90); -webkit-background-clip: text; color: transparent;"></i><br>
                                    <h4 class="text-center" style="color:#234974;">BERANDA</h4>
                                </a>
                            </div>';
                }
            } */
            ?>

            <div class="d-flex justify-content-center align-items-end">
                <div class="d-flex justify-content-center align-items-center p-1" style="border-radius: 50px; background-color: #4aac90;">
                    <i class="fa-brands fa-facebook mt-1 mb-1 ml-5 mr-1 text-white"></i>
                    <i class="fa-brands fa-instagram mt-1 mb-1 ml-1 mr-1 text-white"></i>
                    <i class="fa-brands fa-youtube mt-1 mb-1 ml-1 mr-1 text-white"></i>
                    <i class="fa-brands fa-twitter mt-1 mb-1 ml-1 mr-1 text-white"></i>
                    <h6 class="text-white mt-1 mb-1 ml-1 mr-1">rsudpasarminggu</h6>
                    <h6 class="text-white mt-1 mb-1 ml-1 mr-1">|</h6>
                    <i class="fa-regular fa-paper-plane mt-1 mb-1 ml-1 mr-1 text-white"></i>
                    <h6 class="text-white mt-1 mb-1 ml-1 mr-1">info.rsudpasarminggu@jakarta.go.id</h6>
                    <h6 class="text-white mt-1 mb-1 ml-1 mr-1">|</h6>
                    <i class="fa-solid fa-phone mt-1 mb-1 ml-1 mr-1 text-white"></i>
                    <h6 class="text-white mt-1 mb-1 ml-1 mr-1">(021) 50866999</h6>
                    <!--  <h6 class="text-white mt-1 mb-1 ml-1 mr-1">|</h6>
                    <h6 class="text-white mt-1 mb-1 ml-1 mr-5" id="lastupdate"></h6> -->
                </div>
            </div>
        </div>
    </div>
</body>

</html>

<?php
include_once("inc/script.php");
?>
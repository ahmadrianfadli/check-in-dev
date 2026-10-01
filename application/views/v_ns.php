<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <?php            
            include_once("inc/link.php");
        ?>
    </head>
    <body class="hold-transition layout-top-nav layout-footer-fixed dark-mode">
        <div class="wrapper">
            <div class="content-wrapper">
                <section class="content-header">
                    <div class="container-fluid">
                        <div class="row">
                            <!-- <div class="col-md-6 d-flex justify-content-center align-items-center">
                                <h1 style='font-size:100px'>><?php echo $judul?></h1>
                            </div>
                            <div class="col-md-6 ">
                                <div class="clock"></div>
                            </div> -->
                            <div class="col-md-12 ">
                                <div class="container-fluid d-flex justify-content-center">
                                    <h1><?php echo $judul?></h1>
                                </div>
                            </div>
                            <input type="hidden" id="kodedisplay" name="kodedisplay" value="<?php echo $kodedisplay?>">
                        </div>
                    </div>
                </section>
                
                <section class="content p-4">
                    <div class="row">
                        <!-- <audio id="audioPlayer">
                            <source src="<?php echo base_url();?>/dist/voice/dingdong.wav" type="audio/wav">
                        </audio> -->
                        <a class="btn btn-lg btn-danger" id="playButton">START</a>
                        <div class="d-none row" id="displayantrian">
                            <div class="col-md-6">
                                <div class="d-flex justify-content-center"><h2>MENUNGGU TENSI</h2></div>
                                <br>
                                <div class="row" id="tensiberikutnya"></div>
                            </div>
                            <div class="col-md-6">
                                <div class="d-flex justify-content-center"><h2>SEDANG TENSI</h2></div>
                                <br>
                                <!-- <video style="width:100%; height:30%">
                                    <source src="<?php echo base_url();?>/dist/video/A1.mp4" type="video/mp4">
                                </video> -->
                                <div class="row" id="tensipanggil"></div>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
        </div>

        <script>
           
            //const audioPlayer    = document.getElementById('audioPlayer');
            const playButton     = document.getElementById('playButton');
            const displayantrian = document.getElementById('displayantrian');

            playButton.addEventListener('click', function() {
                //audioPlayer.play();
                playButton.classList.add('d-none'); 
                displayantrian.classList.remove('d-none'); 
            });

        </script>
    </body>
</html>

<?php
    include_once("inc/script.php");
?>

<script type="text/javascript" src="<?php echo base_url();?>dist/js/ns.js"></script>
<!-- <script src="https://code.responsivevoice.org/responsivevoice.js?key=W3jx7fuk"></script> -->
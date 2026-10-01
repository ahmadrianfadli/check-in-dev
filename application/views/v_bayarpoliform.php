<div class="col-md-12 mt-5">
    <!-- <h1 class="font-weight-bold text-center" style="color:#234974;">REGISTRASI PASIEN UMUM</h1> -->
    <?php
    //include_once("inc/banner.php");
    ?>
    <!-- <h3 class="font-weight-bold text-center" style="color:#234974;">SILAKAN MASUKAN NO REKAM MEDIS ANDA ATAU NO KTP ANDA</h3> -->
    <div class="col-md-12 mt-5">
        <form action="javascript:" method="post">
            <div id="info-pasien" class="card border-primary mb-3">
                <!-- <div class="card-header ">Info Pasien</div> -->
                <div class="card-header text-center" style="background:linear-gradient(to right, #4aac90, #234974);color:#ffffff;position:sticky;top:0;z-index:1;"><strong>Info Pasien</strong></div>
                <div class="card-body py-2" id='patientInfo'></div>
            </div>

            <div id="reg-pas" class="card border-primary mb-3 card-step">
                <div class="card-header text-center" style="background:linear-gradient(to right, #4aac90, #234974);color:#ffffff;position:sticky;top:0;z-index:1;"><strong>Pilih Tanggal dan Poli</strong></div>
                <div class="card-body">

                    <div class="col-12">
                        <div class="form-group row">
                            <label class="col-sm-4">Pilih Poli</label>
                            <div class="col-sm-8">
                                <select name="poli_id" id="poli_id" class="form-control">
                                    <option value="">:: Pilih Poli ::</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div id="table-jadwal-poli" class="mb-3"></div>
                    <div class="row">
                        <div class="col-6">
                        </div>
                        <div class="col-6 text-right">
                            <button data-step="3" onclick="prosesbayarpoli();" class="btn btn-primary btn-block btn-next" style="font-size: 36px;" disabled>Selanjutnya <i class="fas fa-chevron-right"></i></button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
    <script>
        let
            startDate = '<?= date('d.m.Y') ?>',
            endDate = '<?= (new DateTime())->add(new DateInterval('P14D'))->format('d.m.Y') ?>';
    </script>
    <?php
    //include_once("inc/keyboard.php");
    ?>
</div>
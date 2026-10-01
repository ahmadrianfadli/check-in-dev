<div style="width: 100%; margin-top: 30px;margin:0 auto;" id="content">
    <style>
        body {
            font-family: Arial, Helvetica, sans-serif;
            /* background-image: url(http://simrs.rsudpm.local/check-in/dist/img/index_footer_bg_graphic_vector-city-jakarta.png); */
            background-size: fixed;
            background-repeat: no-repeat;
            background-position: center bottom;
            width: 100%;
            height: 100vh;
        }

        table {

            border-collapse: collapse;
            width: 100%;
        }

        table th {
            text-align: left;
        }

        #item td,
        #item th {
            border: 1px solid #333333;
            padding: 8px;
        }

        table td {
            vertical-align: top;
        }

        /* 
    #item tr:nth-child(even) {
        background-color: #f2f2f2;
    }

    #item tr:hover {
        background-color: #ddd;
    } */

        #item th {
            padding-top: 12px;
            padding-bottom: 12px;
            text-align: left;
            /* background-color: #04AA6D;
        color: #ffffff; */
        }

        hr {
            border-color: #333;
        }


        @keyframes blink-animation {

            0%,
            49%,
            100% {
                opacity: 1;
                /* Start and end with opacity 1 (visible) */
            }

            50%,
            99% {
                opacity: 0;
                /* Blinking state: invisible */
            }
        }
    </style>

    <h1 class="text-center"><i class="fa-solid fa-circle-check fa-2x text-success"></i></h1>
    <h1 class="text-center text-success"><b>Pembayaran Berhasil</b></h1>
    <h2 class="text-danger fst-italic text-center fw-bold fa-fade" style="margin-top: 30px;">*harap foto struk ini, kami tidak mengeluarkan cetakan struk</h2>

    <div style="text-align: center; margin-top: 50px">
        <div>
            <h2><?= $sysparam->NMRS ?></h2>
        </div>
        <div>
            <h2><?= $sysparam->ALMRS ?></h2>
        </div>
        <div>
            <h2><?= $sysparam->TELPRS ?></h2>
        </div>
    </div>
    <hr />
    <table id="content">
        <thead>
            <tr>
                <th width="50" style="font-size: 24px;">No</th>
                <th width="250" style="font-size: 24px;">: <?= $datapasien->BAYAR_ID ?></th>
                <th width="50" style="font-size: 24px;">Tgl Transaksi</th>
                <th width="250" style="font-size: 24px;">: <?= $datapasien->TGLMASUK ?></th>
            </tr>
            <tr>
                <th colspan="4">
                    <h2 style="margin: 5;"><?= $datapasien->NORM ?></h2>
                </th>
            </tr>
            <tr>
                <th colspan="4">
                    <h2 style="margin: 5;"><?= $datapasien->NAMAPASPJG ?></h2>
                </th>
            </tr>
            <tr>
                <th width="50" style="font-size: 24px;">Status</th>
                <th width="250" style="font-size: 24px;">: <?= $datapasien->PROVIDER ?></th>
            </tr>
        </thead>
    </table>
    <hr />
    <table style="width: 99%;">
        <tbody>
            <tr>
                <?php
                foreach ($detil as $d) {
                ?>
                    <td colspan="<?= count($detil) > 1 ? "2" : "4" ?>" style="<?= count($detil) > 1 ? "width:50%;" : "width:100%;" ?>">
                        <table id="item">
                            <thead>
                                <tr>
                                    <td colspan="3" style="background-color: #f2f2f2; font-size: 26px;"><b><?= $d['NMPOLI'] ?></b></td>
                                </tr>

                                <tr>
                                    <th style="font-size: 24px;">Pelayanan</th>
                                    <th style="text-align: right; font-size: 24px; width: 80">Jumlah</th>
                                    <th style="text-align: right; font-size: 24px; width: 175">Biaya</th>
                                </tr>
                            </thead>

                            <tbody>
                                <?php
                                $totalharga = 0;
                                foreach ($d['DATA'] as $e) {
                                    $totalharga += $e->HARGA_TOTAL;
                                ?>
                                    <tr>
                                        <td style="font-size: 24px;"><?= $e->NMLAYAN ?></td>
                                        <td style="text-align: right; font-size: 24px;"><?= $e->QTY ?></td>
                                        <td style="text-align: right; font-size: 24px;"><?= number_format($e->HARGA_TOTAL, 0, '', '.') ?></td>
                                    </tr>
                                <?php } ?>
                                <tr>
                                    <td colspan="2" style="text-align: center; font-size: 24px;"><b>TOTAL</b></td>
                                    <td style="text-align: right; font-size: 24px;"><b><?= number_format($totalharga, 0, '', '.') ?></b></< /td>
                                </tr>

                            </tbody>
                        </table>
                    </td>

                <?php
                }
                ?>

            </tr>
        </tbody>
    </table>
</div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<!-- Include html2canvas -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
<script>
    document.getElementById('generate-pdf').addEventListener('click', function() {
        const {
            jsPDF
        } = window.jspdf;

        html2canvas(document.getElementById('content')).then(canvas => {
            const imgData = canvas.toDataURL('image/png');
            const pdf = new jsPDF('p', 'mm', 'a3');
            const imgProps = pdf.getImageProperties(imgData);
            const pdfWidth = pdf.internal.pageSize.getWidth();
            const pdfHeight = (imgProps.height * pdfWidth) / imgProps.width;

            pdf.addImage(imgData, 'PNG', 10, 10, pdfWidth - 20, pdfHeight);
            pdf.save('struk.pdf');
        });
    });
</script>
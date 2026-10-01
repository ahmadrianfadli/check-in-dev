<?php @extract($data); ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Lab</title>
    <style>
        body {
            font-family: "Times New Roman";
            font-size: 8px;
            line-height: 1.5;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            border-spacing: 0px;
        }

        td {
            vertical-align: top;
            border: 1px solid #000;
        }

        .no-border td, .no-border th {
            border: none !important;
        }

        .formulir{
            font-weight: bold;
            font-size: 10px !important;
            text-align: center;
        }
        .bold{
            font-weight: bold;
        }
        .text-center{
            text-align: center;
        }
        .text-left{
            text-align: left;
        }
        .text-right{
            text-align: right;
        }
        td.hasil {
            border-right: none !important;
        }
        td.abnormal {
            border-left: none !important;
        }
    </style>
</head>
<body>
    <?php
        $lastUpdatedBy = '';
        $lastUpdatedDate = DateTime::createFromFormat('d.m.Y', '01.01.2000');
        $startSpaceRow = false;
    ?>

    <?php for ($i = 0; $i < count($pasien); $i++) { ?>
        <table <?php if($i > 0){ echo 'style="page-break-before: always;"'; }?>>
            <tr>
                <td width="24%">
                    <br><br><br><br><br>
                    <img src="<?= logo();?>" width="170px" alt="">
                </td>
                <td width="34%" class="formulir">
                    <br><br><br><br><br>
                    FORMULIR HASIL
                    <br>PEMERIKSAAN PATOLOGI KLINIK
                    <br>(005/ FORM/ LAB/ IV/ 2015)
                </td>
                <td width="41%">
                    <br><table class="no-border">
                        <tr>
                            <td style="width: 30%">Nama</td>
                            <td style="width: 10px">:</td>
                            <td style="width: 65%"><?= $pasien[$i]['NAMA_PASIEN'] ?></td>
                        </tr>
                        <tr>
                            <td style="width: 30%">NO. RM</td>
                            <td style="width: 10px">:</td>
                            <td style="width: 65%"><?= $pasien[$i]['INT_PASIEN_ID'] ?></td>
                        </tr>
                        <tr>
                            <td style="width: 30%">Jenis Kelamin</td>
                            <td style="width: 10px">:</td>
                            <td style="width: 65%"><?= ($pasien[$i]['SEX_ID'] == 'L')? 'Laki-Laki' : 'Wanita'; ?></td>
                        </tr>
                        <tr>
                            <td style="width: 30%">Tgl Lahir</td>
                            <td style="width: 10px">:</td>
                            <td style="width: 65%"><?= $pasien[$i]['TGLLAHIR'] ?></td>
                        </tr>
                        <tr>
                            <td style="width: 30%">Umur</td>
                            <td style="width: 10px">:</td>
                            <td style="width: 65%"><?= $pasien[$i]['UMUR']?></td>
                        </tr>
                        <tr>
                            <td style="width: 30%">Alamat</td>
                            <td style="width: 10px">:</td>
                            <td style="width: 65%"><?= $pasien[$i]['ALAMAT1'] ?></td>
                        </tr>
                        <tr>
                            <td style="width: 30%">No. Lab</td>
                            <td style="width: 10px">:</td>
                            <td class="bold" style="width: 65%"><?= $pasien[$i]['SAMPEL_ID'] ?></td>
                        </tr>
                        <tr>
                            <td style="width: 30%">Ruangan</td>
                            <td style="width: 10px">:</td>
                            <td style="width: 65%"><?= $pasien[$i]['NAMA_RUANGAN'] ?></td>
                        </tr>
                        <tr>
                            <td style="width: 30%">Dokter</td>
                            <td style="width: 10px">:</td>
                            <td style="width: 65%"><?= $pasien[$i]['NAMA_DOKTER'] ?></td>
                        </tr>
                        <tr>
                            <td style="width: 30%">Dokter DPJP</td>
                            <td style="width: 10px">:</td>
                            <td style="width: 65%"><?= $pasien[$i]['NAMA_DOKTER_DPJP'] ?></td>
                        </tr>
                        <tr>
                            <td style="width: 30%">Rekanan</td>
                            <td style="width: 10px">:</td>
                            <td style="width: 65%"><?= $pasien[$i]['REKANAN'] ?></td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td colspan="2">
                    <br>
                    <br><table class="no-border">
                        <tr>
                            <td style="width: 40%">Tanggal Penerimaan Sampel</td>
                            <td style="width: 35%">: <?= ($pasien[$i]['SAMPLING_DT_TGL_AMBIL'] != '')? date('d.F.Y', strtotime($pasien[$i]['SAMPLING_DT_TGL_AMBIL'])) : date('d.F.Y', strtotime($pasien[$i]['FULL_TGL_AMBIL'])); ?></td>
                            <td style="width: 10%">Jam</td>
                            <td style="width: 15%">: <?= ($pasien[$i]['SAMPLING_DT_TGL_AMBIL'] != '')? date('H:i:s', strtotime($pasien[$i]['SAMPLING_DT_TGL_AMBIL'])) : $pasien[$i]['FULL_JAM_AMBIL']; ?></td>
                        </tr>
                        <tr>
                            <td style="width: 40%">Tanggal Hasil Selesai</td>
                            <td style="width: 35%">: <?= date('d.F.Y', strtotime($pasien[$i]['SAMPLE_TGL_ACC'])) ?></td>
                            <td style="width: 10%">Jam</td>
                            <td style="width: 15%">: <?= date('H:i:s', strtotime($pasien[$i]['SAMPLE_TGL_ACC'])) ?></td>
                        </tr>
                    </table>
                </td>
                <td align="center">
                    <br><br>Penanggung jawab :
                    <br><?= $infoLab["dokterPJ"];?>
                    <br><?= $infoLab["dokterMikro"];?>
                    <br>
                </td>
            </tr>
        </table>

        <table>
            <tr>
                <td class="bold" style="width: 24%">JENIS PEMERIKSAAN</td>
                <td class="bold text-center" style="width: 18%;">HASIL</td>
                <td class="bold text-center" style="width: 16%">SATUAN</td>
                <td class="bold text-center" style="width: 16%">NILAI RUJUKAN</td>
                <td class="bold text-center" style="width: 25%">KETERANGAN</td>
            </tr>
            <?php foreach ($lab[$i] as $row): ?>
                <?php if (empty($row['SAMPEL_ID'])) : ?>
                    <tr>
                        <td colspan="6" class="bold <?= ($row['ITEM_LEVEL'] == '2')? 'text-center' : 'text-left'; ?>"><?= $row['NAME1'] ?></td>
                    </tr>
                <?php else : ?>
                    <tr>
                        <td><?= htmlentities($row['NAME1']) ?></td>
                        <?php if($row['REPORT_ID'] == "10" || $row['REPORT_ID'] == "19" || $row['REPORT_ID'] == "20"){ ?>
                            <td colspan="4"><?= nl2br(rtf_to_text($row['HASIL_TEKS2']));?></td>
                        <?php }else{ ?>
							<td><table class="no-border" style="width: 100%">
									<tr>
										<td class="text-center" style="width: 80%">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?= htmlentities($row['RESULT_VALUE'])?></td>
										<td class="text-center" style="width: 20%"><?= (!empty(htmlentities($row['RESULT_FLAG'])))? '*' : '';?></td>
									</tr>
								</table>
							</td>
							<td class="text-center"><?= htmlentities($row['UNITS']) ?></td>
							<td class="text-center"><?= htmlentities($row['ANGKA_NORMAL']) ?></td>
							<td><?= str_replace("\n", '<br>', htmlentities($row['KOMENTAR'])) ?></td>
                        <?php } ?>

                        <?php
                        $currentDate = DateTime::createFromFormat('d.m.Y H:i:s', $row['CREATED_DATE2']);

                        if (($currentDate->getTimestamp() - $lastUpdatedDate->getTimestamp()) > 0) {
                            $lastUpdatedDate = $currentDate;
                            $lastUpdatedBy = $row['NAMA'];
                        }
                        ?>

                    </tr>
                <?php endif; ?>
            <?php endforeach; ?>
        </table>
        <br><br>
        <table class="no-border">
            <tr>
                <td style="width: 63%">&nbsp;</td>
                <td style="width: 15%">Pemeriksa</td>
                <td style="width: 20%">: <?= $infoPemeriksa[$i][0]['NAMA'] ?> </td>
            </tr>
            <tr>
                <td style="width: 63%">&nbsp;</td>
                <td style="width: 15%">Nama Acc</td>
                <td style="width: 20%">: <?= $pasien[$i]['DI_ACC_OLEH'] ?></td>
            </tr>
            <tr>
                <td style="width: 63%">&nbsp;</td>
                <td style="width: 15%">Tanggal Cetak</td>
                <td style="width: 20%">: <?= date('d.m.Y H:i:s') ?></td>
            </tr>
        </table>
    <?php } ?>

    <br><br>
    <table class="no-border">
        <tr>
            <td class="text-left" style="width: 50%;"><br><br><br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;~</td>
            <td class="text-right" style="width: 48%"><br><br><br>$&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
        </tr>
    </table>
</body>
</html>

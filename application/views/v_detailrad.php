<?php @extract($data); ?>
<!DOCTYPE html>
<html lang="id">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Detail Radiologi</title>
        <style>
            body {
                font-family: "Arial";
                font-size: 10px;
                /*line-height: 1.5;*/
            }

            table {
                width: 100%;
                border-collapse: collapse;
                line-height: 1.5;
            }

            td {
                vertical-align: top;
                border: 1px solid #000;
            }

            .no-border td, .no-border th {
                border: none !important;
            }

            .kopsurat{
                font-weight: bold;
                font-size: 14px !important;
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
        </style>
    </head>
    <body>
        <table class="no-border">
            <tr>
                <td width="15%" class="text-center">
                    <img src="<?= jayaraya();?>" width="50px" height="50px" alt="">
                </td>
                <td width="70%" class="text-center"><div class="kopsurat">PEMERINTAH PROVINSI DAERAH KHUSUS IBOKOTA
                        <br>RSUD PASAR MINGGU
                    </div>
                    <span style="font-size:12px;font-weight: bold;">
                        Jl. TB Simatupang No.1 Jakarta Selatan
                        <br>TELP : 021-78841672 / FAX : 021-78841672
                    </span>
                </td>
                <td width="15%"><img src="<?= logo();?>" width="170px" alt="">
                </td>
            </tr>

            <tr>
                <td width="100%" style="margin-left:40px;">
                    <hr style="border: 1px solid black; width: 100%;">
                </td>
            </tr>
        </table>

        <table class="no-border">
            <tr>
                <td><table class="no-border">
                        <tr>
                            <td style="width: 35%">NAMA</td>
                            <td style="width: 10px">:</td>
                            <td style="width: 65%"><?= $pasien['NAMA_PASIEN'] ?></td>
                        </tr>
                        <tr>
                            <td style="width: 35%">NO RM</td>
                            <td style="width: 10px">:</td>
                            <td style="width: 65%"><?= $pasien['INT_PASIEN_ID'] ?></td>
                        </tr>
                        <tr>
                            <td style="width: 35%">TGL LAHIR/UMUR</td>
                            <td style="width: 10px">:</td>
                            <td style="width: 65%"><?= str_replace('.','-',$pasien['TGLLAHIR']) ?> / ( <?= str_replace(["Y", "M", "D"], [" TH", " BL", " H"], $pasien['UMUR']);?> )</td>
                        </tr>
                        <tr>
                            <td style="width: 35%">KELAMIN</td>
                            <td style="width: 10px">:</td>
                            <td style="width: 65%"><?= ($pasien['SEX_ID'] == 'L')? 'Laki-Laki' : 'Wanita'; ?></td>
                        </tr>
                        <tr>
                            <td style="width: 35%">PEMERIKSAAN KLINIS</td>
                            <td style="width: 10px">:</td>
                            <td style="width: 65%"><?php echo $ris['NAMA_PEMERIKSAAN'].'<br>'.$ris['KLINIS'];?>
                            </td>
                        </tr>
                    </table>
                </td>
                <td>
                    <table class="no-border">
                        <tr>
                            <td style="width: 40%">TGL. FOTO</td>
                            <td style="width: 10px">:</td>
                            <td style="width: 60%"><?= $ris['ADMIN_DATETIME_START']; ?></td>
                        </tr>
                        <tr>
                            <td style="width: 40%">NO. FOTO</td>
                            <td style="width: 10px">:</td>
                            <td style="width: 60%"><?= $ris['NO_RONTGEN']; ?></td>
                        </tr>
                        <tr>
                            <td style="width: 40%">NO. REGISTER</td>
                            <td style="width: 10px">:</td>
                            <td style="width: 60%"><?= $pasien['EPISODE_ID'] ?></td>
                        </tr>
                        <tr>
                            <td style="width: 40%">RUANGAN</td>
                            <td style="width: 10px">:</td>
                            <td style="width: 60%"><?= $pasien['NAMA_RUANGAN'] ?></td>
                        </tr>
                        <tr>
                            <td style="width: 40%">DOKTER PENGIRIM</td>
                            <td style="width: 10px">:</td>
                            <td style="width: 60%"><?= $pasien['NAMA_DOKTER']?></td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>

        <br><br>
        <table class="no-border" style="font-size: 9px;">
            <?php foreach ($rad as $row):
                $text = $row['RESULTNYA']->load();
                $containsNewline = strpos($text, "\n") !== false;
                ?>
                <tr>
                    <td <?php echo $containsNewline ? 'style="line-height: 0.8;"' : ''; ?>><?php echo preg_replace("/\n/", "", $text); ?></td>
                </tr>
            <?php endforeach; ?>
        </table>

        <br><br>
        <table class="no-border" style="font-size: 9px;">
            <tr>
                <td>Anda dapat melihat hasil foto expertise radiologi melalui akun e-Pasien Anda dengan cara klik link dibawah ini:
                    <span style="color:#0000FF;">https://rsudpasarminggu.jakarta.go.id/e-pasien</span>
                </td>
            </tr>
        </table>

        <br><br>
        <table class="no-border">
            <tr>
                <td class="text-right" style="width: 100%;">Dokter Radiologi&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
            </tr>
            <tr>
                <td class="text-right" style="width: 100%"><br><br><br>$&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<br><br><br></td>
            </tr>
            <tr>
                <td class="text-right" style="width: 100%"><?= $ris['NAMA_RADIOLOG']?></td>
            </tr>
        </table>
    </body>
</html>
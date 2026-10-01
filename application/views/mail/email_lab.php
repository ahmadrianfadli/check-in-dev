<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hasil Pemeriksaan Lab - RSUD Pasar Minggu</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4 !important;
            margin: 0;
            padding: 0;
            color: #333 !important;
        }
        .container {
            max-width: 600px;
            margin: 20px auto;
            background: #ffffff !important;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .header {
            text-align: center;
            padding: 10px 0;
            border-bottom: 2px solid #008080;
        }
        .header img {
            max-width: 300px;
        }
        .content {
            padding: 20px 0;
            text-align: center;
            color: #333 !important;
        }
        .btn {
            display: inline-block;
            background: #008080 !important;
            color: #ffffff !important;
            padding: 10px 20px;
            border-radius: 5px;
            text-decoration: none !important;
            margin-top: 20px;
        }
        .footer {
            text-align: center;
            padding-top: 20px;
            border-top: 2px solid #008080;
            font-size: 12px;
            color: #666;
        }
        .footer table {
            width: 100%;
            text-align: center;
        }
        .footer td {
            padding: 5px 0;
        }
    </style>
</head>
<body>
<div class="container">
    <div class="header">
        <img src="https://rsudpasarminggu.jakarta.go.id/v2/assets/img/rsudpm/logo-rsudpm.png" alt="RSUD Pasar Minggu">
    </div>
    <div class="content">
        <h2>Hasil Pemeriksaan Laboratorium</h2>
        <p>Yth. <strong><?php echo $nama;?></strong>,</p>
        <p>Hasil pemeriksaan laboratorium Anda dengan nomor pasien <strong><?php echo $norm;?></strong> telah tersedia.</p>
        <p>Silakan unduh hasil lengkap Anda melalui lampiran dalam email ini.</p>
        <p>Jika ada pertanyaan atau membutuhkan konsultasi lebih lanjut, silakan hubungi kami.</p>
        <a href="tel:(021)50866999" class="btn">Hubungi RSUD Pasar Minggu</a>
        <p>Mohon jangan membalas email ini, karena email ini dikirim secara otomatis.</p>
        <p>Terima kasih telah mempercayakan pelayanan kesehatan Anda kepada kami.</p>
    </div>
    <div class="footer">
        <table>
            <tr>
                <td><strong>RSUD Pasar Minggu</strong></td>
            </tr>
            <tr>
                <td>Jl. TB Simatupang No. 1, Ragunan, Pasar Minggu</td>
            </tr>
            <tr>
                <td>Jakarta Selatan, DKI Jakarta, Indonesia 12550</td>
            </tr>
            <tr>
                <td>Telepon: (021) 50866999 | +628118712752</td>
            </tr>
        </table>
    </div>
</div>
</body>
</html>
<?php
require_once APPPATH . 'third_party/TCPDF/tcpdf.php';

class Pdf extends TCPDF
{
    private $footerImage;

    public function __construct($reportType = 'lab'){
        parent::__construct();
        $this->reportType = $reportType;
    }

    public function Header()
    {
        $watermark = FCPATH . 'dist/img/rsudpm.png';

        if (file_exists($watermark)) {
            $this->SetAlpha(0.1);

            $this->Image($watermark, 30, 80, 150, 0, 'PNG', '', '', false, 300, '', false, false, 0);

            $this->SetAlpha(1);
        }
    }

    public function setFooterImage($imagePath)
    {
        if (file_exists($imagePath)) {
            $this->footerImage = $imagePath;
        }
    }

    public function Footer() {
        $this->SetFont('helvetica', '', 8);
        $this->SetY(-30);

        if ($this->reportType == 'radiologi') {
            $this->SetX(8);
            $this->Cell(160, 4, "Anda dapat melihat hasil foto expertise radiologi melalui akun e-Pasien Anda dengan cara klik link dibawah ini:", 'B', 4, 'L');

            $this->Ln(2);

            $this->SetX(8);
            $this->SetTextColor(0, 0, 255);
            $this->Cell(0, 4, "https://rsudpasarminggu.jakarta.go.id/e-pasien", 0, 1, 'L');
            $this->SetTextColor(0, 0, 0);
        }else{
            $bsre = FCPATH . 'dist/img/bsre.png';
            $this->Image($bsre, 9, $this->GetY(), 10);

            $this->SetX(22);
            $width = 150;
            if (empty($this->footerImage)) {
                $width = 178;
            }

            $this->Cell($width, 4, "Hasil pemeriksaan ini telah divalidasi secara komputerisasi", 'B', 4, 'L');

            $this->Ln(2);

            $this->SetX(22);
            $this->Cell(0, 4, "Dokumen ini telah ditandatangani secara elektronik menggunakan sertifikat elektronik yang diterbitkan oleh BSrE BSSN.", 0, 1, 'L');
        }

        $posX = $this->GetPageWidth() - 28;
        $posY = $this->SetY(-37);
        if (!empty($this->footerImage)) {
            $this->Image($this->footerImage, $posX, $posY, 22);
        }
    }
}

<?php
class Detaildokter extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();

        $this->load->model("Modeldetaildokter", "md");
    }

    public function index()
    {
        $data["title"]       = "SERIOSA SELF SERVICE KIOSK";
        $this->template->load("v_template", "v_detaildokter", $data);
    }

    public function detailjadwaldokter()
    {
        $dokterid = $this->input->get('dokterid');
        $poliid   = $this->input->get('poliid');

        $result = $this->md->detailjadwaldokter($dokterid, $poliid);
        $events = array();

        foreach ($result as $a) {

            if ($a->JENIS === "1" || $a->JENIS === "2" || $a->JENIS === "3") {
                $data['title']     = $a->KETERANGAN;
                $data['start']     = $a->TGLMULAI;
                $data['end']       = $a->TGLAKHIR;
                $data['color']     = '#DC3545';
                $data['textColor'] = 'white';

                $events[] = $data;
            } else {
                if ($a->KETERANGAN != null) {
                    $data['title']     = $a->KETERANGAN;
                    $data['start']     = $a->TGLMULAI;
                    $data['end']       = $a->TGLAKHIR;
                    $data['color']     = '#DC3545';
                    $data['textColor'] = 'white';

                    $events[] = $data;
                } else {
                    $data['title']     = "E-PASIEN : " . $a->KUOTAONLINE;
                    $data['start']     = $a->TGLMULAI;
                    $data['end']       = $a->TGLAKHIR;
                    $data['color']     = '#234974';
                    $data['textColor'] = 'white';

                    $events[] = $data;
                    $data['title']     = "JAKSEHAT : " . $a->KUOTAONLINE;
                    $data['start']     = $a->TGLMULAI;
                    $data['end']       = $a->TGLAKHIR;
                    $data['color']     = '#234974';
                    $data['textColor'] = 'white';

                    $events[] = $data;
                    $data['title']     = "MOBILE JKN : " . $a->KUOTAONLINE;
                    $data['start']     = $a->TGLMULAI;
                    $data['end']       = $a->TGLAKHIR;
                    $data['color']     = '#234974';
                    $data['textColor'] = 'white';

                    $events[] = $data;

                    $data['title']     = "ONSITE : " . $a->KUOTAOTS;
                    $data['start']     = $a->TGLMULAI;
                    $data['end']       = $a->TGLAKHIR;
                    $data['color']     = '#234974';
                    $data['textColor'] = 'white';

                    $events[] = $data;

                    $data['title']     = "PASCA RANAP : " . $a->KUOTAPASCARANAP;
                    $data['start']     = $a->TGLMULAI;
                    $data['end']       = $a->TGLAKHIR;
                    $data['color']     = '#234974';
                    $data['textColor'] = 'white';

                    $events[] = $data;

                    $data['title']     = "KONTROL : " . $a->KUOTAKONTROL;
                    $data['start']     = $a->TGLMULAI;
                    $data['end']       = $a->TGLAKHIR;
                    $data['color']     = '#234974';
                    $data['textColor'] = 'white';

                    $events[] = $data;
                }
            }
        }

        echo json_encode($events);
    }
}

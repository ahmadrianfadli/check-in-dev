<?php

/* CONCEPT 5
 * DIBUAT UNTUK KEBUTUHAN KIOSK MAGNOLIA
 * 09.02.2026
 * */

class Concept_5 extends CI_Controller{

    public function __construct()
    {
        parent:: __construct();

		if ($_SERVER['REMOTE_ADDR'] == '10.33.0.55') {
			$antrian_penunjang = "http://simrs.rsudpm.local/check-in/index.php/antriankasir?parent=A14&head=concept_5&subhead=antriankasir?head=concept_5&kode=N";
			redirect($antrian_penunjang, 'location');
			exit;
		}
    }

    public function index()
    {
        $data["title"] = "DIGITAL INFORMASI RSUD PASAR MINGGU";
        $this->template->load("v_template", "v_concept_5", $data);
    }
}
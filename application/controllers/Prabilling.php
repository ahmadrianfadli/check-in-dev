<?php
class Prabilling extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model("Modelprabilling", "md");
    }

    public function index()
    {
        $data["title"]       = "ESTIMASI BILLING";
        $this->template->load("v_template", "v_billpasien", $data);
    }

    public function billtrpasienigd()
    {
        $data["title"]       = "ESTIMASI BILLING IGD";
        $this->template->load("v_template", "v_prabillingigd", $data);
    }

    public function billtrpasienranap()
    {
        $data["title"]       = "ESTIMASI BILLING RAWAT INAP";
        $this->template->load("v_template", "v_prabillingranap", $data);
    }

    public function masterantrian()
    {
        $result = $this->md->masterantrian();

        if (!empty($result)) {
            $json['responCode'] = "00";
            $json['responHead'] = "success";
            $json['responDesc'] = "Data Di Temukan";
            $json['responResult'] = $result;
        } else {
            $json['responCode'] = "01";
            $json['responHead'] = "info";
            $json['responDesc'] = "Data Tidak Di Temukan";
        }
        echo json_encode($json);
    }

    public function checktrpasien()
    {
        $kode       = $this->input->post('kode');
        $idpasantri = $this->input->post("idpasantri");

        if ($idpasantri != "") {
            $cekdatapasien = $this->md->cekdatapasien($idpasantri);

            if (!empty($cekdatapasien)) {

                $namapasien = $cekdatapasien->NAMA;
                $idPasien   = $cekdatapasien->PASIEN_ID;

                $cektransaksipasien = $this->md->cektransaksi($idPasien);

                if (!empty($cektransaksipasien)) {

                    $epiid = $cektransaksipasien->EPISODE_ID;
                    $jenisepisode = $cektransaksipasien->JENIS_EPISODE;

                    if ($jenisepisode === 'O') {
                        $json['responCode'] = "00";
                        $json["responHead"] = "success";
                        $json["responurl"] = base_url('prabilling/billtrpasienigd?kode=F&head=concept_3&subhead=antriankasir&pasienid=' . $idPasien) . "&epid=" . $epiid;
                    } else {
                        $json['responCode'] = "00";
                        $json["responHead"] = "success";
                        $json["responurl"] = base_url('prabilling/billtrpasienranap?kode=F&head=concept_3&subhead=antriankasir&pasienid=' . $idPasien) . "&epid=" . $epiid;
                    }
                } else {
                    $json['responCode'] = "02";
                    $json["responHead"] = "error";
                    $json["responDesc"] = "INFORMATION";
                    $json["responHtml"] = "<h5 style='color:#234974;'>Transaksi Tidak Di Temukan</h5>";
                }
            } else {
                $json['responCode'] = "02";
                $json["responHead"] = "error";
                $json["responDesc"] = "INFORMATION";
                $json["responHtml"] = "<h5 style='color:#234974;'>Identitas Anda Tidak Di Temukan</h5>";
            }
        } else {
            $json['responCode'] = "01";
            $json["responHead"] = "error";
            $json["responDesc"] = "INFORMATION";
            $json["responHtml"] = "<h5 style='color:#234974;'>Mohon Masukan Identitas Anda Terlebih Dahulu</h5>";
        }

        echo json_encode($json);
    }

    public function gettrigd()
    {
        //header('Content-type:Application/json');
        $idPasien = $this->input->post('pasienid');
        $transaksi = $this->md->gettransaksiigd($idPasien);
        #grup by episode_id


        #grup by poli
        $poli = [];
        foreach ($transaksi as $i => $d) {
            $poli[$d->POLI_ID]['ID'] = $d->POLI_ID;
            $poli[$d->POLI_ID]['NAMA'] = $d->NAMAPOLI;
            $poli[$d->POLI_ID]['EPISODEID'] = $d->EPISODE_ID;
            $poli[$d->POLI_ID]['JENIS'][] = $d;
        }
        $item = [];
        foreach ($poli as $d) {
            $jenis = [];
            foreach ($d['JENIS'] as $j => $e) {
                $jenis[$e->JENIS]['ID'] = $e->JENIS;
                $jenis[$e->JENIS]['GRUP'] = $e->GRUP;
                $jenis[$e->JENIS]['DATAJENIS'][] = $e;
            }

            $itemalkes = [];
            foreach ($jenis as $f) {
                $itemalkes[] = [
                    'ID' => $f['ID'],
                    'GRUP' => $f['GRUP'],
                    'DATA' => $f['DATAJENIS']
                ];
            }
            $item['POLI'][] = [
                'IDPOLI' => $d['ID'],
                'NAMAPOLI' => $d['NAMA'],
                'EPISODEID' => $d['EPISODEID'],
                'JENIS' => $itemalkes
            ];
        }
        echo json_encode($item);
        //die();
    }

    public function gettrranap()
    {
        //header('Content-type:Application/json');
        $idPasien = $this->input->post('pasienid');
        //$datapasranap = $this->md->getdatapasranap($idPasien);
        $epiid = $this->input->post('epid');
        $transaksi = $this->md->gettransaksiranap($idPasien, $epiid);
        #grup by episode_id


        #grup by poli
        $poli = [];
        foreach ($transaksi as $i => $d) {
            $poli[$d['KODE']]['ID'] = $d['KODE'];
            /* $poli[$d->POLI_ID]['NAMA'] = $d->NAMAPOLI;
            $poli[$d->POLI_ID]['EPISODEID'] = $d->EPISODE_ID; */
            $poli[$d['KODE']]['DATA'][] = $d;
        }
        $item = [];
        foreach ($poli as $d) {
            $jenis = [];

            $item['KODE'][] = [
                'KODE' => $d['ID'],
                'DATA' => $d['DATA']
            ];
        }
        echo json_encode($item);
    }
}

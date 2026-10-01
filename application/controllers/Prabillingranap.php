<?php
    class Prabillingranap extends CI_Controller
    {

        public function __construct()
        {
            parent:: __construct();
            $this->load->model("Modelprabillingranap","md");
        }

        public function index()
        {
            $data["title"]       = "ESTIMASI BILLING RAWAT INAP";
            $this->template->load("v_template","v_billpasienranap",$data);
        }

        public function billtransaksipasien()
        {
            $data["title"]       = "ESTIMASI BILLING RAWAT INAP";
            $this->template->load("v_template", "v_prabillingranap", $data);
        }

        public function masterantrian()
        {
            $result      = $this->md->masterantrian();

            if(!empty($result)){
                $json['responCode']="00";
                $json['responHead']="success";
                $json['responDesc']="Data Di Temukan";
                $json['responResult']=$result;
            }else{
                $json['responCode']="01";
                $json['responHead']="info";
                $json['responDesc']="Data Tidak Di Temukan";
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

                    $idEpi = $cektransaksipasien->EPISODE_ID;
                    
                    if (!empty($cektransaksipasien)) {
                        $json['responCode'] = "00";
                        $json["responHead"] = "success";
                        $json["responurl"] = base_url('prabillingranap/billtransaksipasien?kode=F&head=concept_3&subhead=antriankasir&pasienid=' . $idPasien);
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

        public function gettransranap()
        {
        //header('Content-type:Application/json');
        $idPasien = $this->input->post('pasienid');
        $transaksi = $this->md->getalltransaksi($idPasien);
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
        
    }

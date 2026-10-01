<?php
class Checkin extends CI_Controller
{

    public static $responDesc;
    public static $responHead;

    public function __construct()
    {
        parent::__construct();
        $this->load->model("Modelcheckin", "md");
    }

    public function index()
    {
        $data["title"]   = "SIMRS RSUD PASAR MINGGU";
        $this->load->view("v_checkin", $data);
    }

    public function registrasipasienrjbpjs()
    {
        self::$responHead = "";
        self::$responDesc = "";

        $kodebooking      = $this->input->post("kodebooking");

        if ($kodebooking != "") {
            $checkbooking = $this->md->checkbooking($kodebooking);
            if (!empty($checkbooking)) {
                if ($checkbooking->AKTIF === "1") {
                    if ($checkbooking->STATUS === "1") {

                        // if($checkbooking->LASTKUNJUNGAN!=null){
                        //     if($checkbooking->CEKBPJS==="Y"){
                        //         $fingerprint = BPJSVclaimV2::getfingerprint($checkbooking->NO_KARTU_BPJS,$checkbooking->LASTKUNJUNGAN);

                        //         if(intval($fingerprint['metaData']['code'])===200){
                        //             if($fingerprint['response']['kode']==="1"){
                        //                 self::$responHead ="success";
                        //                 self::$responDesc ="Silakan Menunggu Di Nurse Station <br> <b>".$checkbooking->POLITUJUAN."</b><br>Atasnama <b>".$checkbooking->NAMAPASIEN."</br>";
                        //             }else{
                        //                 self::$responHead ="warning";
                        //                 self::$responDesc ="Silakan Menuju KiosK Untuk Melakukan Registrasi Finger Print";
                        //             }
                        //         }else{
                        //             self::$responHead ="error";
                        //             self::$responDesc =$fingerprint['metaData']['message'];
                        //         }
                        //     }else{
                        //         self::$responHead ="success";
                        //         self::$responDesc ="Silakan Menunggu Di Nurse Station <br> <b>".$checkbooking->POLITUJUAN."</b><br>Atasnama <b>".$checkbooking->NAMAPASIEN."</br>";
                        //     }
                        // }else{
                        //     self::$responHead ="warning";
                        //     self::$responDesc ="Silakan Menuju KiosK Untuk Melakukan Registrasi Finger Print";
                        // }


                        $data['HADIR'] = "Y";
                        if ($this->md->updatekehadiran($checkbooking->BOOKING_ID, $checkbooking->PASIEN_ID, $checkbooking->EPISODE_ID, $data)) {
                            // $json["responCode"]="01";
                            // $json["responHead"]=self::$responHead;
                            // $json["responDesc"]=self::$responDesc;

                            $json["responCode"] = "01";
                            $json["responHead"] = "success";
                            $json["responDesc"] = "Silakan Menunggu Di Nurse Station <br> <b>" . $checkbooking->POLITUJUAN . "</b><br>Atasnama <b>" . $checkbooking->NAMAPASIEN . "</br>";
                        } else {
                            $json["responCode"] = "99";
                            $json["responHead"] = "error";
                            $json["responDesc"] = "Mohon Maaf Anda Gagal Melakukan Check In Rawat Jalan Silakan Hubungi Petugas";
                        }
                    } else {
                        if ($checkbooking->KODEANTRIAN === "A") {
                            $json["responCode"] = "99";
                            $json["responHead"] = "error";
                            $json["responDesc"] = "Mohon Maaf, Silakan Melakukan Proses Check In 30 Menit Sebelum Jadwal Anda";
                        } else {
                            $json["responCode"] = "99";
                            $json["responHead"] = "error";
                            $json["responDesc"] = "Mohon Maaf, Silakan Melakukan Proses Check In 15 Menit Sebelum Jadwal Anda";
                        }
                    }
                } else {
                    if ($checkbooking->ALASAN_BATAL != null) {
                        $json["responCode"] = "99";
                        $json["responHead"] = "error";
                        $json["responDesc"] = "No Booking <b>" . $checkbooking->BOOKING_ID . "</b> Telah Di Batalkan <br> <b>[ " . $checkbooking->ALASAN_BATAL . " ] </b>";
                    } else {
                        $json["responCode"] = "99";
                        $json["responHead"] = "error";
                        $json["responDesc"] = "Nomor Booking <b>" . $checkbooking->BOOKING_ID . "</b> Telah Di Batalkan";
                    }
                }
            } else {
                $json["responCode"] = "99";
                $json["responHead"] = "error";
                $json["responDesc"] = "<h1>Mohon Maaf<br>Nomor Booking " . $kodebooking . "<br>Tidak Ter Registrasi Rawat Jalan Untuk Hari Ini</h1>";
            }
        } else {
            $json["responCode"] = "99";
            $json["responHead"] = "error";
            $json["responDesc"] = "Mohon Masukan Kode Booking Anda Atau No BPJS Anda";
        }

        echo json_encode($json);
    }
}

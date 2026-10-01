<?php
class Modelregistpasienumum extends CI_Model
{
    function sysparam()
    {
        $query = "
                SELECT 
                (SELECT NILAI FROM SR01_SYS_PARAMETER WHERE LOKASI_ID = '001' AND PARAM_ID = 'ADDR01') NMRS,
                (SELECT NILAI FROM SR01_SYS_PARAMETER WHERE LOKASI_ID = '001' AND PARAM_ID = 'ADDR02') ALMRS,
                (SELECT NILAI FROM SR01_SYS_PARAMETER WHERE LOKASI_ID = '001' AND PARAM_ID = 'ADDR03') TELPRS
                FROM DUAL
                ";

        $recordset = $this->db->query($query);
        $recordset = $recordset->row();
        return $recordset;
    }

    public function newOracleConnection()
    {
        $hostname = $this->db->hostname;
        $username = $this->db->username;
        $password = $this->db->password;

        $conn = oci_connect($username, $password, $hostname);

        return $conn;
    }

    function cekdatapasien($parameter)
    {
        $query = "
            SELECT A.INT_PASIEN_ID, A.NAMA, A.NO_IDENTITAS, A.PASIEN_ID, A.EMAIL,
            TO_CHAR(A.TGL_LAHIR, 'DD.MM.YYYY') TGLLAHIR,
            CASE
                WHEN A.NO_SELULAR IS NULL THEN A.TELEPON
                ELSE A.NO_SELULAR
            END NOHP
            FROM SR01_GEN_PASIEN_MS A
            WHERE A.LOKASI_ID = '001' AND A.JENIS_PAS = 'I'
            AND A.INT_PASIEN_ID = '" . $parameter . "' OR A.NO_IDENTITAS = '" . $parameter . "' OR A.PASIEN_ID = '" . $parameter . "'
        ";

        $recordset = $this->db->query($query);
        $recordset = $recordset->row();
        return $recordset;
    }

    function listpoli()
    {
        $query = "
            SELECT X.*
            FROM (
                SELECT NULL POLI_ID, '-PILIH POLI-' KETERANGAN, 1 URUT
                FROM DUAL
                UNION
                SELECT A.POLI_ID, A.KETERANGAN, 2 URUT
                FROM SR01_MED_POLI_MS A
                WHERE A.LOKASI_ID = '001' AND A.AKTIF = '1'
				AND A.IS_EXE = 'T'
                AND A.POLI_ID NOT IN ('APS', 'POLIFISIO', 'UGD01', 'KLINI0000000001', 'MEDIC0000000000', 
                'POLIFISOKUP', 'POLI0000000037', 'POLI0000000048', 'POLIFISWICARA'
                ,'POLI0000000025','POLI0000000027','POLI0000000001','POLI0000000013','POLI0000000024','POLI0000000020','POLI1','POLI0000000004','POLI0000000010')
            ) X
            ORDER BY X.URUT, X.KETERANGAN
        ";

        $recordset = $this->db->query($query);
        $recordset = $recordset->result();
        return $recordset;
    }

    function listdokter($poliId)
    {
        $query = "
            SELECT X.*
            FROM (
                SELECT NULL DOKTER_ID, '-PILIH DOKTER-' NAMA, 1 URUT
                FROM DUAL
                UNION
                SELECT A.DOKTER_ID, A.NAMA, 2 URUT
                FROM SR01_MED_DOKTER_MS A
                WHERE A.LOKASI_ID = '001' AND A.AKTIF = '1'
                AND A.DEF_POLI_ID = '$poliId' 
            ) X
            ORDER BY X.URUT, X.NAMA
        ";

        $recordset = $this->db->query($query);
        $recordset = $recordset->result();
        return $recordset;
    }

    public function getJadwalDokterSekarang($data)
    {
        // TANGGAL HARI INI MENGAMBIL SLOT ANTRIAN OTS = 2
        $poliID = $data['poli_id'];
        $dokterID = $data['dokter_id'];

        $tanggalSekarang = date("d.m.Y");
        $jamSekarang = date("H:i");
        $hariID = (intval(date('w')) + 1);

        $conn = $this->newOracleConnection();

        $queryJadwalDokter = "
            BEGIN
                SR01_ANTRIAN.SLOTPOLI_PERDOKTER(:HASIL, :POLIID, :DRID, :HARIID, :TGL, '2');
            END;
        ";
        $resultJadwalDokter = oci_parse($conn, $queryJadwalDokter);
        $cursorJadwalDokter = oci_new_cursor($conn);
        oci_bind_by_name($resultJadwalDokter, ':HASIL', $cursorJadwalDokter, -1, OCI_B_CURSOR);
        oci_bind_by_name($resultJadwalDokter, ':POLIID', $poliID);
        oci_bind_by_name($resultJadwalDokter, ':DRID', $dokterID);
        oci_bind_by_name($resultJadwalDokter, ':HARIID', $hariID);
        oci_bind_by_name($resultJadwalDokter, ':TGL', $tanggalSekarang);
        oci_execute($resultJadwalDokter);
        oci_execute($cursorJadwalDokter, OCI_DEFAULT);

        $dataJadwalDokter = [];
        oci_fetch_all($cursorJadwalDokter, $dataJadwalDokter, null, null, OCI_FETCHSTATEMENT_BY_ROW);

        $retVal = [];

        foreach ($dataJadwalDokter as $row) {
            $enableStatus = false;

            if (
                $jamSekarang >= $row['JAM_MULAI']
                && $jamSekarang <= $row['JAM_SELESAI']
                && $row['SISAANTRIAN'] > 0
            ) {
                $enableStatus = true;
            } else {
                if ($jamSekarang <= $row['JAM_MULAI'] && $row['SISAANTRIAN'] > 0) {
                    $enableStatus = true;
                }
            }

            if (intval($row['SISAANTRIAN']) <= 0) {
                $labelKeterangan = ' (Kuota Penuh)';
            } else {
                $labelKeterangan = " (Sisa Antrian: " . $row['SISAANTRIAN'] . ")";
            }

            $data = [
                'jamMulai' => $row['JAM_MULAI'],
                'jamSelesai' => $row['JAM_SELESAI'],
                'sisaAntrian' => $row['SISAANTRIAN'],
                'kodeAntrian' => $row['KODE_ANTRIAN'],
                'noAntrian' => $row['NOURUT'],
                'cetakanNoAntrian' => $row['ANTRIAN'],
                'noUrut' => $row['NOURUT'],
                'jadwalID' => $row['JADWAL_ID'],
                'antrianID' => $row['ANTRIAN_ID'],
                'enable' => $enableStatus,
                'value' => $row['JAM_MULAI'] . ";" . $row['JAM_SELESAI'] . ";" . $row['ANTRIAN'] . ";" . $row['NOURUT'] . ";" . $row['JADWAL_ID'] . ";" . $row['ANTRIAN_ID'] . ";" . $hariID,
                'label' => $row['JAM_MULAI'] . " - " . $row['JAM_SELESAI'] . $labelKeterangan,
            ];

            $retVal[] = $data;
        }

        return $retVal;
    }

    public function getJadwalDokterbyTanggal($data)
    {
        // TANGGAL LEBIH BESAR HARI INI MENGAMBIL SLOT ANTRIAN ONLINE = 1
        $poliID = $data['poli_id'];
        $dokterID = $data['dokter_id'];

        // $tanggalSekarang = date("d.m.Y");
        $tanggal = $data['tanggal'];

        $dateObjectNow = DateTime::createFromFormat('d.m.Y', date('d.m.Y'));
        $dateObjectInput = DateTime::createFromFormat('d.m.Y', $tanggal);

        $selisihTimestamp = $dateObjectInput->getTimestamp() - $dateObjectNow->getTimestamp();
        $selisihHari = floor($selisihTimestamp / (60 * 60 * 24));

        $jamSekarang = date("H:i");
        $hariID = (intval($dateObjectInput->format('w')) + 1);

        $conn = $this->newOracleConnection();

        $queryJadwalDokter = "
            BEGIN
                SR01_ANTRIAN.SLOTPOLI_PERDOKTER(:HASIL, :POLIID, :DRID, :HARIID, :TGL, '1');
            END;
        ";
        $resultJadwalDokter = oci_parse($conn, $queryJadwalDokter);
        $cursorJadwalDokter = oci_new_cursor($conn);
        oci_bind_by_name($resultJadwalDokter, ':HASIL', $cursorJadwalDokter, -1, OCI_B_CURSOR);
        oci_bind_by_name($resultJadwalDokter, ':POLIID', $poliID);
        oci_bind_by_name($resultJadwalDokter, ':DRID', $dokterID);
        oci_bind_by_name($resultJadwalDokter, ':HARIID', $hariID);
        oci_bind_by_name($resultJadwalDokter, ':TGL', $tanggal);
        oci_execute($resultJadwalDokter);
        oci_execute($cursorJadwalDokter, OCI_DEFAULT);

        $dataJadwalDokter = [];
        oci_fetch_all($cursorJadwalDokter, $dataJadwalDokter, null, null, OCI_FETCHSTATEMENT_BY_ROW);

        $retVal = [];

        /*  return $dataJadwalDokter;
        die(); */
        foreach ($dataJadwalDokter as $row) {
            $enableStatus = false;

            if ($selisihHari === 0) {
                if ($jamSekarang >= $row['JAM_MULAI'] && $jamSekarang <= $row['JAM_SELESAI'] && $row['SISAANTRIAN_PERSLOT'] > 0) {
                    $enableStatus = true;
                } else {
                    if ($jamSekarang <= $row['JAM_MULAI'] && $row['SISAANTRIAN_PERSLOT'] > 0) {
                        $enableStatus = true;
                    }
                }
            } else {
                if ($row['SISAANTRIAN_PERSLOT'] > 0) {
                    $enableStatus = true;
                }
            }

            if (intval($row['SISAANTRIAN_PERSLOT']) <= 0) {
                $labelKeterangan = ' (Kuota Penuh)';
            } else {
                $labelKeterangan = " (Sisa Antrian: " . $row['SISAANTRIAN_PERSLOT'] . ")";
            }

            $data = [
                'jamMulai' => $row['JAM_MULAI'],
                'jamSelesai' => $row['JAM_SELESAI'],
                'sisaAntrian' => $row['SISAANTRIAN_PERSLOT'],
                'kodeAntrian' => $row['KODE_ANTRIAN'],
               // 'noAntrian' => $row['NO_URUT'],
                'cetakanNoAntrian' => $row['ANTRIAN'],
                //'noUrut' => $row['NO_URUT'],
                'jadwalID' => $row['JADWAL_ID'],
                'antrianID' => $row['ANTRIAN_ID'],
                'enable' => $enableStatus,
                'value' => $row['JAM_MULAI'] . ";" . $row['JAM_SELESAI'] . ";" . $row['ANTRIAN'] . ";" . $row['JADWAL_ID'] . ";" . $row['ANTRIAN_ID'] . ";" . $hariID,
                'label' => $row['JAM_MULAI'] . " - " . $row['JAM_SELESAI'] . $labelKeterangan,
            ];

            $retVal[] = $data;
        }

        return $retVal;
    }

    function getHariLibur($tgl)
    {
        // jika hasil query not empty maka tidak dapat regist
        $query = "
            SELECT Y.*
            FROM (
                SELECT TO_CHAR(TANGGAL, 'DD.MM.YYYY') TANGGAL, KETERANGAN, BOLEH_REGIST
                FROM SR01_JADWAL_LIBUR_MS
                WHERE LOKASI_ID = '001' AND AKTIF = '1'
                AND TO_CHAR(TANGGAL, 'DD.MM.YYYY') = '" . $tgl . "'
                UNION
                SELECT X.*
                FROM (
                    SELECT '" . $tgl . "' TANGGAL, ('HARI '||UPPER(SR01_GET_NAMA_HARI(TO_DATE('" . $tgl . "', 'DD.MM.YYYY')))) KETERANGAN, 'T' BOLEH_REGIST
                    FROM DUAL
                ) X
                WHERE X.KETERANGAN LIKE '%MINGGU'
            ) Y
            WHERE Y.BOLEH_REGIST = 'T'
        ";

        $recordset = $this->db->query($query);
        $recordset = $recordset->row();
        return $recordset;
    }

    function cekpolihariini($parameter, $poliId)
    {
        $query = "
            SELECT A.EPISODE_ID, A.PASIEN_ID, A.TGL_TRANSAKSI, B.KETERANGAN NMPOLI, C.NAMA NAMADR
            FROM SR01_KEU_EPISODE A, SR01_MED_POLI_MS B, SR01_MED_DOKTER_MS C
            WHERE A.LOKASI_ID = B.LOKASI_ID AND A.POLI_ID = B.POLI_ID
            AND A.LOKASI_ID = C.LOKASI_ID = A.DOKTER_ID = C.DOKTER_ID 
            AND A.LOKASI_ID = '001' AND TRUNC(A.TGL_TRANSAKSI) = TRUNC(SYSDATE)
            AND A.STATUS_EPISODE NOT IN ('55','99') AND A.AKTIF = '1'
            AND A.PASIEN_ID = '" . $parameter . "' AND A.POLI_ID = '" . $poliId . "'
        ";

        $recordset = $this->db->query($query);
        $recordset = $recordset->result();
        return $recordset;
    }

    function cekbookingpolibytgl($parameter, $tgl, $poliId)
    {
        $query = "
            SELECT A.EPISODE_ID, A.PASIEN_ID, TRUNC(A.TGL_MASUK) TGLMASUK, B.KETERANGAN NMPOLI, C.NAMA NAMADR 
            FROM WEB_CO_REGISTRASI_ONLINE_HD A, SR01_MED_POLI_MS B, SR01_MED_DOKTER_MS C
            WHERE A.LOKASI_ID = B.LOKASI_ID AND A.POLI_ID = B.POLI_ID
            AND A.LOKASI_ID = C.LOKASI_ID = A.DOKTER_ID = C.DOKTER_ID 
            AND A.LOKASI_ID = '001' AND A.AKTIF = '1'
            AND TO_CHAR(A.TGL_MASUK, 'DD.MM.YYYY') = '" . $tgl . "'
            AND A.PASIEN_ID = '" . $parameter . "' AND A.POLI_ID = '" . $poliId . "'
        ";

        $recordset = $this->db->query($query);
        $recordset = $recordset->result();
        return $recordset;
    }

    function getJawdalDokterDuaMinggu($poliId, $dokterId, $jenis)
    {
        $query = "
            SELECT Y.*,
            (Y.KUOTA - Y.TERDAFTAR) SISA,
            CASE
                WHEN Y.ADA_JADWAL = 'Y' THEN 'Sisa Kuota = '||TO_CHAR((Y.KUOTA - Y.TERDAFTAR))
                WHEN Y.HARI_ID = '1' THEN 'Tidak buka'
                ELSE
                    'Belum dibuka'
            END KETERANGAN
            FROM (
                SELECT X.TANGGAL,
                INITCAP(TRIM(TO_CHAR(TO_DATE(X.TANGGAL, 'DD.MM.YYYY'), 'DD-MON-YYYY'))) TGLPJG, 
                INITCAP(SR01_GET_NAMA_HARI (TO_DATE(X.TANGGAL, 'DD.MM.YYYY'))) HARI,
                SR01_GET_HARI_ID(TO_DATE(X.TANGGAL, 'DD.MM.YYYY')) HARI_ID,
                SR01_ANTRIAN.KUOTA_PERDOKTER('$poliId', SR01_GET_HARI_ID(TO_DATE(X.TANGGAL, 'DD.MM.YYYY')), '$jenis', '$dokterId') KUOTA,
                SR01_ANTRIAN.TERDAFTAR_PERDOKTER('$poliId', '$dokterId', X.TANGGAL, SR01_GET_HARI_ID(TO_DATE(X.TANGGAL, 'DD.MM.YYYY')), '$jenis') TERDAFTAR,
                CASE
                    WHEN (SELECT COUNT(DOKTER_ID) FROM SR01_MED_DOKTER_OFF WHERE LOKASI_ID='001' AND AKTIF='1' AND POLI_ID='$poliId' AND DOKTER_ID='$dokterId' AND TO_CHAR(TANGGAL,'DD.MM.YYYY')=X.TANGGAL AND JENIS = '$jenis')>0 THEN
                        'Y'
                    ELSE 'N'
                END ADA_JADWAL
                FROM (
                    SELECT X.*
                    FROM (
                    SELECT TO_CHAR(SYSDATE + LEVEL - 1, 'DD.MM.YYYY') TANGGAL
                    FROM DUAL
                    CONNECT BY LEVEL <= 15
            ) X
                ) X
            ) Y
        ";

        $recordset = $this->db->query($query);
        $recordset = $recordset->result();
        return $recordset;
    }

    function buatepisode()
    {
        $query = "
            SELECT SR01_BUATID.BUAT_ID_EPISODE_ID('K') EPISODE_ID
            FROM DUAL
        ";

        $recordset = $this->db->query($query);
        $recordset = $recordset->row();
        return $recordset;
    }

    function getidjadwal($poliId, $idDokter, $idhari, $idAntrian)
    {
        $query = "
            SELECT JADWAL_ID
            FROM SR01_JADWAL_ANTRIAN
            WHERE LOKASI_ID = '001' AND AKTIF = '1'
            AND POLI_ID = '$poliId' AND DOKTER_ID = '$idDokter'
            AND HARI_ID = '$idhari' AND ANTRIAN_ID = '$idAntrian'
        ";

        $recordset = $this->db->query($query);
        $recordset = $recordset->row();
        return $recordset;
    }

    function getNominal($poliId, $idDokter, $jamMulai)
    {
        $query = "
            SELECT SR01_GET_TOTAL_KIOSK_QRIS('$poliId', '$idDokter', '$jamMulai') TOTAL
            FROM DUAL
                ";

        $recordset = $this->db->query($query);
        $recordset = $recordset->row();
        return $recordset;
    }

    function simpanRegistrasi($epiId, $idPasien, $poliId, $idDokter, $billNumber, $resQris, $idHari, $idJdldr, $jamMulai, $jamSelesai, $urut, 
    $noHp, $email, $idAntrian, $tgl, $jenis, $expTgl, $reffNom)
    {
        $query = "
            BEGIN
                SR01_REG_KIOSK_QRIS('$epiId', '$idPasien', '$poliId', '$idDokter', '$billNumber', '$resQris', 
                '$idHari', '$idJdldr', '$jamMulai', '$jamSelesai', '$urut', '$noHp', '$email', '$idAntrian', '$tgl', '$jenis',
                '$expTgl', '$reffNom');
            END;
                 ";

        $insresult = $this->db->query($query);
        return $insresult;  
    }

    function getdatabayar($tagihId)
    {
        $query = "
            SELECT KET2, TAGIHAN_ID, STATUS, SUM(BILLING) NOMINAL, (TO_CHAR(CREATED_DATE, 'YYYY-MM-DD') || 'T' || TO_CHAR(CREATED_DATE, 'HH24:MI:SS') || '+07:00') TGLJAM,
            BAYAR_ID, PASIEN_ID
            FROM SR01_PG_TRANSAKSI_HD
            WHERE LOKASI_ID = '001' 
            AND TAGIHAN_ID = '$tagihId'
            AND AKTIF = '1'
            GROUP BY KET2, TAGIHAN_ID, STATUS, CREATED_DATE, BAYAR_ID, PASIEN_ID
                 ";

        $recordset = $this->db->query($query);
        $recordset = $recordset->result();
        return $recordset;
    }

    function datapasien($bayarId, $pasienId)
    {
        $query = "
            SELECT DISTINCT B.INT_PASIEN_ID NORM, B.NAMA NAMAPAS, SR01_GET_SUFFIX('$pasienId') NAMAPASPJG,
            TO_CHAR(A.TGL_MASUK, 'DD.MM.YYYY') TGLMASUK, A.BAYAR_ID, D.NAMA PROVIDER,
            (SELECT SR01_INITCAP_NAMA_DR(E.NAMA) FROM SR01_MED_DOKTER_MS E
             WHERE E.LOKASI_ID = A.LOKASI_ID AND E.DOKTER_ID = A.DOKTER_ID) NMDR,
            (SELECT E.ANTRIAN FROM SR01_JADWAL_ANTRIAN_TEMP E
             WHERE E.LOKASI_ID = A.LOKASI_ID AND E.EPISODE_ID = A.EPISODE_ID
             AND E.PASIEN_ID = A.PASIEN_ID) NOURUT
            FROM SR01_PG_TRANSAKSI_HD A, SR01_GEN_PASIEN_MS B, SR01_KEU_EPISODE C, SR01_KEU_REKANAN_MS D
            WHERE A.LOKASI_ID = B.LOKASI_ID AND A.PASIEN_ID = B.PASIEN_ID
            AND C.LOKASI_ID = D.LOKASI_ID AND C.REKANAN_ID = D.REKANAN_ID
            AND C.LOKASI_ID = A.LOKASI_ID AND C.EPISODE_ID = A.EPISODE_ID
            AND C.PASIEN_ID = A.PASIEN_ID
            AND A.LOKASI_ID = '001' AND A.AKTIF = '1' AND A.STATUS = '3'
            AND A.BAYAR_ID = '$bayarId' AND A.PASIEN_ID = '$pasienId'
                ";

        $recordset = $this->db->query($query);
        $recordset = $recordset->row();
        return $recordset;
    }

    function detillayan($bayarId, $pasienId)
    {
        $query = "
            SELECT X.* 
            FROM (
                SELECT DISTINCT A.EPISODE_ID, A.PASIEN_ID, B.POLI_ID, 
                (SELECT D.KETERANGAN FROM SR01_MED_POLI_MS D
                WHERE D.LOKASI_ID = B.LOKASI_ID AND D.POLI_ID = B.POLI_ID) NMPOLI, 
                C.LAYAN_ID, 
                (SELECT D.NAMA_LAYAN1 FROM SR01_KEU_LAYAN_MS D
                WHERE D.LOKASI_ID = C.LOKASI_ID AND D.LAYAN_ID = C.LAYAN_ID) NMLAYAN,
                C.QTY,
                C.HARGA_TOTAL
                FROM SR01_PG_TRANSAKSI_HD A, SR01_KEU_TRANSAKSI_HD B, SR01_KEU_TRANSCTR_IT C
                WHERE A.LOKASI_ID = B.LOKASI_ID AND A.EPISODE_ID = B.EPISODE_ID 
                AND A.PASIEN_ID = B.PASIEN_ID AND A.BAYAR_ID = B.BAYAR_ID
                AND B.LOKASI_ID = C.LOKASI_ID AND B.EPISODE_ID = C.EPISODE_ID 
                AND B.PASIEN_ID = C.PASIEN_ID AND B.TRANS_ID = C.TRANS_ID
                AND A.LOKASI_ID = '001' AND A.AKTIF = '1' AND A.STATUS = '3'
                AND A.BAYAR_ID = '$bayarId' AND A.PASIEN_ID = '$pasienId'
            ) X
            ORDER BY X.NMPOLI, X.LAYAN_ID
        ";

        $recordset = $this->db->query($query);
        $recordset = $recordset->result();
        return $recordset;
    }

    function ceknotifyqris($billNumber)
    {
        $query = "
            SELECT X.*
            FROM (
                SELECT
                    (SELECT COUNT(A.ORIGINALREFERENCENO) JUMLAH         
                    FROM SR01_PG_NOTIFY A
                    WHERE A.LOKASI_ID = '001' AND A.BILLNUMBER = '$billNumber'
                    ) JUMLAH,

                    (SELECT B.BAYAR_ID FROM SR01_PG_TRANSAKSI_HD B
                    WHERE B.LOKASI_ID = '001' AND B.TAGIHAN_ID = '$billNumber'
                    AND STATUS = '3'
                    GROUP BY B.BAYAR_ID
                    
                    ) BAYARID
                FROM DUAL
                ) X
            WHERE X.JUMLAH > 0
        ";

        $recordset = $this->db->query($query);
        $recordset = $recordset->row();
        return $recordset;
    }

    function cekRegistSebelumnya($pasienId)
    {
        //cek transaksi yg masih aktif
        $query = "
            SELECT A.TRANSAKSI_ID, A.PASIEN_ID, A.EPISODE_ID, A.TAGIHAN_ID, A.BILLING,
            TO_CHAR(A.TGL_MASUK, 'DD.MM.YYYY') TGLMASUK, B.KETERANGAN NAMAPOLI, C.NAMA NAMADR,
            A.KET1
            FROM SR01_PG_TRANSAKSI_HD A, SR01_MED_POLI_MS B, SR01_MED_DOKTER_MS C
            WHERE A.LOKASI_ID = '001' 
            AND A.AKTIF = '1'
            AND A.PASIEN_ID = '$pasienId'
            AND A.STATUS = '1'  
            AND B.LOKASI_ID = A.LOKASI_ID AND B.POLI_ID = A.POLI_ID
            AND C.LOKASI_ID = A.LOKASI_ID AND C.DOKTER_ID = A.DOKTER_ID
            AND A.TGL_EXP >= SYSDATE
            AND A.CREATED_DATE = (
                                    SELECT MAX(D.CREATED_DATE) 
                                    FROM SR01_PG_TRANSAKSI_HD D
                                    WHERE D.LOKASI_ID = A.LOKASI_ID 
                                    AND D.PASIEN_ID = A.PASIEN_ID 
                                    AND D.AKTIF = A.AKTIF
                                    AND D.STATUS = A.STATUS 
                                    AND D.TGL_EXP >= SYSDATE
                                )
        ";

        $recordset = $this->db->query($query);
        $recordset = $recordset->row();
        return $recordset;    
    }

    // function buatepisode()
    // {
    //     $query = "
    //         SELECT SR01_BUATID.BUAT_ID_EPISODE_ID('K')
    //         FROM DUAL
    //     ";

    //     $recordset = $this->db->query($query);
    //     $recordset = $recordset->row();
    //     return $recordset;
    // }

    // function insertRegOnline($idPasien, $epiId, $tgl, $poliId, $idDokter, $idHari, $idJdldr, $jamMulai, $jamSelesai, $urut, $noHp, $email, $idAntrian)
    // {
    //     $query = "
    //         INSERT INTO CLO_MANAGER.WEB_CO_REGISTRASI_ONLINE_HD
    //             (LOKASI_ID, BOOKING_ID, PASIEN_ID, TGL_MASUK, EPISODE_ID, 
    //             POLI_ID, DOKTER_ID, REKANAN_ID, 
    //             AKTIF, CREATED_BY, CREATED_DATE, PASIEN_BARU, VALID, JENIS_TR, 
    //             HARI_ID, JADWAL_ID, JAM_MULAI, JAM_SELESAI, URUT, NO_SELULAR, EMAIL, ANTRIAN_ID, ISREGIST)
    //         Values
    //             ('001', (SELECT SR01_KDBOOKING_ANGKA FROM DUAL), '$idPasien', TO_DATE('$tgl', 'DD.MM.YYYY'), '$epiId', 
    //             '$poliId', '$idDokter', 'UMUM', 
    //             '1', 'KIOSK', SYSDATE, '0', '0', '9', 
    //             '$idHari', '$idJdldr', '$jamMulai', '$jamSelesai', '$urut', '$noHp', '$email', '$idAntrian', 'T')
    //     ";

    //     $insresult = $this->db->query($query);
    //     return $insresult;
    // }

    // function insertJadwalTemp($idPasien, $epiId, $tgl, $poliId, $idDokter, $idHari, $idJdldr, $jamMulai, $jamSelesai, $urut, $jenis, $idAntrian)
    // {
    //     $query = "
    //         INSERT INTO SR01_JADWAL_ANTRIAN_TEMP
    //             (LOKASI_ID, PASIEN_ID, EPISODE_ID, TANGGAL, JAM_MULAI, JAM_SELESAI, 
    //              ANTRIAN_ID, HARI_ID, JADWAL_ID, POLI_ID, DOKTER_ID, JENIS,ANTRIAN)
    //         VALUES
    //             ('001', '$idPasien', '$epiId', TO_DATE('$tgl', 'DD.MM.YYYY'), '$jamMulai', '$jamSelesai', 
    //             '$idAntrian', '$idHari', '$idJdldr', '$poliId', '$idDokter', '$jenis', '$urut')
    //     ";

    //     $insresult = $this->db->query($query);
    //     return $insresult;
    // }

    // function gettransid()
    // {
    //     $query =
    //         "
    //         SELECT TO_CHAR(SYSDATE, 'YYMMDD')||LPAD(COUNT(TRANSAKSI_ID)+1,4,0) TRID
    //         FROM SR01_PG_TRANSAKSI_HD
    //         WHERE LOKASI_ID = '001' AND TRUNC(CREATED_DATE)=TRUNC(SYSDATE)
    //         ";

    //     $recordset = $this->db->query($query);
    //     $recordset = $recordset->row();
    //     return $recordset;
    // }

    // function getreferencenumber($pasienId)
    // {
    //     $query = "
    //         SELECT (X.KODE || X.TGL || X.RM || LPAD(NOMOR, 5, 0)) TRID
    //         FROM (
    //             SELECT '7' KODE, TO_CHAR(SYSDATE, 'YYMMDD') TGL, 
    //             (SELECT INT_PASIEN_ID FROM SR01_GEN_PASIEN_MS
    //              WHERE LOKASI_ID = '001' AND PASIEN_ID = '$pasienId') RM,
    //             (SELECT COUNT(PASIEN_ID)+1 FROM SR01_PG_TRANSAKSI_HD
    //              WHERE LOKASI_ID = '001' AND PASIEN_ID = '$pasienId'
    //              AND TRUNC(CREATED_DATE)=TRUNC(SYSDATE)) NOMOR
    //             FROM DUAL
    //         ) X
    //              ";

    //     $recordset = $this->db->query($query);
    //     $recordset = $recordset->row();
    //     return $recordset;
    // }

    // function insertqrishd($trId, $pasienId, $epiId, $drId, $poliId, $resQris, $refNom, $billNumber, $nominal)
    // {
    //     $sql =
    //         "Insert into SIMRS_MANAGER.SR01_PG_TRANSAKSI_HD
    //             (LOKASI_ID, TRANSAKSI_ID, PASIEN_ID, EPISODE_ID, TGL_MASUK, DOKTER_ID, POLI_ID, KET1, KET2,
    //              TAGIHAN_CODE, TAGIHAN_ID, VA, BILLING, TGL_TAGIHAN, STATUS, AKTIF, CREATED_BY, CREATED_DATE)
    //         Values
    //             ('001', '$trId', '$pasienId', '$epiId', TRUNC(SYSDATE), '$drId', '$poliId', '$resQris', '$refNom', 
    //              'QRISREG', '$billNumber', '$billNumber', '$nominal', TRUNC(SYSDATE), '1', '1', 'PASIEN', SYSDATE)
    //         ";

    //     $insresult = $this->db->query($sql);
    //     return $insresult;
    // }

    
}

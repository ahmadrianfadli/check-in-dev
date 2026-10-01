<?php
class Modelsuratkontrol extends CI_Model
{
    function cekdatapasien($parameter)
    {
        $query = "
                    SELECT A.INT_PASIEN_ID, A.NAMA, A.NO_IDENTITAS, A.PASIEN_ID, A.EMAIL
                    FROM SR01_GEN_PASIEN_MS A
                    WHERE A.LOKASI_ID = '001' AND A.JENIS_PAS = 'I'
                    AND A.INT_PASIEN_ID = '" . $parameter . "' OR A.BPJS_NO = '" . $parameter . "'
                    ";
        $recordset = $this->db->query($query);
        $recordset = $recordset->row();
        return $recordset;
    }
    function cekdatakunjungan($pasienid)
    {
        $query = "
                    SELECT A.EPISODE_ID, B.SEP_NOMOR, B.SEP_POLIID, A.PASIEN_ID, B.NOKUNJUNGAN, B.POLIRUJUKAN_KDPOLI,
                    (SELECT COUNT(EPISODE_ID) FROM WEB_CO_SELESAI_PERIKSA WHERE EPISODE_ID = A.EPISODE_ID AND SHOW_ITEM = '1') SELESAIDOKTER, D.POLI_ID
                    FROM SR01_KEU_EPISODE A, SR01_KEU_BPJS_CVR B, WEB_CO_REGISTRASI_ONLINE_HD C, SR01_MED_POLI_MS D
                    WHERE A.LOKASI_ID = '001'
                    AND A.EPISODE_ID = B.EPISODE_ID
                    AND A.EPISODE_ID = C.EPISODE_ID
                    AND B.AKTIF = '1'
                    AND TRUNC(A.TGL_MASUK) <= TRUNC(SYSDATE)
                    AND A.PASIEN_ID = '{$pasienid}'
                    AND A.AKTIF = '1'
                    AND C.JENIS_TR <> '3'
                    AND D.POLI_BPJSID = B.POLIRUJUKAN_KDPOLI
                    AND D.POLI_ID = B.SEP_POLIID
                    ORDER BY A.TGL_MASUK DESC
                    ";
        $recordset = $this->db->query($query);
        $recordset = $recordset->row();
        return $recordset;
    }
    public function getpoli($polirujukan)
    {
        $query = "
            SELECT POLI_ID, REPLACE(KETERANGAN,'POLI ','') KETERANGAN, POLI_BPJSID
            FROM SR01_MED_POLI_MS
            WHERE
            LOKASI_ID = '001'
            AND POLI_BPJSID IS NOT NULL
            AND AKTIF = '1'
            --AND POLI_ID <> 'UGD01'
            AND POLI_BPJSID = '{$polirujukan}'
            ORDER BY KETERANGAN ASC
        ";
        return $this->db->query($query)->result();
    }
    public function getdokter($poli)
    {
        $query = "
            SELECT A.DOKTER_ID, A.PREFIX, A.NAMA
            FROM SR01_MED_DOKTER_MS A
            WHERE
            A.LOKASI_ID = '001'
            AND A.DOKTER_ID IN (SELECT DOKTER_ID FROM SR01_MED_POLI_DOKTER WHERE POLI_ID = '{$poli}')
            AND A.AKTIF = '1'
            AND A.PREFIX IS NOT NULL
            ORDER BY A.NAMA ASC
        ";
        return $this->db->query($query)->result();
    }
    public function insertSuratKontrol($noSurat, $pasienID, $poliAsal, $dokterAsal, $poliTujuan, $dokterTujuan, $noRujukan, $noSEP, $tanggal)
    {
        $query = "
            INSERT INTO WEB_CO_SURAT_KONTROL (
                PASIEN_ID, NO_SURAT, INT_NO_SURAT, TGL_KONTROL, POLI_ASAL, DOKTER_ASAL, 
                POLI_TUJUAN, DOKTER_TUJUAN, JENIS_EPISODE, KETERANGAN, CREATED_BY,
                JNS_SURAT, NO_RUJUKAN, NO_SEP
            ) VALUES (
                '{$pasienID}', '{$noSurat}', '{$noSurat}', TO_DATE('{$tanggal}','DD.MM.YYYY'), '{$poliAsal}', '{$dokterAsal}', 
                '{$poliTujuan}', '{$dokterTujuan}', 'O', 'Surat Kontrol', 'KIOSK',
                '2', '{$noRujukan}', '{$noSEP}'
            )
        ";

        $result = $this->db->query($query);

        return $result;
    }
}

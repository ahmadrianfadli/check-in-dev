<?php
class Modelcheckinkiosk extends CI_Model
{

    function checkbooking($parameter)
    {
        $query = "
                    SELECT Y.*
                        FROM(
                            SELECT X.*,
                                CASE
                                    WHEN TGL_MASUK = TRUNC(SYSDATE) THEN
                                    '2'
                                    ELSE
                                    CASE
                                        WHEN TGL_MASUK > TRUNC(SYSDATE) THEN
                                        '1'
                                        ELSE
                                        '0'
                                    END
                                END STATUS,
                                CASE
                                    WHEN JAMMINIMUMCHECKIN >= JAMSAATINI THEN
                                    '0'
                                    ELSE
                                    '1'
                                END STATUSCHECKIN
                            FROM(
                                SELECT A.PASIEN_ID, A.CREATED_BY, EPISODE_ID, AKTIF, BOOKING_ID, JAM_MULAI, JAM_SELESAI, HADIR, NO_RUJUKAN_INT, NO_KARTU_BPJS, NO_RUJUKAN, TGL_MASUK, TO_CHAR(TGL_HADIR,'DD.MM.YYYY HH24:MI:SS') TGLHADIR, TO_CHAR(TGL_MASUK,'DD.MM.YYYY') TGLKUNJUNGAN, URUT,
                                    TO_CHAR(SYSDATE,'DD.MM.YYYY HH24:MI:SS') JAMSAATINI,
                                    ( SELECT KETERANGAN FROM SR01_MED_POLI_MS WHERE LOKASI_ID='001' AND AKTIF='1' AND POLI_ID=A.POLI_ID)POLITUJUAN,A.POLI_ID,
                                        SR01_GET_SUFFIX(A.PASIEN_ID)NAMAPASIEN,
                                        ( SELECT INT_PASIEN_ID FROM SR01_GEN_PASIEN_MS WHERE LOKASI_ID='001' AND AKTIF='1' AND PASIEN_ID=A.PASIEN_ID )MRPASIEN,
                                        CASE
                                            WHEN SUBSTR(A.URUT,0,1) = 'A' THEN
                                            CASE
                                                WHEN A.POLI_ID='POLI0000000035' THEN
                                                TO_CHAR(TO_DATE(TO_CHAR(TGL_MASUK,'DD.MM.YYYY')||' '||JAM_MULAI||':00','DD.MM.YYYY HH24:MI:SS') - (90/(24*60)),'DD.MM.YYYY HH24:MI:SS')
                                                ELSE
                                                TO_CHAR(TO_DATE(TO_CHAR(TGL_MASUK,'DD.MM.YYYY')||' '||JAM_MULAI||':00','DD.MM.YYYY HH24:MI:SS') - (30/(24*60)),'DD.MM.YYYY HH24:MI:SS')
                                            END
                                            ELSE
                                            TO_CHAR(TO_DATE(TO_CHAR(TGL_MASUK,'DD.MM.YYYY')||' '||JAM_MULAI||':00','DD.MM.YYYY HH24:MI:SS') - (15/(24*60)),'DD.MM.YYYY HH24:MI:SS')
                                        END JAMMINIMUMCHECKIN,
                                        
                                        CASE
                                             WHEN A.REKANAN_ID = 'UMUM' AND A.CREATED_BY = 'E-PASIEN' THEN
                                                  (SELECT MAX(DECODE(B.STATUS, '3', 'Y', 'T')) FROM SR01_PG_TRANSAKSI_HD B
                                                   WHERE B.LOKASI_ID = A.LOKASI_ID AND B.EPISODE_ID = A.EPISODE_ID AND B.PASIEN_ID = A.PASIEN_ID)
                                             ELSE 'Y'
                                        END ISBAYAR
                                                                                        
                                FROM WEB_CO_REGISTRASI_ONLINE_HD A
                                WHERE A.LOKASI_ID='001'
                                AND   A.TGL_MASUK=TRUNC(SYSDATE)
                                ORDER BY CREATED_DATE DESC
                            )X
                        )Y
                        WHERE Y.BOOKING_ID='" . $parameter . "' OR NO_KARTU_BPJS='" . $parameter . "' OR NO_RUJUKAN='" . $parameter . "' OR NO_RUJUKAN_INT='" . $parameter . "'
                        OR MRPASIEN='" . $parameter . "'
                    ";

        $recordset = $this->db->query($query);
        $recordset = $recordset->row();
        return $recordset;
    }

    function updatekehadiran($bookingid, $pasienid, $episodeid, $data)
    {
        $sql =   $this->db->update("WEB_CO_REGISTRASI_ONLINE_HD", $data, array("BOOKING_ID" => $bookingid, "PASIEN_ID" => $pasienid, "EPISODE_ID" => $episodeid, "AKTIF" => "1"));
        return $sql;
    }
    public function cekconsent($pasienid)
    {
        $query = "
            SELECT PASIEN_ID
            FROM SR01_GEN_PASIEN_CONCENT
            WHERE LOKASI_ID = '001'
            AND AKTIF = '1'
            AND PASIEN_ID = '{$pasienid}'
        ";
        return $this->db->query($query)->row();
    }
    public function getpasien($pasienid)
    {
        $query = "
            SELECT PASIEN_ID, A.NAMA NAMAPASIEN, INT_PASIEN_ID, TO_CHAR(TGL_LAHIR,'YYYY-MM-DD') TGLLAHIR, NO_IDENTITAS, TEMPAT_LAHIR_TXT, ALAMAT1, ALAMAT2, BPJS_NO, ACC_SATUSEHAT, ACC_SATUSEHAT_FASKES,
            (SELECT COUNT(ID_CONCENT) FROM SR01_GEN_PASIEN_CONCENT B WHERE B.PASIEN_ID = A.PASIEN_ID AND AKTIF = '1') ADACONCENT,
            (SELECT HUBUNGAN_PASIEN FROM SR01_GEN_PASIEN_CONCENT B WHERE B.PASIEN_ID = A.PASIEN_ID AND AKTIF = '1') CONCENT_HUBPASIEN,
            (SELECT NILAI_KEPERCAYAAN FROM SR01_GEN_PASIEN_CONCENT B WHERE B.PASIEN_ID = A.PASIEN_ID AND AKTIF = '1') CONCENT_NILAI,
            (SELECT COUNT(EPISODE_ID) FROM SR01_KEU_EPISODE WHERE PASIEN_ID = A.PASIEN_ID AND AKTIF = '1') JMLEPISODE
            FROM SR01_GEN_PASIEN_MS A
            WHERE LOKASI_ID = '001'
            AND AKTIF = '1'
            AND PASIEN_ID = '{$pasienid}'
        ";
        return $this->db->query($query)->row();
    }
    public function getconcent($pasienid)
    {
        $query = "
            SELECT ID_CONCENT FROM SR01_GEN_PASIEN_CONCENT WHERE PASIEN_ID = '{$pasienid}' AND AKTIF = '1'
        ";
        return $this->db->query($query)->row();
    }
    public function kodeoto($table, $field, $panjang = null, $awalan = null)
    {
        $awalanlength = strlen($awalan);
        $query = $this->db->query("SELECT SUBSTR($field,-$panjang,$panjang) F FROM (SELECT $field FROM $table ORDER BY $field DESC) WHERE SUBSTR($field,1,$awalanlength) = '" . $awalan . "' AND ROWNUM=1");
        $row = $query->num_rows();
        $data = $query->row();
        if ($row > 0) {
            $kode = intval($data->F) + 1;
        } else {
            $kode = 1;
        }
        $batas = str_pad($kode, $panjang, "0", STR_PAD_LEFT);
        $kodetampil = $awalan . $batas;
        return $kodetampil;
    }
    public function insert($table, $data)
    {
        return $this->db->insert($table, $data);
    }
    public function update($table, $data, $where)
    {
        return $this->db->update($table, $data, $where);
    }
}

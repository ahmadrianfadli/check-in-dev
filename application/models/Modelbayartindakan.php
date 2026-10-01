<?php

class Modelbayartindakan extends CI_Model
{
    private $db_pg;
    public function __construct()
    {
        parent::__construct();
        $this->db_pg = $this->load->database('pg_prod', TRUE);
    }

    function cekdatapasien($parameter)
    {
        $query = "
                    SELECT A.INT_PASIEN_ID, A.NAMA, A.NO_IDENTITAS, A.PASIEN_ID, A.EMAIL
                    FROM SR01_GEN_PASIEN_MS A
                    WHERE A.LOKASI_ID = '001' AND A.JENIS_PAS = 'I'
                    AND A.INT_PASIEN_ID = '" . $parameter . "' OR A.NO_IDENTITAS = '" . $parameter . "'
                    ";

        $recordset = $this->db->query($query);
        $recordset = $recordset->row();
        return $recordset;
    }

    function cektransaksipasien($pasienid)
    {
        $query ="
            SELECT X.* FROM(
                SELECT A.EPISODE_ID, A.PASIEN_ID, 
                -- SR01_CEK_TRANSAKSI_BLMBAYAR(A.EPISODE_ID, '$pasienid') TOTAL,
                '1000' TOTAL,
                MAX(B.IS_EXE) IS_EXE
                FROM SR01_KEU_EPISODE A
                LEFT JOIN SR01_MED_POLI_MS B ON B.POLI_ID = A.POLI_ID
                WHERE A.LOKASI_ID = '001' AND A.AKTIF = '1'
                    AND A.PASIEN_ID = '" . $pasienid . "'
                    AND A.JENIS_EPISODE = 'O' AND A.STATUS_EPISODE <> '99'
                    AND TRUNC(A.TGL_TRANSAKSI) = TRUNC(SYSDATE)
                GROUP BY A.EPISODE_ID, A.PASIEN_ID
            ) X ORDER BY TOTAL DESC
        ";

        $recordset = $this->db->query($query);
        $recordset = $recordset->row();
        return $recordset;
    }

    function lastantrian($kode)
    {
        $query =
            "
                SELECT A.KODE, URUT, CREATED_DATE,
                        ( SELECT KETERANGAN FROM SR01_ANTRIAN_MS WHERE LOKASI_ID='001' AND AKTIF='1' AND KODE=A.KODE )KETERANGAN
                FROM SR01_ANTRIAN_PASIEN A
                WHERE A.LOKASI_ID='001'
                AND   A.AKTIF='1'
                AND   A.KODE = '" . $kode . "'
                AND   TRUNC(A.TANGGAL)=TRUNC(SYSDATE)
                AND   A.CREATED_DATE = ( SELECT MAX(CREATED_DATE) FROM SR01_ANTRIAN_PASIEN WHERE LOKASI_ID='001' AND KODE=A.KODE AND TRUNC(TANGGAL)=TRUNC(SYSDATE) )
                ORDER BY CREATED_DATE DESC
                    ";

        $recordset = $this->db->query($query);
        $recordset = $recordset->row();
        return $recordset;
    }

    function simpanantrian($data)
    {
        $sql = $this->db->insert("SR01_ANTRIAN_PASIEN", $data);
        return $sql;
    }

    function get_last_antrian($kode)
    {
        $sql = "
            SELECT *
            FROM SR01_ANTRIAN_PASIEN
            WHERE KODE = ?
            AND TRUNC(TANGGAL) = TRUNC(SYSDATE)
            ORDER BY URUT DESC
            FETCH FIRST 1 ROWS ONLY
        ";

        return $this->db->query($sql, [$kode])->row();
    }

    function simpanantrian_postgree($data)
    {
        $query = "
            INSERT INTO antrian_manager.sr01_antrian_pasien
            (
                TANGGAL,
                JENIS_ID,
                KODE,
                IP_ADDRESS,
                URUT,
                NAMA_PASIEN,
                INT_PASIEN_ID,
                PASIEN_ID
            )
            VALUES
            (
                CURRENT_DATE,
                ?,
                ?,
                ?,
                ?,
                ?,
                ?,
                ?
            )
        ";

        return $this->db_pg->query($query, [
            $data['JENIS_ID'],
            $data['KODE'],
            $data['IP_ADDRESS'],
            $data['URUT'],
            $data['NAMA_PASIEN'],
            $data['INT_PASIEN_ID'],
            $data['PASIEN_ID']
        ]);
    }
}
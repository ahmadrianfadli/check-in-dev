<?php

class Modelbayarpoli extends CI_Model {

    private $db_pg;
    public function __construct()
    {
        parent::__construct();
        $this->db_pg = $this->load->database('pg_prod', TRUE);
    }

    function cekdatapasien($parameter)
    {
        $query = "
            SELECT A.INT_PASIEN_ID, A.NAMA, A.NO_IDENTITAS, A.PASIEN_ID, A.EMAIL,
            TO_CHAR(A.TGL_LAHIR, 'DD.MM.YYYY') TGLLAHIR,
            CASE
                WHEN A.NO_SELULAR IS NULL THEN A.TELEPON
                ELSE A.NO_SELULAR
            END NOHP,
            SR01_GET_SUFFIX(A.PASIEN_ID)NAMAPASIEN
            FROM SR01_GEN_PASIEN_MS A
            WHERE A.LOKASI_ID = '001' AND A.JENIS_PAS = 'I'
            AND (A.INT_PASIEN_ID = '" . $parameter . "' OR A.NO_IDENTITAS = '" . $parameter . "' OR A.PASIEN_ID = '" . $parameter . "')
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
                SELECT NULL POLI_ID, '-PILIH POLI-' KETERANGAN, 1 URUT, NULL IS_EXE
                FROM DUAL
                UNION
                SELECT
                    A.POLI_ID, 
                    A.KETERANGAN||
                        CASE 
                            WHEN A.IS_EXE = 'Y' THEN ' (EKSEKUTIF)'
                            ELSE ''
                        END AS KETERANGAN, 
                    2 URUT, A.IS_EXE
                FROM SR01_MED_POLI_MS A
                LEFT JOIN 
                (
                    SELECT 
                        POLI_ID, 
                        MAX(DOKTER_ID), MAX(TANGGAL), 
                        MAX(AKTIF), MAX(JENIS), MAX(IS_EXE)
                    FROM SR01_MED_DOKTER_OFF
                    WHERE
                    AKTIF = '1' 
                    AND JENIS = '1'
                    AND TO_CHAR(TANGGAL,'DD.MM.YYYY') = '24.02.2026'
                    GROUP BY POLI_ID
                    ORDER BY POLI_ID ASC
                ) B ON B.POLI_ID = A.POLI_ID
                WHERE 
                A.LOKASI_ID = '001' AND A.AKTIF = '1'
                AND A.POLI_ID NOT IN ('APS', 'POLIFISIO', 'UGD01', 'KLINI0000000001', 'MEDIC0000000000', 'POLIFISOKUP', 'POLI0000000037', 'POLI0000000048', 'POLIFISWICARA','POLI0000000000','POLI0000000026','POLI0000000019','POLI0000000015')
            ) X
            ORDER BY X.URUT, X.KETERANGAN 
        ";

        $recordset = $this->db->query($query);
        $recordset = $recordset->result();
        return $recordset;
    }

    function cekdatapoli($poli_id)
    {
        $query = "
            SELECT 
                POLI_ID, KETERANGAN, IS_EXE
            FROM SR01_MED_POLI_MS
            WHERE AKTIF = '1'
            AND POLI_ID = '" . $poli_id . "'
        ";

        $recordset = $this->db->query($query);
        $recordset = $recordset->result_array();
        return $recordset;
    }

    function simpanantrian($data)
    {
        $sql =   $this->db->insert("SR01_ANTRIAN_PASIEN", $data);
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
                (TANGGAL, JENIS_ID, KODE, IP_ADDRESS, URUT, NAMA_PASIEN, INT_PASIEN_ID, PASIEN_ID, POLI_ID, NAMA_POLI)
            VALUES 
            (
                CURRENT_DATE,
                '".$data['JENIS_ID']."',
                '".$data['KODE']."',
                '".$data['IP_ADDRESS']."',
                '".$data['URUT']."',
                '".$data['NAMA_PASIEN']."',
                '".$data['INT_PASIEN_ID']."',
                '".$data['PASIEN_ID']."',
                '".$data['POLI_ID']."',
                '".$data['NM_POLI']."'
            )
        ";
        $sql = $this->db_pg->query($query);
        return $sql;
    }

    function lastantrian($kode)
    {
        $query =
            "
                        SELECT A.KODE, URUT, CREATED_DATE, A.TRANS_ID, 
                               ( SELECT KETERANGAN FROM SR01_ANTRIAN_MS WHERE LOKASI_ID='001' AND AKTIF='1' AND KODE=A.KODE ) KETERANGAN,
                               CASE
                                   WHEN A.PASIEN_ID IS NOT NULL THEN
                                        (SELECT B.NAMA FROM SR01_GEN_PASIEN_MS B
                                         WHERE B.LOKASI_ID = A.LOKASI_ID AND B.PASIEN_ID = A.PASIEN_ID)
                                   ELSE
                                        ''
                               END NAMA
                        FROM SR01_ANTRIAN_PASIEN A
                        WHERE A.LOKASI_ID='001'
                        AND   A.AKTIF='1'
                        AND   A.KODE='" . $kode . "'
                        --AND   TRUNC(A.TANGGAL)=TRUNC(SYSDATE)
                        AND   A.TANGGAL >= TRUNC(SYSDATE)
                        AND   A.TANGGAL <  TRUNC(SYSDATE) + 1
                        AND   A.CREATED_DATE = ( SELECT MAX(CREATED_DATE) FROM SR01_ANTRIAN_PASIEN WHERE LOKASI_ID='001' AND KODE=A.KODE AND TRUNC(TANGGAL)=TRUNC(SYSDATE) )
                        ORDER BY CREATED_DATE DESC
                    ";

        $recordset = $this->db->query($query);
        $recordset = $recordset->row();
        return $recordset;
    }
}

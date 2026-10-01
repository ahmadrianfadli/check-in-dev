<?php
class Modelformantrian extends CI_Model
{
    private $db_pg;
    public function __construct()
    {
        parent::__construct();
        $this->db_pg = $this->load->database('pg_prod', TRUE);
    }
	
    public function antrianms($kode)
    {
        $query = "
            SELECT *
            FROM SR01_ANTRIAN_MS
            WHERE KODE = '{$kode}'
        ";
        return $this->db->query($query)->row();
    }

    function cekdatapasien($parameter)
    {
        $query = "
                    SELECT A.INT_PASIEN_ID, A.NAMA, A.NO_IDENTITAS, A.PASIEN_ID, A.EMAIL,
                    CASE
                        WHEN A.NO_KARTUPROV IS NULL THEN A.BPJS_NO
                        ELSE A.NO_KARTUPROV        
                    END NOBPJS,
                    SR01_GET_SUFFIX(A.PASIEN_ID)NAMAPASIEN
                    FROM SR01_GEN_PASIEN_MS A
                    WHERE A.LOKASI_ID = '001' AND A.JENIS_PAS = 'I'
                    AND A.INT_PASIEN_ID = '" . $parameter . "' OR A.NO_IDENTITAS = '" . $parameter . "'
                    OR A.NO_KARTUPROV = '" . $parameter . "' OR A.BPJS_NO = '" . $parameter . "'
                    ";

        $recordset = $this->db->query($query);
        $recordset = $recordset->row();
        return $recordset;
    }

    function cektranslab($pasienId)
    {
        $query = "
                    SELECT A.TRANS_CO, A.PASIEN_ID, A.EPISODE_ID
                    FROM WEB_CO_LAB_DT A
                    WHERE A.SHOW_ITEM = '1' 
                    AND A.PASIEN_ID = '" . $pasienId . "'
                    AND TRUNC(A.TANGGAL) = TRUNC(SYSDATE)
                    GROUP BY A.TRANS_CO, A.PASIEN_ID, A.EPISODE_ID
                ";

        $recordset = $this->db->query($query);
        $recordset = $recordset->row();
        return $recordset;
    }

    function getjenisantrian($kode){
        $query = "
            SELECT 
                JENIS_ID
            FROM SR01_ANTRIAN_MS
            WHERE AKTIF = '1'
                AND KODE = '$kode'
        ";

        $recordset = $this->db->query($query);
        $recordset = $recordset->result_array();
        return $recordset;
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


    function cekspri($pasien_id){
        $query = "
            SELECT 
                A.TRANS_ID, A.EPISODE_ID, A.PASIEN_ID
            FROM WEB_CO_MINTA_RANAP A    
            WHERE A.LOKASI_ID = '001' 
            AND A.PASIEN_ID = '" . $pasien_id . "'
            AND TRUNC(A.TGL_MINTA) BETWEEN TRUNC(SYSDATE)-3 AND TRUNC(SYSDATE)
            AND A.AKTIF = '1'
            AND A.STATUS = '0'
        ";

        $recordset = $this->db->query($query);
        $recordset = $recordset->row();
        return $recordset;
    }
}

<?php
class Modelantriangeneral extends CI_Model
{
    private $db_pg;
    public function __construct()
    {
        parent::__construct();
        $this->db_pg = $this->load->database('pg_prod', TRUE);
    }

    function cektransaksipasien($pasienId)
    {
        $query =
            "
                        SELECT A.EPISODE_ID, A.PASIEN_ID, SR01_CEK_TRANSAKSI_BLMBAYAR(A.EPISODE_ID, '$pasienId') TOTAL
                        FROM SR01_KEU_EPISODE A
                        WHERE A.LOKASI_ID = '001' AND A.AKTIF = '1'
                        AND A.PASIEN_ID = '" . $pasienId . "'
                        AND A.JENIS_EPISODE = 'O' AND A.STATUS_EPISODE <> '99'
                        --AND TRUNC(A.TGL_TRANSAKSI) = TRUNC(SYSDATE)
                        AND   A.TGL_TRANSAKSI >= TRUNC(SYSDATE)
                        AND   A.TGL_TRANSAKSI <  TRUNC(SYSDATE) + 1
                        GROUP BY A.EPISODE_ID, A.PASIEN_ID
                    ";

        $recordset = $this->db->query($query);
        $recordset = $recordset->row();
        return $recordset;
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
                (tanggal, jenis_id, kode, urut, ip_address)
            VALUES 
            (
                CURRENT_DATE,
                '".$data['JENIS_ID']."',
                '".$data['KODE']."',
                '".$data['URUT']."',
                '".$data['IP_ADDRESS']."'
            )
        ";
        $sql = $this->db_pg->query($query);
        return $sql;
    }

    public function waktuambil($paramid)
    {
        $this->db->select('PARAM_ID,KETERANGAN,NILAI');
        $this->db->from('SR01_SYS_PARAMETER');
        $this->db->where('PARAM_ID', $paramid);
        return $this->db->get()->row();
    }

    function getantrian($tid){
        $query =
            "
                        SELECT A.KODE, URUT, CREATED_DATE, A.TRANS_ID, TO_CHAR(A.CREATED_DATE, 'DD/MM/YYYY HH24:MI:SS') TANGGAL_CETAK, 
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
                        --AND   TRUNC(A.TANGGAL)=TRUNC(SYSDATE)
                        AND   A.TANGGAL >= TRUNC(SYSDATE)
                        AND   A.TANGGAL <  TRUNC(SYSDATE) + 1
                        AND   A.CREATED_DATE = ( SELECT MAX(CREATED_DATE) FROM SR01_ANTRIAN_PASIEN WHERE LOKASI_ID='001' AND KODE=A.KODE AND TANGGAL >= TRUNC(SYSDATE) AND TANGGAL <  TRUNC(SYSDATE) + 1)
                        AND   A.TRANS_ID = '". $tid ."'
                        ORDER BY CREATED_DATE DESC
                    ";

        $recordset = $this->db->query($query);
        $recordset = $recordset->row();
        return $recordset;
    }

    function updateantrian($tid){
        $query =
            "
                        UPDATE SR01_ANTRIAN_PASIEN
                        SET
                            PRINT = '1'
                        WHERE LOKASI_ID='001'
                        AND   AKTIF='1'
                        --AND   TRUNC(TANGGAL)=TRUNC(SYSDATE)
                        AND   TANGGAL >= TRUNC(SYSDATE)
                        AND   TANGGAL <  TRUNC(SYSDATE) + 1
                        AND   TRANS_ID = '". $tid ."'
                    ";

        $recordset = $this->db->query($query);
        return $recordset;
    }
}

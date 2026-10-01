<?php
class Modelcetakradiologi extends CI_Model{

    function __construct(){
        parent::__construct();
		$this->db = $this->load->database('production', TRUE);
    }

    public function ceknorm($norm){
        $query = "SELECT * FROM SR01_GEN_PASIEN_MS WHERE INT_PASIEN_ID = '". $norm ."' OR NO_IDENTITAS = '". $norm ."' ";

        $recordset = $this->db->query($query);
        $recordset = $recordset->row();
        return $recordset;
    }

    public function cektgllahir($norm, $tgllahir){
        $query = "SELECT * FROM SR01_GEN_PASIEN_MS WHERE (INT_PASIEN_ID = '". $norm ."' OR NO_IDENTITAS = '". $norm ."')  AND TO_CHAR(TGL_LAHIR, 'DD.MM.YYYY') =  '". $tgllahir ."'";

        $recordset = $this->db->query($query);
        $recordset = $recordset->row();
        return $recordset;
    }

    public function getPasienRadDetail($pasienID, $tglkunjungan)
    {
        $query = "
            SELECT 
                A.EPISODE_ID, A.PASIEN_ID, A.TRANS_ID, A.TRANS_CO,
                TO_CHAR(A.TANGGAL, 'DD.MM.YYYY') TANGGAL, 
                SR01_GET_SUFFIX(B.PASIEN_ID) NAMA_PASIEN,
                LAS_MANAGER.HITUNG_UMUR(TO_DATE(TO_CHAR(B.TGL_LAHIR, 'YYYY-MM-DD'), 'YYYY-MM-DD'), SYSDATE) AS UMUR,
                B.INT_PASIEN_ID, B.SEX_ID, TO_CHAR(B.TGL_LAHIR,'DD.MM.YYYY') AS TGLLAHIR,
                B.ALAMAT1,
                C.NAMA AS NAMA_DOKTER,
                E.NAMA AS NAMA_DOKTER_DPJP,
                CASE
                    WHEN A.POLI_ID IS NOT NULL THEN F.KETERANGAN 
                    ELSE A.RUANG_ID 
                END AS NAMA_RUANGAN,
                G.NAMA AS REKANAN
            FROM SR01_WORKLIST_RAD A
            INNER JOIN SR01_GEN_PASIEN_MS B ON A.PASIEN_ID = B.PASIEN_ID
            INNER JOIN SR01_MED_DOKTER_MS C ON A.DOKTER_ID = C.DOKTER_ID AND C.LOKASI_ID = '001'
            INNER JOIN SR01_KEU_EPISODE D ON A.EPISODE_ID = D.EPISODE_ID AND A.PASIEN_ID = D.PASIEN_ID AND D.LOKASI_ID = '001'
            INNER JOIN SR01_MED_DOKTER_MS E ON D.DOKTER_ID = E.DOKTER_ID AND E.LOKASI_ID = '001'
            LEFT JOIN SR01_MED_POLI_MS F ON A.POLI_ID = F.POLI_ID AND F.LOKASI_ID = '001'
            INNER JOIN SR01_KEU_REKANAN_MS G ON A.REKANAN_ID = G.REKANAN_ID AND G.LOKASI_ID = '001'
            WHERE   
                A.LOKASI_ID = '001'
                AND A.AKTIF = '1'
                AND A.PASIEN_ID = '". $pasienID ."'
                AND TO_CHAR(A.TANGGAL, 'DD.MM.YYYY') =  '". $tglkunjungan ."'
        ";

        $result = $this->db->query($query)->row_array();

        return $result;
    }

    public function getDetailLama($pasienID, $tglkunjungan)
    {
        $query = "
            SELECT 
                A.*, B.RESULT_RTF,
                TRIM(REGEXP_REPLACE((SUBSTR(REGEXP_REPLACE(GETLONG2('WEB_HASIL_RIS', 'RESULT_RTF', 'EPISODE_ID = ''' || A.EPISODE_ID || ''' AND TRANS_ID =  ''' || A.TRANS_ID || ''' '), (SELECT KETERANGAN FROM SR01_GEN_GLOBAL_MS WHERE GLOBAL_ID = 'UTIL001' AND JENIS_ID = 'UTIL'), ''),0,LENGTH (REGEXP_REPLACE(GETLONG2('WEB_HASIL_RIS', 'RESULT_RTF', 'EPISODE_ID = ''' || A.EPISODE_ID || ''' AND TRANS_ID =  ''' || A.TRANS_ID || ''' '), (SELECT KETERANGAN FROM SR01_GEN_GLOBAL_MS WHERE GLOBAL_ID = 'UTIL001' AND JENIS_ID = 'UTIL'), ''))-1)), '^(['||chr(10)||chr(11)||']{2,})','',1,0)) RESULTNYA
            FROM (
                SELECT A.EPISODE_ID, B.LOKASI_ID, A.TEST_ID, B.TRANS_RAD TRANS_ID, C.INT_PASIEN_ID MRN, D.NAMA_LAYAN1 NAMATEST
                FROM WEB_CO_RAD_DT A
                INNER JOIN SR01_WORKLIST_RAD_DT B ON 
                    A.EPISODE_ID = B.EPISODE_ID
                    AND A.TRANS_CO = B.TRANS_CO
                    AND B.AKTIF = '1'
                INNER JOIN SR01_GEN_PASIEN_MS C ON C.LOKASI_ID = '001' AND A.PASIEN_ID = C.PASIEN_ID
                INNER JOIN SR01_KEU_LAYAN_MS D ON D.LOKASI_ID = '001' AND A.TEST_ID = D.LAYAN_ID
                WHERE 
                    A.PASIEN_ID = '". $pasienID ."'
                    AND TO_CHAR(A.TANGGAL, 'DD.MM.YYYY') =  '". $tglkunjungan ."'
                    AND A.SHOW_ITEM = '1'
            ) A
            INNER JOIN WEB_HASIL_RIS B ON
                A.LOKASI_ID = B.LOKASI_ID
                AND A.EPISODE_ID = B.EPISODE_ID
                AND A.TRANS_ID = B.TRANS_ID
                AND A.MRN = B.MRN
                AND B.SHOW_ITEM = '1'
        ";

        $result = $this->db->query($query)->result_array();

        return $result;
    }

    public function getDetailBaru($pasienID, $episodeID, $transCO)
    {
        $query = "
            SELECT A.NO_REGISTER,LOKASI_ID, KODE_PEMERIKSAAN, A.NO_RONTGEN, A.NO_RM, NAMA_PEMERIKSAAN, EXPERTISE_HTML_FINDING ||''|| EXPERTISE_HTML_CONCLUSION RESULTNYA
            FROM RAD_MANAGER.RIS_IN A
            INNER JOIN SR01_GEN_PASIEN_MS B ON A.NO_RM = B.INT_PASIEN_ID
            INNER JOIN RAD_MANAGER.RIS_OUT C ON A.NO_RONTGEN = C.NO_RONTGEN
            WHERE 
            B.PASIEN_ID = '{$pasienID}'
            AND A.NO_REGISTER = '{$episodeID}'
            AND A.ID_DETAIL_RADIOLOGI = '{$transCO}'
        ";
        $result = $this->db->query($query)->result_array();
        return $result;
    }

    public function getRisLama($norm, $idTrans)
    {
        $query = "
            SELECT 
                A.RESULT_RTF,A.ADDENDUM_RTF, B.NAMA,TO_CHAR(A.CREATED_DATE,'DD-MM-YYYY HH24:MI:SS') TGL_ACC
            FROM WEB_HASIL_RIS A
            JOIN RP2_USER_MS B ON A.CREATED_BY = 'RP2_'||B.NIK
            WHERE 
                TRANS_ID='" . $idTrans . "'
                AND MRN='" . $norm . "'
        ";
        echo "<pre>"; print_r($query); echo "</pre>";

        $result = $this->db->query($query)->row_array();

        return $result;
    }

    public function getRisBaru($norm, $episodeId)
    {
        $query = "
            SELECT 
                A.NAMA_PEMERIKSAAN, A.KLINIS,
                B.NO_RM, B.NO_RONTGEN, B.NO_REGISTER,
                TO_CHAR(B.ADMIN_DATETIME_START, 'DD.MM.YYYY HH24:MI:SS') ADMIN_DATETIME_START, 
                B.NAMA_RADIOLOG
            FROM RAD_MANAGER.RIS_IN A
            LEFT JOIN RAD_MANAGER.RIS_OUT B ON A.NO_REGISTER = B.NO_REGISTER
            WHERE 
                B.NO_RM = '". $norm ."'
                AND B.NO_REGISTER = '". $episodeId ."'
        ";

        $result = $this->db->query($query)->row_array();

        return $result;
    }

    public function addDocument($PASIEN_ID,$DIR_FILE,$NM_FILE,$CREATED_DATE,$CREATED_BY,$TANGGAL)
    {
        $query = "INSERT INTO SR01_DOCUMENT (PASIEN_ID, JNS_DOC, DIR_FILE, NM_FILE, CREATED_DATE, CREATED_BY, TANGGAL, SHOW_ITEM, LOKASI_ID, AUTO_PDF, ESIGN) 
                    VALUES (
                        '". $PASIEN_ID ."',
                        '0000R',
                        '". $DIR_FILE ."',
                        '". $NM_FILE ."',
                        TO_DATE('". $CREATED_DATE ."', 'DD.MM.YYYY HH24:MI:SS'),
                        '". $CREATED_BY ."',
                        TO_DATE('". $TANGGAL ."', 'DD.MM.YYYY HH24:MI:SS'),
                        '1',
                        '001',
                        'Y',
                        'N'
                    )";
        $result = $this->db->query($query);
        return;
    }

    public function getDocument($PASIEN_ID, $TANGGAL)
    {
        $query = "
            SELECT
                PASIEN_ID, JNS_DOC, EPISODE_ID, DIR_FILE, NM_FILE, ESIGN, TO_CHAR(TANGGAL, 'DD.MM.YYYY') TANGGAL
            FROM SR01_DOCUMENT
			WHERE 
                PASIEN_ID = '". $PASIEN_ID ."'
                AND TO_CHAR(TANGGAL, 'DD.MM.YYYY') =  '". $TANGGAL ."'
				AND JNS_DOC = '0000R'";

        $result = $this->db->query($query)->result_array();
        return $result;
    }

    public function updateEsign($PASIEN_ID, $TANGGAL)
    {
        $query = "
                UPDATE SR01_DOCUMENT 
                    SET ESIGN = 'Y'
                WHERE
                    PASIEN_ID = '". $PASIEN_ID ."'
                    AND TO_CHAR(TANGGAL, 'DD.MM.YYYY') = '". $TANGGAL ."'
				    AND JNS_DOC = '0000R'";
        $result = $this->db->query($query);
        return;
    }

    public function reUpdateEsign($PASIEN_ID, $TANGGAL)
    {
        $query = "
                UPDATE SR01_DOCUMENT 
                    SET ESIGN = 'N'
                WHERE
                    PASIEN_ID = '". $PASIEN_ID ."'
                    AND TO_CHAR(TANGGAL, 'DD.MM.YYYY') = '". $TANGGAL ."'
				    AND JNS_DOC = '0000R'";
        $result = $this->db->query($query);
        return;
    }

}
?>
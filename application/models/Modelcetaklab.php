<?php
class Modelcetaklab extends CI_Model{

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

    public function getAll($pasienid, $tglkunjungan){
        $query = "
            SELECT 
                A.*, TO_CHAR(A.TANGGAL, 'DD.MM.YYYY') TGL, '1' JENIS,
                TO_CHAR(B.TGL_MASUK, 'DD.MM.YYYY') TGL_MASUK, B.JENIS_EPISODE,
                C.NAMA, D.NAMA AS NAMA_DOKTER, E.KETERANGAN AS NAMA_POLI, F.NAMA AS NAMA_REKANAN
            FROM SR01_WORKLIST_LAB A
            LEFT JOIN SR01_KEU_EPISODE B ON
                B.LOKASI_ID = '001'
                AND B.EPISODE_ID = A.EPISODE_ID
                AND B.AKTIF = '1'
            LEFT JOIN SR01_GEN_PASIEN_MS C ON A.PASIEN_ID = C.PASIEN_ID
            LEFT JOIN SR01_MED_DOKTER_MS D ON B.DOKTER_ID = D.DOKTER_ID
            LEFT JOIN SR01_MED_POLI_MS E ON B.POLI_ID = E.POLI_ID
            LEFT JOIN SR01_KEU_REKANAN_MS F ON B.REKANAN_ID = F.REKANAN_ID
            WHERE 
                A.AKTIF = '1'
                AND A.STATUS = '1'
                AND A.PASIEN_ID = '". $pasienid ."'
                AND TO_CHAR(B.TGL_MASUK, 'DD.MM.YYYY') =  '". $tglkunjungan ."'
            ORDER BY A.TANGGAL DESC
        ";

        $result = $this->db->query($query)->result_array();
        return $result;
    }

    function isCetak($episodeID, $sampelID, $poliID){
        $sql = "SELECT Z.*
                FROM(
                SELECT DISTINCT A.SAMPEL_ID, A.REGISTRASI_ID, B.PASIEN_ID, B.REKANAN_ID, B.POLI_ID, B.TANGGAL
                ,  (SELECT MIN(A.TGL_MASUK)
                    FROM(
                        SELECT C.EPISODE_ID, C.PASIEN_ID, C.TGL_MASUK FROM SR01_KEU_EPISODE C, SR01_MED_PRWT_TR D WHERE C.PASIEN_ID = (SELECT PASIEN_ID FROM SR01_KEU_EPISODE WHERE EPISODE_ID = '". $episodeID ."') AND C.POLI_ID = '". $poliID ."' AND C.AKTIF = '1' AND C.STATUS_EPISODE <> '99' AND C.EPISODE_ID = D.EPISODE_ID AND C.PASIEN_ID = D.PASIEN_ID AND D.STATUS = '1'
                        AND C.TGL_MASUK > (SELECT C1.TGL_MASUK FROM SR01_KEU_EPISODE C1 WHERE C1.EPISODE_ID =  '". $episodeID ."' )
                    )A
                    ) TGL_NEXT
                FROM SAMPEL A, SR01_WORKLIST_LAB B
                WHERE A.SHOW_ITEM = '1'
                AND B.AKTIF = '1'
                AND A.REGISTRASI_ID = B.EPISODE_ID
                AND B.REKANAN_ID = 'BPJS'
                AND B.POLI_ID IS NOT NULL
                AND B.POLI_ID <> 'UGD01'
                AND A.REGISTRASI_ID = '". $episodeID ."'
                AND A.SAMPEL_ID = '". $sampelID ."'
                )Z
                WHERE Z.TGL_NEXT IS NULL";

        $result = $this->db->query($sql)->result_array();
        return $result;
    }

    public function getDetailLab($pasienID, $sampelID)
    {
        $pasienID = '10-' . $pasienID;

        $query = "
            SELECT 
                '.' || SUBSTR(LPAD(' ', ((ITEM_LEVEL - 2) * 2)) || C.NAME1, 1, 400) AS NAMA_TES, 
                C.NAME1,
                C.TEST_ID, 
                C.ITEM_LEVEL, 
                C.UNITS, 
                C.SUB_STRUCTURE_TYPE,
                C.REPORT_ID,
                C.RESULT_ID,
                (
                    SELECT TO_CHAR(E.DI_ACC_TGL, 'DD-MM-YYYY HH24:MM:SS') 
                    FROM SAMPEL E 
                    WHERE 
                        E.SAMPEL_ID = D.SAMPEL_ID 
                        AND E.PASIEN_ID = D.PASIEN_ID
                ) ACC_TGL,
                D.HASIL_NOTE_ID,
                --D.SIGN||D.RESULT_VALUE AS RESULT_VALUE,
                --DECODE(C.RESULT_ID,'1',D.SIGN||D.RESULT_VALUE||' ('||SUBSTR(GET_INTERPRETASI_AMBANG_BATAS(D.DEMOGRAPHIC_ID, D.SAMPEL_ID, D.TEST_ID, D.REFERENCE_ID, D.REFERENCE_ID_AGE, D.RESULT_VALUE, D.SIGN),1,15)||')', D.SIGN||D.RESULT_VALUE) AS RESULT_VALUE, --D.SIGN DIHILANGKAN SUPAYA BISA AKSES KE DB LAMA
                DECODE(
                    CRITICALAPP(D.SAMPEL_ID,D.PASIEN_ID,D.TEST_ID), '1',
                    (DECODE(C.RESULT_ID, '1', D.SIGN || D.RESULT_VALUE || ' (' || SUBSTR(GET_INTERPRETASI_AMBANG_BATAS(D.DEMOGRAPHIC_ID, D.SAMPEL_ID, D.TEST_ID, D.REFERENCE_ID, D.REFERENCE_ID_AGE, D.RESULT_VALUE, D.SIGN),1,15)||')', D.SIGN||D.RESULT_VALUE)), '0',
                    'Hasil Tidak Ditampilkan'
                ) AS RESULT_VALUE, --D.SIGN DIHILANGKAN SUPAYA BISA AKSES KE DB LAMA
                G.HASIL_TEKS HASIL_TEKS1,
                TRIM(CHR(10) FROM TRIM(CHR(13) FROM TRIM(CHR(10) FROM SUBSTR(REGEXP_REPLACE(G.HASIL_TEKS, '(\{.*\})|}|(\\\S+) ', ''),0,LENGTH(REGEXP_REPLACE(G.HASIL_TEKS, '(\{.*\})|}|(\\\S+) ', ''))-0))))HASIL_TEKS2,
                D.RESULT_FLAG,
                D.FLAG,
                D.DONE_STATUS,
                D.SAMPEL_ID,
                D.PASIEN_ID,
                D.CREATED_DATE,
                TO_CHAR(D.CREATED_DATE, 'DD.MM.YYYY HH24:MI:SS') CREATED_DATE2,
                SUBSTR(GETNORMAL_STATUS(C.RESULT_ID,  D.REFERENCE_ID, D.REFERENCE_ID_AGE, D.TEST_ID, '{$pasienID}'), 1, 100) AS ANGKA_NORMAL,
                SUBSTR(GETRUJUKANLUAR('{$pasienID}', '{$sampelID}', 0, C.TEST_ID,D.NILAI_RUJUKAN_ID), 1, 100) AS RUJUK_LUAR,
                (
                    SELECT COUNT(A1.SAMPEL_ID) 
                    FROM DT_TES_ORDER A1, MS_TEST_LAB B1, SAMPEL C1 
                    WHERE A1.PASIEN_ID = '{$pasienID}'
                        AND A1.DONE_STATUS = '04'
                        AND A1.SHOW_ITEM = '1'
                        AND B1.TEST_ID = A1.TEST_ID
                        AND A1.TEST_ID = C.TEST_ID
                        AND C1.DI_ACC_OLEH IS NOT NULL
                        AND C1.SAMPEL_ID = A1.SAMPEL_ID
                        AND C1.SHOW_ITEM = '1'
                        AND A1.SAMPEL_ID <> '{$sampelID}'
                ) JML,
                U.NAMA,
                
                (
                    SELECT TRIM(REGEXP_REPLACE((SUBSTR(REGEXP_REPLACE(X.KOMENTAR, '(\{.\})|}|(\\\S+)', ''),0,LENGTH (REGEXP_REPLACE(X.KOMENTAR, '(\{.\})|}|(\\\S+)', ''))-1)),'[[:space:]]{2,}',' '))
                    FROM MS_KOMENTAR X
                    WHERE X.KOMENTAR_ID = E.KODE_ID
                ) KOMENTAR
                
            FROM MS_TEST_LAB C, DT_TES_ORDER D, MS_PENGGUNA U, MS_AUTO_KOMENTAR E, HASIL_NOTE G
            WHERE C.TEST_ID IN (
                    SELECT DISTINCT(A.TEST_ID)
                    FROM MS_TEST_LAB A 
                    START WITH A.TEST_ID IN (
                        SELECT X.TEST_ID 
                        FROM DT_TES_ORDER X,SAMPEL Y
                        WHERE X.SAMPEL_ID = '{$sampelID}'
                            AND X.PASIEN_ID = '{$pasienID}' 
                            AND X.SHOW_ITEM = '1' 
                            AND TO_NUMBER(X.DONE_STATUS) = 4
                            AND Y.SAMPEL_ID = X.SAMPEL_ID 
                            AND Y.PASIEN_ID = X.PASIEN_ID 
                            AND X.LAST_UPDATED_DATE <= Y.DI_ACC_TGL 
                            AND Y.SHOW_ITEM = '1'
                    )
                    CONNECT BY PRIOR A.OWNER=A.TEST_ID
                )
                AND C.SHOW_ITEM='1'
                AND D.SAMPEL_ID (+) = '{$sampelID}'
                AND D.PASIEN_ID (+) = '{$pasienID}'
                AND D.TEST_ID (+) = C.TEST_ID
                AND C.OWNER <> 'MASTER TES'
                AND TO_NUMBER(D.DONE_STATUS (+)) = 4 
                AND C.SUB_STRUCTURE_TYPE <> 0
                AND U.NAMAUSER (+) = REPLACE(D.LAST_UPDATED_BY, '_THEBREW')
                AND E.TEST_ID (+) = D.TEST_ID 
                AND G.HASIL_NOTE_ID(+) = D.HASIL_NOTE_ID
            ORDER BY C.SORT_PRIORITY
        ";

        $result = $this->db->query($query)->result_array();

        return $result;
    }

    public function getPasienLabDetail($pasienID, $sampelID)
    {
        $query = "
            SELECT 
                A.PASIEN_ID, A.EPISODE_ID, A.SAMPEL_ID,
                SR01_GET_SUFFIX(B.PASIEN_ID) NAMA_PASIEN,
                B.INT_PASIEN_ID, B.SEX_ID, TO_CHAR(B.TGL_LAHIR,'DD.MM.YYYY') AS TGLLAHIR,
                B.ALAMAT1,
                C.NAMA AS NAMA_DOKTER,
                TO_CHAR(D.TGL_MASUK, 'DD.MM.YYYY') TGL_MASUK,
                E.NAMA AS NAMA_DOKTER_DPJP,
                CASE
                    WHEN A.POLI_ID IS NOT NULL THEN F.KETERANGAN 
                    ELSE A.RUANG_ID 
                END AS NAMA_RUANGAN,
                G.NAMA AS REKANAN,
                LAS_MANAGER.HITUNG_UMUR(TO_DATE(TO_CHAR(B.TGL_LAHIR, 'YYYY-MM-DD'), 'YYYY-MM-DD'), SYSDATE) AS UMUR,
                TO_CHAR(A.CREATED_DATE, 'DD-MM-YYYY') AS FULL_TGL_AMBIL,
                TO_CHAR(A.CREATED_DATE, 'HH24:MM:SS') AS FULL_JAM_AMBIL,
                TO_CHAR(A.CREATED_DATE, 'DAY') AS HARI_AMBIL,
                TO_CHAR(A.CREATED_DATE, 'DD') AS TGL_AMBIL,
                TO_CHAR(A.CREATED_DATE, 'MM') AS BULAN_AMBIL,
                TO_CHAR(A.CREATED_DATE, 'YYYY') AS TAHUN_AMBIL,
                TO_CHAR(H.DI_ACC_TGL,'DD-MM-YYYY') AS FULL_TGL_ACC,
                TO_CHAR(H.DI_ACC_TGL,'HH24:MM:SS') AS FULL_JAM_ACC,
                H.DI_ACC_OLEH,
                TO_CHAR(I.TGL_AMBIL, 'DD.MM.YYYY HH24:MI:SS') AS SAMPLING_DT_TGL_AMBIL,
                TO_CHAR(J.DI_ACC_TGL, 'DD.MM.YYYY HH24:MI:SS') AS SAMPLE_TGL_ACC
            FROM SR01_WORKLIST_LAB A
            INNER JOIN SR01_GEN_PASIEN_MS B ON A.PASIEN_ID = B.PASIEN_ID
            INNER JOIN SR01_MED_DOKTER_MS C ON A.DOKTER_ID = C.DOKTER_ID AND C.LOKASI_ID = '001'
            INNER JOIN SR01_KEU_EPISODE D ON A.EPISODE_ID = D.EPISODE_ID AND A.PASIEN_ID = D.PASIEN_ID AND D.LOKASI_ID = '001'
            INNER JOIN SR01_MED_DOKTER_MS E ON D.DOKTER_ID = E.DOKTER_ID AND E.LOKASI_ID = '001'
            LEFT JOIN SR01_MED_POLI_MS F ON A.POLI_ID = F.POLI_ID AND F.LOKASI_ID = '001'
            INNER JOIN SR01_KEU_REKANAN_MS G ON A.REKANAN_ID = G.REKANAN_ID AND G.LOKASI_ID = '001'
            INNER JOIN SAMPEL H ON A.SAMPEL_ID = H.SAMPEL_ID AND H.SHOW_ITEM = '1'
            LEFT JOIN SAMPLING_DT I ON A.SAMPEL_ID = I.SAMPEL_ID
            LEFT JOIN SAMPEL J ON A.SAMPEL_ID = J.SAMPEL_ID
            WHERE   
                A.LOKASI_ID = '001'
                AND A.AKTIF = '1'
                AND A.PASIEN_ID = ?
                AND A.SAMPEL_ID = ?
        ";


        $result = $this->db->query($query, [$pasienID, $sampelID])->row_array();

        return $result;
    }

    public function getDetailLabPemeriksa($sampelID)
    {
        $query = "SELECT DISTINCT A.SAMPEL_ID,(SELECT MAX(B.LAST_UPDATED_DATE) FROM DT_TES_ORDER B WHERE A.SAMPEL_ID=B.SAMPEL_ID AND B.SHOW_ITEM='1')JAM,
                (SELECT A.NAMA FROM MS_PENGGUNA A WHERE A.NAMAUSER=NVL(SUBSTR(B.LAST_UPDATED_BY,0, INSTR(B.LAST_UPDATED_BY,'_')-1),B.LAST_UPDATED_BY))NAMA
                FROM SAMPEL A,DT_TES_ORDER B
                WHERE A.SAMPEL_ID='". $sampelID ."'
                AND A.SHOW_ITEM='1'
                AND B.SHOW_ITEM='1'
                AND B.DONE_STATUS='04'
                AND A.SAMPEL_ID=B.SAMPEL_ID";

        $result = $this->db->query($query)->result_array();

        return $result;
    }

    function getWh($id)
    {
        $query = "
            SELECT 
                 A.VALUE
            FROM WEB_CO_WHOLE_SYSTEM A 
            WHERE 
                A.WH_SYS_ID = ?
        ";
        $query = $this->db->query($query, [$id]);
        $row = $query->row_array();

        return $row;
    }

    public function getWhLab($key)
    {
        $query = "
            SELECT A.VALUE 
            FROM LAS_MANAGER.WHOLE_SYSTEM A 
            WHERE A.WH_SYS_ID = ?
        ";
        $result = $this->db->query($query, [$key])->row_array();

        return $result;
    }

    public function addDocument($PASIEN_ID,$DIR_FILE,$NM_FILE,$CREATED_DATE,$CREATED_BY,$TANGGAL)
    {
        $query = "INSERT INTO SR01_DOCUMENT (PASIEN_ID, JNS_DOC, DIR_FILE, NM_FILE, CREATED_DATE, CREATED_BY, TANGGAL, SHOW_ITEM, LOKASI_ID, AUTO_PDF, ESIGN) 
                    VALUES (
                        '". $PASIEN_ID ."',
                        '0000G',
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
				AND JNS_DOC = '0000G'";

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
				    AND JNS_DOC = '0000G'";
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
				    AND JNS_DOC = '0000G'";
        $result = $this->db->query($query);
        return;
    }
}
?>
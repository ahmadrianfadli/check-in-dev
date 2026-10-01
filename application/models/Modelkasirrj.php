<?php
class Modelkasirrj extends CI_Model
{

    private $db_pg;
    public function __construct()
    {
        parent::__construct();
        $this->db_pg = $this->load->database('pg_prod', TRUE);
    }

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



    function cekdatapasien($parameter)
    {
        $query = "
                    SELECT A.INT_PASIEN_ID, A.NAMA, A.NO_IDENTITAS, A.PASIEN_ID, A.EMAIL,
                    SR01_GET_SUFFIX(A.PASIEN_ID)NAMAPASIEN
                    FROM SR01_GEN_PASIEN_MS A
                    WHERE A.LOKASI_ID = '001' AND A.JENIS_PAS = 'I'
                    AND A.INT_PASIEN_ID = '" . $parameter . "' OR A.NO_IDENTITAS = '" . $parameter . "'
                    ";

        $recordset = $this->db->query($query);
        $recordset = $recordset->row();
        return $recordset;
    }

    function cektransaksi($pasienid)
    {
        $query = "
                    SELECT A.EPISODE_ID
                    FROM SR01_KEU_EPISODE A
                    WHERE A.LOKASI_ID='001'
                    AND   A.AKTIF='1'
                    AND   A.JENIS_EPISODE='O'
                    AND   A.STATUS_EPISODE='00'
                    AND   A.REKANAN_ID='UMUM'
                    AND   A.POLI_ID NOT IN ('UGD01','APS')
                    AND   A.TGL_MASUK=TRUNC(SYSDATE)
                    AND   A.PASIEN_ID='" . $pasienid . "'
                    ";

        $recordset = $this->db->query($query);
        $recordset = $recordset->row();
        return $recordset;
    }

    function cektransaksipasien($pasienid)
    {
        $query =
            "
                         SELECT X.* FROM(
                         SELECT A.EPISODE_ID, A.PASIEN_ID, SR01_CEK_TRANSAKSI_BLMBAYAR(A.EPISODE_ID, '$pasienid') TOTAL
                        FROM SR01_KEU_EPISODE A
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

    function getepisode($pasienid)
    {
        $query = "
                    SELECT X.*
                    FROM (
                            SELECT A.EPISODE_ID, A.PASIEN_ID, A.POLI_ID, A.DOKTER_ID,
                            B.KETERANGAN NAMAPOLI, C.NAMA NAMADR,
                            CASE
                                WHEN (SR01_VA_PRA_VALIDASI(A.PASIEN_ID))>0 THEN 'Y'
                                ELSE 'T'
                            END ADA_TR
                            FROM SR01_KEU_EPISODE A, SR01_MED_POLI_MS B, SR01_MED_DOKTER_MS C
                            WHERE A.LOKASI_ID = B.LOKASI_ID AND A.POLI_ID = B.POLI_ID
                            AND A.LOKASI_ID = C.LOKASI_ID AND A.DOKTER_ID = C.DOKTER_ID
                            AND A.LOKASI_ID = '001' AND A.STATUS_EPISODE <> '99' AND A.AKTIF = '1'
                            AND A.REKANAN_ID <> 'BPJS' AND A.POLI_ID NOT IN ('UGD01','APS')
                            AND TRUNC(A.TGL_TRANSAKSI) = TRUNC(SYSDATE)
                            AND A.PASIEN_ID = '$pasienid'
                    ) X
                    WHERE X.ADA_TR = 'Y'
                    ORDER BY X.EPISODE_ID
            ";

        $recordset = $this->db->query($query);
        $recordset = $recordset->result();
        return $recordset;
    }

    function cekdokterselesai($pasienid)
    {
        $query = "
            SELECT A.EPISODE_ID 
            FROM WEB_CO_SELESAI_PERIKSA A, SR01_KEU_EPISODE C
            WHERE A.EPISODE_ID = C.EPISODE_ID AND A.PASIEN_ID = C.PASIEN_ID
            AND A.POLI_ID = C.POLI_ID AND A.DOKTER_ID = C.DOKTER_ID
            AND C.LOKASI_ID = '001' AND A.SHOW_ITEM IN ('1','5') 
            AND A.PASIEN_ID = '$pasienid'
            AND TRUNC(C.TGL_TRANSAKSI) = TRUNC(SYSDATE)
            AND C.POLI_ID NOT IN ('UGD01','APS')
            GROUP BY A.EPISODE_ID
        ";

        $recordset = $this->db->query($query);
        $recordset = $recordset->row();
        return $recordset;
    }

    function cekobat($pasienid)
    {
        $query = "
            SELECT A.EPISODE_ID 
            FROM WEB_CO_RESEP_DT A, SR01_KEU_EPISODE C
            WHERE A.EPISODE_ID = C.EPISODE_ID AND A.PASIEN_ID = C.PASIEN_ID
            AND A.LOKASI_ID = '001' AND A.STATUS_VER = '0'
            AND A.SHOW_ITEM IN ('1','5') AND A.PASIEN_ID = '$pasienid'
            AND TRUNC(C.TGL_TRANSAKSI) = TRUNC(SYSDATE)
            AND C.POLI_ID NOT IN ('UGD01','APS')
            GROUP BY A.EPISODE_ID
        ";

        $recordset = $this->db->query($query);
        $recordset = $recordset->row();
        return $recordset;
    }

    function ceklab($pasienid)
    {
        $query = "
            SELECT B.EPISODE_ID
            FROM WEB_CO_LAB_DT A, SR01_WORKLIST_LAB B
            WHERE A.EPISODE_ID = B.EPISODE_ID AND A.PASIEN_ID = B.PASIEN_ID
            AND B.LOKASI_ID = '001' AND B.AKTIF = '1' 
            AND A.SHOW_ITEM = '1' AND A.TRANS_BAYAR_ID IS NULL
            AND TRUNC(A.TANGGAL) = TRUNC(SYSDATE)
            AND B.IS_KONFIRM = 'T'
            AND A.PASIEN_ID = '$pasienid'
        ";

        $recordset = $this->db->query($query);
        $recordset = $recordset->row();
        return $recordset;
    }

    function cekrad($pasienid)
    {
        $query = "
            SELECT B.EPISODE_ID
            FROM WEB_CO_RAD_DT A, SR01_WORKLIST_RAD B
            WHERE A.EPISODE_ID = B.EPISODE_ID AND A.PASIEN_ID = B.PASIEN_ID
            AND B.LOKASI_ID = '001' AND B.AKTIF = '1' 
            AND A.SHOW_ITEM = '1' AND A.TRANS_BAYAR_ID IS NULL
            AND TRUNC(A.TANGGAL) = TRUNC(SYSDATE)
            AND B.IS_KONFIRM = 'T'
            AND A.PASIEN_ID = '$pasienid'
        ";

        $recordset = $this->db->query($query);
        $recordset = $recordset->row();
        return $recordset;
    }

    function getalltransaksi($pasienid)
    {
        $query =
            "  
                    SELECT XX.*
                    FROM (
                        SELECT X.*, (X.QTY*X.HARGA) TOTAL
                        FROM (
                            SELECT 'FARMASI' GRUP, 'F' JENIS, A.TRANS_VALID_ID TRANS_CO, A.EPISODE_ID, A.PASIEN_ID,
                            B.OBAT_ID IDLAYAN, B.NAMA_OBAT NAMALAYAN, 'T' CITO, C.REKANAN_ID, C.POLI_ID,
                            (SELECT D.KETERANGAN FROM SR01_MED_POLI_MS D
                             WHERE D.LOKASI_ID = C.LOKASI_ID AND D.POLI_ID = C.POLI_ID
                            ) NAMAPOLI,
                            B.QTY, B.HARGA, C.DOKTER_ID, 'T' WAJIB
                            FROM SR01_FRM_VALIDASI_HD A, SR01_FRM_VALIDASI_IT B, SR01_KEU_EPISODE C
                            WHERE A.LOKASI_ID = B.LOKASI_ID AND A.EPISODE_ID = B.EPISODE_ID
                            AND A.PASIEN_ID = B.PASIEN_ID AND A.TRANS_VALID_ID = B.TRANS_VALID_ID
                            AND A.LOKASI_ID = C.LOKASI_ID AND A.EPISODE_ID = C.EPISODE_ID
                            AND A.PASIEN_ID = C.PASIEN_ID
                            AND A.AKTIF = '1' AND B.SHOW_ITEM = '1' AND A.TRANS_BAYAR_ID IS NULL
                            AND TRUNC(A.TANGGAL) = TRUNC(SYSDATE)
                            AND A.PASIEN_ID = '$pasienid'
          
                            UNION
                            
                            SELECT 'FARMASI' GRUP, 'F' JENIS, A.TRANS_CO, A.EPISODE_ID, A.PASIEN_ID, A.OBAT_ID IDLAYAN, 
                            CASE
                                WHEN A.NAMA_RACIKAN IS NOT NULL THEN UPPER(A.NAMA_RACIKAN)
                                ELSE UPPER(A.NAMA_OBAT) 
                            END NAMALAYAN,
                            'T' CITO, C.REKANAN_ID, C.POLI_ID, D.KETERANGAN NAMAPOLI, 
                            A.QTY, SR01_GETHARGA_OBAT_RACIKAN(A.EPISODE_ID, A.PASIEN_ID, A.TRANS_CO, A.OBAT_ID) HARGA,
                            C.DOKTER_ID, 'Y' WAJIB
                            FROM WEB_CO_RESEP_DT A, SR01_KEU_EPISODE C, SR01_MED_POLI_MS D
                            WHERE A.LOKASI_ID = C.LOKASI_ID AND A.EPISODE_ID = C.EPISODE_ID AND A.PASIEN_ID = C.PASIEN_ID
                            AND C.LOKASI_ID = '001' AND A.SHOW_ITEM IN ('1','5') 
                            AND A.TYPE IN ('01')
                            AND D.LOKASI_ID = C.LOKASI_ID AND D.POLI_ID = C.POLI_ID
                            AND TRUNC(C.TGL_TRANSAKSI) = TRUNC(SYSDATE)
                            AND A.STATUS_VER = '0' 
                            AND A.PASIEN_ID = '$pasienid'
                        ) X

                        UNION

                        SELECT X.GRUP, X.JENIS, X.TRANS_CO, X.EPISODE_ID, X.PASIEN_ID, X.IDLAYAN, 
                        CASE
                              WHEN X.CITO = 'Y' THEN X.NAMALAYAN || '  (CITO)'
                              ELSE
                                   X.NAMALAYAN
                        END NAMALAYAN, 
                        X.CITO, X.REKANAN_ID, X.POLI_ID, X.NAMAPOLI, 1 QTY,
                        X.HARGA, X.DOKTER_ID, X.WAJIB,
                        CASE
                            WHEN X.CITO = 'Y' THEN 
                                (X.QTY * X.HARGA * 1.5)
                            ELSE 
                                (X.QTY * X.HARGA) 
                        END TOTAL
                        FROM (
                            SELECT 'LABORATORIUM' GRUP, 'L' JENIS, A.TRANS_CO, A.EPISODE_ID, A.PASIEN_ID, A.TEST_ID IDLAYAN, B.NAMA_LAYAN1 NAMALAYAN, 
                            DECODE(A.CITO, 'Y', 'Y', 'T') CITO, C.REKANAN_ID, C.POLI_ID, D.KETERANGAN NAMAPOLI, 1 QTY,
                            SR01_REGISTRASI.GETHARGA_LAYAN('001', C.REKANAN_ID, C.KELAS_ID, A.TEST_ID) HARGA,
                            C.DOKTER_ID, 'T' WAJIB
                            FROM WEB_CO_LAB_DT A, SR01_KEU_LAYAN_MS B, SR01_KEU_EPISODE C, SR01_MED_POLI_MS D
                            WHERE A.TEST_ID = B.LAYAN_ID AND A.SHOW_ITEM IN ('1','5')
                            AND A.EPISODE_ID = C.EPISODE_ID AND A.PASIEN_ID = C.PASIEN_ID
                            AND C.LOKASI_ID = D.LOKASI_ID AND C.POLI_ID = D.POLI_ID
                            AND C.LOKASI_ID = '001' 
                            AND TRUNC(C.TGL_TRANSAKSI) = TRUNC(SYSDATE)
                            AND A.TRANS_BAYAR_ID IS NULL
                            AND A.PASIEN_ID = '$pasienid'
                        ) X 

                        UNION 

                        SELECT Y.GRUP, Y.JENIS, Y.TRANS_CO, Y.EPISODE_ID, Y.PASIEN_ID, Y.IDLAYAN, 
                        CASE
                            WHEN Y.CITO = 'Y' THEN 
                              Y.NAMALAYAN || '  (CITO)'
                            ELSE 
                              Y.NAMALAYAN
                        END NAMALAYAN, 
                        Y.CITO, Y.REKANAN_ID, Y.POLI_ID, Y.NAMAPOLI, 1 QTY,
                        Y.HARGA, Y.DOKTER_ID, Y.WAJIB,
                        CASE
                            WHEN Y.CITO = 'Y' THEN
                                (Y.QTY * Y.HARGA * 1.5)
                            ELSE
                                (Y.QTY * Y.HARGA) 
                        END TOTAL
                        FROM (
                            SELECT X.GRUP, X.JENIS, X.TRANS_CO, X.EPISODE_ID, X.PASIEN_ID, X.TEST_ID IDLAYAN, X.NAMALAYAN,  
                            DECODE(X.CITO, 'Y', 'Y', 'T') CITO, X.REKANAN_ID, X.POLI_ID, X.NAMAPOLI, 1 QTY,
                            SR01_REGISTRASI.GETHARGA_LAYAN('001', X.REKANAN_ID, X.KELAS_ID, X.TEST_ID) HARGA, X.DOKTER_ID, 'T' WAJIB
                            FROM (
                                SELECT 'RADIOLOGI' GRUP, 'R' JENIS, A.TRANS_CO, A.EPISODE_ID, A.PASIEN_ID, A.TEST_ID, B.NAMA_LAYAN1 NAMALAYAN, 
                                (SELECT D.CITO_YN FROM SR01_WORKLIST_RAD D
                                    WHERE D.LOKASI_ID = C.LOKASI_ID AND D.EPISODE_ID = C.EPISODE_ID
                                    AND D.PASIEN_ID = C.PASIEN_ID AND D.TRANS_CO = A.TRANS_CO) CITO, 
                                C.REKANAN_ID, C.POLI_ID, D.KETERANGAN NAMAPOLI, C.KELAS_ID, 1 QTY, C.DOKTER_ID
                                FROM WEB_CO_RAD_DT A, SR01_KEU_LAYAN_MS B, SR01_KEU_EPISODE C, SR01_MED_POLI_MS D
                                WHERE A.TEST_ID = B.LAYAN_ID AND A.SHOW_ITEM IN ('1','5')
                                AND A.EPISODE_ID = C.EPISODE_ID AND A.PASIEN_ID = C.PASIEN_ID
                                AND C.LOKASI_ID = D.LOKASI_ID AND C.POLI_ID = D.POLI_ID
                                AND C.LOKASI_ID = '001' 
                                AND TRUNC(C.TGL_TRANSAKSI) = TRUNC(SYSDATE)
                                AND A.TRANS_BAYAR_ID IS NULL
                                AND A.PASIEN_ID = '$pasienid'
                            ) X
                        ) Y

                        UNION

                        SELECT X.*, (X.QTY * X.HARGA) TOTAL
                        FROM (
                            SELECT 'ALKES/PENUNJANG' GRUP, 'A' JENIS, A.TRANS_CO, A.EPISODE_ID, A.PASIEN_ID, A.LAYAN_ID IDLAYAN, 
                            B.NAMA_LAYAN1 NAMALAYAN, 'T' CITO, C.REKANAN_ID, C.POLI_ID, D.KETERANGAN NAMAPOLI, A.QTY, 
                            SR01_REGISTRASI.GETHARGA_LAYAN('001', C.REKANAN_ID, C.KELAS_ID, A.LAYAN_ID) HARGA, C.DOKTER_ID, 'Y' WAJIB
                            FROM WEB_CO_ALKES_DT A, SR01_KEU_LAYAN_MS B, SR01_KEU_EPISODE C, SR01_MED_POLI_MS D
                            WHERE A.LAYAN_ID = B.LAYAN_ID AND A.SHOW_ITEM IN ('1','5')
                            AND A.EPISODE_ID = C.EPISODE_ID AND A.PASIEN_ID = C.PASIEN_ID
                            AND C.LOKASI_ID = D.LOKASI_ID AND C.POLI_ID = D.POLI_ID
                            AND C.LOKASI_ID = '001' 
                            AND TRUNC(C.TGL_TRANSAKSI) = TRUNC(SYSDATE)
                            AND A.TRANS_BAYAR_ID IS NULL 
                            AND A.PASIEN_ID = '$pasienid'
                        ) X
                    ) XX
                    ORDER BY XX.NAMAPOLI, XX.GRUP, XX.NAMALAYAN
                    ";

        $recordset = $this->db->query($query);
        $recordset = $recordset->result();
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
        $sql =   $this->db->insert("SR01_ANTRIAN_PASIEN", $data);
        return $sql;
    }

    function insertqrishd($trId, $pasienId, $epiId, $drId, $poliId, $resQris, $refNom, $expDate, $billNumber, $nominal)
    {
        $sql =
            "Insert into SIMRS_MANAGER.SR01_PG_TRANSAKSI_HD
                (LOKASI_ID, TRANSAKSI_ID, PASIEN_ID, EPISODE_ID, TGL_MASUK, DOKTER_ID, POLI_ID, KET1, KET2,
                 TGL_EXP,
                 TAGIHAN_CODE, TAGIHAN_ID, VA, BILLING, TGL_TAGIHAN, STATUS, AKTIF, CREATED_BY, CREATED_DATE)
            Values
                ('001', '$trId', '$pasienId', '$epiId', TRUNC(SYSDATE), '$drId', '$poliId', '$resQris', '$refNom', 
                 TO_DATE(REPLACE(REPLACE('$expDate', 'T', ' '), '+07:00', ''), 'YYYY-MM-DD HH24:MI:SS'),
                 'QRISRJ', '$billNumber', '$billNumber', '$nominal', TRUNC(SYSDATE), '1', '1', 'PASIEN', SYSDATE)
            ";

        $insresult = $this->db->query($sql);
        return $insresult;
    }

    function insertqrisit($trId, $epiId, $pasienId, $transCo, $rekId, $poliId, $layanId, $namaLayan, $qty, $harga, $total, $cito, $jenis)
    {
        $sql = "
            INSERT INTO SIMRS_MANAGER.SR01_PG_TRANSAKSI_IT (
                LOKASI_ID, TRANSAKSI_ID, EPISODE_ID, PASIEN_ID, TRANS_CO, REKANAN_ID, POLI_ID, LAYAN_ID, NAMA_LAYAN, QTY, HARGA_SATUAN, HARGA_TOTAL, 
                CITO, JENIS, AKTIF, CREATED_BY, CREATED_DATE) 
            VALUES ('001', '$trId', '$epiId', '$pasienId', '$transCo', '$rekId', '$poliId', '$layanId', '$namaLayan', '$qty', '$harga', '$total',
            '$cito', '$jenis', '1', 'PASIEN', SYSDATE)
        ";

        $insresult = $this->db->query($sql);
        return $insresult;
    }

    function gettransid()
    {
        $query =
            "
            SELECT TO_CHAR(SYSDATE, 'YYMMDD')||LPAD(COUNT(TRANSAKSI_ID)+1,4,0) TRID
            FROM SR01_PG_TRANSAKSI_HD
            WHERE LOKASI_ID = '001' AND TRUNC(CREATED_DATE)=TRUNC(SYSDATE)
            ";

        $recordset = $this->db->query($query);
        $recordset = $recordset->row();
        return $recordset;
    }

    function getreferencenumber($pasienId)
    {
        $query = "
            SELECT (X.KODE || X.TGL || X.RM || LPAD(NOMOR, 5, 0)) TRID
            FROM (
                SELECT '7' KODE, TO_CHAR(SYSDATE, 'YYMMDD') TGL, 
                (SELECT INT_PASIEN_ID FROM SR01_GEN_PASIEN_MS
                 WHERE LOKASI_ID = '001' AND PASIEN_ID = '$pasienId') RM,
                (SELECT COUNT(PASIEN_ID)+1 FROM SR01_PG_TRANSAKSI_HD
                 WHERE LOKASI_ID = '001' AND PASIEN_ID = '$pasienId'
                 AND TRUNC(CREATED_DATE)=TRUNC(SYSDATE)) NOMOR
                FROM DUAL
            ) X
                 ";

        $recordset = $this->db->query($query);
        $recordset = $recordset->row();
        return $recordset;
    }

    function getdatabayar($pasienId)
    {
        $query = "
            SELECT KET2, TAGIHAN_ID, SUM(BILLING) NOMINAL, (TO_CHAR(CREATED_DATE, 'YYYY-MM-DD') || 'T' || TO_CHAR(CREATED_DATE, 'HH24:MI:SS') || '+07:00') TGLJAM,
            BAYAR_ID
            FROM SR01_PG_TRANSAKSI_HD
            WHERE LOKASI_ID = '001' 
            AND PASIEN_ID = '$pasienId'
            --AND STATUS = '3' 
            AND AKTIF = '1'
            AND TRUNC(TGL_MASUK) = TRUNC(SYSDATE)
            GROUP BY KET2, TAGIHAN_ID, CREATED_DATE, BAYAR_ID
                 ";

        $recordset = $this->db->query($query);
        $recordset = $recordset->row();
        return $recordset;
    }

    function datapasien($bayarId, $pasienId)
    {
        $query = "
            SELECT DISTINCT B.INT_PASIEN_ID NORM, B.NAMA NAMAPAS, SR01_GET_SUFFIX('$pasienId') NAMAPASPJG,
            TO_CHAR(A.TGL_MASUK, 'DD.MM.YYYY') TGLMASUK, A.BAYAR_ID, D.NAMA PROVIDER
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
            SELECT
                (SELECT COUNT(A.ORIGINALREFERENCENO) JUMLAH         
                FROM SR01_PG_NOTIFY A
                WHERE A.LOKASI_ID = '001' AND A.BILLNUMBER = '$billNumber'
                ) JUMLAH,

                (SELECT B.BAYAR_ID FROM SR01_PG_TRANSAKSI_HD B
                WHERE B.LOKASI_ID = '001' AND B.TAGIHAN_ID = '$billNumber'
                GROUP BY B.BAYAR_ID
                ) BAYARID
            FROM DUAL
        ";

        $recordset = $this->db->query($query);
        $recordset = $recordset->row();
        return $recordset;
    }

    function insertnotify(
        $ORIGINALREFERENCENO,
        $ORIGINALPARTNERREFERENCENO,
        $LATESTTRANSACTIONSTATUS,
        $TRANSACTIONSTATUSDESC,
        $BANKCODE,
        $EXTERNALSTOREID,
        $AMOUNTVALUE,
        $AMOUNTCURRENCY,
        $CUSTOMERPAN,
        $TRANSACTIONDATETIME,
        $AMOUNTFEE,
        $BILLNUMBER,
        $CUSTOMERNAME,
        $TERMINALID,
        $STAN,
        $MERCHANTNAME,
        $APPROVALCODE,
        $MERCHANTPAN,
        $MCC,
        $MERCHANTCITY,
        $MERCHANTCOUNTRY,
        $IPMERCHANT,
        $URLMERCHANT,
        $ACQUIRERCODE,
        $ACQUIRERNAME
    ) {
        $query = "
                    INSERT INTO SR01_PG_NOTIFY
                    (
                        ORIGINALREFERENCENO,
                        ORIGINALPARTNERREFERENCENO,
                        LATESTTRANSACTIONSTATUS,
                        TRANSACTIONSTATUSDESC,
                        BANKCODE,
                        EXTERNALSTOREID,
                        AMOUNTVALUE,
                        AMOUNTCURRENCY,
                        CUSTOMERPAN,
                        TRANSACTIONDATETIME,
                        AMOUNTFEE,
                        BILLNUMBER,
                        CUSTOMERNAME,
                        TERMINALID,
                        STAN,
                        MERCHANTNAME,
                        APPROVALCODE,
                        MERCHANTPAN,
                        MCC,
                        MERCHANTCITY,
                        MERCHANTCOUNTRY,
                        IPMERCHANT,
                        URLMERCHANT,
                        ACQUIRERCODE,
                        ACQUIRERNAME
                    ) 
                    VALUES
                    (
                        '$ORIGINALREFERENCENO',
                        '$ORIGINALPARTNERREFERENCENO',
                        '$LATESTTRANSACTIONSTATUS',
                        '$TRANSACTIONSTATUSDESC',
                        '$BANKCODE',
                        '$EXTERNALSTOREID',
                        '$AMOUNTVALUE',
                        '$AMOUNTCURRENCY',
                        '$CUSTOMERPAN',
                        TO_DATE('$TRANSACTIONDATETIME', 'YYYY.MM.DD HH24:MI:SS'),
                        '$AMOUNTFEE',
                        '$BILLNUMBER',
                        '$CUSTOMERNAME',
                        '$TERMINALID',
                        '$STAN',
                        '$MERCHANTNAME',
                        '$APPROVALCODE',
                        '$MERCHANTPAN',
                        '$MCC',
                        '$MERCHANTCITY',
                        '$MERCHANTCOUNTRY',
                        '$IPMERCHANT',
                        '$URLMERCHANT',
                        '$ACQUIRERCODE',
                        '$ACQUIRERNAME'
                    )
                ";

        $result = $this->db->query($query);
        return $result;
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

    function cekdataantrian($data)
    {
        $sql = "
            SELECT * FROM SR01_ANTRIAN_PASIEN 
            WHERE PASIEN_ID = ?
            AND KODE = ?
            AND AKTIF = '1'
            AND TRUNC(TANGGAL) = TRUNC(SYSDATE)
            ORDER BY CREATED_DATE DESC
        ";

        $query = $this->db->query($sql, [$data['PASIEN_ID'], $data['KODE']]);
        return $query->row();
    }

    function simpanantrian_postgree($data)
    {
        $query = "
            INSERT INTO antrian_manager.sr01_antrian_pasien
                (TANGGAL, JENIS_ID, KODE, IP_ADDRESS, URUT, NAMA_PASIEN, INT_PASIEN_ID, PASIEN_ID)
            VALUES 
            (
                CURRENT_DATE,
                '".$data['JENIS_ID']."',
                '".$data['KODE']."',
                '".$data['IP_ADDRESS']."',
                '".$data['URUT']."',
                '".$data['NAMA_PASIEN']."',
                '".$data['INT_PASIEN_ID']."',
                '".$data['PASIEN_ID']."'
            )
        ";
        $sql = $this->db_pg->query($query);
        return $sql;
    }
}

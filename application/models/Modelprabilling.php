<?php
    class Modelprabilling extends CI_Model{

        function cekdatapasien($parameter)
        {
            $query = "
                        SELECT A.INT_PASIEN_ID, A.NAMA, A.NO_IDENTITAS, A.PASIEN_ID, A.EMAIL
                        FROM SR01_GEN_PASIEN_MS A
                        WHERE A.LOKASI_ID = '001' AND A.JENIS_PAS = 'I'
                        AND A.INT_PASIEN_ID = '" . $parameter . "' OR A.NO_IDENTITAS = '" . $parameter . "' OR A.BPJS_NO = '" . $parameter . "'
                    ";

            $recordset = $this->db->query($query);
            $recordset = $recordset->row();
            return $recordset;
        }

        function getdatapasranap($epiid, $pasienid)
        {
            $query = "
                        SELECT A.EPISODE_ID, A.PASIEN_ID, B.INT_PASIEN_ID, B.NAMA NAMAPAS,
                        A.TGL_MASUK, A.RUANGRWT_ID, C.NAMA NAMAREK, D.NAMA NAMADR,
                        (SELECT E.KETERANGAN FROM SR01_KEU_KELAS_MS E
                         WHERE E.LOKASI_ID = A.LOKASI_ID AND E.KELAS_ID = A.KELAS_ID
                        ) NMKELAS,
                        CASE
                            WHEN A.REKANAN_ID = 'BPJS' THEN B.BPJS_NO
                            ELSE NULL
                        END NOBPJS
                        FROM SR01_KEU_EPISODE A, SR01_GEN_PASIEN_MS B, SR01_KEU_REKANAN_MS C,
                        SR01_MED_DOKTER_MS D
                        WHERE A.LOKASI_ID = B.LOKASI_ID AND A.PASIEN_ID = B.PASIEN_ID
                        AND C.LOKASI_ID = A.LOKASI_ID AND C.REKANAN_ID = A.REKANAN_ID
                        AND D.LOKASI_ID = A.LOKASI_ID AND D.DOKTER_ID = A.DOKTER_ID
                        AND A.LOKASI_ID = '001'
                        AND A.EPISODE_ID = '". $epiid ."' AND A.PASIEN_ID = '". $pasienid ."'
                    ";

            $recordset = $this->db->query($query);
            $recordset = $recordset->row();
            return $recordset;
        }

        function cektransaksi($pasienid)
        {
            $query = "
                    SELECT A.EPISODE_ID, A.PASIEN_ID, B.INT_PASIEN_ID, B.NAMA, B.BPJS_NO, 
                    B.NO_IDENTITAS, TRUNC(A.TGL_MASUK) TGLMASUK, TRUNC(A.TGL_TRANSAKSI) TANGGAL, 
                    A.POLI_ID, A.REKANAN_ID,
                    C.NAMA NAMAREK, D.NAMA NAMADR,
                    (SELECT E.KETERANGAN FROM SR01_KEU_KELAS_MS E
                    WHERE E.LOKASI_ID = A.LOKASI_ID AND E.KELAS_ID = A.KELAS_ID) KELAS,
                    A.JENIS_EPISODE
                    FROM SR01_KEU_EPISODE A, SR01_GEN_PASIEN_MS B, SR01_KEU_REKANAN_MS C, SR01_MED_DOKTER_MS D
                    WHERE A.LOKASI_ID = B.LOKASI_ID AND A.PASIEN_ID = B.PASIEN_ID
                    AND A.LOKASI_ID = C.LOKASI_ID AND A.REKANAN_ID = C.REKANAN_ID
                    AND A.LOKASI_ID = D.LOKASI_ID AND A.DOKTER_ID = D.DOKTER_ID
                    AND A.LOKASI_ID = '001' AND A.STATUS_EPISODE = '00'
                    AND A.TGL_KELUAR IS NULL AND A.JENIS_EPISODE = 'O'
                    AND A.POLI_ID LIKE 'UGD%' AND A.AKTIF = '1' 
                    AND TRUNC(A.TGL_TRANSAKSI) >= TRUNC(SYSDATE)-2 AND TRUNC(A.TGL_TRANSAKSI) <= TRUNC(SYSDATE)
                    AND (B.PASIEN_ID = '" . $pasienid . "' OR B.NO_IDENTITAS = '" . $pasienid . "' OR B.BPJS_NO = '" . $pasienid . "') 

                    UNION

                    SELECT A.EPISODE_ID, A.PASIEN_ID, B.INT_PASIEN_ID, B.NAMA, B.BPJS_NO, 
                    B.NO_IDENTITAS, TRUNC(A.TGL_MASUK) TGLMASUK, TRUNC(A.TGL_TRANSAKSI) TANGGAL, 
                    A.POLI_ID, A.REKANAN_ID,
                    C.NAMA NAMAREK, D.NAMA NAMADR,
                    (SELECT E.KETERANGAN FROM SR01_KEU_KELAS_MS E
                    WHERE E.LOKASI_ID = A.LOKASI_ID AND E.KELAS_ID = A.KELAS_ID) KELAS,
                    A.JENIS_EPISODE
                    FROM SR01_KEU_EPISODE A, SR01_GEN_PASIEN_MS B, SR01_KEU_REKANAN_MS C, SR01_MED_DOKTER_MS D
                    WHERE A.LOKASI_ID = B.LOKASI_ID AND A.PASIEN_ID = B.PASIEN_ID
                    AND A.LOKASI_ID = C.LOKASI_ID AND A.REKANAN_ID = C.REKANAN_ID
                    AND A.LOKASI_ID = D.LOKASI_ID AND A.DOKTER_ID = D.DOKTER_ID
                    AND A.LOKASI_ID = '001' AND A.STATUS_EPISODE = '00'
                    AND A.TGL_KELUAR IS NULL AND A.JENIS_EPISODE = 'I' AND A.AKTIF = '1' 
                    AND A.PASIEN_ID IN (SELECT D.PASIEN_ID FROM SR01_MED_RUANG_PRWT D WHERE D.PASIEN_ID IS NOT NULL) 
                    AND (B.PASIEN_ID = '" . $pasienid . "' OR B.NO_IDENTITAS = '" . $pasienid . "' OR B.BPJS_NO = '" . $pasienid . "')
                    ";

            $recordset = $this->db->query($query);
            $recordset = $recordset->row();
            return $recordset;
        }

        function gettransaksiigd($pasienid)
        {
            $query =
                "  
                        --SELECT XX.GRUP, XX.JENIS, SUM(XX.TOTAL) TOTAL
                        --FROM (
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
                                AND A.STATUS_VER = '0' 
                                AND A.PASIEN_ID = '$pasienid'
                            ) X

                            UNION

                            SELECT X.*, 
                            CASE
                                WHEN X.CITO = 'Y' THEN 
                                    (X.QTY * X.HARGA * 1.5)
                                ELSE 
                                    (X.QTY * X.HARGA) 
                            END TOTAL 
                            FROM (
                                SELECT 'LABORATORIUM' GRUP, 'L' JENIS, A.TRANS_CO, A.EPISODE_ID, A.PASIEN_ID, A.TEST_ID IDLAYAN, B.NAMA_LAYAN1 NAMALAYAN, 
                                DECODE(A.CITO, NULL, 'T', A.CITO) CITO, C.REKANAN_ID, C.POLI_ID, D.KETERANGAN NAMAPOLI, 1 QTY,
                                SR01_REGISTRASI.GETHARGA_LAYAN('001', C.REKANAN_ID, C.KELAS_ID, A.TEST_ID) HARGA,
                                C.DOKTER_ID, 'T' WAJIB
                                FROM WEB_CO_LAB_DT A, SR01_KEU_LAYAN_MS B, SR01_KEU_EPISODE C, SR01_MED_POLI_MS D
                                WHERE A.TEST_ID = B.LAYAN_ID AND A.SHOW_ITEM IN ('1','5')
                                AND A.EPISODE_ID = C.EPISODE_ID AND A.PASIEN_ID = C.PASIEN_ID
                                AND C.LOKASI_ID = D.LOKASI_ID AND C.POLI_ID = D.POLI_ID
                                AND C.LOKASI_ID = '001' AND A.TRANS_BAYAR_ID IS NULL
                                AND A.PASIEN_ID = '$pasienid'
                            ) X 

                            UNION 

                            SELECT Y.*, 
                            CASE
                                WHEN Y.CITO = 'Y' THEN
                                    (Y.QTY * Y.HARGA * 1.5)
                                ELSE
                                    (Y.QTY * Y.HARGA) 
                            END TOTAL
                            FROM (
                                SELECT X.GRUP, X.JENIS, X.TRANS_CO, X.EPISODE_ID, X.PASIEN_ID, X.TEST_ID IDLAYAN, X.NAMALAYAN,  
                                DECODE(X.CITO, NULL, 'T', X.CITO) CITO, X.REKANAN_ID, X.POLI_ID, X.NAMAPOLI, 1 QTY,
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
                                SR01_REGISTRASI.GETHARGA_LAYAN('001', C.REKANAN_ID, C.KELAS_ID, A.LAYAN_ID) HARGA, C.DOKTER_ID, 'T' WAJIB
                                FROM WEB_CO_ALKES_DT A, SR01_KEU_LAYAN_MS B, SR01_KEU_EPISODE C, SR01_MED_POLI_MS D
                                WHERE A.LAYAN_ID = B.LAYAN_ID AND A.SHOW_ITEM IN ('1','5')
                                AND A.EPISODE_ID = C.EPISODE_ID AND A.PASIEN_ID = C.PASIEN_ID
                                AND C.LOKASI_ID = D.LOKASI_ID AND C.POLI_ID = D.POLI_ID
                                AND C.LOKASI_ID = '001' 
                                AND TRUNC(C.TGL_TRANSAKSI) = TRUNC(SYSDATE)
                                AND A.TRANS_BAYAR_ID IS NULL 
                                AND A.PASIEN_ID = '$pasienid'
                            ) X

                            UNION

                            SELECT X.*, (X.QTY * X.HARGA) TOTAL
                            FROM (
                                SELECT 'ALKES/PENUNJANG' GRUP, 'A' JENIS, NULL TRANS_CO, A.EPISODE_ID, A.PASIEN_ID, 'ADM01' IDLAYAN, 
                                (SELECT B.NAMA_LAYAN1 FROM SR01_KEU_LAYAN_MS B
                                 WHERE B.LOKASI_ID = '001' AND B.LAYAN_ID = 'ADM01') NAMALAYAN, 
                                'T' CITO, A.REKANAN_ID, A.POLI_ID, C.KETERANGAN NAMAPOLI, 1 QTY, 
                                SR01_REGISTRASI.GETHARGA_LAYAN('001', A.REKANAN_ID, A.KELAS_ID, 'ADM01') HARGA, A.DOKTER_ID, 'T' WAJIB
                                FROM SR01_KEU_EPISODE A, SR01_MED_POLI_MS C
                                WHERE C.LOKASI_ID = A.LOKASI_ID AND C.POLI_ID = A.POLI_ID
                                AND A.LOKASI_ID = '001' 
                                AND A.PASIEN_ID = '$pasienid'
                            ) X

                            UNION

                            SELECT X.*, (X.QTY * X.HARGA) TOTAL
                            FROM (
                                SELECT 'ALKES/PENUNJANG' GRUP, 'A' JENIS, NULL TRANS_CO, A.EPISODE_ID, A.PASIEN_ID, 'IGD001' IDLAYAN, 
                                (SELECT B.NAMA_LAYAN1 FROM SR01_KEU_LAYAN_MS B
                                 WHERE B.LOKASI_ID = '001' AND B.LAYAN_ID = 'IGD001') NAMALAYAN, 
                                'T' CITO, A.REKANAN_ID, A.POLI_ID, C.KETERANGAN NAMAPOLI, 1 QTY, 
                                SR01_REGISTRASI.GETHARGA_LAYAN('001', A.REKANAN_ID, A.KELAS_ID, 'IGD001') HARGA, A.DOKTER_ID, 'T' WAJIB
                                FROM SR01_KEU_EPISODE A, SR01_MED_POLI_MS C
                                WHERE C.LOKASI_ID = A.LOKASI_ID AND C.POLI_ID = A.POLI_ID
                                AND A.LOKASI_ID = '001' 
                                AND A.PASIEN_ID = '$pasienid'
                            ) X
                        --) XX
                        --GROUP BY XX.GRUP, XX.JENIS
                        --ORDER BY XX.JENIS
                        ";

            $recordset = $this->db->query($query);
            $recordset = $recordset->result();
            return $recordset;
        }

        function gettransaksiranap($pasienid, $epiid)
        {
                   // Memberikan nilai ke parameter-parameter
                   $param1 = '001'; // Nilai statis sesuai contoh Anda
                   $param2 = $pasienid;
                   $param3 = $epiid;
            // Mendapatkan koneksi database dari CodeIgniter
            $conn = $this->db->conn_id; // Asumsikan $this->db adalah instance database CI Anda
            
            // Menyiapkan statement PL/SQL
            $sql = 'BEGIN SR01_RANAP.CETAK_RI(:P_TRRANAP, :param1, :param2, :param3); END;';
            $stid = oci_parse($conn, $sql);
            
            // Membuat cursor baru
            $cursor = oci_new_cursor($conn);
            
            // Mengikat parameter-parameter
            oci_bind_by_name($stid, ':P_TRRANAP', $cursor, -1, OCI_B_CURSOR);
            oci_bind_by_name($stid, ':param1', $param1);
            oci_bind_by_name($stid, ':param2', $param2);
            oci_bind_by_name($stid, ':param3', $param3);        
     
            // Menjalankan statement PL/SQL
            oci_execute($stid);
            
            // Menjalankan cursor untuk mengambil data
            oci_execute($cursor, OCI_DEFAULT);
            
            // Mengambil hasil dari cursor ke dalam array
            $result = [];
            while (($row = oci_fetch_array($cursor, OCI_ASSOC+OCI_RETURN_NULLS)) !== false) {
                $result[] = $row;
            }
    
            // Membersihkan resource statement dan cursor
            oci_free_statement($stid);
            oci_free_statement($cursor);
            
            // Menutup koneksi, jika tidak dikelola oleh framework
            // oci_close($conn);
            
            return $result;
        }

        function gethitungsummary($epiid, $pasienid)
        {
            $query = "
                SELECT  V.JML, 
                    V.INACBGI,
                    V.INACBGII,
                    V.KELAS_V,
                    V.ANGSURAN,
                    V.HAKELAS,
                    V.PERAWATAN, 
                    V.SELISIH*-1 SELISIH,
                            
                    CASE 
                        WHEN V.TOTAL < 0 THEN
                            V.TOTAL*-1
                        ELSE
                            V.TOTAL
                    END TOTAL,                 
                            
                    V.PENGEMBALIAN,
                    V.KAMAR,
                    V.REKANAN,
                    V.STATUS,
                    V.KELAS_HAK
                    FROM (
                
                        SELECT  Z.JML, 
                                Z.INACBGI,
                                Z.INACBGII,
                                Z.KELAS_V,
                                Z.ANGSURAN,
                                Z.HAKELAS,
                                Z.PERAWATAN, 
                                Z.SELISIH*-1 SELISIH,
                                
                                CASE
                                            --WHEN (Z.STATUS = 'Y') AND (Z.INACBGI > Z.PERAWATAN) THEN Z.KELAS_V
                                            WHEN Z.STATUS = 'Y' THEN SR01_HITUNG_NAIKKELAS('" . $epiid . "', '" . $pasienid . "')
                                            WHEN (Z.STATUS <> 'Y') AND (Z.KAMAR LIKE 'V%') THEN ((Z.JML - Z.HAKELAS) - Z.ANGSURAN)
                                            WHEN (Z.STATUS <> 'Y') AND (Z.REKANAN='JASARAHARJA') THEN 
                                                CASE 
                                                        WHEN (Z.HAKELAS > Z.JML) THEN 
                                                            Z.JML
                                                        ELSE
                                                            (Z.HAKELAS - Z.JML)
                                                END
                                            WHEN (Z.STATUS <> 'Y') THEN
                                                CASE
                                                    WHEN Z.SELISIH > 0 THEN
                                                    CASE
                                                        WHEN Z.ANGSURAN > Z.SELISIH  THEN 
                                                            (Z.ANGSURAN - Z.SELISIH) 
                                                        ELSE
                                                            (Z.SELISIH - Z.ANGSURAN)
                                                    END
                                            -- END    
                                                ELSE
                                                    CASE 
                                                        WHEN Z.PENGEMBALIAN > 0 THEN
                                                            Z.PENGEMBALIAN
                                                        --(Y.JML - (Y.ANGSURAN-Y.PENGEMBALIAN))
                                                        ELSE
                                                            (Z.JML - Z.ANGSURAN)
                                                    END
                                                END
                                END TOTAL,                 
                                
                                Z.PENGEMBALIAN,
                                Z.KAMAR,
                                Z.REKANAN,
                                Z.STATUS,
                                Z.KELAS_HAK
                        FROM (
                                SELECT  DECODE(Y.JML, NULL, 0, Y.JML) JML,
                                        Y.ANGSURAN,
                                        DECODE(Y.INACBGI, NULL, 0, Y.INACBGI) INACBGI,
                                        DECODE(Y.INACBGI, NULL, 0, Y.INACBGII) INACBGII,
                                        DECODE(Y.KELAS_V, NULL, 0, Y.KELAS_V) KELAS_V,
                                        DECODE(Y.HAKELAS, NULL, 0, Y.HAKELAS) HAKELAS, 
                                        DECODE(Y.PERAWATAN, NULL, 0, Y.PERAWATAN) PERAWATAN,
                                        CASE
                                            WHEN (Y.KAMAR LIKE 'V%') OR (Y.REKANAN = 'JASARAHARJA') THEN 0
                                            WHEN (Y.REKANAN = 'BPJS') AND (Y.KAMAR = '1' AND Y.KELAS_HAK = '2') THEN (Y.INACBGI - Y.INACBGII)
                                            ELSE
                                                Y.SELISIH
                                        END SELISIH,
                                        
                                        Y.PENGEMBALIAN,
                                        Y.KAMAR,
                                        Y.REKANAN,
                
                                        CASE
                                            WHEN (Y.KAMAR LIKE 'V%') AND (Y.REKANAN LIKE 'BPJS%') THEN 'Y'
                                            WHEN (Y.KAMAR NOT LIKE 'V%') AND (Y.REKANAN LIKE 'BPJS%') AND (TO_NUMBER(Y.KAMAR) < TO_NUMBER(Y.KELAS_HAK)) THEN 'Y'
                                            ELSE
                                                'T'
                                        END STATUS,
                                        
                                        Y.KELAS_HAK
                                FROM
                                (
                                    SELECT 
                    
                                        (SELECT SR01_MONITOR_BPJS.TOTAL_RAWATRS('001','" . $epiid . "','" . $pasienid . "') 
                                        FROM DUAL) JML,
                        
                                        (SELECT NVL(SUM(A.HARGA_TOTAL),0)
                                        FROM SR01_KEU_TRANSRI_IT A
                                        WHERE A.LOKASI_ID = '001' 
                                        AND A.EPISODE_ID = '" . $epiid . "'
                                        AND A.PASIEN_ID = '" . $pasienid . "'
                                        AND A.JENIS_TR = '02'
                                        AND A.AKTIF = '1'
                                        )ANGSURAN,
                        
                                        (SELECT A.BPJS_TANGGUNG
                                        FROM SR01_KEU_EPISODE A
                                        WHERE A.LOKASI_ID = '001' 
                                        AND A.EPISODE_ID = '" . $epiid . "'
                                        AND A.PASIEN_ID = '" . $pasienid . "'
                                        AND A.AKTIF = '1') HAKELAS,
                                        
                                        (SELECT A.INACBG_I
                                        FROM SR01_BPJS_SELISIH A, SR01_GEN_PASIEN_MS B
                                        WHERE A.LOKASI_ID = B.LOKASI_ID AND A.PASIEN_ID = B.INT_PASIEN_ID
                                        AND A.LOKASI_ID = '001' 
                                        AND A.EPISODE_ID = '" . $epiid . "'
                                        AND B.PASIEN_ID = '" . $pasienid . "') INACBGI,
                                        
                                        (SELECT A.INACBG_II
                                        FROM SR01_BPJS_SELISIH A, SR01_GEN_PASIEN_MS B
                                        WHERE A.LOKASI_ID = B.LOKASI_ID AND A.PASIEN_ID = B.INT_PASIEN_ID
                                        AND A.LOKASI_ID = '001' 
                                        AND A.EPISODE_ID = '" . $epiid . "'
                                        AND B.PASIEN_ID = '" . $pasienid . "') INACBGII,
                                        
                                        (SELECT A.GRAND_TOTAL  --A.TOTAL_BIAYA
                                        FROM SR01_BPJS_SELISIH A, SR01_GEN_PASIEN_MS B
                                        WHERE A.LOKASI_ID = B.LOKASI_ID AND A.PASIEN_ID = B.INT_PASIEN_ID
                                        AND A.LOKASI_ID = '001' 
                                        AND A.EPISODE_ID = '" . $epiid . "'
                                        AND B.PASIEN_ID = '" . $pasienid . "') KELAS_V,
                                                        
                                        (SELECT A.BPJS_TRANS
                                        FROM SR01_KEU_EPISODE A
                                        WHERE A.LOKASI_ID = '001' 
                                        AND A.EPISODE_ID = '" . $epiid . "'
                                        AND A.PASIEN_ID = '" . $pasienid . "'
                                        AND A.AKTIF = '1') PERAWATAN,
                        
                                        (SELECT (A.BPJS_TRANS - A.BPJS_TANGGUNG)
                                        FROM SR01_KEU_EPISODE A
                                        WHERE A.LOKASI_ID = '001' 
                                        AND A.EPISODE_ID = '" . $epiid . "'
                                        AND A.PASIEN_ID = '" . $pasienid . "'
                                        AND A.AKTIF = '1') SELISIH,
                        
                                        (SELECT A.KELAS_ID
                                        FROM SR01_KEU_EPISODE A
                                        WHERE A.LOKASI_ID = '001' 
                                        AND A.EPISODE_ID = '" . $epiid . "'
                                        AND A.PASIEN_ID = '" . $pasienid . "'
                                        AND A.AKTIF = '1') KAMAR,
                                        
                                        (SELECT A.KELASTANGGUNGAN_KDKELAS 
                                        FROM SR01_KEU_BPJS_CVR A
                                        WHERE A.LOKASI_ID = '001' AND A.EPISODE_ID = '" . $epiid . "'
                                        AND A.PASIEN_ID = '" . $pasienid . "' AND A.SEP_JENISLAYAN = '1'
                                        AND A.AKTIF = '1'
                                        AND A.CREATED_DATE = (SELECT MAX(B.CREATED_DATE) FROM SR01_KEU_BPJS_CVR B
                                        WHERE B.LOKASI_ID = A.LOKASI_ID AND B.EPISODE_ID = A.EPISODE_ID
                                        AND B.PASIEN_ID = A.PASIEN_ID AND B.SEP_JENISLAYAN = A.SEP_JENISLAYAN
                                        AND B.AKTIF = '1')) KELAS_HAK,
                        
                                        (SELECT A.REKANAN_ID
                                        FROM SR01_KEU_EPISODE A
                                        WHERE A.LOKASI_ID = '001' 
                                        AND A.EPISODE_ID = '" . $epiid . "'
                                        AND A.PASIEN_ID = '" . $pasienid . "'
                                        AND A.AKTIF = '1') REKANAN,
                        
                                        (SELECT NVL(SUM(A.HARGA_TOTAL),0) 
                                        FROM SR01_KEU_TRANSRI_IT A
                                        WHERE A.LOKASI_ID = '001' 
                                        AND A.EPISODE_ID = '" . $epiid . "'
                                        AND A.PASIEN_ID = '" . $pasienid . "'
                                        AND A.JENIS_TR IN ('03','05')
                                        AND A.AKTIF = '1') PENGEMBALIAN
                        
                                        FROM DUAL
                                    ) Y
                        ) Z
                ) V 
            ";
        }

        function masterantrian()
        {
            $query = "  
                        SELECT A.JENIS_ID, KETERANGAN, KODE, ICON_CLASS
                        FROM SR01_ANTRIAN_MS A
                        WHERE A.LOKASI_ID='001'
                        AND   A.AKTIF='1'
                        AND   A.GROUP_ID='KSR'
                        ORDER BY KETERANGAN ASC
                ";

            $recordset = $this->db->query($query);
            $recordset = $recordset->result();
            return $recordset;
        }

    }

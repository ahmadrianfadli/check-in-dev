<?php
class Modelcekresep extends CI_Model
{

    function cekbookingkode($idbooking)
    {
        $query =
            "   
                SELECT EPISODE_ID,
                    INT_PASIEN_ID,
                    NAMA,
                    NO_IDENTITAS,
                    BPJS_NO,
                    BOOKING_ID,
                    POLI
                FROM (

                        SELECT A.EPISODE_ID,
                                B.INT_PASIEN_ID,
                                B.NAMA,
                                B.NO_IDENTITAS,
                                B.BPJS_NO,
                                C.BOOKING_ID,
                                D.KETERANGAN AS POLI
                        FROM SR01_KEU_EPISODE A
                        JOIN SR01_GEN_PASIEN_MS B
                                ON A.LOKASI_ID = B.LOKASI_ID
                                AND A.PASIEN_ID = B.PASIEN_ID
                        JOIN WEB_CO_REGISTRASI_ONLINE_HD C
                                ON A.LOKASI_ID = C.LOKASI_ID
                                AND A.EPISODE_ID = C.EPISODE_ID
                                AND A.PASIEN_ID = C.PASIEN_ID
                        JOIN SR01_MED_POLI_MS D
                                ON A.LOKASI_ID = D.LOKASI_ID
                                AND A.POLI_ID   = D.POLI_ID
                        WHERE A.AKTIF = '1'
                                AND C.AKTIF = '1'
                                AND A.TGL_MASUK >= TRUNC(SYSDATE)
                                AND A.TGL_MASUK <  TRUNC(SYSDATE) + 1
                                AND (
                                        C.BOOKING_ID     = '{$idbooking}' OR
                                        B.INT_PASIEN_ID  = '{$idbooking}' OR
                                        B.BPJS_NO        = '{$idbooking}' OR
                                        B.NO_IDENTITAS   = '{$idbooking}'
                                        )

                        UNION

                        SELECT A.EPISODE_ID,
                                B.INT_PASIEN_ID,
                                B.NAMA,
                                B.NO_IDENTITAS,
                                B.BPJS_NO,
                                A.BOOKING_ID,
                                D.KETERANGAN AS POLI
                        FROM WEB_CO_REGISTRASI_ONLINE_HD A
                        JOIN SR01_GEN_PASIEN_MS B
                                ON A.LOKASI_ID = B.LOKASI_ID
                                AND A.PASIEN_ID = B.PASIEN_ID
                        JOIN SR01_MED_POLI_MS D
                                ON A.LOKASI_ID = D.LOKASI_ID
                                AND A.POLI_ID   = D.POLI_ID
                        WHERE A.AKTIF = '1'
                                AND A.TGL_MASUK >= TRUNC(SYSDATE)
                                AND A.TGL_MASUK <  TRUNC(SYSDATE) + 1
                                AND (
                                        A.BOOKING_ID     = '{$idbooking}' OR
                                        B.INT_PASIEN_ID  = '{$idbooking}' OR
                                        B.BPJS_NO        = '{$idbooking}' OR
                                        B.NO_IDENTITAS   = '{$idbooking}'
                                )

                        UNION

                        SELECT A.EPISODE_ID,
                                B.INT_PASIEN_ID,
                                B.NAMA,
                                B.NO_IDENTITAS,
                                B.BPJS_NO,
                                NULL AS BOOKING_ID,
                                D.KETERANGAN AS POLI
                        FROM SR01_KEU_EPISODE A
                        JOIN SR01_GEN_PASIEN_MS B
                                ON A.LOKASI_ID = B.LOKASI_ID
                                AND A.PASIEN_ID = B.PASIEN_ID
                        JOIN SR01_MED_POLI_MS D
                                ON A.LOKASI_ID = D.LOKASI_ID
                                AND A.POLI_ID   = D.POLI_ID
                        WHERE A.AKTIF = '1'
                                AND A.POLI_ID = 'UGD01'
                                AND A.TGL_MASUK >= TRUNC(SYSDATE)
                                AND A.TGL_MASUK <  TRUNC(SYSDATE) + 1
                                AND (
                                        B.INT_PASIEN_ID = '{$idbooking}' OR
                                        B.BPJS_NO       = '{$idbooking}' OR
                                        B.NO_IDENTITAS  = '{$idbooking}'
                                )
                )
            ";
        // echo '<pre>'. $query .'</pre>';
        $recordset = $this->db->query($query);
        $recordset = $recordset->row();
        return $recordset;
    }

    function cekadaresep($idbooking)
    {
        $query =
            "   
                    SELECT Y.*, DECODE(Y.ESTIMASI, NULL, '-', DECODE(Y.JAMSELESAI, NULL, 'PERKIRAAN SELESAI : ', 'SELESAI DISIAPKAN : ')) ESTIMASITEKS, DECODE(Y.ESTIMASI, NULL, '-', DECODE(Y.JAMSELESAI, NULL, TO_CHAR(Y.ESTIMASI, 'HH24:MI:SS'), Y.JAMSELESAI)) ESTIMASIJAM, DECODE(Y.TIPE, NULL, 'OBAT PATEN', 'OBAT RACIKAN') JENISOBAT
                    FROM(             
                        SELECT X.*, DECODE(X.JAMSELESAI, NULL, 'N', 'Y') ISSELESAI,
                        CASE
                        WHEN (X.REKANAN_ID = 'BPJS') OR (X.REKANAN_ID = 'TB RO0000000000') THEN
                                CASE
                                        WHEN ((X.GERIATRI = 'Y') OR (X.DISABILITAS= 'Y')) THEN 
                                                'FARMASI LANTAI 2'
                                        ELSE
                                                CASE
                                                        WHEN X.GUDANG_VALID IN ('APOTI0000000001', 'DEPO00000000DIS') THEN
                                                                'FARMASI LANTAI 2'
                                                
                                                        WHEN X.GUDANG_VALID IN ('DEPO0000000017') THEN
                                                                'FARMASI LANTAI 5'
                                                        ELSE      
                                                                CASE 
                                                                    WHEN X.POLI_ID = 'POLI0000000006' THEN
                                                                        'FARMASI LANTAI 5'
                                                                    ELSE 
                                                                        'FARMASI LANTAI 1'
                                                                END
                                                END                                                
                                END
                        ELSE
                                 CASE 
                                        WHEN X.POLI_ID = 'POLI0000000006' THEN
                                            'FARMASI LANTAI 5'
                                        ELSE 
                                            'FARMASI LANTAI 2'
                                        END
                    END LOKASIVALID,
                        
                        CASE  
                        WHEN X.TIPE = '01' THEN
                            ( SELECT X.JAMVALID + (1/24*(SELECT NILAI FROM SR01_SYS_PARAMETER WHERE LOKASI_ID = '001' AND PARAM_ID='TIMER16')) FROM DUAL )
                        ELSE
                            ( SELECT X.JAMVALID + (1/24*(SELECT NILAI FROM SR01_SYS_PARAMETER WHERE LOKASI_ID = '001' AND PARAM_ID='TIMER15')) FROM DUAL )
                        END ESTIMASI
                        
                        FROM(
                                SELECT Z.EPISODE_ID, Z.PASIEN_ID, Z.TRANS_CO, Z.KIRIM, Z.VALIDASI, Z.REKANAN_ID, Z.MRN, Z.NAMA, Z.TGL_LAHIR, Z.NO_IDENTITAS, Z.BPJS_NO, Z.POLI, Z.BOOKING_ID, Z.POLI_ID, Z.TIPE, Z.GUDANG_VALID, Z.JAMVALID, Z.JAMSELESAI, Z.GERIATRI, Z.DISABILITAS, Z.BAYAR, Z.DOKTER_ID, Z.NAMADOKTER, SUM(Z.OBATWARNING) OBATWARNING
                                FROM(
                                        SELECT A.EPISODE_ID, A.PASIEN_ID, A.TRANS_CO, A.SHOW_ITEM KIRIM, A.STATUS_VER VALIDASI, B.REKANAN_ID, C.INT_PASIEN_ID MRN, C.NAMA, C.TGL_LAHIR, C.NO_IDENTITAS, C.BPJS_NO, D.KETERANGAN POLI,
                                        ( SELECT H.BOOKING_ID FROM WEB_CO_REGISTRASI_ONLINE_HD H WHERE H.EPISODE_ID = B.EPISODE_ID AND H.PASIEN_ID = B.PASIEN_ID AND H.AKTIF = '1') BOOKING_ID, B.POLI_ID,
                                        ( SELECT DISTINCT(IT.TYPE) FROM SR01_FRM_VALIDASI_IT IT WHERE IT.LOKASI_ID = B.LOKASI_ID AND IT.TYPE ='01' AND IT.PASIEN_ID = B.PASIEN_ID AND IT.EPISODE_ID = B.EPISODE_ID )TIPE,
                                        ( SELECT I.GUDANG_OBAT FROM SR01_FRM_VALIDASI_HD I WHERE I.LOKASI_ID = A.LOKASI_ID AND I.EPISODE_ID = A.EPISODE_ID AND I.PASIEN_ID = A.PASIEN_ID AND I.TRANS_CO = A.TRANS_CO AND I.AKTIF = '1' AND I.STATUS_TR = '00' ) GUDANG_VALID,
                                        DECODE(B.POLI_ID, 'UGD01', (SELECT E.CREATED_DATE FROM SR01_WORKLIST_FRM E WHERE E.LOKASI_ID = B.LOKASI_ID AND E.EPISODE_ID = B.EPISODE_ID AND E.TRANS_CO = A.TRANS_CO AND E.AKTIF = '1'),( SELECT HD.CREATED_DATE FROM SR01_FRM_VALIDASI_HD HD WHERE HD.LOKASI_ID = A.LOKASI_ID AND HD.PASIEN_ID = A.PASIEN_ID AND HD.EPISODE_ID = A.EPISODE_ID AND HD.TRANS_CO = A.TRANS_CO AND HD.AKTIF = '1' AND HD.STATUS_TR = '00'))JAMVALID,
                                        (SELECT TO_CHAR(MAX(W.TGL_SELESAI), 'HH24:MI:SS') FROM SR01_WORKLIST_FRM W WHERE W.LOKASI_ID = A.LOKASI_ID AND W.EPISODE_ID = A.EPISODE_ID AND W.AKTIF = '1' 
                                        AND W.CREATED_DATE = (SELECT MAX(X.CREATED_DATE) FROM SR01_WORKLIST_FRM X WHERE X.LOKASI_ID = W.LOKASI_ID AND X.EPISODE_ID = W.EPISODE_ID AND X.AKTIF = '1')) JAMSELESAI,
                                        C.GERIATRI, C.DISABILITAS,
                                        DECODE(B.REKANAN_ID, 'UMUM', DECODE((SELECT COUNT(Z.EPISODE_ID) FROM SR01_FRM_VALIDASI_HD Z WHERE Z.AKTIF = '1' AND Z.EPISODE_ID = B.EPISODE_ID AND Z.PASIEN_ID = B.PASIEN_ID AND Z.TRANS_BAYAR_ID IS NOT NULL), 0, 'BELUM BAYAR', ''), '') BAYAR, B.DOKTER_ID,
                                        (SELECT NAMA FROM SR01_MED_DOKTER_MS WHERE DOKTER_ID = B.DOKTER_ID) NAMADOKTER,
                                        DECODE(O.OBAT_ID, NULL, 0, 1) OBATWARNING                                                      
                                        FROM WEB_CO_RESEP_DT A LEFT JOIN SR01_FRM_OBAT_MS O ON O.OBAT_ID = A.OBAT_ID AND O.BOLEH_ANTAR = 'N', SR01_KEU_EPISODE B, SR01_GEN_PASIEN_MS C, SR01_MED_POLI_MS D
                                        WHERE A.LOKASI_ID = '001'
                                        AND A.SHOW_ITEM <> '0'
                                        AND TRUNC(A.CREATED_DATE) = TRUNC(SYSDATE)
                                        AND A.LOKASI_ID = B.LOKASI_ID
                                        AND A.EPISODE_ID = B.EPISODE_ID
                                        AND A.PASIEN_ID = B.PASIEN_ID
                                        AND B.JENIS_EPISODE = 'O'
                                        AND A.LOKASI_ID = C.LOKASI_ID
                                        AND A.PASIEN_ID = C.PASIEN_ID
                                        AND B.LOKASI_ID = D.LOKASI_ID
                                        AND B.POLI_ID = D.POLI_ID
                                )Z
                                GROUP BY Z.EPISODE_ID, Z.PASIEN_ID, Z.TRANS_CO, Z.KIRIM, Z.VALIDASI, Z.REKANAN_ID, Z.MRN, Z.NAMA, Z.TGL_LAHIR, Z.NO_IDENTITAS, Z.BPJS_NO, Z.POLI, Z.BOOKING_ID, Z.POLI_ID, Z.TIPE, Z.GUDANG_VALID, Z.JAMVALID, Z.JAMSELESAI, Z.GERIATRI, Z.DISABILITAS, Z.BAYAR, Z.DOKTER_ID, Z.NAMADOKTER
                        )X
                        WHERE ((X.MRN = '{$idbooking}') OR (X.NO_IDENTITAS = '{$idbooking}') OR (X.BOOKING_ID = '{$idbooking}') OR (X.TRANS_CO = '{$idbooking}') OR (X.BPJS_NO = '{$idbooking}'))
                    )Y


            ";
        // echo '<pre>'. $query .'</pre>';
        $recordset = $this->db->query($query);
        $recordset = $recordset->row();
        return $recordset;
    }

    function cektranscodouble($idbooking)
    {
        $query =
            "   
                SELECT DISTINCT
                    EPISODE_ID,
                    TRANS_CO,
                    CNT AS JML
                FROM (
                    SELECT
                        EPISODE_ID,
                        TRANS_CO,
                        COUNT(*) OVER (PARTITION BY EPISODE_ID, TRANS_CO) AS CNT
                    FROM SR01_WORKLIST_FRM
                    WHERE AKTIF = '1'
                    AND CREATED_DATE >= TRUNC(SYSDATE) - 1
                    AND EPISODE_ID IS NOT NULL
                )
                WHERE CNT > 1
            ";
        // echo '<pre>'. $query .'</pre>';
        $recordset = $this->db->query($query);
        $recordset = $recordset->row();
        return $recordset;
    }

    function updateworklist($episode, $transco)
    {
        $query =
            "                                
                UPDATE SR01_WORKLIST_FRM A SET A.AKTIF = '0'
                WHERE A.EPISODE_ID = '{$episode}'
                AND A.TRANS_CO = '{$transco}'
                AND A.CREATED_DATE = (SELECT MAX(B.CREATED_DATE) FROM SR01_WORKLIST_FRM B WHERE B.EPISODE_ID = '{$episode}' AND B.TRANS_CO = '{$transco}')
            ";
        // echo '<pre>'. $query .'</pre>';
        $result = $this->db->query($query);
        return $result;
    }
    function ratingdokter($episode)
    {
        $query = "
            SELECT EPISODE_ID
            FROM SR01_RATING_DOKTER
            WHERE LOKASI_ID = '001'
            AND EPISODE_ID = '{$episode}'
            AND AKTIF = '1'
        ";
        return $this->db->query($query)->row();
    }
    function simpanrating($data)
    {
        return $this->db->insert('SR01_RATING_DOKTER', $data);
    }

    function beristatus($episodeid, $statusambil)
    {
        $query =
            "                                
                UPDATE SR01_WORKLIST_FRM A
                SET     A.PILIHAN_AMBIL = '{$statusambil}',
                        A.TGL_CHECKIN   = CASE
                                            WHEN A.TGL_CHECKIN IS NULL THEN SYSDATE
                                            ELSE A.TGL_CHECKIN
                                        END
                WHERE  A.EPISODE_ID = '{$episodeid}'
            ";
        // echo '<pre>'. $query .'</pre>';
        $result = $this->db->query($query);
        return $result;
    }

    function bericheckin($episodeid)
    {
        $query =
            "                                
                UPDATE SR01_WORKLIST_FRM A
                SET     A.TGL_CHECKIN   = CASE
                                            WHEN A.TGL_CHECKIN IS NULL THEN SYSDATE
                                            ELSE A.TGL_CHECKIN
                                        END
                WHERE  A.EPISODE_ID = '{$episodeid}'
            ";
        // echo '<pre>'. $query .'</pre>';
        $result = $this->db->query($query);
        return $result;
    }
}

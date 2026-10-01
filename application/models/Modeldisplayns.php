<?php
    class Modeldisplayns extends CI_Model{

        function judul($kode){
            $query =
                    "  
                        SELECT DISTINCT A.DISPLAY
                        FROM SR01_ANTRIAN_URL A
                        WHERE A.LOKASI_ID='001'
                        AND   A.AKTIF='1'
                        AND   A.KODE='".$kode."'
                    ";

            $recordset = $this->db->query($query);
            $recordset = $recordset->result();
            return $recordset;
        }

        function antriannursestation($kodedisplay,$batas,$parameter,$orderby){
            $query =
                    "  
                        SELECT Y.*
                        FROM(
                            SELECT ROWNUM NOURUT, X.*
                            FROM(
                                SELECT A.BOOKING_ID, A.PASIEN_ID, A.EPISODE_ID, 
										
										-- URUT,
										CASE
											WHEN A.IS_PRIORITAS = 'Y'
												THEN B.ANTRIAN_PRIORITAS
											ELSE
												A.URUT
										END AS URUT,  
										
										A.POLI_ID, TO_CHAR(A.TGL_HADIR,'DD.MM.YYYY HH24:MI:SS')JAMCHECKKIN, A.TGL_HADIR, SUBSTR(A.URUT,0,1)SLOTANTRIAN, TO_NUMBER(SUBSTR(A.URUT,3,5)) NOMORANTRIAN, A.PANGGIL, A.TGL_PANGGIL,
                                        '/dist/voice/dingdong.wav'||'|'||
                                        '/dist/voice/nomor_antrian.wav'||'|'||
                                        '/dist/voice/'||SUBSTR(A.URUT,0,1)||'.wav'||'|'||
                                        CASE
                                            WHEN TO_NUMBER(SUBSTR(A.URUT,3,5)) <= 11 THEN
                                            '/dist/voice/'||TO_NUMBER(SUBSTR(A.URUT,3,5))||'.wav'||'|'
                                            ELSE
                                            CASE
                                                WHEN TO_NUMBER(SUBSTR(A.URUT,3,5)) < 20 THEN
                                                '/dist/voice/'||TO_NUMBER(SUBSTR(A.URUT,4,5))||'.wav'||'|'||'/dist/voice/belas.wav'||'|'
                                                ELSE
                                                CASE
                                                    WHEN TO_NUMBER(SUBSTR(A.URUT,3,5)) = 20 THEN
                                                    '/dist/voice/'||TO_NUMBER(SUBSTR(A.URUT,3,1))||'.wav'||'|'||'/dist/voice/puluh.wav'||'|'
                                                    ELSE
                                                    '/dist/voice/'||TO_NUMBER(SUBSTR(A.URUT,3,1))||'.wav'||'|'||'/dist/voice/puluh.wav'||'|'||'/dist/voice/'||TO_NUMBER(SUBSTR(A.URUT,4,1))||'.wav'||'|'
                                                END
                                            END
                                        END
                                        ||'/dist/voice/'||A.POLI_ID||'.wav'
                                        SOUNDANTRIAN,
                                        SR01_GET_SUFFIX(A.PASIEN_ID)NAMAPASIEN,
                                        ( SELECT NAMA FROM SR01_KEU_REKANAN_MS WHERE LOKASI_ID='001' AND AKTIF='1' AND REKANAN_ID=A.REKANAN_ID )PROVIDER,
                                        ( SELECT KETERANGAN FROM SR01_MED_POLI_MS WHERE LOKASI_ID='001' AND AKTIF='1' AND POLI_ID=A.POLI_ID )POLITUJUAN,

                                        CASE
                                            WHEN A.POLI_ID IN ('POLIFISOKUP','POLIFISWICARA','POLIFISIO') THEN
                                            'FISIOTHERAPI'
                                            ELSE
                                            ( SELECT UPPER(NAMA) FROM SR01_MED_DOKTER_MS WHERE LOKASI_ID='001' AND AKTIF='1' AND DOKTER_ID=A.DOKTER_ID )
                                        END NAMADOKTER
                                                                                                                        
                                FROM WEB_CO_REGISTRASI_ONLINE_HD A
								LEFT JOIN SR01_JADWAL_ANTRIAN_TEMP B ON B.EPISODE_ID = A.EPISODE_ID AND B.PASIEN_ID = A.PASIEN_ID
                                WHERE A.LOKASI_ID='001'
                                AND   A.AKTIF='1'
                                AND   A.URUT IS NOT NULL
                                AND   A.URUT<>'NULL'
                                ".$parameter."
                                AND   A.POLI_ID IN ( SELECT POLI_ID FROM SR01_ANTRIAN_URL WHERE LOKASI_ID='001' AND AKTIF='1' AND KODE='".$kodedisplay."')   
                                AND   A.EPISODE_ID NOT IN ( SELECT EPISODE_ID FROM SR01_MED_PRWT_TR WHERE LOKASI_ID='001' AND AKTIF='1' AND DONE_STATUS='01' AND PASIEN_ID=A.PASIEN_ID AND EPISODE_ID=A.EPISODE_ID )
								AND   A.TGL_MASUK >= TRUNC(SYSDATE)
								AND   A.TGL_MASUK < TRUNC(SYSDATE)+1
                                ".$orderby."
                            )X
                        )Y
                        WHERE Y.NOURUT <= TO_NUMBER('".$batas."')
                    ";

            $recordset = $this->db->query($query);
            $recordset = $recordset->result();
            return $recordset;
        }

    }
?>
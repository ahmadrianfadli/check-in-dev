<?php
    class Modelcheckin extends CI_Model{

        function checkbooking($parameter){
            $query =
                    "  
                        SELECT X.*,
                                CASE
                                    WHEN JAM_MINIMUM >= JAMSAATINI THEN
                                    '0'
                                    ELSE
                                    '1'
                                END STATUS 
                        FROM(
                            SELECT A.AKTIF, ALASAN_BATAL, BOOKING_ID, PASIEN_ID, EPISODE_ID, POLI_ID, NO_KARTU_BPJS, NO_RUJUKAN_INT, NO_RUJUKAN, URUT, HADIR,
                                SUBSTR(A.URUT,0,1)KODEANTRIAN,
                                CASE
                                    WHEN SUBSTR(A.URUT,0,1) = 'A' THEN
                                    TO_CHAR(TO_DATE(TO_CHAR(TGL_MASUK,'DD.MM.YYYY')||' '||JAM_MULAI||':00','DD.MM.YYYY HH24:MI:SS') - (30/(24*60)),'DD.MM.YYYY HH24:MI:SS')
                                    ELSE
                                    TO_CHAR(TO_DATE(TO_CHAR(TGL_MASUK,'DD.MM.YYYY')||' '||JAM_MULAI||':00','DD.MM.YYYY HH24:MI:SS') - (15/(24*60)),'DD.MM.YYYY HH24:MI:SS')
                                END JAM_MINIMUM,
                                TO_CHAR(SYSDATE,'DD.MM.YYYY HH24:MI:SS') JAMSAATINI,
                                ( SELECT TO_CHAR(MAX(TGL_MASUK),'YYYY-MM-DD') FROM WEB_CO_REGISTRASI_ONLINE_HD WHERE LOKASI_ID='001' AND AKTIF='1' AND REKANAN_ID='BPJS' AND PASIEN_ID=A.PASIEN_ID AND NO_KARTU_BPJS=A.NO_KARTU_BPJS AND TO_CHAR(TGL_MASUK,'DD.MM.YYYY')<TO_CHAR(A.TGL_MASUK,'DD.MM.YYYY'))LASTKUNJUNGAN,
                                ( SELECT NILAI FROM SR01_SYS_PARAMETER WHERE LOKASI_ID='001' AND AKTIF='1' AND PARAM_ID='NURSESTATION01')CEKBPJS,
                                ( SELECT KETERANGAN FROM SR01_MED_POLI_MS WHERE LOKASI_ID='001' AND AKTIF='1' AND POLI_ID=A.POLI_ID)POLITUJUAN,
                                SR01_GET_SUFFIX(A.PASIEN_ID)NAMAPASIEN
                                
                            FROM WEB_CO_REGISTRASI_ONLINE_HD A
                            WHERE A.LOKASI_ID='001'
                            AND   TRUNC(A.TGL_MASUK)=TRUNC(SYSDATE)
                            AND   A.POLI_ID IN ( SELECT POLI_ID FROM SR01_ANTRIAN_URL WHERE LOKASI_ID='001' AND AKTIF='1' )
                        )X
                        WHERE X.BOOKING_ID='".$parameter."' OR NO_KARTU_BPJS='".$parameter."' OR NO_RUJUKAN='".$parameter."' OR NO_RUJUKAN_INT='".$parameter."'
                    ";

            $recordset = $this->db->query($query);
            $recordset = $recordset->row();
            return $recordset;
        }

        function updatekehadiran($bookingid,$pasienid,$episodeid,$data){           
            $sql =   $this->db->update("WEB_CO_REGISTRASI_ONLINE_HD",$data,array("BOOKING_ID"=>$bookingid, "PASIEN_ID"=>$pasienid, "EPISODE_ID"=>$episodeid, "AKTIF"=>"1"));
            return $sql;
        }

    }
?>
<?php
class Modeldetaildokter extends CI_Model
{


    function detailjadwaldokter($dokterid, $poliid)
    {
        $query =
            "  
                        SELECT X.JENIS, TO_CHAR(X.TGLMULAI, 'YYYY-MM-DD')TGLMULAI,  TO_CHAR(X.TGLAKHIR, 'YYYY-MM-DD')TGLAKHIR, KETERANGAN, PARAMETER1, PARAMETER2, KUOTAONLINE,KUOTAOTS,KUOTAPASCARANAP,KUOTAKONTROL
                        FROM(
                            SELECT '1'JENIS, A.TGL_MULAI TGLMULAI, TGL_AKHIR+1 TGLAKHIR, TO_CHAR(TGL_MULAI,'D')HARIID, 'CUTI'KETERANGAN, ''PARAMETER1, ''PARAMETER2,''KUOTAONLINE,''KUOTAOTS,''KUOTAPASCARANAP,''KUOTAKONTROL
                            FROM HRD_CUTI_TRANSAKSI_HD A
                            WHERE A.LOKASI_ID='001'
                            AND   A.AKTIF='1'
                            AND   A.STATUS NOT IN ('X','8','9')
                            AND   A.NIK=(SELECT NIK FROM SR01_GEN_USER_DATA WHERE LOKASI_ID='001' AND AKTIF='1' AND DOKTER_ID='" . $dokterid . "')

                            UNION
                            
                            SELECT '2'JENIS, TANGGAL TGLMULAI, TANGGAL TGLAKHIR, TO_CHAR(TANGGAL,'D')HARIID, 'DOKTER OFF / LIBUR'KETERANGAN, ''PARAMETER1, ''PARAMETER2,''KUOTAONLINE,''KUOTAOTS,''KUOTAPASCARANAP,''KUOTAKONTROL
                            FROM SR01_MED_DOKTER_OFF A
                            WHERE A.LOKASI_ID='001'
                            AND   A.AKTIF='1'
                            AND   A.JENIS='0'
                            AND   A.DOKTER_ID='" . $dokterid . "'
                            AND   A.TANGGAL NOT IN ( SELECT TANGGAL FROM HRD_CUTI_TRANSAKSI_DT WHERE LOKASI_ID='001' AND AKTIF='1' AND TRANS_ID IN (SELECT TRANS_ID FROM HRD_CUTI_TRANSAKSI_HD WHERE LOKASI_ID='001' AND AKTIF='1' AND STATUS NOT IN ('X','8','9') AND NIK=(SELECT NIK FROM SR01_GEN_USER_DATA WHERE LOKASI_ID='001' AND AKTIF='1' AND DOKTER_ID='" . $dokterid . "')))
                            
                            UNION
                            
                            SELECT '3'JENIS, TANGGAL TGLMULAI, TANGGAL TGLAKHIR, TO_CHAR(TANGGAL,'D')HARIID, UPPER(KETERANGAN), ''PARAMETER1, ''PARAMETER2,''KUOTAONLINE,''KUOTAOTS,''KUOTAPASCARANAP,''KUOTAKONTROL
                            FROM SR01_JADWAL_LIBUR_MS A
                            WHERE A.LOKASI_ID='001'
                            AND   A.AKTIF='1'
                            AND   A.BOLEH_REGIST='T'
                            
                            UNION
                            
                            SELECT '4'JENIS, 
                                ANTRIAN.*,
                                CASE WHEN ANTRIAN.TGLMULAI IN ( SELECT TANGGAL FROM SR01_MED_DOKTER_OFF WHERE LOKASI_ID='001' AND AKTIF='1' AND POLI_ID='" . $poliid . "' AND DOKTER_ID='" . $dokterid . "') THEN
                                    ''
                                    ELSE
                                    'DOKTER OFF / LIBUR'
                                END KETERANGAN,
                                TO_CHAR(SR01_ANTRIAN.KUOTA_PERDOKTER_ALL('" . $poliid . "',ANTRIAN.HARIID,'" . $dokterid . "'))PARAMETER1,
                                TO_CHAR(SR01_ANTRIAN.TERDAFTAR_PERDOKTER_ALLSLOT('" . $poliid . "', '" . $dokterid . "', TO_CHAR(ANTRIAN.TGLMULAI,'DD.MM.YYYY'), TO_CHAR(ANTRIAN.TGLMULAI,'D')))PARAMETER2,
                                TO_CHAR(SR01_ANTRIAN.KUOTA_PERDOKTER('" . $poliid . "',ANTRIAN.HARIID,'1','" . $dokterid . "'))KUOTAONLINE,
                                TO_CHAR(SR01_ANTRIAN.KUOTA_PERDOKTER('" . $poliid . "',ANTRIAN.HARIID,'2','" . $dokterid . "'))KUOTAOTS,
                                TO_CHAR(SR01_ANTRIAN.KUOTA_PERDOKTER('" . $poliid . "',ANTRIAN.HARIID,'3','" . $dokterid . "'))KUOTAPASCARANAP,
                                TO_CHAR(SR01_ANTRIAN.KUOTA_PERDOKTER('" . $poliid . "',ANTRIAN.HARIID,'5','" . $dokterid . "'))KUOTAKONTROL
                            FROM(
                                SELECT TRUNC(SYSDATE)    TGLMULAI,  TRUNC(SYSDATE)    TGLAKHIR, TO_CHAR(SYSDATE,'D')    HARIID FROM DUAL UNION
                                SELECT TRUNC(SYSDATE)+1  TGLMULAI,  TRUNC(SYSDATE)+1  TGLAKHIR, TO_CHAR(SYSDATE+1,'D')  HARIID FROM DUAL UNION
                                SELECT TRUNC(SYSDATE)+2  TGLMULAI,  TRUNC(SYSDATE)+2  TGLAKHIR, TO_CHAR(SYSDATE+2,'D')  HARIID FROM DUAL UNION
                                SELECT TRUNC(SYSDATE)+3  TGLMULAI,  TRUNC(SYSDATE)+3  TGLAKHIR, TO_CHAR(SYSDATE+3,'D')  HARIID FROM DUAL UNION
                                SELECT TRUNC(SYSDATE)+4  TGLMULAI,  TRUNC(SYSDATE)+4  TGLAKHIR, TO_CHAR(SYSDATE+4,'D')  HARIID FROM DUAL UNION
                                SELECT TRUNC(SYSDATE)+5  TGLMULAI,  TRUNC(SYSDATE)+5  TGLAKHIR, TO_CHAR(SYSDATE+5,'D')  HARIID FROM DUAL UNION
                                SELECT TRUNC(SYSDATE)+6  TGLMULAI,  TRUNC(SYSDATE)+6  TGLAKHIR, TO_CHAR(SYSDATE+6,'D')  HARIID FROM DUAL UNION
                                SELECT TRUNC(SYSDATE)+7  TGLMULAI,  TRUNC(SYSDATE)+7  TGLAKHIR, TO_CHAR(SYSDATE+7,'D')  HARIID FROM DUAL UNION
                                SELECT TRUNC(SYSDATE)+8  TGLMULAI,  TRUNC(SYSDATE)+8  TGLAKHIR, TO_CHAR(SYSDATE+8,'D')  HARIID FROM DUAL UNION
                                SELECT TRUNC(SYSDATE)+9  TGLMULAI,  TRUNC(SYSDATE)+9  TGLAKHIR, TO_CHAR(SYSDATE+9,'D')  HARIID FROM DUAL UNION
                                SELECT TRUNC(SYSDATE)+10 TGLMULAI,  TRUNC(SYSDATE)+10 TGLAKHIR, TO_CHAR(SYSDATE+10,'D') HARIID FROM DUAL UNION
                                SELECT TRUNC(SYSDATE)+11 TGLMULAI,  TRUNC(SYSDATE)+11 TGLAKHIR, TO_CHAR(SYSDATE+11,'D') HARIID FROM DUAL UNION
                                SELECT TRUNC(SYSDATE)+12 TGLMULAI,  TRUNC(SYSDATE)+12 TGLAKHIR, TO_CHAR(SYSDATE+12,'D') HARIID FROM DUAL UNION
                                SELECT TRUNC(SYSDATE)+13 TGLMULAI,  TRUNC(SYSDATE)+13 TGLAKHIR, TO_CHAR(SYSDATE+13,'D') HARIID FROM DUAL
                            )ANTRIAN
                            WHERE ANTRIAN.HARIID<>'1'
                            AND   ANTRIAN.TGLMULAI NOT IN ( SELECT TANGGAL FROM SR01_MED_DOKTER_OFF WHERE LOKASI_ID='001' AND AKTIF='1' AND JENIS='0' AND DOKTER_ID='" . $dokterid . "' )
                            AND   ANTRIAN.TGLMULAI NOT IN ( SELECT TANGGAL FROM HRD_CUTI_TRANSAKSI_DT WHERE LOKASI_ID='001' AND AKTIF='1' AND TRANS_ID IN (SELECT TRANS_ID FROM HRD_CUTI_TRANSAKSI_HD WHERE LOKASI_ID='001' AND AKTIF='1' AND STATUS NOT IN ('X','8','9') AND NIK=(SELECT NIK FROM SR01_GEN_USER_DATA WHERE LOKASI_ID='001' AND AKTIF='1' AND DOKTER_ID='" . $dokterid . "')))
                        )X
                    ";

        $recordset = $this->db->query($query);
        $recordset = $recordset->result();
        return $recordset;
    }
}

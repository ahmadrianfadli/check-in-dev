<?php
    class Modelpilihdokter extends CI_Model{

        function namadokter($poliid)
        {
            $query =
                    "  
                        SELECT X.*,
                                CEIL(ROWNUM / 6) AS GROUP_ID
                        FROM(
                            SELECT A.DOKTER_ID, DEF_POLI_ID, UPPER(NAMA)NAMA, TO_CHAR(SYSDATE, 'Day, DD Mon YYYY')HARIINI,
                                    ( SELECT KOLEGIUM FROM SR01_RMN_KOLEGIUM_MS WHERE LOKASI_ID='001' AND AKTIF='1' AND KOLEGIUM_ID=(SELECT KOLEGIUM_ID FROM SR01_RMN_DOKTER_MS WHERE LOKASI_ID='001' AND AKTIF='1' AND DOKTER_ID=A.DOKTER_ID))KOLEGIUM
                                                                                    
                            FROM SR01_MED_DOKTER_MS A
                            WHERE A.LOKASI_ID='001'
                            AND   A.AKTIF='1'
                            AND   A.DEF_POLI_ID='".$poliid."'
                            AND   A.DOKTER_ID IN ( SELECT DOKTER_ID FROM SR01_JADWAL_ANTRIAN WHERE LOKASI_ID='001' AND AKTIF='1' AND POLI_ID=A.DEF_POLI_ID AND DOKTER_ID=A.DOKTER_ID )
                            ORDER BY NAMA ASC
                        )X
                    ";

            $recordset = $this->db->query($query);
            $recordset = $recordset->result();
            return $recordset;
        }

    }
?>
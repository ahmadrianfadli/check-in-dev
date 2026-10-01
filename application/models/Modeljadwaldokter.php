<?php
    class Modeljadwaldokter extends CI_Model{


        function daftarpoli()
        {
            $query =
                    "  
                        SELECT POLI_ID, KETERANGAN, KATEGORI_ID, CEIL(ROWNUM / 9) AS GROUP_ID
                        FROM (
                            SELECT A.POLI_ID, REPLACE(KETERANGAN, 'POLI ', '') AS KETERANGAN, KATEGORI_ID
                            FROM SR01_MED_POLI_MS A
                            WHERE A.LOKASI_ID = '001'
                            AND A.AKTIF = '1'
                            AND A.POLI_ID NOT IN ('APS', 'POLI0000000032', 'MEDIC0000000000', 'POLIFISIO', 'POLI0000000000', 'POLI0000000040','CAPD0000000001', 'POLI0000000016', 'POLI0000000015', 'POLI0000000031', 'UGD01', 'UGD02','POLI0000000039', 'KLINI0000000001', 'POLI0000000026')
                            ORDER BY KETERANGAN
                        )
                    ";

            $recordset = $this->db->query($query);
            $recordset = $recordset->result();
            return $recordset;
        }

    }
?>
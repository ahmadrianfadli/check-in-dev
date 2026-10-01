<?php

class Modelpasienlama extends CI_Model
{

    function masterantrian($head = null, $parent = null)
    {

        if ($head) {
            $head = " AND UPPER({$head}) = 'Y'";
        } else {
            $head = '';
        }
        if ($parent) {
//            if ($parent == 'A23') {
//                $parent = "AND (HEADER_ID = '{$parent}' OR JENIS_ID = 'A01')";
//            } else {
                $parent = "AND HEADER_ID = '{$parent}'";
//            }
        } else {
            $parent = 'AND HEADER_ID IS NULL';
        }

        $query = "
            SELECT 
                A.JENIS_ID, A.KODE, A.HEADER_ID,
                KETERANGAN,
                ICON_CLASS,
                -- CASE WHEN A.JENIS_ID = 'A01' THEN 'BPJS' ELSE KETERANGAN END AS KETERANGAN, 
                -- CASE WHEN A.JENIS_ID = 'A01' THEN 'fa-solid fa-b' ELSE ICON_CLASS END AS ICON_CLASS,
                (SELECT COUNT(HEADER_ID) FROM SR01_ANTRIAN_MS WHERE HEADER_ID = A.JENIS_ID) ADAANAK
            FROM SR01_ANTRIAN_MS A
            WHERE A.LOKASI_ID='001'
            AND   A.AKTIF='1'
            -- AND   A.GROUP_ID IN ('MG1')
            {$parent}
            {$head}
            GROUP BY A.JENIS_ID, KETERANGAN, KODE, ICON_CLASS, A.HEADER_ID, URUT
            ORDER BY URUT --KETERANGAN ASC
        ";

        $recordset = $this->db->query($query);
        $recordset = $recordset->result();
        return $recordset;
    }

}

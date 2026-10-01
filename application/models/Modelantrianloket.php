<?php

class Modelantrianloket extends CI_Model
{

    function masterantrian($head = null, $parent = null)
    {

        if ($head) {
            $head = " AND UPPER({$head}) = 'Y'";
        } else {
            $head = '';
        }
        if ($parent) {
            $parent = "AND HEADER_ID = '{$parent}'";
        } else {
            $parent = 'AND HEADER_ID IS NULL';
        }
        $query =
            "  
                        SELECT A.JENIS_ID, KETERANGAN, KODE, ICON_CLASS, A.HEADER_ID,
                        (SELECT COUNT(HEADER_ID) FROM SR01_ANTRIAN_MS WHERE HEADER_ID = A.JENIS_ID) ADAANAK
                        FROM SR01_ANTRIAN_MS A
                        WHERE A.LOKASI_ID='001'
                        AND   A.AKTIF='1'
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

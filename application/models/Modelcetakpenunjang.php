<?php
class Modelcetakpenunjang extends CI_Model{

    function mastercetak($head = null,$parent = null,$kode = null)
    {

        if($head) {
            $head = " AND UPPER({$head}) = 'Y'";
        } else {
            $head = '';
        }
        if($parent) {
            $parent = "AND HEADER_ID = '{$parent}'";
        } else {
            $parent = 'AND HEADER_ID IS NULL';
        }
        if($kode) {
            $kode = "AND KODE = '{$kode}'";
        } else {
            $kode = 'AND KODE IS NULL';
        }
        $query =
            "
                        SELECT A.JENIS_ID, KETERANGAN, KODE, ICON_CLASS, A.HEADER_ID,
                        (SELECT COUNT(HEADER_ID) FROM SR01_ANTRIAN_MS WHERE HEADER_ID = A.JENIS_ID) ADAANAK
                        FROM SR01_ANTRIAN_MS A
                        WHERE A.LOKASI_ID='001'
                        AND   A.AKTIF='1'
                        AND   A.GROUP_ID IN ('KSR','KS2')
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

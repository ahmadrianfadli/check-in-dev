<?php
    class Modelmenu extends CI_Model{

        function menudisplay(){
            $query =
                    "  
                        SELECT DISTINCT A.KODE, DISPLAY, ICON, JENIS
                        FROM SR01_ANTRIAN_URL A
                        WHERE A.LOKASI_ID='001'
                        AND   A.AKTIF='1'
                        AND   A.KODE IS NOT NULL
                        AND   A.DISPLAY IS NOT NULL
                        ORDER BY DISPLAY ASC
                    ";

            $recordset = $this->db->query($query);
            $recordset = $recordset->result();
            return $recordset;
        }

    }
?>
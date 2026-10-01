<?php
    class Modeltabantrianrm extends CI_Model{

        function masterantrian()
        {
            $query =
                    "  
                        SELECT A.JENIS_ID, KETERANGAN, KODE, ICON_CLASS
                        FROM SR01_ANTRIAN_MS A
                        WHERE A.LOKASI_ID='001'
                        AND   A.AKTIF='1'
                        AND   A.GROUP_ID='RM1'
                        ORDER BY KETERANGAN ASC
                    ";

            $recordset = $this->db->query($query);
            $recordset = $recordset->result();
            return $recordset;
        }

        function lastantrian($kode)
        {
            $query =
                    "
                        SELECT A.KODE, URUT, CREATED_DATE,
                                ( SELECT KETERANGAN FROM SR01_ANTRIAN_MS WHERE LOKASI_ID='001' AND AKTIF='1' AND KODE=A.KODE )KETERANGAN
                        FROM SR01_ANTRIAN_PASIEN A
                        WHERE A.LOKASI_ID='001'
                        AND   A.AKTIF='1'
                        AND   A.KODE='".$kode."'
                        AND   TRUNC(A.TANGGAL)=TRUNC(SYSDATE)
                        AND   A.CREATED_DATE = ( SELECT MAX(CREATED_DATE) FROM SR01_ANTRIAN_PASIEN WHERE LOKASI_ID='001' AND KODE=A.KODE AND TRUNC(TANGGAL)=TRUNC(SYSDATE) )
                        ORDER BY CREATED_DATE DESC
                    ";

			$recordset = $this->db->query($query);
			$recordset = $recordset->row();
			return $recordset;
        }

        function simpanantrian($data){           
            $sql =   $this->db->insert("SR01_ANTRIAN_PASIEN",$data);
            return $sql;
        }

    }
?>
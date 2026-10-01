<?php
    class Modelantrian extends CI_Model{

        function getidantrian(){
            $query = "
                SELECT SR01_BUATID.BUAT_ID_ANTRI_KASIR IDANTRIAN
                FROM DUAL
            ";

            $recordset = $this->db->query($query);
            $recordset = $recordset->row();
            return $recordset;
        }

        function cekdatapasien($parameter){
            $query = "
                SELECT A.INT_PASIEN_ID, A.NAMA, A.NO_IDENTITAS, A.PASIEN_ID, A.EMAIL
                FROM SR01_GEN_PASIEN_MS A
                WHERE A.LOKASI_ID = '001' AND A.JENIS_PAS = 'I'
                AND A.INT_PASIEN_ID = '".$parameter."' OR A.NO_IDENTITAS = '".$parameter."'
            ";

            $recordset = $this->db->query($query);
            $recordset = $recordset->row();
            return $recordset;
        }

        function cekpunyaantrian($parameter){
            $query = "
                SELECT A.INT_PASIEN_ID, A.NAMA, A.NO_IDENTITAS, A.PASIEN_ID
                FROM SR01_ANTRIAN_PASIEN A
                WHERE A.LOKASI_ID = '001' AND A.JENIS = '1' AND A.STATUS <> '3'
                AND TRUNC(A.TANGGAL) = TRUNC(SYSDATE)
                AND A.INT_PASIEN_ID = '".$parameter."' OR A.NO_ID = '".$parameter."'
            ";

            $recordset = $this->db->query($query);
            $recordset = $recordset->row();
            return $recordset;    
        }

        function geturutantrikasir(){
            $query = "
                SELECT X.*, (SELECT COUNT(A.KODE)+1 FROM SR01_ANTRIAN_PASIEN A WHERE A.KODE = X.KODE AND TRUNC(A.CREATED_DATE) = TRUNC(SYSDATE)) NOMOR
                FROM (
                    SELECT 'E' KODE
                    FROM DUAL
                ) X
            ";

            $recordset = $this->db->query($query);
            $recordset = $recordset->row();
            return $recordset;
        }

        function insertantrikasir($idantrikasir, $idpasantri, $idPasien, $kdantrian, $noantrian)
        {   
            $query = "
                Insert into SR01_ANTRIAN_PASIEN
                    (LOKASI_ID, TRANS_ID, NO_ID, PASIEN_ID, TANGGAL, KODE, URUT, JENIS, AKTIF, CREATED_DATE, CREATED_BY)
                Values
                    ('001', '$idantrikasir', '$idpasantri', '$idPasien', TRUNC(SYSDATE), '$kdantrian', '$noantrian', '1', '1', SYSDATE, USER)
            ";

            $insresult = $this->db->query($query);
            return $insresult;
        }

    }
?>
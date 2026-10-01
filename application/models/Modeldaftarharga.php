<?php
class Modeldaftarharga extends CI_Model
{
    function hargalayan($kategoriId, $carikata, $is_exe)
    {
        $carikata = $this->db->escape('%' . strtoupper($carikata) . '%');

        // Jika kategori JKL-UMU, ambil JKL-DVK dan JKL-UMU
        if ($kategoriId == 'JKL-UMU') {
            $filterKategori = "LY.KATEGORI_ID IN ('JKL-DVK', 'JKL-UMU')";
        } elseif($kategoriId == 'JKL-PG26') {
            $filterKategori = "LY.KATEGORI_ID LIKE 'PG26%'";
        } else {
            $filterKategori = "LY.KATEGORI_ID = '" . $kategoriId . "'";
        }

//        if ($is_exe == 'Y') {
//            $filterExe = "LY.IS_EXE = 'Y'";
//        } else {
//            $filterExe = "LY.IS_EXE = 'T'";
//        }

        $query = "
            SELECT ROWNUM NOMOR, X.*
            FROM (
                SELECT DISTINCT LY.LAYAN_ID, LY.KATEGORI_ID , LY.NAMA_LAYAN1, LY.NAMA_LAYAN2,
                CASE
                    WHEN (LY.KATEGORI_ID = 'JKL-UMU' AND LY.KATEGORI_ID = 'JKL-DVK') AND (LY.NAMA_LAYAN2 IS NOT NULL) THEN
                        (LY.NAMA_LAYAN1 || ' ('||LY.NAMA_LAYAN2||')')
                    ELSE
                        LY.NAMA_LAYAN1    
                END NAMALAYAN,
                LY.IS_EXE,
                (SELECT Z.HARGA FROM SR01_KEU_HARGA_DT Z WHERE Z.LOKASI_ID = '001' AND Z.LAYAN_ID = LY.LAYAN_ID AND Z.KELAS_ID = '6' AND Z.AKTIF = '1')RJ,
                (SELECT Z.HARGA FROM SR01_KEU_HARGA_DT Z WHERE Z.LOKASI_ID = '001' AND Z.LAYAN_ID = LY.LAYAN_ID AND Z.KELAS_ID = '1' AND Z.AKTIF = '1')KELAS1,
                (SELECT Z.HARGA FROM SR01_KEU_HARGA_DT Z WHERE Z.LOKASI_ID = '001' AND Z.LAYAN_ID = LY.LAYAN_ID AND Z.KELAS_ID = '2' AND Z.AKTIF = '1')KELAS2,
                (SELECT Z.HARGA FROM SR01_KEU_HARGA_DT Z WHERE Z.LOKASI_ID = '001' AND Z.LAYAN_ID = LY.LAYAN_ID AND Z.KELAS_ID = '3' AND Z.AKTIF = '1')KELAS3,
                (SELECT Z.HARGA FROM SR01_KEU_HARGA_DT Z WHERE Z.LOKASI_ID = '001' AND Z.LAYAN_ID = LY.LAYAN_ID AND Z.KELAS_ID = 'V' AND Z.AKTIF = '1')VIP,
                (SELECT Z.HARGA FROM SR01_KEU_HARGA_DT Z WHERE Z.LOKASI_ID = '001' AND Z.LAYAN_ID = LY.LAYAN_ID AND Z.KELAS_ID = 'VVIP' AND Z.AKTIF = '1')VVIP
                FROM SR01_KEU_LAYAN_MS LY
                WHERE LY.LOKASI_ID = '001' AND LY.AKTIF ='1'
                AND LY.KATEGORI_ID LIKE 'PG26%'
                AND LY.LAYAN_ID IN ('26PEMER00000031', '26PEMER00000029', '26PEMER00000032')
                AND UPPER(LY.NAMA_LAYAN1) LIKE $carikata
                ORDER BY LY.NAMA_LAYAN1
            ) X
        ";

        $recordset = $this->db->query($query);
        $recordset = $recordset->result();
        return $recordset;
    }
}

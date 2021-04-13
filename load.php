<?php
include "config/config.php";
$proses = isset($_GET['proses']) ? $_GET['proses'] : "";

if($proses == "getKodeCabang"){
    $searchTerm=isset($_GET['term'])?$_GET['term']:null;
    $sql = "SELECT IdCabang, KodeCabang, NamaCabang FROM tb_cabang WHERE NamaCabang LIKE '%$searchTerm%' OR KodeCabang LIKE '$searchTerm%'";
    $query = $db->query($sql);
    $row = array();
    $data = array();
    while($dt = $query->fetch(PDO::FETCH_ASSOC)){
        $row['label'] = $dt['KodeCabang'];
        $row['NamaCabang'] = $dt['NamaCabang'];
        $row['IdCabang'] = $dt['IdCabang'];
        $data[] = $row;
    }
    echo json_encode($data);
}elseif($proses == "getKelasJabatan"){
    $searchTerm=isset($_GET['term'])?$_GET['term']:null;
    $sql = "SELECT KelasJabatan FROM tb_kelas_jabatan WHERE KelasJabatan LIKE '%$searchTerm%' LIMIT 0,10";
    $query = $db->query($sql);
    $ress = array();
    $res = array();
    while($r = $query->fetch(PDO::FETCH_ASSOC)){
        $ress['label'] = $r['KelasJabatan'];
        $res[] = $ress;
    }
   
    echo json_encode($res);

}elseif($proses == "getJabatanPejabat"){
    $searchTerm=isset($_GET['term'])?$_GET['term']:null;
    $sql = "SELECT NamaJabatan, IdJabatan FROM tb_jabatan_pejabat WHERE NamaJabatan LIKE '%$searchTerm%' LIMIT 0,10";
    $query = $db->query($sql);
    $ress = array();
    $res = array();
    while($r = $query->fetch(PDO::FETCH_ASSOC)){
        $ress['label'] = $r['NamaJabatan'];
        $ress['IdJabatan'] = $r['IdJabatan'];
        $res[] = $ress;
    }
    echo json_encode($res);
}elseif($proses == "GetDataPejabatInMutasi"){
    $searchTerm=isset($_GET['term'])?$_GET['term']:null;
    $sql = "SELECT tb_pejabat.*, tb_jabatan_pejabat.NamaJabatan FROM tb_pejabat INNER JOIN tb_jabatan_pejabat ON tb_pejabat.IdJabatan = tb_jabatan_pejabat.IdJabatan WHERE tb_pejabat.Nik LIKE '%$searchTerm%' OR tb_pejabat.Nama LIKE '%$searchTerm%' LIMIT 10";
    $query = $db->query($sql);
    $data = array();
    $result = array();
    $row = $query->rowCount();
    if($row > 0){
        while($r = $query->fetch(PDO::FETCH_ASSOC)){
            $data['label'] = $r['Nik'];
            $data['Nama'] = $r['Nama'];
            $data['KJ'] = $r['KelasJabatan'];
            $data['IdPejabat'] = $r['IdPejabat'];
            $data['Jabatan'] = $r['NamaJabatan'];
            $data['TptLahir'] = $r['TptLahir'];
            $data['TglLahir'] = $r['TglLahir'];
            $result[] = $data;
        }
    }else{
        $result = array();
    }
    echo json_encode($result);
}elseif($proses == "GetTags"){
    $sql = "SELECT IdJabatan, NamaJabatan FROM tb_jabatan_pejabat ORDER BY IdJabatan ASC";
    $query = $db->query($sql);
    $rows = $query->rowCount();
    $dt = array();
    $result = array();
    if($rows > 0){
        while($r = $query->fetch(PDO::FETCH_ASSOC)){
            $dt['value']  = $r['IdJabatan'];
            $dt['NamaJabatan'] = $r['NamaJabatan'];
            $result[] = $dt;
        }
    }else{
        $result[]=array();
    }

    echo json_encode($result);
}elseif($proses == "getKodeJabatan"){
    $searchTerm=isset($_GET['term'])?$_GET['term']:null;
    $sql = "SELECT KodeJabatan, NamaJabatan FROM tb_jabatan WHERE KodeJabatan LIKE '%$searchTerm%' || NamaJabatan LIKE '%$searchTerm%' ORDER BY IdJabatan ASC";
    $query = $db->query($sql);
    $row = array();
    $data = array();
    while($dt = $query->fetch(PDO::FETCH_ASSOC)){
        $row['label'] = $dt['KodeJabatan'];
        $row['NamaJabatan'] = $dt['NamaJabatan'];
        $data[] = $row;
    }
    echo json_encode($data);
}elseif($proses == "getDataJenisSurat"){
    $searchTerm=isset($_GET['term'])?$_GET['term']:null;
    $sql = "SELECT Kode, NamaJenis,IdJenisSurat FROM tb_jenis_surat_keluar WHERE Kode LIKE '%$searchTerm%' || NamaJenis LIKE '%$searchTerm%' ORDER BY IdJenisSurat ASC";
    $query = $db->query($sql);
    $row = array();
    $data = array();
    while($dt = $query->fetch(PDO::FETCH_ASSOC)){
        $row['label'] = $dt['Kode'];
        $row['NamaJenis'] = $dt['NamaJenis'];
        $row['IdJenisSurat'] = $dt['IdJenisSurat'];
        $data[] = $row;
    }
    echo json_encode($data);
}elseif($proses == "getDataJabatanPejabat"){
    $searchTerm=isset($_GET['term'])?$_GET['term']:null;
    $sql = "SELECT NamaJabatan, IdJabatan,KodeDisposisi FROM tb_jabatan_pejabat WHERE NamaJabatan LIKE '%$searchTerm%'  ORDER BY IdJabatan ASC";
    $query = $db->query($sql);
    $row = array();
    $data = array();
    while($dt = $query->fetch(PDO::FETCH_ASSOC)){
        $row['label'] = $dt['NamaJabatan'];
        $row['IdJabatan'] = $dt['IdJabatan'];
        $row['KodeDisposisi'] = $dt['KodeDisposisi'];
        $data[] = $row;
    }
    echo json_encode($data);
}elseif($proses == "NomorKontrakInduk"){
    $searchTerm=isset($_GET['term'])?$_GET['term']:null;
    $sql = "SELECT NomorKontrak, JudulKontrak, IdKontrak FROM tb_kontrak WHERE (NomorKontrak LIKE '%$searchTerm%' OR JudulKontrak LIKE '%$searchTerm%' ) AND JenisKontrak = '0'  ORDER BY IdKontrak ASC";
    $query = $db->query($sql);
    $row = array();
    $data = array();
    while($dt = $query->fetch(PDO::FETCH_ASSOC)){
        $row['label'] = $dt['NomorKontrak'];
        $row['JudulKontrak'] = $dt['JudulKontrak'];
        $row['IdKontrak'] = $dt['IdKontrak'];
        $data[] = $row;
    }
    echo json_encode($data);
} elseif($proses == "getKodeNomorKontrak"){
    $searchTerm=isset($_GET['term'])?$_GET['term']:null;
    $IdCabang=isset($_GET['IdCabang'])?$_GET['IdCabang']:null;

    $sql = "SELECT IdKontrak,NomorKontrak, JudulKontrak FROM tb_kontrak WHERE IdCabang = '$IdCabang' AND NomorKontrak LIKE '%$searchTerm%'";
    $query = $db->query($sql);
    $data=array();
    $row=array();
    while($dt = $query->fetch(PDO::FETCH_ASSOC)){
        $row['label'] = $dt['NomorKontrak'];
        $row['IdKontrak'] = $dt['IdKontrak'];
        $row['JudulKontrak'] = $dt['JudulKontrak'];
        $data[] = $row;
    }
    echo json_encode($data);
}



?>
<?php
session_start();
include_once '../../config/config.php';
include_once '../../lib/excel_reader/excel_reader2.php';
$UserUpdate = $_SESSION['IdUser'];
$date = date("Y-m-d H:i:s");
$proses = isset($_GET['proses']) ? $_GET['proses'] : "";
switch ($proses) {
	case 'DetailData':
		$Filter = $_GET['Filter'] != "" ? "WHERE tb_cabang.NamaCabang LIKE '%$_GET[Filter]%'" : "";
		$row = array(); 
		$data = array(); 
		$no=1;
		$sql = "SELECT tb_karyawan.IdKaryawan, tb_karyawan.TMTIsu, tb_karyawan.NamaKaryawan, tb_karyawan.NIK, tb_karyawan.JenisKelamin, CONCAT(TptLahir,', ',TglLahir) as TTL, tb_karyawan.TMTIsu, tb_cabang.NamaCabang, tb_divisi.NamaDivisi, tb_jabatan.NamaJabatan FROM tb_karyawan 
		INNER JOIN tb_cabang ON tb_karyawan.IdCabang = tb_cabang.IdCabang 
		LEFT JOIN tb_jabatan ON tb_karyawan.Jabatan = tb_jabatan.KodeJabatan
		LEFT JOIN tb_divisi ON tb_karyawan.UnitTugas = tb_divisi.KodeDivisi
		$Filter ORDER BY tb_karyawan.IdKaryawan DESC
		";
		$query = $db->query($sql);
		$JumRow = $query->rowCount();
		if($JumRow > 0){
			while ($res = $query->fetch(PDO::FETCH_ASSOC)) { 
				$aksi = $_SESSION['Level'] == "author" ? "" : "<a class='btn btn-xs btn-primary' data-toggle='tooltip' title='Ubah Data' onclick=\"Crud('".$res['IdKaryawan']."', 'ubah')\"><i class='fa fa-edit'></i></a> <a class='btn btn-xs btn-danger' data-toggle='tooltip' title='Hapus Data' onclick=\"Crud('".$res['IdKaryawan']."', 'hapus')\"><i class='fa fa-trash-o'></i></a>";
				$cetak = "<a class='btn btn-xs btn-success' data-toggle='tooltip' target='_blank' href='Cetak/CV.php?IdKaryawan=".$res['IdKaryawan']."' title='Cetak CV' ><i class='fa fa-print'></i></a> "; 
				$row['No'] = $no;
				$row['NIK'] = $res['NIK'];
				$row['NamaKaryawan'] = $res['NamaKaryawan'];
				$row['TTL'] = $res['TTL'];
				$row['Cabang'] = $res['NamaCabang'];
				$row['Jabatan'] = $res['NamaJabatan'];
				$row['UnitTugas'] = $res['NamaDivisi'];
				$row['TMTISMA'] = $res['TMTIsu'];
				$row['JK'] = $res['JenisKelamin'];
				$row['Aksi'] = $cetak.$aksi;
				$data['data'][] = $row;
				$no++;
			}
		}else{
			$data['data']='';
		}

		echo json_encode($data);
		break;
	case 'Crud':
		$aksi = $_POST['aksi'];
		$IdKaryawan = $_POST['IdKaryawan'];
		$IdCabang = $_POST['IdCabang'];
		$KodeCabang = $_POST['KodeCabang'];
		$StatusKaryawan = $_POST['StatusKaryawan'];
		if($aksi != "update"){
			$TptLahir = $_POST['TptLahir'];
			$TglLahir = $_POST['TglLahir'];
		}
		$NIK = $_POST['NIK'];
		$NoKtp = $_POST['NoKtp'];
		$NamaKaryawan = $_POST['NamaKaryawan'];
		$JenisKelamin = $_POST['JenisKelamin'];
		$NoHp = $_POST['NoHp'];
		$UnitTugas = $_POST['UnitTugas'];
		$Jabatan = $_POST['Jabatan'];
		$TMTCabang = $_POST['TMTCabang'];
		$TMTIsu = $_POST['TMTIsu'];
		$Agama = $_POST['Agama'];
		$UkuranBaju = $_POST['UkuranBaju'];
		$UkuranSepatu = $_POST['UkuranSepatu'];
		$Alamat = $_POST['Alamat'];
		switch ($aksi) {
			case 'insert':
				try {
					$sql = "INSERT INTO tb_karyawan SET  IdCabang = :IdCabang,  NIK = :NIK, NamaKaryawan = :NamaKaryawan, TglLahir = :TglLahir, TptLahir = :TptLahir,  UnitTugas = :UnitTugas, NoKtp = :NoKtp, JenisKelamin = :JenisKelamin, NoHp = :NoHp, Jabatan = :Jabatan,  TMTCabang = :TMTCabang, TMTIsu = :TMTIsu,  Agama = :Agama, UkuranBaju = :UkuranBaju, UkuranSepatu = :UkuranSepatu, Alamat = :Alamat, TglCreate = :TglCreate, UserUpdate = :UserUpdate";
					$query = $db->prepare($sql);
					$query->bindParam("IdCabang", $IdCabang);
					$query->bindParam("NIK", $NIK);
					$query->bindParam("NamaKaryawan", $NamaKaryawan);
					$query->bindParam("TglLahir", $TglLahir);
					$query->bindParam("TptLahir", $TptLahir);
					$query->bindParam("UnitTugas", $UnitTugas);
					$query->bindParam("NoKtp", $NoKtp);
					$query->bindParam("JenisKelamin", $JenisKelamin);
					$query->bindParam("NoHp", $NoHp);
					$query->bindParam("Jabatan", $Jabatan);
					$query->bindParam("TMTCabang", $TMTCabang);
					$query->bindParam("TMTIsu", $TMTIsu);
					$query->bindParam("Agama", $Agama);
					$query->bindParam("UkuranBaju", $UkuranBaju);
					$query->bindParam("UkuranSepatu", $UkuranSepatu);
					$query->bindParam("Alamat", $Alamat);
					$query->bindParam("TglCreate", $date);
					$query->bindParam("UserUpdate", $UserUpdate);
					$query->execute();
					$IdKaryawan = $db->lastInsertId();
					$Pesan = "<b>".$_SESSION['NamaUser']."</b>, Berhasil Menambah Data Karyawan!";
					AddLog($_SESSION['IdUser'],$Pesan);
					if($query){ 
						echo "sukses"; exit(); 
					} else { echo "gagal"; }
					
					exit();
				} catch (PDOException $e) {
					echo $e->getMessage();
					exit();
				}
				break;
			case 'update':
				try {
					$sql = "UPDATE tb_karyawan SET  IdCabang = :IdCabang,  NamaKaryawan = :NamaKaryawan, UnitTugas = :UnitTugas, NoKtp = :NoKtp, JenisKelamin = :JenisKelamin, NoHp = :NoHp, Jabatan = :Jabatan, TMTCabang = :TMTCabang, TMTIsu = :TMTIsu,  Agama = :Agama, UkuranBaju = :UkuranBaju, UkuranSepatu = :UkuranSepatu, Alamat = :Alamat, TglUpdate = :TglUpdate, UserUpdate = :UserUpdate WHERE IdKaryawan = :IdKaryawan";
					$query = $db->prepare($sql);
					$query->bindParam("IdCabang", $IdCabang);
					$query->bindParam("NamaKaryawan", $NamaKaryawan);
					$query->bindParam("UnitTugas", $UnitTugas);
					$query->bindParam("NoKtp", $NoKtp);
					$query->bindParam("JenisKelamin", $JenisKelamin);
					$query->bindParam("NoHp", $NoHp);
					$query->bindParam("Jabatan", $Jabatan);
					$query->bindParam("TMTCabang", $TMTCabang);
					$query->bindParam("TMTIsu", $TMTIsu);
					$query->bindParam("Agama", $Agama);
					$query->bindParam("UkuranBaju", $UkuranBaju);
					$query->bindParam("UkuranSepatu", $UkuranSepatu);
					$query->bindParam("Alamat", $Alamat);
					$query->bindParam("TglUpdate", $date);
					$query->bindParam("UserUpdate", $UserUpdate);
					$query->bindParam("IdKaryawan", $IdKaryawan);
					$query->execute();
					$IdKaryawan = $db->lastInsertId();
					$Pesan = "<b>".$_SESSION['NamaUser']."</b>, Berhasil Menambah Data Karyawan dengan NIK <b>$NIK<b>!";
					AddLog($_SESSION['IdUser'],$Pesan);
					if($query){ echo "sukses"; exit(); } else { echo "gagal"; }
				} catch (PDOException $e) {
					echo $e->getMessage();
				}
				break;
			case 'delete':
				$sql = "DELETE FROM tb_karyawan WHERE IdKaryawan = :IdKaryawan";
				$query = $db->prepare($sql);
				$query->bindParam("IdKaryawan", $IdKaryawan);
				$query->execute();
				$Pesan = "<b>".$_SESSION['NamaUser']."</b>, Berhasil Menghapus Data Karyawan!";
					AddLog($_SESSION['IdUser'],$Pesan);
				if($query){ 
					$db->query("DELETE FROM tb_list_bank WHERE IdKaryawan = '$IdKaryawan'");
					$db->query("DELETE FROM tb_list_pendidikan WHERE IdKaryawan = '$IdKaryawan'");
					echo "sukses"; exit(); } else { echo "gagal"; }
				break;
		}
		print_r($_POST);
		break;
	
	case 'UploadData':
		$data=array();
		$IdCabang = $_POST['IdCabang'];
		$Jabatan = $_POST['Jabatan'];
		
		if(isset($_FILES['File'])){
		    $target = basename($_FILES['File']['name']) ;
			$exten = explode(".",$target);
			$exten = end($exten);
			$dt=array();
		    if($exten == 'xls'){
		        move_uploaded_file($_FILES['File']['tmp_name'], $target);
                chmod($_FILES['File']['name'],0777);
                // mengambil isi file xls
                $datas = new Spreadsheet_Excel_Reader($_FILES['File']['name'],false);
                // menghitung jumlah baris data yang ada
                $jumlah_baris = $datas->rowcount($sheet_index=0);
                $berhasil=0;
                $gagal=0;
                for ($i=5; $i<=$jumlah_baris; $i++){
					$NamaKaryawan     = $datas->val($i, 2);
					// menangkap data dan memasukkan ke variabel sesuai dengan kolumnya masing-masing
					if($NamaKaryawan != ""){
						$sql = "INSERT INTO tb_karyawan SET IdCabang = :IdCabang, Jabatan = :Jabatan, NamaKaryawan = :NamaKaryawan";
						$query = $db->prepare($sql);
						$query->bindParam('IdCabang',$IdCabang);
						$query->bindParam('Jabatan',$Jabatan);
						$query->bindParam('NamaKaryawan',$NamaKaryawan);
						$query->execute();
						if($query){ $berhasil++; }else{ $gagal++; }
					}
                }
              
                $Pesan = "<b>".$_SESSION['NamaUser']."</b>, Telah Melakukan Upload Data Karyawan <b>$berhasil Berasil, $gagal Gagal</b>!";
				AddLog($_SESSION['IdUser'],$Pesan);
                $data['msg'] = "sukses";
                // hapus kembali file .xls yang di upload tadi*/
                unlink($_FILES['File']['name']);
		    }else{
		    	$data['msg'] = 'filenotsupport';
		    }
		}else{
		    $data['msg'] = "filenotfound";
		}
		echo json_encode($data);
		
		break;
	
	case 'ShowData':
		$IdKaryawan = $_POST['IdKaryawan'];
		$sql = "SELECT tb_karyawan.*, tb_cabang.KodeCabang, tb_cabang.NamaCabang FROM tb_karyawan INNER JOIN tb_cabang ON tb_karyawan.IdCabang = tb_cabang.IdCabang WHERE IdKaryawan = :IdKaryawan";
		$stmt = $db->prepare($sql);
		$stmt->bindParam('IdKaryawan', $IdKaryawan);
		$stmt->execute();
		$res = $stmt->fetch(PDO::FETCH_ASSOC);
		echo json_encode($res);
		break;
	
	case "GetNewNIK" :
	   $sql = "SELECT RIGHT(NIK,4) as NIK FROM tb_karyawan ORDER BY IdKaryawan DESC LIMIT 1";
	   $query = $db->query($sql);
	   $row = $query->rowCount();
	   if($row > 0){
	       $r = $query->fetch(PDO::FETCH_ASSOC);
	       $ID = intval($r['NIK']) + 1;
	       $ID = sprintf('%04d', $ID);
	   }else{
	       $ID = "0001";
	   }
	   echo $ID;
		break;
}

?>
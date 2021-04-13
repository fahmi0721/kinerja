<?php
session_start();
include_once '../../config/config.php';
$UserUpdate = $_SESSION['IdUser'];
$date = date("Y-m-d H:i:s");
$proses = isset($_GET['proses']) ? $_GET['proses'] : "";
switch ($proses) {
	case 'DetailData':
		$row = array(); 
		$data = array(); 
		$no=1;
		$sql = "SELECT * FROM tb_surat_masuk ORDER BY IdSuratMasuk DESC";
		$query = $db->query($sql);
		$JumRow = $query->rowCount();
		if($JumRow > 0){
			while ($res = $query->fetch(PDO::FETCH_ASSOC)) { 
				$aksi = $_SESSION['Level'] == "author" ? "-" : "<a class='btn btn-xs btn-primary' data-toggle='tooltip' title='Ubah Data' onclick=\"Crud('".$res['IdSuratMasuk']."', 'ubah')\"><i class='fa fa-edit'></i></a> <a class='btn btn-xs btn-danger' data-toggle='tooltip' title='Hapus Data' onclick=\"Crud('".$res['IdSuratMasuk']."', 'hapus')\"><i class='fa fa-trash-o'></i></a>";
				$Status = $res['Status'] == 0 ? "<a href='#' onclick=\"Disposisi('".$res['IdSuratMasuk']."',0)\" data-toggle='tooltip' title='Update Disposisi' class='btn btn-xs btn-warning'><i class='fa fa-cog'></i></a>"  :"<a href='#' onclick=\"Disposisi('".$res['IdSuratMasuk']."',1)\" data-toggle='tooltip' title='Lihat Disposisi' class='btn btn-xs btn-success'><i class='fa fa-eye'></i></a>";
				if($_SESSION['Level'] == "author" && $res['Status'] == 1){
					$Status = "<a href='#' onclick=\"Disposisi('".$res['IdSuratMasuk']."',2)\" data-toggle='tooltip' title='Lihat Disposisi' class='btn btn-xs btn-success'><i class='fa fa-eye'></i></a>";
				}

				$row['No'] = $no;
				$row['NoRegis'] = "ISEMA-SM-".sprintf('%05d', $res['IdSuratMasuk']);
				$row['TglSurat'] = $res['TglSurat'];
				$row['BajuSurat'] = $res['BajuSurat'];
				$row['Perihal'] = $res['Perihal'];
				$row['NomorSurat'] = $res['NomorSurat'];
				$row['Tujuan'] = $res['Tujuan'];
				$row['AsalSurat'] = $res['AsalSurat'];
				$row['Status'] = "<center>".$Status." <a href='#' data-toggle='tooltip' onclick=\"DetailSurat('".$res['FileSurat']."')\" title='Detail Surat' class='btn btn-xs btn-info'><i class='fa fa-eye'></i></a></center>";
				$row['Aksi'] = $aksi;
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
		$TmpFileSurat = $_POST['TmpFileSurat'];
		$IdSuratMasuk = $_POST['IdSuratMasuk'];
		$TglSurat = $_POST['TglSurat'];
		$BajuSurat = $_POST['BajuSurat'];
		$Perihal = $_POST['Perihal'];
		$NomorSurat = $_POST['NomorSurat'];
		$Tujuan = $_POST['Tujuan'];
		$AsalSurat = $_POST['AsalSurat'];
		$Tl = $_POST['Tl'];
		$S = $_POST['S'];
		$Status = $_POST['Status'];
		//FILE
		$dir  = "../../Files/SuratMasuk/";
		$NamaFile = $_FILES['FileSurat']['name'];
		$TmpFile = $_FILES['FileSurat']['tmp_name'];
		switch ($aksi) {
			case 'insert':
				$FileSurat = move_uploaded_file($TmpFile, $dir.$NamaFile) ? $NamaFile : "";
				try {
					$sql = "INSERT INTO tb_surat_masuk SET  TglSurat = :TglSurat,  BajuSurat = :BajuSurat, Perihal = :Perihal,  NomorSurat = :NomorSurat, Tujuan = :Tujuan, AsalSurat = :AsalSurat, `Status` = :Statuss, FileSurat = :FileSurat, TglCreate = :TglCreate, UserUpdate = :UserUpdate, Tl = :Tl, S = :S";
					$query = $db->prepare($sql);
					$query->bindParam("TglSurat", $TglSurat);
					$query->bindParam("BajuSurat", $BajuSurat);
					$query->bindParam("Perihal", $Perihal);
					$query->bindParam("NomorSurat", $NomorSurat);
					$query->bindParam("Tujuan", $Tujuan);
					$query->bindParam("AsalSurat", $AsalSurat);
					$query->bindParam("Statuss", $Status);
					$query->bindParam("FileSurat", $FileSurat);
					$query->bindParam("TglCreate", $date);
					$query->bindParam("UserUpdate", $UserUpdate);
					$query->bindParam("Tl", $Tl);
					$query->bindParam("S", $S);
					$query->execute();
					$Pesan = "<b>".$_SESSION['NamaUser']."</b>, Berhasil Menambah Data Surat Masuk Dengan Nomor Surat. <b>$NomorSurat</b>!";
					AddLog($_SESSION['IdUser'],$Pesan);
					if($query){ echo "sukses"; exit(); } else { echo "gagal"; }
					
					exit();
				} catch (PDOException $e) {
					$Pesan = "<b>".$_SESSION['NamaUser']."</b>, Gagal Dengan Keterengan : ".$e->getMessage();
					AddLog($_SESSION['IdUser'],$Pesan);
					echo $e->getMessage();
					exit();
				}

				break;
			case 'update':
				try {
					$Filter = !empty($TmpFile) ? " FileSurat = :FileSurat, " : "";
					$FileSurat = move_uploaded_file($TmpFile, $dir.$NamaFile) ? $NamaFile : "";
					$sql = "UPDATE tb_surat_masuk SET  TglSurat = :TglSurat,  BajuSurat = :BajuSurat, Perihal = :Perihal,  NomorSurat = :NomorSurat, Tujuan = :Tujuan, AsalSurat = :AsalSurat, `Status` = :Statuss, $Filter  TglUpdate = :TglUpdate, UserUpdate = :UserUpdate WHERE IdSuratMasuk = :IdSuratMasuk";
					$query = $db->prepare($sql);
					$query->bindParam("TglSurat", $TglSurat);
					$query->bindParam("BajuSurat", $BajuSurat);
					$query->bindParam("Perihal", $Perihal);
					$query->bindParam("NomorSurat", $NomorSurat);
					$query->bindParam("Tujuan", $Tujuan);
					$query->bindParam("AsalSurat", $AsalSurat);
					$query->bindParam("Statuss", $Status);
					if(!empty($TmpFile)){
						$query->bindParam("FileSurat", $FileSurat);
					}
					$query->bindParam("TglUpdate", $date);
					$query->bindParam("UserUpdate", $UserUpdate);
					$query->bindParam("IdSuratMasuk", $IdSuratMasuk);
					$query->execute();
					$Pesan = "<b>".$_SESSION['NamaUser']."</b>, Berhasil Mengubah Data Surat Masuk!";
					AddLog($_SESSION['IdUser'],$Pesan);
					if($query){ echo "sukses"; exit(); } else { echo "gagal"; }
				} catch (PDOException $e) {
					$Pesan = "<b>".$_SESSION['NamaUser']."</b>, Gagal Mengubah Data Surat Masuk!. Ket : ".$e->getMessage();
					AddLog($_SESSION['IdUser'],$Pesan);
					echo $e->getMessage();
				}
				break;
			case 'delete':
				$FileSurat = $db->query("SELECT FileSurat FROM tb_surat_masuk WHERE IdSuratMasuk = '$IdSuratMasuk'");
				$sql = "DELETE FROM tb_surat_masuk WHERE IdSuratMasuk = :IdSuratMasuk";
				$query = $db->prepare($sql);
				$query->bindParam("IdSuratMasuk", $IdSuratMasuk);
				$query->execute();
				$Pesan = "<b>".$_SESSION['NamaUser']."</b>, Berhasil Menghapus Data Surat Masuk!";
					AddLog($_SESSION['IdUser'],$Pesan);
				if($query){ echo "sukses"; exit(); } else { echo "gagal"; }
				break;
		}
		break;

	case 'ShowData':
		$IdSuratMasuk = $_POST['IdSuratMasuk'];
		$sql = "SELECT * FROM tb_surat_masuk WHERE IdSuratMasuk = :IdSuratMasuk";
		$stmt = $db->prepare($sql);
		$stmt->bindParam('IdSuratMasuk', $IdSuratMasuk);
		$stmt->execute();
		$res = $stmt->fetch(PDO::FETCH_ASSOC);
		echo json_encode($res);
		break;
	
	case 'ShowDataDisposisi':
		$IdSuratMasuk = $_POST['IdSuratMasuk'];
		$sql = "SELECT * FROM tb_disposisi WHERE IdSuratMasuk = :IdSuratMasuk";
		$stmt = $db->prepare($sql);
		$stmt->bindParam('IdSuratMasuk', $IdSuratMasuk);
		$stmt->execute();
		$res = $stmt->fetch(PDO::FETCH_ASSOC);
		$res['ItemKepada'] = explode(",",$res['Kepada']);
		$res['ItemDisposisi'] = explode(",",$res['Disposisi']);
		echo json_encode($res);
		break;
	case 'UpdateDisposisi':
		$aksi = $_POST['aksi'];
		$NomorSurat = $_POST['NomorSurat'];
		$IdSuratMasuk = $_POST['IdSurat'];
		$Kepada = implode(",",$_POST['Kepada']);
		$Disposisi = implode(",",$_POST['Disposisi']);
		$Catatan = $_POST['Catatan'];
		if($aksi == "insert"){
			try{
				$sql = "INSERT INTO tb_disposisi SET IdSuratMasuk = :IdSuratMasuk, Kepada = :Kepada, Disposisi = :Disposisi, Catatan = :Catatan, TglCreated = :TglCreate, UserUpdate = :UserUpdate";
				$query = $db->prepare($sql);
				$query->bindParam('IdSuratMasuk', $IdSuratMasuk);
				$query->bindParam('Kepada', $Kepada);
				$query->bindParam('Disposisi', $Disposisi);
				$query->bindParam('Catatan', $Catatan);
				$query->bindParam('TglCreate', $date);
				$query->bindParam('UserUpdate', $UserUpdate);
				$query->execute();
				$Pesan = "<b>".$_SESSION['NamaUser']."</b>, Berhasil Mendisposisi Surat Dengan Nomor Surat <b>".$NomorSurat."</b>";
				AddLog($_SESSION['IdUser'],$Pesan);
				$Upate = $db->query("UPDATE tb_surat_masuk SET `Status` = '1' WHERE IdSuratMasuk = '$IdSuratMasuk'");
				if($query){ echo "sukses"; exit(); } else { echo "gagal"; }
			} catch (PDOException $e) {
				$Pesan = "<b>".$_SESSION['NamaUser']."</b>, Proses Didposisi Surat Gagal dengan Nomor Surat <b>".$NomorSurat."</b>!. Ket : ".$e->getMessage();
				AddLog($_SESSION['IdUser'],$Pesan);
				echo $e->getMessage();
			}
		}else if($aksi == "update"){
			try{
				$IdDisposisi = $_POST['IdDisposisi'];
				$sql = "UPDATE tb_disposisi SET Kepada = :Kepada, Disposisi = :Disposisi, Catatan = :Catatan,  UserUpdate = :UserUpdate WHERE IdDisposisi = :IdDisposisi";
				$query = $db->prepare($sql);
				$query->bindParam('Kepada', $Kepada);
				$query->bindParam('Disposisi', $Disposisi);
				$query->bindParam('Catatan', $Catatan);
				$query->bindParam('UserUpdate', $UserUpdate);
				$query->bindParam('IdDisposisi', $IdDisposisi);
				$query->execute();
				$Pesan = "<b>".$_SESSION['NamaUser']."</b>, Berhasil  Mengubah Disposisi Surat Dengan Nomor Surat <b>".$NomorSurat."</b>";
				AddLog($_SESSION['IdUser'],$Pesan);
				if($query){ echo "sukses"; exit(); } else { echo "gagal"; }
			} catch (PDOException $e) {
				$Pesan = "<b>".$_SESSION['NamaUser']."</b>, Proses Didposisi Surat Gagal dengan Nomor Surat <b>".$NomorSurat."</b>!. Ket : ".$e->getMessage();
				AddLog($_SESSION['IdUser'],$Pesan);
				echo $e->getMessage();
			}
		}
		
		break;


	
}

?>
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
		$sql = "SELECT tb_pejabat.Nama,tb_pejabat.Title, tb_mutasi_pejabat.Status, tb_pejabat.Nik, tb_mutasi_pejabat.IdMutasi, tb_pejabat.KelasJabatan, tb_mutasi_pejabat.TmtMutasi, tb_mutasi_pejabat.Keterangan, tb_jabatan_pejabat.NamaJabatan FROM tb_mutasi_pejabat INNER JOIN tb_pejabat ON tb_mutasi_pejabat.IdPejabat = tb_pejabat.IdPejabat INNER JOIN tb_jabatan_pejabat ON tb_pejabat.IdJabatan = tb_jabatan_pejabat.IdJabatan ORDER BY IdMutasi DESC";
		$query = $db->query($sql);
		$JumRow = $query->rowCount();
		if($JumRow > 0){
			while ($res = $query->fetch(PDO::FETCH_ASSOC)) { 
				$aksi = $_SESSION['Level'] == "author" ? "-" : "<a class='btn btn-xs btn-primary' data-toggle='tooltip' title='Ubah Data' onclick=\"Crud('".$res['IdMutasi']."', 'ubah')\"><i class='fa fa-edit'></i></a> <a class='btn btn-xs btn-danger' data-toggle='tooltip' title='Hapus Data' onclick=\"Crud('".$res['IdMutasi']."', 'hapus')\"><i class='fa fa-trash-o'></i></a>";
				$Status = array("<label class='label label-warning'>Tunggu Aprove</label>","<label class='label label-success'>Disetujui</label>","<label class='label label-danger'>Tidak Disetujui</label>");
				$apr = $res['Status'] == "0" ? "<a class='btn btn-xs btn-success' data-toggle='tooltip' title='Aprove' onclick=\"Aprove('".$res['IdMutasi']."')\"><i class='fa fa-unsorted'></i></a> " : "";
				$aksi = $res['Status'] != "0" ? "-" : $aksi;
				$row['No'] = $no;
				$row['Nama'] = $res['Nama'].", ".$res['Title'];
				$row['Nik'] = $res['Nik'];
				$row['KJ'] = $res['KelasJabatan']."/".$res['NamaJabatan'];
				$row['TmtMutasi'] =tgl_indo($res['TmtMutasi']);
				$row['Keterangan'] = $res['Keterangan'];
				$row['Keterangan'] = $res['Keterangan'];
				$row['Status'] = $Status[$res['Status']];
				$row['Aksi'] = $apr.$aksi;
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
		$IdPejabat = $_POST['IdPejabat'];
		$IdMutasi = $_POST['IdMutasi'];
		$TmtMutasi = $_POST['TmtMutasi'];
		$Keterangan = $_POST['Keterangan'];
		$Nik = $_POST['Nik'];

		switch ($aksi) {
			case 'insert':
				try {
					$sql = "INSERT INTO tb_mutasi_pejabat SET  IdPejabat = :IdPejabat,  TmtMutasi = :TmtMutasi, Keterangan = :Keterangan, TglCreate = :TglCreate, UserUpdate = :UserUpdate";
					$query = $db->prepare($sql);
					$query->bindParam("IdPejabat", $IdPejabat);
					$query->bindParam("TmtMutasi", $TmtMutasi);
					$query->bindParam("Keterangan", $Keterangan);
					$query->bindParam("TglCreate", $date);
					$query->bindParam("UserUpdate", $UserUpdate);
					$query->execute();
					$Pesan = "<b>".$_SESSION['NamaUser']."</b>, Berhasil Menambah Data  Mutasi dengan Nik Pegawai <b>$Nik</b>!";
					AddLog($_SESSION['IdUser'],$Pesan);
					if($query){ echo "sukses"; exit(); } else { echo "gagal"; }
					
					exit();
				} catch (PDOException $e) {
					echo $e->getMessage();
					exit();
				}
				break;
			case 'update':
				try {
					$sql = "UPDATE tb_mutasi_pejabat SET  IdPejabat = :IdPejabat,  TmtMutasi = :TmtMutasi, Keterangan = :Keterangan, TglUpdate = :TglUpdate, UserUpdate = :UserUpdate WHERE IdMutasi = :IdMutasi";
					$query = $db->prepare($sql);
					$query->bindParam("IdPejabat", $IdPejabat);
					$query->bindParam("TmtMutasi", $TmtMutasi);
					$query->bindParam("Keterangan", $Keterangan);
					$query->bindParam("TglUpdate", $date);
					$query->bindParam("UserUpdate", $UserUpdate);
					$query->bindParam("IdMutasi", $IdMutasi);
					$query->execute();
					$Pesan = "<b>".$_SESSION['NamaUser']."</b>, Berhasil Mengubah Data Mutasi !";
					AddLog($_SESSION['IdUser'],$Pesan);
					if($query){ echo "sukses"; exit(); } else { echo "gagal"; }
				} catch (PDOException $e) {
					echo $e->getMessage();
				}
				break;
			case 'delete':
				$sql = "DELETE FROM tb_mutasi_pejabat WHERE IdMutasi = :IdMutasi";
				$query = $db->prepare($sql);
				$query->bindParam("IdMutasi", $IdMutasi);
				$query->execute();
				$Pesan = "<b>".$_SESSION['NamaUser']."</b>, Berhasil Menghapus Data Mutasi!";
					AddLog($_SESSION['IdUser'],$Pesan);
				if($query){ echo "sukses"; exit(); } else { echo "gagal"; }
				break;
		}
		break;

	case 'ShowData':
		$IdMutasi = $_POST['IdMutasi'];
		$sql = "SELECT tb_pejabat.*, tb_jabatan_pejabat.NamaJabatan FROM tb_pejabat INNER JOIN tb_jabatan_pejabat ON tb_pejabat.IdJabatan = tb_jabatan_pejabat.IdJabatan WHERE tb_pejabat.IdMutasi = :IdMutasi";
		$stmt = $db->prepare($sql);
		$stmt->bindParam('IdMutasi', $IdMutasi);
		$stmt->execute();
		$res = $stmt->fetch(PDO::FETCH_ASSOC);
		echo json_encode($res);
		break;

	case 'Aprove':
		$IdMutasi = $_POST['IdMutasi'];
		$Status = $_POST['Status'];	
		$IdPejabat = $db->query("SELECT IdPejabat FROM tb_mutasi_pejabat WHERE IdMutasi = '$IdMutasi'")->fetch(PDO::FETCH_ASSOC);
		$sql = "UPDATE tb_mutasi_pejabat SET `Status` = :Statuss WHERE IdMutasi = :IdMutasi";
		$qr = $db->prepare($sql);
		$qr->bindParam('Statuss', $Status);
		$qr->bindParam('IdMutasi', $IdMutasi);
		$qr->execute();
		if($Status == '1'){
			$db->query("UPDATE tb_pejabat SET `Status` = '0' WHERE IdPejabat = '$IdPejabat[IdPejabat]'");
		}
		$Pesan = "<b>".$_SESSION['NamaUser']."</b>, Berhasil Mengaprove Data Mutasi!";
		AddLog($_SESSION['IdUser'],$Pesan);
		if($qr){ echo "sukses"; exit(); } else { echo "gagal"; }
		break;
}

?>
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
		$sql = "SELECT * FROM tb_jenis_surat_keluar ORDER BY IdJenisSurat DESC";
		$query = $db->query($sql);
		$JumRow = $query->rowCount();
		if($JumRow > 0){
			while ($res = $query->fetch(PDO::FETCH_ASSOC)) { 
				$aksi = $_SESSION['Level'] == "author" ? "-" : "<a class='btn btn-xs btn-primary' data-toggle='toolltip' title='Ubah Data' onclick=\"Crud('".$res['IdJenisSurat']."', 'ubah')\"><i class='fa fa-edit'></i></a> <a class='btn btn-xs btn-danger' data-toggle='toolltip' title='Hapus Data' onclick=\"Crud('".$res['IdJenisSurat']."', 'hapus')\"><i class='fa fa-trash-o'></i></a>";
				$row['No'] = $no;
				$row['Kode'] = $res['Kode'];
				$row['NamaJenis'] = $res['NamaJenis'];
				$row['Keterangan'] = $res['Keterangan'];
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
		$IdJenisSurat = $_POST['IdJenisSurat'];
		$Kode = $_POST['Kode'];
		$NamaJenis = $_POST['NamaJenis'];
		$Keterangan = $_POST['Keterangan'];
		switch ($aksi) {
			case 'insert':
				try {
					$cek = $db->query("SELECT IdJenisSurat FROM tb_jenis_surat_keluar WHERE Kode = '$Kode'")->rowCount();
					if($cek > 0){ echo "found"; exit; }
					$sql = "INSERT INTO tb_jenis_surat_keluar SET  NamaJenis = :NamaJenis,  Kode = :Kode, TglCreate = :TglCreate, Keterangan = :Keterangan, UserUpdate = :UserUpdate";
					$query = $db->prepare($sql);
					$query->bindParam("NamaJenis", $NamaJenis);
					$query->bindParam("Kode", $Kode);
					$query->bindParam("TglCreate", $date);
					$query->bindParam("Keterangan", $Keterangan);
					$query->bindParam("UserUpdate", $UserUpdate);
					$query->execute();
					$Pesan = "<b>".$_SESSION['NamaUser']."</b>, Berhasil Menambah Data Jenis Surat!";
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
					$sql = "UPDATE tb_jenis_surat_keluar SET  NamaJenis = :NamaJenis,  Kode = :Kode, TglUpdate = :TglUpdate,  Keterangan = :Keterangan, UserUpdate = :UserUpdate WHERE IdJenisSurat = :IdJenisSurat";
					$query = $db->prepare($sql);
					$query->bindParam("NamaJenis", $NamaJenis);
					$query->bindParam("Kode", $Kode);
					$query->bindParam("TglUpdate", $date);
					$query->bindParam("Keterangan", $Keterangan);
					$query->bindParam("UserUpdate", $UserUpdate);
					$query->bindParam("IdJenisSurat", $IdJenisSurat);
					$query->execute();
					$Pesan = "<b>".$_SESSION['NamaUser']."</b>, Berhasil Mengubah Data Jenis Surat!";
					AddLog($_SESSION['IdUser'],$Pesan);
					if($query){ echo "sukses"; exit(); } else { echo "gagal"; }
				} catch (PDOException $e) {
					echo $e->getMessage();
				}
				break;
			case 'delete':
				$sql = "DELETE FROM tb_jenis_surat_keluar WHERE IdJenisSurat = :IdJenisSurat";
				$query = $db->prepare($sql);
				$query->bindParam("IdJenisSurat", $IdJenisSurat);
				$query->execute();
				$Pesan = "<b>".$_SESSION['NamaUser']."</b>, Berhasil Menghapus Data Jenis Surat!";
					AddLog($_SESSION['IdUser'],$Pesan);
				if($query){ echo "sukses"; exit(); } else { echo "gagal"; }
				break;
		}
		break;

	case 'ShowData':
		$IdJenisSurat = $_POST['IdJenisSurat'];
		$sql = "SELECT * FROM tb_jenis_surat_keluar WHERE IdJenisSurat = :IdJenisSurat";
		$stmt = $db->prepare($sql);
		$stmt->bindParam('IdJenisSurat', $IdJenisSurat);
		$stmt->execute();
		$res = $stmt->fetch(PDO::FETCH_ASSOC);
		echo json_encode($res);
		break;
	
}

?>
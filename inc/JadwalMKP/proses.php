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
		$sql = "SELECT * FROM tb_jadwal_mkp ORDER BY IdJadwal DESC";
		$query = $db->query($sql);
		$JumRow = $query->rowCount();
		if($JumRow > 0){
			while ($res = $query->fetch(PDO::FETCH_ASSOC)) { 
				$aksi = $_SESSION['Level'] == "author" ? "-" : "<a class='btn btn-xs btn-primary' data-toggle='toolltip' title='Ubah Data' onclick=\"Crud('".$res['IdJadwal']."', 'ubah')\"><i class='fa fa-edit'></i></a>";
				$row['No'] = $no;
				$row['Mulai'] = tgl_indo(date("Y-m-").$res['Mulai']);
				$row['Sampai'] = tgl_indo(date("Y-m-").$res['Sampai']);
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
		$IdJadwal = $_POST['IdJadwal'];
		$Mulai = $_POST['Mulai'];
		$Sampai = $_POST['Sampai'];
		switch ($aksi) {
			case 'insert':
				try {
					$sql = "INSERT INTO tb_jadwal_mkp SET  Sampai = :Sampai,  Mulai = :Mulai";
					$query = $db->prepare($sql);
					$query->bindParam("Sampai", $Sampai);
					$query->bindParam("Mulai", $Mulai);
					$query->execute();
					$Pesan = "<b>".$_SESSION['NamaUser']."</b>, Berhasil Menambah Data Jadwal MKP!";
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
					$sql = "UPDATE tb_jadwal_mkp SET  Sampai = :Sampai,  Mulai = :Mulai WHERE IdJadwal = :IdJadwal";
					$query = $db->prepare($sql);
					$query->bindParam("Sampai", $Sampai);
					$query->bindParam("Mulai", $Mulai);
					$query->bindParam("IdJadwal", $IdJadwal);
					$query->execute();
					$Pesan = "<b>".$_SESSION['NamaUser']."</b>, Berhasil Mengubah Data Jadwal MKP!";
					AddLog($_SESSION['IdUser'],$Pesan);
					if($query){ echo "sukses"; exit(); } else { echo "gagal"; }
				} catch (PDOException $e) {
					echo $e->getMessage();
				}
				break;
			case 'delete':
				$sql = "DELETE FROM tb_jadwal_mkp WHERE IdJadwal = :IdJadwal";
				$query = $db->prepare($sql);
				$query->bindParam("IdJadwal", $IdJadwal);
				$query->execute();
				$Pesan = "<b>".$_SESSION['NamaUser']."</b>, Berhasil Menghapus Data Jadwal MKP!";
					AddLog($_SESSION['IdUser'],$Pesan);
				if($query){ echo "sukses"; exit(); } else { echo "gagal"; }
				break;
		}
		break;

	case 'ShowData':
		$IdJadwal = $_POST['IdJadwal'];
		$sql = "SELECT * FROM tb_jadwal_mkp WHERE IdJadwal = :IdJadwal";
		$stmt = $db->prepare($sql);
		$stmt->bindParam('IdJadwal', $IdJadwal);
		$stmt->execute();
		$res = $stmt->fetch(PDO::FETCH_ASSOC);
		echo json_encode($res);
		break;
	
}

?>
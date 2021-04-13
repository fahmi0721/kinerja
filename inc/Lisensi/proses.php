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
		$sql = "SELECT * FROM tb_lisensi ORDER BY IdLisensi DESC";
		$query = $db->query($sql);
		$JumRow = $query->rowCount();
		if($JumRow > 0){
			while ($res = $query->fetch(PDO::FETCH_ASSOC)) { 
				$aksi = $_SESSION['Level'] == "author" ? "-" : "<a class='btn btn-xs btn-primary' data-toggle='toolltip' title='Ubah Data' onclick=\"Crud('".$res['IdLisensi']."', 'ubah')\"><i class='fa fa-edit'></i></a> <a class='btn btn-xs btn-danger' data-toggle='toolltip' title='Hapus Data' onclick=\"Crud('".$res['IdLisensi']."', 'hapus')\"><i class='fa fa-trash-o'></i></a>";
				$row['No'] = $no;
				$row['KodeLisensi'] = $res['KodeLisensi'];
				$row['NamaLisensi'] = $res['NamaLisensi'];
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
		$IdLisensi = $_POST['IdLisensi'];
		$KodeLisensi = $_POST['KodeLisensi'];
		$NamaLisensi = $_POST['NamaLisensi'];
		$Keterangan = $_POST['Keterangan'];
		switch ($aksi) {
			case 'insert':
				try {
					$sql = "INSERT INTO tb_lisensi SET  NamaLisensi = :NamaLisensi,  KodeLisensi = :KodeLisensi, TglCreate = :TglCreate,  Keterangan = :Keterangan, UserUpdate = :UserUpdate";
					$query = $db->prepare($sql);
					$query->bindParam("NamaLisensi", $NamaLisensi);
					$query->bindParam("KodeLisensi", $KodeLisensi);
					$query->bindParam("TglCreate", $date);
					$query->bindParam("Keterangan", $Keterangan);
					$query->bindParam("UserUpdate", $UserUpdate);
					$query->execute();
					$Pesan = "<b>".$_SESSION['NamaUser']."</b>, Berhasil Menambah Data Lisensi!";
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
					$sql = "UPDATE tb_lisensi SET NamaLisensi = :NamaLisensi,  KodeLisensi = :KodeLisensi, TglUpdate = :TglUpdate,  Keterangan = :Keterangan, UserUpdate = :UserUpdate WHERE IdLisensi = :IdLisensi";
					$query = $db->prepare($sql);
					$query->bindParam("NamaLisensi", $NamaLisensi);
					$query->bindParam("KodeLisensi", $KodeLisensi);
					$query->bindParam("TglUpdate", $date);
					$query->bindParam("Keterangan", $Keterangan);
					$query->bindParam("UserUpdate", $UserUpdate);
					$query->bindParam("IdLisensi", $IdLisensi);
					$query->execute();
					$Pesan = "<b>".$_SESSION['NamaUser']."</b>, Berhasil Mengubah Data Lisensi!";
					AddLog($_SESSION['IdUser'],$Pesan);
					if($query){ echo "sukses"; exit(); } else { echo "gagal"; }
				} catch (PDOException $e) {
					echo $e->getMessage();
				}
				break;
			case 'delete':
				$sql = "DELETE FROM tb_lisensi WHERE IdLisensi = :IdLisensi";
				$query = $db->prepare($sql);
				$query->bindParam("IdLisensi", $IdLisensi);
				$query->execute();
				$Pesan = "<b>".$_SESSION['NamaUser']."</b>, Berhasil Menghapus Data Lisensi!";
					AddLog($_SESSION['IdUser'],$Pesan);
				if($query){ echo "sukses"; exit(); } else { echo "gagal"; }
				break;
		}
		break;

	case 'ShowData':
		$IdLisensi = $_POST['IdLisensi'];
		$sql = "SELECT * FROM tb_lisensi WHERE IdLisensi = :IdLisensi";
		$stmt = $db->prepare($sql);
		$stmt->bindParam('IdLisensi', $IdLisensi);
		$stmt->execute();
		$res = $stmt->fetch(PDO::FETCH_ASSOC);
		echo json_encode($res);
		break;
	
	case "GetKode":
		$sql = "SELECT MAX(RIGHT(KodeLisensi,5)) as Kode FROM tb_lisensi";
		$query = $db->query($sql);
		$data = $query->fetch(PDO::FETCH_ASSOC);
		$kode = empty($data['Kode']) ? "L-00001" : "L-".sprintf('%05d',$data['Kode'] + 1);
		echo $kode;
		break;
}

?>
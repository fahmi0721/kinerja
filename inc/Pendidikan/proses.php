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
		$sql = "SELECT * FROM tb_pendidikan ORDER BY IdPendidikan DESC";
		$query = $db->query($sql);
		$JumRow = $query->rowCount();
		if($JumRow > 0){
			while ($res = $query->fetch(PDO::FETCH_ASSOC)) { 
				$aksi = $_SESSION['Level'] == "author" ? "-" : "<a class='btn btn-xs btn-primary' data-toggle='toolltip' title='Ubah Data' onclick=\"Crud('".$res['IdPendidikan']."', 'ubah')\"><i class='fa fa-edit'></i></a> <a class='btn btn-xs btn-danger' data-toggle='toolltip' title='Hapus Data' onclick=\"Crud('".$res['IdPendidikan']."', 'hapus')\"><i class='fa fa-trash-o'></i></a>";
				$row['No'] = $no;
				$row['KodePendidikan'] = $res['KodePendidikan'];
				$row['NamaPendidikan'] = $res['NamaPendidikan'];
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
		$IdPendidikan = $_POST['IdPendidikan'];
		$KodePendidikan = $_POST['KodePendidikan'];
		$NamaPendidikan = $_POST['NamaPendidikan'];
		$Keterangan = $_POST['Keterangan'];
		switch ($aksi) {
			case 'insert':
				try {
					$sql = "INSERT INTO tb_pendidikan SET  NamaPendidikan = :NamaPendidikan,  KodePendidikan = :KodePendidikan, TglCreate = :TglCreate,  Keterangan = :Keterangan, UserUpdate = :UserUpdate";
					$query = $db->prepare($sql);
					$query->bindParam("NamaPendidikan", $NamaPendidikan);
					$query->bindParam("KodePendidikan", $KodePendidikan);
					$query->bindParam("TglCreate", $date);
					$query->bindParam("Keterangan", $Keterangan);
					$query->bindParam("UserUpdate", $UserUpdate);
					$query->execute();
					$Pesan = "<b>".$_SESSION['NamaUser']."</b>, Berhasil Menambah Data Pendidikan!";
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
					$sql = "UPDATE tb_pendidikan SET NamaPendidikan = :NamaPendidikan,  KodePendidikan = :KodePendidikan, TglCreate = :TglCreate,  Keterangan = :Keterangan, UserUpdate = :UserUpdate WHERE IdPendidikan = :IdPendidikan";
					$query = $db->prepare($sql);
					$query->bindParam("NamaPendidikan", $NamaPendidikan);
					$query->bindParam("KodePendidikan", $KodePendidikan);
					$query->bindParam("TglCreate", $date);
					$query->bindParam("Keterangan", $Keterangan);
					$query->bindParam("UserUpdate", $UserUpdate);
					$query->bindParam("IdPendidikan", $IdPendidikan);
					$query->execute();
					$Pesan = "<b>".$_SESSION['NamaUser']."</b>, Berhasil Mengubah Data Pendidikan!";
					AddLog($_SESSION['IdUser'],$Pesan);
					if($query){ echo "sukses"; exit(); } else { echo "gagal"; }
				} catch (PDOException $e) {
					echo $e->getMessage();
				}
				break;
			case 'delete':
				$sql = "DELETE FROM tb_pendidikan WHERE IdPendidikan = :IdPendidikan";
				$query = $db->prepare($sql);
				$query->bindParam("IdPendidikan", $IdPendidikan);
				$query->execute();
				$Pesan = "<b>".$_SESSION['NamaUser']."</b>, Berhasil Menghapus Data Pendidikan!";
					AddLog($_SESSION['IdUser'],$Pesan);
				if($query){ echo "sukses"; exit(); } else { echo "gagal"; }
				break;
		}
		break;

	case 'ShowData':
		$IdPendidikan = $_POST['IdPendidikan'];
		$sql = "SELECT * FROM tb_pendidikan WHERE IdPendidikan = :IdPendidikan";
		$stmt = $db->prepare($sql);
		$stmt->bindParam('IdPendidikan', $IdPendidikan);
		$stmt->execute();
		$res = $stmt->fetch(PDO::FETCH_ASSOC);
		echo json_encode($res);
		break;
	
	case "GetKode":
		$sql = "SELECT MAX(RIGHT(KodePendidikan,5)) as Kode FROM tb_pendidikan";
		$query = $db->query($sql);
		$data = $query->fetch(PDO::FETCH_ASSOC);
		$kode = empty($data['Kode']) ? "P-00001" : "P-".sprintf('%05d',$data['Kode'] + 1);
		echo $kode;
		break;
}

?>
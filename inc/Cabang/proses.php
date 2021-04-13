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
		$sql = "SELECT * FROM tb_cabang ORDER BY IdCabang DESC";
		$query = $db->query($sql);
		$JumRow = $query->rowCount();
		if($JumRow > 0){
			while ($res = $query->fetch(PDO::FETCH_ASSOC)) { 
				$aksi = $_SESSION['Level'] == "author" ? "-" : "<a class='btn btn-xs btn-primary' data-toggle='toolltip' title='Ubah Data' onclick=\"Crud('".$res['IdCabang']."', 'ubah')\"><i class='fa fa-edit'></i></a> <a class='btn btn-xs btn-danger' data-toggle='toolltip' title='Hapus Data' onclick=\"Crud('".$res['IdCabang']."', 'hapus')\"><i class='fa fa-trash-o'></i></a>";
				$row['No'] = $no;
				$row['KodeCabang'] = $res['KodeCabang'];
				$row['NamaCabang'] = $res['NamaCabang'];
				$row['Alamat'] = $res['Alamat'];
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
		$IdCabang = $_POST['IdCabang'];
		$KodeCabang = $_POST['KodeCabang'];
		$NamaCabang = $_POST['NamaCabang'];
		$Alamat = $_POST['Alamat'];
		$Keterangan = $_POST['Keterangan'];
		switch ($aksi) {
			case 'insert':
				try {
					$sql = "INSERT INTO tb_cabang SET  NamaCabang = :NamaCabang,  KodeCabang = :KodeCabang, TglCreate = :TglCreate, Alamat = :Alamat, Keterangan = :Keterangan, UserUpdate = :UserUpdate";
					$query = $db->prepare($sql);
					$query->bindParam("NamaCabang", $NamaCabang);
					$query->bindParam("KodeCabang", $KodeCabang);
					$query->bindParam("TglCreate", $date);
					$query->bindParam("Alamat", $Alamat);
					$query->bindParam("Keterangan", $Keterangan);
					$query->bindParam("UserUpdate", $UserUpdate);
					$query->execute();
					$Pesan = "<b>".$_SESSION['NamaUser']."</b>, Berhasil Menambah Data Cabang!";
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
					$sql = "UPDATE tb_cabang SET  NamaCabang = :NamaCabang,  KodeCabang = :KodeCabang, TglUpdate = :TglUpdate, Alamat = :Alamat, Keterangan = :Keterangan, UserUpdate = :UserUpdate WHERE IdCabang = :IdCabang";
					$query = $db->prepare($sql);
					$query->bindParam("NamaCabang", $NamaCabang);
					$query->bindParam("KodeCabang", $KodeCabang);
					$query->bindParam("TglUpdate", $date);
					$query->bindParam("Alamat", $Alamat);
					$query->bindParam("Keterangan", $Keterangan);
					$query->bindParam("UserUpdate", $UserUpdate);
					$query->bindParam("IdCabang", $IdCabang);
					$query->execute();
					$Pesan = "<b>".$_SESSION['NamaUser']."</b>, Berhasil Mengubah Data Cabang!";
					AddLog($_SESSION['IdUser'],$Pesan);
					if($query){ echo "sukses"; exit(); } else { echo "gagal"; }
				} catch (PDOException $e) {
					echo $e->getMessage();
				}
				break;
			case 'delete':
				$sql = "DELETE FROM tb_cabang WHERE IdCabang = :IdCabang";
				$query = $db->prepare($sql);
				$query->bindParam("IdCabang", $IdCabang);
				$query->execute();
				$Pesan = "<b>".$_SESSION['NamaUser']."</b>, Berhasil Menghapus Data Cabang!";
					AddLog($_SESSION['IdUser'],$Pesan);
				if($query){ echo "sukses"; exit(); } else { echo "gagal"; }
				break;
		}
		break;

	case 'ShowData':
		$IdCabang = $_POST['IdCabang'];
		$sql = "SELECT * FROM tb_cabang WHERE IdCabang = :IdCabang";
		$stmt = $db->prepare($sql);
		$stmt->bindParam('IdCabang', $IdCabang);
		$stmt->execute();
		$res = $stmt->fetch(PDO::FETCH_ASSOC);
		echo json_encode($res);
		break;
	
	case "GetKode":
		$sql = "SELECT MAX(RIGHT(KodeCabang,5)) as Kode FROM tb_cabang";
		$query = $db->query($sql);
		$data = $query->fetch(PDO::FETCH_ASSOC);
		$kode = empty($data['Kode']) ? "ISEMA-00001" : "ISEMA-".sprintf('%05d',intval($data['Kode']) + 1);
		echo $kode;
		break;
}

?>
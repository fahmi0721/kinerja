<?php
session_start();
include_once '../../config/config.php';

$UserUpdate = $_SESSION['id'];
$date = date("Y-m-d H:i:s");
$proses = isset($_GET['proses']) ? $_GET['proses'] : "";
switch ($proses) {
	case 'DetailData': 
			$data = array();
			$sql = "SELECT * FROM tb_lowongan ORDER BY IdLowongan DESC";
			$query = $db->query($sql);
			$row = array();
			$no=1;
			while($dt = $query->fetch(PDO::FETCH_ASSOC)){
				$Aksi = $_SESSION['Level'] == "author" ?  "-" :"<center><a data-toggle='tooltip' onclick=\"Crud('".$dt['IdLowongan']."','Ubah')\" title='Ubah Data' class='btn btn-xs btn-info'><i class='fa fa-edit'></i></a> <a data-toggle='tooltip' title='Hapus Data' class='btn btn-xs btn-danger'  onclick=\"Crud('".$dt['IdLowongan']."','delete')\"><i class='fa fa-trash-o'></i></a></center>";
				$data['No']   = $no;
				$data['NamaLowongan'] = $dt['NamaLowongan'];
				$data['Penempatan'] = $dt['Penempatan'];
				$data['Periode'] = tgl_indo($dt['TglBuka'])." S/D ". tgl_indo($dt['TglTutup']);
				$data['Keterangan'] = $dt['Keterangan'];
				$data['Aksi'] = $Aksi;
				$row['data'][] = $data;
				$no++;
			}
			echo json_encode($row);
		break;
	case 'Crud':
		$aksi = $_POST['aksi'];
		$IdLowongan = $_POST['IdLowongan'];
		$NamaLowongan = $_POST['NamaLowongan'];
		$Penempatan = $_POST['Penempatan'];
		$TglBuka = $_POST['TglBuka'];
		$TglTutup = $_POST['TglTutup'];
		$Keterangan = $_POST['Keterangan'];
		switch ($aksi) {
			case 'insert':
				try {
					$sql = "INSERT INTO tb_lowongan SET NamaLowongan = :NamaLowongan, Penempatan = :Penempatan, TglBuka = :TglBuka, TglTutup = :TglTutup, Keterangan = :Keterangan, TglCreate = :TglCreate, UserUpdate = :UserUpdate";
					$query = $db->prepare($sql);
					$query->bindParam("NamaLowongan", $NamaLowongan);
					$query->bindParam("Penempatan", $Penempatan);
					$query->bindParam("TglBuka", $TglBuka);
					$query->bindParam("TglTutup", $TglTutup);
					$query->bindParam("Keterangan", $Keterangan);
					$query->bindParam("TglCreate", $date);
					$query->bindParam("UserUpdate", $UserUpdate);
					$query->execute();
					echo "sukses";
					exit();
				} catch (PDOException $e) {
					echo $e->getMessage();
					exit();
				}

				break;
			case 'update':
				try {
					$sql = "UPDATE tb_lowongan SET NamaLowongan = :NamaLowongan, Penempatan = :Penempatan, TglBuka = :TglBuka, TglTutup = :TglTutup, Keterangan = :Keterangan, TglUpdate = :TglUpdate, UserUpdate = :UserUpdate WHERE IdLowongan = :IdLowongan";
					$query = $db->prepare($sql);
					$query->bindParam("NamaLowongan", $NamaLowongan);
					$query->bindParam("Penempatan", $Penempatan);
					$query->bindParam("TglBuka", $TglBuka);
					$query->bindParam("TglTutup", $TglTutup);
					$query->bindParam("Keterangan", $Keterangan);
					$query->bindParam("TglUpdate", $date);
					$query->bindParam("UserUpdate", $UserUpdate);
					$query->bindParam("IdLowongan", $IdLowongan);
					$query->execute();
					echo "sukses";
				} catch (PDOException $e) {
					echo $e->getMessage();
				}
				break;
			case 'delete':
				$sql = "DELETE FROM tb_lowongan WHERE IdLowongan = :IdLowongan";
				$query = $db->prepare($sql);
				$query->bindParam("IdLowongan", $IdLowongan);
				$query->execute();
				if($query){ echo "sukses"; exit(); } else { echo "gagal"; }
				break;
			
		}
		break;

	case 'ShowData':
		$IdLowongan = $_POST['IdLowongan'];
		$sql = "SELECT * FROM tb_lowongan WHERE IdLowongan = :IdLowongan";
		$stmt = $db->prepare($sql);
		$stmt->bindParam('IdLowongan', $IdLowongan);
		$stmt->execute();
		$res = $stmt->fetch(PDO::FETCH_ASSOC);
		echo json_encode($res);
		break;
	
	
}

?>
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
		$sql = "SELECT * FROM tb_divisi ORDER BY IdDivisi DESC";
		$query = $db->query($sql);
		$JumRow = $query->rowCount();
		if($JumRow > 0){
			while ($res = $query->fetch(PDO::FETCH_ASSOC)) { 
				$aksi = $_SESSION['Level'] == "author" ? "-" : "<a class='btn btn-xs btn-primary' data-toggle='toolltip' title='Ubah Data' onclick=\"Crud('".$res['IdDivisi']."', 'ubah')\"><i class='fa fa-edit'></i></a> <a class='btn btn-xs btn-danger' data-toggle='toolltip' title='Hapus Data' onclick=\"Crud('".$res['IdDivisi']."', 'hapus')\"><i class='fa fa-trash-o'></i></a>";
				$row['No'] = $no;
				$row['KodeDivisi'] = $res['KodeDivisi'];
				$row['NamaDivisi'] = $res['NamaDivisi'];
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
		$IdDivisi = $_POST['IdDivisi'];
		$KodeDivisi = $_POST['KodeDivisi'];
		$NamaDivisi = $_POST['NamaDivisi'];
		$Keterangan = $_POST['Keterangan'];
		switch ($aksi) {
			case 'insert':
				try {
					$sql = "INSERT INTO tb_divisi SET  NamaDivisi = :NamaDivisi,  KodeDivisi = :KodeDivisi, TglCreate = :TglCreate,  Keterangan = :Keterangan, UserUpdate = :UserUpdate";
					$query = $db->prepare($sql);
					$query->bindParam("NamaDivisi", $NamaDivisi);
					$query->bindParam("KodeDivisi", $KodeDivisi);
					$query->bindParam("TglCreate", $date);
					$query->bindParam("Keterangan", $Keterangan);
					$query->bindParam("UserUpdate", $UserUpdate);
					$query->execute();
					$Pesan = "<b>".$_SESSION['NamaUser']."</b>, Berhasil Menambah Data Divisi!";
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
					$sql = "UPDATE tb_divisi SET NamaDivisi = :NamaDivisi,  KodeDivisi = :KodeDivisi, TglCreate = :TglCreate,  Keterangan = :Keterangan, UserUpdate = :UserUpdate WHERE IdDivisi = :IdDivisi";
					$query = $db->prepare($sql);
					$query->bindParam("NamaDivisi", $NamaDivisi);
					$query->bindParam("KodeDivisi", $KodeDivisi);
					$query->bindParam("TglCreate", $date);
					$query->bindParam("Keterangan", $Keterangan);
					$query->bindParam("UserUpdate", $UserUpdate);
					$query->bindParam("IdDivisi", $IdDivisi);
					$query->execute();
					$Pesan = "<b>".$_SESSION['NamaUser']."</b>, Berhasil Mengubah Data Divisi!";
					AddLog($_SESSION['IdUser'],$Pesan);
					if($query){ echo "sukses"; exit(); } else { echo "gagal"; }
				} catch (PDOException $e) {
					echo $e->getMessage();
				}
				break;
			case 'delete':
				$sql = "DELETE FROM tb_divisi WHERE IdDivisi = :IdDivisi";
				$query = $db->prepare($sql);
				$query->bindParam("IdDivisi", $IdDivisi);
				$query->execute();
				$Pesan = "<b>".$_SESSION['NamaUser']."</b>, Berhasil Menghapus Data Divisi!";
					AddLog($_SESSION['IdUser'],$Pesan);
				if($query){ echo "sukses"; exit(); } else { echo "gagal"; }
				break;
		}
		break;

	case 'ShowData':
		$IdDivisi = $_POST['IdDivisi'];
		$sql = "SELECT * FROM tb_divisi WHERE IdDivisi = :IdDivisi";
		$stmt = $db->prepare($sql);
		$stmt->bindParam('IdDivisi', $IdDivisi);
		$stmt->execute();
		$res = $stmt->fetch(PDO::FETCH_ASSOC);
		echo json_encode($res);
		break;
	
	case "GetKode":
		$sql = "SELECT MAX(RIGHT(KodeDivisi,5)) as Kode FROM tb_divisi";
		$query = $db->query($sql);
		$data = $query->fetch(PDO::FETCH_ASSOC);
		$kode = empty($data['Kode']) ? "D-00001" : "D-".sprintf('%05d',$data['Kode'] + 1);
		echo $kode;
		break;
}

?>
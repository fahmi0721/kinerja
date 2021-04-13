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
		$sql = "SELECT * FROM tb_jabatan_pejabat ORDER BY IdJabatan ASC";
		$query = $db->query($sql);
		$JumRow = $query->rowCount();
		if($JumRow > 0){
			while ($res = $query->fetch(PDO::FETCH_ASSOC)) { 
				$aksi = $_SESSION['Level'] == "author" ? "-" : "<a class='btn btn-xs btn-primary' data-toggle='toolltip' title='Ubah Data' onclick=\"Crud('".$res['IdJabatan']."', 'ubah')\"><i class='fa fa-edit'></i></a> <a class='btn btn-xs btn-danger' data-toggle='toolltip' title='Hapus Data' onclick=\"Crud('".$res['IdJabatan']."', 'hapus')\"><i class='fa fa-trash-o'></i></a>";
				$row['No'] = $no;
				$row['NamaJabatan'] = $res['NamaJabatan'];
				$row['KodeDisposisi'] = $res['KodeDisposisi'];
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
		$IdJabatan = $_POST['IdJabatan'];
		$NamaJabatan = $_POST['NamaJabatan'];
		$Keterangan = $_POST['Keterangan'];
		$KodeDisposisi = $_POST['KodeDisposisi'];
		switch ($aksi) {
			case 'insert':
				try {
					$sql = "INSERT INTO tb_jabatan_pejabat SET  NamaJabatan = :NamaJabatan,   Keterangan = :Keterangan, KodeDisposisi = :KodeDisposisi, TglCreate = :TglCreate, UserUpdate = :UserUpdate";
					$query = $db->prepare($sql);
					$query->bindParam("NamaJabatan", $NamaJabatan);
					$query->bindParam("Keterangan", $Keterangan);
					$query->bindParam("KodeDisposisi", $KodeDisposisi);
					$query->bindParam("TglCreate", $date);
					$query->bindParam("UserUpdate", $UserUpdate);
					$query->execute();
					$Pesan = "<b>".$_SESSION['NamaUser']."</b>, Berhasil Menambah Data Jabatan Pejabat!";
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
					$sql = "UPDATE tb_jabatan_pejabat SET  NamaJabatan = :NamaJabatan,   Keterangan = :Keterangan, KodeDisposisi = :KodeDisposisi, UserUpdate = :UserUpdate, TglUpdate = :TglUpdate WHERE IdJabatan = :IdJabatan";
					$query = $db->prepare($sql);
					$query->bindParam("NamaJabatan", $NamaJabatan);
					$query->bindParam("Keterangan", $Keterangan);
					$query->bindParam("KodeDisposisi", $KodeDisposisi);
					$query->bindParam("UserUpdate", $UserUpdate);
					$query->bindParam("TglUpdate", $date);
					$query->bindParam("IdJabatan", $IdJabatan);
					$query->execute();
					$Pesan = "<b>".$_SESSION['NamaUser']."</b>, Berhasil Mengubah Data Jabatan Pejabat!";
					AddLog($_SESSION['IdUser'],$Pesan);
					if($query){ echo "sukses"; exit(); } else { echo "gagal"; }
				} catch (PDOException $e) {
					echo $e->getMessage();
				}
				break;
			case 'delete':
				$sql = "DELETE FROM tb_jabatan_pejabat WHERE IdJabatan = :IdJabatan";
				$query = $db->prepare($sql);
				$query->bindParam("IdJabatan", $IdJabatan);
				$query->execute();
				$Pesan = "<b>".$_SESSION['NamaUser']."</b>, Berhasil Menghapus Data Jabatan Pejabat!";
					AddLog($_SESSION['IdUser'],$Pesan);
				if($query){ echo "sukses"; exit(); } else { echo "gagal"; }
				break;
		}
		break;

	case 'ShowData':
		$IdJabatan = $_POST['IdJabatan'];
		$sql = "SELECT * FROM tb_jabatan_pejabat WHERE IdJabatan = :IdJabatan";
		$stmt = $db->prepare($sql);
		$stmt->bindParam('IdJabatan', $IdJabatan);
		$stmt->execute();
		$res = $stmt->fetch(PDO::FETCH_ASSOC);
		echo json_encode($res);
		break;
	
	case "GetKode":
		$sql = "SELECT MAX(RIGHT(KodeJabatan,5)) as Kode FROM tb_jabatan_pejabat";
		$query = $db->query($sql);
		$data = $query->fetch(PDO::FETCH_ASSOC);
		$kode = empty($data['Kode']) ? "J-00001" : "J-".sprintf('%05d',$data['Kode'] + 1);
		echo $kode;
		break;
}

?>
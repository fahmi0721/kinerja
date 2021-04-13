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
		$sql = "SELECT * FROM tb_kelas_jabatan ORDER BY IdKelasJabatan DESC";
		$query = $db->query($sql);
		$JumRow = $query->rowCount();
		if($JumRow > 0){
			while ($res = $query->fetch(PDO::FETCH_ASSOC)) { 
				$aksi = $_SESSION['Level'] == "author" ? "-" : "<a class='btn btn-xs btn-primary' data-toggle='toolltip' title='Ubah Data' onclick=\"Crud('".$res['IdKelasJabatan']."', 'ubah')\"><i class='fa fa-edit'></i></a> <a class='btn btn-xs btn-danger' data-toggle='toolltip' title='Hapus Data' onclick=\"Crud('".$res['IdKelasJabatan']."', 'hapus')\"><i class='fa fa-trash-o'></i></a>";
				$row['No'] = $no;
				$row['KelasJabatan'] = $res['KelasJabatan'];
				$row['TKO'] = rupiah($res['TKO'],"Rp.");
				$row['TKP'] = rupiah($res['TKP'],"Rp.");
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
		$IdKelasJabatan = $_POST['IdKelasJabatan'];
		$KelasJabatan = $_POST['KelasJabatan'];
		$TKO = preg_replace('/[^0-9]/','',$_POST['TKO']);
		$TKP = preg_replace('/[^0-9]/','',$_POST['TKP']);
		$Keterangan = $_POST['Keterangan'];
		switch ($aksi) {
			case 'insert':
				try {
					$sql = "INSERT INTO tb_kelas_jabatan SET  TKO = :TKO, TKP = :TKP,  KelasJabatan = :KelasJabatan, TglCreate = :TglCreate,  Keterangan = :Keterangan, UserUpdate = :UserUpdate";
					$query = $db->prepare($sql);
					$query->bindParam("TKO", $TKO);
					$query->bindParam("TKP", $TKP);
					$query->bindParam("KelasJabatan", $KelasJabatan);
					$query->bindParam("TglCreate", $date);
					$query->bindParam("Keterangan", $Keterangan);
					$query->bindParam("UserUpdate", $UserUpdate);
					$query->execute();
					$Pesan = "<b>".$_SESSION['NamaUser']."</b>, Berhasil Menambah Data Kelas Jabatan!";
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
					$sql = "UPDATE tb_kelas_jabatan SET TKO = :TKO, TKP = :TKP, KelasJabatan = :KelasJabatan, TglUpdate = :TglUpdate,  Keterangan = :Keterangan, UserUpdate = :UserUpdate WHERE IdKelasJabatan = :IdKelasJabatan";
					$query = $db->prepare($sql);
					$query->bindParam("TKO", $TKO);
					$query->bindParam("TKP", $TKP);
					$query->bindParam("KelasJabatan", $KelasJabatan);
					$query->bindParam("TglUpdate", $date);
					$query->bindParam("Keterangan", $Keterangan);
					$query->bindParam("UserUpdate", $UserUpdate);
					$query->bindParam("IdKelasJabatan", $IdKelasJabatan);
					$query->execute();
					$Pesan = "<b>".$_SESSION['NamaUser']."</b>, Berhasil Mengubah Data Kelas Jabatan!";
					AddLog($_SESSION['IdUser'],$Pesan);
					if($query){ echo "sukses"; exit(); } else { echo "gagal"; }
				} catch (PDOException $e) {
					echo $e->getMessage();
				}
				break;
			case 'delete':
				$sql = "DELETE FROM tb_kelas_jabatan WHERE IdKelasJabatan = :IdKelasJabatan";
				$query = $db->prepare($sql);
				$query->bindParam("IdKelasJabatan", $IdKelasJabatan);
				$query->execute();
				$Pesan = "<b>".$_SESSION['NamaUser']."</b>, Berhasil Menghapus Data Kelas Jabatan!";
					AddLog($_SESSION['IdUser'],$Pesan);
				if($query){ echo "sukses"; exit(); } else { echo "gagal"; }
				break;
		}
		break;

	case 'ShowData':
		$IdKelasJabatan = $_POST['IdKelasJabatan'];
		$sql = "SELECT * FROM tb_kelas_jabatan WHERE IdKelasJabatan = :IdKelasJabatan";
		$stmt = $db->prepare($sql);
		$stmt->bindParam('IdKelasJabatan', $IdKelasJabatan);
		$stmt->execute();
		$res = $stmt->fetch(PDO::FETCH_ASSOC);
		echo json_encode($res);
		break;
	
	
}

?>
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
		$sql = "SELECT * FROM tb_indikator_kp ORDER BY IdIkp DESC";
		$query = $db->query($sql);
		$JumRow = $query->rowCount();
		$jbt ="";
		if($JumRow > 0){
			while ($res = $query->fetch(PDO::FETCH_ASSOC)) { 
				$aksi = $_SESSION['Level'] == "author" ? "-" : "<a class='btn btn-xs btn-primary' data-toggle='toolltip' title='Ubah Data' onclick=\"Crud('".$res['IdIkp']."', 'ubah')\"><i class='fa fa-edit'></i></a> <a class='btn btn-xs btn-danger' data-toggle='toolltip' title='Hapus Data' onclick=\"Crud('".$res['IdIkp']."', 'hapus')\"><i class='fa fa-trash-o'></i></a>";
				$qrJb = $db->query("SELECT IdJabatan, NamaJabatan FROM tb_jabatan_pejabat WHERE IdJabatan IN ($res[IdJabatan])");
				while($r = $qrJb->fetch(PDO::FETCH_ASSOC)){
					$jbt .= "-  ".$r['NamaJabatan']."<br>";
				}
				$row['No'] = $no;
				$row['Kompetensi'] = $res['Kompetensi'];
				$row['Bobot'] = $res['Bobot'];
				$row['Target'] = $res['Target'];
				$row['Keterangan'] = $res['Keterangan'];
				$row['Jabatan'] = $jbt;
				$row['Aksi'] = $aksi;
				$data['data'][] = $row;
				$no++;
				$jbt ="";
			}
		}else{
			$data['data']='';
		}

		echo json_encode($data);
		break;
	case 'Crud':
		$aksi = $_POST['aksi'];
		$IdIkp = $_POST['IdIkp'];
		$Kompetensi = $_POST['Kompetensi'];
		$Bobot = $_POST['Bobot'];
		$Target = $_POST['Target'];
		$IdJabatan = $_POST['IdJabatan'];
		$Keterangan = $_POST['Keterangan'];
		switch ($aksi) {
			case 'insert':
				try {
					$sql = "INSERT INTO tb_indikator_kp SET  Bobot = :Bobot,  Kompetensi = :Kompetensi, TglCreate = :TglCreate, `Target` = :Targets, Keterangan = :Keterangan, UserUpdate = :UserUpdate, IdJabatan = :IdJabatan";
					$query = $db->prepare($sql);
					$query->bindParam("Bobot", $Bobot);
					$query->bindParam("Kompetensi", $Kompetensi);
					$query->bindParam("TglCreate", $date);
					$query->bindParam("Targets", $Target);
					$query->bindParam("Keterangan", $Keterangan);
					$query->bindParam("UserUpdate", $UserUpdate);
					$query->bindParam("IdJabatan", $IdJabatan);
					$query->execute();
					$Pesan = "<b>".$_SESSION['NamaUser']."</b>, Berhasil Menambah Data Indikator Kompetensi!";
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
					$sql = "UPDATE tb_indikator_kp SET  Bobot = :Bobot,  Kompetensi = :Kompetensi, TglUpdate = :TglUpdate, `Target` = :Targets, Keterangan = :Keterangan, UserUpdate = :UserUpdate, IdJabatan = :IdJabatan  WHERE IdIkp = :IdIkp";
					$query = $db->prepare($sql);
					$query->bindParam("Bobot", $Bobot);
					$query->bindParam("Kompetensi", $Kompetensi);
					$query->bindParam("TglUpdate", $date);
					$query->bindParam("Targets", $Target);
					$query->bindParam("Keterangan", $Keterangan);
					$query->bindParam("UserUpdate", $UserUpdate);
					$query->bindParam("IdJabatan", $IdJabatan);
					$query->bindParam("IdIkp", $IdIkp);
					$query->execute();
					$Pesan = "<b>".$_SESSION['NamaUser']."</b>, Berhasil Mengubah Data Indikator Kompetensi!";
					AddLog($_SESSION['IdUser'],$Pesan);
					if($query){ echo "sukses"; exit(); } else { echo "gagal"; }
				} catch (PDOException $e) {
					echo $e->getMessage();
				}
				break;
			case 'delete':
				$sql = "DELETE FROM tb_indikator_kp WHERE IdIkp = :IdIkp";
				$query = $db->prepare($sql);
				$query->bindParam("IdIkp", $IdIkp);
				$query->execute();
				$Pesan = "<b>".$_SESSION['NamaUser']."</b>, Berhasil Menghapus Data Indikator Kompetensi!";
				AddLog($_SESSION['IdUser'],$Pesan);
				if($query){ echo "sukses"; exit(); } else { echo "gagal"; }
				break;
		}
		break;

	case 'ShowData':
		$IdIkp = $_POST['IdIkp'];
		$sql = "SELECT * FROM tb_indikator_kp WHERE IdIkp = :IdIkp";
		$stmt = $db->prepare($sql);
		$stmt->bindParam('IdIkp', $IdIkp);
		$stmt->execute();
		$res = $stmt->fetch(PDO::FETCH_ASSOC);
		$dt=array();
		$resd = array();
		$qrJb = $db->query("SELECT IdJabatan, NamaJabatan FROM tb_jabatan_pejabat WHERE IdJabatan IN ($res[IdJabatan])");
		while($r = $qrJb->fetch(PDO::FETCH_ASSOC)){
			$dt['value'] = $r['IdJabatan'];
			$dt['NamaJabatan'] = $r['NamaJabatan'];
			$resd[] = $dt;
		}
		$res['item'] = $resd;
		echo json_encode($res);
		break;
	
}

?>
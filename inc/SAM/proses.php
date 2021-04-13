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
		$rJabatan = array();
		$no=1;
		$qrJabatan = $db->query("SELECT IdJabatan, NamaJabatan FROM tb_jabatan_pejabat ORDER BY IdJabatan ASC");
		while($r = $qrJabatan->fetch(PDO::FETCH_ASSOC)){
			$rJabatan[$r['IdJabatan']] = $r['NamaJabatan'];
		}
		
		$sql = "SELECT * FROM tb_aprove_mkp ORDER BY IdAproveMkp DESC";
		$query = $db->query($sql);
		$JumRow = $query->rowCount();
		if($JumRow > 0){
			while ($res = $query->fetch(PDO::FETCH_ASSOC)) { 
				$RKBShow = "";
				$aksi = $_SESSION['Level'] == "author" ? "-" : "<a class='btn btn-xs btn-primary' data-toggle='toolltip' title='Ubah Data' onclick=\"Crud('".$res['IdAproveMkp']."', 'ubah')\"><i class='fa fa-edit'></i></a> <a class='btn btn-xs btn-danger' data-toggle='toolltip' title='Hapus Data' onclick=\"Crud('".$res['IdAproveMkp']."', 'hapus')\"><i class='fa fa-trash-o'></i></a>";
				$RKB = explode(",",$res['IdJabatanRKB']);
				for($i=0; $i<count($RKB); $i++){
					$RKBShow .= "- ".$rJabatan[$RKB[$i]]."<br>";
				}

				$row['No'] = $no;
				$row['JabatanAprove'] = $rJabatan[$res['IdJabatanAprove']];
				$row['JabatanPembuatRKB'] = $RKBShow;
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
		$IdJabatanAprove = $_POST['JabatanAprove'];
		$IdAproveMkp = $_POST['IdAproveMkp'];
		$IdJabatanRKB = $_POST['JabatanPembuatRKB'];
		$Keterangan = $_POST['Keterangan'];
		switch ($aksi) {
			case 'insert':
				try {
					$sql = "INSERT INTO tb_aprove_mkp SET  IdJabatanAprove = :IdJabatanAprove,  IdJabatanRKB = :IdJabatanRKB, TglCreate = :TglCreate,  Keterangan = :Keterangan, UserUpdate = :UserUpdate";
					$query = $db->prepare($sql);
					$query->bindParam("IdJabatanAprove", $IdJabatanAprove);
					$query->bindParam("IdJabatanRKB", $IdJabatanRKB);
					$query->bindParam("TglCreate", $date);
					$query->bindParam("Keterangan", $Keterangan);
					$query->bindParam("UserUpdate", $UserUpdate);
					$query->execute();
					$Pesan = "<b>".$_SESSION['NamaUser']."</b>, Berhasil Menambah Data Aprove MKP!";
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
					$sql = "UPDATE tb_aprove_mkp SET IdJabatanAprove = :IdJabatanAprove,  IdJabatanRKB = :IdJabatanRKB, TglUpdate = :TglUpdate,  Keterangan = :Keterangan, UserUpdate = :UserUpdate WHERE IdAproveMkp = :IdAproveMkp";
					$query = $db->prepare($sql);
					$query->bindParam("IdJabatanAprove", $IdJabatanAprove);
					$query->bindParam("IdJabatanRKB", $IdJabatanRKB);
					$query->bindParam("TglUpdate", $date);
					$query->bindParam("Keterangan", $Keterangan);
					$query->bindParam("UserUpdate", $UserUpdate);
					$query->bindParam("IdAproveMkp", $IdAproveMkp);
					$query->execute();
					$Pesan = "<b>".$_SESSION['NamaUser']."</b>, Berhasil Mengubah Data Aprove MKP!";
					AddLog($_SESSION['IdUser'],$Pesan);
					if($query){ echo "sukses"; exit(); } else { echo "gagal"; }
				} catch (PDOException $e) {
					echo $e->getMessage();
				}
				break;
			case 'delete':
				$sql = "DELETE FROM tb_aprove_mkp WHERE IdAproveMkp = :IdAproveMkp";
				$query = $db->prepare($sql);
				$query->bindParam("IdAproveMkp", $IdAproveMkp);
				$query->execute();
				$Pesan = "<b>".$_SESSION['NamaUser']."</b>, Berhasil Menghapus Data Aprove MKP!";
					AddLog($_SESSION['IdUser'],$Pesan);
				if($query){ echo "sukses"; exit(); } else { echo "gagal"; }
				break;
		}
		
		break;

	case 'ShowData':
		$IdAproveMkp = $_POST['IdAproveMkp'];
		$sql = "SELECT * FROM tb_aprove_mkp WHERE IdAproveMkp = :IdAproveMkp";
		$stmt = $db->prepare($sql);
		$stmt->bindParam('IdAproveMkp', $IdAproveMkp);
		$stmt->execute();
		$res = $stmt->fetch(PDO::FETCH_ASSOC);
		$JabatanAprove = $db->query("SELECT NamaJabatan,IdJabatan FROM tb_jabatan_pejabat WHERE IdJabatan = '$res[IdJabatanAprove]'")->fetch(PDO::FETCH_ASSOC);
		$res['itemJabatanAperove']['value']=$JabatanAprove['IdJabatan'];
		$res['itemJabatanAperove']['NamaJabatan']=$JabatanAprove['NamaJabatan'];
		$rows =array();
		$Jabatans = $db->query("SELECT NamaJabatan,IdJabatan FROM tb_jabatan_pejabat WHERE IdJabatan IN ($res[IdJabatanRKB])");
		while($r = $Jabatans->fetch(PDO::FETCH_ASSOC)){
			$rows['value'] = $r['IdJabatan'];
			$rows['NamaJabatan'] = $r['NamaJabatan'];
			$res['itemJabatanRKB'][]=$rows;
		}
		echo json_encode($res);
		break;
	

}

?>
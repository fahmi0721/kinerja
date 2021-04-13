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
		$sql = "SELECT a.*, b.NamaCabang FROM tb_paket_penagihan a INNER JOIN tb_cabang b ON a.IdCabang = b.IdCabang ORDER BY IdPaketPenagihan DESC";
		$query = $db->query($sql);
		$JumRow = $query->rowCount();
		if($JumRow > 0){
			while ($res = $query->fetch(PDO::FETCH_ASSOC)) { 
				$aksi = $_SESSION['Level'] == "author" ? "" : "<a class='btn btn-xs btn-primary' data-toggle='toolltip' title='Ubah Data' onclick=\"Crud('".$res['IdPaketPenagihan']."', 'ubah')\"><i class='fa fa-edit'></i></a> <a class='btn btn-xs btn-danger' data-toggle='toolltip' title='Hapus Data' onclick=\"Crud('".$res['IdPaketPenagihan']."', 'hapus')\"><i class='fa fa-trash-o'></i></a>";
				$row['No'] = $no;
				$row['NamaPaket'] = $res['NamaPaket'];
				$row['NamaCabang'] = $res['NamaCabang'];
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
		$IdPaketPenagihan = $_POST['IdPaketPenagihan'];
		$IdCabang = $_POST['IdCabang'];
		$NamaPaket = $_POST['NamaPaket'];
		$Paket = implode(",",$_POST['Jabatan']);
		switch ($aksi) {
			case 'insert':
				try {
					$sql = "INSERT INTO tb_paket_penagihan SET  IdCabang = :IdCabang,  NamaPaket = :NamaPaket, TglCreate = :TglCreate,  Paket = :Paket, UserUpdate = :UserUpdate";
					$query = $db->prepare($sql);
					$query->bindParam("IdCabang", $IdCabang);
					$query->bindParam("NamaPaket", $NamaPaket);
					$query->bindParam("TglCreate", $date);
					$query->bindParam("Paket", $Paket);
					$query->bindParam("UserUpdate", $UserUpdate);
					$query->execute();
					$Pesan = "<b>".$_SESSION['NamaUser']."</b>, Berhasil Menambah Data Paket Penagihan!";
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
					$sql = "UPDATE tb_paket_penagihan SET  IdCabang = :IdCabang,  NamaPaket = :NamaPaket, TglUpdate = :TglUpdate,  Paket = :Paket, UserUpdate = :UserUpdate WHERE IdPaketPenagihan = :IdPaketPenagihan";
					$query = $db->prepare($sql);
					$query->bindParam("IdCabang", $IdCabang);
					$query->bindParam("NamaPaket", $NamaPaket);
					$query->bindParam("TglUpdate", $date);
					$query->bindParam("Paket", $Paket);
					$query->bindParam("UserUpdate", $UserUpdate);
					$query->bindParam("IdPaketPenagihan", $IdPaketPenagihan);
					$query->execute();
					$Pesan = "<b>".$_SESSION['NamaUser']."</b>, Berhasil Mengubah Data Paket Penagihan!";
					AddLog($_SESSION['IdUser'],$Pesan);
					if($query){ echo "sukses"; exit(); } else { echo "gagal"; }
				} catch (PDOException $e) {
					echo $e->getMessage();
				}
				break;
			case 'delete':
				$sql = "DELETE FROM tb_paket_penagihan WHERE IdPaketPenagihan = :IdPaketPenagihan";
				$query = $db->prepare($sql);
				$query->bindParam("IdPaketPenagihan", $IdPaketPenagihan);
				$query->execute();
				$Pesan = "<b>".$_SESSION['NamaUser']."</b>, Berhasil Menghapus Paket Penagihan!";
					AddLog($_SESSION['IdUser'],$Pesan);
				if($query){ echo "sukses"; exit(); } else { echo "gagal"; }
				break;
		}
		break;

	case 'ShowData':
		$IdPaketPenagihan = $_POST['IdPaketPenagihan'];
		$sql = "SELECT a.*, b.NamaCabang, b.KodeCabang FROM tb_paket_penagihan a INNER JOIN tb_cabang b ON a.IdCabang = b.IdCabang WHERE a.IdPaketPenagihan = :IdPaketPenagihan";
		$stmt = $db->prepare($sql);
		$stmt->bindParam('IdPaketPenagihan', $IdPaketPenagihan);
		$stmt->execute();
		$res = $stmt->fetch(PDO::FETCH_ASSOC);
		$Jabatans = explode(",",$res['Paket']);
		$res['Items'] = $Jabatans;

		echo json_encode($res);
		break;

	case "GetListPenagihan":
		$res=array();
		$data = array();
		$Jabatans=array();
		foreach($_POST['Jabatan'] as $jb){
			$Jabatans[] = "'".$jb."'";
		}
		$Jabatan = implode(",",$Jabatans);
		$IdCabang = $_POST['IdCabang'];
		if(!empty($_POST['Jabatan'])){
			$sql = "SELECT a.NamaKaryawan, b.NamaJabatan FROM tb_karyawan a INNER JOIN tb_jabatan b ON a.Jabatan = b.KodeJabatan WHERE Jabatan IN ($Jabatan) AND a.IdCabang = '$IdCabang' ORDER BY b.NamaJabatan ASC";
			$query = $db->query($sql);
			$rows = $query->rowCount();
			if($rows > 0){
				$res['messages'] = 'success';
				while($r = $query->fetch(PDO::FETCH_ASSOC)){
					$data['NamaKaryawan']= $r['NamaKaryawan'];
					$data['NamaJabatan']= $r['NamaJabatan'];
					$res['Item'][] = $data;
				}
			}else{
				$res['messages'] = null;
			}
		}else{
			$res['messages'] = null;
		}
		echo json_encode($res);
		break;
	
	case "GetDataJabatabCabang":
		$IdCabang = $_POST['IdCabang'];
		$sql = "SELECT tb_karyawan.Jabatan, tb_jabatan.NamaJabatan FROM tb_karyawan INNER JOIN tb_jabatan ON tb_karyawan.Jabatan = tb_jabatan.KodeJabatan WHERE tb_karyawan.IdCabang = '$IdCabang' GROUP BY tb_karyawan.Jabatan ORDER BY tb_jabatan.NamaJabatan ASC";
		$query = $db->query($sql);
		$rows = $query->rowCount();
		$data = array();
		$result = array();
		if($rows > 0){
			$data['messages']='success';
			while($res = $query->fetch(PDO::FETCH_ASSOC)){
				$result['Jabatan'] = $res['Jabatan'];
				$result['NamaJabatan'] = $res['NamaJabatan'];
				$data['Item'][] = $result;
			}
		}else{
			$data['messages']='notfound';
		}
		echo json_encode($data);
		break;
}

?>
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
		$sql = "SELECT a.*, b.NamaCabang FROM tb_biaya_bahan a INNER JOIN tb_cabang b ON a.IdCabang = b.IdCabang ORDER BY a.IdBiayaBahan DESC, a.IdCabang ASC";
		$query = $db->query($sql);
		$JumRow = $query->rowCount();
		if($JumRow > 0){
			while ($res = $query->fetch(PDO::FETCH_ASSOC)) { 
				$aksi = $_SESSION['Level'] == "author" ? "-" : "<a class='btn btn-xs btn-primary' data-toggle='toolltip' title='Ubah Data' onclick=\"Crud('".$res['IdBiayaBahan']."', 'ubah')\"><i class='fa fa-edit'></i></a> <a class='btn btn-xs btn-danger' data-toggle='toolltip' title='Hapus Data' onclick=\"Crud('".$res['IdBiayaBahan']."', 'hapus')\"><i class='fa fa-trash-o'></i></a>";
				$row['No'] = $no;
				$row['NamaCabang'] = $res['NamaCabang'];
				$row['NamaBiaya'] = $res['NamaBiaya'];
				$row['Biaya'] = rupiah($res['Biaya']);
				$row['Berlaku'] = tgl_indo($res['BerlakuMulai'])." s/d ".tgl_indo($res['BerlakuSampai']);
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
		$IdBiayaBahan = $_POST['IdBiayaBahan'];
		$NamaBiaya = $_POST['NamaBiaya'];
		$Biaya = angkaDecimal($_POST['Biaya']);
		$Ppn = angkaDecimal($_POST['Ppn']);
		$BerlakuMulai = $_POST['BerlakuMulai'];
		$BerlakuSampai = $_POST['BerlakuSampai'];
		$Keterangan = $_POST['Keterangan'];
		switch ($aksi) {
			case 'insert':
				try {
					$sql = "INSERT INTO tb_biaya_bahan SET  IdCabang = :IdCabang,  NamaBiaya = :NamaBiaya, Biaya = :Biaya, BerlakuMulai = :BerlakuMulai, BerlakuSampai = :BerlakuSampai, TglCreate = :TglCreate, Ppn = :Ppn, Keterangan = :Keterangan, UserUpdate = :UserUpdate";
					$query = $db->prepare($sql);
					$query->bindParam("IdCabang", $IdCabang);
					$query->bindParam("NamaBiaya", $NamaBiaya);
					$query->bindParam("Biaya", $Biaya);
					$query->bindParam("BerlakuMulai", $BerlakuMulai);
					$query->bindParam("BerlakuSampai", $BerlakuSampai);
					$query->bindParam("TglCreate", $date);
					$query->bindParam("Ppn", $Ppn);
					$query->bindParam("Keterangan", $Keterangan);
					$query->bindParam("UserUpdate", $UserUpdate);
					$query->execute();
					$Pesan = "<b>".$_SESSION['NamaUser']."</b>, Berhasil Menambah Data Biaya Bahan!";
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
					$sql = "UPDATE tb_biaya_bahan SET  NamaBiaya = :NamaBiaya, Biaya = :Biaya, BerlakuMulai = :BerlakuMulai, BerlakuSampai = :BerlakuSampai, TglUpdate = :TglUpdate, Ppn = :Ppn, Keterangan = :Keterangan, UserUpdate = :UserUpdate WHERE IdBiayaBahan = :IdBiayaBahan";
					$query = $db->prepare($sql);
					$query->bindParam("NamaBiaya", $NamaBiaya);
					$query->bindParam("Biaya", $Biaya);
					$query->bindParam("BerlakuMulai", $BerlakuMulai);
					$query->bindParam("BerlakuSampai", $BerlakuSampai);
					$query->bindParam("TglUpdate", $date);
					$query->bindParam("Ppn", $Ppn);
					$query->bindParam("Keterangan", $Keterangan);
					$query->bindParam("UserUpdate", $UserUpdate);
					$query->bindParam("IdBiayaBahan", $IdBiayaBahan);
					$query->execute();
					$Pesan = "<b>".$_SESSION['NamaUser']."</b>, Berhasil Mengubah Data Biaya Bahan!";
					AddLog($_SESSION['IdUser'],$Pesan);
					if($query){ echo "sukses"; exit(); } else { echo "gagal"; }
				} catch (PDOException $e) {
					echo $e->getMessage();
				}
				break;
			case 'delete':
				$sql = "DELETE FROM tb_biaya_bahan WHERE IdBiayaBahan = :IdBiayaBahan";
				$query = $db->prepare($sql);
				$query->bindParam("IdBiayaBahan", $IdBiayaBahan);
				$query->execute();
				$Pesan = "<b>".$_SESSION['NamaUser']."</b>, Berhasil Menghapus Data Biaya Bahan!";
					AddLog($_SESSION['IdUser'],$Pesan);
				if($query){ echo "sukses"; exit(); } else { echo "gagal"; }
				break;
		}
		break;

	case 'ShowData':
		$IdBiayaBahan = $_POST['IdBiayaBahan'];
		$sql = "SELECT a.*, b.NamaCabang, b.KodeCabang FROM tb_biaya_bahan a INNER JOIN tb_cabang b ON a.IdCabang = b.IdCabang WHERE IdBiayaBahan = :IdBiayaBahan";
		$stmt = $db->prepare($sql);
		$stmt->bindParam('IdBiayaBahan', $IdBiayaBahan);
		$stmt->execute();
		$res = $stmt->fetch(PDO::FETCH_ASSOC);
		echo json_encode($res);
		break;
	
}

?>
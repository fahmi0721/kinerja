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
	
	case "GetUnitKerja":
		$IdCabang = $_POST['IdCabang'];
		$result = array();
		$res = array();
		$ress = array();
		$sql = "SELECT a.NamaJabatan, a.KodeJabatan FROM tb_jabatan a INNER JOIN tb_karyawan b ON a.KodeJabatan = b.Jabatan WHERE b.IdCabang = '$IdCabang' AND b.NomorKontrak IS NULL GROUP BY b.Jabatan";
		$query = $db->query($sql);
		$row = $query->rowCount();
		if($row > 0){
			$result['messages'] = "success";
			while($r = $query->fetch(PDO::FETCH_ASSOC)){
				$res['KodeJabatan'] = $r['KodeJabatan'];
				$res['NamaJabatan'] = $r['NamaJabatan'];
				$sql1 = "SELECT IdKaryawan , NamaKaryawan FROM tb_karyawan WHERE IdCabang = '$IdCabang' AND Jabatan = '$r[KodeJabatan]' AND NomorKontrak IS NULL";
				$query2 = $db->query($sql1);
				while($rr = $query2->fetch(PDO::FETCH_ASSOC)){
					$ress['IdKaryawan'] = $rr['IdKaryawan'];
					$ress['NamaKaryawan'] = $rr['NamaKaryawan'];
					$result['ItemKaryawan'][$r['KodeJabatan']][] = $ress;
				}
				$ress = array();
				$result['item'][] = $res;
			}
		}else{
			$result['messages'] = "nodata";
		}

		echo json_encode($result);
		break;
}

?>
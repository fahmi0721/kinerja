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
		$sql = "SELECT NamaJenisTk, Jabatan FROM tb_jenis_tk";
		$query = $db->query($sql);
		$JumRow = $query->rowCount();
		if($JumRow > 0){
			while ($res = $query->fetch(PDO::FETCH_ASSOC)) { 
				$list = array();
				$jbbb = explode(",",$res['Jabatan']);
				for($i=0; $i < count($jbbb); $i++){
					$list[] = "'".$jbbb[$i]."'";
				}
				$jb = implode(",",$list);
				$dt = $db->query("SELECT COUNT(IdKaryawan) as tot FROM tb_karyawan WHERE Jabatan IN ($jb)")->fetch(PDO::FETCH_ASSOC);
				$aksi = $_SESSION['Level'] == "author" ? "-" : "<a class='btn btn-xs btn-primary' data-toggle='toolltip' title='Ubah Data' onclick=\"Crud('".$res['IdBiayaBahan']."', 'ubah')\"><i class='fa fa-edit'></i></a> <a class='btn btn-xs btn-danger' data-toggle='toolltip' title='Hapus Data' onclick=\"Crud('".$res['IdBiayaBahan']."', 'hapus')\"><i class='fa fa-trash-o'></i></a>";
				$row['No'] = $no;
				$row['NamaJenisTk'] = $res['NamaJenisTk'];
				$row['Jumlah'] = $dt['tot'];
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
		$IdJenisTk = $_POST['IdJenisTk'];
		$NamaJenisTk = $_POST['NamaJenisTk'];
		$Jabatan = implode(",",$_POST['Jabatan']);
		
		switch ($aksi) {
			case 'insert':
				try {
					$sql = "INSERT INTO tb_jenis_tk  SET  NamaJenisTk = :NamaJenisTk,  Jabatan = :Jabatan, TglCreate = :TglCreate, UserUpdate = :UserUpdate";
					$query = $db->prepare($sql);
					$query->bindParam("NamaJenisTk", $NamaJenisTk);
					$query->bindParam("Jabatan", $Jabatan);
					$query->bindParam("TglCreate", $date);
					$query->bindParam("UserUpdate", $UserUpdate);
					$query->execute();
					$Pesan = "<b>".$_SESSION['NamaUser']."</b>, Berhasil Menambah Data Brekdown Anggaran!";
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
	
	case 'ShowJabatan':
		$sql = "SELECT Jabatan FROM tb_jenis_tk ORDER BY IdJenisTk ASC";
		$query = $db->query($sql);
		$row = $query->rowCount();
		$data=array();
		$rr = array();
		if($row > 0){
			$jbb=array();
			$jbbb=array();
			while($r = $query->fetch(PDO::FETCH_ASSOC)){
				$jbb = explode(",",$r['Jabatan']);
				for($i=0; $i < count($jbb); $i++){
					$jbbb[] = $jbb[$i];
				}
			}

			$list = array();
			for($i=0; $i < count($jbbb); $i++){
				$list[] = "'".$jbbb[$i]."'";
			}
			$jb = implode(",",$list);

			$sql1 = "SELECT KodeJabatan, NamaJabatan FROM tb_jabatan WHERE KodeJabatan NOT IN ($jb) ORDER BY NamaJabatan ASC";
			$query1 = $db->query($sql1);
			while($rs = $query1->fetch(PDO::FETCH_ASSOC)){
				$rr['KodeJabatan'] = $rs['KodeJabatan'];
				$rr['NamaJabatan'] = $rs['NamaJabatan'];
				$data['Item'][] = $rr;
			}
		}else{
			$sql1 = "SELECT KodeJabatan, NamaJabatan FROM tb_jabatan ORDER BY NamaJabatan ASC";
			$query1 = $db->query($sql1);
			while($rs = $query1->fetch(PDO::FETCH_ASSOC)){
				$rr['KodeJabatan'] = $rs['KodeJabatan'];
				$rr['NamaJabatan'] = $rs['NamaJabatan'];
				$data['Item'][] = $rr;
			}
		}
		echo json_encode($data);
		
		break;
	
}

?>
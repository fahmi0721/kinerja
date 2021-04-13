<?php
session_start();
include_once '../../config/config.php';
include_once '../../lib/excel_reader/excel_reader2.php';
$UserUpdate = $_SESSION['IdUser'];
$date = date("Y-m-d H:i:s");
$proses = isset($_GET['proses']) ? $_GET['proses'] : "";
switch ($proses) {
	case 'DetailData':
		$row = array(); 
		$data = array(); 
		$no=1;
		$sql = "SELECT * FROM tb_jabatan ORDER BY IdJabatan DESC";
		$query = $db->query($sql);
		$JumRow = $query->rowCount();
		if($JumRow > 0){
			while ($res = $query->fetch(PDO::FETCH_ASSOC)) { 
				$aksi = $_SESSION['Level'] == "author" ? "-" : "<a class='btn btn-xs btn-primary' data-toggle='toolltip' title='Ubah Data' onclick=\"Crud('".$res['IdJabatan']."', 'ubah')\"><i class='fa fa-edit'></i></a> <a class='btn btn-xs btn-danger' data-toggle='toolltip' title='Hapus Data' onclick=\"Crud('".$res['IdJabatan']."', 'hapus')\"><i class='fa fa-trash-o'></i></a>";
				$row['No'] = $no;
				$row['KodeJabatan'] = $res['KodeJabatan'];
				$row['NamaJabatan'] = $res['NamaJabatan'];
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
		$KodeJabatan = $_POST['KodeJabatan'];
		$NamaJabatan = $_POST['NamaJabatan'];
		$Keterangan = $_POST['Keterangan'];
		switch ($aksi) {
			case 'insert':
				try {
					$sql = "INSERT INTO tb_jabatan SET  NamaJabatan = :NamaJabatan,  KodeJabatan = :KodeJabatan, TglCreate = :TglCreate,  Keterangan = :Keterangan, UserUpdate = :UserUpdate";
					$query = $db->prepare($sql);
					$query->bindParam("NamaJabatan", $NamaJabatan);
					$query->bindParam("KodeJabatan", $KodeJabatan);
					$query->bindParam("TglCreate", $date);
					$query->bindParam("Keterangan", $Keterangan);
					$query->bindParam("UserUpdate", $UserUpdate);
					$query->execute();
					$Pesan = "<b>".$_SESSION['NamaUser']."</b>, Berhasil Menambah Data Jabatan!";
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
					$sql = "UPDATE tb_jabatan SET NamaJabatan = :NamaJabatan,  KodeJabatan = :KodeJabatan, TglCreate = :TglCreate,  Keterangan = :Keterangan, UserUpdate = :UserUpdate WHERE IdJabatan = :IdJabatan";
					$query = $db->prepare($sql);
					$query->bindParam("NamaJabatan", $NamaJabatan);
					$query->bindParam("KodeJabatan", $KodeJabatan);
					$query->bindParam("TglCreate", $date);
					$query->bindParam("Keterangan", $Keterangan);
					$query->bindParam("UserUpdate", $UserUpdate);
					$query->bindParam("IdJabatan", $IdJabatan);
					$query->execute();
					$Pesan = "<b>".$_SESSION['NamaUser']."</b>, Berhasil Mengubah Data Jabatan!";
					AddLog($_SESSION['IdUser'],$Pesan);
					if($query){ echo "sukses"; exit(); } else { echo "gagal"; }
				} catch (PDOException $e) {
					echo $e->getMessage();
				}
				break;
			case 'delete':
				$sql = "DELETE FROM tb_jabatan WHERE IdJabatan = :IdJabatan";
				$query = $db->prepare($sql);
				$query->bindParam("IdJabatan", $IdJabatan);
				$query->execute();
				$Pesan = "<b>".$_SESSION['NamaUser']."</b>, Berhasil Menghapus Data Jabatan!";
					AddLog($_SESSION['IdUser'],$Pesan);
				if($query){ echo "sukses"; exit(); } else { echo "gagal"; }
				break;
		}
		break;

	case 'ShowData':
		$IdJabatan = $_POST['IdJabatan'];
		$sql = "SELECT * FROM tb_jabatan WHERE IdJabatan = :IdJabatan";
		$stmt = $db->prepare($sql);
		$stmt->bindParam('IdJabatan', $IdJabatan);
		$stmt->execute();
		$res = $stmt->fetch(PDO::FETCH_ASSOC);
		echo json_encode($res);
		break;
	
	case "GetKode":
		$sql = "SELECT MAX(RIGHT(KodeJabatan,5)) as Kode FROM tb_jabatan";
		$query = $db->query($sql);
		$data = $query->fetch(PDO::FETCH_ASSOC);
		$kode = empty($data['Kode']) ? "J-00001" : "J-".sprintf('%05d',$data['Kode'] + 1);
		echo $kode;
		break;
	case 'UploadData' :
		if(isset($_FILES['File'])){
			$target = basename($_FILES['File']['name']);
			$exten = explode(".",$target);
			$exten = end($exten);
			if($exten == "xls"){
				move_uploaded_file($_FILES['File']['tmp_name'], $target);
				chmod($_FILES['File']['name'],0777);
				$datas = new Spreadsheet_Excel_Reader($_FILES['File']['name'],false);
				// menghitung jumlah baris data yang ada
				
				$jumlah_baris = $datas->rowcount($sheet_index=0);
				$sql = "SELECT MAX(RIGHT(KodeJabatan,5)) as Kode FROM tb_jabatan";
				$query = $db->query($sql);
				$data = $query->fetch(PDO::FETCH_ASSOC);
				$kode = empty($data['Kode']) ? 1 : $data['Kode'];
				$Posisi=0;
				for ($i=6; $i<=$jumlah_baris; $i++){
					$Kode = "J-".sprintf('%05d',$kode + $Posisi);
					$NamaJabatan = $datas->val($i, 2);
					$sql = "INSERT INTO tb_jabatan SET KodeJabatan = :KodeJabatan, NamaJabatan = :NamaJabatan,  TglCreate = :TglCreate, UserUpdate = :UserUpdate";
					$query = $db->prepare($sql);
					$query->bindParam('KodeJabatan', $Kode);
					$query->bindParam('NamaJabatan', $NamaJabatan);
					$query->bindParam('TglCreate', $date);
					$query->bindParam('UserUpdate', $UserUpdate);
					$query->execute();
					$Posisi++;
				}
			}
		}
		echo "sukses";
		break;
	
	
}

?>
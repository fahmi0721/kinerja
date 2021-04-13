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
		$sql = "SELECT tb_pejabat.*, tb_jabatan_pejabat.NamaJabatan FROM tb_pejabat INNER JOIN tb_jabatan_pejabat ON tb_pejabat.IdJabatan = tb_jabatan_pejabat.IdJabatan WHERE tb_pejabat.Status != '0' ORDER BY tb_pejabat.IdPejabat ASC";
		$query = $db->query($sql);
		$JumRow = $query->rowCount();
		if($JumRow > 0){
			while ($res = $query->fetch(PDO::FETCH_ASSOC)) { 
				$aksi = $_SESSION['Level'] == "author" ? "-" : "<a class='btn btn-xs btn-primary' data-toggle='tooltip' title='Ubah Data' onclick=\"Crud('".$res['IdPejabat']."', 'ubah')\"><i class='fa fa-edit'></i></a> <a class='btn btn-xs btn-danger' data-toggle='tooltip' title='Hapus Data' onclick=\"Crud('".$res['IdPejabat']."', 'hapus')\"><i class='fa fa-trash-o'></i></a>";
				$Foto = "<a class='btn btn-xs btn-success' data-toggle='tooltip' title='Lihat Foto' onclick=\"ShowFoto('".$res['Foto']."')\"><i class='fa fa-picture-o'></i></a>";
				$row['No'] = $no;
				$row['Nama'] = $res['Nama'].", ".$res['Title'];
				$row['Nik'] = $res['Nik'];
				$row['NoHp'] = $res['NoHP'];
				$row['TTL'] = $res['TptLahir'].", ".tgl_indo($res['TglLahir']);
				$row['KJ'] = $res['KelasJabatan'];
				$row['Jabatan'] = $res['NamaJabatan'];
				$row['Alamat'] = $res['Alamat'];
				$row['Foto'] = $Foto;
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
		$IdPejabat = $_POST['IdPejabat'];
		$Title = $_POST['Title'];
		$Nik = $_POST['Nik'];
		$Nama = $_POST['Nama'];
		$Alamat = $_POST['Alamat'];
		$JK = $_POST['JK'];
		$TptLahir = $_POST['TptLahir'];
		$TglLahir = $_POST['TglLahir'];
		$NoHp = $_POST['NoHp'];
		$KelasJabatan = $_POST['KelasJabatan'];
		$IdJabatan = $_POST['IdJabatan'];
		$Alamat = $_POST['Alamat'];
		$TmpFoto = $_POST['TmpFoto'];

		$dir = "../../img/Pejabat/";
		switch ($aksi) {
			case 'insert':
				try {
					$filter = "";
					$Foto ="";
					if(!empty($_FILES['Foto']['tmp_name'])){
						
						$foto = explode(".",$_FILES['Foto']['name']);
						$extensi = strtolower(end($foto));
						$Foto = time().rand(0,9999).".".$extensi;
						move_uploaded_file($_FILES['Foto']['tmp_name'], $dir.$Foto);
						$filter = ", Foto = :Foto";
					}
					
					$sql = "INSERT INTO tb_pejabat SET  Nama = :Nama,  Title = :Title, Nik = :Nik, JK = :JK, TglLahir = :TglLahir, TptLahir = :TptLahir, KelasJabatan = :KelasJabatan, IdJabatan = :IdJabatan, NoHp = :NoHp, Alamat = :Alamat,  TglCreate = :TglCreate, UserUpdate = :UserUpdate $filter";
					$query = $db->prepare($sql);
					$query->bindParam("Nama", $Nama);
					$query->bindParam("Title", $Title);
					$query->bindParam("Nik", $Nik);
					$query->bindParam("JK", $JK);
					$query->bindParam("TglLahir", $TglLahir);
					$query->bindParam("TptLahir", $TptLahir);
					$query->bindParam("KelasJabatan", $KelasJabatan);
					$query->bindParam("IdJabatan", $IdJabatan);
					$query->bindParam("NoHp", $NoHp);
					$query->bindParam("Alamat", $Alamat);
					$query->bindParam("TglCreate", $date);
					$query->bindParam("UserUpdate", $UserUpdate);
					if(!empty($_FILES['Foto']['tmp_name'])){
						$query->bindParam("Foto", $Foto);
					}
					$query->execute();
					$Pesan = "<b>".$_SESSION['NamaUser']."</b>, Berhasil Menambah Data  Pejabat!";
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
					$filter = "";
					$Foto ="";
					if(!empty($_FILES['Foto']['tmp_name'])){
						HapusFile($dir,$TmpFoto);
						$foto = explode(".",$_FILES['Foto']['name']);
						$extensi = strtolower(end($foto));
						$Foto = time().rand(0,9999).".".$extensi;
						move_uploaded_file($_FILES['Foto']['tmp_name'], $dir.$Foto);
						$filter = ", Foto = :Foto";
					}
					$sql = "UPDATE tb_pejabat SET  Nama = :Nama,  Title = :Title, Nik = :Nik, JK = :JK, TglLahir = :TglLahir, TptLahir = :TptLahir, KelasJabatan = :KelasJabatan, IdJabatan = :IdJabatan, NoHp = :NoHp, Alamat = :Alamat,  TglUpdate = :TglUpdate, UserUpdate = :UserUpdate $filter WHERE IdPejabat = :IdPejabat";
					$query = $db->prepare($sql);
					$query->bindParam("Nama", $Nama);
					$query->bindParam("Title", $Title);
					$query->bindParam("Nik", $Nik);
					$query->bindParam("JK", $JK);
					$query->bindParam("TglLahir", $TglLahir);
					$query->bindParam("TptLahir", $TptLahir);
					$query->bindParam("KelasJabatan", $KelasJabatan);
					$query->bindParam("IdJabatan", $IdJabatan);
					$query->bindParam("NoHp", $NoHp);
					$query->bindParam("Alamat", $Alamat);
					$query->bindParam("TglUpdate", $date);
					$query->bindParam("UserUpdate", $UserUpdate);
					if(!empty($_FILES['Foto']['tmp_name'])){
						$query->bindParam("Foto", $Foto);
					}
					$query->bindParam("IdPejabat", $IdPejabat);
					$query->execute();
					$Pesan = "<b>".$_SESSION['NamaUser']."</b>, Berhasil Mengubah Data  Pejabat!";
					AddLog($_SESSION['IdUser'],$Pesan);
					if($query){ echo "sukses"; exit(); } else { echo "gagal"; }
				} catch (PDOException $e) {
					echo $e->getMessage();
				}
				break;
			case 'delete':
				$TmpFoto = $db->query("SELECT Foto FROM tb_pejabat WHERE IdPejabat = '$IdPejabat'")->fetch(PDO::FETCH_ASSOC);
				HapusFile($dir,$TmpFoto['Foto']);
				$sql = "DELETE FROM tb_pejabat WHERE IdPejabat = :IdPejabat";
				$query = $db->prepare($sql);
				$query->bindParam("IdPejabat", $IdPejabat);
				$query->execute();
				$Pesan = "<b>".$_SESSION['NamaUser']."</b>, Berhasil Menghapus Data Pejabat!";
					AddLog($_SESSION['IdUser'],$Pesan);
				if($query){ echo "sukses"; exit(); } else { echo "gagal"; }
				break;
		}
		break;

	case 'ShowData':
		$IdPejabat = $_POST['IdPejabat'];
		$sql = "SELECT tb_pejabat.*, tb_jabatan_pejabat.NamaJabatan FROM tb_pejabat INNER JOIN tb_jabatan_pejabat ON tb_pejabat.IdJabatan = tb_jabatan_pejabat.IdJabatan WHERE tb_pejabat.IdPejabat = :IdPejabat";
		$stmt = $db->prepare($sql);
		$stmt->bindParam('IdPejabat', $IdPejabat);
		$stmt->execute();
		$res = $stmt->fetch(PDO::FETCH_ASSOC);
		echo json_encode($res);
		break;

	case 'GetNik':
		$TglLahir = explode("-",$_POST['TglLahir']);
		$tahun = substr($TglLahir[0],2,2);
		$bulan = $TglLahir[1];
		$sql = "SELECT MAX(RIGHT(Nik,4)) as NIK FROM tb_pejabat LIMIT 1";
		$r = $db->query($sql);
		$row = $r->rowCount();
		if($row > 0){
			$rs = $r->fetch(PDO::FETCH_ASSOC);
			$ID = intval($rs['NIK']) + 1;
			$Nik = "1".$tahun.$bulan. sprintf("%04d",$ID);
		}else{
			$Nik = "1".$tahun.$bulan."0001";
		}
		$result = array("msg" => "sukses", "NIK" => $Nik);
		echo json_encode($result);
		break;

}

?>
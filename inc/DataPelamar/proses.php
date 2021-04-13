<?php
session_start();
include_once '../../config/config.php';

$UserUpdate = 1;
$date = date("Y-m-d H:i:s");
$proses = isset($_GET['proses']) ? $_GET['proses'] : "";
switch ($proses) {
	case 'DetailData': 
			$data = array();
			$sql = "SELECT * FROM tb_pelamar ORDER BY IdPelamar DESC";
			$query = $db->query($sql);
			$row = array();
			$no=1;
			while($dt = $query->fetch(PDO::FETCH_ASSOC)){
				$Aksi = $_SESSION['Level'] == "author" ? "" : " <a data-toggle='tooltip' onclick=\"Crud('".$dt['IdPelamar']."','Ubah')\" title='Ubah Data' class='btn btn-xs btn-info'><i class='fa fa-edit'></i></a> <a data-toggle='tooltip' title='Hapus Data' class='btn btn-xs btn-danger'  onclick=\"Crud('".$dt['IdPelamar']."','delete')\"><i class='fa fa-trash-o'></i></a>";
				$data['No']   = $no;
				$data['NoLamaran'] = "ISEMA-PLM-".sprintf("%05d",$dt['NoLamaran']);
				$data['Nama'] = $dt['Nama'];
				$data['NoKTP'] = $dt['NoKTP'];
				$data['TTL'] = $dt['TempatLahir'].", ".tgl_indo($dt['TglLahir']);
				$data['Agama'] = $dt['Agama'];
				$data['NoTelp'] = $dt['NoTelp'];
				$data['Pendidikan'] = $dt['Pendidikan']." / ".$dt['Universitas'];
				$data['Alamat'] = $dt['Alamat'];
				$data['Aksi'] = "<center><a data-toggle='tooltip' onclick=\"DetailBerkas('".$dt['IdPelamar']."','Ubah')\" title='Detail Data' class='btn btn-xs btn-success'><i class='fa fa-eye'></i></a> $Aksi</center>";
				$row['data'][] = $data;
				$no++;
			}
			echo json_encode($row);
		break;
	case 'Crud':
		$aksi = $_POST['aksi'];
		$IdPelamar = $_POST['IdPelamar'];
		$IdLowongan = $_POST['Lowongan'];
		$NoLamaran = $_POST['NoLamaran'];
		$Nama = $_POST['Nama'];
		$NoKTP = $_POST['NoKTP'];
		$TempatLahir = $_POST['TempatLahir'];
		$TglLahir = $_POST['TglLahir'];
		$Agama = $_POST['Agama'];
		$NoTelp = $_POST['NoTelp'];
		$Email = $_POST['Email'];
		$Pendidikan = $_POST['Pendidikan'];
		$Universitas = $_POST['Universitas'];
		$Alamat = $_POST['Alamat'];
		if($aksi != "delete"){
			$Berkas = implode("#", $_POST['Berkas']);
		}
		switch ($aksi) {
			case 'insert':
				try {
					$sql = "INSERT INTO tb_pelamar SET IdLowongan = :IdLowongan, NoLamaran = :NoLamaran, Nama = :Nama,  NoKTP = :NoKTP, TempatLahir = :TempatLahir, TglLahir = :TglLahir, Agama = :Agama, NoTelp = :NoTelp, Email = :Email, Pendidikan =  :Pendidikan, Universitas  = :Universitas, Alamat = :Alamat, Berkas = :Berkas, TglCreate = :TglCreate, UserUpdate = :UserUpdate";
					$query = $db->prepare($sql);
					$query->bindParam("IdLowongan", $IdLowongan);
					$query->bindParam("NoLamaran", $NoLamaran);
					$query->bindParam("Nama", $Nama);
					$query->bindParam("NoKTP", $NoKTP);
					$query->bindParam("TempatLahir", $TempatLahir);
					$query->bindParam("TglLahir", $TglLahir);
					$query->bindParam("Agama", $Agama);
					$query->bindParam("NoTelp", $NoTelp);
					$query->bindParam("Email", $Email);
					$query->bindParam("Pendidikan", $Pendidikan);
					$query->bindParam("Universitas", $Universitas);
					$query->bindParam("Alamat", $Alamat);
					$query->bindParam("Berkas", $Berkas);
					$query->bindParam("TglCreate", $date);
					$query->bindParam("UserUpdate", $UserUpdate);
					$query->execute();
					echo "sukses";
					exit();
				} catch (PDOException $e) {
					echo $e->getMessage();
					exit();
				}

				break;
			case 'update':
				try {
					$sql = "UPDATE tb_pelamar SET IdLowongan = :IdLowongan, NoLamaran = :NoLamaran, Nama = :Nama,  NoKTP = :NoKTP, TempatLahir = :TempatLahir, TglLahir = :TglLahir, Agama = :Agama, NoTelp = :NoTelp, Email = :Email, Pendidikan =  :Pendidikan, Universitas  = :Universitas, Alamat = :Alamat, Berkas = :Berkas, TglUpdate = :TglUpdate, UserUpdate = :UserUpdate WHERE IdPelamar = :IdPelamar";
					$query = $db->prepare($sql);
					$query->bindParam("IdLowongan", $IdLowongan);
					$query->bindParam("NoLamaran", $NoLamaran);
					$query->bindParam("Nama", $Nama);
					$query->bindParam("NoKTP", $NoKTP);
					$query->bindParam("TempatLahir", $TempatLahir);
					$query->bindParam("TglLahir", $TglLahir);
					$query->bindParam("Agama", $Agama);
					$query->bindParam("NoTelp", $NoTelp);
					$query->bindParam("Email", $Email);
					$query->bindParam("Pendidikan", $Pendidikan);
					$query->bindParam("Universitas", $Universitas);
					$query->bindParam("Alamat", $Alamat);
					$query->bindParam("Berkas", $Berkas);
					$query->bindParam("TglUpdate", $date);
					$query->bindParam("UserUpdate", $UserUpdate);
					$query->bindParam("IdPelamar", $IdPelamar);
					$query->execute();
					echo "sukses";
					exit();
				} catch (PDOException $e) {
					echo $e->getMessage();
				}
				break;
			case 'delete':
				$sql = "DELETE FROM tb_pelamar WHERE IdPelamar = :IdPelamar";
				$query = $db->prepare($sql);
				$query->bindParam("IdPelamar", $IdPelamar);
				$query->execute();
				if($query){ echo "sukses"; exit(); } else { echo "gagal"; }
				break;
			
		}
		
		
		break;
	
	case 'ShowData':
		$IdPelamar = $_POST['IdPelamar'];
		$sql = "SELECT * FROM tb_pelamar WHERE IdPelamar = :IdPelamar";
		$stmt = $db->prepare($sql);
		$stmt->bindParam('IdPelamar', $IdPelamar);
		$stmt->execute();
		$res = $stmt->fetch(PDO::FETCH_ASSOC);
		$DataBerkas = explode("#", $res['Berkas']);
		$res['ListBerkas'] = $DataBerkas;
		echo json_encode($res);
		break;
	
	
}

?>
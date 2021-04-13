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
		$sql = "SELECT a.*, b.NamaJabatan FROM tb_nota_dinas a INNER JOIN tb_jabatan_pejabat b ON a.IdJabatanDitujukan = b.IdJabatan ORDER BY a.IdNotaDinas DESC";
		$query = $db->query($sql);
		$JumRow = $query->rowCount();
		if($JumRow > 0){
			while ($res = $query->fetch(PDO::FETCH_ASSOC)) { 

				$aksi = $_SESSION['Level'] == "author" ? "-" : "<a class='btn btn-xs btn-primary' data-toggle='toolltip' title='Ubah Data' onclick=\"Crud('".$res['IdNotaDinas']."', 'ubah')\"><i class='fa fa-edit'></i></a> <a class='btn btn-xs btn-danger' data-toggle='toolltip' title='Hapus Data' onclick=\"Crud('".$res['IdNotaDinas']."', 'hapus','$res[FileSurat]')\"><i class='fa fa-trash-o'></i></a>";
				$File = "-";
				if(!empty($res['FileSurat']) && file_exists("../../Files/NotaDinas/".$res['FileSurat'])){
					$File = "<a class='btn btn-xs btn-success' target='_Blank' href='Files/NotaDinas/$res[FileSurat]' data-toggle='tooltip' title='Lihat Surat' ><i class='fa fa-eye'></i></a>";
				}
				$CekDisposisi = $db->query("SELECT IdDisposisi FROM tb_disposisi_nota_dinas WHERE IdNotaDinas = '$res[IdNotaDinas]'")->rowCount();
				$BtnDispo = $CekDisposisi <= 0 ? "<a class='btn btn-xs btn-warning' onclick=\"OpenDisposisi('".$res['IdNotaDinas']."',0)\"   data-toggle='tooltip' title='Tambah Disposisi' ><i class='fa fa-cog'></i></a>" : "<a class='btn btn-xs btn-primary'  data-toggle='tooltip' onclick=\"OpenDisposisi('".$res['IdNotaDinas']."',1)\" title='Disposisi'><i class='fa fa-paper-plane'></i></a>";

				$BtnDispo = $_SESSION['Level'] != "author" ? $BtnDispo : "";
				if($_SESSION['Level'] == "author"){
					$BtnDispo = $CekDisposisi > 0 ? "<a class='btn btn-xs btn-primary'  data-toggle='tooltip' onclick=\"OpenDisposisi('".$res['IdNotaDinas']."',2)\" title='Disposisi'><i class='fa fa-paper-plane'></i></a>" : "";
				}


				$row['No'] = $no;
				$row['NomorNotaDinas'] = $res['NomorNotaDinas'];
				$row['TanggalNotaDinas'] = tgl_indo($res['TanggalNotaDinas']);
				$row['Perihal'] = $res['Perihal'];
				$row['NamaJabatan'] = $res['NamaJabatan'];
				$row['Keterangan'] = $res['Keterangan'];
				$row['File'] = $File." ".$BtnDispo;
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
		$IdNotaDinas = $_POST['IdNotaDinas'];
		$IdJabatanDari = $_POST['IdJabatanDari'];
		$IdJabatanDitujukan = $_POST['IdJabatanDitujukan'];
		$TmpFile = $_POST['TmpFile'];

		$NomorUrut = $_POST['NomorUrut'];
		$KodeDisposisi = $_POST['KodeDisposisi'];
		$Bulan = $_POST['Bulan'];
		$Tahun = $_POST['Tahun'];

		$NomorNotaDinas = "ND.".$NomorUrut."/".$Bulan."/".$KodeDisposisi."-".$Tahun;
		$TanggalNotaDinas = $_POST['TanggalNotaDinas'];
		$Perihal = $_POST['Perihal'];
		$Keterangan = $_POST['Keterangan'];
		switch ($aksi) {
			case 'insert':
				try {
					$Cek = $_FILES['FileSurat']['error'];
					if($Cek == 0){
						$FileName = $_FILES['FileSurat']['name'];
						$Type = $_FILES['FileSurat']['type'];
						$FileTmp = $_FILES['FileSurat']['tmp_name'];
						$UploadTo = "../../Files/NotaDinas/";
						$NameFile = rand(0,999).time().".pdf";
						$filter = array("application/pdf");
						$Upload = move_uploaded_file($FileTmp,$UploadTo.$NameFile) ? true : false;
						if($Upload){
							$sql = "INSERT INTO tb_nota_dinas SET  IdJabatanDari = :IdJabatanDari, IdJabatanDitujukan = :IdJabatanDitujukan,  NomorNotaDinas = :NomorNotaDinas, TglCreate = :TglCreate, TanggalNotaDinas = :TanggalNotaDinas, Perihal = :Perihal,  Keterangan = :Keterangan, UserUpdate = :UserUpdate, FileSurat = :FileSurat";
							$query = $db->prepare($sql);
							$query->bindParam("IdJabatanDari", $IdJabatanDari);
							$query->bindParam("IdJabatanDitujukan", $IdJabatanDitujukan);
							$query->bindParam("NomorNotaDinas", $NomorNotaDinas);
							$query->bindParam("TglCreate", $date);
							$query->bindParam("TanggalNotaDinas", $TanggalNotaDinas);
							$query->bindParam("Perihal", $Perihal);
							$query->bindParam("Keterangan", $Keterangan);
							$query->bindParam("UserUpdate", $UserUpdate);
							$query->bindParam("FileSurat", $NameFile);
							$query->execute();
						}else{
							echo "gagal";
							exit();
						}
					}else{
						$sql = "INSERT INTO tb_nota_dinas SET  IdJabatanDari = :IdJabatanDari, IdJabatanDitujukan = :IdJabatanDitujukan,  NomorNotaDinas = :NomorNotaDinas, TglCreate = :TglCreate, TanggalNotaDinas = :TanggalNotaDinas, Perihal = :Perihal,  Keterangan = :Keterangan, UserUpdate = :UserUpdate";
						$query = $db->prepare($sql);
						$query->bindParam("IdJabatanDari", $IdJabatanDari);
						$query->bindParam("IdJabatanDitujukan", $IdJabatanDitujukan);
						$query->bindParam("NomorNotaDinas", $NomorNotaDinas);
						$query->bindParam("TglCreate", $date);
						$query->bindParam("TanggalNotaDinas", $TanggalNotaDinas);
						$query->bindParam("Perihal", $Perihal);
						$query->bindParam("Keterangan", $Keterangan);
						$query->bindParam("UserUpdate", $UserUpdate);
						$query->execute();
					}
					$Pesan = "<b>".$_SESSION['NamaUser']."</b>, Berhasil Menambah Data Nota Dinas!";
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
					$Cek = $_FILES['FileSurat']['error'];
					if($Cek == 0){
						$UploadTo = "../../Files/NotaDinas/";
						$FileName = $_FILES['FileSurat']['name'];
						$Type = $_FILES['FileSurat']['type'];
						$FileTmp = $_FILES['FileSurat']['tmp_name'];
						$NameFile = rand(0,999).time().".pdf";
						$filter = array("application/pdf");
						$Upload = move_uploaded_file($FileTmp,$UploadTo.$NameFile) ? true : false;
						if($Upload){
							if(!empty($TmpFile) && file_exists($UploadTo.$TmpFile)){
								unlink($UploadTo.$TmpFile);
							}
							$sql = "UPDATE tb_nota_dinas SET TglUpdate = :TglUpdate, TanggalNotaDinas = :TanggalNotaDinas, Perihal = :Perihal,  Keterangan = :Keterangan, UserUpdate = :UserUpdate, FileSurat = :FileSurat WHERE IdNotaDinas = :IdNotaDinas";
							$query = $db->prepare($sql);
							$query->bindParam("TglUpdate", $date);
							$query->bindParam("TanggalNotaDinas", $TanggalNotaDinas);
							$query->bindParam("Perihal", $Perihal);
							$query->bindParam("Keterangan", $Keterangan);
							$query->bindParam("UserUpdate", $UserUpdate);
							$query->bindParam("FileSurat", $NameFile);
							$query->bindParam("IdNotaDinas", $IdNotaDinas);
							$query->execute();
						}else{
							echo "gagal";
							exit();
						}
					}else{
						$sql = "UPDATE tb_nota_dinas SET  TglUpdate = :TglUpdate, TanggalNotaDinas = :TanggalNotaDinas, Perihal = :Perihal,  Keterangan = :Keterangan, UserUpdate = :UserUpdate WHERE IdNotaDinas = :IdNotaDinas";
						$query = $db->prepare($sql);
						$query->bindParam("TglUpdate", $date);
						$query->bindParam("TanggalNotaDinas", $TanggalNotaDinas);
						$query->bindParam("Perihal", $Perihal);
						$query->bindParam("Keterangan", $Keterangan);
						$query->bindParam("UserUpdate", $UserUpdate);
						$query->bindParam("IdNotaDinas", $IdNotaDinas);
						$query->execute();
					}
					$Pesan = "<b>".$_SESSION['NamaUser']."</b>, Berhasil Mengubah Data Nota Dinas!";
					AddLog($_SESSION['IdUser'],$Pesan);
					if($query){ echo "sukses"; exit(); } else { echo "gagals"; }
				} catch (PDOException $e) {
					echo $e->getMessage();
				}
				break;
			case 'delete':
				$Dir = "../../Files/NotaDinas/";
				if(!empty($TmpFile) && file_exists($Dir.$TmpFile)){
					unlink($Dir.$TmpFile);
				}
				$sql = "DELETE FROM tb_nota_dinas WHERE IdNotaDinas = :IdNotaDinas";
				$query = $db->prepare($sql);
				$query->bindParam("IdNotaDinas", $IdNotaDinas);
				$query->execute();
				$Pesan = "<b>".$_SESSION['NamaUser']."</b>, Berhasil Menghapus Nota Dinas!";
					AddLog($_SESSION['IdUser'],$Pesan);
				if($query){ echo "sukses"; exit(); } else { echo "gagal"; }
				break;
		}
		break;

	case 'ShowData':
		$IdNotaDinas = $_POST['IdNotaDinas'];
		$sql = "SELECT * FROM tb_nota_dinas WHERE IdNotaDinas = :IdNotaDinas";
		$stmt = $db->prepare($sql);
		$stmt->bindParam('IdNotaDinas', $IdNotaDinas);
		$stmt->execute();
		$res = $stmt->fetch(PDO::FETCH_ASSOC);
		$Dari = $db->query("SELECT NamaJabatan FROM tb_jabatan_pejabat WHERE IdJabatan = '$res[IdJabatanDari]'")->fetch(PDO::FETCH_ASSOC);
		$Ditujukan = $db->query("SELECT NamaJabatan FROM tb_jabatan_pejabat WHERE IdJabatan = '$res[IdJabatanDitujukan]'")->fetch(PDO::FETCH_ASSOC);

		$expl = explode("/",$res['NomorNotaDinas']);
		$thn = explode("-",$expl[2]);
		$Urut = explode(".",$expl[0]);
		
		$res['Dari'] = $Dari['NamaJabatan'];
		$res['Ditujukan'] = $Ditujukan['NamaJabatan'];
		$res['NomorUrut'] = $Urut[1];
		$res['KodeDisposisi'] = $thn[0];
		$res['Tahun'] = $thn[1];
		$res['Bulan'] = $expl[1];
		echo json_encode($res);
		break;
	
	case "GetNomorSurat":
		$data = array();
		$Tahun = date("Y");
		$sql = "SELECT NomorNotaDinas FROM tb_nota_dinas WHERE DATE_FORMAT(TglCreate,'%Y') = :Tahun ORDER BY IdNotaDinas DESC LIMIT 1";
		$query = $db->prepare($sql);
		$query->bindParam('Tahun', $Tahun);
		$query->execute();
		$row = $query->rowCount();
		$res = $query->fetch(PDO::FETCH_ASSOC);
		$Nomor = explode("/",$res['NomorNotaDinas']);
		$Urut = explode(".",$Nomor[0]);

		if($row > 0){
			$data['NomorUrut'] = $Urut[1] + 1;
		}else{
			$data['NomorUrut'] = 1;
		}

		echo json_encode($data);
		break;
	
	case "CrudDisposisi":
		$aksi = $_POST['aksi'];
		$IdNotaDinas = $_POST['IdSurat'];
		$IdDisposisi = $_POST['IdDisposisi'];
		$Kepada =isset($_POST['Kepada']) ? implode(",",$_POST['Kepada']) : "";
		$Disposisi = isset($_POST['Disposisi']) ? implode(",",$_POST['Disposisi']) : "";
		$Catatan = $_POST['Catatan'];
		switch ($aksi) {
			case 'insert':
				$sql = "INSERT INTO tb_disposisi_nota_dinas SET IdNotaDinas = :IdNotaDinas, Kepada = :Kepada, Disposisi = :Disposisi, Catatan = :Catatan, TglCreated = :TglCreated, UserUpdate = :UserUpdate";
				$query = $db->prepare($sql);
				$query->bindParam("IdNotaDinas", $IdNotaDinas);
				$query->bindParam("Kepada", $Kepada);
				$query->bindParam("Disposisi", $Disposisi);
				$query->bindParam("Catatan", $Catatan);
				$query->bindParam("TglCreated", $date);
				$query->bindParam("UserUpdate", $UserUpdate);
				$query->execute();
				$Pesan = "<b>".$_SESSION['NamaUser']."</b>, Berhasil Mengubah Data Diposisi Nota Dinas!";
				AddLog($_SESSION['IdUser'],$Pesan);
				if($query){ echo "sukses"; exit(); } else { echo "gagal"; }
				break;
			
			case 'update':
				$sql = "UPDATE tb_disposisi_nota_dinas SET  Kepada = :Kepada, Disposisi = :Disposisi, Catatan = :Catatan, TglUpdate = :TglUpdate, UserUpdate = :UserUpdate WHERE IdDisposisi = :IdDisposisi";
				$query = $db->prepare($sql);
				$query->bindParam("Kepada", $Kepada);
				$query->bindParam("Disposisi", $Disposisi);
				$query->bindParam("Catatan", $Catatan);
				$query->bindParam("TglUpdate", $date);
				$query->bindParam("UserUpdate", $UserUpdate);
				$query->bindParam("IdDisposisi", $IdDisposisi);
				$query->execute();
				$Pesan = "<b>".$_SESSION['NamaUser']."</b>, Berhasil Menambah Data Diposisi Nota Dinas!";
				AddLog($_SESSION['IdUser'],$Pesan);
				if($query){ echo "sukses"; exit(); } else { echo "gagal"; }
				print_r($_POST);
				break;
				
			
			default:
				echo "error";
				break;
		}
		break;
	case "ShowDataDisposisi":
		$IdNotaDinas = $_POST['IdNotaDinas'];
		$sql = "SELECT * FROM tb_disposisi_nota_dinas WHERE IdNotaDinas = '$IdNotaDinas'";
		$query = $db->query($sql);
		$res = $query->fetch(PDO::FETCH_ASSOC);
		$res['ItemKepada'] = explode(",",$res['Kepada']);
		$res['ItemDisposisi'] = explode(",",$res['Disposisi']);

		echo json_encode($res);
		break;

}

?>
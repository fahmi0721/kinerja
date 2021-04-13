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
		$sql = "SELECT * FROM tb_surat_keluar ORDER BY IdSuratKeluar DESC";
		$query = $db->query($sql);
		$JumRow = $query->rowCount();
		if($JumRow > 0){
			while ($res = $query->fetch(PDO::FETCH_ASSOC)) { 
				$aksi = $_SESSION['Level'] == "author" ? "-" : "<a class='btn btn-xs btn-primary' data-toggle='toolltip' title='Ubah Data' onclick=\"Crud('".$res['IdSuratKeluar']."', 'ubah')\"><i class='fa fa-edit'></i></a> <a class='btn btn-xs btn-danger' data-toggle='toolltip' title='Hapus Data' onclick=\"Crud('".$res['IdSuratKeluar']."', 'hapus','$res[FileSurat]')\"><i class='fa fa-trash-o'></i></a>";
				$File = "-";
				if(!empty($res['FileSurat']) && file_exists("../../Files/SuratKeluar/".$res['FileSurat'])){
					$File = "<a class='btn btn-xs btn-success' target='_Blank' href='Files/SuratKeluar/$res[FileSurat]' data-toggle='toolltip' title='Ubah Data' ><i class='fa fa-eye'></i></a>";
				}
				$row['No'] = $no;
				$row['NomorSurat'] = $res['NomorSurat'];
				$row['TanggalSurat'] = tgl_indo($res['TanggalSurat']);
				$row['Perihal'] = $res['Perihal'];
				$row['Ditujukan'] = $res['Ditujukan'];
				$row['Keterangan'] = $res['Keterangan'];
				$row['File'] = $File;
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
		$IdSuratKeluar = $_POST['IdSuratKeluar'];
		$IdJenisSurat = $_POST['IdJenisSurat'];
		$TmpFile = $_POST['TmpFile'];
		$Kode = $_POST['Kode'];

		$NomorUrut = $_POST['NomorUrut'];
		$KodeJenis = $_POST['KodeJenis'];
		$Halaman = $_POST['Halaman'];
		$isema = $_POST['isema'];
		$Tahun = $_POST['Tahun'];

		$NomorSurat = $NomorUrut."/".$KodeJenis."/".$Halaman."/".$isema."-".$Tahun;
		$TanggalSurat = $_POST['TanggalSurat'];
		$Perihal = $_POST['Perihal'];
		$Ditujukan = $_POST['Ditujukan'];
		$Keterangan = $_POST['Keterangan'];
		switch ($aksi) {
			case 'insert':
				try {
					$Cek = $_FILES['FileSurat']['error'];
					if($Cek == 0){
						$FileName = $_FILES['FileSurat']['name'];
						$Type = $_FILES['FileSurat']['type'];
						$FileTmp = $_FILES['FileSurat']['tmp_name'];
						$UploadTo = "../../Files/SuratKeluar/";
						$NameFile = rand(0,999).time().".pdf";
						$filter = array("application/pdf");
						$Upload = move_uploaded_file($FileTmp,$UploadTo.$NameFile) ? true : false;
						if($Upload){
							
							$sql = "INSERT INTO tb_surat_keluar SET  IdJenisSurat = :IdJenisSurat,  NomorSurat = :NomorSurat, TglCreate = :TglCreate, TanggalSurat = :TanggalSurat, Perihal = :Perihal, Ditujukan = :Ditujukan, Keterangan = :Keterangan, UserUpdate = :UserUpdate, FileSurat = :FileSurat";
							$query = $db->prepare($sql);
							$query->bindParam("IdJenisSurat", $IdJenisSurat);
							$query->bindParam("NomorSurat", $NomorSurat);
							$query->bindParam("TglCreate", $date);
							$query->bindParam("TanggalSurat", $TanggalSurat);
							$query->bindParam("Perihal", $Perihal);
							$query->bindParam("Ditujukan", $Ditujukan);
							$query->bindParam("Keterangan", $Keterangan);
							$query->bindParam("UserUpdate", $UserUpdate);
							$query->bindParam("FileSurat", $NameFile);
							$query->execute();
						}else{
							echo "gagal ok".$Type;
							exit();
						}
						//echo $fil;
					}else{
						$sql = "INSERT INTO tb_surat_keluar SET  IdJenisSurat = :IdJenisSurat,  NomorSurat = :NomorSurat, TglCreate = :TglCreate, TanggalSurat = :TanggalSurat, Perihal = :Perihal, Ditujukan = :Ditujukan, Keterangan = :Keterangan, UserUpdate = :UserUpdate";
						$query = $db->prepare($sql);
						$query->bindParam("IdJenisSurat", $IdJenisSurat);
						$query->bindParam("NomorSurat", $NomorSurat);
						$query->bindParam("TglCreate", $date);
						$query->bindParam("TanggalSurat", $TanggalSurat);
						$query->bindParam("Perihal", $Perihal);
						$query->bindParam("Ditujukan", $Ditujukan);
						$query->bindParam("Keterangan", $Keterangan);
						$query->bindParam("UserUpdate", $UserUpdate);
						$query->execute();
					}
					$Pesan = "<b>".$_SESSION['NamaUser']."</b>, Berhasil Menambah Data Surat Keluar!";
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
						$UploadTo = "../../Files/SuratKeluar/";
						
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
							$sql = "UPDATE tb_surat_keluar SET   TglUpdate = :TglUpdate, TanggalSurat = :TanggalSurat, Perihal = :Perihal, Ditujukan = :Ditujukan, Keterangan = :Keterangan, UserUpdate = :UserUpdate, FileSurat = :FileSurat WHERE IdSuratKeluar = :IdSuratKeluar";
							$query = $db->prepare($sql);
							$query->bindParam("TglUpdate", $date);
							$query->bindParam("TanggalSurat", $TanggalSurat);
							$query->bindParam("Perihal", $Perihal);
							$query->bindParam("Ditujukan", $Ditujukan);
							$query->bindParam("Keterangan", $Keterangan);
							$query->bindParam("UserUpdate", $UserUpdate);
							$query->bindParam("FileSurat", $NameFile);
							$query->bindParam("IdSuratKeluar", $IdSuratKeluar);
							$query->execute();
						}else{
							print_r($_FILES);
							exit();
						}
					}else{
						$sql = "UPDATE tb_surat_keluar SET  TglUpdate = :TglUpdate, TanggalSurat = :TanggalSurat, Perihal = :Perihal, Ditujukan = :Ditujukan, Keterangan = :Keterangan, UserUpdate = :UserUpdate WHERE IdSuratKeluar = :IdSuratKeluar";
						$query = $db->prepare($sql);
						$query->bindParam("TglUpdate", $date);
						$query->bindParam("TanggalSurat", $TanggalSurat);
						$query->bindParam("Perihal", $Perihal);
						$query->bindParam("Ditujukan", $Ditujukan);
						$query->bindParam("Keterangan", $Keterangan);
						$query->bindParam("UserUpdate", $UserUpdate);
						$query->bindParam("IdSuratKeluar", $IdSuratKeluar);
						$query->execute();
					}
					$Pesan = "<b>".$_SESSION['NamaUser']."</b>, Berhasil Mengubah Data Surat Keluar!";
					AddLog($_SESSION['IdUser'],$Pesan);
					if($query){ echo "sukses"; exit(); } else { echo "gagals"; }
				} catch (PDOException $e) {
					echo $e->getMessage();
				}
				break;
			case 'delete':
				$Dir = "../../Files/SuratKeluar/";
				if(!empty($TmpFile) && file_exists($Dir.$TmpFile)){
					unlink($Dir.$TmpFile);
				}
				$sql = "DELETE FROM tb_surat_keluar WHERE IdSuratKeluar = :IdSuratKeluar";
				$query = $db->prepare($sql);
				$query->bindParam("IdSuratKeluar", $IdSuratKeluar);
				$query->execute();
				$Pesan = "<b>".$_SESSION['NamaUser']."</b>, Berhasil Menghapus Surat Keluar!";
					AddLog($_SESSION['IdUser'],$Pesan);
				if($query){ echo "sukses"; exit(); } else { echo "gagal"; }
				break;
		}
		break;

	case 'ShowData':
		$IdSuratKeluar = $_POST['IdSuratKeluar'];
		$sql = "SELECT a.*, b.Kode, b.NamaJenis FROM tb_surat_keluar a INNER JOIN tb_jenis_surat_keluar b ON a.IdJenisSurat = b.IdJenisSurat WHERE a.IdSuratKeluar = :IdSuratKeluar";
		$stmt = $db->prepare($sql);
		$stmt->bindParam('IdSuratKeluar', $IdSuratKeluar);
		$stmt->execute();
		$res = $stmt->fetch(PDO::FETCH_ASSOC);
		$expl = explode("/",$res['NomorSurat']);
		$thn = explode("-",$expl[3]);

		$res['NomorUrut'] = $expl[0];
		$res['KodeJenis'] = $expl[1];
		$res['Halaman'] = $expl[2];
		$res['Tahun'] = $thn[1];
		echo json_encode($res);
		break;
	
	case "GetNomorSurat":
		$data = array();
		$Tahun = $_POST['Tahun'];
		$IdJenisSurat = $_POST['IdJenisSurat'];
		$sql = "SELECT NomorSurat FROM tb_surat_keluar WHERE IdJenisSurat = :IdJenisSurat AND DATE_FORMAT(TanggalSurat,'%Y') = :Tahun ORDER BY IdSuratKeluar DESC LIMIT 1";
		$query = $db->prepare($sql);
		$query->bindParam('IdJenisSurat', $IdJenisSurat);
		$query->bindParam('Tahun', $Tahun);
		$query->execute();
		$row = $query->rowCount();
		$res = $query->fetch(PDO::FETCH_ASSOC);
		$Nomor = explode("/",$res['NomorSurat']);

		if($row > 0){
			$NomorUrutNow = $Nomor[0];
			$HalamanNow = $Nomor[2];
			if($NomorUrutNow < 20){
				$data['NomorUrut'] = $NomorUrutNow +1;
				$data['Halaman'] = $HalamanNow;
			}else{
				$data['NomorUrut'] = 1;
				$data['Halaman'] = $HalamanNow + 1;
				
			}
		}else{
			$data['NomorUrut'] = 1;
			$data['Halaman'] = 1;
		}
		$data['Tahun'] = $Tahun;

		echo json_encode($data);
		break;
}

?>
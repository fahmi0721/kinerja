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
		$sql = "SELECT * FROM tb_template_invoice ORDER BY IdTemplateInvoice";
		$query = $db->query($sql);
		$JumRow = $query->rowCount();
		if($JumRow > 0){
			while ($res = $query->fetch(PDO::FETCH_ASSOC)) { 
				$aksi = $_SESSION['Level'] == "author" ? "-" : "<a class='btn btn-xs btn-primary' data-toggle='tooltip' title='Ubah Data' onclick=\"Crud('".$res['IdTemplateInvoice']."', 'ubah')\"><i class='fa fa-edit'></i></a> <a class='btn btn-xs btn-danger' data-toggle='tooltip' title='Hapus Data' onclick=\"Crud('".$res['IdTemplateInvoice']."', 'hapus','".$res['FileTemplate']."')\"><i class='fa fa-trash-o'></i></a>";
				
				$row['No'] = $no;
				$row['JudulTemplate'] = $res['JudulTemplate'];
				$row['Keterangan'] = $res['Keterangan'];
				$row['TglCreate'] = tgl_indo($res['TglCreate']);
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
		$IdTemplateInvoice = $_POST['IdTemplateInvoice'];
		$TmpFiles = $_POST['TmpFile'];
		$JudulTemplate = $_POST['JudulTemplate'];
		$Keterangan = $_POST['Keterangan'];
		switch ($aksi) {
			case 'insert':
				try {
					$NamaFile = "-";
					if($_FILES['FileTemplate']['error'] == 0){
						$NM = $_FILES['FileTemplate']['name'];
						$TmpFile = $_FILES['FileTemplate']['tmp_name'];
						/** Extnsi */
						$extensi = explode(".",$NM);
						$extensi = end($extensi);
						$extensi = strtolower($extensi);


						$NamaFile = time().rand(0,999).".php";
						$filter = array("php");
						$UploadTo = "../../Cetak/TemplateInvoice/";
						$upload = UploadFile($NamaFile, $TmpFile, $UploadTo, $extensi, $filter);
						if(!$upload){
							echo "filenotsupport";
							exit();
						}
						
						
					}
					
					
					$sql = "INSERT INTO  tb_template_invoice SET  JudulTemplate = :JudulTemplate,  FileTemplate = :FileTemplate, Keterangan = :Keterangan, TglCreate = :TglCreate, UserUpdate = :UserUpdate";
					$query = $db->prepare($sql);
					$query->bindParam("JudulTemplate", $JudulTemplate);
					$query->bindParam("FileTemplate", $NamaFile);
					$query->bindParam("Keterangan", $Keterangan);
					$query->bindParam("TglCreate", $date);
					$query->bindParam("UserUpdate", $UserUpdate);
					$query->execute();
					$Pesan = "<b>".$_SESSION['NamaUser']."</b>, Berhasil Menambah Data Template Invoice!";
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
					$NamaFile = "";
					if($_FILES['FileTemplate']['error'] == 0){
						$UploadTo = "../../Cetak/TemplateInvoice/";
						
						$filters = ",FileTemplate = :FileTemplate";
						$NM = $_FILES['FileTemplate']['name'];
						$TmpFile = $_FILES['FileTemplate']['tmp_name'];
						/** Extnsi */
						$extensi = explode(".",$NM);
						$extensi = end($extensi);
						$extensi = strtolower($extensi);


						$NamaFile = time().rand(0,999).".php";
						$filter = array("php");
						$upload = UploadFile($NamaFile, $TmpFile, $UploadTo, $extensi, $filter);
						if(!$upload){
							echo "filenotsupport";
							exit();
						}
						HapusFile($TmpFiles,$UploadTo);
					}else{
						$filters = "";

					}
						
					$sql = "UPDATE tb_template_invoice SET  JudulTemplate = :JudulTemplate,  FileTemplate = :FileTemplate, Keterangan = :Keterangan, TglUpdate = :TglUpdate, UserUpdate = :UserUpdate WHERE IdTemplateInvoice = :IdTemplateInvoice";
					$query = $db->prepare($sql);
					$query->bindParam("JudulTemplate", $JudulTemplate);
					$query->bindParam("FileTemplate", $NamaFile);
					$query->bindParam("Keterangan", $Keterangan);
					$query->bindParam("TglUpdate", $date);
					$query->bindParam("UserUpdate", $UserUpdate);
					if($_FILES['FileTemplate']['error'] == 0){
						$query->bindParam("FileTemplate", $NamaFile);
					}
					$query->bindParam("IdTemplateInvoice", $IdTemplateInvoice);
					$query->execute();
					$Pesan = "<b>".$_SESSION['NamaUser']."</b>, Berhasil Mengubah Data Template Invoice!";
					AddLog($_SESSION['IdUser'],$Pesan);
					if($query){ echo "sukses"; exit(); } else { echo "gagal"; }
				} catch (PDOException $e) {
					echo $e->getMessage();
				}
				break;
			case 'delete':
				$UploadTo = "../../Cetak/TemplateInvoice/";
				HapusFile($TmpFiles,$UploadTo);
				$sql = "DELETE FROM tb_template_invoice WHERE IdTemplateInvoice = :IdTemplateInvoice";
				$query = $db->prepare($sql);
				$query->bindParam("IdTemplateInvoice", $IdTemplateInvoice);
				$query->execute();
				$Pesan = "<b>".$_SESSION['NamaUser']."</b>, Berhasil Menghapus Data Template Invoice!";
					AddLog($_SESSION['IdUser'],$Pesan);
				if($query){ echo "sukses"; exit(); } else { echo "gagal"; }
				break;
		}
		break;

	case 'ShowData':
		$IdTemplateInvoice = $_POST['IdTemplateInvoice'];
		$sql = "SELECT * FROM tb_template_invoice WHERE IdTemplateInvoice = '$IdTemplateInvoice'";
		$stmt = $db->prepare($sql);
		$stmt->bindParam('IdTemplateInvoice', $IdTemplateInvoice);
		$stmt->execute();
		$res = $stmt->fetch(PDO::FETCH_ASSOC);
		echo json_encode($res);
		break;
	
}

?>
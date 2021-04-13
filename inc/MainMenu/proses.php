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
		$sql = "SELECT * FROM tb_menu ORDER BY IdMenu DESC";
		$query = $db->query($sql);
		$JumRow = $query->rowCount();
		if($JumRow > 0){
			while ($res = $query->fetch(PDO::FETCH_ASSOC)) { 
				$Status = $res['Status'] == "0" ? "<button class='btn btn-xs btn-danger' onclick=\"UpdateStatus('".$res['IdMenu']."','1')\" type='button'><i class='fa fa-lock'></i></button>" : "<button  onclick=\"UpdateStatus('".$res['IdMenu']."','0')\" class='btn btn-xs btn-success' type='button'><i class='fa fa-unlock'></i></button>";
				$row['No'] = $no;
				$row['Direktori'] = $res['Direktori'];
				$row['NamaMenu'] = $res['NamaMenu'];
				$row['Position'] = $res['Position'];
				$row['Status'] = $Status;
				$row['Icon'] = "<i class='fa ".$res['Icon']."'></i>";
				$row['Aksi'] = "<a class='btn btn-xs btn-primary' data-toggle='toolltip' title='Ubah Data' onclick=\"Crud('".$res['IdMenu']."', 'ubah')\"><i class='fa fa-edit'></i></a> <a class='btn btn-xs btn-danger' data-toggle='toolltip' title='Hapus Data' onclick=\"Crud('".$res['IdMenu']."', 'hapus')\"><i class='fa fa-trash-o'></i></a>";
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
		$IdMenu = $_POST['IdMenu'];
		$NamaMenu = $_POST['NamaMenu'];
		$Direktori = $_POST['Direktori'];
		$Icon = $_POST['Icon'];
		$Position = $_POST['Position'];
		switch ($aksi) {
			case 'insert':
				try {
					$cek = $db->query("SELECT COUNT(IdMenu) as tot FROM tb_menu WHERE Direktori = '$Direktori'")->fetch(PDO::FETCH_ASSOC);
					if($cek['tot'] > 0){
						echo "ada";
						exit();
					}
					$sql = "INSERT INTO tb_menu SET  NamaMenu = :NamaMenu,  Direktori = :Direktori, Icon = :Icon,  TglCreate = :TglCreate, Position = :Position, UserUpdate = :UserUpdate";
					$query = $db->prepare($sql);
					$query->bindParam("NamaMenu", $NamaMenu);
					$query->bindParam("Direktori", $Direktori);
					$query->bindParam("Icon", $Icon);
					$query->bindParam("TglCreate", $date);
					$query->bindParam("Position", $Position);
					$query->bindParam("UserUpdate", $UserUpdate);
					$query->execute();
					$Pesan = "<b>".$_SESSION['NamaUser']."</b>, Berhasil Menambahkan Data Menu!";
					AddLog($_SESSION['IdUser'],$Pesan);
					echo "sukses";
					exit();
				} catch (PDOException $e) {
					echo $e->getMessage();
					exit();
				}

				break;
			case 'update':
				try {
					$sql = "UPDATE tb_menu SET NamaMenu = :NamaMenu,  Direktori = :Direktori, Icon = :Icon,  Position = :Position, TglUpdate = :TglUpdate, UserUpdate = :UserUpdate WHERE IdMenu = :IdMenu";
					$query = $db->prepare($sql);
					$query->bindParam("NamaMenu", $NamaMenu);
					$query->bindParam("Direktori", $Direktori);
					$query->bindParam("Icon", $Icon);
					$query->bindParam("Position", $Position);
					$query->bindParam("TglUpdate", $date);
					$query->bindParam("UserUpdate", $UserUpdate);
				
					$query->bindParam("IdMenu", $IdMenu);
					$Pesan = "<b>".$_SESSION['NamaUser']."</b>, Berhasil Mangubah Data Menu!";
					AddLog($_SESSION['IdUser'],$Pesan);
					$query->execute();
					echo "sukses";
				} catch (PDOException $e) {
					echo $e->getMessage();
				}
				break;
			case 'delete':
				$sql = "DELETE FROM tb_menu WHERE IdMenu = :IdMenu";
				$query = $db->prepare($sql);
				$query->bindParam("IdMenu", $IdMenu);
				$query->execute();
				$Pesan = "<b>".$_SESSION['NamaUser']."</b>, Berhasil Menghapus Data Menu!";
				AddLog($_SESSION['IdUser'],$Pesan);
				if($query){ echo "sukses"; exit(); } else { echo "gagal"; }
				break;
		}
		break;

	case 'ShowData':
		$IdMenu = $_POST['IdMenu'];
		$sql = "SELECT * FROM tb_menu WHERE IdMenu = :IdMenu";
		$stmt = $db->prepare($sql);
		$stmt->bindParam('IdMenu', $IdMenu);
		$stmt->execute();
		$res = $stmt->fetch(PDO::FETCH_ASSOC);
		echo json_encode($res);
		break;
	case 'GetParentMenu':
		$data = array();
		$dt = array();
		$sql = "SELECT NamaMenu, IdMenu FROM tb_menu GROUP BY IdMenu ORDER BY IdMenu DESC";
		$query = $db->query($sql);
		$row = $query->rowCount();
		while($res = $query->fetch(PDO::FETCH_ASSOC)){
			$dt['IdMenu'] = $res['IdMenu'];
			$dt['NamaMenu'] = $res['NamaMenu'];
			$data['item'][] = $dt;

		}
		$data['row'] = $row;
		echo json_encode($data);
		break;
	case "UpdateMenu" :
		$IdMenu = $_POST['IdMenu'];
		$Status = $_POST['Status'];
		$sql = "UPDATE tb_menu SET Status = :Status WHERE IdMenu = :IdMenu";
		$query = $db->prepare($sql);
		$query->bindParam('Status', $Status);
		$query->bindParam('IdMenu', $IdMenu);
		$query->execute();
		if($query){ echo "sukses"; }else{ echo "gagal"; }
		break;
}

?>
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
		$sql = "SELECT * FROM tb_users ORDER BY IdUser DESC";
		$query = $db->query($sql);
		$JumRow = $query->rowCount();
		if($JumRow > 0){
			while ($res = $query->fetch(PDO::FETCH_ASSOC)) { 
				$aksi = $_SESSION['Level'] == "author" ? "-" : "<a class='btn btn-xs btn-primary' data-toggle='toolltip' title='Ubah Data' onclick=\"Crud('".$res['IdUser']."', 'ubah')\"><i class='fa fa-edit'></i></a> <a class='btn btn-xs btn-danger' data-toggle='toolltip' title='Hapus Data' onclick=\"Crud('".$res['IdUser']."', 'hapus')\"><i class='fa fa-trash-o'></i></a>";
				$row['No'] = $no;
				$row['NamaUser'] = $res['NamaUser'];
				$row['Username'] = $res['UserName'];
				$row['Level'] = $res['Level'];
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
		$IdUser = $_POST['IdUser'];
		$Username = $_POST['Username'];
		$NamaUser = $_POST['NamaUser'];
		$Password = !empty($_POST['Password']) ? md5("isma".$_POST['Password']) : "";
		$Level = $_POST['Level'];
		switch ($aksi) {
			case 'insert':
				try {
					$cek = $db->query("SELECT COUNT(IdUser) as tot FROM tb_users WHERE UserName = '$Username'")->fetch(PDO::FETCH_ASSOC);
					if($cek['tot'] > 0){
						echo "ada";
						exit();
					}
					$sql = "INSERT INTO tb_users SET  UserName = :Username,  NamaUser = :NamaUser, TglCreate = :TglCreate, Password = :Password, Level = :Level, UserUpdate = :UserUpdate";
					$query = $db->prepare($sql);
					$query->bindParam("Username", $Username);
					$query->bindParam("NamaUser", $NamaUser);
					$query->bindParam("TglCreate", $date);
					$query->bindParam("Password", $Password);
					$query->bindParam("Level", $Level);
					$query->bindParam("UserUpdate", $UserUpdate);
					$query->execute();
					if($query){
						$IdUser = $db->lastInsertId();
						for ($i=0; $i < COUNT($_POST['Menu']) ; $i++) { 
							$IdMenu = $_POST['Menu'][$i];
							$sqlMenu = "INSERT INTO tb_menu_akses SET IdMenu ='$IdMenu', Iduser = '$IdUser', TglCreate = '$date', UserUpdate = '$UserUpdate'";
							$queryMenu = $db->query($sqlMenu);
						}
					}
					$Pesan = "<b>".$_SESSION['NamaUser']."</b>, Berhasil Menambah Data User!";
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
					$filter = !empty($Password) ? ", Password = :Password" : "";
					$sql = "UPDATE tb_users SET Username = :Username,  NamaUser = :NamaUser, Level = :Level, TglUpdate = :TglUpdate, UserUpdate = :UserUpdate $filter WHERE IdUser = :IdUser";
					$query = $db->prepare($sql);
					$query->bindParam("Username", $Username);
					$query->bindParam("NamaUser", $NamaUser);
					$query->bindParam("Level", $Level);
					$query->bindParam("TglUpdate", $date);
					$query->bindParam("UserUpdate", $UserUpdate);
					if(!empty($Password)){
						$query->bindParam("Password", $Password);
					}
					$query->bindParam("IdUser", $IdUser);
					
					$query->execute();
					if($query){
						$db->query("DELETE FROM tb_menu_akses WHERE IdUser = '$IdUser'");
						for ($i=0; $i < COUNT($_POST['Menu']) ; $i++) { 
							$IdMenu = $_POST['Menu'][$i];
							$sqlMenu = "INSERT INTO tb_menu_akses SET IdMenu ='$IdMenu', Iduser = '$IdUser', TglCreate = '$date', UserUpdate = '$UserUpdate'";
							$queryMenu = $db->query($sqlMenu);
						}
					}
					$Pesan = "<b>".$_SESSION['NamaUser']."</b>, Berhasil Mengubah Data User!";
					AddLog($_SESSION['IdUser'],$Pesan);
					echo "sukses";
				} catch (PDOException $e) {
					echo $e->getMessage();
				}
				break;
			case 'delete':
				$sql = "DELETE FROM tb_users WHERE IdUser = :IdUser";
				$query = $db->prepare($sql);
				$query->bindParam("IdUser", $IdUser);
				$query->execute();
				$Pesan = "<b>".$_SESSION['NamaUser']."</b>, Berhasil Menghapus Data User!";
					AddLog($_SESSION['IdUser'],$Pesan);
				if($query){ echo "sukses"; exit(); } else { echo "gagal"; }
				break;
		}
		break;

	case 'ShowData':
		$IdUser = $_POST['IdUser'];
		$sql = "SELECT * FROM tb_users WHERE IdUser = :IdUser";
		$stmt = $db->prepare($sql);
		$stmt->bindParam('IdUser', $IdUser);
		$stmt->execute();
		$res = $stmt->fetch(PDO::FETCH_ASSOC);
		echo json_encode($res);
		break;
	case  "LoadAksesMenu" :
		$IdUser = empty($_POST['IdUser']) ? "" : $_POST['IdUser'];
		$listIdMenu = array();
		if(!empty($IdUser)){
			$query = $db->query("SELECT IdMenu FROM tb_menu_akses WHERE IdUser = '$IdUser'");
			$row = $query->rowCount();
			if($row > 0){
				while($dt = $query->fetch(PDO::FETCH_ASSOC)){
					$listIdMenu[] = $dt['IdMenu'];
				}
			}
		}
		$sqlParent = "SELECT NamaMenu, IdMenu FROM tb_menu WHERE Position = 'item-root'";
		$queryParent = $db->query($sqlParent);
		$data="";
		while($resultParent = $queryParent->fetch(PDO::FETCH_ASSOC)){
			$checked = in_array($resultParent['IdMenu'], $listIdMenu) ? "checked" : "";
			$data .= "<div class='col-sm-4'>";
				$data .="<label class='checkbox'>";
					$data .= "<input type='checkbox' class='Menu' $checked name='Menu[]' onclick='PilihMenu($resultParent[IdMenu])' id='Menu$resultParent[IdMenu]' value='$resultParent[IdMenu]'> $resultParent[NamaMenu]";
					$sql = "SELECT NamaMenu, IdMenu FROM tb_menu WHERE Position = '$resultParent[NamaMenu]'";
					$query = $db->query($sql);
					while($result = $query->fetch(PDO::FETCH_ASSOC)){
						$checked = in_array($result['IdMenu'], $listIdMenu) ? "checked" : "";
						$data .="<label class='checkbox'>";
						$data .= "<input type='checkbox' $checked class='Menu$resultParent[IdMenu] CountMenu Menu' onclick='PilihSubMenu($resultParent[IdMenu])' name='Menu[]' id='Menu$result[IdMenu]' value='$result[IdMenu]'> $result[NamaMenu]";
						$data .= "</label>";
					}
				$data .= "</label>";
			$data .= "</div>";
		}
		echo $data;
		break;	
	
	
}

?>
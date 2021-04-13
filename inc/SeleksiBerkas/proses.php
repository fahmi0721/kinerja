<?php
session_start();
include_once '../../config/config.php';

$UserUpdate = $_SESSION['id'];
$date = date("Y-m-d H:i:s");
$proses = isset($_GET['proses']) ? $_GET['proses'] : "";
switch ($proses) {
	case 'DetailData': 
			$data = array();
			$IdLowongan = isset($_GET['IdLowongan']) ? $_GET['IdLowongan'] : "";
			if($IdLowongan != ""){
				$iFilter = " WHERE IdLowongan = '".$IdLowongan."'";
				$Pendidikan ="";
				if(!empty($_GET['Pendidikan']) AND is_array($_GET['Pendidikan'])){
					$pend = $_GET['Pendidikan'];
					$Pendidikan = "(";
					for ($i=0; $i < count($pend); $i++) { 
						if($i==0){
							$Pendidikan .= "'".$pend[$i]."'";
						}else{
							$Pendidikan .= ", '".$pend[$i]."'";
						}
					}
					$Pendidikan.= ")";
				}
				$iPendidikan = $Pendidikan != "" ? " AND Pendidikan IN ".$Pendidikan : "";
				$iUsia = !empty($_GET['Usia']) ? " AND TIMESTAMPDIFF(YEAR, TglLahir, CURDATE()) <= ".$_GET['Usia'] : "";
				$sql = "SELECT *, TIMESTAMPDIFF(YEAR, TglLahir,CURDATE()) as Usia FROM tb_pelamar ";
				$sql .= $iFilter.$iPendidikan.$iUsia;
				$sql .= " ORDER BY Nama ASC";
			}else{
				$Pendidikan ="";
				if(!empty($_GET['Pendidikan']) AND is_array($_GET['Pendidikan'])){
					$pend = $_GET['Pendidikan'];
					$Pendidikan = "(";
					for ($i=0; $i < count($pend); $i++) { 
						if($i==0){
							$Pendidikan .= "'".$pend[$i]."'";
						}else{
							$Pendidikan .= ", '".$pend[$i]."'";
						}
					}
					$Pendidikan.= ")";
				}
				if($Pendidikan != "" AND !empty($_GET['Usia'])){
					$iFilter = "WHERE Pendidikan IN ".$Pendidikan. " AND TIMESTAMPDIFF(YEAR, TglLahir,CURDATE()) <= " .$_GET['Usia'];
				}elseif($Pendidikan != ""){
					$iFilter = "WHERE Pendidikan IN ".$Pendidikan;
				}elseif(!empty($_GET['Usia'])){
					$iFilter = " WHERE TIMESTAMPDIFF(YEAR, TglLahir, CURDATE()) <= ".$_GET['Usia'];
				}
				
				$sql = "SELECT *, TIMESTAMPDIFF(YEAR, TglLahir,CURDATE()) as Usia FROM tb_pelamar ";
				$sql .= $iFilter;
				$sql .= " ORDER BY Nama ASC";
			}
			//echo $sql;
			$query = $db->query($sql);
			$rows = $query->rowCount();
			$row = array();
			$no=1;
			if($rows > 0){
				while($dt = $query->fetch(PDO::FETCH_ASSOC)){
					$DataBerkas = explode("#", $dt['Berkas']);
					if(!empty($_GET['Berkas']) && is_array($_GET['Berkas'])){
						$CekBerkas = SerachBerkas($_GET['Berkas'],$DataBerkas);
						if($CekBerkas == 1){
							$Berkas = "<ul>";
							foreach ($DataBerkas as $berkas) {
								$Berkas .= "<li>".$berkas."</li>";
							}
							$Berkas .= "</ul>";
							$data['No']   = $no;
							$data['NoLamaran'] = $dt['NoLamaran'];
							$data['Nama'] = $dt['Nama'];
							$data['NoKTP'] = $dt['NoKTP'];
							$data['TTL'] = $dt['TempatLahir'].", ".tgl_indo($dt['TglLahir']);
							$data['Usia'] = $dt['Usia']. " Tahun";
							$data['Agama'] = $dt['Agama'];
							$data['NoTelp'] = $dt['NoTelp'];
							$data['Pendidikan'] = $dt['Pendidikan']." / ".$dt['Universitas'];
							$data['Alamat'] = $dt['Alamat'];
							$data['Berkas'] = $Berkas;
							$data['Pilih'] = '-';
							$row['data'][] = $data;
							$no++;
						}
					}else{
						$Berkas = "<ul>";
						foreach ($DataBerkas as $berkas) {
							$Berkas .= "<li>".$berkas."</li>";
						}
						$Berkas .= "</ul>";
						$data['No']   = $no;
						$data['NoLamaran'] = $dt['NoLamaran'];
						$data['Nama'] = $dt['Nama'];
						$data['NoKTP'] = $dt['NoKTP'];
						$data['TTL'] = $dt['TempatLahir'].", ".tgl_indo($dt['TglLahir']);
						$data['Usia'] = $dt['Usia']. " Tahun";
						$data['Agama'] = $dt['Agama'];
						$data['NoTelp'] = $dt['NoTelp'];
						$data['Pendidikan'] = $dt['Pendidikan']." / ".$dt['Universitas'];
						$data['Alamat'] = $dt['Alamat'];
						$data['Berkas'] = $Berkas;
						$data['Pilih'] = '-';
						$row['data'][] = $data;
						$no++;
					}
				}
			}else{
				$row['data']="";
			}
			echo json_encode($row);
		break;
	case 'Crud':
		$aksi = $_POST['aksi'];
		$IdLowongan = $_POST['IdLowongan'];
		$NamaLowongan = $_POST['NamaLowongan'];
		$Penempatan = $_POST['Penempatan'];
		$TglBuka = $_POST['TglBuka'];
		$TglTutup = $_POST['TglTutup'];
		$Keterangan = $_POST['Keterangan'];
		switch ($aksi) {
			case 'insert':
				try {
					$sql = "INSERT INTO tb_lowongan SET NamaLowongan = :NamaLowongan, Penempatan = :Penempatan, TglBuka = :TglBuka, TglTutup = :TglTutup, Keterangan = :Keterangan, TglCreate = :TglCreate, UserUpdate = :UserUpdate";
					$query = $db->prepare($sql);
					$query->bindParam("NamaLowongan", $NamaLowongan);
					$query->bindParam("Penempatan", $Penempatan);
					$query->bindParam("TglBuka", $TglBuka);
					$query->bindParam("TglTutup", $TglTutup);
					$query->bindParam("Keterangan", $Keterangan);
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
					$sql = "UPDATE tb_lowongan SET NamaLowongan = :NamaLowongan, Penempatan = :Penempatan, TglBuka = :TglBuka, TglTutup = :TglTutup, Keterangan = :Keterangan, TglUpdate = :TglUpdate, UserUpdate = :UserUpdate WHERE IdLowongan = :IdLowongan";
					$query = $db->prepare($sql);
					$query->bindParam("NamaLowongan", $NamaLowongan);
					$query->bindParam("Penempatan", $Penempatan);
					$query->bindParam("TglBuka", $TglBuka);
					$query->bindParam("TglTutup", $TglTutup);
					$query->bindParam("Keterangan", $Keterangan);
					$query->bindParam("TglUpdate", $date);
					$query->bindParam("UserUpdate", $UserUpdate);
					$query->bindParam("IdLowongan", $IdLowongan);
					$query->execute();
					echo "sukses";
				} catch (PDOException $e) {
					echo $e->getMessage();
				}
				break;
			case 'delete':
				$sql = "DELETE FROM tb_lowongan WHERE IdLowongan = :IdLowongan";
				$query = $db->prepare($sql);
				$query->bindParam("IdLowongan", $IdLowongan);
				$query->execute();
				if($query){ echo "sukses"; exit(); } else { echo "gagal"; }
				break;
			
		}
		break;
	
	case 'GetPendidikan' : 
		$IdLowongan = $_POST['IdLowongan'];
		$filter = $IdLowongan != "nodata" ? "WHERE IdLowongan = '".$IdLowongan."'" : "";
		$sql = "SELECT Pendidikan FROM tb_pelamar ".$filter." GROUP BY Pendidikan";
		$query = $db->query($sql);
		$data=array();
		while($res = $query->fetch(PDO::FETCH_ASSOC)){
			$data[] = $res['Pendidikan'];
		}
		echo json_encode($data);
		break;
	
	case 'GetBerkas' :
		$IdLowongan = $_POST['IdLowongan'];
		$filter = $IdLowongan != "nodata" ? "WHERE IdLowongan = '".$IdLowongan."'" : "";
		$sql = "SELECT Berkas FROM tb_pelamar ".$filter." ORDER BY IdPelamar ASC";
		$query = $db->query($sql);
		$ListBerkas = array();
		while($result = $query->fetch(PDO::FETCH_ASSOC)){
			$DataBerkas = explode("#", $result['Berkas']);
			foreach ($DataBerkas as $berkas) {
				if(!in_array($berkas, $ListBerkas)){
					$ListBerkas[] = $berkas;
				}
			}
		}
		echo json_encode($ListBerkas);
		break;

	case 'GetLowongan':
		$IdLowongan = $_POST['IdLowongan'];
		$sql = "SELECT * FROM tb_lowongan WHERE IdLowongan = :IdLowongan";
		$stmt = $db->prepare($sql);
		$stmt->bindParam('IdLowongan', $IdLowongan);
		$stmt->execute();
		$res = $stmt->fetch(PDO::FETCH_ASSOC);
		echo json_encode($res);
		break;

	case 'ShowData':
		$IdLowongan = $_POST['IdLowongan'];
		$sql = "SELECT * FROM tb_lowongan WHERE IdLowongan = :IdLowongan";
		$stmt = $db->prepare($sql);
		$stmt->bindParam('IdLowongan', $IdLowongan);
		$stmt->execute();
		$res = $stmt->fetch(PDO::FETCH_ASSOC);
		echo json_encode($res);
		break;
	
	
}

?>
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
		$sql = "SELECT tb_cabang.NamaCabang, tb_cabang.IdCabang, tb_skema_gaji.GajiPokok FROM tb_skema_gaji INNER JOIN tb_cabang ON tb_skema_gaji.IdCabang = tb_cabang.IdCabang GROUP BY IdCabang  ORDER BY IdSkemaGaji DESC";
		$query = $db->query($sql);
		$JumRow = $query->rowCount();
		if($JumRow > 0){
			while ($res = $query->fetch(PDO::FETCH_ASSOC)) { 
				$qr = $db->query("SELECT COUNT(a.IdSkemaGaji) as tot, b.NamaJabatan FROM tb_skema_gaji a INNER JOIN tb_jabatan b ON a.Jabatan = b.KodeJabatan WHERE a.IdCabang = '$res[IdCabang]' GROUP BY a.Jabatan ORDER BY b.NamaJabatan ASC");
				$Pjtk ="";
				$TotPjtk = 0;
				while($rs = $qr->fetch(PDO::FETCH_ASSOC)){
					$Pjtk .= "- ".$rs['NamaJabatan']." (".$rs['tot'].") <br>";
					$TotPjtk += $rs['tot'];
				}
				$Pjtk .= "<b>Total tenaga Kerja (".$TotPjtk.")</b>";
				$row['No'] = $no;
				$row['NamaCabang'] = $res['NamaCabang'];
				$row['UpahPokok'] = rupiah($res['GajiPokok'], "Rp. ");
				$row['JumlahTK'] = $Pjtk;
				$row['Aksi'] = "<a class='btn btn-xs btn-primary' data-toggle='tooltip' title='Detail Data' onclick=\"LoadDetailKaryawan('".$res['IdCabang']."')\"><i class='fa fa-eye'></i></a>";
				$data['data'][] = $row;
				$no++;
			}
		}else{
			$data['data']='';
		}

		echo json_encode($data);
		break;
	case 'DetailDataKaryawan':
		$row = array(); 
		$data = array(); 
		$no=1;
		$IdCabang = $_GET['IdCabang'];
		$sql = "SELECT a.IdSkemaGaji, b.NamaKaryawan, c.NamaJabatan, a.Upah FROM tb_skema_gaji a INNER JOIN tb_karyawan b ON a.IdKaryawan = b.IdKaryawan INNER JOIN tb_jabatan c ON a.Jabatan = c.KodeJabatan WHERE a.IdCabang = '$IdCabang' ORDER BY b.NamaKaryawan ASC";
		$query = $db->query($sql);
		$JumRow = $query->rowCount();
		if($JumRow > 0){
			while ($res = $query->fetch(PDO::FETCH_ASSOC)) {
				$aksi = "<a class='btn btn-xs btn-success' data-toggle='tooltip' title='Detail Data' onclick=\"DetailCabangKaryawan('".$res['IdSkemaGaji']."')\"><i class='fa fa-eye'></i></a> ";
				$aksi .= $_SESSION['Level'] == "author" ? "" : "<a class='btn btn-xs btn-primary' data-toggle='tooltip' title='Ubah Data' onclick=\"Crud('".$res['IdSkemaGaji']."', 'ubah')\"><i class='fa fa-edit'></i></a>  <a class='btn btn-xs btn-danger' data-toggle='tooltip' title='Hapus Data' onclick=\"Crud('".$res['IdSkemaGaji']."', 'hapus')\"><i class='fa fa-trash-o'></i></a>";
				$row['No'] = $no;
				$row['NamaKaryawan'] = $res['NamaKaryawan'];
				$row['Jabatan'] = $res['NamaJabatan'];
				$row['Upah'] = rupiah($res['Upah'],"Rp. ");
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
		
		$IdSkemaGaji = angka($_POST['IdSkemaGaji']);
		$IdKontrak = $_POST['IdKontrak'];
		$UpahPokok = angka($_POST['UpahPokok']);
		$UangMakanPersen = str_replace(",",".",$_POST['UangMakanPersen']);
		$UangMakanHari = angka($_POST['UangMakanHari']);
		$UangMakan = angka($_POST['UangMakan']);
		$UangTransportPersen = str_replace(",",".",$_POST['UangTransportPersen']);
		$UangTransportHari = angka($_POST['UangTransportHari']);
		$UangTransport = angka($_POST['UangTransport']);
		$Tunjangan = angka($_POST['Tunjangan']);
		$Upah = angka($_POST['Upah']);
		$BpjsKesPersen = str_replace(",",".",$_POST['BpjsKesPersen']);
		$BpjsKes = angka($_POST['BpjsKes']);
		$BpjsTkPersen = str_replace(",",".",$_POST['BpjsTkPersen']);
		$BpjsTk = angka($_POST['BpjsTk']);
		$PakaianKerjaPersen = str_replace(",",".",$_POST['PakaianKerjaPersen']);
		$PakaianKerja = angka($_POST['PakaianKerja']);
		$Thr = angka($_POST['Thr']);
		$Pesangon = angka($_POST['Pesangon']);
		$Dplk = angka($_POST['Dplk']);
		$JasaPjtkPersen = angka($_POST['JasaPjtkPersen']);
		$JasaPjtk = angka($_POST['JasaPjtk']);
		
		switch ($aksi) {
			case 'insert':
				try {
					$IdCabang = $_POST['IdCabang'];
					/** Jabatans */
					$Jabatans = $_POST['Jabatan'];
					foreach($Jabatans as $Jabatan){
						$sql = "SELECT IdKaryawan FROM tb_karyawan WHERE IdCabang = '$IdCabang' AND Jabatan = '$Jabatan'";
						$query = $db->query($sql);
						while($r = $query->fetch(PDO::FETCH_ASSOC)){
							$IdKaryawan = $r['IdKaryawan'];
							$sql1 = "INSERT INTO tb_skema_gaji SET IdCabang = :IdCabang, IdKontrak = :IdKontrak, IdKaryawan = :IdKaryawan, Jabatan = :Jabatan, GajiPokok = :GajiPokok, UangMakanPersen = :UangMakanPersen, UangMakanHari = :UangMakanHari, UangMakan = :UangMakan, UangTransportPersen = :UangTransportPersen, UangTransportHari = :UangTransportHari, UangTransport = :UangTransport, Tunjangan = :Tunjangan, Upah = :Upah, BpjsKesPersen = :BpjsKesPersen, BpjsKes = :BpjsKes, BpjsTkPersen = :BpjsTkPersen, BpjsTk = :BpjsTk, PakaianKerjaPersen  = :PakaianKerjaPersen, PakaianKerja = :PakaianKerja, Thr = :Thr, Pesangon = :Pesangon, Dplk = :Dplk, JasaPjtkPersen =:JasaPjtkPersen, JasaPjtk = :JasaPjtk, TglCreate = :TglCreate, UserUpdate = :UserUpdate";

							$query1 = $db->prepare($sql1);
							$query1->bindParam('IdCabang', $IdCabang);
							$query1->bindParam('IdKontrak', $IdKontrak);
							$query1->bindParam('IdKaryawan', $IdKaryawan);
							$query1->bindParam('Jabatan', $Jabatan);
							$query1->bindParam('GajiPokok', $UpahPokok);
							$query1->bindParam('UangMakanPersen', $UangMakanPersen);
							$query1->bindParam('UangMakanHari', $UangMakanHari);
							$query1->bindParam('UangMakan', $UangMakan);
							$query1->bindParam('UangTransportPersen', $UangTransportPersen);
							$query1->bindParam('UangTransportHari', $UangTransportHari);
							$query1->bindParam('UangTransport', $UangTransport);
							$query1->bindParam('Tunjangan', $Tunjangan);
							$query1->bindParam('Upah', $Upah);
							$query1->bindParam('BpjsKesPersen', $BpjsKesPersen);
							$query1->bindParam('BpjsKes', $BpjsKes);
							$query1->bindParam('BpjsTkPersen', $BpjsTkPersen);
							$query1->bindParam('BpjsTk', $BpjsTk);
							$query1->bindParam('PakaianKerjaPersen', $PakaianKerjaPersen);
							$query1->bindParam('PakaianKerja', $PakaianKerja);
							$query1->bindParam('Thr', $Thr);
							$query1->bindParam('Pesangon', $Pesangon);
							$query1->bindParam('Dplk', $Dplk);
							$query1->bindParam('JasaPjtkPersen', $JasaPjtkPersen);
							$query1->bindParam('JasaPjtk', $JasaPjtk);
							$query1->bindParam('TglCreate', $date);
							$query1->bindParam('UserUpdate', $UserUpdate);
							$query1->execute();
						}
					}
					$Pesan = "<b>".$_SESSION['NamaUser']."</b>, Berhasil Menambah Data Skema Gaji!";
					AddLog($_SESSION['IdUser'],$Pesan);
					if($query){ echo "sukses"; exit(); } else { echo "gagal"; }
					exit();
				} catch (PDOException $e) {
					echo $e->getMessage();
					exit();
				}

				break;
			case 'insertKhusus':
				try {
					$IdCabang = $_POST['IdCabang'];
					$Jabatan = $_POST['Jabatan'];
					$SkemaKhusus = !empty($_POST['SkemaKhusus']) ? $_POST['SkemaKhususDepan'].$_POST['SkemaKhusus'] : "";
					/** Jabatans */
					$IdKaryawans = $_POST['IdKaryawan'];
					foreach($IdKaryawans as $IdKaryawan){
						$sql1 = "INSERT INTO tb_skema_gaji SET IdCabang = :IdCabang, IdKaryawan = :IdKaryawan, Jabatan = :Jabatan, GajiPokok = :GajiPokok, UangMakanPersen = :UangMakanPersen, UangMakanHari = :UangMakanHari, UangMakan = :UangMakan, UangTransportPersen = :UangTransportPersen, UangTransportHari = :UangTransportHari, UangTransport = :UangTransport, Tunjangan = :Tunjangan, Upah = :Upah, BpjsKesPersen = :BpjsKesPersen, BpjsKes = :BpjsKes, BpjsTkPersen = :BpjsTkPersen, BpjsTk = :BpjsTk, PakaianKerjaPersen  = :PakaianKerjaPersen, PakaianKerja = :PakaianKerja, Thr = :Thr, Pesangon = :Pesangon, Dplk = :Dplk, JasaPjtkPersen =:JasaPjtkPersen, JasaPjtk = :JasaPjtk, TglCreate = :TglCreate, UserUpdate = :UserUpdate, SkemaKhusus = :SkemaKhusus";
						$query1 = $db->prepare($sql1);
						$query1->bindParam('IdCabang', $IdCabang);
						$query1->bindParam('IdKaryawan', $IdKaryawan);
						$query1->bindParam('Jabatan', $Jabatan);
						$query1->bindParam('GajiPokok', $UpahPokok);
						$query1->bindParam('UangMakanPersen', $UangMakanPersen);
						$query1->bindParam('UangMakanHari', $UangMakanHari);
						$query1->bindParam('UangMakan', $UangMakan);
						$query1->bindParam('UangTransportPersen', $UangTransportPersen);
						$query1->bindParam('UangTransportHari', $UangTransportHari);
						$query1->bindParam('UangTransport', $UangTransport);
						$query1->bindParam('Tunjangan', $Tunjangan);
						$query1->bindParam('Upah', $Upah);
						$query1->bindParam('BpjsKesPersen', $BpjsKesPersen);
						$query1->bindParam('BpjsKes', $BpjsKes);
						$query1->bindParam('BpjsTkPersen', $BpjsTkPersen);
						$query1->bindParam('BpjsTk', $BpjsTk);
						$query1->bindParam('PakaianKerjaPersen', $PakaianKerjaPersen);
						$query1->bindParam('PakaianKerja', $PakaianKerja);
						$query1->bindParam('Thr', $Thr);
						$query1->bindParam('Pesangon', $Pesangon);
						$query1->bindParam('Dplk', $Dplk);
						$query1->bindParam('JasaPjtkPersen', $JasaPjtkPersen);
						$query1->bindParam('JasaPjtk', $JasaPjtk);
						$query1->bindParam('TglCreate', $date);
						$query1->bindParam('UserUpdate', $UserUpdate);
						$query1->bindParam('SkemaKhusus', $SkemaKhusus);
						$query1->execute();
					}
					$Pesan = "<b>".$_SESSION['NamaUser']."</b>, Berhasil Menambah Data Skema Gaji!";
					AddLog($_SESSION['IdUser'],$Pesan);
					if($query1){ echo "sukses"; exit(); } else { echo "gagal"; }
					exit();
				} catch (PDOException $e) {
					echo $e->getMessage();
					exit();
				}

				break;
			case 'update':
				try {
					$sql = "UPDATE tb_skema_gaji SET  GajiPokok = :GajiPokok, UangMakanPersen = :UangMakanPersen, UangMakanHari = :UangMakanHari, UangMakan = :UangMakan, UangTransportPersen = :UangTransportPersen, UangTransportHari = :UangTransportHari, UangTransport = :UangTransport, Tunjangan = :Tunjangan, Upah = :Upah, BpjsKesPersen = :BpjsKesPersen, BpjsKes = :BpjsKes, BpjsTkPersen = :BpjsTkPersen, BpjsTk = :BpjsTk, PakaianKerjaPersen  = :PakaianKerjaPersen, PakaianKerja = :PakaianKerja, Thr = :Thr, Pesangon = :Pesangon, Dplk = :Dplk, JasaPjtkPersen =:JasaPjtkPersen, JasaPjtk = :JasaPjtk, TglUpdate = :TglUpdate, UserUpdate = :UserUpdate WHERE IdSkemaGaji = :IdSkemaGaji";
					$query = $db->prepare($sql);
					$query->bindParam('GajiPokok', $UpahPokok);
					$query->bindParam('UangMakanPersen', $UangMakanPersen);
					$query->bindParam('UangMakanHari', $UangMakanHari);
					$query->bindParam('UangMakan', $UangMakan);
					$query->bindParam('UangTransportPersen', $UangTransportPersen);
					$query->bindParam('UangTransportHari', $UangTransportHari);
					$query->bindParam('UangTransport', $UangTransport);
					$query->bindParam('Tunjangan', $Tunjangan);
					$query->bindParam('Upah', $Upah);
					$query->bindParam('BpjsKesPersen', $BpjsKesPersen);
					$query->bindParam('BpjsKes', $BpjsKes);
					$query->bindParam('BpjsTkPersen', $BpjsTkPersen);
					$query->bindParam('BpjsTk', $BpjsTk);
					$query->bindParam('PakaianKerjaPersen', $PakaianKerjaPersen);
					$query->bindParam('PakaianKerja', $PakaianKerja);
					$query->bindParam('Thr', $Thr);
					$query->bindParam('Pesangon', $Pesangon);
					$query->bindParam('Dplk', $Dplk);
					$query->bindParam('JasaPjtkPersen', $JasaPjtkPersen);
					$query->bindParam('JasaPjtk', $JasaPjtk);
					$query->bindParam('TglUpdate', $date);
					$query->bindParam('UserUpdate', $UserUpdate);
					$query->bindParam('IdSkemaGaji', $IdSkemaGaji);
					$query->execute();
					$Pesan = "<b>".$_SESSION['NamaUser']."</b>, Berhasil Mengubah Data Skema Gaji!";
					AddLog($_SESSION['IdUser'],$Pesan);
					if($query){ echo "sukses"; exit(); } else { echo "gagal"; }
				} catch (PDOException $e) {
					echo $e->getMessage();
				}
				break;
			case 'delete':
				$sql = "DELETE FROM tb_skema_gaji WHERE IdSkemaGaji = :IdSkemaGaji";
				$query = $db->prepare($sql);
				$query->bindParam("IdSkemaGaji", $IdSkemaGaji);
				$query->execute();
				$Pesan = "<b>".$_SESSION['NamaUser']."</b>, Berhasil Menghapus Data Skema Gaji!";
					AddLog($_SESSION['IdUser'],$Pesan);
				if($query){ echo "sukses"; exit(); } else { echo "gagal"; }
				break;
		}
		break;

	case 'ShowData':
		$IdSkemaGaji = $_POST['IdSkemaGaji'];
		$sql = "SELECT a.*, b.NamaKaryawan  FROM tb_skema_gaji a INNER JOIN tb_karyawan b ON a.IdKaryawan = b.IdKaryawan WHERE a.IdSkemaGaji = :IdSkemaGaji";
		$stmt = $db->prepare($sql);
		$stmt->bindParam('IdSkemaGaji', $IdSkemaGaji);
		$stmt->execute();
		$res = $stmt->fetch(PDO::FETCH_ASSOC);
		echo json_encode($res);
		break;
	
	case "GetDataJabatabCabang":
		$IdCabang = $_POST['IdCabang'];
		$sql = "SELECT tb_karyawan.Jabatan, tb_jabatan.NamaJabatan FROM tb_karyawan INNER JOIN tb_jabatan ON tb_karyawan.Jabatan = tb_jabatan.KodeJabatan WHERE tb_karyawan.IdCabang = '$IdCabang' GROUP BY tb_karyawan.Jabatan ORDER BY tb_jabatan.NamaJabatan ASC";
		$query = $db->query($sql);
		$rows = $query->rowCount();
		$data = array();
		$result = array();
		if($rows > 0){
			$data['messages']='success';
			while($res = $query->fetch(PDO::FETCH_ASSOC)){
				$result['Jabatan'] = $res['Jabatan'];
				$result['NamaJabatan'] = $res['NamaJabatan'];
				$data['Item'][] = $result;
			}
		}else{
			$data['messages']='notfound';
		}
		echo json_encode($data);
		break;
	case "GetDataKaryawanJabatan":
		$IdCabang = $_POST['IdCabang'];
		$Jabatan = $_POST['Jabatan'];
		$sql = "SELECT IdKaryawan, NamaKaryawan FROM tb_karyawan  WHERE IdCabang = '$IdCabang' AND Jabatan = '$Jabatan' ORDER BY NamaKaryawan ASC";
		$query = $db->query($sql);
		$rows = $query->rowCount();
		$data = array();
		$result = array();
		if($rows > 0){
			$data['messages']='success';
			while($res = $query->fetch(PDO::FETCH_ASSOC)){
				$result['IdKaryawan'] = $res['IdKaryawan'];
				$result['NamaKaryawan'] = $res['NamaKaryawan'];
				$data['Item'][] = $result;
			}
		}else{
			$data['messages']='notfound';
		}
		echo json_encode($data);
		break;
	
	case 'DetailDataKaryawanOne':
		$IdSkemaGaji = $_POST['IdSkemaGaji'];
		$sql = "SELECT a.*, b.NamaKaryawan, c.NamaJabatan FROM tb_skema_gaji a INNER JOIN tb_karyawan b ON a.IdKaryawan = b.IdKaryawan INNER JOIN tb_jabatan c ON a.Jabatan = c.KodeJabatan WHERE a.IdSkemaGaji = '$IdSkemaGaji'";
		$data = $db->query($sql)->fetch(PDO::FETCH_ASSOC);

		echo json_encode($data);
		break;
	
	case "GetNomorKontrak" :
		$result = array();
		$data = array();
		$IdCabang = $_POST['IdCabang'];
		$YearNow = date("Y");
		$sql = "SELECT NomorKontrak, JudulKontrak, IdKontrak FROM tb_kontrak WHERE IdCabang = '$IdCabang'";
		$query = $db->query($sql);
		$row = $query->rowCount();
		if($row > 0){
			while($res = $query->fetch(PDO::FETCH_ASSOC)){
				$data['NomorKontrak'] = $res['NomorKontrak'];
				$data['JudulKontrak'] = $res['JudulKontrak'];
				$data['IdKontrak'] = $res['IdKontrak'];
				$result['Item'][] = $data;
			}
		}
		$result['Row'] = $row;

		echo json_encode($result);

		break;
	default :
		echo json_encode("Not Found");
		break;
}

?>
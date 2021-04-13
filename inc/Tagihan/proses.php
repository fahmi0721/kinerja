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
		$sql = "SELECT a.*, b.NamaCabang FROM tb_tagihan a INNER JOIN tb_cabang b ON a.IdCabang = b.IdCabang ORDER BY a.IdTagihan DESC";
		$query = $db->query($sql);
		$JumRow = $query->rowCount();
		if($JumRow > 0){
			while ($res = $query->fetch(PDO::FETCH_ASSOC)) { 
				$aksi = $_SESSION['Level'] == "author" ? "" : "<a href='Cetak/DaftarTagihan.php?IdTagihan=".$res['IdTagihan']."' class='btn btn-xs btn-success' data-toggle='tooltip' title='Cetak Data' ><i class='fa fa-print'></i></a> <a class='btn btn-xs btn-primary' data-toggle='tooltip' title='Ubah Data' onclick=\"Crud('".$res['IdTagihan']."', 'ubah')\"><i class='fa fa-edit'></i></a> <a class='btn btn-xs btn-danger' data-toggle='tooltip' title='Hapus Data' onclick=\"Crud('".$res['IdTagihan']."', 'hapus')\"><i class='fa fa-trash-o'></i></a>";
				$pr = explode("-",$res['Periode']);
				$row['No'] = $no;
				$row['NamaCabang'] = $res['NamaCabang'];
				$row['Periode'] = $pr[0]." ".getBulan($pr[1]);
				$row['JudulTagihan'] = $res['JudulTagihan'];
				$row['Aksi'] = "<a class='btn btn-xs btn-warning' data-toggle='tooltip' title='Detail Data' onclick=\"Crud('".$res['IdTagihan']."','detail')\"><i class='fa fa-eye'></i></a> ".$aksi;
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
		$IdTagihan = $_POST['IdTagihan'];
		$Periode = $_POST['Tahun']."-".$_POST['Bulan'];
		$PotonganPercent = $_POST['PotonganPercent'];
		$IdKaryawan = $_POST['IdKaryawan'];
		$Potongan = $_POST['Potongan'];
		$JudulTagihan = $_POST['JudulTagihan'];
		
		switch ($aksi) {
			case 'insert':
				try {
					$IdPaketPenagihan = $_POST['IdPaketPenagihan'];
					$IdCabang = $_POST['IdCabang'];
					$sql = "INSERT INTO tb_tagihan SET IdCabang = :IdCabang, IdPaketPenagihan = :IdPaketPenagihan, JudulTagihan = :JudulTagihan, Periode = :Periode, TglCreate = :TglCreate, UserUpdate = :UserUpdate";
					$query = $db->prepare($sql);
					$query->bindParam('IdCabang', $IdCabang);
					$query->bindParam('IdPaketPenagihan', $IdPaketPenagihan);
					$query->bindParam('JudulTagihan', $JudulTagihan);
					$query->bindParam('Periode', $Periode);
					$query->bindParam('TglCreate', $date);
					$query->bindParam('UserUpdate', $UserUpdate);
					$query->execute();
					$IdTagihan = $db->lastInsertId();
					TambahDaftarTagihan($IdTagihan,$PotonganPercent,$Potongan,$IdKaryawan);
					$Pesan = "<b>".$_SESSION['NamaUser']."</b>, Berhasil Menambah Data Tagihan!";
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
					$IdDaftarTagihan = $_POST['IdDaftarTagihan'];
					$sql = "UPDATE tb_tagihan SET JudulTagihan = :JudulTagihan, Periode = :Periode, TglUpdate = :TglUpdate, UserUpdate = :UserUpdate WHERE IdTagihan = :IdTagihan";
					$query = $db->prepare($sql);
					$query->bindParam('JudulTagihan', $JudulTagihan);
					$query->bindParam('Periode', $Periode);
					$query->bindParam('TglUpdate', $date);
					$query->bindParam('UserUpdate', $UserUpdate);
					$query->bindParam('IdTagihan', $IdTagihan);
					$query->execute();
					UpdatehDaftarTagihan($PotonganPercent,$Potongan,$IdDaftarTagihan);
					if($query){ echo "sukses"; exit(); } else { echo "gagal"; }
					$Pesan = "<b>".$_SESSION['NamaUser']."</b>, Berhasil Mengubah Data Tagihan!";
					AddLog($_SESSION['IdUser'],$Pesan);
					if($query){ echo "sukses"; exit(); } else { echo "gagal"; }
				} catch (PDOException $e) {
					echo $e->getMessage();
				}
				break;
			case 'delete':
				$sql = "DELETE FROM tb_tagihan WHERE IdTagihan = :IdTagihan";
				$query = $db->prepare($sql);
				$query->bindParam("IdTagihan", $IdTagihan);
				$query->execute();
				$Pesan = "<b>".$_SESSION['NamaUser']."</b>, Berhasil Menghapus Data Tagihan!";
				AddLog($_SESSION['IdUser'],$Pesan);
				if($query){ echo "sukses"; exit(); } else { echo "gagal"; }
				break;
		}
		break;

	case 'ShowData':
		$IdTagihan = $_POST['IdTagihan'];
		$sql = "SELECT a.*, b.IdCabang, b.KodeCabang, b.NamaCabang FROM tb_tagihan a INNER JOIN tb_cabang b ON a.IdCabang = b.IdCabang WHERE a.IdTagihan = :IdTagihan";
		$stmt = $db->prepare($sql);
		$stmt->bindParam('IdTagihan', $IdTagihan);
		$stmt->execute();
		$res = $stmt->fetch(PDO::FETCH_ASSOC);
		$pr = explode("-",$res['Periode']);
		$res['Tahun'] = $pr[0];
		$res['Bulan'] = $pr[1];
		echo json_encode($res);
		break;
	
	case "GetDataPaket":
		$IdCabang = $_POST['IdCabang'];
		$sql = "SELECT IdPaketPenagihan, NamaPaket FROM tb_paket_penagihan WHERE IdCabang = '$IdCabang'";
		$query = $db->query($sql);
		$rows = $query->rowCount();
		$data = array();
		$result = array();
		if($rows > 0){
			$data['messages']='success';
			while($res = $query->fetch(PDO::FETCH_ASSOC)){
				$result['IdPaketPenagihan'] = $res['IdPaketPenagihan'];
				$result['NamaPaket'] = $res['NamaPaket'];
				$data['Item'][] = $result;
			}
		}else{
			$data['messages']='notfound';
		}
		echo json_encode($data);
		break;
	case "GetDataKaryawanPaket":
		$IdPaketPenagihan = $_POST['IdPaketPenagihan'];
		$sql = "SELECT Paket FROM tb_paket_penagihan WHERE IdPaketPenagihan = '$IdPaketPenagihan'";
		$res = $db->query($sql)->fetch(PDO::FETCH_ASSOC);
		$Jabatans = explode(",",$res['Paket']);
		$jab = array();
		foreach($Jabatans as $jb){
			$jab[] = "'".$jb."'";
		}

		$Jabatan = implode(",",$jab);
		$IdCabang = $_POST['IdCabang'];

		$sql1 = "SELECT a.IdKaryawan, a.NamaKaryawan, b.NamaJabatan, c.GajiPokok FROM tb_karyawan a INNER JOIN tb_jabatan b ON a.Jabatan = b.KodeJabatan INNER JOIN tb_skema_gaji c ON a.IdKaryawan = c.IdKaryawan WHERE c.Jabatan IN ($Jabatan) AND a.IdCabang = '$IdCabang' GROUP BY a.IdKaryawan";
		$query = $db->query($sql1);
		$rows = $query->rowCount();
		$data = array();
		$result = array();
		if($rows > 0){
			$data['messages']='success';
			while($ress = $query->fetch(PDO::FETCH_ASSOC)){
				$result['IdKaryawan'] = $ress['IdKaryawan'];
				$result['NamaKaryawan'] = $ress['NamaKaryawan'];
				$result['NamaJabatan'] = $ress['NamaJabatan'];
				$result['GajiPokok'] = $ress['GajiPokok'];
				$data['Item'][] = $result;
			}
		}else{
			$data['messages']='notfound';
		}
		echo json_encode($data);
		break;

	case "GetDataKaryawanPaketUbah":
		$IdPaketPenagihan = $_POST['IdPaket'];
		$IdTagihan = $_POST['IdTagihan'];
		$sql = "SELECT Paket FROM tb_paket_penagihan WHERE IdPaketPenagihan = '$IdPaketPenagihan'";
		$res = $db->query($sql)->fetch(PDO::FETCH_ASSOC);
		$Jabatans = explode(",",$res['Paket']);
		$jab = array();
		foreach($Jabatans as $jb){
			$jab[] = "'".$jb."'";
		}
		$IdCabang = $_POST['IdCabang'];
		$Jabatan = implode(",",$jab);
		$sql1 = "SELECT a.IdKaryawan, a.NamaKaryawan, b.NamaJabatan, c.GajiPokok FROM tb_karyawan a INNER JOIN tb_jabatan b ON a.Jabatan = b.KodeJabatan INNER JOIN tb_skema_gaji c ON a.IdKaryawan = c.IdKaryawan WHERE c.Jabatan IN ($Jabatan) AND a.IdCabang = '$IdCabang' GROUP BY a.IdKaryawan";
		$query = $db->query($sql1);
		$rows = $query->rowCount();
		$data = array();
		$result = array();
		if($rows > 0){
			$data['messages']='success';
			while($ress = $query->fetch(PDO::FETCH_ASSOC)){
				$resd = $db->query("SELECT IdDaftarTagihan, PotonganPercent, Potongan FROM tb_daftar_tagihan WHERE IdKaryawan = '$ress[IdKaryawan]' AND IdTagihan = '$IdTagihan'")->fetch(PDO::FETCH_ASSOC);
				$result['PotonganPercent'] = empty($resd['PotonganPercent']) ? 0 : $resd['PotonganPercent'];
				$result['Potongan'] = empty($resd['Potongan']) ? 0 : $resd['Potongan'];
				
				$result['IdDaftarTagihan'] = $resd['IdDaftarTagihan'];
				$result['IdKaryawan'] = $ress['IdKaryawan'];
				$result['NamaKaryawan'] = $ress['NamaKaryawan'];
				$result['NamaJabatan'] = $ress['NamaJabatan'];
				$result['GajiPokok'] = $ress['GajiPokok'];
				$data['Item'][] = $result;
			}
		}else{
			$data['messages']='notfound';
		}
		echo json_encode($data);
		break;
	
	default :
		echo json_encode($_POST);
		break;
}

?>
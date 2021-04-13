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
		$sql = "SELECT a.*, b.NamaCabang FROM tb_kontrak a INNER JOIN tb_cabang b ON a.IdCabang = b.IdCabang WHERE a.JenisKontrak = '0'  ORDER BY a.IdKontrak DESC";
		$query = $db->query($sql);
		$JumRow = $query->rowCount();
		if($JumRow > 0){
			while ($res = $query->fetch(PDO::FETCH_ASSOC)) { 
				$TotalTagihan = CekTotalTagihan($res['IdKontrak']);
				$aksi = $_SESSION['Level'] == "author" ? "" : "<a class='btn btn-xs btn-primary' data-toggle='tooltip' title='Ubah Data' onclick=\"Crud('".$res['IdKontrak']."', 'ubah')\"><i class='fa fa-edit'></i></a> <a class='btn btn-xs btn-danger' data-toggle='tooltip' title='Hapus Data' onclick=\"Crud('".$res['IdKontrak']."', 'hapus','".$res['FileKontrak']."')\"><i class='fa fa-trash-o'></i></a>";
				$detail = "<a class='btn btn-xs btn-success' data-toggle='tooltip' title='Detail Data' onclick=\"Crud('".$res['IdKontrak']."', 'detail')\"><i class='fa fa-eye'></i></a>";
				$row['No'] = $no;
				$row['NamaCabang'] = $res['NamaCabang'];
				$row['JudulKontrak'] = $res['JudulKontrak'];
				$row['NomorKontrak'] = $res['NomorKontrak'];
				$row['Berlaku'] = tgl_indo($res['BerlakuMulai'])." s/d ".tgl_indo($res['BerlakuSampai']);
				$row['TotalTagihan'] = "Rp. ". rupiah($TotalTagihan);
				$row['Addendum'] = "<a data-toggle='tooltip' title='Detail Data Addendum' onclick=\"LoadDataReload('".$res['IdKontrak']."')\" class='btn btn-xs btn-warning' data-toggle='tooltip'  ><i class='fa fa-link'></i></a>";
				$row['FileKontrak'] = $res['FileKontrak'] != "-" ? "<a target='_blank' href='Files/Kontrak/".$res['FileKontrak']."' class='btn btn-xs btn-success' data-toggle='tooltip' title='File' ><i class='fa fa-file-pdf-o'></i></a>" : "-";
				$row['Aksi'] = $detail." ".$aksi;
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
		$IdCabang = $_POST['IdCabang'];
		$IdKontrak = $_POST['IdKontrak'];
		$JenisKontrak = $_POST['JenisKontrak'];
		$TmpFiles = $_POST['TmpFile'];
		$JudulKontrak = $_POST['JudulKontrak'];
		$IdKontrakInduk = isset($_POST['IdKontrakInduk']) ? $_POST['IdKontrakInduk'] : "";
		$NomorKontrak = $_POST['NomorKontrak'];
		$BerlakuMulai = $_POST['BerlakuMulai'];
		$BerlakuSampai = $_POST['BerlakuSampai'];
		$Keterangan = $_POST['Keterangan'];
		switch ($aksi) {
			case 'insert':
				try {
					$CekNomorKontrak = CekNomorKontrak($NomorKontrak);
					if($CekNomorKontrak == "found"){ echo $CekNomorKontrak; exit(); }
					$NamaFile = "-";
					if($_FILES['FileKontrak']['error'] == 0){
						$TmpFile = $_FILES['FileKontrak']['tmp_name'];
						$extensi = $_FILES['FileKontrak']['type'];
						$NamaFile = time().rand(0,999).".pdf";
						$filter = array("application/pdf");
						$UploadTo = "../../Files/Kontrak/";
						$upload = UploadFile($NamaFile, $TmpFile, $UploadTo, $extensi, $filter);
						if(!$upload){
							echo "filenotsupport";
							exit();
						}
					}
					
					$sql = "INSERT INTO tb_kontrak SET  IdCabang = :IdCabang,  JenisKontrak = :JenisKontrak, JudulKontrak = :JudulKontrak, BerlakuMulai = :BerlakuMulai, BerlakuSampai = :BerlakuSampai, TglCreate = :TglCreate, NomorKontrak = :NomorKontrak, IdKontrakInduk = :IdKontrakInduk, Keterangan = :Keterangan, UserUpdate = :UserUpdate , FileKontrak = :FileKontrak";
					$query = $db->prepare($sql);
					$query->bindParam("IdCabang", $IdCabang);
					$query->bindParam("JenisKontrak", $JenisKontrak);
					$query->bindParam("JudulKontrak", $JudulKontrak);
					$query->bindParam("BerlakuMulai", $BerlakuMulai);
					$query->bindParam("BerlakuSampai", $BerlakuSampai);
					$query->bindParam("TglCreate", $date);
					$query->bindParam("NomorKontrak", $NomorKontrak);
					$query->bindParam("IdKontrakInduk", $IdKontrakInduk);
					$query->bindParam("Keterangan", $Keterangan);
					$query->bindParam("UserUpdate", $UserUpdate);
					$query->bindParam("FileKontrak", $NamaFile);
					$query->execute();
					$IdLastInsert = $db->lastInsertId();
					InsertDataKontrakList($UserUpdate,$IdLastInsert);
					$Pesan = "<b>".$_SESSION['NamaUser']."</b>, Berhasil Menambah Data Kontrak!";
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
					if($_FILES['FileKontrak']['error'] == 0){
						$UploadTo = "../../Files/Kontrak/";
						
						$filters = ",FileKontrak = :FileKontrak";
						$TmpFile = $_FILES['FileKontrak']['tmp_name'];
						$extensi = $_FILES['FileKontrak']['type'];
						$NamaFile = time().rand(0,999).".pdf";
						$filter = array("application/pdf");
						$upload = UploadFile($NamaFile, $TmpFile, $UploadTo, $extensi, $filter);
						if(!$upload){
							echo "filenotsupport";
							exit();
						}
						HapusFile($TmpFiles,$UploadTo);
					}else{
						$filters = "";

					}
					$sql = "UPDATE tb_kontrak SET IdKontrakInduk = :IdKontrakInduk,  BerlakuMulai = :BerlakuMulai, BerlakuSampai = :BerlakuSampai, TglUpdate = :TglUpdate, NomorKontrak = :NomorKontrak, Keterangan = :Keterangan, UserUpdate = :UserUpdate $filters WHERE IdKontrak = :IdKontrak";
					$query = $db->prepare($sql);
					$query->bindParam("IdKontrakInduk", $IdKontrakInduk);
					$query->bindParam("BerlakuMulai", $BerlakuMulai);
					$query->bindParam("BerlakuSampai", $BerlakuSampai);
					$query->bindParam("TglUpdate", $date);
					$query->bindParam("NomorKontrak", $NomorKontrak);
					$query->bindParam("Keterangan", $Keterangan);
					$query->bindParam("UserUpdate", $UserUpdate);
					if($_FILES['FileKontrak']['error'] == 0){
						$query->bindParam("FileKontrak", $NamaFile);
					}
					$query->bindParam("IdKontrak", $IdKontrak);
					$query->execute();
					HapusListKontrak($IdKontrak);
					InsertDataKontrakList($UserUpdate,$IdKontrak);
					$Pesan = "<b>".$_SESSION['NamaUser']."</b>, Berhasil Mengubah Data Kontrak!";
					AddLog($_SESSION['IdUser'],$Pesan);
					if($query){ echo "sukses"; exit(); } else { echo "gagal"; }
				} catch (PDOException $e) {
					echo $e->getMessage();
				}
				break;
			case 'delete':
				$UploadTo = "../../Files/Kontrak/";
				HapusFile($TmpFiles,$UploadTo);
				$sql = "DELETE FROM tb_kontrak WHERE IdKontrak = :IdKontrak";
				$query = $db->prepare($sql);
				$query->bindParam("IdKontrak", $IdKontrak);
				$query->execute();
				DeleteAllAddendum($IdKontrak);
				$Pesan = "<b>".$_SESSION['NamaUser']."</b>, Berhasil Menghapus Data Kontrak!";
					AddLog($_SESSION['IdUser'],$Pesan);
				if($query){ echo "sukses"; exit(); } else { echo "gagal"; }
				break;
		}
		break;

	case 'ShowData':
		$IdKontrak = $_POST['IdKontrak'];
		$sql = "SELECT a.*, b.NamaCabang, b.KodeCabang FROM tb_kontrak a INNER JOIN tb_cabang b ON a.IdCabang = b.IdCabang WHERE IdKontrak = :IdKontrak";
		$stmt = $db->prepare($sql);
		$stmt->bindParam('IdKontrak', $IdKontrak);
		$stmt->execute();
		$res = $stmt->fetch(PDO::FETCH_ASSOC);
		echo json_encode($res);
		break;

	case 'CalculateMounth':
		$waktuawal  = date_create($_POST['first']); //waktu di setting
		$ls = explode("-",$_POST['last']);
		$add = $ls[2] == "31" ? "+1 day" : "+2 day";
		$add = $ls[2] == "29" ? "+3 day" : $add;
		$last = date('Y-m-d', strtotime($add, strtotime($_POST['last'])));
		$waktuakhir = date_create($last); //2019-02-21 09:35 waktu sekarang

		$diff  = date_diff($waktuawal, $waktuakhir);

		$data = array();
		$data['y'] = $diff->y;
		$data['m'] = $diff->m;
		
		echo json_encode($data);
		

		break;
	
	case "CrudListSementara":
		$Berlaku = $_POST['BerlakuMulai'];
		$Sampai = $_POST['BerlakuSampai'];
		$BpjsKes = angka($_POST['BpjsKes']);
		$BpjsKesPersen = angkaDecimal($_POST['BpjsKesPersen']);
		$BpjsTk = angka($_POST['BpjsTk']);
		$BpjsTkPersen = angkaDecimal($_POST['BpjsTkPersen']);
		$Dplk = angka($_POST['Dplk']);
		$JaminanHariTua = angka($_POST['JaminanHariTua']);
		$JaminanHariTuaPersen = angkaDecimal($_POST['JaminanHariTuaPersen']);
		$JaminanKecelakaanKerja = angka($_POST['JaminanKecelakaanKerja']);
		$JaminanKecelakaanKerjaPersen = angkaDecimal($_POST['JaminanKecelakaanKerjaPersen']);
		$JaminanKematian = angka($_POST['JaminanKematian']);
		$JaminanKematianPersen = angkaDecimal($_POST['JaminanKematianPersen']);
		$JaminanPensiun = angka($_POST['JaminanPensiun']);
		$JaminanPensiunPersen = angkaDecimal($_POST['JaminanPensiunPersen']);
		$JasaPjtk= angka($_POST['JasaPJTK']);
		$JasaPjtkPersen = angkaDecimal($_POST['JasaPjtkPersen']);
		$Jumlah= angka($_POST['Jumlah']);
		$LamaKontrak= angka($_POST['LamaKontrak']);
		$NamaList= $_POST['NamaList'];
		$PakaianKerja = angka($_POST['PakaianKerja']);
		$PakaianKerjaPersen = angkaDecimal($_POST['PakaianKerjaPersen']);
		$Pesangon = angka($_POST['Pesangon']);
		$Tagihan = angka($_POST['Tagihan']);
		$Thr = angka($_POST['Thr']);
		$Tunjangan = angka($_POST['Tunjangan']);
		$UpahPokok = angka($_POST['UpahPokok']);
		$UangMakan = angka($_POST['UangMakan']);
		$UangMakanHari = angka($_POST['UangMakanHari']);
		$UangMakanPersen = angkaDecimal($_POST['UangMakanPersen']);
		$UangTransport = angka($_POST['UangTransport']);
		$UangTransportHari = angka($_POST['UangTransportHari']);
		$UangTransportPersen = angkaDecimal($_POST['UangTransportPersen']);

		$data= array();
		try {
			$sql = "INSERT INTO tb_kontrak_list_sementara SET
					Berlaku = :Berlaku,
					Sampai = :Sampai,
					BpjsKes = :BpjsKes,
					BpjsKesPersen = :BpjsKesPersen,
					BpjsTk = :BpjsTk,
					BpjsTkPersen = :BpjsTkPersen,
					Dplk = :Dplk,
					JaminanHariTua = :JaminanHariTua,
					JaminanHariTuaPersen = :JaminanHariTuaPersen,
					JaminanKecelakaanKerja = :JaminanKecelakaanKerja,
					JaminanKecelakaanKerjaPersen = :JaminanKecelakaanKerjaPersen,
					JaminanKematian = :JaminanKematian,
					JaminanKematianPersen = :JaminanKematianPersen,
					JaminanPensiun = :JaminanPensiun,
					JaminanPensiunPersen = :JaminanPensiunPersen,
					JasaPjtk = :JasaPjtk,
					JasaPjtkPersen = :JasaPjtkPersen,
					Jumlah = :Jumlah,
					LamaKontrak = :LamaKontrak,
					NamaList = :NamaList,
					PakaianKerja = :PakaianKerja,
					PakaianKerjaPersen = :PakaianKerjaPersen,
					Pesangon = :Pesangon,
					Thr = :Thr,
					Tagihan = :Tagihan,
					Tunjangan = :Tunjangan,
					UpahPokok = :UpahPokok,
					UangMakan = :UangMakan,
					UangMakanHari = :UangMakanHari,
					UangMakanPersen = :UangMakanPersen,
					UangTransport = :UangTransport,
					UangTransportPersen = :UangTransportPersen,
					UangTransportHari = :UangTransportHari,
					UserUpdate = :UserUpdate
			";
			$query = $db->prepare($sql);
			$query->bindParam('Berlaku',$Berlaku);
			$query->bindParam('Sampai',$Sampai);
			$query->bindParam('BpjsKes',$BpjsKes);
			$query->bindParam('BpjsKesPersen',$BpjsKesPersen);
			$query->bindParam('BpjsTk',$BpjsTk);
			$query->bindParam('BpjsTkPersen',$BpjsTkPersen);
			$query->bindParam('Dplk',$Dplk);
			$query->bindParam('JaminanHariTua',$JaminanHariTua);
			$query->bindParam('JaminanHariTuaPersen',$JaminanHariTuaPersen);
			$query->bindParam('JaminanKecelakaanKerja',$JaminanKecelakaanKerja);
			$query->bindParam('JaminanKecelakaanKerjaPersen',$JaminanKecelakaanKerjaPersen);
			$query->bindParam('JaminanKematian',$JaminanKematian);
			$query->bindParam('JaminanKematianPersen',$JaminanKematianPersen);
			$query->bindParam('JaminanPensiun',$JaminanPensiun);
			$query->bindParam('JaminanPensiunPersen',$JaminanPensiunPersen);
			$query->bindParam('JasaPjtk',$JasaPjtk);
			$query->bindParam('JasaPjtkPersen',$JasaPjtkPersen);
			$query->bindParam('Jumlah',$Jumlah);
			$query->bindParam('LamaKontrak',$LamaKontrak);
			$query->bindParam('NamaList',$NamaList);
			$query->bindParam('PakaianKerja',$PakaianKerja);
			$query->bindParam('PakaianKerjaPersen',$PakaianKerjaPersen);
			$query->bindParam('Pesangon',$PakaianKerja);
			$query->bindParam('Thr',$Thr);
			$query->bindParam('Tagihan',$Tagihan);
			$query->bindParam('Tunjangan',$Tunjangan);
			$query->bindParam('UpahPokok',$UpahPokok);
			$query->bindParam('UangMakan',$UangMakan);
			$query->bindParam('UangMakanHari',$UangMakanHari);
			$query->bindParam('UangMakanPersen',$UangMakanPersen);
			$query->bindParam('UangTransport',$UangTransport);
			$query->bindParam('UangTransportPersen',$UangTransportPersen);
			$query->bindParam('UangTransportHari',$UangTransportHari);
			$query->bindParam('UserUpdate',$UserUpdate);
			$query->execute();
			$data['status'] = "success";
			$data['messages'] = "Tambah Data List Kontrak Berhasil";
		} catch (PDOException $e) {
			$data['status'] = "error";
			$data['messages'] = $e->getMessage();
		}
		echo json_encode($data);
		break;
	
	case 'DetailListKontrak' :
		$sql = "SELECT IdListKontrak, Jumlah, NamaList, LamaKontrak, Tagihan, (Tagihan * Jumlah * LamaKontrak) as TotTagihan FROM tb_kontrak_list_sementara WHERE UserUpdate = '$UserUpdate'";
		$query = $db->query($sql);
		$res = array();
		$data=array();
		$No=1;
		if($query->rowCount() > 0){
			while($dt = $query->fetch(PDO::FETCH_ASSOC)){
				$res['No'] = $No;
				$res['NamaList'] = $dt['NamaList'];
				$res['IdListKontrak'] = $dt['IdListKontrak'];
				$res['Jumlah'] = $dt['Jumlah'];
				$res['LamaKontrak'] = $dt['LamaKontrak'];
				$res['Tagihan'] = $dt['Tagihan'];
				$res['TotTagihan'] = $dt['TotTagihan'];
				$data['item'][]=$res;
				$No++;
			}
			$data['status'] = 'success';
			$data['messages'] = 'OK';
		}else{
			$data['status'] = 'error';
			$data['messages'] = 'no data availible in table';
			$data['item'] = '';
		}
		echo json_encode($data);
		
		break;

	case 'HapusList' :
		$IdListKontrak = $_POST['IdListKontrak'];
		try {
			$sql = "DELETE FROM tb_kontrak_list_sementara WHERE IdListKontrak = :IdListKontrak";
			$query = $db->prepare($sql);
			$query->bindParam('IdListKontrak', $IdListKontrak);
			$query->execute();
			$data['status'] = "success";
			$data['messages'] = "Hapus Data List Kontrak Berhasil";
		} catch (PDOException $th) {
			$data['status'] = "error";
			$data['messages'] = $e->getMessage();
		}
		echo json_encode($data);
		break;

	case 'HapusAllList' :
		$sql = "DELETE FROM tb_kontrak_list_sementara WHERE UserUpdate = :UserUpdate";
		$query = $db->prepare($sql);
		$query->bindParam('UserUpdate', $UserUpdate);
		$query->execute();
		break;
	
	case 'InsertToListSementara':
		$IdKontrak = $_POST['IdKontrak'];
		try{
			IsertIntoListSementara($IdKontrak,$UserUpdate);
			echo "sekses";
		}catch(PDOException $e){
			echo $e->getMessage();
		}
		break;
	
	case 'ShowListKontrakSementara':
		$IdListKontrak = $_POST['IdListKontrak'];
		$sql = "SELECT * FROM tb_kontrak_list_sementara WHERE IdListKontrak = '$IdListKontrak'";
		$query = $db->query($sql);
		$data = $query->fetch(PDO::FETCH_ASSOC);
		echo json_encode($data);
		break;
	case 'UpdateListSementara':
		$IdListKontrak = $_POST['IdListKontrak'];
		$Berlaku = $_POST['BerlakuMulai'];
		$Sampai = $_POST['BerlakuSampai'];
		$BpjsKes = angka($_POST['BpjsKes']);
		$BpjsKesPersen = angkaDecimal($_POST['BpjsKesPersen']);
		$BpjsTk = angka($_POST['BpjsTk']);
		$BpjsTkPersen = angkaDecimal($_POST['BpjsTkPersen']);
		$Dplk = angka($_POST['Dplk']);
		$JaminanHariTua = angka($_POST['JaminanHariTua']);
		$JaminanHariTuaPersen = angkaDecimal($_POST['JaminanHariTuaPersen']);
		$JaminanKecelakaanKerja = angka($_POST['JaminanKecelakaanKerja']);
		$JaminanKecelakaanKerjaPersen = angkaDecimal($_POST['JaminanKecelakaanKerjaPersen']);
		$JaminanKematian = angka($_POST['JaminanKematian']);
		$JaminanKematianPersen = angkaDecimal($_POST['JaminanKematianPersen']);
		$JaminanPensiun = angka($_POST['JaminanPensiun']);
		$JaminanPensiunPersen = angkaDecimal($_POST['JaminanPensiunPersen']);
		$JasaPjtk= angka($_POST['JasaPJTK']);
		$JasaPjtkPersen = angkaDecimal($_POST['JasaPjtkPersen']);
		$Jumlah= angka($_POST['Jumlah']);
		$LamaKontrak= angka($_POST['LamaKontrak']);
		$NamaList= $_POST['NamaList'];
		$PakaianKerja = angka($_POST['PakaianKerja']);
		$PakaianKerjaPersen = angkaDecimal($_POST['PakaianKerjaPersen']);
		$Pesangon = angka($_POST['Pesangon']);
		$Tagihan = angka($_POST['Tagihan']);
		$Thr = angka($_POST['Thr']);
		$Tunjangan = angka($_POST['Tunjangan']);
		$UpahPokok = angka($_POST['UpahPokok']);
		$UangMakan = angka($_POST['UangMakan']);
		$UangMakanHari = angka($_POST['UangMakanHari']);
		$UangMakanPersen = angkaDecimal($_POST['UangMakanPersen']);
		$UangTransport = angka($_POST['UangTransport']);
		$UangTransportHari = angka($_POST['UangTransportHari']);
		$UangTransportPersen = angkaDecimal($_POST['UangTransportPersen']);

		$data= array();
		try {
			$sql = "UPDATE tb_kontrak_list_sementara SET
					Berlaku = :Berlaku,
					Sampai = :Sampai,
					BpjsKes = :BpjsKes,
					BpjsKesPersen = :BpjsKesPersen,
					BpjsTk = :BpjsTk,
					BpjsTkPersen = :BpjsTkPersen,
					Dplk = :Dplk,
					JaminanHariTua = :JaminanHariTua,
					JaminanHariTuaPersen = :JaminanHariTuaPersen,
					JaminanKecelakaanKerja = :JaminanKecelakaanKerja,
					JaminanKecelakaanKerjaPersen = :JaminanKecelakaanKerjaPersen,
					JaminanKematian = :JaminanKematian,
					JaminanKematianPersen = :JaminanKematianPersen,
					JaminanPensiun = :JaminanPensiun,
					JaminanPensiunPersen = :JaminanPensiunPersen,
					JasaPjtk = :JasaPjtk,
					JasaPjtkPersen = :JasaPjtkPersen,
					Jumlah = :Jumlah,
					LamaKontrak = :LamaKontrak,
					NamaList = :NamaList,
					PakaianKerja = :PakaianKerja,
					PakaianKerjaPersen = :PakaianKerjaPersen,
					Pesangon = :Pesangon,
					Thr = :Thr,
					Tagihan = :Tagihan,
					Tunjangan = :Tunjangan,
					UpahPokok = :UpahPokok,
					UangMakan = :UangMakan,
					UangMakanHari = :UangMakanHari,
					UangMakanPersen = :UangMakanPersen,
					UangTransport = :UangTransport,
					UangTransportPersen = :UangTransportPersen,
					UangTransportHari = :UangTransportHari,
					UserUpdate = :UserUpdate
				WHERE 
					IdListKontrak =:IdListKontrak
			";
			$query = $db->prepare($sql);
			$query->bindParam('Berlaku',$Berlaku);
			$query->bindParam('Sampai',$Sampai);
			$query->bindParam('BpjsKes',$BpjsKes);
			$query->bindParam('BpjsKesPersen',$BpjsKesPersen);
			$query->bindParam('BpjsTk',$BpjsTk);
			$query->bindParam('BpjsTkPersen',$BpjsTkPersen);
			$query->bindParam('Dplk',$Dplk);
			$query->bindParam('JaminanHariTua',$JaminanHariTua);
			$query->bindParam('JaminanHariTuaPersen',$JaminanHariTuaPersen);
			$query->bindParam('JaminanKecelakaanKerja',$JaminanKecelakaanKerja);
			$query->bindParam('JaminanKecelakaanKerjaPersen',$JaminanKecelakaanKerjaPersen);
			$query->bindParam('JaminanKematian',$JaminanKematian);
			$query->bindParam('JaminanKematianPersen',$JaminanKematianPersen);
			$query->bindParam('JaminanPensiun',$JaminanPensiun);
			$query->bindParam('JaminanPensiunPersen',$JaminanPensiunPersen);
			$query->bindParam('JasaPjtk',$JasaPjtk);
			$query->bindParam('JasaPjtkPersen',$JasaPjtkPersen);
			$query->bindParam('Jumlah',$Jumlah);
			$query->bindParam('LamaKontrak',$LamaKontrak);
			$query->bindParam('NamaList',$NamaList);
			$query->bindParam('PakaianKerja',$PakaianKerja);
			$query->bindParam('PakaianKerjaPersen',$PakaianKerjaPersen);
			$query->bindParam('Pesangon',$PakaianKerja);
			$query->bindParam('Thr',$Thr);
			$query->bindParam('Tagihan',$Tagihan);
			$query->bindParam('Tunjangan',$Tunjangan);
			$query->bindParam('UpahPokok',$UpahPokok);
			$query->bindParam('UangMakan',$UangMakan);
			$query->bindParam('UangMakanHari',$UangMakanHari);
			$query->bindParam('UangMakanPersen',$UangMakanPersen);
			$query->bindParam('UangTransport',$UangTransport);
			$query->bindParam('UangTransportPersen',$UangTransportPersen);
			$query->bindParam('UangTransportHari',$UangTransportHari);
			$query->bindParam('UserUpdate',$UserUpdate);
			$query->bindParam('IdListKontrak',$IdListKontrak);
			$query->execute();
			$data['status'] = "success";
			$data['messages'] = "Mengubah Data List Kontrak Berhasil";
		} catch (PDOException $e) {
			$data['status'] = "error";
			$data['messages'] = $e->getMessage();
		}
		echo json_encode($data);

		break;
	
	case 'LoadDataKontrakInduk':
		$IdKontrak = $_POST['IdKontrak'];
		$sql = "SELECT BerlakuSampai FROM tb_kontrak WHERE IdKontrak = '$IdKontrak'";
		$query = $db->query($sql);
		$data1 = $query->fetch(PDO::FETCH_ASSOC);
		
		$sql1 = "SELECT * FROM tb_kontrak_list WHERE IdKontrak = '$IdKontrak' LIMIT 1";
		$query1 = $db->query($sql1);
		$data = $query1->fetch(PDO::FETCH_ASSOC);
		$data['BerlakuSampai'] = $data1['BerlakuSampai'];

		echo json_encode($data);
		break;
	
	case 'DetailAddendum' :
		$IdKontrak = $_POST['IdKontrak'];
		$row = array(); 
		$data = array(); 
		$no=1;
		$sql = "SELECT a.*, b.NamaCabang FROM tb_kontrak a INNER JOIN tb_cabang b ON a.IdCabang = b.IdCabang WHERE a.JenisKontrak = '1' AND IdKontrakInduk = '$IdKontrak' ORDER BY a.IdKontrak DESC";
		$query = $db->query($sql);
		$JumRow = $query->rowCount();
		if($JumRow > 0){
			while ($res = $query->fetch(PDO::FETCH_ASSOC)) { 
				$TotalTagihan = CekTotalTagihan($res['IdKontrak']);
				$aksi = $_SESSION['Level'] == "author" ? "" : "<a class='btn btn-xs btn-primary' data-toggle='tooltip' title='Ubah Data' onclick=\"Crud('".$res['IdKontrak']."', 'ubah')\"><i class='fa fa-edit'></i></a> <a class='btn btn-xs btn-danger' data-toggle='tooltip' title='Hapus Data' onclick=\"Crud('".$res['IdKontrak']."', 'hapus','".$res['FileKontrak']."')\"><i class='fa fa-trash-o'></i></a>";
				$detail = "<a class='btn btn-xs btn-success' data-toggle='tooltip' title='Detail Data' onclick=\"Crud('".$res['IdKontrak']."', 'detail')\"><i class='fa fa-eye'></i></a>";
				$row['No'] = $no;
				$row['JudulKontrak'] = $res['JudulKontrak'];
				$row['NomorKontrak'] = $res['NomorKontrak'];
				$row['Berlaku'] = tgl_indo($res['BerlakuMulai'])." s/d ".tgl_indo($res['BerlakuSampai']);
				$row['TotalTagihan'] = "Rp. ". rupiah($TotalTagihan);
				$row['Addendum'] = "<a data-toggle='tooltip' title='Detail Data Addendum' onclick=\"LoadDataReload('".$res['IdKontrak']."')\" class='btn btn-xs btn-warning' data-toggle='tooltip'  ><i class='fa fa-link'></i></a>";
				$row['FileKontrak'] = $res['FileKontrak'] != "-" ? "<a target='_blank' href='Files/Kontrak/".$res['FileKontrak']."' class='btn btn-xs btn-success' data-toggle='tooltip' title='File' ><i class='fa fa-file-pdf-o'></i></a>" : "-";
				$row['Aksi'] = $detail." ".$aksi;
				$data['data'][] = $row;
				$no++;
			}
		}else{
			$data['data']='';
		}

		echo json_encode($data);

		break;
}

?>
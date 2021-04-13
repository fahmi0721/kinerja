<?php
session_start();
include_once '../../config/config.php';
include_once '../../lib/excel_reader/excel_reader2.php';
$UserUpdate = $_SESSION['IdUser'];
$date = date("Y-m-d H:i:s");
$proses = isset($_GET['proses']) ? $_GET['proses'] : "";
switch ($proses) {
	case 'DetailData':
		$Filter = $_GET['Filter'] != "" ? "WHERE tb_cabang.NamaCabang LIKE '%$_GET[Filter]%'" : "";
		$row = array(); 
		$data = array(); 
		$no=1;
		$sql = "SELECT tb_karyawan.IdKaryawan, tb_karyawan.TMTIsu, tb_karyawan.NamaKaryawan, tb_karyawan.NIK, tb_karyawan.JenisKelamin, CONCAT(TptLahir,', ',TglLahir) as TTL, tb_karyawan.TMTIsu, tb_cabang.NamaCabang, tb_divisi.NamaDivisi, tb_jabatan.NamaJabatan FROM tb_karyawan 
		INNER JOIN tb_cabang ON tb_karyawan.IdCabang = tb_cabang.IdCabang 
		LEFT JOIN tb_jabatan ON tb_karyawan.Jabatan = tb_jabatan.KodeJabatan
		LEFT JOIN tb_divisi ON tb_karyawan.UnitTugas = tb_divisi.KodeDivisi
		$Filter
		";
		$query = $db->query($sql);
		$JumRow = $query->rowCount();
		if($JumRow > 0){
			while ($res = $query->fetch(PDO::FETCH_ASSOC)) { 
				$aksi = $_SESSION['Level'] == "author" ? "" : "<a class='btn btn-xs btn-primary' data-toggle='tooltip' title='Ubah Data' onclick=\"Crud('".$res['IdKaryawan']."', 'ubah')\"><i class='fa fa-edit'></i></a> <a class='btn btn-xs btn-danger' data-toggle='tooltip' title='Hapus Data' onclick=\"Crud('".$res['IdKaryawan']."', 'hapus')\"><i class='fa fa-trash-o'></i></a>";
				$cetak = "<a class='btn btn-xs btn-success' data-toggle='tooltip' target='_blank' href='Cetak/CV.php?IdKaryawan=".$res['IdKaryawan']."' title='Cetak CV' ><i class='fa fa-print'></i></a> "; 
				$row['No'] = $no;
				$row['NIK'] = $res['NIK'];
				$row['NamaKaryawan'] = $res['NamaKaryawan'];
				$row['TTL'] = $res['TTL'];
				$row['Cabang'] = $res['NamaCabang'];
				$row['Jabatan'] = $res['NamaJabatan'];
				$row['UnitTugas'] = $res['NamaDivisi'];
				$row['TMTISMA'] = $res['TMTIsu'];
				$row['JK'] = $res['JenisKelamin'];
				$row['Aksi'] = $cetak.$aksi;
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
		$IdKaryawan = $_POST['IdKaryawan'];
		$IdCabang = $_POST['IdCabang'];
		$KodeCabang = $_POST['KodeCabang'];
		$StatusKaryawan = $_POST['StatusKaryawan'];
		$TptLahir = $_POST['TptLahir'];
		$TglLahir = $_POST['TglLahir'];
		$NIK = $_POST['NIK'];
		$NoKtp = $_POST['NoKtp'];
		$NamaKaryawan = $_POST['NamaKaryawan'];
		$JenisKelamin = $_POST['JenisKelamin'];
		$NoHp = $_POST['NoHp'];
		$UnitTugas = $_POST['UnitTugas'];
		$Jabatan = $_POST['Jabatan'];
		$BpjsKes = $_POST['BpjsKes'];
		$BpjsTk = $_POST['BpjsTk'];
		$RekeningUtama = $_POST['RekeningUtama'];
		$TMTCabang = $_POST['TMTCabang'];
		$TMTIsu = $_POST['TMTIsu'];
		$PendidikanTerakhir = $_POST['PendidikanTerakhir'];
		$Agama = $_POST['Agama'];
		$UkuranBaju = $_POST['UkuranBaju'];
		$UkuranSepatu = $_POST['UkuranSepatu'];
		$Alamat = $_POST['Alamat'];
		switch ($aksi) {
			case 'insert':
				try {
					$sql = "INSERT INTO tb_karyawan SET  IdCabang = :IdCabang,  NIK = :NIK, NamaKaryawan = :NamaKaryawan, TglLahir = :TglLahir, TptLahir = :TptLahir,  UnitTugas = :UnitTugas, NoKtp = :NoKtp, JenisKelamin = :JenisKelamin, NoHp = :NoHp, Jabatan = :Jabatan, BpjsKes = :BpjsKes, BpjsTk = :BpjsTk, RekeningUtama = :RekeningUtama, TMTCabang = :TMTCabang, TMTIsu = :TMTIsu, PendidikanTerakhir = :PendidikanTerakhir, Agama = :Agama, UkuranBaju = :UkuranBaju, UkuranSepatu = :UkuranSepatu, Alamat = :Alamat, TglCreate = :TglCreate, UserUpdate = :UserUpdate";
					$query = $db->prepare($sql);
					$query->bindParam("IdCabang", $IdCabang);
					$query->bindParam("NIK", $NIK);
					$query->bindParam("NamaKaryawan", $NamaKaryawan);
					$query->bindParam("TglLahir", $TglLahir);
					$query->bindParam("TptLahir", $TptLahir);
					$query->bindParam("UnitTugas", $UnitTugas);
					$query->bindParam("NoKtp", $NoKtp);
					$query->bindParam("JenisKelamin", $JenisKelamin);
					$query->bindParam("NoHp", $NoHp);
					$query->bindParam("Jabatan", $Jabatan);
					$query->bindParam("BpjsKes", $BpjsKes);
					$query->bindParam("BpjsTk", $BpjsTk); 
					$query->bindParam("RekeningUtama", $RekeningUtama);
					$query->bindParam("TMTCabang", $TMTCabang);
					$query->bindParam("TMTIsu", $TMTIsu);
					$query->bindParam("PendidikanTerakhir", $PendidikanTerakhir);
					$query->bindParam("Agama", $Agama);
					$query->bindParam("UkuranBaju", $UkuranBaju);
					$query->bindParam("UkuranSepatu", $UkuranSepatu);
					$query->bindParam("Alamat", $Alamat);
					$query->bindParam("TglCreate", $date);
					$query->bindParam("UserUpdate", $UserUpdate);
					$query->execute();
					$IdKaryawan = $db->lastInsertId();
					$Pesan = "<b>".$_SESSION['NamaUser']."</b>, Berhasil Menambah Data Karyawan!";
					AddLog($_SESSION['IdUser'],$Pesan);
					if($query){ 
						for($i=0; $i < COUNT($_POST['NamaBank']); $i++){
							$NamaBank = $_POST['NamaBank'][$i];
							$Norekening = $_POST['Norekening'][$i];
							$sqlBank = "INSERT INTO tb_list_bank SET IdKaryawan = :IdKaryawan, NamaBank = :NamaBank, Norekening = :Norekening, TglCreate = :TglCreate, UserUpdate = :UserUpdate";
							$queryBank = $db->prepare($sqlBank);
							$queryBank->bindParam('IdKaryawan', $IdKaryawan);
							$queryBank->bindParam('NamaBank', $NamaBank);
							$queryBank->bindParam('Norekening', $Norekening);
							$queryBank->bindParam('TglCreate', $date);
							$queryBank->bindParam('UserUpdate', $UserUpdate);
							$queryBank->execute();
						}

						for($i=0; $i < COUNT($_POST['Tingkat']); $i++){
							$KodePendidikan = $_POST['Tingkat'][$i];
							$Jurusan = $_POST['Jurusan'][$i];
							$Tahun = $_POST['Tahun'][$i];
							$sqlPendidikan = "INSERT INTO tb_list_pendidikan SET IdKaryawan = :IdKaryawan, KodePendidikan = :KodePendidikan, Jurusan = :Jurusan, Tahun = :Tahun, TglCreate = :TglCreate, UserUpdate = :UserUpdate";
							$queryPendidikan = $db->prepare($sqlPendidikan);
							$queryPendidikan->bindParam('IdKaryawan', $IdKaryawan);
							$queryPendidikan->bindParam('KodePendidikan', $KodePendidikan);
							$queryPendidikan->bindParam('Jurusan', $Jurusan);
							$queryPendidikan->bindParam('Tahun', $Tahun);
							$queryPendidikan->bindParam('TglCreate', $date);
							$queryPendidikan->bindParam('UserUpdate', $UserUpdate);
							$queryPendidikan->execute();
						}
						echo "sukses"; exit(); 
					} else { echo "gagal"; }
					
					exit();
				} catch (PDOException $e) {
					echo $e->getMessage();
					exit();
				}
				
				
				break;
			case 'update':
				try {
					$sql = "UPDATE tb_karyawan SET  IdCabang = :IdCabang,  NamaKaryawan = :NamaKaryawan, TglLahir = :TglLahir, TptLahir = :TptLahir,  UnitTugas = :UnitTugas, NoKtp = :NoKtp, JenisKelamin = :JenisKelamin, NoHp = :NoHp, Jabatan = :Jabatan, BpjsKes = :BpjsKes, BpjsTk = :BpjsTk, RekeningUtama = :RekeningUtama, TMTCabang = :TMTCabang, TMTIsu = :TMTIsu, PendidikanTerakhir = :PendidikanTerakhir, Agama = :Agama, UkuranBaju = :UkuranBaju, UkuranSepatu = :UkuranSepatu, Alamat = :Alamat, TglUpdate = :TglUpdate, UserUpdate = :UserUpdate WHERE IdKaryawan = :IdKaryawan";
					$query = $db->prepare($sql);
					$query->bindParam("IdCabang", $IdCabang);
					$query->bindParam("NamaKaryawan", $NamaKaryawan);
					$query->bindParam("TglLahir", $TglLahir);
					$query->bindParam("TptLahir", $TptLahir);
					$query->bindParam("UnitTugas", $UnitTugas);
					$query->bindParam("NoKtp", $NoKtp);
					$query->bindParam("JenisKelamin", $JenisKelamin);
					$query->bindParam("NoHp", $NoHp);
					$query->bindParam("Jabatan", $Jabatan);
					$query->bindParam("BpjsKes", $BpjsKes);
					$query->bindParam("BpjsTk", $BpjsTk); 
					$query->bindParam("RekeningUtama", $RekeningUtama);
					$query->bindParam("TMTCabang", $TMTCabang);
					$query->bindParam("TMTIsu", $TMTIsu);
					$query->bindParam("PendidikanTerakhir", $PendidikanTerakhir);
					$query->bindParam("Agama", $Agama);
					$query->bindParam("UkuranBaju", $UkuranBaju);
					$query->bindParam("UkuranSepatu", $UkuranSepatu);
					$query->bindParam("Alamat", $Alamat);
					$query->bindParam("TglUpdate", $date);
					$query->bindParam("UserUpdate", $UserUpdate);
					$query->bindParam("IdKaryawan", $IdKaryawan);
					$query->execute();
					$IdKaryawan = $db->lastInsertId();
					$Pesan = "<b>".$_SESSION['NamaUser']."</b>, Berhasil Menambah Data Karyawan dengan NIK <b>$NIK<b>!";
					AddLog($_SESSION['IdUser'],$Pesan);
					if($query){ echo "sukses"; exit(); } else { echo "gagal"; }
				} catch (PDOException $e) {
					echo $e->getMessage();
				}
				break;
			case 'delete':
				$sql = "DELETE FROM tb_karyawan WHERE IdKaryawan = :IdKaryawan";
				$query = $db->prepare($sql);
				$query->bindParam("IdKaryawan", $IdKaryawan);
				$query->execute();
				$Pesan = "<b>".$_SESSION['NamaUser']."</b>, Berhasil Menghapus Data Karyawan!";
					AddLog($_SESSION['IdUser'],$Pesan);
				if($query){ 
					$db->query("DELETE FROM tb_list_bank WHERE IdKaryawan = '$IdKaryawan'");
					$db->query("DELETE FROM tb_list_pendidikan WHERE IdKaryawan = '$IdKaryawan'");
					echo "sukses"; exit(); } else { echo "gagal"; }
				break;
		}
		print_r($_POST);
		break;
	
	case 'UploadData':
		$data=array();
		$IdCabang = $_POST['IdCabang'];
		$StatusKaryawan = $_POST['StatusKaryawan'];
		$sqlKaryawan = $db->query("SELECT MAX(RIGHT(NIK,4)) as NIK FROM tb_karyawan ORDER BY IdKaryawan DESC");
		$rowKaryawan = $sqlKaryawan->rowCount();
		if($rowKaryawan > 0){
		    $r = $sqlKaryawan->fetch(PDO::FETCH_ASSOC);
		    $ID = intval($r['NIK']);
		}else{
		    $ID = 0;
		}
		if(isset($_FILES['File'])){
		    $target = basename($_FILES['File']['name']) ;
		    $exten = end(explode(".",$target));
		    if($exten == 'xls'){
		        move_uploaded_file($_FILES['File']['tmp_name'], $target);
                chmod($_FILES['File']['name'],0777);
                // mengambil isi file xls
                $datas = new Spreadsheet_Excel_Reader($_FILES['File']['name'],false);
                // menghitung jumlah baris data yang ada
                $jumlah_baris = $datas->rowcount($sheet_index=0);
                $berhasil=0;
                $gagal=0;
                $PosisiNIK = 1;
                for ($i=6; $i<=$jumlah_baris; $i++){
                    $NIK = ($ID + $PosisiNIK);
                    // menangkap data dan memasukkan ke variabel sesuai dengan kolumnya masing-masing
                    $NamaKaryawan     = $datas->val($i, 3);
                    $Jabatan   = $datas->val($i, 4);
                    $NamaBank  = $datas->val($i, 5);
                    $Norekening = $datas->val($i, 6);
                    $RekeningUtama = $NamaBank."#".$Norekening;
                    $TMTCabang = $datas->val($i, 7);
                    $TMTIsu = $datas->val($i, 8);
                    $Agama = $datas->val($i, 9);
                    $JenisKelamin = $datas->val($i, 10);
                    $BpjsKes = $datas->val($i, 11);
                    $BpjsTk = $datas->val($i, 12);
                    $NoKtp = $datas->val($i, 13);
                    $TptLahir = $datas->val($i, 14);
                    $TglLahir = $datas->val($i, 15);
                    $Alamat = $datas->val($i, 16);
                    $NoHp = $datas->val($i, 17);
                    $PendidikanTerakhir = $datas->val($i, 18);
                    $NoNpwp = $datas->val($i, 19);
                    $NamaIbu = $datas->val($i, 20);
                    $Email = $datas->val($i, 21);
                    /* CREATE NIK */
                    $bulan = explode("-",$TglLahir)[1];
                    $tahun = substr(explode("-",$TglLahir)[0],2,2);
                    $NIKDepan = $StatusKaryawan.$bulan.$tahun;
                    $NIK = sprintf('%04d',$NIK);
                    $NIK = $NIKDepan.$NIK;
                   
                    
                    if($TglLahir != "" AND $NoKtp != ""){
                        $CekData = $db->query("SELECT COUNT(IdKaryawan) as tot FROM tb_karyawan WHERE NoKtp = '$NoKtp'")->fetch(PDO::FETCH_ASSOC);
                        if($CekData['tot'] <= 0){
                            $sql = "INSERT INTO tb_karyawan SET NIK = :NIK, IdCabang = :IdCabang, NamaKaryawan = :NamaKaryawan, Jabatan = :Jabatan, RekeningUtama = :RekeningUtama, TMTCabang = :TMTCabang, TMTIsu = :TMTIsu, Agama = :Agama, JenisKelamin = :JenisKelamin, BpjsKes = :BpjsKes, BpjsTk = :BpjsTk, NoKtp = :NoKtp, TptLahir = :TptLahir, TglLahir = :TglLahir, Alamat = :Alamat, NoHp = :NoHp, PendidikanTerakhir = :PendidikanTerakhir, NoNpwp = :NoNpwp, NamaIbu = :NamaIbu, Email = :Email";
                            $stmt = $db->prepare($sql);
                            $stmt->bindParam("NIK", $NIK);
                            $stmt->bindParam("IdCabang", $IdCabang);                            
                            $stmt->bindParam("NamaKaryawan", $NamaKaryawan);
                            $stmt->bindParam("Jabatan", $Jabatan);
                            $stmt->bindParam("RekeningUtama", $RekeningUtama);
                            $stmt->bindParam("TMTCabang", $TMTCabang);
                            $stmt->bindParam("TMTIsu", $TMTIsu);
                            $stmt->bindParam("Agama", $Agama);
                            $stmt->bindParam("JenisKelamin", $JenisKelamin);
                            $stmt->bindParam("BpjsKes", $BpjsKes);
                            $stmt->bindParam("BpjsTk", $BpjsTk);
                            $stmt->bindParam("NoKtp", $NoKtp);
                            $stmt->bindParam("TptLahir", $TptLahir);
                            $stmt->bindParam("TglLahir", $TglLahir);
                            $stmt->bindParam("Alamat", $Alamat);
                            $stmt->bindParam("NoHp", $NoHp);
                            $stmt->bindParam("PendidikanTerakhir", $PendidikanTerakhir);
                            $stmt->bindParam("NoNpwp", $NoNpwp);
                            $stmt->bindParam("NamaIbu", $NamaIbu);
                            $stmt->bindParam("Email", $Email);
                            $stmt->execute();
                            if($stmt){
                                $data['item']['berhasil'][$berhasil]['NIK'] = $NIK;
                                $data['item']['berhasil'][$berhasil]['NoKtp'] = $NoKtp;
                                $berhasil++;
                                $PosisiNIK++;
                            }
                        }else{
                            $data['item']['gagal'][$gagal]['NIK'] = $NIK;
                            $data['item']['gagal'][$gagal]['NoKtp'] = $NoKtp;
                            $gagal++;
                        }
                    }else{
                        $data['item']['gagal'][$gagal]['NIK'] = '-';
                        $data['item']['gagal'][$gagal]['NoKtp'] = $NoKtp;
                        $gagal++;
                    }
                }
                $JumBerhasil = COUNT($data['item']['berhasil']);
                $JumGagal = COUNT($data['item']['gagal']);
                $Pesan = "<b>".$_SESSION['NamaUser']."</b>, Telah Melakukan Upload Data Karyawan, <b>$JumBerhasil Berasil</b> dan <b>$JumGagal</b> Gagal!";
				AddLog($_SESSION['IdUser'],$Pesan);
                $data['msg'] = "sukses";
                // hapus kembali file .xls yang di upload tadi
                unlink($_FILES['File']['name']);
		    }else{
		    	$data['msg'] = 'filenotsupport';
		    }
		}else{
		    $data['msg'] = "filenotfound";
		}
		echo json_encode($data);
		
		break;
	
	case 'ShowData':
		$IdKaryawan = $_POST['IdKaryawan'];
		$sql = "SELECT tb_karyawan.*, tb_cabang.KodeCabang, tb_cabang.NamaCabang FROM tb_karyawan INNER JOIN tb_cabang ON tb_karyawan.IdCabang = tb_cabang.IdCabang WHERE IdKaryawan = :IdKaryawan";
		$stmt = $db->prepare($sql);
		$stmt->bindParam('IdKaryawan', $IdKaryawan);
		$stmt->execute();
		$res = $stmt->fetch(PDO::FETCH_ASSOC);
		echo json_encode($res);
		break;
	
	case "LoadDataBank":
		$IdKaryawan = $_POST['IdKaryawan'];
		$sql = "SELECT NamaBank, Norekening FROM tb_list_bank WHERE IdKaryawan = :IdKaryawan";
		$query = $db->prepare($sql);
		$query->bindParam("IdKaryawan", $IdKaryawan);
		$query->execute();
		$row = $query->rowCount();
		$data=array();
		if($row > 0){
			while($r = $query->fetch(PDO::FETCH_ASSOC)){
				$data[] = $r['NamaBank']."#".$r['Norekening'];
			}
			echo json_encode($data);
		}else{
			$data['msg']="nodata";
			echo json_encode($data);
		}
		break;
	case "LoadPendidikan":
		$IdKaryawan = $_POST['IdKaryawan'];
		$sql = "SELECT tb_list_pendidikan.Jurusan, tb_pendidikan.NamaPendidikan FROM tb_list_pendidikan INNER JOIN tb_pendidikan ON tb_list_pendidikan.KodePendidikan = tb_pendidikan.KodePendidikan WHERE tb_list_pendidikan.IdKaryawan = :IdKaryawan";
		$query = $db->prepare($sql);
		$query->bindParam("IdKaryawan", $IdKaryawan);
		$query->execute();
		$row = $query->rowCount();
		$data=array();
		if($row > 0){
			while($r = $query->fetch(PDO::FETCH_ASSOC)){
				$data[] = $r['NamaPendidikan']."#".$r['Jurusan'];
			}
			echo json_encode($data);
		}else{
			$data['msg']="nodata";
			echo json_encode($data);
		}
		break;
	case "GetNewNIK" :
	   $sql = "SELECT RIGHT(NIK,4) as NIK FROM tb_karyawan ORDER BY IdKaryawan DESC LIMIT 1";
	   $query = $db->query($sql);
	   $row = $query->rowCount();
	   if($row > 0){
	       $r = $query->fetch(PDO::FETCH_ASSOC);
	       $ID = intval($r['NIK']) + 1;
	       $ID = sprintf('%04d', $ID);
	   }else{
	       $ID = "0001";
	   }
	   echo $ID;
		break;
}

?>
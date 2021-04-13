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
		$sql = "SELECT a.Nik, CONCAT(b.Nama,', ',b.Title) as Nama,  SUM(a.Bobot) as Bobot, SUM(a.Realisasi) as Realisasi, SUM(a.Nilai) as Nilai, DATE_FORMAT(a.TglCreate, '%Y-%m') as Periode FROM tb_rkb a INNER JOIN tb_pejabat b ON a.Nik = b.Nik  GROUP BY a.Nik, DATE_FORMAT(a.TglCreate, '%Y-%m') ";
		$query = $db->query($sql);
		$JumRow = $query->rowCount();
		if($JumRow > 0){
			while ($res = $query->fetch(PDO::FETCH_ASSOC)) { 
				$CekStatusNilai = GetStatusNilai($res['Nik'], $res['Periode']);
				$JumRkb = $db->query("SELECT IdRkb FROM tb_rkb WHERE Nik = '$res[Nik]' AND DATE_FORMAT(TglCreate, '%Y-%m') = '$res[Periode]'")->rowCount();
				if($CekStatusNilai > 0){
					$ID = base64_encode($res['Nik']."#".$res['Periode']);
					$aksi = $_SESSION['Level'] == "author" ? "" : " <a href='Cetak/RekapRKB.php?ID=".$ID."' target='_blnak' class='btn btn-xs btn-success' data-toggle='tooltip' title='Cetak Data'><i class='fa fa-print'></i></a>";

					$BobotB = $db->query("SELECT SUM(a.Bobot) as Bobot, SUM(a.Target) as Targets , SUM(b.Realisasi) as Realisasi, SUM(b.Nilai) as Nilai FROM tb_indikator_kp a INNER JOIN tb_nilai_kp b ON a.IdIkp = b.IdIkp WHERE b.Nik = '$res[Nik]' AND DATE_FORMAT(b.TglCreate, '%Y-%m') = '$res[Periode]'")->fetch(PDO::FETCH_ASSOC);

					$FinalSkor = ($res['Nilai']*80)/100 + ($BobotB['Nilai'] * 20)/100; 
					$row['No'] = $no;
					$row['Periode'] = $res['Periode'];
					$row['NamaPejabat'] = $res['Nama'];
					$row['BobotA'] = $res['Bobot'];
					$row['TargetA'] = $JumRkb * 100;
					$row['RealisasiA'] = $res['Realisasi'];
					$row['NilaiA'] = $res['Nilai'];
					$row['BobotB'] = $BobotB['Bobot'];
					$row['TargetB'] = $BobotB['Targets'];
					$row['RealisasiB'] = $BobotB['Realisasi'];
					$row['NilaiB'] = round($BobotB['Nilai'],2);
					$row['FinalSkor'] = round($FinalSkor,2);
					$row['Aksi'] = "<a class='btn btn-xs btn-primary' href='javascript:void(0)' data-toggle='tooltip' title='Detail Data' onclick=\"DetailData('".$res['Nik']."','".$res['Periode']."')\"><i class='fa fa-eye'></i></a> ".$aksi;
					$data['data'][] = $row;
					$no++;
				}
			}
		}else{
			$data['data']='';
		}
		echo json_encode($data);
		break;
	case "DataPejabat":
		$Nik = $_POST['Nik'];
		$sql = "SELECT a.Nama, a.Nik, a.TptLahir, a.TglLahir, a.KelasJabatan, b.NamaJabatan, a.NoHP, a.Alamat FROM tb_pejabat a INNER JOIN tb_jabatan_pejabat b ON a.IdJabatan = b.IdJabatan WHERE a.Nik = '$Nik'";
		$query = $db->query($sql);
		$data = $query->fetch(PDO::FETCH_ASSOC);

		$res['Nama'] = $data['Nama'];
		$res['NIK'] = $data['Nik'];
		$res['TTL'] = $data['TptLahir'].", ".tgl_indo($data['TglLahir']);
		$res['KJ'] = $data['NamaJabatan']."/".$data['KelasJabatan'];
		$res['NOHP'] = $data['NoHP'];
		$res['ALAMAT'] = $data['Alamat'];
		echo json_encode($res);
		break;
	
	case "DetailKP":
		$Nik = $_POST['Nik'];
		$Periode = $_POST['Periode'];
		$sql = "SELECT RKB, Bobot, Realisasi, Nilai FROM tb_rkb WHERE Nik = '$Nik' AND DATE_FORMAT(TglCreate, '%Y-%m') = '$Periode' ORDER BY IdRkb ASC";
		$query = $db->query($sql);
		$res = array();
		$rs=array();
		$no=1;
		$TotBobot=0;
		$TotTarget=0;
		$TotRealisasi=0;
		$TotNilai=0;
		while($data = $query->fetch(PDO::FETCH_ASSOC)){
			$rs['No'] = $no;
			$rs['RKB'] = $data['RKB'];
			$rs['Bobot'] = $data['Bobot'];
			$rs['Target'] =100;
			$rs['Realisasi'] = $data['Realisasi'];
			$rs['Nilai'] = $data['Nilai'];

			$TotBobot= $TotBobot + $data['Bobot'];
			$TotTarget=$TotTarget + 100;
			$TotRealisasi=$TotRealisasi + $data['Realisasi'];
			$TotNilai=$TotNilai + $data['Nilai'];
			$res['item'][]=$rs;
			$no++;
		}
		$res['TotBobot'] = $TotBobot;
		$res['TotTarget'] = $TotTarget;
		$res['TotRealisasi'] = $TotRealisasi;
		$res['TotNilai'] = $TotNilai;
		$res['TotNilai'] = $TotNilai;
		$res['msg'] = "success";

		
		echo json_encode($res);
		break;
	
	case "DetailKonpetensi":
		$Nik = $_POST['Nik'];
		$Periode = $_POST['Periode'];
		$sql = "SELECT a.Bobot, a.Kompetensi, a.Target, b.Realisasi, b.Nilai FROM tb_indikator_kp a INNER JOIN tb_nilai_kp b ON a.IdIkp = b.IdIkp WHERE b.Nik = '$Nik' AND DATE_FORMAT(b.TglCreate, '%Y-%m') = '$Periode'";
		$query = $db->query($sql);
		$res = array();
		$rs=array();
		$no=1;
		$TotBobot=0;
		$TotTarget=0;
		$TotRealisasi=0;
		$TotNilai=0;
		while($data = $query->fetch(PDO::FETCH_ASSOC)){
			$rs['No'] = $no;
			$rs['Kompetensi'] = $data['Kompetensi'];
			$rs['Bobot'] = $data['Bobot'];
			$rs['Target'] =$data['Target'];
			$rs['Realisasi'] = $data['Realisasi'];
			$rs['Nilai'] = $data['Nilai'];

			$TotBobot= $TotBobot + $data['Bobot'];
			$TotTarget=$TotTarget + $data['Target'];
			$TotRealisasi=$TotRealisasi + $data['Realisasi'];
			$TotNilai=$TotNilai + $data['Nilai'];
			$res['item'][]=$rs;
			$no++;
		}
		$res['TotBobot'] = $TotBobot;
		$res['TotTarget'] = $TotTarget;
		$res['TotRealisasi'] = $TotRealisasi;
		$res['TotNilai'] = $TotNilai;
		$res['TotNilai'] = $TotNilai;
		$res['msg'] = "success";

		
		echo json_encode($res);
		break;

}

?>
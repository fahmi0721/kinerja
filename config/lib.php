<?php
	function hari_indo($tgl) {
		$tanggal = $tgl;
		$day = date('D', strtotime($tanggal));
		$dayList = array(
			'Sun' => 'Minggu',
			'Mon' => 'Senin',
			'Tue' => 'Selasa',
			'Wed' => 'Rabu',
			'Thu' => 'Kamis',
			'Fri' => 'Jumat',
			'Sat' => 'Sabtu'
		);
		return $dayList[$day];
	}

	//====== FUNGSI JAM INDONESIA ===///
	function jam_indo($tgl){
		$timestamp = strtotime($tgl);
		return date("h.i A", $timestamp);
	}

	//====== FUNGSI TANGGAL INDONESIA ===///
	function tgl_indo($tgl){
		$tanggal = substr($tgl,8,2);
		$bulan = getBulan(substr($tgl,5,2));
		$tahun = substr($tgl,0,4);
		return $tanggal.' '.$bulan.' '.$tahun;		 
	}

	function getBulan($bln){
	    switch ($bln){
	        case 1:
	          return "Januari";
	          break;
	        case 2:
	          return "Februari";
	          break;
	        case 3:
	          return "Maret";
	          break;
	        case 4:
	          return "April";
	          break;
	        case 5:
	          return "Mei";
	          break;
	        case 6:
	          return "Juni";
	          break;
	        case 7:
	          return "Juli";
	          break;
	        case 8:
	          return "Agustus";
	          break;
	        case 9:
	          return "September";
	          break;
	        case 10:
	          return "Oktober";
	          break;
	        case 11:
	          return "November";
	          break;
	        case 12:
	          return "Desember";
	          break;
	    }
	}


	function tgl_indo1($tgl){
		$tanggal = substr($tgl,8,2);
		$bulan = getBulan1(substr($tgl,5,2));
		$tahun = substr($tgl,0,4);
		return $tanggal.' '.$bulan.' '.$tahun;		 
	}

	function getBulan1($bln){
	    switch ($bln){
	        case 1:
	          return "Jan"; break;
	        case 2:
	          return "Feb"; break;
	        case 3:
	          return "Mar"; break;
	        case 4:
	          return "Apr"; break;
	        case 5:
	          return "Mei"; break;
	        case 6:
	          return "Jun"; break;
	        case 7:
	          return "Jul"; break;
	        case 8:
	          return "Agus"; break;
	        case 9:
	          return "Sep"; break;
	        case 10:
	          return "Okt"; break;
	        case 11:
	          return "Nov"; break;
	        case 12:
	          return "Des"; break;
	    }
	}

	function NotInjek($str){
		$str = addslashes($str);
		$str = filter_var($str,FILTER_SANITIZE_STRING);
		return  $str;
	}

	function AddLog($IdUser,$Pesan){
		$Tgl = date('Y-m-d H:i:s');
		$sql = "INSERT INTO tb_log SET IdUser = :IdUser, Pesan = :Pesan, Tgl = :Tgl";
		$query = $GLOBALS['db']->prepare($sql);
		$query->bindParam("IdUser", $IdUser);
		$query->bindParam("Pesan", $Pesan);
		$query->bindParam("Tgl", $Tgl);
		$query->execute();
		return true;
	}

	function rupiah($rp,$prefix=null){
		$cek = explode(".",$rp);
		$cek = count($cek);
		$pc = $cek > 1 ? 3 : 0;
		$a = number_format($rp, $pc, ',','.');
		return $prefix != null ? $prefix." ". $a : $a;
	}

	

	function StatusNilaiMK($Nik,$Periode){
		$sql = "SELECT StatusNilai FROM tb_rkb WHERE Nik = '$Nik' AND DATE_FORMAT(TglCreate, '%Y-%m') = '$Periode'";
		$query = $GLOBALS['db']->query($sql);
		$row = $query->rowCount();
		$Status = 0;
		if($row>0){
			while($r = $query->fetch(PDO::FETCH_ASSOC)){
				if($r['StatusNilai'] == 1){
					$Status = 1;
				}
			}
		}else{
			$Status = 0;
		}
		return $Status;
	}

	function StatusNilaiKomepetensi($Nik,$Periode){
		$sql = "SELECT StatusNilai FROM tb_nilai_kp WHERE Nik = '$Nik' AND DATE_FORMAT(TglCreate, '%Y-%m') = '$Periode'";
		$query = $GLOBALS['db']->query($sql);
		$row = $query->rowCount();
		$Status = 0;
		if($row>0){
			while($r = $query->fetch(PDO::FETCH_ASSOC)){
				if($r['StatusNilai'] == 1){
					$Status = 1;
				}
			}
		}else{
			$Status = 0;
		}
		return $Status;
	}

	function GetStatusNilai($Nik,$Periode){
		$Status = 0;
		if(StatusNilaiMK($Nik,$Periode) == 1){
			$Status = 1 ;
		}
		if(StatusNilaiKomepetensi($Nik,$Periode) == 1){
			$Status = 1 ;
		}
		return $Status;
	}

	function Terbilang($anka){
		$x = abs($anka);
		$angka = array('','Satu','Dua','Tiga','Empat','Lima','Enam','Tujuh','Delapan','Sembilan','Sepuluh','Sebelas');
		$tep = " ";
		if($x < 12){
			$tep = " ". $angka[$x];
		} else if($x < 20){
			$tep = Terbilang($x - 10). " Belas";
		} else if($x < 100){
			$tep = Terbilang($x / 10). " Puluh". Terbilang($x % 10);	
		} else if($x < 200){
			$tep = " Seratus". Terbilang($x - 100);	
		} else if($x < 1000){
			$tep = Terbilang($x / 100). " Ratus". Terbilang($x % 100);	
		} else if($x < 2000){
			$tep = " Seribu". Terbilang($x - 100);	
		} else if($x < 1000000){
			$tep = Terbilang($x / 1000). " Ribu". Terbilang($x % 1000);	
		} else if($x < 1000000000){
			$tep = Terbilang($x / 1000000). " Juta". Terbilang($x % 1000000);	
		}

		return $tep;
	}

	function angka($str){
		$str = preg_replace( '/[^0-9]/', '', $str );
		return $str;
	}

	function angkaDecimal($str){
		$str = preg_replace( '/[^,0-9]/', '', $str );
		$str = str_replace(",",".",$str);
		return $str;
	}

	function GetAtasan($IdJabatan){
		$sql = "SELECT a.Nama,b.NamaJabatan FROM tb_aprove_mkp c INNER JOIN tb_jabatan_pejabat b ON c.IdJabatanAprove = b.IdJabatan INNER JOIN tb_pejabat a ON a.IdJabatan = b.IdJabatan  WHERE IdJabatanRKB LIKE '%$IdJabatan%'";
		$r = $GLOBALS['db']->query($sql)->fetch(PDO::FETCH_ASSOC);
		$data = array();
		$data['Nama'] = $r['Nama'];
		$data['NamaJabatan'] = $r['NamaJabatan'];

		return $data;
	}
	
	function TambahDaftarTagihan($IdTagihan,$PotonganPercent,$Potongan,$IdKaryawan){
		for($i=0; $i < count($PotonganPercent); $i++){
			$PP = str_replace(",",".",$PotonganPercent[$i]);
			$P = angka($Potongan[$i]);
			$sql = "INSERT INTO tb_daftar_tagihan SET IdTagihan = '$IdTagihan', IdKaryawan = '$IdKaryawan[$i]', PotonganPercent = '$PP', Potongan = '$P'";
			$query = $GLOBALS['db']->query($sql);
		}
		return true;
	}

	function UpdatehDaftarTagihan($PotonganPercent,$Potongan,$IdDaftarTagihan){
		for($i=0; $i < count($PotonganPercent); $i++){
			$PP = str_replace(",",".",$PotonganPercent[$i]);
			$P = angka($Potongan[$i]);
			$ID = angka($IdDaftarTagihan[$i]);
			$sql = "UPDATE tb_daftar_tagihan SET  PotonganPercent = '$PP', Potongan = '$P' WHERE IdDaftarTagihan = '$ID'";
			$query = $GLOBALS['db']->query($sql);
		}
		return true;
	}
	function UploadFile($NamaFile, $TmpFile, $UploadTo, $extensi, $filter){
		if(!empty($filter)){
			if(in_array($extensi,$filter)){
				move_uploaded_file($TmpFile,$UploadTo.$NamaFile);
				return true;
				exit;
			}else{
				return false;
				exit;

			}
		}else{
			move_uploaded_file($TmpFile,$UploadTo);
			return true;
			exit;

		}
	}

	function HapusFile($Files,$Dir){
		if(file_exists($Dir.$Files) && $Files != ""){
			unlink($Dir.$Files);
		}
		return true;
	}

	function CekNomorKontrak($NoomorKontrak){
		$sql = "SELECT COUNT(IdKontrak) as tot FROM tb_kontrak WHERE NomorKontrak = '$NoomorKontrak'";
		$row = $GLOBALS['db']->query($sql)->fetch(PDO::FETCH_ASSOC);
		if($row['tot'] > 0 ){
			return "found";
		}else{
			return "notfound";
		}
	}

	function IsertIntoListSementara($IdKontrak,$UserUpdate){
		$sqlOK = $GLOBALS['db']->query("SELECT * FROM tb_kontrak_list WHERE IdKontrak = '$IdKontrak'");
		while($dtOK = $sqlOK->fetch(PDO::FETCH_ASSOC)){
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
			$query = $GLOBALS['db']->prepare($sql);
			$query->bindParam('Berlaku',$dtOK['Berlaku']);
			$query->bindParam('Sampai',$dtOK['Sampai']);
			$query->bindParam('BpjsKes',$dtOK['BpjsKes']);
			$query->bindParam('BpjsKesPersen',$dtOK['BpjsKesPersen']);
			$query->bindParam('BpjsTk',$dtOK['BpjsTk']);
			$query->bindParam('BpjsTkPersen',$dtOK['BpjsTkPersen']);
			$query->bindParam('Dplk',$dtOK['Dplk']);
			$query->bindParam('JaminanHariTua',$dtOK['JaminanHariTua']);
			$query->bindParam('JaminanHariTuaPersen',$dtOK['JaminanHariTuaPersen']);
			$query->bindParam('JaminanKecelakaanKerja',$dtOK['JaminanKecelakaanKerja']);
			$query->bindParam('JaminanKecelakaanKerjaPersen',$dtOK['JaminanKecelakaanKerjaPersen']);
			$query->bindParam('JaminanKematian',$dtOK['JaminanKematian']);
			$query->bindParam('JaminanKematianPersen',$dtOK['JaminanKematianPersen']);
			$query->bindParam('JaminanPensiun',$dtOK['JaminanPensiun']);
			$query->bindParam('JaminanPensiunPersen',$dtOK['JaminanPensiunPersen']);
			$query->bindParam('JasaPjtk',$dtOK['JasaPjtk']);
			$query->bindParam('JasaPjtkPersen',$dtOK['JasaPjtkPersen']);
			$query->bindParam('Jumlah',$dtOK['Jumlah']);
			$query->bindParam('LamaKontrak',$dtOK['LamaKontrak']);
			$query->bindParam('NamaList',$dtOK['NamaList']);
			$query->bindParam('PakaianKerja',$dtOK['PakaianKerja']);
			$query->bindParam('PakaianKerjaPersen',$dtOK['PakaianKerjaPersen']);
			$query->bindParam('Pesangon',$dtOK['PakaianKerja']);
			$query->bindParam('Thr',$dtOK['Thr']);
			$query->bindParam('Tagihan',$dtOK['Tagihan']);
			$query->bindParam('Tunjangan',$dtOK['Tunjangan']);
			$query->bindParam('UpahPokok',$dtOK['UpahPokok']);
			$query->bindParam('UangMakan',$dtOK['UangMakan']);
			$query->bindParam('UangMakanHari',$dtOK['UangMakanHari']);
			$query->bindParam('UangMakanPersen',$dtOK['UangMakanPersen']);
			$query->bindParam('UangTransport',$dtOK['UangTransport']);
			$query->bindParam('UangTransportPersen',$dtOK['UangTransportPersen']);
			$query->bindParam('UangTransportHari',$dtOK['UangTransportHari']);
			$query->bindParam('UserUpdate',$UserUpdate);
			$query->execute();
		}
	}

	function HapusListKontrak($IdKontrak){
		$sql = "DELETE FROM tb_kontrak_list WHERE IdKontrak = '$IdKontrak'";
		$query = $GLOBALS['db']->query($sql);
		if($query){
			return true;
		}else{
			return false;
		}
	}

	function InsertDataKontrakList($UserUpdate,$IdKontrak){
		$sqlSementara = $GLOBALS['db']->query("SELECT * FROM tb_kontrak_list_sementara WHERE UserUpdate = '$UserUpdate'");
		while($dtSementara = $sqlSementara->fetch(PDO::FETCH_ASSOC)){
			$sql = "INSERT INTO tb_kontrak_list SET
				IdKontrak = :IdKontrak,
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
			$query = $GLOBALS['db']->prepare($sql);
			$query->bindParam('IdKontrak',$IdKontrak);
			$query->bindParam('Berlaku',$dtSementara['Berlaku']);
			$query->bindParam('Sampai',$dtSementara['Sampai']);
			$query->bindParam('BpjsKes',$dtSementara['BpjsKes']);
			$query->bindParam('BpjsKesPersen',$dtSementara['BpjsKesPersen']);
			$query->bindParam('BpjsTk',$dtSementara['BpjsTk']);
			$query->bindParam('BpjsTkPersen',$dtSementara['BpjsTkPersen']);
			$query->bindParam('Dplk',$dtSementara['Dplk']);
			$query->bindParam('JaminanHariTua',$dtSementara['JaminanHariTua']);
			$query->bindParam('JaminanHariTuaPersen',$dtSementara['JaminanHariTuaPersen']);
			$query->bindParam('JaminanKecelakaanKerja',$dtSementara['JaminanKecelakaanKerja']);
			$query->bindParam('JaminanKecelakaanKerjaPersen',$dtSementara['JaminanKecelakaanKerjaPersen']);
			$query->bindParam('JaminanKematian',$dtSementara['JaminanKematian']);
			$query->bindParam('JaminanKematianPersen',$dtSementara['JaminanKematianPersen']);
			$query->bindParam('JaminanPensiun',$dtSementara['JaminanPensiun']);
			$query->bindParam('JaminanPensiunPersen',$dtSementara['JaminanPensiunPersen']);
			$query->bindParam('JasaPjtk',$dtSementara['JasaPjtk']);
			$query->bindParam('JasaPjtkPersen',$dtSementara['JasaPjtkPersen']);
			$query->bindParam('Jumlah',$dtSementara['Jumlah']);
			$query->bindParam('LamaKontrak',$dtSementara['LamaKontrak']);
			$query->bindParam('NamaList',$dtSementara['NamaList']);
			$query->bindParam('PakaianKerja',$dtSementara['PakaianKerja']);
			$query->bindParam('PakaianKerjaPersen',$dtSementara['PakaianKerjaPersen']);
			$query->bindParam('Pesangon',$dtSementara['PakaianKerja']);
			$query->bindParam('Thr',$dtSementara['Thr']);
			$query->bindParam('Tagihan',$dtSementara['Tagihan']);
			$query->bindParam('Tunjangan',$dtSementara['Tunjangan']);
			$query->bindParam('UpahPokok',$dtSementara['UpahPokok']);
			$query->bindParam('UangMakan',$dtSementara['UangMakan']);
			$query->bindParam('UangMakanHari',$dtSementara['UangMakanHari']);
			$query->bindParam('UangMakanPersen',$dtSementara['UangMakanPersen']);
			$query->bindParam('UangTransport',$dtSementara['UangTransport']);
			$query->bindParam('UangTransportPersen',$dtSementara['UangTransportPersen']);
			$query->bindParam('UangTransportHari',$dtSementara['UangTransportHari']);
			$query->bindParam('UserUpdate',$UserUpdate);
			$query->execute();
		}
		
	}

	function CekTotalTagihan($IdKontrak){
		$TotalTagihan = 0;
		$DataInduk = $GLOBALS['db']->query("SELECT SUM(Tagihan * Jumlah * LamaKontrak) as TotTagihan FROM tb_kontrak_list WHERE IdKontrak = '$IdKontrak'")->fetch(PDO::FETCH_ASSOC);
		$TotalTagihan = $TotalTagihan + $DataInduk['TotTagihan'];
		$sqlSub = "SELECT IdKontrak FROM tb_kontrak WHERE IdKontrakInduk = '$IdKontrak'";
		$query = $GLOBALS['db']->query($sqlSub);
		$row = $query->rowCount();
		if($row > 0){
			while($dt = $query->fetch(PDO::FETCH_ASSOC)){
				$DataInduk1 = $GLOBALS['db']->query("SELECT SUM(Tagihan * Jumlah * LamaKontrak) as TotTagihan FROM tb_kontrak_list WHERE IdKontrak = '$dt[IdKontrak]'")->fetch(PDO::FETCH_ASSOC);
				$TotalTagihan = $TotalTagihan + $DataInduk1['TotTagihan'];
			}
		}
		return $TotalTagihan;

	}
	
	function DeleteAllAddendum($IdKontrak){
		$sql = "DELETE FROM tb_kontrak WHERE IdKontrakInduk = '$IdKontrak'";
		$query = $GLOBALS['db']->query($sql);
		if($query){
			return true;
		}else{
			return false;
		}
	}

?>
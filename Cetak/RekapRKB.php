<?php 
include_once '../config/config.php';
require_once "../lib/dompdf-0.8.2/autoload.inc.php";
$ID= explode("#",base64_decode($_GET['ID']));
$Nik = $ID[0];
$Periode = $ID[1];
$Bul = explode("-",$Periode);
$Bulan = strtoupper(getBulan($Bul[1]));
$Tahun = $Bul[0];
$Pejabat = $db->query("SELECT a.Nama,a.Title, a.IdJabatan, b.NamaJabatan, a.KelasJabatan FROM tb_pejabat a INNER JOIN tb_jabatan_pejabat b ON a.IdJabatan = b.IdJabatan WHERE a.Nik = '$Nik'")->fetch(PDO::FETCH_ASSOC);
$KelasJabatan = $db->query("SELECT TKO,TKP FROM tb_kelas_jabatan WHERE KelasJabatan = '$Pejabat[KelasJabatan]'")->fetch(PDO::FETCH_ASSOC);

/** MK */
$sqlMK = "SELECT RKB, Bobot, Realisasi, Nilai FROM  tb_rkb WHERE Nik = '$Nik' AND DATE_FORMAT(TglCreate, '%Y-%m') = '$Periode'";
$queryMK = $db->query($sqlMK);
$rowMK = $queryMK->rowCount();
$resultMK="";
$TotNilai=0;
$TotBobot=0;
if($rowMK > 0){
    $No=1;
    while($resMK = $queryMK->fetch(PDO::FETCH_ASSOC)){
        $resultMK .= "<tr>";
            $resultMK .= "<td class='text-center'>$No</td>";
            $resultMK .= "<td>$resMK[RKB]</td>";
            $resultMK .= "<td class='text-center'>$resMK[Bobot]</td>";
            $resultMK .= "<td class='text-center'>100</td>";
            $resultMK .= "<td class='text-center'>$resMK[Realisasi]</td>";
            $resultMK .= "<td class='text-center'>$resMK[Nilai]</td>";
        $resultMK .= "</tr>";
        $TotBobot = $TotBobot + $resMK['Bobot'];
        $TotNilai = $TotNilai + $resMK['Nilai'];
        $No++;
    }
    $resultMK .= "<tr>";
        $resultMK .= "<th class='text-center'>&nbsp;</th>";
        $resultMK .= "<th>&nbsp;</th>";
        $resultMK .= "<th class='text-center'>$TotBobot</th>";
        $resultMK .= "<th class='text-center'></th>";
        $resultMK .= "<th class='text-center'>&nbsp;</th>";
    $resultMK .= "<th class='text-center'>$TotNilai</th>";
$resultMK .= "</tr>";
}else{
    $resultMK = '<tr><td colspan="6"><center>No Data</center></td></tr>';
}
$delapanpuluhPersen = ($TotNilai*80) / 100;


/** Kompetensi */
$sqlK = "SELECT a.Bobot, a.Kompetensi, a.Target, b.Realisasi, b.Nilai FROM tb_indikator_kp a INNER JOIN tb_nilai_kp b ON a.IdIkp = b.IdIkp WHERE b.Nik = '$Nik' AND DATE_FORMAT(b.TglCreate, '%Y-%m') = '$Periode'";
$queryK = $db->query($sqlK);
$rowK = $queryK->rowCount();
$resultK="";
$TotNilaiK=0;
$TotBobotK=0;

if($rowK > 0){
    $NoK=1;
    while($resK = $queryK->fetch(PDO::FETCH_ASSOC)){
        $resultK .= "<tr>";
            $resultK .= "<td class='text-center'>$NoK</td>";
            $resultK .= "<td>$resK[Kompetensi]</td>";
            $resultK .= "<td class='text-center'>$resK[Bobot]</td>";
            $resultK .= "<td class='text-center'>$resK[Target]</td>";
            $resultK .= "<td class='text-center'>$resK[Realisasi]</td>";
            $resultK .= "<td class='text-center'>".round($resK['Nilai'],2)."</td>";
        $resultK .= "</tr>";
        $TotBobotK = $TotBobotK + $resK['Bobot'];
        $TotNilaiK = $TotNilaiK + $resK['Nilai'];
        $NoK++;
    }
    $resultK .= "<tr>";
        $resultK .= "<td class='text-center'>&nbsp;</td>";
        $resultK .= "<td>&nbsp;</td>";
        $resultK .= "<th class='text-center'>$TotBobotK</th>";
        $resultK .= "<th class='text-center'></th>";
        $resultK .= "<th class='text-center'>&nbsp;</th>";
    $resultK .= "<th class='text-center'>".round($TotNilaiK,2)."</th>";
$resultK .= "</tr>";
}else{
    $resultK = '<tr><td colspan="6"><center>No Data</center></td></tr>';
}
$duapuluhPersen = round(($TotNilaiK*20) /100,2);
$SKOR = $delapanpuluhPersen + $duapuluhPersen;
$TKO = (100 * $KelasJabatan['TKO']) / 100;
$TKP = ($SKOR * $KelasJabatan['TKP'])/100;
$Tunjangan = $TKO + $TKP;
$HariIni = tgl_indo(date("Y-m-d"));

$Atasan = GetAtasan($Pejabat['IdJabatan']);
if($Periode == "2020-08" && $Nik = '179080005'){
    $Atasan['Nama'] = "AKHIRMAN";
    $Atasan['NamaJabatan'] = "DIREKTUR SDM, UMUM & KEUANGAN";
    
}

$data ="<head><meta http-equiv='Content-Type' content='text/html; charset=utf-8'>
    <title>Cetak Penilaian Kinerja Pegawai</title>
    <style>
        body { 
            font-family: sans-serif;
            font-size: 12px;
        }
        h4,p{
            margin:0;
        }
        .table{
            width:100%;
            font-family: sans-serif  ;
            font-size: 12px;
            border: 2px solid;
            border-collapse: collapse;
        }
        .tables{
            width:100%;
            font-family: sans-serif  ;
            font-size: 12px;
            border: 2px solid;
            border-collapse: collapse;
            
        }
        .table tr{
            border: 1px solid;
            border-collapse: collapse;
        }
        .table th, .table td{
            border: 1px solid;
            border-collapse: collapse;
            padding:5px;
        }
        .ttd{
            width:100%;
            font-size: 11px;
        }
        .text-center{
            text-align : center;
        }
        .single_record{
            page-break-after: always;
        }

    </style>    
</head>
<body>
    <h4><center>PENILAIAN KINERJA PEGAWAI PT INTAN SEJAHTERA UTAMA </center></h4>
    <h4><center>BULAN ".$Bulan." ".$Tahun."</center></h4>
    <br>
    <br>
    <p><b>$Pejabat[Nama], $Pejabat[Title] </b></p>
    <p><b>$Pejabat[NamaJabatan]</b></p>
    <br>
    <p><b>PENIALAIAN KINERJA</b></p>
    <table class='table'>
        <thead>
            <tr>
                <th width='3%' class='text-center '>NO</th>
                <th class='text-center'>RENCANA KERJA BULANAN</th>
                <th width='10%' class='text-center'>BOBOT</th>
                <th width='10%' class='text-center'>TARGET (%)</th>
                <th width='10%' class='text-center'>REALISASI</th>
                <th width='10%' class='text-center'>NILAI</th>
            </tr>
        </thead>
        <tbody>
            ".$resultMK."
        </tbody>
    </table>
    <br>
    <p><u><b>TARGET KINERJA</b></u></p>
    <ol>
        <li> 0% - 49.9% &nbsp;&nbsp;&nbsp;Realisasi tidak memenuhi sebagian besar dari target PKPB</li>
        <li> 50% - 74,9%&nbsp; Realisasi memenuhi sebagian dari target PKPB</li>
        <li> 75% - 94,9%&nbsp; Realisasi memenuhi sebagian besar dari target PKPB</li>
        <li> 95% - 100%&nbsp;&nbsp; Realisasi memenuhi dari target PKPB</li>
    </ol>
    <br>
    <br>
    <table class='ttd'>
        <tr>
            <td>&nbsp;</td>
            <td><center>Makassar, $HariIni</center></td>
        </tr>
        <tr>
            <td><center>PEJABAT PENILAI</center></td>
            <td><center>YANG DINILAI :</center></td>
        </tr>
        <tr>
            <td height='60px' valign='top'><center>".$Atasan['NamaJabatan']."</center></td>
            <td><center>&nbsp;</center></td>
        </tr>
        <tr>
            <td><center><u><b>".$Atasan['Nama']."</b></u></center></td>
            <td><center><u><b>$Pejabat[Nama]</b></u></center></td>
        </tr>
    </table>
    <div class='single_record'></div>

    <p><b>PENIALAIAN KOMOPETENSI PEGAWAI</b></p>
    <table class='table'>
        <thead>
            <tr>
                <th width='3%' class='text-center '>NO</th>
                <th class='text-center'>KOMPETENSI</th>
                <th width='10%' class='text-center'>BOBOT</th>
                <th width='10%' class='text-center'>TARGET (%)</th>
                <th width='10%' class='text-center'>REALISASI</th>
                <th width='10%' class='text-center'>NILAI</th>
            </tr>
        </thead>
        <tbody>
            ".$resultK."
        </tbody>
    </table>
    <br>
    <p><u><b>TARGET KOMPOTENSI</b></u></p>
    <ol>
        <li> Nilai 1 : Perilaku dibawah target level kompetensi</li>
        <li> Nilai 2 : Perilaku terkadang sesuai target level kompetensi</li>
        <li> Nilai 3 : Perilaku konsisten sesuai dengan target level kompetensi</li>
        <li> Nilai 4 : Perilaku konsisten di atas target level kompetensi</li>
    </ol>
    <br>
    <br>
    <table class='ttd'>
        <tr>
            <td>&nbsp;</td>
            <td><center>Makassar, $HariIni</center></td>
        </tr>
        <tr>
            <td><center>PEJABAT PENILAI</center></td>
            <td><center>YANG DINILAI :</center></td>
        </tr>
        <tr>
            <td height='60px' valign='top'><center>".$Atasan['NamaJabatan']."</center></td>
            <td><center>&nbsp;</center></td>
        </tr>
        <tr>
            <td><center><u><b>".$Atasan['Nama']."</b></u></center></td>
            <td><center><u><b>$Pejabat[Nama]</b></u></center></td>
        </tr>
    </table>
    <div class='single_record'></div>

    <table class='tables'>
        <tr>
            <td colspan='7' style='padding-top:20px;padding-left:3px'>PENILAIAN</td>
        </tr>
        <tr >
            <td style='padding-left:3px' width='3%'>a.</td>
            <th colspan='6' style='padding-left:3px'>Tunjangan Kinerja Organisasi (TKO)</th>
        </tr>
        <tr>
            <td></td>
            <td style='padding-left:3px' width='30%'>(Skor Unit Kerja x 100%) x Standar Tunjangan Kinerja</td>
            <td></td>
            <td align='right'>100</td>
            <td colspan='3' style='padding-left:3px'>maksimal 100%</td>
        </tr>
        <tr>
            <td colspan='7' height='10px'></td>
        </tr>
        <tr>
            <td style='padding-left:3px'>b.</td>
            <th colspan='6'>Tunjangan Kinerja Pegawai (TKP)</th>
        </tr>
        <tr>
            <td style='padding-left:3px'></td>
            <td >(Kinerja x 80%) + (Kompetensi x 20%)</td>
            <td width='8%'>Kinerja</td>
            <td width='10%' align='right'>".$delapanpuluhPersen."</td>
            <td colspan='3'></td>
        </tr>
        <tr>
            <td colspan='2'>&nbsp;</td>
            <td>Kompetensi</td>
            <td align='right' style='border-bottom:1px solid #777'>".$duapuluhPersen."</td>
            <td colspan='3'></td>
        </tr>
        <tr>
            <td colspan='2'>&nbsp;</td>
            <td>SKOR</td>
            <td align='right'>".$SKOR."</td>
            <td colspan='3'></td>
        </tr>
        <tr>
            <td colspan='7' height='10px'></td>
        </tr>
        <tr>
            <td colspan='2' style='padding-left:3px'>Besaran Tunjangan Kinerja Organisasi</td>
            <td>".rupiah($KelasJabatan['TKO'])."</td>
            <td align='right'>100</td>
            <td align='right'>".rupiah($TKO)."</td>
            <td style='padding-left:3px'>".$Pejabat['KelasJabatan']."</td>
            <td></td>
        </tr>
        <tr>
            <td colspan='2' style='padding-left:3px'>Besaran Tunjangan Kinerja Pegawai</td>
            <td>".rupiah($KelasJabatan['TKP'])." </td>
            <td align='right'>$SKOR</td>
            <td align='right' width='10%' style='border-bottom:1px solid #777'>".rupiah($TKP)."</td>
            <td style='padding-left:3px'>".$Pejabat['KelasJabatan']."</td>
            <td></td>
        </tr>
        <tr>
            <td style='padding-bottom:25px; padding-left:3px' colspan='4'>Jumlah Tunjangan Kinerja yang diterima <b>".Terbilang($Tunjangan)." Rupiah</b></td>
            <td align='right' valign='top'>".rupiah($Tunjangan)."</td>
            <td></td>
            <td ></td>
        </tr>
    </table>
    
    

</body>
</html>";

$dompdf = new Dompdf\Dompdf();

$dompdf->load_html($data);
//portrait / landscape
//$dompdf->loadHtml(html_entity_decode($data));
$dompdf->set_paper("a4", "landscape");
$dompdf->render();

$dompdf->stream("tes.pdf", array("Attachment" => false));
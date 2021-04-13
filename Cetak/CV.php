<?php 
session_start();
include_once '../config/config.php';
require_once "../lib/dompdf-0.8.2/autoload.inc.php";
$IdKaryawan = $_GET['IdKaryawan'];
$sqlCV = "SELECT * FROM tb_karyawan WHERE IdKaryawan = '$IdKaryawan'";
$query = $db->query($sqlCV);
$r = $query->fetch(PDO::FETCH_ASSOC);

$data =  "<html>
    <head>
        <title>".$r['NamaKaryawan']."</title>
        <style>
            * { font-family: Arial, Helvetica, sans-serif; font-size:10pt; }
            body{
                margin :5px;
                font-family: arial;
                font-size: 12px;
            }
            @page {
                size: A4;
                margin: 0;
                
            }
            .BoxUtama{
                border: 5px solid #2196f3; 
                padding: 10px;
            }
            .center{
                text-align: center;
            }
            .bold{
                font-weight: bolder;
            }
            small{
                postion: fixed;
                bottom:0;
                font-size: 8pt;
            }
        </style>

    </head>
    <body>
        <div class='BoxUtama'>
            <h1 class='center'><u style='font-size:16pt'>CURRICULMN VIATE</u></h1>
            <table width='100%' cellpadding='0' cellspacing='0'>
                <tr>
                    <td class='bold' width='20%'>Nama Lengkap</td>
                    <td width='2%'><center>:</center></td>";
                    $data .= "<td' width='40%'>".$r['NamaKaryawan']."</td>";
                    $data .= "<td rowspan='5' valign='top' style='text-align:right'>";
                        $data .= "<img src='LAKI-LAKI.jpeg' style='max-width: 150px'>";
                    $data .= "</td>
                </tr>
                <tr>
                    <td class='bold'>TMT ISEMA</td>
                    <td><center>:</center></td>";
                    $data .= "<td>".$r['TMTIsu']."</td>";
                $data .="</tr>
                <tr>
                    <td class='bold'>ALAMAT</td>
                    <td><center>:</center></td>";
                    $data .= "<td>".$r['Alamat']."</td>";
                $data .="</tr>
                <tr>
                    <td class='bold'>TELP/NOHP</td>
                    <td><center>:</center></td>";
                   $data .= "<td>".$r['NoHp']."</td>";
                $data .= "</tr>
                <tr>
                    <td class='bold'>JABATAN</td>
                    <td><center>:</center></td>";
                    $data .= "<td>".$r['Jabatan']."</td>";
                $data .= "</tr>
            </table>
            <hr>
            <h2><u><i>PERSONAL DATA</i></u></h2>
            <table width='100%' cellpadding='5' cellspacing='0'>
                <tr>
                    <td class='bold' width='20%'>NO KTP</td>
                    <td width='2%'>:</td>";
                    $data .= "<td>".$r['NoKtp']."</td>";
                $data .= "</tr>
                <tr>
                    <td class='bold'>AGAMA</td>
                    <td width='2%'>:</td> ";
                    $data .= "<td>".$r['Agama']."</td>";
                $data .= "</tr>
                <tr>
                    <td class='bold'>TTL</td>
                    <td width='2%'>:</td>";
                    $data .= "<td>".$r['TptLahir'].", ".$r['TglLahir']."</td>";
                $data .= "</tr>
                <tr>
                    <td class='bold'>JENIS KELAMIN</td>
                    <td width='2%'>:</td>";
                    $data .= "<td>".$r['JenisKelamin']."</td>";
                $data .= "</tr>
                <tr>
                    <td class='bold'>UNIT TUGAS</td>
                    <td width='2%'>:</td>";
                    $data .= "<td>".$r['UnitTugas']."</td>";
                $data .= "</tr>
                <tr>
                    <td class='bold'>NO BPJS KESHATAN</td>
                    <td width='2%'>:</td>";
                    $data .= "<td>".$r['BpjsKes']."</td>";
                $data .= "</tr>
                <tr>
                    <td class='bold'>NO BPJS KETENAGAKERJAAN</td>
                    <td width='2%'>:</td>";
                    $data .= "<td>".$r['BpjsTk']."</td>";
                $data .= "</tr>
                <tr>
                    <td class='bold'>UKURAN BAJU</td>
                    <td width='2%'>:</td>";
                    $data .= "<td>".$r['UkuranBaju']."</td>";
                $data .= "</tr>
                <tr>
                    <td class='bold'>UKURAN SEPATU</td>
                    <td width='2%'>:</td>";
                    $data .= "<td>".$r['UkuranSepatu']."</td>";
                $data .= "</tr>
            </table>
            <hr>
            <h2><u><i>RIWAYAT PENDIDIKAN</i></u></h2>
            <table width='100%' cellpadding='5' cellspacing='0'> ";
                $sqlListPendidkan = "SELECT tb_list_pendidikan.*, tb_pendidikan.NamaPendidikan FROM tb_list_pendidikan INNER JOIN tb_pendidikan ON tb_list_pendidikan.KodePendidikan = tb_pendidikan.KodePendidikan WHERE tb_list_pendidikan.IdKaryawan = '$IdKaryawan'";
                $queryPendidikan = $db->query($sqlListPendidkan);
                while($rPendidikan = $queryPendidikan->fetch(PDO::FETCH_ASSOC)){
                    $NamaPendidikan =$rPendidikan['NamaPendidikan'];
                    $Tahun = $rPendidikan['Tahun'];
                    $Jurusan = $rPendidikan['Jurusan'];
                $data .= "<tr>";
                    $data .="<td class='bold' width='20%'>".$NamaPendidikan." (".$rPendidikan['Tahun'].") </td>";
                    $data .= "<td width='2%'>:</td>";
                    $data .= "<td>(".$rPendidikan['Jurusan'].")</td>";
                $data .= "</tr>";
                }
                /*<tr>
                    <td class='bold' width='20%'>SD (2002-2008) </td>
                    <td>:</td>
                    <td>SD 16 INPRES TALAWE</td>
                </tr>
                <tr>
                    <td class='bold' width='20%'>SMP (2008-2011) </td>
                    <td>:</td>
                    <td>SMPN 1 MAROS UTARA</td>
                </tr>
                <tr>
                    <td class='bold' width='20%'>SMK (2011-2014) </td>
                    <td>:</td>
                    <td>SMKN 1 MAROS (RPL)</td>
                </tr>

                <tr>
                    <td class='bold' width='20%'>STRATA 1 (2014-2018) </td>
                    <td>:</td>
                    <td>STMIK AKBA (TEKNIK INFORMATIKA)</td>
                </tr>*/
            $data .= "</table>

        </div>

    </body>
</html>";

use Dompdf\Dompdf;
$dompdf = new Dompdf();

$dompdf->load_html($data);
//portrait / landscape
$dompdf->set_paper("a4", "landscape");
$dompdf->render();

$dompdf->stream($r['NamaKaryawan'].".pdf", array("Attachment" => false));

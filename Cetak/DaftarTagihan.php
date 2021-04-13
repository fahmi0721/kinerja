<?php 

include_once '../config/config.php';

require_once "../lib/dompdf-0.8.2/autoload.inc.php";

$Jabatan  = array();

$str = "";



$IdTagihan = addslashes($_GET['IdTagihan']);

/** TAGIHAN */

$Tagihan = $db->query("SELECT * FROM tb_tagihan WHERE IdTagihan = '$IdTagihan'")->fetch(PDO::FETCH_ASSOC);

$Periode = explode("-",$Tagihan['Periode']);



/** PAKET */

$Paket = $db->query("SELECT Paket FROM tb_paket_penagihan WHERE IdPaketPenagihan = '$Tagihan[IdPaketPenagihan]'")->fetch(PDO::FETCH_ASSOC);

$Pakets = explode(",",$Paket['Paket']);

foreach($Pakets as $pkt){

    $Jabatan[] = "'".$pkt."'";

}

$Jabatans = implode(",",$Jabatan);

$sql = "SELECT a.NamaKaryawan, b.NamaJabatan, c.*, d.Potongan, (c.Upah + c.BpjsTk + c.BpjsKes + c.PakaianKerja + c.Dplk + c.Thr + c.JasaPjtk) as JumTagihan FROM tb_karyawan a INNER JOIN tb_jabatan b ON a.Jabatan = b.KodeJabatan INNER JOIN tb_skema_gaji c ON a.IdKaryawan = c.IdKaryawan INNER JOIN tb_daftar_tagihan d ON a.IdKaryawan = d.IdKaryawan WHERE a.IdCabang = '$Tagihan[IdCabang]' AND a.Jabatan IN ($Jabatans) ORDER BY a.NamaKaryawan ASC";

$query = $db->query($sql);

$No=1;

$ToTalUpah=0;

$ToTalBpjsTk=0;

$ToTalBpjsKes=0;

$ToTalPakaian=0;

$ToTalThr=0;

$ToTalPesangon=0;

$ToTalDplk=0;

$ToTalPjtk=0;

$ToTalJmlTghn=0;

$ToTalPtg=0;

$ToTalPpn=0;

$ToTalTghnPpn=0;

while($r = $query->fetch(PDO::FETCH_ASSOC)){

    $JumTagihan = $r['JumTagihan'];

    $ppn = round(($JumTagihan * 10) / 100,0);

    $JumTagihanSppn = $JumTagihan + $ppn;

    $str .= "<tr>";

                $str .="<td widtd='3%' class='text-center'>".$No."</td>";

                $str .=" <td >$r[NamaKaryawan]</td>";

                $str .=" <td >$r[NamaJabatan]</td>";

              

                $str .=" <td align='right'>".rupiah($r['Upah'])."</td>";

                $str .=" <td align='right'>".rupiah($r['BpjsTk'])."</td>";

                $str .=" <td align='right'>".rupiah($r['BpjsKes'])."</td>";

                $str .=" <td align='right'>".rupiah($r['PakaianKerja'])."</td>";

                $str .=" <td align='right'>".rupiah($r['Thr'])."</td>";

                $str .=" <td align='right'>".rupiah($r['Pesangon'])."</td>";

                $str .=" <td align='right'>".rupiah($r['Dplk'])."</td>";

                $str .=" <td align='right'>".rupiah($r['JasaPjtk'])."</td>";

                $str .=" <td align='right'>".rupiah($JumTagihan)."</td>";

            $str .= "</tr>";

            $ToTalUpah= $ToTalUpah + $r['Upah'];

            $ToTalBpjsTk= $ToTalBpjsTk + $r['BpjsTk'];

            $ToTalBpjsKes= $ToTalBpjsKes + $r['BpjsKes'];

            $ToTalPakaian= $ToTalPakaian + $r['PakaianKerja'];

            $ToTalThr= $ToTalThr + $r['Thr'];

            $ToTalPesangon= $ToTalPesangon + $r['Pesangon'];

            $ToTalDplk= $ToTalDplk + $r['Dplk'];

            $ToTalPjtk= $ToTalPjtk + $r['JasaPjtk'];

            $ToTalJmlTghn= $ToTalJmlTghn + $JumTagihan;

            $ToTalPtg= $ToTalPtg + $r['Potongan'];

            $ToTalPpn= $ToTalPpn + $ppn;

            $ToTalTghnPpn= $ToTalTghnPpn + $JumTagihanSppn;



            /** Total Data */



            $No++;

}





$data ="<head>

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

            font-size: 10px;

            border: 2px solid;

            border-collapse: collapse;

        }

        .tables{

            width:100%;

            font-family: sans-serif  ;

            font-size: 10px;

            

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

    <h4><center>REKAP NAMA TENAGA OUTSOURCHING</center></h4>

    <h4><center>BULAN <span style='text-transform:uppercase'>".getBulan($Periode[1])."<span> ".$Periode[0]."</center></h4>

    <br>

    <br>

    <table class='table'>

        <thead>

            <tr>

                <th width='3%' class='text-center '>NO</th>

                <th class='text-center' width='10%'>NAMA KARYAWAN</th>

                <th class='text-center' width='15%'>UNIT KERJA</th>

                <th class='text-center'>UPAH</th>

                <th class='text-center'>BPJS KETENAGAKERJ AAN</th>

                <th class='text-center'>BPJS KESEHATAN</th>

                <th class='text-center'>PAKAIAN KERJA & PERLENGKAPAN</th>

                <th class='text-center'>THR</th>

                <th class='text-center'>PESANGON</th>

                <th class='text-center'>DPLK</th>

                <th class='text-center'>JASA PJTK</th>

                <th class='text-center'>JUMLAH TAGIHAN</th>

            </tr>

        </thead>

        <tbody>".$str."</tbody>

        <tbody>

            <tr>

                <th width='3%' class='text-center '>&nbsp;</th>

                <th>&nbsp;</th>

                <th>&nbsp;</th>

                <th align='right'>".rupiah($ToTalUpah)."</th>

                <th align='right'>".rupiah($ToTalBpjsTk)."</th>

                <th align='right'>".rupiah($ToTalBpjsKes)."</th>

                <th align='right'>".rupiah($ToTalPakaian)."</th>

                <th align='right'>".rupiah($ToTalThr)."</th>

                <th align='right'>".rupiah($ToTalPesangon)."</th>

                <th align='right'>".rupiah($ToTalDplk)."</th>

                <th align='right'>".rupiah($ToTalPjtk)."</th>

                <th align='right'>".rupiah($ToTalJmlTghn)."</th>

            </tr>

        </tbody>

       

    </table>

    <br>

    <table width='35%'  style='margin-left: 25px; font-size:10px;  font-family: sans-serif; border:1px solid #777;'>

        <tr>

            <td><b>TOTAL BIAYA TENAGA KERJA PERBULAN</b></td>

            <td align='right' style='padding-right:10px'><b>".rupiah($ToTalJmlTghn)."</b></td>

        </ tr>

        <tr>

            <td>PPN 10%</td>

            <td align='right' style='padding-right:10px'>".rupiah($ToTalPpn)."<p style='border-bottom:1px solid #777;'></p></td>

        </tr>

        <tr>

            <td>&nbsp;</td>

            <td align='right' style='padding-right:10px'><b>".rupiah($ToTalTghnPpn)."</b></td>

        </tr>

    </table>



    <br>

    <table class='tables'>

        <tbody>

            <tr>

                <td class='text-center'>&nbsp;</td>

                <td class='text-center'>&nbsp;</td>

                <td class='text-center'>&nbsp;</td>

                <td class='text-center'>Makassar, ".tgl_indo(date('Y-m-d'))."</td>

            </tr>

            <tr>

                <td class='text-center'>Direktur SDM, Umum, dan Keuangan</td>

                <td class='text-center'>Manager Keuangan</td>

                <td class='text-center'> Manager SDM dan Umum</td>

                <td class='text-center'> Untuk daftar tersebut, </td>

            </tr>

            <tr>

                <td class='text-center' height='120px'>Akhirman</td>

                <td class='text-center'> Rustini</td>

                <td class='text-center'>  A. Sastrawaty</td>

                <td class='text-center'>  Fahmi Idrus</td>

            </tr>

        </tbody>

    </table>





</body>

</html>";



$dompdf = new Dompdf\Dompdf();



$dompdf->load_html($data);

//portrait / landscape

//$dompdf->loadHtml(html_entity_decode($data));

$dompdf->set_paper("a4", "landscape");

$dompdf->render();



$dompdf->stream($Tagihan['JudulTagihan'].".pdf", array("Attachment" => false));
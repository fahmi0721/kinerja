<?php
require_once "../config/config.php";
require_once "../lib/dompdf-0.8.2/autoload.inc.php";
$html ="
<html>
    <head>
        <style>
            * { font-family: Arial, Helvetica, sans-serif; font-size:10pt; }
            @page { margin: 50px 50px 50px 50px; }

            .BoxUtama{
                border:1px solid #000000;
                position: relative;
                padding:5px;
                margin-bottom: 20px;
            }
            .BoxLeft{
                position: absolute;
                right: 0;
                top: 0;
                width: 150px;
            }
            .BoxNomor{
                width: 90%;
                padding : 3px 0 3px 0;
                border:1px solid #000000;
                text-align:center;
            }
            .BoxJumlah{
                width: 30%;
                padding : 5px 0  5px 10px;
                border:2px solid #000000;
                text-align:center:
            }
            .kwitansi p{
                line-height:18px;
            }
        </style>
    </head>
    <body>";
         for($i=0; $i < 2; $i++){ 

        $html .= "<div class='BoxUtama'>
            <img src='../img/Logo.jpg' style='max-width:150px; margin-top:8px; margin-left:15px;'>
            <div class='BoxLeft'>
                <b>KWITANSI</b>
                <div class='BoxNomor'>
                    257A/IX/2019
                </div>
            </div>
           <br>
           <br>
            <table width='100%' class='kwitansi' cellpadding='0' cellspacing='0' border='0'>
                <tr>
                    <td width='25%'>Telah Terima Dari </td>
                    <td width='2%' align='center'>:</td>
                    <td width='70%'>PT Pelabuhan Indonesia IV (Persero) Cabang Terminal Petikemas Makassar</td>
                </tr>
                <tr>
                    <td >Uang Sejumlah </td>
                    <td align='center'>:</td>
                    <td>Tiga ratus juta tujuh puluh enma juta dua ratus ujuh ribu tuju ratus rupiah</td>
                </tr>
                <tr>
                    <td  valign='top'>Untuk Pembayaran </td>
                    <td valign='top' align='center'>:</td>
                    <td valign='top'>Pengadaan tenaga Outsourcing di PT Pelabuhan Indonesia IV (Persero) Cabang Terminal Petikemas Makassar, bulan Februari 2019. Berdasarkan perjanjian No. 03/HK.301/ISEMA-2019</td>
                </tr>
                <tr>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                </tr>
                <tr>
                    <td align='right'><b>Jumlah</b></td>
                    <td>&nbsp;</td>
                    <td><div class='BoxJumlah'>Rp. 376.207.700</div></td>
                </tr>
                <tr>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                </tr>
                <tr>
                    <td colspan='3'>Diteranferkan ke Rekening Mandiri</td>
                </tr>
                <tr>
                    <td>Atas Nama</td>
                    <td align='center'>:</td>
                    <td>PT INTAN SEJAHTERA UTAMA</td>
                </tr>
                <tr>
                    <td>No. Rekening</td>
                    <td align='center'>:</td>
                    <td>152002222218</td>
                </tr>
                <tr>
                    <td>Bank</td>
                    <td align='center'>:</td>
                    <td>MANDIRI</td>
                </tr>
                <tr>
                    <td colspan='3'>
                        <div style='float:right; width:250px;'>
                            <p align='center' style='margin-bottom:70px'>Makassar, 11 Februari 2019</p>
                            <p align='center' style='margin:0'><b>AKHIRMAN</b></p>
                            <div style='margin:0 auto;padding:0; border:1px solid #000; width:80%'></div>
                            <p align='center' style='margin:5px 0 0 0'>Direktur SDM, Umum & Keuangan</p>
                        </div>
                        <span style='Clear:both'></span>
                    </td>
                </tr>
            </table>
            
        </div>";
         } 
    $html .= "</body>
</html>";

use Dompdf\Dompdf;
$dompdf = new Dompdf();

$dompdf->load_html($html);
//portrait / landscape
$dompdf->set_paper("a4", "portrait");
$dompdf->render();

$dompdf->stream("Invoice.pdf", array("Attachment" => false));

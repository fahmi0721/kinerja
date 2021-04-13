<?php
require_once "../config/config.php";
require_once "../lib/dompdf-0.8.2/autoload.inc.php";
$IdInvoice = addslashes($_GET['IdInvoice']);

$sql = "SELECT tb_vendor.NamaVendor, tb_vendor.UMP, tb_vendor.JumlahTenagaKerja, tb_invoice.* FROM tb_vendor INNER JOIN tb_invoice ON tb_vendor.KodeVendor = tb_invoice.KodeVendor WHERE tb_invoice.IdInvoice = '$IdInvoice'";
$dt = $db->query($sql)->fetch(PDO::FETCH_ASSOC);
$ExpPeriode = explode("-",$dt['Periode']);
$Periode = getBulan($ExpPeriode[0])." ".$ExpPeriode[1];
$detail = GenerateTagihan($dt['UMP'],$dt['JumlahTenagaKerja']);
$html = "<html>
    <head>    
        <title>Cetak Invoice ".$dt['NamaVendor']." ".$Periode."</title>
        <style>
            * { font-family: Arial, Helvetica, sans-serif; font-size:10pt; }
            @page { margin: 50px 50px 50px 50px; }
            .borderTop { border-top: solid 1px #000;}
            .borderLeft { border-left: solid 1px #000;}
            .borderRight { border-right: solid 1px #000;}
            .borderBottom { border-bottom: solid 1px #000;}
            .PaddingLeft { padding-left: 17px;}
            .PaddingRight { padding-right: 17px;}
            .ul{ margin:0 0 10px 0; padding:0;}
            ul.ul > li{ list-style-position : inside; list-style-type: none; }
            ul.ul > li:before{  display: inline-block; content: '-'; width: 2em; margin-left: 1px;  }
            .pull-left { float:left; }
            .pull-right { float:right; }
            .clearfix { clear:both; }
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
                padding : 5px 0 5px 0;
                border:1px solid #000000;
                text-align:center:
            }
            .BoxJumlah{
                width: 30%;
                padding : 5px 0  5px 10px;
                border:2px solid #000000;
                text-align:center:
            }
           
            .kwitansi p{
                line-height:18px;
                padding:0;
                margin:0;
            }
        </style>
    </head>
    <body>
        <center><img src='Logo.jpg' style='max-width:150px'></center>
        <br>
        <table width='100%' cellpadding='0' cellspacing='0'>
            <tr>
                <td width='15%'>Nomor</td>
                <td width='5%' align='center'>:</td>
                <td width='40%'>".$dt['Nomor']."</td>
                <td width='5%'></td>
                <td>Makassar, ".tgl_indo($dt['TglInvoice'])."</td>
            </tr>
            <tr>
                <td>Lampiran</td>
                <td width='5%' align='center'>:</td>
                <td> 1 (satu) rangkap</td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <td>Perihal</td>
                <td width='5%' align='center'>:</td>
                <td> Permohonan Pembayaran</td>
                <td></td>
                <td>Kepada</td>
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td></td>
                <td>Yth,</td>
                <td>General Manager</td>
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td>".$dt['NamaVendor']."</td>
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td>di</td>
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td>Tempat</td>
            </tr>
        </table>
        <br>
        <table width='100%' cellpadding='0' cellspacing='0'>
            <tr>
                <td width='3%' valign='top'>1.</td>
                <td valign='top'>
                    <p style='margin:0 0 10px 0'>Bersama ini kami sampaikan tagihan biaya tenaga kerja berdasarkan perjanjian No. 03/HK.301/ISEMA-2019, untuk bulan ".$Periode." sebagai berikut :</p>
                </td>
            </tr>
            <tr>
                <td width='3%' valign='top'></td>
                <td>
                    <table width='98%' cellpaddin='0' cellspacing='0'>
                        <tr>
                            <td class='borderTop borderLeft  borderBottom' align='center' width='5%'><b>NO</b></td>
                            <td class='borderTop borderLeft  borderBottom' align='center'><b>URAIAN</b></td>
                            <td class='borderTop borderLeft borderRight  borderBottom' align='center'><b>BIAYA PERORANGAN</b></td>
                        </tr>
                        <tr>
                            <td class='borderLeft' align='center'>&nbsp;</td>
                            <td class='borderLeft' align='center'></td>
                            <td class='borderLeft borderRight'></td>
                        </tr>
                        <tr>
                            <td class='borderLeft' align='center'>1</td>
                            <td class='borderLeft PaddingLeft'>Upah Tenaga Kerja</td>
                            <td class='borderLeft borderRight PaddingLeft PaddingRight'>
                                <span class='pull-left'>Rp. </span>
                                <span class='pull-right'>".rupiah1($detail['UPT'])."</span>
                                <span class='clearfix'></span>
                            </td>
                        </tr>
                        <tr>
                            <td class='borderLeft' align='center'>2</td>
                            <td class='borderLeft PaddingLeft'>BPJS Ketenagakerjaan</td>
                            <td class='borderLeft borderRight PaddingLeft PaddingRight'>
                                <span class='pull-left'>Rp. </span>
                                <span class='pull-right'>".rupiah1($detail['BPJSTK'])."</span>
                                <span class='clearfix'></span>
                            </td>
                        </tr>
                        <tr>
                            <td class='borderLeft' align='center'>3</td>
                            <td class='borderLeft PaddingLeft'>BPJS Kesehatan</td>
                            <td class='borderLeft borderRight PaddingLeft PaddingRight'>
                                <span class='pull-left'>Rp. </span>
                                <span class='pull-right'>".rupiah1($detail['BPJSKES'])."</span>
                                <span class='clearfix'></span>
                            </td>
                        </tr>
                        <tr>
                            <td class='borderLeft' align='center'>4</td>
                            <td class='borderLeft PaddingLeft'>Pakaian Kerja &amp; Perlengkapan</td>
                            <td class='borderLeft borderRight PaddingLeft PaddingRight'>
                                <span class='pull-left'>Rp. </span>
                                <span class='pull-right'>".rupiah1($detail['Pakaian'])."</span>
                                <span class='clearfix'></span>
                            </td>
                        </tr>
                        <tr>
                            <td class='borderLeft' align='center'>5</td>
                            <td class='borderLeft PaddingLeft'>Tunjagan Hari Raya (THR)</td>
                            <td class='borderLeft borderRight PaddingLeft PaddingRight'>
                                <span class='pull-left'>Rp. </span>
                                <span class='pull-right'>".rupiah1($detail['THR'])."</span>
                                <span class='clearfix'></span>
                            </td>
                        </tr>
                        <tr>
                            <td class='borderLeft' align='center'>6</td>
                            <td class='borderLeft PaddingLeft'>Uang Pesangon</td>
                            <td class='borderLeft borderRight PaddingLeft PaddingRight'>
                                <span class='pull-left'>Rp. </span>
                                <span class='pull-right'>".rupiah1($detail['UangPesangon'])."</span>
                                <span class='clearfix'></span>
                           </td>
                        </tr>
                        <tr>
                            <td class='borderLeft' align='center'>7</td>
                            <td class='borderLeft PaddingLeft'>Iuran Dana Pensiun Lembaga Keuangan(DPLK)</td>
                            <td class='borderLeft borderRight PaddingLeft PaddingRight'>
                                <span class='pull-left'>Rp. </span>
                                <span class='pull-right'>".rupiah1($detail['DPLK'])."</span>
                                <span class='clearfix'></span>
                            </td>
                        </tr>
                        <tr>
                            <td class='borderLeft' align='center'>8</td>
                            <td class='borderLeft PaddingLeft'>Jasa Perusahaan Jasa Tenaga Kerja (PJTK)</td>
                            <td class='borderLeft borderRight PaddingLeft PaddingRight'>
                                <span class='pull-left'>Rp. </span>
                                <span class='pull-right'>".rupiah1($detail['PJTK'])."</span>
                                <span class='clearfix'></span>
                            </td>
                        </tr>

                        <tr>
                            <td class='borderLeft' align='center'>&nbsp;</td>
                            <td class='borderLeft'></td>
                            <td class='borderLeft borderRight PaddingLeft PaddingRight'>
                                <div width='90%' style='border-top:1px solid #000; margin: 0 0 5px 0'></div>
                                <span class='pull-left'>Rp. </span>
                                <span class='pull-right'>".rupiah1($detail['Total1'])."</span>
                                <span class='clearfix'></span>
                            </td>
                        </tr>

                        <tr>
                            <td class='borderLeft' align='center'></td>
                            <td class='borderLeft PaddingLeft'>Dibulatkan</td>
                            <td class='borderLeft borderRight PaddingLeft PaddingRight'>
                                <span class='pull-left'>Rp. </span>
                                <span class='pull-right'>".rupiah1($detail['Dibulatkan'])."</span>
                                <span class='clearfix'></span>
                           </td>
                        </tr>
                        <tr>
                            <td class='borderLeft' align='center'>&nbsp;</td>
                            <td class='borderLeft PaddingLeft'>PPN 10%</td>
                            <td class='borderLeft borderRight PaddingLeft PaddingRight'>
                                <span class='pull-left'>Rp. </span>
                                <span class='pull-right'>".rupiah1($detail['Ppn10_1'])."</span>
                                <span class='clearfix'></span>
                            </td>
                        </tr>
                        <tr>
                            <td class='borderLeft' align='center'>&nbsp;</td>
                            <td class='borderLeft PaddingLeft'>Biaya Per Orang</td>
                            <td class='borderLeft borderRight PaddingLeft PaddingRight'>
                                <span class='pull-left'>Rp. </span>
                                <span class='pull-right'>".rupiah1($detail['BiayaPerOrang'])."</span>
                                <span class='clearfix'></span>
                            </td>
                        </tr>
                        <tr>
                            <td class='borderLeft' align='center'>&nbsp;</td>
                            <td class='borderLeft'></td>
                            <td class='borderLeft borderRight'></td>
                        </tr>

                        <tr>
                            <td class='borderLeft' align='center'>&nbsp;</td>
                            <td class='borderLeft'><b>A. BIAYA SELURUH TENAGA KERJA</b></td>
                            <td class='borderLeft borderRight'></td>
                        </tr>
                        <tr>
                            <td class='borderLeft' align='center'>&nbsp;</td>
                            <td class='borderLeft PaddingLeft'>Jumlah Tenaga Kerja ".$dt['JumlahTenagaKerja']." Orang</td>
                            <td class='borderLeft borderRight PaddingLeft PaddingRight'>
                                <span class='pull-left'>Rp. </span>
                                <span class='pull-right'>".rupiah1($detail['Total'])."</span>
                                <span class='clearfix'></span>
                            </td>
                        </tr>
                        <tr>
                            <td class='borderLeft' align='center'>&nbsp;</td>
                            <td class='borderLeft PaddingLeft'>PPN 10%</td>
                            <td class='borderLeft borderRight PaddingLeft PaddingRight'>
                                <span class='pull-left'>Rp. </span>
                                <span class='pull-right'>".rupiah1($detail['Ppn10_2'])."</span>
                                <span class='clearfix'></span>
                            </td>
                        </tr>
                        <tr>
                            <td class='borderLeft' align='center'>&nbsp;</td>
                            <td class='borderLeft '></td>
                            <td class='borderLeft borderRight PaddingLeft PaddingRight'>
                                <div width='90%' style='border-top:1px solid #000;  margin: 0 0 5px 0'></div>
                                <span class='pull-left'><b>Rp. </b></span>
                                <span class='pull-right'><b>".rupiah1($detail['SubTotal'])."</b></span>
                                <span class='clearfix'></span>
                                
                        </tr>
                        
                        <tr>
                            <td class='borderLeft  borderBottom' align='center'>&nbsp;</td>
                            <td class='borderLeft  borderBottom' align='center'></td>
                            <td class='borderLeft borderRight  borderBottom'></td>
                        </tr>
                    </table>
                    <br>
                </td>
            </tr>
            <tr>
                <td width='3%' valign='top'>2.</td>
                <td>
                    <p style='margin:0 0 5px 0;'>Tersebut 1(satu) diatas mohon dapat dibayarkan tagihan tersebut sebesar Rp. ".rupiah1($detail['SubTotal']).".-</p>
                    <p style='margin:0 0 5px 0;'>(".Terbilang($detail['SubTotal'])." Rupiah)</p>
                    <p style='margin:0 0 10px 0; line-height:20px;' >sudah termasuk PPN 10% ke <b>Bank Mandiri an. PT Intan Sejahtera Utama dengan No. Rek. 1520022222018</b></p>
                </td>
            </tr>
            <tr>
                <td width='3%' valign='top'>3.</td>
                <td>
                    <p style='margin:0 0 5px 0'>Sebagai bahan kelengkapan terlampir dokumen sebagai berikut:</p>
                    <ul class='ul'>
                        <li>Kwitansi Pembayaran</li>
                        <li>Faktur Pajak</li>
                        <li>PPN 10%</li>
                    </ul>
                </td>
            </tr>
            <tr>
                <td width='3%' valign='top'>4.</td>
                <td>
                    <p style='margin:0 0 10px 0'>Demikian surat permohonan kami, atas bantuan dan kerjasamanya diucakpan terimakasih.</p>
                </td>
            </tr>
            <tr>
                <td width='3%' valign='top'></td>
                <td style='position:relative'>
                   <div style='padding:0; position:absolute; right:0px; top:0; width:250px'>
                        <p align='center' style='margin-bottom:50px'>Hormat Kami,</p>
                        <p align='center' style='margin:0'><b>AKHIRMAN</b></p>
                        <div style='margin:0 auto;padding:0; border:1px solid #000; width:60%'></div>
                        <p align='center' style='margin:5px 0 0 0'>Direktur SDM, Umum & Keuangan</p>
                   </div>
                </td>
            </tr>
        </table>
        <footer style='position:fixed; bottom:15px; text-align:center; width:100%;'>
            <p style='margin:3px 0 0 0; font-size:10px; font-family:sans-serif'>PT. INTAN SEJAHTERA UTAMA</p>
            <p style='margin:3px 0 0 0; font-size:10px;'>(MEMBER OF PELINDO4 GROUP)</p>
            <p style='margin:0 0 0 0; font-size:10px;'>1<sup style='font-size:10px;'>st</sup>Floor-Head Office Jl. Soekarno No. 1 Makassar 90173 Telepon (0411) 3616549</p>
            <p style='margin:3px 0 0 0; font-size:10px;'>Kontak Pos 1040 Email : Intansejahterautama@gmail.com</p>
        </footer>
<div style='page-break-after:always;'></div>
        ";
for($i=0;$i<2;$i++){
$html .="

<div class='BoxUtama'>
            <img src='Logo.jpg' style='max-width:150px; margin-top:8px; margin-left:15px;'>
            <div class='BoxLeft'>
                <b>KWITANSI</b>
                <div class='BoxNomor'>
                ".$dt['Nomor']."
                </div>
            </div>
           <br>
           <br>
            <table width='100%' class='kwitansi' cellpadding='0' cellspacing='0' border='0'>
                <tr>
                    <td width='25%'>Telah Terima Dari </td>
                    <td width='2%' align='center'>:</td>
                    <td width='70%'><p>".$dt['NamaVendor']."<p></td>
                </tr>
                <tr>
                    <td>Uang Sejumlah </td>
                    <td align='center'>:</td>
                    <td><p>".Terbilang($dt['JumlahTagihan'])." Rupiah<p></td>
                </tr>
                <tr>
                    <td valign='top'>Untuk Pembayaran </td>
                    <td valign='top' align='center'>:</td>
                    <td valign='top'><p>Pengadaan tenaga Outsourcing di ".$dt['NamaVendor'].", bulan ".$Periode.". Berdasarkan perjanjian No. 03/HK.301/ISEMA-2019<p></td>
                </tr>
                <tr>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                </tr>
                <tr>
                    <td align='right'><b>Jumlah</b></td>
                    <td>&nbsp;</td>
                    <td><div class='BoxJumlah'>".rupiah($dt['JumlahTagihan'], "Rp. ")."</div></td>
                </tr>
                <tr>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                </tr>
                <tr>
                    <td colspan='3'>Ditransferkan ke Rekening Mandiri</td>
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
                            <p align='center' style='margin-bottom:70px'>Makassar, ".tgl_indo($dt['TglInvoice'])."</p>
                            <p align='center' style='margin:0'><b>AKHIRMAN</b></p>
                            <div style='margin:0 auto;padding:0; border:1px solid #000; width:80%'></div>
                            <p align='center' style='margin:5px 0 0 0'>Direktur SDM, Umum & Keuangan</p>
                        </div>
                        <div style='clear:both'></div>
                    </td>
                </tr>
            </table>
            
        </div>";
}
$html .="<div style='width:100%; height:100px; background:#fff; position:fixed; bottom:-50px;'></div>";
$html .="
    </body>

</html>";

use Dompdf\Dompdf;
$dompdf = new Dompdf();

$dompdf->load_html($html);
//portrait / landscape
$dompdf->set_paper("a4", "portrait");
$dompdf->render();

$dompdf->stream("Invoice.pdf", array("Attachment" => false));
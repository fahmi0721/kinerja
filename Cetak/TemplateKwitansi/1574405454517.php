<?php
    function Tes($r){
        
        $Lampiran = "1 (satu) Rangkap";
        $Perihal = "Permohonan Pembayaran"; 
        $Periode = "September";
        $DiTujukanKe = "PT. PELINDO IV (Persero)<br>Cabang Terminal Petikemas Makassar";

        /** Perjanjian */
        $Perjanjian[] = array("perjanjian No.","02/HK.301/TPM-2019");
        $Perjanjian[] = array("Addendum I No.","03/HK.301/ISEMA-2018");
        $Perjanjian[] = array("Addendum II No.","03/HK.301/ISEMA-2018");
        $Perjanjian[] = array("Addendum III No.","03/HK.301/ISEMA-2018");
        $TotDasar = count($Perjanjian);
        $TotDasarFirst =  $TotDasar-1;
        $dasar = "";
        for($i=0; $i<$TotDasarFirst; $i++){
            $dasar .= $Perjanjian[$i][0]." ".$Perjanjian[$i][1].", "; 
        }
        $dasar .= "dan ".$Perjanjian[$TotDasarFirst][0]." ".$Perjanjian[$TotDasarFirst][1];

        $html = "<html>
            <head>    
                <title>Surat Tagihan</title>
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
                <center><img src='../img/Logo.jpg' style='max-width:150px'></center>
                <br>
                <table width='100%' cellpadding='0' cellspacing='0'>
                    <tr>
                        <td width='15%'>Nomor</td>
                        <td width='5%' align='center'>:</td>
                        <td width='40%'></td>
                        <td width='5%'></td>
                        <td>Makassar, ".tgl_indo(date('Y-m-d'))."</td>
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
                        <td>".$DiTujukanKe."</td>
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
                            <p style='margin:0 0 10px 0'>Bersama ini kami sampaikan tagihan biaya tenaga kerja berdasarkan ".$dasar.", untuk bulan ".$Periode." sebagai berikut :</p>
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
                                        <span class='pull-right'>0</span>
                                        <span class='clearfix'></span>
                                    </td>
                                </tr>
                                <tr>
                                    <td class='borderLeft' align='center'>2</td>
                                    <td class='borderLeft PaddingLeft'>BPJS Ketenagakerjaan</td>
                                    <td class='borderLeft borderRight PaddingLeft PaddingRight'>
                                        <span class='pull-left'>Rp. </span>
                                        <span class='pull-right'>0</span>
                                        <span class='clearfix'></span>
                                    </td>
                                </tr>
                                <tr>
                                    <td class='borderLeft' align='center'>3</td>
                                    <td class='borderLeft PaddingLeft'>BPJS Kesehatan</td>
                                    <td class='borderLeft borderRight PaddingLeft PaddingRight'>
                                        <span class='pull-left'>Rp. </span>
                                        <span class='pull-right'>0</span>
                                        <span class='clearfix'></span>
                                    </td>
                                </tr>
                                <tr>
                                    <td class='borderLeft' align='center'>4</td>
                                    <td class='borderLeft PaddingLeft'>Pakaian Kerja &amp; Perlengkapan</td>
                                    <td class='borderLeft borderRight PaddingLeft PaddingRight'>
                                        <span class='pull-left'>Rp. </span>
                                        <span class='pull-right'>0</span>
                                        <span class='clearfix'></span>
                                    </td>
                                </tr>
                                <tr>
                                    <td class='borderLeft' align='center'>5</td>
                                    <td class='borderLeft PaddingLeft'>Tunjagan Hari Raya (THR)</td>
                                    <td class='borderLeft borderRight PaddingLeft PaddingRight'>
                                        <span class='pull-left'>Rp. </span>
                                        <span class='pull-right'>0</span>
                                        <span class='clearfix'></span>
                                    </td>
                                </tr>
                                <tr>
                                    <td class='borderLeft' align='center'>6</td>
                                    <td class='borderLeft PaddingLeft'>Uang Pesangon</td>
                                    <td class='borderLeft borderRight PaddingLeft PaddingRight'>
                                        <span class='pull-left'>Rp. </span>
                                        <span class='pull-right'>0</span>
                                        <span class='clearfix'></span>
                                </td>
                                </tr>
                                <tr>
                                    <td class='borderLeft' align='center'>7</td>
                                    <td class='borderLeft PaddingLeft'>Iuran Dana Pensiun Lembaga Keuangan(DPLK)</td>
                                    <td class='borderLeft borderRight PaddingLeft PaddingRight'>
                                        <span class='pull-left'>Rp. </span>
                                        <span class='pull-right'>0</span>
                                        <span class='clearfix'></span>
                                    </td>
                                </tr>
                                <tr>
                                    <td class='borderLeft' align='center'>8</td>
                                    <td class='borderLeft PaddingLeft'>Jasa Perusahaan Jasa Tenaga Kerja (PJTK)</td>
                                    <td class='borderLeft borderRight PaddingLeft PaddingRight'>
                                        <span class='pull-left'>Rp. </span>
                                        <span class='pull-right'>0</span>
                                        <span class='clearfix'></span>
                                    </td>
                                </tr>

                                <tr>
                                    <td class='borderLeft' align='center'>&nbsp;</td>
                                    <td class='borderLeft'></td>
                                    <td class='borderLeft borderRight PaddingLeft PaddingRight'>
                                        <div width='90%' style='border-top:1px solid #000; margin: 0 0 5px 0'></div>
                                        <span class='pull-left'>Rp. </span>
                                        <span class='pull-right'>0</span>
                                        <span class='clearfix'></span>
                                    </td>
                                </tr>

                                <tr>
                                    <td class='borderLeft' align='center'></td>
                                    <td class='borderLeft PaddingLeft'>Dibulatkan</td>
                                    <td class='borderLeft borderRight PaddingLeft PaddingRight'>
                                        <span class='pull-left'>Rp. </span>
                                        <span class='pull-right'>0</span>
                                        <span class='clearfix'></span>
                                </td>
                                </tr>
                                <tr>
                                    <td class='borderLeft' align='center'>&nbsp;</td>
                                    <td class='borderLeft PaddingLeft'>PPN 10%</td>
                                    <td class='borderLeft borderRight PaddingLeft PaddingRight'>
                                        <span class='pull-left'>Rp. </span>
                                        <span class='pull-right'>0</span>
                                        <span class='clearfix'></span>
                                    </td>
                                </tr>
                                <tr>
                                    <td class='borderLeft' align='center'>&nbsp;</td>
                                    <td class='borderLeft PaddingLeft'>Biaya Per Orang</td>
                                    <td class='borderLeft borderRight PaddingLeft PaddingRight'>
                                        <span class='pull-left'>Rp. </span>
                                        <span class='pull-right'>0</span>
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
                                    <td class='borderLeft PaddingLeft'>Jumlah Tenaga Kerja 0 Orang</td>
                                    <td class='borderLeft borderRight PaddingLeft PaddingRight'>
                                        <span class='pull-left'>Rp. </span>
                                        <span class='pull-right'>0</span>
                                        <span class='clearfix'></span>
                                    </td>
                                </tr>
                                <tr>
                                    <td class='borderLeft' align='center'>&nbsp;</td>
                                    <td class='borderLeft PaddingLeft'>PPN 10%</td>
                                    <td class='borderLeft borderRight PaddingLeft PaddingRight'>
                                        <span class='pull-left'>Rp. </span>
                                        <span class='pull-right'>0</span>
                                        <span class='clearfix'></span>
                                    </td>
                                </tr>
                                <tr>
                                    <td class='borderLeft' align='center'>&nbsp;</td>
                                    <td class='borderLeft '></td>
                                    <td class='borderLeft borderRight PaddingLeft PaddingRight'>
                                        <div width='90%' style='border-top:1px solid #000;  margin: 0 0 5px 0'></div>
                                        <span class='pull-left'><b>Rp. </b></span>
                                        <span class='pull-right'><b>0</b></span>
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
                            <p style='margin:0 0 5px 0;'>Tersebut 1(satu) diatas mohon dapat dibayarkan tagihan tersebut sebesar Rp. 0</p>
                            <p style='margin:0 0 5px 0;'>-</p>
                            <p style='margin:0 0 10px 0; line-height:20px;' >sudah termasuk PPN 10% ke <b>Bank BNI an. PT Intan Sejahtera Utama dengan No. Rek. 2018313114</b></p>
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
            </body>

        </html>";
        return $html;
    }

?>
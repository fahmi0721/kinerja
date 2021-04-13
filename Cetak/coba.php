<?php
require_once "../config/config.php";
require_once "../lib/dompdf-0.8.2/autoload.inc.php";
include "TemplateKwitansi/1574405454517.php";

$tes = Tes("ok");

use Dompdf\Dompdf;
$dompdf = new Dompdf();

$dompdf->load_html($tes);
//portrait / landscape
$dompdf->set_paper("a4", "portrait");
$dompdf->render();

$dompdf->stream("Invoice.pdf", array("Attachment" => false));


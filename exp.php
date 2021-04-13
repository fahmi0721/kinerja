<?php


$str = "1.200.000,20";
$str = preg_replace( '/[^,0-9]/', '', $str );
$str = str_replace(",",".",$str);

echo $str;
<?php
include "config/config.php";
session_start();
$Pesan = "<b>$_SESSION[NamaUser]</b> Berhasil Logout Dari Sistem";
AddLog($_SESSION['IdUser'],$Pesan);
session_destroy();
header("location:login.php");


<?php
require_once 'config/config.php';

$username = NotInjek($_POST['username']);
$password = NotInjek(md5("isma".$_POST['password']));

$sql = "SELECT * FROM tb_users WHERE UserName = :Username";
$query = $db->prepare($sql);
$query->bindParam("Username", $username);
$query->execute();
$rows = $query->rowCount();
session_start();
if($rows > 0){
	$r = $query->fetch(PDO::FETCH_ASSOC);
	if($r['Password'] == $password){
		$_SESSION['Username'] = $r['UserName'];
		$_SESSION['NamaUser'] = $r['NamaUser'];
		$_SESSION['Level'] = $r['Level'];
		$_SESSION['IdUser'] = $r['IdUser'];
		$Pesan = "<b>$r[NamaUser]</b> Berhasil Login Ke Sistem";
		AddLog($r['IdUser'],$Pesan);
		header("location:index.php");
	}else{
		$Pesan = "<b>$r[NamaUser]</b>, Password Yang Dimasukkan Salah.";
		AddLog($r['IdUser'],$Pesan);
		header("location:login.php?status=108&error=Password is incorrect");

	}
	
}else{
	$Pesan = "<b>$username</b>, Mencoba Masuk Ke Sistem";
	AddLog(0,$Pesan);
	header("location:login.php?status=108&error=Username is incorrect");
}

?>
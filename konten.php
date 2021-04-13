<?php 
$page = isset($_GET['page']) ? $_GET['page'] : null;

if($page != null){
	$page = str_replace("../", "", addslashes($page));
	$files = "inc/".$page."/detail.php";
	$listData =  array();
	$sql = "SELECT tb_menu.Direktori FROM tb_menu INNER JOIN tb_menu_akses ON tb_menu.IdMenu = tb_menu_akses.IdMenu WHERE tb_menu_akses.IdUser = '$_SESSION[IdUser]' AND tb_menu.Status = '1' GROUP BY tb_menu.IdMenu";
	$query = $db->query($sql);
	$row = $query->rowCount();
	if($row > 0){
		while($dt = $query->fetch(PDO::FETCH_ASSOC)){
			$listData[] = $dt['Direktori'];
		}
	}
	if($page == "MainMenu" AND $_SESSION['Level'] == "admin"){
		$listData = array("MainMenu");
	}
	if(file_exists($files) && in_array($page,$listData)){
		include_once $files;
	}else{
		echo "<div class='error-page'>
	        <h2 class='headline text-yellow' style='margin-top:-15px;'> 404</h2>

	        <div class='error-content'>
	          <h2><i class='fa fa-warning text-yellow'></i> Oops! Page not found.</h2>
	          <h5>Halaman Yang Anda Pilih Tidak Ditemukan Oleh Sistem. Silahkan Hubungi Administrator.</h5>
	        </div>
	      </div>";
	}
}else{
	include_once 'inc/home.php';
}
?>
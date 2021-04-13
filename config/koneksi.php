<?php
	$options = [
	    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
	    PDO::ATTR_CASE => PDO::CASE_NATURAL,
	    PDO::ATTR_ORACLE_NULLS => PDO::NULL_EMPTY_STRING
	];
	try{
		$db=new PDO('mysql:host=localhost;dbname=u7151562_sdm','u7151562_efisma','Efisma12345',$options);
	}catch(PDOException $e) {
	    die("Database connection failed: " . $e->getMessage());
	}
?>
<?php
	ob_start();
	
	include './dbconnect.php';
	include './fun.php';
	session_start();
	$nome=pre($_POST['nome']);
	if(isset($_POST['madre'])){
		$idmadre=pre($_POST['madre']);
	}else{$idmadre=0;}
	$id_utente=$_SESSION['id_utente'];
	mysqli_query($GLOBALS["___mysqli_ston"], "INSERT INTO `categoria` VALUES ('',$id_utente,'$nome',$idmadre)");

	header("location:./?p=categorie");
	ob_end_flush();
?>
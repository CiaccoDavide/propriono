<?php
	ob_start();
	
	include './dbconnect.php';
	include './fun.php';
	session_start();
	$tipo=pre($_GET['tipo']);
	$ammontare=pre($_POST['ammontare']);
	$idassociato=pre($_POST['associato']);
	apri_conto($_SESSION['id_utente'],$tipo,$ammontare,$tabella_1,$tabella_2,$idassociato);
	
	ob_end_flush();
?>
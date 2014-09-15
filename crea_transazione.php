<?php
	ob_start();
	
	include './dbconnect.php';
	include './fun.php';
	session_start();
	$tipo=pre($_GET['tipo']);
	$ammontare=pre($_POST['ammontare']);
	$idconto=pre($_POST['idconto']);
	$idcategoria=pre($_POST['categoria']);
	$id_utente=$_SESSION['id_utente'];
	if(isset($_POST['tipotrans']))
		$tipotrans=$_POST['tipotrans'];
	else
		$tipotrans='spesa';
	if(isset($_POST['descrizione']))
		$desc=$_POST['descrizione'];
	else
		$desc='';
	if(isset($_POST['data']))
		$data=str_replace($_POST['data'],"","-");
	else
		$data=date('Ymd');

	if(strcmp($tipo,"deposito")==0){
		if(strcmp($tipotrans,"spesa")==0){
			mysqli_query($GLOBALS["___mysqli_ston"], "INSERT INTO `transazione`(`ID_TRANS`, `DATA`, `DESCRIZIONE`, `IMPORTO`, `ID_CATEGORIA`, `ID_CONTO_CREDITO`, `ID_CONTO_DEP`, `ID_UTENTE`) VALUES ('',$data,'$desc',-$ammontare,'$idcategoria',0,$idconto,$id_utente)");
			mysqli_query($GLOBALS["___mysqli_ston"], "UPDATE `conto_dep` SET `USCITE`=`USCITE`+$ammontare WHERE ID_UTENTE LIKE $id_utente AND ID_CONTO_DEP LIKE $idconto");
		}elseif(strcmp($tipotrans,"entrata")==0){
			mysqli_query($GLOBALS["___mysqli_ston"], "INSERT INTO `transazione`(`ID_TRANS`, `DATA`, `DESCRIZIONE`, `IMPORTO`, `ID_CATEGORIA`, `ID_CONTO_CREDITO`, `ID_CONTO_DEP`, `ID_UTENTE`) VALUES ('',$data,'$desc',$ammontare,'$idcategoria',0,$idconto,$id_utente)");
			mysqli_query($GLOBALS["___mysqli_ston"], "UPDATE `conto_dep` SET `ENTRATE`=`ENTRATE`+$ammontare WHERE ID_UTENTE LIKE $id_utente AND ID_CONTO_DEP LIKE $idconto");
		}
	}
	elseif (strcmp($tipo,"credito")==0){//una transazione di credito può essere solo una spesa
		$row=mysqli_fetch_array(mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM `CONTO_CREDITO` WHERE ID_CONTO_CREDITO LIKE '$idconto'"));
		$credito_rimasto=$row['TOT_ATTIVO']-$row['TOT_PASSIVO'];
		$id_dep=$row['ID_CONTO_DEP'];
		if($credito_rimasto>=$ammontare){
			mysqli_query($GLOBALS["___mysqli_ston"], "INSERT INTO `transazione`(`ID_TRANS`, `DATA`, `DESCRIZIONE`, `IMPORTO`, `ID_CATEGORIA`, `ID_CONTO_CREDITO`, `ID_CONTO_DEP`, `ID_UTENTE`) VALUES ('',$data,'$desc',$ammontare,'$idcategoria',$idconto,$id_dep,$id_utente)");
			mysqli_query($GLOBALS["___mysqli_ston"], "UPDATE `conto_credito` SET `TOT_PASSIVO`=`TOT_PASSIVO`+$ammontare WHERE ID_CONTO_CREDITO LIKE $idconto");
		}else{/*segnala errore*/}
	}

	header("location:./?p=transazioni");

	ob_end_flush();
?>
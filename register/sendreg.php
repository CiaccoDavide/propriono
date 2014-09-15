<?php
	ob_start();
	
	include '../dbconnect.php';
	include '../fun.php';

	$nome=pre($_POST['nome']);
	$cognome=pre($_POST['cogn']);
	$pass=pre($_POST['pass']);
	$mail=pre($_POST['mail']);

	if(strlen($pass)>5){

		$sql="SELECT * FROM $tabella_0 WHERE E_MAIL='$mail'";
		$result=mysqli_query($GLOBALS["___mysqli_ston"], $sql);
		// Mysql_num_row is counting table row
		$count=mysqli_num_rows($result);
		// If result matched $myusername and $mypassword, table row must be 1 row
		if($count==1){
			header("location:./?error=alreadyhere");
		}else{
			//registra utente
			mysqli_query($GLOBALS["___mysqli_ston"], "INSERT INTO $tabella_0 VALUES ('', '$nome','$cognome','$pass', '$mail')");
			header("location:../index.php?now=youcanlogin");
		}
	}else{
		header("location:./?error=tooshort");
	}

	ob_end_flush();
?>
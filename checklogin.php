<?php
ob_start();
include './dbconnect.php';
include './fun.php';

// Per prevenire la MySQL injection
$myemail = pre($_POST['myusr']);
$mypassword = pre($_POST['mypwd']);

$sql="SELECT * FROM $tabella_0 WHERE E_MAIL='$myemail' and PSSW='$mypassword'";
$result=mysqli_query($GLOBALS["___mysqli_ston"], $sql);
// Mysql_num_row is counting table row
$count=mysqli_num_rows($result);
		$row=mysqli_fetch_array($result);
// If result matched $myusername and $mypassword, table row must be 1 row
if($count==1){
	// Register $myusername, $mypassword and redirect to file "login_success.php"
	session_start();
	$_SESSION['myusername']=$myemail;
	
	$_SESSION['id_utente']=$row['ID_UTENTE'];
	$_SESSION['nomecognome']=$row['NOME'].'&nbsp'.$row['COGNOME'];

	header("location:./");
}
else {
	header("location:./?wro=ng");
}
ob_end_flush();
?>
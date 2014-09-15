<?php
$host="127.0.0.1"; // Host name
$username="root"; // Mysql username
$password=""; // Mysql password
$db_name="progetto"; // Database name
$tabella_0="UTENTE";
$tabella_1="`CONTO_DEP`";
$tabella_2="CONTO_CREDITO";

// Connect to server and select databse.
($GLOBALS["___mysqli_ston"] = mysqli_connect("$host",  "$username",  "$password"))or die("cannot connect");
((bool)mysqli_query($GLOBALS["___mysqli_ston"], "USE $db_name"))or die("cannot select DB");
?>
<!DOCTYPE html>
<html>
	<head>
		<?php
		ini_set('display_errors','Off');
		session_start();
		if($_SESSION['myusername'])
			header("location:../");
		?>
		<meta charset="UTF-8" />
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"> 
		<meta name="viewport" content="width=device-width, initial-scale=1.0"> 
		<title>Basi di dati - Progetto</title>
		<meta name="description" content="Il nostro progetto di basi di dati" />
		<meta name="keywords" content="progettobasididati" />
		<meta name="author" content="CiaccoDavide&AlbertoCrosta" />
		<link rel="shortcut icon" href="../favicon.ico">
		<link rel="stylesheet" type="text/css" href="../css/main.css">
		<script src="./jquery.min.js"></script>
	</head>
	<body>
		<header class="reghead"><back><a href="../"><-Back.</a></back><tit>Register</tit></header>
		<div class="login">
			<form name="login" method="post" action="sendreg.php">
				<br><input name="nome" type="text" id="user" placeholder="Nome" autofocus required>
				<br><input name="cogn" type="text" id="user" placeholder="Cognome" required>
				<br><input name="mail" type="email" id="user" placeholder="Email" required>
				<br><input name="pass" type="password" id="pass" placeholder="Password" required>
				<br><input name="verpass" type="password" id="verpass" placeholder="Repeat Password" required>
				<br><input type="submit" name="Register" value="Register">
				<br>
				&nbsp
				
				<?php
					$error = $_GET['error'];
					if(strcmp($error,"alreadyhere")==0)
						echo '<error>User already taken!</error>';
					else if(strcmp($error,"tooshort")==0)
						echo '<error>Password and/or Username are too short! (put together at least 6 letters)</error>';
				?>
				
				<br>
				
			</form>
		</div>
	</body>
</html>
<!DOCTYPE html>
<html>
	<head>
		<meta http-equiv="content-type" content="text/html; charset=UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0"/> 
		<title>Basi di dati - Progetto</title>
		<meta name="description" content="Il nostro progetto di basi di dati"/>
		<meta name="keywords" content="progettobasididati"/>
		<meta name="author" content="CiaccoDavideAlbertoCrosta"/>
		<link rel="shortcut icon" href="./favicon.ico"/>
		<link rel="stylesheet" type="text/css" href="./css/main.css"/>
		<script src="./jquery.min.js"></script>
	</head>
	<body>

		<?php
		$rand=rand(0,100);
		session_start();
		include './fun.php';

		//USER NOT LOGGED
		if(!$_SESSION['nomecognome']){
			
				$error = $_GET['wro'];
				if(strcmp($error,"ng")==0)
					$errstring=	'<error>Wrong credentials!</error>';
				else $errstring='';
			
		echo '
<header>
	<a href="./"><h1>Basi di Dati</h1></a>
</header>

<div class="login">
	<form name="login" method="post" action="checklogin.php">
	  	<input type="email" name="myusr" placeholder="Email" required="required">
	    <br>
		<input type="password" name="mypwd" placeholder="Password" required="required"> 
	    <br>
		<input type="submit" name="submit" value="Login">   or   <a href="./register">Register</a>
	    <br>
			'.$errstring.'
	</form>
</div>
			';
		}else{//USER LOGGED
			$pagina = $_GET['p'];//indica la pagina da mostrare
			//l'header è uguale per tutte le pagine
			echo '
			<header>
				<a href="./"><h1>Basi di Dati</h1></a>
				<a href="./logout.php"><div class="logout">Logout</div></a>
			</header>
			';
			switch ($pagina) {
				default:
					echo '
<links>
	<a href="./"><cont class="active">Home</cont></a>
	<a href="./?p=conti"><cont>Lista conti</cont></a>
	<a href="./?p=transazioni"><cont>Transazioni</cont></a>
	<a href="./?p=bilanci"><cont>Bilanci</cont></a>
	<a href="./?p=rapporti"><cont>Rapporti</cont></a>
	<a href="./?p=categorie"><cont>Categorie</cont></a>
</links>

<div id="main">
	<p>Benvenuto '.$_SESSION['nomecognome'].', cosa vuoi fare oggi?</p>

	<div id="contiesistenti"></div>

	<p>&nbsp&nbsp&nbsp- <a href="./?p=conti">Crea un nuovo conto.</a></p>
	<p>&nbsp&nbsp&nbsp- <a href="./?p=conti">Controlla i conti aperti.</a></p>
	<p>&nbsp&nbsp&nbsp- <a href="./?p=transazioni">Controlla le tue transazioni.</a></p>
	<p>&nbsp&nbsp&nbsp- <a href="./?p=bilanci">Crea o visiona i bilanci.</a></p>
	<p>&nbsp&nbsp&nbsp- <a href="./?p=rapporti">Genera e consulta i rapporti.</a></p>
	<p>&nbsp&nbsp&nbsp- <a href="./?p=categorie">Gestisci le categorie.</a></p>
	<p>&nbsp&nbsp&nbsp- <a href="./logout.php">Esci.</a></p>
</div>
					';
					break;
					
				case 'conti':
					echo '
<links>
	<a href="./"><cont>Home</cont></a>
	<a href="./?p=conti"><cont class="active">Lista conti</cont></a>
	<a href="./?p=transazioni"><cont>Transazioni</cont></a>
	<a href="./?p=bilanci"><cont>Bilanci</cont></a>
	<a href="./?p=rapporti"><cont>Rapporti</cont></a>
	<a href="./?p=categorie"><cont>Categorie</cont></a>
</links>
<div id="main">
	<p>&nbsp&nbsp&nbsp- <a id="apriform" href="">Crea un nuovo conto.</a></p>
	<div id="creaconto"><br>
		<div id="newconto" name="send" method="post" action="crea_conto.php">
			<p>Tipo del conto:</p>

			<input id="dp" type="radio" name="tipo" value="deposito" checked>Conto di deposito
			<input id="ct" type="radio" name="tipo" value="credito">Conto di credito

			<div id="debi">
				<form id="newcontod" name="sendd" method="post" action="crea_conto.php?tipo=deposito">
					<p>Ammontare iniziale [€]:</p>
					<input id="ammontare" type="number" name="ammontare" placeholder="Ammontare" required="required"/><br>
					<input id="sndb" type="submit" name="msgsend" value="Apri conto di deposito">
				</form>
			</div>

			<div id="cred">
				<form id="newcontoc" name="sendc" method="post" action="crea_conto.php?tipo=credito">
					<p>Tetto massimo [€]:</p>
					<input id="tetto" type="number" name="ammontare" placeholder="Tetto massimo" required="required"/><br>
					<select id="associato" name="associato" required>
						<option selected="selected" disabled="disabled" value="">Seleziona un deposito</option>
						'.lista_conti($_SESSION['id_utente'],'seldep').'
					</select><br>
						<input id="sndb" type="submit" name="msgsend" value="Apri conto di credito">
				</form>
			</div>
		</div>	
	</div>
	<br>

	<p>Lista dei conti di deposito attivi:</p>
	<div class="lista_conti">'.lista_conti($_SESSION['id_utente'],'deposito').'</div>
	<br>
	<p>Lista dei conti di credito attivi:</p>
	<div class="lista_conti">'.lista_conti($_SESSION['id_utente'],'credito').'</div>

</div>
<script>
	$(document).ready(function(){
		$("#creaconto").toggle();
		//apri/chiudi il form per un nuovo conto
		$(function() {
		    $(document).on(\'click\', \'#apriform\', function(e) {
		       	$("#creaconto").slideToggle();
		       e.preventDefault();
		    });
		});
		$("#dp").click(function(){$("#cred").hide("fast");$("#debi").show("fast");});
		$("#ct").click(function(){$("#cred").show("fast");$("#debi").hide("fast");});
	});
</script>
					';
					break;
					
				case 'transazioni':
					echo '
<links>
	<a href="./"><cont>Home</cont></a>
	<a href="./?p=conti"><cont>Lista conti</cont></a>
	<a href="./?p=transazioni"><cont class="active">Transazioni</cont></a>
	<a href="./?p=bilanci"><cont>Bilanci</cont></a>
	<a href="./?p=rapporti"><cont>Rapporti</cont></a>
	<a href="./?p=categorie"><cont>Categorie</cont></a>
</links>
<div id="main">
		<p>&nbsp&nbsp&nbsp- <a id="apriform" href="">Crea una nuova transazione.</a></p>
	<div id="creatransazione"><br>
		<div id="newtrans" name="send" method="post" action="crea_transazione.php">
			<p>Tipo del conto su cui applicare la transazione:</p>
			<input id="dp" type="radio" name="tipo" value="deposito" checked>Conto di deposito
			<input id="ct" type="radio" name="tipo" value="credito">Conto di credito<br>

			<div id="debi">
				<form id="newtransd" name="sendd" method="post" action="crea_transazione.php?tipo=deposito">
					<p>Seleziona un conto di Credito:</p>
					<select id="idconto" name="idconto" required>
						<option selected="selected" disabled="disabled" value=""> - </option>
						'.lista_conti($_SESSION['id_utente'],'seldep').'
					</select><br>

					<p>Natura della transazione:</p>
					<input type="radio" name="tipotrans" value="spesa" checked>Spesa
					<input type="radio" name="tipotrans" value="credito">Entrata<br>

					<p>Entità economica della transazione [€]:</p>
					<input id="ammontare" type="number" name="ammontare" placeholder="Ammontare" required="required"/><br>
					<p>Descrizione:</p>
					<textarea name="descrizione" rows="4" cols="50" required></textarea>
					'.selezione_cats($_SESSION['id_utente']).'<br>
					<p>Data della transazione:</p>
					<input type="date" name="data" min="'.oggi().'" value="'.oggi().'"><br>
					<input id="sndb" type="submit" name="msgsend" value="Aggiungi transazione">
				</form>
			</div>

			<div id="cred">
				<form id="newtransd" name="sendd" method="post" action="crea_transazione.php?tipo=credito">
					<p>Seleziona un conto di Credito:</p>
					<select id="idconto" name="idconto" required>
						<option selected="selected" disabled="disabled" value=""> - </option>
						'.lista_conti($_SESSION['id_utente'],'selcre').'
					</select><br>
					<p>Entità economica della transazione [€]:</p>
					<input id="ammontare" type="number" name="ammontare" placeholder="Ammontare" required="required"/><br>
					<p>Descrizione:</p>
					<textarea name="desc" rows="4" cols="50" required></textarea>
					'.selezione_cats($_SESSION['id_utente']).'<br>
					<input id="sndb" type="submit" name="msgsend" value="Aggiungi transazione">
				</form>
			</div>

</div>
			
		
	</div>
	<br>
	<p>Lista delle transazioni:</p><br>
	<div class="lista_conti">'.lista_transazioni($_SESSION['id_utente']).'</div>
	<br>

</div>
<script>
	$(document).ready(function(){
		$("#creatransazione").toggle();
		//apri/chiudi il form per un nuovo conto
		$(function() {
		    $(document).on(\'click\', \'#apriform\', function(e) {
		       	$("#creatransazione").slideToggle();
		       e.preventDefault();
		    });
		});
		$("#dp").click(function(){$("#cred").hide("fast");$("#debi").show("fast");});
		$("#ct").click(function(){$("#cred").show("fast");$("#debi").hide("fast");});
	});
</script>
					';
					break;
					
				case 'bilanci':
					echo '
<links>
	<a href="./"><cont>Home</cont></a>
	<a href="./?p=conti"><cont>Lista conti</cont></a>
	<a href="./?p=transazioni"><cont>Transazioni</cont></a>
	<a href="./?p=bilanci"><cont class="active">Bilanci</cont></a>
	<a href="./?p=rapporti"><cont>Rapporti</cont></a>
	<a href="./?p=categorie"><cont>Categorie</cont></a>
</links>
<div id="main">
		
</div>
					';
					break;
					
				case 'rapporti':
					echo '
<links>
	<a href="./"><cont>Home</cont></a>
	<a href="./?p=conti"><cont>Lista conti</cont></a>
	<a href="./?p=transazioni"><cont>Transazioni</cont></a>
	<a href="./?p=bilanci"><cont>Bilanci</cont></a>
	<a href="./?p=rapporti"><cont class="active">Rapporti</cont></a>
	<a href="./?p=categorie"><cont>Categorie</cont></a>
</links>
<div id="main">
		
</div>
					';
					break;
					
				case 'categorie':
					echo '
<links>
	<a href="./"><cont>Home</cont></a>
	<a href="./?p=conti"><cont>Lista conti</cont></a>
	<a href="./?p=transazioni"><cont>Transazioni</cont></a>
	<a href="./?p=bilanci"><cont>Bilanci</cont></a>
	<a href="./?p=rapporti"><cont>Rapporti</cont></a>
	<a href="./?p=categorie"><cont class="active">Categorie</cont></a>
</links>
<div id="main">
	'.lista_cats($_SESSION['id_utente']).'

	<p>&nbsp&nbsp&nbsp- <a id="apriform" href="">Aggiungi una nuova categoria.</a></p>
	<div id="creaconto">
		<div id="newconto" name="send" method="post" action="crea_conto.php">
			<br><p>Tipo della categoria:</p>
			<input id="dp" type="radio" name="tipo" value="super" checked>Categoria<br>
			<input id="ct" type="radio" name="tipo" value="sub">Sotto-categoria<br>

			<div id="superc">
				<form id="newcatsuper" name="sendd" method="post" action="crea_cat.php?tipo=super">
					<p>Nome:</p>
					<input id="nome" type="text" name="nome" placeholder="Nome della categoria" required="required"/><br>
					<input id="sndb" type="submit" name="msgsend" value="Crea categoria">
				</form>
			</div>

			<div id="subc">
				<form id="newcatsub" name="sendc" method="post" action="crea_cat.php?tipo=sub">
					<p>Nome:</p>
					<input id="nome" type="text" name="nome" placeholder="Nome della categoria" required="required"/><br>
					<p>Seleziona una categoria madre:</p>
					<select id="madre" name="madre" required>
						<option selected="selected" disabled="disabled" value="">Seleziona una categoria madre</option>
						'.selezione_supercats($_SESSION['id_utente']).'
					</select><br>
						<input id="sndb" type="submit" name="msgsend" value="Crea sotto-categoria">
				</form>
			</div>
		</div>	
	</div>

</div>
<script>
	$(document).ready(function(){
		$("#creaconto").toggle();$("#subc").hide("fast");
		//apri/chiudi il form per un nuovo conto
		$(function() {
		    $(document).on(\'click\', \'#apriform\', function(e) {
		       	$("#creaconto").slideToggle();
		       e.preventDefault();
		    });
		});
		$("#dp").click(function(){$("#subc").hide("fast");$("#superc").show("fast");});
		$("#ct").click(function(){$("#subc").show("fast");$("#superc").hide("fast");});
	});
</script>
					';
					break;
			}
			
		}
		?>
		
		
		
		
		
	</body>
	
</html>



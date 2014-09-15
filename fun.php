<?php

	function lista_conti($id_utente,$tipo){//Stampa la lista dei conti attivi
		include_once './dbconnect.php';
		if(strcmp($tipo,'deposito')==0){//lista conti deposito
			$sql1="SELECT * FROM `CONTO_DEP` WHERE ID_UTENTE LIKE '$id_utente'";
			$result1=mysqli_query($GLOBALS["___mysqli_ston"], $sql1);
			$n_conti_aperti=0;
			$stringa_lista="";
			$str0='<table><tr id="rba"><td>Conto</td><td>Saldo attuale</td><td>Spese</td><td>Data apertura</td></tr>';
			while($row1=mysqli_fetch_array($result1)){
				$n_conti_aperti++;
				if($n_conti_aperti%2==0)
					$stringa_lista.='<tr id="ra">';
				else
					$stringa_lista.='<tr id="rb">';
				$stringa_lista.='<td id="fc">#'.$row1['ID_CONTO_DEP'].'</td><td>'.($row1['ENTRATE']-$row1['USCITE']).' €</td><td>'.$row1['USCITE'].' €</td><td>'.$row1['DATA'].'</td></tr>';
			}
			if($n_conti_aperti==0)
				return '<p>Non hai ancora aperto nessun conto di deposito!</p>';
			else
				return $str0.$stringa_lista.'</table>';
		}elseif(strcmp($tipo,'seldep')==0){//lista conti deposito per selezione associazione con nuovo conto di credito
			$sql1="SELECT * FROM `CONTO_DEP` WHERE ID_UTENTE LIKE '$id_utente'";
			$result1=mysqli_query($GLOBALS["___mysqli_ston"], $sql1);
			$n_conti_aperti=0;
			$stringa_lista="";
			while($row1=mysqli_fetch_array($result1)){
				$n_conti_aperti++;
				$stringa_lista.='<option value="'.$row1['ID_CONTO_DEP'].'">Deposito #'.$row1['ID_CONTO_DEP'].'</option>';
			}
			if($n_conti_aperti==0)
				return '';
			else
				return $stringa_lista;
		}elseif(strcmp($tipo,'credito')==0){//lista conti credito
			$sql="SELECT * FROM `CONTO_DEP` WHERE ID_UTENTE LIKE '$id_utente'";
			$result=mysqli_query($GLOBALS["___mysqli_ston"], $sql);

			$n_conti_aperti=0;
			$stringa_lista="";

			while($row=mysqli_fetch_array($result)){
				$id_dep=$row['ID_CONTO_DEP'];
				$sql1="SELECT * FROM `CONTO_CREDITO` WHERE ID_CONTO_DEP LIKE '$id_dep'";
				$result1=mysqli_query($GLOBALS["___mysqli_ston"], $sql1);
				$str0='<table><tr id="rba"><td>Conto</td><td>Tetto massimo</td><td>Addebiti</td><td>Deposito associato</td></tr>';
				while($row1=mysqli_fetch_array($result1)){
					$n_conti_aperti++;
					if($n_conti_aperti%2==0)
						$stringa_lista.='<tr id="ra">';
					else
						$stringa_lista.='<tr id="rb">';
					$stringa_lista.='<td id="fc">#'.$row1['ID_CONTO_CREDITO'].'</td><td>'.$row1['TOT_ATTIVO'].' €</td><td>'.$row1['TOT_PASSIVO'].' €</td><td>#'.$row1['ID_CONTO_DEP'].'</td></tr>';
				}
			}

			if($n_conti_aperti==0)
				return '<p>Non hai ancora aperto nessun conto di credito!</p>';
			else
				return $str0.$stringa_lista.'</table>';
		}elseif(strcmp($tipo,'selcre')==0){//lista conti credito per selezione creazione transazione
			$sql="SELECT * FROM `CONTO_DEP` WHERE ID_UTENTE LIKE '$id_utente'";
			$result=mysqli_query($GLOBALS["___mysqli_ston"], $sql);

			$n_conti_aperti=0;
			$stringa_lista="";

			while($row=mysqli_fetch_array($result)){
				$id_dep=$row['ID_CONTO_DEP'];
				$sql1="SELECT * FROM `CONTO_CREDITO` WHERE ID_CONTO_DEP LIKE '$id_dep'";
				$result1=mysqli_query($GLOBALS["___mysqli_ston"], $sql1);
				while($row1=mysqli_fetch_array($result1)){
					$n_conti_aperti++;
					$stringa_lista.='<option value="'.$row1['ID_CONTO_CREDITO'].'">Credito #'.$row1['ID_CONTO_CREDITO'].'</option>';
				}
			}
			if($n_conti_aperti==0)
				return '';
			else
				return $stringa_lista;
		}
	}
	function lista_transazioni($id_utente){
		$result=mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM `TRANSAZIONE` WHERE ID_UTENTE LIKE '$id_utente'");
		$n_transazioni=0;
		$stringa_lista="";
		$str0='<table><tr id="rba"><td>#Transazione</td><td>Valore</td><td>Categoria</td><td>Descrizione</td><td>#Credito</td><td>#Deposito</td><td>Data</td></tr>';
		while($row=mysqli_fetch_array($result)){
			$n_transazioni++;
			$imp=$row['IMPORTO'];
			if($imp<0)
				$stringa_lista.='<tr id="dab">';
			else
				$stringa_lista.='<tr id="good">';
			
			$stringa_lista.='<td id="fc">#'.$row['ID_TRANS'].'</td><td>'.$row['IMPORTO'].' €</td><td>'.nome_cate($row['ID_CATEGORIA']).'</td><td>'.$row['DESCRIZIONE'].'</td><td>'.(($row['ID_CONTO_CREDITO']==0)?"":$row['ID_CONTO_CREDITO']).'</td><td>'.$row['ID_CONTO_DEP'].'</td><td>'.$row['DATA'].'</td></tr>';
			
		}
		if($n_transazioni==0)
			return '<p>Non hai ancora effettuato nessuna transazione!</p>';
		else
			return $str0.$stringa_lista.'</table>';
	}


	function pre($stringa){//Per prevenire la MySQL injection
		return ((isset($GLOBALS["___mysqli_ston"]) && is_object($GLOBALS["___mysqli_ston"])) ? mysqli_real_escape_string($GLOBALS["___mysqli_ston"], stripslashes($stringa)) : ((trigger_error("[MySQLConverterToo] Fix the mysql_escape_string() call! This code does not work.", E_USER_ERROR)) ? "" : ""));
	}

	function apri_conto($id_utente,$tipo,$ammontare,$tabella_deposito,$tabella_credito,$idassociato){
		$data=date('Ymd');
		if(strcmp($tipo,"deposito")==0){
			mysqli_query($GLOBALS["___mysqli_ston"], "INSERT INTO $tabella_deposito VALUES ('',$ammontare,0,$id_utente,$data)");
		}
		elseif (strcmp($tipo,"credito")==0){
			mysqli_query($GLOBALS["___mysqli_ston"], "INSERT INTO `progetto`.`conto_credito` (`ID_CONTO_CREDITO`, `TOT_ATTIVO`, `TOT_PASSIVO`, `ID_CONTO_DEP`, `DATA`) VALUES (NULL, $ammontare, 0, $idassociato, $data);");
		}
		header("location:./?p=conti");
	}
	


	function lista_cats($id_utente){
		include_once './dbconnect.php';
		$num_categorie=0;
		$num_sottocategorie=0;
		$str='';
		$madre=0;


		$result=mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM `CATEGORIA` WHERE ID_UTENTE LIKE '$id_utente' AND ID_CAT_MOM LIKE 0");
		while($row=mysqli_fetch_array($result)){
			$id_cat=$row['ID_CATEGORIA'];
			$str.='<div class="super">-'.nome_cate($id_cat).'</div>';
			$result1=mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM `CATEGORIA` WHERE ID_CAT_MOM LIKE $id_cat");
				while($row1=mysqli_fetch_array($result1))
					$str.='<div class="sub"> &#8627'.$row1['DESCRIZIONE'].'</div>';
				$str.='<div class="sub"> &#8627<form id="newcat" name="newcat" method="post" action="crea_cat.php"><input type="hidden" id="madre" name="madre" value="'.$id_cat.'"><input class="newcat" type="text" name="nome" placeholder="Nuova sotto-categoria di '.nome_cate($id_cat).'" required></form></div><br>';
		}$str.='<div class="super"> <form id="newcat" name="newcat" method="post" action="crea_cat.php"><input class="newcat" type="text" id="nome" name="nome" placeholder="Nuova categoria" required></form></div><br>';

		if($num_categorie==-20)
			return '<p>Non hai ancora creato nessuna categoria!</p>';
		else
			return '<p>Categorie:</p><br><div id="elenco_categorie">'.$str.'<small>Sono presenti '.$num_categorie.' categorie e '.$num_sottocategorie.' sotto-categorie</small></div><br>';
	}
	function selezione_cats($id_utente){
		include_once './dbconnect.php';
		
		$num_categorie=0;
		$str='<p>Seleziona una categoria:</p><select id="categoria" name="categoria">';

		$result=mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM `CATEGORIA` WHERE ID_UTENTE LIKE '$id_utente' AND ID_CAT_MOM LIKE 0");
		while($row=mysqli_fetch_array($result)){
			$id_cat=$row['ID_CATEGORIA'];
			$str.='<option value="'.$id_cat.'">-'.nome_cate($id_cat).'</option>';
			$result1=mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM `CATEGORIA` WHERE ID_CAT_MOM LIKE $id_cat");
				while($row1=mysqli_fetch_array($result1))
					$str.='<option value="'.$row1['ID_CATEGORIA'].'">&nbsp;&nbsp; &#8627'.$row1['DESCRIZIONE'].'</option>';
				$num_categorie++;
		}

		if($num_categorie==0)
			return '</select><p>Non hai ancora creato nessuna categoria!</p>';
		else
			return $str.'</select>';

	}
	function selezione_supercats($id_utente){
		include_once './dbconnect.php';
		
		$result=mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM `CATEGORIA` WHERE ID_UTENTE LIKE '$id_utente' AND ID_CAT_MOM LIKE 0");
		$num_categorie=0;
		$str='<p>Seleziona una categoria:</p>';
		while($row=mysqli_fetch_array($result)){
			$num_categorie++;
			$str.='<option value="'.$row['ID_CATEGORIA'].'">'.$row['DESCRIZIONE'].'';
		}
		if($num_categorie==0)
			return '';
		else
			return $str;
	}
	function nome_cate($id_cate){//recupera il nome di una categoria
		include_once './dbconnect.php';
		$result=mysqli_fetch_array(mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM `CATEGORIA` WHERE ID_CATEGORIA LIKE '$id_cate'"));
		return $result['DESCRIZIONE'];
	}
	function oggi(){return date('Y-m-d');}
?>
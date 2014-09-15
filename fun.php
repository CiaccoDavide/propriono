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
					$stringa_lista.='<option value="'.$row1['ID_CONTO_CREDITO'].'">Deposito #'.$row1['ID_CONTO_CREDITO'].'</option>';
				}
			}
			if($n_conti_aperti==0)
				return '';
			else
				return $stringa_lista;
		}
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
	function lista_cats(){
		
	}
	function selezione_cats($id_utente){
		include_once './dbconnect.php';
		
		$result=mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM `CATEGORIA` WHERE ID_UTENTE LIKE '$id_utente' AND ID_CAT_MOM LIKE 0");
		$num_categorie=0;
		$str='<input list="categorie" name="categorie"><datalist id="categorie">';
		$str0='<input list="categorie" name="categorie"><datalist id="categorie">';
		while($row=mysqli_fetch_array($result)){
			$num_categorie++;
			$str.='<option value="'.$row['DESCRIZIONE'].'">';
		}
		$str.='</datalist>';
		$result1=mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM `CATEGORIA` WHERE ID_UTENTE LIKE '$id_utente' AND ID_CAT_MOM NOT LIKE 0 ORDER BY ID_CAT_MOM");
		
		$str.='<select id="scategorie"><option>Seleziona</option>';



			$madre=0;
		while($row1=mysqli_fetch_array($result1)){
			$num_categorie++;
			if($madre!=$row1['ID_CAT_MOM']){
				if($madre!=0)
					$str.='</optgroup>';
				$madre=$row1['ID_CAT_MOM'];
				$str.='<optgroup label="'.nome_cate($madre).'" disabled><option value="'.$row1['DESCRIZIONE'].'">'.$row1['DESCRIZIONE'].'</option>';
			}
		}
		

		if($num_categorie==0)
			return '</select><p>Non hai ancora creato nessuna categoria!</p>';
		else
			return $str.'</select>';

	}
	function nome_cate($id_cate){//recupera il nome di una categoria
		include_once './dbconnect.php';
		$result=mysqli_fetch_array(mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM `CATEGORIA` WHERE ID_CATEGORIA LIKE '$id_cate'"));
		return $result['DESCRIZIONE'];
	}
?>


<select>
  <optgroup label="Group 1">
    <option>Option 1.1</option>
  </optgroup> 
  <optgroup label="Group 2">
    <option>Option 2.1</option>
    <option>Option 2.2</option>
  </optgroup>
  <optgroup label="Group 3" disabled>
    <option>Option 3.1</option>
    <option>Option 3.2</option>
    <option>Option 3.3</option>
  </optgroup>
</select>
<?php
//http://p0stgr3sm3rd4.altervista.org/graph.php?probvuoto=30&probpieno=30&ygap=1&xstart=0.5&ystart=0.5
$altezza = $_GET['altezza'];
$larghezza = $_GET['larghezza'];
//$altezza = 200;
//$larghezza = 500;

$pixelsize = $_GET['pixelsize'];
$ygap = $_GET['ygap'];
$ystart = $_GET['ystart'];
$xstart = $_GET['xstart'];
$colore_r = "ff5860";
$colore_g = "38ef8b";
$colore_b = "1a79ff";
$sfondo = "2a2a2a";
$probvuoto = $_GET['probvuoto'];
$probpieno = $_GET['probpieno'];

	$colore_r = preg_replace(' /[^0-9A-Fa-f]/ ', '', $colore_r);
        $colore_rVal = hexdec($colore_r);
        $colore_rR = 0xFF & ($colore_rVal >> 0x10);
        $colore_rG = 0xFF & ($colore_rVal >> 0x8);
        $colore_rB = 0xFF & $colore_rVal;

	$colore_g = preg_replace(' /[^0-9A-Fa-f]/ ', '', $colore_g);
        $colore_gVal = hexdec($colore_g);
        $colore_gR = 0xFF & ($colore_gVal >> 0x10);
        $colore_gG = 0xFF & ($colore_gVal >> 0x8);
        $colore_gB = 0xFF & $colore_gVal;

  	$colore_b = preg_replace(' /[^0-9A-Fa-f]/ ', '', $colore_b);
        $colore_bVal = hexdec($colore_b);
        $colore_bR = 0xFF & ($colore_bVal >> 0x10);
        $colore_bG = 0xFF & ($colore_bVal >> 0x8);
        $colore_bB = 0xFF & $colore_bVal;

	$sfondo = preg_replace("/[^0-9A-Fa-f]/", '', $sfondo);
		$sfondoVal = hexdec($sfondo);
		$sfondoR = 0xFF & ($sfondoVal >> 0x10);
		$sfondoG = 0xFF & ($sfondoVal >> 0x8);
		$sfondoB = 0xFF & $sfondoVal;


//CREA "CANVAS"
$img = imagecreatetruecolor($larghezza, $altezza);
//COLORS
$colore_r = imagecolorallocate($img,$colore_rR,$colore_rG,$colore_rB);
$colore_g = imagecolorallocate($img,$colore_gR,$colore_gG,$colore_gB);
$colore_b = imagecolorallocate($img,$colore_bR,$colore_bG,$colore_bB);
$sfondo = imagecolorallocate($img,$sfondoR,$sfondoG,$sfondoB);



$random=$_GET['random'];
$n=$_GET['ndati'];//numero di dati


if(strcmp($_GET['tipo'],"entrate")==0){
	//SFONDO
	imagefilledrectangle($img, 0,0,$larghezza, $altezza, $sfondo);
	imageline($img,20,$altezza-20,$larghezza-20,$altezza-20,$colore_b);
	imageline($img,20,20,$larghezza-($larghezza-20),$altezza-20,$colore_b);
	
	$arr = array_fill(0, $n, NULL);
	$max = 0;
	for ($i=0;$i<$n;$i++) {
	  $arr[$i] = rand(0, $random);
	  if($arr[$i]>$max)
	  	$max=$arr[$i];
	}
	$espansione_altezza=($altezza-20-20)/$max;
	$espansione_larghezza=($larghezza-20-20)/$n;
	for ($i=0;$i<$n;$i++) {
		imagefilledrectangle($img, 22+$espansione_larghezza*$i,$altezza-22,22+$espansione_larghezza*$i+($espansione_larghezza-2), $altezza-22-($espansione_altezza*$arr[$i]), $colore_g);
	}
}elseif(strcmp($_GET['tipo'],"uscite")==0){
	//SFONDO
	imagefilledrectangle($img, 0,0,$larghezza, $altezza, $sfondo);
	imageline($img,20,$altezza-20,$larghezza-20,$altezza-20,$colore_b);
	imageline($img,20,20,$larghezza-($larghezza-20),$altezza-20,$colore_b);
	
	$arr = array_fill(0, $n, NULL);
	$max = 0;
	for ($i=0;$i<$n;$i++) {
	  $arr[$i] = rand(0, $random);
	  if($arr[$i]>$max)
	  	$max=$arr[$i];
	}
	$espansione_altezza=($altezza-20-20)/$max;
	$espansione_larghezza=($larghezza-20-20)/$n;
	for ($i=0;$i<$n;$i++) {
		imagefilledrectangle($img, 22+$espansione_larghezza*$i,$altezza-22,22+$espansione_larghezza*$i+($espansione_larghezza-2), $altezza-22-($espansione_altezza*$arr[$i]), $colore_r);
	}
}elseif(strcmp($_GET['tipo'],"misto")==0){
	//SFONDO
	imagefilledrectangle($img, 0,0,$larghezza, $altezza, $sfondo);
	imageline($img,20,$altezza/2,$larghezza-20,$altezza/2,$colore_b);//orizzontale
	imageline($img,20,20,$larghezza-($larghezza-20),$altezza-20,$colore_b);//verticale
	
	$arr = array_fill(0, $n, NULL);
	$max = 0;
	for ($i=0;$i<$n;$i++) {
	  $arr[$i] = rand(-$random, $random);
	  if($arr[$i]>$max)
	  	$max=$arr[$i];
	}
	$espansione_altezza=(($altezza-20-20)/2)/($max);
	$espansione_larghezza=($larghezza-20-20)/$n;
	for ($i=0;$i<$n;$i++) {
		if($arr[$i]>0)
			imagefilledrectangle($img, 22+$espansione_larghezza*$i,($altezza/2)-2,22+$espansione_larghezza*$i+($espansione_larghezza-2), ($altezza/2)-($espansione_altezza*$arr[$i])+2, $colore_g);
		elseif($arr[$i]<0)
			imagefilledrectangle($img, 22+$espansione_larghezza*$i,($altezza/2)+2,22+$espansione_larghezza*$i+($espansione_larghezza-2), ($altezza/2)-($espansione_altezza*$arr[$i])-2, $colore_r);
	}
}elseif(strcmp($_GET['tipo'],"andamento")==0){
	//SFONDO
	imagefilledrectangle($img, 0,0,$larghezza, $altezza, $sfondo);
	imageline($img,20,$altezza-20,$larghezza-20,$altezza-20,$colore_b);
	imageline($img,20,20,$larghezza-($larghezza-20),$altezza-20,$colore_b);
	
	$arr = array_fill(0, $n, NULL);
	$max = 0;
	for ($i=0;$i<$n;$i++) {
	  $arr[$i] = rand(0, $random);
	  if($arr[$i]>$max)
	  	$max=$arr[$i];
	}
	$espansione_altezza=($altezza-20-20)/$max;
	$espansione_larghezza=($larghezza-20-20)/$n;
	for ($i=0;$i<$n;$i++) {
		if($arr[$i]-$arr[$i-1]>=0)
			imagefilledrectangle($img, 22+$espansione_larghezza*$i,$altezza-22,22+$espansione_larghezza*$i+($espansione_larghezza-2), $altezza-22-($espansione_altezza*$arr[$i]), $colore_g);
		else
			imagefilledrectangle($img, 22+$espansione_larghezza*$i,$altezza-22,22+$espansione_larghezza*$i+($espansione_larghezza-2), $altezza-22-($espansione_altezza*$arr[$i]), $colore_r);
		
	}
}elseif(strcmp($_GET['tipo'],"test")==0){
	//SFONDO
	imagefilledrectangle($img, 0,0,$larghezza, $altezza, $sfondo);
	imageline($img,20,$altezza-20,$larghezza-20,$altezza-20,$colore_b);
	imageline($img,20,20,$larghezza-($larghezza-20),$altezza-20,$colore_b);
	
	$arr = array_fill(0, $n, NULL);
	$max = 0;
	for ($i=0;$i<$n;$i++) {
	  $arr[$i] = rand(0, $random);
	  if($arr[$i]>$max)
	  	$max=$arr[$i];
	}
	$espansione_altezza=($altezza-20-20)/$max;
	$espansione_larghezza=($larghezza-20-20)/$n;

	$punti[]=$larghezza-22;$punti[]=$altezza-22;
	$punti[]= 22;$punti[]=$altezza-22;
	for ($i=0;$i<$n;$i++) {
		$punti[] = 22+$espansione_larghezza*$i;
		$punti[] = $altezza-22-($espansione_altezza*$arr[$i]);
	}
	imagefilledpolygon($img, $punti, count($punti)/2, $colore_g);
	for ($j=0;$j<count($punti);$j++) {
		$tempx=$punti[$j];
		$j++;
		imagefilledrectangle($img, $tempx, 0, $tempx+0.5, $altezza-22, $sfondo);
	}

}

header("Content-type: image/png");
imagepng($img);
imagedestroy($img);
?>
<?php 
include("../../includes/config.php");

$width = isset($_REQUEST["width"]) ? $_REQUEST["width"] : 0;
$height = isset($_REQUEST["height"]) ? $_REQUEST["height"] : 0;

$top = isset($_REQUEST["top"]) ? $_REQUEST["top"] : -1;
$left = isset($_REQUEST["left"]) ? $_REQUEST["left"] : -1;

$image = (isset($_REQUEST["img"]) && $_REQUEST["img"]!="") ? $_REQUEST["img"] : "../../components/core/img/nophoto.gif";
$image=str_replace(" ","%20",$image);
//$image = str_replace(_CONFIG_HTTP."app/uploads/", _FRAMEWORK_UPLOADS_PATH, $image);
$image=_FRAMEWORK_UPLOADS_PATH."/".$image;


$cache = isset($_REQUEST["cache"]) ? $_REQUEST["cache"] : "cache";

// Preparo nome per cache
$cache_path = _FRAMEWORK_UPLOADS_PATH."/".$cache."/";
$cache_file = $image;


// Catturo info su immagine
$ims = getimagesize($image);

// Calcolo proporzione immagine
$prop = $ims[0] / $ims[1];

// Calcolo nuove dimensioni per scalo
$newwidth = (int) ($prop * $height);
//echo $newwidth."<br/>";
$newheight = (int) ($width / $prop);
//echo $newheight."<br/>";

// Sostituisco spazi e / con _
$cache_file = preg_replace("(/|:| |\.|\\\\)",'_', $cache_file);
$cache_file = str_replace(" ",'_', $cache_file);
$cache_file = str_replace(" ",'_', $cache_file);

// Aggiungo dimensioni
$cache_file .= "_w_" . $width . "_h_" . $height;

// Nome file in cache
if($ims[2] == IMAGETYPE_GIF) {
	// Aggiungo estensione
	$cache_file .= ".gif";
}

if($ims[2] == IMAGETYPE_JPEG) {
	// Aggiungo estensione
	$cache_file .= ".jpeg";
}

if($ims[2] == IMAGETYPE_PNG) {
	// Aggiungo estensione
	$cache_file .= ".png";
}

// Controllo esistena cache
if(is_file($cache_path . $cache_file) && (filemtime($cache_path . $cache_file) >= filemtime($image))) {
	header("location: "._SITE_PATH."/uploads/framework/".$cache."/". $cache_file); 
	exit();
}

// Catturo immagine oridinale e la converto in formato dati
if($ims[2] == IMAGETYPE_GIF) {
	$org_img = imagecreatefromgif($image);
}

if($ims[2] == IMAGETYPE_JPEG) {
	$org_img = imagecreatefromjpeg($image);
}

if($ims[2] == IMAGETYPE_PNG) {
	$org_img = imagecreatefrompng($image);
}

// Genero image in funzione dei parametri richiesti
$img = imagecreatetruecolor($width,$height);

// Se immagine già dimensionata evito l'operazione
if($ims[0] == $width && $ims[1] == $height) {

	// Trasformo in immagine la nuova versione
	if($ims[2] == IMAGETYPE_GIF) {
		header ("Content-type: image/gif");
		imagegif($org_img);
	}

	if($ims[2] == IMAGETYPE_JPEG) {
		header ("Content-type: image/jpeg");
		imagejpeg($org_img, "", 100);
	}

	if($ims[2] == IMAGETYPE_PNG) {
		header("Content-type: image/png");
		imagepng($org_img);
	}
	
	exit();
}

// Verifica fattibilita e scalo immagine
// Se scalando su altezza, larghezza non scende sotto il minimo
if ($newwidth > $width) {

	// Scalo fissando altezza
	$newheight = $height;

	// Se scalando su larghezza, altezza non scende sotto il minimo
} else if ($newheight > $height) {

	// Scalo fissando larghezza
	$newwidth = $width;
}

// Scalo immagine
$img_scale = imagecreatetruecolor($newwidth,$newheight);
imagecopyresampled($img_scale, $org_img, 0, 0, 0, 0, $newwidth, $newheight, $ims[0], $ims[1]);

// Cordinate x,y per il crop
if($left < 0) {
	$left = ($newwidth - $width) / 2;
}

if($top < 0) {
	$top = ($newheight - $height) / 2;
}

// Creo taglio immagine
imagecopy($img, $img_scale, 0, 0, $left, $top, $newwidth, $newheight);

// Trasformo in immagine la nuova versione
if($ims[2] == IMAGETYPE_GIF) {
	header ("Content-type: image/gif");
	imagegif($img);
	imagegif($img, $cache_path.$cache_file);
}

if($ims[2] == IMAGETYPE_JPEG) {
	header ("Content-type: image/jpeg");
	imagejpeg($img, "", 90);
	imagejpeg($img, $cache_path.$cache_file, 90);
}

if($ims[2] == IMAGETYPE_PNG) {
	header("Content-type: image/png");
	imagepng($img);
	imagepng($img, $cache_path.$cache_file);
}

// Svuoto puntatore
imagedestroy($img);
?>
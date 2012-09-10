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

// Sostituisco spazi e / con _
$cache_file = preg_replace("(/|:| |\.|\\\\)",'_', $cache_file);
$cache_file = str_replace(" ",'_', $cache_file);
$cache_file = str_replace(" ",'_', $cache_file);

// Catturo info su immagine
$ims = getimagesize($image);

// Calcolo proporzione immagine
$prop = $ims[0] / $ims[1];

if($ims[0] <= $width){
	$width=$ims[0];
}
if($ims[1] <= $height){
	$height=$ims[1];
}

// Verifica richiesta una sola dimensione
if($width != 0 || $height != 0) {

	// Non richista modifica larghezza -> adatta
	if($width < $height) {
		$width = (int) ( ($ims[0]/$ims[1]) * $height);
				
		// Aggiungo dimensioni a file
		$cache_file .= "_h_" . $height;  
	}
	
	// Non richista modifica altezza -> adatta
	if($height < $width) {
		$height = (int) (($ims[1]/$ims[0]) * $width);  
				
		// Aggiungo dimensioni a file
		$cache_file .= "_w_" . $width;
	}
}

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

// Calcolo nuove dimensioni per scalo
$newwidth = (int) ($prop * $height);
//echo $newwidth."<br/>";

$newheight = (int) ($width / $prop);
//echo $newheight."<br/>";

// Genero image in funzione dei parametri richiesti
$img = imagecreatetruecolor($width,$height);

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


// Scalo immagine
$img_scale = imagecreatetruecolor($width,$height);

imagecopyresampled($img_scale, $org_img, 0, 0, 0, 0, $width, $height, $ims[0], $ims[1]);


// Trasformo in immagine la nuova versione
if($ims[2] == IMAGETYPE_GIF) {
	header ("Content-type: image/gif");
	imagegif($img_scale);
	imagegif($img_scale, $cache_path.$cache_file);
}  

if($ims[2] == IMAGETYPE_JPEG) {
	header ("Content-type: image/jpeg");
	imagejpeg($img_scale, "", 90);
	imagejpeg($img_scale, $cache_path.$cache_file, 90);
}

if($ims[2] == IMAGETYPE_PNG) {
	header("Content-type: image/png");
	imagepng($img_scale);
	imagepng($img_scale, $cache_path.$cache_file);
}

// Svuoto puntatore
imagedestroy($img);

?>
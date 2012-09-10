<?php 
include("../../includes/config.php");

function inversecolor($hexcode) {
	
	if(substr($hexcode, 0, 1) == "#") {
		$hexcode = substr($hexcode, -6);
	}
	
	$r = hexdec(substr($hexcode, 0, 2));
	$g = hexdec(substr($hexcode, 2, 2));
	$b = hexdec(substr($hexcode, 4, 2));

	$rgb = array();
	
	$rgb[] = $r;
	$rgb[] = $g;
	$rgb[] = $b;
	
	return $rgb;
}

$width = isset($_REQUEST["width"]) ? $_REQUEST["width"] : 0;
$height = isset($_REQUEST["height"]) ? $_REQUEST["height"] : 0;

	
$image = isset($_REQUEST["img"]) ? $_REQUEST["img"] : "../../components/core/img/nophoto.gif";

$image=str_replace(" ","%20",$image);
//$image = str_replace(_CONFIG_HTTP."app/uploads/", _FRAMEWORK_UPLOADS_PATH, $image);
$image=_FRAMEWORK_UPLOADS_PATH."/".$image;


$cache = isset($_REQUEST["cache"]) ? $_REQUEST["cache"] : "cache";
// Preparo nome per cache
$cache_path = _FRAMEWORK_UPLOADS_PATH."/".$cache."/";
$cache_file = $image;

$info = getimagesize($image);

if (isset($_REQUEST["color"])){
	$color = inversecolor($_REQUEST["color"]);
	$r = $color[0];
	$g = $color[1];
	$b = $color[2];
}	else{
	switch($info['mime']){
		case "image/gif":
			$im = imagecreatefromgif($image);
			break;
		case "image/jpeg":
			$im = imagecreatefromjpeg($image);
			break;
		case "image/png":
			$im = imagecreatefrompng($image);
			break;
		case "image/bmp":
			$im = imagecreatefromwbmp($image);
			break;
		case "":
			$im = false;
			break;
	}
	if($im){
		$rgb = imagecolorat($im, 1, 1);
		$alpha = imagecolorsforindex($im,$rgb);

		$r = $alpha['red'];
		$g = $alpha['green'];
		$b = $alpha['blue'];

	}else{
		$r=255;
		$g=255;
		$b=255;
	}
	if ($alpha['alpha'] == 127){
		$r=255;
		$g=255;
		$b=255;
	}


}


// Sostituisco spazi e / con _
$cache_file = preg_replace("(/|:| |\.|\\\\)",'_', $cache_file);
$cache_file = str_replace(" ",'_', $cache_file);
$cache_file = str_replace(" ",'_', $cache_file);

// Aggiungo dimensioni
$cache_file .= "_w_" . $width . "_h_" . $height;

// Nome file in cache
if($info[2] == IMAGETYPE_GIF) {
	// Aggiungo estensione
	$cache_file .= ".gif";
}

if($info[2] == IMAGETYPE_JPEG) {
	// Aggiungo estensione
	$cache_file .= ".jpeg";
}

if($info[2] == IMAGETYPE_PNG) {
	// Aggiungo estensione
	$cache_file .= ".png";
}

// Controllo esistena cache
if(is_file($cache_path . $cache_file)) {
	header("location: "._SITE_PATH."/uploads/framework/".$cache."/". $cache_file); 
	exit();
}

if ($info['0'] > $info['1']){
	$new_width = $width;
	$new_height = ($info['1'] / $info['0']) * $new_width;
}else{
	$new_height = $height;
	$new_width = ($info['0'] / $info['1']) * $new_height;
}
if($info['0']<$new_width){
	$new_height = $info['1'];
	$new_width = $info['0'];
}
if($new_height>$height){
	$new_height = $height;
	$new_width = ($info['0'] / $info['1']) * $new_height;
}

$top = ($height - $new_height) / 2;
$left = ($width - $new_width) / 2;

switch($info['mime']){
	case "image/gif":
		$bigimage = imagecreatefromgif($image);
		$tnimage = imagecreatetruecolor($width, $height);
		imagepalettecopy($tnimage, $bigimage);
		imagegammacorrect($tnimage, 0, 256);
		$color = imagecolorallocate($tnimage, $r, $g, $b);		
		ImageFilledRectangle($tnimage,0,0,$width,$height,$color);			
		imagecopyresampled($tnimage, $bigimage, $left, $top, 0, 0, $new_width, $new_height, $info['0'], $info['1']);
		break;
	case "image/jpeg":	
		$bigimage = imagecreatefromjpeg($image);
		$tnimage = imagecreatetruecolor($width, $height);
		$color = imagecolorallocate($tnimage, $r, $g, $b);		
		ImageFilledRectangle($tnimage,0,0,$width,$height,$color);			
		imagecopyresampled($tnimage, $bigimage, $left, $top, 0, 0, $new_width, $new_height, $info['0'], $info['1']);
		break;
	case "image/bmp":
		$bigimage = imagecreatefromwbmp($image);
		$tnimage = imagecreatetruecolor($width, $height);
		$color = imagecolorallocate($tnimage, $r, $g, $b);		
		ImageFilledRectangle($tnimage,0,0,$width,$height,$color);			
		imagecopyresampled($tnimage, $bigimage, $left, $top, 0, 0, $new_width, $new_height, $info['0'], $info['1']);
		break;
	case "image/png":
		$bigimage = imagecreatefrompng($image);
		$tnimage = imagecreatetruecolor($width, $height);
		imagepalettecopy($tnimage, $bigimage);
		imagegammacorrect($tnimage, 0, 256);
		$color = imagecolorallocate($tnimage, $r, $g, $b);		
		ImageFilledRectangle($tnimage,0,0,$width,$height,$color);			
		imagecopyresampled($tnimage, $bigimage, $left, $top, 0, 0, $new_width, $new_height, $info['0'], $info['1']);
		break;
	case "":
		$tnimage  = imagecreate(250, 250); /* Create a blank image */
		$bgc = imagecolorallocate($tnimage, 255, 255, 255);
		$tc  = imagecolorallocate($tnimage, 0, 0, 0);
		imagefilledrectangle($tnimage, 0, 0, $new_width, $new_height, $bgc);
		imagestring($tnimage, 4, 5, 5, "404 Not Found", $tc);
		break;
}
if($info[2] == IMAGETYPE_GIF) {

	header ("Content-type: image/gif");
	imagegif($tnimage);
	imagegif($tnimage, $cache_path.$cache_file);
}  

if($info[2] == IMAGETYPE_JPEG) {
	header ("Content-type: image/jpeg");
	imagejpeg($tnimage, "", 90);
	imagejpeg($tnimage, $cache_path.$cache_file, 90);
}

if($info[2] == IMAGETYPE_PNG) {
	header("Content-type: image/png");
	imagepng($tnimage);
	imagepng($tnimage, $cache_path.$cache_file);
}
imagedestroy($tnimage);
imagedestroy($bigimage);

?>
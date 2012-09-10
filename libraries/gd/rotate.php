<?php 

class RotateImage {
	
	function RotateImage($image=false,$degrees=180,$cache="cache"){
	
		$image = (isset($image) && $image!="") ? $image : "../../components/core/img/nophoto.gif";
		$image=str_replace(" ","%20",$image);
		$image=_FRAMEWORK_UPLOADS_PATH."/".$image;
		
		
		// Preparo nome per cache
		
		$cache_path = _FRAMEWORK_UPLOADS_PATH."/".$cache."/";
		$cache_file = $image;
		
		$cache_file = preg_replace("(/|:| |\.|\\\\)",'_', $cache_file);
		$cache_file = str_replace(" ",'_', $cache_file);
		$cache_file = str_replace(" ",'_', $cache_file);
		
		
		FileSystem::searchAndDestroy($cache_path,$cache_file);
		
		// Catturo info su immagine
		$ims = getimagesize($image);

		
		// Genero image in funzione dei parametri richiesti
		$img = imagecreatetruecolor($ims[0],$ims[1]);
		
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
		
		$rotate = imagerotate($org_img, $degrees, 0) ;
		
		// Trasformo in immagine la nuova versione
		if($ims[2] == IMAGETYPE_GIF) {
			imagegif($rotate, $image);
		}  
		
		if($ims[2] == IMAGETYPE_JPEG) {
			imagejpeg($rotate, $image, 90);
		}
		
		if($ims[2] == IMAGETYPE_PNG) {
			imagepng($rotate, $image);
		}
		
		// Svuoto puntatore
		imagedestroy($rotate);
	}
}
?>
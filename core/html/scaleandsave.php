<?php 

class ScaleAndSaveImage {
 
	function ScaleAndSaveImage($image="../../components/core/img/nophoto.gif",$width=0,$height=0,$top=-1,$left=-1){
	
		$image=str_replace(" ","%20",$image);
		//$image = str_replace(_CONFIG_HTTP."app/uploads/", _FRAMEWORK_UPLOADS_PATH, $image);
		$image=_FRAMEWORK_UPLOADS_PATH."/".$image;
		
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
			}
			
			// Non richista modifica altezza -> adatta
			if($height < $width) {
				$height = (int) (($ims[1]/$ims[0]) * $width);  
			}
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
			imagegif($img_scale, $image);
		}  
		
		if($ims[2] == IMAGETYPE_JPEG) {
			imagejpeg($img_scale, $image, 90);
		}
		
		if($ims[2] == IMAGETYPE_PNG) {
			imagepng($img_scale, $image);
		}
		// Svuoto puntatore
		imagedestroy($img);
		imagedestroy($img_scale);
	}
}

?>
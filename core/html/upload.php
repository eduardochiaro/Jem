<?php
/**
 * Classe per l'upload di file
 */
class Upload {
	function uploadFile($file, $folder, $workspace, $namephoto=null){
		$files		=	$workspace->getFiles($file);
		
		$namephoto	=	($namephoto)?$namephoto:uniqid();
		
		if($files['name']){
			
			$extension	=	Upload :: getExtension($files['name']);
			$newfile	=	$namephoto.".".$extension;
			
			$toFolder =  $folder;
			
			if(!is_dir($toFolder)){
				if(!mkdir($toFolder,0777)){
					$toFolder = _FRAMEWORK_UPLOADS_PATH ;
				}
			}
			$uploadfile	= $toFolder. _PATH_SEPARATOR .$newfile;
			if(move_uploaded_file($files['tmp_name'], $uploadfile)){
				chmod($uploadfile,0777);
				return $folder. _PATH_SEPARATOR . $newfile;
			}else{
				return false;
			}
		}else{
			return false;
		}
	}
	
	/**
	 * Ritorna l'estensione del file
	 */
	function getExtension($file){
		$path_info = pathinfo($file);
    	return $path_info['extension'];
	}
	
	function deleteFile($file,$folder,$workspace){
		$response=unlink($_FRAMEWORK_UPLOADS_PATH. _PATH_SEPARATOR .$file);
		return $response;
	}
	
	function uploadCSV($file, $workspace){
		$filenew = Upload::uploadFile($file ,_UPLOADS_PATH."/tmp", $workspace);
		$loadC = array();
		$row = 1;
		if (($handle = fopen($filenew, "r")) !== FALSE) {
			while (($data = fgetcsv($handle, 1000, "\n")) !== FALSE) {
				$num = count($data);
				$row++;
				for ($c=0; $c < $num; $c++) {
					$loadC[] = explode(";", $data[$c]);
				}
			}
			fclose($handle);
		}	
		return $loadC;
	}
}
?>
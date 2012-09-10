<?php

class FormUploads {

	
	function images($workspace, $original, $many=0, $class=''){
		
		$_forms = new Forms($workspace);
		if(!is_array($original)) $original = array(1 => array("file" =>$original));
		if($many  > 0){
			echo "<div class=\"immagine-inserita\"><table cellpadding=\"0\" cellspacing=\"0\">";
			echo "<tr>";
			echo "<th>carica</th>";
			if(isset($original[1]['file'])) echo "<th colspan='2'></th>";
			echo "</tr>";
			for($i=1;$i<=$many;$i++){
				$text = $i;
				if($many <= 1){
					$text = "";
				}	
				echo "<tr><td>";
				echo "<p class=\"".$class."\">"."\n";
					echo $_forms->element("carica immagine ".$text,"photo-".$i,$_forms->file("photo-".$i,''),"generica");
				echo "</p>";
				if(isset($original[$i]['file']) && $original[$i]['file']!=""){
					echo "</td><td>";
					echo Utils::scaleImage($original[$i]['file'],100,100,0,0,"immagine ".$i);
					echo "</td><td class='ultimo'>";
					echo $_forms->checkbox("delete-photo-".$i,'1',false,'').' cancella';
				}
				echo "</td></tr>";
			}
			echo "</table></div>";
		}
	}

	
	function files($workspace, $original, $many=0, $class=''){
		
		$_forms = new Forms($workspace);
		if($many  > 0){
			echo "<div class=\"immagine-inserita\"><table cellpadding=\"0\" cellspacing=\"0\">";
			echo "<tr>";
			echo "<th>carica</th>";
			echo "<th>titolo</th>";
			if(isset($original[1]['file'])) echo "<th colspan='2'></th>";
			echo "</tr>";
			for($i=1;$i<=$many;$i++){
			
				$text = $i;
				if($many <= 1){
					$text = "";
				}	
				echo "</td><td>";
				echo "<p class=\"".$class."\">"."\n";
					echo $_forms->element("carica documento ".$text,"file-".$i,$_forms->file("file-".$i,''),"generica");
				echo "</p>";
				echo "</td><td>";
				echo "<p class=\"".$class."\">"."\n";
					echo $_forms->element("titolo documento ".$text,"title-file-".$i,$_forms->text("title-file-".$i,$original[$i]['title'],"lungo",'maxlength="220" size="20"'),"generica");
				echo "</p>";
				if(isset($original[$i]['file']) && $original[$i]['file']!=""){
					echo "</td><td>";
					echo Utils::href(Utils::siteOptions("siteurl")."uploads/framework/".$original[$i]['file'],($original[$i]['title'])?$original[$i]['title']:"documento ".$i);
					
					echo "</td><td class='ultimo'>";
					echo $_forms->checkbox("delete-file-".$i,'1',false,'').' cancella';
				}
				echo "</td></tr>";
			}
			echo "</table></div>";
		}
	}
}


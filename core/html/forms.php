<?php
class Forms{
	
	function element($text,$id=false,$append=false,$class='',$add=''){
		return "<label id=\"label-".trim($id)."\" for=\"form-".trim($id)."\" ".(($class)?"class=\"".$class."\"":"")." ".$add.">".trim($text)."</label>"."\n".$append;
	}

    function text($name,$value='',$class='',$add='') { 		
		$workspace = Jem::getInstance();
		$err = $workspace->getFormError();
		$class = $class.( (in_array($name, $err))?( ($class)?" red":"red" ):"" );
	
        return "<input type=\"text\" name=\"".trim($name)."\" id=\"form-".trim($name)."\" value=\"".$value."\" ".(($class)?"class=\"".$class."\"":"")." ".$add." />"; 
    } 
    
    function password($name,$value='',$class='',$add='') {
		$workspace = Jem::getInstance();
		$err = $workspace->getFormError();
		$class = $class.( (in_array($name,$err))?( ($class)?" red":"red" ):"" );
	
        return "<input type=\"password\" name=\"".trim($name)."\" id=\"form-".trim($name)."\" value=\"".$value."\" ".(($class)?"class=\"".$class."\"":"")." ".$add." />"; 
    }

    function select($name,$opt_value,$selected='',$class='',$add='') {
		$workspace = Jem::getInstance();
		$err = $workspace->getFormError();
		$class = $class.( (in_array($name, $err))?( ($class)?" red":"red" ):"" );
	
        $select .= "<select name=\"".trim($name)."\" id=\"form-".trim($name)."\" ".(($class)?"class=\"".$class."\"":"")." ".$add.">"; 

        foreach($opt_value as $value => $text) { 
			$text = htmlentities($text);
            if($value == $selected) { 
                $select .= "<option selected=\"selected\" value=\"".$value."\">".$text."</option>"; 
            }else{ 
                $select .= "<option value=\"".$value."\">".$text."</option>"; 
            } 
        } 
        $select .= "</select>"; 
        return $select; 
    } 

    function multiselect($name,$opt_value,$selected='',$class='',$add='') {
		$workspace = Jem::getInstance();
		$err = $workspace->getFormError();
		$class = $class.( (in_array($name,$err))?( ($class)?" red":"red" ):"" );
	
        $select .= "<select multiple=\"multiple\"  name=\"".trim($name)."[]\" id=\"form-".trim($name)."\" ".(($class)?"class=\"".$class."\"":"")." ".$add.">"; 
		if(!$selected){
			$selected = array();
		}
        foreach($opt_value as $value => $text) { 
			$text = htmlentities($text);
            if(in_array($value, $selected)) { 
                $select .= "<option selected=\"selected\" value=\"".$value."\">".$text."</option>"; 
            }else{ 
                $select .= "<option value=\"".$value."\">".$text."</option>"; 
            } 
        } 
        $select .= "</select>"; 
        return $select; 
    } 
    
    function selectdate($name,$limit,$selected='',$class='',$add='',$label='||||',$y="yyyy",$m="mm",$d="dd") {
		$workspace = Jem::getInstance();
		$err = $workspace->getFormError();
		$class = $class.( (in_array($name,$err))?( ($class)?" red":"red" ):"" );
	
    	$name=explode("||",$name);
    	$limit=explode("||",$limit);
    	$label=explode("||",$label);
    	
    	$data=explode("-",$selected);
    	$selin[$y]=$data[0];
    	$selin[$m]=$data[1];
    	$selin[$d]=$data[2];
    	
    	for($i=0;$i<count($name);$i++){
    		$n=$name[$i];
    		$lm=$limit[$i];
    		$sel=$selin[$n];
    		$lab=$label[$i];
    		
			$select .= "<select name=\"".$n."\" id=\"form-".$n."\" ".(($class)?"class=\"".$class."\"":"")." ".$add.">"; 
			if($lab!="") $select .= "<option value=\"\">".$lab."</option>"; 
    		$lm=explode(",",$lm);
    		if($lm[0]>$lm[1]){
				for($z=$lm[0];$z>=$lm[1];$z--) { 
					if($z == $sel) { 
						$select .= "<option selected=\"selected\" value=\"".$z."\">".$z."</option>"; 
					}else{ 
						$select .= "<option value=\"".$z."\">".$z."</option>"; 
					} 
				} 
    		
    		}else{
				for($z=$lm[0];$z<=$lm[1];$z++) { 
					if($z == $sel) { 
						$select .= "<option selected=\"selected\" value=\"".$z."\">".$z."</option>"; 
					}else{ 
						$select .= "<option value=\"".$z."\">".$z."</option>"; 
					} 
				} 
			}
			$select .= "</select>"; 
        }
        return $select; 
    } 

    function checkbox($name,$value='1',$checked='',$class='',$add='',$suffisso='1') {
		$workspace = Jem::getInstance();
		$err = $workspace->getFormError();
		$class = $class.( (in_array($name,$err))?( ($class)?" red":"red" ):"" );
	
        if($checked == $value) $addchecked = "checked=\"checked\""; 
        return "<input type=\"checkbox\" name=\"".trim($name)."\" id=\"form-".trim($name)."-".$suffisso."\" ".$addchecked." value=\"".$value."\" ".(($class)?"class=\"".$class."\"":"")." ".$add." />"; 
    } 

    function radio($name,$value='1',$checked='',$class='',$add='',$suffisso='1') {
		$workspace = Jem::getInstance();
		$err = $workspace->getFormError();
		$class = $class.( (in_array($name,$err))?( ($class)?" red":"red" ):"" );
	
        if($checked==$value) $addchecked = "checked=\"checked\""; 
        return "<input type=\"radio\" name=\"".trim($name)."\" id=\"form-".trim($name)."-".$suffisso."\" ".$addchecked." value=\"".$value."\" ".(($class)?"class=\"".$class."\"":"")." ".$add." />"; 
    } 

    function textarea($name,$rows,$cols,$value='',$class='',$add='') {
		$workspace = Jem::getInstance();
		$err = $workspace->getFormError();
		$class = $class.( (in_array($name,$err))?( ($class)?" red":"red" ):"" );
		if($class == "editor-full") $value = str_replace("&quot;","",$value);
	
        return "<textarea name=\"".trim($name)."\" id=\"form-".trim($name)."\" rows=\"".$rows."\" cols=\"".$cols."\" ".(($class)?"class=\"".$class."\"":"")." ".$add.">".$value."</textarea>"; 
    } 

    function file($name, $class='',$add='') {
		$workspace = Jem::getInstance();
		$err = $workspace->getFormError();
		$class = $class.( (in_array($name,$err))?( ($class)?" red":"red" ):"" );
	
        return "<input type=\"file\" name=\"".trim($name)."\" id=\"form-".trim($name)."\" ".(($class)?"class=\"".$class."\"":"")." ".$add." />"; 
    } 

    function hidden($name,$value='',$add='') {
		$workspace = Jem::getInstance();
		$err = $workspace->getFormError();
		$class = $class.( (in_array($name,$err))?( ($class)?" red":"red" ):"" );
	
        return "<input type=\"hidden\" name=\"".trim($name)."\" id=\"form-".trim($name)."\" value=\"".$value."\" ".(($class)?"class=\"".$class."\"":"")." ".$add." />"; 
    } 

    function button($value,$type='submit',$class='',$add='') { 
      	return "<button type=\"".$type."\" ".(($class)?"class=\"".$class."\"":"")." ".$add." >".$value."</button>"; 

    } 


}
?>
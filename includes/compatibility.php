<?php
if (!function_exists("array_diff_key")){
    function array_diff_key(){
        $arrs = func_get_args();
        $result = array_shift($arrs);
        foreach ($arrs as $array) {
            foreach ($result as $key => $v) {
                if (array_key_exists($key, $array)) {
                    unset($result[$key]);
                }
            }
        }
        return $result;
   }
}
if(!function_exists("imagerotate")) {
    function imagerotate($src_img, $angle, $bicubic=false) {
  
	   // convert degrees to radians
	   $angle = (-($angle)) + 180;
	   $angle = deg2rad($angle);
	  
	   $src_x = imagesx($src_img);
	   $src_y = imagesy($src_img);
	  
	   $center_x = floor($src_x/2);
	   $center_y = floor($src_y/2);
	
	   $cosangle = cos($angle);
	   $sinangle = sin($angle);
	
	   $corners=array(array(0,0), array($src_x,0), array($src_x,$src_y), array(0,$src_y));
	
	   foreach($corners as $key=>$value) {
	     $value[0]-=$center_x;        //Translate coords to center for rotation
	     $value[1]-=$center_y;
	     $temp=array();
	     $temp[0]=$value[0]*$cosangle+$value[1]*$sinangle;
	     $temp[1]=$value[1]*$cosangle-$value[0]*$sinangle;
	     $corners[$key]=$temp;    
	   }
	   
	   $min_x=1000000000000000;
	   $max_x=-1000000000000000;
	   $min_y=1000000000000000;
	   $max_y=-1000000000000000;
	   
	   foreach($corners as $key => $value) {
	     if($value[0]<$min_x)
	       $min_x=$value[0];
	     if($value[0]>$max_x)
	       $max_x=$value[0];
	   
	     if($value[1]<$min_y)
	       $min_y=$value[1];
	     if($value[1]>$max_y)
	       $max_y=$value[1];
	   }
	
	   $rotate_width=round($max_x-$min_x);
	   $rotate_height=round($max_y-$min_y);
	
	   $rotate=imagecreatetruecolor($rotate_width,$rotate_height);
	   imagealphablending($rotate, false);
	   imagesavealpha($rotate, true);
	
	   //Reset center to center of our image
	   $newcenter_x = ($rotate_width)/2;
	   $newcenter_y = ($rotate_height)/2;
	
	   for ($y = 0; $y < ($rotate_height); $y++) {
	     for ($x = 0; $x < ($rotate_width); $x++) {
	       // rotate...
	       $old_x = round((($newcenter_x-$x) * $cosangle + ($newcenter_y-$y) * $sinangle))
	         + $center_x;
	       $old_y = round((($newcenter_y-$y) * $cosangle - ($newcenter_x-$x) * $sinangle))
	         + $center_y;
	      
	       if ( $old_x >= 0 && $old_x < $src_x
	             && $old_y >= 0 && $old_y < $src_y ) {
	
	           $color = imagecolorat($src_img, $old_x, $old_y);
	       } else {
	         // this line sets the background colour
	         $color = imagecolorallocatealpha($src_img, 255, 255, 255, 127);
	       }
	       imagesetpixel($rotate, $x, $y, $color);
	     }
	   }
	   
	  return($rotate);
	}
}

if ( !function_exists('json_encode') ){
	function json_encode( $data ) {           
		if( is_array($data) || is_object($data) ) {
			$islist = is_array($data) && ( empty($data) || array_keys($data) === range(0,count($data)-1) );
		   
			if( $islist ) {
				$json = '[' . implode(',', array_map('__json_encode', $data) ) . ']';
			} else {
				$items = Array();
				foreach( $data as $key => $value ) {
					$items[] = __json_encode("$key") . ':' . __json_encode($value);
				}
				$json = '{' . implode(',', $items) . '}';
			}
		} elseif( is_string($data) ) {
			# Escape non-printable or Non-ASCII characters.
			# I also put the \\ character first, as suggested in comments on the 'addclashes' page.
			$string = '"' . addcslashes($data, "\\\"\n\r\t/" . chr(8) . chr(12)) . '"';
			$json    = '';
			$len    = strlen($string);
			# Convert UTF-8 to Hexadecimal Codepoints.
			for( $i = 0; $i < $len; $i++ ) {
			   
				$char = $string[$i];
				$c1 = ord($char);
			   
				# Single byte;
				if( $c1 <128 ) {
					$json .= ($c1 > 31) ? $char : sprintf("\\u%04x", $c1);
					continue;
				}
			   
				# Double byte
				$c2 = ord($string[++$i]);
				if ( ($c1 & 32) === 0 ) {
					$json .= sprintf("\\u%04x", ($c1 - 192) * 64 + $c2 - 128);
					continue;
				}
			   
				# Triple
				$c3 = ord($string[++$i]);
				if( ($c1 & 16) === 0 ) {
					$json .= sprintf("\\u%04x", (($c1 - 224) <<12) + (($c2 - 128) << 6) + ($c3 - 128));
					continue;
				}
				   
				# Quadruple
				$c4 = ord($string[++$i]);
				if( ($c1 & 8 ) === 0 ) {
					$u = (($c1 & 15) << 2) + (($c2>>4) & 3) - 1;
			   
					$w1 = (54<<10) + ($u<<6) + (($c2 & 15) << 2) + (($c3>>4) & 3);
					$w2 = (55<<10) + (($c3 & 15)<<6) + ($c4-128);
					$json .= sprintf("\\u%04x\\u%04x", $w1, $w2);
				}
			}
		} else {
			# int, floats, bools, null
			$json = strtolower(var_export( $data, true ));
		}
		return $json;
	} 
}
if ( !function_exists('json_decode') ){
	function json_decode($json){
		$comment = false;
		$out = '$x=';
	 
		for ($i=0; $i<strlen($json); $i++)
		{
			if (!$comment)
			{
				if (($json[$i] == '{') || ($json[$i] == '['))       $out .= ' array(';
				else if (($json[$i] == '}') || ($json[$i] == ']'))   $out .= ')';
				else if ($json[$i] == ':')    $out .= '=>';
				else                         $out .= $json[$i];         
			}
			else $out .= $json[$i];
			if ($json[$i] == '"' && $json[($i-1)]!="\\")    $comment = !$comment;
		}
		eval($out . ';');
		return $x;
	}
}  
        ?>